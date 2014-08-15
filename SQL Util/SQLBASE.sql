/* TABELAS BASE */

create table access_level (
    id int(11) not null auto_increment primary key,
    name varchar(64) not null unique
);

CREATE TABLE access_tool(
    id int(11) not null auto_increment primary key,
    code varchar(4) not null ,
    name varchar(64) not null ,
    description text,
    table_name varchar(128)
);

CREATE TABLE access (
    id int(11) not null auto_increment primary key,
    fkaccess_level int(11) not null,
    fkaccess_tool int(11) not null,

    CONSTRAINT UNIQUE_ACCESS UNIQUE(fkaccess_tool,fkaccess_level),
    CONSTRAINT FK_access_tool FOREIGN KEY(fkaccess_tool) references access_tool(id) ON DELETE CASCADE,
    CONSTRAINT FK_access_level FOREIGN KEY(fkaccess_level) references access_level(id) ON DELETE CASCADE
);

CREATE TABLE menul(
	id int(11) not null auto_increment primary key,
  mask varchar(32) not null,
  link varchar(32) not null,
  icon varchar(32),
  ordem int(2),
  fkaccess_tool int(11) not null,
  
  CONSTRAINT FK_menul_access_tool FOREIGN KEY ( fkaccess_tool ) REFERENCES access_tool( id ) ON DELETE CASCADE
);

CREATE TABLE user(
  id int( 11 ) not null auto_increment primary key ,
  type varchar(32) DEFAULT 'admin' ,
  login varchar(128) UNIQUE not null,
  password varchar( 128 ) ,
  last_access timestamp ,
  fkaccess_level int(11) not null ,
  img_user varchar(512),
  
  CONSTRAINT fk_level_usuario FOREIGN KEY ( fkaccess_level ) REFERENCES access_level( id ) ON DELETE NO ACTION
);



create table history (
    id int(11) not null auto_increment primary key,
    title varchar(512) not null,
    date_created timestamp default current_timestamp,
    sql_backup varchar(512)
);

-- insere nível
INSERT INTO access_level (name) VALUES ('root');

-- inserindo tools de sistema
INSERT INTO access_tool (id, name, code, table_name) VALUES (1,'historico','_his','history');
INSERT INTO access_tool (id, name, code, table_name) VALUES (2,'user','_use','user');
INSERT INTO access_tool (id, name, code, table_name) VALUES (3,'level','_lev','level');


-- access to root
INSERT INTO access (fkaccess_level, fkaccess_tool) VALUES (1, 1), (1, 2), (1, 3);


-- usuário admin
insert into user (login,password,fkaccess_level) values ('admin','$1$/zlHd98H$ZjByvg8uTH2bqfj/ciy2M0',1);


    /** FIM SQL BASE **/

    CREATE TABLE post_categoria(
        id int(11) not null primary key auto_increment,
        name varchar(256) not null,
        description text,
        data_criacao timestamp default current_timestamp

    );
    /** BLOG  **/
    CREATE TABLE post(
       id int(11) not null primary key auto_increment,
       titulo varchar(256) not null,
       subtitulo varchar(512) not null,
       chamada text not null,
       data_criacao timestamp default current_timestamp,
       texto text,
       tags varchar(256),
       publicado boolean default false,
       fkPost_categoria INT(11),

       CONSTRAINT fk_categoria_post FOREIGN KEY ( fkPost_categoria ) REFERENCES post_categoria( id ) ON DELETE set null
   );

    CREATE TABLE post_tag(
        id int(11) not null primary key auto_increment,
        titulo varchar(256) not null,
        description text,
        data_criacao timestamp default current_timestamp
    );


    oracle
    select 'drop table '||table_name||' cascade constraints;' from information_schema.tables where TABLE_SCHEMA = 'loop';
    mysql :
    SELECT CONCAT(' oie ',titulo, ' - ') FROM post;

--select 'drop sequence '||sequence_name||' ; ' from user_sequences;  ORACLE


--trigger ::
SELECT CONCAT('DELIMITER $$ 
	CREATE TRIGGER trig_historico_',table_name,' AFTER UPDATE ON ',table_name,
   ' FOR EACH ROW BEGIN
   INSERT INTO historico (operacao) VALUES ("REGISTRO EDITADO EM "',table_name,')
   END$$
   DELIMITER ;') from information_schema.tables where TABLE_SCHEMA='loop' and table_name <> 'historico';


--deletar trigger do proprio historico.

DELIMITER $$
CREATE TRIGGER antesDeUpdate_empregados 
BEFORE UPDATE ON empregados
FOR EACH ROW BEGIN
INSERT INTO empregados_auditoria
SET acao = 'update',
id_empregado = OLD.id_empregado,
sobrename = OLD.sobrename,
modificadoem = NOW(); END$$
DELIMITER ;



-- Check constraint - mySql
DELIMITER $$
CREATE TRIGGER test_before_insert BEFORE INSERT ON `Test`
FOR EACH ROW
BEGIN
IF CHAR_LENGTH( NEW.ID ) < 4 THEN
SIGNAL SQLSTATE '12345'
SET MESSAGE_TEXT := 'check constraint on Test.ID failed';
END IF;
END$$   
DELIMITER ;  