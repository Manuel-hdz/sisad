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
		include ("op_consultarMantenimiento.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionGerencia.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="../../includes/ajax/cargarCombo.js"></script>
	<script type="text/javascript" src="includes/ajax/cargarComboEquipoMtto.js"></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>    
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-consultar {position:absolute;left:30px;top:146px;width:277px;height:20px;z-index:11;}
		#tabla-consultarEquipo {position:absolute;left:30px;top:190px;width:293px;height:240px;z-index:14;}
		#calendarioInicio {position:absolute;left:235px;top:232px;width:30px;height:26px;z-index:13;}		
		#calendarioFin{position:absolute;left:235px;top:270px;width:30px;height:26px;z-index:13;}
		#mostrarRepo{position:absolute;left:371px;top:194px;width:597px;height:473px;z-index:13; overflow:scroll}
		-->
    </style>
</head>
<body><?php
	//Si viene en el post sbt_continuar desplegar la tabla de resultados del equipo seleccionado
	if(isset($_POST['sbt_consultar'])){?>
        <div id='mostrarRepo' class='borde_seccion2'><?php
            mostrarReporte();?>
        </div><?php
	}?>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-consultar">Mantenimiento- Reporte de Servicios</div>
	<fieldset class="borde_seccion" id="tabla-consultarEquipo" name="tabla-consultarEquipo">
	<legend class="titulo_etiqueta">Reporte por Equipo</legend>	
	<br>
	<form onSubmit="return valFormConsultarEq(this);" name="frm_consultarMantenimiento" method="post" action="frm_consultarMantenimiento.php">
    <table cellpadding="5" cellspacing="5" class="tabla_frm">
    	<tr>
            <td><div align="right">Fecha Inicio</div></td>
            <td><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y", strtotime("-30 day"));?>"
            readonly="readonly"/></td>
        </tr>        
    	<tr>
            <td><div align="right">Fecha Fin</div></td>
            <td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
		</tr>        
    	<tr>
            <td><div align="right">Familia</div></td>
            <td><?php				
				$conn = conecta("bd_mantenimiento");
				$result = mysql_query("SELECT DISTINCT familia FROM equipos WHERE area = 'CONCRETO' ORDER BY familia");
				if($conceptos=mysql_fetch_array($result)){?>
					<select name="cmb_familia" id="cmb_familia" size="1" class="combo_box" 
					onchange="cargarEquiposFamilia(this.value,'CONCRETO','cmb_equipo','Equipo','');">
						<option value="">Familia</option><?php 
						do{							
							echo "<option value='$conceptos[familia]'>$conceptos[familia]</option>";
						}while($conceptos=mysql_fetch_array($result)); ?>
					</select><?php
				}
				else{
					echo "<label class='msje_correcto'> No hay Equipos Registrados en Mantenimiento</label>
					<input type='hidden' name='cmb_familia' id='cmb_familia' value = ''/>";
				}
				//Cerrar la conexion con la BD
				mysql_close($conn);?>				
			</td>
		</tr>    
    	<tr>
            <td><div align="right">Equipo</div></td>
            <td>
            	<select name="cmb_equipo" id="cmb_equipo">
                	<option value="">Equipo</option>
                </select>
            </td>  
		</tr>  
        <tr>
            <td colspan="2">
                <div align="center">
                  <input name="sbt_consultar" type="submit" class="botones" id="sbt_consultar"  value="Consultar" title="Consultar Reporte" 
                    onmouseover="window.status='';return true"/>
                    &nbsp;&nbsp;&nbsp;
                    <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Inicio" 
                    onMouseOver="window.status='';return true" onclick="location.href='inicio_gerencia.php';" />
                </div>          
            </td>
        </tr>
    </table>
    </form>
</fieldset>
    <div id="calendarioInicio">
      <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_consultarMantenimiento.txt_fechaIni,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Inicio"/> 
	</div>
    <div id="calendarioFin">
        <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_consultarMantenimiento.txt_fechaFin,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha Final"/> 
	</div>
</body><?php
 }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>