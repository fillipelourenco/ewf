--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: clientes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE clientes (
    id_cliente bigint NOT NULL,
    razao_social character varying(50),
    nome_curto character varying(20),
    responsavel character varying(25),
    id_empresa integer
);


ALTER TABLE public.clientes OWNER TO postgres;

--
-- Name: clientes_id_cliente_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE clientes_id_cliente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.clientes_id_cliente_seq OWNER TO postgres;

--
-- Name: clientes_id_cliente_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE clientes_id_cliente_seq OWNED BY clientes.id_cliente;


--
-- Name: clientes_id_cliente_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('clientes_id_cliente_seq', 0, true);


--
-- Name: comentarios; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE comentarios (
    id_comentario integer NOT NULL,
    comentario text,
    momento_comentario timestamp without time zone DEFAULT now(),
    id_tarefa integer,
    id_usuario integer
);


ALTER TABLE public.comentarios OWNER TO postgres;

--
-- Name: comentarios_id_comentario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE comentarios_id_comentario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.comentarios_id_comentario_seq OWNER TO postgres;

--
-- Name: comentarios_id_comentario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE comentarios_id_comentario_seq OWNED BY comentarios.id_comentario;


--
-- Name: comentarios_id_comentario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('comentarios_id_comentario_seq', 0, true);


--
-- Name: componentes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE componentes (
    id_componente integer NOT NULL,
    nome character varying(45),
    descricao text,
    id_projeto integer
);


ALTER TABLE public.componentes OWNER TO postgres;

--
-- Name: componentes_id_componente_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE componentes_id_componente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.componentes_id_componente_seq OWNER TO postgres;

--
-- Name: componentes_id_componente_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE componentes_id_componente_seq OWNED BY componentes.id_componente;


--
-- Name: componentes_id_componente_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('componentes_id_componente_seq', 0, true);


--
-- Name: empresas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE empresas (
    id_empresa integer NOT NULL,
    nome character varying(45),
    cidade character varying(60),
    uf character varying(2),
    site character varying(50),
    login character varying(45),
    sequencia_tarefa integer DEFAULT 0,
    sequencia_requisicao integer DEFAULT 0,
    sequencia_formulario integer DEFAULT 0
);


ALTER TABLE public.empresas OWNER TO postgres;

--
-- Name: empresas_id_empresa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE empresas_id_empresa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.empresas_id_empresa_seq OWNER TO postgres;

--
-- Name: empresas_id_empresa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE empresas_id_empresa_seq OWNED BY empresas.id_empresa;


--
-- Name: empresas_id_empresa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('empresas_id_empresa_seq', 0, true);


--
-- Name: formularios; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE formularios (
    id_formulario integer NOT NULL,
    chave character varying(20),
    descricao text,
    status boolean DEFAULT true,
    id_projeto integer,
    id_versao integer
);


ALTER TABLE public.formularios OWNER TO postgres;

--
-- Name: formularios_id_formulario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE formularios_id_formulario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.formularios_id_formulario_seq OWNER TO postgres;

--
-- Name: formularios_id_formulario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE formularios_id_formulario_seq OWNED BY formularios.id_formulario;


--
-- Name: formularios_id_formulario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('formularios_id_formulario_seq', 0, true);


--
-- Name: iteracoes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE iteracoes (
    id_iteracao integer NOT NULL,
    resposta text NOT NULL,
    momento_resposta timestamp without time zone DEFAULT now() NOT NULL,
    id_requisicao integer NOT NULL,
    id_usuario integer NOT NULL
);


ALTER TABLE public.iteracoes OWNER TO postgres;

--
-- Name: iteracoes_id_iteracao_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE iteracoes_id_iteracao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.iteracoes_id_iteracao_seq OWNER TO postgres;

--
-- Name: iteracoes_id_iteracao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE iteracoes_id_iteracao_seq OWNED BY iteracoes.id_iteracao;


--
-- Name: iteracoes_id_iteracao_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('iteracoes_id_iteracao_seq', 0, true);


