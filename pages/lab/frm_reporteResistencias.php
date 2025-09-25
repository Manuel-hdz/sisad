<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml"><?php

	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion de laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_reporteResistencias.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-generar {position:absolute;left:30px;top:146px;width:341px;height:20px;z-index:11;}
		#tabla-generarReporte {position:absolute;left:30px;top:190px;width:510px;height:150px;z-index:14;}
		#detalle_mezcla {position:absolute;left:30px;top:371px;width:906px;height:177px;z-index:17;overflow:scroll;}
		#detalle_mezclaSel {position:absolute;left:30px;top:190px;width:900px;height:360px;z-index:21;overflow:scroll;}
		#btn-regresardetalle {position:absolute;left:47px;top:600px;width:946px;height:40px;z-index:9;}
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-generar">Generar Reporte de Resistencias</div><?php 
	if(!isset($_POST['ckb_idMuestra'])){?>
        <fieldset class="borde_seccion" id="tabla-generarReporte" name="tabla-generarReporte">
        <legend class="titulo_etiqueta">Seleccionar Muestra para Generar Reporte de Resistencias</legend>	
        <br>
        <form onSubmit="return valFormReporteResistencias(this);" name="frm_reporteResistencias" method="post" action="frm_reporteResistencias.php">
        <table width="568" cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
              	<td width="96"><div align="right">*Tipo Prueba</div></td>
                <td width="435"><?php 
                    //Conectar a la BD de laboratorio
                    $conn = conecta("bd_laboratorio");
                    $sql_stm = "SELECT DISTINCT tipo_prueba FROM muestras"; 
                    $rs = mysql_query($sql_stm);?>
                    <select name="cmb_tipoPrueba" id="cmb_tipoPrueba" class="combo_box" 
                        onchange="cargarCombo(this.value,'bd_laboratorio','muestras','id_muestra','tipo_prueba','cmb_idMuestra','ID Muestra','')">
                        <option value="" selected="selected">Seleccione</option><?php
                            if($datos=mysql_fetch_array($rs)){
                                do{ 
                                    echo "<option value = '$datos[tipo_prueba]'>$datos[tipo_prueba] </option>";							
                                }while($datos=mysql_fetch_array($rs));
                            }?>
					</select>         
              	</td>
            </tr>
            <tr>         
              	<td width="96"><div align="right">*Clave</div></td>
                <td width="435">
                    <select name="cmb_idMuestra" id="cmb_idMuestra" class="combo_box">
                        <option value="">ID Muestra</option>
                    </select>
              	</td>			
            </tr>  
            <tr>
                <td colspan="2" align="center">
                  	<input name="sbt_continuar" id="sbt_continuar" type="submit" class="botones" value="Continuar" onmouseover="window.status='';return true" 
                    title="Continuar Mezclas"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_reportes.php';"
                    title="Regresar al men&uacute; de Reportes"/>
                </td>
            </tr>
        </table>
        </form>
        </fieldset><?php
		
		
		
		if(isset($_POST['sbt_continuar'])){?>
            <form name="frm_detalleMuestra" method="post" action="frm_reporteResistencias.php">
                <input type="hidden" name="cmb_idMuestra" value="<?php echo $_POST['cmb_idMuestra'];?>" />
                <input type="hidden" name="cmb_tipoPrueba" value="<?php echo $_POST['cmb_tipoPrueba'];?>" />
                <input type="hidden" name="sbt_continuar" value="Continuar" />                            
                <div id='detalle_mezcla' class='borde_seccion2' align="center"><?php
                    $control = mostrarMuestras();?>
                </div>
            </form><?php
		}
		
		
		
	}//FIN if(!isset($_POST['ckb_idMezcla']))	
	else {?>    
        <div id="detalle_mezclaSel" class="borde_seccion2" align="center"><?php
            //Mostrar el detalle  Seleccionado
            $id = mostrarDetalleMezcla();?>   
        </div><?php
			
		// recuperar los valores de la consulta para que el boton que nos permite regresar a la consulta de las mezclas?>
		<div id="btn-regresardetalle" align="center">
		<form name="" action="frm_reporteResistencias.php" method="post"><?php
			if (isset($_POST['hdn_continuar'])){?>
				<input type="hidden" name="cmb_idMuestra" value="<?php echo $_POST['hdn_idMuestra'] ?>"/>
				<input type="hidden" name="cmb_tipoPrueba" value="<?php echo $_POST['hdn_tipoMuestra'] ?>"/>
				<input type="hidden" name="sbt_continuar" value="<?php echo $_POST['hdn_continuar'] ?>"/><?php
			}?>
			<input name="sbt_regresarRepMez" type="submit" class="botones" value="Regresar" title="Regresar a la Consulta de Mezclas" 
			onmouseover="window.estatus='';return true" id="sbt_regresarRepMez"/><?php
			if($id!=""){?>
				<form name="frm_generarReporteMezclas" method="post">
					<input type="hidden" name="hdn_id" id="hdn_id" value="<?php echo $id; ?>" />
					
					<input name="btn_generarRep" type="button" class="botones" value="Generar Reporte" title="Ver Reporte Fotográfico" 
					onmouseover="window.status='';return true" onclick="recolectarDatos();"/>
				</form><?php
			}?>
			
		</form>
        </div><?php		
	}?>	
</body><?php
}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>