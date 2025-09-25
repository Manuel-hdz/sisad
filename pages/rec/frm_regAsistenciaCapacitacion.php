<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del M�dulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");	
		include ("op_regAsistenciaCapacitacion.php");?>	

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:302px;height:20px;z-index:11;}
		#tabla-registrarCapacitacionClave {position:absolute;left:30px;top:190px;width:590px;height:151px;z-index:12;}
		#tabla-registrarCapacitacionFecha {position:absolute;left:670px;top:190px;width:300px;height:151px;z-index:12;}
		#calendario-Ini {position:absolute;left:900px;top:232px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:900px;top:268px;width:30px;height:26px;z-index:14;}
		#detalle_capacitacion {position:absolute;left:30px;top:371px;width:936px;height:177px;z-index:17;overflow:scroll;}
		#btn-consultar {position:absolute;left:30px;top:600px;width:946px;height:40px;z-index:9;}
		-->
    </style>
</head>
<body><?php
	
	//Obtener la fecha del sistema para la fecha inicio y fecha fin
	$txt_fechaIni = date("d/m/Y", strtotime("-30 day"));
	$txt_fechaFin = date("d/m/Y");?>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-registrar">Personal que Recibi&oacute; Capacitaci&oacute;nes</div>
    
	<?php // fieldset para manipulacion de capacitaciones buscadas por su clave?>	
    <fieldset class="borde_seccion" id="tabla-registrarCapacitacionClave" name="tabla-registrarCapacitacionClave">
	<legend class="titulo_etiqueta">Seleccionar la Clave de Capacitaci&oacute;n</legend>	
	<br>
	<form onSubmit="return valFormregistrarCapacitacionClave(this);" name="frm_registrarCapacitacion" method="post" action="frm_regAsistenciaCapacitacion.php">
    <table width="100%" height="162" cellpadding="5" cellspacing="5" class="tabla_frm">
		<tr>
          <td width="157" height="28"><div align="right">Nom. Capacitaci&oacute;n </div></td>
            <td width="177">
				<?php 
					//Conectarse con la BD indicada
					$conn = conecta("bd_recursos");		
					//Sentencia SQL para extraer los datos de la capacitacion
					$stm_sql = "SELECT id_capacitacion,nom_capacitacion,fecha_inicio FROM capacitaciones ORDER BY fecha_inicio  DESC,nom_capacitacion";
					//Ejecutar sentencia SQL
					$rs = mysql_query($stm_sql);
					//Confirmar que la consulta fue exitosa y guardar los datos en el ComboBox		
					if($datos = mysql_fetch_array($rs)){			
						//Declarar el ComboBox
						echo "<select name='cmb_claveCapacitacion' id='cmb_claveCapacitacion' class='combo_box'>";
						//Colocar el mensaje inicial
						echo "<option value=''>Capacitaciones</option>";
						//Obtener el a�o de las capacitaciones
						$anio = substr($datos['fecha_inicio'],0,4);
						echo "
							<option value=''></option>
							<option value=''>----- $anio -----</option>
							<option value=''></option>
						";
						do{
							//Verificar cuando se cambia de a�o y mostrarlo en el combo
							if($anio!=substr($datos['fecha_inicio'],0,4)){
								$anio = substr($datos['fecha_inicio'],0,4);
								echo "
									<option value=''></option>
									<option value=''>----- $anio -----</option>
									<option value=''></option>
								";
							}
							echo "<option value='$datos[id_capacitacion]'>$datos[nom_capacitacion]</option>";
						}while($datos = mysql_fetch_array($rs));
						echo "</select>";
					}
					else{
						echo "<label class='msje_correcto'><u><strong> NO</u></strong> hay Capacitaciones Registradas</label>
						<input type='hidden' name='cmb_claveCapacitacion' id='cmb_claveCapacitacion'/>";
					}
					//Cerrar la conexion con la BD		
					mysql_close($conn);	
					?>
			</td>
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input name="sbt_consultar2" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
                title="Consultar Capacitaciones"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_capacitaciones.php';"
                title="Regresar al men&uacute; de Capacitaciones"/>
            </td>
        </tr>
	</table>
    </form>
    </fieldset>
    
	<?php // fieldset para manipulacion de capacitaciones buscadas por fecha?>	
   	<fieldset class="borde_seccion" id="tabla-registrarCapacitacionFecha" name="tabla-registrarCapacitacionFecha">
	<legend class="titulo_etiqueta">Seleccionar Rango de Fechas</legend>	
	<br>
	<form onSubmit="return valFormregistrarCapacitacionFecha(this);" name="frm_registrarCapacitacion2" method="post" action="frm_regAsistenciaCapacitacion.php">
    <table width="100%" height="36" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="133"><div align="right">Fecha Inicio</div></td>
            <td width="202"><input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaIni;?>" 
            	readonly="readonly"/>
            </td>
        </tr>
        <tr>
            <td><div align="right">Fecha Fin </div></td>
            <td><input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo $txt_fechaFin;?>" readonly="readonly"/></td> 
        </tr>
        <tr>
            <td colspan="2" align="center">
                <input name="sbt_consultar" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
                title="Consultar Capacitaciones"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_capacitaciones.php';"
                title="Regresar al men&uacute; de Capacitaciones"/>
            </td>
        </tr>
    </table>
    </form>   
    </fieldset>
    
	<?php //Calendarios para consultar capacitacion por fecha?>
    <div id="calendario-Ini">
        <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_registrarCapacitacion2.txt_fechaIni,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Inicio"/> 
    </div>
    
    <div id="calendario-Fin">
        <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_registrarCapacitacion2.txt_fechaFin,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Fin"/> 
    </div><?php
	
	//Si esta definido sbt_consultar2 se muestran las capacitaciones 
	if(isset($_POST["sbt_consultar2"])){
		//Guardar el id de la capacitacion  en la sesion	
		$_SESSION['id_capacitacion'] = array ("id_capacitacion"=> $_POST['cmb_claveCapacitacion']); 
		echo "<meta http-equiv='refresh' content='0;url=frm_regAsistencia.php'>";	
	 }
	
	//Si esta definido sbt_consultar se muestran las capacitaciones 
	if(isset($_POST["sbt_consultar"])){?>
        <form onSubmit="return valFormregAsistencia(this);" name="frm_valFormregAsistencia" method="post" action="frm_regAsistenciaCapacitacion.php">
        	<?php if(isset($_POST["sbt_consultar"])){ ?>
                <input  type="hidden" name="txt_fechaIni" value="<?php echo $_POST['txt_fechaIni'];?>" />
                <input type="hidden" name="txt_fechaFin" value="<?php echo $_POST['txt_fechaFin'];?>" />
                <input type="hidden" name="sbt_consultar" value="Consultar" />
            <?php }?>
            <div id='detalle_capacitacion' class='borde_seccion2' align="center"><?php
				$control= mostrarCapacitaciones();?>
            </div><?php
            if ($control==1){?>
                <div id='btn-consultar' align="center">
                  <input name="sbt_registrar" type="submit" class="botones" value="Registrar" onmouseover="window.status='';return true" 
                    title="Registrar Asistencias a la Capacitaci&oacute;n Seleccionada"/>
                </div>
            <?php } ?>
        </form><?php
	}
	
	//Si esta definido sbt_consultar2 se muestran las capacitaciones 
	if(isset($_POST["sbt_registrar"])){
		//Guardar el id de la capacitacion  en la sesion	
		$_SESSION['id_capacitaciones'] =  $_POST[ 'rdb_rfc']; 
		echo "<meta http-equiv='refresh' content='0;url=frm_regAsistencia.php'>";	
	 }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>