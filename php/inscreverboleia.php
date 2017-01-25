<?php
	include("config.php");
	session_start();
	$nick=$_SESSION['nick'];
	if($_SERVER["REQUEST_METHOD"] == "POST"){
			$nickp=strtok($_POST['inscricao'],",");
			$data=strtok(",");
			$matricula = $_POST['cond_val'];
		if(isset($_POST['condutor'])){
			pg_query("update boleia set nick_condutor='$nick', matricula='$matricula' where nick='$nickp' and data_hora='$data'") or die(pg_last_error());
			$data = "Condutor";
		}else{
			pg_query("insert into inscricaop values('$nick', '$nickp', '$data')")  or die(pg_last_error());
			$data = "Passageiro";
			}
		echo("<script type=\"text/javascript\"> window.alert(\"Registou-se como " . $data . " na Boleia.\")</script>");
	}
	$sql = "select * from boleia where current_timestamp < data_hora";
	$result = pg_query($sql)  or die(pg_last_error());
?>
<!DOCTYPE html>
<html lang="pt-PT">
	<head>
		<title>Car Pooling Service</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="mystyle.css" />
		<script>function switch_cond(){
			if (document.getElementById("cond").disabled){
				document.getElementById("cond").disabled = false;}
			else{
				document.getElementById("cond").disabled = true;}
		}</script>
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
						<li><a href="paginainicial.php"><b>Página Inicial</b></a></li>
						<li><a href="criarboleia.php"><b>Criar boleia</b></a></li>
						<li><a class="menu-top-item-selected" href="inscreverboleia.php"><b>Inscrever numa Boleia existente</b></a></li>
						<li><a href="registarviatura.php"><b>Registar viatura</b></a></li>
						<li><a href="logout.php"><b>Terminar sessão</b></a></li>
					</ul>
				</div>
			</div>
			<div id='main_container'>
				<div id='main'>
					<h3 class='.mbottom03'>Inscrever em Boleia existente</h3>
					<form action="" method="post">
						<table id="tabela_boleias" cellspacing="1" cellpadding="2">
							<tr>
								<th nowrap="">Organizador</th>
								<th nowrap="">Data e Hora</th>
								<th nowrap="">Custo p/pax</th>
								<th nowrap="">Origem</th>
								<th nowrap="">Destino</th>
								<th nowrap="">Condutor</th>
								<th nowrap="">Matrícula</th>
								<th nowrap=""></th>
							</tr>
							<?php
								/*Imprime a tabela das boleias que ainda não se realizaram*/
								while ($row = pg_fetch_assoc($result))
								{
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
									echo("</td><td>");
									echo("<input type=\"radio\" name=\"inscricao\" value=\"" . $row["nick"] . "," . $row["data_hora"] . "\">");
									echo("</td></tr>");}?>
						</table>
					<div>
						Inscrever como condutor?
						<input name="condutor" type="checkbox" onclick="switch_cond()">
						<select name="cond_val" id="cond" disabled="true">
							<?php
								$query = "select matricula from viatura where nick = '$nick'";
								$result = pg_query($query)  or die(pg_last_error());
								while($row = pg_fetch_assoc($result)){
									echo("<option value=" . $row['matricula'] . ">" . $row['matricula'] . "</option>");
								}?>
						</select>
					</div>
					<div>
						<input type="submit" value="Inscrever" class="button">
					</div><br>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
