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
		//ESter archivo se incluye para eliminar los Archivos que se hayan cargado y deban ser eliminados en base al boton de Cancelar
		include ("op_agregarEquipo.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregado { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-equipoAgregado { position:absolute; left:30px; top:190px; width:945px; height:485px; z-index:12; padding:15px; padding-top:0px;}
		-->
    </style>
</head>
<body>
	<?php 
	//verificar que este definido el ID del equipo a mostrar
	if (isset($_GET["id_eq"])){
		//Asignamos el id que viene en el Get a una variable para manejarlo mas facilmente
		$id_equipo=$_GET["id_eq"];
		//Si en el GET viene definido el valor CANCELAR, entonces procedemos a borrar los archivos que se hayan cargado al servidor, pasando el nombre de la carpeta como parametro
		//Boton de cancelar presionado desde agregar refacciones
		if (isset($_GET["cancelarRefac"])&& isset($_SESSION['refacciones']))
			unset($_SESSION['refacciones']);		
		//El boton de CANCELAR se presiona desde la pantalla siguiente, es decir, la de Agregar Documentacion de Equipos
		if (isset($_GET["cancelar"])&& isset($_SESSION["docTemporal"]))
			borrarArchivos($id_equipo);
		//Se conecta a la Base de Datos para obtener los datos que se han agregado recientemente
		$conn=conecta("bd_mantenimiento");
		//Sentencia SQL para obtener los datos segun el ID
		$stm_sql="SELECT * FROM equipos WHERE id_equipo='$id_equipo'";
		//Ejecutar la sentencia previamente creada
		$rs=mysql_query($stm_sql);
		//Pasamos el resultado de la consulta a un arreglo de Datos
		$datos=mysql_fetch_array($rs);
		//Verificamos que el arreglo de documentos no este declarado, en caso de ser asi, vaciarlo
		if (isset($_SESSION["documentos"])){
			unset($_SESSION["documentos"]);
		}
		//Verificamos que el arreglo de docTemporal no este declarado, en caso de ser asi, vaciarlo
		//El arreglo docTemporal permite almacenar el nombre de los archivos que en caso que se presione CANCELAR, seran eliminados del servidor
		if (isset($_SESSION["docTemporal"])){
			unset($_SESSION["docTemporal"]);
		}
	?>
		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-agregado">Equipo Agregado</div>
	
		<fieldset class="borde_seccion" id="tabla-equipoAgregado" name="tabla-equipoAgregado">
		<legend class="titulo_etiqueta">Equipo Agregado</legend>	
		<br>
		<form name="frm_equipoAgregado" method="post" action="frm_agregarDocumentacionEquipo.php">
	    <table width="959" height="336" cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
			<td width="135"><div align="right">Clave del Equipo</div></td>
			<td width="358"><input name="txt_clave" id="txt_clave" type="text" class="caja_de_texto" size="15" maxlength="13" readonly="readonly" value="<?php echo $datos["id_equipo"]; ?>"/></td>
			<td width="136"><div align="right">Fecha de Fabricaci&oacute;n del Equipo </div></td>
       		<td width="263"><input type="text" name="txt_fechaFabricacionEquipo" id="txt_fechaFabricacionEquipo" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo modFecha($datos["fecha_fabrica"],1); ?>"/>
			 <input type="checkbox" name="ckb_fechaFabricacionEquipo" value="checkbox" /></td>
		</tr>
   		<tr>
       		<td><div align="right">Fecha</div></td>
           	<td><input type="text" name="txt_fecha" id="txt_fecha" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo modFecha($datos["fecha_alta"],1);?>"/></td>
	        <td><div align="right">No. Placas </div></td>
    	    <td><input type="text" name="txt_placa" id="txt_placa" size="10" maxlength="10" class="caja_de_texto" readonly="readonly" value="<?php echo $datos["placas"]; ?>"/>
			 <input type="checkbox" name="ckb_placa" value="checkbox" /></td>
		</tr>
	    <tr>
			<td><div align="right">Nombre del Equipo </div></td>
		  <td><input name="txt_nombre" id="txt_nombre" type="text" class="caja_de_texto" size="50" maxlength="60" readonly="readonly" value="<?php echo $datos["nom_equipo"]; ?>"/>
			  <input type="checkbox" name="ckb_nombre" value="checkbox" />		</td>
			<td><div align="right">Tenencia</div></td>
		  <td><input name="txt_tenencia" id="txt_tenencia" type="text" class="caja_de_texto" size="20" maxlength="20" readonly="readonly" value="<?php echo $datos["tenencia"]; ?>"/><input type="checkbox" name="ckb_tenencia" value="checkbox" />			</td>
		</tr>
		<tr>
			<td><div align="right">Marca/Modelo </div></td>
			<td>
				<input name="txt_marcaModelo" type="text" class="caja_de_texto" id="txt_marcaModelo" size="20" maxlength="60" readonly="readonly" value="<?php echo $datos["marca_modelo"];?>"/> 
				 <input type="checkbox" name="ckb_marcaModelo" value="checkbox" />
           		*Modelo  
       	      	<input name="txt_modelo" type="text" class="caja_de_texto" id="txt_modelo" size="15" maxlength="30" readonly="readonly" value="<?php echo $datos["modelo"]; ?>"/> <input type="checkbox" name="ckb_modelo" value="checkbox" />			</td>
			<td><div align="right">No. Tarjeta Circulaci&oacute;n </div></td>
			<td><input name="txt_tarjetaCirculacion" id="txt_tarjetaCirculacion" type="text" class="caja_de_texto" size="20" maxlength="20" readonly="readonly" value="<?php echo $datos["tar_circulacion"]; ?>"/>
			<input type="checkbox" name="ckb_tarjetaCirculacion" value="checkbox" /></td>
		</tr>
		<tr>
			<td><div align="right">No. de Serie </div></td>
           	<td><input name="txt_serie" id="txt_serie" type="text" class="caja_de_texto" size="20" maxlength="20" readonly="readonly" value="<?php echo $datos["num_serie"]; ?>"/>
			 <input type="checkbox" name="ckb_serie" value="checkbox" /></td>
			<td><div align="right">No. P&oacute;liza  </div></td>
			<td><input name="txt_poliza" id="txt_poliza" type="text" class="caja_de_texto" size="20" maxlength="20" readonly="readonly" value="<?php echo $datos["poliza"]; ?>"/>
			<input type="checkbox" name="ckb_poliza" value="checkbox" /></td>
		</tr>
		<tr>
			<td><div align="right">No. de Serie de la Olla </div></td>
			<td><input name="txt_serieOlla" id="txt_serieOlla" type="text" class="caja_de_texto" size="20" maxlength="20" readonly="readonly" value="<?php echo $datos["num_serie_olla"]; ?>"/><input type="checkbox" name="ckb_serieOlla" value="checkbox" /></td>
			<td><div align="right">Asignado a </div></td>
			<td><input name="txt_asignado" id="txt_asignado" type="text" class="caja_de_texto" size="40" maxlength="40" readonly="readonly" value="<?php echo $datos["asignado"]; ?>"/><input type="checkbox" name="ckb_asignado" value="checkbox" /></td>
		</tr>       	
		<tr>
			<td><div align="right">Tipo de Motor </div></td>
			<td><input name="txt_motor" id="txt_motor" type="text" class="caja_de_texto" size="15" maxlength="15" readonly="readonly" value="<?php echo $datos["tipo_motor"]; ?>"/> <input type="checkbox" name="ckb_motor" value="checkbox" /></td>
			<td><div align="right">Proveedor</div></td>
			<td><input name="txt_proveedor" id="txt_proveedor" type="text" class="caja_de_texto" size="40" maxlength="80" readonly="readonly" value="<?php echo $datos["proveedor"]; ?>"/>
			<input type="checkbox" name="ckb_proveedor" value="checkbox" /></td>
		</tr>
		<tr>
			<td><div align="right">&Aacute;rea</div></td>
		  <td>
	  	  <input name="txt_area" id="txt_area" type="text" class="caja_de_texto" size="40" maxlength="40" readonly="readonly" value="<?php echo $datos["area"]; ?>"/></td>
			<td><div align="right">Fotograf&iacute;a</div></td>
			<td>
				<?php
				//Verificamos si el campo mime esta vacio, para poder anunciar si el equipo agregado incluye o no Fotografia
				if ($datos["mime"]!=""){?>
					<label class="msje_correcto">Agregado con Foto</label>
				<?php }
				else{?>
					<label class="msje_correcto">Agregado sin Foto</label>
				<?php }?>			</td>
		</tr>
		<tr>
			<td><div align="right">Familia</div></td>
			<td><input name="txt_area" id="txt_area" type="text" class="caja_de_texto" size="30" maxlength="30" readonly="readonly" value="<?php echo $datos["familia"]; ?>"/>
			 <input type="checkbox" name="ckb_area" value="checkbox" /></td>
			<td><div align="right">Descripci&oacute;n</div></td>
			<td><textarea name="txa_descripcion" id="txa_observaciones" maxlength="160" readonly="readonly" class="caja_de_texto" rows="2" cols="30"><?php echo $datos["descripcion"]; ?></textarea><input type="checkbox" name="ckb_descripcion" value="checkbox" /></td>
		</tr>
		<tr>
			<td><div align="right">Hor&oacute;metro/Od&oacute;metro</div></td>
			<td colspan="5"><div "5" align="center" class="msje_correcto">
			   <div align="left">
			   <input name="txt_metrica" id="txt_metrica" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly" value="<?php echo $datos["metrica"]; ?>"/>
			   <input type="checkbox" name="ckb_metrica" value="checkbox" />
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			   
&iexcl;Veh&iacute;culo Agregado con &Eacute;xito!</div>
			</div></td>
		</tr>
		<tr>
			<td colspan="4">
				<div align="center">
				 <input name="btn_regEquipo" type="button" class="botones_largos" value="Agregar Otro Equipo" 
                title="Agregar Otro Equipo" 
                onclick="frm_equipoAgregado.action='frm_agregarEquipo.php';frm_equipoAgregado.submit();" />
				&nbsp;&nbsp;&nbsp;
                <input name="btn_regRefaccion" type="button" class="botones_largos" value="Registrar Refacciones" 
                title="Registrar Refaccionesl del Equipo  <?php echo $_GET["id_eq"];?>" 
                onclick="location.href='frm_agregarRefacciones.php?id=<?php echo $_GET["id_eq"];?>';" />
                &nbsp;&nbsp;&nbsp;
				<input name="sbt_registrar" type="submit" class="botones_largos"  value="Registrar Documentaci&oacute;n" 
                title="Registrar la Documentaci&oacute;n del Equipo <?php echo $_GET["id_eq"];?>" 
				onMouseOver="window.status='';return true" />
				&nbsp;&nbsp;&nbsp;
				<input name="btn_finalizar" type="button" class="botones" value="Finalizar" title="Registra los datos del Vehículo" 
				onMouseOver="window.status='';return true" onclick="location.href='exito.php'" />
				</div>			</td>
		</tr>
		</table>
		</form>
</fieldset>
	<?php 
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin del IF que comprueba si en el GET viene definida la clave del Equipo
	else{
		//Si no esta definido el GET, se llego a esta pantalla de otra manera, en dado caso cerrar la sesion
		echo "<meta http-equiv='refresh' content='0;url=../salir.php'>";
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>