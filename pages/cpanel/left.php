<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Menu</title>

	<script type="text/javascript" src="../../includes/reloj.js" ></script>
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
	
	<script type="text/javascript" src="../../includes/jquery-1.5.1.js"></script>
	<script type="text/javascript" src="includes/jquery.lksMenu.js"></script>
	<link rel="stylesheet" type="text/css" href="includes/lksMenuSkin1.css" />
	<script>
		$('document').ready(function(){
			$('.menu').lksMenu();
		});
	</script>
	
	<style type="text/css">
	<!--
	body{background-image:url(images/menu.png);background-repeat:no-repeat;margin-left: 0px;margin-top: 0px;margin-right: 0px;margin-bottom: 0px;}
	.titulo_menu{font-family: MicrogrammaDMedExt;font-weight: bold;}
	#clock{position:absolute;z-index:1;left:90px; top: 645px;}
	a:visited{color: #000000;text-decoration: none;}
	a:link{text-decoration: none;color: #000000;}
	a:hover{text-decoration: underline;color: #006600;}
	a:active{text-decoration: none;}
	#etiqueta{position:absolute;z-index:1;top:10px;left:0px; width:100%;}
	-->
	</style>
</head>

<body onLoad="setInterval(muestraReloj, 1000);">
<br /><br />
<div class="menu">
<ul>
	<li><a href="main.php" target="mainFrame"><strong class="titulo_menu">&bull;Inicio</strong><img src="images/home.png" width="30" height="30" border="0"/></a></li>
	<li>
		<a href="#"><strong class="titulo_menu">&bull;Usuarios</strong><img src="images/usuarios.png" width="30" height="30" border="0"/></a>
		<ul>
			<li><a href="frm_agregarUsuario.php" target="mainFrame" style="color:#006600">&raquo;Registrar Usuario</a></li>
			<li><a href="frm_borrarUsuario.php" target="mainFrame" class="opciones" style="color:#006600">&raquo;Borrar Usuario</a></li>
			<li><a href="frm_modificarUsuario.php" target="mainFrame" class="opciones" style="color:#006600">&raquo;Modificar Usuario</a></li>
			<li><a href="frm_desbloquearEquipo.php" target="mainFrame" class="opciones" style="color:#006600">&raquo;Desbloquear Equipo</a></li>
			<li><a href="frm_consultarPassword.php" target="mainFrame" class="opciones" style="color:#006600">&raquo;Consultar Contrase&ntilde;a</a></li>
		</ul>
	</li>
	<li>
		<a href="#"><strong class="titulo_menu">&bull;Permisos</strong><img src="images/permisos.png" width="30" height="30" border="0"/></a>
		<ul>
			<li><a href="frm_registrarPermisos.php" target="mainFrame" class="opciones" style="color:#006600">&raquo;Modificar Permisos</a></li>
		</ul>
	</li>
	<li>
		<a href="#"><strong class="titulo_menu">&bull;Bit&aacute;cora</strong><img src="images/bitacora.png" width="30" height="30" border="0"/></a>
		<ul>
			<li><a href="frm_consultarBitacora.php" target="mainFrame" class="opciones" style="color:#006600">&raquo;Consultar Movimientos</a></li>
		</ul>
	</li>
	<li>
		<a href="#"><strong class="titulo_menu">&bull;Mi Cuenta</strong><img src="images/editar.png" width="30" height="30" border="0"/></a>
		<ul>
			<li><a href="frm_modificarPassword.php" target="mainFrame" class="opciones" style="color:#006600">&raquo;Editar Contrase&ntilde;a</a></li>
		</ul>
	</li>
	
</ul>
</div>

<div id="etiqueta" class="titulo_menu" align="center">Men&uacute;</div>
<div id="menu" style="position:absolute;top:40px;left:10px"></div>
<div id="clock">
	<label id="reloj" class="fecha"></label>
</div>

</body>
</html>