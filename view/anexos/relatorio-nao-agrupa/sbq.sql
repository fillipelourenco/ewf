-- File name: E:\sbq.sql
-- Created by DBConvert http://www.dbconvert.com


--
-- Table structure for table `questoes`
--

CREATE TABLE "boq"."questoes" (  "id_questao" SERIAL NOT NULL ,
  "enunciado" TEXT NOT NULL ,
  "observacao" TEXT NULL ,
  "duracao_estimada" INTEGER NOT NULL ,
  "nivel" INTEGER NOT NULL ,
  "tipo" INTEGER NOT NULL ,
  "resposta" TEXT NULL ,
  "imagem" BYTEA NULL ,
  PRIMARY KEY ("id_questao")
); 


--
-- Table structure for table `alternativas`
--

CREATE TABLE "boq"."alternativas" (  "id_alternativa" SERIAL NOT NULL ,
  "id_questao" INTEGER NOT NULL ,
  "alternativa" TEXT NULL ,
  "correta" BOOLEAN NOT NULL DEFAULT false,
  "imagem" BYTEA NULL ,
  PRIMARY KEY ("id_alternativa"),FOREIGN KEY ("id_questao") REFERENCES "boq"."questoes" ( "id_questao" ) ON UPDATE NO ACTION ON DELETE NO ACTION
); 
CREATE INDEX "alternativas_fk_alternativa_questao" ON "boq"."alternativas" ("id_questao");


--
-- Table structure for table `unidades`
--

CREATE TABLE "boq"."unidades" (  "id_unidade" SERIAL NOT NULL ,
  "nome" VARCHAR(45) NOT NULL ,
  PRIMARY KEY ("id_unidade")
); 


--
-- Table structure for table `avaliacoes`
--

CREATE TABLE "boq"."avaliacoes" (  "id_avaliacao" SERIAL NOT NULL ,
  "turma" TEXT NOT NULL ,
  "semestre" INTEGER NOT NULL ,
  "tipo" INTEGER NOT NULL ,
  "id_unidade" INTEGER NULL ,
  PRIMARY KEY ("id_avaliacao"),FOREIGN KEY ("id_unidade") REFERENCES "boq"."unidades" ( "id_unidade" ) ON UPDATE NO ACTION ON DELETE NO ACTION
); 
CREATE INDEX "avaliacoes_fk_avaliacao_unidade" ON "boq"."avaliacoes" ("id_unidade");


--
-- Table structure for table `avaliacoes_questoes`
--

CREATE TABLE "boq"."avaliacoes_questoes" (  "id_avaliacao_questao" SERIAL NOT NULL ,
  "id_avaliacao" INTEGER NOT NULL ,
  "id_questao" INTEGER NOT NULL ,
  "peso" DOUBLE PRECISION NOT NULL ,
  PRIMARY KEY ("id_avaliacao_questao"),FOREIGN KEY ("id_avaliacao") REFERENCES "boq"."avaliacoes" ( "id_avaliacao" ) ON UPDATE NO ACTION ON DELETE NO ACTION,FOREIGN KEY ("id_questao") REFERENCES "boq"."questoes" ( "id_questao" ) ON UPDATE NO ACTION ON DELETE NO ACTION
); 
CREATE INDEX "avaliacoes_questoes_fk_pq_questao" ON "boq"."avaliacoes_questoes" ("id_questao");
CREATE INDEX "avaliacoes_questoes_fk_pq_avaliacao" ON "boq"."avaliacoes_questoes" ("id_avaliacao");
CREATE INDEX "avaliacoes_questoes_fk_aq_questao" ON "boq"."avaliacoes_questoes" ("id_questao");


--
-- Table structure for table `listas`
--

CREATE TABLE "boq"."listas" (  "id_lista" SERIAL NOT NULL ,
  "descricao" TEXT NOT NULL ,
  PRIMARY KEY ("id_lista")
); 


--
-- Table structure for table `listas_questoes`
--

CREATE TABLE "boq"."listas_questoes" (  "id_lista_questao" SERIAL NOT NULL ,
  "id_lista" INTEGER NOT NULL ,
  "id_questao" INTEGER NOT NULL ,
  PRIMARY KEY ("id_lista_questao"),FOREIGN KEY ("id_lista") REFERENCES "boq"."listas" ( "id_lista" ) ON UPDATE NO ACTION ON DELETE NO ACTION,FOREIGN KEY ("id_questao") REFERENCES "boq"."questoes" ( "id_questao" ) ON UPDATE NO ACTION ON DELETE NO ACTION
); 
CREATE INDEX "listas_questoes_fk_lq_lista" ON "boq"."listas_questoes" ("id_lista");
CREATE INDEX "listas_questoes_fk_lq_questao" ON "boq"."listas_questoes" ("id_questao");


