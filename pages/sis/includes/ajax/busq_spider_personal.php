<?php
	
	// Implementacion PHP5 - usando MySQLi.
	// mysqli('172.16.113.93', 'nombreUsuario', 'PassUsuario', 'BaseDeDatos');
	$db = new mysqli('localhost', 'admin_sisad_clf' ,'SistemasCLF.2024', 'bd_recursos');
	
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
				//Verificamos si la tabla es la de empleados o la de bolsa_trabajo
				if ($_GET['nomTabla']=="empleados"){
					// Aqui se realiza la consulta a la Base de Datos
					// Ejemplo: SELECT nombreColumna FROM NombreTabla WHERE nombrecolumna LIKE '%$queryString%' LIMIT 10
					if ($_GET["depto"]=="todo")
						$query = $db->query("SELECT rfc_empleado,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat) LIKE '%$queryString%' AND estado_actual='ALTA' LIMIT 10");
					else
						$query = $db->query("SELECT rfc_empleado,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat) LIKE '%$queryString%' AND estado_actual='ALTA' AND area='$_GET[depto]' LIMIT 10");
				}
				if ($_GET['nomTabla']=="bolsa_trabajo"){
					$query = $db->query("SELECT folio_aspirante AS rfc_empleado,CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre FROM bolsa_trabajo WHERE CONCAT(nombre,' ',ap_paterno,' ',ap_materno) LIKE '%$queryString%' LIMIT 10");
				}
				if ($_GET['nomTabla']=="bajas_modificaciones"){
					$query = $db->query("SELECT DISTINCT empleados_rfc_empleado AS rfc_empleado,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM bajas_modificaciones WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat) LIKE '%$queryString%' LIMIT 10");
				}
				if ($_GET['nomTabla']=="empleados_baja"){
					if ($_GET["depto"]=="todo")
						$query = $db->query("SELECT rfc_empleado,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat) LIKE '%$queryString%' AND estado_actual='BAJA' LIMIT 10");
					else
						$query = $db->query("SELECT rfc_empleado,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat) LIKE '%$queryString%' AND area='$_GET[depto]' AND estado_actual='BAJA' LIMIT 10");
				}
				
				if($query) {
					//Esta variable Indica cuando no hay registros
					$ctrlRegistros = 0;
					// Mientras haya resultados, ciclar a través de ellos - fetching an Object.
					while($result = $query ->fetch_object()){// Establece los resultados a modo de lista.										
						
						//Indicar si hubo resultados para desplegar
						$ctrlRegistros = 1;
						
						//Colocar la Funcion obtenerRFCEmpleado en el evento onclick para Obtener el RFC del Empleado
						if(isset($_GET['ctrlOnclik']) && $_GET['ctrlOnclik']==1){
							//Colocar el nombre de la caja de texto que contendra el RFC Encontrado de acuerdo al Nombre del Empleado
							$nomCajaTextoRFC= 'txt_RFCEmpleado';
							// Mostrar la lista en el siguiente formato $result->nombreColumna
							// La función OnClick llena la caja de texto con el resultado seleccionado.
							echo utf8_encode('
								<li title='.$result->rfc_empleado.' onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$result->nombre.'\' , \''.$_GET['num'].'\');
								obtenerRFCEmpleado(\''.$result->nombre.'\' , \''.$nomCajaTextoRFC.'\');">'.$result->nombre.'</li>
							');							
						}
						//Colocar la Funcion validarEstadoEmpleado en el evento onclick para Obtener validar si el Empleado es candidato a un prestamo
						else if(isset($_GET['ctrlOnclik']) && $_GET['ctrlOnclik']==2){
							//Colocar el nombre de la caja de texto que contendra el RFC Encontrado de acuerdo al Nombre del Empleado
							$nomCajaTextoRFC= 'txt_RFCEmpleado';
							// La función OnClick llena la caja de texto con el resultado seleccionado.
							echo utf8_encode('
								<li title='.$result->rfc_empleado.' onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$result->nombre.'\' , \''.$_GET['num'].'\');
								validarEstadoEmpleado(\''.$result->nombre.'\' , \''.$nomCajaTextoRFC.'\');" >'.$result->nombre.'</li>
							');														
						}
						else{
							// La función OnClick llena la caja de texto con el resultado seleccionado.
							echo utf8_encode('
								<li title='.$result->rfc_empleado.' onClick="fill(\''.$_GET['nomCajaTexto'].'\' , \''.$result->nombre.'\' , \''.$_GET['num'].'\');">'.$result->nombre.'</li>
							');
						}
	         		
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