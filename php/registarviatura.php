<?php
include("config.php");
session_start();
$nick=$_SESSION['nick'];
if($_SERVER["REQUEST_METHOD"] == "POST"){
	$marca=$_POST['marca'];
	$modelo=$_POST['modelo'];
	$matricula=$_POST['matricula1'].'-'.$_POST['matricula2'].'-'.$_POST['matricula3'];
	$ocupantes=$_POST['ocupantes'];
	pg_query("insert into viatura values('$matricula', '$marca', '$modelo', $ocupantes, '$nick')") or die(pg_last_error());
	echo("<script type=\"text/javascript\"> window.alert(\"A sua viatura foi registada.\")</script>");
}?>
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
						<li><a href="paginainicial.php"><b>Página Inicial</b></a></li>
						<li><a href="criarboleia.php"><b>Criar boleia</b></a></li>
						<li><a href="inscreverboleia.php"><b>Inscrever numa Boleia existente</b></a></li>
						<li><a class="menu-top-item-selected" href="registarviatura.php"><b>Registar viatura</b></a></li>
						<li><a href="logout.php"><b>Terminar sessão</b></a></li>
					</ul>
				</div>
			</div>
			<div id='main_container'>
				<div id='main'>
					<h3 class='.mbottom03'>Registar Nova Viatura</h3>
					<form action="" method="post">
					Marca:<br>
					<input type="text" name="marca"><br /><br />
					Modelo:<br>
					<input type="text" name="modelo"><br /><br />
					Matricula:<br>
					<input type="text" name="matricula1" size="2" maxlength="2">-<input type="text" name="matricula2" size="2" maxlength="2">-<input type="text" name="matricula3" size="2" maxlength="2"><br /><br />
					Nº de ocupantes:<br>
					<input type="number" name="ocupantes" min="2" max="9" maxlength="1" size="1"><br /><br />
					<input type="submit" value="Registar" class="button"/>
				</div>
			</div>
		</div>
	</body>
</html>