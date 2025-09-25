<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 09/Enero/2011
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar las alertas del plan de acciones en el caso de que ya haya sido complementado; esto con el objetivo recordarle al
	  *	Administrador si se cumplio con la fecha planeada de la accion en el plan de acciones
	  **/	 	 
	 	 	
	
	/* Esta función se ecarga de buscar las pruebas que tienee que ser programadas*/  
	function monitorearFechaPlanAcciones(){
		 /******************************************************************************
		 * DETERMINAR QUE PLANES ESTAN PROXIMAS A SER MOSTRADOS                *
		 ******************************************************************************/
		 //Tomamos la fecha del dia actual para tomarla como limite de fechas
		$fechaBusq=date("d/m/Y");
		//Creamos la sentencia
		$stm_sql = "SELECT referencias_id_referencia, detalle_referencias.plan_acciones_id_plan_acciones, fecha_planeada, fecha_real_terminacion, validacion_ase FROM 
					(detalle_referencias JOIN alertas_plan_acciones 
					ON detalle_referencias.plan_acciones_id_plan_acciones=alertas_plan_acciones.plan_acciones_id_plan_acciones)  WHERE estado='0'";		 
		 //Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			do{	
				//Verificamos que la validacion del Administrador se encuentre vacia de ser asi el Administrador aun no lo aprueba
				if($datos['fecha_real_terminacion']==NULL&&$datos['validacion_ase']==""){
					//Guardamos el id del Plan de Acciones 
					$idPA=$datos['plan_acciones_id_plan_acciones'];
					//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
					$fechaBdMod=modFecha($datos["fecha_planeada"],1);
					$seccFechaBD = split("/",$fechaBdMod);
					$seccFechaBusq = split("/",$fechaBusq);
					//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año)
					$fechaIni_enDias = gregoriantojd ($seccFechaBD[1], $seccFechaBD[0], $seccFechaBD[2]);
					$fechaFin_enDias = gregoriantojd ($seccFechaBusq[1], $seccFechaBusq[0], $seccFechaBusq[2]);
					//Obtenemos la diferencia
					$diferencia = ($fechaIni_enDias-$fechaFin_enDias);				
				}
			}while($datos=mysql_fetch_array($rs));
		}	 
	}//Fin de la funcion monitorearRecordatoriosInternos()
	
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertasFechaReferencias(){
		//Conectarse con la BD de Aseguramiento y mantener la conexion para utilizar las funciones de monitorearRecordatoriosInternos() y 
		//las funciones para desplegar las alertas
		$conn = conecta("bd_aseguramiento");	
		
		//Llamar a la función para monitoreo de las mezclas que estan proximas a ser probadas
		monitorearFechaPlanAcciones();									
		
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a prueba 
		$stm_sql = "SELECT referencias_id_referencia,detalle_referencias.plan_acciones_id_plan_acciones, fecha_planeada,fecha_real_terminacion, validacion_ase FROM 
					(detalle_referencias JOIN alertas_plan_acciones
					ON detalle_referencias.plan_acciones_id_plan_acciones=alertas_plan_acciones.plan_acciones_id_plan_acciones) WHERE estado='0' ";		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Variable que nos permitira conocer el numero de registros
		$num_alertas=0;
		//Variable que permite conocer si entreo en la siguiente conodicion; cuando es un solo registro
		$aux=0;
		//Guardamos la fecha planeada
		$fechaPlaneada="";
		//Guardamos el plan de accion
		$planAccion = "";	
		//Comprobamos cuales registros son los que estan con la validacion de ase para contarlos
		if($datos=mysql_fetch_array($rs)){
			do{
				if($datos['fecha_real_terminacion']==NULL&&$datos['validacion_ase']==""){
					$num_alertas++;
					$aux=1;
					$fechaPlaneada = $datos['fecha_planeada'];
					$planAccion = $datos['plan_acciones_id_plan_acciones'];
				}
			}while($datos=mysql_fetch_array($rs));																																				
		}
		/**********************************************************************************
		 * DESPLEGAR ALERTAS PARA LAS PRUEBAS QUE SE ENCUENTRAN PRÓXIMAS A SER REALIZADAS *
		 **********************************************************************************/			
		//Si la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas==1&&$aux!=0){		
			//Tomamos las fechas y las convertimos a formato necesario para la consulta		
			$fechaIni=$fechaPlaneada;
			$fechaFin=date("Y-m-d");
			//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
			$cantRestante = restarFechas($fechaIni, $fechaFin);	
			

			//Deplegar Ventana de Alerta de una sola alerta
			mostrarAlertasReferencias($cantRestante,$planAccion);	
		}
			//Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de las mezclas que 
			//estan proximas para realizar pruebas
			else if($num_alertas>1){								
				//Mostrar solo un mensaje de varios prueba estan a punto de realizar las pruebas
				notificarAlertaReferencias($num_alertas);
			}												
		//Cerrar la conexion con la BD		
		mysql_close($conn);								
	}//Cierre de la funcion desplegarAlertas();
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertasReferencias($cantRestante,$idPA){		
		//Crear la sentencia para obtener los datos del plan de acciones, el cual ha generado una alerta
		$stm_sql = "SELECT detalle_referencias.plan_acciones_id_plan_acciones, fecha_planeada, fecha_real_terminacion,validacion_ase, area_auditada 
					FROM ((detalle_referencias JOIN plan_acciones ON 
					id_plan_acciones=plan_acciones_id_plan_acciones)JOIN alertas_plan_acciones ON 
					detalle_referencias.plan_acciones_id_plan_acciones=alertas_plan_acciones.plan_acciones_id_plan_acciones) WHERE
					detalle_referencias.plan_acciones_id_plan_acciones='$idPA' AND estado='0'";
		
		//Variable para almacenar el mensaje que sera desplegado en la alerta
		$msg="";
				
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Desplegar Ventana si los datos son extraidos exitosamente
		if($datos=mysql_fetch_array($rs)){
		echo "cantidad en alertas referencias".$cantRestante;
			//Determinar el Mensaje que sera desplegado dependiendo del numero de dias restantes
			if($cantRestante==0)
				$msg="La Referencia del Plan de Acciones Correspondiente al Departamento <strong>$datos[area_auditada]</strong> debio realizarse <strong>HOY</strong>"; 
			//Determinar el color de la ventana en base a la cantidad de dias faltantes o restantes
			$nom_form = "";
			if($cantRestante>0){
			$nom_form = "_red";
				$cantRestante = $cantRestante;
				$msg = " Han Pasado <strong>$cantRestante</strong> d&iacute;as  para Cumplir la Fecha Planeada de la Referencia Correspondiente al Departamento<strong> $datos[area_auditada] </strong>"; 
	
		}			
			?>				
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>
									
			<body>						
				<script type="text/javascript" language="javascript">
					setTimeout("popup_show('popupRef', 'popup_dragRef', 'popup_exitRef', 'screen-center', 380, 0);",1000);
				</script>
				<!-- ********************************************************* Popup Window **************************************************** -->
				<div class="sample_popup" id="popupRef" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_dragRef">
						<img class="menu_form_exit" id="popup_exitRef" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
								REFERENCIA PLANEADA A VENCER
					</div>
		
					<div class="menu_form_body<?php echo $nom_form;?>">
					<form name="frm_mostrarAlerta" action="frm_complementarPlanAcciones.php?band=1&idPA=<?php echo $idPA;?>" method="post">				
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
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><strong>&iquest;Verificar Programaci&oacute;n?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="sbt_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Recordatorio Ahora!" 
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
	function notificarAlertaReferencias($num_alertas){?>
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>								
														
		<body>				
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popupRef', 'popup_dragRef', 'popup_exitRef', 'screen-center', 380, 0);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popupRef" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_dragRef">
				<img class="menu_form_exit" id="popup_exitRef" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					REFERENCIAS PLANEADAS A VENCER
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlertaRecordatorio" action="frm_complementarPlanAcciones.php?band=1" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
						<strong><?php echo $num_alertas;?> Referencias</strong> correspondientes a Planes de Acci&oacute;n Est&aacute;n Pr&oacute;ximas a Cumplirse						
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda Consultar la Programaci&oacute;n de las Referencias</u>
						</td>						
					</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Verificar Programaci&oacute;n?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Recordatorios Ahora!" 
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