--
-- Table structure for table `afirmacoes`
--

CREATE TABLE "boq"."afirmacoes" (  "id_afirmacao" SERIAL NOT NULL ,
  "id_questao" INTEGER NOT NULL ,
  "afirmacao" TEXT NOT NULL ,
  "justificativa" TEXT NULL ,
  "verdadeiro" BOOLEAN NOT NULL ,
  "imagem" BYTEA NULL ,
  PRIMARY KEY ("id_afirmacao"),FOREIGN KEY ("id_questao") REFERENCES "boq"."questoes" ( "id_questao" ) ON UPDATE NO ACTION ON DELETE NO ACTION
); 
CREATE INDEX "afirmacoes_fk_afirmacao_questao" ON "boq"."afirmacoes" ("id_questao");


--
-- Table structure for table `assuntos`
--

CREATE TABLE "boq"."assuntos" (  "id_assunto" SERIAL NOT NULL ,
  "nome" VARCHAR(45) NOT NULL ,
  "id_unidade" INTEGER NOT NULL ,
  PRIMARY KEY ("id_assunto"),FOREIGN KEY ("id_unidade") REFERENCES "boq"."unidades" ( "id_unidade" ) ON UPDATE NO ACTION ON DELETE NO ACTION
); 
CREATE INDEX "assuntos_fk_assuntos_unidades" ON "boq"."assuntos" ("id_unidade");
CREATE INDEX "assuntos_fk_assunto_unidade" ON "boq"."assuntos" ("id_unidade");


--
-- Table structure for table `questoes_assuntos`
--

CREATE TABLE "boq"."questoes_assuntos" (  "id_questao_assunto" SERIAL NOT NULL ,
  "id_questao" INTEGER NOT NULL ,
  "id_assunto" INTEGER NOT NULL ,
  PRIMARY KEY ("id_questao_assunto"),FOREIGN KEY ("id_assunto") REFERENCES "boq"."assuntos" ( "id_assunto" ) ON UPDATE NO ACTION ON DELETE NO ACTION,FOREIGN KEY ("id_questao") REFERENCES "boq"."questoes" ( "id_questao" ) ON UPDATE NO ACTION ON DELETE NO ACTION
); 
CREATE INDEX "questoes_assuntos_fk_qa_questao" ON "boq"."questoes_assuntos" ("id_questao");
CREATE INDEX "questoes_assuntos_fk_qa_assunto" ON "boq"."questoes_assuntos" ("id_assunto");


--
-- Table structure for table `usuarios`
--

CREATE TABLE "boq"."usuarios" (  "id_usuario" SERIAL NOT NULL ,
  "nome" VARCHAR(45) NOT NULL ,
  "email" VARCHAR(45) NOT NULL ,
  "login" VARCHAR(45) NOT NULL ,
  "senha" VARCHAR(45) NOT NULL ,
  PRIMARY KEY ("id_usuario")
); 


--
-- Dumping data for table `questoes`
--

INSERT INTO "boq"."questoes" ("id_questao","enunciado","observacao","duracao_estimada","nivel","tipo","resposta","imagem") VALUES (1,'Faça um programa que exiba na tela a mensagem  "Olá Mundo"',' ',5,1,1,'todo',NULL);

INSERT INTO "boq"."questoes" ("id_questao","enunciado","observacao","duracao_estimada","nivel","tipo","resposta","imagem") VALUES (2,'Faça um programa que solicita que o usuário digite o seu nome e exiba mensagem "Olá" seguido do nome digitado pelo usuário',' ',5,1,1,'todo',NULL);

INSERT INTO "boq"."questoes" ("id_questao","enunciado","observacao","duracao_estimada","nivel","tipo","resposta","imagem") VALUES (3,'Faça um programa que solicita ao usuário um número real e exibe na tela a metade do número digitado',' ',5,1,1,'todo',NULL);

INSERT INTO "boq"."questoes" ("id_questao","enunciado","observacao","duracao_estimada","nivel","tipo","resposta","imagem") VALUES (4,'Para quê serve a instrução IF?',' ',5,1,2,'todo',NULL);

