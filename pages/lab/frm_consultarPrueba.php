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
		//Archivo con las funciones que competen a mostrar los datos de las pruebas
		include ("op_consultarPrueba.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-eliminar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-consultarPruebaXTipo {position:absolute;left:30px;top:190px;width:430px;height:150px;z-index:12;}
		#tabla-consultarPruebaXNorma {position:absolute;left:520px;top:190px;width:430px;height:150px;z-index:13;}
		#tabla-consultarPruebaTodas {position:absolute;left:30px;top:380px;width:430px;height:150px;z-index:14;}
		#tabla-resultados {position:absolute;left:30px;top:190px;width:950px;height:430px;z-index:15;overflow:scroll;}
		#boton {position:absolute;left:30px;top:670px;width:950px;height:50px;z-index:16;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-eliminar">Consultar Prueba</div>
	
	<?php
	//Verificar si se ha presionado algun boton con los criterios de busqueda
	if (!isset($_POST["sbt_continuarTipo"]) && !isset($_POST["sbt_continuarNorma"]) && !isset($_POST["sbt_continuarTodas"])){
		?>
		<fieldset class="borde_seccion" id="tabla-consultarPruebaXTipo">
		<legend class="titulo_etiqueta">Consultar Prueba por Tipo</legend>	
		<br>
		<form name="frm_consultarPruebaXTipo" method="post" action="frm_consultarPrueba.php" onsubmit="return valFormConsultarPrueba1(this);">
		<table cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Tipo de Prueba</div></td>
				<td><?php 
					$grupo=cargarComboEspecifico("cmb_tipo","tipo","catalogo_pruebas","bd_laboratorio","1","estado","Tipo",""); 
					if($grupo==0){ 
						echo "<label class='msje_correcto'>No hay Pruebas Registradas</label>";
					}?>
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<div align="center">
						<input name="sbt_continuarTipo" type="submit" class="botones"  value="Continuar" 
						onMouseOver="window.status='';return true" <?php if ($grupo==0){ echo "disabled='disabled' title='No hay Pruebas Registradas'";}else{ echo "title='Mostrar las Pruebas en Base al Tipo Seleccionado'";} ?>/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Pruebas" 
						onMouseOver="window.status='';return true" onclick="location.href='menu_pruebas.php';" />
					</div>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-consultarPruebaXNorma">
		<legend class="titulo_etiqueta">Consultar Prueba por Norma</legend>	
		<br>
		<form name="frm_consultarPruebaXNorma" method="post" action="frm_consultarPrueba.php" onsubmit="return valFormConsultarPrueba2(this);">
		<table cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td><div align="right">Norma de Prueba</div></td>
				<td><?php 
					$norma=cargarComboEspecifico("cmb_norma","norma","catalogo_pruebas","bd_laboratorio","1","estado","Norma",""); 
					if($norma==0){ 
						echo "<label class='msje_correcto'>No hay Pruebas Registradas</label>";
					}?>
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<div align="center">
						<input name="sbt_continuarNorma" type="submit" class="botones"  value="Continuar" 
						onMouseOver="window.status='';return true" <?php if ($grupo==0){ echo "disabled='disabled' title='No hay Pruebas Registradas'";}else{ echo "title='Mostrar las Pruebas en Base a la Norma Seleccionada'";} ?>/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Pruebas" 
						onMouseOver="window.status='';return true" onclick="location.href='menu_pruebas.php';" />
					</div>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
		
		<fieldset class="borde_seccion" id="tabla-consultarPruebaTodas">
		<legend class="titulo_etiqueta">Mostrar todas las Pruebas</legend>	
		<br>
		<form name="frm_consultarPruebaTodas" method="post" action="frm_consultarPrueba.php">
		<table cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
			<tr>
				<td>
					<p>
					<div align="center">
						<input name="sbt_continuarTodas" type="submit" class="botones"  value="Continuar" 
						onMouseOver="window.status='';return true" <?php if ($grupo==0){ echo "disabled='disabled' title='No hay Pruebas Registradas'";}else{ echo "title='Mostrar Todas las Pruebas'";} ?>/>
						&nbsp;&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Pruebas" 
						onMouseOver="window.status='';return true" onclick="location.href='menu_pruebas.php';" />
					</div>
					</p>
				</td>
			</tr>
		</table>
		</form>
		</fieldset>
	<?php
	}
		//Verificar si se ha presionado alguno de los botones de continuar
		if (isset($_POST["sbt_continuarTipo"]) || isset($_POST["sbt_continuarNorma"]) || isset($_POST["sbt_continuarTodas"])){
			//Dibujar el DIV de resultados segun el criterio
			echo "<div id='tabla-resultados' class='borde_seccion2'>";
			//Mostrar resultados por Tipo de Prueba
			if (isset($_POST["sbt_continuarTipo"])){
				mostrarPruebas($_POST["cmb_tipo"],1);
			}
			//Mostrar resultados por Norma
			if (isset($_POST["sbt_continuarNorma"])){
				mostrarPruebas($_POST["cmb_norma"],2);
			}
			//Mostrar todo el catalogo de Pruebas
			if (isset($_POST["sbt_continuarTodas"])){
				mostrarPruebas("",3);
			}
			echo "</div>";
			
			//Dibujar el DIV que contendra el boton
			echo "<div id='boton' align='center'>";
			?>
			<input type="button" name="btn_regresar" class="botones" title="Regresar a Consultar con Otro Criterio" onmouseover="window.status='';return true;" value="Regresar" onclick="location.href='frm_consultarPrueba.php'"/>
			<?php
			echo "</div>";
		}
	?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>