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
		include ("op_agregarEquipo.php");?> 	

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;width:316px;height:20px;z-index:11;}
		#tabla-agregarRefacciones {position:absolute;left:30px;top:190px;width:500px;height:227px;z-index:12;}
		#refaccionesAgregadas {position:absolute;left:32px;top:456px;width:710px;height:161px;z-index:12; overflow:scroll}
		-->
    </style>
</head>
<body><?php
	$mens="";
	
	//Verificamos que se haya pulsado el boton de agregar otro para proceder a cargar los datos en el arreglo de sesion
	if(isset($_POST['sbt_agregar'])){
		//Si esta definido el arreglo, añadir el siguiente elemento a el	
		if(isset($_SESSION['refacciones'])){
			$refacciones[] = array ("id_equipo"=>$_POST['txt_clave'], "nom_refaccion"=>strtoupper($_POST['txt_nomRefaccion']), 
			"descripcion"=>strtoupper($_POST['txa_descripcion']));
			//Guardar los datos en la SESSION
			$_SESSION['refacciones'] = $refacciones;
		}				
		else{//Si no esta definido el arreglo, definirlo
			//Crear el arreglo con las refacciones del equipo
			$refacciones = array(array ("id_equipo"=>$_POST['txt_clave'], "nom_refaccion"=>strtoupper($_POST['txt_nomRefaccion']), 
			"descripcion"=>strtoupper($_POST['txa_descripcion'])));
			//Guardar los datos en la SESSION
			$_SESSION['refacciones'] = $refacciones;
		}
	}
    if(isset($_GET['id']))
		$clave=$_GET['id'];?>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Refacciones de Equipos</div>
    <fieldset class="borde_seccion" id="tabla-agregarRefacciones" name="tabla-agregarRefacciones">
	<legend class="titulo_etiqueta">Agregar Refacci&oacute;n </legend>	
	<br><?php
	if(isset($_GET['addmod'])){?>
        <form onSubmit="return valFormAgregarRefacciones(this);" name="frm_agregarRefacciones" method="post" 
        action="frm_agregarRefacciones.php?id=<?php echo $_GET['id'];?>&addmod"><?php
	}
	else { ?>	
        <form onSubmit="return valFormAgregarRefacciones(this);" name="frm_agregarRefacciones" method="post" 
        action="frm_agregarRefacciones.php?id=<?php echo $_GET['id'];?>"><?php
	}?>	
        
    <table width="499" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="126"><div align="right">Id Equipo</div></td>
            <td width="336"><input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="15" maxlength="13" readonly="readonly" 
                value="<?php echo $clave; ?>"/>
        </td>
        <tr>
            <td><div align="right">*Nombre Refacci&oacute;n</div></td>
            <td><input name="txt_nomRefaccion" id="txt_nomRefaccion" type="text" class="caja_de_texto" size="40" maxlength="40" value=""  
            	onkeypress="return permite(event,'num_car',1);"/>
            </td>
        </tr>
    	<tr>
            <td><div align="right">*Descripci&oacute;n</div></td>
            <td><textarea name="txa_descripcion" id="txa_descripcion" cols="45" rows="2" maxlength="120" onkeypress="return permite(event,'num_car',0);" 
                onkeyup="return ismaxlength(this)" class="caja_de_texto"></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="4"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>  
        <tr>
        	<td colspan="2" align="center">
                <input name="sbt_agregar" type="submit" class="botones" value="Agregar" onmouseover="window.status='';return true;" 
                title="Agregar Refacción al Equipo <?php echo $clave;?>"/>
                &nbsp;&nbsp;&nbsp;<?php
				if(isset($_SESSION['refacciones'])){?> 
                    <input name="btn_finalizar" type="button" class="botones" value="Finalizar"
                    onclick="location.href='frm_agregarRefacciones.php?id=<?php echo $clave;?>&btn_finalizar'" title="Guardar las Refacciones Agregadas"/><?php
                }?>
                &nbsp;&nbsp;&nbsp;<?php
                //Verificar de donde se llego a esta pagina para asignar el respectivo Boton de Cancelar
				if (isset($_GET['addmod'])){?>
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
					onclick="confirmarSalida('frm_modificarRefacciones.php?id_equipo=<?php echo $clave;?>&cancelarRefac');" 
					title="Cancelar y Registros y Volver a Modificar Refacciones"/><?php
				 }
				else{?>
                    <input name="btn_cancelar" type="button" class="botones" value="Cancelar" 
                    onclick="confirmarSalida('frm_equipoAgregado.php?id_eq=<?php echo $clave;?>&cancelarRefac');" title="Cancelar y Volver al Equipo Agregado"/><?php 
				}?>
          </td>
        </tr>
	</table>    
    </form>
    </fieldset><?php 
	//Si esta inicializado el arreglo de Sesion, mostrar los conceptos agregados a el
	if(isset($_SESSION['refacciones'])){?>
		<div id='refaccionesAgregadas' class='borde_seccion2'><?php
			mostrarRefaccionesReg();?>
		</div><?php
	}
    
	//Si esta se ha presionado el boton finalizar proceder a guardar los datos almacenados en la sesion
	if(isset($_GET['btn_finalizar'])){
		agregarRefacciones();
	} ?>

</body>

<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>