--
-- Name: logs_requisicoes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE logs_requisicoes (
    id_log_requisicao integer NOT NULL,
    chave character varying(45),
    situacao_anterior integer DEFAULT 0,
    situacao_atual integer,
    momento_alteracao timestamp without time zone DEFAULT now(),
    id_usuario integer,
    id_empresa integer
);


ALTER TABLE public.logs_requisicoes OWNER TO postgres;

--
-- Name: logs_requisicoes_id_log_requisicao_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE logs_requisicoes_id_log_requisicao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.logs_requisicoes_id_log_requisicao_seq OWNER TO postgres;

--
-- Name: logs_requisicoes_id_log_requisicao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE logs_requisicoes_id_log_requisicao_seq OWNED BY logs_requisicoes.id_log_requisicao;


--
-- Name: logs_requisicoes_id_log_requisicao_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('logs_requisicoes_id_log_requisicao_seq', 0, true);


--
-- Name: logs_tarefas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE logs_tarefas (
    id_log_tarefa integer NOT NULL,
    chave character varying(45),
    situacao_anterior integer DEFAULT 0,
    situacao_atual integer,
    momento_alteracao timestamp without time zone DEFAULT now(),
    id_usuario integer,
    id_empresa integer
);


ALTER TABLE public.logs_tarefas OWNER TO postgres;

--
-- Name: logs_tarefas_id_log_tarefa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE logs_tarefas_id_log_tarefa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.logs_tarefas_id_log_tarefa_seq OWNER TO postgres;

--
-- Name: logs_tarefas_id_log_tarefa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE logs_tarefas_id_log_tarefa_seq OWNED BY logs_tarefas.id_log_tarefa;


--
-- Name: logs_tarefas_id_log_tarefa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('logs_tarefas_id_log_tarefa_seq', 0, true);


--
-- Name: notificacoes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE notificacoes (
    id_notificacao integer NOT NULL,
    titulo character varying(100),
    mensagem text,
    situacao boolean DEFAULT false NOT NULL,
    momento_envio timestamp without time zone DEFAULT now() NOT NULL,
    id_usuario_remetente integer,
    id_usuario_destinatario integer
);


ALTER TABLE public.notificacoes OWNER TO postgres;

--
-- Name: notificacoes_id_notificacao_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE notificacoes_id_notificacao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.notificacoes_id_notificacao_seq OWNER TO postgres;

--
-- Name: notificacoes_id_notificacao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE notificacoes_id_notificacao_seq OWNED BY notificacoes.id_notificacao;


--
-- Name: notificacoes_id_notificacao_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('notificacoes_id_notificacao_seq', 0, true);


--
-- Name: perguntas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE perguntas (
    id_pergunta integer NOT NULL,
    titulo text,
    tipo integer,
    id_componente integer,
    id_formulario integer
);


ALTER TABLE public.perguntas OWNER TO postgres;

--
-- Name: perguntas_id_pergunta_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE perguntas_id_pergunta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.perguntas_id_pergunta_seq OWNER TO postgres;

--
-- Name: perguntas_id_pergunta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE perguntas_id_pergunta_seq OWNED BY perguntas.id_pergunta;


--
-- Name: perguntas_id_pergunta_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('perguntas_id_pergunta_seq', 0, true);


--
-- Name: permissoes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE permissoes (
    id_permissao integer NOT NULL,
    id_usuario integer,
    id_projeto integer,
    integra boolean DEFAULT false
);


ALTER TABLE public.permissoes OWNER TO postgres;

--
-- Name: permissoes_id_permissao_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE permissoes_id_permissao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.permissoes_id_permissao_seq OWNER TO postgres;

--
-- Name: permissoes_id_permissao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE permissoes_id_permissao_seq OWNED BY permissoes.id_permissao;


--
-- Name: permissoes_id_permissao_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('permissoes_id_permissao_seq', 0, true);


--
-- Name: projetos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE projetos (
    id_projeto integer NOT NULL,
    nome character varying(45),
    descricao text,
    login character varying(45),
    inicio date,
    id_empresa integer
);


