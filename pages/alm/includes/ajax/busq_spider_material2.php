<?php
	
	// Implementacion PHP5 - usando MySQLi.
	// mysqli('172.16.113.93', 'nombreUsuario', 'PassUsuario', 'BaseDeDatos');
	$db = new mysqli('localhost', 'admin_sisad_clf' ,'SistemasCLF.2024', 'bd_almacen');
	
	if(!$db) {
		// Muestra Error si no se puede conectar
		echo 'ERROR: No se pudo conectar a la Base de Datos';
	} else {
		// Is there a posted query string?
		if(isset($_POST['queryString'])) {
			$queryString = $db->real_escape_string($_POST['queryString']);
			//$area = $_GET["area"];
			// Verifica que se hayan escrito caracteres para revisar en la Base de Datos
			if(strlen($queryString) >0) {
				// Ejecuta la consulta usando LIKE '$queryString%'
				// El porcentaje es un comodin
				$query = $db->query("SELECT id_material, nom_material, existencia, unidad_medida
									 FROM materiales
									 JOIN unidad_medida ON materiales_id_material = id_material
									 WHERE nom_material LIKE  '%$queryString%'
									 ORDER BY nom_material
									 LIMIT 10
									");
				//Variable con el valor de Aceptado
				$aceptado="'SI'";
				//Verificar la consulta
				if($query) {
					//Esta variable Indica cuando no hay registros
					$ctrlRegistros = 0;
					// Mientras haya resultados, ciclar a través de ellos - fetching an Object.
					while($result = $query ->fetch_object()){// Establece los resultados a modo de lista.										
						//Indicar si hubo resultados para desplegar
						$ctrlRegistros = 1;
						//Verificar a que caja de Texto se le escribira la existencia
						$cajaExistencia="document.getElementById('txt_existencia')";
						//Verificar a que caja de Texto se le escribira la unidad de medida
						$cajaUnidadMedida="document.getElementById('txt_unidadMedida')";
						//Verificar el campo Hidden donde se guardara la Clave segun el Stock de Almacen
						$claveAlmacen="document.getElementById('txt_clave')";
						//Sustituir el caracter de comillas dobles en el nombre para evitar errores de cadenas no terminadas
						$nombreMat=str_replace("\"","inch",$result->nom_material);
						//Colocar apostrofes al inicio y fin de cada clave para mostrar que son campos tipo Texto
						$claveMat="'".$result->id_material."'";
						$unidad="'".$result->unidad_medida."'";
						// La función OnClick llena la caja de texto con el resultado seleccionado.
						echo utf8_encode('
							<li title='.$result->id_material.' onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$nombreMat.'\' , \''.$_GET['num'].'\');'.$cajaExistencia.'.value='.$result->existencia.';'.$cajaUnidadMedida.'.value='.$unidad.';'.$claveAlmacen.'.value='.$claveMat.';">'.$result->nom_material.'</li>');
					}// cierre while($result = $query ->fetch_object()) 
					//Indicar que no hay registros
					if($ctrlRegistros==0){
						$dato = "";
						$msg = "No Hay Coincidencias";
						//Verificar a que caja de Texto se le escribira la existencia
						$cajaExistencia="document.getElementById('txt_existencia')";
						//Verificar a que caja de Texto se le escribira la unidad de medida
						$cajaUnidadMedida="document.getElementById('txt_unidadMedida')";
						//Verificar el campo Hidden donde se guardara la Clave segun el Stock de Almacen
						$claveAlmacen="document.getElementById('txt_clave')";
						
						$vaciar="''";
						echo utf8_encode('
							<li onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$dato.'\' , \''.$_GET['num'].'\');'.$cajaExistencia.'.value='.$vaciar.';'.$cajaUnidadMedida.'.value='.$vaciar.';'.$claveAlmacen.'.value='.$vaciar.';">'.$msg.'</li>
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