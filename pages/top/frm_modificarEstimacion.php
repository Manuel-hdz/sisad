	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Topografía
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include ("op_modificarEstimacion.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
    <script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<script type="text/javascript" src="includes/ajax/verificarQuincena.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboQuincena.js"></script>		
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
		<!--
		#tabla-buscarObra {position:absolute;left:30px;top:190px;width:428px;height:150px;z-index:12;}
		#titulo-modificar {position:absolute;left:30px;top:146px;width:210px;height:20px;z-index:11;}
		#tabla-registrarEstimacion {position:absolute;left:30px;top:190px;width:847px;height:425px;z-index:12;}
		#calendario-IniBusq {position:absolute;left:295px;top:232px;width:30px;height:26px;z-index:13;}
		#calendario-FinBusq {position:absolute;left:295px;top:270px;width:30px;height:26px;z-index:14;}
		#detalle_estimacion {position:absolute;left:30px;top:421px;width:940px;height:171px;z-index:17;overflow:scroll;}
		#btn-modificar {position:absolute;left:30px;top:644px;width:987px;height:40px;z-index:9;}
		#calendarioElaboracion {position:absolute;left:748px;top:235px;width:30px;height:26px;z-index:13;}
		#tabla-modificarEstimacionQuincena {position:absolute;left:30px;top:190px;width:420px;height:200px;z-index:12;}
		#tabla-modificarEstimacionMes {position:absolute;left:550px;top:190px;width:420px;height:200px;z-index:12;}
		-->
    </style>
</head>
<body><?php

	if (isset($_POST['sbt_modificar'])){
		modificarEstimacionSeleccionada();
	}
	
	//Obtener la fecha del sistema para la fecha inicio y fecha fin
	$txt_fechaIni = date("d/m/Y", strtotime("-30 day"));
	$txt_fechaFin = date("d/m/Y");?>
    

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Modificar Estimaciones </div>
    
	<?php // fieldset para manipulacion de Estimaciones buscadas por fecha
	if (!isset($_POST['rdb']) ){
	    
		//Fieldset para consultar traspaleo por quincena?>
		<fieldset class="borde_seccion" id="tabla-modificarEstimacionQuincena" name="tabla-modificarEstimacionQuincena">
		<legend class="titulo_etiqueta">Modificar Estimaci&oacute;n por Quincena</legend>	
		<br>
		<form onSubmit="return valFormModificarEstQuin(this);" name="frm_modificarEstimacion" method="post" action="frm_modificarEstimacion.php">
            <table cellpadding="5" cellspacing="5" class="tabla_frm">
                <tr>
                    <td width="27%"><div align="right">Tipo Obra</div></td>
                    <td width="73%"><?php 
						$res = cargarComboConId("cmb_tipoObra","tipo_obra","tipo_obra","obras","bd_topografia","Tipo Obra","",
						"cargarComboConId(this.value,'bd_topografia','obras','nombre_obra','id_obra','tipo_obra','cmb_nomObra','Obras','');"); 
						if($res==0){?>
                            <label class="msje_correcto">No Hay Datos Registrados</label>
                            <input type="hidden" name="cmb_tipoObra" value="" /><?php
                        }?>
                    </td>
                </tr>
                <tr>
                    <td><div align="right">Nombre Obra</div></td>
                    <td>
                        <select name="cmb_nomObra" id="cmb_nomObra" class="combo_box" onchange="cargarComboQuincena(this.value,'estimaciones','cmb_numQuincena');">
                            <option value="">Obras</option>  
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><div align="right">No Quincena</div></td>
                    <td>
                        <select name="cmb_numQuincena" id="cmb_numQuincena" class="combo_box">
                            <option value="">No. Quincena</option>
                        </select>
                    </td>
                </tr>
                <tr>&nbsp;</tr>
                <tr>
                    <td colspan="2">
                        <div align="center"><?php
							if($res==1){?>
                                <input name="sbt_consultarQuincena" type="submit" class="botones" id="sbt_consultarQuincena"  
                                value="Consultar" title="Consultar Estimación de la Quincena Seleccionada" onmouseover="window.status='';return true"/><?php
							}?>
                            <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_estimaciones.php';"
                            title="Regresar al Menú de Estimaciones"/>
                        </div>
                    </td>
                </tr>
            </table>
		</form>   
</fieldset><?php
		
		//Fieldset para consultar traspaleo por mes?>
		<fieldset class="borde_seccion" id="tabla-modificarEstimacionMes" name="tabla-modificarEstimacionMes">
		<legend class="titulo_etiqueta">Modificar Estimaci&oacute;n por Mes</legend>	
		<br>
		<form onSubmit="return valFormModificarEstMes(this);" name="frm_modificarEstimacion" method="post" action="frm_modificarEstimacion.php">
            <table cellpadding="5" cellspacing="5" class="tabla_frm">
                <tr>
                    <td width="19"><div align="right">Mes</div></td> 
                    <td width="246">                   
                        <select name="cmb_mes" class="combo_box">
                            <option value="">Seleccionar Mes</option>
                            <option value="ENERO">Enero</option>
							<option value="FEBRERO">Febrero</option>
							<option value="MARZO">Marzo</option>
							<option value="ABRIL">Abril</option>
							<option value="MAYO">Mayo</option>
							<option value="JUNIO">Junio</option>
							<option value="JULIO">Julio</option>
							<option value="AGOSTO">Agosto</option>
							<option value="SEPTIEMBRE">Septiembre</option>
							<option value="OCTUBRE">Octubre</option>
							<option value="NOVIEMBRE">Noviembre</option>
							<option value="DICIEMBRE">Diciembre</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><div align="right">Año</div></td>
                    <td><?php cargarAniosDisponible(); ?></td>
                </tr>  
                </tr>&nbsp;<tr>
                <tr>
                    <td colspan="2">
                        <div align="center"><?php
							if($res==1){?>
                                <input name="sbt_consultarMes" type="submit" class="botones" id="sbt_consultarMes"  
                                value="Consultar" title="Consultar Estimación por Mes y Año" onmouseover="window.status='';return true" /><?php
							}?>
                            <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_estimaciones.php';"
                            title="Regresar al Menú de Estimaciones"/>
                        </div>
                    </td>
                </tr>
            </table>
		</form>   
</fieldset><?php
		
	 
		//Si esta definido  sbt_consultar se muestran las estimaciones 
		 if(isset($_POST['sbt_consultarMes']) || isset ($_POST['sbt_consultarQuincena'])){?>
            <form onSubmit="return valFormModificarEst(this);" name="frm_modificarEst" method="post" action="frm_modificarEstimacion.php">
				<input type="hidden" name="sbt_consultar" value="Consultar" />
				<div id='detalle_estimacion' class='borde_seccion2' align="center"><?php
					$control=mostrarEstimaciones();?>
                </div>
				<?php
				
				//Verificar si el resultado de la busqueda arroja resultados para mostrar el boton de modificar
				if ($control==1){?>
					<div id='btn-modificar' align="center">
					  <input name="sbt_modificar" type="submit" class="botones" id="sbt_modificar" value="Modificar" 
						onmouseover="window.status='';return true" title="Modificar Estimación" />
					</div><?php 
				 }?>
			</form><?php 
		}
	}//  fin if (!isset($_POST['rdb']))
	
	//Calendario  para la fecha de elaboración 
	else {?> 
		<div id="calendarioElaboracion">
            <input type="image" name="txt_fechaElaborado" id="txt_fechaElaborado" src="../../images/calendar.png"
			onclick="displayCalendar(document.frm_modificarEstimacion.txt_fechaElaborado,'dd/mm/yyyy',this)" 
			onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
			title="Seleccionar la Fecha de la Estimación"/> 
		</div><?php
	}?>
</body>
<?php  }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>