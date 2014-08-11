<?php  
    $sql_base = 'create table '.$schema_name.'.access_level (
    id int(11) not null auto_increment primary key,
    name varchar(64) not null unique
);

CREATE TABLE '.$schema_name.'.access_tool(
    id int(11) not null auto_increment primary key,
    code varchar(4) not null ,
    name varchar(64) not null ,
    description text,
    table_name varchar(128)
);

CREATE TABLE '.$schema_name.'.access (
    id int(11) not null auto_increment primary key,
    fkaccess_level int(11) not null,
    fkaccess_tool int(11) not null,

    CONSTRAINT UNIQUE_ACCESS UNIQUE(fkaccess_tool,fkaccess_level),
    CONSTRAINT FK_access_tool FOREIGN KEY(fkaccess_tool) references access_tool(id) ON DELETE CASCADE,
    CONSTRAINT FK_access_level FOREIGN KEY(fkaccess_level) references access_level(id) ON DELETE CASCADE
);

create table '.$schema_name.'.album(
    id int(11) not null auto_increment primary key,
    name varchar(256),
    description text
);

create table '.$schema_name.'.foto_album(
    id int(11) not null auto_increment primary key,
    description text,
    fkalbum int(11),
    link varchar(256),

    CONSTRAINT fkalbum_fotos FOREIGN KEY(fkalbum) references album(id) ON DELETE CASCADE
);

CREATE TABLE '.$schema_name.'.menul(
	id int(11) not null auto_increment primary key,
  mask varchar(32) not null,
  link varchar(32) not null,
  icon varchar(32),
  ordem int(2),
  fkaccess_tool int(11) not null,
  
  CONSTRAINT FK_menul_access_tool FOREIGN KEY ( fkaccess_tool ) REFERENCES access_tool( id ) ON DELETE CASCADE
);

CREATE TABLE '.$schema_name.'.user(
  id int( 11 ) not null auto_increment primary key ,
  type varchar(32) DEFAULT "admin" ,
  login varchar(128) UNIQUE not null,
  password varchar( 128 ) ,
  last_access timestamp  DEFAULT NULL,
  fkaccess_level int(11) not null ,

  CONSTRAINT fk_level_usuario FOREIGN KEY ( fkaccess_level ) REFERENCES access_level( id ) ON DELETE NO ACTION
);



create table '.$schema_name.'.history (
    id int(11) not null auto_increment primary key,
    title varchar(512) not null,
    date_created timestamp default current_timestamp,
    sql_backup varchar(512)
);

-- insere nível
INSERT INTO '.$schema_name.'.access_level (name) VALUES ("root");

-- inserindo tools de sistema
INSERT INTO '.$schema_name.'.access_tool (id, name, code, table_name) VALUES (1,"historico","_his","history");
INSERT INTO '.$schema_name.'.access_tool (id, name, code, table_name) VALUES (2,"user","_use","user");
INSERT INTO '.$schema_name.'.access_tool (id, name, code, table_name) VALUES (3,"level","_lev","level");


-- access to root
INSERT INTO '.$schema_name.'.access (fkaccess_level, fkaccess_tool) VALUES (1, 1), (1, 2), (1, 3);


-- usuário admin
INSERT INTO '.$schema_name.'.user (login,password,fkaccess_level) values ("admin","$1$/zlHd98H$ZjByvg8uTH2bqfj/ciy2M0",1);
'; 
?>