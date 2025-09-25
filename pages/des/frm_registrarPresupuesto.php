<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarPresupuesto.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
    <script type="text/javascript" src="includes/ajax/verificarRangoFechas.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#tabla-registrarPresupuesto {position:absolute;left:30px;top:190px;width:880px;height:338px;z-index:14;}
		#calendario-Ini {position:absolute;left:334px;top:233px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:333px;top:270px;width:30px;height:26px;z-index:14;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Ingresar Avance Presupuestado </div><?php
	
	if(isset($_POST['sbt_guardar']))
		guardarPresupuesto();?>

	<script type="text/javascript" language="javascript">
		setTimeout("sumarDiasMes(); calcularDomingos();",500);

	</script>
      
    <fieldset class="borde_seccion" id="tabla-registrarPresupuesto" name="tabla-registrarPresupuesto">
	<legend class="titulo_etiqueta">Ingresar Datos Avance Presupuestado</legend>	
	<br>
	<form onSubmit="return valFormRegPresupuesto(this);" name="frm_registrarPresupuesto" method="post" enctype="multipart/form-data" action="frm_registrarPresupuesto.php">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="20%"><div align="right">Fecha Inicio</div></td>
				<td width="22%">
					<input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y"); ?>" 
					onchange="sumarDiasMes(); calcularDomingos();verificarRangoValido(this.value,txt_fechaFin.value,cmb_obra.value);"	readonly="readonly" />
				</td>
				<td width="20%"><div align="right">D&iacute;as Laborables</div>
				</div></td>
				<td width="38%">
					<input name="txt_diasLaborales" type="text" class="caja_de_texto" id="txt_diasLaborales" onchange="calcularPptoDiario(); formatCero();"  
					onkeypress="return permite(event,'num',3);" value="" size="3" maxlength="3"/>
				</td>
			</tr>     
			<tr>
				<td><div align="right">Fecha Fin</div></td>
				<td>
					<input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/y", strtotime("+1 month")); ?>" 
					readonly="readonly" onchange="calcularDomingos();verificarRangoValido(txt_fechaIni.value,this.value,cmb_obra.value);" />
				</td>
				<td><div align="right">Domingos</div></td>
				<td>	
					<input name="txt_domingos" type="text" class="caja_de_texto" id="txt_domingos" value="" size="3" maxlength="3" readonly="readonly"/>
				</td>
			</tr> 
			<tr>
				<td><div align="right">*Cliente</div></td>
				<td><?php $cmb_cliente="";
					$conn = conecta("bd_desarrollo");
					$result=mysql_query("SELECT DISTINCT id_cliente, nom_cliente FROM catalogo_clientes ORDER BY id_cliente");
						if($clientes=mysql_fetch_array($result)){?>
							<select name="cmb_cliente" id="cmb_cliente" size="1" class="combo_box"  
							onchange="verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,cmb_cliente.value);">
							<option value="">Cliente</option>
						<?php do{
							if ($clientes['nom_cliente'] == $cmb_cliente){
								echo "<option value='$clientes[id_cliente]' selected='selected'>$clientes[nom_cliente]</option>";
							}
								else{
									echo "<option value='$clientes[id_cliente]'>$clientes[nom_cliente]</option>";
								}
							}while($clientes=mysql_fetch_array($result)); 
					//oCerrar la conexion con la BD		
					mysql_close($conn);?>
							</select>
					<?php }
						else{
						echo "<label class='msje_correcto'> No hay clientes Registrados</label>
						<input type='hidden' name='cmb_cliente' id='cmb_cliente' disabled='disabled'/>";
						}?>			
				</td>
				<td><div align="right">
					<input type="checkbox" name="ckb_nuevoCliente" id="ckb_nuevoCliente"
					onclick="agregarNuevoCliente(this, 'txt_nuevoCliente', 'cmb_cliente');"/>Agregar Cliente</div>			
				</td>
				<td>
					<input name="txt_nuevoCliente" id="txt_nuevoCliente" type="text" class="caja_de_texto" size="40" readonly="readonly" 
					onkeypress="return permite(event,'num',2);" />			
				</td>
			</tr>
			<tr>
				<td><div align="right">*Mts. Presupuestados</div></td>
				<td>
					<input type="text" name="txt_mtsPresupuestados" id="txt_mtsPresupuestados" maxlength="10" size="8" class="caja_de_texto" 
					onkeypress="return permite(event,'num',2);" value=""  onchange="formatCurrency(this.value,'txt_mtsPresupuestados');calcularPptoDiario(); "  />
					m 
				</td>
				<td><div align="right">*Mts. Presupuestados Diario</div></td>
				<td>
					<input type="text" name="txt_mtsPresupuestadosDiarios" id="txt_mtsPresupuestadosDiarios" value="" maxlength="10" size="8" class="caja_de_texto"
					onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_mtsPresupuestadosDiarios');" readonly="readonly"/>
					m 
				</td>
			</tr>
			<tr>
				<td><div align="right">*Mts. Quincena 1</div></td>
				<td>
					<input name="txt_mtsQuincena1" type="text" class="caja_de_texto" id="txt_mtsQuincena1" 
					onchange="formatCurrency(this.value,'txt_mtsQuincena1');" 
					onkeypress="return permite(event,'num',2);" value="" size="8" maxlength="10" />
					m
				</td>
				<td><div align="right">*Mts. Quincena 2</div></td>
				<td>
					<input type="text" name="txt_mtsQuincena2" id="txt_mtsQuincena2" value="" maxlength="10" size="8" class="caja_de_texto"
					onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_mtsQuincena2');"/>
					m 
				</td>
			</tr>
			<tr>
			<td><div align="right">*Disparos por D&iacute;a</div></td>
				<td>
					<input type="text" name="txt_disparosDia" id="txt_disparosDia" maxlength="10" size="8" class="caja_de_texto" 
					onkeypress="return permite(event,'num',3);" value=""  />
				</td>
				<td><div align="right">*Disparos por Turno</div></td>
				<td>
					<input type="text" name="txt_disparosTurno" id="txt_disparosTurno" value="" maxlength="10" size="8" class="caja_de_texto"
					onkeypress="return permite(event,'num',3);" />
				</td>
			</tr>
			<tr>
				<td colspan="5"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td colspan="6"><div align="center">
					<input type="hidden" name="hdn_fechas" id="hdn_fechas" value="0"/>
					<input type="hidden" name="hdn_band" id="hdn_band" value="si"/>
					<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Presupuesto Mensual" 
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Restablecer Formulario" onMouseOver="window.status='';return true"
					onclick="restablecePresupuesto();"  /> 
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Cancelar" 
					title="Cancelar la Operaci&oacute;n de Registro y Regresar al Men&uacute;  Presupuesto" 
					onMouseOver="window.status='';return true" onclick="confirmarSalida('menu_presupuesto.php?borrar')"/>
				</div></td>
			</tr>
		</table>
    </form>
</fieldset>
    
    <div id="calendario-Ini">
        <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_registrarPresupuesto.txt_fechaIni,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Inicio"/> 
	</div>
    
    <div id="calendario-Fin">
        <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_registrarPresupuesto.txt_fechaFin,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Fin"/> 
	</div>
    
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>