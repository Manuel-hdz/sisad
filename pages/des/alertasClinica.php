<?php
	/**
	  * Nombre del Módulo: UYnidad de Salud Ocupacional                                               
	  * Nombre Programador:Nadia Madahi López Hernandez
	  * Fecha: 27/Septiembre/2012
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de los Historiales Clinicos a realizar
	  **/	 	 
	

	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertasHC(){
		//Conectarse con la BD de USO
		$conn = conecta("bd_clinica");	
			
		$stm_sql = "SELECT DISTINCT catalogo_departamentos_id_departamento, estado, fecha_programada, nom_empleado, id_empleados_empresa 
		FROM alerta_examen WHERE catalogo_departamentos_id_departamento = '3' AND estado = '1' ";		
					
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Sentencia para contar el numero de alertas
		$num_alertas = mysql_num_rows($rs);				
		
		//Controlar multiples accesos
		$ctrl = 0;
		
		if($num_alertas>1){//Notificar la Cantidad de Nuevos Empleados al Administrador del departamento correspondiente
			//Mostrar solo un mensaje de varios Trabajadores a los cuales se les tiene que realizar un nuevo examen
			notificarAlertaClinica($num_alertas);	
			$ctrl = 1;		
		}
		else if($num_alertas==1){//Mostrar la Alerta de un Empleado con sus Datos			
			//Extraccion de los datos del Empleado para mostrar en la Alerta
			$datos = mysql_fetch_array($rs);
			mostrarAlertasClinica($datos['catalogo_departamentos_id_departamento'],$ctrl);			
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Cierre de la funcion desplegarAlertas();
	
	
	/*Esta función notifica al Usuario Cuantos empleados hay de nuevo ingreso*/
	function notificarAlertaClinica($num_alertas){?>
		<head>				
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="includes/sample.css" />
			<script type="text/javascript" src="includes/popup-window.js"></script>
		</head>
		<body>		
						
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popup_rh0', 'popup_drag_rh0', 'popup_exit_rh0', 'screen-top-left', 0, 0);",1000);			
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup_rh0" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag_rh0">
				<img class="menu_form_exit" id="popup_exit_rh0" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
				AVISO DE LA CLINICA
			</div>

			<div class="menu_form_body">
				<form name="frm_consultarAlertasClinica" action="frm_consultarAlertasClinica.php" method="post">
				<!--<form name="frm_mostrarAlertasClinica" action="frm_consultarAlertasRH.php" method="post">-->

				<table>
					<tr>
						<td colspan="2" align="center"><?php 
							echo "<p>Se Tienen <strong>".$num_alertas."</strong> Ex&aacute;menes M&eacute;dicos Programados dentro de la Unidad de Salud Ocupacional</p>";?>
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se Recomienda que los Trabajadores se Presenten en la Clinica</u>
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center"><strong><br>&iquest;Ver Empleados?</strong></td>
					</tr>							
					<tr>
						<td align="center" colspan="2">
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Revisar Datos Ahora!" onMouseOver="window.status='';return true" />
						</td>
					</tr>
				</table>
				</form>
			</div>
		</div>
		<!-- ********************************************************* Popup Window **************************************************** --><?php
	}
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertasClinica($idNumEmpleado, $ctrl){		
		//Conectarse a la BD de Recursos Humanos
		$conn = conecta("bd_clinica");
		
		//Crear la sentencia para obtener los datos del Empleado, el cual ha generado una alerta de acuerdo al historial programado
		$stm_sql = "SELECT catalogo_departamentos_id_departamento, puesto_realizar, fecha_programada, alerta_examen.nom_empleado, alerta_examen.id_empleados_empresa 
		FROM alerta_examen JOIN historial_clinico ON id_historial = historial_clinico_id_historial WHERE catalogo_departamentos_id_departamento = '$idNumEmpleado' 
		AND estado = '1'";
		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql,$conn);		
		if($datos=mysql_fetch_array($rs)){			
			$idNumEmpleado = $datos['catalogo_departamentos_id_departamento'];
			$idDepto = obtenerDato('bd_recursos', 'empleados', 'id_depto', 'id_empleados_empresa', $idNumEmpleado);
			//Si ctrl vale 0, significa que no ha sido desplegada ninguna alerta, por lo tanto hay que agregar el encabezado y cuerpo para mostrar las alertas
			if($ctrl==0){ ?>			
				<head>				
					<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
					<link rel="stylesheet" type="text/css" href="includes/sample.css" />
					<script type="text/javascript" src="includes/popup-window.js"></script>
				</head>
				<body>
			<?php } ?>
						
			<script type="text/javascript" language="javascript">			
				setTimeout("popup_show('popup_rh<?php echo $ctrl?>', 'popup_drag_rh<?php echo $ctrl?>', 'popup_exit_rh<?php echo $ctrl?>', 'screen-top-left', 0, 0);",1000);				
			</script>
			<!-- ********************************************************* Popup Window **************************************************** -->
			<div class="sample_popup" id="popup_rh<?php echo $ctrl?>" style="display: none;">
				<div align="center" class="menu_form_header" id="popup_drag_rh<?php echo $ctrl?>">
					<img class="menu_form_exit" id="popup_exit_rh<?php echo $ctrl?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					AVISO DE LA CLINICA
				</div>
	
				<div class="menu_form_body">
				<form name="frm_consultarAlertasClinica" action="frm_consultarAlertasClinica.php" method="post">
				<input type="hidden" name="cmb_nombre" id="rfc_empleadoAlerta1" value="<?php echo $idNumEmpleado;?>" />
				<table>
					<tr>
						<td colspan="2" align="center">
							El Trabajador <strong>"<?php echo $datos['nom_empleado'];?>"</strong> Debe de Presentarse en la Clinica para su Examen Medico.							
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="155" align="center" colspan="2">Puesto: <strong><?php echo $datos['puesto_realizar'];?></strong></td>

					</tr>
					<tr>
						<td width="155" align="center" colspan="2">Fecha Programada: <strong><?php echo modFecha($datos['fecha_programada'],1);?></strong></td>

					</tr>											
					<tr>
						<td align="center" colspan="2">
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Verificar Informaci&oacute;n General" 
							onMouseOver="window.status='';return true" />
						</td>
					</tr>
				</table>
				</form>
				</div>
			</div>
			<!-- ********************************************************* Popup Window **************************************************** --><?php
			if($ctrl==0)					
				echo "</body>";
		}//Cierre if($datos=mysql_fetch_array($rs))		
	}//Cierre de la funcion mostrarAlertas($idNumEmpleado, $ctrl)
	
	
?>