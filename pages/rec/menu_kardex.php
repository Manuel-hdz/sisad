<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Recursos Humanos
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
		#parrilla-menu1 {	position:absolute;	left:135px;	top:160px;	width:698px;	height:400px;	z-index:1;}
		-->
    </style>
</head>
<body>
	<div id="parrilla-menu1">
  	<table class="tabla_frm" width="645" border="0" align="center" cellpadding="5" cellspacing="5">
        <tr>
            <td width="424" align="center">
                <form action="frm_recolectarChecadas.php">
                    <input type="image" src="images/exportar-personal.png" width="100" height="200" border="0" title="Recolectar Checadas" 
                    onmouseover="window.status='';return true"/><br/>
                    <input type="image" src="../../images/btn-col-checadas.png"  name="btn1" id="bnt1" width="118" height="46" border="0" 
                    title="Recolectar Checadas" onclick="MM_nbGroup('down','group1','btn1','',1)" 
                    onmouseover="MM_nbGroup('over','btn1','../../images/btn-col-checadas-over.png','',1); window.status='';return true"
                    onmouseout="MM_nbGroup('out')" />	
                </form>			
            </td>
            <td width="367" align="center">
                <form action="frm_consultarKardex.php">
                    <input type="image" src="images/add-kardex.png" width="130" height="200" border="0" title="Generar Kardex con opci&oacute;n a Modificaci&oacute;n" 
                    onmouseover="window.status='';return true" /><br/>
                    <input type="image" src="../../images/btn-add-kardex.png" name="btn2" id="bnt2" width="118" height="46" border="0" 
                    title="Generar Kardex con opci&oacute;n a Modificaci&oacute;n" 
                    onclick="MM_nbGroup('down','group1','btn2','',1)" 
                    onmouseover="MM_nbGroup('over','btn2','../../images/btn-add-kardex-over.png','',1);window.status='';return true" 
                    onmouseout="MM_nbGroup('out')"/>
                </form>	  		
            </td>  
        </tr>
	</table>
	<br/>
	<br/>
</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>