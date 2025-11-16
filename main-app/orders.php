<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once './back/dbConnection.php';

if (isset($_SESSION['user']['id']) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    logAction($pdo, $_SESSION['user']['id'], 'page_visit', 'Page: Cart (' . $_SERVER['REQUEST_URI'] . ')');
}

// Получение роли текущего пользователя
$user_id = $_SESSION['user']['id'];
$user_role = $_SESSION['user']['role'];
$query_role = "SELECT role FROM customer WHERE user_id = :user_id";
$stmt_role = $pdo->prepare($query_role);
$stmt_role->execute(['user_id' => $user_id]);
$user_role = $stmt_role->fetchColumn();

// Формирование запроса к базе данных в зависимости от роли пользователя
$fields = '*';

// Получение доступных статусов для текущей роли
$role_status = [
    "Cook" => ['ожидает готовки', 'в готовке', 'ожидает курьера', 'готов доставить', 'переданно курьеру', 'отмена'],
    "Courier" => ['ожидает курьера', 'готов доставить', 'переданно курьеру', 'доставляется', 'доставлен', 'возникла ошибка'],
    "Manager" => ['ожидает готовки', 'в готовке', 'ожидает курьера', 'готов доставить', 'переданно курьеру', 'доставляется', 'доставлен', 'возникла ошибка', 'отмена']
];

// Обработка формы изменения статуса заказа
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $order_id = $_POST['order_id'];
    $current_status = $_POST['current_status'];
    $courer_id = $_POST['courer_id'] ?? null;
    
    if ($user_role == 'Manager') {
        $new_status = $_POST['status'];
        $query_update_status = "UPDATE orders SET status = :status WHERE order_id = :order_id";
        $stmt_update_status = $pdo->prepare($query_update_status);
        $stmt_update_status->execute(['status' => $new_status, 'order_id' => $order_id]);

        logAction($pdo, $user_id, 'update_order_status', "Order ID: " . $order_id . ", Old: " . $current_status . ", New: " . $new_status . ", Role: " . $user_role);

    } else {
        $action = $_POST['action'];
        if ($action == 'cancel') {
            $new_status = 'отмена';
        } else if ($action == 'advance') {
            $role_statuses = $role_status[$user_role];
            $current_index = array_search($current_status, $role_statuses);
            $new_status = $role_statuses[$current_index + 1] ?? $current_status; // Если статусов больше нет, остается на текущем

            // Проверка для курьера, чтобы он мог изменять статус только своего заказа
            if ($user_role == 'Courier' && $current_status == 'ожидает курьера') {
                $query_update_status = "UPDATE orders SET status = :status, courer_id = :courer_id WHERE order_id = :order_id";
                $stmt_update_status = $pdo->prepare($query_update_status);
                $stmt_update_status->execute(['status' => $new_status, 'courer_id' => $user_id, 'order_id' => $order_id]);

                logAction($pdo, $user_id, 'update_order_status', "Order ID: " . $order_id . ", Old: " . $current_status . ", New: " . $new_status . ", Role: " . $user_role);

            } elseif ($user_role == 'Courier' && $courer_id != $user_id) {
                echo "Вы не можете изменять статус этого заказа.";
                exit();
            } else {
                $query_update_status = "UPDATE orders SET status = :status WHERE order_id = :order_id";
                $stmt_update_status = $pdo->prepare($query_update_status);
                $stmt_update_status->execute(['status' => $new_status, 'order_id' => $order_id]);

                logAction($pdo, $user_id, 'update_order_status', "Order ID: " . $order_id . ", Old: " . $current_status . ", New: " . $new_status . ", Role: " . $user_role);

            }
        }
    }
}

// Запрос к базе данных для получения данных о заказах
$query_orders = "SELECT $fields FROM orders";
$stmt_orders = $pdo->query($query_orders);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon_io/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon_io/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon_io/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon_io/site.webmanifest">
    <title>Суси • Панель заказов</title>
