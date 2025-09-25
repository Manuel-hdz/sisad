<?php
	/**
	  * Nombre del Módulo: Seguridsd                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández - Nadia Madahi López Hernandez
	  * Fecha: 10/Marzo/2012
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de los Planes de contingencia a realizar
	  **/	 	 
	 	 	
	
	//Esta funcion genera la Clave para la alerta del plan generado
	function obtenerIdAlerta(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las  letras en la Id del plan que sa va registrar.
		$id_cadena = "APC";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener los registros de los planes  del mes y año en curso 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de plan registrado
		$stm_sql = "SELECT COUNT(id_alerta_plan) AS cant FROM alertas_planes_contingencia WHERE id_alerta_plan LIKE 'APC$mes$anio%'";
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}	
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id_cadena;
	}//Fin de la Funcion obtenerIdAlertaPlan()	
	
	
	/* Esta función se ecarga de buscar las pruebas que tienee que ser programadas*/  
	function monitorearAlertasPlanContingencia(){	

		//Borrar la tabla de alertas para guardar los registros de las alertas
		mysql_query("DELETE FROM alertas_planes_contingencia WHERE estado = 'NO'");							
		
		/*********************************************************************************
		 * DETERMINAR QUE PRUEBAS ESTAN PROXIMAS A SER EJECUTADAS                        *
		 *********************************************************************************/
		$fechaBusq = date("d/m/Y");//Obtener fecha actual
		//Crear la Sentencia SQL para obtener todas las fechas de planes que han sido programados, las cuales no se les haya registrado los resultados o tiempos correspondientes
		$stm_sql = "SELECT id_plan, fecha_programada FROM planes_contingencia WHERE estado = 'NO'";		 
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			do{
				$idPlanContingencia = $datos['id_plan'];
				//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
				$fechaBdMod = modFecha($datos["fecha_programada"],1);
				$seccFechaBD = split("/",$fechaBdMod);
				$seccFechaBusq = split("/",$fechaBusq);
				//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año), Obtener la cantidad de dias por fecha
				$fechaIni_enDias = gregoriantojd ($seccFechaBD[1], $seccFechaBD[0], $seccFechaBD[2]);
				$fechaFin_enDias = gregoriantojd ($seccFechaBusq[1], $seccFechaBusq[0], $seccFechaBusq[2]);
				//Obtener la diferencia de dias de la fecha programada y la fecha actual
				//echo "Diferencia dias alerta".$diferencia = ($fechaIni_enDias-$fechaFin_enDias);
				$diferencia = ($fechaIni_enDias-$fechaFin_enDias);
				
				//Si Faltan 5 o menos dias para realizar el plan de contingencia, guardar la alerta para ser emitida
				if($diferencia<=5){
					$fecha = date("Y-m-d");
					//Antes de registrar la alerta, verificar si esta registrado el plan en una alerta y si lo estan revisar el estado de la misma.				
					if($alerta=mysql_fetch_array(mysql_query("SELECT * FROM alertas_planes_contingencia WHERE planes_contingencia_id_plan = '$idPlanContingencia' 
						GROUP BY estado ASC"))){							
						//El estado 'SI' indica que la alerta ya fue atendida; 
						if($alerta['estado']=='SI'){
							$idAlerta = obtenerIdAlerta();
							$stm_sqlAlerta = "INSERT INTO alertas_planes_contingencia VALUES('$idPlanContingencia' , '$idAlerta', 'NO', '$diferencia' )";
							$rs2 = mysql_query($stm_sqlAlerta);	
						}
					}
					//De lo contrario registrarlo en la Base de datos por primera vez
					else{
					
						$idAlerta=obtenerIdAlerta();
						
						$conn = conecta("bd_seguridad");
						
						$stm_sqlAlerta = "INSERT INTO alertas_planes_contingencia VALUES('$idPlanContingencia' , '$idAlerta', 'NO', '$diferencia' )";
						$rs2 = mysql_query($stm_sqlAlerta);	
					}
				}
				
			}while($datos=mysql_fetch_array($rs));
		}	 
	}//Fin de la funcion monitorearAlertasPlanContingencia()
	
	
	//Esta funcion muestra las alertas registradas en la BD, las alertas se mostraran desde 5 dias antes y hasta que sean registrados los resultados de los Planes de Contingencias
	function desplegarAlertasPlanContingencia(){
		//Conectarse con la BD de Seguridad y mantener la conexion para utilizar las funciones de monitorearAlertasPlanContingencia(), obtenerIdAlerta() y
		//las funciones para desplegar las alertas
		$conn = conecta("bd_seguridad");	
		
		//Llamar a la función para monitoreo las Alertas del Plan que estan proximas a ser ejecutadas o realizadas
		monitorearAlertasPlanContingencia();									
		
		/*****************************/
		//las funciones para desplegar las alertas
		$conn = conecta("bd_seguridad");	
		/*****************************/
		
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden al plan que se programo  
		$stm_sql = "SELECT id_alerta_plan, planes_contingencia_id_plan FROM alertas_planes_contingencia WHERE estado = 'NO'";		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Sentencia para contar el numero de alertas
		$num_alertas=mysql_num_rows($rs);
																																							
		/**********************************************************************************
		 * DESPLEGAR ALERTAS PARA LOS PLANES QUE SE ENCUENTRAN PRÓXIMOS A SER REALIZADOS *
		 **********************************************************************************/			
					
		//Si la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas==1){									
			//Extraer el Id del plan de prueba del Result Set
			$datos = mysql_fetch_array($rs);
			//Obtener la Cantidad de dias restantes o exedidos para realizar el plan indicada en l tabla de alertas
			$datos_cantRestante = mysql_fetch_array(mysql_query("SELECT dias_restantes FROM alertas_planes_contingencia WHERE id_alerta_plan = '$datos[id_alerta_plan]'"));
			$cantRestante = $datos_cantRestante['dias_restantes'];
			//Deplegar Ventana de Alerta de una sola alerta
			mostrarAlertasPC($datos['planes_contingencia_id_plan'],$cantRestante);
		}
		//Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de las planes que 
		//estan proximas para realizar o ejecutar
		else if($num_alertas>1){								
			//Mostrar solo un mensaje de varios planes estan a punto de ejecutar
			notificarAlertaPC($num_alertas);
		}												
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);								
	}//Cierre de la funcion desplegarAlertasPlanContingencia();
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertasPC($id_plan, $cantRestante){		
		//Crear la sentencia para obtener los datos del Plan, el cual ha generado una alerta
		$stm_sql = "SELECT DISTINCT planes_contingencia_id_plan, id_alerta_plan, planes_contingencia.estado, fecha_programada, nom_simulacro FROM  alertas_planes_contingencia 
					JOIN planes_contingencia ON planes_contingencia_id_plan = id_plan WHERE planes_contingencia_id_plan = '$id_plan' ";
					
		//Variable para almacenar el mensaje que sera desplegado en la alerta
		$msg="";
		
			
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Desplegar Ventana si los datos son extraidos exitosamente
		if($datos=mysql_fetch_array($rs)){
			//Determinar el Mensaje que sera desplegado dependiendo del numero de dias restantes
			if($cantRestante==0)
				$msg="El Plan Contingencia <strong>$datos[nom_simulacro]</strong> debe ser realizado <strong>HOY</strong>"; 
			if($cantRestante>0)
				$msg="El Plan Contingencia <strong> $datos[nom_simulacro] </strong> esta a <strong>$cantRestante </strong>d&iacute;as de Realizaci&oacute;n de Plan Contingencia ";
			//Determinar el color de la ventana en base a la cantidad de dias faltantes o restantes
			$nom_form = "";
			if($cantRestante<0){
				$cantRestante = $cantRestante * -1;
				$msg = " Han Pasado <strong>$cantRestante</strong> d&iacute;as  para la realizaci&oacute;n de Plan Cotingencia  <strong> $datos[nom_simulacro] </strong>"; 
				$nom_form = "_red";
				
			}				
			?>				
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>
									
			<body>						
				<script type="text/javascript" language="javascript">
					setTimeout("popup_show('popup_plan', 'popup_drag_plan', 'popup_exit_plan', 'screen-center', 0, 200);",1000);
				</script>
				<!-- ********************************************************* Popup Window **************************************************** -->
				<div class="sample_popup" id="popup_plan" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_drag_plan">
						<img class="menu_form_exit" id="popup_exit_plan" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
								RECORDATORIO PLAN CONTINGENCIA
					</div>
		
					<div class="menu_form_body<?php echo $nom_form;?>">
					<form name="frm_mostrarAlerta" action="frm_consultarAlertasPlan.php" method="post">				
					<table>
						<tr>
							<td colspan="2" align="center">
								<?php echo $msg;?>																				
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="2">Fecha Programada: <strong><?php echo modFecha($datos['fecha_programada'],1);?></strong></td>
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><strong>&iquest;Registrar Resultado?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="sbt_aceptar" type="submit" value="Aceptar" class="botones" title="Registrar Resultados del Plan Ejecutado" 
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
	}//Cierre de la funcion mostrarAlertasPC($id_plan, $cantRestante)

	
	/*Esta función despliega en una ventana de Alerta la cantidad de planes que son candidatas a ser realizadas*/
	function notificarAlertaPC($num_alertas){?>
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>								
														
		<body>				
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popup_plan', 'popup_drag_plan', 'popup_exit_plan', 'screen-center', 0, 200);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup_plan" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag_plan">
				<img class="menu_form_exit" id="popup_exit_plan" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					RECORDATORIO PLAN CONTINGENCIA
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_consultarAlertasPlan.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							Un Total de <strong><?php echo $num_alertas; ?></strong> Planes de Contingencia Est&aacute;n Pr&oacute;ximos a Ejecutarse					
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda Consultar la Programaci&oacute;n de los Planes de Contingencia</u>
						</td>						
					</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Planes Contingencia Programados?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Planes Ahora!" 
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