<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");		
		include ("op_registrarBitacora.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-registrar {	position:absolute;	left:30px;	top:146px;	width:212px;	height:20px;	z-index:11;}
			#tabla-registrarBitacora {	position:absolute;	left:30px;	top:190px;	width:904px;	height:50px;	z-index:12;	padding:15px;	padding-top:0px;}
			#tabla-registrarMecanicos {	position:absolute;	left:30px;	top:272px;	width:426px;	height:227px;	z-index:15; }
			#tabla-mostrarMecanico    {	position:absolute;	left:500px;	top:277px;	width:426px;	height:201px;	z-index:17;	overflow:scroll	}
			#btns-regpdf {	position:absolute;	left:270px;	top:336px;	width:397px;	height:43px;	z-index:12;	padding:16px;	padding-top:0px;}					
		-->
    </style>
</head>
<body>	
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Complementar Bit&aacute;cora</div><?php

	//Cuando se entra a esta página desde el registro de Bitácora de Mtto Preventivo o Correctivo, obtenemos el No. de Mecánico de la SESSION o 
	//colocamos 1 en el caso de que no exista
	$cont = 0;
	if(!isset($_POST["sbt_agregarMec"])){
		if(!isset($_SESSION["mecanicos"]))
			$cont = 1;
		else//De lo contrario si se vuelve a entrar a agregar otro mecanico, para conservar la partida se cuenta el arreglo y se le agrega uno
			$cont = count($_SESSION["mecanicos"])+1;			
	}
	
	//Si el boton viene definido se incrementa la partida
	if(isset($_POST["sbt_agregarMec"])){
		$cont = $_POST["txt_partidaMec"]+1;
	}?>				
	
	
	<form onSubmit="return valFormComplementarMecanico(this);" name="frm_complementarMecanico" method="post">	
 	<fieldset class="borde_seccion" id="tabla-registrarBitacora" name="tabla-registrarBitacora">
		<legend class="titulo_etiqueta">Registrar Bit&aacute;cora</legend>	
		<table width="879" height="42" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr><?php //Decidimos que tipo de mantenimiento para mostrar los datos clave bitacora, clave equipo y orden de trabajo 
				if(($_POST["txt_tipoMant"])=="CORRECTIVO"){?>
					<td width="107"><div align="right">Clave Bit&aacute;cora </div></td>
					<td width="88">
						<input name="txt_claveBitacora" id="txt_claveBitacora" type="text" class="caja_de_texto" size="15" maxlength="13" 
						value="<?php echo $_POST["txt_claveBitacora"]; ?>" readonly="readonly" />
					</td>
					<td width="158"><div align="right">Clave del Equipo </div></td>
					<td width="109">
						<input name="cmb_claveEquipo" id="cmb_claveEquipo" type="text" class="caja_de_texto" size="15" maxlength="13"  
						value="<?php echo $_POST['cmb_claveEquipo'];?>" readonly="readonly"/>
					</td>
					<td width="166"><div align="right">Clave Orden de Trabajo </div></td>
					<td width="109">
						<input name="txt_claveOrdenTrabajo" id="txt_claveOrdenTrabajo" type="text" class="caja_de_texto" size="15" maxlength="13" 
						value="<?php echo $_POST['txt_claveOrdenTrabajo'];?>" readonly="readonly"/>
					</td><?php 
				}
				else{//De lo contrario selecciona los botones del mantenimiento preventivo ?>
					<td width="107"><div align="right">Clave Bit&aacute;cora </div></td>
					<td width="88">
						<input name="txt_claveBitacora" id="txt_claveBitacora" type="text" class="caja_de_texto" size="15" maxlength="13" 
						value="<?php echo $_POST["txt_claveBitacora"]; ?>" readonly="readonly" /> 
					</td>
					<td width="158"><div align="right">Clave del Equipo </div></td>
					<td width="109">
						<input name="txt_claveEquipo" id="txt_claveEquipo" type="text" class="caja_de_texto" size="15" maxlength="13" 
						value="<?php echo $_POST['txt_claveEquipo'];?>" readonly="readonly"/>
					</td>
					<td width="166"><div align="right">Clave Orden de Trabajo </div></td>
					<td width="109">
						<input name="txt_ot" id="txt_ot" type="text" class="caja_de_texto" size="15" maxlength="13"  value="<?php echo $_POST['txt_ot'];?>" 
						readonly="readonly"/>
					</td><?php 
				}?>
			</tr>
		</table>
 	</fieldset>

   
    <fieldset class="borde_seccion" id="tabla-registrarMecanicos" name="tabla-registrarMecanicos">	
		<legend class="titulo_etiqueta">Registrar Mec&aacute;nicos</legend>
		<br/>
		<table width="429" height="124" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="44"><div align="right">Partida</div></td>
				<td width="10">
					<input name="txt_partidaMec" id="txt_partidaMec" type="text" class="caja_de_num" size="2" value="<?php echo $cont;?>" maxlength="50" 
					onkeypress="return permite(event,'num_car', 0);" readonly="readonly"/>
				</td>
				<td width="108" height="70"><div align="right">Nombre Mec&aacute;nico </div></td>
				<td width="200" colspan="2">
					<input name="txt_mecanico" id="txt_mecanico" type="text" class="caja_de_texto" size="40" maxlength="40" 
					onkeypress="return permite(event,'num_car', 1);" />
				</td>
			</tr>
		</table>
		<p align="center">
			<input type="hidden" name="txt_tipoMant" value="<?php echo $_POST["txt_tipoMant"];?>"/>
			<input name="sbt_agregarMec" type="submit" class="botones"  value="Agregar" title="Complementar Bitacora" onmouseover="window.status='';return true" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
			&nbsp;&nbsp;&nbsp;&nbsp;<?php 
			if(($_POST["txt_tipoMant"])=="CORRECTIVO"){?>
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar Registro de Bitacora" 
				onmouseover="window.status='';return true" onclick="location.href='frm_bitacoraMttoCorrectivo.php?cancel=si'" id="btn_cancelar" /><?php 
			}
			else{?>
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar Registro de Bitacora" 
				onmouseover="window.status='';return true" onclick="location.href='frm_bitacoraMttoPreventivo.php?cancel=si'" id="btn_cancelar" /><?php 
			}?>
		</p>
		
		<div id="btns-regpdf">
		<table width="396" height="69" align="center" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td>
					<div align="center"><?php 
						if(($_POST["txt_tipoMant"])=="CORRECTIVO"){?>
							<input name="btn_regMatMant" type="button" class="botones"  value="Finalizar" title="Registrar Materiales de Mantenimiento" 
							onclick="location.href='frm_bitacoraMttoCorrectivo.php'" onmouseover="window.status='';return true"/><?php 
						}
						else{?>
							<input name="btn_regMatMant" type="button" class="botones"  value="Finalizar" title="Registrar Materiales de Mantenimiento" 
							onclick="location.href='frm_bitacoraMttoPreventivo.php'" onmouseover="window.status='';return true"/><?php 
						}?>
					</div>			 
				</td>
			</tr>
		</table>
		</div>		
  	</fieldset><?php
	
	//Verificar que en el POST esten los datos definidos a fin de agregarlos al arreglo de Sesion
	if (isset($_POST["txt_mecanico"])){
		//Si ya esta definido el arreglo $mecanicos, entonces agregar el siguiente registro a el
		if(isset($_SESSION['mecanicos'])){			
			//Guardar los datos en el arreglo
			$mecanicos[] = array("partida"=>($txt_partidaMec), "mecanico"=>strtoupper($txt_mecanico));
		}
		//Si no esta definido el arreglo $mecanicos definirlo y agregar el primer registro
		else{			
			//Guardar los datos en el arreglo
			$mecanicos = array(array("partida"=>($txt_partidaMec),"mecanico"=>strtoupper($txt_mecanico)));
			$_SESSION['mecanicos'] = $mecanicos;	
		}	
	}
	
	//Verificar que este definido el Arreglo de Mecanicos, si es asi, lo mostramos en el formulario
	if (isset($_SESSION["mecanicos"])){
		echo "<div id='tabla-mostrarMecanico' class='borde_seccion2'>";
		mostrarMecanicos($mecanicos);
		echo "</div>";
	}?>
  	</form>  
	
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>