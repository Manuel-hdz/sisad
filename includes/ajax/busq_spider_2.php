<?php
	
	// Implementacion PHP5 - usando MySQLi.
	// mysqli('172.16.113.93', 'nombreUsuario', 'PassUsuario', 'BaseDeDatos');
	$db = new mysqli('localhost', 'admin_sisad_clf' ,'SistemasCLF.2024', $_GET['nomBd']);
	
	if(!$db) {
		// Muestra Error si no se puede conectar
		echo 'ERROR: No se pudo conectar a la Base de Datos';
	} else {
		// Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = $db->real_escape_string($_POST['queryString']);
			
			// Verifica que se hayan escrito caracteres para revisar en la Base de Datos
			
			if(strlen($queryString) >0) {			
				// Ejecuta la consulta usando LIKE '$queryString%'
				// The percentage sign is a wild-card, in my example of countries it works like this...
				// $queryString = 'Uni';
				// Returned data = 'United States, United Kindom';
				
				// Aqui se realiza la consulta a la Base de Datos
				// Ejemplo: SELECT nombreColumna FROM NombreTabla WHERE nombrecolumna LIKE '%$queryString%' LIMIT 10
				
				$query = $db->query("SELECT ".$_GET['nomCampo'].",".$_GET['nomCampo2']." FROM ".$_GET['nomTabla']." WHERE ".$_GET['nomCampo']." LIKE '%$queryString%' LIMIT 10");
				if($query) {
					//Esta variable Indica cuando no hay registros
					$ctrlRegistros = 0;
					// Mientras haya resultados, ciclar a través de ellos - fetching an Object.
					while ($result = $query ->fetch_object()) {
					
						//Indicar si hubo resultados para desplegar
						$ctrlRegistros = 1;
						
						// Establece los resultados a modo de lista.
						// La función OnClick llena la caja de texto con el resultado seleccionado.
						
						// Mostrar la lista en el siguiente formato $result->nombreColumna
	         			echo utf8_encode('<li onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$result->$_GET['nomCampo'].'\' ,\''.$_GET['nomCajaTexto2'].'\' , \''.$result->$_GET['nomCampo2'].'\' , \''.$_GET['num'].'\');">'.$result->$_GET['nomCampo'].'</li>');
	         		}//Cierre while ($result = $query ->fetch_object())
					
					//Indicar que no hay registros
					if($ctrlRegistros==0){
						$dato = "";
						$msg = "No Hay Coincidencias";
						echo utf8_encode('
							<li onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$dato.'\' ,\''.$_GET['nomCajaTexto2'].'\' , \''.$dato.'\' , \''.$_GET['num'].'\');">'.$msg.'</li>
						');
					}
					
				} else {
					echo 'ERROR: Hubo un problema con la consulta.';
				}
			} else {
				// No hace Nada.
			} // There is a queryString.
		} else {
			echo 'No debe haber acceso directo a este script';
		}
	}
?>