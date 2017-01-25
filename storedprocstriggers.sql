/*Trigger passageiro_insert chama passageiro_proc()
 * 
 *Introduz automaticamente um utente novo na tabela de passageiros
 */
create or replace function passageiro_proc()
returns trigger
as
$$
begin
	insert into passageiro values(new.nick);
	return new;
end
$$
language plpgsql;

create trigger passageiro_insert
after insert on utente for each row
execute procedure passageiro_proc();

/*Trigger condutor_insert chama condutor_proc()
 *
 *Introduz automaticamente na tabela condutor um utente que registe uma viatura
 */
create or replace function condutor_proc()
returns trigger
as
$$
declare 
existe numeric(1);
begin
	select count(nick) into existe from condutor where nick=new.nick;
	if existe = 0 then
		insert into condutor values(new.nick);
	end if;
	return new;
end
$$
language plpgsql;

create trigger condutor_insert
after insert on viatura for each row
execute procedure condutor_proc();

/*Trigger inscricao_insert chama inscricao_proc()
 *
 *Introduz nas tabelas inscricaoP o passageiro, se for o caso, aquando da criaçao de uma boleia
 */
create or replace function inscricao_proc()
returns trigger
as
$$
begin
	if new.nick_condutor is null then
		insert into inscricaoP values(new.nick, new.nick, new.data_hora);
	end if;
	
	return new;
end
$$
language plpgsql;

create trigger inscricao_insert
after insert on boleia for each row
execute procedure inscricao_proc();

/*Trigger inscreve_passageiro chama inscricao_insert_proc()
 *
 *Restrição de integridade a)
 */
create or replace function passageiro_insert_proc()
returns trigger
as
$$
declare
matricula_nova varchar(8);
max_ocupantes numeric(1);
boleia_ocupantes numeric(1);
saldo_passageiro numeric(9,2);
custo_boleia numeric(9,2);
begin
	if not new.nick_organizador = new.nick_passageiro then
		select matricula into matricula_nova from boleia B where B.nick = new.nick_organizador and B.data_hora = new.data_hora;
		select saldo into saldo_passageiro from utente U where U.nick = new.nick_passageiro;
		select custo_passageiro into custo_boleia from Boleia B where B.nick = new.nick_organizador and B.data_hora=new.data_hora;
		select count(*) into boleia_ocupantes from inscricaoP I where I.nick_organizador = new.nick_organizador and I.data_hora = new.data_hora;
		if matricula_nova is not null then
			select maxocupantes into max_ocupantes from viatura V where V.matricula = matricula_nova;
			if max_ocupantes < boleia_ocupantes then
				raise exception 'Boleia já está cheia';
			end if;
			if saldo_passageiro < custo_boleia then
				raise exception 'Saldo insuficiente';
			else
				update utente set saldo = saldo - custo_boleia where nick = new.nick_passageiro;
			end if;
		else
			if max_ocupantes < 9 then
				raise exception 'Boleia já está cheia';
			end if;
			if saldo_passageiro < custo_boleia then
				raise exception 'Saldo insuficiente';
			else
				update utente set saldo = saldo - custo_boleia where nick = new.nick_passageiro;
			end if;
		end if;
	end if;
	return new;
end
$$
language plpgsql;

create trigger inscreve_passageiro
before insert on inscricaop for each row
execute procedure passageiro_insert_proc();
 
/*Trigger inscreve_condutor chama condutor_insert_proc()
 *
 *Restrição de integridade b)
 */ 
create or replace function condutor_insert_proc()
returns trigger
as
$$
declare
max_ocupantes numeric(1);
boleia_ocupantes numeric(1);
condutor varchar(255);
begin
	select maxocupantes into max_ocupantes from viatura where matricula = new.matricula;
	select count(*) into boleia_ocupantes from inscricaoP where nick_organizador = new.nick and data_hora = new.data_hora;
	select nick_condutor into condutor from boleia where nick = new.nick and data_hora = new.data_hora;
	if condutor is not null then
		raise exception 'A boleia já tem condutor';
	end if;
	if max_ocupantes < boleia_ocupantes then
		raise exception 'A viatura não tem lugares suficientes';
	end if;
	return new;
end
$$
language plpgsql;

create trigger inscreve_condutor
before update on Boleia for each row
execute procedure condutor_insert_proc();