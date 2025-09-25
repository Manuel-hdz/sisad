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
		include("op_registrarServicios.php");	
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:309px; height:20px; z-index:11; }
		#tabla-agregarServicio { position:absolute; left:30px; top:190px; width:681px; height:235px; z-index:12; padding:15px; padding-top:0px;}
		#calendario {position:absolute;left:239px;top:233px;width:30px;height:26px;z-index:13;}
		#tabla-resultados {position:absolute;left:30px;top:450px;width:681px;height:200px;z-index:14;overflow:scroll;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Registrar Servicios con Minera Fresnillo </div>
	
	<?php
	if(!isset($_POST['sbt_guardar'])){
		if(isset($_POST['sbt_agregar'])){
			$id=$_POST["hdn_id"];
			$fecha=$_POST["txt_fecha"];
			$categoria=$_POST["cmb_categoria"];
			$actividad=strtoupper($_POST["txa_actividad"]);
			$turnOf=$_POST["txt_turnosOf"];
			$turnAy=$_POST["txt_turnosAy"];
			//Si ya esta definido el arreglo $registroServicios, entonces agregar el siguiente registro a el
			if(isset($_SESSION['registroServicios'])){
				//Guardar los datos en el arreglo
				$registroServicios[] = array("id"=>$id,"fecha"=>$fecha,"categoria"=>$categoria,"actividad"=>$actividad,"turnOf"=>$turnOf,"turnAy"=>$turnAy);
			}
			//Si no esta definido el arreglo $registroServicios definirlo y agregar el primer registro
			else{		
				//Guardar los datos en el arreglo
				$registroServicios = array(array("id"=>$id,"fecha"=>$fecha,"categoria"=>$categoria,"actividad"=>$actividad,"turnOf"=>$turnOf,"turnAy"=>$turnAy));
				$_SESSION['registroServicios'] = $registroServicios;
			}
		}
	}
	else{
		//Guardar en la Base de Datos
		registrarServicios();
	}
	if (!isset($_POST["sbt_agregar"]))
		$id=obtenerId();
	else
		$id=$_POST["hdn_id"]+1;
	?>
	
	<fieldset class="borde_seccion" id="tabla-agregarServicio" name="tabla-agregarServicio">
	<legend class="titulo_etiqueta">Ingresar Datos del Servicio </legend>	
	<br>
	<form onSubmit="return valFormRegServicios(this);" name="frm_registrarServicio" method="post" action="frm_registrarServicios.php">
    <table width="684" height="216" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
  			<td width="83"><div align="right">Fecha</div></td>
			<td width="172">
				<input type="text" name="txt_fecha" id="txt_fecha" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" 
				value="<?php echo date("d/m/Y"); ?>"/>
				<input type="hidden" name="hdn_id" id="hdn_id" value="<?php echo $id?>"/>
			</td>
			<td width="98"><div align="right">*Categor&iacute;a</div></td>
			<td width="264">
				<select name="cmb_categoria" id="cmb_categoria" class="combo_box" onchange="activarTurnosAdmon(this.value);">
					<option selected="selected" value="">Categor&iacute;a</option>
					<option value="OFICIAL">OFICIAL</option>
					<option value="AYUDANTE GENERAL">AYUDANTE GENERAL</option>
					<option value="AMBOS">AMBOS</option>
				</select>
	  		</td>
		</tr>
		<tr>
	  		<td rowspan="2"><div align="right">*Actividad</div></td>
			<td rowspan="2">
				<textarea name="txa_actividad" id="txa_actividad" maxlength="160" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="3" cols="30"
           	 	onkeypress="return permite(event,'num_car', 0);" ></textarea>
			</td>
			<td><div align="right">**Turnos Oficial</div></td>
	  		<td>
				<input name="txt_turnosOf" id="txt_turnosOf" type="text" class="caja_de_texto" size="5" maxlength="5" value="0" 
				onkeypress="return permite(event,'num', 2);" onchange="formatCurrency(this.value,'txt_turnosOf');" readonly="readonly"/>
				<input type="hidden" name="hdn_revisarOf" id="hdn_revisarOf" value="no"/>
			</td>
		</tr>
		<tr>
			<td><div align="right">**Turnos Ayudante</div></td>
	  		<td>
				<input name="txt_turnosAy" id="txt_turnosAy" type="text" class="caja_de_texto" size="5" maxlength="5" value="0" 
				onkeypress="return permite(event,'num', 2);" 			readonly="readonly"  onchange="formatCurrency(this.value,'txt_turnosAy');" />
				<input type="hidden" name="hdn_revisarAy" id="hdn_revisarAy" value="no"/>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<strong>
					*Los campos marcados con asterisco (*) son <u>obligatorios.</u><br>
					**Los campos marcados con doble asterisco (**) son <u>obligatorios.</u> Dependiendo lo seleccionado.
				</strong>
			</td>
		</tr>
		<tr>
			<td colspan="4">
				<div align="center">  
					<input type="hidden" name="hdn_validar" id="hdn_validar" value="si"/>
					<?php if (isset($_SESSION["registroServicios"])){?>
					<input name="sbt_guardar"  id="sbt_guardar" type="submit" class="botones"  value="Guardar" title="Guardar los Datos Registrados" 
					onMouseOver="window.status='';return true" onclick="hdn_validar.value='no';"/>
					&nbsp;&nbsp;&nbsp;
					<?php }?>
					<input name="sbt_agregar"  id="sbt_agregar" type="submit" class="botones"  value="Agregar" title="Agregar los Datos al Registro" 
					onMouseOver="window.status='';return true" />
					&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true" 
					onclick='txt_turnosOf.readOnly=true;txt_turnosAy.readOnly=true;'/> 
					&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Servicios" 
					onMouseOver="window.status='';return true" onclick="confirmarSalida('menu_servicios.php?borrar')" />
				</div>		
			</td>
		</tr>
	</table>
	</form>
	</fieldset>

	<div id="calendario">
		<input type="image" name="fecha" id="fecha_registro" src="../../images/calendar.png"
		onclick="displayCalendar(document.frm_registrarServicio.txt_fecha,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" title="Seleccionar Fecha de Realizaci&oacute;n del Servicio"/> 
	</div>

	<?php 
	if(isset($_SESSION["registroServicios"])){?>
		<div id="tabla-resultados" class="borde_seccion2">
			<?php
			mostrarServicios($_SESSION["registroServicios"]);
			?>
		</div>
	<?php }?>

</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>