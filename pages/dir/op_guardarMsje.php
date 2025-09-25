<?php
session_start();

if(isset($_SESSION['usr_reg'])){
	$hora=date("g:i:s A");
	$text = $_POST['text'];
	if($text!=""){
		$fp = fopen("../../includes/chat/log.html", 'a');
		fwrite($fp, "<p><label class='msje_correcto'>(".$hora.") ".$_SESSION['usr_reg'].":</label> <label style='color:#000;'>".stripslashes(htmlspecialchars($text))."<label style='color:#000;'></p>");
		fclose($fp);
		$_SESSION["ultimoMsjeChat"]=$hora;
	}
}
?>