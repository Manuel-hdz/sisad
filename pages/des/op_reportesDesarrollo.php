<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 30/Diciembre/2011
	  * Descripción: Este archivo contiene funciones para generar y mostrar los Reportes hechos en Desarrollo
	**/
	
	//Funcion que muestra los registros de Rezagado
	function mostrarRezagado(){
		$res="false";
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Realizar la conexion a la BD de Desarollo
		$conn = conecta("bd_desarrollo");	
		//Escribimos la consulta a realizarse 
		/*$stm_sql = "SELECT DISTINCT rezagado.bitacora_avance_id_bitacora,fecha,turno,puesto,nombre,obra,machote,medida,avance,tep_origen,tep_destino,tep_cuch,min_origen,min_destino,min_cuch,
					id_equipo,horo_ini,horo_fin,horas_totales,rezagado.observaciones AS obsRez, bitacora_avance.observaciones AS obsAva 
					FROM rezagado JOIN personal ON rezagado.bitacora_avance_id_bitacora=personal.bitacora_avance_id_bitacora 
					JOIN equipo ON rezagado.bitacora_avance_id_bitacora=equipo.bitacora_avance_id_bitacora JOIN bitacora_avance ON rezagado.bitacora_avance_id_bitacora=id_bitacora 
					JOIN catalogo_ubicaciones ON catalogo_ubicaciones_id_ubicacion=id_ubicacion
					WHERE equipo.area='SCOOP' AND personal.area='SCOOP' AND fecha BETWEEN '$fechaI' AND '$fechaF'";*/
		$stm_sql = "SELECT T3.bitacora_avance_id_bitacora, T3.fecha, T3.turno, T2.puesto, 
					CONCAT( T7.nombre,  ' ', T7.ape_pat,  ' ', T7.ape_mat ) AS nombre_emp, 
					T6.id_equipo, T6.horo_ini, T6.horo_fin, T6.horas_totales, T4.obra, T1.machote, 
					T1.medida, T1.avance, T3.origen, T3.destino, T3.cuch, T3.traspaleo, T3.tope_limpio, 
					T3.observaciones AS obsRez, T1.observaciones AS obsAva
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					JOIN rezagado AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
					JOIN catalogo_ubicaciones AS T4 ON T1.catalogo_ubicaciones_id_ubicacion = T4.id_ubicacion
					JOIN catalogo_salarios AS T5 ON T2.puesto = T5.puesto
					AND T2.area = T5.area
					JOIN equipo AS T6 ON T6.bitacora_avance_id_bitacora = T1.id_bitacora
					JOIN bd_recursos.empleados AS T7 ON T7.id_empleados_empresa = T2.nombre
					WHERE T6.area =  'SCOOP'
					AND T2.area =  'SCOOP'
					AND T2.puesto =  'OPERADOR'
					AND T3.fecha
					BETWEEN  '$fechaI'
					AND  '$fechaF'";
		
		$msje="Registros de Rezagado del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "	
				<br>		
				<table cellpadding='5' width='1200'>   
				<caption class='titulo_etiqueta'>$msje</caption>
				<thead>
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>FECHA</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>TURNO</th>
				        <th class='nombres_columnas' align='center' rowspan='2'>PUESTO</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>EQUIPO</th>
						<th class='nombres_columnas' align='center' colspan='3'>HOROMETRO</th>
						<th class='nombres_columnas' align='center' colspan='4'>AVANCE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>ORIGEN</th>
						<th class='nombres_columnas' align='center' rowspan='2'>DESTINO</th>
						<th class='nombres_columnas' align='center' rowspan='2'>NO.<br>CUCHARONES</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TRASPALEO</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TOPE LIMPIO</th>
						<th class='nombres_columnas' align='center' colspan='2'>OBSERVACIONES</th>
						<th class='nombres_columnas' align='center' rowspan='2'>CONSUMOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>FALLAS</th>
      				</tr>
					<tr>
						<th class='nombres_columnas' align='center'>INICIAL</th>
        				<th class='nombres_columnas' align='center'>FINAL</th>
						<th class='nombres_columnas' align='center'>HORAS<br>TOTALES</th>
						
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
						
						<th class='nombres_columnas' align='center'>REZAGADO</th>
        				<th class='nombres_columnas' align='center'>AVANCE</th>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				echo "	<tr>
						<td class='nombres_filas' align='center'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='left'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_emp]</td>
						<td class='$nom_clase' align='center'>$datos[id_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[horo_ini]</td>
						<td class='$nom_clase' align='center'>$datos[horo_fin]</td>
						<td class='$nom_clase' align='center'>$datos[horas_totales]</td>
						
						<td class='$nom_clase' align='center'>$datos[obra]</td>
						<td class='$nom_clase' align='center'>$datos[machote]</td>
						<td class='$nom_clase' align='center'>$datos[medida]</td>
						<td class='$nom_clase' align='center'>$datos[avance]</td>
						
						<td class='$nom_clase' align='center'>$datos[origen]</td>
						<td class='$nom_clase' align='center'>$datos[destino]</td>
						<td class='$nom_clase' align='center'>$datos[cuch]</td>";
				if($datos["traspaleo"] == 0){
					echo "	<td class='$nom_clase' align='center'></td>";
				} else {
					echo "	<td class='$nom_clase' align='center'>X</td>";
				}
				if($datos["tope_limpio"] == 0){
					echo "	<td class='$nom_clase' align='center'></td>";
				} else {
					echo "	<td class='$nom_clase' align='center'>X</td>";
				}
				echo "	<td class='$nom_clase' align='center'>$datos[obsRez]</td>
						<td class='$nom_clase' align='center'>$datos[obsAva]</td>
						";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verConsumos<?php echo $cont;?>" id="btn_verConsumos<?php echo $cont;?>" class="botones" value="Ver Consumos" onMouseOver="window.estatus='';return true" title="Ver Consumos Registrados" 
							onClick="javascript:window.open('verRegistroConsumos.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=REZAGADO&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>						
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFallas<?php echo $cont;?>" id="btn_verFallas<?php echo $cont;?>" class="botones" value="Ver Fallas" onMouseOver="window.estatus='';return true" title="Ver Fallas Registradas" 
							onClick="javascript:window.open('verRegistroFallas.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=REZAGADO&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>				
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";
			$stm_sql2 = "SELECT CONCAT( T7.nombre,  ' ', T7.ape_pat,  ' ', T7.ape_mat ) AS nombre_emp, T2.nombre,
						T2.puesto, T3.fecha, T4.obra, T5.sueldo_base, T3.cuch, T3.tope_limpio, T3.origen, T3.destino, T3.traspaleo, T3.observaciones
						FROM bitacora_avance AS T1
						JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
						JOIN rezagado AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
						JOIN catalogo_ubicaciones AS T4 ON T1.catalogo_ubicaciones_id_ubicacion = T4.id_ubicacion
						JOIN catalogo_salarios AS T5 ON T2.puesto = T5.puesto
						AND T2.area = T5.area
						JOIN equipo AS T6 ON T6.bitacora_avance_id_bitacora = T1.id_bitacora
						JOIN bd_recursos.empleados AS T7 ON T7.id_empleados_empresa = T2.nombre
						WHERE T6.area =  'SCOOP'
						AND T2.area =  'SCOOP'
						AND T2.puesto =  'OPERADOR'
						AND T3.fecha
						BETWEEN  '$fechaI'
						AND  '$fechaF'
						ORDER BY T2.nombre, T3.fecha";
			$res=$stm_sql."<br>".$msje."<br>".$stm_sql2."<br>".$fechaI."<br>".$fechaF;		
		}
		else{
			//Si no hay registros en la Bitacora, se indica esto al usuario
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><label class='msje_correcto'>No hay Registros de Rezagado en las Fechas Introducidas</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		return $res;
	}//Fin de la Funcion de mostrarRezagado
	
	//Funcion que muestra los registros de Equipo Utilitario
	function mostrarUtilitario(){
		$res="false";
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Realizar la conexion a la BD de Desarollo
		$conn = conecta("bd_desarrollo");	
		//Escribimos la consulta a realizarse 
		$stm_sql = "SELECT id_bitacora,fecha,turno,puesto,nombre,id_equipo,horo_ini,horo_fin,horas_totales,lugar_amacizado,lugar_balastreado,limpia_acequia,observaciones 
					FROM bitacora_retro_bull JOIN personal ON id_bitacora=personal.bitacora_retro_bull_id_bitacora JOIN equipo ON id_bitacora=equipo.bitacora_retro_bull_id_bitacora 
					WHERE equipo.area='RETRO-BULL' AND personal.area='RETRO-BULL' AND fecha BETWEEN '$fechaI' AND '$fechaF'";
		$msje="Registros de Equipo Utilitario del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "	
				<br>		
				<table cellpadding='5' width='1200'>   
				<caption class='titulo_etiqueta'>$msje</caption>    			
				<thead>
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>FECHA</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>TURNO</th>
				        <th class='nombres_columnas' align='center' rowspan='2'>PUESTO</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>EQUIPO</th>
						<th class='nombres_columnas' align='center' colspan='3'>HOROMETRO</th>
						<th class='nombres_columnas' align='center' colspan='3'>TEPETATE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>OBSERVACIONES</th>
						<th class='nombres_columnas' align='center' rowspan='2'>CONSUMOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>FALLAS</th>
      				</tr>
					<tr>
						<th class='nombres_columnas' align='center'>INICIAL</th>
        				<th class='nombres_columnas' align='center'>FINAL</th>
						<th class='nombres_columnas' align='center'>HORAS<br>TOTALES</th>
						<th class='nombres_columnas' align='center'>LUGAR AMACIZADO</th>
        				<th class='nombres_columnas' align='center'>LUGAR BALASTREADO</th>
						<th class='nombres_columnas' align='center'>LIMPIA ACEQUIA</th>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				echo "	<tr>
						<td class='nombres_filas' align='center'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='left'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>
						<td class='$nom_clase' align='center'>$datos[id_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[horo_ini]</td>
						<td class='$nom_clase' align='center'>$datos[horo_fin]</td>
						<td class='$nom_clase' align='center'>$datos[horas_totales]</td>
						<td class='$nom_clase' align='center'>$datos[lugar_amacizado]</td>
						<td class='$nom_clase' align='center'>$datos[lugar_balastreado]</td>
						<td class='$nom_clase' align='center'>$datos[limpia_acequia]</td>
						<td class='$nom_clase' align='center'>$datos[observaciones]</td>
						";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verConsumos<?php echo $cont;?>" id="btn_verConsumos<?php echo $cont;?>" class="botones" value="Ver Consumos" onMouseOver="window.estatus='';return true" title="Ver Consumos Registrados" 
							onClick="javascript:window.open('verRegistroConsumos.php?id_bitacora=<?php echo $datos['id_bitacora'];?>&tipoReg=RETRO-BULL&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>						
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFallas<?php echo $cont;?>" id="btn_verFallas<?php echo $cont;?>" class="botones" value="Ver Fallas" onMouseOver="window.estatus='';return true" title="Ver Fallas Registradas" 
							onClick="javascript:window.open('verRegistroFallas.php?id_bitacora=<?php echo $datos['id_bitacora'];?>&tipoReg=RETRO-BULL&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>				
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";	
			$res=$stm_sql."<br>".$msje;		
		}
		else{
			//Si no hay registros en la Bitacora, se indica esto al usuario
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><label class='msje_correcto'>No hay Registros de Equipo Utilitario en las Fechas Introducidas</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		return $res;
	}//Fin de la Funcion de mostrarUtilitario
	
	//Funcion que muestra los registros de Barrenacion con Jumbo
	function mostrarBarrenacionJum(){
		$res="false";
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Realizar la conexion a la BD de Desarollo
		$conn = conecta("bd_desarrollo");	
		//Escribimos la consulta a realizarse 
		$stm_sql = "SELECT T3.bitacora_avance_id_bitacora, T3.fecha, T3.turno, T2.puesto, 
					CONCAT( T7.nombre,  ' ', T7.ape_pat,  ' ', T7.ape_mat ) AS nombre_emp, 
					T4.obra, T1.machote, T1.medida, T1.avance, T6.id_equipo, T6.horo_ini, 
					T6.horo_fin, T6.horas_totales, T3.`barrenos_dados` , T3.barrenos_disp, 
					T3.barrenos_long, T3.reanclaje, T3.coples, T3.zancos, T3.anclas, T3.escareado, 
					T3.topes_barrenados, T8.desborde, T8.encapille, T8.despate, 
					T3.observaciones AS obsJu, T1.observaciones AS obsAva
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					JOIN barrenacion_jumbo AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
					JOIN catalogo_ubicaciones AS T4 ON T1.catalogo_ubicaciones_id_ubicacion = T4.id_ubicacion
					JOIN catalogo_salarios AS T5 ON T2.puesto = T5.puesto
					AND T2.area = T5.area
					JOIN equipo AS T6 ON T6.bitacora_avance_id_bitacora = T1.id_bitacora
					JOIN bd_recursos.empleados AS T7 ON T7.id_empleados_empresa = T2.nombre
					JOIN barrenos AS T8 ON T8.bitacora_avance_id_bitacora= T1.id_bitacora 
					WHERE T6.area =  'JUMBO'
					AND T2.area =  'JUMBO'
					AND T3.fecha
					BETWEEN  '$fechaI'
					AND  '$fechaF'";
					
		$msje="Registros de Barrenaci&oacute;n con Jumbo del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "	
				<br>		
				<table cellpadding='5' width='1200'>   
				<caption class='titulo_etiqueta'>$msje</caption>    			
				<thead>
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>FECHA</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>TURNO</th>
				        <th class='nombres_columnas' align='center' rowspan='2'>PUESTO</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
						<th class='nombres_columnas' align='center' colspan='4'>AVANCE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>EQUIPO</th>
						<th class='nombres_columnas' align='center' colspan='3'>HOROMETRO</th>
						<th class='nombres_columnas' align='center' colspan='3'>BRAZO 1</th>
						<th class='nombres_columnas' align='center' colspan='3'>BRAZO 2</th>
						<th class='nombres_columnas' align='center' colspan='7'>BARRENOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TOPES BARRENADOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>REANCLAJE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>COPLES</th>
						<th class='nombres_columnas' align='center' rowspan='2'>ZANCOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>ANCLAS</th>
						<th class='nombres_columnas' align='center' colspan='2'>OBSERVACIONES</th>
						<th class='nombres_columnas' align='center' rowspan='2'>CONSUMOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>FALLAS</th>
      				</tr>
					<tr>
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
						<th class='nombres_columnas' align='center'>INICIAL</th>
        				<th class='nombres_columnas' align='center'>FINAL</th>
						<th class='nombres_columnas' align='center'>HORAS<br>TOTALES</th>
						<th class='nombres_columnas' align='center'>INICIAL</th>
        				<th class='nombres_columnas' align='center'>FINAL</th>
						<th class='nombres_columnas' align='center'>HORAS<br>TOTALES</th>
						<th class='nombres_columnas' align='center'>INICIAL</th>
        				<th class='nombres_columnas' align='center'>FINAL</th>
						<th class='nombres_columnas' align='center'>HORAS<br>TOTALES</th>
						<th class='nombres_columnas' align='center'>DADOS</th>
        				<th class='nombres_columnas' align='center'>DISPARADOS</th>
						<th class='nombres_columnas' align='center'>LONGITUD</th>
						<th class='nombres_columnas' align='center'>DESBORDE</th>
						<th class='nombres_columnas' align='center'>ENCAPILLE</th>
						<th class='nombres_columnas' align='center'>DESPATE</th>
						<th class='nombres_columnas' align='center'>ESCAREADO</th>
						<th class='nombres_columnas' align='center'>BARRENACI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{
				$brazo1HI=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horo_ini","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",1);
				if($brazo1HI==""){
					$brazo1HI=0;
					$brazo1HF=0;
					$brazo1HT=0;
				}
				else{
					$brazo1HF=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horo_fin","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",1);
					$brazo1HT=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horas_totales","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",1);
				}
				
				$brazo2HI=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horo_ini","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",2);
				if($brazo2HI==""){
					$brazo2HI=0;
					$brazo2HF=0;
					$brazo2HT=0;
				}
				else{
					$brazo2HF=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horo_fin","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",2);
					$brazo2HT=obtenerDatoBicondicional("bd_desarrollo","registro_brazos","horas_totales","bitacora_avance_id_bitacora",$datos["bitacora_avance_id_bitacora"],"num_brazo",2);
				}
			
				if($cont%2!=0)
					$row = 2;
				else
					$row = 1;
			
				if ($row==2){
					echo "	<tr>
						<td class='nombres_filas' align='center' rowspan='$row'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='left' rowspan='$row'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_emp]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[obra]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[machote]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[medida]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[avance]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[id_equipo]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[horo_ini]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[horo_fin]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[horas_totales]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$brazo1HI</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$brazo1HF</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$brazo1HT</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$brazo2HI</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$brazo2HF</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$brazo2HT</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[barrenos_dados]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[barrenos_disp]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[barrenos_long]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[desborde]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[encapille]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[despate]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[escareado]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[topes_barrenados]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[reanclaje]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[coples]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[zancos]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[anclas]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[obsJu]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[obsAva]</td>
						";?>
						<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
							<input type="button" name="btn_verConsumos<?php echo $cont;?>" id="btn_verConsumos<?php echo $cont;?>" class="botones" value="Ver Consumos" onMouseOver="window.estatus='';return true" title="Ver Consumos Registrados" 
							onClick="javascript:window.open('verRegistroConsumos.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=JUMBO&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>						
						<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
							<input type="button" name="btn_verFallas<?php echo $cont;?>" id="btn_verFallas<?php echo $cont;?>" class="botones" value="Ver Fallas" onMouseOver="window.estatus='';return true" title="Ver Fallas Registradas" 
							onClick="javascript:window.open('verRegistroFallas.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=JUMBO&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>				
					</tr>
				<?php
				}
				else{
					echo "<tr>
					<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_emp]</td>
					<tr>";
				}
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";	
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";
			
			$stm_sql2 = "SELECT CONCAT( T7.nombre,  ' ', T7.ape_pat,  ' ', T7.ape_mat ) AS nombre_emp, T2.nombre,
						T2.puesto, T3.fecha, T4.obra, T5.sueldo_base, T3.anclas, T3.barrenos_dados, 
						T3.escareado, T3.topes_barrenados, T1.avance, T3.observaciones
						FROM bitacora_avance AS T1
						JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
						JOIN barrenacion_jumbo AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
						JOIN catalogo_ubicaciones AS T4 ON T1.catalogo_ubicaciones_id_ubicacion = T4.id_ubicacion
						JOIN catalogo_salarios AS T5 ON T2.puesto = T5.puesto
						AND T2.area = T5.area
						JOIN equipo AS T6 ON T6.bitacora_avance_id_bitacora = T1.id_bitacora
						JOIN bd_recursos.empleados AS T7 ON T7.id_empleados_empresa = T2.nombre
						WHERE T6.area =  'JUMBO'
						AND T2.area =  'JUMBO'
						AND T2.puesto =  'OPERADOR'
						AND T3.fecha
						BETWEEN  '$fechaI'
						AND  '$fechaF'
						ORDER BY T2.nombre, T3.fecha";
			
			$res=$stm_sql."<br>".$msje."<br>".$stm_sql2."<br>".$fechaI."<br>".$fechaF;
		}
		else{
			//Si no hay registros en la Bitacora, se indica esto al usuario
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><label class='msje_correcto'>No hay Registros de Equipo Barrenacion con Jumbo en las Fechas Introducidas</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		return $res;
	}//Fin de la Funcion de mostrarBarrenacionJum
	
	//Funcion que muestra los registros de Ayudante General
	function mostrarAyudanteGeneral(){
		$total_tb = 0; $total_bd=0; $total_tc=0;
		
		$res="false";
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Realizar la conexion a la BD de Desarollo
		$conn = conecta("bd_desarrollo");	
		//Escribimos la consulta a realizarse 
		$stm_sql = "SELECT T3.bitacora_avance_id_bitacora, T3.fecha, T3.turno, T2.puesto,
					CONCAT( T7.nombre,  ' ', T7.ape_pat,  ' ', T7.ape_mat ) AS nombre_emp, 
					T4.obra, T1.machote, T1.medida, T1.avance, T6.id_equipo, T6.horo_ini, 
					T6.horo_fin, T6.horas_totales, T3.barrenos_disp, T3.topes_barrenados, 
					T3.observaciones AS obsJu, T1.observaciones AS obsAva
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					JOIN barrenacion_jumbo AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
					JOIN catalogo_ubicaciones AS T4 ON T1.catalogo_ubicaciones_id_ubicacion = T4.id_ubicacion
					JOIN catalogo_salarios AS T5 ON T2.puesto = T5.puesto
					AND T2.area = T5.area
					JOIN equipo AS T6 ON T6.bitacora_avance_id_bitacora = T1.id_bitacora
					AND T2.area = T6.area
					JOIN bd_recursos.empleados AS T7 ON T7.id_empleados_empresa = T2.nombre
					WHERE T2.puesto =  'AYUDANTE'
					AND T6.area =  'JUMBO'
					AND T3.fecha
					BETWEEN  '$fechaI'
					AND  '$fechaF'";
					
		$msje="Registros de Ayudante General <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		$rs2 = mysql_query($stm_sql);
		$datos2=mysql_fetch_array($rs2);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			$datos2=mysql_fetch_array($rs2);
			//Desplegar los resultados de la consulta en una tabla
			echo "	
				<br>		
				<table cellpadding='5' width='1200'>   
				<caption class='titulo_etiqueta'>$msje</caption>    			
				<thead>
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>FECHA</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>TURNO</th>
				        <th class='nombres_columnas' align='center' rowspan='2'>PUESTO</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
						<th class='nombres_columnas' align='center' colspan='4'>AVANCE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>EQUIPO</th>
						<th class='nombres_columnas' align='center' colspan='3'>HOROMETRO</th>
						<th class='nombres_columnas' align='center' rowspan='2'>BARRENOS DISPARADOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TOPES BARRENADOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TOPES CARGADOS</th>
						<th class='nombres_columnas' align='center' colspan='2'>OBSERVACIONES</th>
						<th class='nombres_columnas' align='center' rowspan='2'>CONSUMOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>FALLAS</th>
      				</tr>
					<tr>
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
						<th class='nombres_columnas' align='center'>INICIAL</th>
        				<th class='nombres_columnas' align='center'>FINAL</th>
						<th class='nombres_columnas' align='center'>HORAS<br>TOTALES</th>
						<th class='nombres_columnas' align='center'>GENERAL</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{
				$total_tb += $datos["topes_barrenados"]; $total_bd += $datos["barrenos_disp"]; $total_tc += 0;
				 if($datos["nombre_emp"] != $datos2["nombre_emp"] || $datos["fecha"] != $datos2["fecha"]) { 
					echo "	<tr>
						<td class='nombres_filas' align='center'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='left'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_emp]</td>
						
						<td class='$nom_clase' align='center'>$datos[obra]</td>
						<td class='$nom_clase' align='center'>$datos[machote]</td>
						<td class='$nom_clase' align='center'>$datos[medida]</td>
						<td class='$nom_clase' align='center'>$datos[avance]</td>
						
						<td class='$nom_clase' align='center'>$datos[id_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[horo_ini]</td>
						<td class='$nom_clase' align='center'>$datos[horo_fin]</td>
						<td class='$nom_clase' align='center'>$datos[horas_totales]</td>
						
						<td class='$nom_clase' align='center'>$total_bd</td>
						<td class='$nom_clase' align='center'>$total_tb</td>
						<td class='$nom_clase' align='center'>0</td>
						<td class='$nom_clase' align='center'>$datos[obsJu]</td>
						<td class='$nom_clase' align='center'>$datos[obsAva]</td>
						";?>
						<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
							<input type="button" name="btn_verConsumos<?php echo $cont;?>" id="btn_verConsumos<?php echo $cont;?>" class="botones" value="Ver Consumos" onMouseOver="window.estatus='';return true" title="Ver Consumos Registrados" 
							onClick="javascript:window.open('verRegistroConsumos.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=JUMBO&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>						
						<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
							<input type="button" name="btn_verFallas<?php echo $cont;?>" id="btn_verFallas<?php echo $cont;?>" class="botones" value="Ver Fallas" onMouseOver="window.estatus='';return true" title="Ver Fallas Registradas" 
							onClick="javascript:window.open('verRegistroFallas.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=JUMBO&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>				
					</tr>
				<?php
					$total_tb = 0; $total_bd=0; $total_tc=0;
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";	
				}
				$datos2=mysql_fetch_array($rs2);
			}while($datos=mysql_fetch_array($rs));
				
		}
		
		$stm_sql2 = "SELECT id_bitacora, fecha_registro, avance, T2.nombre,  T4.obra, T2.area, T2.puesto, T2.nombre,
					CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T1.observaciones
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					JOIN bd_recursos.empleados AS T3 ON T3.id_empleados_empresa = T2.nombre
					JOIN catalogo_ubicaciones AS T4 ON T1.catalogo_ubicaciones_id_ubicacion = T4.id_ubicacion
					WHERE T2.puesto =  'AYUDANTE'
					AND fecha_registro
					BETWEEN  '$fechaI'
					AND  '$fechaF'
					ORDER BY nombre_emp, fecha_registro";
			
		$res=$stm_sql."<br>".$msje."<br>".$stm_sql2;	
		
		$stm_sql3 = "SELECT T3.bitacora_avance_id_bitacora, T3.fecha, T3.turno, T2.puesto, 
						CONCAT( T7.nombre,  ' ', T7.ape_pat,  ' ', T7.ape_mat ) AS nombre_emp, 
						T4.obra, T1.machote, T1.medida, T1.avance, T6.id_equipo, T6.horo_ini, 
						T6.horo_fin, T6.horas_totales, T3.topes_cargados, 
						T3.observaciones AS obsJu, T1.observaciones AS obsAva
						FROM bitacora_avance AS T1
						JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
						JOIN voladuras AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
						JOIN catalogo_ubicaciones AS T4 ON T1.catalogo_ubicaciones_id_ubicacion = T4.id_ubicacion
						JOIN catalogo_salarios AS T5 ON T2.puesto = T5.puesto
						AND T2.area = T5.area
						JOIN equipo AS T6 ON T6.bitacora_avance_id_bitacora = T1.id_bitacora
						AND T2.area = T6.area
						JOIN bd_recursos.empleados AS T7 ON T7.id_empleados_empresa = T2.nombre
						WHERE T2.puesto =  'AYUDANTE'
						AND T6.area =  'VOLADURAS'
						AND T3.fecha
						BETWEEN  '$fechaI'
						AND  '$fechaF'";
					
			//Ejecutar la Sentencia 
			$rs_vol = mysql_query($stm_sql3);
			$rs_vol2 = mysql_query($stm_sql3);
			$datos_vol2=mysql_fetch_array($rs_vol2);
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos_vol=mysql_fetch_array($rs_vol)){
				$datos_vol2=mysql_fetch_array($rs_vol2);
			
			do{
				$total_tb += 0; $total_bd += 0; $total_tc += $datos_vol["topes_cargados"];
				 if($datos_vol["nombre_emp"] != $datos_vol2["nombre_emp"] || $datos_vol["fecha"] != $datos_vol2["fecha"]) { 
					echo "	<tr>
						<td class='nombres_filas' align='center'>".modFecha($datos_vol["fecha"],1)."</td>
						<td class='$nom_clase' align='left'>$datos_vol[turno]</td>
						<td class='$nom_clase' align='center'>$datos_vol[puesto]</td>
						<td class='$nom_clase' align='center'>$datos_vol[nombre_emp]</td>
						
						<td class='$nom_clase' align='center'>$datos_vol[obra]</td>
						<td class='$nom_clase' align='center'>$datos_vol[machote]</td>
						<td class='$nom_clase' align='center'>$datos_vol[medida]</td>
						<td class='$nom_clase' align='center'>$datos_vol[avance]</td>
						
						<td class='$nom_clase' align='center'>$datos_vol[id_equipo]</td>
						<td class='$nom_clase' align='center'>$datos_vol[horo_ini]</td>
						<td class='$nom_clase' align='center'>$datos_vol[horo_fin]</td>
						<td class='$nom_clase' align='center'>$datos_vol[horas_totales]</td>
						
						<td class='$nom_clase' align='center'>0</td>
						<td class='$nom_clase' align='center'>0</td>
						<td class='$nom_clase' align='center'>$total_tc</td>
						<td class='$nom_clase' align='center'>$datos_vol[obsJu]</td>
						<td class='$nom_clase' align='center'>$datos_vol[obsAva]</td>
						";?>
						<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
							<input type="button" name="btn_verConsumos<?php echo $cont;?>" id="btn_verConsumos<?php echo $cont;?>" class="botones" value="Ver Consumos" onMouseOver="window.estatus='';return true" title="Ver Consumos Registrados" 
							onClick="javascript:window.open('verRegistroConsumos.php?id_bitacora=<?php echo $datos_vol['bitacora_avance_id_bitacora'];?>&tipoReg=VOLADURAS&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>						
						<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
							<input type="button" name="btn_verFallas<?php echo $cont;?>" id="btn_verFallas<?php echo $cont;?>" class="botones" value="Ver Fallas" onMouseOver="window.estatus='';return true" title="Ver Fallas Registradas" 
							onClick="javascript:window.open('verRegistroFallas.php?id_bitacora=<?php echo $datos_vol['bitacora_avance_id_bitacora'];?>&tipoReg=VOLADURAS&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>				
					</tr>
				<?php
					$total_tb = 0; $total_bd=0; $total_tc=0;
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}
				
				$datos_vol2=mysql_fetch_array($rs_vol2);
			}while($datos_vol=mysql_fetch_array($rs_vol));
			$res=$stm_sql."<br>".$msje."<br>".$stm_sql2."<br>".$stm_sql3;
		}
		else{
			//Si no hay registros en la Bitacora, se indica esto al usuario
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><label class='msje_correcto'>No hay Registros de Ayudante General en las Fechas Introducidas</u></em></label>";
		}
		$res=$stm_sql."<br>".$msje."<br>".$stm_sql2."<br>".$stm_sql3."<br>".$fechaI."<br>".$fechaF;
		echo "</tbody>";
		echo "</table>";
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		return $res;
	}//Fin de la Funcion de mostrarAyudanteGeneral
	
	//Funcion que muestra los registros de Barrenacion con Máquina de Pierna
	function mostrarBarrenacionMP(){
		$res="false";
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Realizar la conexion a la BD de Desarollo
		$conn = conecta("bd_desarrollo");	
		//Escribimos la consulta a realizarse 
		$stm_sql = "SELECT barrenacion_maq_pierna.bitacora_avance_id_bitacora,fecha,turno,puesto,nombre,obra,machote,medida,avance,barrenos_dados,barrenos_disparos,barrenos_longitud,
					broca_nva,broca_afil,barra_6,barra_8,anclas,id_equipo,equipo.horo_ini AS hie,equipo.horo_fin AS hfe,equipo.horas_totales AS hte,barrenacion_maq_pierna.observaciones AS obsMP, 
					bitacora_avance.observaciones AS obsAva 
					FROM barrenacion_maq_pierna JOIN personal ON barrenacion_maq_pierna.bitacora_avance_id_bitacora=personal.bitacora_avance_id_bitacora 
					JOIN equipo ON barrenacion_maq_pierna.bitacora_avance_id_bitacora=equipo.bitacora_avance_id_bitacora 
					JOIN bitacora_avance ON barrenacion_maq_pierna.bitacora_avance_id_bitacora=id_bitacora 
					JOIN catalogo_ubicaciones ON catalogo_ubicaciones_id_ubicacion=id_ubicacion 
					WHERE equipo.area='MP' AND personal.area='MP' AND fecha BETWEEN '$fechaI' AND '$fechaF'";
					
		$msje="Registros de Barrenaci&oacute;n con M&aacute;quina de Pierna del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "	
				<br>		
				<table cellpadding='5' width='1200'>   
				<caption class='titulo_etiqueta'>$msje</caption>    			
				<thead>
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>FECHA</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>TURNO</th>
				        <th class='nombres_columnas' align='center' rowspan='2'>PUESTO</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
						<th class='nombres_columnas' align='center' colspan='4'>AVANCE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>EQUIPO</th>
						<th class='nombres_columnas' align='center' colspan='3'>HOROMETRO</th>
						<th class='nombres_columnas' align='center' colspan='3'>BARRENOS</th>
						<th class='nombres_columnas' align='center' colspan='2'>BROCAS</th>
						<th class='nombres_columnas' align='center' colspan='2'>BARRAS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>ANCLAS</th>
						<th class='nombres_columnas' align='center' colspan='2'>OBSERVACIONES</th>
						<th class='nombres_columnas' align='center' rowspan='2'>CONSUMOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>FALLAS</th>
      				</tr>
					<tr>
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
						<th class='nombres_columnas' align='center'>INICIAL</th>
        				<th class='nombres_columnas' align='center'>FINAL</th>
						<th class='nombres_columnas' align='center'>HORAS<br>TOTALES</th>
						<th class='nombres_columnas' align='center'>DADOS</th>
        				<th class='nombres_columnas' align='center'>DISPARADOS</th>
						<th class='nombres_columnas' align='center'>LONGITUD</th>
						<th class='nombres_columnas' align='center'>NUEVAS</th>
						<th class='nombres_columnas' align='center'>AFILADAS</th>
						<th class='nombres_columnas' align='center'>6</th>
						<th class='nombres_columnas' align='center'>8</th>
						<th class='nombres_columnas' align='center'>BARRENACI&Oacute;N</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{
				if($cont%2!=0)
					$row = 2;
				else
					$row = 1;
			
				if ($row==2){
					echo "	<tr>
						<td class='nombres_filas' align='center' rowspan='$row'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='left' rowspan='$row'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[obra]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[machote]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[medida]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[avance]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[id_equipo]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[hie]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[hfe]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[hte]</td>
											
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[barrenos_dados]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[barrenos_disparos]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[barrenos_longitud]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[broca_nva]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[broca_afil]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[barra_6]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[barra_8]</td>
						
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[anclas]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[obsMP]</td>
						<td class='$nom_clase' align='center' rowspan='$row'>$datos[obsAva]</td>
						";?>
						<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
							<input type="button" name="btn_verConsumos<?php echo $cont;?>" id="btn_verConsumos<?php echo $cont;?>" class="botones" value="Ver Consumos" onMouseOver="window.estatus='';return true" title="Ver Consumos Registrados" 
							onClick="javascript:window.open('verRegistroConsumos.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=BARRENACIONMP&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>						
						<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
							<input type="button" name="btn_verFallas<?php echo $cont;?>" id="btn_verFallas<?php echo $cont;?>" class="botones" value="Ver Fallas" onMouseOver="window.estatus='';return true" title="Ver Fallas Registradas" 
							onClick="javascript:window.open('verRegistroFallas.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=BARRENACIONMP&no=<?php echo $cont;?>',
							'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
						</td>				
					</tr>
				<?php
				}
				else{
					echo "<tr>
					<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>
					<tr>";
				}
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";	
			$res=$stm_sql."<br>".$msje;		
		}
		else{
			//Si no hay registros en la Bitacora, se indica esto al usuario
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><label class='msje_correcto'>No hay Registros de Barrenaci&oacute;n con M&aacute;quina de Pierna en las Fechas Introducidas</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		return $res;
	}//Fin de la Funcion de mostrarBarrenacionMP
	
	//Funcion que muestra los registros de Voladuras
	function mostrarVoladuras(){
		$res="false";
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Realizar la conexion a la BD de Desarollo
		$conn = conecta("bd_desarrollo");	
		//Escribimos la consulta a realizarse 
		/*$stm_sql = "SELECT voladuras.bitacora_avance_id_bitacora,fecha,turno,puesto,nombre,obra,machote,medida,avance,long_barreno_carg,factor_carga,
					id_equipo,horo_ini,horo_fin,horas_totales,voladuras.observaciones AS obsVol, bitacora_avance.observaciones AS obsAva 
					FROM voladuras JOIN personal ON voladuras.bitacora_avance_id_bitacora=personal.bitacora_avance_id_bitacora 
					JOIN equipo ON voladuras.bitacora_avance_id_bitacora=equipo.bitacora_avance_id_bitacora 
					JOIN bitacora_avance ON voladuras.bitacora_avance_id_bitacora=id_bitacora 
					JOIN catalogo_ubicaciones ON catalogo_ubicaciones_id_ubicacion=id_ubicacion
					WHERE equipo.area='VOLADURAS' AND personal.area='VOLADURAS' AND fecha BETWEEN '$fechaI' AND '$fechaF'";*/
		$stm_sql = "SELECT T3.bitacora_avance_id_bitacora, T3.fecha, T3.turno, 
					CONCAT( T7.nombre,  ' ', T7.ape_pat,  ' ', T7.ape_mat ) AS nombre_emp, 
					T2.puesto, T6.id_equipo, T6.horo_ini, T6.horo_fin, T6.horas_totales, T4.obra, 
					T1.machote, T1.medida, T1.avance, T3.long_barreno_carg, T3.factor_carga, 
					T3.topes_cargados, T3.observaciones AS obsVol, T1.observaciones AS obsAva
					FROM bitacora_avance AS T1
					JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
					JOIN voladuras AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
					JOIN catalogo_ubicaciones AS T4 ON T1.catalogo_ubicaciones_id_ubicacion = T4.id_ubicacion
					JOIN catalogo_salarios AS T5 ON T2.puesto = T5.puesto
					AND T2.area = T5.area
					JOIN equipo AS T6 ON T6.bitacora_avance_id_bitacora = T1.id_bitacora
					JOIN bd_recursos.empleados AS T7 ON T7.id_empleados_empresa = T2.nombre
					WHERE T6.area =  'VOLADURAS'
					AND T2.area =  'VOLADURAS'
					AND T3.fecha
					BETWEEN  '$fechaI'
					AND  '$fechaF'";
		
		$msje="Registros de Voladuras del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "	
				<br>		
				<table cellpadding='5' width='1200'>   
				<caption class='titulo_etiqueta'>$msje</caption>    			
				<thead>
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>FECHA</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>TURNO</th>
				        <th class='nombres_columnas' align='center' rowspan='2'>PUESTO</th>
        				<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>EQUIPO</th>
						<th class='nombres_columnas' align='center' colspan='3'>HOROMETRO</th>
						<th class='nombres_columnas' align='center' colspan='4'>AVANCE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>LONGITUD<br>BARRENO<br>CARGA</th>
						<th class='nombres_columnas' align='center' rowspan='2'>FACTOR CARGA</th>
						<th class='nombres_columnas' align='center' rowspan='2'>TOPE CARGADO</th>
						<th class='nombres_columnas' align='center' rowspan='2'>EXPLOSIVO<br>EMPLEADO</th>
						<th class='nombres_columnas' align='center' colspan='2'>OBSERVACIONES</th>
						<th class='nombres_columnas' align='center' rowspan='2'>CONSUMOS</th>
						<th class='nombres_columnas' align='center' rowspan='2'>FALLAS</th>
      				</tr>
					<tr>
						<th class='nombres_columnas' align='center'>INICIAL</th>
        				<th class='nombres_columnas' align='center'>FINAL</th>
						<th class='nombres_columnas' align='center'>HORAS<br>TOTALES</th>
						
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
						
						<th class='nombres_columnas' align='center'>VOLADURA</th>
        				<th class='nombres_columnas' align='center'>AVANCE</th>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				if($cont%2!=0)
					$row = 2;
				else
					$row = 1;
			
				if ($row==2){
					echo "	<tr>
							<td class='nombres_filas' align='center' rowspan='$row'>".modFecha($datos["fecha"],1)."</td>
							<td class='$nom_clase' align='left' rowspan='$row'>$datos[turno]</td>
							<td class='$nom_clase' align='center'>$datos[puesto]</td>
							<td class='$nom_clase' align='center'>$datos[nombre_emp]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[id_equipo]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[horo_ini]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[horo_fin]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[horas_totales]</td>
							
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[obra]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[machote]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[medida]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[avance]</td>
							
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[long_barreno_carg]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[factor_carga]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[topes_cargados]</td>";
							
							?>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
								<input type="button" name="btn_verExplosivo<?php echo $cont;?>" id="btn_verExplosivo<?php echo $cont;?>" class="botones" value="Ver Explosivos" onMouseOver="window.estatus='';return true" title="Ver Explosivos Consumidos" 
								onClick="javascript:window.open('verRegistroExplosivos.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&no=<?php echo $cont;?>',
								'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
							</td>
							<?php
							
					echo "<td class='$nom_clase' align='center' rowspan='$row'>$datos[obsVol]</td>
							<td class='$nom_clase' align='center' rowspan='$row'>$datos[obsAva]</td>
							";?>
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
								<input type="button" name="btn_verConsumos<?php echo $cont;?>" id="btn_verConsumos<?php echo $cont;?>" class="botones" value="Ver Consumos" onMouseOver="window.estatus='';return true" title="Ver Consumos Registrados" 
								onClick="javascript:window.open('verRegistroConsumos.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=VOLADURAS&no=<?php echo $cont;?>',
								'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
							</td>						
							<td class="<?php echo $nom_clase; ?>" align="center" rowspan='<?php echo $row?>'>
								<input type="button" name="btn_verFallas<?php echo $cont;?>" id="btn_verFallas<?php echo $cont;?>" class="botones" value="Ver Fallas" onMouseOver="window.estatus='';return true" title="Ver Fallas Registradas" 
								onClick="javascript:window.open('verRegistroFallas.php?id_bitacora=<?php echo $datos['bitacora_avance_id_bitacora'];?>&tipoReg=VOLADURAS&no=<?php echo $cont;?>',
								'_blank','top=50, left=50, width=600, height=400, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled=true;"/>
							</td>				
						</tr>
					<?php
				}
				else{
					echo "<tr>
					<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_emp]</td>
					<tr>";
				}
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";	
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";
			
			$stm_sql2 = "SELECT CONCAT( T7.nombre,  ' ', T7.ape_pat,  ' ', T7.ape_mat ) AS nombre_emp, T2.nombre,
						T2.puesto, T3.fecha, T4.obra, T5.sueldo_base, T3.topes_cargados, T1.avance, T3.observaciones
						FROM bitacora_avance AS T1
						JOIN personal AS T2 ON T1.id_bitacora = T2.bitacora_avance_id_bitacora
						JOIN voladuras AS T3 ON T1.id_bitacora = T3.bitacora_avance_id_bitacora
						JOIN catalogo_ubicaciones AS T4 ON T1.catalogo_ubicaciones_id_ubicacion = T4.id_ubicacion
						JOIN catalogo_salarios AS T5 ON T2.puesto = T5.puesto
						AND T2.area = T5.area
						JOIN equipo AS T6 ON T6.bitacora_avance_id_bitacora = T1.id_bitacora
						JOIN bd_recursos.empleados AS T7 ON T7.id_empleados_empresa = T2.nombre
						WHERE T6.area =  'VOLADURAS'
						AND T2.area =  'VOLADURAS'
						AND T2.puesto = 'OPERADOR'
						AND T3.fecha
						BETWEEN  '$fechaI'
						AND  '$fechaF'
						ORDER BY T2.nombre, T3.fecha";
			
			$res=$stm_sql."<br>".$msje."<br>".$stm_sql2."<br>".$fechaI."<br>".$fechaF;;		
		}
		else{
			//Si no hay registros en la Bitacora, se indica esto al usuario
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><label class='msje_correcto'>No hay Registros de Voladuras en las Fechas Introducidas</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		return $res;
	}//Fin de la Funcion de mostrarVoladuras
	
	//Funcion que muestra los registros de Avance
	function mostrarAvance(){
		$res="false";
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Realizar la conexion a la BD de Desarollo
		$conn = conecta("bd_desarrollo");	
		//Escribimos la consulta a realizarse para contar los Disparos con Jumbo
		$stm_sqlJu ="SELECT id_ubicacion,obra,SUM(barrenos_disp) AS disp,machote,medida,avance 
					FROM bitacora_avance JOIN catalogo_ubicaciones ON id_ubicacion=catalogo_ubicaciones_id_ubicacion 
					JOIN barrenacion_jumbo ON bitacora_avance_id_bitacora=id_bitacora
					WHERE fecha BETWEEN '$fechaI' AND '$fechaF' GROUP BY id_ubicacion ORDER BY obra";
					
		//Escribimos la consulta a realizarse para contar los Disparos con Maquina de Pierna
		$stm_sqlMP ="SELECT id_ubicacion,obra,SUM(barrenos_disparos) AS disp,machote,medida,avance 
					FROM bitacora_avance JOIN catalogo_ubicaciones ON id_ubicacion=catalogo_ubicaciones_id_ubicacion 
					JOIN barrenacion_maq_pierna ON bitacora_avance_id_bitacora=id_bitacora 
					WHERE fecha BETWEEN '$fechaI' AND '$fechaF' GROUP BY id_ubicacion ORDER BY obra";
					
		$msje="Registro de Avance del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		
		//Ejecutar la Sentencia de Avance con Jumbo
		$rsJu = mysql_query($stm_sqlJu);
		//Ejecutar la Sentencia de Avance con Maquina de Pierna
		$rsMP = mysql_query($stm_sqlMP);
		//Obtener el numero de resultados para Jumbo
		$tamJu=mysql_num_rows($rsJu);
		//Obtener el numero de resultados para Maquina de Pierna
		$tamMP=mysql_num_rows($rsMP);

		//Verificar que por lo menos 1 tenga mas de un registro		
		if($tamJu>0 || $tamMP>0){
			if ($tamJu>=$tamMP){
				//Confirmar que la consulta de datos de Jumbo fue realizada con exito.
				if($datosJu=mysql_fetch_array($rsJu)){
					do{
						$avance[]=array("id_ubicacion"=>$datosJu["id_ubicacion"],"obra"=>$datosJu["obra"],"disp"=>$datosJu["disp"],"machote"=>$datosJu["machote"],"medida"=>$datosJu["medida"],"avance"=>$datosJu["avance"]);
					}while($datosJu=mysql_fetch_array($rsJu));
				}
		
				//Confirmar que la consulta de datos de Maquina de Pierna fue realizada con exito.
				if($datosMP=mysql_fetch_array($rsMP)){
					do{
						//Variable que indica si se agrego o no un Registro
						$band=0;
						$cont=0;
						do{
							if($avance[$cont]["id_ubicacion"]==$datosMP["id_ubicacion"]){
								$avance[$cont]["disp"]+=$datosMP["disp"];
								$band=1;
							}
							$cont++;
						}while($cont<$tamJu);
						if ($band==0)
							$avance[]=array("id_ubicacion"=>$datosMP["id_ubicacion"],"obra"=>$datosMP["obra"],"disp"=>$datosMP["disp"],"machote"=>$datosMP["machote"],"medida"=>$datosMP["medida"],"avance"=>$datosMP["avance"]);
					}while($datosMP=mysql_fetch_array($rsMP));
				}
			}
			else{
				//Confirmar que la consulta de datos de Jumbo fue realizada con exito.
				if($datosMP=mysql_fetch_array($rsMP)){
					do{
						$avance[]=array("id_ubicacion"=>$datosMP["id_ubicacion"],"obra"=>$datosMP["obra"],"disp"=>$datosMP["disp"],"machote"=>$datosMP["machote"],"medida"=>$datosMP["medida"],"avance"=>$datosMP["avance"]);
					}while($datosMP=mysql_fetch_array($rsMP));
				}
		
				//Confirmar que la consulta de datos de Maquina de Pierna fue realizada con exito.
				if($datosJu=mysql_fetch_array($datosJu)){
					do{
						//Variable que indica si se agrego o no un Registro
						$band=0;
						$cont=0;
						do{
							if($avance[$cont]["id_ubicacion"]==$datosJu["id_ubicacion"]){
								$avance[$cont]["disp"]+=$datosJu["disp"];
								$band=1;
							}
							$cont++;
						}while($cont<$tamMP);
						if ($band==0)
							$avance[]=array("id_ubicacion"=>$datosJu["id_ubicacion"],"obra"=>$datosJu["obra"],"disp"=>$datosJu["disp"],"machote"=>$datosJu["machote"],"medida"=>$datosJu["medida"],"avance"=>$datosJu["avance"]);
					}while($datosJu=mysql_fetch_array($rsJu));
				}
			}
			//Desplegar los resultados de la consulta en una tabla
			echo "	
				<br>		
				<table cellpadding='5' width='100%'>   
				<caption class='titulo_etiqueta'>$msje</caption>    			
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>OBRA</th>
						<th class='nombres_columnas' align='center'>DISPAROS TOTALES</th>
						<th class='nombres_columnas' align='center'>MACHOTE</th>
						<th class='nombres_columnas' align='center'>MEDIDA</th>
						<th class='nombres_columnas' align='center'>AVANCE</th>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			$cont=0;
			$tam=count($avance);
			do{	
				echo "	<tr>
						<th class='nombres_filas' align='center'>".$avance[$cont]["obra"]."</th>
						<td class='$nom_clase' align='center'>".$avance[$cont]["disp"]."</td>
						<td class='$nom_clase' align='center'>".$avance[$cont]["machote"]."</td>
						<td class='$nom_clase' align='center'>".$avance[$cont]["medida"]."</td>
						<td class='$nom_clase' align='center'>".$avance[$cont]["avance"]."</td>
						</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
			}while($cont<$tam);
			echo "</tbody>";
			echo "</table>";
			$res=$stm_sqlJu."<br>".$stm_sqlMP."<br>".$msje;		
		}
		else{
			//Si no hay registros en la Bitacora, se indica esto al usuario
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><label class='msje_correcto'>No hay Registros de Avance en las Fechas Introducidas</u></em></label>";
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
		return $res;
	}//Fin de la Funcion de mostrarVoladuras
	
	//Funcion que muestra los registros de Servicios
	function mostrarServicios(){
		$res="false";
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Realizar la conexion a la BD de Desarollo
		$conn = conecta("bd_desarrollo");	
		//Escribimos la consulta a realizarse 
		$stm_sql = "SELECT fecha,categoria,actividad,turnoOf,turnoAy FROM detalle_servicios WHERE fecha BETWEEN '$fechaI' AND '$fechaF'";
		$msje="Servicios Registrados del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
		$titulo="Te presento Relaci&oacute;n de Turnos Administrativos del ".modFecha($fechaI,2)." al ".modFecha($fechaF,2);
		//Ejecutar la Sentencia 
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "	
				<br>		
				<table cellpadding='5' width='100%'>   
				<caption class='titulo_etiqueta'>$msje</caption>    			
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>FECHA</th>
        				<th class='nombres_columnas' align='center'>CATEGOR&Iacute;A</th>
				        <th class='nombres_columnas' align='center'>ACTIVIDAD</th>
        				<th class='nombres_columnas' align='center'>TURNOS OFICIAL</th>
						<th class='nombres_columnas' align='center'>TURNOS AYUDANTE</th>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				echo "	<tr>
						<td class='nombres_filas' align='center'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='center'>$datos[categoria]</td>
						<td class='$nom_clase' align='center'>$datos[actividad]</td>
						<td class='$nom_clase' align='center'>$datos[turnoOf]</td>
						<td class='$nom_clase' align='center'>$datos[turnoAy]</td>
						</tr>
						";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";	
			$res=$stm_sql."<br>".$titulo;		
		}
		else{
			//Si no hay registros en la Bitacora, se indica esto al usuario
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><label class='msje_correcto'>No hay Registros de Servicios en las Fechas Introducidas</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		return $res;
	}//Fin de la Funcion de mostrarRezagado
	
	function generarGrafico(){
		$periodo=$_POST["cmb_periodo"];
		$cliente=$_POST["cmb_cliente"];
		$mes=substr($periodo,5,3);
		$mes=obtenerNombreCompletoMes($mes);
		$anio=substr($periodo,0,4);
		$nomCliente=obtenerDato("bd_desarrollo","catalogo_clientes","nom_cliente","id_cliente",$cliente);
		$titulo="REPORTE DE AVANCE VS PRESUPUESTO DE $mes $anio EN $nomCliente";
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		$sql_stm="SELECT fecha_inicio,fecha_fin,mts_mes,mts_mes_dia FROM presupuesto WHERE periodo = '$periodo' AND catalogo_clientes_id_cliente='$cliente'";
		$rs=mysql_query($sql_stm);
		if($datosPeriodo=mysql_fetch_array($rs)){
			//Arreglos para almacenar los totales que se muestran al final de cada Ubicación que es Mostrada
			$sumPorDia = array();//Arreglo que contendra la suma de los lanzamientos hechos por día
			$prodRealPorDia = array();//Arreglo que contendra el volumen real de los lanzamientos por dia acumulados
			$prodPresPorDia = array();//Arreglo que contendra el volumen presupuestado de los lanzamientos por dia acumulados
			$difPorDia = array();//Arrelo que contendrá la diferencia respecto
			
			$fechaIni = $datosPeriodo['fecha_inicio'];
			$fechaFin = $datosPeriodo['fecha_fin'];						
			//Obtener el año de inicio y el año de fin de las fechas que componen el periodo
			$anioInicio = substr($fechaIni,0,4);
			$anioFin = substr($fechaFin,0,4);
			//Separar el valor del Periodo para obtener los meses, aqui se considera que los periodos son siempre de dos meses consecutivos
			$nomMesInicio = obtenerNombreCompletoMes(substr($periodo,5,3));
			$nomMesFin = obtenerNombreCompletoMes(substr($periodo,9,3));
			//Obtener los dias del mes de Inicio del periodo
			$diasMesInicio = diasMes(obtenerNumMes($nomMesInicio), $anioInicio);
			//Obtener el ancho en dias de los meses que componen el periodo
			$anchoDiasInicio = $diasMesInicio - intval(substr($fechaIni,-2)) + 1;
			$anchoDiasFin = intval(substr($fechaFin,-2));
			$totalDias = $anchoDiasInicio + $anchoDiasFin;
			
			//Obtener el dia, mes y año de inicio como actuales
			$diaActual = intval(substr($fechaIni,-2));
			$mesActual = intval(substr($fechaIni,5,2));
			$anioActual = $anioInicio;
			
			$ctrlInicializacion = 0;
			//Ciclo para recorrer la totalidad de dias del periodo seleccionado												
			for($i=0;$i<$totalDias;$i++){
				//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para hacer la consulta en la BD
				$fechaActual = $anioActual;
				if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
				if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
				//Inicializar cada posición del arreglo que contandrá la suma por día de cada una de las ubicaciones
				$sumPorDia[$fechaActual] = 0; 
				//Inicializar el arreglo que contendra el total por dia, que incluye todas las ubicaciones
				if($ctrlInicializacion==0){
					$sumTotalPorDia[$fechaActual] = 0;
				}										
				//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes
				if($diaActual==$diasMesInicio){
					$diaActual = 0;
					$mesActual++;
					
					//Verificar el cambio de año
					if($mesActual==13){
						$mesActual = 1;
						$anioActual++;
					}
				}
				//Ejecutar la Sentencia para obtener los datos del Lanzamiento en la Fecha, y Ubicación indicados
				$datosLanzamiento = mysql_fetch_array(mysql_query("SELECT SUM(avance) AS cantidad FROM bitacora_avance WHERE catalogo_ubicaciones_id_ubicacion =ANY(SELECT id_ubicacion FROM catalogo_ubicaciones WHERE catalogo_clientes_id_cliente='$cliente') AND fecha_registro = '$fechaActual'"));
				//Inicializar cantidad con valor de 0 por default
				$cantidad=0;
				//Si el valor de retorno es diferente de NULL, extraerlo
				if ($datosLanzamiento!=NULL)
					$cantidad = $datosLanzamiento['cantidad'];
				//Sumar los volumenes encontrados por dia, tanto para Lanzador como para el Ayudante para el total por Dia
				$sumPorDia[$fechaActual] += $cantidad;
				//Acumular el total por dia por todas la ubicaciones dentro del periodo selecionado
				$sumTotalPorDia[$fechaActual] += $cantidad;		
				//Variables para realizar los calculos
				$cont = 1;
				$prodRealAnterior = 0;												
				//Obtener el volumen real acumulado, volumen presupuestado real y la diferencia para ir viendo el avance dia a dia
				foreach($sumPorDia as $fechaActual => $volumen){					
//					//Comprobar si la Fecha Actual es Domingo
//					$domingo = false;
//					if(obtenerNombreDia($fechaActual)=="Domingo")
//						$domingo = true;
					//Colocar los valores del Volumen Real, presupuestado y diferencia en el primer dia del periodo
					if($cont==1){
						//Guardar la Produccion del Día y el Prespuesto del Día con valores FLotantes y a partir de ello realizar los respaldos necesarios para los calculos de los siguientes días
						$prodRealPorDia[$fechaActual] = floatval($volumen);
						$prodPresPorDia[$fechaActual] = floatval($datosPeriodo["mts_mes_dia"]);
						//Obtener la diferencia del Día
						$difPorDia[$fechaActual] = $prodRealPorDia[$fechaActual] - $prodPresPorDia[$fechaActual];
						//Guardar la produccion real del dia como anterior
						$prodRealAnterior = $prodRealPorDia[$fechaActual];
						//Guardar el presupuesto del dia como anterior
						$presAnterior = $prodPresPorDia[$fechaActual];
					}
					else{//Acumular los datos para el resto de los dias del periodo
						//Acumular el volumen diario real produccido
						$prodRealPorDia[$fechaActual] = floatval($volumen + $prodRealAnterior);
						//Guardar la produccion real del dia como anterior
						$prodRealAnterior = $prodRealPorDia[$fechaActual];
						//Verificar si el dia es domingo y no acumular el volumen Presupuestado
//						if($domingo){
//							$prodPresPorDia[$fechaActual] = $presAnterior;
//						}
//						else{
							$prodPresPorDia[$fechaActual] = floatval($datosPeriodo["mts_mes_dia"] + $presAnterior);
							//Guardar el presupuesto del Dia como Presupuesto del Día Anterior
							$presAnterior = $prodPresPorDia[$fechaActual];
//						}
					}
					//Contador para saber cuando se colocan los valores del Dia inicial
					$cont++;
				}//Cierre foreach($sumPorDia as $diaActual => $volumen){
				//Incrementar el dia
				$diaActual++;
			}//Cierre for($i=0;$i<$totalDias;$i++)
			//Cerrar la Conexion con la BD
			mysql_close($conn);
			//Dibujar Grafica
			$grafica=dibujarGrafica1($prodPresPorDia,$prodRealPorDia,$titulo);
			return $grafica;
		}
		else
			return "";
	}
	
	//Grafica que es incluida en el reporte de Agregados
	function dibujarGrafica1($datosPreupuesto,$datosProduccion,$msg){	
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_plotline.php');
							
		//Obtener las fechas para ser colocados en la Grafica
		$fechas = array_keys($datosPreupuesto);	
		
		$dias = array();
		//Solo dejar los digitos del dia de cada fecha para ser colocados en la grafica
		foreach($fechas as $ind => $fecha)
			$dias[] = substr($fecha,-2);
		
		//Redeondear los valores del presupuesto y colocarlos en otro Arreglo
		$preupuesto = array();
		foreach($datosPreupuesto as $ind => $valor)
			$preupuesto[] = round($valor);
		
		//Redeondear los valores del presupuesto y colocarlos en otro Arreglo
		$produccion = array();
		foreach($datosProduccion as $ind => $valor)
			$produccion[] = round($valor);
		
		//Crear el Grafico, se deben hacer dos llamadas a los metodos Graph() y SetScale()
		$graph = new Graph(940,450);
		$graph->SetScale('textlin');
		$graph->title->Set($msg);
		//Colocar los Margenes del Grafico(Izq,Der,Arriba,Abajo)
		$graph->SetMargin(60,120,40,60);				
		//Colocar el Color del Margen
		$graph->SetMarginColor('white@0.5');
		
		//Colocar los Titulos a los Ejes
		$graph->yaxis->title->Set('METROS LINEALES');//Eje Y
		$graph->yaxis->title->SetMargin(20);
		$graph->xaxis->title->Set('DIAS');//Eje X
			
		//Crear la primera linea del Grafico con los Datos del Presupuesto
		$lineplot=new LinePlot($preupuesto);
		$lineplot->SetColor('red');
		$lineplot->SetLegend('Presupuesto');
		//Muestra y formatea los valores de los datos en la linea correspondiente
		$lineplot->mark->SetType(MARK_FILLEDCIRCLE);
		$lineplot->mark->SetFillColor("black");
		$lineplot->mark->SetWidth(2);
		$lineplot->value->Show();
		
		//Crear la segunda linea del Grafico con los Datos de la Produccion		
		$lineplot2=new LinePlot($produccion);
		$lineplot2->SetColor('blue');
		$lineplot2->SetLegend('Producción Real');	
		//Muestra los valores de los datos en la linea correspondiente
		//$lineplot2->value->Show();					
		
		//Agregar Nombres de los rotulos del eje X
		$graph->xaxis->SetTickLabels($dias);
		//Establecer el margen separación entre etiquetas del Eje X
		$graph->xaxis->SetTextLabelInterval(1);
		
		//Agregar las lineas de datos a la grafica
		$graph->Add($lineplot);
		$graph->Add($lineplot2);
		
		//Alinear los rotulos de la leyenda
		$graph->legend->SetPos(0.05,0.5,'right','center');
		
		//Crea un nombre oara la grafica que sera guardada en un archivo temporal
		$rnd=rand(0,1000);		
		$grafica= "tmp/grafica".$rnd.".png";
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		//Retornar el directorio y nombre de la grafica creada temporalmente para ser mostrada en una pagina HTML
		return $grafica;
					
	}//Cierre dibujarGrafica($msg,$datosPreupuesto,$datosProduccion)
?>