ALTER TABLE public.projetos OWNER TO postgres;

--
-- Name: projetos_id_projeto_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE projetos_id_projeto_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.projetos_id_projeto_seq OWNER TO postgres;

--
-- Name: projetos_id_projeto_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE projetos_id_projeto_seq OWNED BY projetos.id_projeto;


--
-- Name: projetos_id_projeto_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('projetos_id_projeto_seq', 0, true);


--
-- Name: requisicoes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE requisicoes (
    id_requisicao integer NOT NULL,
    chave character varying(20),
    tipo integer,
    situacao integer,
    titulo character varying(100),
    descricao text,
    prioridade integer DEFAULT 1::numeric,
    pasta character varying(100),
    momento_cadastro timestamp without time zone DEFAULT now() NOT NULL,
    momento_alteracao timestamp without time zone,
    id_projeto integer,
    id_componente integer,
    id_cliente integer,
    id_usuario_cadastro integer,
    id_usuario_alteracao integer,
    id_usuario_responsavel integer,
    id_usuario_solicitante integer
);


ALTER TABLE public.requisicoes OWNER TO postgres;

--
-- Name: requisicoes_id_requisicao_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE requisicoes_id_requisicao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.requisicoes_id_requisicao_seq OWNER TO postgres;

--
-- Name: requisicoes_id_requisicao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE requisicoes_id_requisicao_seq OWNED BY requisicoes.id_requisicao;


--
-- Name: requisicoes_id_requisicao_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('requisicoes_id_requisicao_seq', 0, true);


--
-- Name: respostas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE respostas (
    id_resposta integer NOT NULL,
    id_pergunta integer,
    id_usuario integer,
    resposta text
);


ALTER TABLE public.respostas OWNER TO postgres;

--
-- Name: respostas_id_resposta_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE respostas_id_resposta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.respostas_id_resposta_seq OWNER TO postgres;

--
-- Name: respostas_id_resposta_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE respostas_id_resposta_seq OWNED BY respostas.id_resposta;


--
-- Name: respostas_id_resposta_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('respostas_id_resposta_seq', 0, true);


--
-- Name: riscos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE riscos (
    id_risco integer NOT NULL,
    tipo character varying(20),
    probabilidade character varying(20),
    efeito character varying(20),
    risco text,
    estrategia text,
    id_projeto integer
);


ALTER TABLE public.riscos OWNER TO postgres;

--
-- Name: riscos_id_risco_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE riscos_id_risco_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.riscos_id_risco_seq OWNER TO postgres;

--
-- Name: riscos_id_risco_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE riscos_id_risco_seq OWNED BY riscos.id_risco;


--
-- Name: riscos_id_risco_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('riscos_id_risco_seq', 0, false);


--
-- Name: rotulos; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE rotulos (
    id_rotulo integer NOT NULL,
    nome character varying(45),
    id_empresa integer
);


ALTER TABLE public.rotulos OWNER TO postgres;

--
-- Name: rotulos_id_rotulo_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE rotulos_id_rotulo_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.rotulos_id_rotulo_seq OWNER TO postgres;

--
-- Name: rotulos_id_rotulo_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE rotulos_id_rotulo_seq OWNED BY rotulos.id_rotulo;


--
-- Name: rotulos_id_rotulo_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('rotulos_id_rotulo_seq', 0, true);


--
-- Name: tarefas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE tarefas (
    id_tarefa integer NOT NULL,
    chave character varying(45),
    situacao integer,
    titulo character varying(100),
    descricao text,
    prazo date,
    pasta character varying(100),
    estimativa character varying(45),
    dependencia character varying(45),
    pai character varying(64),
    momento_cadastro timestamp without time zone DEFAULT now() NOT NULL,
    id_rotulo integer,
    id_componente integer,
    id_usuario_requisitante integer,
    id_usuario_responsavel integer,
    id_usuario_cadastro integer,
    id_projeto integer,
    id_empresa integer
);


ALTER TABLE public.tarefas OWNER TO postgres;

