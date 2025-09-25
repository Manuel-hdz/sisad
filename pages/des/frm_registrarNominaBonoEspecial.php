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
		include ("op_registrarNomina.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarCatalogoSueldos.js"></script>

    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:298px;height:20px;z-index:11;}
		#tabla-personal {position:absolute;left:30px;top:470px;width:840px;height:200px;z-index:12; overflow:scroll;}
		#tabla-registrarPresupuesto {position:absolute;left:30px;top:190px;width:840px;height:252px;z-index:14;}
		#res-spider {position:absolute;z-index:15;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar N&oacute;mina de Desarrollo</div>
    <?php
	//Verificar si esta definido en el GET para borrarlo
	if (isset($_GET["id_reg"])){
		//Recuperar el elemento a Borrar
		$borrar=$_GET["id_reg"];
		//Borrarlo del arreglo de Sesion
		unset($_SESSION["bonoNomina"][$borrar]);
		//Reorganizar los valores ingresados en el Arreglo de Sesion
		$_SESSION['bonoNomina'] = array_values($_SESSION['bonoNomina']);
		//Verificar si el arreglo de Sesion no tiene valores para borrarlo por completo
		if (count($_SESSION["bonoNomina"])==0)
			unset($_SESSION["bonoNomina"]);
	}
	if(!isset($_POST['sbt_guardar'])){
		if(isset($_POST['sbt_agregar'])){
			//Agregar Registro Bono en Arreglo de Nomina
			$area=$_POST["cmb_area"];
			$nombre=strtoupper($_POST["txt_nombre"]);
			$puesto=$_POST["cmb_puestos"];
			$sueldoB=str_replace(",","",$_POST["txt_sueldoBase"]);
			$fechaI=modFecha($_POST["txt_fechaIni"],3);
			$fechaF=modFecha($_POST["txt_fechaFin"],3);
			$bono=str_replace(",","",$_POST["txt_bono"]);
			$bonoMetros=str_replace(",","",$_POST["txt_bonoMetros"]);
			$sueldoT=str_replace(",","",$_POST["txt_sueldoTotal"]);
			$observaciones=strtoupper($_POST["txa_observaciones"]);
			$rfc=$_POST["hdn_rfc"];
			//Si ya esta definido el arreglo $bonoNomina, entonces agregar el siguiente registro a el
			if(isset($_SESSION['bonoNomina'])){	
				//Verificar si el registro ya esta agregado en el Arreglo
				$ctrl = verificarRegistroBono($nombre,"arr");
				//Si el valor de ctrl es 0, comparar con la Base de Datos que el usuario no este agregado ya
				if ($ctrl==0){
					$ctrl = verificarRegistroBono($nombre,"bd");
					//Si control vale 0, significa que la persona no esta registrada en la SESSION ni en la BD, entonces procedemos a almacenar el registro en la SESSION
					if ($ctrl==0){
						//Guardar los datos en el arreglo
						$bonoNomina[] = array("area"=>$area,"puesto"=>$puesto,"rfc"=>$rfc,"nombre"=>$nombre,"sueldoB"=>$sueldoB,"fechaI"=>$fechaI,"fechaF"=>$fechaF,"bono"=>$bono,"bonoM"=>$bonoMetros,"sueldoT"=>$sueldoT,"observaciones"=>$observaciones);
					}
					else{
						//ctrl=2
						//El Trabajador ya esta registrado en la nómina para las Fechas especificadas
						?>
						<script type="text/javascript" language="javascript">
							setTimeout("mensaje();",1000);							
							function mensaje(){
								alert("El Trabajador <?php echo $txt_nombre;?> ya Tiene Nómina Registrada en las Fechas Seleccionadas");
							}
						</script><?php
					}
				}
				//ctrl=1
				//El trabajador seleccionado ya esta registrado en la SESSION
				else{?>
					<script type="text/javascript" language="javascript">
						setTimeout("mensaje();",1000);							
						function mensaje(){
							alert("El Trabajador <?php echo $txt_nombre;?> ya fue Agregado a la Nómina en Proceso");
						}
					</script><?php
				}
			}
			//Si no esta definido el arreglo $datosEntrada definirlo y agregar el primer registro
			else{		
				//Revisar que el trabajador seleccionado NO este registrado en otra cuadrilla en la Base de Datos
				$ctrl=verificarRegistroBono($nombre,"bd");
				if ($ctrl==0){//Agregar el trabajador a la SESSION
					//Guardar los datos en el arreglo
					$bonoNomina = array(array("area"=>$area,"puesto"=>$puesto,"rfc"=>$rfc,"nombre"=>$nombre,"sueldoB"=>$sueldoB,"fechaI"=>$fechaI,"fechaF"=>$fechaF,"bono"=>$bono,"bonoM"=>$bonoMetros,"sueldoT"=>$sueldoT,"observaciones"=>$observaciones));
					$_SESSION['bonoNomina'] = $bonoNomina;
				}else{ //El trabajador seleccionado ya esta registrado en la Nomina en la Base de Datos?>
					<script type="text/javascript" language="javascript">
						setTimeout("mensaje();",1000);		
						function mensaje(){
							alert("El Trabajador <?php echo $txt_nombre;?> ya Tiene Nómina Registrada en las Fechas Seleccionadas");
						}
					</script><?php
				}
			}
			//Fin de Agregar Registro Bono en Arreglo de Nomina
		}
		?>	
			
		<fieldset class="borde_seccion" id="tabla-registrarPresupuesto" name="tabla-registrarPresupuesto">
		<legend class="titulo_etiqueta">Ingresar Datos de Bono Especial</legend>	
		<br>
		<form onSubmit="return validarFormBonoEspecial(this);" name="frm_registrarBono" method="post" action="frm_registrarNominaBonoEspecial.php">
		<table width="817" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="66"><div align="right">*&Aacute;rea</div></td>
				<td width="120">
				<?php 
					$res=cargarComboTotal("cmb_area","area","area","catalogo_salarios","bd_desarrollo","Área","","txt_sueldoBase.value='',txt_porcActividad.value='',txt_porcMetro.value='';cargarCombo(this.value,'bd_desarrollo','catalogo_salarios','puesto','area','cmb_puestos','Puestos','');activarBono(this.value);quitarSueldoBase()","area","","");
					if ($res==""){
						echo "<label class='msje_correcto'>Ingrese Un &Aacute;rea</label>";
						echo "<input type='hidden' id='cmb_area' name='cmb_area'/>";
						echo "<input type='hidden' id='cmb_puestos' name='cmb_puestos'/>";
					}
				?>
				</td>
				<td width="86"><div align="right">*Trabajador</div></td>
				<td colspan="3">
					<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados','1');" 
					value="" size="60" maxlength="80" onkeypress="return permite(event,'car',0);" tabindex="1"/>
					<div id="res-spider">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<td width="66"><div align="right">*Puesto</div></td>
				<td width="120">
					<?php if ($res!="") {?>
						<select name="cmb_puestos" id="cmb_puestos" onchange="obtenerSueldo(this,cmb_area);setTimeout('sumarSueldoBono()',500);" class="combo_box" tabindex="2">
						<option value="">Puestos</option>
						</select>
					<?php }
						else
						echo "<label class='msje_correcto'>Ingrese Un Puesto</label>";
					?>
			  </td>
				<td><div align="right">Sueldo Base</div></td>
				<td width="101">$ 
			  <input type="text" class="caja_de_num" value="0.00" name="txt_sueldoBase" id="txt_sueldoBase" size="10" readonly="readonly"/></td>
			  <td width="81"><div align="right">N&oacute;mina del</div></td>
				<td width="266">
				<input type="text" class="caja_de_texto" value="<?php echo $_POST["txt_fechaIni"];?>" name="txt_fechaIni" id="txt_fechaIni" size="10" readonly="readonly"/>
				&nbsp;al&nbsp;
			  <input type="text" class="caja_de_texto" value="<?php echo $_POST["txt_fechaFin"];?>" name="txt_fechaFin" id="txt_fechaFin" size="10" readonly="readonly"/></td>
			</tr>
			<tr>
				<td><div align="right">*Bono por Actividades </div></td>
				<td>$ <input type="text" class="caja_de_num" value="0.00" name="txt_bono" id="txt_bono" size="10" maxlength="6" onkeypress="return permite(event,'num',2);" 
						onchange="formatCurrency(this.value,'txt_bono');sumarBonoSueldo(txt_sueldoBase.value,txt_bono.value)" tabindex="3" title="Bono de Trabajo"/>
					<input type="checkbox" name="ckb_catalogoBonos" id="ckb_catalogoBonos" style="visibility:hidden" onclick="abrirVentBono(this,cmb_area.value,txt_nombre.value,txt_fechaIni.value,txt_fechaFin.value,cmb_puestos.value,txt_sueldoBase.value,txt_porcActividad.value);" title="Click para Agregar Bono" tabindex="4"/>
				</td>
				<td><div align="right">*Sueldo Total</div></td>
				<td>$ <input type="text" class="caja_de_num" value="0.00" name="txt_sueldoTotal" id="txt_sueldoTotal" size="10" readonly="readonly"/></td>
				<td><div align="right">Observaciones</div></td>
				<td colspan="3"><textarea class="caja_de_texto" name="txa_observaciones" id="txa_observaciones" cols="30" rows="3" onkeypress="return permite(event,'num_car',0);" maxlength="120" onkeyup="return ismaxlength(this)" tabindex="5"></textarea>
				</td>
			</tr>
			<tr>
				<td><div align="right">Bono por Metros</div></td>
				<td colspan="5">$
					<input type="text" name="txt_bonoMetros" id="txt_bonoMetros" class="caja_de_num" readOnly="readonly" size="10" onkeypress="return permite(event,'num',2);" value="0.00"/>
					<span id="etiqMetrosAvance" class="msje_correcto"></span>
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<div align="center">
						<input name="txt_porcActividad" id="txt_porcActividad" type="hidden"/>
						<input name="txt_porcMetro" id="txt_porcMetro" type="hidden"/>
						<input type="hidden" name="hdn_estado" id="hdn_estado" value="Agregar"/>
						<input type="hidden" name="hdn_accion" id="hdn_accion" value="otro"/>
						<input type="hidden" name="hdn_rfc" id="hdn_rfc"/>
						<?php
						$status="";
						if (!isset($_SESSION["bonoNomina"]))
							$status="disabled='disabled'";
						?>
						<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar N&oacute;mina" 
						onmouseover="window.status='';return true" onclick="hdn_accion.value='guardar'" <?php echo $status;?>/>
						&nbsp;&nbsp;&nbsp;
						<input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar Bono" 
						onmouseover="window.status='';return true" tabindex="6"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar el Formulario" 
						onMouseOver="window.status='';return true" onclick="resetFormNomina();"/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar la N&oacute;mina Actual y volver a Seleccionar otras Fechas" 
						onMouseOver="window.status='';return true" <?php if (isset($_SESSION["bonoNomina"])){?> onclick="confirmarSalida('frm_registrarNomina.php?borrar');" <?php }else {?>onclick="location.href='frm_registrarNomina.php?borrar'"<?php }?>/>
					</div>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<?php
			if (isset($_SESSION["bonoNomina"])){
				echo "<div id='tabla-personal' class='borde_seccion2'>";
				echo "<form method='post' action='frm_registrarNominaBonoEspecial.php' name='frm_borrarRegistroNomina'>";
				echo "<input type='hidden' name='txt_fechaIni' value='$_POST[txt_fechaIni]'/>";
				echo "<input type='hidden' name='txt_fechaFin' value='$_POST[txt_fechaFin]'/>";
				mostrarPersonalNomina($_SESSION["bonoNomina"]);
				echo "</form>";
				echo "</div>";
			}
	}// Fin de if(!isset($_POST['sbt_guardar']))
	else{
		guardarNomina();
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>