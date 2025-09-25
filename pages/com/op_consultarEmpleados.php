<?php

	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández	
	  * Fecha: 15/Abril/2011
	  * Descripción: Este archivo contiene funciones para ver las Altas, bajas, prestamos, incapacidades e incidencias de Kardex en el Sistema
	  **/ 

	//Esta función se encarga de mostrar los empleados que fueron dados de baja, por area y fecha
	function mostrarBajasEmpleados(){		
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		//Recuperamos los datos necesarios del POST
		$area=$_POST["cmb_area"];
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT empleados_rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, fecha_ingreso, fecha_baja, area, puesto, 
					observaciones FROM bajas_modificaciones WHERE fecha_baja>='$fechaIni' AND fecha_baja<='$fechaFin' AND area='$area' AND fecha_baja!='0000-00-00' ORDER BY puesto";
		//Ejecutamos la sentencia
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' cellspacing='5' width='100%' id='tabla-resultados-empleado'>";
			echo "<caption class='titulo_etiqueta'>EMPLEADOS DADOS DE BAJA DEL &Aacute;REA: <u><em>$area</em></u></caption>
				<thead>";
			echo "<tr>							
					<th align='center' class='nombres_columnas'>NO.</th>
					<th align='center' class='nombres_columnas'>RFC</th>
					<th align='center' class='nombres_columnas'>NOMBRE</th>
					<td align='center' class='nombres_columnas'>FECHA INGRESO</td>
					<td align='center' class='nombres_columnas'>FECHA BAJA</td>
					<td align='center' class='nombres_columnas'>&Aacute;REA</td>
					<td align='center' class='nombres_columnas'>PUESTO</td>
					<td align='center' class='nombres_columnas'>OBSERVACIONES</td>
				</tr>
				
				</thead>";				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{
				echo "<tr>		
						<td align='center' class='$nom_clase'>$cont</td>
						<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_ingreso'],1)."</td>
						<td align='center' class='$nom_clase'>".modFecha($datos['fecha_baja'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[area]</td>
						<td align='center' class='$nom_clase'>$datos[puesto]</td>
						<td align='center' class='$nom_clase'>$datos[observaciones]</td>
				</tr>";									
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";	
			echo "</table>";
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
		echo "<label align='center' class='msje_correcto'>NO EXISTEN EMPLEADOS DADOS DE BAJA DEL &Aacute;REA: <u><em>$area</em></u> DE: <u><em>".modFecha($fechaIni,1)."</em></u> A <u><em>".modFecha($fechaFin,1)."</em></u></label>";?>								            
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcio
	
	
	//Esta función se encarga de mostrar los empleados que fueron dados de alta en area y fecha especificas
	function mostrarAltasEmpleados(){		
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		//Recuperamos los datos necesarios del POST
		$area=$_POST["cmb_area"];
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT rfc_empleado,CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, fecha_ingreso, area, puesto FROM empleados
					WHERE fecha_ingreso>='$fechaIni'AND fecha_ingreso<='$fechaFin' AND area='$area' ORDER BY puesto";
		//Ejecutamos la sentencia
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' cellspacing='5' width='100%' id='tabla-resultados-empleado'>";
			echo "<caption class='titulo_etiqueta'>EMPLEADOS DADOS DE ALTA DEL &Aacute;REA: <u><em>$area</em></u></caption>
				<thead>";
			echo "<tr>							
					<th align='center' class='nombres_columnas'>NO.</th>
					<th align='center' class='nombres_columnas'>RFC</th>
					<th align='center' class='nombres_columnas'>NOMBRE</th>
					<td align='center' class='nombres_columnas'>FECHA INGRESO</td>
					<td align='center' class='nombres_columnas'>&Aacute;REA</td>
					<td align='center' class='nombres_columnas'>PUESTO</td>
				</tr>
				
				</thead>";				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{
				echo "<tr>		
						<td align='center' class='$nom_clase'>$cont</td>
						<td align='center' class='$nom_clase'>$datos[rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>". modFecha($datos['fecha_ingreso'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[area]</td>
						<td align='center' class='$nom_clase'>$datos[puesto]</td>
				</tr>";									
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";	
			echo "</table>";
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
		echo "<label align='center' class='msje_correcto'>NO EXISTEN EMPLEADOS DADOS DE ALTA DEL &Aacute;REA: <u><em>$area</em></u> DE: <u><em>".modFecha($fechaIni,1)."</em></u> A <u><em>".modFecha($fechaFin,1)."</em></u></label>";?>								            
		<?php 
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcio	
	
	
	//Esta función se encarga de mostrar los empleados que fueron que tienen incapacidad en area y fecha especificas
	function mostrarIncapacidadesEmpleados(){		
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		//Recuperamos los datos necesarios del POST
		$area=$_POST["cmb_area"];
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT empleados_rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, fecha_entrada, area, puesto, kardex.estado 
					FROM (kardex JOIN empleados ON empleados_rfc_empleado=rfc_empleado) WHERE fecha_entrada>= '$fechaIni' AND fecha_entrada<='$fechaFin' 
					AND area='$area' AND kardex.estado LIKE 'I%' ORDER BY estado";
		//Ejecutamos la sentencia
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' cellspacing='5' width='100%' id='tabla-resultados-empleado'>";
			echo "<caption class='titulo_etiqueta'>EMPLEADOS CON INCAPACIDAD DEL &Aacute;REA: <u><em>$area</em></u></caption>
				<thead>";
			echo "<tr>							
					<th align='center' class='nombres_columnas'>NO.</th>
					<th align='center' class='nombres_columnas'>RFC</th>
					<th align='center' class='nombres_columnas'>NOMBRE</th>
					<td align='center' class='nombres_columnas'>FECHA ENTRADA</td>
					<td align='center' class='nombres_columnas'>&Aacute;REA</td>
					<td align='center' class='nombres_columnas'>PUESTO</td>
					<td align='center' class='nombres_columnas'>ESTADO</td>
				</tr>
				
				</thead>";				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{
				echo "<tr>		
						<td align='center' class='$nom_clase'>$cont</td>
						<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>". modFecha($datos['fecha_entrada'],1)."</td>
						<td align='center' class='$nom_clase'>$datos[area]</td>
						<td align='center' class='$nom_clase'>$datos[puesto]</td>";
						//Determinamos el nombre a mostrar
						if($datos['estado']=='I')
							echo"<td class='$nom_clase' align='center'> <label  class='msje_correcto'>INCAPACIDAD</label></td>";
						else if($datos['estado']=='IRT')
							echo"<td class='$nom_clase' align='center'><label  class='msje_incorrecto'>INCAPACIDAD POR RIESGO DE TRABAJO</label></td>";
						else
							echo"<td class='$nom_clase' align='center'> <label  class='msje_correcto'>INCAPACIDAD POR ENFERMEDAD</label></td>";
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
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
		echo "<label align='center' class='msje_correcto'>NO EXISTEN EMPLEADOS CON INCAPACIDAD DEL &Aacute;REA: <u><em>$area</em></u> DE: <u><em>".modFecha($fechaIni,1)."</em></u> A <u><em>".modFecha($fechaFin,1)."</em></u></label>";?>								            
		<?php
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcio	


	//Esta función se encarga de mostrar los empleados que fueron que tienen Prestaciones/Financiamientos en area y fecha especificas
	function mostrarPresFinEmpleados(){		
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		//Recuperamos los datos necesarios del POST
		$area=$_POST["cmb_area"];
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT empleados_rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, id_deduccion, nom_deduccion,fecha_abono, 
					detalle_abonos.abono, descripcion, puesto FROM ((deducciones JOIN empleados ON empleados_rfc_empleado=rfc_empleado) 
					JOIN detalle_abonos ON deducciones_id_deduccion=id_deduccion) WHERE fecha_abono>= '$fechaIni' AND fecha_abono<='$fechaFin' AND area='$area' 
					ORDER BY puesto";
		
		//Ejecutamos la sentencia
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' cellspacing='5' width='100%' id='tabla-resultados-empleado'>";
			echo "<caption class='titulo_etiqueta'>EMPLEADOS CON PR&Eacute;STAMOS/FINANCIAMIENTOS DEL &Aacute;REA: <u><em>$area</em></u></caption>
				<thead>";
			echo "<tr>							
					<td align='center' class='nombres_columnas'>NO.</td>
					<th align='center' class='nombres_columnas'>RFC</th>
					<th align='center' class='nombres_columnas'>NOMBRE</th>
					<th align='center' class='nombres_columnas'>ID DEDUCCI&Oacute;N</th>
					<td align='center' class='nombres_columnas'>NOMBRE DEDUCCI&Oacute;N</td>
					<td align='center' class='nombres_columnas'>FECHA DEDUCCI&Oacute;N</td>
					<td align='center' class='nombres_columnas'>DESCUENTO</td>
					<td align='center' class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
					<td align='center' class='nombres_columnas'>PUESTO</td>
				</tr>
				
				</thead>";				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			echo "<tbody>";
			do{
				echo "<tr>		
						<td align='center' class='$nom_clase'>$cont</td>
						<td align='center' class='$nom_clase'>$datos[empleados_rfc_empleado]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[nom_deduccion]</td>
						<td align='center' class='$nom_clase'>$datos[id_deduccion]</td>
						<td align='center' class='$nom_clase'>". modFecha($datos['fecha_abono'],1)."</td>
						<td align='center' class='$nom_clase'>$".number_format($datos["abono"],2,".",",")."</td>
						<td align='center' class='$nom_clase'>$datos[descripcion]</td>
						<td align='center' class='$nom_clase'>$datos[puesto]</td>";
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
		}
		else//Si no se encuentra ningun resultado desplegar un mensaje					
		echo "<label align='center' class='msje_correcto'>NO EXISTEN EMPLEADOS  CON PR&Eacute;STAMOS/FINANCIAMIENTOS DEL &Aacute;REA: DEL &Aacute;REA: <u><em>$area</em></u> DE: <u><em>".modFecha($fechaIni,1)."</em></u> A <u><em>".modFecha($fechaFin,1)."</em></u></label>";?>								            
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcio	
	
	//Esta funcion muestra el Kardex de los Trabajadores de un area en las fechas seleccionadas
	function mostrarKardex(){
		//Recuperar el área seleccionada
		$area=$_POST["cmb_area"];
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
	}//Fin de function mostrarKardex()
	
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
	}//function obtenerNombreMes($mes)
	
	//Obtener la checada del Trabajador en la Fecha indicada
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
			/*Decomentar esta linea si solo se quiere dejar el campo con color en caso de Asistencia, de la forma Actual, muestra un recuadro verde con una A en blanco
			if ($estado=="A")
				$color.=";color:#669900";
			*/
			$titulo="$nombre tiene ".obtenerIncidencia($estado)." el $fechaMostrar";
		}
		$fecha=str_replace("-","°",$fecha);
		$checada="<input type='text' name='ckb_$fecha $ctrl' id='ckb_$fecha $ctrl' class='caja_de_num' size='1' readonly='readonly' value='$estado' style='font-size:19px;cursor:default;$color' title='$titulo'/>";
		return $checada;
	}//Fin de function obtenerChecada($fecha,$rfc,$ctrl)
	
	//Obtener la Descripcion de la Incidencia de Kardex registrada
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
	}//Fin function obtenerIncidencia($estado)
?>