INSERT INTO "boq"."questoes" ("id_questao","enunciado","observacao","duracao_estimada","nivel","tipo","resposta","imagem") VALUES (5,'Qual a sintaxe correta da instrução IF?',' ',5,1,2,'todo',NULL);

INSERT INTO "boq"."questoes" ("id_questao","enunciado","observacao","duracao_estimada","nivel","tipo","resposta","imagem") VALUES (6,'Analise e assinale a alternativa que representam as afirmações corretas. Justifique as falsas.',' ',5,2,3,'todo',NULL);

SELECT setval("boq"."questoes_id_questao_seq", max("id_questao") ) FROM "boq"."questoes"; 


--
-- Dumping data for table `alternativas`
--

INSERT INTO "boq"."alternativas" ("id_alternativa","id_questao","alternativa","correta","imagem") VALUES (1,4,'Imprimir na tela','0',NULL);

INSERT INTO "boq"."alternativas" ("id_alternativa","id_questao","alternativa","correta","imagem") VALUES (2,4,'Repetir a execução de um código','0',NULL);

INSERT INTO "boq"."alternativas" ("id_alternativa","id_questao","alternativa","correta","imagem") VALUES (3,4,'Alterar a sequencia de execução de um código caso uma condição seja satisfeita','0',NULL);

INSERT INTO "boq"."alternativas" ("id_alternativa","id_questao","alternativa","correta","imagem") VALUES (4,4,'Garante que um código sempre será executado','0',NULL);

INSERT INTO "boq"."alternativas" ("id_alternativa","id_questao","alternativa","correta","imagem") VALUES (5,5,'if (1>2) : print("1 é maior do que 2")','0',NULL);

INSERT INTO "boq"."alternativas" ("id_alternativa","id_questao","alternativa","correta","imagem") VALUES (6,5,'if (5<3) : print("5 é maior do que 3")','0',NULL);

INSERT INTO "boq"."alternativas" ("id_alternativa","id_questao","alternativa","correta","imagem") VALUES (7,5,'(10==10) if: print("10 é igual a 10")','0',NULL);

INSERT INTO "boq"."alternativas" ("id_alternativa","id_questao","alternativa","correta","imagem") VALUES (8,5,'(if: 1<3) print("1 é menor que 3")','0',NULL);

SELECT setval("boq"."alternativas_id_alternativa_seq", max("id_alternativa") ) FROM "boq"."alternativas"; 


--
-- Dumping data for table `unidades`
--

INSERT INTO "boq"."unidades" ("id_unidade","nome") VALUES (1,'Unidade I');

INSERT INTO "boq"."unidades" ("id_unidade","nome") VALUES (2,'Unidade II');

INSERT INTO "boq"."unidades" ("id_unidade","nome") VALUES (3,'Unidade III');

SELECT setval("boq"."unidades_id_unidade_seq", max("id_unidade") ) FROM "boq"."unidades"; 


--
-- Dumping data for table `avaliacoes`
--

INSERT INTO "boq"."avaliacoes" ("id_avaliacao","turma","semestre","tipo","id_unidade") VALUES (1,'Prova 1',2011,1,1);

INSERT INTO "boq"."avaliacoes" ("id_avaliacao","turma","semestre","tipo","id_unidade") VALUES (2,'Prova 2',2011,1,1);

SELECT setval("boq"."avaliacoes_id_avaliacao_seq", max("id_avaliacao") ) FROM "boq"."avaliacoes"; 


--
-- Dumping data for table `avaliacoes_questoes`
--

INSERT INTO "boq"."avaliacoes_questoes" ("id_avaliacao_questao","id_avaliacao","id_questao","peso") VALUES (1,1,1,1);

INSERT INTO "avaliacoes_questoes" ("id_avaliacao_questao","id_avaliacao","id_questao","peso") VALUES (2,1,2,1);

INSERT INTO "boq"."avaliacoes_questoes" ("id_avaliacao_questao","id_avaliacao","id_questao","peso") VALUES (3,1,3,2);

INSERT INTO "boq"."avaliacoes_questoes" ("id_avaliacao_questao","id_avaliacao","id_questao","peso") VALUES (4,1,4,2);

INSERT INTO "boq"."avaliacoes_questoes" ("id_avaliacao_questao","id_avaliacao","id_questao","peso") VALUES (5,2,4,2);