--
-- Name: tarefas_id_tarefa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE tarefas_id_tarefa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.tarefas_id_tarefa_seq OWNER TO postgres;

--
-- Name: tarefas_id_tarefa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE tarefas_id_tarefa_seq OWNED BY tarefas.id_tarefa;


--
-- Name: tarefas_id_tarefa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('tarefas_id_tarefa_seq', 0, true);


--
-- Name: usuarios; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE usuarios (
    id_usuario integer NOT NULL,
    nome character varying(50),
    tipo integer,
    email character varying(60),
    login character varying(20),
    senha character varying(20),
    id_empresa integer,
    id_cliente integer,
    inicio boolean DEFAULT false
);


ALTER TABLE public.usuarios OWNER TO postgres;

--
-- Name: usuarios_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuarios_id_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.usuarios_id_usuario_seq OWNER TO postgres;

--
-- Name: usuarios_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuarios_id_usuario_seq OWNED BY usuarios.id_usuario;


--
-- Name: usuarios_id_usuario_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('usuarios_id_usuario_seq', 0, true);


--
-- Name: versoes; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE versoes (
    id_versao integer NOT NULL,
    master_version integer,
    great_version integer,
    average_version integer,
    small_version integer,
    descricao text,
    data_cadastro date DEFAULT now(),
    id_projeto integer
);


ALTER TABLE public.versoes OWNER TO postgres;

--
-- Name: versoes_id_versao_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE versoes_id_versao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.versoes_id_versao_seq OWNER TO postgres;

--
-- Name: versoes_id_versao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE versoes_id_versao_seq OWNED BY versoes.id_versao;


--
-- Name: versoes_id_versao_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('versoes_id_versao_seq', 0, true);


--
-- Name: versoes_tarefas; Type: TABLE; Schema: public; Owner: postgres; Tablespace: 
--

CREATE TABLE versoes_tarefas (
    id_versao_tarefa integer NOT NULL,
    id_versao integer NOT NULL,
    id_tarefa integer NOT NULL
);


ALTER TABLE public.versoes_tarefas OWNER TO postgres;

--
-- Name: versoes_tarefas_id_tarefa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE versoes_tarefas_id_tarefa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.versoes_tarefas_id_tarefa_seq OWNER TO postgres;

--
-- Name: versoes_tarefas_id_tarefa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE versoes_tarefas_id_tarefa_seq OWNED BY versoes_tarefas.id_tarefa;


--
-- Name: versoes_tarefas_id_tarefa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('versoes_tarefas_id_tarefa_seq', 0, false);


--
-- Name: versoes_tarefas_id_versao_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE versoes_tarefas_id_versao_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.versoes_tarefas_id_versao_seq OWNER TO postgres;

--
-- Name: versoes_tarefas_id_versao_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE versoes_tarefas_id_versao_seq OWNED BY versoes_tarefas.id_versao;


--
-- Name: versoes_tarefas_id_versao_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('versoes_tarefas_id_versao_seq', 0, false);


--
-- Name: versoes_tarefas_id_versao_tarefa_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE versoes_tarefas_id_versao_tarefa_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.versoes_tarefas_id_versao_tarefa_seq OWNER TO postgres;

--
-- Name: versoes_tarefas_id_versao_tarefa_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE versoes_tarefas_id_versao_tarefa_seq OWNED BY versoes_tarefas.id_versao_tarefa;


--
-- Name: versoes_tarefas_id_versao_tarefa_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('versoes_tarefas_id_versao_tarefa_seq', 0, false);


--
-- Name: id_cliente; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clientes ALTER COLUMN id_cliente SET DEFAULT nextval('clientes_id_cliente_seq'::regclass);


--
-- Name: id_comentario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comentarios ALTER COLUMN id_comentario SET DEFAULT nextval('comentarios_id_comentario_seq'::regclass);


--
-- Name: id_componente; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY componentes ALTER COLUMN id_componente SET DEFAULT nextval('componentes_id_componente_seq'::regclass);


