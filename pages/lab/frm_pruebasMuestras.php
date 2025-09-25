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
		include ("op_pruebasMuestras.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
		<!--
		#titulo-consultar {position:absolute;left:30px;top:146px;width:368px;height:20px;z-index:11;}
		#tabla-consultarMezclaFecha {position:absolute;left:40px;top:190px;width:360px;height:151px;z-index:12;}
		#tabla-consultarMezclaClave {position:absolute;left:478px;top:190px;width:450px;height:151px;z-index:12;}
		#calendario-Ini {position:absolute;left:277px;top:232px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:277px;top:270px;width:30px;height:26px;z-index:14;}
		#detalle_mezcla {position:absolute;left:30px;top:370px;width:940px;height:200px;z-index:17;overflow:scroll;}
		#boton {position:absolute;left:30px;top:620px;width:940px;height:40px;z-index:9;}
		-->
    </style>
</head>
<body><?php

	//Obtener la fecha del sistema para la fecha inicio y fecha fin
	$txt_fechaIni = date("d/m/Y", strtotime("-30 day"));
	$txt_fechaFin = date("d/m/Y");?>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-consultar">Programar Fechas de Pruebas a las Muestras</div>
	    
    
    <?php // fieldset para manipulacion de mezclas buscadas por fecha?>	
    <fieldset class="borde_seccion" id="tabla-consultarMezclaFecha">
    <legend class="titulo_etiqueta">Consultar por Fecha de Colado</legend>	
    <br>
    <form onSubmit="return valFormConsultarMuestrasFecha(this);" name="frm_consultarMuestrasFecha" method="post" action="frm_pruebasMuestras.php">
    <table width="372" height="36" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="106"><div align="right">Fecha Inicio</div></td>
            <td width="229"><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10"value="<?php echo $txt_fechaIni;?>" 
                readonly="readonly"/>
            </td>
        </tr>
        <tr>
            <td><div align="right">Fecha Fin </div></td>
            <td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaFin;?>" 
            readonly="readonly"/></td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input name="sbt_consultarMF" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
                title="Consultar Muestras en el Rango de Fechas Seleccionado"/>
                &nbsp;&nbsp;&nbsp;
                <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_mezclas.php';"
                title="Regresar al men&uacute; de Mezclas"/>
            </td>
        </tr>
    </table>
    </form>   
    </fieldset>		
    
    <div id="calendario-Ini">
      <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_consultarMuestrasFecha.txt_fechaIni,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Inicio"/> 
    </div>
    
    <div id="calendario-Fin">
      <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_consultarMuestrasFecha.txt_fechaFin,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Fin"/> 
    </div>
	
	
	<?php // fieldset para manipulacion de mezclas buscadas por su clave ?>	
    <fieldset class="borde_seccion" id="tabla-consultarMezclaClave" name="tabla-consultarMezclaClave">
    <legend class="titulo_etiqueta">Consultar  por Clave </legend>	
    <br>
    <form onSubmit="return valFormConsultarMuestraClave(this);" name="frm_consultarMuestraClave" method="post" action="frm_pruebasMuestras.php">
	<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
			<td align="right">Codigo/Localizaci&oacute;n</td>
			<td><?php 
				$res = cargarComboConId("cmb_codLocalizacion","codigo_localizacion","codigo_localizacion","muestras","bd_laboratorio","Seleccionar","",
				"cargarCombo(this.value,'bd_laboratorio','muestras','id_muestra','codigo_localizacion','cmb_idMuestra','Muestra','')");
				if($res==0){?>
					<span class="msje_correcto">No Hay Datos Registrados</span><?php
				}?>
			</td>
		</tr>
		<tr>
			<td align="right">Muestra</td>
			<td>
				<select name="cmb_idMuestra" id="cmb_idMuestra" class="combo_box">
					<option value="">Muestra</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="2" align="center"> 
				<input name="sbt_consultarMC" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" title="Consultar Muestra Seleccionada"/>
				&nbsp;&nbsp;&nbsp;				
				<input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_mezclas.php';"
                title="Regresar al men&uacute; de Mezclas"/>
			</td>
		</tr>
	</table>					    
    </form>
	</fieldset><?php
    
	
	
    //Si esta definido sbt_consultarMF o sbt_consultarMC mostrar las muestran que coninsidan con los parametros seleccionados
    if(isset($_POST['sbt_consultarMF']) || isset($_POST['sbt_consultarMC'])){?>
        <form onSubmit="return valFormConsultarDetMuestra(this);" name="frm_consultarDetMuestra" method="post" action="frm_pruebasMuestras2.php"><?php            
            if(isset($_POST["sbt_consultarMF"])){ ?>
                <input type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni'];?>" />
                <input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin'];?>" />
                <input type="hidden" name="sbt_consultarMF" value="Consultar" /><?php
             }
			 if(isset($_POST["sbt_consultarMC"])){ ?>
                <input type="hidden" name="cmb_claveMuestra" value="<?php echo $_POST['cmb_idMuestra'];?>" />
                <input type="hidden" name="sbt_consultarMC" value="Consultar"/><?php
             }?>
			 
            <div id='detalle_mezcla' class='borde_seccion2' align="center"><?php
                $control = mostrarMuestras();?>
            </div>
            <?php
                    
            //Verificar si el resultado de la busqueda arroja resultados para mostrar el boton de modificar
            if ($control==1){?>
                <div id='boton' align="center">
                    <input name="sbt_continuar" type="submit" class="botones" id="sbt_continuar" value="Continuar" 
                    onmouseover="window.status='';return true" title="Continuar" />
          </div><?php
             }?>
        </form><?php
    }// FIN if(isset($_POST['sbt_consultar2']) || isset($_POST['sbt_consultar']))?>
	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>