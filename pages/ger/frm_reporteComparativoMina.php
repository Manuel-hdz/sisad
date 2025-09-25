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
		include ("op_reporteComparativoMina.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css"/>

    <style type="text/css">
		<!--
		#titulo-generar {position:absolute;left:30px;top:146px;width:279px;height:20px;z-index:11;}
		#tabla-generarRepoAnio {position:absolute;left:30px;top:190px;width:430px;height:150px;z-index:14;}
		#tabla-mostrarReportes {position:absolute;left:30px;top:190px;width:333px;height:380px;z-index:14; overflow:scroll}
		#btns{position:absolute;left:30px;top:624px;width:900px;height:38px;z-index:14;}
		#grafica{position:absolute;left:420px;top:190px;width:590px;height:420px;z-index:15;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-generar">Reporte Comparativo de Minas</div><?php
	//Verificar si se ha presionado el boton de consultar para llamar la función que nos mostrará la información
	if(isset($_POST['sbt_consultarAnio'])){?>
    	<form action="guardar_reporte.php" method="post">
            <div id="tabla-mostrarReportes" class="borde_seccion2"><?php
				//El arreglo contendra las sig posiciones.  0=>cmb_ubicacion,  1=>cmb_anios,  2=>msje de la grafica, 3=>Nombre de la gráfica>
                $arreglo_Inf=mostrarReporte();?>
            </div>
            <div id=grafica>
                <img src="<?php echo $arreglo_Inf[3]; ?>" width="100%" height="100%" border="0" 
                onclick="window.open('verGraficaCompMina.php?nombre=<?php echo $arreglo_Inf[3]; ?>', '_blank','top=100, left=100, width=1000, height=500, status=no, menubar=no, resizable=no, scrollbars=no, toolbar=no, location=no, directories=no')" title="Click para Ampliar la Im&aacute;gen"/>
            </div>
            <div id="btns" align="center">
                <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='frm_reporteComparativoMina.php';"
                title="Regresar a la Pantalla Anterior"/>
                &nbsp;&nbsp;&nbsp;	
                <input name="hdn_tipoReporte" type="hidden" value="reporteComparativoMina"/>
                <input name="hdn_destino" type="hidden" value="<?php echo $arreglo_Inf[0];?>"/>
				<input name="hdn_anio" type="hidden" value="<?php echo $arreglo_Inf[1];?>"/>
				<input name="hdn_msg" type="hidden" value="<?php echo $arreglo_Inf[2];?>"/>                							
                <input name="hdn_nomGrafica" type="hidden" value="<?php echo $arreglo_Inf[3];?>"/>                							
                <input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
                title="Exportar los Datos de la Consulta Realizada" onmouseover="window.estatus='';return true"/>
            </div>
		</form><?php
	}
	if(!isset($_POST['sbt_consultarAnio'])){
		// fieldset para generar el reporte cuando se esta seleccionando un rango de fechas?>
        <fieldset class="borde_seccion" id="tabla-generarRepoAnio" name="tabla-generarRepoAnio">
        <legend class="titulo_etiqueta">Seleccionar A&ntilde;o</legend>	
		<br>
        <form onSubmit="return valFormGenRepoCompMinAnio(this);" name="frm_reporteAnio" method="post" action="frm_reporteComparativoMina.php">
        <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td width="20%"><div align="right">Ubicaci&oacute;n</div></td>
				<td colspan="2"><?php
					$res=cargarCombo('cmb_ubicacion','destino','bitacora_zarpeo','bd_gerencia','Ubicaci&oacute;n','');
					if($res==0){
						echo "<label class='msje_correcto'>No hay Ubicaciones Registradas</label>
						<input type='hidden' name='cmb_ubicacion' id='cmb_ubicacion' disabled='disabled'/>";
					}?> 
                </td>         
            </tr>    
            <tr>
                <td width="20%"><div align="right">A&ntilde;o</div></td>
				<td><?php cargarAniosDisponibles(); ?></td>
			</tr>             
			<tr>
				<td align="center" colspan="2">
					<input name="sbt_consultarAnio" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
					title="Consultar"/>
					&nbsp;&nbsp;&nbsp;
					<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_reportes.php';"
					title="Regresar al men&uacute; de Reportes"/>
				</td>
			</tr>
        </table>
		</form>
		</fieldset><?php
	}//FIN if(!isset($_POST['sbt_consultarAnio']))?>
</body><?php
 }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>