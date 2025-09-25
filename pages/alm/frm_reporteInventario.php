<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Este archivo contiene las funciones para mostrar las Ordenes de Compra registradas y el detalle de las mismas
		include ("op_reporteInventario.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>	
    <style type="text/css">
		<!--
		#titulo-salida { position:absolute; left:15px; top:146px; width:141px; height:19px; z-index:11; }
		#form-datos-inv { position:absolute; left:30px; top:190px; width:509px; height:182px; z-index:13; }
		#form-datos-inv-max-min { position:absolute; left:30px; top:430px; width:509px; height:140px; z-index:13; }
		#registro-material { position:absolute; left:586px; top:192px; width:545px; height:206px; z-index:14; }
		#titulo-reporteInventario { position:absolute; left:30px; top:146px; width:236px; height:19px; z-index:11; }
		#tabla-inventario { position:absolute; left:30px; top:190px; width:940px; height:418px; z-index:12; overflow:scroll; }
		#calendario { position:absolute; left:335px; top:232px; width:29px; height:25px; z-index:14; }
		#btns-regpdf { position: absolute; left:30px; top:660px; width:945px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-reporteInventario">Reporte de Inventario </div>

<?php //Si la variables $txt_fecha no esta definida en el arreglo $_POST, entonces desplegar el formulario para solictar las fechas
	if(!isset($_POST['sbt_registrar']) && !isset($_POST['sbt_registrarMaxMin'])){?>	
	<fieldset id="form-datos-inv" class="borde_seccion">	
	<legend class="titulo_etiqueta">Seleccionar la Fecha de Realizaci&oacute;n del Inventario</legend>
	<br>
	<form name="frm_datosReporteInventario" action="frm_reporteInventario.php" method="post">
	<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<!--<td width="120">
			<div align="right">Fecha de Cierre</div></td>
			<td width="120"><input name="txt_fechaCierre" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="50"></td>
		    <td width="120">&nbsp;</td>-->
			<td width="120">
			<div align="right">Familia</div></td>
			<td width="120">
				<?php
				$conn = conecta("bd_almacen");
				$rs = mysql_query("SELECT DISTINCT linea_articulo FROM materiales WHERE grupo!='PLANTA' ORDER BY linea_articulo");
				if($row=mysql_fetch_array($rs)){?>            
                    <select name="cmb_categoria" size="1" class="combo_box">
                        <option value="TODAS">TODAS</option><?php 
						do{
                            if ($row['linea_articulo'] == $cmb_categoria){
                                echo "<option value='$row[linea_articulo]' selected='selected'>$row[linea_articulo]</option>";
                            }
                            else{
                                echo "<option value='$row[linea_articulo]'>$row[linea_articulo]</option>";
                            }
                        }while($row=mysql_fetch_array($rs));?>
                    </select><?php
				}
				else {?>
					<label class="msje_correcto"><u><strong>NO</strong></u> Hay Categor&iacute;as Para Modificar</label>
					<input type='hidden' name='cmb_categoria' id='cmb_categoria'/>
                <?php } ?>
			</td>
		    <td width="120">&nbsp;</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		    <td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<input type="hidden" name="hdn_reporte" id="hdn_reporte" value="Inventario">
			  <div align="center">
			    <input name="sbt_registrar" type="submit" class="botones" value="Ver Reporte" onMouseOver="window.status='';return true" title="Mostrar Inventario" />
	          </div>			</td>
			<td>
		      
	          <div align="center">
	            <input name="btn_limpiar" type="reset" class="botones" value="Restablecer" onMouseOver="window.status='';return true" title="Restablecer la Fecha Seleccionada" />
              </div></td>
		    <td><div align="center">
		      <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Reportes" onClick="location.href='menu_reportes.php'" />
		      </div></td>
		</tr>
	</table>    
	</form>
	</fieldset>
	
	<fieldset id="form-datos-inv-max-min" class="borde_seccion">	
	<legend class="titulo_etiqueta">Seleccionar M&aacute;ximos o M&iacute;nimos</legend>
	<br>
	<form name="frm_datosReporteInventarioMaxMin" action="frm_reporteInventario.php" method="post">
	<table border="0" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="120">
				<div align="right">Filtrar por:</div></td>
				<td>
					<select class="combo_box" name="cmb_filtro" id="cmb_filtro">
						<option value="minimo">M&Iacute;NIMO</option>
						<option value="maximo">M&Aacute;XIMO</option>
					</select>
				</td>
			</td>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		    <td>&nbsp;</td>
			<input name="txt_fechaCierre" type="hidden" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="50">
		</tr>
		<tr>
			<td>
				<input type="hidden" name="hdn_reporte" id="hdn_reporte" value="MaxMin">
				<div align="center">
					<input name="sbt_registrarMaxMin" type="submit" class="botones" value="Ver Reporte" onMouseOver="window.status='';return true" title="Mostrar Inventario M&aacute;ximos o M&iacute;nimos" />
				</div>
			</td>
			<td>
				<div align="center">
					<input name="btn_limpiar" type="reset" class="botones" value="Restablecer" onMouseOver="window.status='';return true" title="Restablecer la Fecha Seleccionada" />
				</div>
			</td>
		    <td>
				<div align="center">
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Reportes" onClick="location.href='menu_reportes.php'" />
				</div>
			</td>
		</tr>
	</table>    
	</form>
	</fieldset>
	
	
	<!--<div id="calendario">
		<input type="image" onclick="displayCalendar(document.frm_datosReporteInventario.txt_fechaCierre,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
</div>-->
	
	<?php 
	}
	else{?>
		<div id="tabla-inventario" align="center" class="borde_seccion2">
		<?php 
			dibujarDetalle($cmb_categoria); 
		?>
	<?php }?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>