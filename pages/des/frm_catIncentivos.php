<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	 	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo con las operaciones sobre el catalogo de Incentivos
		include ("op_catIncentivos.php");?>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
   	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
    <style type="text/css">
		<!--
		#titulo-ingresar{position:absolute; left:30px; top:146px; width:248px; height:19px; z-index:11;}	
		#registrar-incentivo{position:absolute; left:30px; top:190px; width:655px; height:175px; z-index:12;}
		#resultado-estandares{position:absolute; left:30px; top:400px; width:950px; height:200px; z-index:13; overflow:scroll;}
		#botones{position:absolute;left:30px;top:650px;width:900px;height:30px;z-index:14;}
		#agregar-incentivo{position:absolute; left:30px; top:190px; width:565px; height:175px; z-index:15;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-ingresar">Incentivos para Actividades</div>
	
	<?php 
	//Verificar si el botón de sbt_agregarNuevo ha sido presionado, en cuyo caso, agregar la actividad
	if (isset($_POST["sbt_agregarNuevo"])){
		agregarActividad();
	}
	
	if (isset($_POST["sbt_agregar"]) || isset($_POST["sbt_modificar"])){
		$nuevo="no";
		if (isset($_POST["hdn_incentivo"])){
			$inc=$_POST["hdn_incentivo"];
			$area=obtenerDato("bd_desarrollo", "incentivos_actividades", "area", "id_incentivo", $inc);
			$estandar=obtenerDato("bd_desarrollo", "incentivos_actividades", "estandar", "id_incentivo", $inc);
		}
		else{
			//Si esta definido el combo de Area, tomar el valor del mismo
			if(isset($_POST["cmb_area"]))
				$area=$_POST["cmb_area"];
			else
				$area=$_POST["hdn_area"];
			//Si esta definido el combo de Estandar, tomar el valor del mismo
			if(isset($_POST["txt_nuevoEstandar"]))
				$estandar=$_POST["txt_nuevoEstandar"];
			else
				$estandar=$_POST["hdn_estandar"];
			$inc=calculaIDNuevo($area);
			$nuevo="si";
		}
		$msg="Actividades del Est&aacute;ndar $estandar para $area";
		$actividad="";
		$costo="0.00";
		//Conectar a la BD para calcular el siguiente numero
		$conn=conecta("bd_desarrollo");
		//Escribimos la consulta que rescata el id del incentivo
		$stm_sql = "SELECT MAX(numero)+1 AS num FROM detalle_incentivos WHERE incentivos_actividades_id_incentivo='$inc'";
		//Ejecutar la Sentencia creada
		$rs = mysql_query($stm_sql);
		$datos=mysql_fetch_array($rs);
		$num=$datos["num"];
		if ($num==NULL)
			$num=1;
		mysql_close($conn);
		if (isset($_POST["sbt_modificar"])){
			$num=$_POST["rdb_actividad"];
			$conn=conecta("bd_desarrollo");
			//Escribimos la consulta que rescata el concepto y costo del incentivo
			$stm_sql = "SELECT concepto,costo FROM detalle_incentivos WHERE incentivos_actividades_id_incentivo='$inc' AND numero='$num'";
			//Ejecutar la Sentencia creada
			$rs = mysql_query($stm_sql);
			$datos=mysql_fetch_array($rs);
			$actividad=$datos["concepto"];
			$costo=number_format($datos["costo"],2,".",",");
			mysql_close($conn);
		}
		?>
		<fieldset class="borde_seccion" id="agregar-incentivo" name="agregar-incentivo">
		<legend class="titulo_etiqueta">Ingresar Incentivos para Actividades del Est&aacute;ndar <?php echo $estandar;?> para <?php echo $area;?></legend>
		<br>
			<form name="frm_agregarIncentivos" method="post" action="frm_catIncentivos.php" onsubmit="return valFormAgregarIncentivos(this);">
				<input type="hidden" name="hdn_numero" value="<?php echo $num?>"/>
				<input type="hidden" name="hdn_inc" value="<?php echo $inc?>"/>
				<input type="hidden" name="hdn_nuevo" value="<?php echo $nuevo?>"/>
				<input type="hidden" name="hdn_est" value="<?php echo $estandar?>"/>
				<input type="hidden" name="hdn_area" value="<?php echo $area?>"/>
				<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
				<tr>
					<td width="14%" valign="top"><div align="right">*Actividad</div></td>
					<td width="17%" valign="top">
						<textarea name="txa_actividad" id="txa_actividad" maxlength="160" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="3" cols="30" onkeypress="return permite(event,'num_car');" ><?php echo $actividad;?></textarea>
					</td>
					<td width="41%" valign="top"><div align="right">*Costo</div></td>
					<td width="28%" valign="top">$<input type="text" name="txt_costo" id="txt_costo" class="caja_de_num" size="10" onkeypress="return permite(event,'num');" onchange="formatCurrency(this.value,'txt_costo');" value="<?php echo $costo;?>"/></td>
				</tr>
				<tr>
					<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
				</tr>
				<tr>
					<td colspan="4" align="center">
						<?php
							if (isset($_POST["sbt_agregar"])){
								?>
								<input type="submit" class="botones" name="sbt_agregarNuevo" id="sbt_agregarNuevo" value="Agregar" title="Agregar Actividad" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php
							}
							else{
								?>
								<input type="submit" class="botones" name="sbt_modificarActividad" id="sbt_modificarActividad" value="Modificar" title="Modificar Actividad" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Restablecer" title="Restablecer el Formulario"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<?php
							}
						?>						
						<input type="button" class="botones" name="btn_cancelar" id="btn_cancelar" value="Cancelar" title="Cancelar y Volver al Men&uacute; de Sueldos" onclick="location.href='frm_catIncentivos.php'"/>
					</td>
				</tr>
				</table>
			</form>
		</fieldset>
		<?php
	}
	else{
	?>	
		<fieldset class="borde_seccion" id="registrar-incentivo" name="registrar-incentivo">
		<legend class="titulo_etiqueta">Consultar Incentivos y Actividades</legend>	
		<br>
		<form name="frm_catalogoIncentivos" method="post" action="frm_catIncentivos.php" onsubmit="return valFormIncentivos(this);">
			<table width="100%" border="0" align="left" cellpadding="5" cellspacing="5" class="tabla_frm">		
			<tr>
			  <td width="14%"><div align="right">*&Aacute;rea</div></td>
				<td width="17%">
					<?php 
					//Decomentar esta linea simboliza verificar las claves de las actividades de los incentivos, sin embargo, se deja solo comentado por si acaso
					//cargarComboTotal("cmb_area","area","area","catalogo_salarios","bd_desarrollo","Área","","cargarCombo(this.value,'bd_desarrollo','incentivos_actividades','estandar','area','cmb_estandar','Estándar','');","area","","");
					?>
				<select name="cmb_area" id="cmb_area" onchange="cargarCombo(this.value,'bd_desarrollo','incentivos_actividades','estandar','area','cmb_estandar','Estándar','')">
					<option value="" selected="selected">&Aacute;rea</option>
					<option value="JUMBO">JUMBO</option>
					<option value="SCOOP">SCOOP</option>
					<option value="VOLADURAS">VOLADURAS</option>
				</select>
				</td>
			  <td width="41%">
					<div align="right"></div>
			  </td>
			  <td width="28%"></td>
			</tr>
			<tr>
				<td><div align="right">*Est&aacute;ndar</div></td>
				<td>
				<select name="cmb_estandar" id="cmb_estandar">
					<option value="">Est&aacute;ndar</option>
				</select>
				</td>
				<td>
					<div align="right"><input type="checkbox" name="ckb_nuevoEstandar" id="ckb_nuevoEstandar" onclick="agregarNuevoEstandar();" title="Seleccione para Escribir el Nombre de un Est&aacute;ndar que no exista"/>Agregar Nuevo Est&aacute;ndar</div>
				</td>
				<td><input type="text" name="txt_nuevoEstandar" id="txt_nuevoEstandar" class="caja_de_texto" readonly="readonly" size="30"/></td>
			</tr>
			<tr>
				<td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u>.</strong></td>
			</tr>
			<tr>
				<td colspan="4" align="center">
					<span id="boton"></span>
					<input type="submit" class="botones" name="sbt_consultar" id="sbt_consultar" value="Consultar" title="Consultar los Est&aacute;ndares" onmouseover="window.status='';return true;"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" name="btn_limpiar" id="btn_limpiar" value="Limpiar" title="Limpiar el Formulario" onclick="restableceIncentivos();"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="button" class="botones" name="btn_cancelar" id="btn_cancelar" value="Cancelar" title="Cancelar y Volver al Men&uacute; de Sueldos" onclick="location.href='menu_sueldos.php'"/>
				</td>
			</tr>
			</table>
		</form>
		</fieldset>
		
		<?php
		if (isset($_POST["sbt_consultar"]) || isset($_POST["sbt_eliminar"]) || isset($_POST["sbt_agregarNuevo"]) || isset($_POST["sbt_modificarActividad"])){
			$resBorrar=-1;
			if (isset($_POST["sbt_eliminar"])){
				$resBorrar=borrarActividad();
				if ($resBorrar==3 || $resBorrar==4){
					?>
					<script type="text/javascript" language="javascript">
					setTimeout("alert('Actividad Borrada del Estándar');",1000);
					</script>
					<?php
				}
			}
			$resModificar=0;
			if (isset($_POST["sbt_modificarActividad"])){
				$resModificar=modificarActividad();
				if ($resModificar==1){
					?>
					<script type="text/javascript" language="javascript">
					setTimeout("alert('Actividad Modificada Correctamente');",1000);
					</script>
					<?php
				}
			}
			if ($resBorrar!=4){
				?>
					<form name="frm_catalogoActividades" method="post" onsubmit="return valFormActividades(this);">
					<div class="borde_seccion2" id="resultado-estandares">
						<?php
						$res=mostrarActividades();
						?>
					</div>
					<div id="botones" align="center">
						<?php //Recapturar el area y el estandar para el caso de eliminar
						if (isset($_POST["sbt_eliminar"])){
							if (isset($_POST["cmb_area"]))
								$area=$_POST["cmb_area"];
							else
								$area=$_POST["txt_nuevaArea"];
							if (isset($_POST["cmb_estandar"]))
								$estandar=$_POST["cmb_estandar"];
							else
								$estandar=$_POST["txt_nuevoEstandar"];
						}
						else{
							$area="";
							$estandar="";
						}
						?>
						<input type="hidden" name="cmb_area" value="<?php echo $area;?>"/>
						<input type="hidden" name="cmb_estandar" value="<?php echo $estandar;?>"/>
						<input type="hidden" name="txt_nuevoEstandar" value=""/>
						<input type="hidden" name="hdn_accion" id="hdn_accion" value=""/>
						<input type="submit" name="sbt_agregar" id="sbt_agregar" value="Agregar" class="botones" onmouseover="window.status='';return true;" 
						onclick="hdn_accion.value='Agregar'" title="Agregar Nueva Actividad"/>&nbsp;&nbsp;&nbsp;&nbsp;
						<?php if ($res==0){?>
						<input type="submit" name="sbt_eliminar" id="sbt_eliminar" value="Eliminar" class="botones" onmouseover="window.status='';return true;" 
						onclick="hdn_accion.value='Eliminar'" title="Eliminar Actividad"/>&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" name="sbt_modificar" id="sbt_modificar" value="Modificar" class="botones" onmouseover="window.status='';return true;"
						onclick="hdn_accion.value='Modificar';" title="Modificar Actividad"/>&nbsp;&nbsp;&nbsp;&nbsp;
						<?php }?>
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Sueldos" 
						onMouseOver="window.status='';return true" onclick="location.href='menu_sueldos.php';" />
					</div>
					</form>
				<?php 
			}//Fin del if ($resBorrar==4){
		}//Fin del if (isset($_POST["sbt_consultar"]) || isset($_POST["sbt_eliminar"])){
	}//Fin del ELse de -> if (isset($_POST["sbt_agregar"]) || isset($_POST["sbt_modificar"]))
	?>
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>