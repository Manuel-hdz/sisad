<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Producción
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){		
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarProduccion.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/validacionProduccion.js" ></script>
	<script type="text/javascript" src="includes/ajax/obtenerDatoPresupuesto.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>   

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>

    <style type="text/css">
		<!--
			#titulo-modificar {	position:absolute;	left:30px;	top:146px;	width:262px;	height:20px;	z-index:11;}
			#tabla-modificar {position:absolute;left:30px;top:190px;width:450px;height:161px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-modificarFecha {position:absolute;left:542px;top:192px;width:412px;height:149px;z-index:12;padding:15px;padding-top:0px;}
			#tabla-resultados {position:absolute;left:28px;top:373px;width:919px;height:297px;z-index:12;padding:15px;padding-top:0px; overflow:scroll}
			#calendar-uno{position:absolute; left:245px; top:233px; width:30px; height:26px; z-index:18; }	
			#calendar-dos{position:absolute; left:244px; top:270px; width:30px; height:26px; z-index:20; }	
			#calendar-tres{position:absolute; left:961px; top:237px; width:30px; height:26px; z-index:21; }
			#titulo-tabla {	position:absolute; left:29px; top:69px;	width:919px; height:295px; z-index:8; overflow:scroll}
			#tabla-escogerRegistro {position:absolute;left:30px;top:190px;width:450px;height:149px;z-index:12;padding:15px;padding-top:0px;}	
		-->
    </style>
</head>
<body>
	
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Modificar Registro de Producci&oacute;n </div><?php
	if(isset($_POST["hdn_band"])){
		//Liberar datos de la SESSION
		unset($_SESSION["menuProduccion"]);
	}
	
	//Si existe la sesion quiere decir que se regreso a la pagina actual despues de ingresar en las posteriores por lo tanto guardammos las variables para que 
	//permanezca la consulta
	if(isset($_SESSION["menuProduccion"]) && !isset($_POST["ckb_fecha"])){
		//Creamos el boton y creamos las variables de Session a POST
		$_POST['sbt_consultar'] = "";
		if(isset($_SESSION["menuProduccion"]["fecha"])){
			$_POST['txt_fecha'] = $_SESSION["menuProduccion"]["fecha"];
			$_POST['cmb_destino'] = $_SESSION["menuProduccion"]["destino"];
		}
		else if(isset($_SESSION["menuProduccion"]["periodo"])){
			$_POST['cmb_periodo'] = $_SESSION["menuProduccion"]["periodo"];
			$_POST['cmb_destino'] = $_SESSION["menuProduccion"]["destino"];
		}
	}
		
	//Guardar datos para realizar consulta de los registros de Produccion en la SESSION
	if(isset($_POST["txt_fecha"])){
		$_SESSION["menuProduccion"] = array("fecha"=>$_POST["txt_fecha"], "destino"=>$_POST['cmb_destino']);
	}
	if(isset($_POST["cmb_periodo"])){
		$_SESSION["menuProduccion"] = array("periodo"=>$_POST["cmb_periodo"], "destino"=>$_POST['cmb_destino']);
	}
				
	//Si existe el ckb_fecha entonces redireccionamos a la nueva pantalla
	if(isset($_POST["ckb_fecha"])){
		$destino=$_POST['hdn_destino'];
		echo "<meta http-equiv='refresh' content='0;url=frm_modificarProduccion2.php?fecha=$_POST[ckb_fecha]&destino=$destino'";
	}
	else{
		if(isset($_POST["sbt_consultar"])){?>
			<form action="frm_modificarProduccion.php" name="frm_consultarProduccion" method="post">
				<div align='center' id='tabla-resultados' class='borde_seccion2'><?php 
					mostrarProduccion();?>
 				</div>
				<input type='hidden' name='hdn_destino' id='hdn_destino' value='<?php echo $_POST['cmb_destino'];?>'/>
         	</form><?php 
		}?>
		
		
		<fieldset class="borde_seccion" id="tabla-modificar" name="tabla-modificar">
		<legend class="titulo_etiqueta">Buscar Registro por Periodo </legend>	
		<br>
		<form onsubmit="return valFormModificarProduccionUno(this);" name="frm_modificarProduccion" method="post"  id="frm_modificarProduccion">
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>			  
				<td><div align="right">Destino</div></td>
        	      <td width="57"><?php
						$cmb_destino="";
						$conn = conecta("bd_produccion");
						$result=mysql_query("SELECT id_destino ,destino FROM catalogo_destino ORDER BY destino");?>
					  <select name="cmb_destino" id="cmb_destino"  class="combo_box" 
						onchange="cargarComboOrdenado(this.value,'bd_produccion','presupuesto','periodo','catalogo_destino_id_destino','cmb_periodo','Periodo','','fecha_fin')">	
						<option value="">Destino</option>
						<?php
								 while ($row=mysql_fetch_array($result)){
									if ($row['id_destino'] == $cmb_destino){
										echo "<option value='$row[id_destino]' selected='selected'>$row[destino]</option>";
									}
									else{
										echo "<option value='$row[id_destino]'>$row[destino]</option>";
									}
								} 
						//Cerrar la conexion con la BD		
						mysql_close($conn);?>
					  </select>
				  </td>
				  <td><div align="right">Periodo</div></td>
				  <td width="147"><select name="cmb_periodo" id="cmb_periodo" class="combo_box">
					  <option value="">Seleccione</option>
					</select>
				  </td>
			</tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr>
				<td colspan="4">
					<div align="center"> 
						<input type="hidden" name="hdn_band" id="hdn_band"/>
						<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar"
						onmouseover="window.status='';return true;" title="Continuar Modificaci&oacute;n del Registro de Producci&oacute;n"/>   
						&nbsp;&nbsp;&nbsp;	    	
						<input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
						title="Regresar al Men&uacute; Bit&aacute;cora"
						onmouseover="window.status='';return true" onclick="location.href='menu_bitacora.php';"/>					
					</div>				
				</td>
			</tr>
		</table>
		</form>
</fieldset>
		
		
		<fieldset class="borde_seccion" id="tabla-modificarFecha" name="tabla-modificarFecha">
		<legend class="titulo_etiqueta">Buscar Registro por  Fecha Especif&iacute;ca </legend>	
		<br>
		<form onsubmit="return valFormModificarProduccionFecha(this);"  name="frm_modificarFecha" method="post"  id="frm_modificarFecha" >
		<table width="407" height="108" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
			  <td width="104"><div align="right">Destino</div></td>
					<td><?php 
					  	$comprobar=cargarComboTotal("cmb_destino","destino", "id_destino","catalogo_destino","bd_produccion","Seleccione","","","destino","","");
						if($comprobar==0){						
							echo "<label class='msje_correcto'> No hay Periodos Registrados</label>
							<input type='hidden' name='cmb_destino' id='cmb_destino'/>";
						}		
					?></td>
			  <td width="146"><div align="right">Fecha</div></td>	
				<td width="105">
			  		<input name="txt_fecha" id="txt_fecha" readonly="readonly" type="text" 	value="<?php echo date("d/m/Y"); ?>" size="10"  width="90"/> 
		  	  </td>
			</tr>
			<tr><td>&nbsp;</td><td>&nbsp;</td></tr>
			<tr>
				<td colspan="5">
					<div align="center">
						<input type="hidden" name="hdn_band" id="hdn_band"/>
						<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" value="Consultar"
						onmouseover="window.status='';return true;" title="Continuar Modificaci&oacute;n del Registro de Producci&oacute;n"/>   
						&nbsp;&nbsp;&nbsp;         	    	
						<input name="btn_regresarMenu" id="btn_regresarMenu"type="button" class="botones" value="Regresar" 
						title="Regresar al Men&uacute; Bit&aacute;cora"
						onmouseover="window.status='';return true" onclick="location.href='menu_bitacora.php';"/>					
					</div>				
				</td>
			</tr>
		</table>
		</form>
</fieldset>
		
		<div id="calendar-tres">
			<input name="fechaIni" type="image" id="fechaIni" onclick="displayCalendar(document.frm_modificarFecha.txt_fecha,'dd/mm/yyyy',this)"
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
			width="25" height="25" border="0" title="Seleccione Fecha"/>
</div><?php 
						
	}?>

</body><?php
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>