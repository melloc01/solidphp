CREATE TABLE categoria_post (

    id 		int(11) not null primary key auto_increment,
    nome 	varchar(128) not null unique

);

CREATE TABLE post (
    id 		int(11) not null primary key auto_increment,
    titulo 	varchar(512) not null,
    texto 	text not null,
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	fkCategoria varchar(128) not null,
    tags varchar(2048),
    
	CONSTRAINT fkCategoria_post FOREIGN KEY(fkCategoria) references categoria_post(nome)
);