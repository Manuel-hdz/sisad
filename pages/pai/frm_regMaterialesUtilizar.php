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
		include ("op_gestionarServiciosExternos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar{position:absolute;left:30px;top:146px;width:330px;height:20px;z-index:11;}	
		#tabla-registrarActividades{position:absolute;left:30px;top:190px;width:620px;height:160px;z-index:13;padding:15px;	padding-top:0px;}
		#tabla-mostrarActividades{position:absolute;left:30px;top:390px;width:620px;height:270px;z-index:14;overflow:scroll;}		
		-->
    </style>
</head>
<body><?php


	/******************************** GUARDAR LOS DATOS DE LA PAGINA ANTERIOR EN LA SESSION PARA VOLVERLOS A MOSTRAR ****************/	
	//Agregamos los datos del post de la Orden de Tabajo para Servicios Externos al arreglo de SESSION
	if(isset($_POST["cmb_clasificacion"])){
		$_SESSION['ordenServicioExterno'] = array("idOrdenTrabajo"=>$_POST['txt_ordenTrabajo'], "fechaRegistro"=>$_POST['txt_fechaRegistro'],
		"area"=>$_POST['txt_area'], "clasificacion"=>$_POST['cmb_clasificacion'], "fechaSolicitud"=>$_POST['txt_fechaSolicitud'], "fechaRecepcion"=>$_POST['txt_fechaRecepcion'],
		"comboProveedor"=>$_POST['cmb_proveedor'], "proveedor"=>strtoupper($_POST['txt_proveedor']), "direccion"=>strtoupper($_POST['txt_direccion']), 
		"repProveedor"=>strtoupper($_POST['txt_repProveedor']), "encCompras"=>strtoupper($_POST['txt_encCompras']), "solicito"=>strtoupper($_POST['txt_solicito']), 
		"autorizo"=>strtoupper($_POST['txt_autorizo']));
	}//Cierre if(isset($_POST["cmb_clasificacion"]))
	

	//Agregar los datos de los Materiales cuando se le de clic al boton de Agregar (sbt_agregarMat)
	if(isset($_POST["sbt_agregarMat"])){
		//Si ya esta definido el arreglo $materialesUtilizar, entonces agregar el siguiente registro a él
		if(isset($_SESSION['materialesUtilizar'])){			
			//Guardar los datos en el arreglo
			$_SESSION['materialesUtilizar'][] = array("partida"=>($txt_partida), "material"=>strtoupper($txa_material),"cantidad"=>$txt_cantidad);
		}
		//Si no esta definido el arreglo $actividades definirlo y agregar el primer registro
		else{			
			//Guardar los datos en el arreglo
			$materialesUtilizar = array(array("partida"=>($txt_partida),"material"=>strtoupper($txa_material),"cantidad"=>$txt_cantidad));
			
			//Guardar en la SESSION
			$_SESSION['materialesUtilizar'] = $materialesUtilizar;	
		}	
	}//Cierre if(isset($_POST["sbt_agregarMat"]))						
	
	
	//Cuando se entra a esta página desde el registro de la Orden de Trabajo para Servicios Externos, obtenemos el No. de Material de la SESSION o 
	//colocamos 1 en el caso de que no exista
	$cont = 0;
	if(!isset($_POST["sbt_agregarMat"])){
		if(!isset($_SESSION["materialesUtilizar"]))
			$cont = 1;
		else//De lo contrario si el arreglo viene definido, es contado y se agrega uno mas, para formar el sig. numero de partida
			$cont = count($_SESSION["materialesUtilizar"]) + 1;
	}
	
			
	//Verificamos que el boton este definido; cada vez que se presione aumentara la partida
	if(isset($_POST["sbt_agregarMat"])){
		$cont = $_POST["txt_partida"]+1;
	}?>


	<div id="barra"><img src="../../images/title-bar-bg-gomar.png" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Registrar Materiales a Utilizar</div>
	
	
	<fieldset class="borde_seccion" id="tabla-registrarActividades" name="tabla-registrarActividades">
	<legend class="titulo_etiqueta">Registrar Materiales</legend>
	
	<form onSubmit="return valFormMaterialesUtilizar(this);" name="frm_materialesUtilizar" method="post">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  	<td width="10%" valign="top"><div align="right">Partida</div></td>
			<td width="10%" valign="top">
		  		<input name="txt_partida" id="txt_partida" type="text" class="caja_de_num" size="2" value="<?php echo $cont;?>" readonly="readonly" />
			</td>      	    
      	    <td width="20%" valign="top" align="right">*Material</td>
      	    <td width="60%" valign="top" rowspan="3">
			  	<textarea name="txa_material" id="txa_material" cols="50" rows="3" class="caja_de_texto"  maxlength="120" onkeyup="return ismaxlength(this)" 
				onkeypress="return permite(event,'num_car', 0);"></textarea>			
			</td>
  	  	</tr>
		<tr>
			<td align="right">Cantidad</td>
			<td>
				<input type="text" name="txt_cantidad" id="txt_cantidad" class="caja_de_texto" onkeypress="return permite(event,'num', 2);" size="5" maxlength="10" />
			</td>
			<td>&nbsp;</td>
		</tr>
      	<tr>
      		<td colspan="4" align="center"><?php
				
				if(isset($_SESSION["materialesUtilizar"])){?>
					<input type="button" name="btn_finalizarRegistro" class="botones" value="Finalizar" title="Finalizar Registro de Actividades a Realizar" 
					onclick="location.href='frm_generarOrdenServiciosE.php'" />
					&nbsp;&nbsp;<?php					
				}?>
						
				<input name="sbt_agregarMat" type="submit" class="botones" value="Agregar" title="Registrar Material" onmouseover="window.status='';return true" />
				&nbsp;&nbsp;
       	 		<input name="rst_limpiar" type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
				&nbsp;&nbsp;
                <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar Registro de la Orden de Trabajo" 
                onmouseover="window.status='';return true" onclick="location.href='frm_generarOrdenServiciosE.php?cancelar=materiales'" />
			</td>
		</tr>
	</table>
	</form>
</fieldset><?php
				
	
	//Verificar que este definido el Arreglo de $actividadesRealizar, si es asi, Mostramos su contenido
	if(isset($_SESSION["materialesUtilizar"])){?>
		<div id="tabla-mostrarActividades" class="borde_seccion2"><?php
			mostrarMaterialesUtilizar($_SESSION["materialesUtilizar"]);?>
		</div>
		<?php
	}?>
	
		
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>