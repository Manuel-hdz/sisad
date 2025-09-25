<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 30/Marzo/2011
	  * Descripción: Este archivo contiene funciones para Crear y mostrar las Checadas de los Trabajadores
	**/
	
	//Funcion que muestra el Kardex individual, por año
	function kardexIndividual(){
		//Abrir la conexion a la BD de recursos
		$conn=conecta("bd_recursos");
		//Recoger el año del Combo
		$anio=$_POST["cmb_ejercicio"];
		//Recoger el nombre del Trabajador de la caja de Texto
		$trabajador=$_POST["txt_nombre"];
		//Obtener el RFC del Trabajador con la siguiente funcion
		$trabajador=obtenerDatoEmpleadoPorNombre("rfc_empleado",$trabajador);
		//Comenzar a dibujar la Tabla
		echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
		echo "<caption class='titulo_etiqueta'>Kardex</caption>";
		echo "	<tr>
					<td class='nombres_columnas' align='center' rowspan='2'>MESES</td>
					<td class='nombres_columnas' align='center' colspan='31'>DIAS</td>
				</tr>";
		//Variable que en su primer uso permite dibujar los numeros del 1 al 31 como parte de las columnas
		$cont=1;
		echo "<tr>";
		do{
			echo "<td class='nombres_columnas' align='center'>$cont</td>";
			$cont++;
		}while($cont<=31);
		echo "</tr>";
		//Declarar la variable mes vacia, esta variable contendra el nombre de Cada mes
		$mes="";
		//Reiniciar esta variable para obtener el nombre de cada mes en un ciclo
		$cont=1;
		//Ciclo que dibuja cada renglon con los meses y en su momento el respectivo estado
		do{
			//Segun el valor de $cont, obtener el nombre de cada mes
			switch($cont){
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
			echo "<tr>";
			echo "<td class='nombres_filas' align='center'>$mes</td>";
			
			//Si el valor de $cont es menor a 10, concatenarle un 0 a la izquierda, de forma que podamos realizar la comparacion con los datos de la Fecha de Checada
			//por ejemplo 01,02,03,...,09, a partir del 10, el numero queda tal cual esta
			if ($cont<10)
				$mesFecha="0".$cont;
			else
				$mesFecha=$cont;
			//Obtener los dias del Mes segun el registro seleccionado
			//p.e. Enero->31,Febrero->29(si es bisiesto),...Diciembre->31
			$diasMes=diasMes($mesFecha,$anio);
			//Preparar la sentencia SQL por cada mes dejando una consulta de la siguiente manera
			//p.e. SELECT estado,fecha_checada,hora_checada FROM checadas WHERE empleados_rfc_empleado='$trabajador' AND fecha_checada BETWEEN '2012-01-01' AND '2012-01-31'
			$stm_sql="SELECT estado,fecha_checada,hora_checada FROM checadas WHERE empleados_rfc_empleado='$trabajador' AND fecha_checada BETWEEN '".$anio."-".$mesFecha."-01' AND '".$anio."-".$mesFecha."-".$diasMes."' AND estado!='SALIDA' ORDER BY fecha_checada,hora_checada";
			//Ejecutar la sentencia SQL
			$rs=mysql_query($stm_sql);
			//Si la consulta regresa resultados, verificarlos
			if ($datos=mysql_fetch_array($rs)){
				//Obtener el dia de la primer Checada encontrada
				$mesCda=substr($datos["fecha_checada"],5,2);
				//Si el mes de la Fecha es igual al Mes de la checada, verificar datos
				if ($mesFecha==$mesCda){
					//Variable que representa cada dia en las columnas de meses
					$diasCols=1;
					do{
						//Obtener el Dia en formato XX
						//p.e. 01,02,03,...,09, a partir del 10, el numero queda tal cual esta
						if ($diasCols<10)
							$diaFecha="0".$diasCols;
						else
							$diaFecha=$diasCols;
						//Obtener el nombre del Dia de la Semana
						$nomDia=obtenerNombreDia($anio."-".$cont."-".$diasCols);
						//Obtener el dia de la Checada
						$diaCda=substr($datos["fecha_checada"],-2);
						//Variable para controlar el color de Fondo en caso de haber o no, datos
						$color="";
						//Esta variable dibuja un cuadro de Texto vacio con el nombre y ID correspondientes a la mezcla del mes y del dia
						//p.e. Enero 21 -> name='0121'
						$estado="<input type='text' name='ckb_$mesFecha$diaFecha' id='ckb_$mesFecha$diaFecha' class='caja_de_num' size='1' readonly='readonly' onclick='asignarEstadoKardex(this,hdn_nombre.value,hdn_anio.value);' title='Asignar Estado para el $nomDia $diasCols de $mes' style='font-size:19px;cursor:pointer'/>";
						//Verificar si la fecha es es la misma que la del dia de la Checada
						if ($diaFecha==$diaCda){
							//Ingresar en la variable estado el valor del Estado tomado por la Fecha
							$estado=$datos["estado"];
							//Color de relleno de la caja de Texto en caso de haber datos
							$color="background-color:#669900";
							if ($estado=="A")
								$color.=";color:#669900";
							//Crear un campo de Texto con el valor del estado recien recuperado
							$estado="<input type='text' name='ckb_$mesFecha$diaFecha' id='ckb_$mesFecha$diaFecha' class='caja_de_num' size='1' readonly='readonly' onclick='asignarEstadoKardex(this,hdn_nombre.value,hdn_anio.value);' value='$estado' title='Asignar Estado para el $nomDia $diasCols de $mes' style='font-size:19px;cursor:pointer;$color'/>";
							//Adelantar al siguiente registro el resultado de la consulta
							$datos=mysql_fetch_array($rs);
							//Obtener el dia de la siguiente Checada y compararla para verificar la posible salida
							$diaCda=substr($datos["fecha_checada"],-2);
							//Verificar si la fecha de la checada siguiente recogida es ahora la misma que la de la fecha actual, para verificar la posible salida
							if ($diaFecha==$diaCda)
								//Adelantar al siguiente registro el resultado de la consulta para buscar el dato que no corresponda a la salida
								$datos=mysql_fetch_array($rs);
						}
						//Dibujar en una columna el valor que $estado tomó segun lo verificado
						echo "<td align='center'>$estado</td>";
						//Incrementar el contados de los dias de la columna
						$diasCols++;
					}while($diasCols<=$diasMes);//Mientras que los dias de la columna sean menos a los días que tiene el mes en dicho año
				}//Fin del if ($mesFecha==$mesCda), en caso de no haber datos, continuar al siguiente registro
			}//Fin del IF que revisa si se encontraron resultados, en caso de no haberlos, mostrar cajas de Texto vacias
			else{
				//Variable de control de dias por cada mes
				$sinDato=1;
				do{
					if ($sinDato<10)
						$nomSinDato="0".$sinDato;
					else
						$nomSinDato=$sinDato;
					//Obtener el nombre del Dia de la Semana
					$nomDia=obtenerNombreDia($anio."-".$cont."-".$sinDato);
					echo "<td align='center'><input type='text' name='ckb_$mesFecha$nomSinDato' id='ckb_$mesFecha$nomSinDato' class='caja_de_num' size='1' readonly='readonly' onclick='asignarEstadoKardex(this,hdn_nombre.value,hdn_anio.value);' title='Asignar Estado para el $nomDia $sinDato de $mes' style='font-size:19px;cursor:pointer'/></td>";
					$sinDato++;
				}while($sinDato<=$diasMes);//Mientras que la variable sea menor o igual a los dias del Mes Seleccionado
			}
			echo "</tr>";
			$cont++;
		}while($cont<=12);//Mientras que la variable sea menor o igual a la cantidad de Meses, es decir, 12
		echo "<input type='hidden' name='hdn_nombre' id='hdn_nombre' value='$_POST[txt_nombre]'/>";
		echo "<input type='hidden' name='hdn_anio' id='hdn_anio' value='$anio'/>";
		echo "</table>";
	}//Fin de kardexIndividual
	
	//Funcion que dibujar el Encabezado de la Tabla de Kardex Individual
	function mostrarEncabezado(){
		//Recuperar el nombre del Trabajador
		$nombre=$_POST["txt_nombre"];
		//Obtener la Clave de Empleado del Trabajador con la siguiente funcion
		$cve_emp=obtenerDatoEmpleadoPorNombre("id_empleados_empresa",$nombre);
		//Obtener el Area del Trabajador con la siguiente funcion
		$area=obtenerDatoEmpleadoPorNombre("area",$nombre);
		//Obtener el Puesto del Trabajador con la siguiente funcion
		$puesto=obtenerDatoEmpleadoPorNombre("puesto",$nombre);
		//Obtener el Puesto del Trabajador con la siguiente funcion
		$fechaIng=obtenerDatoEmpleadoPorNombre("fecha_ingreso",$nombre);
		$fechaIng=modFecha($fechaIng,1);
		echo "
		<table width='100%' class='tabla_frm' cellpadding='5'>
			<caption class='titulo_etiqueta'>Datos Trabajador</caption>
			<tr>
				<td><div align='right'>Clave</div></td>
				<td><div align='left'>&nbsp;&nbsp;&nbsp;<input type='text' id='txt_clave' name='txt_clave' value='$cve_emp' size='5' class='caja_de_texto' readonly='readonly'/></div></td>
				<td><div align='right'>Nombre:</div></td>
				<td colspan='3'><div align='left'>&nbsp;&nbsp;&nbsp;<input type='text' id='txt_nombre' name='txt_nombre' value='$nombre' size='80' class='caja_de_texto' readonly='readonly'/></div></td>
			</tr>
			<tr>
				<td><div align='right'>&Aacute;rea</div></td>
				<td><div align='left'>&nbsp;&nbsp;&nbsp;<input type='text' id='txt_area' name='txt_area' value='$area' size='30' class='caja_de_texto' readonly='readonly'/></div></td>
				<td><div align='right'>Puesto:</div></td>
				<td><div align='left'>&nbsp;&nbsp;&nbsp;<input type='text' id='txt_puesto' name='txt_puesto' value='$puesto' size='40' class='caja_de_texto' readonly='readonly'/></div></td>
				<td><div align='right'>Fecha Ingreso:</div></td>
				<td><div align='left'>&nbsp;&nbsp;&nbsp;<input type='text' id='txt_puesto' name='txt_puesto' value='$fechaIng' size='10' class='caja_de_texto' readonly='readonly'/></div></td>
			</tr>
		</table>";
	}
	
	//Funcion para dibujar el Kardex por Área
	function kardexAsistencias(){
		//Variable para asignar un solo estado al rango de fechas seleccionado
		$incidenciaFechas="";
		//Recuperar el área seleccionada siempre y cuando este definida
		if(isset($_POST["ckb_filtroTrab"])){
			$stm_sql = "SELECT *, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre_emp
						FROM  `empleados` 
						WHERE (
							`area` LIKE  'ZARPEO FRESNILLO'
							OR  `area` LIKE  'ZARPEO SAUCITO'
						)
						AND  `id_cuentas` =  'CUEN001'
						AND estado_actual = 'ALTA'
						AND CONCAT(nombre,' ',ape_pat,' ',ape_mat) = '$_POST[txt_nombreK]'
						ORDER BY nombre_emp";
		}
		else{//Consulta usando Filtro por Nombre de Trabajador
			$stm_sql = "SELECT *, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre_emp
						FROM  `empleados` 
						WHERE (
							`area` LIKE  'ZARPEO FRESNILLO'
							OR  `area` LIKE  'ZARPEO SAUCITO'
						)
						AND  `id_cuentas` =  'CUEN001'
						AND estado_actual = 'ALTA'
						ORDER BY nombre_emp";
		}
		//Conectar a la BD de Recursos
		$conn=conecta("bd_recursos");
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
						<th class='nombres_columnas' align='center' rowspan='2'>ID</th>
						<th class='nombres_columnas' align='center' rowspan='2'>NOMBRE</th>
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
			echo "<td class='nombres_columnas' align='center' colspan='4'>TOTAL POR EMPLEADO</td>";
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
			echo "	<td class='nombres_columnas' align='center'>A</td>
					<td class='nombres_columnas' align='center'>F</td>
					<td class='nombres_columnas' align='center'>I</td>
					<td class='nombres_columnas' align='center'>AL</td>
					</tr>
					</thead>";
			
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			//Llenado de Datos de la Tabla
			do{
				$ta_emp = 0; $tf_emp = 0; $ti_emp = 0; $tal_emp = 0;
				echo "<tr>";
				echo "<td class='nombres_filas' align='center'>$datos[id_empleados_empresa]</td>";
				echo "<td class='$nom_clase' align='left'>$datos[nombre_emp]</td>";
				$ctrl=0;
				do{
					//Funcion que obtiene la checada en caso de Existir
					$checada=obtenerChecada($fechas[$ctrl],$datos["id_empleados_empresa"],$ta_emp,$tf_emp,$ti_emp,$tal_emp);
					echo "<td class='$nom_clase' align='center'>$checada[0]</td>";
					$ctrl++;
					$ta_emp = $checada[1]; $tf_emp = $checada[2]; $ti_emp = $checada[3]; $tal_emp = $checada[4];
				}while($ctrl<(count($fechas)));
				echo "<td class='$nom_clase' align='center' style='font-size:19px;'>$ta_emp</td>";
				echo "<td class='$nom_clase' align='center' style='font-size:19px;'>$tf_emp</td>";
				echo "<td class='$nom_clase' align='center' style='font-size:19px;'>$ti_emp</td>";
				echo "<td class='$nom_clase' align='center' style='font-size:19px;'>$tal_emp</td>";
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
			
			return array(array_sum($cantDias),$stm_sql);
		}////Fin del IF que verifica que existan resultados en la consulta
		else{
			//Este punto debe ser inalcanzable
		}
	}//Fin de KardexArea
			
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
	
	function obtenerChecada($fecha,$id_empleado,$a,$f,$i,$al){
		$conexion = conecta("bd_gerencia");
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
		$dia = strtolower(obtenerNombreDia2($fecha));
		//Sentencia SQL para extraer la checada de la tabla correspondiente
		$stm_sql = "SELECT T2 . $dia
					FROM  `nominas` AS T1
					JOIN  `detalle_nominas` AS T2
					USING (  `id_nomina` ) 
					WHERE  `fecha_inicio` <=  '$fecha'
					AND  `fecha_fin` >=  '$fecha'
					AND  `id_empleados_empresa` =  '$id_empleado'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		$estado="";
		$checada="<input type='text' class='caja_de_num' size='1' readonly='readonly' value='' style='font-size:19px;border-width:0;background-color:transparent;'/>";
		//$nombre=obtenerNombreEmpleado($rfc);
		//$fechaMostrar=modFecha($fecha,2);
		//Variables para controlar el color de Fondo y Letra en caso de haber o no, datos
		$color="";
		//Si la consulta regresa resultados, verificarlos
		if ($datos=mysql_fetch_array($rs)){
			$estado=$datos["".$dia];
			if ($estado=="A"){
				$a += 1;
				$color="background-color:#006600";
				$checada="<input type='text' class='caja_de_num' size='1' readonly='readonly' value='$estado' style='font-size:19px;$color;border-width:0;'/>";
			}
			if ($estado=="F"){
				$f += 1;
				$color="background-color:#990000";
				$checada="<input type='text' class='caja_de_num' size='1' readonly='readonly' value='$estado' style='font-size:19px;$color;border-width:0;'/>";
			}
			if ($estado=="I"){
				$i += 1;
				$color="background-color:#000099";
				$checada="<input type='text' class='caja_de_num' size='1' readonly='readonly' value='$estado' style='font-size:19px;$color;border-width:0;'/>";
			}
			if ($estado=="B"){
				$al += 1;
				$color="background-color:#666666";
				$estado="AL";
				$checada="<input type='text' class='caja_de_num' size='1' readonly='readonly' value='$estado' style='font-size:19px;$color;border-width:0;'/>";
			}
			//if ($estado=="A")
				//$color.=";color:#669900";
		}
		$fecha=str_replace("-","°",$fecha);
		
		//$checada="<input type='text' name='ckb_$fecha $ctrl' id='ckb_$fecha $ctrl' class='caja_de_num' size='1' readonly='readonly' onclick='asignarEstadoKardexArea(this);' value='$dia' style='font-size:19px;cursor:pointer;$color' title='Asignar la Incidencia del $fechaMostrar para $nombre'/>";
		return array($checada,$a,$f,$i,$al);
	}
?>