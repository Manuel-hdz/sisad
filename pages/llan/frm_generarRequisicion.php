<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Paileria
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		//Manejo de la funciones para registrar la Entrada de Materiales en la BD 
		include ("op_generarRequisicion.php");
		//Archivo que permite editar los registros de la requisicion
		include("op_editarRegistros.php");?><head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionMantenimientoE.js" ></script>
	<script type="text/javascript" src="includes/ajax/buscarMaterial.js"></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	
	<script type="text/javascript" language="javascript">
		function asignarSolicitante(depto){
			if(depto=="MANTENIMIENTO CONCRETO")
				document.getElementById("txt_solicitanteReq").value=document.getElementById("hdn_mttoS").value;
			else if((depto=="MANTENIMIENTO MINA"))
				document.getElementById("txt_solicitanteReq").value=document.getElementById("hdn_mttoM").value;
			else
				document.getElementById("txt_solicitanteReq").value="";
		}
	</script>/
	
    

<style type="text/css">
		<!--
		#procesando { position:absolute; left:406px; top:274px; width:133px; height:86px; z-index:17; }
		#material-requisicion { position:absolute; left:30px; top:600px; width:860px; height:187px; z-index:14; }
		#tabla-material { position:absolute; left:20px; top:190px; width:499px;	height:240px; z-index:16; }
		#datos-gral { position:absolute; left:20px; top:455px; width:940px; height:120px; z-index:15; }
		#titulo-generar { position:absolute; left:30px; top:146px; width:187px; height:19px; z-index:11; }
		#tabla-otrosMat { position:absolute; left:567px; top:190px; width:394px; height:240px; z-index:14; }
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-generar">Generar Requisici&oacute;n </div>

	<?php //Si la variable de txt_areaSolicitante esta definida en el arreglo $_POST, proceder a guardar la informacion de la BD.
	if(!isset($_POST['txt_areaSolicitante'])){
		$area="MANTENIMIENTO SUPERFICIE";
		//Extraer el RFC del encargado de departamento
		$solicitanteS=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento",$area);
		//Con el RFC, traer el nombre completo del encargado del depto
		$solicitanteS=obtenerNombreEmpleado($solicitanteS);
		$area="MANTENIMIENTO MINA";
		//Extraer el RFC del encargado de departamento
		$solicitanteM=obtenerDato("bd_recursos","organigrama","empleados_rfc_empleado","departamento",$area);
		//Con el RFC, traer el nombre completo del encargado del depto
		$solicitanteM=obtenerNombreEmpleado($solicitanteM);
		//Obtener el nombre del empleado con el Usuario adjudicado
		$elaborador=obtenerDato("bd_usuarios","credenciales","nombre","usuarios_usuario",$_SESSION["usr_reg"]);
	?>
		
	<fieldset id="tabla-material" class="borde_seccion">
	<legend class="titulo_etiqueta">Seleccionar Material del Cat&aacute;logo de Almac&eacute;n</legend>
	<br>	
	<form onsubmit="return valFormGenerarRequisicion(this);" name="frm_generarRequisicion" method="post" action="frm_generarRequisicion.php" >
	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" >		
		<tr>
	  	  	<td width="82"  class="tabla_frm"><div align="right">Categor&iacute;a</div></td>
		 	<td width="276"  class="tabla_frm"><?php  		
				$res = cargarComboConId("cmb_categoria","linea_articulo","linea_articulo","materiales","bd_almacen","Categor&iacute;a","",
				"mensaje.style.visibility='hidden';cargarComboIdNombreOrd(this.value,'bd_almacen','materiales','nom_material','id_material','linea_articulo','cmb_material','Material','nom_material','')");
				if($res==0){?>
					<label class="msje_correcto"> No hay Categor&iacute;as Registradas, Contacte Administrador Almac&eacute;n</label>
					<input type="hidden" name="cmb_categoria" id="cmb_categoria"/><?php
				}?>
	  	  	</td>			
		</tr>
		<tr>
		  	<td width="80"><div align="right">Material</div></td>
			<td colspan="2">
            	<select name="cmb_material" id="cmb_material" size="1" class="combo_box">
					<option value="" selected="selected">Material</option>					
		  		</select>
			</td>	
		<tr>
			<td><div align="right">Clave</div></td>
			<td>
				<input type="text" name="txt_clave" id="txt_clave" size="10" maxlength="10" onblur="buscarMaterialBD(this,3);" 
                onkeypress="return permite(event,'num_car',1);" />
			</td>
			<td><span id="mensaje" class="msje_correcto" style="visibility:hidden;">No Se Encontr&oacute; Ning&uacute;n Material</span></td>
		</tr>
		<tr>
			<td><div align="right">Aplicaci&oacute;n</div></td>
			<td colspan="2">
				<input name="txt_aplicacionReq" type="text" class="caja_de_texto" id="txt_aplicacionReq"  size="30" maxlength="60" 
            	onkeypress="return permite(event,'num_car',1);"/>
			</td>
		</tr>
		<tr>
			<td><div align="right">Cantidad</div></td>
			<td><input name="txt_cantReq" type="text" class="caja_de_texto" id="txt_cantReq"  size="10" maxlength="10" onkeypress="return permite(event,'num',2);" /></td>
			<td>
				<input type="submit" name="btn_agregarOtro" class="botones" value="Agregar Otro" onMouseOver="window.status='';return true" 
            	title="Agregar Material al Registro de la Requisici&oacute;n"/>
			</td>
		</tr>
	</table>
	</form>
	</fieldset>
	
	<fieldset id="tabla-otrosMat" class="borde_seccion">
	<legend class="titulo_etiqueta">Agregar Material no Registrado en el Cat&aacute;logo de Almac&eacute;n</legend>
	<br>
	<form onsubmit="return valFormMaterialesRequisicion(this);" name="frm_MaterialesRequisicion" method="post" action="frm_generarRequisicion.php">
 	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td width="90"><div align="right">Material</div></td>
			<td colspan="2" width="180"><input name="txt_matReq" type="text"  class="caja_de_texto" id="txt_matReq" onkeypress="return permite(event,'num_car',0);"
            size="30" maxlength="60" /></td>
		</tr>	
      	<tr>
			<td><div align="right">Unidad Medida </div></td>
			<td colspan="2"><input name="txt_unidadMedida" type="text"  class="caja_de_texto" id="txt_unidadMedida" onkeypress="return permite(event,'car',1);" 
            size="30" maxlength="30" /></td>
		</tr>
		<tr>
			<td><div align="right">Clave</div></td>
			<td colspan="2">
				<input name="txt_clave" type="text" class="caja_de_texto" id="txt_clave" size="6" maxlength="10" readonly="readonly" value="N/A"/>
			</td>
		</tr>
		<tr>
		    <td><div align="right">Aplicacion</div></td>
		    <td colspan="2"><input name="txt_aplicacionReq2" type="text" class="caja_de_texto" id="txt_aplicacionReq2" onkeypress="return permite(event,'num_car',1);" 
            size="30" maxlength="60"/></td>
		</tr>
		<tr>
			<td><div align="right">Cantidad</div></td>
			<td><input name="txt_cantReq2" type="text" class="caja_de_texto" id="txt_cantReq2" onkeypress="return permite(event,'num',2);" size="6" 
            	maxlength="10" />
            </td>		
			<td align="center">
				<input type="hidden" name="hdn_claveValida" id="hdn_claveValida" value="si"/>
				<input type="submit" name="btn_agregarOtro2" class="botones" value="Agregar Otro" onMouseOver="window.status='';return true" 
                title="Agregar Material al Registro de la Requisici&oacute;n" />
			</td> 		
		</tr>	
	</table>
	</form>
	</fieldset>

	<fieldset id="datos-gral" class="borde_seccion">
	<legend class="titulo_etiqueta">Informaci&oacute;n Complementaria de la Requisici&oacute;n</legend>
	<br>
	<form onsubmit="return valFormInformacionRequisicion(this);" name="frm_InformacionRequisicion" method="post" action="frm_generarRequisicion.php">
  	<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		 	<td width="100"><div align="right">Justificaci&oacute;n</div></td>
      		<td width="180">
				<textarea name="txa_justificacionReq" maxlength="120" onkeyup="return ismaxlength(this)" cols="30" rows="2" class="caja_de_texto" 
                id="txa_justificacionReq" 
				onkeypress="return permite(event,'num_car',0);"></textarea>			
            </td>
		    <td><div align="right">Elabor&oacute;</div></td>
		    <td>
				<input name="txt_elaboradorReq" type="text" id="txt_elaboradorReq" onkeypress="return permite(event,'num_car');" size="40" maxlength="60" value="<?php echo $elaborador;?>"/>
		  		<input name="hdn_fecha" type="hidden" id="hdn_fecha" value="<?php echo verFecha(3);?>"  />
			</td>    
		    <td width="120"><div align="right">Prioridad</div></td>
            <td><select name="cmb_prioridad" id="cmb_prioridad" size="1" class="combo_box">
                <?php //Evitar que la variable $cmb_prioridad marque un error por no estar definida			
				if(!isset($_POST['cmb_prioridad']))
					$cmb_prioridad = "";?>
				<option value="" selected="selected">Prioridad</option>
				<option <?php if($cmb_prioridad=="MEDIA") echo "selected='selected'"; ?> value="MEDIA">MEDIA</option>
				<option <?php if($cmb_prioridad=="URGENTE") echo "selected='selected'"; ?> value="URGENTE">URGENTE</option>
            	</select>
            </td>
            <td><input type="hidden" name="hdn_materialAgregado" id="hdn_materialAgregado"<?php
				if(isset($_POST['txt_cantReq']) || isset($_POST['txt_cantReq2']) || (isset($_SESSION['datosRequisicion']) && count($_SESSION['datosRequisicion'])>0) ) 
					echo "value='si'"; 
				else 
					echo "value='no'"; 
				?> />
            	<input name="sbt_generar" type="submit" class="botones" id="sbt_generar" value="Generar" onmouseover="window.status='';return true" 
                title="Generar Requisici&oacute;n" />
            </td>
		</tr>
    	<tr>			
			<td><div align="right">&Aacute;rea Solicitante</div></td>
      		<td>
				<select name="txt_areaSolicitante" id="txt_areaSolicitante" class="combo_box" onchange="asignarSolicitante(this.value);">
					<option value="" selected="selected">&Aacute;rea</option>
					<option value="MANTENIMIENTO CONCRETO">MTTO ZARPEO</option>
					<option value="MANTENIMIENTO MINA">MTTO DESARROLLO</option>
				</select>
				<input type="hidden" name="hdn_mttoS" id="hdn_mttoS" value="<?php echo $solicitanteS?>"/>
				<input type="hidden" name="hdn_mttoM" id="hdn_mttoM" value="<?php echo $solicitanteM?>"/>
			</td>
			<td><div align="right">Solicit&oacute;</div></td>
		    <td colspan="2">
				<input name="txt_solicitanteReq" type="text" id="txt_solicitanteReq" onkeypress="return permite(event,'car',2);" size="42" 
                maxlength="60" value="" readonly="readonly"/>
            </td>
            <td><?php
				$id_req = obtenerIdRequisicion();
				if (isset($_SESSION["id_requisicion"])){
					$estado = "";
					$id_req = $_SESSION["id_requisicion"];	
				}
				?>
				<input name="btn_comentario" type="button" class="botones" value="Comentario" title="Ingresar Comentario a la Requisici&oacute;n"
				onclick="window.open('verComentarioReq.php?id_requisicion=<?php echo $id_req;?>', 
				'_blank','top=100, left=100, width=500, height=200, status=no, menubar=no, resizable=no, scrollbars=no, toolbar=no, location=no, directories=no');" />
			</td>
            <td><input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Requisiciones" 
            	onclick="location.href='menu_requisiciones.php?cancel'"/>
            </td>
    	</tr>
	</table>
	</form>
	</fieldset>
	
	<div id="material-requisicion"><?php 			
		//Aquí se regustran en el arreglo de datosRequisicion los materiales agregados desde el catalogo de almacén.
		if( (isset($_POST['cmb_material']) || isset($_POST['txt_clave'])) && isset($_POST['txt_cantReq']) && isset($_POST['txt_aplicacionReq'])){			
			//Determinar el Origen de la Clave, ComboBox cmb_material o Caja de Texto txt_clave
			if(isset($_POST['cmb_material']) && $_POST['cmb_material']!="")		$id_material = $_POST['cmb_material'];
			else $id_material = $_POST['txt_clave'];
		
			//Si ya esta definido el arreglo $datosRequisicion en la SESSION, entonces agregar el siguiente registro a &eacute;l
			if(isset($_SESSION['datosRequisicion'])){						
				//Verificar que el registro que se quiere agregar no exista en el arreglo
				if(!verRegDuplicado($datosRequisicion, "clave", $id_material)){
					//Obtener el nombre del material para agregarlo al arreglo
					$nombre = obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $id_material);
					//Obtener  la unidad de medida del material para agregarlo al arreglo
					$unidad = obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $id_material);
					//Convertir a Mayusculas el campo de Aplicacion
					$txt_aplicacionReq = strtoupper($txt_aplicacionReq);
					//Guardar los datos en el arreglo
					$datosRequisicion[] = array("clave"=>$id_material, "material"=>$nombre, "unidad"=>$unidad, "cantReq"=>$txt_cantReq, 
					"aplicacionReq"=>$txt_aplicacionReq);
				}
				else{
					?>
					<script type="text/javascript" language="javascript">
						setTimeout("alert('El Material ya fue Agregado a la Requisición');",500);
					</script>
					<?php
				}														
			}
			//Si no esta definido el arreglo $datosRequisicion en la SESSION definirlo y agregar el primer registro
			else{			
				//Obtener el nombre del material para agregarlo al arreglo
				$nombre = obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $id_material);
				//Obtener  la unidad de medida del material para agregarlo al arreglo
				$unidad = obtenerDato("bd_almacen","unidad_medida", "unidad_medida", "materiales_id_material", $id_material);
				//Convertir a Mayusculas el campo de Aplicacion
				$txt_aplicacionReq = strtoupper($txt_aplicacionReq);
				//Crear el arreglo con el primer registro
				$datosRequisicion = array(array("clave"=>$id_material, "material"=>$nombre, "unidad"=>$unidad, "cantReq"=>$txt_cantReq, 
				"aplicacionReq"=>$txt_aplicacionReq, "nuevo_con_clave"=>1));
				//Guardar el arreglo en la SESSION
				$_SESSION['datosRequisicion'] = $datosRequisicion;	
				//Crear el ID de la Entrada de Material
				$_SESSION['id_requisicion'] = obtenerIdRequisicion();
			}				
		}	
		//Aquí se registran en el arreglo de datosRequisicion los materiales agregados desde el formulario de nuevos Materiales
		if(isset($_POST['txt_matReq']) && isset($_POST['txt_unidadMedida']) && isset($_POST['txt_cantReq2']) && isset($_POST['txt_aplicacionReq2'])){
			//Definimos la clave como N/A por default, esto para si el material agregado a la Requisicion no tiene clave asignada
			$clave="N/A";
			//Si esta definida una clave, agregarla, de lo contrario escribir N/A
			if(isset($_POST['txt_clave'])&&$_POST['txt_clave']!="")
				$clave=strtoupper($_POST['txt_clave']);
			//Si ya esta definido el arreglo $datosRequisicion en la SESSION, entonces agregar el siguiente registro a &eacute;l
			if(isset($_SESSION['datosRequisicion'])){									
				//Verificar que el registro que se quiere agregar no exista en el arreglo
				if(!verRegDuplicado($datosRequisicion, "material", $txt_matReq)){
					//Pasar a mayusculas los campos ingresados desde el segundo formulario
					$txt_matReq = strtoupper($txt_matReq); $txt_unidadMedida = strtoupper($txt_unidadMedida); $txt_aplicacionReq2 = strtoupper($txt_aplicacionReq2);
					//Guardar los datos en el arreglo
					$datosRequisicion[] = array("clave"=>$clave, "material"=>$txt_matReq, "unidad"=>$txt_unidadMedida, "cantReq"=>$txt_cantReq2, 
					"aplicacionReq"=>$txt_aplicacionReq2);
				}
			}
			//Si no esta definido el arreglo $datosRequisicion en la SESSION definirlo y agregar el primer registro
			else{	
				//Pasar a mayusculas los campos ingresados desde el segundo formulario
				$txt_matReq = strtoupper($txt_matReq); $txt_unidadMedida = strtoupper($txt_unidadMedida); $txt_aplicacionReq2 = strtoupper($txt_aplicacionReq2);						
				//Crear el arreglo con el primer registro
				$datosRequisicion = array(array("clave"=>$clave, "material"=>$txt_matReq, "unidad"=>$txt_unidadMedida, "cantReq"=>$txt_cantReq2, 
				"aplicacionReq"=>$txt_aplicacionReq2));
				//Guardar el arreglo en la SESSION
				$_SESSION['datosRequisicion'] = $datosRequisicion;	
				//Crear el ID de la requisicion
				$_SESSION['id_requisicion'] = obtenerIdRequisicion();
			}				
		}
		//Verificar que el arreglo de datos haya sido definido en la SESSION
		if((isset($_SESSION['datosRequisicion']) && count($_SESSION['datosRequisicion'])>0) && isset($_SESSION['id_requisicion'])){?>
			<p align="center" class="titulo_etiqueta">Registro de la Requisici&oacute;n No. <?php echo $_SESSION['id_requisicion']; ?></p><?php
      		 mostrarRegistros($datosRequisicion);				
		}
	
	}//Cierre if if(!isset($_POST['txt_areaSolicitante']))
	else{
		guardarRequisicion($txa_justificacionReq,$hdn_fecha,$txt_areaSolicitante,$txt_solicitanteReq,$txt_elaboradorReq);?>
		<div class="titulo_etiqueta" id="procesando">
      		<div align="center">
        		<p><img src="../../images/loading.gif" width="70" height="70"  /></p>
        		<p>Procesando...</p>
      		</div>
		</div><?php
	}?>	
</div>	    	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>