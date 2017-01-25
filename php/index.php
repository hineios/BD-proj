<?php
	include("config.php");
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$myusername=addslashes($_POST['username']); 
		$mypassword=addslashes($_POST['password']); 
		$sql="SELECT nick FROM utente WHERE nick='$myusername'";
		$result=pg_query($sql);
		$count=pg_num_rows($result);
// If result matched $myusername, table row must be 1 row
		if($count==1){
			session_start();
			$_SESSION['nick']=$myusername;
			$sql="SELECT nome FROM utente WHERE nick='$myusername'";
			$result=pg_query($sql);
			$row=pg_fetch_array($result);
			$nome=$row['nome'];
			$_SESSION['login_nome']=$nome;
			header("location: paginainicial.php");
		}else {
			$error="O utilizador não existe";
		}
}
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
		<div id="container">
			<div id="wrapper">
				<form action="" method="post">
					<label>Nick:</label><br />
					<input type="text" name="username" class="box"/><br /><br />
					<input class="button" type="submit" value="Login"/>
					<a href="registar.php" class="button">Registar</a><br />
				</form>
				<div class="error"><?php echo $error; ?></div>
			</div>	 <!-- #wrapper -->
		</div> <!-- #container -->
	</body>
</html>
