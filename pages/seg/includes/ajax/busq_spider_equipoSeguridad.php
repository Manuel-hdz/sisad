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
			
			// Verifica que se hayan escrito caracteres para revisar en la Base de Datos
			if(strlen($queryString) >0) {
				// Ejecuta la consulta usando LIKE '$queryString%'
				// El porcentaje es un comodin
				//Verificamos si la tabla es la de vida util de los equipos de seguridad
				if ($_GET['nomTabla']=="materiales"){
					// Aqui se realiza la consulta a la Base de Datos
					// Ejemplo: SELECT nombreColumna FROM NombreTabla WHERE nombrecolumna LIKE '%$queryString%' LIMIT 10
					//if ($_GET["depto"]=="todo")
					$query = $db->query("SELECT id_material, nom_material AS nombre FROM materiales WHERE (linea_articulo='EQUIPO DE SEGURIDAD Y ROPERIA') AND nom_material LIKE '%$queryString%' LIMIT 10");				
				}				
				if($query) {
					//Esta variable Indica cuando no hay registros
					$ctrlRegistros = 0;
					// Mientras haya resultados, ciclar a través de ellos - fetching an Object.
					while($result = $query ->fetch_object()){// Establece los resultados a modo de lista.										
						
						//Indicar si hubo resultados para desplegar
						$ctrlRegistros = 1;
						
						//Colocar la Funcion obtenerNombreClaveEquipo en el evento onclick para Obtener la clave del material de seguridad
						if(isset($_GET['ctrlOnclik']) && $_GET['ctrlOnclik']==1){
							//Colocar el nombre de la caja de texto que contendra la Clave Encontrado de acuerdo al Nombre del Material
							$nomCajaTextoClave= 'txt_claveMaterial';
							// Mostrar la lista en el siguiente formato $result->nombreColumna
							// La función OnClick llena la caja de texto con el resultado seleccionado.
							echo utf8_encode('
								<li title='.$result->id_material.' onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$result->nombre.'\' , \''.$_GET['num'].'\');
								obtenerNombreClaveEquipo(\''.$result->id_material.'\');verificarRegistroMaterialES(\''.$result->id_material.'\');">'.$result->nombre.'</li>
							');							
						}
							else{
								// La función OnClick llena la caja de texto con el resultado seleccionado.
								echo utf8_encode('
									<li title='.$result->id_material.' onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$result->nombre.'\' , \''.$_GET['num'].'\');
									obtenerNombreClaveEquipo(\''.$result->id_material.'\');verificarRegistroMaterialES(\''.$result->id_material.'\');">'.$result->nombre.'</li>
								');
							}//Cierre del else{ echo utf8_encode
	         		
					}// cierre while($result = $query ->fetch_object()) 
					
					//Indicar que no hay registros
					if($ctrlRegistros==0){
						$dato = "";
						$msg = "No Hay Coincidencias";
						echo utf8_encode('
							<li onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$dato.'\' , \''.$_GET['num'].'\');">'.$msg.'</li>
						');
					} //Cierre 	if($ctrlRegistros==0){
					
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