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
		include ("op_registrarListaMaestraDoc.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="includes/ajax/buscarClausula.js"></script>
	<script type="text/javascript" src="../../includes/validacionAseguramiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;	width:352px;height:20px;z-index:11;}
		#tabla-agregarRegistro {position:absolute;left:30px;top:190px;width:764px;height:121px;z-index:12;}
		#tabla-agregarRegistro2 {position:absolute;left:32px;top:349px;width:764px;height:170px;z-index:12;}
		#calendario{position:absolute;left:687px;top:284px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>
<?php
	/* Esta página maneja diversos escenarios para lo cual se realiza la siguiente explicacion con los posibles casos
	1.- Cuando se presiono el botón guardar y no existe el arregloo de sesion lista maestra (el cual guarda el valor de los formatos) se guardara el valor del post
		para evitar que el usuario vuelva a capturarlo.
		*Al guardar el registro se activara un arreglo de sesion que se llama lista; este permitira enviar un mensaje indicando que el arreglo fue guardado
		correctamente. 
		*Al mostrar el mensaje el arreglo lista sera dado de baja para para poder indicar nuevamente cuando se guardo otro registro.
	2.- Si se guardo el registro con exito los campos relacionados con manual de calidad, Así mismo se creara el arreglo de sesion bandera en el cual se guardara 
		el valor del post clave y que permitira poner las cajas de texto correspondientes a manual de calidad apareceran como solo lectura y el arreglo de session
		lista maestra sera dado de baja. Se quedara en la misma pantalla para permitir el registro de las otras clausulas*/
		
	//Comprobar si existe el boton guardar; de no existir queda por entendido que se entra por primera vs a la página; entonces se dan de baja los arreglos de sesion
	if(!isset($_POST['sbt_guardar'])){
		//Comprobamos si existe el arreglo lista que funciona como una bandera para indicar que se agregaron los registros de formatos
		if(isset($_SESSION['lista'])){
			//Damos de baja el arreglo
			unset($_SESSION['lista']);
		}
		//Comprobamos si existe la lista maestra 
		if(isset($_SESSION['lista_maestra'])){
			//Liberamos la session lista maestra
			unset($_SESSION['lista_maestra']);
		}
		//Comprobamos si existe la sesion bandera
		if(isset($_SESSION['bandera'])){
			//Liberamos el arreglo de sesion
			unset($_SESSION['bandera']);
		}
	}//Cierre if(!isset($_POST['sbt_guardar']))
	
	//Comprobamos que exista la sesion lista maestra o lista para guardar el valor de la clave del manual en la sesion bandera
	if(isset($_SESSION["lista_maestra"])||isset($_SESSION["lista"])){
		//Guardamos el valor de la caja de texto en la sesion bandera
		$_SESSION['bandera']=$_POST['txt_claveManual'];
		//Liberamos el arreglo lista maestra; esto indica que se guardo un registro y se tiene que limpiar la sesion para almacenar nuevos formatos
		unset($_SESSION["lista_maestra"]);
	}
	
	/*Verificamos que exista el boton guardar; de ser asi asignar a las variables los valores correspondientes. Considerando los siguientes casos*/		
	if(isset($_POST['sbt_guardar'])){
		$claveManual=$_POST["txt_claveManual"];
		$tituloManual=$_POST["txa_tituloManual"];
		$noRevManu=$_POST["txt_noRevManu"];
		$claveClausula=$_POST["txt_claveClausula"];
		$tituloClausula=$_POST["txa_tituloClausula"];
		$fecha=$_POST["txt_fecha"];
	}
	else{
		$claveManual="";
		$tituloManual="";
		$noRevManu="";
		$claveClausula="";
		$tituloClausula="";
		$fecha="";
	}
?>
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar Lista Maestra Documentos </div>
	<form action="frm_registrarListaMaestraDoc.php" method="post"name="frm_agregarRegistro" id="frm_agregarRegistro" onsubmit="return valFormRegFormLista(this);">
	<fieldset class="borde_seccion" id="tabla-agregarRegistro" name="tabla-agregarRegistro">
    <legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Manual </legend>
    <br />
    <table width="764" height="89"  cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
        	<td width="131" height="31"><div align="right">*Clave Manual </div></td>
         	<td width="212">
		  		<input name="txt_claveManual"  onkeypress="return permite(event,'num_car', 1);" id="txt_claveManual" type="text" class="caja_de_texto" size="10" 
				value="<?php echo $claveManual; ?>" <?php if(isset($_SESSION['bandera'])){?> readonly="readonly" <?php } if(!isset($_SESSION['bandera'])){?>
				onblur="return verificarDatoBD(this,'bd_aseguramiento','manual_calidad','id_manual','nombre');"<?php }?>/>
				<?php if(!isset($_POST['bandera'])){?>
					<span id='error' class="msj_error" >Clave Duplicada</span>
				<?php }?>
			</td>
          	<td><div align="right">*Titulo </div></td>
          	<td width="197">
		  		<textarea name="txa_tituloManual" id="txa_tituloManual" maxlength="60" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" 
				cols="30"onkeypress="return permite(event,'num_car', 0);" <?php if(isset($_SESSION['bandera'])){?> 
				readonly="readonly"<?php }?>><?php echo $tituloManual; ?></textarea>
			</td>
        </tr>
        <tr>
        	<td><div align="right">*No de Revisi&oacute;n </div></td>
          	<td>
				<input name="txt_noRevManu" id="txt_noRevManu" <?php if(isset($_SESSION['bandera'])){?> readonly="readonly"<?php }?>type="text" 
				class="caja_de_texto" size="4" onkeypress="return permite(event,'num', 2);" value="<?php echo $noRevManu; ?>"/>
			</td>
          	<td width="157"><div align="right">*Fecha</div></td>
          	<td width="197">
				<input name="txt_fecha" type="text" id="txt_fecha" size="10" maxlength="15" 
				value="<?php  if(!isset($_POST["txt_fecha"])){echo date("d/m/Y");} else{ echo $fecha;}?>" readonly="readonly"/>
			</td>
        </tr>
    </table>
	</fieldset>
	<fieldset class="borde_seccion" id="tabla-agregarRegistro2" name="tabla-agregarRegistro2">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n de la Clausula </legend>
    <br />
    <table width="764" height="134"  cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
        	<td width="131" height="31"><div align="right">*Clave Clausula </div></td>
          	<td width="212">
		  		<input name="txt_claveClausula" id="txt_claveClausula" type="text" class="caja_de_texto" size="10" 
		  		onkeypress="return permite(event,'num_car', 1);" value="<?php echo $claveClausula; ?>"
				<?php if(isset($_SESSION['bandera'])){?>onblur="return verificarClausula(this,'<?php echo $_SESSION['bandera'];?>');"<?php }?>/>
          		<span id='errorClausula' class="msj_error">Clave Duplicada</span>			
			</td>
        	<td width="157"><div align="right">*Titulo Clausula </div></td>
        	<td width="197">	
		  		<textarea name="txa_tituloClausula" id="txa_tituloClausula" maxlength="160" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" 
				cols="30"onkeypress="return permite(event,'num_car', 0);"><?php echo $tituloClausula; ?></textarea>		
			</td>
        </tr>
        <tr>
        	<td colspan="3"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>
        <tr>
        	<td colspan="4">
				<div align="center">
		   			<input name="btn_regFormato" type="button" class="botones_largos" id= "btn_regFormato" value="Registrar Formatos" title="Registrar Formatos" 
					onmouseover="window.status='';return true" onclick="envioDatosGet();" />
		   			&nbsp;&nbsp;&nbsp;
            		<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" 
					title="Guardar Registro De Lista Maestra" onmouseover="window.status='';return true" />
            		&nbsp;&nbsp;&nbsp;
            		<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
            		&nbsp;&nbsp;&nbsp;
            		<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Repositorio"
					onmouseover="window.status='';return true"  onclick="confirmarSalida('menu_listaDocumentos.php')" />
            		<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/>
          		</div>
			</td>
        </tr>
      </table>
	</fieldset>   
	</form>
	<div id="calendario">
		<input name="calendario" type="image" id="calendario2" onclick="displayCalendar (document.frm_agregarRegistro.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" <?php if(isset($_SESSION['bandera'])){?> style="visibility:hidden"<?php }?> src="../../images/calendar.png"  
		title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0" />
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>