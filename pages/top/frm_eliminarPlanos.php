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
		include ("op_eliminarPlanos.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionTopografia.js" ></script>
	<script type="text/javascript" src="../../includes/calendario.js?random=20060118"></script>
	<link type="text/css" rel="stylesheet" href="../../includes/estiloCalendario.css?random=20051112" media="screen"></link>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
			#titulo-eliminar {position:absolute;left:30px;top:146px;	width:210px;height:20px;z-index:11;}
			#tabla-eliminarPlano {position:absolute;left:30px;top:190px;width:425px;height:162px;z-index:12;}
			#calendario-uno{position:absolute;left:258px;top:235px;width:30px;height:26px;z-index:13;}
			#calendario-dos{position:absolute;left:258px;top:271px;width:30px;height:26px;z-index:13;}
			#tabla-planos { position:absolute; left:30px; top:380px; width:945px; height:250px; z-index:21; overflow:scroll; }
			#btns-regpdf { position: absolute; left:30px; top:675px; width:945px; height:40px; z-index:23; }
		-->
    </style>
</head>
<body>

    <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-eliminar">Eliminar  Planos </div>
	<fieldset class="borde_seccion" id="tabla-eliminarPlano" name="tabla-eliminarPlano">
	<legend class="titulo_etiqueta">Selecciona Fechas del Plano </legend>	
	<br>
	<form onsubmit="return valFormEliminarPlano(this);" name="frm_eliminarPlanoFecha" method="post" action="frm_eliminarPlanos.php"  enctype="multipart/form-data">
		<table width="415"  cellpadding="5" cellspacing="5" class="tabla_frm">
      		<tr>
		  		<td width="104"><div align="right">Fecha Inicio</div></td>
       	  		<td width="276">
					<input name="txt_fechaIni" type="text" id="txt_fechaIni" size="10" maxlength="15" 
		  			value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" readonly="readonly"/>
				</td>
        	</tr>
          		<td width="104"><div align="right">Fecha Fin </div></td>
          		<td><input name="txt_fechaFin" type="text" id="txt_fechaFin" size="10" maxlength="15" value="<?php echo date("d/m/Y");?>" readonly="readonly"/></td>
        	</tr>
			<tr>
       	  		<td colspan="4">
					<div align="center"> 
						<input name="sbt_consultar" type="submit" class="botones" id= "sbt_guardar" value="Consultar" title="Consultar Planos"
						onMouseOver="window.status='';return true"/>
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; Planos" 
						onmouseover="window.status='';return true" onclick="location.href='menu_planos.php'" />
					</div>
				</td>
			</tr>
   	  </table>
	</form>
</fieldset>

   <div id="calendario-uno">
   		<input name="calendario_planos" type="image" id="calendario_planos" onclick="displayCalendar (document.frm_eliminarPlanoFecha.txt_fechaIni,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" 
		title="Seleccione Fecha de Inicio" />
	</div>
	<div id="calendario-dos">
   		<input name="calendario_planos" type="image" id="calendario_planos" onclick="displayCalendar (document.frm_eliminarPlanoFecha.txt_fechaFin,'dd/mm/yyyy',this)" 
		onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" title="Seleccione Fecha Fin" />
	</div>
<?php 
//Verificamos que el boton consultar sea presionado; si es asi mostrar los Empleados
	if(isset($_POST["sbt_consultar"])){?>
		<form name="frm_eliminarPlano"  onsubmit="return valFormEliminar(this);"method="post" ><?php 
			echo"<div id='tabla-planos' class='borde_seccion2' align='center'>";
				//Si $ band regresa 0 la consulta no trajo resultados por loc ual no se debe de mostrar el boton de eliminar
				$band=mostrarPlanos();
				echo "</div>";?>
				<div id="btns-regpdf" align="center">
				<?php if($band!=0){?>
						<input type="submit" name="sbt_eliminar" value="Eliminar" class="botones" title="Eliminar Plano" 
	        			onMouseOver="window.estatus='';return true"/>
				<?php }?>
						&nbsp;&nbsp;&nbsp;&nbsp;
				    	<input name="btn_cancelar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; Planos" 
        	        	onMouseOver="window.status='';return true" onclick="location.href='menu_planos.php'" />
				</div>
		</form><?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>