<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Gerencia Técnica
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{ 
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo muestra los pedidos registrados en la BD
		include ("op_consultarPedido.php");?>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<style type="text/css">
		<!--
		#titulo-consultaPedido { position:absolute;	left:30px; top:146px; width:163px; height:22px;	z-index:11;	}
		#tabla-pedido { position:absolute; left:30px; top:190px; width:291px; height:170px; z-index:13;	}
		#calendarioInicio {position:absolute; left:272px; top:232px; width:30px; height:26px; z-index:14; }
		#calendarioFin {position:absolute; left:272px; top:271px; width:30px; height:26px; z-index:15; }
		#resultados{position:absolute; left:30px; top:400px; width:900px; height:255px; z-index:16; overflow:scroll;}
		#detalle{position:absolute; left:30px; top:190px; width:900px; height:420px; z-index:17; overflow:scroll;}
		#botones { position:absolute; left:30px; top:670px; width:900px; height:37px; z-index:17; }
		-->
   	</style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultaPedido">Consulta de Pedidos </div>
	<?php if(!isset($_POST["ckb_idPedido"])){?>
	<fieldset class="borde_seccion" id="tabla-pedido" name="tabla-pedido" >
		<legend class="titulo_etiqueta"> Consultar Pedidos de Gerencia T&eacute;cnica</legend>
		<br />
		<form name="frm_consultadePedido" onsubmit="return valFormFechasPedidos(this);" method="post" action="frm_consultarPedido.php">
		<table align="center" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%" >
			<tr>
				<td align="right">Fecha Inicio</td>
				<td>
					<input name="txt_fechaIni" type="text" id="txt_fechaIni" value="<?php echo date("d/m/Y", strtotime("-30 day")); ?>" size="10" maxlength="15" readonly="true" width="90"/>
				</td>
			</tr>
			<tr>
				<td align="right">Fecha Fin</td>
				<td>
					<input name="txt_fechaFin" type="text" id="txt_fechaFin" value="<?php echo date("d/m/Y");?>" size="10" maxlength="15" readonly="true" width="90"/>
				</td>    	    
			</tr>
			<tr>
				<td colspan="2" align="center">
				<br />
					<input name="sbt_consultar" type="submit" class="botones" id="btn_pedido"  value="Consultar" onmouseover="window.status='';return true;" title="Consultar Pedido" />&nbsp;&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Requisiciones" onclick="location.href='menu_requisiciones.php'"/>
				</td>
			</tr>
		</table>
		</form>
</fieldset>  
	
		<?php //Declaracion de los layers con los calendarios?>
		<div id="calendarioInicio">
			<input name="calendario_inicio" type="image" id="calendario_inicio" onclick="displayCalendar(document.frm_consultadePedido.txt_fechaIni,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
</div>		
		
		<div id="calendarioFin">
			<input name="calendario_fin" type="image" id="calendario_fin" onclick="displayCalendar(document.frm_consultadePedido.txt_fechaFin,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
</div>
	<?php }
	if(isset($_POST["sbt_consultar"]) || isset($_GET["f1"])){
		if(isset($_POST["sbt_consultar"])){
			$f1=$_POST["txt_fechaIni"];
			$f2=$_POST["txt_fechaFin"];
		}
		else{
			$f1=$_GET["f1"];
			$f2=$_GET["f2"];
		}?>
		<div id="resultados" class="borde_seccion2">
		<form name='frm_pedidos' method='post' action="frm_consultarPedido.php">
		<input type="hidden" name="hdn_fechaIni" value="<?php echo $f1;?>"/>
		<input type="hidden" name="hdn_fechaFin" value="<?php echo $f2;?>"/><?php
		mostrarPedidos($f1,$f2);?>
		</form>
		</div><?php
	}
	if(isset($_POST["ckb_idPedido"])){?>
		<div id="detalle" class="borde_seccion2"><?php 
			$idPedido=mostrarDetallePedido();?>
		</div>
		<div id="botones" align="center"><?php
		$f1=$_POST["hdn_fechaIni"];
		$f2=$_POST["hdn_fechaFin"];?>
		<input name="btn_pedido" type="button" class="botones" value="Ver PDF" title="Mostrar PDF del Pedido" 
        onClick="window.open('../../includes/generadorPDF/pedido2.php?id=<?php echo $idPedido; ?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>
		&nbsp;&nbsp;&nbsp;&nbsp;
		<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Requisiciones" 
        onClick="location.href='frm_consultarPedido.php?f1=<?php echo $f1;?>&f2=<?php echo $f2;?>'"/>
</div><?php
	}?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>