--
-- Name: id_empresa; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY empresas ALTER COLUMN id_empresa SET DEFAULT nextval('empresas_id_empresa_seq'::regclass);


--
-- Name: id_formulario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY formularios ALTER COLUMN id_formulario SET DEFAULT nextval('formularios_id_formulario_seq'::regclass);


--
-- Name: id_iteracao; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY iteracoes ALTER COLUMN id_iteracao SET DEFAULT nextval('iteracoes_id_iteracao_seq'::regclass);


--
-- Name: id_log_requisicao; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY logs_requisicoes ALTER COLUMN id_log_requisicao SET DEFAULT nextval('logs_requisicoes_id_log_requisicao_seq'::regclass);


--
-- Name: id_log_tarefa; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY logs_tarefas ALTER COLUMN id_log_tarefa SET DEFAULT nextval('logs_tarefas_id_log_tarefa_seq'::regclass);


--
-- Name: id_notificacao; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY notificacoes ALTER COLUMN id_notificacao SET DEFAULT nextval('notificacoes_id_notificacao_seq'::regclass);


--
-- Name: id_pergunta; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY perguntas ALTER COLUMN id_pergunta SET DEFAULT nextval('perguntas_id_pergunta_seq'::regclass);


--
-- Name: id_permissao; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permissoes ALTER COLUMN id_permissao SET DEFAULT nextval('permissoes_id_permissao_seq'::regclass);


--
-- Name: id_projeto; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY projetos ALTER COLUMN id_projeto SET DEFAULT nextval('projetos_id_projeto_seq'::regclass);


--
-- Name: id_requisicao; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicoes ALTER COLUMN id_requisicao SET DEFAULT nextval('requisicoes_id_requisicao_seq'::regclass);


--
-- Name: id_resposta; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY respostas ALTER COLUMN id_resposta SET DEFAULT nextval('respostas_id_resposta_seq'::regclass);


--
-- Name: id_risco; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY riscos ALTER COLUMN id_risco SET DEFAULT nextval('riscos_id_risco_seq'::regclass);


--
-- Name: id_rotulo; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rotulos ALTER COLUMN id_rotulo SET DEFAULT nextval('rotulos_id_rotulo_seq'::regclass);


--
-- Name: id_tarefa; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tarefas ALTER COLUMN id_tarefa SET DEFAULT nextval('tarefas_id_tarefa_seq'::regclass);


--
-- Name: id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios ALTER COLUMN id_usuario SET DEFAULT nextval('usuarios_id_usuario_seq'::regclass);


--
-- Name: id_versao; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY versoes ALTER COLUMN id_versao SET DEFAULT nextval('versoes_id_versao_seq'::regclass);


--
-- Name: id_versao_tarefa; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY versoes_tarefas ALTER COLUMN id_versao_tarefa SET DEFAULT nextval('versoes_tarefas_id_versao_tarefa_seq'::regclass);


--
-- Name: id_versao; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY versoes_tarefas ALTER COLUMN id_versao SET DEFAULT nextval('versoes_tarefas_id_versao_seq'::regclass);


--
-- Name: id_tarefa; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY versoes_tarefas ALTER COLUMN id_tarefa SET DEFAULT nextval('versoes_tarefas_id_tarefa_seq'::regclass);


--
-- Name: clientes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY clientes
    ADD CONSTRAINT clientes_pkey PRIMARY KEY (id_cliente);


--
-- Name: comentarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY comentarios
    ADD CONSTRAINT comentarios_pkey PRIMARY KEY (id_comentario);


--
-- Name: componentes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY componentes
    ADD CONSTRAINT componentes_pkey PRIMARY KEY (id_componente);


--
-- Name: empresas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY empresas
    ADD CONSTRAINT empresas_pkey PRIMARY KEY (id_empresa);


--
-- Name: formularios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY formularios
    ADD CONSTRAINT formularios_pkey PRIMARY KEY (id_formulario);


--
-- Name: iteracoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY iteracoes
    ADD CONSTRAINT iteracoes_pkey PRIMARY KEY (id_iteracao);


