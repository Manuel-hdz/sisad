<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 19/Julio/2012
	  * Descripción: Este archivo contiene funciones para generar el Reporte de Disponibilidad Mecanica, Fisica y Utilizacion de Equipos
	  **/

	/*Funcion que recopila los datos para dibujar la grafica*/
	function reporteEstadisticoEquipos(){
		//Verificar el departamento donde el usuario esta logueado
		if($_SESSION["depto"]=="MttoConcreto")
			$area="CONCRETO";
		else
			$area="MINA";
		//Obtener las Fechas
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//Extraer y verificar el Equipo, si tiene valor diferente de vacio, verificar la Familia
		$equipo=$_POST["cmb_equipo"];
		//Obtener el Turno
		$turno=$_POST["cmb_turno"];
		//Variable que segun el dato del Turno, indicara si es jornada de 8 o de 24 Hrs
		$hrsProgramadas=24;
		//Preparacion de la sentencia SQL
		$sql_stm="SELECT equipos_id_equipo,tipo_mtto,fecha_mtto,turno,tiempo_total FROM bitacora_mtto WHERE fecha_mtto BETWEEN '$fechaI' AND '$fechaF'";
		if($turno!=""){
			$sql_stm.=" AND turno='$turno'";
			$hrsProgramadas=8;
		}
		if($equipo!="")
			$sql_stm.=" AND equipos_id_equipo='$equipo'";
		else
			$sql_stm.=" AND equipos_id_equipo=ANY(SELECT id_equipo FROM equipos WHERE area='$area')";
		//Concatenar la parte final de la sentencia
		$sql_stm.=" ORDER BY equipos_id_equipo,tipo_mtto DESC,fecha_mtto,turno";
		//Conectar a la BD de Mtto
		$conn=conecta("bd_mantenimiento");
		//Ejecutar la sentencia
		$rs=mysql_query($sql_stm);
		//Extraer los datos de la consulta
		if($datos=mysql_fetch_array($rs)){
			//Arreglo que almacenara los Equipos
			$equipos=array();
			//Arreglo que almacenara el numero de Horas de Servicio en caso que se encuentren resultados
			$hrsMtto=array();
			//Asignar a la variable Equipo, el primero encontrado
			$equipo=$datos["equipos_id_equipo"];
			//Obtener el Tiempo Total en cantidad
			$tiempoMttoPrev=0;
			$tiempoMttoCorr=0;
			//Asignar a la variable Fecha la Fecha actual
			$fecha=$datos["fecha_mtto"];
			//Asignar a la variable Turno el Turno actual}
			$turno=$datos["turno"];
			do{
				//Verificar que sea el mismo equipo
				if($equipo==$datos["equipos_id_equipo"]){
					//Verificar que sea el mismo tipo de Mtto
					if($datos["tipo_mtto"]=="PREVENTIVO"){
						//Verificar que sea la misma Fecha de Mtto
						if($fecha==$datos["fecha_mtto"]){
							//Verificar que sea el mismo Turno
							if($turno==$datos["turno"]){
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoPrev+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
							}
							//Si no es el mismo Turno, cambiar el Puntero y restablecer el acumulador de Horas Preventivas
							else{
								$turno=$datos["turno"];
								$tiempoMttoPrev=0;
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoPrev+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
							}
						}
						//Si no es la misma Fecha, cambiar el puntero y restablecer el acumulador de Horas Preventivas
						else{
							$fecha=$datos["fecha_mtto"];
							$tiempoMttoPrev=0;
							//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
							$hora=split(":",$datos["tiempo_total"]);
							//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
							$hrs=intval($hora[0]);
							//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
							$min=intval($hora[1]);
							//Obtener el Tiempo Total en cantidad
							$tiempoMttoPrev+=round(($hrs+($min/60)),2);
							$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
						}
					}
					//Si el Mtto no es Preventivo, entonces es correctivo
					else{
						//Verificar que sea la misma Fecha de Mtto
						if($fecha==$datos["fecha_mtto"]){
							//Verificar que sea el mismo Turno
							if($turno==$datos["turno"]){
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoCorr+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
							}
							//Si no es el mismo Turno, cambiar el Puntero y restablecer el acumulador de Horas Preventivas
							else{
								$turno=$datos["turno"];
								$tiempoMttoCorr=0;
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoCorr+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
							}
						}
						//Si no es la misma Fecha, cambiar el puntero y restablecer el acumulador de Horas Correctivas
						else{
							$fecha=$datos["fecha_mtto"];
							$tiempoMttoCorr=0;
							//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
							$hora=split(":",$datos["tiempo_total"]);
							//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
							$hrs=intval($hora[0]);
							//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
							$min=intval($hora[1]);
							//Obtener el Tiempo Total en cantidad
							$tiempoMttoCorr+=round(($hrs+($min/60)),2);
							$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
						}
					}
				}
				//Aqui el Equipo ya es otro
				else{
					$equipo=$datos["equipos_id_equipo"];
					$tiempoMttoPrev=0;
					$tiempoMttoCorr=0;
					//Verificar que sea el mismo tipo de Mtto
					if($datos["tipo_mtto"]=="PREVENTIVO"){
						//Verificar que sea la misma Fecha de Mtto
						if($fecha==$datos["fecha_mtto"]){
							//Verificar que sea el mismo Turno
							if($turno==$datos["turno"]){
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoPrev+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
							}
							//Si no es el mismo Turno, cambiar el Puntero y restablecer el acumulador de Horas Preventivas
							else{
								$turno=$datos["turno"];
								$tiempoMttoPrev=0;
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoPrev+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
							}
						}
						//Si no es la misma Fecha, cambiar el puntero y restablecer el acumulador de Horas Preventivas
						else{
							$fecha=$datos["fecha_mtto"];
							$tiempoMttoPrev=0;
							//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
							$hora=split(":",$datos["tiempo_total"]);
							//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
							$hrs=intval($hora[0]);
							//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
							$min=intval($hora[1]);
							//Obtener el Tiempo Total en cantidad
							$tiempoMttoPrev+=round(($hrs+($min/60)),2);
							$equipos[$equipo][$fecha][$turno]["P"]=$tiempoMttoPrev;
						}
					}
					//Mantenimiento Correctivo
					else{
						//Verificar que sea la misma Fecha de Mtto
						if($fecha==$datos["fecha_mtto"]){
							//Verificar que sea el mismo Turno
							if($turno==$datos["turno"]){
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoCorr+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
							}
							//Si no es el mismo Turno, cambiar el Puntero y restablecer el acumulador de Horas Preventivas
							else{
								$turno=$datos["turno"];
								$tiempoMttoCorr=0;
								//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
								$hora=split(":",$datos["tiempo_total"]);
								//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
								$hrs=intval($hora[0]);
								//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
								$min=intval($hora[1]);
								//Obtener el Tiempo Total en cantidad
								$tiempoMttoCorr+=round(($hrs+($min/60)),2);
								$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
							}
						}
						//Si no es la misma Fecha, cambiar el puntero y restablecer el acumulador de Horas Correctivas
						else{
							$fecha=$datos["fecha_mtto"];
							$tiempoMttoCorr=0;
							//Dividir el tiempo de Mtto por el simbolo de DOS PUNTOS ":"
							$hora=split(":",$datos["tiempo_total"]);
							//Incrementar las horas segun la primer posicion, del arreglo que quedo como resultado
							$hrs=intval($hora[0]);
							//Incrementar las horas segun la segunda posicion, del arreglo que quedo como resultado
							$min=intval($hora[1]);
							//Obtener el Tiempo Total en cantidad
							$tiempoMttoCorr+=round(($hrs+($min/60)),2);
							$equipos[$equipo][$fecha][$turno]["C"]=$tiempoMttoCorr;
						}
					}
				}
			}while($datos=mysql_fetch_array($rs));
		}//La sentencia no requiere un ELSE
		/*INICIO DE LA DECLARACION DEL ENCABEZADO*/
		//Obtener la cantidad de Dias entre las 2 Fechas
		$dias=restarFechas($fechaI,$fechaF)+1;
		//Partir la Fecha de Inicio en secciones de dia, mes y año
		$diaI=substr($fechaI,0,2);
		$mesI=substr($fechaI,3,2);
		$anioI=substr($fechaI,-4);
		//Obtener la cantidad de Dias del primer Mes
		$cantDiasMesCurso=diasMes($mesI,$anioI);
		//Convertir en numero los dias,mes y año de la Fecha de Inicio
		$diasInicio=0+$diaI;
		$mesInicio=0+$mesI;
		$anioInicio=0+$anioI;
		//Partir la Fecha de Fin en secciones de dia, mes y año
		$diaF=substr($fechaF,0,2);
		$mesF=substr($fechaF,3,2);
		$anioF=substr($fechaF,-4);
		//Convertir en numero los dias,mes y año de la Fecha de Inicio
		$diasTope=0+$diaF;
		$mesTope=0+$mesF;
		$anioTope=0+$anioF;
		//Comenzar a dibujar la Tabla
		echo "<table class='tabla_frm' cellpadding='5'>";
		echo "<caption class='titulo_etiqueta'>F&oacute;rmulas Empleadas</caption>";
		echo "	<tr>
					<td class='nombres_columnas' align='right'>DISPONIBILIDAD MEC&Aacute;NICA</td>
					<td class='renglon_gris' align='left'>(<span title='Horas Registrada de Uso'>HorasTrabajadas</span>/(<span title='Horas Registrada de Uso'>HorasTrabajadas</span>+<span title='Horas Totales entre Tiempo de Matenimiento Preventivo y Correctivo'>HorasMantenimiento</span>))*100</td>
				</tr>	
				<tr>
					<td class='nombres_columnas' align='right'>DISPONIBILIDAD F&Iacute;SICA</td>
					<td class='renglon_blanco' align='left'>((<span title='Horas Registrada de Uso'>HorasTrabajadas</span>+<span title='Horas que Pasa el Equipo en Espera'>HorasStandBy</span>)/<span title='Jornadas de 8 Horas por Turno'>HorasPorTurno</span>)*100</td>
				</tr>	
				<tr>
					<td class='nombres_columnas' align='right'>UTILIZACI&Oacute;N EFECTIVA</td>
					<td class='renglon_gris' align='left'>(<span title='Horas Registrada de Uso'>HorasTrabajadas</span>/<span title='Jornadas de 8 Horas por Turno'>HorasPorTurno</span>)*100</td>
				</tr>";
		echo "</table>";
		//Cuerpo del Encabezado
		echo "<table class='tabla_frm' cellpadding='5'>";
		echo "<caption class='titulo_etiqueta'>Reporte de Equipos</caption>";
		echo "	<tr>
					<td class='nombres_columnas' align='center' rowspan='3'>EQUIPO</td>
					<td class='nombres_columnas' align='center' rowspan='3'>TURNO</td>
					<td class='nombres_columnas' align='center' colspan='".($dias*3)."'>FECHA</td>
				</tr>
		";
		//Declaracion del renglon para las Fechas
		echo "<tr>";
		//Asignar la fecha de inicio a una variable
		$fechaMostrar=$fechaI;
		//Arreglo que contendra los datos para la generacion de un grafico
		$datosGraficar=array();
		do{
			//Dibujar la Fecha de Inicio
			echo "<td class='nombres_columnas' colspan='3' align='center' width='300px'>".modFecha($fechaMostrar,1)."</td>";
			$fechaMostrar=sumarDiasFecha($fechaMostrar,1);
		}while($fechaMostrar!=sumarDiasFecha($fechaF,1));
		echo "</tr>";
		//Declaracion de los Renglones de los conceptos
		echo "<tr>";
		$cont=0;
		do{
			echo "<td class='nombres_columnas' align='center' width='100px'>D.M.</td>";
			$cont++;
			echo "<td class='nombres_columnas' align='center' width='100px'>D.F.</td>";
			$cont++;
			echo "<td class='nombres_columnas' align='center' width='100px'>U.E.</td>";
			$cont++;
		}while($cont<($dias*3));
		echo "</tr>";
		/*FIN DE LA DECLARACION DEL ENCABEZADO*/
		/*INICIO DEL LLENADO DE DATOS*/
		//Extraer todos los Equipos de MttoMina que se encuentren Activos
		$sql_stm="SELECT id_equipo FROM equipos WHERE area='$area' AND estado='ACTIVO'";
		//Si el Equipo esta definido desde el POST, usarlo como filtro, (Esto es redundante pero se puede aplicar la misma estructura)
		if($_POST["cmb_equipo"]!="")
			$sql_stm.=" AND id_equipo='$equipo'";
		$sql_stm.=" ORDER BY familia,id_equipo";
		$rs=mysql_query($sql_stm);
		//Contador para el manejo de Turnos
		$contador=1;
		//Clase inicial para el renglon
		$nom_clase = "renglon_gris";
		if($datosEquipos=mysql_fetch_array($rs)){
			//Recorrer los Equipos
			do{
				//Asignar el idEquipo a una variable para su mejor manejo
				$idEquipo=$datosEquipos["id_equipo"];
				//Verificar si el Equipo se encuentra en el arreglo de Equipos
				if(isset($equipos[$idEquipo])){
					//Cuando no hay Turno definido
					if($_POST["cmb_turno"]==""){
						//Obtener el Turno
						$numTurno=1;
						do{
							//Obtener el Turno en modo Texto
							if($numTurno==1)
								$turnoActual="TURNO DE PRIMERA";
							if($numTurno==2)
								$turnoActual="TURNO DE SEGUNDA";
							if($numTurno==3)
								$turnoActual="TURNO DE TERCERA";
							//Obtener la Fecha Actual
							$fechaActual=$fechaI;
							echo "<tr>";
							echo "<td align='center' class='$nom_clase'>$idEquipo</td>";
							echo "<td align='center' class='$nom_clase'>$turnoActual</td>";
							do{
								//Obtener las horas en Turno
								$hrsTurno=obtenerHorasXFechaXTurno($idEquipo,$fechaActual,$turnoActual);
								//Variable para acumular horas en Mtto Preventivo y Correctivo
								$hrsMtto=0;
								//Sumar a la variable de las Horas de Mantenimiento las Horas encontradas por equipo,fecha,turno y que son registradas directamente
								//sin necesidad de haberse generado una órden de Trabajo y que por lo tanto, no se guardan en la bitácora de Mantenimiento
								$hrsMtto+=obtenerMttoDiarioTurno($idEquipo,$fechaActual,$turnoActual);
								//Si esta definida la siguiente posicion, tiene servicios preventivos
								if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["P"]))
									//Acumular a las horas de Mtto, las horas de Mtto Preventivo
									$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["P"];
								//Si esta definida la siguiente posicion, tiene servicios correctivos
								if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["C"]))
									//Acumular a las horas de Mtto, las horas de Mtto Correctivo
									$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["C"];
								//Calcular las Horas en Stand By, se pasa el 8 directo, ya que los calculos son por Turno
								$hrsSB=8-($hrsTurno+$hrsMtto);
								//Calcular la Disponibilidad Mecanica
								if($hrsTurno!=0 && $hrsMtto!=0)
									$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
								elseif($hrsTurno!=0 || $hrsMtto!=0)
									$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
								else
									$dispMec=0;
								//Calcular la Disponibilidad Física en base a 8 horas por Turno
								$dispFis=(($hrsTurno+$hrsSB)/8)*100;
								//Calcular la Utilizacion Efectiva en base a 8 horas por Turno
								$utilEfec=($hrsTurno/8)*100;
								//Convertir los datos encontrados a numeros mas entendibles
								$dispMec=number_format($dispMec,2,".",",");
								$dispFis=number_format($dispFis,2,".",",");
								$utilEfec=number_format($utilEfec,2,".",",");
								//Disponibilidad Mecanica
								if($dispMec=="0.00")
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$dispMec%<span></td>";
								elseif(floatval($dispMec)>=85)
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$dispMec%<span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$dispMec%<span></td>";
								//Disponibilidad Fisica
								if($dispFis=="0.00")
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$dispFis%</span></td>";
								elseif(floatval($dispFis)>=85)
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$dispFis%</span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$dispFis%</span></td>";
								//Utilizacion Efectivas
								if($utilEfec=="0.00")
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$utilEfec%</span></td>";
								elseif(floatval($utilEfec)>=85)
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$utilEfec%</span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$utilEfec%</span></td>";
								//Guardar los datos en el arreglo de graficacion
								$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["DM"]=$dispMec;
								$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["DF"]=$dispFis;
								$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["UE"]=$utilEfec;
								//Incrementar la Fecha Actual en 1 Dia
								$fechaActual=sumarDiasFecha($fechaActual,1);
							}while($fechaActual!=sumarDiasFecha($fechaF,1));
							$numTurno++;
							echo "</tr>";
						}while($numTurno!=4);
					}
					//Cuando SI hay Turno definido
					else{
						$turnoActual=$_POST["cmb_turno"];
						//Obtener la Fecha Actual
						$fechaActual=$fechaI;
						echo "<tr>";
						echo "<td align='center' class='$nom_clase'>$idEquipo</td>";
						echo "<td align='center' class='$nom_clase'>$turnoActual</td>";
						do{
							//Obtener las horas en Turno
							$hrsTurno=obtenerHorasXFechaXTurno($idEquipo,$fechaActual,$turnoActual);
							//Variable para acumular horas en Mtto Preventivo y Correctivo
							$hrsMtto=0;
							//Sumar a la variable de las Horas de Mantenimiento las Horas encontradas por equipo,fecha,turno y que son registradas directamente
							//sin necesidad de haberse generado una órden de Trabajo y que por lo tanto, no se guardan en la bitácora de Mantenimiento
							$hrsMtto+=obtenerMttoDiarioTurno($idEquipo,$fechaActual,$turnoActual);
							//Si esta definida la siguiente posicion, tiene servicios preventivos
							if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["P"]))
								//Acumular a las horas de Mtto, las horas de Mtto Preventivo
								$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["P"];
							//Si esta definida la siguiente posicion, tiene servicios correctivos
							if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["C"]))
								//Acumular a las horas de Mtto, las horas de Mtto Correctivo
								$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["C"];
							//Calcular las Horas en Stand By, se pasa el 8 directo, ya que los calculos son por Turno
							$hrsSB=8-($hrsTurno+$hrsMtto);
							//Calcular la Disponibilidad Mecanica
							if($hrsTurno!=0 && $hrsMtto!=0)
								$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
							elseif($hrsTurno!=0 || $hrsMtto!=0)
								$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
							else
								$dispMec=0;
							//Calcular la Disponibilidad Física en base a 8 horas por Turno
							$dispFis=(($hrsTurno+$hrsSB)/8)*100;
							//Calcular la Utilizacion Efectiva en base a 8 horas por Turno
							$utilEfec=($hrsTurno/8)*100;
							//Convertir los datos encontrados a numeros mas entendibles
							$dispMec=number_format($dispMec,2,".",",");
							$dispFis=number_format($dispFis,2,".",",");
							$utilEfec=number_format($utilEfec,2,".",",");
							//Disponibilidad Mecanica
							if($dispMec=="0.00")
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$dispMec%<span></td>";
							elseif(floatval($dispMec)>=85)
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$dispMec%<span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$dispMec%<span></td>";
							//Disponibilidad Fisica
							if($dispFis=="0.00")
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$dispFis%</span></td>";
							elseif(floatval($dispFis)>=85)
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$dispFis%</span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$dispFis%</span></td>";
							//Utilizacion Efectivas
							if($utilEfec=="0.00")
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$utilEfec%</span></td>";
							elseif(floatval($utilEfec)>=85)
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$utilEfec%</span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$utilEfec%</span></td>";
							//Guardar los datos en el arreglo de graficacion
							$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["DM"]=$dispMec;
							$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["DF"]=$dispFis;
							$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["UE"]=$utilEfec;
							//Incrementar la Fecha Actual en 1 Dia
							$fechaActual=sumarDiasFecha($fechaActual,1);
						}while($fechaActual!=sumarDiasFecha($fechaF,1));
						echo "</tr>";
					}
				}
				else{
					/********************/
					//EQUIPOS SIN MTTO
					//Cuando no hay Turno definido
					if($_POST["cmb_turno"]==""){
						//Obtener el Turno
						$numTurno=1;
						do{
							//Obtener el Turno en modo Texto
							if($numTurno==1)
								$turnoActual="TURNO DE PRIMERA";
							if($numTurno==2)
								$turnoActual="TURNO DE SEGUNDA";
							if($numTurno==3)
								$turnoActual="TURNO DE TERCERA";
							//Obtener la Fecha Actual
							$fechaActual=$fechaI;
							echo "<tr>";
							echo "<td align='center' class='$nom_clase'>$idEquipo</td>";
							echo "<td align='center' class='$nom_clase'>$turnoActual</td>";
							do{
								//Obtener las horas en Turno
								$hrsTurno=obtenerHorasXFechaXTurno($idEquipo,$fechaActual,$turnoActual);
								//Variable para acumular horas en Mtto Preventivo y Correctivo
								$hrsMtto=0;
								//Sumar a la variable de las Horas de Mantenimiento las Horas encontradas por equipo,fecha,turno y que son registradas directamente
								//sin necesidad de haberse generado una órden de Trabajo y que por lo tanto, no se guardan en la bitácora de Mantenimiento
								$hrsMtto+=obtenerMttoDiarioTurno($idEquipo,$fechaActual,$turnoActual);
								//Si esta definida la siguiente posicion, tiene servicios preventivos
								if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["P"]))
									//Acumular a las horas de Mtto, las horas de Mtto Preventivo
									$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["P"];
								//Si esta definida la siguiente posicion, tiene servicios correctivos
								if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["C"]))
									//Acumular a las horas de Mtto, las horas de Mtto Correctivo
									$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["C"];
								//Calcular las Horas en Stand By, se pasa el 8 directo, ya que los calculos son por Turno
								$hrsSB=8-($hrsTurno+$hrsMtto);
								//Calcular la Disponibilidad Mecanica
								if($hrsTurno!=0 && $hrsMtto!=0)
									$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
								elseif($hrsTurno!=0 || $hrsMtto!=0)
									$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
								else
									$dispMec=0;
								//Calcular la Disponibilidad Física en base a 8 horas por Turno
								$dispFis=(($hrsTurno+$hrsSB)/8)*100;
								//Calcular la Utilizacion Efectiva en base a 8 horas por Turno
								$utilEfec=($hrsTurno/8)*100;
								//Convertir los datos encontrados a numeros mas entendibles
								$dispMec=number_format($dispMec,2,".",",");
								$dispFis=number_format($dispFis,2,".",",");
								$utilEfec=number_format($utilEfec,2,".",",");
								//Disponibilidad Mecanica
								if($dispMec=="0.00")
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$dispMec%<span></td>";
								elseif(floatval($dispMec)>=85)
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$dispMec%<span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$dispMec%<span></td>";
								//Disponibilidad Fisica
								if($dispFis=="0.00")
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$dispFis%</span></td>";
								elseif(floatval($dispFis)>=85)
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$dispFis%</span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$dispFis%</span></td>";
								//Utilizacion Efectivas
								if($utilEfec=="0.00")
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_incorrecto'>$utilEfec%</span></td>";
								elseif(floatval($utilEfec)>=85)
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span class='msje_correcto'>$utilEfec%</span></td>";
								else
									echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
											<span>$utilEfec%</span></td>";
								//Guardar los datos en el arreglo de graficacion
								$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["DM"]=$dispMec;
								$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["DF"]=$dispFis;
								$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["UE"]=$utilEfec;
								//Incrementar la Fecha Actual en 1 Dia
								$fechaActual=sumarDiasFecha($fechaActual,1);
							}while($fechaActual!=sumarDiasFecha($fechaF,1));
							$numTurno++;
							echo "</tr>";
						}while($numTurno!=4);
					}
					//Con turno Definido
					else{
						$turnoActual=$_POST["cmb_turno"];
						//Obtener la Fecha Actual
						$fechaActual=$fechaI;
						echo "<tr>";
						echo "<td align='center' class='$nom_clase'>$idEquipo</td>";
						echo "<td align='center' class='$nom_clase'>$turnoActual</td>";
						do{
							//Obtener las horas en Turno
							$hrsTurno=obtenerHorasXFechaXTurno($idEquipo,$fechaActual,$turnoActual);
							//Variable para acumular horas en Mtto Preventivo y Correctivo
							$hrsMtto=0;
							//Sumar a la variable de las Horas de Mantenimiento las Horas encontradas por equipo,fecha,turno y que son registradas directamente
							//sin necesidad de haberse generado una órden de Trabajo y que por lo tanto, no se guardan en la bitácora de Mantenimiento
							$hrsMtto+=obtenerMttoDiarioTurno($idEquipo,$fechaActual,$turnoActual);
							//Si esta definida la siguiente posicion, tiene servicios preventivos
							if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["P"]))
								//Acumular a las horas de Mtto, las horas de Mtto Preventivo
								$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["P"];
							//Si esta definida la siguiente posicion, tiene servicios correctivos
							if(isset($equipos[$idEquipo][$fechaActual][$turnoActual]["C"]))
								//Acumular a las horas de Mtto, las horas de Mtto Correctivo
								$hrsMtto+=$equipos[$idEquipo][$fechaActual][$turnoActual]["C"];
							//Calcular las Horas en Stand By, se pasa el 8 directo, ya que los calculos son por Turno
							$hrsSB=8-($hrsTurno+$hrsMtto);
							//Calcular la Disponibilidad Mecanica
							if($hrsTurno!=0 && $hrsMtto!=0)
								$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
							elseif($hrsTurno!=0 || $hrsMtto!=0)
								$dispMec=($hrsTurno/($hrsTurno+$hrsMtto))*100;
							else
								$dispMec=0;
							//Calcular la Disponibilidad Física en base a 8 horas por Turno
							$dispFis=(($hrsTurno+$hrsSB)/8)*100;
							//Calcular la Utilizacion Efectiva en base a 8 horas por Turno
							$utilEfec=($hrsTurno/8)*100;
							//Convertir los datos encontrados a numeros mas entendibles
							$dispMec=number_format($dispMec,2,".",",");
							$dispFis=number_format($dispFis,2,".",",");
							$utilEfec=number_format($utilEfec,2,".",",");
							//Disponibilidad Mecanica
							if($dispMec=="0.00")
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$dispMec%<span></td>";
							elseif(floatval($dispMec)>=85)
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$dispMec%<span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Disponibilidad Mec&aacute;nica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$dispMec%<span></td>";
							//Disponibilidad Fisica
							if($dispFis=="0.00")
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$dispFis%</span></td>";
							elseif(floatval($dispFis)>=85)
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$dispFis%</span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Disponibilidad F&iacute;sica del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$dispFis%</span></td>";
							//Utilizacion Efectivas
							if($utilEfec=="0.00")
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_incorrecto'>$utilEfec%</span></td>";
							elseif(floatval($utilEfec)>=85)
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span class='msje_correcto'>$utilEfec%</span></td>";
							else
								echo "<td align='center' class='$nom_clase' title='Utilizaci&oacute;n Efectiva del $idEquipo en $turnoActual del ".modFecha($fechaActual,1)."'>
										<span>$utilEfec%</span></td>";
							//Guardar los datos en el arreglo de graficacion
							$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["DM"]=$dispMec;
							$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["DF"]=$dispFis;
							$datosGraficar[$idEquipo][$fechaActual][$turnoActual]["UE"]=$utilEfec;
							//Incrementar la Fecha Actual en 1 Dia
							$fechaActual=sumarDiasFecha($fechaActual,1);
						}while($fechaActual!=sumarDiasFecha($fechaF,1));
						echo "</tr>";
					}
					/********************/
				}
				//Incrementar el contador de Turnos
				$contador++;
				//Verificar que clase le toca al Renglon
				if($contador%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datosEquipos=mysql_fetch_array($rs));
		}
		/*FIN DEL LLENADO DE DATOS*/
		echo "</table>";
		mysql_close($conn);
	}//Fin function reporteEstadisticoEquipos()
	
	//Funcion que extrae las horas efectivas de un Equipo en una fecha y tuno determinados
	function obtenerHorasXFechaXTurno($equipo,$fecha,$turno){
		//Sentencia SQL para extraer el total de horas efectivas
		$sql="SELECT SUM(hrs_efectivas) AS total FROM horometro_odometro WHERE equipos_id_equipo='$equipo' AND fecha='$fecha' AND turno='$turno'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar resultados
		if($datos=mysql_fetch_array($rs)){
			//Si regresa NULL, regresar 0, de lo contrario, asignar el Valor
			if($datos["total"]!=NULL)
				$horas=$datos["total"];
			else
				$horas=0;
		}
		else
			$horas=-1;
		//Retornar el valor de las Horas
		return $horas;
	}//Fin de obtenerHorasXFechaXTurno($equipo,$fecha,$turno) 
	
	/*Funcion que obtiene el nombre del Mes segun el Numero*/
	function nombreMes($mes){
		switch($mes){
			case "01": $mes="ENERO"; break;
			case "02": $mes="FEBRERO"; break;
			case "03": $mes="MARZO"; break;
			case "04": $mes="ABRIL"; break;
			case "05": $mes="MAYO"; break;
			case "06": $mes="JUNIO"; break;
			case "07": $mes="JULIO"; break;
			case "08": $mes="AGOSTO"; break;
			case "09": $mes="SEPTIEMBRE"; break;
			case "10": $mes="OCTUBRE"; break;
			case "11": $mes="NOVIEMBRE"; break;
			case "12": $mes="DICIEMBRE"; break;
		}
		return $mes;
	}//Fin de function nombreMes($mes)
	
	//Funcion que extrae las horas efectivas de un Equipo en una fecha y tuno determinados
	function obtenerMttoDiarioTurno($equipo,$fecha,$turno){
		//Variable para acumular las horas de Mtto Preventivo
		$horas=0;
		//Sentencia SQL para extraer el total de horas efectivas
		$sql="SELECT SUM(mtto_prev) AS total FROM horometro_odometro WHERE equipos_id_equipo='$equipo' AND fecha='$fecha' AND turno='$turno'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar resultados
		if($datos=mysql_fetch_array($rs)){
			//Si regresa NULL, regresar 0, de lo contrario, asignar el Valor
			if($datos["total"]!=NULL)
				$horas=$datos["total"];
		}
		//Retornar el valor de las Horas
		return $horas;
	}//Fin de obtenerHorasXFechaXTurno($equipo,$fecha,$turno)
?>