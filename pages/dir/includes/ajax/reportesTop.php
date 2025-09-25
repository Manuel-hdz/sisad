<?php
	/**
	  * Nombre del Módulo: Direccion General
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 07/Septiembre/2012                                      			
	  * Descripción: Este archivo contiene la función que muestra registros previos y siguientes
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
			include("../../../../includes/func_fechas.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["rep"])){
		$tipoRep=$_GET["rep"];
		switch($tipoRep){
			case 1:
				$fechaIni=modFecha($_GET["fechaIni"],3);
				$fechaFin=modFecha($_GET["fechaFin"],3);
				$titulo="PLANOS CARGADOS DEL $_GET[fechaIni] AL $_GET[fechaFin]";
				$tabla=verReportePlanos($fechaIni,$fechaFin,$titulo);
				header("Content-type: text/xml");	
				if ($tabla!=""){
					//Remplazar el tag de apertura "menor que" por un simbolo menos usado, en este caso "¬"
					$tabla=str_replace("<","¬",$tabla);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<tabla>$tabla</tabla>
						</existe>");
				}
				else{
					//Crear XML de error
					echo utf8_encode("
					<existe>
						<valor>false</valor>
					</existe>");
				}
			break;
			case 2:
				$quincena=$_GET["quincena"];
				$titulo="CONCILIACIÓN DE LA QUINCENA $quincena";
				$tabla=verReporteConciliacion($quincena,$titulo);
				header("Content-type: text/xml");	
				if ($tabla!=""){
					//Remplazar el tag de apertura "menor que" por un simbolo menos usado, en este caso "¬"
					$tabla=str_replace("<","¬",$tabla);
					//Crear XML de la clave Generada
					echo utf8_encode("
						<existe>
							<valor>true</valor>
							<titulo>$titulo</titulo>
							<tabla>$tabla</tabla>
						</existe>");
				}
				else{
					//Crear XML de error
					echo utf8_encode("
					<existe>
						<valor>false</valor>
					</existe>");
				}
			break;
		}
	}	
	
	/*Funcion que se encarga de recopilar los datos el dibujado del Grafico*/
	function verReportePlanos($fechaIni,$fechaFin,$titulo){
		//Realizar la conexion con la BD
		$conn = conecta("bd_topografia");
		//Revisar si se han agregado fotos a la Base de Datos
		$stm_sql="SELECT id_plano, nom_plano, descripcion, fecha, hora, nom_archivo FROM planos WHERE fecha>='$fechaIni' AND fecha<='$fechaFin'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se hayan encontrado resultados
		if ($datos=mysql_fetch_array($rs)){
			$table="				
				<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta' style='color:#FFF'>$titulo</br>";
			$table.="
				<tr>
					<td class='nombres_columnas' align='center'>NOMBRE PLANO</td>
					<td class='nombres_columnas' align='center'>DESCRIPCIÓN</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>PLANO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			//Creamos la variable que permitira saber si los archivos de la BD corresponden con los del servidor
			$contArchivos=0;
			//Contador para saber el numero de revisiones que hace dentro de la carpeta seleccionada
			$contador=0;
			do{
				//Obtener la Fecha
				$fecha=modFecha($datos['fecha'],1);
				//Obtener la Hora
				$hora=substr($datos["hora"],0,5);
				$hora=str_replace(":","",$hora);
				//Carpeta donde se ubican los planos
				$carpeta="../top/documentos/".str_replace("/","",$fecha)."/".$hora."/";
				$table.="
					<tr>
						<td class='$nom_clase' align='center'>$datos[nom_plano]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>$datos[hora] $fecha</td>";

				$table.="	<td class='$nom_clase' align='center'>
							<input type='button' name='btn_archivo' id='btn_archivo' class='botones' value='Descargar' title='Ver Plano $datos[nom_plano]'
							onclick=\"location.href='$carpeta"."$datos[nom_archivo]'\" target='_blank'/>
						</td>";
												
				$table.="</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";			
			}while($datos=mysql_fetch_array($rs)); 	
			$table.="</table>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
			return $table;
		}
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
			return "";
		}
	}

	//Funcion que genera el Reporte de Conciliacion de la Quincena seleccionada
	function verReporteConciliacion($noQuincena,$titulo){
		$tabla="<p align='center' class='titulo_etiqueta' style='color:#FFF'>$titulo</p><br><br><br>";
		//Conectar a la BD de Topografia
		$conn=conecta("bd_topografia");
		//Variable contador
		$ctrl=0;
		//Ciclo para obras amortizables y de costos
		do{
			if($ctrl==0){
				//Crear sentencia SQL
				$sql_stm ="SELECT * FROM estimaciones JOIN obras ON obras_id_obra=id_obra JOIN subcategorias ON subcategorias_id=id WHERE no_quincena='$noQuincena' AND categoria='AMORTIZABLE' ORDER BY orden,tipo_obra";
				$titulo="Registro de Estimaciones de Obras Amortizables";
			}
			else{
				//Crear sentencia SQL
				$sql_stm ="SELECT * FROM estimaciones JOIN obras ON obras_id_obra=id_obra JOIN subcategorias ON subcategorias_id=id WHERE no_quincena='$noQuincena' AND categoria='COSTOS' ORDER BY orden,tipo_obra";
				$titulo="Registro de Estimaciones de Obras De Costos";
			}	
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($sql_stm);									
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos=mysql_fetch_array($rs)){
				//Desplegar los resultados de la consulta en una tabla
				$tabla.="				
				<table cellpadding='5' width='1700'>	
					<tr>
						<td colspan='10' align='center' class='titulo_etiqueta' width='100%' style='color:#FFF'>$titulo</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center' colspan='10' width='100%'>ESTIMACIONES</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center' width='21%'>CONCEPTO</td>
						<td class='nombres_columnas' align='center' width='9%'>SECCIÓN</td>
						<td class='nombres_columnas' align='center' width='9%'>UNIDAD</td>
						<td class='nombres_columnas' align='center' width='9%'>CANTIDAD</td>
						<td class='nombres_columnas' align='center' width='9%'>PRECIO/U MN</td>
						<td class='nombres_columnas' align='center' width='9%'>PRECIO/U USD</td>
						<td class='nombres_columnas' align='center' width='9%'>TASA CAMBIO</td>
						<td class='nombres_columnas' align='center' width='9%'>TOTAL MN</td>
						<td class='nombres_columnas' align='center' width='9%'>TOTAL USD</td>
						<td class='nombres_columnas' align='center' >IMPORTE TOTAL</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				//Contadores que nos permiten sumar el total de cada coumna	
				$totalMN=0;
				$totalUSD=0;
				$importe=0;
				//Esto se realiza para solo imprimir un solo encabezado del tipo de obra y enseguida del el todos registros
				$tipo_obra= $datos['tipo_obra'];
				$idSubcategoria=$datos["subcategoria"];
				$tabla.="
					<tr>
						<td class='nombres_columnas'>$datos[subcategoria]</td>
					</tr>";
				do{	
					// Mostrar los totales de cada columna para todos los registros excepto el último
					if($idSubcategoria != $datos['subcategoria']){
						$tabla.="
							<tr>
								<td class='$nom_clase' colspan='6' align='right'></td>
								<td class='nombres_columnas' align='right'>TOTALES</td>
								<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
								<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
								<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
							</tr>";
						$idSubcategoria=$datos["subcategoria"];
						$tabla.="
							<tr>
								<td class='nombres_columnas'>$datos[subcategoria]</td>
							</tr>";
						//Reiniciar los contadores para empezar la suma con el siguiente tipo de obra	
						$totalMN=0;
						$totalUSD=0;
						$importe=0;
					}
					//Mostrar todos los registros que han sido completados
					$tabla.="
						<tr>	
							<td class='$nom_clase' align='left'>$datos[nombre_obra]</td>
							<td class='$nom_clase'>$datos[seccion]</td>
							<td class='$nom_clase'>$datos[unidad]</td>					
							<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['pumn_estimacion'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['puusd_estimacion'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['t_cambio'],4,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['total_mn'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['total_usd'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['importe'],2,".",",")."</td>
						</tr>";
						//Realizar la suma por cada registro de los totales
						$totalMN += $datos['total_mn'];
						$totalUSD += $datos['total_usd'];
						$importe += $datos['importe'];
						
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";				
				}while($datos=mysql_fetch_array($rs));
				//Fin de la tabla donde se muestran los resultados de la consulta
				// Mostrar los totales de cada columna para el último registro
				$tabla.="
					<tr>
						<td class='$nom_clase' colspan='6' align='right'></td>
						<td class='nombres_columnas' align='right'>TOTALES</td>
						<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
						<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
						<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
					</tr>";
				$tabla.="</table>";
			}//Cierre if($datos=mysql_fetch_array($rs))
			//Incrementar el contador
			$ctrl++;
		}while($ctrl<=1);
		
		/*TRASPALEOS*/
		$tabla.="<table cellpadding='5' width='1900'>";
		//Esta variable Indicara si se arrojaron resultados de las Obras de Costos y Amortizable
		$status = 0;
		//Crear un ciclo de 2 Iteraciones para obtener las Obras de Amortizaciones y las de Costos		
		for($i=0;$i<2;$i++){
			//Variables para crear la sentencia y manejar el mensaje de datos no disponibles
			$sql_stm_categoria = "";
			$categoria = "";
			//Crear la Sentencia para obtener el ID de las Obras correspondientes a cada Categoria
			if($i==0){
				$categoria_msg = "OBRAS AMORTIZABLES";
				$sql_stm_categoria = "SELECT id_obra,subcategoria FROM obras JOIN traspaleos ON id_obra=obras_id_obra JOIN subcategorias ON subcategorias_id=id 
									WHERE categoria='AMORTIZABLE' AND no_quincena='$noQuincena' ORDER BY orden,id_obra";
			}
			else if($i==1){
				$categoria_msg = "OBRA DE COSTOS";
				$sql_stm_categoria = "SELECT id_obra,subcategoria FROM obras JOIN traspaleos ON id_obra=obras_id_obra JOIN subcategorias ON subcategorias_id=id 
									WHERE categoria='COSTOS' AND no_quincena='$noQuincena' ORDER BY orden,id_obra";
			}				
			//Ejecutar la Consulta
			$rs_categoria = mysql_query($sql_stm_categoria);
			//Variables para Acumular los totales de Cada Categoria
			$totalMN = 0;
			$totalUSD = 0;
			$importe = 0;
			if($idObras=mysql_fetch_array($rs_categoria)){
				//Colocar el Titulo de la Tabla solo una vez cuando existan datos para mostrar
				if($status==0){					
					$tabla.="	
						<tr>
							<td colspan='14' align='center' class='titulo_etiqueta' style='color:#FFF'>Registro de Traspaleos</td>
						</tr>
						<tr>
							<td class='nombres_columnas' align='center' colspan='14'>TRASPALEOS</td>
						</tr>";
				}
				//Incrementar la Variable de Status para Indicar que fueron Mostrardos los Datos
				$status++;
				//Colocar el Encabezados para las Obras Amortizables y Costos
				$tabla.="
				<tr>
					<td class='nombres_columnas' align='center' >$categoria_msg</td>
					<td class='nombres_columnas' align='center' >ACUMULADO</td>
					<td class='nombres_columnas' align='center' >SECCIÓN</td>
					<td class='nombres_columnas' align='center' >ÁREA</td>
					<td class='nombres_columnas' align='center' >VOLUMEN</td>
					<td class='nombres_columnas' align='center' >ORIGEN</td>
					<td class='nombres_columnas' align='center' >DESTINO</td>
					<td class='nombres_columnas' align='center' >DISTANCIA</td>
					<td class='nombres_columnas' align='center' >PRECIO/U M.N</td>
					<td class='nombres_columnas' align='center' >PRECIO/U USD</td>
					<td class='nombres_columnas' align='center' >TASA CAMBIO</td>
					<td class='nombres_columnas' align='center' >TOTAL MN</td>
					<td class='nombres_columnas' align='center' >TOTAL USD</td>
					<td class='nombres_columnas' align='center' >IMPORTE</td>
				</tr>";
				$idSubcategoria=$idObras["subcategoria"];
				$tabla.="
				<tr>
					<td class='nombres_columnas' align='center'>$idObras[subcategoria]</td>
				</tr>
				";
				//Iterar segun la cantidad de Obras registradas en cada Categoria				
				do{
					//Obtener el ID de cada Obra registrarda en cada Categoria
					$idObra = $idObras['id_obra'];
					//Crear sentencia SQL para Obtener el Traspaleo Registrado a la Obra
					$sql_stm_obra ="SELECT id_obra,nombre_obra,acumulado_quincena,seccion,area,unidad,volumen,origen,destino,distancia,pu_mn,pu_usd,t_cambio,total_mn,total_usd,importe_total 
									FROM traspaleos JOIN detalle_traspaleos ON traspaleos_id_traspaleo = id_traspaleo JOIN obras ON obras_id_obra=id_obra 
									WHERE id_obra='$idObra' AND no_quincena='$noQuincena' ORDER BY no_registro;";									
					//Ejecutar la sentencia previamente creada
					$rs_obra = mysql_query($sql_stm_obra);																								
					//Confirmar que la consulta de datos fue realizada con exito.
					if($datos_traspaleo=mysql_fetch_array($rs_obra)){												
						//Controlar el color de cada renglon
						$nom_clase = "renglon_gris";
						$cont = 1;
						if($idSubcategoria!=$idObras["subcategoria"]){
							$tabla.="
							<tr>
								<td class='nombres_columnas' align='center'>$idObras[subcategoria]</td>
							</tr>
							";
						}
						//Iterar Segun la Cantidad de Registros de Traspaleo en Cada Obra														
						do{											
							//Mostrar todos los registros que han sido completados
							$tabla.="
								<tr>	
									<td class='$nom_clase' align='left'>$datos_traspaleo[nombre_obra]</td>
									<td class='$nom_clase' align='right'>$datos_traspaleo[acumulado_quincena]</td>
									<td class='$nom_clase'>$datos_traspaleo[seccion]</td>
									<td class='$nom_clase'>$datos_traspaleo[area]</td>";
							
							//Hacer el Analisis para Clasificar el registro de Traspaleo
							if($datos_traspaleo['importe_total']>0){
								//Colocar la celda con el Color para indicar que se trata de VACIADERO
								if($datos_traspaleo['distancia']<50){
									$tabla.="<td bgcolor='#00B050' align='right' style='color:#000'>$datos_traspaleo[volumen]</td>";
								}
								//Colocar la celda con el Color para indicar que se trata de APLANILLE
								else if($datos_traspaleo['destino']=="APLANILLE"){
									$tabla.="<td bgcolor='#948B54' align='right' style='color:#000'>$datos_traspaleo[volumen]</td>";
								}
								//Colocar la celda con el Color de acuerdo a la distancia
								else{
									$color = obtenerColorDistancia($datos_traspaleo['distancia'],$datos_traspaleo['id_obra']);
									$tabla.="<td bgcolor='#$color' align='right' style='color:#000'>$datos_traspaleo[volumen]</td>";
								}
							}
							else//Colocar la celda del volumen sin fondo
								$tabla.="<td class='$nom_clase' align='right'>$datos_traspaleo[volumen]</td>";
																																							
							$tabla.="	
									<td class='$nom_clase'>$datos_traspaleo[origen]</td>
									<td class='$nom_clase'>$datos_traspaleo[destino]</td>
									<td class='$nom_clase'>$datos_traspaleo[distancia]</td>
									<td class='$nom_clase' align='right'>$   ".number_format($datos_traspaleo['pu_mn'],2,".",",")."</td>
									<td class='$nom_clase' align='right'>$   ".number_format($datos_traspaleo['pu_usd'],2,".",",")."</td>
									<td class='$nom_clase' align='right'>$   ".number_format($datos_traspaleo['t_cambio'],4,".",",")."</td>
									<td class='$nom_clase' align='right'>$   ".number_format($datos_traspaleo['total_mn'],2,".",",")."</td>
									<td class='$nom_clase' align='right'>$   ".number_format($datos_traspaleo['total_usd'],2,".",",")."</td>
									<td class='$nom_clase' align='right'>$   ".number_format($datos_traspaleo['importe_total'],2,".",",")."</td>
								</tr>";
							//Realizar la suma por cada registro de los totales
							$totalMN += $datos_traspaleo['total_mn'];
							$totalUSD += $datos_traspaleo['total_usd'];
							$importe += $datos_traspaleo['importe_total'];
					
							//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";				
						}while($datos_traspaleo=mysql_fetch_array($rs_obra));
						//Colocar un Renglon Para Separar los Registros de Cada Obra
						$tabla.="<tr><td colspan='14'></td></tr>";						
					}//Cierre if($datos_traspaleo=mysql_fetch_array($rs_obra))
					$idSubcategoria=$idObras["subcategoria"];
				}while($idObras=mysql_fetch_array($rs_categoria));
				//Mostrar los totales de las obras registrardas en cada Categoria
				$tabla.="
				<tr>
					<td class='$nom_clase' colspan='10' align='right'></td>
					<td class='nombres_columnas' align='right'>TOTALES</td>
					<td class='nombres_columnas' align='right'>$   ".number_format($totalMN,2,".",",")."</td>
					<td class='nombres_columnas' align='right'>$   ".number_format($totalUSD,2,".",",")."</td>
					<td class='nombres_columnas' align='right'>$   ".number_format($importe,2,".",",")."</td>
				</tr>";
				//Colocar un Espacio entre la Tabla que muestra la Obras Amortizables y las Obras de Costos
				$tabla.="
				<tr><td colspan='14'></td></tr>
				<tr><td colspan='14'></td></tr>
				<tr><td colspan='14'></td></tr>";
			}//Cierre if($idObras=mysql_fetch_array($rs_categoria))
		}//Cierre for($i=0;$i<2;$i++)
		$tabla.="</table>";
		
		mysql_close($conn);
		return $tabla;
	}
	
	/*Obtener el Color correspondiente al Intervalo en la lista de precios Asociada a las Obras de acuerdo a la Distancia*/
	function obtenerColorDistancia($distancia,$idObra){
		//Crear la Sentencia para obtener el color correspondiente al rango de precios asociado a la Obra indicada
		$sql_stm = "SELECT color FROM (lista_precios JOIN precios_traspaleo ON precios_traspaleo_id_precios=id_precios) JOIN obras ON id_precios=obras.precios_traspaleo_id_precios
					WHERE id_obra = '$idObra' AND $distancia>=distancia_inicio AND $distancia<=distancia_fin";
		//Ejecutar la Setencia
		$rs = mysql_query($sql_stm);
		
		//Extraer los datos y retornar el valor encontrado, de lo contrario regresar vacio
		if($datos=mysql_fetch_array($rs))
			return $datos['color'];		
		else
			return "";
	}//Cierre de la funcion obtenerColorDistancia($distancia,$idObra)
?>