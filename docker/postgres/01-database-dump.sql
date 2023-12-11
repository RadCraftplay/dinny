--
-- PostgreSQL database dump
--

-- Dumped from database version 15.1 (Debian 15.1-1.pgdg110+1)
-- Dumped by pg_dump version 15.1

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
-- Name: public; Type: SCHEMA; Schema: -; Owner: pg_database_owner
--

CREATE SCHEMA IF NOT EXISTS public;


ALTER SCHEMA public OWNER TO pg_database_owner;

--
-- Name: SCHEMA public; Type: COMMENT; Schema: -; Owner: pg_database_owner
--

COMMENT ON SCHEMA public IS 'standard public schema';


--
-- Name: purge_old_views(); Type: FUNCTION; Schema: public; Owner: dinny
--

CREATE FUNCTION public.purge_old_views() RETURNS trigger
    LANGUAGE plpgsql
AS $$
begin
    delete from server_views where (now() - ((31)::double precision * '1 day'::interval)) > date_viewed;
    return new;
end;
$$;


ALTER FUNCTION public.purge_old_views() OWNER TO dinny;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: server_views; Type: TABLE; Schema: public; Owner: dinny
--

CREATE TABLE public.server_views (
                                     server_id uuid NOT NULL,
                                     date_viewed timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.server_views OWNER TO dinny;

--
-- Name: servers; Type: TABLE; Schema: public; Owner: dinny
--

CREATE TABLE public.servers (
                                submission_id uuid DEFAULT gen_random_uuid() NOT NULL,
                                submitter_id uuid NOT NULL,
                                title text NOT NULL,
                                service_type_id smallint DEFAULT 4 NOT NULL,
                                address text NOT NULL,
                                description text,
                                submission_date timestamp with time zone DEFAULT now() NOT NULL,
                                expiration_date timestamp with time zone DEFAULT (now() + ((7)::double precision * '1 day'::interval))
);


ALTER TABLE public.servers OWNER TO dinny;

--
-- Name: users; Type: TABLE; Schema: public; Owner: dinny
--

CREATE TABLE public.users (
                              user_id uuid DEFAULT gen_random_uuid() NOT NULL,
                              email text NOT NULL,
                              username text NOT NULL,
                              user_role_id smallint DEFAULT 1 NOT NULL,
                              credentials_id uuid NOT NULL
);


ALTER TABLE public.users OWNER TO dinny;

--
-- Name: popular_servers; Type: VIEW; Schema: public; Owner: dinny
--

CREATE VIEW public.popular_servers AS
SELECT v.server_id,
       s.title,
       u.username,
       count(v.date_viewed) AS views
FROM ((public.server_views v
    JOIN public.servers s ON ((s.submission_id = v.server_id)))
    JOIN public.users u ON ((s.submitter_id = u.user_id)))
GROUP BY v.server_id, s.title, u.username
ORDER BY (count(v.date_viewed)) DESC
LIMIT 10;


ALTER TABLE public.popular_servers OWNER TO dinny;

--
-- Name: saved_servers; Type: TABLE; Schema: public; Owner: dinny
--

CREATE TABLE public.saved_servers (
                                      user_id uuid NOT NULL,
                                      submission_id uuid NOT NULL,
                                      bookmarked_date timestamp with time zone DEFAULT now() NOT NULL
);


ALTER TABLE public.saved_servers OWNER TO dinny;

--
-- Name: service_types; Type: TABLE; Schema: public; Owner: dinny
--

CREATE TABLE public.service_types (
                                      service_type_id smallint NOT NULL,
                                      service_name text NOT NULL,
                                      service_image_name text NOT NULL
);


ALTER TABLE public.service_types OWNER TO dinny;

--
-- Name: service_types_service_type_id_seq; Type: SEQUENCE; Schema: public; Owner: dinny
--

CREATE SEQUENCE public.service_types_service_type_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.service_types_service_type_id_seq OWNER TO dinny;

--
-- Name: service_types_service_type_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dinny
--

ALTER SEQUENCE public.service_types_service_type_id_seq OWNED BY public.service_types.service_type_id;


--
-- Name: user_credentials; Type: TABLE; Schema: public; Owner: dinny
--

CREATE TABLE public.user_credentials (
                                         credentials_id uuid DEFAULT gen_random_uuid() NOT NULL,
                                         password_hash character varying(60) NOT NULL,
                                         password_salt character varying(7) NOT NULL
);


ALTER TABLE public.user_credentials OWNER TO dinny;

--
-- Name: user_roles; Type: TABLE; Schema: public; Owner: dinny
--

CREATE TABLE public.user_roles (
                                   role_id smallint NOT NULL,
                                   role_name character varying(20) NOT NULL
);


ALTER TABLE public.user_roles OWNER TO dinny;

--
-- Name: user_roles_role_id_seq; Type: SEQUENCE; Schema: public; Owner: dinny
--

CREATE SEQUENCE public.user_roles_role_id_seq
    AS smallint
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_roles_role_id_seq OWNER TO dinny;

--
-- Name: user_roles_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: dinny
--

ALTER SEQUENCE public.user_roles_role_id_seq OWNED BY public.user_roles.role_id;


--
-- Name: service_types service_type_id; Type: DEFAULT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.service_types ALTER COLUMN service_type_id SET DEFAULT nextval('public.service_types_service_type_id_seq'::regclass);


--
-- Name: user_roles role_id; Type: DEFAULT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.user_roles ALTER COLUMN role_id SET DEFAULT nextval('public.user_roles_role_id_seq'::regclass);


--
-- Data for Name: service_types; Type: TABLE DATA; Schema: public; Owner: dinny
--

INSERT INTO public.service_types VALUES (1, 'Discord', 'discord.svg');
INSERT INTO public.service_types VALUES (2, 'TeamSpeak', 'teamspeak.svg');
INSERT INTO public.service_types VALUES (3, 'Mumble', 'mumble.svg');
INSERT INTO public.service_types VALUES (4, 'Other', 'other.svg');


--
-- Data for Name: user_roles; Type: TABLE DATA; Schema: public; Owner: dinny
--

INSERT INTO public.user_roles VALUES (1, 'User');
INSERT INTO public.user_roles VALUES (2, 'Admin');


--
-- Name: service_types_service_type_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dinny
--

SELECT pg_catalog.setval('public.service_types_service_type_id_seq', 4, true);


--
-- Name: user_roles_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: dinny
--

SELECT pg_catalog.setval('public.user_roles_role_id_seq', 2, true);


--
-- Name: user_credentials credentials_pk; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.user_credentials
    ADD CONSTRAINT credentials_pk PRIMARY KEY (credentials_id);


--
-- Name: saved_servers saved_servers_pk; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.saved_servers
    ADD CONSTRAINT saved_servers_pk PRIMARY KEY (submission_id, user_id);


--
-- Name: servers servers_pk; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_pk PRIMARY KEY (submission_id);


--
-- Name: service_types service_types_name_unique; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.service_types
    ADD CONSTRAINT service_types_name_unique UNIQUE (service_name);


--
-- Name: service_types service_types_pk; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.service_types
    ADD CONSTRAINT service_types_pk PRIMARY KEY (service_type_id);


--
-- Name: user_roles user_roles_pk; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.user_roles
    ADD CONSTRAINT user_roles_pk PRIMARY KEY (role_id);


--
-- Name: users users_pk; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pk PRIMARY KEY (user_id);


--
-- Name: users users_pk2; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pk2 UNIQUE (email);


--
-- Name: users users_pk3; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pk3 UNIQUE (username);


--
-- Name: users users_pk4; Type: CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pk4 UNIQUE (credentials_id);


--
-- Name: server_views purge_old_views_tr; Type: TRIGGER; Schema: public; Owner: dinny
--

CREATE TRIGGER purge_old_views_tr AFTER INSERT ON public.server_views FOR EACH STATEMENT EXECUTE FUNCTION public.purge_old_views();


--
-- Name: saved_servers saved_servers_servers_submission_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.saved_servers
    ADD CONSTRAINT saved_servers_servers_submission_id_fk FOREIGN KEY (submission_id) REFERENCES public.servers(submission_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: saved_servers saved_servers_users_user_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.saved_servers
    ADD CONSTRAINT saved_servers_users_user_id_fk FOREIGN KEY (user_id) REFERENCES public.users(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: server_views server_views_servers_submission_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.server_views
    ADD CONSTRAINT server_views_servers_submission_id_fk FOREIGN KEY (server_id) REFERENCES public.servers(submission_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: servers servers_service_types_service_type_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_service_types_service_type_id_fk FOREIGN KEY (service_type_id) REFERENCES public.service_types(service_type_id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- Name: servers servers_users_submitter_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.servers
    ADD CONSTRAINT servers_users_submitter_id_fk FOREIGN KEY (submitter_id) REFERENCES public.users(user_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: users users_user_credentials_credentials_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_user_credentials_credentials_id_fk FOREIGN KEY (credentials_id) REFERENCES public.user_credentials(credentials_id) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- Name: users users_user_roles_role_id_fk; Type: FK CONSTRAINT; Schema: public; Owner: dinny
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_user_roles_role_id_fk FOREIGN KEY (user_role_id) REFERENCES public.user_roles(role_id) ON UPDATE CASCADE ON DELETE SET DEFAULT;


--
-- PostgreSQL database dump complete
--