INSERT INTO "boq"."avaliacoes_questoes" ("id_avaliacao_questao","id_avaliacao","id_questao","peso") VALUES (6,2,5,2);

INSERT INTO "boq"."avaliacoes_questoes" ("id_avaliacao_questao","id_avaliacao","id_questao","peso") VALUES (7,2,6,2);

SELECT setval("boq"."avaliacoes_questoes_id_avaliacao_questao_seq", max("id_avaliacao_questao") ) FROM "boq"."avaliacoes_questoes"; 


--
-- Dumping data for table `listas`
--

INSERT INTO "boq"."listas" ("id_lista","descricao") VALUES (1,'Lista Geral 1');

SELECT setval("boq"."listas_id_lista_seq", max("id_lista") ) FROM "boq"."listas"; 


--
-- Dumping data for table `listas_questoes`
--

INSERT INTO "boq"."listas_questoes" ("id_lista_questao","id_lista","id_questao") VALUES (1,1,1);

INSERT INTO "boq"."listas_questoes" ("id_lista_questao","id_lista","id_questao") VALUES (2,1,2);

INSERT INTO "boq"."listas_questoes" ("id_lista_questao","id_lista","id_questao") VALUES (3,1,3);

INSERT INTO "boq"."listas_questoes" ("id_lista_questao","id_lista","id_questao") VALUES (4,1,4);

INSERT INTO "boq"."listas_questoes" ("id_lista_questao","id_lista","id_questao") VALUES (5,1,5);

INSERT INTO "boq"."listas_questoes" ("id_lista_questao","id_lista","id_questao") VALUES (6,1,6);

SELECT setval('boq."listas_questoes_id_lista_questao_seq"', max("id_lista_questao") ) FROM "boq"."listas_questoes"; 


--
-- Dumping data for table `afirmacoes`
--

INSERT INTO "boq"."afirmacoes" ("id_afirmacao","id_questao","afirmacao","justificativa","verdadeiro","imagem") VALUES (1,6,'Um IF pode ter apenas um unico ELIF','todo','1',NULL);

INSERT INTO "boq"."afirmacoes" ("id_afirmacao","id_questao","afirmacao","justificativa","verdadeiro","imagem") VALUES (2,6,'Um IF pode ter apenas um único else','todo','1',NULL);

INSERT INTO "boq"."afirmacoes" ("id_afirmacao","id_questao","afirmacao","justificativa","verdadeiro","imagem") VALUES (3,6,'Todo IF precisa ter um ELSE','todo','1',NULL);

INSERT INTO "boq"."afirmacoes" ("id_afirmacao","id_questao","afirmacao","justificativa","verdadeiro","imagem") VALUES (4,6,'É possível ter um IF dentro de um bloco de código de outro IF, ELIF ou ELSE','todo','1',NULL);

SELECT setval('boq."afirmacoes_id_afirmacao_seq"', max("id_afirmacao") ) FROM "boq"."afirmacoes"; 


--
-- Dumping data for table `assuntos`
--

INSERT INTO "boq"."assuntos" ("id_assunto","nome","id_unidade") VALUES (1,'Algoritmos Sequenciais',1);

INSERT INTO "boq"."assuntos" ("id_assunto","nome","id_unidade") VALUES (2,'Desvio Condicional',1);

INSERT INTO "boq"."assuntos" ("id_assunto","nome","id_unidade") VALUES (3,'Laço de Repetição',2);

INSERT INTO "boq"."assuntos" ("id_assunto","nome","id_unidade") VALUES (4,'Vetores e Matrizes',3);

SELECT setval('boq."assuntos_id_assunto_seq"', max("id_assunto") ) FROM "boq"."assuntos"; 


--
-- Dumping data for table `usuarios`
--

INSERT INTO "boq"."usuarios" ("id_usuario","nome","email","login","senha") VALUES (1,'Fillipe Lourenço','fillipe.lourenco@dce.ufpb.br','fillipe','fillipe');

INSERT INTO "boq"."usuarios" ("id_usuario","nome","email","login","senha") VALUES (2,'Thiago Luna','thiago.luna@dce.ufpb.br','thiago','thiago');

INSERT INTO "boq"."usuarios" ("id_usuario","nome","email","login","senha") VALUES (3,'Vanessa Dantas','vanessa@dce.ufpb.br','vanessa','vanessa');

SELECT setval('boq."usuarios_id_usuario_seq"', max("id_usuario") ) FROM "usuarios"; 

