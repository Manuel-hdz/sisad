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
		include ("op_modificarCapacitacion.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/ajax/validarDatoBD.js"></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
    
    <style type="text/css">
		<!--
		#titulo-modificar {position:absolute;left:30px;top:146px;width:274px;height:20px;z-index:11;}
		#tabla-modificarCapacitacionClave {position:absolute;left:30px;top:190px;width:590px;height:151px;z-index:12;}
		#tabla-modificarCapacitacionFecha {position:absolute;left:670px;top:190px;width:300px;height:151px;z-index:12;}
		#calendario-Ini {position:absolute;left:900px;top:232px;width:30px;height:26px;z-index:13;}
		#calendario-Fin {position:absolute;left:900px;top:268px;width:30px;height:26px;z-index:14;}
		#detalle_capacitacion {position:absolute;left:30px;top:371px;width:936px;height:177px;z-index:17;overflow:scroll;}
		#tabla-modificarCapacitacion {position:absolute;left:30px;top:190px;width:850px;height:470px;z-index:21;}
		#btn-detalleCapacitacion {position:absolute;left:507px;top:406.5px;width:173px;height:57px;z-index:6;}
		#detalle{position:absolute;left:45px;top:199px;width:940px;height:454px;z-index:31;overflow: scroll}
		#calendarioIni {position:absolute;left:290px;top:270px;width:30px;height:26px;z-index:13;}
		#calendarioFin {position:absolute;left:735px;top:270px;width:30px;height:26px;z-index:14;}
    </style>
</head>
<body><?php 
	//Obtener la fecha del sistema para la fecha inicio y fecha fin
	$txt_fechaIni = date("d/m/Y", strtotime("-30 day"));
	$txt_fechaFin = date("d/m/Y");?>

	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="titulo-modificar">Modificar  Capacitaciones Registradas</div><?php
	
	if(!isset($_POST['verDetalle'])){
    // fieldset para manipulacion de capacitaciones buscadas por su clave?>	
    <fieldset class="borde_seccion" id="tabla-modificarCapacitacionClave" name="tabla-modificarCapacitacionClave">
	<legend class="titulo_etiqueta">Modificar Capacitaci&oacute;n por Clave </legend>	
	<br>
	<form onSubmit="return valFormmodificarCapacitacionClave(this);" name="frm_modificarCapacitacion" method="post" action="frm_modificarCapacitacion.php">
    <table width="100%" height="162" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
          	<td height="28"><div align="right">Nom. Capacitaci&oacute;n </div></td>
            <td>
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
            <td colspan="2" align="center"><input name="sbt_consultar2" type="submit" class="botones" value="Consultar" onmouseover="window.status='';return true" 
                title="Modificar Capacitaciones"/>
				&nbsp;&nbsp;&nbsp;&nbsp;
                <input name="btn_regresar" type="button" class="botones" value="Regresar" onclick="location.href='menu_capacitaciones.php';"
                title="Regresar al men&uacute; de Capacitaciones"/>
            </td>
        </tr>
	</table>
    </form>
    </fieldset>
    
	<?php // fieldset para manipulacion de capacitaciones buscadas por fecha?>	
   	<fieldset class="borde_seccion" id="tabla-modificarCapacitacionFecha" name="tabla-modificarCapacitacionFecha">
	<legend class="titulo_etiqueta">Modificar Capacitaci&oacute;n por Fecha</legend>	
	<br>
	<form onSubmit="return valFormmodificarCapacitacionFecha(this);" name="frm_modificarCapacitacion2" method="post" action="frm_modificarCapacitacion.php">
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
                title="Modificar Capacitaciones"/>
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
        onclick="displayCalendar(document.frm_modificarCapacitacion2.txt_fechaIni,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Inicio"/> 
    </div>
    
    <div id="calendario-Fin">
      <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
        onclick="displayCalendar(document.frm_modificarCapacitacion2.txt_fechaFin,'dd/mm/yyyy',this)" 
        onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
        title="Seleccionar Fecha de Fin"/> 
    </div><?php
	
		//Si esta definido sbt_consultar2 o sbt_consultar se muestran las capacitaciones 
		if(isset($_POST["sbt_consultar2"]) || isset($_POST['sbt_consultar']) || isset($_GET["crt1"]) || isset($_GET["crt2"])){
			?>
			<div id="detalle_capacitacion" class="borde_seccion2" align="center">
				<?php
				mostrarCapacitaciones();
				?>
			</div>
			<?php
		}
	}// Fin de 	if(isset($_POST['verDetalle']))
	else{
		//Fieldset el cual se desplegara en el momento que sedesea modificar la capacitacion, esto se encuentra en el op_modificarCapaitacion ?>             
        <fieldset class="borde_seccion" id="tabla-modificarCapacitacion">
        <legend class="titulo_etiqueta">Modificar Capacitaci&oacute;n </legend>	
        <br>
        <form onSubmit="return valFormModCapacitacion2(this);" name="frm_modCapacitacion2" method="post" action="frm_modificarCapacitacion.php"><?php 
			//Mostrar el detalle  Seleccionado
			modificarCapacitacion($ckb);?>
        </form>
        </fieldset>
        <?php //  calendarios que permiten modificar las fechas?>
        <div id="calendarioIni">
            <input type="image" name="txt_fechaIni" id="txt_fechaIni" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_modCapacitacion2.txt_fechaIni,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Inicio"/> 
        </div>
        
        <div id="calendarioFin">
            <input type="image" name="txt_fechaFin" id="txt_fechaFin" src="../../images/calendar.png"
            onclick="displayCalendar(document.frm_modCapacitacion2.txt_fechaFin,'dd/mm/yyyy',this)" 
            onmouseover="window.status='';return true"width="25" height="25" border="0" align="absbottom" 
            title="Seleccionar Fecha de Fin"/> 
        </div>
		<?php  
	}// Fin else?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>