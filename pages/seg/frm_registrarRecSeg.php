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
		//Manejo de la funciones para registrar los residuos peligrosos dentro de la bitacora en la BD de Seguridad
		include ("op_registrarRecSeg.php");?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>

    <style type="text/css">
		<!--
		#titulo-regBitacora { position:absolute; left:30px; top:146px; width:268px; height:19px; z-index:11; }
		#tabla-recorridos2 { position:absolute; left:14px; top:322px; width:929px; height:132px; z-index:12; }
		#tabla-recorridos3 { position:absolute; left:18px; top:527px; width:924px; height:132px; z-index:13; overflow:scroll}
		#tabla-recorridos { position:absolute; left:14px; top:192px; width:925px;	height:97px; z-index:16; }
		#fechaIngreso { position:absolute; left:928px; top:220px; width:30px; height:26px; z-index:14; }
		#fechaSalida { position:absolute; left:973px; top:293px; width:30px; height:26px; z-index:15; }
		#botonesBit {position:absolute;left:4px;top:161px;width:957px;height:37px;z-index:17;}
		-->
    </style>
</head>
<body>
	<?php
		//Verificar que el boton agregar esta definido
		if(isset($_POST["sbt_agregar"])){	
			//Si ya esta definido el arreglo, entonces agregar el siguiente registro a el
			if(isset($_SESSION['recorridosSeg'])){			
				//Guardar los datos en el arreglo
				$recorridosSeg[] = array("area"=>strtoupper($_POST['txa_area']), "anomaliaDet"=>strtoupper($_POST['txa_anomaliaDet']),
								   "anomaliaCor"=>strtoupper($_POST['txa_anomaliaCor']), "lugar"=>strtoupper($_POST['txt_lugar']));
			}
			//Si no esta definido el arreglo definirlo y agregar el primer registro
			else{			
					//Guardar los datos en el arreglo
					$recorridosSeg = array( array("area"=>strtoupper($_POST['txa_area']), "anomaliaDet"=>strtoupper($_POST['txa_anomaliaDet']),
								   "anomaliaCor"=>strtoupper($_POST['txa_anomaliaCor']), "lugar"=>strtoupper($_POST['txt_lugar'])));
					$_SESSION['recorridosSeg'] = $recorridosSeg;	
			}	
		}
		
		
		//Verificar que este definido el Arreglo de fotos, si es asi, lo mostramos en el formulario
		if (isset($_SESSION["recorridosSeg"])){
			echo "<div id='tabla-recorridos3' class='borde_seccion2'>";
			mostrarRegRecorridos($recorridosSeg);
			echo "</div>";
		}
		
		
	//Comprobamos si existe la sesion para asignar los datos generales de lainformacion de los recorridos
	if(!isset($_SESSION['recorridosSeg'])){
		$claveReg = obtenerIdRS();
		$responsable = "";
		$observaciones = "";
		$departamentos = "";
		$atributo = "";
		$fecha = date("d/m/Y"); 
	}
	if(isset($_POST['sbt_agregar'])&&!isset($_SESSION['recorridosPrinc'])){
		$claveReg = $_POST['txt_clave'];
		$responsable = strtoupper($_POST['txt_responsable']);
		$observaciones = strtoupper($_POST['txa_observaciones']);
		$departamentos = $_POST['txt_ubicacion'];
		$atributo = "readonly='readonly'";
		$fecha = $_POST['txt_fecha'];
		$_SESSION['recorridosPrinc'] = array("claveReg"=>$claveReg,"responsable"=>$responsable,"observaciones"=>$observaciones,"departamentos"=>$departamentos,
		"atributo"=>$atributo, "fecha"=>$fecha);
	}
	if(isset($_SESSION['recorridosPrinc'])){
		$claveReg = $_SESSION['recorridosPrinc']['claveReg'];
		$responsable = strtoupper($_SESSION['recorridosPrinc']['responsable']);
		$observaciones = strtoupper($_SESSION['recorridosPrinc']['observaciones']);
		$departamentos = $_SESSION['recorridosPrinc']['departamentos'];
		$atributo = $_SESSION['recorridosPrinc']['atributo'];
		$fecha = $_SESSION['recorridosPrinc']['fecha'];
	}
	
				
	?>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-regBitacora">Registrar Recorridos Seguridad </div>

	<form onsubmit="return valFormRegRecorridosSeguridad(this);"name="frm_regRecSeg" method="post" action="frm_registrarRecSeg.php">
	<fieldset id="tabla-recorridos" class="borde_seccion">
	<legend class="titulo_etiqueta">Ingresar Informaci&oacute;n de los Recorridos </legend>	
	<table width="94%" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
	  	  <td width="7%"><div align="right">Clave</div></td>
		 	<td width="12%"><input name="txt_clave" class="caja_de_texto" id="txt_clave" size="10" maxlength="10" 
			value="<?php echo $claveReg;?>" readonly="readonly"  type="text"  /></td>
		  <td width="12%"><div align="right">*Responsable</div></td>
		  <td width="35%"><input name="txt_responsable" class="caja_de_texto" id="txt_responsable"  size="60" maxlength="60"  value="<?php echo $responsable;?>"
		  onkeypress="return permite(event,'car',2);"  type="text" <?php echo $atributo;?>/></td>
		  <td width="11%"><div align="right">*Fecha</div></td>
		  <td width="23%"><input name="txt_fecha" id="txt_fecha" class="caja_de_texto" size="10"
            	value="<?php echo $fecha?>" 
    	        readonly="readonly"  type="text"  /></td>
		</tr>
		<tr>
			<td><div align="right">*Observaciones</div></td>
			<td><textarea name="txa_observaciones" id="txa_observaciones" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);" <?php echo $atributo;?>><?php echo $observaciones;?></textarea></td>
			<td><div align="right">*Departamentos</div></td>
			<td colspan="3"><input name="txt_ubicacion" id="txt_ubicacion" type="text" class="caja_de_texto" size="60" readonly="readonly" 
				onclick="window.open('verDepartamentos.php','_blank','top=50, left=50, width=380, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar Departamentos" value="<?php echo  $departamentos;?>"/></td>
		</tr>
	</table>
