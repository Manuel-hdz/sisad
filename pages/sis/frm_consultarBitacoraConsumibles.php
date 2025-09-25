<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	include ("op_consultarBitacoraConsumibles.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
	?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionSistemas.js" ></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#botones_pdf {position:absolute;left:30px;top:680px;width:940px;height:20px;z-index:11;}
		#consultar-consumibles {position:absolute;left:30px;top:190px;width:600px;height:200px;z-index:12;}
		#calendario_repInicio { position:absolute; left:231px; top:295px; width:29px; height:24px; z-index:14; }
		#calendario_repCierre { position:absolute; left:600px; top:295px; width:30px; height:26px; z-index:15; }
		#tabla-consumibles {position:absolute; left:30px; top:180px; width:940px; height:450px; z-index:18; overflow:auto; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Consultar Consumibles de Impresoras</div>
		
	<?php
	//Verificar si se debe guardar un registro
	if(isset($_POST["sbt_guardar"])){
		consultarBitacoraConsumibles();
	} else {
		$fechaIni = date("d/m/Y", strtotime("-7 day")); $fechaFin = date("d/m/Y");
		?>
		<fieldset class="borde_seccion" id="consultar-consumibles" name="consultar-consumibles">
		<legend class="titulo_etiqueta">Seleccionar los parametros de busqueda</legend>	
		<br>
		<form name="frm_consultarBitacoraConsumibles" method="post" action="frm_consultarBitacoraConsumibles.php" onsubmit="">
			<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
				<td><div align="right">*Consumible</div></td>
				<td>
					<?php
					//conectar a gerencia
					$conn = conecta('bd_sistemas');
					$rs = mysql_query("SELECT * FROM consumibles ORDER BY descripcion, color");
					if(mysql_num_rows($rs)>0){
					?>
						<select name="cmb_consumibles" id="cmb_consumibles" class="combo_box" required="required">
							<option value="">Consumible</option>
							<option value="TODOS">TODOS</option>
					<?php
						while($datos=mysql_fetch_array($rs)){
						?>
							<option value="<?php echo $datos["id_consumibles"];?>"><?php echo $datos["descripcion"]." ".$datos["color"];?></option>
						<?php
						}
						?>
						</select>
					<?php
					//cerrar conexion
					mysql_close($conn);
					}
					else
						echo "<select name='cmb_tipo' id='cmb_tipo' class='combo_box' required='required'>
								<option value=''>No Hay Datos</option>
							  </select>";
				?>
				</td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<input type="checkbox" name="ckb_incluirFechas" id="ckb_incluirFechas" value="SI" />Incluir Fechas en la Consulta
				</td>
			</tr>
			<tr>
				<td align="right">Fecha Inicio</td>
				<td>
					<input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" value="<?php echo $fechaIni;  ?>" 
					size="10" maxlength="15" />
				</td>
				<td align="right">Fecha Fin</td>
				<td>
					<input name="txt_fechaFin" id="txt_fechaFin"   readonly="readonly" value="<?php echo $fechaFin;  ?>" size="10" maxlength="15" width="90" />				  
				</td>
			</tr>
			<tr>
				<td colspan="8"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
			</tr>
			<tr>
				<td colspan="8" align="center">
					<input type="submit" class="botones" name="sbt_guardar" id="sbt_guardar" value="Consultar" title="Consultar Bitacora de Consumible;" onmouseover="window.status='';return true;"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" class="botones" name="btn_regresar" id="btn_regresar" value="Regresar" title="Volver al Men&uacute; Bitacora de Consumibles" onclick="location.href='menu_bitconsumibles.php'"/>
				</td>
			</tr>
			</table>
		</form>
		</fieldset>
		<div id="calendario_repInicio">
			<input name="calendario" type="image" id="calendario" onclick="displayCalendar(document.frm_consultarBitacoraConsumibles.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<div id="calendario_repCierre">
			<input name="calendario" type="image" id="calendario" onclick="displayCalendar(document.frm_consultarBitacoraConsumibles.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
		</div>
		<!--<div id="tabla-consumibles" class="borde_seccion2" align="center"> 
		<?php
	}
		//mostrarConsumibles();
		?>
	</div>-->
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>