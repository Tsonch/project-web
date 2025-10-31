--
-- PostgreSQL database dump
--

-- Dumped from database version 15.2 (Debian 15.2-1.pgdg110+1)
-- Dumped by pg_dump version 16.3

-- Started on 2025-10-29 15:32:30

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 5 (class 2615 OID 2200)
-- Name: public; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

-- CREATE SCHEMA public;


ALTER SCHEMA public OWNER TO pg_database_owner;

--
-- TOC entry 3411 (class 0 OID 0)
-- Dependencies: 5
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA public IS 'standard public schema';


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- TOC entry 216 (class 1259 OID 24796)
-- Name: customer; Type: TABLE; Schema: public; Owner: group-5
--

CREATE TABLE public.customer (
    user_id integer NOT NULL,
    name character varying(50) NOT NULL,
    surname character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(100) NOT NULL,
    role character varying(30) NOT NULL
);


ALTER TABLE public.customer OWNER TO "group-5";

--
-- TOC entry 215 (class 1259 OID 24795)
-- Name: customer_user_id_seq; Type: SEQUENCE; Schema: public; Owner: group-5
--

CREATE SEQUENCE public.customer_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.customer_user_id_seq OWNER TO "group-5";

--
-- TOC entry 3412 (class 0 OID 0)
-- Dependencies: 215
-- Name: customer_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: group-5
--

ALTER SEQUENCE public.customer_user_id_seq OWNED BY public.customer.user_id;


-- Таблица page_stats (для статистики посещений в tracking-service)
CREATE TABLE IF NOT EXISTS public.page_stats (
    page VARCHAR(255) PRIMARY KEY,
    visits INTEGER DEFAULT 0
);
ALTER TABLE public.page_stats OWNER TO "group-5";

-- Для page_stats: Начальные записи (опционально)
-- INSERT INTO public.page_stats (page, visits) VALUES ('Main Menu (/index.php)', 0) ON CONFLICT DO NOTHING;
-- INSERT INTO public.page_stats (page, visits) VALUES ('Cart (/cart.php)', 0) ON CONFLICT DO NOTHING;
-- INSERT INTO public.page_stats (page, visits) VALUES ('Orders (/orders.php)', 0) ON CONFLICT DO NOTHING;

--
-- TOC entry 224 (class 1259 OID 24847)
-- Name: ingridient_item; Type: TABLE; Schema: public; Owner: group-5
--

CREATE TABLE public.ingridient_item (
    id_ingridient integer,
    id_item integer
);


ALTER TABLE public.ingridient_item OWNER TO "group-5";

--
-- TOC entry 223 (class 1259 OID 24841)
-- Name: ingridients; Type: TABLE; Schema: public; Owner: group-5
--

CREATE TABLE public.ingridients (
    id_ingridient integer NOT NULL,
    name character varying(50) NOT NULL
);


ALTER TABLE public.ingridients OWNER TO "group-5";

--
-- TOC entry 222 (class 1259 OID 24840)
-- Name: ingridients_id_ingridient_seq; Type: SEQUENCE; Schema: public; Owner: group-5
--

CREATE SEQUENCE public.ingridients_id_ingridient_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.ingridients_id_ingridient_seq OWNER TO "group-5";

--
-- TOC entry 3413 (class 0 OID 0)
-- Dependencies: 222
-- Name: ingridients_id_ingridient_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: group-5
--

ALTER SEQUENCE public.ingridients_id_ingridient_seq OWNED BY public.ingridients.id_ingridient;


--
-- TOC entry 220 (class 1259 OID 24812)
-- Name: items; Type: TABLE; Schema: public; Owner: group-5
--

CREATE TABLE public.items (
    item_id integer NOT NULL,
    name character varying(50) NOT NULL,
    img_path character varying(255) NOT NULL,
    price integer NOT NULL,
    description character varying(255),
    grams integer,
    category character varying(25)
);


ALTER TABLE public.items OWNER TO "group-5";

