<?php
include("config.php");
session_start();
$nick=$_SESSION['nick'];
	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$horas = $_POST['horas'];
		$minutos = $_POST['minutos'];
		$data_hora = $_POST['orderdate']." ".$horas.":".$minutos;
		$data_termino=$_POST['orderdate1']." 00:00";
		$euros = $_POST['euros'];
		$centimos = $_POST['centimos'];
		$centimos = $centimos / 100;
		$custo = $euros + $centimos;
		$nome_origem = strtok($_POST['trajecto'],",");
		$nome_destino = strtok(",");
		if(isset($_POST['cond'])){
			$matricula = $_POST['cond_val'];
			pg_query("insert into Boleia values('$nick', '$data_hora', $custo, '$nome_origem', '$nome_destino', '$nick', '$matricula')") or die(pg_last_error());
		}else{
			pg_query("insert into Boleia(nick, data_hora, custo_passageiro, nome_origem, nome_destino) values('$nick', '$data_hora', $custo, '$nome_origem', '$nome_destino')") or die(pg_last_error());
		}
		if(isset($_POST['freq'])){
			$freq_val = $_POST['freq_val'];
			pg_query("INSERT INTO BoleiaFrequente VALUES('$nick', '$data_hora', '$data_termino', '$freq_val')") or die(pg_last_error());
		}else{
			pg_query("INSERT INTO BoleiaUnica VALUES('$nick', '$data_hora')") or die(pg_last_error());
		}
		echo("<script type=\"text/javascript\"> window.alert(\"A sua boleia foi criada.\")</script>");
	}?>
<!DOCTYPE html>
<html lang="pt-PT">
	<head>
		<title>Car Pooling Service</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" type="text/css" href="mystyle.css" />
		<script type="text/javascript" src="calendarDateInput.js">
/***********************************************
* Jason's Date Input Calendar- By Jason Moon http://calendar.moonscript.com/dateinput.cfm
* Script featured on and available at http://www.dynamicdrive.com
* Keep this notice intact for use.
***********************************************/
		</script>
		<script type="text/javascript">
		window.onload = function(){
			var horasArray = new Array("00", "01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21", "22", "23");
			var minutosArray = new Array("00", "05", "10", "15", "20", "25", "30", "35", "40", "45", "50", "55");
			var horas = document.getElementById("horas");
			var minutos = document.getElementById("minutos");
			for(i=0; i<horasArray.length; i++){
				var Entry = document.createElement("option");
				Entry.text = horasArray[i];
				horas.add(Entry, null);
			}
			for(i=0; i<minutosArray.length; i++){
				var Entry = document.createElement("option");
				Entry.text = minutosArray[i];
				minutos.add(Entry, null);
			}
		}
		function off(){
				document.getElementById("orderdate1_Month_ID").disabled = true;
				document.getElementById("orderdate1_Day_ID").disabled = true;
				document.getElementById("orderdate1_Year_ID").disabled = true;
		}
		function switch_freq(){
			if (document.getElementById("freq").disabled){
				document.getElementById("orderdate1_Month_ID").disabled = false;
				document.getElementById("freq").disabled = false;
				document.getElementById("orderdate1_Day_ID").disabled = false;
				document.getElementById("orderdate1_Year_ID").disabled = false;}
			else{
				document.getElementById("orderdate1_Month_ID").disabled = true;
				document.getElementById("freq").disabled = true;
				document.getElementById("orderdate1_Day_ID").disabled = true;
				document.getElementById("orderdate1_Year_ID").disabled = true;}
		}
		function switch_cond(){
			if (document.getElementById("cond").disabled){
				document.getElementById("cond").disabled = false;}
			else{
				document.getElementById("cond").disabled = true;}
		}
		</script>
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
						<li><a class="menu-top-item-selected" href="criarboleia.php"><b>Criar boleia</b></a></li>
						<li><a href="inscreverboleia.php"><b>Inscrever numa Boleia existente</b></a></li>
						<li><a href="registarviatura.php"><b>Registar viatura</b></a></li>
						<li><a href="logout.php"><b>Terminar sessão</b></a></li>
					</ul>
				</div>
			</div>
			<div id='main_container'>
				<div id='main'>
					<h3 class='.mbottom03'>Criar Boleia</h3>
					<form action="" method="post">
						Seleccione Data de Início:
						<script>DateInput('orderdate', true, 'YYYY-MM-DD')</script><br />
						Seleccione Hora de Início:<br/>
						<select name="horas" id="horas"></select>
						<select name="minutos" id="minutos"></select><br/><br/>
						Introduza o custo p/pax da Boleia:<br/>
						<input type="number" name="euros" size="3" maxlength="3">,
						<input type="number" name="centimos" size="2" maxlength="2">€<br/><br/>
						Seleccione Trajecto:<br/>
						<select name="trajecto">
							<?php
								$query = "select nome_origem, nome_destino from trajecto";
								$result = pg_query($query)  or die(pg_last_error());
								while($row = pg_fetch_assoc($result)){
									echo("<option value=" . $row['nome_origem'] . "," . $row['nome_destino'] . ">" . $row['nome_origem'] ." -> " . $row['nome_destino'] . "</option>");
								}?>
						</select><br/><br/>
						<div>
							Boleia Frequente?
							<input name="freq" type="checkbox" onclick="switch_freq()">
							<div>
								Seleccione Frequência:<br>
								<select name="freq_val" id="freq" disabled="true">
									<option>diaria</option>
									<option>semanal</option>
									<option>mensal</option>
								</select><br>
								Seleccione Data de Termino:
								<script name="data_termino" id="freq" disabled="true">DateInput('orderdate1', true, 'MM-DD-YYYY')</script>
								<script>off()</script>
							</div>
						</div><br/>
						<div>
							Deseja inscrever-se como condutor?
							<input name="cond" type="checkbox" onclick="switch_cond()"><br>
							Seleccione Viatura:<br>
							<select name="cond_val" id="cond" disabled="true">
								<?php
									$query = "select matricula from viatura where nick = '$nick'";
									$result = pg_query($query)  or die(pg_last_error());
									while($row = pg_fetch_assoc($result)){
										echo("<option value=" . $row['matricula'] . ">" . $row['matricula'] . "</option>");
									}?>
							</select><br /><br />
							<input type="submit" value="Inscrever" class="button"/>
						</div>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>



