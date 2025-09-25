<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php");
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Almacén
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		//Este archivo contiene las funciones para dar de baja una equivalencia de un material registrado
		include ("op_eliminarEquivalencias.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
<script type="text/javascript" src="../../includes/validacionAlmacen.js"></script>

	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />
	
    <style type="text/css">
	<!--
	#Titulo-barra { position:absolute; left:30px; top:146px; width:168px; height:17px; z-index:11; }
	#tabla { position:absolute; left:30px; top:186px; width:340px; height:143px; z-index:12; }
	#boton-cancelar{ position:absolute; left:301px; top:436px; width:123px; height:37px; z-index:13; }
	#tabla-equiv { position:absolute; left:30px; top:400px; width:700px; height:275px; z-index:13; overflow:scroll }
	-->
    </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="Titulo-barra">Eliminar Equivalencias</div>
<?php //Si la variable $rdb_clave no esta definida, mostrar el formulario para seleccionar la equivalencia de material a eliminar
	if(!isset($_POST['rdb_clave'])){ ?>	
		<fieldset id="tabla" class="borde_seccion">
		<legend class="titulo_etiqueta">Eliminar Equivalencia de Material</legend>
		<br>
		<form name="frm_cargarInfoCombos" method="post" action="">
		<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="120"><div align="right">Material</div></td>
				<td width="140" >
			    <?php  
				$conn = conecta("bd_almacen");
				$result=mysql_query("SELECT DISTINCT id_material,nom_material FROM materiales JOIN equivalencias ON id_material=materiales_id_material");?>
				<select name="cmb_material" size="1" onChange="javascript:document.frm_cargarInfoCombos.submit();" class="combo_box">
					<option value="">Material</option>
					<?php 
					//Evitar que la variable $cmb_material marque un error por no estar definida			
					if(!isset($_POST['cmb_material'])) $cmb_material = "";
					while ($row=mysql_fetch_array($result)){
						if ($row['id_material'] == $cmb_material){
							echo "<option value='$row[id_material]' selected='selected'>$row[nom_material]</option>";
						}
						else{
							echo "<option value='$row[id_material]'>$row[nom_material]</option>";
						}
					} 
					//Cerrar la conexion con la BD		
					mysql_close($conn);?>
				</select>		  
				</td>
			</tr>
			<tr>
				<td colspan="2" align="center">
					<input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Regresar al Men&uacute; de Equivalencias" onclick="location.href='menu_equivalencias.php'" />
				</td>
			</tr>
		</table>
		</form>			
</fieldset>

		<?php
	 	if(isset($_POST['cmb_material']) && $cmb_material!=""){
			echo "<div id='tabla-equiv' class='borde_seccion2' align='center'>";
				mostrarEquivalencias($cmb_material);
			echo "</div>";			 
		}
	}//Cierre if(isset($_POST['rdb_clave']))
	else{
		eliminarEquivalencia($rdb_clave);	
	}?> 
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>

