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
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />

    <style type="text/css">
		<!--
		#parrilla-menu1 {position:absolute;left:101px;top:159px;width:806px;height:400px;z-index:1;}
		#botones { position:absolute; left:30px; top:660px; width:940px; height:25px; z-index:15;}
		-->
    </style>
</head>
<body>

	<div id="parrilla-menu1">
	<table width="100%" border="0" align="center">
    	<tr>
            <td width="100%" align="center">
                <?php if ($_GET["doc"]=="pedido"){?>
                    <form action="formatos/pedido.xls">
                        <input type="image" src="images/formato-pedido.png" width="380" height="476" onmouseover="window.status=''; return true;" 
                        title="Descargar Formato de Pedido"/>
                    </form>
                <?php }if ($_GET["doc"]=="cotizacion"){?>
                    <script>
                        <?php //Se le dan 500 milisegundos para aparecer y así permite cargar todos los elementos de la página?>
                        setTimeout("alert('Este formato no es parte de la documentación certificada y aprobada por el departamento de Aseguramiento de Calidad')",500);
                    </script>
                    <form action="formatos/cotizacion.xls">
                        <input type="image" src="images/formato-cotizacion.png" width="380" height="476" onmouseover="window.status=''; return true;" 
                        title="Descargar Formato de Cotizaci&oacute;n"/>
                    </form>
                <?php }if ($_GET["doc"]=="cheque"){?>
                    <script>
                        <?php //Se le dan 500 milisegundos para aparecer y así permite cargar todos los elementos de la página?>
                        setTimeout("alert('Este formato es sólo para imprimirse sobre un cheque, al NO imprimirse como tal, no tiene validéz oficial')",500);
                    </script>
                    <form action="formatos/cheque.xls">
                        <input type="image" src="images/formato-cheque.png" width="380" height="476" onmouseover="window.status=''; return true;" 
                        title="Descargar Formato de Cheque"/>
                    </form>
                <?php }?>
            </td>
   	 </tr>
	</table>
	</div>
	
	<div id="botones">
		<table width="100%" align="center">
			<tr>
				<td align="center">
					<input type="button" class="botones" name="btn_Cancelar" id="btn_Cancelar" value="Cancelar" onclick="location.href='menu_formatos.php'" 
                	title="Regresar a la pantalla de Formatos"/>
				</td>
			</tr>
		</table>
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>