<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Mantenimiento
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		
		include ("op_registrarBitacora.php");
?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<script type="text/javascript" src="../../includes/validacionMantenimiento.js" ></script>   
   
    <style type="text/css">
		<!--		
			#titulo-detalle {	position:absolute;	left:30px;	top:146px;	width:329px;	height:23px;	z-index:10;}
			#tabla-detalle-materiales{	position:absolute;	left:30px;	top:204px;	width:900px;	height:277px;	overflow:scroll;	z-index:16;}
			#btns-regpdf {	position: absolute;	left:31px;	top:545px;	width:941px;	height:40px;	z-index:23;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-detalle">Materiales Utilizados en el Matenimiento</div>
	<legend class="titulo_etiqueta"> Materiales Utilizados en el Matenimiento</legend>
 	
   	
	<div id='tabla-detalle-materiales' align="center" class="borde_seccion2"><?php 
		$noVale=$_GET["vale"];
		mostrarDetalle($noVale);?>
	</div>	
    
	<div id="btns-regpdf" align="center">
		<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar Consultar Bit&aacute;cora" 
		onMouseOver="window.estatus='';return true" 
		onclick="location.href='frm_regMatMtto.php?vale=<?php echo $noVale;?>'"  />
	</div>
         
</body>          
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>