--
-- TOC entry 219 (class 1259 OID 24811)
-- Name: items_item_id_seq; Type: SEQUENCE; Schema: public; Owner: group-5
--

CREATE SEQUENCE public.items_item_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.items_item_id_seq OWNER TO "group-5";

--
-- TOC entry 3414 (class 0 OID 0)
-- Dependencies: 219
-- Name: items_item_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: group-5
--

ALTER SEQUENCE public.items_item_id_seq OWNED BY public.items.item_id;


--
-- TOC entry 218 (class 1259 OID 24803)
-- Name: orders; Type: TABLE; Schema: public; Owner: group-5
--

CREATE TABLE public.orders (
    order_id integer NOT NULL,
    user_id integer NOT NULL,
    courer_id integer,
    time_duration character varying(100) NOT NULL,
    status character varying(30) NOT NULL,
    item_list character varying(255) NOT NULL,
    total_price integer NOT NULL,
    address character varying(100) NOT NULL,
    comment character varying(255)
);


ALTER TABLE public.orders OWNER TO "group-5";

--
-- TOC entry 217 (class 1259 OID 24802)
-- Name: orders_order_id_seq; Type: SEQUENCE; Schema: public; Owner: group-5
--

CREATE SEQUENCE public.orders_order_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.orders_order_id_seq OWNER TO "group-5";

--
-- TOC entry 3415 (class 0 OID 0)
-- Dependencies: 217
-- Name: orders_order_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: group-5
--

ALTER SEQUENCE public.orders_order_id_seq OWNED BY public.orders.order_id;


--
-- TOC entry 226 (class 1259 OID 24898)
-- Name: rating; Type: TABLE; Schema: public; Owner: group-5
--

CREATE TABLE public.rating (
    id integer NOT NULL,
    courier_id integer NOT NULL,
    rating integer NOT NULL
);


ALTER TABLE public.rating OWNER TO "group-5";

--
-- TOC entry 225 (class 1259 OID 24897)
-- Name: rating_id_seq; Type: SEQUENCE; Schema: public; Owner: group-5
--

CREATE SEQUENCE public.rating_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.rating_id_seq OWNER TO "group-5";

--
-- TOC entry 3416 (class 0 OID 0)
-- Dependencies: 225
-- Name: rating_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: group-5
--

ALTER SEQUENCE public.rating_id_seq OWNED BY public.rating.id;


--
-- TOC entry 221 (class 1259 OID 24818)
-- Name: user_item; Type: TABLE; Schema: public; Owner: group-5
--

CREATE TABLE public.user_item (
    user_id integer NOT NULL,
    item_id integer NOT NULL
);


ALTER TABLE public.user_item OWNER TO "group-5";

--
-- TOC entry 228 (class 1259 OID 33067)
-- Name: user_logs; Type: TABLE; Schema: public; Owner: group-5
--

