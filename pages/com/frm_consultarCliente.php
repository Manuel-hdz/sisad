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
		//Este archivo contiene las funciones para mostrar la informacion del cliente que se esta consultando
		include ("op_consultarCliente.php");
	
?>

<hetad>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/ajax/busq_spider.js"></script>	
    <style type="text/css">
		<!--
		#form-datos-salida { position:absolute;	left:30px; top:190px; width:660px; height:100px;	z-index:13;}
		#titulo-reporteOC {	position:absolute; left:30px; top:146px; width:173px; height:21px; z-index:11; }
		#ver-cliente { position:absolute; left:30px; top:320px; width:945px; height:350px; z-index:12; overflow: scroll; border: thick outset #FFFFFF; padding: 10px; }
		#ver-clientes { position:absolute; left:750px; top:190px; width:220px; height:100px; z-index:14; }
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-reporteOC">Consultar Cliente </div>	
	<fieldset class="borde_seccion" id="form-datos-salida" name="form-datos-salida">
	<legend class="titulo_etiqueta">Consultar Cliente por Nombre</legend>	
	<br>		
	<form onSubmit="return verContFormConsultarCliente(this);" name="frm_consultarCliente" method="post" action="frm_consultarCliente.php">
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr valign="top">			<!--Usar un layer para escribir la etiqueta, esto evita que el texto se mueva al aparecer los resultados de la consulta-->
				<td><div align="right">Cliente</div></td>
				<td align="center">
					<input name="txt_nombre" type="text" class="caja_de_texto" id="txt_nombre" onkeyup="lookup(this,'bd_compras','clientes','razon_social','1');"
                    value="" size="60" maxlength="80" onkeypress="return permite(event,'num_car', 0);"/>
					<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
						<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow" />
						<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
	    			</div>
				</td>
				<td align="center">
					<input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar" title="Mostrar Informaci&oacute;n del Cliente Seleccionado"
                    onmouseover="window.status='';return true" value="Consultar" />
				</td>
				<td align="center">
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Clientes" 
                    onMouseOver="window.status='';return true" onclick="location.href='menu_clientes.php'" />
				</td>
			</tr>
		</table>
	</form>
</fieldset>
		
<?php 
	if(isset($_POST['txt_nombre']) || isset($_POST['sbt_verClientes'])){
		?><div id="ver-cliente"><?php
		if(isset($_POST['txt_nombre']) && $txt_nombre!=""){		
			mostrarCliente($txt_nombre);		
		}
	
		if(isset($_POST['sbt_verClientes'])){
			mostrarCliente("todos");
		}		
		?></div><?php 		
	}
?>

<fieldset class="borde_seccion" id="ver-clientes" name="ver-clientes">
	<legend class="titulo_etiqueta">Ver Todos los Clientes</legend>
	<br /><br />	
	<form name="frm_verClientes" action="frm_consultarCliente.php" method="post">
		<div align="center">
		<input type="submit" name="sbt_verClientes" value="Ver Clientes" title="Ver Clientes" onmouseover="window.status='';return true" class="botones" />
		</div>
	</form>
</fieldset>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>