<?php
	//Este archivo tiene la funcion para eliminar Archivos Cargados al Servidor, en caso que no se haya terminado el proceso de guardado y se cierre la sesion
	
	//Modulo de conexion a la Base de Datos
	include("../includes/conexion.inc");
	
	//Aqui se da de baja la variables de sesion que se registraron cuando el usuario inicio sesion	
	session_start();
	//Esta comprobacion es para verificar si se agregaron documentos a los Equipos de Mantenimiento y por alguna razon no se termino el proceso de Agregado, 
	//entonces eliminamos los que se hayan cargado para evitar inconsitencias de informacion
	if (isset($_SESSION["docTemporal"])){
		//include ("../includes/operacionesGenerales.php");
		include ("man/op_agregarEquipo.php");
		borrarArchivosExtremo();
	}
	if (isset($_SESSION["fotos"])){
		include_once("man/op_registrarBitacora.php");
		borrarFotosSesion();
	}
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias para GERENCIA
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="Gerencia"){
		include_once("ger/op_generarRequisicion.php");
		borrarFotosSesionGerencia();
	}
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias para TOPOGRAFÍA
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="Topografia"){
		include_once("top/op_generarRequisicion.php");
		borrarFotosSesionTopografia();
	}
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias para PRODUCCIÓN
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="Produccion"){
		include_once("pro/op_generarRequisicion.php");
		borrarFotosSesionProduccion();
	}
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias para RECURSOS HUMANOS
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="RecursosHumanos"){
		include_once("rec/op_generarRequisicion.php");
		borrarFotosSesionRecursos();
	}
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias para MANTENIMIENTO (concreto)
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="MttoConcreto"){
		include_once("man/op_generarRequisicion.php");
		borrarFotosSesionMantenimiento();
	}
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias para MANTENIMIENTO (mina)
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="MttoMina"){
		include_once("man/op_generarRequisicion.php");
		borrarFotosSesionMantenimiento();
	}
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias para LABORATORIO
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="Laboratorio"){
		include_once("lab/op_generarRequisicion.php");
		borrarFotosSesionLaboratorio();
	}
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias a ALMACÉN
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="Almacen"){
		include_once("alm/op_generarRequisicion.php");
		borrarFotosSesionAlmacen();
	}
	
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias a DESARROLLO
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="Desarrollo"){
		include_once("des/op_generarRequisicion.php");
		borrarFotosSesionDesarrollo();
	}
	
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias a ASEGURAMIENTO CALIDAD
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="Desarrollo"){
		include_once("ase/op_generarRequisicion.php");
		borrarFotosSesionAseguramiento();
	}
	
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias a SEGURIDAD INDUSTRIAL
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="Seguridad"){
		include_once("seg/op_generarRequisicion.php");
		borrarFotosSesionSeguridad();
	}
	
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias a SEGURIDAD AMBIENTAL
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="SeguridadAmbiental"){
		include_once("pai/op_generarRequisicion.php");
		borrarFotosSesionSeguridad();
	}
	
	//Si existe el arreglo fotos Req llamamos a la funcion que permite eliminar las fotografias a PAILERIA
	if (isset($_SESSION["fotosReq"])&&$_SESSION["depto"]=="Paileria"){
		include_once("pai/op_generarRequisicion.php");
		borrarFotosSesionPaileria();
	}
	
	//verificar si el arreglo de fotosPruebas esta dedinido si es asi borrar las fotografias de laboratorio
	if(isset($_SESSION["fotosPruebas"])){
		include_once("lab/op_registrarPruebas.php");
		borrarFotosExtremoLab();
	}
	
	//Borrar los archivos temporales de módulo de gerencia técnica
	if(isset($_SESSION['depto']) && $_SESSION['depto']=="GerenciaTecnica"){
		include_once("ger/op_reporteComparativoMina.php");
		borrarTemporales();
	}
	
	//Para las Entradas de Almacen, Eliminar los materiales registrados, cuando el proceso de regitro no se haya finalizado correctamente o se haya cerrado la sesion
	if(isset($_SESSION['procesoRegistroMat']) && $_SESSION['procesoRegistroMat']=="NoTerminado"){ 			
		include_once("../includes/op_operacionesBD.php");
		deshacerCambios($_SESSION['clavesRegistradasMat']);						
	}
		
	if(session_is_registered('usr_reg')){		
		session_destroy();
	} else {
		header("Location: login.php?usr_sts=unr");
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<!-- Aqui se redirecciona a la pagina de index.html al momento de salir -->
	<meta http-equiv="refresh" content="5;url=login.php?usr_sts=accinc"> 
	<style type="text/css">
		<!--
		body { background-image: url(../images/bk2.jpg);}
		.style12 {font-family: Arial, Helvetica, sans-serif; color: #000000; }
		.style15 {font-family: MicrogrammaDMedExt; color: #33761B; font-size: 13px; font-weight: bold; }
		.cerrar-sesion {font-size: 18px; font-family: Arial, Helvetica, sans-serif; font-weight: bold; color: #FFFFFF; }
		#fondo-titulo {position:absolute; left:0px; top:0; width:1035px; height:54px; z-index:1 }
		#titulo {position:absolute; left:180px; top:19px; width:658px; height:25px; z-index:2 }
		#fondo-login {position:absolute; left:290px; top:225px; width:378px; height:225px; z-index:3 }
		#logo {position:absolute; left:395px; top:248px; width:203px; height:103px; z-index:4 }
		#cerrar-sesion {position:absolute; left:426px; top:360px; width:174px; height:31px; z-index:5; }		
		-->
	</style>
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
</head>
<body>
	<div id="fondo-titulo"><img src="../images/dock-bg2.gif" width="1035" height="51" /></div>
	<div id="titulo" align="center"><span class="style15">Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</span></div>
	<div id="cerrar-sesion" align="center">  
  		<p><span class="cerrar-sesion">Cerrando Sesi&oacute;n...</span></p>
  		<p><img src="../images/cargando.gif" width="170" height="18" /></p>
</div>   
	<div id="fondo-login"><img src="../images/login.png" width="451" height="211" /></div>
    <div id="logo"><img src="../images/logo.png" width="230" height="100" /></div>
</body>
</html>
