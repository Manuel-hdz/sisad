<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Armando Ayala Alvarado
	  * Fecha: Mayo/2016
	  **/	 	 
	
	function monitorearRequisicionesBaja(){
		
		$deptos = array("ALM"=>"bd_almacen", "ASE"=>"bd_aseguramiento", "USO"=>"bd_clinica", "DES"=>"bd_desarrollo", "GER"=>"bd_gerencia", "LAB"=>"bd_laboratorio", 
						"MAM"=>"bd_mantenimiento", "MAC"=>"bd_mantenimiento", "PAI"=>"bd_paileria", "PRO"=>"bd_produccion", "REC"=>"bd_recursos", "SEG"=>"bd_seguridad", 
						"TOP"=>"bd_topografia","MAI"=>"bd_comaro");
		$no_Req = array();
		
		$req_alm = 0; $req_ase = 0; $req_uso = 0; 
		$req_des = 0; $req_ger = 0; $req_lab = 0; 
		$req_man = 0; $req_mac = 0; $req_pai = 0; 
		$req_pro = 0; $req_rec = 0; $req_seg = 0; 
		$req_top = 0; $total = 0;
		
		foreach($deptos as $depto => $nomBaseDatos){
			
			if($conn = conecta($nomBaseDatos)){
				$stm_sql_req = "SELECT T1.id_requisicion, T1.estado, T1.prioridad, DATEDIFF( CURDATE( ) , T1.fecha_req ) AS dias_dif, TIMEDIFF( CURTIME( ) , T2.hora ) AS horas_dif
								FROM requisiciones AS T1
								JOIN bitacora_movimientos AS T2 ON id_operacion = id_requisicion
								WHERE estado =  'ENVIADA'
								AND tipo_operacion =  'GenerarRequisicion'
								AND id_requisicion LIKE  '%$depto%'
								AND autorizada = 1";
				$rs_req = mysql_query($stm_sql_req);
				if($datos_req=mysql_fetch_array($rs_req)){
					do{
						if($datos_req["prioridad"] == "BAJA" && $datos_req["dias_dif"] < 3){
							switch($depto){
								case "ALM":
									$req_alm++;
								break;
								case "ASE":
									$req_ase++;
								break;
								case "USO":
									$req_uso++;
								break;
								case "DES":
									$req_des++;
								break;
								case "GER":
									$req_ger++;
								break;
								case "LAB":
									$req_lab++;
								break;
								case "MAN":
									$req_man++;
								break;
								case "MAC":
									$req_mac++;
								break;
								case "PAI":
									$req_pai++;
								break;
								case "PRO":
									$req_pro++;
								break;
								case "REC":
									$req_rec++;
								break;
								case "SEG":
									$req_seg++;
								break;
								case "TOP":
									$req_top++;
								break;
							}
							$no_Req = array("ALM"=>$req_alm, "ASE"=>$req_ase, "USO"=>$req_uso, "DES"=>$req_des, "GER"=>$req_ger, "LAB"=>$req_lab, 
											"MAN"=>$req_man, "MAC"=>$req_mac, "PAI"=>$req_pai, "PRO"=>$req_pro, "REC"=>$req_rec, "SEG"=>$req_seg, 
											"TOP"=>$req_top);
							$total++;
						}
					}while($datos_req=mysql_fetch_array($rs_req));
				}
				mysql_close($conn);
			}
		}
		if($total > 0)
			notificarAlertaPrioridad($total,"baja");
	}
	
	function monitorearRequisicionesMedia(){
		
		$deptos = array("ALM"=>"bd_almacen", "ASE"=>"bd_aseguramiento", "USO"=>"bd_clinica", "DES"=>"bd_desarrollo", "GER"=>"bd_gerencia", "LAB"=>"bd_laboratorio", 
						"MAM"=>"bd_mantenimiento", "MAC"=>"bd_mantenimiento", "PAI"=>"bd_paileria", "PRO"=>"bd_produccion", "REC"=>"bd_recursos", "SEG"=>"bd_seguridad", 
						"TOP"=>"bd_topografia","MAI"=>"bd_comaro");
		$no_Req = array();
		
		$req_alm = 0; $req_ase = 0; $req_uso = 0; 
		$req_des = 0; $req_ger = 0; $req_lab = 0; 
		$req_man = 0; $req_mac = 0; $req_pai = 0; 
		$req_pro = 0; $req_rec = 0; $req_seg = 0; 
		$req_top = 0; $total = 0;
		
		foreach($deptos as $depto => $nomBaseDatos){
			
			if($conn = conecta($nomBaseDatos)){
				$stm_sql_req = "SELECT T1.id_requisicion, T1.estado, T1.prioridad, DATEDIFF( CURDATE( ) , T1.fecha_req ) AS dias_dif, TIMEDIFF( CURTIME( ) , T2.hora ) AS horas_dif
								FROM requisiciones AS T1
								JOIN bitacora_movimientos AS T2 ON id_operacion = id_requisicion
								WHERE estado =  'ENVIADA'
								AND tipo_operacion =  'GenerarRequisicion'
								AND id_requisicion LIKE  '%$depto%'
								AND autorizada = 1";
				$rs_req = mysql_query($stm_sql_req);
				if($datos_req=mysql_fetch_array($rs_req)){
					do{
						if( ($datos_req["prioridad"] == "MEDIA" && $datos_req["dias_dif"] < 3) || ($datos_req["prioridad"] == "BAJA" && $datos_req["dias_dif"] > 2 && $datos_req["dias_dif"] < 6) ){
							switch($depto){
								case "ALM":
									$req_alm++;
								break;
								case "ASE":
									$req_ase++;
								break;
								case "USO":
									$req_uso++;
								break;
								case "DES":
									$req_des++;
								break;
								case "GER":
									$req_ger++;
								break;
								case "LAB":
									$req_lab++;
								break;
								case "MAN":
									$req_man++;
								break;
								case "MAC":
									$req_mac++;
								break;
								case "PAI":
									$req_pai++;
								break;
								case "PRO":
									$req_pro++;
								break;
								case "REC":
									$req_rec++;
								break;
								case "SEG":
									$req_seg++;
								break;
								case "TOP":
									$req_top++;
								break;
							}
							$no_Req = array("ALM"=>$req_alm, "ASE"=>$req_ase, "USO"=>$req_uso, "DES"=>$req_des, "GER"=>$req_ger, "LAB"=>$req_lab, 
											"MAN"=>$req_man, "MAC"=>$req_mac, "PAI"=>$req_pai, "PRO"=>$req_pro, "REC"=>$req_rec, "SEG"=>$req_seg, 
											"TOP"=>$req_top);
							$total++;
						}
					}while($datos_req=mysql_fetch_array($rs_req));
				}
				mysql_close($conn);
			}
		}
		if($total > 0)
			notificarAlertaPrioridad($total,"media");
	}
	
	function monitorearRequisicionesUrgente(){
		
		$deptos = array("ALM"=>"bd_almacen", "ASE"=>"bd_aseguramiento", "USO"=>"bd_clinica", "DES"=>"bd_desarrollo", "GER"=>"bd_gerencia", "LAB"=>"bd_laboratorio", 
						"MAM"=>"bd_mantenimiento", "MAC"=>"bd_mantenimiento", "PAI"=>"bd_paileria", "PRO"=>"bd_produccion", "REC"=>"bd_recursos", "SEG"=>"bd_seguridad", 
						"TOP"=>"bd_topografia","MAI"=>"bd_comaro");
		$no_Req = array();
		
		$req_alm = 0; $req_ase = 0; $req_uso = 0; 
		$req_des = 0; $req_ger = 0; $req_lab = 0; 
		$req_man = 0; $req_mac = 0; $req_pai = 0; 
		$req_pro = 0; $req_rec = 0; $req_seg = 0; 
		$req_top = 0; $total = 0;
		
		foreach($deptos as $depto => $nomBaseDatos){
			
			if($conn = conecta($nomBaseDatos)){
				$stm_sql_req = "SELECT T1.id_requisicion, T1.estado, T1.prioridad, DATEDIFF( CURDATE( ) , T1.fecha_req ) AS dias_dif, TIMEDIFF( CURTIME( ) , T2.hora ) AS horas_dif
								FROM requisiciones AS T1
								JOIN bitacora_movimientos AS T2 ON id_operacion = id_requisicion
								WHERE estado =  'ENVIADA'
								AND tipo_operacion =  'GenerarRequisicion'
								AND id_requisicion LIKE  '%$depto%'
								AND autorizada = 1";
				$rs_req = mysql_query($stm_sql_req);
				if($datos_req=mysql_fetch_array($rs_req)){
					do{
						if($datos_req["prioridad"] == "URGENTE" && $datos_req["dias_dif"] < 1){
							switch($depto){
								case "ALM":
									$req_alm++;
								break;
								case "ASE":
									$req_ase++;
								break;
								case "USO":
									$req_uso++;
								break;
								case "DES":
									$req_des++;
								break;
								case "GER":
									$req_ger++;
								break;
								case "LAB":
									$req_lab++;
								break;
								case "MAN":
									$req_man++;
								break;
								case "MAC":
									$req_mac++;
								break;
								case "PAI":
									$req_pai++;
								break;
								case "PRO":
									$req_pro++;
								break;
								case "REC":
									$req_rec++;
								break;
								case "SEG":
									$req_seg++;
								break;
								case "TOP":
									$req_top++;
								break;
							}
							$no_Req = array("ALM"=>$req_alm, "ASE"=>$req_ase, "USO"=>$req_uso, "DES"=>$req_des, "GER"=>$req_ger, "LAB"=>$req_lab, 
											"MAN"=>$req_man, "MAC"=>$req_mac, "PAI"=>$req_pai, "PRO"=>$req_pro, "REC"=>$req_rec, "SEG"=>$req_seg, 
											"TOP"=>$req_top);
							$total++;
						}
					}while($datos_req=mysql_fetch_array($rs_req));
				}
				mysql_close($conn);
			}
		}
		if($total > 0)
			notificarAlertaPrioridad($total,"urgente");
	}
	
	function monitorearRequisicionesPasadas(){
		
		$deptos = array("ALM"=>"bd_almacen", "ASE"=>"bd_aseguramiento", "USO"=>"bd_clinica", "DES"=>"bd_desarrollo", "GER"=>"bd_gerencia", "LAB"=>"bd_laboratorio", 
						"MAM"=>"bd_mantenimiento", "MAC"=>"bd_mantenimiento", "PAI"=>"bd_paileria", "PRO"=>"bd_produccion", "REC"=>"bd_recursos", "SEG"=>"bd_seguridad", 
						"TOP"=>"bd_topografia","MAI"=>"bd_comaro");
		$no_Req = array();
		
		$req_alm = 0; $req_ase = 0; $req_uso = 0; 
		$req_des = 0; $req_ger = 0; $req_lab = 0; 
		$req_man = 0; $req_mac = 0; $req_pai = 0; 
		$req_pro = 0; $req_rec = 0; $req_seg = 0; 
		$req_top = 0; $total = 0;
		
		foreach($deptos as $depto => $nomBaseDatos){
			
			if($conn = conecta($nomBaseDatos)){
				$stm_sql_req = "SELECT T1.id_requisicion, T1.estado, T1.prioridad, DATEDIFF( CURDATE( ) , T1.fecha_req ) AS dias_dif, TIMEDIFF( CURTIME( ) , T2.hora ) AS horas_dif
								FROM requisiciones AS T1
								JOIN bitacora_movimientos AS T2 ON id_operacion = id_requisicion
								WHERE estado =  'ENVIADA'
								AND tipo_operacion =  'GenerarRequisicion'
								AND id_requisicion LIKE  '%$depto%'
								AND autorizada = 1";
				$rs_req = mysql_query($stm_sql_req);
				if($datos_req=mysql_fetch_array($rs_req)){
					do{
						if( ($datos_req["prioridad"] == "URGENTE" && $datos_req["dias_dif"] > 0) || ($datos_req["prioridad"] == "MEDIA" && $datos_req["dias_dif"] > 2) || ($datos_req["prioridad"] == "BAJA" && $datos_req["dias_dif"] > 5) ){
							switch($depto){
								case "ALM":
									$req_alm++;
								break;
								case "ASE":
									$req_ase++;
								break;
								case "USO":
									$req_uso++;
								break;
								case "DES":
									$req_des++;
								break;
								case "GER":
									$req_ger++;
								break;
								case "LAB":
									$req_lab++;
								break;
								case "MAN":
									$req_man++;
								break;
								case "MAC":
									$req_mac++;
								break;
								case "PAI":
									$req_pai++;
								break;
								case "PRO":
									$req_pro++;
								break;
								case "REC":
									$req_rec++;
								break;
								case "SEG":
									$req_seg++;
								break;
								case "TOP":
									$req_top++;
								break;
							}
							$no_Req = array("ALM"=>$req_alm, "ASE"=>$req_ase, "USO"=>$req_uso, "DES"=>$req_des, "GER"=>$req_ger, "LAB"=>$req_lab, 
											"MAN"=>$req_man, "MAC"=>$req_mac, "PAI"=>$req_pai, "PRO"=>$req_pro, "REC"=>$req_rec, "SEG"=>$req_seg, 
											"TOP"=>$req_top);
							$total++;
						}
					}while($datos_req=mysql_fetch_array($rs_req));
				}
				mysql_close($conn);
			}
		}
		if($total > 0)
			notificarAlertaPrioridad($total,"pasada");
	}
	
	function desplegarAlertasPrioridad(){
		monitorearRequisicionesBaja();
		monitorearRequisicionesMedia();
		monitorearRequisicionesUrgente();
		monitorearRequisicionesPasadas();
	}
	
	function notificarAlertaPrioridad($num_alertas,$priodad){?>
		<head>				
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="includes/sample.css" />
			<script type="text/javascript" src="includes/popup-window.js"></script>
		</head>
		<body>
		
		<?php
		if($priodad == "baja"){
		?>
			<script type="text/javascript" language="javascript">			
				setTimeout("popup_show('popup<?php echo $priodad; ?>', 'popup_drag<?php echo $priodad; ?>', 'popup_exit<?php echo $priodad; ?>', 'screen-center-left', 0, 0);",1000);			
			</script>
		<?php
			$fondo = "includes/aviso-form-baja.png";
		}
		?>
		
		<?php
		if($priodad == "media"){
		?>
			<script type="text/javascript" language="javascript">			
				setTimeout("popup_show('popup<?php echo $priodad; ?>', 'popup_drag<?php echo $priodad; ?>', 'popup_exit<?php echo $priodad; ?>', 'screen-center-right', 0, 0);",1000);			
			</script>
		<?php
			$fondo = "includes/aviso-form-media.png";
		}
		?>
		
		<?php
		if($priodad == "urgente"){
		?>
			<script type="text/javascript" language="javascript">			
				setTimeout("popup_show('popup<?php echo $priodad; ?>', 'popup_drag<?php echo $priodad; ?>', 'popup_exit<?php echo $priodad; ?>', 'screen-bottom-center', 0, 0);",1000);			
			</script>
		<?php
			$fondo = "includes/aviso-form-urgente.png";
		}
		?>
		
		<?php
		if($priodad == "pasada"){
		?>
			<script type="text/javascript" language="javascript">			
				setTimeout("popup_show('popup<?php echo $priodad; ?>', 'popup_drag<?php echo $priodad; ?>', 'popup_exit<?php echo $priodad; ?>', 'screen-center', 0, 0);",1000);			
			</script>
		<?php
			$fondo = "includes/alert.gif";
		}
		?>
		
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup<?php echo $priodad; ?>" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag<?php echo $priodad; ?>">
				<img class="menu_form_exit" id="popup_exit<?php echo $priodad; ?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
				AVISO DE REQUISICI&Oacute;N
			</div>

			<div class="menu_form_body" style="background: url('<?php echo $fondo; ?>') no-repeat bottom;">
				<form name="frm_mostrarAlerta<?php echo $priodad; ?>" action="frm_consultarAlertasRequisiciones.php" method="post">
					<input type="hidden" id="requi_prio" name="requi_prio" value="<?php echo $priodad; ?>" />
					<font color="white">
					<table>
						<tr>
							<?php
							if($priodad == "pasada"){
							?>
								<td colspan="2" align="center">
									<p><strong>Un total de <?php echo $num_alertas; ?> Requisiciones Han Sobrepasado el Tiempo Limite</strong></p>
								</td>
							<?php
							} else {
							?>
								<td colspan="2" align="center">							
									<p><strong>Un total de <?php echo $num_alertas; ?> Requisiciones de Prioridad <?php echo strtoupper($priodad); ?> no Han Sido Atendidas</strong></p>				
								</td>
							<?php
							}
							?>
						</tr>
						<tr>
							<td colspan="2" align="center" bgcolor=""><u>Se Recomienda Revisar la Secci&oacute;n de Requisiciones</u>
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Requisiciones?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Mostrar Requisiciones Sin Atender" onMouseOver="window.status='';return true" />								
							</td>
						</tr>
					</table>
					</font>
				</form>
			</div>
		</div>
		<!-- ********************************************************* Popup Window **************************************************** -->
		</body><?php
	}//Cierre de la Funcion notificarAlerta($num_alertas)
	
	
	/*//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertas($idRequisicion){
		
		//Arreglo que contendra los Departamentos como clave y el nombre de la BD correspondiente a cada uno como valor, Falta agregar los Deptos que faltan por desarrollar
		$deptos = array("ALM"=>"bd_almacen", "MAN"=>"bd_mantenimiento", "MAC"=>"bd_mantenimiento", "MAM"=>"bd_mantenimiento", "REC"=>"bd_recursos", "TOP"=>"bd_topografia",
						"LAB"=>"bd_laboratorio", "PRO"=>"bd_produccion", "GER"=>"bd_gerencia", "DES"=>"bd_desarrollo", "SEG"=>"bd_seguridad", "ASE"=>"bd_aseguramiento",
						"PAI"=>"bd_paileria","MAE"=>"bd_mantenimientoE","USO"=>"bd_clinica");			
		
		//Identificar el DEPTO al que pertence la Requisición para obtener los datos que serán desplegados al usuario
		$depto = substr($idRequisicion,0,3);
		$nomBaseDatos = $deptos[$depto];
		
		//Reconectar a la BD de la cual serán extraidos los datos de la Requisición
		$conn = conecta($nomBaseDatos);		
						
		//Crear la sentencia para obtener los datos de la Requisicion que genero una alerta
		$stm_sql = "SELECT area_solicitante, fecha_req, solicitante_req, estado FROM requisiciones WHERE id_requisicion = '$idRequisicion'";
		
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
				setTimeout("popup_show('popup', 'popup_drag', 'popup_exit', 'screen-bottom-right', 0, 0);",1000);				
			</script>
			
			<!-- ********************************************************* Popup Window **************************************************** -->
			<div class="sample_popup" id="popup" style="display: none;">
				<div align="center" class="menu_form_header" id="popup_drag">
					<img class="menu_form_exit" id="popup_exit" src="includes/aviso-form-exit.png" alt="" title="Posponer" />					
					AVISO DE REQUISICI&Oacute;N						
				</div>
	
				<div class="menu_form_body">								
				<form name="frm_mostrarAlerta" action="frm_detallesDelPedido.php?depto=<?php echo $nomBaseDatos; ?>" method="post">				
				<table>
					<tr>
						<td colspan="2" align="center">
							Se ha Detectado una Nueva Requisici&oacute;n
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="155" align="center" colspan="2">No. Requisici&oacute;n: <strong><?php echo $idRequisicion; ?></strong></td>
					</tr>
					<tr>
						<td align="center" colspan="2">&Aacute;rea: <strong><?php echo $datos['area_solicitante'];?></strong></td>
					</tr>
					<tr>
						<td align="center" colspan="2">Fecha de la Requisici&oacute;n <strong><?php echo modFecha($datos['fecha_req'],1);?></strong></td>
					</tr>
					<tr>
						<td align="center" colspan="2">Solicit&oacute;: <strong><?php echo $datos['solicitante_req'];?></strong></td>
					</tr>
					<tr>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2" align="center">&iquest;Generar Pedido?</td>
					</tr>							
					<tr>
						<td align="center" colspan="2">
							<?php //Estas variales ayudaran a mostrar el formulario donde solo se capturan los precios de los articulos de la Requisicion ?>
							<input type="hidden" name="hdn_numero" id="hdn_numero" value="<?php echo $idRequisicion; ?>" />
							<input type="hidden" name="hdn_bd" id="hdn_bd" value="<?php echo $nomBaseDatos; ?>" />
							<input type="hidden" name="hdn_estado" id="hdn_estado" value="<?php echo $datos['estado']; ?>" />
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Generar Pedido Ahora!" onMouseOver="window.status='';return true" />						
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
		
				
	}//Cierre de la funcion mostrarAlertas($idRequisicion)*/
	
	function horaDecimal($hora){
		$dec = substr($hora,0,2) + (substr($hora,3,2) / 60);
		return $dec;
	}
?>