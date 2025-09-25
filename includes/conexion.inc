<?php
function conecta($bd){
	$dbd=mysql_connect("localhost:3308", "admin_sisad_clf", "SistemasCLF.2024");
	if (!$dbd){
		die ("<h3>*** ERROR al conectar... :(");
	}

	if (!mysql_select_db($bd, $dbd)){		
		//regresamos false para que redireccione el resultado
		$dbd=false;
		//Esta línea destruye la conexion si no se encontro la BD
		//die("<h3>ERROR: ".mysql_error()."</h3>");
	}			
	
	return $dbd;
}

function conecta_sabinas($bd){
	$dbd=mysql_connect("172.28.171.105:3308", "admin_sisad_clf", "SistemasCLF.2024");
	if (!$dbd){
		die ("<h3>*** ERROR al conectar... :(");
	}

	if (!mysql_select_db($bd, $dbd)){		
		//regresamos false para que redireccione el resultado
		$dbd=false;
		//Esta línea destruye la conexion si no se encontro la BD
		//die("<h3>ERROR: ".mysql_error()."</h3>");
	}			
	
	return $dbd;
}
?>