CREATE TABLE IF NOT EXISTS `funcionario` (
	id int(12) PRIMARY KEY AUTO_INCREMENT,
	id_usuario int(12),
	codigofuncionario varchar(32),
	bairro varchar(128),
	admissao date,
	demissao date
);