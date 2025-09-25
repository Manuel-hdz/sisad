<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
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
	<script type="text/javascript" src="../../includes/validacionProduccion.js" ></script>
    <script type="text/javascript" src="includes/ajax/verificarRangoFechas.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#tabla-registrarPresupuesto {position:absolute;left:30px;top:190px;width:716px;height:280px;z-index:14;}
		#calendario-Ini {position:absolute;left:305px;top:231px;width:30px;height:26px;z-index:16;}
		#calendario-Fin {position:absolute;left:308px;top:271px;width:30px;height:26px;z-index:16;}
		-->
    </style>
</head>
<body><?php
	if(isset($_POST['sbt_guardar']))
		guardarPresupuesto();?>	

	<script type="text/javascript" language="javascript">
		setTimeout("sumarDiasMes(); calcularDomingos();",500);
	</script>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Ingresar Presupuesto Mensual</div>
    <fieldset class="borde_seccion" id="tabla-registrarPresupuesto" name="tabla-registrarPresupuesto">
	<legend class="titulo_etiqueta">Ingresar Datos del Presupuesto Mensual</legend>	
	<br>
	<form onSubmit="return valFormRegPresupuesto(this);" name="frm_registrarPresupuesto" method="post" action="frm_registrarPresupuesto.php">
    <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  <td width="151"><div align="right">Fecha Inicio</div></td>
			<td width="136"><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10"
            	value="<?php echo date("d/m/Y"); ?>" 
	            onchange="sumarDiasMes(); calcularDomingos(); verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,cmb_destino.value)"
    	        readonly="readonly"/>			</td>
			<td width="145"><div align="right">D&iacute;as Laborales</div></td>
			<td width="217"><input type="text" class="caja_de_texto" value="" name="txt_diasLaborales" id="txt_diasLaborales" size="4" 
				 onchange="calcularPptoDiario(); formatCero();" onkeypress="return permite(event,'num',3);"/></td>
		</tr>     
		<tr>
		  <td width="151"><div align="right">Fecha Fin</div></td>
			<td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y", strtotime("+1 month")); ?>" 
            	readonly="readonly"
				 onchange="if(calcularDomingos()){ 
						  	verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,cmb_destino.value);
						  }"/>			</td>
		  <td width="145"><div align="right">Domingos</div></td>
			<td><input type="text" class="caja_de_texto" value="" name="txt_domingos" id="txt_domingos" size="4" readonly="readonly"/></td>        
		</tr> 
        <tr>
		  <td width="151"><div align="right">*Volumen Presupuestado</div></td>
			<td><input type="text" name="txt_volPresupuestado" id="txt_volPresupuestado" maxlength="10" size="10" class="caja_de_texto" 
				onkeypress="return permite(event,'num',2);" value="" 
				onchange="formatCurrency(this.value,'txt_volPresupuestado'); calcularPptoDiario();"/>
		    m&sup3;</td>
			<td><div align="right">*Volumen Diario</div></td>
			<td><input type="text" name="txt_presupuestoDiario" id="txt_presupuestoDiario" value="" maxlength="10" size="10" class="caja_de_texto"
            	onkeypress="return permite(event,'num',2);" onchange="formatCurrency(this.value,'txt_presupuestoDiario')"/></td>
		</tr>
		<tr>
		  <td width="151"><div align="right">*Destino</div></td>
				<td width="136">
					<?php $cmb_destino="";
						$conn = conecta("bd_produccion");
						$result=mysql_query("SELECT DISTINCT id_destino, destino FROM catalogo_destino ORDER BY destino");
						if($destino=mysql_fetch_array($result)){?>
					<select name="cmb_destino" id="cmb_destino" size="1" class="combo_box"  
					onchange="verificarRangoValido(txt_fechaIni.value,txt_fechaFin.value,hdn_claveDefinida.value,cmb_destino.value);">
					  <option value="">Destino</option>
                      <?php 
							  do{
								if ($destino['destino'] == $cmb_destino){
									echo "<option value='$destino[id_destino]' selected='selected'>$destino[destino]</option>";
								}
								else{
									echo "<option value='$destino[id_destino]'>$destino[destino]</option>";
								}
							}while($destino=mysql_fetch_array($result)); 
							//oCerrar la conexion con la BD		
							mysql_close($conn);
							?>
                    </select>
		  <?php }
					else{
						echo "<label class='msje_correcto'> No hay Destinos Registrados</label>
						<input type='hidden' name='cmb_destino' id='cmb_destino' disabled='disabled'/>";
					}?>			</td>
			<td><div align="right">
				<input type="checkbox" name="ckb_nuevoDestino" id="ckb_nuevoDestino"
				 onclick="agregarNuevoDestino(this, 'txt_nuevoDestino', 'cmb_destino');"/>Agregar Destino</div>			</td>
			<td>
				<input name="txt_nuevoDestino" id="txt_nuevoDestino" type="text" class="caja_de_texto" size="40" readonly="readonly" 
				onkeypress="return permite(event,'num',2);"  />			</td>
		</tr>
        <tr>
            <td height="29" colspan="4"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>
        <tr>
            <td colspan="4">
                <div align="center">
                	<input type="hidden" name="hdn_fechas" id="hdn_fechas" value="0"/>
                    <input type="hidden" name="hdn_band" id="hdn_band" value="si"/>
                    <input type="hidden" name="hdn_claveDefinida" id="hdn_claveDefinida" value=""/>
					
					<?php //Esta variable 'txt_volProducido' solo se coloca para que no marque error la funcion de nuevoDestino()?>
					<input type="hidden" name="txt_volProducido" id="txt_volProducido" value=""/>
					<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Presupuesto Mensual" 
                    onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Restablecer Formulario" onMouseOver="window.status='';return true"
					onclick="restablecePresupuesto();"  /> 
					&nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute;  Planeación" 
                    onMouseOver="window.status='';return true" onclick="location.href='menu_presupuesto.php';" />
                </div>			</td>
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
</body><?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>