CREATE TABLE public.user_logs (
    log_id integer NOT NULL,
    user_id integer NOT NULL,
    action character varying(100) NOT NULL,
    details text,
    ip_address VARCHAR(45),
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE public.user_logs OWNER TO "group-5";
CREATE INDEX IF NOT EXISTS idx_user_logs_user_id ON public.user_logs(user_id);
CREATE INDEX IF NOT EXISTS idx_user_logs_created_at ON public.user_logs(created_at);

--
-- TOC entry 227 (class 1259 OID 33066)
-- Name: user_logs_log_id_seq; Type: SEQUENCE; Schema: public; Owner: group-5
--

CREATE SEQUENCE public.user_logs_log_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_logs_log_id_seq OWNER TO "group-5";

--
-- TOC entry 3417 (class 0 OID 0)
-- Dependencies: 227
-- Name: user_logs_log_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: group-5
--

ALTER SEQUENCE public.user_logs_log_id_seq OWNED BY public.user_logs.log_id;


--
-- TOC entry 3220 (class 2604 OID 24799)
-- Name: customer user_id; Type: DEFAULT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.customer ALTER COLUMN user_id SET DEFAULT nextval('public.customer_user_id_seq'::regclass);


--
-- TOC entry 3223 (class 2604 OID 24844)
-- Name: ingridients id_ingridient; Type: DEFAULT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.ingridients ALTER COLUMN id_ingridient SET DEFAULT nextval('public.ingridients_id_ingridient_seq'::regclass);


--
-- TOC entry 3222 (class 2604 OID 24815)
-- Name: items item_id; Type: DEFAULT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.items ALTER COLUMN item_id SET DEFAULT nextval('public.items_item_id_seq'::regclass);


--
-- TOC entry 3221 (class 2604 OID 24806)
-- Name: orders order_id; Type: DEFAULT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.orders ALTER COLUMN order_id SET DEFAULT nextval('public.orders_order_id_seq'::regclass);


--
-- TOC entry 3224 (class 2604 OID 24901)
-- Name: rating id; Type: DEFAULT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.rating ALTER COLUMN id SET DEFAULT nextval('public.rating_id_seq'::regclass);


--
-- TOC entry 3225 (class 2604 OID 33070)
-- Name: user_logs log_id; Type: DEFAULT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.user_logs ALTER COLUMN log_id SET DEFAULT nextval('public.user_logs_log_id_seq'::regclass);


--
-- TOC entry 3393 (class 0 OID 24796)
-- Dependencies: 216
-- Data for Name: customer; Type: TABLE DATA; Schema: public; Owner: group-5
--

COPY public.customer (user_id, name, surname, email, password, role) FROM stdin;
1	test	test	test@test.com	81dc9bdb52d04dc20036dbd8313ed055	User
2	Boris	Britva	test@mail.ru	202cb962ac59075b964b07152d234b70	Manager
3	Gordon	Ramzy	ramzi@mail.ru	202cb962ac59075b964b07152d234b70	Cook
4	Yandex	Boets	boets@mail.ru	202cb962ac59075b964b07152d234b70	Courier
8	testuser	testuser	testuser@mail.ru	202cb962ac59075b964b07152d234b70	User
9	Curier	Curie 2	curier@mail.ru	202cb962ac59075b964b07152d234b70	Courier
11	user	test	usertest@mail.ru	202cb962ac59075b964b07152d234b70	User
\.


--
-- TOC entry 3401 (class 0 OID 24847)
-- Dependencies: 224
-- Data for Name: ingridient_item; Type: TABLE DATA; Schema: public; Owner: group-5
--

COPY public.ingridient_item (id_ingridient, id_item) FROM stdin;
\.


--
-- TOC entry 3400 (class 0 OID 24841)
-- Dependencies: 223
-- Data for Name: ingridients; Type: TABLE DATA; Schema: public; Owner: group-5
--

COPY public.ingridients (id_ingridient, name) FROM stdin;
1	лаваш
2	помидоры
3	сыр
4	колбаса
5	огурцы
7	Сосиска
8	тесто
\.


--
-- TOC entry 3397 (class 0 OID 24812)
-- Dependencies: 220
-- Data for Name: items; Type: TABLE DATA; Schema: public; Owner: group-5
--

COPY public.items (item_id, name, img_path, price, description, grams, category) FROM stdin;
62	&lt;script&gt;alert(&quot;!&quot;)&lt;/script&gt;	img/z.jpg	123	&lt;script&gt;alert(&quot;!&quot;)&lt;/script&gt;	\N	\N
63	ЭБИ ТЕМПУРА	assets/img/ebi-tempura.png	482	Креветки, обжаренные в лёгком кляре. Подаются с соусом для темпуры (тенцую), натертым редисом дайкон и лимоном.	523	темпура
64	екнекн	assets/img/IMG_0359.jpg	456	апрапр	546	пкр
65	Гость	assets/img/Frame_4.png	346	ввапылаомстполеу	346	аааа
66	Гость	assets/img/wallpaperflare.com_wallpaper.jpg	66	епп	66	пкр
68	МИКС ТЕМПУРА	assets/img/mics-tempura.png	352	Это микс из темпур, только лучшее мясо и салат.	512	ТЕМПУРА
67	Темпура	assets/img/ebi-tempura.png	341	Креветки, обжаренные в лёгком кляре. Подаются с соусом для темпуры (тенцую), натертым редисом дайкон и лимоном.	241	ТЕМПУРА
69	Магуро Темпура	assets/img/maguro-tempura.png	612	Это темпура из маги	451	ТЕМПУРА
70	Мега Темпура	assets/img/header.png	351	Это мега темпура, тут ничего говорить....	523	ТЕМПУРА
71	Якитори ясай	assets/img/yakitory-yasai.png	351	Вкусные овощи на палочке	513	ЯКИТОРИ
73	Генби-Якитори	assets/img/yakitory-genbi.png	512	Это очень вкусное мясо на полочке	435	ЯКИТОРИ
74	Якитори-Еби	assets/img/yakitory-ebi.png	623	Кого еби...	923	ЯКИТОРИ
75	Унаги Якитори	assets/img/unagi-tempura.png	421	Картошка запеченная с сыром.	613	ЯКИТОРИ
76	Магуро Сукияки	assets/img/maguro-tempura.png	924	Какие суки...	736	СУКИЯКИ
77	Тофу-Сукияки	assets/img/sukiyaki-tofu.png	532	Крайне вкусный рис с мясом	313	СУКИЯКИ
78	Окономияки Ветчина-сэр	assets/img/okonomiyaki-vetchina-sir.png	522		613	ОКОНОМИЯКИ
79	Окономияки Морепродукты	assets/img/okonomiyaki-moreproducti.png	621		331	ОКОНОМИЯКИ
\.


--
-- TOC entry 3395 (class 0 OID 24803)
-- Dependencies: 218
-- Data for Name: orders; Type: TABLE DATA; Schema: public; Owner: group-5
--

COPY public.orders (order_id, user_id, courer_id, time_duration, status, item_list, total_price, address, comment) FROM stdin;
41	8	\N	11:52	ожидает курьера		0	45645645 456456 564	fdggdfgdfdfg
43	8	\N	Как можно скорее	В обработке	МИКС ТЕМПУРА x1, Якитори ясай x1, Унаги Якитори x1, Магуро Сукияки x1, 	2048	test_street test_house test_appartment	Test comments
42	8	\N	Как можно скорее	ожидает курьера	Якитори x1, 	123	test_streat Test_house test_kvartira	Test_comment
40	1	4	13:45	готов доставить	shaurmeow, 	1000	asdas asdas adsas	asdsa
\.


--
-- TOC entry 3403 (class 0 OID 24898)
-- Dependencies: 226
-- Data for Name: rating; Type: TABLE DATA; Schema: public; Owner: group-5
--

COPY public.rating (id, courier_id, rating) FROM stdin;
\.


--
-- TOC entry 3398 (class 0 OID 24818)
-- Dependencies: 221
-- Data for Name: user_item; Type: TABLE DATA; Schema: public; Owner: group-5
--

COPY public.user_item (user_id, item_id) FROM stdin;
\.


--
-- TOC entry 3405 (class 0 OID 33067)
-- Dependencies: 228
-- Data for Name: user_logs; Type: TABLE DATA; Schema: public; Owner: group-5
--

COPY public.user_logs (log_id, user_id, action, details, created_at) FROM stdin;
1	2	login	Email: test@mail.ru	2025-10-27 13:10:51.506593
2	2	logout		2025-10-27 13:23:44.837038
3	8	login	Email: testuser@mail.ru	2025-10-27 13:24:42.844471
4	8	add_to_cart	Item ID: 67	2025-10-27 13:26:21.941995
5	8	create_order	Order ID: 42, Total: 123	2025-10-27 13:27:05.805505
6	8	logout	Email: 	2025-10-27 13:53:52.454855
7	8	login	Email: testuser@mail.ru	2025-10-27 14:35:00.779126
8	8	logout		2025-10-27 14:35:31.473182
9	2	login	Email: test@mail.ru	2025-10-27 14:35:57.067195
10	2	upload_item	Item ID: 68, Name: МИКС ТЕМПУРА	2025-10-27 14:38:18.7864
11	2	update_item	Item ID: 67, Name: Темпура	2025-10-27 14:42:34.473313
12	2	upload_item	Item ID: 69, Name: Магуро Темпура	2025-10-27 14:44:55.578051
13	2	upload_item	Item ID: 70, Name: Мега Темпура	2025-10-27 14:47:25.511096
14	2	upload_item	Item ID: 71, Name: Якитори yasai	2025-10-27 14:48:35.074763
15	2	update_item	Item ID: 71, Name: Якитори ясай	2025-10-27 14:49:10.283166
16	2	upload_item	Item ID: 72, Name: Якитори ясай	2025-10-27 14:49:33.44946
17	2	delete_item	Item ID: 72	2025-10-27 14:49:41.327964
18	2	upload_item	Item ID: 73, Name: Генби-Якитори	2025-10-27 14:50:19.255187
19	2	upload_item	Item ID: 74, Name: Якитори-Еби	2025-10-27 14:51:08.808301
20	2	upload_item	Item ID: 75, Name: Унаги Якитори	2025-10-27 14:52:25.673232
21	2	upload_item	Item ID: 76, Name: Магуро Сукияки	2025-10-27 14:53:10.488047
22	2	upload_item	Item ID: 77, Name: Тофу-Сукияки	2025-10-27 14:54:03.771378
23	2	upload_item	Item ID: 78, Name: Окономияки Ветчина-сэр	2025-10-27 14:54:46.206069
24	2	upload_item	Item ID: 79, Name: Окономияки Морепродукты	2025-10-27 14:55:19.717445
25	2	update_order_status	Order ID: 41, Old: в готовке, New: ожидает курьера	2025-10-27 14:55:40.4523
26	2	update_order_status	Order ID: 42, Old: В обработке, New: в готовке, Role: Manager	2025-10-27 15:03:25.881456
27	2	logout		2025-10-27 15:04:30.685314
28	8	login	Email: testuser@mail.ru Role: User	2025-10-27 15:05:29.44918
29	8	add_to_cart	Item ID: 68	2025-10-27 15:05:44.249719
30	8	add_to_cart	Item ID: 70	2025-10-27 15:05:46.749506
31	8	add_to_cart	Item ID: 71	2025-10-27 15:05:48.919713
32	8	add_to_cart	Item ID: 75	2025-10-27 15:05:51.759805
33	8	add_to_cart	Item ID: 76	2025-10-27 15:05:59.919712
34	8	remove_from_cart	Item ID: 70	2025-10-27 15:06:26.046777
35	8	create_order	Order ID: 43, Total: 2048test_street test_house test_appartment, Address:	2025-10-27 15:07:10.228781
36	8	page_visit	Page: Main Menu (/index.php)	2025-10-27 15:21:51.247163
37	8	page_visit	Page: Cart (/cart.php)	2025-10-27 15:21:59.159726
38	8	page_visit	Page: Main Menu (/index.php)	2025-10-27 15:22:05.520754
39	8	logout		2025-10-27 15:22:20.588412
40	2	login	Email: test@mail.ru Role: Manager	2025-10-27 15:22:35.005349
41	2	page_visit	Page: Main Menu (/index.php)	2025-10-27 15:22:35.403998
42	2	page_visit	Page: Cart (/orders.php)	2025-10-27 15:22:44.495969
43	2	logout		2025-10-27 15:22:50.76862
44	2	login	Email: test@mail.ru Role: Manager	2025-10-28 11:13:25.462185
45	2	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:13:25.882197
46	2	page_visit	Page: Cart (/orders.php)	2025-10-28 11:14:00.872742
47	2	page_visit	Page: Cart (/orders.php)	2025-10-28 11:14:34.657389
48	2	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:14:49.604799
49	2	logout		2025-10-28 11:14:54.791669
50	8	login	Email: testuser@mail.ru Role: User	2025-10-28 11:15:08.285685
51	8	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:15:08.68614
52	8	page_visit	Page: Cart (/cart.php)	2025-10-28 11:15:32.273463
53	8	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:15:48.331607
54	8	page_visit	Page: Cart (/cart.php)	2025-10-28 11:19:26.397548
55	8	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:20:00.3532
56	8	logout		2025-10-28 11:21:01.489046
57	8	login	Email: testuser@mail.ru Role: User	2025-10-28 11:21:36.051801
58	8	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:21:36.438279
59	8	logout		2025-10-28 11:21:38.564296
60	2	login	Email: test@mail.ru Role: Manager	2025-10-28 11:21:46.271392
61	2	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:21:46.665183
62	2	logout		2025-10-28 11:21:49.838877
63	3	login	Email: ramzi@mail.ru Role: Cook	2025-10-28 11:22:28.108971
64	3	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:22:28.492197
65	3	page_visit	Page: Cart (/orders.php)	2025-10-28 11:22:29.427646
66	3	logout		2025-10-28 11:23:11.637977
67	4	login	Email: boets@mail.ru Role: Courier	2025-10-28 11:23:15.306132
68	4	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:23:15.69265
69	4	page_visit	Page: Cart (/orders.php)	2025-10-28 11:23:16.623625
70	4	logout		2025-10-28 11:23:24.064134
71	11	signup	Email: usertest@mail.ru	2025-10-28 11:24:25.939432
72	3	login	Email: ramzi@mail.ru Role: Cook	2025-10-28 11:25:10.454402
73	3	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:25:10.830501
74	3	page_visit	Page: Cart (/orders.php)	2025-10-28 11:25:11.764035
75	3	update_order_status	Order ID: 42, Old: в готовке, New: ожидает курьера, Role: Cook	2025-10-28 11:25:18.484062
76	3	logout		2025-10-28 11:25:35.883364
77	4	login	Email: boets@mail.ru Role: Courier	2025-10-28 11:25:48.599246
78	4	page_visit	Page: Main Menu (/index.php)	2025-10-28 11:25:48.986469
79	4	page_visit	Page: Cart (/orders.php)	2025-10-28 11:25:49.933342
80	4	update_order_status	Order ID: 40, Old: ожидает курьера, New: готов доставить, Role: Courier	2025-10-28 11:25:57.999935
81	4	logout		2025-10-28 11:29:24.58354
\.


--
-- TOC entry 3418 (class 0 OID 0)
-- Dependencies: 215
-- Name: customer_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: group-5
--

SELECT pg_catalog.setval('public.customer_user_id_seq', 11, true);


--
-- TOC entry 3419 (class 0 OID 0)
-- Dependencies: 222
-- Name: ingridients_id_ingridient_seq; Type: SEQUENCE SET; Schema: public; Owner: group-5
--

SELECT pg_catalog.setval('public.ingridients_id_ingridient_seq', 8, true);


--
-- TOC entry 3420 (class 0 OID 0)
-- Dependencies: 219
-- Name: items_item_id_seq; Type: SEQUENCE SET; Schema: public; Owner: group-5
--

SELECT pg_catalog.setval('public.items_item_id_seq', 79, true);


--
-- TOC entry 3421 (class 0 OID 0)
-- Dependencies: 217
-- Name: orders_order_id_seq; Type: SEQUENCE SET; Schema: public; Owner: group-5
--

SELECT pg_catalog.setval('public.orders_order_id_seq', 43, true);


--
-- TOC entry 3422 (class 0 OID 0)
-- Dependencies: 225
-- Name: rating_id_seq; Type: SEQUENCE SET; Schema: public; Owner: group-5
--

SELECT pg_catalog.setval('public.rating_id_seq', 1, false);


--
-- TOC entry 3423 (class 0 OID 0)
-- Dependencies: 227
-- Name: user_logs_log_id_seq; Type: SEQUENCE SET; Schema: public; Owner: group-5
--

SELECT pg_catalog.setval('public.user_logs_log_id_seq', 81, true);


--
-- TOC entry 3228 (class 2606 OID 24801)
-- Name: customer customer_pkey; Type: CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.customer
    ADD CONSTRAINT customer_pkey PRIMARY KEY (user_id);


--
-- TOC entry 3236 (class 2606 OID 24846)
-- Name: ingridients ingridients_pkey; Type: CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.ingridients
    ADD CONSTRAINT ingridients_pkey PRIMARY KEY (id_ingridient);


--
-- TOC entry 3234 (class 2606 OID 24817)
-- Name: items items_pkey; Type: CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.items
    ADD CONSTRAINT items_pkey PRIMARY KEY (item_id);


--
-- TOC entry 3232 (class 2606 OID 24810)
-- Name: orders orders_pkey; Type: CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_pkey PRIMARY KEY (order_id);


--
-- TOC entry 3238 (class 2606 OID 24903)
-- Name: rating rating_pkey; Type: CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.rating
    ADD CONSTRAINT rating_pkey PRIMARY KEY (id);


--
-- TOC entry 3230 (class 2606 OID 24839)
-- Name: customer unique_email; Type: CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.customer
    ADD CONSTRAINT unique_email UNIQUE (email);


--
-- TOC entry 3242 (class 2606 OID 33075)
-- Name: user_logs user_logs_pkey; Type: CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.user_logs
    ADD CONSTRAINT user_logs_pkey PRIMARY KEY (log_id);


--
-- TOC entry 3239 (class 1259 OID 33082)
-- Name: idx_user_logs_created_at; Type: INDEX; Schema: public; Owner: group-5
--

CREATE INDEX idx_user_logs_created_at ON public.user_logs USING btree (created_at);


--
-- TOC entry 3240 (class 1259 OID 33081)
-- Name: idx_user_logs_user_id; Type: INDEX; Schema: public; Owner: group-5
--

CREATE INDEX idx_user_logs_user_id ON public.user_logs USING btree (user_id);


--
-- TOC entry 3246 (class 2606 OID 24860)
-- Name: ingridient_item ingridient_item_id_ingridient_fkey; Type: FK CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.ingridient_item
    ADD CONSTRAINT ingridient_item_id_ingridient_fkey FOREIGN KEY (id_ingridient) REFERENCES public.ingridients(id_ingridient);


--
-- TOC entry 3247 (class 2606 OID 24855)
-- Name: ingridient_item ingridient_item_id_item_fkey; Type: FK CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.ingridient_item
    ADD CONSTRAINT ingridient_item_id_item_fkey FOREIGN KEY (id_item) REFERENCES public.items(item_id);


--
-- TOC entry 3243 (class 2606 OID 24831)
-- Name: orders orders_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.orders
    ADD CONSTRAINT orders_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.customer(user_id);


--
-- TOC entry 3248 (class 2606 OID 24904)
-- Name: rating rating_courier_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.rating
    ADD CONSTRAINT rating_courier_id_fkey FOREIGN KEY (courier_id) REFERENCES public.customer(user_id);


--
-- TOC entry 3244 (class 2606 OID 24826)
-- Name: user_item user_item_item_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.user_item
    ADD CONSTRAINT user_item_item_id_fkey FOREIGN KEY (item_id) REFERENCES public.items(item_id);


--
-- TOC entry 3245 (class 2606 OID 24821)
-- Name: user_item user_item_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.user_item
    ADD CONSTRAINT user_item_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.customer(user_id);


--
-- TOC entry 3249 (class 2606 OID 33076)
-- Name: user_logs user_logs_user_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: group-5
--

ALTER TABLE ONLY public.user_logs
    ADD CONSTRAINT user_logs_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.customer(user_id) ON DELETE CASCADE;


-- Completed on 2025-10-29 15:32:34

--
-- PostgreSQL database dump complete
--