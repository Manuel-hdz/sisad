<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Recursos Humanos
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_recolectarChecadas.php");
		?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
	<script type="text/javascript" src="includes/ajax/limpiarIbix.js" ></script>
	<script type="text/javascript" language="javascript">
		function go(op) {
			w = new ActiveXObject("WScript.Shell");
			w.run('recolectarChecadas.bat');			
			document.getElementById("btn_cancelar").disabled=true;
			document.getElementById("hdn_revisar").value="si";
			return true;
		}
		
		function revisarSalida(){
			if (document.getElementById("hdn_revisarEnvio").value=="si"){
				if (document.getElementById("hdn_revisar").value=="si")
					frm_recolectarChecadas.submit();
			}
		}
	</script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-agregar { position:absolute; left:30px; top:146px; width:196px; height:20px; z-index:11; }
		#pregunta {position:absolute;left:30px;top:190px;width:933px;height:114px;z-index:12;}
		#calendario {position:absolute;left:612px;top:238px;width:30px;height:26px;z-index:13;}
		-->
    </style>
</head>
	
<body onfocus="revisarSalida();">
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-agregar">Recolectar Checadas</div>
	
	<div align="center" id="pregunta"><?php 
	
		if(!isset($_POST["hdn_revisar"])){?>
			<br><br><br>
			<label class="titulo_etiqueta">&iquest;Recolectar Checadas de los Trabajadores?</label>	
			<br><br><br>
			
			<form name="frm_recolectarChecadas" method="post" action="frm_recolectarChecadas.php">
			<table width="100%"cellpadding="5" cellspacing="5" class="tabla_frm">
				<tr>
					<td>
						<div align="center">
							<input type="hidden" name="hdn_revisar" id="hdn_revisar" value="no"/>
							<input type="hidden" name="hdn_revisarEnvio" id="hdn_revisarEnvio" value="si"/>
							<input type="button" name="btn_recolectar" id="btn_recolectar" class="botones_largos" value="Recolectar Checadas" 
							title="Abrir la Interfaz de Recolecci&oacute;n de Checadas" onclick="return go();"/>
							&nbsp;&nbsp;&nbsp;
							<input name="btn_cancelar" id="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Empleados" 
							onMouseOver="window.status='';return true" onclick="location.href='menu_kardex.php'" />
						</div>
					</td>
				</tr>
			</table>
			</form><?php
		}//Cierre if(!isset($_POST["hdn_revisar"]))
		else{?>
			<input type="hidden" name="hdn_revisarEnvio" id="hdn_revisarEnvio" value="no"/>
			<br><br><br><br>
			<p><img src="images/enviando2.gif"/></p>			
			<p class="msje_correcto">Analizando la Informaci&oacute;n Recopilada...</p><?php
			//Procesar los datos de la BD de IBIX (Access) a la BD de Recurso Humanos (MySQL)
			cargarChecadasIbix();
		}?>
		
	</div>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>