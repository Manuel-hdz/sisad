<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Compras
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
	//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Archivo que realiza la evaluacion de Proveedores y lo inserta en la BD
		include ("op_evaluarProveedor.php");
	
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>

	<style type="text/css">
	<!--		
		#titulo-barra {position:absolute;left:30px;top:146px;width:165px;height:20px;z-index:11;}
		#contenido-tablas {position:absolute;left:15px;top:189px;width:459px;height:492px;z-index:12;}	
		#boton-cancelar {position:absolute;left:331px;top:283px;width:124px;height:37px;z-index:13;}
		#boton-cancelar2 {position:absolute;left:350px;top:660px;width:124px;height:37px;z-index:13;}
		#img-evaluacion {position:absolute;left:497px;top:106px;width:492px;height:271px;z-index:14;}
		#calendario_repInicio { position:absolute; left:220px; top:290px; width:29px; height:24px; z-index:14; }
		#calendario_repCierre { position:absolute; left:415px; top:290px; width:30px; height:26px; z-index:15; }
		-->
	</style>
</head>
	<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Evaluar Proveedor</div>

	<?php if (!isset($_POST["sbt_guardar"])){
	$txt_nombre=$_POST["hdn_proveedor"];
	?>
	<fieldset class="borde_seccion" id="contenido-tablas" name="contenido-tablas">
		<legend class="titulo_etiqueta">Evaluaci&oacute;n de Proveedor</legend>
		<form name="frm_evaluarProveedor2" method="post" action="frm_evaluarProveedor2.php" onsubmit="return valFormEvaluarProveedor(this);">
			<p class="titulo_etiqueta">Evaluaci&oacute;n del Proveedor: <?php echo $txt_nombre; ?></p>
			<input type="hidden" name="rfc_proveedores" id="rfc_proveedores" value="<?php echo obtenerDato("bd_compras", "proveedores", "rfc", 
			"razon_social",$txt_nombre)?>"  />
			<p class="titulo_etiqueta">Seleccione la fecha del per&iacute;odo de Evaluaci&oacute;n&#13;</p>
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
	    	<tr>
   			  	<td><div align="right">Fecha Inicio</div></td>
        		<td>
                	<input name="txt_fechaInicio" type="text" value=<?php echo date("d/m/Y",strtotime("-30 day")); ?> size="10" maxlength="15" readonly=true 
                	width="50">
                 </td>
				<td>&nbsp;</td>
	   			<td><div align="right">Fecha Fin</div></td>
    	    	<td>
                	<input name="txt_fechaCierre" type="text" value=<?php echo date("d/m/Y"); ?> size="10" maxlength="15" readonly=true width="50">
                </td>
		   	</tr>
  	  		</table>
		
			<table width="453" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
    		<tr>
       			<td colspan="2"><strong>Tiempo de Entrega&#13;</strong></td>
        		<td width="100">&nbsp;</td>
      		</tr>
      		<tr>
        		<td width="125">
					<input type="radio" name="rdb_tiempoEntrega" id="rdb_tiempoEntrega1" value="6" 
					onclick="acumularPuntos(this,'cant_tiempoEntrega');" /> 
        			Satisfactorio
				</td>
        		<td width="170">
					<input type="radio" name="rdb_tiempoEntrega" id="rdb_tiempoEntrega2" value="4" 
					onclick="acumularPuntos(this,'cant_tiempoEntrega');" />
        			En Desarrollo
				</td>
        		<td>
					<input name="rdb_tiempoEntrega" type="radio" id="rdb_tiempoEntrega3" 
					onclick="acumularPuntos(this,'cant_tiempoEntrega');" value="2" />
        			No Satisfactorio
					<input type="hidden" name="cant_tiempoEntrega" id="cant_tiempoEntrega" value="0"  />
				</td>
      		</tr>
      		<tr>
        		<td><strong>Producto/Servicio&#13;</strong></td>
        		<td></td>
        		<td></td>
      		</tr>
      		<tr>
        		<td>
					<input type="radio" name="rdb_ProdServicio" id="rdb_ProdServicio1" value="6" 
					onclick="acumularPuntos(this,'cant_prodServicio');" />
        			Satisfactorio
				</td>
        		<td>
					<input type="radio" name="rdb_ProdServicio" id="rdb_ProdServicio2" value="4" 
					onclick="acumularPuntos(this,'cant_prodServicio');" />
					Confiable Condicionado
				</td>
        		<td>
					<input type="radio" name="rdb_ProdServicio" id="rdb_ProdServicio3" value="2" 
					onclick="acumularPuntos(this,'cant_prodServicio');" />
        			En Desarrollo
					<input type="hidden" name="cant_prodServicio" id="cant_prodServicio" value="0"  /> 
				</td>
     		</tr>
     		<tr>
        		<td colspan="3"><strong>Entrega Certificado de Calidad/Producto</strong></td>        
     		</tr>
     		<tr>
        		<td>
					<input type="radio" name="rdb_entCertificado" id="rdb_entCertificado1" value="6" 
					onclick="acumularPuntos(this,'cant_entCertificado');" />
        			Enviado
				</td>
         		<td>
					<input type="radio" name="rdb_entCertificado" id="rdb_entCertificado2" value="0" 
					onclick="acumularPuntos(this,'cant_entCertificado');" />
         			No Enviado
					<input type="hidden" name="cant_entCertificado" id="cant_entCertificado" value="0" />
				</td>
        		<td>
                </td>
     		</tr>
     		<tr>
       		  <td><div align="left">Comentarios&#13;</div></td>
        		<td><textarea name="txa_comentarios" cols="30" rows="3" class="caja_de_texto" onkeypress="return permite(event,'num_car',0);"></textarea></td>
        		<td><p align="center">Total</p>
       		    <p align="center">
       		      <input name="txt_total" id="txt_total" type="text" class="caja_de_num" size="5" maxlength="10" readonly="readonly"/>
				  <input type="hidden" name="hdn_totalPuntos" id="hdn_totalPuntos" value="" />
       		    </p></td>
      		</tr>
      		<tr>
        		<td align="center">
        		  	<input name="sbt_guardar" type="submit" class="botones" value="Guardar" title="Guardar Información de Evaluación" onMouseOver="window.status='';return true;"/>
      		  	</td>
        		<td align="center">
        		  	<input name="rst_limpiar" type="reset"  class="botones" value="Limpiar" title="Limpiar los Campos que Contienen Información" onMouseOver="window.status='';return true"/>
				</td>
        		<td align="center">
        			<input name="btn_cancelar" type="button" value="Cancelar" class="botones" title="Regresar a la página de Evaluar Proveedor" onclick="location.href='frm_evaluarProveedor.php'" onMouseOver="window.status='';return true"/>
      		  	</td>
     	    </tr>
	  	</table>
	    <div id="img-evaluacion">
            <a href="#" onclick="javascript:window.open('images/criterios-evaluacion.png','_blank','top=0, left=0, width=800, height=600, status=no, menubar=no, resizable=no, scrollbars=yes,toolbar=no, location=no,directories=no');">
            	<img src="images/criterios-evaluacion.png" width="498" height="257" border="0"/>
			</a>
		</div>
	</form>
	</fieldset>
	<div id="calendario_repInicio">
		<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_evaluarProveedor2.txt_fechaInicio,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>
	
	<div id="calendario_repCierre">
        <input name="calendario_cieRep" type="image" id="calendario_cieRep" onclick="displayCalendar(document.frm_evaluarProveedor2.txt_fechaCierre,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
	</div>
	<?php }
	else{
		evaluacion();
	}
	?>
	</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>
