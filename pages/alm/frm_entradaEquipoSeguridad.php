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
		//Este archivo contiene las operaciones para registar la salida de Material en la BD de Almacen
		include ("op_entradaEquipoSeguridad.php");
?>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
<script type="text/javascript" src="../../includes/validacionAlmacen.js" ></script>
	<link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

	    <style type="text/css">
		<!--
		#equipo-seguridad {position:absolute; left:30px; top:146px; width:146px; height:23px; z-index:11;}
		#tabla {position:absolute; left:30px; top:190px; width:420px; height:94px; z-index:12;}
		#botones {position:absolute; width:940px; height:51px; z-index:13; left: 30px; top: 670px;}		
		#materiales{position:absolute; width:940px; height:287px; z-index:13; left: 30px; top: 324px; overflow:scroll;}		
		-->
        </style>
</head>
<body>
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
	<div class="titulo_barra" id="equipo-seguridad">Equipo Seguridad </div>
<?php //Si el valor registrar no esta definido, desplegar el formulario para registrar el equipo de seguridad 
?>
	<form  name="frm_verEquipoSeguridad" onsubmit="return valFormEquipo(this);" method="post" action="frm_entradaEquipoSeguridad.php">
	<fieldset class="borde_seccion" id="tabla" name="tabla">
		<legend class="titulo_etiqueta">Seleccionar los Datos del Trabajador</legend>
	    <table width="420" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr>
				<td width="174" align="right">Nombre del Trabajador</td>
        		<td width="160">
		 		 	<?php 
						$nom_seleccionado='';
						if (isset($_POST["cmb_nombre"])){
							$nom_seleccionado=$cmb_nombre;
						}
						cargarComboNombres("cmb_nombre","nombre","ape_pat","ape_mat","empleados","bd_recursos","Trabajador",1,"frm_verEquipoSeguridad",$nom_seleccionado);
					?>
				</td>
			</tr>
			<tr>
				<td><div align="right">Fecha Entrega</div></td>
				<td>
					<input name="txt_fecha" type="text"  disabled="disabled" class="caja_de_texto" id="txt_fecha" value="<?php echo verFecha(4);?>" size="10"
					maxlength="10" />
				</td>
			</tr>
		</table>
	<?php if(isset($_POST['cmb_nombre'])){ ?>			
	</fieldset>
		<div align="center" class="borde_seccion2" id="materiales" name="materiales">
		<p class="titulo_etiqueta" >Seleccionar el Material entregado al Trabajador</p>
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
			<tr class="nombres_filas">
				<td colspan="5" align="center" class="nombres_columnas">Equipo de Seguridad</td>
			</tr>
			<tr>
				<td align="center" class="nombres_columnas"><strong>Seleccionar </strong></td>
				<td align="center" class="nombres_columnas"><strong>Clave Material </strong></td>
				<td align="center" class="nombres_columnas"><strong>Material</strong></td>
				<td align="center" class="nombres_columnas"><strong>Entregado</strong></td>
				<td align="center" class="nombres_columnas"><strong>Observaciones</strong></td>
			</tr>
			<?php 
			$conec=conecta("bd_almacen");
			$stm_sql="SELECT id_material, nom_material FROM materiales WHERE id_material LIKE 'SEG%' OR linea_articulo LIKE '%SEG%' OR grupo LIKE '%SEG%'";
			$rs=mysql_query($stm_sql);
			if($row = mysql_fetch_array($rs)){
				$cont=1;
				$nom_clase="renglon_gris";
				do {
					echo "<tr>";
					echo "<td align='center' class='$nom_clase' width='127'><input name='ckb_equipo".$cont."' type='checkbox' onclick='activarCampos(this, $cont)' id='ckb_equipo".$cont."' value='".$row['id_material']."'/></td>";
					echo"<input type='hidden' name='hdn_nombre$cont'  value='$row[id_material]' id='hdn_nombre$cont'/>";
					echo "<td align='center' class='$nom_clase' width='127'>$row[id_material]</td>";
					echo "<td align='center' class='$nom_clase' width='127'>$row[nom_material]</td>";
					echo "<td align='center' class='$nom_clase' width='127'>
					<select disabled='disabled' name='cmb_estado$cont' id='cmb_estado$cont'>
                		<option value=''>Estado</option>
						<option value='ENTREGADO'>ENTREGADO</option>
						<option value='NO ENTREGADO'>NO ENTREGADO</option>
	                </select></td>";
					echo "<td class='$nom_clase' width='127'><input disabled='disabled' type='text' size=70 maxlenght=70 name='txt_observaciones$cont' id='txt_observaciones$cont'/></td>";
					echo "</tr>";
				$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while ($row = mysql_fetch_array($rs));
				?><input type="hidden" name="hdn_cant" id="hdn_cant" value="<?php echo $cont?>"/><?php
			}
			?>
    </table>
	</div>
	<div id="botones">
		<table align="center">
			<tr>
				<td>
					<input type="submit" name="sbt_registrar" id="sbt_registrar" class="botones" value="Registrar" onMouseOver="window.status='';return true" 
					title="Registra Equipo de Seguridad"/>
					&nbsp;&nbsp;&nbsp;
					<input type="reset" class="botones" value="Limpiar" title="Limpiar Formulario" />
					&nbsp;&nbsp;&nbsp;
					<input type="button" onMouseOver="window.status='';return true" onclick="location.href='frm_entradaMaterial.php'" class="botones" value="Cancelar" 
					title="Regresar a la Página de Salida de Material" />
				</td>
			</tr>
		</table>
	</div>
	</form>
<?php }?>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>