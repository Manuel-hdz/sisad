<?php
	
	// Implementacion PHP5 - usando MySQLi.
	// mysqli('172.16.113.93', 'nombreUsuario', 'PassUsuario', 'BaseDeDatos');
	$db = new mysqli('localhost', 'admin_sisad_clf' ,'SistemasCLF.2024', 'bd_gerencia');
	
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
				// El porcentaje es un comodin
				$query = $db->query("SELECT rfc_trabajador, nombre_emp, id_cuadrilla FROM integrantes_cuadrilla WHERE nombre_emp 
									 LIKE '%$queryString%' LIMIT 10");
				if($query) {
					//Esta variable Indica cuando no hay registros
					$ctrlRegistros = 0;
					// Mientras haya resultados, ciclar a través de ellos - fetching an Object.
					while($result = $query ->fetch_object()){// Establece los resultados a modo de lista.
						//Colocar el nombre de la caja de texto que contendra el RFC Encontrado de acuerdo al Nombre del Empleado
						$nomCajaTextoRFC= 'hdn_rfc';
						$nomCajaTextoC= 'hdn_cuad';
						//Indicar si hubo resultados para desplegar
						$ctrlRegistros = 1;
						// La función OnClick llena la caja de texto con el resultado seleccionado.
						echo utf8_encode('
								<li title='.$result->rfc_trabajador.' onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$result->nombre_emp.'\' , \''.$_GET['num'].'\');
								fill(\''.$nomCajaTextoRFC.'\' , \''.$result->rfc_trabajador.'\' , \''.$_GET['num'].'\');
								fill(\''.$nomCajaTextoC.'\' , \''.$result->id_cuadrilla.'\' , \''.$_GET['num'].'\');">'.$result->nombre_emp.'</li>
							');		         		
					}// cierre while($result = $query ->fetch_object()) 
					
					//Indicar que no hay registros
					if($ctrlRegistros==0){
						$nomCajaTextoRFC= 'hdn_rfc';
						$nomCajaTextoC= 'hdn_cuad';
						$dato = "";
						$msg = "No Hay Coincidencias";
						echo utf8_encode('
							<li onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$dato.'\' , \''.$_GET['num'].'\');
							fill(\''.$nomCajaTextoRFC.'\' , \''.$dato.'\' , \''.$_GET['num'].'\');
							fill(\''.$nomCajaTextoC.'\' , \''.$dato.'\' , \''.$_GET['num'].'\');">'.$msg.'</li>
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