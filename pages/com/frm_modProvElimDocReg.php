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
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    	<style type="text/css">
			<!--
			#apDiv1 { position:absolute; left:24px;	top:34px; width:528px; height:26px; z-index:1; }
			#titulo-barra {position:absolute; left:25px; top:146px; width:408px; height:22px; z-index:11;}
			#tablas {position:absolute;	left:25px; top:206px; width:754px; height:45px;	z-index:12;}
			#boton-cancelar { position:absolute; left:315px; top:362px; width:124px; height:37px; z-index:13;}
			-->
        </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-barra">Modificar Proveedor/Eliminar Documentaci&oacute;n Registrada</div>
	<div id="tablas">
		<form  name="frm_modProvDocReg" method="post" action=""> 
   			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
      			<tr>
      		   	 <td><div align="right">RFC</div></td>
        	   	 <td><input type="text" name="txt_rfc"  /></td>
      			</tr>
			 </table>
    			<p>&nbsp;</p>
    			<p class="titulo_etiqueta">Eliminar Documentaci&oacute;n por Nombre</p>
    		<table width="281" border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
    			 <tr>
                 	<td><div align="right">Documento</div></td>
                    <td><select name="cmb_documento" ><option>Selecciona</option></select></td>
                </tr>
     			<tr>
        			<td><input name="sbt_eliminar" type="submit" class="botones"  value="Eliminar" /></td>
        			<td><input name="btn_finalizar" type="submit" class="botones"  value="Finalizar" /></td>
     			</tr>
		    </table>    
		</form>
	</div>
		<div id="boton-cancelar">
 		<form action="inicio_compras.php" method="post">
    		<input name="sbt_cancelar" type="submit" value="Cancelar" class="botones" title="Cancelar" />
 		</form>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>