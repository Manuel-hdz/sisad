<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 29/Abril/2011                                      			
	  * Descripción: Este archivo contiene funciones para consultar la metrica relacionada con el formulario de consultar horoodometro de la bd
	  **/		 
	  
	//Esta funcion se encarga de mostrar el registro del horometro u odometro segun el equipo seleccionado
	function mostrarMetrica(){	
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
		
		//Recuperar el valor de los combos seleccionados
		$area= $_POST['cmb_area'];
		$familia= $_POST['cmb_familia'];		
		$equipo= $_POST['cmb_claveEquipo'];
		//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
		$f1 = modFecha($_POST['txt_fechaIni'],3);
		$f2 = modFecha($_POST['txt_fechaFin'],3);
		
		//Crear sentencia SQL
		$sql_stm="SELECT equipos_id_equipo, fecha, reg_inicial, reg_final, hrs_efectivas, turno, observaciones, km_servicio, metrica, 
			nom_equipo FROM horometro_odometro JOIN equipos ON equipos_id_equipo=id_equipo WHERE equipos_id_equipo = '$equipo' AND fecha>='$f1' AND fecha<='$f2'
			ORDER BY fecha,turno";
			
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Registro del Equipo <em><u> $equipo </u></em> En el Rango de Fechas del: <em><u> $_POST[txt_fechaIni] </u></em> al <em><u> $_POST[txt_fechaFin]
		</u></em>";
			
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>El Equipo <em><u>$equipo</u></em> En el Rango de Fechas del: <em><u> $_POST[txt_fechaIni]
		</u></em> al <em><u> $_POST[txt_fechaFin] </u></em> No Tiene Registros";										

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			if(($datos['metrica']=='HOROMETRO')){
				//Desplegar los resultados de la consulta en una tabla
				echo "
				<table cellpadding='5' width='101%'>				
					<tr>
						<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>EDITAR</td>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
						<td class='nombres_columnas' align='center'>HOR&Oacute;METRO INICIAL<br>(HRS)</td>
						<td class='nombres_columnas' align='center'>HOR&Oacute;METRO FINAL<br>(HRS)</td>
						<td class='nombres_columnas' align='center'>HORAS SERVICIO</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>";
							?>
							<td class="nombres_filas">
								<input type="hidden" name="hdn_fecha<?php echo $cont?>" id="hdn_fecha<?php echo $cont?>" value="<?php echo $datos['fecha']?>"/>
								<input type="hidden" name="hdn_turno<?php echo $cont?>" id="hdn_turno<?php echo $cont?>" value="<?php echo $datos['turno']?>"/>
								<input type="checkbox" name="ckb_editarMetrica<?php echo $cont?>" id="ckb_editarMetrica<?php echo $cont?>" onclick="activarCampos(this, <?php echo $cont; ?>)"/>
								<?php echo $cont?>.-
							</td>
							<?php
					echo "
							<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase'>";
								?>
								<input type="text" name="txt_horoIni<?php echo $cont;?>" id="txt_horoIni<?php echo $cont;?>" size="10" disabled="disabled"
								onkeypress="return permite(event,'num', 2);" class="caja_de_num" value="<?php echo number_format($datos["reg_inicial"],2,".",",");?>"
								onchange="formatCurrency(value,'txt_horoIni<?php echo $cont;?>'); calcularHrsServicio(<?php echo $cont;?>);" />
								<?php
					echo
							"</td>
							<td class='$nom_clase'>";
								?>
								<input type="text" name="txt_horoFin<?php echo $cont;?>" id="txt_horoFin<?php echo $cont;?>" size="10" disabled="disabled"
								onkeypress="return permite(event,'num', 2);" class="caja_de_num" value="<?php echo number_format($datos["reg_final"],2,".",",");?>"
								onchange="formatCurrency(value,'txt_horoFin<?php echo $cont;?>'); calcularHrsServicio(<?php echo $cont;?>);" />
								<?php
					echo
							"</td>   
							<td class='$nom_clase'>";
								?>
								<input type="text" name="txt_hrsEfectivas<?php echo $cont;?>" id="txt_hrsEfectivas<?php echo $cont;?>" size="10" disabled="disabled"
								onkeypress="return permite(event,'num', 2);" class="caja_de_num" value="<?php echo number_format($datos["hrs_efectivas"],2,".",",");?>"/>
								<?php
					echo
							"</td>
							<td class='$nom_clase'>$datos[turno]</td>
							<td class='$nom_clase'>$datos[observaciones]</td>";
					echo "
						</tr>";
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";				
				}while($datos=mysql_fetch_array($rs));
				//Fin de la tabla donde se muestran los resultados de la consulta
				echo "<input type='hidden' name='hdn_equipo' id='hdn_equipo' value='$equipo'/>";
				echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
				echo "</table>";
			}//Fin 	if(($datos['metrica']==HOROMETRO))
			else{// Mostrar la tabla correspondiente a los ODOMETROS
			//Desplegar los resultados de la consulta en una tabla
				echo "				
				<table cellpadding='5' width='100%'>				
					<tr>
						<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>ID EQUIPO</td>
						<td class='nombres_columnas' align='center'>NOMBRE DEL EQUIPO</td>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
						<td class='nombres_columnas' align='center'>OD&Oacute;METRO INICIAL</td>
						<td class='nombres_columnas' align='center'>OD&Oacute;METRO FINAL</td>
						<td class='nombres_columnas' align='center'>OD&Oacute;METRO EFECTIVO</td>
						<td class='nombres_columnas' align='center'>TURNO</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>
							<td class='$nom_clase'>$datos[equipos_id_equipo]</td>
							<td class='$nom_clase'>$datos[nom_equipo]</td>
							<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
							<td class='$nom_clase'>$datos[reg_inicial] ".'KMS'."</td>
							<td class='$nom_clase'>$datos[reg_final] ".'KMS'."</td>   
							<td class='$nom_clase'>$datos[km_servicio] ".'KMS'."</td>
							<td class='$nom_clase'>$datos[turno]</td>
							<td class='$nom_clase'>$datos[observaciones]</td>
						</tr>";
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";				
				}while($datos=mysql_fetch_array($rs));
				//Fin de la tabla donde se muestran los resultados de la consulta
				echo "</table>";
			}
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
		}
	}//Fin de la funcion mostrarMetrica
	
	//Funcion que modifica las Metricas registradas
	function modificarRegistroMetricas(){
		//Abrir la conexion con la Bd de Mtto
		$conn=conecta("bd_mantenimiento");
		//contador para recorrer el vector POST
		$cont=1;
		//Obtener la cantidad de checkbox´s
		$cantidad=$_POST["hdn_cant"];
		//Obtener el nombre del Equipo
		$equipo=$_POST["hdn_equipo"];
		echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
		//Recorrer el vector POST
		do{
			//Verificar check por check cuales estan definidos
			if(isset($_POST["ckb_editarMetrica$cont"])){
				//Recuperar los datos para preparar la sentencia de actualizacion
				$fecha=$_POST["hdn_fecha$cont"];
				$turno=$_POST["hdn_turno$cont"];
				$horoIni=$_POST["txt_horoIni$cont"];
				$horoFin=$_POST["txt_horoFin$cont"];
				$hrsEfectivas=$_POST["txt_hrsEfectivas$cont"];
				//Actualizar el acumulado de Servicios con las Horas Efectivas
				$resUpd=acumuladoServicios($hrsEfectivas,$fecha,$equipo,$turno);
				if($resUpd==""){
					//Preparar la sentencia de actualizaion de datos sobre el registro de horometros
					$sql_stm="UPDATE horometro_odometro SET reg_inicial='$horoIni',reg_final='$horoFin',hrs_efectivas='$hrsEfectivas' WHERE equipos_id_equipo='$equipo' AND fecha='$fecha' AND turno='$turno'";
					//Ejecutar la sentencia de Actulizacion
					$rs=mysql_query($sql_stm);
					if(!$rs){
						//Obtenemos el error que se haya generado
						$error=mysql_error();
						//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
						echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
					}
				}
				else{
					//Si los datos no se modificaron correctamente, se redirecciona a la pagina de error
					echo "<meta http-equiv='refresh' content='0;url=error.php?err=$resUpd'>";
				}
			}
			//Incrementar el contador
			$cont++;
		}while($cont<$cantidad);
		//Cerrar la conexion a la BD de Mtto
		mysql_close($conn);
		//Registrar la Operacion en la Bitácora de Movimientos
		registrarOperacion("bd_mantenimiento","$equipo","ActualizarRegMetrica",$_SESSION['usr_reg']);
		//Redireccionar a la pantalla de Exito
		echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
	}//Fin de function modificarRegistroMetricas()
	
	//Funcion que actualiza el acumulado de servicios
	function acumuladoServicios($hrsEfectivas,$fecha,$equipo,$turno){
		//Extraer las horas efectivas para el equipo X en lla fecha y tuno seleccionados
		$datos=mysql_fetch_array(mysql_query("SELECT hrs_efectivas FROM horometro_odometro WHERE equipos_id_equipo='$equipo' AND fecha='$fecha' AND turno='$turno'"));
		//Restar las horas efectivas "nuevas" menos las horas Efectivas Registradas
		$hrsAcum=$hrsEfectivas-$datos["hrs_efectivas"];
		//Sentencia para actualizar el acumulado de Servicios segun la suma de las horas registradas mas la nuevas, para el equipo siempre y cuando la fecha sea mayor o igual a la del ultimo Mantenimiento
		$sql_stm="UPDATE acumulado_servicios SET hrs_acum=hrs_acum+$hrsAcum WHERE equipos_id_equipo='$equipo' AND fecha_mtto<='$fecha'";
		//Ejecutar la sentencia de Actulizacion
		$rs=mysql_query($sql_stm);
		//Verificar que la sentencia de actualizacion se realizo correctamente
		if($rs)
			return "";
		else
			return $err=mysql_error();
	}//Fin de acumuladoServicios($hrsEfectivas,$fecha,$equipo)
?> 