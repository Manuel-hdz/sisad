<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 18/Octubre/2011
	  * Descripción: Este archivo contiene funciones para Realizar consultas Externas
	**/
	
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
        				<th class='nombres_columnas' align='center'>NOMBRE (DESCRIPCI&Oacute;N)</th>
				        <th class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</th>
        				<th class='nombres_columnas' align='center'>L&Iacute;NEA DEL ART&Iacute;CULO (CATEGOR&Iacute;A)</th>
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
							'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" <?php echo $ctrl_imagen; ?> />							
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
		$area="DESARROLLO";
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

	//Funcion que permite mostrar las fotografías registradas al presionar el boton
	function mostrarPlanos(){
		//Arcivos que se incluyen para obtener informacion de la bitácora
		include_once ("../../includes/conexion.inc");
		include_once ("../../includes/op_operacionesBD.php");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Realizar la conexion con la BD
		$conn = conecta("bd_topografia");
		
		//Tomamos los datos que vienen del post y las modificamos para la consulta
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Ruta donde se almacenan los documentos
		$carpeta="documentos/";
		
		//Revisar si se han agregado fotos a la Base de Datos
		$stm_sql="SELECT id_plano, nom_plano, descripcion, fecha, hora, nom_archivo FROM planos WHERE fecha>='$fechaIni' AND fecha<='$fechaFin'";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se hayan encontrado resultados
		if ($datos=mysql_fetch_array($rs)){
			echo "				
				<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>Planos Registrados de <em><u>".modFecha($fechaIni,1)."</em></u> A <em><u>".modFecha($fechaFin,1)."</em></u> </caption></br>";
			echo "
				<tr>
					<td class='nombres_columnas' align='center'>ID PLANO</td>
					<td class='nombres_columnas' align='center'>NOMBRE PLANO</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>HORA</td>
					<td class='nombres_columnas' align='center'>NOMBRE ARCHIVO</td>
					<td class='nombres_columnas' align='center'>PLANO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			//Creamos la variable que permitira saber si los archivos de la BD corresponden con los del servidor
			$contArchivos=0;
			//Contador para saber el numero de revisiones que hace dentro de la carpeta seleccionada
			$contador=0;
			do{										
				echo "
					<tr>
						<td class='$nom_clase' align='center'>$datos[id_plano]</td>
						<td class='$nom_clase' align='center'>$datos[nom_plano]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[hora]</td>
						<td class='$nom_clase' align='center'>$datos[nom_archivo]</td>";?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_Archivo" class="botones" value="Ver Plano" onMouseOver="window.estatus='';return true" 
							title="Ver Plano<?php echo $datos['nom_archivo'];?>" 
							onClick="javascript:window.open('verPlano.php?id_plano=<?php echo $datos['nom_archivo'];?>&fecha=<?php echo $datos['fecha'];?>&hora=<?php echo $datos['hora'];?>',
							'_blank','top=50, left=50, width=200, height=200, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
						</td>
						<?php
												
				echo "</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";			
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		}
		else{
			echo "<label class='msje_correcto' align='center'><b>No Hay Planos Registrados de <em><u>".modFecha($fechaIni,1)."</em></u> A <em><u>".modFecha($fechaFin,1)."</em></u></b></label>";
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Fin de la funcion mostrarPlanos
	
	//Funcion que muestra el consumo de Aceites en Mantenimiento dada una fecha
	function reporteAceites(){
		//Arcivos que se incluyen para obtener informacion de la bitácora
		include_once ("../../includes/conexion.inc");
		include_once ("../../includes/op_operacionesBD.php");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Realizar la conexion con la BD
		$conn = conecta("bd_mantenimiento");
		
		//Tomamos los datos que vienen del post y las modificamos para la consulta
		$fecha=modFecha($_POST["txt_fecha"],3);
		
		//Revisar si se han agregado fotos a la Base de Datos
		$stm_sql="SELECT nom_aceite,fecha,SUM(bitacora_aceite.cantidad) AS aceiteConsumido,turno,supervisor_mtto FROM bitacora_aceite 
					JOIN catalogo_aceites ON catalogo_aceites_id_aceite=id_aceite WHERE fecha='$fecha' AND tipo_mov='S' 
					GROUP BY nom_aceite,turno,supervisor_mtto ORDER BY fecha,nom_aceite,turno,supervisor_mtto";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar que se hayan encontrado resultados
		if ($datos=mysql_fetch_array($rs)){
			echo "
				<table cellpadding='5' width='100%' align='center' id='tablaResultados'> 
				<caption class='titulo_etiqueta'>Registro de Consumo de Aceites del <em><u>".modFecha($fecha,1)."</em></u></caption></br>";
			echo "
				<tr>
					<td class='nombres_columnas' align='center'>NOMBRE ACEITE</td>
					<td class='nombres_columnas' align='center'>TURNO</td>
					<td class='nombres_columnas' align='center'>SUPERVISOR DE MANTENIMIENTO EN TURNO</td>
					<td class='nombres_columnas' align='center'>CONSUMO DE ACEITE</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "<tr>";
				echo "	
						<td class='$nom_clase' align='center'>$datos[nom_aceite]</td>
						<td class='$nom_clase' align='center'>$datos[turno]</td>
						<td class='$nom_clase' align='center'>$datos[supervisor_mtto]</td>
						<td class='$nom_clase' align='center'>".number_format($datos["aceiteConsumido"],2,".",",")." LTS</td>
					";
				echo "</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";			
			}while($datos=mysql_fetch_array($rs));
			//Obtener el Total por cada Aceite
			$stm_sql="SELECT nom_aceite,SUM(bitacora_aceite.cantidad) AS aceiteConsumido FROM bitacora_aceite JOIN catalogo_aceites 
						ON catalogo_aceites_id_aceite=id_aceite WHERE fecha='$fecha' AND tipo_mov='S' GROUP BY nom_aceite ORDER BY nom_aceite";
			//Ejecutar sentencia SQL
			$rs2=mysql_query($stm_sql);
			//Extraer los datos
			$datos2=mysql_fetch_array($rs2);
			do{
				echo "
				<tr>
					<td align='right' colspan='3'><strong>CONSUMO DE $datos2[nom_aceite]</strong></td>
					<td align='center' class='nombres_columnas'>".number_format($datos2["aceiteConsumido"],2,".",",")." LTS</td>
				</tr>";
			}while($datos2=mysql_fetch_array($rs2));
			echo "</table>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
			$grafica=dibujarGraficaAceites($fecha);
			return $grafica;
		}
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
			?>
				<script language="javascript" type="text/javascript">
					location.href='frm_consultarMantenimiento.php?noResults=<?php echo modFecha($fecha,1);?>';
				</script>
			<?php
		}
	}//Fin de la funcion reporteAceites
	
	//Funcion que dibuja la grafica del consumo de Aceistes por Turno y Aceite
	function dibujarGraficaAceites($fecha){
		$conn=conecta("bd_mantenimiento");
		//Obtener el Total por cada Aceite
		$stm_sql="SELECT DISTINCT catalogo_aceites_id_aceite,nom_aceite FROM bitacora_aceite JOIN catalogo_aceites ON catalogo_aceites_id_aceite=id_aceite WHERE fecha='$fecha' AND tipo_mov='S' ORDER BY nom_aceite";
		//Ejecutar sentencia SQL
		$rs=mysql_query($stm_sql);
		//Extraer los datos
		$datos=mysql_fetch_array($rs);
		//Declarar los arreglos para guardar los aceites y sus datos
		$nomAceites=array();
		$aceitePrimera=array();
		$aceiteSegunda=array();
		$aceiteTercera=array();
		do{
			//Obtener el nombre de los Aceites
			$nomAceites[]=$datos["nom_aceite"];

			//Obtener el consumo de Aceite en el Turno de primera
			$aceite1=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS consumo FROM bitacora_aceite WHERE fecha='$fecha' AND turno='PRIMERA' AND catalogo_aceites_id_aceite='$datos[catalogo_aceites_id_aceite]'"));
			if($aceite1["consumo"]==NULL)
				$aceitePrimera[]=0;
			else
				$aceitePrimera[]=$aceite1["consumo"];
			//Obtener el consumo de Aceite en el Turno de segunda
			$aceite2=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS consumo FROM bitacora_aceite WHERE fecha='$fecha' AND turno='SEGUNDA' AND catalogo_aceites_id_aceite='$datos[catalogo_aceites_id_aceite]'"));
			if($aceite2["consumo"]==NULL)
				$aceiteSegunda[]=0;
			else
				$aceiteSegunda[]=$aceite2["consumo"];
			//Obtener el consumo de Aceite en el Turno de tercera
			$aceite3=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS consumo FROM bitacora_aceite WHERE fecha='$fecha' AND turno='TERCERA' AND catalogo_aceites_id_aceite='$datos[catalogo_aceites_id_aceite]'"));
			if($aceite3["consumo"]==NULL)
				$aceiteTercera[]=0;
			else
				$aceiteTercera[]=$aceite3["consumo"];
		}while($datos=mysql_fetch_array($rs));
		//Cerrar la conexion con la BD
		mysql_close($conn);
		//Incluir las funciones para cibujar las graficas
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_bar.php');
		//Declarar la variable para regresar el nombre de la primer grafica
		$grafica1="";
		//Obtener la cantidad de Registros
		$cantRes=count($nomAceites);
		//Registros por Grafica
		$cantDatos=3;
		//Obtener la cantidad de graficas
		$ciclos=$cantRes/$cantDatos;
		//Redondear el valor de los ciclos
		$ciclos=intval($ciclos);
		//Obtener el residuo para saber si incrementar en 1 la cantidad de ciclos
		$residuo=$cantRes%$cantDatos;
		//Si residuo es mayor a 0, incrementar en uno los ciclos
		if($residuo>0)
			$ciclos+=1;
		//Inicializar variable de control para la cantidad de ciclos
		$cont=0;
		//Contador por cada grafica a dibujar
		$contPorGrafica=0;
		do{
			//Declarar el arreglo de cada Aceite por cada grafica
			$turnoP=array();
			$turnoS=array();
			$turnoT=array();
			//Declarar el arreglo de leyendas por cada grafica
			$leyendaPorGrafica=array();
			//Obtener los datos a graficar
			do{
				//Obtener el consumo para Aceite por turno
				$turnoP[]=$aceitePrimera[$contPorGrafica];
				$turnoS[]=$aceiteSegunda[$contPorGrafica];
				$turnoT[]=$aceiteTercera[$contPorGrafica];
				//Asignar a la posicion actual la leyenda en la posicion que corresponde
				$leyendaPorGrafica[]=$nomAceites[$contPorGrafica];
				//Incrementar la variable de control por cada grafica
				$contPorGrafica++;
			}while(count($leyendaPorGrafica)<$cantDatos && $contPorGrafica<$cantRes);
			$datay1 = $turnoP;
			$datay2 = $turnoS;
			$datay3 = $turnoT;
			// Create the graph and setup the basic parameters
			$graph = new Graph(945,430,'auto');
			$graph->img->SetMargin(80,30,60,125);
			$graph->SetScale('textint');
			$graph->SetFrame(false);
			$graph->yaxis->SetLabelFormat('%.2f');
			// Setup X-axis labels
			$graph->xaxis->SetTickLabels($leyendaPorGrafica);
			$graph->xaxis->SetLabelAngle(20);
			$graph->xaxis->SetFont(FF_VERDANA,FS_NORMAL,8);
			// Setup graph title ands fonts
			$graph->title->Set("Consumo de Aceite por Turno el ".modFecha($fecha,1));
			$graph->yaxis->scale->SetGrace(20);
			$graph->yaxis->SetTitleMargin(60);
			$graph->yaxis->title->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->yaxis->title->SetColor('darkred');
			$graph->yaxis->title->Set('Litros');
			//Pie de Tabla
			$graph->footer->center->Set('Aceite');
			$graph->footer->center->SetFont(FF_ARIAL,FS_BOLD,12);
			$graph->footer->center->SetColor('darkred');
			//
			$bplot1 = new BarPlot($datay1);
			$bplot2 = new BarPlot($datay2);
			$bplot3 = new BarPlot($datay3);
			$bplot1->SetFillColor("orange");
			$bplot2->SetFillColor("blue");
			$bplot3->SetFillColor("darkgreen");
			// Black color for positive values and darkred for negative values
			$gbarplot = new GroupBarPlot(array($bplot1,$bplot2,$bplot3));
			$gbarplot->SetWidth(0.6);
			$bplot1->value->Show();
			$bplot1->value->SetFormat('%.2f');
			$bplot2->value->Show();
			$bplot2->value->SetFormat('%.2f');
			$bplot3->value->Show();
			$bplot3->value->SetFormat('%.2f');
			$bplot1->SetLegend("PRIMERA");
			$bplot2->SetLegend("SEGUNDA");
			$bplot3->SetLegend("TERCERA");
			$graph->Add($gbarplot);
			//crear el nombre aleatorio de la grafica, generar el valor automaticamente en un rango de 0 a 1000
			$rnd=rand(0,1000);
			$grafica= "tmp/grafica".$rnd.".png";
			//Dibujar la grafica y guardarla en un archivo temporal	
			$graph->Stroke($grafica);
			$cont++;
			//Agregar la primer grafica al DIV principal
			if($cont==1)
				$grafica1=$grafica;
			//Agregar las siguientes graficas al DIV secundario
			else
				$grafica1.="¬".$grafica;
		}while($cont<$ciclos);
		return $grafica1;
	}
	
	//Funcion que muestra los Equipos con la posibilidad de seleccionarlos para mostrarlos en los Reportes
	function mostrarEquiposMttoC(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Creamos la sentencia SQL para mostrar los Equipos
		$stm_sql="SELECT id_equipo,nom_equipo,familia,asignado,proveedor FROM equipos WHERE area='CONCRETO' AND estado='ACTIVO' ORDER BY familia,id_equipo";
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		//Verificar los resultados de la consulta
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Seleccionar los Equipos a Mostrar en el Reporte</caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center' colspan='2'>CLAVE</td>
						<td class='nombres_columnas' align='center' rowspan='2'>NOMBRE EQUIPO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>FAMILIA</td>
						<td class='nombres_columnas' align='center' rowspan='2'>PROVEEDOR</td>
						<td class='nombres_columnas' align='center' rowspan='2'>EQUIPO ASIGNADO A</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='left' colspan='2'>
							<input type='checkbox' name='ckbTodo' id='ckbTodo' onclick='checarTodos(this);'/>Seleccionar Todos
						</td>
					</tr>
					";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>";
				?>
					<td class="nombres_filas" align="center">
						<input type="checkbox" name="ckb_equipo<?php echo $cont;?>" id="ckb_equipo<?php echo $cont;?>" value="<?php echo $datos['id_equipo']; ?>" onclick="desSeleccionar(this)"/>
					</td>
				<?php
				echo "	<td class='nombres_filas' align='center'>$datos[id_equipo]</td>					
						<td class='$nom_clase' align='left'>$datos[nom_equipo]</td>
						<td class='$nom_clase' align='left'>$datos[familia]</td>
						<td class='$nom_clase' align='left'>$datos[proveedor]</td>
						<td class='$nom_clase' align='left'>$datos[asignado]</td>
						";
				echo "	</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "<input type='hidden' name='hdn_cantEquipos' id='hdn_cantEquipos' value='$cont'/>";
			echo "</table>";
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}
	
	//Funcion para Mostrar la Disponibilidad de un Equipo por Fecha y Turno
	function reporteDisponibilidadFecha(){
		//Abrir la conexion con la BD de Mantenimiento
		$conn=conecta("bd_mantenimiento");
		//Obtener las Fechas en formato MySQL
		$fechaI=modFecha($_POST["hdn_fechaI"],3);
		$fechaF=modFecha($_POST["hdn_fechaF"],3);
		//Obtener los dias de Diferencia
		$diasDiff=restarFechas($fechaI,$fechaF);
		//Obtener la cantidad de checkbox escritos
		$cantidad=$_POST["hdn_cantEquipos"];
		//Contador para recorrer el arreglo POST
		$cont=1;
		//Recorrer el arreglo POST para verificar con que equipos realizar la rutina
		do{
			//Verificar que Equipo esta definido
			if(isset($_POST["ckb_equipo$cont"])){
				//Obtener el ID del Equipo
				$equipo=$_POST["ckb_equipo$cont"];
				//Sentencia SQL para verificar si tiene registro en la bitacora de Mantenimiento
				$sql_stm="SELECT id_bitacora,fecha_mtto,turno,horometro,odometro,tiempo_total,comentarios FROM bitacora_mtto 
						WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF' AND equipos_id_equipo='$equipo' ORDER BY fecha_mtto,turno";
				//Ejecutar la sentencia SQL
				$rs=mysql_query($sql_stm);
				//Verificar el resultado de la ejecucion de sentencia
				if($datos=mysql_fetch_array($rs)){
					echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
					echo "<caption class='titulo_etiqueta'>Disponibilidad del Equipo <em><u>$equipo</u></em></caption>";
					echo "	<tr>
								<td class='nombres_columnas' align='center'>FECHA</td>
								<td class='nombres_columnas' align='center'>TURNO</td>
								<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
								<td class='nombres_columnas' align='center'>TIEMPO MANTENIMIENTO</td>
								<td class='nombres_columnas' align='center'>% DISPONIBILIDAD</td>
								<td class='nombres_columnas' align='center'>DETALLES BIT&Aacute;CORA</td>
							</tr>";
					//Variable para controlar la cantidad de Dias
					$dias=0;
					//Ciclo para extraer los datos consultados
					do{
						//Sumar a a fecha los dias por cada ciclo
						$fecha=sumarDiasFecha($fechaI,$dias);
						//Contador para control de Turnos
						$contador=1;
						//Nombre de clase
						$nom_clase = "renglon_gris";		
						//Ciclo para controlar los turnos
						do{
							//Verificar y asignar el nombre del Turno
							switch($contador){
								case 1:
									$turno="TURNO DE PRIMERA";
								break;
								case 2:
									$turno="TURNO DE SEGUNDA";
								break;
								case 3:
									$turno="TURNO DE TERCERA";
								break;
							}
							//Si es el primer Turno, los renglones,columnas se deben mostrar diferente
							if($contador==1){
								//Variable con el titulo a asignar en caso que no se pueda calcular la disponibilidad
								$titulo="";
								//Variable para activar o desactivar el boton de consulta de la Bitacora de Mantenimiento
								$ctrl_btn="";
								//Si el turno es igual al de primera y la fecha de Mantenimiento tambien, calcular la disponibilidad
								if($datos["turno"]==$turno && $datos["fecha_mtto"]=="$fecha"){
									//Extraer los comentarios
									$comentarios=$datos["comentarios"];
									//Extraer el tiempo total del mantenimiento
									$tiempoTotal=$datos["tiempo_total"];
									//Calcular la disponibilidad del Equipo en la fecha y turno actuales
									$disponibilidad=calcularDisponibilidadFecha($fecha,$tiempoTotal,$equipo,$turno);
									/************Convertir el Tiempo Total a numero fraccionario*******************/
									//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
									$hora=split(":",$tiempoTotal);
									//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
									$hrs=intval($hora[0]);
									//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
									$min=intval($hora[1]);
									//Obtener el Tiempo Total en cantidad
									$tiempoTotal=round(($hrs+($min/60)),2);
									/************Fin Convertir el Tiempo Total a numero fraccionario****************/
									//Si la disponibilidad es de 0, quiere decir que no hay registro de metrica en esa Fecha y Turno
									if($disponibilidad==0){
										$titulo=" title='No se Puede Calcular la Disponibilidad en el $turno, ya que no hay Registro de Hor&oacute;metro/Od&oacute;metro del Equipo $equipo para ese Turno'";
										$disp="<label class='msje_incorrecto'>$disponibilidad%</label>";
									}
									//Si la disponibilidad es mayor a 0, indicarlo sin estilo especifico
									else
										$disp="<label>$disponibilidad%</label>";
								}
								//Si no es el turno ni la fecha actual, ingresar los datos de las variables directamente
								else{
									$comentarios="NO HAY REGISTROS DE SERVICIOS DE MANTENIMIENTO";
									$tiempoTotal="0.00";
									$disp="<label class='msje_correcto'>100%</label>";
									//Se deshabilita el boton puesto que no hay actividades de Mantenimiento que revisar
									$ctrl_btn=" disabled='disabled'";
								}
								//Dibujar el renglon para el turno de primera con los resultados obtenidos
								echo "	<tr>
											<td class='$nom_clase' align='center' rowspan='3'>".modFecha($fecha,1)."</td>
											<td class='$nom_clase' align='center'>$turno</td>
											<td class='$nom_clase' align='center'>$comentarios</td>
											<td class='$nom_clase' align='center'>$tiempoTotal HRS</td>
											<td class='$nom_clase' align='center'$titulo>$disp</td>";
											?>
											<td class="<?php echo $nom_clase?>" align="center">
												<input type="button" name="btn_verActividades<?php echo $cont.$contador?>" id="btn_verActividades<?php echo $equipo.$fecha.$contador?>" 
												class="botones" value="Ver Detalle" onMouseOver="window.estatus='';return true;" title="Ver Detalle del Equipo <?php echo $equipo;?>" 
												onClick="javascript:window.open('verDetalleBitacora.php?id_bitacora=<?php echo $datos['id_bitacora'];?>&btn=btn_verActividades<?php echo $equipo.$fecha.$contador?>',
												'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled='true'"
												<?php echo $ctrl_btn;?>/>
											</td>
											<?php
								echo "	</tr>";
								//Verificar que el boton deshabilitar este vacio, de ser asi encontro resultados
								if($ctrl_btn=="")
									//Si encontro resultados, pasar al siguiente Registro
									$datos=mysql_fetch_array($rs);
							}
							//Verificar que el turno sea el segundo o tercer mediante el contador
							if($contador>1){
								//Variable con el titulo a asignar en caso que no se pueda calcular la disponibilidad
								$titulo="";
								//Variable para activar o desactivar el boton de consulta de la Bitacora de Mantenimiento
								$ctrl_btn="";
								//Si el turno es igual al de primera y la fecha de Mantenimiento tambien, calcular la disponibilidad
								if($datos["turno"]==$turno && $datos["fecha_mtto"]=="$fecha"){
									//Extraer los comentarios
									$comentarios=$datos["comentarios"];
									//Extraer el tiempo total del mantenimiento
									$tiempoTotal=$datos["tiempo_total"];
									//Calcular la disponibilidad del Equipo en la fecha y turno actuales
									$disponibilidad=calcularDisponibilidadFecha($fecha,$tiempoTotal,$equipo,$turno);
									/************Convertir el Tiempo Total a numero fraccionario*******************/
									//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
									$hora=split(":",$tiempoTotal);
									//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
									$hrs=intval($hora[0]);
									//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
									$min=intval($hora[1]);
									//Obtener el Tiempo Total en cantidad
									$tiempoTotal=round(($hrs+($min/60)),2);
									/************Fin Convertir el Tiempo Total a numero fraccionario****************/
									//Si la disponibilidad es de 0, quiere decir que no hay registro de metrica en esa Fecha y Turno
									if($disponibilidad==0){
										$titulo=" title='No se Puede Calcular la Disponibilidad en el $turno, ya que no hay Registro de Hor&oacute;metro/Od&oacute;metro del Equipo $equipo para ese Turno'";
										$disp="<label class='msje_incorrecto'>$disponibilidad%</label>";
									}
									//Si la disponibilidad es mayor a 0, indicarlo sin estilo especifico
									else
										$disp="<label>$disponibilidad%</label>";
								}
								//Si no es el turno ni la fecha actual, ingresar los datos de las variables directamente
								else{
									$comentarios="NO HAY REGISTROS DE SERVICIOS DE MANTENIMIENTO";
									$tiempoTotal="0.00";
									$disp="<label class='msje_correcto'>100%</label>";
									//Se deshabilita el boton puesto que no hay actividades de Mantenimiento que revisar
									$ctrl_btn=" disabled='disabled'";
								}
								//Dibujar el renglon para los turnos de segunda o tercera segun corresponda con los resultados obtenidos
								echo "	<tr>
											<td class='$nom_clase' align='center'>$turno</td>
											<td class='$nom_clase' align='center'>$comentarios</td>
											<td class='$nom_clase' align='center'>$tiempoTotal HRS</td>
											<td class='$nom_clase' align='center'$titulo>$disp</td>";
											?>
											<td class="<?php echo $nom_clase?>" align="center">
												<input type="button" name="btn_verActividades<?php echo $equipo.$fecha.$contador?>" id="btn_verActividades<?php echo $equipo.$fecha.$contador?>" 
												class="botones" value="Ver Detalle" onMouseOver="window.estatus='';return true;" title="Ver Detalle del Equipo <?php echo $equipo;?>" 
												onClick="javascript:window.open('verDetalleBitacora.php?id_bitacora=<?php echo $datos['id_bitacora'];?>&btn=btn_verActividades<?php echo $equipo.$fecha.$contador?>',
												'_blank','top=50, left=50, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');this.disabled='true'"
												<?php echo $ctrl_btn;?>/>
											</td>
											<?php
								echo "	</tr>";
								//Verificar que el boton deshabilitar este vacio, de ser asi encontro resultados
								if($ctrl_btn=="")
									//Si encontro resultados, pasar al siguiente Registro
									$datos=mysql_fetch_array($rs);
							}
							//Incrementar el contador para el control de turnos
							$contador++;
							//En baseo al contador, indicar que clase debe tener el renglon
							if($contador%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						}while($contador<=3);//Ciclo por Turnos
						//Incrementar el contador para pasar al siguiente dia
						$dias++;
					}while($dias<=$diasDiff);//Ciclo por dias entre las fechas
					echo "</table><br>";//Cerrar la tabla y dar un "enter"
				}
				//En caso de No Encontrar resultados para el Equipo seleccionado, se pasa la Disponibilidad al 100%
				else{
					echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
					echo "<caption class='titulo_etiqueta'>Disponibilidad del Equipo <em><u>$equipo</u></em></caption>";
					echo "	<tr>
								<td class='nombres_columnas' align='center'>FECHA</td>
								<td class='nombres_columnas' align='center'>TURNO</td>
								<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
								<td class='nombres_columnas' align='center'>TIEMPO MANTENIMIENTO</td>
								<td class='nombres_columnas' align='center'>% DISPONIBILIDAD</td>
								<td class='nombres_columnas' align='center'>DETALLES BIT&Aacute;CORA</td>
							</tr>";
					//Variable para controlar la cantidad de Dias
					$dias=0;
					//Ciclo para controlar los dias entre las Fechas
					do{
						//Sumar los dias a la fecha de Inicio
						$fecha=sumarDiasFecha($fechaI,$dias);
						//Contador para el manejo de Turnos
						$contador=1;
						//Clase inicial para el renglon
						$nom_clase = "renglon_gris";
						//Ciclo para controlar los Turnos
						do{
							//Verificar el Turno actual
							switch($contador){
								case 1:
									$turno="TURNO DE PRIMERA";
								break;
								case 2:
									$turno="TURNO DE SEGUNDA";
								break;
								case 3:
									$turno="TURNO DE TERCERA";
								break;
							}
							//Ciclo de dibujo para el renglon del turno de primera
							if($contador==1){
								echo "	<tr>
											<td class='$nom_clase' align='center' rowspan='3'>".modFecha($fecha,1)."</td>
											<td class='$nom_clase' align='center'>$turno</td>
											<td class='$nom_clase' align='center'>NO HAY REGISTROS DE SERVICIOS DE MANTENIMIENTO</td>
											<td class='$nom_clase' align='center'>0 HRS</td>
											<td class='$nom_clase' align='center'><label class='msje_correcto'>100%</label></td>";
											?>
											<td class="<?php echo $nom_clase?>" align="center">
												<input type="button" name="btn_verActividades" id="btn_verActividades" class="botones" value="Ver Detalle" onMouseOver="window.estatus='';return true;" 
												title="Ver Detalle del Equipo <?php echo $equipo;?>" disabled="disabled"/>
											</td>
											<?php
								echo "	</tr>";
							}
							//Ciclo de dibujo para los renglones del turno de segunda y tercera
							if($contador>1){
								echo "	<tr>
											<td class='$nom_clase' align='center'>$turno</td>
											<td class='$nom_clase' align='center'>NO HAY REGISTROS DE SERVICIOS DE MANTENIMIENTO</td>
											<td class='$nom_clase' align='center'>0 HRS</td>
											<td class='$nom_clase' align='center'><label class='msje_correcto'>100%</label></td>";
											?>
											<td class="<?php echo $nom_clase?>" align="center">
												<input type="button" name="btn_verActividades" id="btn_verActividades" class="botones" value="Ver Detalle" onMouseOver="window.estatus='';return true;" 
												title="Ver Detalle del Equipo <?php echo $equipo;?>" disabled="disabled"/>
											</td>
											<?php
								echo "	</tr>";
							}
							//Incrementar el contador de Turnos
							$contador++;
							//Verificar que clase le toca al Renglon
							if($contador%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";
						}while($contador<=3);//Ciclo para turnos
						$dias++;//Incrementar los dias
					}while($dias<=$diasDiff);//Ciclo para fechas
					echo "</table><br>";//Cerrar la tabla y dar un "enter"
				}//Cierre del ELSE donde no hubo datos segun la consulta
			}//Cierre del if(isset($_POST["ckb_equipo$cont"]))
			$cont++;//Incrementar el contador para el manejo de Equipos en el POST
		}while($cont<$cantidad);//Ciclo de Equipos en el POST
		//Cerrar la conexion con la BD de Mantenimiento
		mysql_close($conn);
	}//Cierre de reporteDisponibilidadFecha()
	
	//Funcion para calcular la Disponibilidad de un Equipo pasando como parametros
	function calcularDisponibilidadFecha($fecha,$tiempoTotal,$equipo,$turno){
		//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
		$hora=split(":",$tiempoTotal);
		//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
		$hrs=intval($hora[0]);
		//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
		$min=intval($hora[1]);
		//Obtener el Tiempo Total en cantidad
		$tiempoMtto=round(($hrs+($min/60)),2);
		//Sentencia SQL
		$sql_stm="SELECT SUM(hrs_efectivas) AS hrs_servicio FROM horometro_odometro WHERE fecha='$fecha' AND equipos_id_equipo='$equipo' AND turno='$turno'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql_stm);
		//Extraer los datos de la consulta
		if($datos=mysql_fetch_array($rs)){
			//Si las Horas de Servicio son diferente de NULL, extraer los datos
			if($datos["hrs_servicio"]!=NULL){
				//Extraer los datos Obtenidos
				do{
					$hrs_servicio=$datos["hrs_servicio"];
				}while($datos=mysql_fetch_array($rs));
			}
			else
				$hrs_servicio=0;
		}
		//Calcular la disponibilidad siempre y cuando se pueda realizar, de lo contrario regresar 0,
		if($hrs_servicio!=0)
			$disponibilidad=round((100-(($tiempoMtto*100)/$hrs_servicio)),2);
		else
			$disponibilidad=$hrs_servicio;
		//Regresar el valor de la Disponibilidad
		return $disponibilidad;
	}
?>