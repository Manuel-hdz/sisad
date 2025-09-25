<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarPruebas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:325px;height:20px;z-index:11;}
		#tabla-registrarPruebasFecha {position:absolute;left:30px;top:190px;width:294px;height:157px;z-index:14;}
		#tabla-registrarPruebasNombre {position:absolute;left:410px;top:190px;width:302px;height:157px;z-index:14;}
		#calendario-Ini {position:absolute;left:257px;top:232px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:257px;top:270px;width:30px;height:26px;z-index:14;}
		#detalle {position:absolute;left:30px;top:374px;width:680px;height:177px;z-index:17;overflow:scroll;}
		#boton {position:absolute;left:40px;top:604px;width:680px;height:40px;z-index:9;}
		-->
    </style>
</head>
<body><?php
		if(isset($_SESSION['nomAgregado']))
			unset($_SESSION['nomAgregado']);
		if(isset($_SESSION['infoAgregado']))
			unset($_SESSION['infoAgregado']);
		if(isset($_SESSION['pruebas']))		
			unset($_SESSION['pruebas']);?>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar Resultados de Pruebas a Agregados</div><?php
    // fieldset para la busqueda por fechas?>
    <fieldset class="borde_seccion" id="tabla-registrarPruebasFecha" name="tabla-registrarPruebasFecha">
	<legend class="titulo_etiqueta">Bucar Agregados por Fecha de Registro</legend>	
	<br>
	<form onSubmit="return valFormBuscarAgFecha(this);" name="frm_registrarPruebasAgregados" method="post" action="frm_registrarPruebasAgregados.php">
    <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="39%" ><div align="right">*Fecha Inicio</div></td>
            <td width="61%" ><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10"
                value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" readonly="readonly"/>
            </td>
        </tr>
        <tr>
            <td><div align="right">*Fecha Fin </div></td>
            <td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y");?>" 
                readonly="readonly"/></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input name="sbt_consultar" id="sbt_consultar" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
                title="Consultar Agregados por Fecha de Registro"/>
                &nbsp;&nbsp;&nbsp;
                <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='frm_registrarPruebas.php';"
                title="Regresar a la Pantalla Anterior"/>
            </td>
        </tr>
    </table>
    </form>
    </fieldset><?php
    // fieldset para la busqueda por Nombre?>
    <fieldset class="borde_seccion" id="tabla-registrarPruebasNombre" name="tabla-registrarPruebasNombre">
    <legend class="titulo_etiqueta">Bucar Agregados por Nombre</legend>	
    <br>
    <form onSubmit="return valFormBuscarAgNombre(this);" name="frm_registrarPruebasAgregados2" method="post" action="frm_registrarPruebasAgregados.php">
    <table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="83"><div align="right">*Agregado</div></td>
            <td width="440"><?php 
            $result=cargarComboEspecifico("cmb_agregado","nom_material","materiales","bd_almacen","AGREGADO","linea_articulo","Agregado","");
            if($result==0){
	            echo "<label class='msje_correcto'><u><strong> NO</u></strong> Hay Agregados Registrados</label>";
				
            }?>
            </td>
        </tr>  
        <tr> 
    	    <td>&nbsp;</td>
        </tr>
        <tr>
        	<td colspan="2" align="center"><?php 
				if($result==1){?>
                    <input name="sbt_consultar2" id="sbt_consultar2" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
                    title="Consultar Agregado por Nombre"/>
                    &nbsp;&nbsp;&nbsp;<?php
				}?>	
    	    	<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='frm_registrarPruebas.php';"
    	    	title="Regresar a la Pantalla Anterior"/>
        	</td>
        </tr>
    </table>
    </form>
	</fieldset>
    
    <div id="calendario-Ini">
      <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_registrarPruebasAgregados.txt_fechaIni,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Inicio"/> 
    </div>
    
    <div id="calendario-Fin">
      <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_registrarPruebasAgregados.txt_fechaFin,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Fin"/> 
    </div><?php
	
	if(isset($_POST['sbt_consultar']) || isset($_POST['sbt_consultar2']) ){?>
        <form onSubmit="return valFormRegistrar(this);" name="frm_registrarPruebasAgregados1" method="post" action="frm_registrarAgregados.php">
            <div id='detalle' class='borde_seccion2' align="center"><?php
                $control = mostrarAgregados();?>
            </div>
            <?php
            
            //Verificar si el resultado de la busqueda arroja resultados para mostrar el boton de continuar
            if ($control==1){?>
                <div id='boton' align="center">
                    <input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar" value="Continuar" 
                    onmouseover="window.status='';return true" title="Continuar" />
                </div><?php
            }?>
        </form><?php
	}?>
</body>

<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>