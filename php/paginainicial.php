<?php
	include("config.php");
	session_start();
	$nick=$_SESSION['nick'];
	$sql = "select B.nick, B.data_hora, B.custo_passageiro, B.nome_origem, B.nome_destino, B.nick_condutor, B.matricula from boleia B, InscricaoP I where current_timestamp < B.data_hora and B.nick = I.nick_organizador and B.data_hora = I.data_hora and I.nick_passageiro = '$nick'";
	$result = pg_query($sql);
	$viaturas = pg_query("select * from Viatura where nick = '$nick'");
	$dados = pg_fetch_assoc(pg_query("select * from utente where nick = '$nick'"));
?>
<!DOCTYPE html>
<html lang="pt-PT">
	<head>
		<title>Car Pooling Service</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="mystyle.css" />
	</head>
	<body>
		<div id="logoist">
			<h1><a href="http://www.ist.utl.pt">Instituto Superior Técnico</a></h1>
		</div>
		<h2><?php echo('Bem-Vindo ');
							echo($_SESSION['login_nome']);?></h2>
		<div>
			<div id='latnav_container'>
				<div id="latnav">
					<ul>
						<li><a class="menu-top-item-selected" href="paginainicial.php"><b>Página Inicial</b></a></li>
						<li><a href="criarboleia.php"><b>Criar boleia</b></a></li>
						<li><a href="inscreverboleia.php"><b>Inscrever numa Boleia existente</b></a></li>
						<li><a href="registarviatura.php"><b>Registar viatura</b></a></li>
						<li><a href="logout.php"><b>Terminar sessão</b></a></li>
					</ul>
				</div><!-- #latnav -->
			</div><!-- #latnav_container -->
			<div id='main_container'>
				<div id='main'>
					<div>
						<?php
							echo "Nome: " .$dados["nome"]."<br/><br/>
										Número: " .$dados["numero"]."<br/><br/>
										Saldo disponível: " .$dados["saldo"]."<br/><br/>"?>
					</div>
					<?php
					/*Imprimir tabela das Viaturas do utilizador*/
							if(pg_num_rows($viaturas) == 0){
								echo "<h3>Não tem viaturas registadas.</h3>";
							}else{
								echo "<h3>As suas Viaturas:</h3>";
								echo("<table id=\"tabela_boleias\" cellspacing=\"1\" cellpadding=\"2\">");
								echo "<tr>
											<th nowrap=\"\">Marca</th>
											<th nowrap=\"\">Modelo</th>
											<th nowrap=\"\">Matricula</th>
											<th nowrap=\"\">Capacidade</th>
											</tr>";
								while ($row = pg_fetch_assoc($viaturas)){
									echo("<tr><td>");
									echo($row["marca"]);
									echo("</td><td>");
									echo($row["modelo"]);
									echo("</td><td>");
									echo($row["matricula"]);
									echo("</td><td>");
									echo($row["maxocupantes"]);
									echo("</td></tr>");}
								echo ("</table>");}
					/*Imprimir tabela das boleias em que o utente participa*/
							if(pg_num_rows($result) == 0){
								echo "<h3>Não está inscrito em boleias.</h3>";
							}else{
								echo("<h3>As suas Boleias:</h3>");
								echo("<table id=\"tabela_boleias\" cellspacing=\"1\" cellpadding=\"2\">");
								echo "<tr>
							<th nowrap=\"\">Organizador</th>
							<th nowrap=\"\">Data e Hora</th>
							<th nowrap=\"\">Custo p/pax</th>
							<th nowrap=\"\">Origem</th>
							<th nowrap=\"\">Destino</th>
							<th nowrap=\"\">Condutor</th>
							<th nowrap=\"\">Matrícula</th>
							</tr>";
								while ($row = pg_fetch_assoc($result)){
									echo("<tr><td>");
									echo($row["nick"]);
									echo("</td><td>");
									echo($row["data_hora"]);
									echo("</td><td>");
									echo($row["custo_passageiro"]);
									echo("</td><td>");
									echo($row["nome_origem"]);
									echo("</td><td>");
									echo($row["nome_destino"]);
									echo("</td><td>");
									echo($row["nick_condutor"]);
									echo("</td><td>");
									echo($row["matricula"]);
									echo("</td></tr>");}
								echo "</table>";}?>
				</div><!-- #main -->
			</div><!-- #main_container -->
		</div>
	</body>
</html>
