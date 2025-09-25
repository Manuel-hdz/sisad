<?php
	
	function desplegarAlertasKiosco(){
		$conn = conecta("bd_kiosco");	
		
		$stm_sql = "SELECT id_vale_kiosco FROM alertas WHERE estado = 1";
		$rs = mysql_query($stm_sql);
		$num_alertas=mysql_num_rows($rs);
		$ctrl=0;
		
		if($num_alertas>1){
			notificarAlertaKiosco($num_alertas,100);
		} else {
			if($datos=mysql_fetch_array($rs)){
				mostrarAlertasKiosco($datos['id_vale_kiosco'], 100);
			}
		}
		mysql_close($conn);		
	}
	
	function notificarAlertaKiosco($num_alertas,$ctrl){
		?>
		<head>				
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="includes/sample.css" />
			<script type="text/javascript" src="includes/popup-window.js"></script>
		</head>
		<body>
			<script type="text/javascript" language="javascript">
				setTimeout("popup_show('popup<?php echo $ctrl?>', 'popup_drag<?php echo $ctrl?>', 'popup_exit<?php echo $ctrl?>', 'screen-bottom', 0, 0);",1000);
			</script>
			<!-- ********************************************************* Popup Window **************************************************** -->
			<div class="sample_popup" id="popup<?php echo $ctrl?>" style="display: none;">
				<div align="center" class="menu_form_header" id="popup_drag<?php echo $ctrl?>">
					<img class="menu_form_exit" id="popup_exit<?php echo $ctrl?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					<?php
					echo "AVISO DE VALES DE KIOSCO";
					?>
				</div>
				<div class="menu_form_body">
					<form name="frm_mostrarAlerta" action="frm_consultarAlertasKiosco.php" method="post">
						<table>
							<tr>
								<td colspan="2" align="center">
									<?php
									echo "<p>Un total de <strong> ".$num_alertas."</strong> Vales del Kiosco estan pendientes</p>";
									?>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center" bgcolor="#CCFF00">
									<u>Se recomienda generar Vales</u>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="center"><strong><br>&iquest;Ver Vales?</strong></td>
							</tr>
							<tr>
								<td align="center" colspan="2">
									<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Generar Vales" onMouseOver="window.status='';return true" />
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
	
	function mostrarAlertasKiosco($id_vale, $num){
		$stm_sql = "SELECT * FROM vale_kiosco WHERE id_vale_kiosco='$id_vale'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){ ?>
			
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>
			<body>
				<script type="text/javascript" language="javascript">
					setTimeout("popup_show('popup<?php echo $num?>', 'popup_drag<?php echo $num?>', 'popup_exit<?php echo $num?>', 'screen-bottom', 0, 0);",1000);
				</script>
				<!-- ********************************************************* Popup Window **************************************************** -->
				<div class="sample_popup" id="popup<?php echo $num?>" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_drag<?php echo $num?>">
						<img class="menu_form_exit" id="popup_exit<?php echo $num?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
						<?php
						echo "AVISO DE VALES DE KIOSCO";
						?>
					</div>
					<div class="menu_form_body">
						<form name="frm_mostrarAlerta" action="frm_salidaMaterial.php" method="post">
							<table>
								<tr>
									<td colspan="2" align="center">
										El vale <strong>"<?php echo $datos['id_vale_kiosco'];?>"</strong> se encuentra pendiente.
										<input type="hidden" name="id_kiosco" value="<?php echo $datos['id_vale_kiosco']; ?>" />
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">&nbsp;</td>
								</tr>
								<tr>
									<td width="155" align="center" colspan="2">
										No. Empleado: <strong><?php echo $datos['id_empleados_empresa'];?></strong>
									</td>
								</tr>
								<tr>
									<td align="center" colspan="2">
										Emmpleado: <strong><?php echo $datos['nombre_empleado'];?></strong>
									</td>
								</tr>
								<tr>
									<td align="center" colspan="2">
										Fecha de Vale: <strong><?php echo modFecha($datos['fecha'],1);?></strong>
									</td>
								</tr>
								<tr>
									<td colspan="2" align="center">&nbsp;</td>
								</tr>
								<tr>
									<td colspan="2" align="center">
										&iquest;Generar Salida?
									</td>
								</tr>
								<input type="hidden" id="vale_kiosco" name="vale_kiosco" value="<?php echo $datos['id_vale_kiosco'].",".$datos['id_empleados_empresa']; ?>" />
								<input type="hidden" id="id_empl" name="id_empl" value="<?php echo $datos['id_empleados_empresa']; ?>" />
								<input type="hidden" id="es_epp" name="es_epp" value="<?php echo $datos['epp']; ?>" />
								<tr>
									<td align="center" colspan="2">
										<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Generar Salida" onMouseOver="window.status='';return true" />
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
	}
?>