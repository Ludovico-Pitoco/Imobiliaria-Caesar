CREATE TABLE IF NOT EXISTS apartamento(
    codapartamento INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    descricao TEXT NOT NULL,
    localizacao VARCHAR(80) NOT NULL,
    tamanho VARCHAR(10) NOT NULL,
    quartos INT NOT NULL,
    banheiros INT NOT NULL,
    estacionamento INT NOT NULL,
    pet BOOLEAN NOT NULL,
    andar VARCHAR(10) NOT NULL,
    preco VARCHAR(80) NOT NULL,
    foto VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS terreno(
    codterreno INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    descricao TEXT NOT NULL,
    localizacao VARCHAR(80) NOT NULL,
    extensao VARCHAR(10) NOT NULL,
    preco VARCHAR(80) NOT NULL,
    foto VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS casa(
    codcasa INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(80) NOT NULL,
    descricao TEXT NOT NULL,
    localizacao VARCHAR(80) NOT NULL,
    tamanho VARCHAR(10) NOT NULL,
    quartos INT NOT NULL,
    banheiros INT NOT NULL,
    estacionamento INT NOT NULL,
    pet BOOLEAN NOT NULL,
    preco VARCHAR(80) NOT NULL,
    foto VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS conta(
    codconta INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(80) NOT NULL UNIQUE,
    email VARCHAR(80) NOT NULL UNIQUE,
    senha VARCHAR(80) NOT NULL,
    telefone VARCHAR(80) NOT NULL UNIQUE
);