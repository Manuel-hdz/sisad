<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 15/Diciembre/2011
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de pruebas a realizar.
	  **/	 	 
	
	/* Esta función se ecarga de buscar las pruebas que tienee que ser programadas*/  
	function monitorearRecordatoriosExternos(){
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		//Obtenemos el id del Departameno
		$idDepto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "usuario", $user);
		//Realizar la conexion a la BD de Aseguramiento
		$conn = conecta("bd_aseguramiento");
	    /******************************************************************************
		* DETERMINAR QUE RECORDATORI]OS ESTAN PROXIMAS A SER MOSTRADOS                *
	    ******************************************************************************/
		$fechaBusq=date("d/m/Y");
	 	$stm_sql = "SELECT id_alerta, descripcion, fecha_programada FROM (alertas_generales JOIN  detalle_alertas_generales ON 		
						id_alerta=alertas_generales_id_alerta) WHERE estado='1' AND tipo_alerta='EXTERNA' AND 
						catalogo_departamentos_id_departamento='$idDepto'";		 
		 //Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			do{
				//Almacenamos el id del recordatorio
				$idRecordatorio=$datos['id_alerta'];
				//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
				$fechaBdMod=modFecha($datos["fecha_programada"],1);
				$seccFechaBD = split("/",$fechaBdMod);
				$seccFechaBusq = split("/",$fechaBusq);
				//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año)
				$fechaIni_enDias = gregoriantojd ($seccFechaBD[1], $seccFechaBD[0], $seccFechaBD[2]);
				$fechaFin_enDias = gregoriantojd ($seccFechaBusq[1], $seccFechaBusq[0], $seccFechaBusq[2]);
				$diferencia = ($fechaIni_enDias-$fechaFin_enDias);	
				//Realizamos una actualización para ingresar el numero de dias restantes
				$stm_update = "UPDATE alertas_generales SET dias_restantes = '$diferencia' WHERE id_alerta = '$idRecordatorio'";
				//Ejecutamos la consulta previamente creada
				$rs_upd = mysql_query($stm_update);						
			}while($datos=mysql_fetch_array($rs));
		}	 
	}//Fin de la funcion monitorearRecordatoriosExternos()
	
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertasRecordatorioExterno(){
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		//Obtenemos el id del Departameno
		$idDepto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "usuario", $user);
		//Conectarse con la BD de Aseguramiento y mantener la conexion para utilizar las funciones de monitorearRecordatoriosInternos() y 
		//las funciones para desplegar las alertas
		$conn = conecta("bd_aseguramiento");	
		
		//Llamar a la función para monitoreo de las mezclas que estan proximas a ser probadas
		monitorearRecordatoriosExternos();									
		
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a prueba 
		$stm_sql = "SELECT id_alerta, fecha_generacion, fecha_programada  FROM (alertas_generales JOIN detalle_alertas_generales ON
					id_alerta=alertas_generales_id_alerta) WHERE estado = '1' AND tipo_alerta='EXTERNA' AND catalogo_departamentos_id_departamento='$idDepto'
					AND dias_restantes<=5";		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Sentencia para contar el numero de alertas
		$num_alertas=mysql_num_rows($rs);
		
		/**********************************************************************************
		 * DESPLEGAR ALERTAS PARA LAS PRUEBAS QUE SE ENCUENTRAN PRÓXIMAS A SER REALIZADAS *
		 **********************************************************************************/			
					
		//Si la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas==1){		
			//Extraer el Id del plan de prueba del Result Set
			$datos = mysql_fetch_array($rs);
			//Obtener la Cantidad de dias restantes o exedidos para realizar el recordatorio    
			$datos_cantRestante = mysql_fetch_array(mysql_query("SELECT dias_restantes FROM alertas_generales WHERE id_alerta = '$datos[id_alerta]'"));
			$cantRestante = $datos_cantRestante['dias_restantes'];
			//Deplegar Ventana de Alerta de una sola alerta
			mostrarAlertasCalidad($cantRestante,$datos['id_alerta']);
		}
		
		//Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de las mezclas que 
		//estan proximas para realizar pruebas
		else if($num_alertas>1){								
			//Mostrar solo un mensaje de varios prueba estan a punto de realizar las pruebas
			notificarAlertaCalidad($num_alertas);
		}												
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);								
	}//Cierre de la funcion desplegarAlertas();
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertasCalidad($cantRestante,$idAlerta){	
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		
		//Obtenemos el id del Departameno
		$idDepto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "usuario", $user);
			
		//Conectarse con la BD de Aseguramiento y mantener la conexion para utilizar las funciones de monitorearRecordatoriosInternos() y 
		//las funciones para desplegar las alertas
		$conn = conecta("bd_aseguramiento");	
		
		//Crear la sentencia para obtener los datos del Equipo, el cual ha generado una alerta
		$stm_sql = "SELECT id_alerta, descripcion, fecha_programada FROM (alertas_generales JOIN detalle_alertas_generales ON id_alerta=alertas_generales_id_alerta)
		 			WHERE id_alerta='$idAlerta' AND tipo_alerta='EXTERNA' AND catalogo_departamentos_id_departamento='$idDepto'";
		
		//Variable para almacenar el mensaje que sera desplegado en la alerta
		$msg="";
					
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Desplegar Ventana si los datos son extraidos exitosamente
		if($datos=mysql_fetch_array($rs)){
			//Determinar el Mensaje que sera desplegado dependiendo del numero de dias restantes
			if($cantRestante==0)
				$msg="El Aviso<strong> $datos[descripcion]</strong> debe ser realizado <strong>HOY</strong>"; 
			if($cantRestante>0)
				$msg="El Aviso<strong> $datos[descripcion] </strong> esta a <strong>$cantRestante </strong>d&iacute;as de Vencer";
			//Determinar el color de la ventana en base a la cantidad de dias faltantes o restantes
			$nom_form = "";
			if($cantRestante<0){
				$cantRestante = $cantRestante * -1;
				$msg = " Han Pasado <strong>$cantRestante</strong> d&iacute;as  para Cumplir Aviso <strong> $datos[descripcion] </strong>"; 
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
					setTimeout("popup_show('popupASE', 'popup_dragASE', 'popup_exitASE', 'element', 50, 230);",1000);
				</script>
				<!-- ********************************************************* Popup Window **************************************************** -->
				<div class="sample_popup" id="popupASE" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_dragASE">
						<img class="menu_form_exit" id="popup_exitASE" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
								AVISO ACTIVIDAD ASE CALIDAD
					</div>
		
					<div class="menu_form_body<?php echo $nom_form;?>">
					<form name="frm_mostrarAlerta" action="frm_consultarRecordatorioExterno.php?idAlerta=<?php echo $idAlerta;?>" method="post">				
					<table>
						<tr>
							<td colspan="2" align="center">
								<?php echo $msg;?>
								<input type="hidden" name="hdn_idPlan" value="<?php echo $datos['id_alerta'];?>" />							
								<input type="hidden" name="hdn_nombre" value="<?php echo $datos['descripcion'];?>" />								
								<input type="hidden" name="hdn_fechaProgramada" value="<?php echo $datos['fecha_programada'];?>" />																						
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
							<td colspan="2" align="center"><strong>&iquest;Ver Detalle?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="sbt_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Avisos Ahora!" 
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
	function notificarAlertaCalidad($num_alertas){?>
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>								
														
		<body>				
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popupASE', 'popup_dragASE', 'popup_exitASE', 'element', 50, 230);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popupASE" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_dragASE">
				<img class="menu_form_exit" id="popup_exitASE" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					ALERTA ACTIVIDAD ASE CALIDAD
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlertaRecordatorio" action="frm_consultarRecordatorioExterno.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							Un Total de <strong><?php echo $num_alertas; ?></strong> <strong>Avisos</strong> Est&aacute;n Pr&oacute;ximos a Vencer						
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda Consultar la Programaci&oacute;n de los Avisos</u>
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
