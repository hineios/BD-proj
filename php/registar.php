<?php
	include("config.php");
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		// username and password sent from form 
		$nick=addslashes($_POST['nick']); 
		$nome=addslashes($_POST['nome']); 
		$ist_numero=$_POST['ist_numero']; 
		$tipo=addslashes($_POST['tipo']);
		$curso=$_POST['curso'];
		if(strlen($ist_numero) > 5){
			$error="Número ist inválido";
		}else if(pg_num_rows(pg_query("select nick from utente where nick='$nick'")) != 0){
			$error="Nick já existe";
		}else{
			pg_query("INSERT INTO utente VALUES('$nick', '$nome', '$ist_numero', 0)");
			if($tipo == "aluno"){
				pg_query("insert into $tipo values ('$nick', '$curso')");
			}else{pg_query("INSERT INTO $tipo VALUES('$nick')");}
			session_start();
			$_SESSION['nick']=$nick;
			$_SESSION['login_nome']=$nome;
			header("location: paginainicial.php");
		}
	}?>
<!DOCTYPE html>
<html lang="pt-PT">
	<head>
		<title>Car Pooling Service</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="mystyle.css" />
		<script type="text/javascript"> 
			function disablefield(){ 
			if (document.getElementById('aluno').checked == 1){ 
				document.getElementById('curso').disabled='';
				document.getElementById('curso').value='Introduza curso';
			}else{ 
				document.getElementById('curso').disabled='disabled';
				document.getElementById('curso').value='';
			} } 
</script>
	</head>
	<body>
			<div id="logoist">
				<h1><a href="http://www.ist.utl.pt">Instituto Superior Técnico</a></h1>
			</div>
		<div id="container">
			<div id="wrapper">				
				<div style="margin:30px">
				<form action="" method="post">
					<table id="registo" cellspacing="5" cellpading="5">
						<div class=""><b>Registar Novo Utilizador</b></div>
						<tr>
							<td><label>Nick:</label></td>
							<td><input type="text" name="nick" class="box" required></td>
						</tr><tr>
							<td><label>Nome:</label></td>
							<td><input type="text" name="nome" class="box" required></td>
						</tr><tr>
							<td><label>Número:</label></td>
							<td><input type="number" name="ist_numero" class="box" required></td>
						</tr><tr>
							<td>
								<input type="radio" name="tipo" value="aluno" id="aluno" onchange="disablefield()">Aluno<br />
								<input type="radio" name="tipo" value="docente" onchange="disablefield()">Docente<br />
								<input type="radio" name="tipo" value="funcionario" onchange="disablefield()">Funcionário<br />
							</td>
							<td><input type="text" name="curso" id="curso" disabled></td>
						</tr><tr>
							<td width="100%" colspan="2">
								<input type="submit" value=" Registar" class="button"/>
								<a href="index.php" class="button">Voltar</a>
							</td>
						</tr>
					</table>
				</form>
				<div class="error"><?php echo $error; ?></div>
				</div>
				</div>
			</div> <!-- wrapper-->
		</div> <!-- container-->
	</body>
</html>
