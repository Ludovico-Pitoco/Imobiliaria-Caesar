create table if not exists apartamento(
	codapartamento int AUTO_INCREMENT primary key,
    nome varchar(80) not null,
    localizacao varchar(80) not null,
    tamanho varchar(10) not null,
    quartos int not null,
    banheiros int not null,
    estacionamento int not null,
    pet boolean not null,
    andar varchar(10) not null,
    preco varchar(80) not null
);
create table if not exists terreno(
	codterreno int AUTO_INCREMENT primary key,
    nome varchar(80) not null,
    localizacao varchar(80) not null,
    extensao varchar(10) not null,
    preco varchar(80) not null
);
create table if not exists casa(
	codapartamento int AUTO_INCREMENT primary key,
    nome varchar(80) not null,
    localizacao varchar(80) not null,
    tamanho varchar(10) not null,
    quartos int not null,
    banheiros int not null,
    estacionamento int not null,
    pet boolean not null,
    preco varchar(80) not null
);

create table if not exists conta(
	codconta int AUTO_INCREMENT primary key,
    usuario varchar(80) not null unique,
    email varchar(80) not null unique,
    senha varchar(80) not null,
    telefone varchar(80) not null unique
);