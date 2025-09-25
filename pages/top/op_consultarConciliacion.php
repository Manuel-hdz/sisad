<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 02/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de consultar conciliacion en la BD
	**/
	
	
	/*Esta funcion obtiene las quincenas disponibles en las Estimaciones y Traspaleos registrados en la BD*/
	function obtenerQuincenas(){
		//Conectarse con la BD de topografia
		$conn = conecta("bd_topografia");
		
		//Esta variable indica si hubo resultados de Estimaciones y Traspaleo
		$status = 0;
		
		//Arreglo para almacenar las quincenas de traspaleo y de estimaciones
		$quincenasDisponibles = array("estimaciones"=>array("numeros"=>array(),"meses"=>array(),"anios"=>array()), 
									  "traspaleos"=>array("numeros"=>array(),"meses"=>array(),"anios"=>array()));
		
		//Ejecutar la consulta para obtener los de las Estimaciones
		$rs_datos_estimaciones = mysql_query("SELECT DISTINCT no_quincena FROM estimaciones");
		if($datos=mysql_fetch_array($rs_datos_estimaciones)){
			do{
				//Separar la Quincena en No., Mes y Año
				$partesQuincena = split(" ",$datos['no_quincena']);
				//Guardar cada uno de los datos de la Quincena por separado
				$quincenasDisponibles['estimaciones']['numeros'][] = $partesQuincena[0];
				$quincenasDisponibles['estimaciones']['meses'][] = $partesQuincena[1];
				$quincenasDisponibles['estimaciones']['anios'][] = $partesQuincena[2];
			}while($datos=mysql_fetch_array($rs_datos_estimaciones));
		}
		else
			$status = 1;
		
						
		//Ejecutar la consulta para obtener los de las Estimaciones
		$rs_datos_traspaleo = mysql_query("SELECT DISTINCT no_quincena FROM traspaleos");
		if($datos=mysql_fetch_array($rs_datos_traspaleo)){
			
			//Regresar el valor de la variable, en el caso de que no haya registros de Estimacion, pero si de Traspaleo
			$status = 0;
			
			do{
				//Separar la Quincena en No., Mes y Año
				$partesQuincena = split(" ",$datos['no_quincena']);
				//Guardar cada uno de los datos de la Quincena por separado
				$quincenasDisponibles['traspaleos']['numeros'][] = $partesQuincena[0];
				$quincenasDisponibles['traspaleos']['meses'][] = $partesQuincena[1];
				$quincenasDisponibles['traspaleos']['anios'][] = $partesQuincena[2];
			}while($datos=mysql_fetch_array($rs_datos_traspaleo));
		}
		else
			$status = 1;
		
		
		//Cerrar la Conexion con la BD
		mysql_close($conn);
		
		
		//Regresar los datos encontrados o en su defecto los datos disponibles.
		if($status==0){
			//Combinar y Quitar valores repetidos de los arreglos finales con los Datos de las Quincenas
			$finalNums = array_unique(array_merge($quincenasDisponibles['estimaciones']['numeros'],$quincenasDisponibles['traspaleos']['numeros']));		
			$finalMeses = array_unique(array_merge($quincenasDisponibles['estimaciones']['meses'],$quincenasDisponibles['traspaleos']['meses']));		
			$finalAnios = array_unique(array_merge($quincenasDisponibles['estimaciones']['anios'],$quincenasDisponibles['traspaleos']['anios']));					
			
			//Ordenar los meses antes de guardarlos en el Arreglo final
			sort($finalNums);
			$finalMeses = ordenarMeses($finalMeses);
			sort($finalAnios);
			
			
			//Integrar los datos en un solo arreglo
			$finalQuincenas = array("numeros"=>$finalNums,"meses"=>$finalMeses,"anios"=>$finalAnios);		
			
			return $finalQuincenas;			
		}
		else
			return 0;
	}//Cierre de la Funcion obtenerQuincenas()
	
	
	
	//Funcion que se encarga de desplegar las estimaciones en las conciliaciones en el rango de fechas
	function mostrarConciliacionEstim(){
		//Variable para controlar los titulos de las tablas
		$band="";
		
		//Arreglo que permitira llevar la consulta y el mensaje al frm_consultarConciliacion para de ahi mandarlos por el boton exportar a excel a guardar_reporte	
		$arreglo_est = array();

		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");		
		
		//Obtener los datos de la Quincena del POST
		$noQuincena = $_POST['cmb_noQuincena']." ".$_POST['cmb_mes']." ".$_POST['cmb_anio'];
		
		//Variable contador
		$ctrl=0;
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		echo $msg = "<p align='center' class='titulo_etiqueta'>Conciliaci&oacute;n de la Quincena <em><u>$noQuincena</u></em></p>";
		
		do{
		
			if($ctrl==0){
				//Crear sentencia SQL
				$sql_stm ="SELECT * FROM estimaciones JOIN obras ON obras_id_obra=id_obra JOIN subcategorias ON subcategorias_id=id WHERE no_quincena='$noQuincena' AND categoria='AMORTIZABLE' ORDER BY orden,tipo_obra";
				$titulo="Registro de Estimaciones de Obras Amortizables en la Quincena <em><u>$noQuincena</u></em>";
			}
			else{
				//Crear sentencia SQL
				$sql_stm ="SELECT * FROM estimaciones JOIN obras ON obras_id_obra=id_obra JOIN subcategorias ON subcategorias_id=id WHERE no_quincena='$noQuincena' AND categoria='COSTOS' ORDER BY orden,tipo_obra";
				$titulo="Registro de Estimaciones de Obras De Costos en la Quincena <em><u>$noQuincena</u></em>";
			}	
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro de Estimaci&oacute;n en la Quincena <em><u>$noQuincena</u></em> para <em><u>$titulo</u></em></label>";
			
			
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($sql_stm);									
										
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos=mysql_fetch_array($rs)){
		
				//Desplegar los resultados de la consulta en una tabla
				echo "				
				<table cellpadding='5' width='1700'>	
					<tr>
						<td colspan='10' align='center' class='titulo_etiqueta' width='100%'>$titulo</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center' colspan='10' width='100%'>ESTIMACIONES</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center' width='21%'>CONCEPTO</td>
						<td class='nombres_columnas' align='center' width='9%'>SECCI&Oacute;N</td>
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
				echo "
					<tr>
						<td class='nombres_columnas'>$datos[subcategoria]</td>
					</tr>";
				do{	
					// Mostrar los totales de cada columna para todos los registros excepto el último
					if($idSubcategoria != $datos['subcategoria']){
						echo"
							<tr>
								<td class='$nom_clase' colspan='6' align='right'></td>
								<td class='nombres_columnas' align='right'>TOTALES</td>
								<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
								<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
								<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
							</tr>";
						$idSubcategoria=$datos["subcategoria"];
						echo "
							<tr>
								<td class='nombres_columnas'>$datos[subcategoria]</td>
							</tr>";
						//Reiniciar los contadores para empezar la suma con el siguiente tipo de obra	
						$totalMN=0;
						$totalUSD=0;
						$importe=0;
					}	
	
					//Mostrar todos los registros que han sido completados
					echo "
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
				echo"
					<tr>
						<td class='$nom_clase' colspan='6' align='right'></td>
						<td class='nombres_columnas' align='right'>TOTALES</td>
						<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
						<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
						<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
					</tr>";
				echo "</table>";
				echo "<br><br><br><br><br>";
				//regresar la informacion precargada en el arreglo;
				$arreglo_est[] = $noQuincena;
				$arreglo_est[] = $msg;			
			}//Cierre if($datos=mysql_fetch_array($rs))
			else{
				if($ctrl==0)
					$band=$msg_error;
				else
					//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
					echo $msg_error;		
			}
			//Incrementar el contador
			$ctrl++;
		}while($ctrl<=1);
		if($band!="")
			echo $band;
		return $arreglo_est;
	}//Cierre de la funcion mostrarConciliacionEstim()
	
	
	//Funcion que se encarga de desplegar los traspaleos en conciliaciones en el rango de fechas
	function mostrarConciliacionTrasp(){
		$band="";
		//Arreglo que permitira llevar la consulta y el mensaje al frm_consultarConciliacion para de ahi mandarlos por el boton exportar a excel a guardar_reporte	
		$arreglo_trasp = array();

		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		
		//Obtener los datos de la Quincena
		$noQuincena = $_POST['cmb_noQuincena']." ".$_POST['cmb_mes']." ".$_POST['cmb_anio'];
		
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Registros de Traspaleo en la Quincena <em><u>$noQuincena</u></em>";
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro de Traspaleo en la Quincena <em><u>$noQuincena</u></em> Para las ";						
		
		
		//Esta variable Indicara si se arrojaron resultados de las Obras de Costos y Amortizable
		$status = 0;
		
		//Declarar la Tabla que contendra los datos de Traspaleo
		echo "<br><br><br><br>				
			  <table cellpadding='5' width='1900'>";
				
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
					echo "	
						<tr>
							<td colspan='14' align='center' class='titulo_etiqueta'>$msg</td>
						</tr>
						<tr>
							<td class='nombres_columnas' align='center' colspan='14'>TRASPALEOS</td>
						</tr>";
				}
			
				//Incrementar la Variable de Status para Indicar que fueron Mostrardos los Datos
				$status++;
				
				//Colocar el Encabezados para las Obras Amortizables y Costos
				echo "
				<tr>
					<td class='nombres_columnas' align='center' >$categoria_msg</td>
					<td class='nombres_columnas' align='center' >ACUMULADO</td>
					<td class='nombres_columnas' align='center' >SECCI&Oacute;N</td>
					<td class='nombres_columnas' align='center' >&Aacute;REA</td>
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
				echo "
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
							echo "
							<tr>
								<td class='nombres_columnas' align='center'>$idObras[subcategoria]</td>
							</tr>
							";
						}
						//Iterar Segun la Cantidad de Registros de Traspaleo en Cada Obra														
						do{											
							//Mostrar todos los registros que han sido completados
							echo "
								<tr>	
									<td class='$nom_clase' align='left'>$datos_traspaleo[nombre_obra]</td>
									<td class='$nom_clase' align='right'>$datos_traspaleo[acumulado_quincena]</td>
									<td class='$nom_clase'>$datos_traspaleo[seccion]</td>
									<td class='$nom_clase'>$datos_traspaleo[area]</td>";
							
							//Hacer el Analisis para Clasificar el registro de Traspaleo
							if($datos_traspaleo['importe_total']>0){
								//Colocar la celda con el Color para indicar que se trata de VACIADERO
								if($datos_traspaleo['distancia']<50){
									echo "<td bgcolor='#00B050' align='right'>$datos_traspaleo[volumen]</td>";
								}
								//Colocar la celda con el Color para indicar que se trata de APLANILLE
								else if($datos_traspaleo['destino']=="APLANILLE"){
									echo "<td bgcolor='#948B54' align='right'>$datos_traspaleo[volumen]</td>";
								}
								//Colocar la celda con el Color de acuerdo a la distancia
								else{
									$color = obtenerColorDistancia($datos_traspaleo['distancia'],$datos_traspaleo['id_obra']);
									echo "<td bgcolor='#$color' align='right'>$datos_traspaleo[volumen]</td>";
								}
							}
							else//Colocar la celda del volumen sin fondo
								echo "<td class='$nom_clase' align='right'>$datos_traspaleo[volumen]</td>";
																																							
							echo "	
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
						echo "<tr><td colspan='14'>&nbsp;</td></tr>";						
					}//Cierre if($datos_traspaleo=mysql_fetch_array($rs_obra))
					$idSubcategoria=$idObras["subcategoria"];
				}while($idObras=mysql_fetch_array($rs_categoria));
				//Mostrar los totales de las obras registrardas en cada Categoria
				echo"
				<tr>
					<td class='$nom_clase' colspan='10' align='right'></td>
					<td class='nombres_columnas' align='right'>TOTALES</td>
					<td class='nombres_columnas' align='right'>$   ".number_format($totalMN,2,".",",")."</td>
					<td class='nombres_columnas' align='right'>$   ".number_format($totalUSD,2,".",",")."</td>
					<td class='nombres_columnas' align='right'>$   ".number_format($importe,2,".",",")."</td>
				</tr>";
				//Colocar un Espacio entre la Tabla que muestra la Obras Amortizables y las Obras de Costos
				echo"
				<tr><td colspan='14'>&nbsp;</td></tr>
				<tr><td colspan='14'>&nbsp;</td></tr>
				<tr><td colspan='14'>&nbsp;</td></tr>";
			}//Cierre if($idObras=mysql_fetch_array($rs_categoria))
			else{
				$band.="$msg_error $categoria_msg</label><br><br><br><br>";
			}
		}//Cierre for($i=0;$i<2;$i++)
		echo "</table>";
		echo $band;
		
		//Si se mostraron resultados al menos de una categoria, devolver el No. de Quincena y el Mensaje
		if($status>0){
			//regresar la informacion precargada en el arreglo;
			$arreglo_trasp[] = $noQuincena;
			$arreglo_trasp[] = $msg;			
			return $arreglo_trasp;
		}
		else{
			return $arreglo_trasp;
		}
	}//Cierre de la funcion mostrarConciliacionTrasp()
	
	
	
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
	
	function mostrarConciliacionEquipo(){
		//Arreglo que permitira llevar la consulta y el mensaje al frm_consultarConciliacion para de ahi mandarlos por el boton exportar a excel a guardar_reporte	
		$arreglo_equipo = array();
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");		
		//Obtener los datos de la Quincena del POST
		$noQuincena = $_POST['cmb_noQuincena']." ".$_POST['cmb_mes']." ".$_POST['cmb_anio'];
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM bitacora_eq_pesado JOIN equipo_pesado ON id_registro=equipo_pesado_id_registro JOIN detalle_eq_pesado ON bitacora_eq_pesado_idbitacora=idbitacora WHERE no_quincena='$noQuincena' ORDER BY concepto";
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg = "Maquinaria Pesada de la Quincena <em><u>$noQuincena</u></em>";
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro de Equipo Pesado en la Quincena <em><u>$noQuincena</u></em></label>";										
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='1700'>	
				<tr>
					<td colspan='10' align='center' class='titulo_etiqueta' width='100%'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' colspan='10' width='100%'>REGISTROS DE MAQUINARIA PESADA</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center' width='21%'>CONCEPTO</td>
					<td class='nombres_columnas' align='center' width='9%'>EQUIPO</td>
					<td class='nombres_columnas' align='center' width='9%'>UNIDAD</td>
					<td class='nombres_columnas' align='center' width='9%'>CANTIDAD</td>
					<td class='nombres_columnas' align='center' width='9%'>P.U. M.N.</td>
					<td class='nombres_columnas' align='center' width='9%'>P.U. USD</td>
					<td class='nombres_columnas' align='center' width='9%'>TASA CAMBIO</td>
					<td class='nombres_columnas' align='center' width='9%'>TOTAL MN</td>
					<td class='nombres_columnas' align='center' width='9%'>TOTAL USD</td>
					<td class='nombres_columnas' align='center' >IMPORTE TOTAL M.N.</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Contadores que nos permiten sumar el total de cada coumna	
			$totalMN=0;
			$totalUSD=0;
			$importe=0;
			//Esto se realiza para solo imprimir un solo encabezado del tipo de obra y enseguida del el todos registros
			$tipo_obra= $datos['fam_equipo'];
			echo "
				<tr>
					<td class='nombres_columnas'>$datos[fam_equipo]</td>
				</tr>";
			do{	
				// Mostrar los totales de cada columna para todos los registros excepto el último
				if($tipo_obra != $datos['fam_equipo']){
					echo"
						<tr>
							<td class='$nom_clase' colspan='6' align='right'></td>
							<td class='nombres_columnas' align='right'>TOTALES</td>
							<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
							<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
							<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
						</tr>";
					$tipo_obra = $datos['fam_equipo'];
					echo "
						<tr>
							<td class='nombres_columnas'>$datos[fam_equipo]</td>
						</tr>";
					//Reiniciar los contadores para empezar la suma con el siguiente tipo de obra	
					$totalMN=0;
					$totalUSD=0;
					$importe=0;
				}
				$subtotalMN=$datos['cantidad']*$datos['pumn_estimacion'];
				$subtotalUSD=$datos['cantidad']*$datos['puusd_estimacion']*$datos['t_cambio'];
				$subtotal=$subtotalMN+$subtotalUSD;
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>	
						<td class='$nom_clase' align='left'>$datos[concepto]</td>
						<td class='$nom_clase'>$datos[id_equipo]</td>
						<td class='$nom_clase'>$datos[unidad]</td>					
						<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['pumn_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['puusd_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['t_cambio'],4,".",",")."</td>
						<td class='$nom_clase'>$".number_format($subtotalMN,2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($subtotalUSD,2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($subtotal,2,".",",")."</td>
					</tr>";
					//Realizar la suma por cada registro de los totales
					$totalMN += $subtotalMN;
					$totalUSD += $subtotalUSD;
					$importe += $subtotal;
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			// Mostrar los totales de cada columna para el último registro
			echo"
				<tr>
					<td class='$nom_clase' colspan='6' align='right'></td>
					<td class='nombres_columnas' align='right'>TOTALES</td>
					<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
				</tr>";
			echo "</table>";
			//regresar la informacion precargada en el arreglo;
			$arreglo_equipo[] = $sql_stm;
			$arreglo_equipo[] = $msg;			
			return $arreglo_equipo;
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return $arreglo_equipo;//Regresar el arreglo vacio
		}
	}
?>