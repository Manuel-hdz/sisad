<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Aseguramiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarListaMaestraRegCal.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionAseguramiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:352px;height:20px;z-index:11;}
		#tabla-agregarRegistro {position:absolute;left:30px;top:190px;width:764px;height:393px;z-index:12;}
		#calendario{position:absolute;left:288px;top:351px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar Lista Maestra de Registros Calidad </div>
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Registro </legend>	
	<br>
	
	<form onsubmit="return valFormLista(this);"name="frm_agregarRegistro" method="post" action="frm_registrarListaMaestraRegCal.php">
	  <table width="764" height="358"  cellpadding="5" cellspacing="5" class="tabla_frm">
      	<tr>
        	<td width="131" height="31"><div align="right">*Departamento Emisor </div></td>
          	<td width="212">
		  	<?php  
				$cmb_depto="";
				$conn = conecta("bd_usuarios");
				$result=mysql_query("SELECT DISTINCT UPPER (depto) as depto FROM usuarios WHERE depto != 'Panel' AND depto != 'DireccionGral' ORDER BY depto");
				if($depto=mysql_fetch_array($result)){?>
              	<select name="cmb_depto" id="cmb_depto" size="1" class="combo_box" tabindex="1">
               		<option value="">Departamento</option>
                	<?php 
				  	do{
						if ($depto['depto'] == $cmb_depto){
							echo "<option value='$depto[depto]' selected='selected'>$depto[depto]</option>";
						}
						else{
							echo "<option value='$depto[depto]'>$depto[depto]</option>";
						}
					}while($depto=mysql_fetch_array($result)); 
				//Cerrar la conexion con la BD		
				mysql_close($conn);?>
              	</select>
              	<?php }
				else{
					echo "<label class='msje_correcto'> No hay Departamentos Registrados</label>
					<input type='hidden' name='cmb_depto' id='cmb_depto'/>";?>
              <?php }?>          
	  	 	 </td>
          	<td><div align="right">*Indexaci&oacute;n </div></td>
         	<td width="197">
				<input name="txt_indexacion" id="txt_indexacion" type="text" class="caja_de_texto" size="30" onkeypress="return permite(event,'num_car', 1);" 
				tabindex="2"/></td>
        </tr>
        <tr>
        	<td><div align="right">*C&oacute;digo Formato </div></td>
          	<td>
				<input name="txt_noFormato" id="txt_noFormato" type="text" class="caja_de_texto" size="15" onkeypress="return permite(event,'num_car', 1);" 
				tabindex="3"/>
			</td>
          	<td width="157"><div align="right">*Periodo Mantenimiento</div></td>
          	<td width="197">
				<input name="txt_perMtto" id="txt_perMtto" type="text" class="caja_de_texto" size="30" onkeypress="return permite(event,'num_car', 1);" tabindex="4"/>
			</td>
        </tr>
        <tr>
          	<td><div align="right">*No. de Revisi&oacute;n </div></td>
          	<td>
				<input name="txt_noRevision" id="txt_noRevision" type="text" class="caja_de_texto" size="10" onkeypress="return permite(event,'num', 2);" tabindex="5"/>
			</td>
          	<td><div align="right">*Disposici&oacute;n Final </div></td>
          	<td>
				<input name="txt_dispFinal" id="txt_dispFinal" type="text" class="caja_de_texto" size="20" onkeypress="return permite(event,'num_car', 1);" tabindex="6"/>
			</td>
        </tr>
        <tr>
        	<td><div align="right">*Fecha</div></td>
          	<td><input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
          	<td><div align="right">*Documentos Asociados </div></td>
          	<td>
				<input name="txt_docAso" id="txt_docAso" type="text" class="caja_de_texto" size="30" onkeypress="return permite(event,'num_car', 1);" tabindex="8"/>
			</td>
        </tr>
		<tr>
		  	<td height="43"><div align="right">*T&iacute;tulo</div></td>
		 	<td >
				<textarea name="txa_titulo" id="txa_titulo" maxlength="80" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);" tabindex="9"></textarea> </td>
		 	<td><div align="right"> <div align="right">*M&eacute;todo de Colecci&oacute;n </div></div></td>
         	<td>
				<textarea name="txa_metColeccion" id="txa_metColeccion" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" 
				cols="30" onkeypress="return permite(event,'num_car', 0);" tabindex="10"></textarea>
			</td>
        </tr>
		<tr>
			<td><div align="right">*Accesible a </div></td>
			<td>
				<?php  
					$cmb_acceso="";
					$conn = conecta("bd_aseguramiento");
					$result=mysql_query("SELECT DISTINCT acceso FROM catalogo_acceso ORDER BY acceso");
					if($acceso=mysql_fetch_array($result)){?>
					  <select name="cmb_acceso" id="cmb_acceso" size="1" class="combo_box" tabindex="11">
						<option value="">Acceso</option>
						<?php 
						  do{
								if ($acceso['acceso'] == $cmb_acceso){
									echo "<option value='$acceso[acceso]' selected='selected'>$acceso[acceso]</option>";
								}
								else{
									echo "<option value='$acceso[acceso]'>$acceso[acceso]</option>";
								}
							}while($acceso=mysql_fetch_array($result)); 
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
					  </select>
					  <?php }
						else{
							echo "<label class='msje_correcto'> No hay Accesos Registrados</label>
							<input type='hidden' name='cmb_acceso' id='cmb_acceso'/>";?>
					  <?php }?>          
			</td>
			<td><div align="right">
				<input type="checkbox" name="ckb_acceso" id="ckb_acceso" onclick="agregarNuevoAcceso(this, 'txt_acceso', 'cmb_acceso'); " 
				title="Seleccione para Escribir el Nombre de un Acceso que no Exista" tabindex="12" />
	            Agregar Acceso </div>
			</td>
			<td><input name="txt_acceso" id="txt_acceso" type="text" class="caja_de_texto" size="20" readonly="readonly"/></td>
		</tr>
		<tr>
			<td><div align="right">*Ubicaci&oacute;n</div></td>
          	<td>
				<input name="txt_ubicacion" id="txt_ubicacion" type="text" class="caja_de_texto" size="40" readonly="readonly" 
				onclick="window.open('verDepartamentos.php','_blank','top=50, left=50, width=380, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar Departamentos" tabindex="13"/>
			</td>
		</tr>
        <tr>
          	<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>
        <tr>
        	<td colspan="4"><div align="center">
            	<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Registro De Lista Maestra" onmouseover="window.status='';return true"/>
	            &nbsp;&nbsp;&nbsp;
    	        <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
        	    &nbsp;&nbsp;&nbsp;
            	<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Repositorio" onmouseover="window.status='';return true"  onclick="confirmarSalida('menu_listaRegCal.php')" />
				<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si" />
          </div></td>
        </tr>
      </table>
	</form>
</fieldset>
<div id="calendario">
	<input name="calendario" type="image" id="calendario2" onclick="displayCalendar (document.frm_agregarRegistro.txt_fecha,'dd/mm/yyyy',this)" 
	onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0"
	tabindex="7"/>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>