</fieldset>
	
	<fieldset id="tabla-recorridos2" class="borde_seccion">
	<legend class="titulo_etiqueta">Registrar Informaci&oacute;n de los Recorridos de Seguridad </legend>
 	<table width="933" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  <td width="73"><div align="right">*&Aacute;rea</div></td>
		  <td width="203"><textarea name="txa_area" id="txa_area" maxlength="100" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea></td>
		  <td width="116"><div align="right">*Anomal&iacute;a Detectada </div></td>
		  <td rowspan="3"><textarea name="txa_anomaliaDet" id="txa_anomaliaDet" maxlength="700" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="5" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea></td>
		  <td width="104"><div align="right">*Correcci&oacute;n Anomal&iacute;a </div></td>
		  <td rowspan="3"><textarea name="txa_anomaliaCor" id="txa_anomaliaCor" maxlength="700" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="5" cols="30"
				onkeypress="return permite(event,'num_car', 0);"></textarea></td>
		</tr>	
		<tr>
		  <td width="73"><div align="right">*Lugar</div></td>
		  <td width="203"><input name="txt_lugar" class="caja_de_texto" id="txt_lugar"  size="40" maxlength="80"  
		  onkeypress="return permite(event,'num_car',8);"  type="text"/></td>
		</tr>
		<tr>
			<td colspan="3"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
		</tr>	
	</table>
	<div align="center" id="botonesBit">
		<tr>
            <td colspan="6">
                <div align="center">
					<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value=""/>
					<?php if(isset($_SESSION['recorridosSeg'])){?>
	                  	<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Finalizar" 
					 	title="Finalizar Registro de Recorridos de Seguridad" onmouseover="window.status='';return true" onclick="hdn_botonSel.value='finalizar'" />
	                	&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" 
				 	title="Agregar Registro Recorridos de Seguridad" onmouseover="window.status='';return true" onclick="hdn_botonSel.value='agregar'"/>
                  	&nbsp;&nbsp;&nbsp;
					<input name="btn_limpiar" type="button" class="botones" value="Limpiar" id="btn_limpiar" title="Limpia el Formulario" 
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Cancelar" title="Cancelar Registro  y Regresar Al Men&uacute; Recorridos Seguridad" 
					onclick="confirmarSalida('menu_recorridosSeguridad.php?cancel=<?php echo obtenerIdRS();?>');" onmouseover="window.status='';return true" />
              </div>			
			</td>
		</tr>
</div>
</fieldset>


    </form>
	<?php if(!isset($_SESSION['recorridosSeg'])){?>
		<div id="fechaIngreso">
       		<input type="image" name="txt_fecha" id="txt_fecha" src="../../images/calendar.png"
	        onclick="displayCalendar(document.frm_regRecSeg.txt_fecha,'dd/mm/yyyy',this)" 
    	    onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        	title="Seleccionar Fecha de Ingreso"/> 
		</div>
	<?php }?>


</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>