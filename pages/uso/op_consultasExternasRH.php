<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupoacional
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 27/Agosto/2012
	  * Descripción: Este archivo contiene funciones para Realizar consultas a otros departamentos desde el Modulo de la Unidad de Salud Ocupacional
	**/

	//Funcion que muestra los proveedores, esta consulta es hecha al Departamento de Recursos Humanos
	function mostrarEmpleados(){
		//Realizar la conexion a la BD de Recursos
		$conn = conecta("bd_recursos");		
		//Escribimos la consulta a realizarse 
		$stm_sql = "SELECT * FROM empleados";
		//Ejecutar la Sentencia para obtener los datos del empleado seleccionado
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='1200' id='tabla-resultadosEmpleados'> 
				<thead>
				";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>RFC</th>
						<th class='nombres_columnas' align='center'>CURP</th>
						<th class='nombres_columnas' align='center'>ID EMPRESA</th>
						<th class='nombres_columnas' align='center'>ID &Aacute;REA</th>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>TIPO SANGRE</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO SEGURO SOCIAL</td>
						<td class='nombres_columnas' align='center'>FECHA INGRESO</td>
						<td class='nombres_columnas' align='center'>ANTIG&Uuml;EDAD</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<th class='nombres_columnas' align='center'>&Aacute;REA</th>
						<td class='nombres_columnas' align='center'>JORNADA</td>
						<td class='nombres_columnas' align='center'>DIRECCI&Oacute;N</td>
						<th class='nombres_columnas' align='center'>MUNICIPIO/ LOCALIDAD</th>
						<th class='nombres_columnas' align='center'>ESTADO</th>
						<th class='nombres_columnas' align='center'>PAIS</th>
						<th class='nombres_columnas' align='center'>NACIONALIDAD</th>
						<td class='nombres_columnas' align='center'>FOTOGRAF&Iacute;A</td>
						<td class='nombres_columnas' align='center'>CAPACITACIONES</td>
						<td class='nombres_columnas' align='center'>CONTACTO POR ACCIDENTE</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;				
			echo "<tbody>";
			do{	
				$ctrl_imagen = "";
				if($datos['mime']=="")
					$ctrl_imagen = "disabled='disabled'";									
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$datos[rfc_empleado]</td>
						<td class='$nom_clase' align='left'>$datos[curp]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase' align='center'>$datos[id_empleados_area]</td>
						<td class='$nom_clase' align='center'>$datos[nombre] $datos[ape_pat] $datos[ape_mat]</td>
						<td class='$nom_clase' align='left'>$datos[tipo_sangre]</td>
						<td class='$nom_clase' align='center'>$datos[no_ss]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos["fecha_ingreso"],2)."</td>
						<td class='$nom_clase' align='left'>".round((restarFechas($datos["fecha_ingreso"],date("Y-m-d"))/365),2)." a&ntilde;os</td>
						<td class='$nom_clase' align='left'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='center'>$datos[jornada]&nbsp;Hrs.</td>
						<td class='$nom_clase' align='center'>$datos[calle] $datos[num_ext] $datos[num_int] $datos[colonia]</td>
						<td class='$nom_clase' align='center'>$datos[localidad]</td>
						<td class='$nom_clase' align='center'>$datos[estado]</td>
						<td class='$nom_clase' align='center'>$datos[pais]</td>
						<td class='$nom_clase' align='center'>$datos[nacionalidad]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFoto" class="botones" value="Foto" onMouseOver="window.estatus='';return true" title="Ver Foto del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verImagen.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" <?php echo $ctrl_imagen; ?>/>							
						</td>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verCapacitaciones" class="botones" value="Capacitaciones" onMouseOver="window.estatus='';return true" title="Ver verCapacitaciones del Empleado <?php echo $datos['rfc_empleado'];?>" 
							onClick="javascript:window.open('verCapacitaciones.php?id_empleado=<?php echo $datos['rfc_empleado']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td><?php
				echo "	
						<td class='$nom_clase' align='left'>Nombre: $datos[nom_accidente]<br>Tel: $datos[tel_accidente]<br>Cel: $datos[cel_accidente]</td>
						<td class='$nom_clase' align='left'>$datos[observaciones]</td>
						</tr>";					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";			
		}else{
			//Si no hay provedores registrados por Compras, se indica esto al usuario
			echo "<label class='msje_correcto'>No existen Empleados Registrados en el Sistema</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
	}//Fin de la funcion de mostrarProveedores
	
	
	//Función que permite mostrar el reporte de Asistencias
	function mostrarIncapacidades($fechaIni,$fechaFin,$tipo){	
		
		//Convertimos las fechas en formato aaa-mm-dd ya que son como se guardan en la BD.
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		$titulo = "";
		//Volvemos a convertir las fechas definidas anteriormente para colocar el titulo dentro d ela tabla que mostrara los resultados 
		$fechaIni=modFecha($fechaI,1);
		$fechaFin=modFecha($fechaF,1);
		$conn=conecta("bd_recursos");
		
		$stm_sql = "";
		$consulta = "";
				
		//Sentencia SQL para extraer a los Trabajadores del Área Seleccionada		
		if($tipo==""){					
			$stm_sql="SELECT DISTINCT rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados 
			JOIN checadas ON rfc_empleado = empleados_rfc_empleado WHERE fecha_checada BETWEEN '$fechaI' AND '$fechaF' 
			AND (checadas.estado = 'RT' OR checadas.estado = 'E' OR checadas.estado = 'T')";
			
			/*echo $stm_sql = "SELECT DISTINCT rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre 
			FROM empleados JOIN checadas ON rfc_empleado = empleados_rfc_empleado WHERE fecha_checada BETWEEN '$fechaI' AND '$fechaF' 
			AND rfc_empleado = empleados_rfc_empleado AND SUBSTRING(checadas.estado,1,2)!='A' AND SUBSTRING(checadas.estado,1,2)!='F' 
			AND SUBSTRING(checadas.estado,1,2)!='d' AND SUBSTRING(checadas.estado,1,2)!='V' AND SUBSTRING(checadas.estado,1,2)!='r' 
			AND SUBSTRING(checadas.estado,1,3)!='F/J' AND SUBSTRING(checadas.estado,1,2)!='P' AND SUBSTRING(checadas.estado,1,3)!='P/G' 
			AND SUBSTRING(checadas.estado,1,2)!='D' AND SUBSTRING(checadas.estado,1,2)!='R' AND SUBSTRING(checadas.estado,1,2)!='E' AND checadas.estado NOT LIKE  'SALIDA%'";*/
			
			//Titulo de la consulta correspondiente a la eleccion de informacion de acuerdo a un  rango de fechas
			$titulo = "Reporte de Incapacidades por Empleado del $fechaIni al $fechaFin";
		}
		else if($tipo=="RT"){
			$stm_sql="SELECT DISTINCT rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados JOIN checadas 
			ON rfc_empleado = empleados_rfc_empleado WHERE fecha_checada BETWEEN '$fechaI' AND '$fechaF' AND checadas.estado='RT'";
			
			 //Titulo de la consulta correspondiente a la eleccion de informacion de acuerdo a un  rango de fechas
			$titulo = "Reporte de Incapacidades por Accidentes de Trabajo  del $fechaIni al $fechaFin";
		}
		else if($tipo=="E"){
			$stm_sql="SELECT DISTINCT rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados JOIN checadas 
			ON rfc_empleado = empleados_rfc_empleado WHERE fecha_checada BETWEEN '$fechaI' AND '$fechaF' AND checadas.estado='E'";
			
			 //Titulo de la consulta correspondiente a la eleccion de informacion de acuerdo a un  rango de fechas
			$titulo = "Reporte de Incapacidades por Enfermedad General del $fechaIni al $fechaFin";
		}
		else if($tipo=="T"){
			$stm_sql="SELECT DISTINCT rfc_empleado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados JOIN checadas 
			ON rfc_empleado = empleados_rfc_empleado WHERE fecha_checada BETWEEN '$fechaI' AND '$fechaF' AND checadas.estado='T'";
			
			//Titulo de la consulta correspondiente a la eleccion de informacion de acuerdo a un  rango de fechas
			$titulo = "Reporte de Incapacidades en Trayecto del $fechaIni al $fechaFin";	
		}

		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que existan registos
		if($datos=mysql_fetch_array($rs)){
			
			mysql_query("SELECT DISTINCT fecha_checada, hora_checada, checadas.estado FROM empleados JOIN checadas ON 
			rfc_empleado = empleados_rfc_empleado WHERE rfc_empleado = empleados_rfc_empleado AND fecha_checada 
			BETWEEN '$fechaI' AND '$fechaF'"); 

			//Recuperar las Fechas de Inicio y de Fin
			$fechaI=$_POST["txt_fechaIni"];
			$fechaF=$_POST["txt_fechaFin"];
			//Convertir las Fechas a formato legible por MySQL
			$fechaIMod=modFecha($fechaI,3);
			$fechaFMod=modFecha($fechaF,3);
			//Variable para controlar ancho de Tabla
			$anchoTabla="100%";
			//Obtener la cantidad de Dias entre las 2 Fechas
			$dias=restarFechas($fechaIMod,$fechaFMod)+1;
			if ($dias>10 && $dias<=31)
				$anchoTabla="150%";
			if ($dias>31 && $dias<=60)
				$anchoTabla="250%";
			if ($dias>60)
				$anchoTabla="500%";
			$verificaMes=0;
			//Partir la Fecha de Inicio en secciones de dia, mes y año
			$diaI=substr($fechaI,0,2);
			$mesI=substr($fechaI,3,2);
			$anioI=substr($fechaI,-4);
			//Obtener la cantidad de Dias del primer Mes
			$cantDiasMesCurso=diasMes($mesI,$anioI);
			//Convertir en numero los dias,mes y año de la Fecha de Inicio
			$diasActual=0+$diaI;
			$mesActual=0+$mesI;
			$anioActual=0+$anioI;
			//Partir la Fecha de Fin en secciones de dia, mes y año
			$diaF=substr($fechaF,0,2);
			$mesF=substr($fechaF,3,2);
			$anioF=substr($fechaF,-4);
			//Convertir en numero los dias,mes y año de la Fecha de Inicio
			$diasTope=0+$diaF;
			$mesTope=0+$mesF;
			$anioTope=0+$anioF;
			
			//Comenzar a dibujar la Tabla
			echo "<table class='tabla_frm' cellpadding='5' width='$anchoTabla' id='tabla-resultadosKardex'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>";
			echo "
				<thead>
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>RFC</th>
						<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>";
			//Obtener en el contador como primer valor
			$cont=$diasActual;
			//Arreglo con la cantidad de Dias por Mes
			$cantDias=array();
			//Arreglo con las Fechas
			$fechas=array();
			//Proceso cuando el año de tope e inicial son iguales
			if ($anioTope==$anioActual){
				//Proceso cuando el mes de Tope es mayor al Actual
				if ($mesTope>$mesActual){
					/***********************************/
					$mes=obtenerNombreMes($mesActual);
					$cols=($cantDiasMesCurso-$diasActual)+1;
					$cantDias[]=$cols;
					$ctrlFechas=$diasActual;
					do{
						$fechas[]=$anioActual."-".$mesActual."-".$cont;
						$cont++;
					}while($cont<=$cantDiasMesCurso);
					//Dibujar la columna del primer Mes
					echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
					/***********************************/
					if(($mesActual+1)<$mesTope){
						//Siguientes Meses hasta antes del Tope
						do{
							$mesActual=$mesActual+1;
							$cantDiasMesCurso=diasMes($mesActual,$anioActual);
							$cont=1;
							do{
								$fechas[]=$anioActual."-".$mesActual."-".$cont;
								$cont++;
							}while($cont<=$cantDiasMesCurso);
							/***********************************/
							$mes=obtenerNombreMes($mesActual);
							$cols=$cantDiasMesCurso;
							$cantDias[]=$cols;
							//Dibujar la columna del primer Mes
							echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
							/***********************************/
						}while(($mesActual+1)<$mesTope);
					}
					//Mes Tope
					$mesActual=$mesTope;
					$cont=1;
					do{
						$fechas[]=$anioActual."-".$mesActual."-".$cont;
						$cont++;
					}while($cont<=$diasTope);
					/***********************************/
					$mes=obtenerNombreMes($mesActual);
					$cols=$diasTope;
					$cantDias[]=$cols;
					//Dibujar la columna del primer Mes
					echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
					/***********************************/
				}
				//Procesos cuando el mes de Tope y de inicio son iguales
				else{
					if($mesTope==$mesActual){
						do{
							$fechas[]=$anioActual."-".$mesActual."-".$cont;
							$cont++;
						}while($cont<=$diaF);
					}
					/***********************************/
					$mes=obtenerNombreMes($mesActual);
					$cols=($diaF-$diaI)+1;
					$cantDias[]=$cols;
					//Dibujar la columna del primer Mes
					echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
					/***********************************/
				}
			}
			//Proceso cuando los años son diferentes
			else{
				
				//if($mesActual<=$mesTope){
					$ctrl=1;
					//Primer Mes
					do{
						$fechas[]=$anioActual."-".$mesActual."-".$cont;
						$cont++;
					}while($cont<=$cantDiasMesCurso);
					/***********************************/
					$mes=obtenerNombreMes($mesActual);
					$cols=($cantDiasMesCurso-$diasActual)+1;
					$cantDias[]=$cols;
					//Dibujar la columna del primer Mes
					echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
					/***********************************/
					$estado=0;
					//Meses Siguientes
					do{
						$mesActual++;
						if($mesActual>12){
							$mesActual=$mesActual-12;
							$anioActual++;
						}
						$cantDiasMesCurso=diasMes($mesActual,$anioActual);
						/***********************************/
						$mes=obtenerNombreMes($mesActual);
						$cols=$cantDiasMesCurso;
						$cantDias[]=$cols;
						//Dibujar la columna del primer Mes
						echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
						/***********************************/
						$cont=1;
						do{
							$fechas[]=$anioActual."-".$mesActual."-".$cont;
							$cont++;
						}while($cont<=$cantDiasMesCurso);
						if ($anioActual==$anioTope && $mesActual==($mesTope-1))
							$estado=1;
					}while($estado!=1);
					//Ultimo Mes
					$cont=1;
					do{
						$fechas[]=$anioActual."-".$mesActual."-".$cont;
						$cont++;
					}while($cont<=$diasTope);
					/***********************************/
					$mes=obtenerNombreMes($mesTope);
					$cols=$diasTope;
					$cantDias[]=$cols;
					//Dibujar la columna del primer Mes
					echo "<td class='nombres_columnas' align='center' colspan='$cols'>$mes</td>";
					/***********************************/
				//}
			}
			echo "</tr>";
			//Obtener la cantidad de Dias entre las 2 Fechas
			$diasTotales=restarFechas($fechaIMod,$fechaFMod)+1;
			//Contador para recorrer el arreglo de los Dias de cada Mes
			$cont=0;
			//Cantidad de Registros para mostrar el numero de dias
			$tamDias=count($cantDias);
			echo "<tr>";
			do{
				//Registro Primer Mes
				if ($cont==0){
					if($tamDias==1)
						$cantDiasMesCurso=$diasTope;
					else
						$cantDiasMesCurso=diasMes($mesI,$anioI);
					$ctrl=$diaI;
					do{
						if (strlen($ctrl)!=2)
							echo "<td class='nombres_columnas' align='center'>0$ctrl</td>";
						else
							echo "<td class='nombres_columnas' align='center'>$ctrl</td>";
						$ctrl++;
					}while($ctrl<=$cantDiasMesCurso);
				}
				//Registro Siguientes Meses
				if ($cont>0){
					//Variable para mostrar los numeros de la fecha en la columna
					$ctrl=1;
					do{
						if (strlen($ctrl)!=2)
							echo "<td class='nombres_columnas' align='center'>0$ctrl</td>";
						else
							echo "<td class='nombres_columnas' align='center'>$ctrl</td>";
						$ctrl++;
					}while($ctrl<=$cantDias[$cont]);
				}
				$cont++;
			}while($cont<$tamDias);
			echo "</tr>
					</thead>";
			
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			//Llenado de Datos de la Tabla
			do{
				//$fechaIng=modFecha($datos["fecha_ingreso"],1);
				//if ($fechaIng=="00/00/0000")
					//$fechaIng="<label class='msje_incorrecto' title='No se Ha Proporcionado la Fecha de Ingreso, se Recomienda Solucionar este detalle desde la Secci&oacute;n de Modificar Empleado(s)'>N/D</label>";
				echo "<tr>";
				echo "<td class='nombres_filas' align='center'>$datos[rfc_empleado]</td>";
				echo "<td class='$nom_clase' align='left'>$datos[nombre]</td>";
				//echo "<td class='$nom_clase' align='center'>$fechaIng</td>";
				$ctrl=0;
				do{
					//Funcion que obtiene la checada en caso de Existir
					$checada=obtenerChecada($fechas[$ctrl],$datos["rfc_empleado"],$cont);
					echo "<td class='$nom_clase' align='center'>$checada</td>";
					$ctrl++;
				}while($ctrl<(count($fechas)));
				echo "</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			return $consulta;

			echo "</table>";
		}////Fin del IF que verifica que existan resultados en la consulta
		else{
			echo "<meta http-equiv='refresh' content='0;url=frm_conIncapacidadesRH.php?noResults'>";

		}
	}//Cierre de la funcion reporteAsistencias()
	
	
	//Funcion para obtener el nombre de los Meses en la consulta de Kardex (Recursos Humanos)
	function obtenerNombreMes($mes){
		//Comparar el valor de Mes para obtener su nombre de Mes correspondiente
		switch($mes){
			case 1:
				$mes="ENERO";
				break;
			case 2:
				$mes="FEBRERO";
				break;
			case 3:
				$mes="MARZO";
				break;
			case 4:
				$mes="ABRIL";
				break;
			case 5:
				$mes="MAYO";
				break;
			case 6:
				$mes="JUNIO";
				break;
			case 7:
				$mes="JULIO";
				break;
			case 8:
				$mes="AGOSTO";
				break;
			case 9:
				$mes="SEPTIEMBRE";
				break;
			case 10:
				$mes="OCTUBRE";
				break;
			case 11:
				$mes="NOVIEMBRE";
				break;
			case 12:
				$mes="DICIEMBRE";
				break;
		}
		return $mes;
	}
	
	function obtenerChecada($fecha,$rfc,$ctrl){
		//Hacer un split a la Fecha por los guiones
		$fechaArray=split("-",$fecha);
		//Si el mes en la fecha es de un digito, colocar un 0, a la izquiera
		if(strlen($fechaArray[1])<2)
			$fechaArray[1]="0".$fechaArray[1];
		//Si el dia en la fecha es de un digito, colocar un 0, a la izquiera
		if(strlen($fechaArray[2])<2)
			$fechaArray[2]="0".$fechaArray[2];
		//Reensamblar la Fecha con los guiones dejandola con el formato aaaa-mm-dd
		$fecha=$fechaArray[0]."-".$fechaArray[1]."-".$fechaArray[2];
		//Sentencia SQL para extraer la checada de la tabla correspondiente
		$stm_sql="SELECT DISTINCT fecha_checada, estado FROM checadas WHERE empleados_rfc_empleado='$rfc' AND fecha_checada='$fecha' AND estado!='SALIDA' 
		AND fecha_checada!='' ORDER BY fecha_checada,hora_checada";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		$estado="";
		$nombre=obtenerNombreEmpleado($rfc);
		$fechaMostrar=modFecha($fecha,2);
		$titulo="$nombre NO Tiene Registro de Incidencia el $fechaMostrar";
		//Variables para controlar el color de Fondo y Letra en caso de haber o no, datos
		$color="";
		//Si la consulta regresa resultados, verificarlos
		if ($datos=mysql_fetch_array($rs)){
			$estado=$datos["estado"];
			$color="background-color:#669900";
			if ($estado=="A")
				$color.=";color:#669900";
			$titulo="$nombre tiene ".obtenerIncidencia($estado)." el $fechaMostrar";
		}
		$fecha=str_replace("-","°",$fecha);
		$checada="<input type='text' name='ckb_$fecha $ctrl' id='ckb_$fecha $ctrl' class='caja_de_num' size='1' readonly='readonly' value='$estado' style='font-size:19px;cursor:default;$color' title='$titulo'/>";
		return $checada;
	}
	
	function obtenerIncidencia($estado){
		$inc="";
		switch($estado){
			case "A":
				$inc="Asistencia";
			break;
			case "F":
				$inc="Falta";
			break;
			case "V":
				$inc="Vacaciones";
			break;
			case "r":
				$inc="Retardo";
			break;
			case "F/J":
				$inc="Falta Justificada";
			break;
			case "P":
				$inc="Permiso Sin Goce de Sueldo";
			break;
			case "P/G":
				$inc="Permiso Con Goce de Sueldo";
			break;
			case "E":
				$inc="Incapacidad por Enfermedad General";
			break;
			case "RT":
				$inc="Incapacidad por Accidente de Trabajo";
			break;
			case "T":
				$inc="Incapacidad en Trayecto";
			break;
			case "D":
				$inc="Sanción Discplinaria";
			break;
			case "R":
				$inc="Regresaron";
			break;
		}
		return strtoupper($inc);
	}
	
		function borrarHistorial(){
		 //Esta función elimina los graficos generados durante las consultas y se presione un boton de cancelar
		$h=opendir('tmp');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.png'){
				unlink('tmp/'.$file);
			}
		}
		closedir($h);
	}
?>