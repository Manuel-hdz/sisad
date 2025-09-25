<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador:Nadia Madahi López Hernandez
	  * Fecha: 16/Octubre/2012
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de los Historiales Clinicos a realizar
	  **/	 	 
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertasCompras(){
		//Conectarse con la BD de USO
		$conn = conecta("bd_compras");	
			
		$stm_sql = "SELECT caja_chica_id_caja_chica, fecha, responsable, descripcion, cant_entregada, estado, departamento FROM detalle_caja_chica WHERE  
		departamento = 'COMPRAS' AND estado='0'";		
						
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Sentencia para contar el numero de alertas
		$num_alertas = mysql_num_rows($rs);				
		
		//Controlar multiples accesos
		$ctrl = 0;
		if($num_alertas>1){
			//Mostrar solo un mensaje de varios Movimiento de los cuales se le notifican al encargado
			notificarAlertaCompras($num_alertas);	
			$ctrl = 1;		
		}
		else if($num_alertas==1){//Mostrar la Alerta de un Empleado con sus Datos			
			//Extraccion de los datos del Empleado para mostrar en la Alerta
			$datos = mysql_fetch_array($rs);
			mostrarAlertasCompras($datos['departamento'],$ctrl);			
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Cierre de la funcion desplegarAlertas();
	
	
	/*Esta función notifica al Usuario Cuantos empleados hay de nuevo ingreso*/
	function notificarAlertaCompras($num_alertas){?>
		<head>				
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="includes/sample.css" />
			<script type="text/javascript" src="includes/popup-window.js"></script>
		</head>
		<body>		
						
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popup_rh0', 'popup_drag_rh0', 'popup_exit_rh0', 'screen-bottom-right', 0, -25);",1000);			
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup_rh0" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag_rh0">
				<img class="menu_form_exit" id="popup_exit_rh0" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
				AVISO COMPRAS
			</div>

			<div class="menu_form_body">
				<form name="frm_consultarAlertasCompras" action="frm_consultarAlertasCompras.php" method="post">
				<table>		
					<tr>
						<td colspan="2" align="center"><?php 
							echo "<p>Existen <strong>".$num_alertas."</strong> Deudas con el Departamento de Compras en la Oficina General</p>";?>
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se Recomienda Consultar el Detalle</u>
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center"><strong><br>&iquest;Ver Registros?</strong></td>
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
	function mostrarAlertasCompras($ctrl){		
		//Conectarse a la BD de 
		$conn = conecta("bd_compras");
		
		$stm_sql = "SELECT caja_chica_id_caja_chica, fecha, responsable, descripcion, cant_entregada, estado, departamento FROM detalle_caja_chica 
		WHERE estado = '0' AND departamento = 'COMPRAS'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql,$conn);		
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
				setTimeout("popup_show('popup_rh<?php echo $ctrl?>', 'popup_drag_rh<?php echo $ctrl?>', 'popup_exit_rh<?php echo $ctrl?>', 'screen-bottom-right', 0, -25);",1000);				
			</script>
			<!-- ********************************************************* Popup Window **************************************************** -->
			<div class="sample_popup" id="popup_rh<?php echo $ctrl?>" style="display: none;">
				<div align="center" class="menu_form_header" id="popup_drag_rh<?php echo $ctrl?>">
					<img class="menu_form_exit" id="popup_exit_rh<?php echo $ctrl?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					AVISO COMPRAS
				</div>
	
				<div class="menu_form_body">
				<form name="frm_consultarAlertasCompras" action="frm_consultarAlertasCompras.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							Existe una Deuda con el Departamento de Compras en la Oficina General 

						</td>						
					</tr>
					<tr>
						<td>&nbsp;</td>

					</tr>	
					<tr>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="155" align="center" colspan="2">Se Recomienda Consultar el Detalle</strong></td>

					</tr>
					<tr>
						<td>&nbsp;</td>

					</tr>										
					<tr>
						<td align="center" colspan="2">
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Revisar Datos Ahora!" 
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
	}//Cierre de la funcion mostrarAlertas($idMov, $ctrl)
	
	
?>