--
-- Name: logs_requisicoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY logs_requisicoes
    ADD CONSTRAINT logs_requisicoes_pkey PRIMARY KEY (id_log_requisicao);


--
-- Name: logs_tarefas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY logs_tarefas
    ADD CONSTRAINT logs_tarefas_pkey PRIMARY KEY (id_log_tarefa);


--
-- Name: notificacoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY notificacoes
    ADD CONSTRAINT notificacoes_pkey PRIMARY KEY (id_notificacao);


--
-- Name: perguntas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY perguntas
    ADD CONSTRAINT perguntas_pkey PRIMARY KEY (id_pergunta);


--
-- Name: permissoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY permissoes
    ADD CONSTRAINT permissoes_pkey PRIMARY KEY (id_permissao);


--
-- Name: projetos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY projetos
    ADD CONSTRAINT projetos_pkey PRIMARY KEY (id_projeto);


--
-- Name: requisicoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY requisicoes
    ADD CONSTRAINT requisicoes_pkey PRIMARY KEY (id_requisicao);


--
-- Name: respostas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY respostas
    ADD CONSTRAINT respostas_pkey PRIMARY KEY (id_resposta);


--
-- Name: riscos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY riscos
    ADD CONSTRAINT riscos_pkey PRIMARY KEY (id_risco);


--
-- Name: rotulos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY rotulos
    ADD CONSTRAINT rotulos_pkey PRIMARY KEY (id_rotulo);


--
-- Name: tarefas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY tarefas
    ADD CONSTRAINT tarefas_pkey PRIMARY KEY (id_tarefa);


--
-- Name: usuarios_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_pkey PRIMARY KEY (id_usuario);


--
-- Name: versoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY versoes
    ADD CONSTRAINT versoes_pkey PRIMARY KEY (id_versao);


--
-- Name: versoes_tarefas_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY versoes_tarefas
    ADD CONSTRAINT versoes_tarefas_pkey PRIMARY KEY (id_versao_tarefa);


--
-- Name: comentarios_fk_tarefa; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX comentarios_fk_tarefa ON comentarios USING btree (id_tarefa);


--
-- Name: comentarios_fk_usuario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX comentarios_fk_usuario ON comentarios USING btree (id_usuario);


--
-- Name: componentes_fk_projetos; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX componentes_fk_projetos ON componentes USING btree (id_projeto);


--
-- Name: iteracao_fk_requisicao; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX iteracao_fk_requisicao ON iteracoes USING btree (id_requisicao);


--
-- Name: iteracao_fk_usuario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX iteracao_fk_usuario ON iteracoes USING btree (id_usuario);


--
-- Name: notificacoes_fk_destinatario; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX notificacoes_fk_destinatario ON notificacoes USING btree (id_usuario_destinatario);


--
-- Name: notificacoes_fk_remetente; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX notificacoes_fk_remetente ON notificacoes USING btree (id_usuario_remetente);


--
-- Name: projetos_fk_empresas; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX projetos_fk_empresas ON projetos USING btree (id_empresa);


--
-- Name: requisicao_fk_cadastro; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX requisicao_fk_cadastro ON requisicoes USING btree (id_usuario_cadastro);


--
-- Name: requisicao_fk_componente; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX requisicao_fk_componente ON requisicoes USING btree (id_componente);


--
-- Name: requisicao_fk_requisitante; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX requisicao_fk_requisitante ON requisicoes USING btree (id_usuario_alteracao);


--
-- Name: riscos_fk_projeto; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX riscos_fk_projeto ON riscos USING btree (id_projeto);


--
-- Name: rotulos_fk_empresas; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX rotulos_fk_empresas ON rotulos USING btree (id_empresa);


--
-- Name: tarefas_fk_cadastro; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX tarefas_fk_cadastro ON tarefas USING btree (id_usuario_cadastro);


--
-- Name: tarefas_fk_componente; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX tarefas_fk_componente ON tarefas USING btree (id_componente);


