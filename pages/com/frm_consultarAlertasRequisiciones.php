<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js"></script>	
	<script type="text/javascript" src="includes/ajax/validarEstado.js"></script>
	
    <style type="text/css">
		<!--
		#form-datos-alertas { position:absolute; left:30px; top:190px; width:940px; height:210px; z-index:12; }
		#titulo-alertas { position:absolute; left:30px; top:146px; width:436px; height:19px; z-index:11; }
		-->
    </style>
</head>
<body>
	
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-alertas">Requisiciones que No Han Sido Atendidas</div>

		
	<div id="form-datos-alertas">
	<p align="center" class="titulo_etiqueta">Seleccionar la Requisici&oacute;n de la Cual Ser&aacute; Generado un Pedido</p>
	<form name="frm_datosRequisicionPedido" action="" method="post">
	<table border="0" align="center" cellpadding="5" class="tabla_frm">
		 <tr>
			 <td align="center" class="nombres_columnas">SELECCIONAR</td>
			 <td align="center" class="nombres_columnas">NO. REQUISICI&Oacute;N</td>
			 <td align="center" class="nombres_columnas">&Aacute;REA</td>
			 <td align="center" class="nombres_columnas">FECHA DE LA REQUISICI&Oacute;N</td>
			 <td align="center" class="nombres_columnas">SOLICITANTE</td>
			 <td align="center" class="nombres_columnas">PRIORIDAD</td>
		</tr><?php
		
		$deptos = array("ALM"=>"bd_almacen", "ASE"=>"bd_aseguramiento", "USO"=>"bd_clinica", "DES"=>"bd_desarrollo", "GER"=>"bd_gerencia", "LAB"=>"bd_laboratorio", 
						"MAM"=>"bd_mantenimiento", "MAC"=>"bd_mantenimiento", "PAI"=>"bd_paileria", "PRO"=>"bd_produccion", "REC"=>"bd_recursos", "SEG"=>"bd_seguridad", 
						"TOP"=>"bd_topografia","MAI"=>"bd_comaro");
		
		$nom_clase = "renglon_gris";
		$cont = 1;
		
		foreach($deptos as $depto => $nomBaseDatos){
			
			if($conn = conecta($nomBaseDatos)){
				$rs_req = mysql_query( "SELECT T1.id_requisicion, T1.estado, T1.prioridad, T2.hora, DATEDIFF( CURDATE( ) , T1.fecha_req ) AS dias_dif, TIMEDIFF( CURTIME( ) , T2.hora ) AS horas_dif, area_solicitante, fecha_req, solicitante_req
										FROM requisiciones AS T1
										JOIN bitacora_movimientos AS T2 ON id_operacion = id_requisicion
										WHERE estado =  'ENVIADA'
										AND tipo_operacion =  'GenerarRequisicion'
										AND id_requisicion LIKE  '%$depto%'
										AND autorizada = 1");
				if($datos_req=mysql_fetch_array($rs_req)){
					do{
						if($_POST["requi_prio"] == "baja"){
							if($datos_req["prioridad"] == "BAJA" && $datos_req["dias_dif"] < 3){
								?>
								<tr>
									<td align="center" class="<?php echo $nom_clase; ?>">
										<input type="radio" name="rdb_idRequisicion" id="rdb_idRequisicion" value="<?php echo $datos_req['id_requisicion']; ?>" onclick="verificarEstado(this);" />
									</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['id_requisicion']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['area_solicitante']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_req['fecha_req'],1)." - ".modHora($datos_req['hora']); ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['solicitante_req']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['prioridad']; ?></td>
								</tr>
								<?php
							}
						}
						else if($_POST["requi_prio"] == "media"){
							if( ($datos_req["prioridad"] == "MEDIA" && $datos_req["dias_dif"] < 3) || ($datos_req["prioridad"] == "BAJA" && $datos_req["dias_dif"] > 2 && $datos_req["dias_dif"] < 6) ){
								?>
								<tr>
									<td align="center" class="<?php echo $nom_clase; ?>">
										<input type="radio" name="rdb_idRequisicion" id="rdb_idRequisicion" value="<?php echo $datos_req['id_requisicion']; ?>" onclick="verificarEstado(this);" />
									</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['id_requisicion']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['area_solicitante']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_req['fecha_req'],1)." - ".modHora($datos_req['hora']); ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['solicitante_req']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['prioridad']; ?></td>
								</tr>
								<?php
							}
						}
						else if($_POST["requi_prio"] == "urgente"){
							if($datos_req["prioridad"] == "URGENTE" && $datos_req["dias_dif"] < 1){
								?>
								<tr>
									<td align="center" class="<?php echo $nom_clase; ?>">
										<input type="radio" name="rdb_idRequisicion" id="rdb_idRequisicion" value="<?php echo $datos_req['id_requisicion']; ?>" onclick="verificarEstado(this);" />
									</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['id_requisicion']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['area_solicitante']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_req['fecha_req'],1)." - ".modHora($datos_req['hora']); ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['solicitante_req']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['prioridad']; ?></td>
								</tr>
								<?php
							}
						}
						else if($_POST["requi_prio"] == "pasada"){
							if( ($datos_req["prioridad"] == "URGENTE" && $datos_req["dias_dif"] > 0) || ($datos_req["prioridad"] == "MEDIA" && $datos_req["dias_dif"] > 2) || ($datos_req["prioridad"] == "BAJA" && $datos_req["dias_dif"] > 5) ){
								?>
								<tr>
									<td align="center" class="<?php echo $nom_clase; ?>">
										<input type="radio" name="rdb_idRequisicion" id="rdb_idRequisicion" value="<?php echo $datos_req['id_requisicion']; ?>" onclick="verificarEstado(this);" />
									</td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['id_requisicion']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['area_solicitante']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos_req['fecha_req'],1)." - ".modHora($datos_req['hora']); ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['solicitante_req']; ?></td>
									<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos_req['prioridad']; ?></td>
								</tr>
								<?php
							}
						}
						
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
					
					}while($datos_req=mysql_fetch_array($rs_req));
				}
			}
		}
		
		mysql_close($conn);
		
		?>	
    	<tr>
    		<td colspan="6">
				<input type="hidden" name="hdn_numero" id="hdn_numero" value="" />
        		<input type="hidden" name="hdn_bd" id="hdn_bd" value="" />
        		<input type="hidden" name="hdn_estado" id="hdn_estado" value="" />			
			</td>
		</tr>
		
	</table>
	</form>
	</div>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>