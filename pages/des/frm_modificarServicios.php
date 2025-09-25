<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Desarrollo
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_modificarServicios.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>

    <style type="text/css">
		<!--
			#titulo-Modificar {position:absolute;left:30px;top:146px;	width:315px;height:20px;z-index:11;}
			#tabla-modificarRegistro{ position:absolute; left:30px; top:190px; width:681px; height:235px; z-index:12; padding:15px; padding-top:0px;}
			#tabla-registros { position:absolute; left:30px; top:350px; width:945px; height:270px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:670px; width:945px; height:40px; z-index:23; }
			#titulo-registrar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
			#titulo-modificar { position:absolute; left:30px; top:146px; width:309px; height:20px; z-index:11; }
			#tabla-reporte-fecha {position:absolute;left:30px;top:190px;width:425px;height:126px;z-index:12;}
			#calendar-uno {position:absolute; left:443px; top:232px; width:30px; height:26px; z-index:18; }
			#calendar-dos {position:absolute; left:245px; top:232px; width:30px; height:26px; z-index:18; }
			#calendario {position:absolute;left:239px;top:233px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
<body><?php 
	if(isset($_POST['sbt_modificar']) || isset($_POST["sbt_guardar"])){
		if (isset($_POST["sbt_modificar"]))
			modificarRegistroSeleccionado();
		if (isset($_POST["sbt_guardar"]))
			guardarModificacionRegistro();
	}else{?>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-Modificar">Modificar Servicios con Minera Fresnillo </div>
	<fieldset class="borde_seccion" id="tabla-reporte-fecha">
    <legend class="titulo_etiqueta">Seleccionar Registros por Fecha </legend>
    <br />
    <form  onsubmit="return valFormModServ(this);"method="post" name="frm_modificarRegistros" id="frm_modificarRegistros" >
      <table width="438" height="108"  cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
          <td width="88"><div align="right">*Fecha Inicio</div></td>
          <td width="92"><input name="txt_fechaIni" id="txt_fechaIni" readonly="readonly" type="text" 
					value="<?php echo date("d/m/Y", strtotime("-6 day")); ?>" size="10"  width="90"/></td>
          <td width="75"><div align="right">*Fecha Fin </div></td>
          <td width="116"><input name="txt_fechaFin" id="txt_fechaFin" type="text"  readonly="readonly" 
					value="<?php echo date("d/m/Y"); ?>" size="10" width="90"/>          </td>
        </tr>

        <tr>
          <td colspan="4"><div align="center">
              <input name="sbt_consultar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Consultar Servicios"
						onmouseover="window.status='';return true"/>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Servicios" 
						onmouseover="window.status='';return true" onclick="location.href='menu_servicios.php'" />
          </div></td>
        </tr>
      </table>
    </form>
</fieldset>
		<div id="calendar-uno">
		<input name="fechaFin" type="image" id="fechaFin" onclick="displayCalendar(document.frm_modificarRegistros.txt_fechaFin,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" title="Seleccionar Fecha de Fin"
		width="25" height="25" border="0"/>
	</div>
	<div id="calendar-dos">
		<input name="fechaFin" type="image" id="fechaIni" onclick="displayCalendar(document.frm_modificarRegistros.txt_fechaIni,'dd/mm/yyyy',this)"
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom"
		width="25" height="25" border="0" title="Seleccionar Fecha de Inicio"/>
	</div>
	<?php 
	//Verificamos que el boton Modificar sea presionado; si es asi mostrar los Empleados
	if(isset($_POST["txt_fechaIni"])){?>
		<form name="frm_modificarServicios"  onsubmit="return valFormConsultaServicios(this);"method="post" ><?php 
			echo"<div id='tabla-registros' class='borde_seccion2' align='center'>";
				//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de Modificar
				$band=mostrarRegistros();
			echo "</div>";?>
			<div id="btns-regpdf" align="center">
				<?php if($band!=0){?>
					<input type="submit" name="sbt_modificar" value="Modificar" class="botones" title="Modificar Registro" 
					onmouseover="window.estatus='';return true"/>
				<?php }?>
			</div>
			<input type="hidden" name="txt_fechaIni" value="<?php echo $_POST["txt_fechaIni"];?>"/>
			<input type="hidden" name="txt_fechaFin" value="<?php echo $_POST["txt_fechaFin"];?>"/>
		</form><?php 
	}
}//Cierre if(isset($_POST['sbt_modificar'])){?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>