--
-- Name: tarefas_fk_projeto; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX tarefas_fk_projeto ON tarefas USING btree (id_projeto);


--
-- Name: tarefas_fk_requisitante; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX tarefas_fk_requisitante ON tarefas USING btree (id_usuario_requisitante);


--
-- Name: tarefas_fk_responsavel; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX tarefas_fk_responsavel ON tarefas USING btree (id_usuario_responsavel);


--
-- Name: tarefas_fk_rotulo; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX tarefas_fk_rotulo ON tarefas USING btree (id_rotulo);


--
-- Name: usuarios_fk_empresas; Type: INDEX; Schema: public; Owner: postgres; Tablespace: 
--

CREATE INDEX usuarios_fk_empresas ON usuarios USING btree (id_empresa);


--
-- Name: clientes_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY clientes
    ADD CONSTRAINT clientes_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES empresas(id_empresa);


--
-- Name: comentarios_id_tarefa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comentarios
    ADD CONSTRAINT comentarios_id_tarefa_fkey FOREIGN KEY (id_tarefa) REFERENCES tarefas(id_tarefa);


--
-- Name: comentarios_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY comentarios
    ADD CONSTRAINT comentarios_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- Name: componentes_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY componentes
    ADD CONSTRAINT componentes_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES projetos(id_projeto);


--
-- Name: formularios_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY formularios
    ADD CONSTRAINT formularios_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES projetos(id_projeto);


--
-- Name: formularios_id_versao_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY formularios
    ADD CONSTRAINT formularios_id_versao_fkey FOREIGN KEY (id_versao) REFERENCES versoes(id_versao);


--
-- Name: iteracoes_id_requisicao_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY iteracoes
    ADD CONSTRAINT iteracoes_id_requisicao_fkey FOREIGN KEY (id_requisicao) REFERENCES requisicoes(id_requisicao);


--
-- Name: iteracoes_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY iteracoes
    ADD CONSTRAINT iteracoes_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- Name: logs_requisicoes_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY logs_requisicoes
    ADD CONSTRAINT logs_requisicoes_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES empresas(id_empresa);


--
-- Name: logs_requisicoes_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY logs_requisicoes
    ADD CONSTRAINT logs_requisicoes_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- Name: logs_tarefas_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY logs_tarefas
    ADD CONSTRAINT logs_tarefas_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES empresas(id_empresa);


--
-- Name: logs_tarefas_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY logs_tarefas
    ADD CONSTRAINT logs_tarefas_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- Name: notificacoes_id_usuario_destinatario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY notificacoes
    ADD CONSTRAINT notificacoes_id_usuario_destinatario_fkey FOREIGN KEY (id_usuario_destinatario) REFERENCES usuarios(id_usuario);


--
-- Name: notificacoes_id_usuario_remetente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY notificacoes
    ADD CONSTRAINT notificacoes_id_usuario_remetente_fkey FOREIGN KEY (id_usuario_remetente) REFERENCES usuarios(id_usuario);


--
-- Name: perguntas_id_componente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY perguntas
    ADD CONSTRAINT perguntas_id_componente_fkey FOREIGN KEY (id_componente) REFERENCES componentes(id_componente);


--
-- Name: perguntas_id_formulario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY perguntas
    ADD CONSTRAINT perguntas_id_formulario_fkey FOREIGN KEY (id_formulario) REFERENCES formularios(id_formulario);


--
-- Name: permissoes_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permissoes
    ADD CONSTRAINT permissoes_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES projetos(id_projeto);


--
-- Name: permissoes_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY permissoes
    ADD CONSTRAINT permissoes_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- Name: projetos_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY projetos
    ADD CONSTRAINT projetos_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES empresas(id_empresa);


--
-- Name: requisicoes_id_cliente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicoes
    ADD CONSTRAINT requisicoes_id_cliente_fkey FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente);


--
-- Name: requisicoes_id_componente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicoes
    ADD CONSTRAINT requisicoes_id_componente_fkey FOREIGN KEY (id_componente) REFERENCES componentes(id_componente);


