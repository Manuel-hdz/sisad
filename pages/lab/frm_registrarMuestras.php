<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Inlcuir el archivo que contiene las funciones para almacenar los datos en la BD de Laboratorio
		include ("op_gestionarMuestras.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="includes/ajax/clavesConcreto.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>	    
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-registrarMuestra {position:absolute;left:30px;top:190px;width:740px;height:370px;z-index:14;}
		#div-calendario {position:absolute; left:634px; top:335px; width:30px; height:26px; z-index:15; }
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar Muestra </div><?php
	
	if(isset($_POST['sbt_registrar'])){
		guardarDatosMuestra();
	}
	
	if(!isset($_POST['sbt_registrar'])){ ?>		
		<fieldset class="borde_seccion" id="tabla-registrarMuestra" name="tabla-registrarMuestra">
		<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n de la Mezcla</legend>	
		<br>
		<form onSubmit="return valFormRegistrarMuestra(this);" name="frm_registrarMuestra" method="post" action="">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">*Mezcla</div></td>
				<td colspan="3"><?php
					$res = cargarComboEspecifico("cmb_idMezcla","id_mezcla","mezclas","bd_laboratorio","1","estado","Mezclas","");
					if($res==0){?>
						<span class="msje_correcto">No Hay Mezclas Registradas</span><?php
					}?>			
				</td>
			</tr>
			<tr>
				<td><div align="right">*Id Muestra </div></td>
				<td colspan="3">
					<input name="txt_idMuestra" type="text" class="caja_de_texto" id="txt_idMuestra" value="" size="50" maxlength="5" readonly="readonly" />			
				</td>
			</tr>
			<tr>
				<td><div align="right">*Tipo de Prueba</div></td>
				<td>
					<select name="cmb_tipoPrueba" id="cmb_tipoPrueba" class="combo_box" onchange="activarCampos(this)">
						<option value="" selected="selected">Seleccionar</option>
						<option value="CONCRETO">CONCRETO</option>
						<option value="OBRA DE ZARPEO">OBRA DE ZARPEO</option>
						<option value="OBRA EXTERNA">OBRA EXTERNA</option>
					</select>			
				</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>            
			</tr>
			<tr>
				<td width="25%" align="right">*N° de Muestra</td>
				<td width="25%">
					<input type="text" name="txt_noMuestra" id="txt_noMuestra" value="" size="5" maxlength="5" onkeypress="return permite(event, 'num',2);" class="caja_de_texto"
					onblur="calcularIdMuestra(1);" readonly="readonly" />			
				</td>
				<td width="25%" align="right">*Fecha Colado</td>
				<td width="25%">
					<input type="text" name="txt_fechaColado" id="txt_fechaColado" value="<?php echo date("d/m/Y");?>" size="10" readonly="readonly" class="caja_de_texto" />
				</td>
			</tr>		
			<tr>
				<td align="right">*Revenimiento</td>
				<td>
					<input type="text" name="txt_revenimiento" id="txt_revenimiento" value="" size="10" maxlength="10" class="caja_de_texto"
					onkeypress="return permite(event, 'num',2);" onchange="formatCurrency(txt_revenimiento.value,'txt_revenimiento');" />&nbsp;cm.
				</td>
				<td align="right">*F' c Proyecto</td>
				<td>
					<input type="text" name="txt_fProyecto" id="txt_fProyecto" value="" size="10" maxlength="10" class="caja_de_texto"
					onkeypress="return permite(event, 'num_car',4);" onchange="formatCurrency(txt_fProyecto.value,'txt_fProyecto');" />&nbsp;Kg./cm&sup2;
				</td>
			</tr>        
			<tr>
				<td><div align="right">**Localizaci&oacute;n</div></td>
				<td>
					<select name="cmb_localizacion" id="cmb_localizacion" class="combo_box" onchange="agregarNvoLugar(this); calcularIdMuestra(1);" disabled="disabled">
						<option value="">Localizaci&oacute;n</option><?php 
						$conn = conecta("bd_laboratorio");//Conectarse con la BD de Laboratorio
						//Ejecutar la Sentencia para Obtener las Ubicaciones o Codigo registrado en las Muestras ingresadas en la BD de Laboratorio
						$rs_lugares = mysql_query("SELECT DISTINCT codigo_localizacion FROM muestras WHERE tipo_prueba!='CONCRETO' ORDER BY codigo_localizacion");
						if($lugares=mysql_fetch_array($rs_lugares)){
							//Colocar los lugares encontrados
							do{
								echo "<option value='$lugares[codigo_localizacion]'>$lugares[codigo_localizacion]</option>";							
							}while($lugares=mysql_fetch_array($rs_lugares));
						}					
						mysql_close($conn);?>
						<option value="NUEVA">Agregar Nueva</option>
					</select>					
				</td>
				<td><div align="right">**C&oacute;digo</div></td>
				<td colspan="3">
					<input type="text" name="txt_codigo" id="txt_codigo" value="" size="40" maxlength="50" readonly="readonly"
					onkeypress="return permite(event, 'num_car',4);" class="caja_de_texto" />			
				</td>
			</tr>
			<tr>
				<td colspan="4"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
				<tr>
				<td colspan="4"><strong>** Datos marcados con doble asterisco son <u>obligatorios</u> dependiendo del tipo de prueba</strong></td>
			</tr>
			<tr>
				<td colspan="4">
					<div align="center">
						<input name="sbt_registrar" id="sbt_registrar" type="submit" class="botones" value="Registrar" title="Guardar Datos de la Muestra" 
						onmouseover="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;
						<input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
						onmouseover="window.status='';return true" />
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" id="btn_regresar" type="button" class="botones" value="Regresar" 
						title="Regresar a la Selecci&oacute;n de Operaci&oacute;n a Realizar" onclick="location.href='frm_gestionarMuestras.php'"/>
					</div>			
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<div id="div-calendario">
		  <input type="image" name="txt_imgFechaColado" id="txt_imgFechaColado" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_registrarMuestra.txt_fechaColado,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar Fecha de Colado"/>
		</div><?php
	}//Cierre else if(!isset($_POST['sbt_continuar']))?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>