</head>
<body>
    <header>
        <div class="header-container">
            <nav class="header-nav">
                <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                    <a href="index.php">Меню</a>
                <?php } ?>
                <a href="/back/sign_up_and_login/logout.php">Выход</a>
            </nav>
        </div>
    </header>
    <main>
        <div class="container-orders">
            <div class="filter-sidebar">
                <form id="filter-form" class="filter-form">
                <label style="font-family: 'Stick', sans-serif;">Фильтр по статусу:</label>
                    <?php if ($user_role != 'Courier') { ?>
                        <label><input type="checkbox" name="status[]" value="в обработке"> в обработке</label>
                    <?php } ?>
                    <?php if ($user_role != 'Courier') { ?>
                        <label><input type="checkbox" name="status[]" value="ожидает готовки"> ожидает готовки</label>
                    <?php } ?>
                    <?php if ($user_role != 'Courier') { ?>
                        <label><input type="checkbox" name="status[]" value="в готовке"> в готовке</label>
                    <?php } ?>
                    <label><input type="checkbox" name="status[]" value="ожидает курьера"> ожидает курьера</label>
                    <label><input type="checkbox" name="status[]" value="переданно курьеру"> переданно курьеру</label>
                    <?php if ($user_role != 'Courier') { ?>
                        <label><input type="checkbox" name="status[]" value="отмена"> отмена</label>
                    <?php } ?>
                    <label><input type="checkbox" name="status[]" value="доставляется"> доставляется</label>
                    <label><input type="checkbox" name="status[]" value="доставлен"> доставлен</label>
                    <label><input type="checkbox" name="status[]" value="возникла ошибка"> возникла ошибка</label>
                    <button type="submit">Применить фильтр</button>
                    <button type="button" id="clear-filter">Очистить фильтр</button>
                </form>
            </div>
            <div class="main-orders">
                <?php while ($row = $stmt_orders->fetch(PDO::FETCH_ASSOC)) {
                    // Проверка доступности заказа для текущей роли и статуса
                    if ($user_role == 'Cook' && !in_array($row['status'], ['ожидает готовки', 'в готовке', 'ожидает курьера', 'готов доставить'])) {
                        continue;
                    } elseif ($user_role == 'Courier') {
                        // Курьер видит только те заказы, которые назначены на него
                        if (!is_null($row['courer_id']) && $row['courer_id'] != $user_id) {
                            continue;
                        }
                        if (!in_array($row['status'], ['готов доставить', 'ожидает курьера', 'доставляется', 'возникла ошибка', 'переданно курьеру'])) {
                            continue;
                        }
                    } ?>

                    <div class="order-element" <?php echo 'data-status="' . $row['status'] . '"'; ?> >
                        <h2>Заказ No<span><?php echo htmlspecialchars($row['order_id']); ?></span></h2>
                        <h2>Статус: <span><?php echo htmlspecialchars($row['status']); ?></span> </h2>
                        <?php if (!is_null($row['courer_id'])) { ?>
                            <h2>Курьер: <span><?php echo htmlspecialchars($row['courer_id']); ?></span></h2>
                        <?php } ?>
                        <!-- Меню выбора для каждой роли -->
                        <?php if ($user_role == 'Manager') { ?>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="hidden" name="current_status" value="<?php echo $row['status']; ?>">
                                <select name="status">
                                    <?php foreach ($role_status['Manager'] as $status) { ?>
                                        <option value="<?php echo $status; ?>" <?php echo $row['status'] == $status ? 'selected' : ''; ?>><?php echo $status; ?></option>
                                    <?php } ?>
                                </select>
                                <button type="submit" class="rounded-pill btn btn-primary">Изменить статус</button>
                            </form>

                            <?php if ($row['status'] == 'доставлен' || $row['status'] == 'отмена') { ?>
                                <form method="post" action="back/CRUD/delete_order.php" class="delete-form">
                                    <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                    <button style="margin-top: 10px;" type="submit" class="rounded-pill btn btn-danger">Удалить заказ</button>
                                </form>
                            <?php } ?>
                            
                        <?php } ?>
                        <?php if ($user_role == 'Cook' || $user_role == 'Courier') { ?>
                            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" class="status-form">
                                <input type="hidden" name="order_id" value="<?php echo $row['order_id']; ?>">
                                <input type="hidden" name="current_status" value="<?php echo $row['status']; ?>">
                                <input type="hidden" name="courer_id" value="<?php echo $row['courer_id']; ?>">
                                <button type="submit" name="action" value="advance" class="rounded-pill btn btn-primary">Продвинуть статус</button>
                                <button type="submit" name="action" value="cancel" class="rounded-pill btn btn-danger">Отменить заказ</button>
                            </form>
                        <?php } ?>
                        <?php if ($user_role != 'Cook') { ?>
                            <h2>Адрес: <span><?php echo htmlspecialchars($row['address']); ?></span></h2>
                        <?php } ?>
                        <h2>Комментарий:<br>
                            <textarea disabled><?php echo htmlspecialchars($row['comment']); ?></textarea>
                        </h2>
                        <h2>Сумма: <span><?php echo htmlspecialchars($row['total_price']); ?> руб.</span></h2>
                        <details>
                            <summary>Состав заказа</summary>
                            <h2>
                                <?php
                                $array = unserialize($row['item_list']);
                                foreach ($array as $key => $item) {
                                    echo $item['name'] . ' — ' . $item['quantity'] . '<br>';
                                }
                                ?>
                            </h2>
                        </details>
                    </div>
                <?php } ?>


            </div>
                <?php if ($_SESSION['user']['role'] == "Manager") { ?>
                    <div class="manager-summary">
                        <div class="manager-summary__header">
                            <h2 class="manager-summary__title">Сводка по логам (заказы)</h2>
                            <div class="manager-summary__controls">
                            <input id="ms-filter-email" class="ms-input" type="text" placeholder="Фильтр по email">
                            <input id="ms-filter-from"  class="ms-input" type="datetime-local" title="С даты/времени">
                            <input id="ms-filter-to"    class="ms-input" type="datetime-local" title="По дату/время">
                            <button id="ms-filter-apply" class="ms-btn">Фильтровать</button>
                            <button id="ms-filter-clear" class="ms-btn ms-btn--secondary">Сброс</button>
                            <button id="ms-export-csv"   class="ms-btn ms-btn--secondary">Копировать CSV</button>
                            </div>
                        </div>

                        <?php
                            $sql = <<<SQL
                            WITH
                            order_events AS (
                                SELECT ul.user_id,
                                    (regexp_match(ul.details, 'Order ID:\\s*([0-9]+)'))[1]::int AS order_id,
                                    ul.created_at AS order_ts
                                FROM public.user_logs ul
                                WHERE ul.action = 'create_order'
                            ),
                            raw_sessions AS (
                                SELECT user_id, action, created_at AS ts,
                                    lead(action) OVER (PARTITION BY user_id ORDER BY created_at) AS next_action,
                                    lead(created_at) OVER (PARTITION BY user_id ORDER BY created_at) AS next_ts
                                FROM public.user_logs
                                WHERE action IN ('login','logout')
                            ),
                            login_sessions AS (
                                SELECT user_id, ts AS login_ts,
                                    CASE WHEN next_action = 'logout' THEN next_ts END AS logout_ts
                                FROM raw_sessions
                                WHERE action = 'login'
                            ),
                            orders_in_sessions AS (
                                SELECT oe.user_id, oe.order_id, oe.order_ts, ls.login_ts, ls.logout_ts
                                FROM order_events oe
                                LEFT JOIN LATERAL (
                                SELECT ls.*
                                FROM login_sessions ls
                                WHERE ls.user_id = oe.user_id
                                    AND ls.login_ts <= oe.order_ts
                                    AND (ls.logout_ts IS NULL OR oe.order_ts <= ls.logout_ts)
                                ORDER BY ls.login_ts DESC
                                LIMIT 1
                                ) ls ON true
                            ),
                            cart_events AS (
                                SELECT ul.user_id,
                                    ul.created_at AS ts,
                                    CASE WHEN ul.action = 'add_to_cart' THEN 1
                                            WHEN ul.action = 'remove_from_cart' THEN -1
                                    END AS delta,
                                    (regexp_match(ul.details, 'Item ID:\\s*([0-9]+)'))[1]::int AS item_id
                                FROM public.user_logs ul
                                WHERE ul.action IN ('add_to_cart','remove_from_cart')
                            ),
                            cart_until_order AS (
                                SELECT ois.user_id, ois.order_id, ce.item_id, SUM(ce.delta) AS qty
                                FROM orders_in_sessions ois
                                JOIN cart_events ce
                                ON ce.user_id = ois.user_id
                                AND ce.ts >= COALESCE(ois.login_ts, timestamp 'epoch')
                                AND ce.ts <= ois.order_ts
                                GROUP BY ois.user_id, ois.order_id, ce.item_id
                            ),
                            cart_items_total AS (
                                SELECT user_id, order_id, SUM(GREATEST(qty,0)) AS cart_items
                                FROM cart_until_order
                                GROUP BY user_id, order_id
                            )
                            SELECT
                                ois.order_id,
                                c.user_id,
                                c.email,
                                EXTRACT(EPOCH FROM (ois.order_ts - ois.login_ts))::int AS time_to_order_seconds,
                                EXTRACT(EPOCH FROM (COALESCE(ois.logout_ts, ois.order_ts) - ois.login_ts))::int AS session_time_seconds,
                                COALESCE(cit.cart_items, 0) AS cart_items,
                                ois.order_ts
                            FROM orders_in_sessions ois
                            LEFT JOIN public.customer c ON c.user_id = ois.user_id
                            LEFT JOIN cart_items_total cit ON cit.user_id = ois.user_id AND cit.order_id = ois.order_id
                            ORDER BY ois.order_ts DESC
                            LIMIT 200
                            SQL;

                            $stmt = $pdo->query($sql);
                            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            function fmt_hms($seconds) {
                                $seconds = max(0, (int)$seconds);
                                $h = floor($seconds / 3600);
                                $m = floor(($seconds % 3600) / 60);
                                $s = $seconds % 60;
                                return sprintf('%02d:%02d:%02d', $h, $m, $s);
                            }
                        ?>

                        <div class="manager-summary__table-wrap">
                            <table class="ms-table" id="ms-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>User ID</th>
                                        <th>Email</th>
                                        <th>Время до заказа</th>
                                        <th>Время на сайте</th>
                                        <th>Товаров в корзине</th>
                                        <th>Время события</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        <?php foreach ($rows as $r) { ?>
                                            <tr
                                            data-email="<?= htmlspecialchars($r['email']) ?>"
                                            data-ts="<?= htmlspecialchars($r['order_ts']) ?>"
                                            >
                                            <td><?= htmlspecialchars($r['order_id']) ?></td>
                                            <td><?= htmlspecialchars($r['user_id']) ?></td>
                                            <td><?= htmlspecialchars($r['email']) ?></td>
                                            <td><span class="badge badge--time"><?= fmt_hms($r['time_to_order_seconds']) ?></span></td>
                                            <td><span class="badge badge--session"><?= fmt_hms($r['session_time_seconds']) ?></span></td>
                                            <td><span class="badge badge--cart"><?= htmlspecialchars($r['cart_items']) ?></span></td>
                                            <td><?= htmlspecialchars($r['order_ts']) ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                            </table>
                        </div>
                    </div>

                    <script>
                        (function(){
                        const emailInput = document.getElementById('ms-filter-email');
                        const fromInput  = document.getElementById('ms-filter-from');
                        const toInput    = document.getElementById('ms-filter-to');
                        const applyBtn   = document.getElementById('ms-filter-apply');
                        const clearBtn   = document.getElementById('ms-filter-clear');
                        const exportBtn  = document.getElementById('ms-export-csv');
                        const table      = document.getElementById('ms-table');
                        const rows       = Array.from(table.querySelectorAll('tbody tr'));

                        function normalizeTs(ts) {
                            // ts строка вида "2025-10-27 15:07:10.228781"
                            // для сравнения используем Date.parse с заменой пробела на 'T'
                            if(!ts) return NaN;
                            const iso = ts.replace(' ', 'T');
                            const d = Date.parse(iso);
                            return isNaN(d) ? NaN : d;
                        }

                        function filter() {
                            const emailQ = (emailInput.value || '').toLowerCase().trim();
                            const fromTs = fromInput.value ? Date.parse(fromInput.value) : NaN;
                            const toTs   = toInput.value   ? Date.parse(toInput.value)   : NaN;

                            rows.forEach(tr => {
                            const email = (tr.dataset.email || '').toLowerCase();
                            const tsStr = tr.dataset.ts || '';
                            const t     = normalizeTs(tsStr);

                            let pass = true;

                            if (emailQ && !email.includes(emailQ)) pass = false;
                            if (!isNaN(fromTs) && !isNaN(t) && t < fromTs) pass = false;
                            if (!isNaN(toTs)   && !isNaN(t) && t > toTs)   pass = false;

                            tr.style.display = pass ? '' : 'none';
                            });
                        }

                        function clearFilters() {
                            emailInput.value = '';
                            fromInput.value  = '';
                            toInput.value    = '';
                            filter();
                        }

                        function exportCSV() {
                            const visible = rows.filter(tr => tr.style.display !== 'none');
                            const header = ['order_id','user_id','email','time_to_order','session_time','cart_items','order_ts'];
                            const lines = [header.join(',')];

                            visible.forEach(tr => {
                            const tds = tr.querySelectorAll('td');
                            const csvRow = [
                                tds[0].innerText.trim(), // order_id
                                tds[1].innerText.trim(), // user_id
                                tds[2].innerText.trim(), // email
                                tds[3].innerText.trim(), // time_to_order
                                tds[4].innerText.trim(), // session_time
                                tds[5].innerText.trim(), // cart_items
                                tds[6].innerText.trim(), // order_ts
                            ].map(v => `"${v.replace(/"/g, '""')}"`);
                            lines.push(csvRow.join(','));
                            });

                            const txt = lines.join('\n');
                            navigator.clipboard.writeText(txt).then(() => {
                            exportBtn.textContent = 'Скопировано!';
                            setTimeout(() => exportBtn.textContent = 'Копировать CSV', 1200);
                            });
                        }

                        applyBtn.addEventListener('click', filter);
                        clearBtn.addEventListener('click', clearFilters);
                        exportBtn.addEventListener('click', exportCSV);

                        // По Enter в email — тоже фильтровать
                        emailInput.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); filter(); }});
                        })();
                    </script>
                <?php } ?>

        </div>
    </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('clear-filter').addEventListener('click', function() {
                var checkboxes = document.querySelectorAll('.filter-form input[type="checkbox"]');
                checkboxes.forEach(function(checkbox) {
                    checkbox.checked = false;
                });
                document.getElementById('filter-form').submit();
            });
        });
    </script>
</body>
</html>
