<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 15/Diciembre/2011
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de pruebas a realizar.
	  **/	 	 
	
	/* Esta función se ecarga de buscar las pruebas que tienee que ser programadas*/  
	function monitorearRecordatoriosAuditoria(){
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		//Obtenemos el id del Departameno
		$depto=obtenerDato("bd_usuarios", "usuarios", "depto", "usuario", $user);
		//Ponemos dicho Departamento en Mayusculas para realizar la comparacion con el campo de la BD
		$depto=strtoupper($depto);
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
	    /******************************************************************************
		* DETERMINAR QUE RECORDATORI]OS ESTAN PROXIMAS A SER MOSTRADOS                *
	    ******************************************************************************/
		$fechaBusq=date("d/m/Y");
	 	$stm_sql = "SELECT id_alertas_plan_acciones, plan_acciones_id_plan_acciones, fecha_generacion FROM (alertas_plan_acciones JOIN  plan_acciones ON 		
						plan_acciones_id_plan_acciones=id_plan_acciones) WHERE estado='1' AND 
						area_auditada='$depto'";		 
		 //Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			do{
				$idRecordatorio=$datos['id_alertas_plan_acciones'];
				//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
				$fechaBdMod=modFecha($datos["fecha_generacion"],1);	
				$seccFechaBD = split("/",$fechaBdMod);
				$seccFechaBusq = split("/",$fechaBusq);
				//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año), Obtener la cantidad de dias por fecha
				$fechaIni_enDias = gregoriantojd ($seccFechaBD[1], $seccFechaBD[0], $seccFechaBD[2]);
				$fechaFin_enDias = gregoriantojd ($seccFechaBusq[1], $seccFechaBusq[0], $seccFechaBusq[2]);
				$diferencia = $fechaIni_enDias-$fechaFin_enDias;			
			}while($datos=mysql_fetch_array($rs));
		}	 
	}//Fin de la funcion monitorearRecordatoriosExternos()
	
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertasPlanAcciones(){
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		//Obtenemos el id del Departameno
		$depto=obtenerDato("bd_usuarios", "usuarios", "depto", "usuario", $user);
		//Ponemos dicho Departamento en Mayusculas para realizar la comparacion con el campo de la BD
		$depto=strtoupper($depto);
		//Conectarse con la BD de Aseguramiento y mantener la conexion para utilizar las funciones de monitorearRecordatoriosInternos() y 
		//las funciones para desplegar las alertas
		$conn = conecta("bd_aseguramiento");	
		
		//Llamar a la función para monitoreo de las mezclas que estan proximas a ser probadas
		monitorearRecordatoriosAuditoria();									
		
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a prueba 
		$stm_sql = "SELECT id_alertas_plan_acciones, fecha_generacion FROM (alertas_plan_acciones JOIN plan_acciones ON
						 id_plan_acciones=plan_acciones_id_plan_acciones) WHERE estado = '1' AND area_auditada='$depto'";		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Sentencia para contar el numero de alertas
		$num_alertas=mysql_num_rows($rs);
		
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);																																					
		/**********************************************************************************
		 * DESPLEGAR ALERTAS PARA LAS PRUEBAS QUE SE ENCUENTRAN PRÓXIMAS A SER REALIZADAS *
		 **********************************************************************************/			
					
		//Si la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas==1){		
			$fechaBusq=date("d/m/Y");
			//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
			$fechaBdMod=modFecha($datos["fecha_generacion"],1);	
			$seccFechaBD = split("/",$fechaBdMod);
			$seccFechaBusq = split("/",$fechaBusq);
			//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año), Obtener la cantidad de dias por fecha
			$fechaIni_enDias = gregoriantojd ($seccFechaBD[1], $seccFechaBD[0], $seccFechaBD[2]);
			$fechaFin_enDias = gregoriantojd ($seccFechaBusq[1], $seccFechaBusq[0], $seccFechaBusq[2]);
			$cantRestante = $fechaIni_enDias-$fechaFin_enDias;	

		
			//Deplegar Ventana de Alerta de una sola alerta
			mostrarAlertasPA($cantRestante,$datos['id_alertas_plan_acciones']);
		}
		
		//Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de las mezclas que 
		//estan proximas para realizar pruebas
		else if($num_alertas>1){								
			//Mostrar solo un mensaje de varios prueba estan a punto de realizar las pruebas
			notificarAlertaPA($num_alertas);
		}												
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);								
	}//Cierre de la funcion desplegarAlertas();
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertasPA($dias,$idAlerta){	
		
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		
		//Obtenemos el id del Departameno
		$depto=obtenerDato("bd_usuarios", "usuarios", "depto", "usuario", $user);
		//Ponemos dicho Departamento en Mayusculas para realizar la comparacion con el campo de la BD
		$depto=strtoupper($depto);
		
		//Conectarse con la BD de Aseguramiento y mantener la conexion para utilizar las funciones de monitorearRecordatoriosInternos() y 
		//las funciones para desplegar las alertas
		$conn = conecta("bd_aseguramiento");	
		
		//Crear la sentencia para obtener los datos del Equipo, el cual ha generado una alerta
		$stm_sql = "SELECT id_alertas_plan_acciones, fecha_generacion FROM (alertas_plan_acciones JOIN plan_acciones ON plan_acciones_id_plan_acciones=id_plan_acciones)
		 			WHERE id_alertas_plan_acciones='$idAlerta' AND area_auditada='$depto'";
		
		//Variable para almacenar el mensaje que sera desplegado en la alerta
		$msg="";
				
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Desplegar Ventana si los datos son extraidos exitosamente
		if($datos=mysql_fetch_array($rs)){
			//Determinar el Mensaje que sera desplegado dependiendo del numero de dias restantes
			if($dias==0){
				$msg="El Plan de Acci&oacute;n debe ser realizado <strong>HOY</strong>"; 
			}
			if($dias>0){
				$fecha = modFecha($datos['fecha_generacion'],1);
				$msg="El Plan de Acciones con Fecha de Generaci&oacute;n<strong> ".$fecha."</strong> esta a <strong>$dias </strong>d&iacute;as de Vencer";
			}
			//Determinar el color de la ventana en base a la cantidad de dias faltantes o restantes
			$nom_form = "";
			if($dias<0){
				$dias = $dias * -1;
				$msg = " Han Pasado <strong>$dias</strong> d&iacute;as  para Cumplir <strong>Plan de Acciones</strong>"; 
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
					setTimeout("popup_show('popupPA', 'popup_dragPA', 'popup_exitPA', 'element', 50, 30);",1000);
				</script>
				<!-- ********************************************************* Popup Window **************************************************** -->
				<div class="sample_popup" id="popupPA" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_dragPA">
						<img class="menu_form_exit" id="popup_exitPA" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
								AVISO PLAN DE ACCIONES PUBLICADO
					</div>
		
					<div class="menu_form_body<?php echo $nom_form;?>">
					<form name="frm_mostrarAlerta" action="frm_consultarPlanAcciones.php?idAlerta=<?php echo $idAlerta;?>&url=1" method="post">				
					<table>
						<tr>
							<td colspan="2" align="center">
								<?php echo $msg;?>
								<input type="hidden" name="hdn_idPlan" value="<?php echo $datos['id_alertas_plan_acciones'];?>" />														
								<input type="hidden" name="hdn_fechaProgramada" value="<?php echo $datos['fecha_generacion'];?>" />																						
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="2">Fecha Programada: <strong><?php echo modFecha($datos['fecha_generacion'],1);?></strong></td>
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><strong>&iquest;Ver Detalle?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="sbt_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Plan Acciones Ahora!" 
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
	}//Cierre de la funcion mostrarAlertas($id_plan_prueba, $cantRestante)

	
	/*Esta función despliega en una ventana de Alerta la cantidad de mezclas que son candidatas a recibir pruebas*/
	function notificarAlertaPA($num_alertas){?>
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>								
														
		<body>				
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popupPA', 'popup_dragPA', 'popup_exitPA', 'element', 50, 430);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popupPA" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_dragPA">
				<img class="menu_form_exit" id="popup_exitPA" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					AVISO PLAN DE ACCIONES PUBLICADO
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlertaRecordatorio" action="frm_consultarPlanAcciones.php?url=1" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							Un Total de <strong><?php echo $num_alertas; ?></strong> <strong>Planes de Acci&oacute;n</strong> Han Sido Publicados						
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda Consultar Plan de Acci&oacute;n</u>
						</td>						
					</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Detalle?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Avisos Ahora!" 
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
