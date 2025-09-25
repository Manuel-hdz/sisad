<?php
	
	// Implementacion PHP5 - usando MySQLi.
	// mysqli('localhost', 'nombreUsuario', 'PassUsuario', 'BaseDeDatos');
	$db = new mysqli('localhost', 'admin_sisad_clf' ,'SistemasCLF.2024', 'bd_compras');
	
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
				$query = $db->query("SELECT * FROM proveedores WHERE razon_social LIKE '%$queryString%' LIMIT 10");
				if($query) {
					//Esta variable Indica cuando no hay registros
					$ctrlRegistros = 0;
					// Mientras haya resultados, ciclar a través de ellos - fetching an Object.
					while($result = $query ->fetch_object()){// Establece los resultados a modo de lista.
						//Indicar si hubo resultados para desplegar
						$ctrlRegistros = 1;
						
						$txtProv="document.getElementById('txt_proveedor')";
						$nombreProv="'".$result->razon_social."'";
						
						$txtRFC="document.getElementById('hdn_rfc')";
						$rfcProv="'".$result->rfc."'";
						
						$txtDireccion="document.getElementById('txt_direccion')";
						$direccionProv="'".$result->calle;
						if($result->numero_ext != "")
							$direccionProv .= " #".$result->numero_ext;
						if($result->numero_int != "")
							$direccionProv .= " INT ".$result->numero_int;
						if($result->ciudad != "")
							$direccionProv .= ", ".$result->ciudad;
						if($result->estado != "")
							$direccionProv .= ", ".$result->estado;
						if($result->cp != "")
							$direccionProv .= " C.P. ".$result->cp;
						
						$direccionProv .= "'";
						
						echo utf8_encode('
								<li onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$result->razon_social.'\' , \''.$_GET['num'].'\');'.$txtProv.'.value='.$nombreProv.';'.$txtRFC.'.value='.$rfcProv.';'.$txtDireccion.'.value='.$direccionProv.';">'.$result->razon_social.'</li>
							');
					}// cierre while($result = $query ->fetch_object()) 
					
					//Indicar que no hay registros
					if($ctrlRegistros==0){
						$dato = "";
						$msg = "No Hay Coincidencias";
						echo utf8_encode('
							<li onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$dato.'\' , \''.$_GET['num'].'\');">'.$msg.'</li>
						');
					}
				} else {
					echo 'ERROR: Hubo un problema con la consulta.'.mysqli_error();
				}
			} else {
				// No hace Nada.
			} // There is a queryString.
		} else {
			echo 'No debe haber acceso directo a este script';
		}
	}
?>