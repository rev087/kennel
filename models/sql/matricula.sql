CREATE TABLE IF NOT EXISTS `matricula` (
	/* informações de usuário */
	id int(12) PRIMARY KEY AUTO_INCREMENT,
	allowaccess tinyint(1) default 0,
	login varchar(32),
	senha varchar(32),
	email varchar(128),
	allowmail tinyint(1) default 1,
	
	/* informações da matrícula */
	nome varchar(128),
	matricula varchar(128),
	nascimento date,
	cpf varchar(32),
	cep varchar(32),
	endereco varchar(128),
	cidade varchar(64),
	estado varchar(2),
	pai varchar(128),
	mae varchar(128),
	telefone varchar(32),
	profissao varchar(128),
	divulgacao varchar(128),
	indicador varchar(128)
);

INSERT INTO `matricula` (login, senha, nome, email, allowaccess)
VALUES ('user', 'user', 'Jack Nicholson', 'bruno@vault7.com', 1);