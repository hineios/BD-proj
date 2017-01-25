create table Utente
	(nick			varchar(255) 		not null unique,
	nome			varchar(255)		not null,
	numero			numeric(5)			not null,
	saldo			numeric(9,2)	 		not null,
	primary key(nick));
	
create table Viatura
	(matricula		varchar(8)		not null unique,
	marca			varchar(255)  		not null,
	modelo			varchar(255)		not null,
	maxocupantes		numeric(1)			not null,
	nick			varchar(255)		not null,
	primary key(matricula),
	foreign key(nick) references Utente(nick),
	check(maxocupantes >= 2 and maxocupantes <= 9));
	
create table Aluno
	(nick			varchar(255)		not null,
	curso			varchar(255)		not null,
	primary key(nick),
	foreign key(nick) references Utente(nick));
	
create table Docente
	(nick			varchar(255)		not null,
	primary key(nick),
	foreign key(nick) references Utente(nick));

create table Funcionario
	(nick			varchar(255)		not null,
	primary key(nick),
	foreign key(nick) references Utente(nick));
	
create table Condutor
	(nick			varchar(255)		not null,
	primary key(nick),
	foreign key(nick) references Utente(nick));
	
create table Passageiro
	(nick			varchar(255)		not null,
	primary key(nick),
	foreign key(nick) references Utente(nick));
	
create table Local
	(nome			varchar(255)		not null unique,
	latitude		numeric(3)			not null,
	longitude		numeric(3)			not null,
	primary key(nome));
	
create table Trajecto
	(nome_origem	varchar(255)		not null,
	nome_destino	varchar(255)		not null,
	primary key(nome_origem, nome_destino),
	foreign key(nome_origem) references Local(nome),
	foreign key(nome_destino) references Local(nome));
	
create table Boleia
	(nick			varchar(255)		not null,
	data_hora		timestamp(0)		not null,
	custo_passageiro	numeric(3,2)	not null,
	nome_origem		varchar(255)		not null,
	nome_destino		varchar(255)		not null,
	nick_condutor		varchar(255)		,
	matricula		varchar(8)		,
	primary key(nick, data_hora),
	foreign key(nick) references Utente(nick),
	foreign key(nome_origem, nome_destino) references Trajecto(nome_origem, nome_destino),
	foreign key(nick_condutor) references Condutor(nick),
	foreign key(matricula) references Viatura(matricula),
	check(custo_passageiro >= 0));
	
create type frequencia as enum('diaria', 'semanal', 'mensal');

create table BoleiaFrequente
	(nick			varchar(255)		not null,
	data_hora		timestamp(0)		not null,
	data_termino		timestamp(0)		not null,
	tipo			frequencia			not null,
	primary key(nick, data_hora),
	foreign key(nick, data_hora) references Boleia(nick, data_hora));
	
create table BoleiaUnica
	(nick			varchar(255)		not null,
	data_hora		timestamp(0)		not null,
	primary key(nick, data_hora),
	foreign key(nick, data_hora) references Boleia(nick, data_hora));
	
create table InscricaoP
	(nick_passageiro	varchar(255)	not null,
	nick_organizador	varchar(255)	not null,
	data_hora		timestamp(0)	not null,
	primary key(nick_passageiro, nick_organizador, data_hora),
	foreign key(nick_passageiro) references Passageiro(nick),
	foreign key(nick_organizador, data_hora) references Boleia(nick, data_hora));