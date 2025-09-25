<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Seguridad
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarRecordatorio.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionSeguridad.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
		#tabla-modificarRecordatorio {position:absolute;left:30px;top:190px;width:812px;height:246px;z-index:12;}
		#calendario{position:absolute;left:685px;top:300px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body>

<?php 
	if(!isset($_POST['sbt_guardar'])&&!isset($_POST['sbt_eliminar'])){?>
		<script language="javascript" type="text/javascript">
			setTimeout("activarCamposRegRec()",300);
		</script><?php 
		//Cuando no sea externa la alerta estos valores seran como vacios, de lo contrario seran obtenidos en las consultas posteriores
		$archivos="";
		$deptos="";
	
		//Conectar a la BD de Seguridad
		$conn = conecta("bd_seguridad");

		//Guardamos en la variable radio el valor que viene del POST con el registro seleccionado
		$radio=$_POST['rdb_id'];;
			
		//Crear sentencia SQL para obtener los datos registrados
		$sql_stm ="SELECT * FROM alertas_generales WHERE id_alerta='$radio'";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);
			
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);

		//Si el Tipo de Alerta es externa realizamos las siguientes consultas para obtener los archivos agregados asi como los departamentos
		if($datos['tipo_alerta']=="EXTERNA"){
			//Consulta que permite seleccionar los departamentos para despues proceder a concatenarlos
			$stm_sqlDep="SELECT catalogo_departamentos_id_departamento FROM detalle_alertas_generales WHERE alertas_generales_id_alerta='$radio'";
				
			//Ejecutar la sentencia previamente creada
			$rsDep = mysql_query($stm_sqlDep);
				
			//Confirmar que la consulta de datos fue realizada con exito.
			$datosDep=mysql_fetch_array($rsDep);
			
			//Variable que permite controlar el agregado de archivos
			$contad = 1;
			do{	
				if($contad==1){
					$nomDepto = obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datosDep['catalogo_departamentos_id_departamento']);
					$deptos = strtoupper($nomDepto);
				}
				if($contad>1){
					$nomDepto = obtenerDato("bd_usuarios", "usuarios", "depto", "no_depto", $datosDep['catalogo_departamentos_id_departamento']);
					$deptos .= ",".strtoupper($nomDepto);
				}
				$contad++;
			}while($datosDep=mysql_fetch_array($rsDep));
			//Eliminamos PANEL de la variable departamentos
			$deptos=str_replace("PANEL,,","",$deptos);
				
			//ReConectar a la BD de Seguridad ya que la funcion de obtener dato anterior, cierra la conexcion
			$conn = conecta("bd_seguridad");
				
			//Consulta que permite seleccionar los departamentos para despues proceder a concatenarlos
			$stm_sqlArch="SELECT DISTINCT repositorio_documentos_id_documento FROM archivos_vinculados WHERE alertas_generales_id_alerta='$radio'";
				
			//Ejecutar la sentencia previamente creada
			$rsArch = mysql_query($stm_sqlArch);
				
			//Confirmar que la consulta de datos fue realizada con exito.
			$datosArch=mysql_fetch_array($rsArch);
			
			//Variable para controlar el ciclo	
			$contador = 1;
			do{	
				if($contador==1){
					$archivos =$datosArch['repositorio_documentos_id_documento'];
					$archivos = strtoupper($archivos);
				}
				if($contador>1){
					$archivos .= ",".$datosArch['repositorio_documentos_id_documento'];
					$archivos = strtoupper($archivos);
				}
				$contador++;
			}while($datosArch=mysql_fetch_array($rsArch));
		}
	?>
    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Recordatorios </div>
	<fieldset class="borde_seccion" id="tabla-modificarRecordatorio" name="tabla-modificarRecordatorio">
	<legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n del Recordatorio </legend>	
	<br>
	
	<form onsubmit="return valFormRegRec(this);" name="frm_modificarRecordatorio"  id="frm_modificarRecordatorio" method="post" action="op_modificarRecordatorio.php">
	<table width="806" height="187"  cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td height="47"><div align="right">*Clave Recordatorio </div></td>
			<td width="204">
				<input name="txt_idRecordatorio" id="txt_idRecordatorio" type="text" class="caja_de_texto" size="15" maxlength="15" 
				value="<?php echo $datos['id_alerta'];?>"readonly="readonly"/>			
			</td>
			<td width="144"><div align="right">*Descripci&oacute;n</div></td>
			<td width="222">
				<textarea name="txa_descripcion" id="txa_descripcion" maxlength="160" onkeyup="return ismaxlength(this)" class="caja_de_texto" rows="2" cols="30"
				onkeypress="return permite(event,'num_car', 0);"><?php echo $datos['descripcion'];?></textarea>			
			</td>
		</tr>
		<tr>
			<td width="169" height="31"><div align="right">*Tipo Recordatorio </div></td>
			<td>
				<select name="cmb_tipoAler" id="cmb_tipoAler" size="1" class="combo_box" onchange="activarCamposRegRec();">
					<option value="">Tipo</option>
					<option <?php if($datos['tipo_alerta']=="INTERNA"){echo "selected='selected'";}?>  value="INTERNA">INTERNA</option>
					<option <?php if($datos['tipo_alerta']=="EXTERNA"){echo "selected='selected'";}?> value="EXTERNA">EXTERNA</option>
				</select>			
			</td>
			<td><div align="right">*Fecha Programada</div></td>
			<td>
				<input name="txt_fechaProg" type="text" id="txt_fechaProg" size="10" maxlength="15" value="<?php echo modFecha($datos['fecha_programada'],1);?>" 
				readonly="readonly"/>
			</td>
		</tr>
	  	<tr>
	  		<td><div align="right" id="div_agrDep">*Agregar Departamentos </div></td>
	    	<td>
				<input name="txt_ubicacion" id="txt_ubicacion" type="text" class="caja_de_texto" size="40" readonly="readonly" value="<?php echo $deptos;?>"
				onclick="window.open('verDepartamentos.php','_blank','top=50, left=50, width=380, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar Departamentos"/>
			</td>
	    	<td><div align="right" id="div_agrArc">Agregar Archivos </div></td>
	    	<td>
				<input name="txt_archivos" id="txt_archivos" type="text" class="caja_de_texto" size="40" readonly="readonly"  value="<?php echo $archivos;?>"
				onclick="window.open('verArchivos.php','_blank','top=50, left=50, width=680, height=500, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');" title="De Click Sobre La Caja De Texto Para Agregar Archivos"/>
			</td>
	  </tr>
		<tr><td colspan="5"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td></tr>
		<tr>
			<td colspan="6">
				<div align="center">
					<input name="sbt_guardar" type="submit" class="botones" id= "sbt_guardar" value="Guardar" title="Guardar Recordatorio"
					onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="rst_limpiar" type="reset" class="botones"  value="Restablecer" title="Limpiar Formulario" onmouseover="window.status='';return true"/>
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Recordatorios" 
					onmouseover="window.status='';return true"  onclick="confirmarSalida('frm_modificarRecordatorio.php')" />
				</div>			
			</td>
		</tr>
    </table>
	</form>
</fieldset>
<div id="calendario">
	<input name="calendario" type="image" id="calendario2" onclick="displayCalendar (document.frm_modificarRecordatorio.txt_fechaProg,'dd/mm/yyyy',this)" 
	onmouseover="window.status='';return true" src="../../images/calendar.png"  title="Seleccione una fecha" align="absbottom" width="25" height="25" border="0"/>						
</div>	
<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>