--
-- Name: requisicoes_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicoes
    ADD CONSTRAINT requisicoes_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES projetos(id_projeto);


--
-- Name: requisicoes_id_usuario_alteracao_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicoes
    ADD CONSTRAINT requisicoes_id_usuario_alteracao_fkey FOREIGN KEY (id_usuario_alteracao) REFERENCES usuarios(id_usuario);


--
-- Name: requisicoes_id_usuario_cadastro_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicoes
    ADD CONSTRAINT requisicoes_id_usuario_cadastro_fkey FOREIGN KEY (id_usuario_cadastro) REFERENCES usuarios(id_usuario);


--
-- Name: requisicoes_id_usuario_responsavel_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY requisicoes
    ADD CONSTRAINT requisicoes_id_usuario_responsavel_fkey FOREIGN KEY (id_usuario_responsavel) REFERENCES usuarios(id_usuario);


--
-- Name: respostas_id_pergunta_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY respostas
    ADD CONSTRAINT respostas_id_pergunta_fkey FOREIGN KEY (id_pergunta) REFERENCES perguntas(id_pergunta);


--
-- Name: respostas_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY respostas
    ADD CONSTRAINT respostas_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuarios(id_usuario);


--
-- Name: riscos_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY riscos
    ADD CONSTRAINT riscos_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES projetos(id_projeto);


--
-- Name: rotulos_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY rotulos
    ADD CONSTRAINT rotulos_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES empresas(id_empresa);


--
-- Name: tarefas_id_componente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tarefas
    ADD CONSTRAINT tarefas_id_componente_fkey FOREIGN KEY (id_componente) REFERENCES componentes(id_componente);


--
-- Name: tarefas_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tarefas
    ADD CONSTRAINT tarefas_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES empresas(id_empresa);


--
-- Name: tarefas_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tarefas
    ADD CONSTRAINT tarefas_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES projetos(id_projeto);


--
-- Name: tarefas_id_rotulo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tarefas
    ADD CONSTRAINT tarefas_id_rotulo_fkey FOREIGN KEY (id_rotulo) REFERENCES rotulos(id_rotulo);


--
-- Name: tarefas_id_usuario_cadastro_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tarefas
    ADD CONSTRAINT tarefas_id_usuario_cadastro_fkey FOREIGN KEY (id_usuario_cadastro) REFERENCES usuarios(id_usuario);


--
-- Name: tarefas_id_usuario_requisitante_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tarefas
    ADD CONSTRAINT tarefas_id_usuario_requisitante_fkey FOREIGN KEY (id_usuario_requisitante) REFERENCES usuarios(id_usuario);


--
-- Name: tarefas_id_usuario_responsavel_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY tarefas
    ADD CONSTRAINT tarefas_id_usuario_responsavel_fkey FOREIGN KEY (id_usuario_responsavel) REFERENCES usuarios(id_usuario);


--
-- Name: usuarios_id_cliente_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_id_cliente_fkey FOREIGN KEY (id_cliente) REFERENCES clientes(id_cliente);


--
-- Name: usuarios_id_empresa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuarios
    ADD CONSTRAINT usuarios_id_empresa_fkey FOREIGN KEY (id_empresa) REFERENCES empresas(id_empresa);


--
-- Name: versoes_id_projeto_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY versoes
    ADD CONSTRAINT versoes_id_projeto_fkey FOREIGN KEY (id_projeto) REFERENCES projetos(id_projeto);


--
-- Name: versoes_tarefas_id_tarefa_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY versoes_tarefas
    ADD CONSTRAINT versoes_tarefas_id_tarefa_fkey FOREIGN KEY (id_tarefa) REFERENCES tarefas(id_tarefa);


--
-- Name: versoes_tarefas_id_versao_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY versoes_tarefas
    ADD CONSTRAINT versoes_tarefas_id_versao_fkey FOREIGN KEY (id_versao) REFERENCES versoes(id_versao);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

