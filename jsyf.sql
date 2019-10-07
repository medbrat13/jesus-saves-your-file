--
-- PostgreSQL database dump
--

-- Dumped from database version 11.4 (Debian 11.4-1)
-- Dumped by pg_dump version 11.4 (Debian 11.4-1)

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

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: files; Type: TABLE; Schema: public;
--

CREATE TABLE public.files (
                              id integer NOT NULL,
                              name character varying(255) NOT NULL,
                              date timestamp with time zone DEFAULT now() NOT NULL,
                              size character varying(255) NOT NULL,
                              resolution character varying(255),
                              duration character varying(255),
                              path character varying(255) NOT NULL,
                              preview_path character varying(255),
                              ext character varying(255),
                              "user" integer NOT NULL
);


ALTER TABLE public.files OWNER TO batya;

--
-- Name: TABLE files; Type: COMMENT; Schema: public;
--

COMMENT ON TABLE public.files IS 'Список файлов';


--
-- Name: files_id_seq; Type: SEQUENCE; Schema: public;
--

ALTER TABLE public.files ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.files_id_seq
        START WITH 1
        INCREMENT BY 1
        NO MINVALUE
        NO MAXVALUE
        CACHE 1
    );


--
-- Name: files_user_seq; Type: SEQUENCE; Schema: public;
--

CREATE SEQUENCE public.files_user_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.files_user_seq OWNER TO batya;

--
-- Name: files_user_seq; Type: SEQUENCE OWNED BY; Schema: public;
--

ALTER SEQUENCE public.files_user_seq OWNED BY public.files."user";


--
-- Name: users; Type: TABLE; Schema: public;
--

CREATE TABLE public.users (
                              id integer NOT NULL,
                              name character varying(255) NOT NULL
);


ALTER TABLE public.users OWNER TO batya;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public;
--

ALTER TABLE public.users ALTER COLUMN id ADD GENERATED ALWAYS AS IDENTITY (
    SEQUENCE NAME public.users_id_seq
        START WITH 1
        INCREMENT BY 1
        NO MINVALUE
        NO MAXVALUE
        CACHE 1
    );


--
-- Name: files user; Type: DEFAULT; Schema: public;
--

ALTER TABLE ONLY public.files ALTER COLUMN "user" SET DEFAULT nextval('public.files_user_seq'::regclass);


--
-- Name: files_id_seq; Type: SEQUENCE SET; Schema: public;
--

SELECT pg_catalog.setval('public.files_id_seq', 78, true);


--
-- Name: files_user_seq; Type: SEQUENCE SET; Schema: public;
--

SELECT pg_catalog.setval('public.files_user_seq', 9, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public;
--

SELECT pg_catalog.setval('public.users_id_seq', 10, true);


--
-- Name: files id; Type: CONSTRAINT; Schema: public;
--

ALTER TABLE ONLY public.files
    ADD CONSTRAINT id PRIMARY KEY (id);


--
-- Name: files id_uniq; Type: CONSTRAINT; Schema: public;
--

ALTER TABLE ONLY public.files
    ADD CONSTRAINT id_uniq UNIQUE (id);


--
-- Name: users users_id_key; Type: CONSTRAINT; Schema: public;
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_id_key UNIQUE (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public;
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: files files_user_fkey; Type: FK CONSTRAINT; Schema: public;
--

ALTER TABLE ONLY public.files
    ADD CONSTRAINT files_user_fkey FOREIGN KEY ("user") REFERENCES public.users(id);


--
-- PostgreSQL database dump complete
--
