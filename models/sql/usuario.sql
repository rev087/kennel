CREATE TABLE IF NOT EXISTS `usuario` (
	id int(16) PRIMARY KEY AUTO_INCREMENT,
	login varchar(32),
	senha varchar(32),
	nome varchar(128),
	email varchar(128),
	allowmail tinyint(1)
);

INSERT INTO `usuario` (login, senha, nivel, nome, email, allowmail)
VALUES ('teste', 'teste', 2, 'Dom Casmurro', 'bruno@vault7.com', 1);