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
		//Este archivo el detalle de la Caja Chica seleccionada
		include ("op_consultarCajaChica.php");

	
?><head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></LINK>
	<SCRIPT type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="../../includes/jquery-1.2.1.pack.js"></script>
	<script type="text/javascript" src="../../includes/validacionCompras.js" ></script>
   
   	<style type="text/css">
		<!--
		#titulo-consultaCajaChica { position:absolute;	left:30px; top:146px; width:163px; height:17px;	z-index:11;	}
		#tabla-consultaCajaChica {position:absolute;left:30px;top:190px;width:388px;height:150px;	z-index:13;}		
		#detalle-cajaChica {position:absolute;left:30px;top:190px;width:940px;height:400px;	z-index:12;overflow:scroll;}
		#btns-regpdf { position: absolute; left:320px; top:650px; width:400px; height:40px; z-index:14; }
		#stb_consultar{position: absolute; left:280px; top:630px; width:400px; height:40px; z-index:14;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultaCajaChica">Consulta de Caja Chica </div>

    <?php if(isset($_POST['sbt_consultar'])){
	/*if( ((isset($_POST['cmb_anio']) && $_POST['cmb_anio']!="") && (isset($_POST['cmb_mes']) && $_POST['cmb_mes']!=""))){*/
		//Desplegarel detalle de la Caja Chica Seleccionada 
		?><div id="detalle-cajaChica" class="borde_seccion2" align="center"><?php
		if(isset($_POST["hdn_cont"])){
			$num = $_POST['hdn_cont'];
			//Quitar la coma a la diferencia y al total de gastos del movimiento, para poder realziar la operaciones requeridas.
			$txt_dif=str_replace(",","",$_POST["txt_dif".$num]);
			$txt_totalGastos=str_replace(",","",$_POST["txt_totalGastos".$num]);
			//Actuzalizar el movimiento seleccionado
			$msg = actualizarMovimiento($_POST["cmb_mes"],$_POST["hdn_NoMov".$num],$_POST["txt_factura".$num],$_POST["txa_descripcion".$num],
			$txt_dif,$txt_totalGastos);
		}
		mostrarCajaChica($_POST['cmb_mes']);
		?></div>
    <?php		
	}//Cierre if(!isset($_POST['cmd_mes']))
	else{?>                    
	<fieldset class="borde_seccion" id="tabla-consultaCajaChica" name="tabla-consultaCajaChica" >
	<legend class="titulo_etiqueta">Consultar Caja Chica</legend>
	<form onsubmit="return valFormConsultarCajaChica(this);" name="frm_consultarCajaChica" method="post" action="frm_consultarCajaChica.php">
    <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
   	 		<td align="right" width="20%">A&ntilde;o</td>
			<td width="20%"><?php
				 if(!isset($_POST['cmb_anio']))
					$cmb_anio = "";					
				 cargarComboAnios($cmb_anio);?>               	 		
      		</td>
           	<td align="center">
       	  		<input type="submit" name="sbt_consultar" value="Consultar" class="botones" title="Consultar Caja Chica"
            	onmouseover="window.status='';return true" />        
	  		</td>              
  	 	</tr>
		<tr>
		<?php if(isset($_POST['cmb_anio'])){ ?>		
			<td align="right">Mes</td>
			<td><?php cargarComboMeses($cmb_anio);?></td>		
        <?php } else { ?>		
       	  	<td colspan="2">&nbsp;</td>
        <?php } ?>
			<td align="center">
				<input name="btn_regresar" type="button" value="Regresar" class="botones" title="Regresar al Men&uacute; de Caja Chica"
       			onclick="location.href='menu_cajaChica.php'" />
			</td>
		</tr>         	 		
	</table>
   </form>
		
</fieldset>
    <?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>