<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <link rel="shortcut icon" href="../images/SisadWin.ico">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script language="javascript" type="text/javascript" src="../includes/disableKeys.js"></script>
	<script language="JavaScript" type="text/JavaScript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script>
	<style type="text/css">
		<!--
		body { background-image: url(../images/bk2.jpg);}
		.mensajes {font-family: Arial, Helvetica, sans-serif; color: #BF0000; font-size: 12px; font-weight: bold; }
		.titulo-login {font-family: MicrogrammaDMedExt; color: #33761B; font-size: 13px; font-weight: bold; }
		.datos-frm {font-family: Arial, Helvetica, sans-serif; color: #FFFFFF; font-weight: bold; }
		-->
	</style>
</head>
<body onload="document.getElementById('txt_usuario').focus();">

	<div id="fondo-titulo" style="position:absolute; left:0px; top:0; width:1035px; height:54px; z-index:1"><img src="../images/dock-bg2.gif" width="1035" height="51" /></div>
	<div id="titulo" style="position:absolute; left:180px; top:19px; width:658px; height:25px; z-index:2">
		<div align="center"><span class="titulo-login">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</span></div>
</div>
	<div id="fondo-login" style="position:absolute; left:290px; top:225px; width:378px; height:225px; z-index:3"><img src="../images/login.png" width="451" height="211" /></div>
	<div id="logo" style="position:absolute; left:395px; top:248px; width:203px; height:103px; z-index:4"><img src="../images/logo.png" width="230" height="100" /></div>

	<div id="form-datos" style="position:absolute; left:345px; top:353px; width:287px; height:86px; z-index:5">  
		<form action="autentificar.php" method="post" name="frm_login">
			<table width="287" border="0" align="center">
				<tr>
					<td width="90"><div align="right" class="datos-frm"><strong>Usuario</strong></div></td>
					<td width="186">
						<input type="text" name="txt_usuario" id="txt_usuario" size="20" maxlength="20"/>
			        </td>
				</tr>
      			<tr>
        			<td><div align="right" class="datos-frm">Contrase&ntilde;a</div></td>
        			<td><input type="password" name="txt_clave" size="20" maxlength="30"/></td>
      			</tr>
    		</table>
			<div id="img-acceso" style="position:absolute; left:260px; top:-3px; width:65px; height:70px; z-index:6">
				<?php
					if ($_GET['usr_sts']!="hit"&&$_GET['usr_sts']!="err")
						$src = "../images/lock.png";
					if ($_GET['usr_sts']=="hit") 
						$src = "../images/tiempo.png";
					if ($_GET['usr_sts']=="err") 
						$src = "../images/lock-err.png";
				?>
				<input type="image" name="img_enviar"  src="<?php echo "$src"; ?>" width="37" height="71" border="0" 
				 onMouseOver="window.status='';return true" title="Entrar" />				 
		  </div>
  		</form>
</div>
	<div id="mensajes" style="position:absolute; left:440px; top:402px; width:246px; height:32px; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#990000; font-weight:bold z-index:7;">
	<?php
		if ($_GET["usr_sts"]=="err"){
			echo "<p align='center'><div align='left' class='mensajes'>* Usuario &oacute; Contrase&ntilde;a Invalidos</div></p>";
			$fllo = session_name("fllo");
			session_start();
			if (isset($_SESSION["intento"])&&$_SESSION["intento"]>3)
				echo "<p align='center'><div align='left' class='mensajes'>* Te queda un intento más</div></p>";
		}
	
		if ($_GET["usr_sts"]=="unr")
			echo "<p align='center'><div align='left' class='mensajes'>* Debes Autentificarte</div></p>";
	
		if ($_GET["usr_sts"]=="tme")
			echo "<p align='center'><div align='left' class='mensajes'>* Expiro el Tiempo de la Sesi&oacute;n</div></p>";

		if ($_GET["usr_sts"]=="ssinc")
			echo "<p align='center'><div align='left' class='mensajes'>* Existe otra sesion iniciada en este Equipo</div></p>";
			
		if ($_GET["usr_sts"]=="hit"){
			echo "<p align='center'><div align='left' class='mensajes'>* Ha Excedido el N&uacute;mero de Intentos Permitidos Espere 15 min</div></p>";
		}
	?>	
	</div> 
</body>
</html>
