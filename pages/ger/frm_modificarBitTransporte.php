<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarBitacoras.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="includes/ajax/busq_spider_personal.js"></script>
	<script type="text/javascript" src="../../includes/ajax/obtenerDatoBD.js"></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:383px;height:20px;z-index:11;}
		#tabla-registrarBit {position:absolute;left:30px;top:190px;width:750px;height:240px;z-index:14;}
		#res-spiderChofer{position:absolute; z-index:15;}
		#detalleRegBit { position:absolute; left:30px; top:490px; width:850px; height:222px; z-index:5; overflow:scroll}

		-->
    </style>
</head>
<body><?php
		
	//Declarar las variables vacias para prevenir errores
	$fecha="";
	$nombre="";
	$puesto="";
	$destino="";
	$cantidad="";
	$comentarios="";
	$idBitcora="";
		
	//Si se ha presionado el boton de finalizar proceder a llamar la funcion que ser encarga de guardar los datos en la bd
	if(isset($_POST['sbt_guardar'])){
		actualizarRegBitTrans();
	}
		
	if(!isset($_POST['sbt_guardar'])){
	
		//Si viene en el post el rdb_idBitacora realizar la consulta para poder precargar los valores en cada una de los elementos del formulario
		if(isset($_POST['rdb_idBitacora']) && isset($_GET['mod']) ){
		
			$destino= obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$_GET['ubicacion']);		
			
			//Conectar a la BD de Gerencia Técnica
			$conn = conecta("bd_gerencia");
			
			//recuperar el id de la bitacora
			$idBitacora=$_POST['rdb_idBitacora'];
			
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM bitacora_transporte WHERE id_bitacora_transporte = '$idBitacora'";
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($sql_stm);									
			//Confirmar que la consulta de datos fue realizada con exito.
			$datos=mysql_fetch_array($rs);
			
			//Asignar el valor obtenido de las consultas a las variables
			$fecha= modfecha($datos['fecha'],1);
			$nombre= $datos['nombre'];
			$puesto= $datos['puesto'];
			$destino= $datos['destino'];
			$cantidad=  number_format($datos['cantidad'], 2,".",",");
			$comentarios= $datos['comentarios'];
			
			$idBitcora= $_POST['rdb_idBitacora'];
		}//FIN if(isset($_POST['rdb_idBitacora']))?>


		<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
		<div class="titulo_barra" id="titulo-registrar">Modificar Registro a la Bit&aacute;cora de Transporte</div>
	
		<fieldset class="borde_seccion" id="tabla-registrarBit" name="tabla-registrarBit">
		<legend class="titulo_etiqueta">Ingresar la Nueva Informaci&oacute;n del Registro de Transporte</legend>	
		<br>
		<form onSubmit="return valFormActRegBitTransp(this);" name="frm_modificarBitTransporte" method="post" action="frm_modificarBitTransporte.php">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
                <td width="98"><div align="right">Fecha</div></td>
                <td width="123"><input name="txt_fecha" id="txt_fecha" type="text" class="caja_de_texto" size="10" readonly="readonly"
	                value="<?php echo $fecha;?>"/>
                </td>
				<td width="247"><div align="right">*Ubicaci&oacute;n</div></td>
                <td colspan="3">
				<input type="text" name="txt_ubicacion" id="txt_ubicacion" size="35" class="caja_de_texto" readonly="readonly" value="<?php echo $destino;?>"/>                 
                </td>
            </tr>   
			<tr>
				<td><div align="right">*Nombre</div></td>
				<td colspan="2">
					<input type="text" name="txt_nombre" id="txt_nombre" onkeyup="lookup(this,'empleados','1');" 
					value="<?php echo $nombre;?>" size="60" maxlength="80" onkeypress="return permite(event,'car',0);"/>
					<div id="res-spiderChofer">
						<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
						</div>
					</div>
					<input type="hidden" name="hdn_rfc" id="hdn_rfc" value=""/>            
				</td>
				<td ><div align="right">*Cargo</div></td>
				<td width="123">
					<select name="cmb_choferSup" id="cmb_choferSup" class="combo_box">
						<option value=""  <?php if ($puesto == '') echo "selected='selected'"?>>Chofer/Suplente</option>
						<option value="CHOFER" <?php if ($puesto == 'CHOFER') echo "selected='selected'"?> >CHOFER</option>
						<option value="SUPLENTE" <?php if ($puesto == 'SUPLENTE') echo "selected='selected'"?> >SUPLENTE</option>	
					</select>
				</td>
			</tr>
			<tr>
				<td><div align="right">*Cantidad</div></td>
				<td>
                    <input type="text" name="txt_cantidad" id="txt_cantidad" class="caja_de_texto" value="<?php echo $cantidad;?>" onkeypress="return permite(event,'num',2);"
					size="7" maxlength="7" onchange="formatCurrency(txt_cantidad.value,'txt_cantidad');"/> m&sup3;
				</td>
                
                <td><div align="right">Observaciones</div></td>
				<td colspan="2"><textarea name="txa_comentarios" maxlength="120" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="40" 
					onkeypress="return permite(event,'num_car',0);" ><?php echo $comentarios;?></textarea>
				</td>
                <td></td>
			</tr>

			<tr>
				<td colspan="5"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
			</tr>
			<tr>
				<td colspan="6">
					<div align="center">
						&nbsp;&nbsp;&nbsp;
						<input type="hidden" name="hdn_idBitacora" id="hdn_idBitacora" value="<?php echo $idBitcora; ?>"/>
						<input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Guardar" title="Guardar Cambios en el Registro" 
						onmouseover="window.status='';return true"/>
                        &nbsp;&nbsp;&nbsp;				
						<input type="button" name="btn_regresar" class="botones" value="Regresar" title="Regresar" onMouseOver="window.status='';return true" 
						onclick="location.href='frm_modificarRegistroBitacoraTransp.php?cmb_ubicacion=<?php echo $_GET['ubicacion'];?>&txt_fecha=<?php echo $_GET['fecha'];?>'"/>
					</div>          
				</td>
			</tr>
		</table>
		</form>
		</fieldset><?php
	}//FIN 	if(!isset($_POST['sbt_guardar']))?>
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>