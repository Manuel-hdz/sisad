<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 12/Septiembre/2012
	  * Descripción: Permite generar reportes de asistencia de los empleados 
	**/
	
	//Función que permite mostrar el reporte de Asistencias
	function reporteAsistencias(){	
		//Recuperar el área seleccionada
		$area="CONCRETO";
		$conn=conecta("bd_recursos");
		//Sentencia SQL para extraer a los Trabajadores del Área DESARROLLO
		$stm_sql="SELECT rfc_empleado,fecha_ingreso, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados WHERE area='$area'";
		//Variable para asignar un solo estado al rango de fechas seleccionado
		$incidenciaFechas="";
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
			echo "<table class='tabla_frm' cellpadding='5' id='tabla-resultadosKardex' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Kardex</caption>";
			echo "
				<thead>
					<tr>
						<th class='nombres_columnas' align='center' rowspan='2'>RFC</th>
						<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE DEL TRABAJADOR</th>
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
				//Remplazar los espacios vacios por _ con el color del fondo
				if($nom_clase=="renglon_gris")
					$color="#E7E7E7";
				else
					$color="#FFFFFF";
				$nombre=str_replace(" ","<label style='color:$color'>_</label>",$datos["nombre"]);
				echo "<td class='$nom_clase' align='left'>$nombre</td>";
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
				//Obtener el RFC en caso que se vaya a dibujar el boton
				if($incidenciaFechas=="activar")
					$incidenciaFechas=$datos["rfc_empleado"];
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";			
			return array_sum($cantDias);
		}////Fin del IF que verifica que existan resultados en la consulta
		else{
			//Este punto debe ser inalcanzable
		}
	}//Cierre de la funcion 
	
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
		//Variables para controlar el color de Fondo y Letra en caso de haber o no, datos
		$color="";
		//Si la consulta regresa resultados, verificarlos
		if ($datos=mysql_fetch_array($rs)){
			$estado=$datos["estado"];
			$color="background-color:#669900";
			if ($estado=="A")
				$color.=";color:#669900";
		}
		$fecha=str_replace("-","°",$fecha);
		switch($estado){
			case "A":
				$desc="ASISTENCIA";
				$clase="msje_correcto";
			break;
			case "d":
				$desc="DESCANSO";
				$clase="msje_correcto";
			break;
			case "V":
				$desc="VACACIONES";
				$clase="msje_correcto";
			break;
			case "r":
				$desc="RETARDO";
				$clase="msje_correcto";
			break;
			case "F/J":
				$desc="FALTA JUSTIFICADA";
				$clase="msje_correcto";
			break;
			case "P/G":
				$desc="PERMISO CON GOCE DE SUELDO";
				$clase="msje_correcto";
			break;
			case "F":
				$desc="FALTA";
				$clase="msje_incorrecto";
			break;
			case "P":
				$desc="PERMISO SIN GOCE DE SUELDO";
				$clase="msje_incorrecto";
			break;
			case "D":
				$desc="SANCI&Oacute;N DISCIPLINARIA";
				$clase="msje_incorrecto";
			break;
			case "R":
				$desc="REGRESADO";
				$clase="msje_incorrecto";
			break;
			case "E":
				$desc="INCAPACIDAD POR ENFERMEDAD GENERAL";
				$clase="msje_incorrecto";
			break;
			case "RT":
				$desc="INCAPACIDAD POR ACCIDENTE DE TRABAJO";
				$clase="msje_incorrecto";
			break;
			case "T":
				$desc="INCAPACIDAD EN TRAYECTO";
				$clase="msje_incorrecto";
			break;
			default:
				$desc="NO EXISTE REGISTRO";
				$clase="";
			break;
		}
		$checada="<label class='$clase' title='Nombre: $nombre \nFecha: $fechaMostrar \nDescripci&oacute;n: $desc'>$estado</label>";
		return $checada;
	}
?>