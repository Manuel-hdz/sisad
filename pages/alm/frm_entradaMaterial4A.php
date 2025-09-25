<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Almac�n
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Manejo de la funciones para registrar la Salida de Materiales en la BD
		include ("op_entradaMaterial.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
    <style type="text/css">
		<!--
		#titulo-barra { position:absolute; left:25px; top:146px; width:152px; height:19px; z-index:11; }
		#generar-rea { position:absolute; left:30px; top:190px; width:940px; height:104px; z-index:12; }		
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Entrada Material </div>
	
	<div id="generar-rea">
    <form name="frm_generarREA" method="post" action="frm_reporteREA.php">
    <p align="center" class="titulo_etiqueta">&iquest;Generar Reporte de Entradas a Almac&eacute;n de los &uacute;ltimos registros realizados?</p>
	<table cellpadding="5" cellspacing="5" align="center">
		<tr>			
		  	<td width="120">
				<p align="center"><input type="submit" name="sbt_generarREA" value="Si" class="botones" onMouseOver="window.status='';return true" title="Generar Reporte REA"/>
			</td>
		    <td width="120"><input name="btn_no" type="button" class="botones" value="No" onclick="location.href='exito.php'" title="Continuar" /></td>
			<td>
				<input type="button" class="botones_largos" value="Generar Comprobante" title="Generar Comprobante de la Entrada realizada" onclick="window.open('../../includes/generadorPDF/entradaMaterial.php?id=<?php echo $_GET['clave_entrada']; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>
			</td>
			<td>				
				<input type="hidden" name="fecha_ini" value="<?php echo date("d/m/Y"); ?>" />
				<input type="hidden" name="fecha_end" value="<?php echo date("d/m/Y"); ?>" />
				<input type="hidden" name="clave" value="<?php echo $_GET['clave_entrada']; ?>" />
			</td>
		</tr>
	</table>
	</form>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>