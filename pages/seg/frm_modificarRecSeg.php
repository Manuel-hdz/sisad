<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad Industrial
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Manejo de la funciones para modificar el registro de los recorridos de seguridad
		include ("op_modificarRecSeg.php");
		
		//Verificamos que existan las sesiones de ser asi darlas de baja
		if(isset($_SESSION['recorridosSeg'])){
			unset($_SESSION['recorridosSeg']);
		}
		if(isset($_SESSION['banderas'])){
			unset($_SESSION['banderas']);
		}
		//Verificamos que exista la sesion registro forografico de ser asi 
		if(isset($_SESSION['registroFotografico'])){
			//Comprobamos si es que el usuario presiono el boton cancelar
			if(isset($_GET['cancel'])){
				//De ser asi eliminar el registro fotografico existenten $_GET[cancel]=es igual al id del recorrido de seguridad en el cual se estaba trabajando
				borrarFotos($_GET['cancel']);
			}
			//Instruccion para liberar la sesion
			unset($_SESSION['registroFotografico']);	
		}?>

		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

		<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
		<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
		<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
		<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>

    	<style type="text/css">
			<!--
			#titulo-modBitacora { position:absolute; left:30px; top:146px; width:268px; height:19px; z-index:11; }
			#tabla-fechas { position:absolute; left:30px; top:192px; width:393px;	height:147px; z-index:16; }
			#tabla-id { position:absolute; left:533px; top:192px; width:393px;	height:147px; z-index:16; }
			#tabla-verDetalle { position:absolute; left:30px; top:373px; width:960px; height:250px; z-index:16; overflow:scroll }
			#fechaIni { position:absolute; left:244px; top:219px; width:30px; height:26px; z-index:14; }
			#fechaFin { position:absolute; left:243px; top:258px; width:30px; height:26px; z-index:14; }
			#btns-regpdf { position: absolute; left:30px; top:600px; width:945px; height:40px; z-index:23; }
			#botonesExp {position:absolute;left:166px;top:670px;width:750px;height:37px;z-index:14;}
		-->
	    </style>
		</head>
		<body>
			<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
			<div class="titulo_barra" id="titulo-modBitacora">Modificar Recorridos de Seguridad </div>
			<form  onsubmit="return valFormFechasRecSeg(this);"name="frm_modRegistro" method="post" action="frm_modificarRecSeg.php">
			<fieldset id="tabla-fechas" class="borde_seccion">
			<legend class="titulo_etiqueta">Seleccionar  Registro a Modificar por Fechas </legend>
				<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td><div align="right">*Fecha Ini </div></td>
						<td colspan="3">
							<input name="txt_fechaIni" id="txt_fechaIni" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y"); ?>" readonly="readonly"  
							type="text"/>
						</td>
					</tr>
					<tr>
			  			<td width="29%"><div align="right">*Fecha Fin </div></td>
						<td width="17%">
							<input name="txt_fechaFin" id="txt_fechaFin" class="caja_de_texto" size="10" 
							value="<?php echo date("d/m/Y",strtotime("+30 day")); ?>" readonly="readonly" type="text"/>
						</td>
						<td width="26%">&nbsp;</td>
						<td width="28%">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="4">
				  			<div align="center">
								<input type="submit" name="sbt_consultar" value="Consultar" class="botones" title="Consultar Recorridos de Seguridad" 
								onMouseOver="window.estatus='';return true"/>
								&nbsp;&nbsp;&nbsp;&nbsp;
								<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Recorridos de Seguridad" 
								onMouseOver="window.status='';return true" onclick="location.href='menu_recorridosSeguridad.php'" />
							</div>
						</td>
					</tr>
				</table>
			</fieldset>
			</form>
			
		
			<form onsubmit="return valFormModRec(this);" name="frm_modRegistro2" method="post" action="frm_modificarRecSeg.php">
			<fieldset id="tabla-id" class="borde_seccion">
			<legend class="titulo_etiqueta">Seleccionar Clave de Registro a Modificar</legend>
				<table width="100%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
			  			<td><div align="right">*Clave</div></td>
						<td colspan="3"><?php 
							$cmb_id="";
							$conn = conecta("bd_seguridad");
							$result=mysql_query("SELECT DISTINCT recorridos_seguridad_id_recorrido FROM detalle_recorridos_seguridad 
								ORDER BY recorridos_seguridad_id_recorrido");
							if($id=mysql_fetch_array($result)){?>
								<select name="cmb_id" id="cmb_id" size="1" class="combo_box">
								  <option value="">CLAVE RECORRIDO</option>
								  <?php 
								  do{
									if ($id['recorridos_seguridad_id_recorrido'] == $cmb_id){
										echo "<option value='$id[recorridos_seguridad_id_recorrido]' selected='selected'>$id[recorridos_seguridad_id_recorrido]</option>";
									}
									else{
										echo "<option value='$id[recorridos_seguridad_id_recorrido]'>$id[recorridos_seguridad_id_recorrido]</option>";
									}
								}while($id=mysql_fetch_array($result)); 
								//Cerrar la conexion con la BD		
								mysql_close($conn);
								?>
								</select>
					<?php }
						else{
							echo "<label class='msje_correcto'> No hay Recorridos Registrados</label>
							<input type='hidden' name='cmb_id' id='cmb_id'/>";
					  }?>
					</td>
				</tr>
				<tr>
			  		<td width="24%">&nbsp;</td>
			  		<td width="22%">&nbsp;</td>
					<td width="26%">&nbsp;</td>
				  <td width="28%">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="4">
				  		<div align="center">
							<input type="submit" name="sbt_consultar" value="Consultar" class="botones" title="Consultar Recorridos de Seguridad" 
							onMouseOver="window.estatus='';return true"/>
							&nbsp;&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Recorridos de Seguridad" 
							onMouseOver="window.status='';return true" onclick="location.href='menu_recorridosSeguridad.php'" />
				  		</div>
					</td>
				</tr>
			</table>
		</fieldset>
		</form>
		
	  
	  	<div id="fechaIni">
			<input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modRegistro.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Inicio"/> 
		</div>
		<div id="fechaFin">
			<input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modRegistro.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar la Fecha de Fin"/> 
		</div>
	
		<?php //Comprobamos si fue presionado el boton de consutlar
		if(isset($_POST['sbt_consultar'])){?>
			<form  name="frm_verDetalle" id="frm_verDetalle" method="post" action="frm_modificarRecSeg2.php">
			<div id="tabla-verDetalle" class="borde_seccion2">
				<input type="hidden" name="hdn_btn" id="hdn_btn" value="radio"/>
				<?php 
					$band=mostrarRegistros();
				?>
			</div>
			<?php if($band==1){?>
			<div align="center" id="botonesExp">
    			<input name="sbt_exportar" type="submit" class="botones" id="sbt_exportar"  value="Exportar a Excel" 
				title="Exportar Registro de Recorridos de Seguridad" onmouseover="window.status='';return true" 
				onclick="hdn_btn.value='sbt_exportar';cambiarSubmitRS();"/>
			</div>	
			<?php }?>
			</form>
	<?php }?>
	</body>
	<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>