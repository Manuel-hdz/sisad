<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 01/Marzo/2011
	  * Descripción: Este archivo contiene funciones para Realizar consultas a otros departamentos desde el Modulo de Mantenimiento
	**/

	//Funcion que muestra los proveedores, esta consulta es hecha al Departamento de Compras
	function mostrarProveedores(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");		
		//Escribimos la consulta a realizarse por Servicio o Material
		$stm_sql = "SELECT * FROM proveedores";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='1200' id='tabla-resultadosProveedores'> 
				<thead>
				";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>RFC <img src='../../images/orden.png' width='5' height='10' border='0'/></th>
						<th class='nombres_columnas' align='center'>RAZÓN SOCIAL <img src='../../images/orden.png' width='5' height='10' border='0'/></th>
						<td class='nombres_columnas' align='center'>CALLE</td>
						<td class='nombres_columnas' align='center'>NÚMERO EXTERNO</td>
						<td class='nombres_columnas' align='center'>NÚMERO INTERNO</td>
						<td class='nombres_columnas' align='center'>COLONIA</td>
						<td class='nombres_columnas' align='center'>CÓDIGO POSTAL</td>
						<th class='nombres_columnas' align='center'>CIUDAD <img src='../../images/orden.png' width='5' height='10' border='0'/></th>
						<th class='nombres_columnas' align='center'>ESTADO <img src='../../images/orden.png' width='5' height='10' border='0'/></th>
						<td class='nombres_columnas' align='center'>TELÉFONO</td>
						<td class='nombres_columnas' align='center'>TELÉFONO 2 </td>
						<td class='nombres_columnas' align='center'>FAX</td>
						<th class='nombres_columnas' align='center'>RELEVANCIA <img src='../../images/orden.png' width='5' height='10' border='0'/></th>
						<td class='nombres_columnas' align='center'>CORREO</td>
						<td class='nombres_columnas' align='center'>CORREO  2</td>
						<th class='nombres_columnas' align='center'>CONTACTO <img src='../../images/orden.png' width='5' height='10' border='0'/></th>
						<th class='nombres_columnas' align='center'>MATERIAL DE SERVICIO <img src='../../images/orden.png' width='5' height='10' border='0'/></th>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{										
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$datos[rfc]</td>					
						<td class='$nom_clase' align='left'>$datos[razon_social]</td>
						<td class='$nom_clase' align='left'>$datos[calle]</td>
						<td class='$nom_clase' align='center'>$datos[numero_ext]</td>
						<td class='$nom_clase' align='center'>$datos[numero_int]</td>					
						<td class='$nom_clase' align='left'>$datos[colonia]</td>
						<td class='$nom_clase' align='center'>$datos[cp]</td>
						<td class='$nom_clase' align='left'>$datos[ciudad]</td>
						<td class='$nom_clase' align='left'>$datos[estado]</td>					
						<td class='$nom_clase' align='center'>$datos[telefono]</td>
						<td class='$nom_clase' align='center'>$datos[telefono2]</td>
						<td class='$nom_clase' align='center'>$datos[fax]</td>
						<td class='$nom_clase' align='center'>$datos[relevancia]</td>
						<td class='$nom_clase' align='left'>$datos[correo]</td>
						<td class='$nom_clase' align='left'>$datos[correo2]</td>
						<td class='$nom_clase' align='left'>$datos[contacto]</td>
						<td class='$nom_clase' align='left'>$datos[mat_servicio]</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";			
		}else{
			//Si no hay provedores registrados por Compras, se indica esto al usuario
			echo "<label class='msje_correcto'>No existen Proveedores Registrados en el Sistema</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
	}//Fin de la funcion de mostrarProveedores
	
	//Funcion que muestra los materiales que existen en el Stock de Almacén
	function mostrarMateriales(){
		//Realizar la conexion a la BD de Almacen
		$conn = conecta("bd_almacen");		
		//Escribimos la consulta a realizarse por Servicio o Material
		$stm_sql = "SELECT * FROM materiales JOIN unidad_medida ON id_material=materiales_id_material ORDER BY linea_articulo";
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='1200' id='tabla-resultadosMateriales'>      			
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>CLAVE</th>
        				<th class='nombres_columnas' align='center'>NOMBRE (DESCRIPCION)</th>
				        <th class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</th>
        				<th class='nombres_columnas' align='center'>LINEA DEL ARTICULO (CATEGORIA)</th>
						<th class='nombres_columnas' align='center'>GRUPO</th>
						<th class='nombres_columnas' align='center'>EXISTENCIA</th>
        				<th class='nombres_columnas' align='center'>PROVEEDOR</th>
						<td class='nombres_columnas' align='center'>FOTOGRAF&Iacute;A</td>
						<td class='nombres_columnas' align='center'>EQUIVALENCIAS</td>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			
			echo "<tbody>";
			do{	
			$ctrl_imagen = "";
				if($datos['mime']=="")
					$ctrl_imagen = "disabled='disabled'";
				$unidad_medida = obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $datos['id_material']);
				echo "	<tr>
						<td class='nombres_filas' align='center'>$datos[id_material]</td>
						<td class='$nom_clase' align='left'>$datos[nom_material]</td>
						<td class='$nom_clase' align='center'>$unidad_medida</td>
						<td class='$nom_clase' align='center'>$datos[linea_articulo]</td>
						<td class='$nom_clase' align='center'>$datos[grupo]</td>
						<td class='$nom_clase' align='center'>$datos[existencia]</td>
						<td class='$nom_clase' align='center'>$datos[proveedor]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verFoto" class="botones" value="Foto" onMouseOver="window.estatus='';return true" title="Ver Foto del Material <?php echo $datos['nom_material'];?>" 
							onClick="javascript:window.open('verImagenMaterial.php?id_material=<?php echo $datos['id_material']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" <?php echo $ctrl_imagen; ?>/>							
						</td>						
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verEquivalencias" class="botones" value="Equivalencias" onMouseOver="window.estatus='';return true" 
							title="Ver Equivalencias del Material <?php echo $datos['nom_material'];?>" 
							onClick="javascript:window.open('verEquivalencias.php?id_material=<?php echo $datos['id_material']; ?>',
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>				
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
					
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";			
		}
		else{
			//Si no hay provedores registrados por Compras, se indica esto al usuario
			echo "<label class='msje_correcto'>No existen Materiales Registrados en el Sistema</u></em></label>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
	}//Fin de la Funcion de mostrarMateriales

	//Función que permite mostrar el reporte de Asistencias
	function reporteAsistencias(){	
		//Recuperar el área seleccionada
		$area="MANTENIMIENTO";
		$conn=conecta("bd_recursos");
		//Sentencia SQL para extraer a los Trabajadores del Área DESARROLLO
		$stm_sql="SELECT rfc_empleado,fecha_ingreso, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE area='$area'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que existan registos
		if($datos=mysql_fetch_array($rs)){
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
			echo "<caption class='titulo_etiqueta'>Kardex</caption>";
			echo "
				<thead>
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>RFC</th>
						<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
						<th class='nombres_columnas' align='center' rowspan='2'>FECHA INGRESO</th>
						";
			
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
				$fechaIng=modFecha($datos["fecha_ingreso"],1);
				if ($fechaIng=="00/00/0000")
					$fechaIng="<label class='msje_incorrecto' title='No se Ha Proporcionado la Fecha de Ingreso, se Recomienda Solucionar este detalle desde la Secci&oacute;n de Modificar Empleado(s)'>N/D</label>";
				echo "<tr>";
				echo "<td class='nombres_filas' align='center'>$datos[rfc_empleado]</td>";
				echo "<td class='$nom_clase' align='left'>$datos[nombre]</td>";
				echo "<td class='$nom_clase' align='center'>$fechaIng</td>";
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
			echo "</table>";
		}////Fin del IF que verifica que existan resultados en la consulta
		else{
			echo "SIN DATOS";
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
		$stm_sql="SELECT estado FROM checadas WHERE empleados_rfc_empleado='$rfc' AND fecha_checada='$fecha' AND estado!='SALIDA' ORDER BY fecha_checada,hora_checada";
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
?>