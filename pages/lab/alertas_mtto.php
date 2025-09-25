<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 24/junio/2011
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de Equipos con matenimientos a realizar.
	  **/	 	 

	//Genera la Id de la Alerta que será registrada en la tabla de alertas
	function obtenerIdAlertaMtto(){		
		//Definir las tres letras en la Id de la Alerta
		$id_cadena = "ALR";
		
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el año actual para ser agregados en la consulta y asi obtener las entradas del mes y año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de alertas registradas en la BD
		$stm_sql = "SELECT MAX(id_alertas_mtto) AS clave FROM alertas_mtto WHERE id_alertas_mtto LIKE 'ALR$mes$anio%'";
		//Ejecutar Sentencia
		$rs = mysql_query($stm_sql);		
		//Evaluar Resultados y Generar Id a partir de ellos
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}		
		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdAlertaMtto()
	
	
	/*
	 * Esta función se ecarga de buscar los Equipos que se encuentran en la fecha limite pare realizar el mantenimiento.
	 */  
	function monitorearEquipos(){
		//Borrar la tabla de alertas para registrar los nuevos cambios que existan en base a los Equipos
		mysql_query("DELETE FROM alertas_mtto WHERE estado = 1");							
		/*********************************************************************************
		 * DETERMINAR QUE EQUIPOS ESTAN PRÓXIMOS A RECIBIR  MATENIMIENTO                 *
		 *********************************************************************************/
		$fechaBusq=date("d/m/Y");
		$stm_sql = "SELECT equipo_lab_no_interno, fecha_mtto, tipo_servicio FROM cronograma_servicios WHERE estado=0";		 
		 //Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			
			do{
				$idCronograma=$datos['equipo_lab_no_interno'];
				//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
				$fechaBdMod=modFecha($datos["fecha_mtto"],1);
				$seccFechaBD = split("/",$fechaBdMod);//Formato de la Fecha dd/mm/aaaa
				$seccFechaBusq = split("/",$fechaBusq);
				//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año)
				$fechaIni_enDias = gregoriantojd ($seccFechaBD[1], $seccFechaBD[0], $seccFechaBD[2]);
				$fechaFin_enDias = gregoriantojd ($seccFechaBusq[1], $seccFechaBusq[0], $seccFechaBusq[2]);
				$diferencia = ($fechaIni_enDias-$fechaFin_enDias);				
					
				//Verificamos el tipo de servicio para enviar la alerta
				if($datos["tipo_servicio"]=="CALIBRACION"){
					if($diferencia<=30){
						$fecha=date("Y-m-d");
						$sql_stm = "SELECT * FROM alertas_mtto WHERE equipo_lab_no_interno = '$idCronograma' AND origen='CALIBRACION' GROUP BY estado ASC";
						//Antes de registrar la alerta, verificar si esta registrado el equipo en una alerta y si lo estan revisar el estado de la misma.				
						if($alerta=mysql_fetch_array(mysql_query($sql_stm))){							
							//El estado 2 indica que la alerta ya fue atendida; 
							if($alerta['estado']==2){
								$idAlerta=obtenerIdAlertaMtto();
								$stm_sqlAlerta = "INSERT INTO alertas_mtto VALUES('$idAlerta' , '$datos[equipo_lab_no_interno]', '1', '$fecha','CALIBRACION'
												 '$diferencia')";
								mysql_query($stm_sqlAlerta);
							}
						}
						//De lo contrario registrarlo en la Base de datos por primera vez
						else{
							$idAlerta=obtenerIdAlertaMtto();
							$stm_sqlAlerta = "INSERT INTO alertas_mtto VALUES('$idAlerta' , '$datos[equipo_lab_no_interno]', '1', '$fecha','CALIBRACION', '$diferencia')";
							mysql_query($stm_sqlAlerta);
						}
					}
				}
				
				
				if($datos["tipo_servicio"]=="MANTENIMIENTO"){
					if($diferencia<=15){
						$fecha=date("Y-m-d");
						$sql_stm = "SELECT * FROM alertas_mtto WHERE equipo_lab_no_interno = '$idCronograma' AND origen='MANTENIMIENTO' GROUP BY estado ASC";
						//Antes de registrar la alerta, verificar si esta registrado el equipo en una alerta y si lo estan revisar el estado de la misma.				
						if($alerta=mysql_fetch_array(mysql_query($sql_stm))){							
							//El estado 2 indica que la alerta ya fue atendida; 
							if($alerta['estado']==2){
								$idAlerta=obtenerIdAlertaMtto();
								$stm_sqlAlerta = "INSERT INTO alertas_mtto VALUES('$idAlerta' , '$datos[equipo_lab_no_interno]', '1', '$fecha','MANTENIMIENTO','$diferencia')";
								mysql_query($stm_sqlAlerta);
							}
						}
						//De lo contrario registrarlo en la Base de datos por primera vez
						else{
							$idAlerta=obtenerIdAlertaMtto();
							$stm_sqlAlerta = "INSERT INTO alertas_mtto VALUES('$idAlerta' , '$datos[equipo_lab_no_interno]', '1', '$fecha', 'MANTENIMIENTO',
											 '$diferencia')";
							mysql_query($stm_sqlAlerta);
						}
					}
				}
				
			}while($datos=mysql_fetch_array($rs));
		}	 
	}//Fin de la funcion monitorearEquipos()
	
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertasMtto(){
		//Conectarse con la BD de Laboratorio y mantener la conexion para utilizar las funciones de monitorearEquipos(), obtenerIdAlertaMtto() 
		//y las funciones para desplegar las alertas
		$conn = conecta("bd_laboratorio");	
		
		//Llamar a la función para monitoreo de los Equipos que estan proximas a ser probadas
		monitorearEquipos();									
		
		
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a prueba 
		$stm_sql = "SELECT id_alertas_mtto, equipo_lab_no_interno FROM alertas_mtto WHERE estado = 1";		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Sentencia para contar el numero de alertas
		$num_alertas=mysql_num_rows($rs);
																																							
		/**********************************************************************************
		 * DESPLEGAR ALERTAS PARA LOS EQUIPOS QUE SE ENCUENTRAN PRÓXIMAS A TENER MTTOS    *
		 **********************************************************************************/			
					
		//Si la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas==1){									
			//Extraer el Id del plan de prueba del Result Set
			$datos = mysql_fetch_array($rs);
			//Obtener la Cantidad de dias restantes o exedidos para realizar la prueba indicada en el cronometro de servicios    
			$datos_cantRestante = mysql_fetch_array(mysql_query("SELECT dias_restantes FROM alertas_mtto WHERE id_alertas_mtto = '$datos[id_alertas_mtto]'"));
			$cantRestante = $datos_cantRestante['dias_restantes'];
			//Deplegar Ventana de Alerta de una sola alerta
			mostrarAlertasMtto($datos['equipo_lab_no_interno'],$cantRestante);
		}
		//Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de 
		//los Equipos que estan proximas a tener Mantenimiento
		else if($num_alertas>1){								
			//Mostrar solo un mensaje de varios Equipos estan a punto de realizar los Mantenimientos
			notificarAlertaMtto($num_alertas);
		}												
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);								
	}//Cierre de la funcion desplegarAlertasMtto();
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertasMtto($idCrono, $cantRestante){		
		
		//Crear la sentencia para obtener los datos del Equipo, el cual ha generado una alerta
		$stm_sql = "SELECT equipo_lab_no_interno, nombre, fecha_mtto, tipo_servicio FROM (cronograma_servicios JOIN equipo_lab ON no_interno=equipo_lab_no_interno) 
					WHERE equipo_lab_no_interno='$idCrono'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Desplegar Ventana si los datos son extraidos exitosamente
		if($datos=mysql_fetch_array($rs)){
			//Determinar el Color y el Mensaje de la ventana en base a la cantidad de dias faltantes o restantes
			$nom_form = "";
			$msj = "El Equipo <strong>$datos[nombre]</strong> esta a <strong>$cantRestante</strong> d&iacute;as de Realizaci&oacute;n del Matenimiento";
			if($cantRestante<0){ 				 
				$nom_form = "_red";
				$cantRestante = $cantRestante * -1;
				$msj = "Han Pasado <strong>$cantRestante</strong> d&iacute;as para la Realizaci&oacute;n del Matenimiento al Equipo <strong>$datos[nombre]</strong>";
			}?>					
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>
									
			<body>						
				<script type="text/javascript" language="javascript">
					setTimeout("popup_show('popupMtto', 'popup_drag_mtto', 'popup_exit_mtto', 'screen-bottom-right', 0, 0);",1000);
				</script>
				<!-- ********************************************************* Popup Window MTTO **************************************************** -->
				<div class="sample_popup" id="popupMtto" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_drag_mtto">
						<img class="menu_form_exit" id="popup_exit_mtto" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
							ALERTA DE MANTENIMIENTO
					</div>
		
					<div class="menu_form_body<?php echo $nom_form;?>">
					<form name="frm_mostrarAlerta" action="frm_consultarAlertasMtto.php" method="post">				
					<table>
						<tr>
							<td colspan="2" align="center">								 
								<?php echo $msj; ?>
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="2">Fecha Programada del Mantenimiento: <strong><?php echo modFecha($datos['fecha_mtto'],1);?></strong></td>
						</tr>
						<tr>
							<td align="center" colspan="2">Tipo Servicio: <strong><?php echo $datos['tipo_servicio'];?></strong></td>
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><strong>&iquest;Registrar Resultado?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="sbt_aceptar" type="submit" value="Aceptar" class="botones" title="Registrar Resultado Ahora!" 
								onMouseOver="window.status='';return true" />
								<input type="hidden" name="hdn_org" id="hdn_org" />
							</td>
						</tr>
					</table>
					</form>
					</div>
				</div>
				<!-- ********************************************************* Popup Window **************************************************** -->						
			</body>
			<?php					
		} 
	}//Cierre de la funcion mostrarAlertas($idCrono, $cantRestante)

	
	/*Esta función despliega en una ventana de Alerta la cantidad de Equipos que son candidatos a recibir Mantenimiento*/
	function notificarAlertaMtto($num_alertas){?>
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>								
														
		<body>				
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popupMtto', 'popup_drag_mtto', 'popup_exit_mtto', 'screen-bottom-right', 0, 0);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popupMtto" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag_mtto">
				<img class="menu_form_exit" id="popup_exit_mtto" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					ALERTA DE MANTENIMIENTO
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_consultarAlertasMtto.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							Un Total de <strong><?php echo $num_alertas; ?></strong> Equipos est&aacute;n Pr&oacute;ximos a Realizaci&oacute;n de 
							<strong>Mantenimiento</strong>							
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda Consultar la Programaci&oacute;n de Mantenimiento</u>
						</td>						
					</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Equipos?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Equipos Ahora!" 
								onMouseOver="window.status='';return true" />
								<input type="hidden" name="hdn_org" id="hdn_org" />
							</td>
						</tr>
					</table>
					</form>
			</div>
		</div>		
		<!-- ********************************************************* Popup Window **************************************************** -->
		</body><?php 
	}
?>