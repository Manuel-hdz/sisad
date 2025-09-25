<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Antonio de Jesus Jimenez Cuevas                            
	  * Fecha: 07/Octubre/2010                                      			
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php
	  **/	 	 
	 	 	
	
	//Genera la Id de la Alerta que será registrada en la tabla de alertas
	function obtenerIdAlertaMat(){		
		//Definir las tres letras en la Id de la Alerta
		$id_cadena = "ALR";
		
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el año actual para ser agregados en la consulta y asi obtener las entradas del mes y año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de alertas registradas en la BD
		//$stm_sql = "SELECT MAX(id_alerta) AS clave FROM alertas WHERE id_alerta LIKE 'ALR$mes$anio%'";
		$stm_sql = "SELECT MAX( CAST( SUBSTR( id_alerta, 8, 6 ) AS UNSIGNED ) ) AS clave
					FROM alertas
					WHERE id_alerta LIKE  'ALR$mes$anio%'";
		//Ejecutar Alerta		
		$rs = mysql_query($stm_sql);		
		if($datos=mysql_fetch_array($rs)){
			//$cant = intval(substr($datos['clave'],7,3));
			$cant = intval($datos['clave']);
			$cant += 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}		
		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdAlertaMat()
	
	
	/*
	 * Esta función se ecarga de buscar los materiales que han rebasado su punto de reorden y verifica que no exista ninguna alerta registrada en la BD
	 * antes de agregar el registro de la alerta
	 */  
	function monitorearMateriales(){
		//Borrar la tabla de alertas para registrar los nuevos cambios que existan en base a las modificaciones realizadas al Material
		mysql_query("DELETE FROM alertas WHERE estado = 1 AND origen !='RH'");
				
		//Crear la sentencia para consultar los materiales que han rebasado su punto de reorden, Esto sirve para generar Requisiciones exclusivamente
		$stm_sql_req = "SELECT id_material FROM materiales WHERE existencia<=re_orden AND relevancia='STOCK' AND proveedor!='MINERA FRESNILLO, S.A. DE C.V.' AND id_material!='' AND relevancia!='' AND grupo!='PLANTA'";
		//Ejecutar la sentencia creada para las Requisiciones
		$rs_req = mysql_query($stm_sql_req);
		if($datos=mysql_fetch_array($rs_req)){
			do{	
				//Antes de registrar la alerta, verificar si esta registrado el material en una alerta y si lo estan revisar el estado de la misma.				
				if($row=mysql_fetch_array(mysql_query("SELECT * FROM alertas WHERE materiales_id_material = '$datos[id_material]' GROUP BY estado ASC"))){
					//El estado 3 indica que el material tenia una alerta registrada previamente y que este ha recibido la entrada de material correspondiente
					if($row['estado']==3){
						$id_alerta = obtenerIdAlertaMat();
						$fecha = verFecha(3);						
						mysql_query("INSERT INTO alertas (id_alerta, materiales_id_material, estado, fecha_generacion, origen) VALUES('$id_alerta','$datos[id_material]',1,'$fecha','REQ')");					
					}
				}
				else{
					$id_alerta = obtenerIdAlertaMat();
					$fecha = verFecha(3);
					mysql_query("INSERT INTO alertas (id_alerta, materiales_id_material, estado, fecha_generacion, origen) VALUES('$id_alerta','$datos[id_material]',1,'$fecha','REQ')");
				}
			}while($datos=mysql_fetch_array($rs_req));
		}
		
		//Crear la sentencia para consultar los materiales que han rebasado su punto de reorden, Esto sirve para generar Órdenes de Compra exclusivamente
		$stm_sql_oc = "SELECT id_material FROM materiales WHERE existencia<=re_orden AND proveedor='MINERA FRESNILLO, S.A. DE C.V.' AND id_material!='' AND relevancia!=''";
		//Ejecutar la sentencia creada para las Órdenes de Compra
		$rs_oc = mysql_query($stm_sql_oc);
		if($datos=mysql_fetch_array($rs_oc)){
			do{	
				//Antes de registrar la alerta, verificar si esta registrado el material en una alerta y si lo estan revisar el estado de la misma.				
				if($row=mysql_fetch_array(mysql_query("SELECT * FROM alertas WHERE materiales_id_material = '$datos[id_material]' GROUP BY estado ASC"))){
					//El estado 3 indica que el material tenia una alerta registrada previamente y que este ha recibido la entrada de material correspondiente
					if($row['estado']==3){
						$id_alerta = obtenerIdAlertaMat();
						$fecha = verFecha(3);						
						mysql_query("INSERT INTO alertas (id_alerta, materiales_id_material, estado, fecha_generacion, origen) VALUES('$id_alerta','$datos[id_material]',1,'$fecha','OC')");					
					}
				}
				else{
					$id_alerta = obtenerIdAlertaMat();
					$fecha = verFecha(3);
					mysql_query("INSERT INTO alertas (id_alerta, materiales_id_material, estado, fecha_generacion, origen) VALUES('$id_alerta','$datos[id_material]',1,'$fecha','OC')");
				}
			}while($datos=mysql_fetch_array($rs_oc));
		}
		
	}//Fin de la funcion monitorearMateriales()
	
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertas(){
		//Conectarse con la BD de Almacen y mantener la conexion para utilizar las funciones de monitorearMateriales() y mostrarAlertasMat($id_material)
		$conn = conecta("bd_almacen");	
		//Llamar a la función para monitoreo de materiales
		monitorearMateriales();		
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Requisiciones
		$stm_sql = "SELECT materiales_id_material FROM alertas WHERE estado = 1 AND origen='REQ'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Sentencia para contar el numero de alertas
		$num_alertas=mysql_num_rows($rs);
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Órdenes de Compra
		$stm_sql_oc = "SELECT materiales_id_material FROM alertas WHERE estado = 1 AND origen='OC'";
		//Ejecutar la sentencia previamente creada que corresponde a buscar Órdenes de Compra
		$rs_oc = mysql_query($stm_sql_oc);
		//Sentencia para contar el numero de alertas de Órdenes de Compra
		$num_alertas_oc=mysql_num_rows($rs_oc);
		//Controlar multiples accesos
		$ctrl=0;
		//Comprobar el número de Alertas
		if($num_alertas>1){
			//Especificar el Origen de la alerta
			$origen="Requisici&oacute;n";
			//Mostrar solo un mensaje de varios materiales alcanzando su punto de reorden
			notificarAlertaMat($num_alertas,$origen,$ctrl);

			if($num_alertas_oc>1){
				$ctrl=1;
				$origen= "&Oacute;rden de Compra";
				notificarAlertaMat($num_alertas_oc,$origen,$ctrl);
			}
			///////////////LINEAS DE PRUEBA
			else{
				if($datos_oc=mysql_fetch_array($rs_oc)){
					$ctrl=1;
					$origen= "&Oacute;rden de Compra";
					do{
						mostrarAlertasMat($datos_oc['materiales_id_material'],$ctrl,$origen);
					}while($datos_oc=mysql_fetch_array($rs_oc));
				}
			}
			//////////////FIN DE LINEAS DE PRUEBA
		}
		else{
			if($datos=mysql_fetch_array($rs)){
				$num = 1;
				$origen="Requisici&oacute;n";
				do{
					mostrarAlertasMat($datos['materiales_id_material'],$num,$origen);
					$num++;
				}while($datos=mysql_fetch_array($rs));
				
				//Lineas Recientes
				if ($num_alertas_oc==1){
					$num=0;
					$origen= "&Oacute;rden de Compra";
					if($datos_oc=mysql_fetch_array($rs_oc)){
						do{
							mostrarAlertasMat($datos_oc['materiales_id_material'],$num,$origen);
						}while($datos_oc=mysql_fetch_array($rs_oc));
					}
					$ctrl=1;
				}
				else{
					if($num_alertas_oc>1){
						$ctrl=1;
					}
					if($ctrl==1){
						$ctrl=0;
						$origen= "&Oacute;rden de Compra";
						notificarAlertaMat($num_alertas_oc,$origen,$ctrl);
					}		
				}
				//Hasta aqui
			}
			else{
				if($num_alertas_oc>1&&$ctrl==0&&$num_alertas<1){
					//Especificar el Origen de la alerta
					$origen= "&Oacute;rden de Compra";
					if($num_alertas>1){
						$ctrl=1;	
					}
					//Mostrar solo un mensaje de varios materiales alcanzando su punto de reorden
					notificarAlertaMat($num_alertas_oc,$origen,$ctrl);
				}else{
				/////
					if($datos_oc=mysql_fetch_array($rs_oc)){
					$num=1;
					$origen= "&Oacute;rden de Compra";
						do{
							mostrarAlertasMat($datos_oc['materiales_id_material'],$num,$origen);
						}while($datos_oc=mysql_fetch_array($rs_oc));				
					}$ctrl=1;
				}
			}
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Cierre de la funcion desplegarAlertas();
	
	function notificarAlertaMat($num_alertas,$origen,$ctrl){
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
			<?php if ($origen=="Requisici&oacute;n"){?>
				setTimeout("popup_show('popup<?php echo $ctrl?>', 'popup_drag<?php echo $ctrl?>', 'popup_exit<?php echo $ctrl?>', 'screen-top', 0, 0);",1000);
			<?php }else{?>
				setTimeout("popup_show('popup<?php echo $ctrl?>', 'popup_drag<?php echo $ctrl?>', 'popup_exit<?php echo $ctrl?>', 'screen-top-right', 0, 0);",1000);
			<?php }?>
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup<?php echo $ctrl?>" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag<?php echo $ctrl?>">
				<img class="menu_form_exit" id="popup_exit<?php echo $ctrl?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
				<?php if($origen=="Requisici&oacute;n")
							echo "AVISO DE REQUISICI&Oacute;N";
						else
							echo "AVISO DE &Oacute;RDEN DE COMPRA";
				?>
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_consultarAlertas_bak.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							<?php if ($origen=="Requisici&oacute;n"){
								echo "<p>Un total de <strong> ".$num_alertas."</strong> Art&iacute;culos de Almac&eacute;n han alcanzado o sobrepasado el punto de Reorden</p>";
							}else
								echo "<p>Un total de <strong> ".$num_alertas."</strong> Art&iacute;culos de Almac&eacute;n suministrados por Minera Fresnillo han alcanzado o sobrepasado el punto de Reorden</p>";
							?>
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda generar <?php echo $origen;?></u>
						</td>						
					</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Art&iacute;culos?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Generar <?php echo $origen;?> Ahora!" onMouseOver="window.status='';return true" />
								<input type="hidden" name="hdn_org" id="hdn_org" value="<?php echo $origen;?>"/>
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
	function mostrarAlertasMat($id_material, $num, $origen){		
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
				<?php if ($origen=="Requisici&oacute;n"){?>
					setTimeout("popup_show('popup<?php echo $num?>', 'popup_drag<?php echo $num?>', 'popup_exit<?php echo $num?>', 'screen-top', 0, 0);",1000);
				<?php }else{?>
					setTimeout("popup_show('popup<?php echo $num?>', 'popup_drag<?php echo $num?>', 'popup_exit<?php echo $num?>', 'screen-top-right', 0, 0);",1000);
				<?php }?>
			</script>
			<!-- ********************************************************* Popup Window **************************************************** -->
			<div class="sample_popup" id="popup<?php echo $num?>" style="display: none;">
				<div align="center" class="menu_form_header" id="popup_drag<?php echo $num?>">
					<img class="menu_form_exit" id="popup_exit<?php echo $num?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					<?php if($origen=="Requisici&oacute;n")
							echo "AVISO DE REQUISICI&Oacute;N";
						else
							echo "AVISO DE &Oacute;RDEN DE COMPRA";
					?>
				</div>
	
				<div class="menu_form_body">
				<?php if ($origen=="Requisici&oacute;n"){?>
					<form name="frm_mostrarAlerta" action="frm_generarRequisicion.php" method="post">
				<?php }
					else{?>
					<form name="frm_mostrarAlerta" action="frm_generarOC.php" method="post">
				<?php }?>
				<table>
					<tr>
						<td colspan="2" align="center">
							El art&iacute;culo <strong>"<?php echo $datos['nom_material'];?>"</strong> rebas&oacute; el Punto de Reorden.
							<?php 
							if($origen=="Requisici&oacute;n"){
								echo "<input type='hidden' name='id_mat' value='$id_material' />";?>
								<input type="hidden" name="linea" value="<?php echo $datos['linea_articulo'];?>" />
							<?php }
							else {?>	
								<input type="hidden" name="nom_mat" value="<?php echo $datos['nom_material'];?>" />
							<?php }?>
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
						<td colspan="2" align="center">&iquest;Generar <?php echo $origen;?>?</td>
					</tr>							
					<tr>
						<td align="center" colspan="2">
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Generar <?php echo $origen;?> Ahora!" onMouseOver="window.status='';return true" />
							<input type="hidden" name="hdn_org" id="hdn_org" value="<?php echo $origen;?>"/>
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
	}//Cierre de la funcion mostrarAlertasMat($id_material, $num)				
?>