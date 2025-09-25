<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 30/Abril/2011                                      			
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD de Recursos Humanos para indicar al Administrador del Almacen 
	  * cuando hay nuevos Empleados, los cuales deben contar con su equipo de Seguridad, toma la conexion en el archivo conexion.inc incluido en el archivo head_menu.php
	  **/	 	 
	
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertasRH(){
		//Conectarse con la BD de Almacen
		$conn = conecta("bd_almacen");	
			
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Empleados
		$stm_sql = "SELECT rfc_empleado FROM alertas WHERE estado = 1 AND origen='RH'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Sentencia para contar el numero de alertas
		$num_alertas = mysql_num_rows($rs);				
		
		
		//Controlar multiples accesos
		$ctrl = 0;
		
		if($num_alertas>1){//Notificar la Cantidad de Nuevos Empleados al Administrador de Almacen
			//Mostrar solo un mensaje de varios Empleados de Nuevo Ingreso
			notificarAlertaRH($num_alertas);	
			$ctrl = 1;		
		}
		else if($num_alertas==1){//Mostrar la Alerta de un Empleado con sus Datos			
			//Extraccion de los datos del Empleado para mostrar en la Alerta
			$datos = mysql_fetch_array($rs);
			mostrarAlertasRH($datos['rfc_empleado'],$ctrl);			
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Cierre de la funcion desplegarAlertas();
	
	
	/*Esta función notifica al Usuario Cuantos empleados hay de nuevo ingreso*/
	function notificarAlertaRH($num_alertas){?>
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
				AVISO DE RECURSOS HUMANOS
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlertasRH" action="frm_consultarAlertasRH.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center"><?php 
							echo "<p>Se Han Registrado <strong>".$num_alertas."</strong> Nuevos Empleados en Recursos Humanos</p>";?>
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se Recomienda Preparar Equipo de Seguridad</u>
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
	function mostrarAlertasRH($rfcEmpleado, $ctrl){		
		//Conectarse a la BD de Recursos Humanos
		$conn_rh = conecta("bd_recursos");
		
		//Crear la sentencia para obtener los datos del Empleado, el cual ha generado una alerta
		$stm_sql = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre, area, puesto, id_empleados_empresa FROM empleados WHERE rfc_empleado='$rfcEmpleado'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql,$conn_rh);		
		if($datos=mysql_fetch_array($rs)){			
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
					AVISO DE RECURSOS HUMANOS
				</div>
	
				<div class="menu_form_body">
				<form name="frm_mostrarAlertaEmpleado" action="frm_equipoSeguridad.php" method="post">
				<input type="hidden" name="cmb_nombre" id="rfc_empleadoAlerta1" value="<?php echo $rfcEmpleado;?>" />
				<input type="hidden" name="txt_codigo" id="txt_codigo" value="<?php echo $datos['id_empleados_empresa'];?>" />
				<table>
					<tr>
						<td colspan="2" align="center">
							El Empleado <strong>"<?php echo $datos['nombre'];?>"</strong> Ha Sido Registrado en Recursos Humano.							
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="155" align="center" colspan="2">&Aacute;rea: <strong><?php echo $datos['area'];?></strong></td>
					</tr>
					<tr>
						<td align="center" colspan="2">Puesto: <strong><?php echo $datos['puesto'];?></strong></td>
					</tr>					
					<tr>
						<td colspan="2" align="center">&iquest;Registrar Equipo de Seguridad?</td>
					</tr>							
					<tr>
						<td align="center" colspan="2">
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Registar Equipo de Seguridad Ahora!" onMouseOver="window.status='';return true" />
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
	}//Cierre de la funcion mostrarAlertas($rfcEmpleado, $ctrl)
	
	
?>