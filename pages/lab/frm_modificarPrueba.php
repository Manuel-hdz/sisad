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
		//Archivo con las funciones que competen al borrado de una prueba
		include ("op_modificarPrueba.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-modificar { position:absolute; left:30px; top:146px; width:132px; height:20px; z-index:11; }
		#tabla-modificarPruebaXTipo {position:absolute;left:30px;top:190px;width:430px;height:150px;z-index:12;}
		#tabla-modificarPruebaXNorma {position:absolute;left:520px;top:190px;width:430px;height:150px;z-index:13;}
		#tabla-resultados {position:absolute;left:30px;top:420px;width:950px;height:200px;z-index:14;overflow:scroll;}
		#boton {position:absolute;left:30px;top:670px;width:950px;height:50px;z-index:15;}
		#tabla-modificarPrueba {position:absolute;left:30px;top:190px;width:850px;height:350px;z-index:16;}
		-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-modificar">Modificar Pruebas</div>
	
	<?php
		//Si se ha presionado el botón de Modificar, actualizar la informacion en la Base de Datos, en secuencia, este es el ultimo paso
		if (isset($_POST["sbt_modificar"])){
			modificarPrueba();
		}
		else{
			//Si se ha presionado el boton de continuar, mostrar la tabla con la Prueba seleccionada para poder verificar y modificar sus datos
			if (isset($_POST["sbt_continuar"])){
				mostrarPruebaDetalle();
			}
			//Si no se ha presionado ningun boton en absoluto, mostrar los formularios para poder seleccionar los criterios de busqueda
			else{
		?>
				<fieldset class="borde_seccion" id="tabla-modificarPruebaXTipo">
				<legend class="titulo_etiqueta">Modificar Prueba por Tipo</legend>	
				<br>
				<form name="frm_modificarPruebaXTipo" method="post" action="frm_modificarPrueba.php" onsubmit="return valFormModificarPrueba1(this);">
				<table cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td><div align="right">Tipo de Prueba</div></td>
						<td><?php 
							$grupo=cargarComboEspecifico("cmb_tipo","tipo","catalogo_pruebas","bd_laboratorio","1","estado","Tipo",""); 
							if($grupo==0) 
								echo "<label class='msje_correcto'>No hay Pruebas Registradas</label>";
							?>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<div align="center">
								<input name="sbt_continuarTipo" type="submit" class="botones"  value="Continuar" 
								onMouseOver="window.status='';return true" <?php if ($grupo==0){ echo "disabled='disabled' title='No hay Pruebas Registradas'";}else{ echo "title='Mostrar las Pruebas'";} ?>/>
								&nbsp;&nbsp;&nbsp;
								<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Pruebas" 
								onMouseOver="window.status='';return true" onclick="location.href='menu_pruebas.php';" />
							</div>
						</td>
					</tr>
				</table>
				</form>
				</fieldset>
				
				<fieldset class="borde_seccion" id="tabla-modificarPruebaXNorma">
				<legend class="titulo_etiqueta">Modificar Prueba por Norma</legend>	
				<br>
				<form name="frm_modificarPruebaXNorma" method="post" action="frm_modificarPrueba.php" onsubmit="return valFormModificarPrueba2(this);">
				<table cellpadding="5" cellspacing="5" class="tabla_frm">
					<tr>
						<td><div align="right">Norma de Prueba</div></td>
						<td><?php 
							$grupo=cargarComboEspecifico("cmb_norma","norma","catalogo_pruebas","bd_laboratorio","1","estado","Norma",""); 
							if($grupo==0){ 
								echo "<label class='msje_correcto'>No hay Pruebas Registradas</label>";
							}?>
						</td>
					</tr>
					<tr>
						<td colspan="6">
							<div align="center">
								<input name="sbt_continuarNorma" type="submit" class="botones"  value="Continuar" 
								onMouseOver="window.status='';return true" <?php if ($grupo==0){ echo "disabled='disabled' title='No hay Pruebas Registradas'";}else{ echo "title='Mostrar las Pruebas'";} ?>/>
								&nbsp;&nbsp;&nbsp;
								<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar al Men&uacute; de Pruebas" 
								onMouseOver="window.status='';return true" onclick="location.href='menu_pruebas.php';" />
							</div>
						</td>
					</tr>
				</table>
				</form>
				</fieldset>
				
				<?php
					//Verificar si se ha presionado alguno de los botones de continuar
					if (isset($_POST["sbt_continuarTipo"]) || isset($_POST["sbt_continuarNorma"])){
						echo "<form name='frm_modificarPrueba' method='post' action='frm_modificarPrueba.php' onsubmit='return valFormSeleccionarPruebaModificar(this);'>";
						//Dibujar el DIV de resultados segun el criterio
						echo "<div id='tabla-resultados' class='borde_seccion2'>";
						//Mostrar resultados por Tipo de Prueba
						if (isset($_POST["sbt_continuarTipo"]))
							mostrarPruebas($_POST["cmb_tipo"],1);
						
						//Mostrar resultados por Norma
						if (isset($_POST["sbt_continuarNorma"]))
							mostrarPruebas($_POST["cmb_norma"],2);
						
						echo "</div>";
						
						//Dibujar el DIV que contendra el boton
						echo "<div id='boton' align='center'>";
						?>
						<input type="submit" name="sbt_continuar" class="botones" title="Continuar a Modificar Prueba Seleccionada" onmouseover="window.status='';return true;" value="Continuar"/>
						<?php
						echo "</div>";
						echo "</form>";
					}
			}//Cierre del else de sbt_continuar
		}//Cierre del else de sbt_modificar
		?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>