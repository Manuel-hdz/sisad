<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Antonio de Jesus Jimenez Cuevas                            
	  * Fecha: 07/Octubre/2010                                      			
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php
	  **/	 	 

	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertasAlmacen(){
		//Conectarse con la BD de Almacen y mantener la conexion para utilizar las funciones de monitorearMateriales() y mostrarAlertasAlmacen($id_material)
		$conn = conecta("bd_almacen");	
		$sql="SELECT COUNT(*),id_material FROM materiales JOIN alertas ON id_material=materiales_id_material WHERE existencia<=re_orden AND grupo!='PLANTA' AND estado=1";
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			$numAlertas=$datos[0];
			if($numAlertas==1)
				mostrarAlertasAlmacen($datos["id_material"], 1);
			elseif($numAlertas>1)
				notificarAlertaAlmacen($numAlertas,$numAlertas);
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Cierre de la funcion desplegarAlertas();
	
	function notificarAlertaAlmacen($num_alertas,$ctrl){
		if ($ctrl==0){	
		?>
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>
			<body>
		<?php }?>
						
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popup<?php echo $ctrl?>', 'popup_drag<?php echo $ctrl?>', 'popup_exit<?php echo $ctrl?>', 'screen-bottom-left', 0, 0);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup<?php echo $ctrl?>" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag<?php echo $ctrl?>">
				<img class="menu_form_exit" id="popup_exit<?php echo $ctrl?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
				AVISO DE ALMAC&Eacute;N
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_consultarMaterialesReorden.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							<?php
								echo "<p>Un total de <strong> ".$num_alertas."</strong> Art&iacute;culos de Almac&eacute;n han alcanzado o sobrepasado el punto de Reorden</p>";
							?>
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center"><strong><br>&iquest;Ver Art&iacute;culos?</strong></td>
					</tr>							
					<tr>
						<td align="center" colspan="2">
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Materiales en Punto de Reorden" onMouseOver="window.status='';return true" />
						</td>
					</tr>
					</table>
					</form>
			</div>
		</div>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<?php if ($ctrl==0)
		echo "</body>";
	}
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertasAlmacen($id_material, $num){		
		//Crear la sentencia para obtener los datos del material, el cual ha generado una alerta
		$stm_sql = "SELECT nom_material, unidad_medida, existencia, nivel_minimo, re_orden, linea_articulo 
					FROM materiales JOIN unidad_medida ON id_material = materiales_id_material WHERE id_material='$id_material'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){ ?>
			
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>
			<body>
						
			<script type="text/javascript" language="javascript">
				setTimeout("popup_show('popup<?php echo $num?>', 'popup_drag<?php echo $num?>', 'popup_exit<?php echo $num?>', 'screen-bottom-left', 0, 0);",1000);
			</script>
			<!-- ********************************************************* Popup Window **************************************************** -->
			<div class="sample_popup" id="popup<?php echo $num?>" style="display: none;">
				<div align="center" class="menu_form_header" id="popup_drag<?php echo $num?>">
					<img class="menu_form_exit" id="popup_exit<?php echo $num?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					AVISO DE ALMAC&Eacute;N
				</div>
	
				<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_consultarMaterialesReorden.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							El art&iacute;culo <strong>"<?php echo $datos['nom_material'];?>"</strong> rebas&oacute; el Punto de Reorden.
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="155" align="center" colspan="2">Existencia: <strong><?php echo $datos['existencia']." ".$datos['unidad_medida'];?></strong></td>
					</tr>
					<tr>
						<td align="center" colspan="2">Nivel M&iacute;nimo: <strong><?php echo $datos['nivel_minimo'];?></strong></td>
					</tr>
					<tr>
						<td align="center" colspan="2">Punto de Reorden: <strong><?php echo $datos['re_orden'];?></strong></td>
					</tr>
					<tr>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="center"><strong><br>&iquest;Ver Art&iacute;culo?</strong></td>
					</tr>							
					<tr>
						<td align="center" colspan="2">
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Material en Punto de Reorden" onMouseOver="window.status='';return true" />
						</td>
					</tr>
				</table>
				</form>
				</div>
			</div>
			<!-- ********************************************************* Popup Window **************************************************** -->						
			<?php					
		} ?>
		</body>
		<?php				
	}//Cierre de la funcion mostrarAlertasAlmacen($id_material, $num)				
?>