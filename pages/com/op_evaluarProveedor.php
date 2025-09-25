<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                          
	  * Fecha: 16/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de evaluarProveedor2 en la BD de Compras
	  				Asi como la visualizacion de los resultados de la evaluacion
	  **/
		
		//Esta funcion obtiene los datos de la evaluacion y los registra en la BD
		function evaluacion(){
			//Realizar la conexion a la BD de Compras
			$conn = conecta("bd_compras");	 		
			//Obtener la fecha actual en formato leido por MySQL	
			$fecha= date("Y-m-d");
			$periodo=$_POST["txt_fechaInicio"]." - ".$_POST["txt_fechaCierre"];;
			$rfc_proveedores=$_POST["rfc_proveedores"];
			$rdb_tiempoEntrega=$_POST["rdb_tiempoEntrega"];
			$rdb_ProdServicio=$_POST["rdb_ProdServicio"];
			$rdb_entCertificado=$_POST["rdb_entCertificado"];
			$hdn_totalPuntos=$_POST["hdn_totalPuntos"];
			$txa_comentarios=strtoupper($_POST["txa_comentarios"]);
			//Crear la sentencia para realizar el registro de evaluación en la BD de Compras en la tabla de evaluacion
			$stm_sql = "INSERT INTO evaluacion (periodo,proveedores_rfc,tiempo_entrega,servicio_producto,cert_calidad_prod,total_pts,comentarios,fecha_evaluacion)
			VALUES('$periodo','$rfc_proveedores','$rdb_tiempoEntrega','$rdb_ProdServicio','$rdb_entCertificado','$hdn_totalPuntos','$txa_comentarios','$fecha')";		
			//Ejecutar la sentencia previamente creada		
			$rs = mysql_query($stm_sql);									
			//Confirmar que la insercion de datos fue realizada con exito.
			if($rs){	
				registrarOperacion("bd_compras",$rfc_proveedores,"EvaluarProveedor",$_SESSION['usr_reg']);		
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
			else{			
				echo $error = mysql_error();			
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
			//Cerrar la conexion con la BD		
			//La Conexion a la BD se cierra en la funcion registrarOperacion("bd_compras",$txt_rfc,"AgregarCliente",$_SESSION['usr_reg']);		
		}

		//Esta funcion muestra las evaluaciones registradas en la BD
		function mostrarEvaluacion(){
			//Realizar la conexion a la BD de Compras
			$conn = conecta("bd_compras");
			//Obtener el RFC del proveedor seleccionado
			$rfc=obtenerDato("bd_compras","proveedores","rfc","razon_social",$_POST["txt_nombre"]);
			//Si rfc regresa vacio, el proveedor no existe en la BD y por lo tanto no se debe realizar la evaluacion
			if ($rfc!=""){
				//Sentencia SQl para mostrar el registro de Evaluaciones
				$sql="SELECT periodo,tiempo_entrega,servicio_producto,cert_calidad_prod,total_pts,comentarios,fecha_evaluacion FROM evaluacion WHERE proveedores_rfc='$rfc'";
				//Ejecutar la consulta
				$rs=mysql_query($sql);
				//Verificar que la consulta se realiza con éxito
				if($datos = mysql_fetch_array($rs)){
					echo "				
						<table cellpadding='5' width='100%' align='center'> 
						<caption class='titulo_etiqueta'>Evaluaciones del Proveedor ".$_POST["txt_nombre"]."</caption></br>
						<tr>
							<td class='nombres_columnas' align='center'>PROVEEDOR</td>
							<td class='nombres_columnas' align='center'>PER&Iacute;ODO</td>
							<td class='nombres_columnas' align='center'>FECHA DE EVALUACI&Oacute;N</td>
							<td class='nombres_columnas' align='center'>PUNTOS TIEMPO ENTREGA</td>
							<td class='nombres_columnas' align='center'>PUNTOS SERVICIO/PRODUCTO</td>
							<td class='nombres_columnas' align='center'>PUNTOS CERTIFICADO CALIDAD DEL PRODUCTO</td>
							<td class='nombres_columnas' align='center'>TOTAL DE PUNTOS</td>
							<td class='nombres_columnas' align='center'>COMENTARIOS</td>
						</tr>";
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{										
						echo "	<tr>					
						<td class='$nom_clase' align='center'>".$_POST["txt_nombre"]."</td>					
						<td class='$nom_clase' align='center'>$datos[periodo]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos["fecha_evaluacion"],2)."</td>
						<td class='$nom_clase' align='center'>$datos[tiempo_entrega]</td>
						<td class='$nom_clase' align='center'>$datos[servicio_producto]</td>
						<td class='$nom_clase' align='center'>$datos[cert_calidad_prod]</td>
						<td class='$nom_clase' align='center'>$datos[total_pts]</td>
						<td class='$nom_clase' align='center'>$datos[comentarios]</td>
						</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";					
					}while($datos=mysql_fetch_array($rs));
					echo "</table>";
					//Regresamos 1 indicando que el proveedor si existe en la BD y se puede realizar la evaluacion
					return 1;
				}
				else{
					echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><p align='center' class='msje_correcto'>EL PROVEEDOR <u>".$_POST["txt_nombre"]."</u> NO TIENE 
					EVALUACIONES REGISTRADAS,<br/>¿EVALUAR AHORA?</p><br/>";
					//Regresamos 1 indicando que el proveedor si existe en la BD y se puede realizar la evaluacion
					return 1;
				}
			}else{
					echo "<br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><p align='center' class='msje_correcto'>EL PROVEEDOR <u>".$_POST["txt_nombre"]."</u> NO ESTA REGISTRADO EN EL SISTEMA<br/>IMPOSIBLE REALIZAR UNA EVALUACI&Oacute;N</p><br/>";
					//Regresamos 0 indicando que el proveedor no existe en la BD y por lo tanto, no se puede realizar la evaluacion
					return 0;
			}
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
?>
