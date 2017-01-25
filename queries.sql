a)

select B.nick, B.data_hora, I.nick_passageiro, B.custo_passageiro, B.nome_origem, B.nome_destino, B.nick_condutor, B.matricula, V.marca, V.modelo, V.maxocupantes from Boleia B inner join Inscricaop I on(B.nick=I.nick_organizador and B.data_hora = I.data_hora) left outer join Viatura V on(B.matricula = V.matricula) where current_timestamp < B.data_hora; 

b)

create view soma as select B1.nick, B1.nick_condutor, sum(B1.custo_passageiro) as custo_total from Boleia B1, InscricaoP I where B1.nick = I.nick_organizador and B1.nick_condutor is not null group by B1.nick, B1.nick_condutor;
create view media as select S.nick_condutor, avg(S.custo_total) as custo_medio from soma S group by S.nick_condutor;
select M.nick_condutor from media M where M.custo_medio = (select max(M1.custo_medio) from media M1);

c)

create view totais as select count(distinct nome_destino) from trajecto where nome_origem='FML';
create view userTraj as select nick_passageiro, count(distinct B.nome_destino) from boleia B, inscricaop I WHERE B.nick = I.nick_organizador and nome_origem='FML' group by I.nick_passageiro;
create view condTraj as select nick_condutor, count(distinct B.nome_destino) from boleia B WHERE B.nick = B.nick_condutor and nome_origem='FML' group by nick_condutor;
select nick_passageiro from (select * from userTraj union select * from condTraj) A, totais T where A.count = T.count;
