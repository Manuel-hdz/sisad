<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Manejo de la funciones para Registrar los datos del Aspirante en la BD 
		include ("op_modificarAspirante.php");?>
		
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute; left:30px; top:146px; width:447px; height:20px; z-index:11; }
		#tabla-modificarPuestoAspirante { position:absolute; left:20px; top:190px; width:949px; height:247px; z-index:12; padding:15px; padding-top:0px;}
		#calendario {position:absolute; left:822px; top:232px; width:30px; height:26px; z-index:13;}
		#resultados-modificarPuestoAspirante { position:absolute; left:38px; top:485px; width:908px; height:159px; z-index:22; overflow:scroll; }
		-->
    </style>
</head>
<body><?php 	
	
	//Desplegar los Puestos Asociados al Aspirante cuando al menos uno haya sido agregado a la SESSION
	if(isset($_SESSION['datosPuestoAspirante'])){?>
		<div id="resultados-modificarPuestoAspirante" class='borde_seccion2' align="center"><?php			
			mostrarPuestosAspirante();?>
		</div><?php 
	}?>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar el Puesto Recomendado para el Aspirante a Empleo</div>
	<fieldset class="borde_seccion" id="tabla-modificarPuestoAspirante">
	<legend class="titulo_etiqueta">Modificar las Área y Puestos Recomendados para el Nuevo Aspirante</legend>	
	<br>
	<!--En  la propiedad action=""  de este formulario debera contener el nombre del formulario   -->
	<form onSubmit="return valFormModificarPuestoAspirante(this);" name="frm_modificarPuestoAspirante" method="post" action="frm_modificarPuestoAspirante.php" >
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
		  	<td width="106"><div align="right">*Folio Aspirante </div></td>
			<!--Dentro de este campo se manda llamar la función obtenerFolioAspirante  para que este campo en el formulario despliegue 
				el folio en orden consecutivo y de acuerdo al mes en el que se esta registrando el aspirante  -->
			<td width="221">			
		  		<input name="txt_folioAspirante" id="txt_folioAspirante" type="text" class="caja_de_texto" size="10" maxlength="10" onkeypress="return permite(event,'num_car', 3);" 
				readonly="readonly" value="<?php echo $_SESSION['datosAspirante']['folio'];?>" />				
			</td>
		 	<td width="192"><div align="right">Nombre del Aspirante </div></td>
			<td width="363">
				<!-- Variable para almacenar el nombre del aspirante concatenado y que por medio de la SESSION me envie a este formulario el nombre completo del aspirantes(nombre concatenado) -->
				<?php $nomAspirante = $_SESSION['datosAspirante']['nombre']." ".$_SESSION['datosAspirante']['apePat']." ".$_SESSION['datosAspirante']['apeMat'];?>
				<input name="txt_nombreAspirante" id="txt_nombreAspirante" type="text" class="caja_de_texto" readonly="readonly" size="60" maxlength="60" value="<?php echo $nomAspirante; ?> "/>
			</td>
		</tr>
		<tr>
			<td><div align="right">*&Aacute;rea Recomendada </div></td>
			<td><?php				
				$conn = conecta("bd_recursos");
				$result=mysql_query("SELECT DISTINCT area FROM area_puesto ORDER BY area");?>
				<select name="cmb_area" id="cmb_area" size="1" class="combo_box" onchange="cargarCombo(this.value,'bd_recursos','area_puesto','puesto','area','cmb_puesto','Puesto Recomendado','');">
					<option value="">&Aacute;rea</option><?php 
					while ($row=mysql_fetch_array($result)){
						echo "<option value='$row[area]'>$row[area]</option>";						
					} 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
				</select>				
			</td>
			<td align="right">
				<div align="right">
				<input type="checkbox" name="ckb_areaRecomendada" id="ckb_areaRecomendada" 
				onclick="agregarNuevaArea(this, 'ckb_puestoRecomendado', 'txt_areaRecomendada', 'txt_puestoRecomendado', 'cmb_area', 'cmb_puesto');"
				title="Seleccione para escribir el nombre de la nueva Área Recomendada" />
			    Agregar Nueva &Aacute;rea Recomendada </div>
			</td>
			<td><input name="txt_areaRecomendada" id="txt_areaRecomendada" type="text" class="caja_de_texto" readonly="readonly" size="20" maxlength="20"/></td>
		</tr>						
		<tr>
			<td><div align="right">*Puesto Recomendado </div></td>
			<td>
				<select name="cmb_puesto" id="cmb_puesto" class="combo_box">
			  		<option value="">Puesto Recomendado</option>
				</select>
			</td>
			<td align="right">
				<div align="right">
				<input type="checkbox" name="ckb_puestoRecomendado" id="ckb_puestoRecomendado" 
				onclick="agregarNuevoPuesto(this, 'ckb_areaRecomendada', 'txt_areaRecomendada', 'txt_puestoRecomendado', 'cmb_area', 'cmb_puesto');" 
				title="Seleccione para escribir el nombre de la nueva Área Recomendada"/>
				Agregar Nuevo Puesto Recomendado				</div></td>				
			<td><input name="txt_puestoRecomendado" id="txt_puestoRecomendado" type="text" class="caja_de_texto" readonly="readonly" size="30" maxlength="30"/></td>
		</tr>
		<tr>
		   <td colspan="2"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
   		   <td colspan="2" class="msje_correcto" align="right"><strong><?php echo $msgAreaPuesto; ?></strong></td>
		</tr>
		<tr>
			<td colspan="4">
				<div align="center"><?php //onclick="location.href='frm_modificarContactoAspirante.php'" estaba en el primero boton en vez del primer onclick ?>
				<input type="hidden" name="hdn_botonSeleccionado" id="hdn_botonSeleccionado" value="" />
				<?php if(isset($_SESSION['datosPuestoAspirante'])){//Si al menos un puesto ha sido modficado, mostrar el boton de registrar Contactos ?>
					<input name="sbt_modificarContactoAspirante" type="submit"  class="botones_largos"  value="Modificar Contactos" title="Modificar los Datos de los Contactos del Aspirante" 
					onmouseover="window.status='';return true"  onclick="hdn_botonSeleccionado.value='registrarContacto'" />
				<?php } ?>
				&nbsp;&nbsp;&nbsp;
				<input name="sbt_registrarPuesto" type="submit" class="botones_largos" id="sbt_registrarPuesto" title="Agregar Puesto Recomendado para el Aspirante Registrado" 
				onMouseOver="window.status='';return true"  value="Agregar Puesto" onclick="hdn_botonSeleccionado.value='registrarAreaPuesto'"  />				
				&nbsp;&nbsp;&nbsp;
				<input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario" onMouseOver="window.status='';return true"/> 
				&nbsp;&nbsp;&nbsp;
				<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al menú de Bolsa de Trabajo" 
				onMouseOver="window.status='';return true" onclick="confirmarSalida('menu_bolsaTrabajo.php');" />
				</div>
			</td>
		</tr>
	</table>
	</form>
</fieldset>					
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>