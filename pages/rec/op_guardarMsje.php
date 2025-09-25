<?php
session_start();

include_once("../../includes/conexion.inc");

//Conectarse a la BD
$conn = conecta("bd_usuarios");
//Crear la Sentencia SQL
$sql_stm = "SELECT estado FROM chat WHERE usuarios_usuario='".$_SESSION['usr_reg']."'";
//Ejecutar la Sentencia previamente creada
$rs = mysql_query($sql_stm);
//Comparar los resultados obtenidos 
if($datos=mysql_fetch_array($rs))
	$continuar=true;
else
	$continuar=false;

if(isset($_SESSION['usr_reg']) && $continuar && $_POST['text']!=""){
	ini_set("date.timezone","America/Mexico_City");
	$hora=date("g:i:s A");
	$text = $_POST['text'];
	$fp = fopen("../../includes/chat/log.html", 'a');
	fwrite($fp, "<p><label class='msje_correcto'>(".$hora.") ".$_SESSION['usr_reg'].":</label> <label style='color:#000;'>".stripslashes(htmlspecialchars($text))."<label style='color:#000;'></p>");
	fclose($fp);
	$_SESSION["ultimoMsjeChat"]=$hora;
}
?>