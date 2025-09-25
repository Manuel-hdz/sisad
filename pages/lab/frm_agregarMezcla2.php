<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php


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
		include ("op_agregarMezcla.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
    <script type="text/javascript" src="../../includes/ajax/cargarCombo.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-agregarMezcla {position:absolute;left:30px;top:190px;width:914px;height:240px;z-index:14;}
		#materialesAgregados {position:absolute;left:32px;top:474px;width:914px;height:161px;z-index:12;overflow:scroll}
		-->
    </style>
</head>
<body><?php 

	//Guardar en la SESSION los datos generales de la Mezcla registrados en la pantalla de Agregar Mezcla
	if(isset($_POST['sbt_continuar'])){
	
		$idMezcla = strtoupper($_POST["txt_idMezcla"]);
		$nomMezcla = strtoupper($_POST["txt_nombreMezcla"]);
		$expediente= strtoupper($_POST["txt_expediente"]);
		$eqMezclado = strtoupper($_POST["txt_eqMezclado"]);
		$fechaRegistro = modFecha($_POST["txt_fechaRegistro"],3);

		//Si no esta definido el arreglo, definirlo
		//Crear el arreglo con los datos generales
		$mezclaGral = array("idMezcla"=>$idMezcla, "nomMezcla"=>$nomMezcla, "expediente"=>$expediente, "eqMezclado"=>$eqMezclado, "fechaReg"=>$fechaRegistro);
		//Guardar los datos en la SESSION
		$_SESSION['mezclaGral'] = $mezclaGral; 
	}
	
	
	//Verificamos que se haya pulsado el boton de agregar otro para proceder a cargar los datos en el arreglo de sesion
	if(isset($_POST['sbt_agregar'])){
		//Guardar el Numero tal y como lo formate la caja de texto con la funcion formatNumDecimalLab() de JavaScript
		$cantidad = $_POST['txt_cantidad'];
		//Verificar que el registro no este duplicado 	
		$repetido = 0;
		if(isset($_SESSION['materiales'])){
			foreach($_SESSION["materiales"] as $ind => $registro){
				if($_POST["cmb_nombre"]==$registro["claveMat"]){
					$repetido = 1;
					break;
				}
			}	
		}//Cierre if(isset($_SESSION['materiales']))
			
		//Si el registro no esta 
		if($repetido==0){
			//Si esta definido el arreglo, añadir el siguiente elemento a el	
			$materiales[] = array ("claveMat"=>$_POST['cmb_nombre'], "categoria"=>$_POST['cmb_categoria'], "cantidad"=>$cantidad, 
			"unidad"=>strtoupper($_POST['txt_unidadMedida']));
			//Guardar los datos en la SESSION
			$_SESSION['materiales'] = $materiales;	
		}				
		else{?>
			<script language="javascript" type="text/javascript">
				setTimeout("alert('El Agregado ya se encuentra incluido en la Mezcla');", 1000);
			</script><?php  
		} 
	}//Cierre if(isset($_POST['sbt_agregar']))?>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Agregar Mezcla</div>
	
	<fieldset class="borde_seccion" id="tabla-agregarMezcla" name="tabla-agregarMezcla">
		<legend class="titulo_etiqueta">Ingrese los Materiales que Componen la Mezcla</legend>	
		<br>
		<form onSubmit="return valFormAgregarMezcla2(this);" name="frm_agregarMezcla2" method="post" action="frm_agregarMezcla2.php">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="15%"><div align="right">Id Mezcla</div></td>
                <td width="35%">
                    <input type="text" name="txt_idMezcla" id="txt_idMezcla" value="<?php echo $_SESSION['mezclaGral']['idMezcla']?>" readonly="readonly" size="30"/>
				</td> 
				<td width="15%">&nbsp;</td>
				<td width="35%">&nbsp;</td>
            </tr>
            <tr>
                <td><div align="right">*Categoria</div></td>
                <td>
                    <select name="cmb_categoria" id="cmb_categoria" class="combo_box"
                        onchange="cargarComboConId(this.value,'bd_almacen','materiales','nom_material','id_material','linea_articulo','cmb_nombre','Material','');">
                        <option value="">Categor&iacute;a</option>
                    </select>                
					<script type="text/javascript" language="javascript">
						cargarComboConId('PLANTA','bd_almacen','materiales','linea_articulo','linea_articulo','grupo','cmb_categoria','Categoría','');
                    </script>                
				</td>
                <td width="148"><div align="right">*Nombre</div></td>
                <td width="247">
                    <select name="cmb_nombre" id="cmb_nombre" class="combo_box" 
					onchange="obtenerDatoBD(this.value,'bd_almacen','unidad_medida','unidad_medida','materiales_id_material','txt_unidadMedida')">
                        <option value="">Material</option>
                    </select>				
				</td>
            </tr>
            <tr>
                <td><div align="right">*Cantidad</div></td>
                <td>
                    <input type="text" name="txt_cantidad" id="txt_cantidad" value="" maxlength="10" size="10" 
                    onkeypress="return permite(event, 'num',2)" onchange="formatNumDecimalLab(this.value,'txt_cantidad');" />
					&nbsp;&nbsp;&nbsp;					
					<input type="text" name="txt_unidadMedida" id="txt_unidadMedida" class="caja_de_texto" value="" size="15" />			
				</td>
				<td align="right">Volumen</td>
				<td>
					<input type="text" name="txt_volumen" id="txt_volumen" value="1" maxlength="3" size="3"  readonly="readonly"/>m&sup3;
				</td>
            </tr>            
            <tr>
                <td colspan="4"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
            </tr>
            <tr>
                <td colspan="4"><div align="center">
					<input type="hidden" name="hdn_botonSel" id="hdn_botonSel" value="" />
                    <input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar" value="Agregar" title="Agregar otro Material" 
                    onmouseover="window.status='';return true" onclick="hdn_botonSel.value='agregar'" />
                    &nbsp;&nbsp;&nbsp;<?php
                    if (isset($_SESSION['materiales'])){?>
                        <input name="btn_finalizar" type="button" class="botones" id="btn_finalizar"  value="Finalizar" title="Finalizar Registro de Materiales" 
                        onmouseover="window.status='';return true" onclick="location.href='frm_agregarMezcla2.php?btn_finalizar'"/>
                        &nbsp;&nbsp;&nbsp;<?php
                    } ?>
                    <input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
                    onmouseover="window.status='';return true"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Mezclas " 
                    onmouseover="window.status='';return true" onclick="confirmarSalida('menu_mezclas.php');"/></div>				
				</td>   	
            </tr>        
      	</table>
    	</form>
	</fieldset><?php 
	//Si esta inicializado el arreglo de Sesion, mostrar los conceptos agregados
	if(isset($_SESSION['materiales'])){?>
		<div id='materialesAgregados' class='borde_seccion2'><?php
			mostrarMatAdd();?>
		</div><?php
	}
	
	//Si esta se ha presionado el boton finalizar proceder a guardar los datos almacenados en la sesion
	if(isset($_GET['btn_finalizar'])){
		guardarMateriales();
	} ?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>