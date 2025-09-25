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
		//Archivo que incluye las operaciones para registrar a los Beneficiarios
		include ("op_modificarEmpleado.php");?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
    <style type="text/css">
		<!--				
		#titulo-consultar {position:absolute; left:30px; top:146px; width:286px; height:25px; z-index:11; }
		#consultar-empleado {position:absolute; left:30px; top:190px; width:900px; height:350px; z-index:14; overflow:scroll;}
		#botones{position:absolute;left:30px;top:650px;width:950px;height:37px;z-index:13;}
		-->
    </style>
</head>
<body>
	<div id="barra"> <img src="../../images/title-bar-bg.gif" width="999" height="30" /></div><?php
	 
	if ($_GET["mod"]=="ben")
		$msje="Beneficiarios";
	else
		$msje="Becarios";?>
	<div class="titulo_barra" id="titulo-consultar"><?php echo $msje;?> de Empleados </div><?php	
	
	//Elimina el Arreglo de Sesion en caso de estar definido y haber presionado el boton de Cancelar
	if (isset($_GET["cancela"]) && isset($_SESSION["beneficiarios"]))
		unset($_SESSION["beneficiarios"]);
	//Elimina el Arreglo de Sesion en caso de estar definido y haber presionado el boton de Cancelar
	if (isset($_GET["cancela"]) && isset($_SESSION["becarios"]))
		unset($_SESSION["becarios"]);
	//Recuperar el RFC del empleado
	$rfc=$_GET["rfc"];		
	//Obtener nombre de Recursos Humanos
	$nombre=obtenerNombreEmpleado($rfc);
	//Obtener el tipo de Datos a desplegar
	$tipo=$_GET["mod"];
	//Abrir conexion a la Base de Datos
	$conn = conecta("bd_recursos");
	//Verificar el tipo de modificaciones
	//ben=Beneficiarios
	echo "<div class='borde_seccion2' id='consultar-empleado'>";
	if ($tipo=="ben"){
		if (isset($_POST["sbt_agregar"]))
			echo "<meta http-equiv='refresh' content='0;url=frm_agregarEmpleadoBeneficiario.php?rfc=$rfc&mod=ben'>";
		if (isset($_POST["sbt_eliminar"]))
			$estado=eliminarBeneficiarios($rfc);
		?>
		<form name="frm_modificarBeneficiarios" onsubmit="return valFormModBeneficiarios(this);" method="post" action="frm_modificarBeneficiarios.php?rfc=<?php echo $rfc;?>&mod=ben">
		<?php
		//Recuper los datos almacenados en la tabla
		$sql = "SELECT nombre, parentesco, edad, porcentaje FROM beneficiarios WHERE empleados_rfc_empleado = '$rfc'";
		//Ejecutar la consulta
		$rs = mysql_query($sql);
		if($datos = mysql_fetch_array($rs)){
			echo "				
			<table cellpadding='5' width='100%' align='center'> 
			<caption class='titulo_etiqueta'>Beneficiarios Registrados de $nombre</caption></br>";
			echo "
			<tr>
				<td width='20' class='nombres_columnas' align='center'>SELECCIONAR</td>
				<td class='nombres_columnas' align='center'>NOMBRE</td>
				<td class='nombres_columnas' align='center'>PARENTESCO</td>
				<td class='nombres_columnas' align='center'>EDAD</td>
				<td class='nombres_columnas' align='center'>PORCENTAJE</td>
			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
				<tr>					
					<td class='$nom_clase' align='center'><input type='radio' name='rdb_rfc' value='$datos[nombre]'</td>
					<td class='$nom_clase' align='center'>$datos[nombre]</td>					
					<td class='$nom_clase' align='center'>$datos[parentesco]</td>
					<td class='$nom_clase' align='center'>$datos[edad] AÑOS</td>
					<td class='$nom_clase' align='center'>$datos[porcentaje]%</td>
				</tr>
				";
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			if (isset($estado) && $estado==1)
				echo "<tr><td colspan='5' align='center'><label class='msje_correcto'>¡Beneficiario Borrado con &Eacute;xito!</label></td></tr>";
			echo "</table>"; 
		}
		else{
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Beneficiarios Registrados de <em><u>$nombre</u></em></p>";
		}
		echo "</div>";
		?>
		<div id="botones" align="center">
			<input type="hidden" name="hdn_bandera" value="si"/>
			<input type="submit" class="botones" value="Agregar" name="sbt_agregar" title="Agregar a un nuevo Beneficiario" onmouseover="window.status='';return true;" onclick="hdn_bandera.value='no';"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<?php if (isset($cont)) {?>
				<input type="submit" class="botones" value="Eliminar" name="sbt_eliminar" title="Borrar Beneficiario" onmouseover="window.status='';return true;" onclick="hdn_bandera.value='si';"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php }?>
			<input type="button" class="botones" value="Regresar" name="btn_cancelar" title="Regresar a la Ventana de Modificaci&oacute;n de Datos Personales" 
			onclick="location.href='frm_modificarEmpleado.php?rfc=<?php echo $rfc;?>';"/>
		</div><?php
	}	
	//Verificar el tipo de modificaciones
	//bec=Becarios
	if ($tipo=="bec"){
		if (isset($_POST["sbt_agregar"]))
			echo "<meta http-equiv='refresh' content='0;url=frm_agregarEmpleadoBecarios.php?rfc=$rfc&mod=bec'>";
		if (isset($_POST["sbt_eliminar"]))
			$estado=eliminarBecarios($rfc);
		?>
		<form name="frm_modificarBecarios" method="post" action="frm_modificarBeneficiarios.php?rfc=<?php echo $rfc;?>&mod=bec" onsubmit="return valFormModBecarios(this);">
		<?php
		//Recuper los datos almacenados en la tabla
		$sql = "SELECT nom_becario,parentesco,grado_estudio,promedio,cantidad FROM becas WHERE empleados_rfc_empleado = '$rfc'";
		//Ejecutar la consulta
		$rs = mysql_query($sql);
		if($datos = mysql_fetch_array($rs)){
			echo "				
			<table cellpadding='5' width='100%' align='center'> 
			<caption class='titulo_etiqueta'>Becarios Registrados de $nombre</caption></br>";
			echo "
			<tr>
				<td width='20' class='nombres_columnas' align='center'>SELECCIONAR</td>
				<td class='nombres_columnas' align='center'>NOMBRE</td>
				<td class='nombres_columnas' align='center'>PARENTESCO</td>
				<td class='nombres_columnas' align='center'>GRADO ESTUDIO</td>
				<td class='nombres_columnas' align='center'>PROMEDIO</td>
				<td class='nombres_columnas' align='center'>VALOR DE BECA</td>
			</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
				<tr>					
					<td class='$nom_clase' align='center'><input type='radio' name='rdb_rfc' value='$datos[nom_becario]'</td>
					<td class='$nom_clase' align='center'>$datos[nom_becario]</td>					
					<td class='$nom_clase' align='center'>$datos[parentesco]</td>
					<td class='$nom_clase' align='center'>$datos[grado_estudio]</td>
					<td class='$nom_clase' align='center'>$datos[promedio]</td>
					<td class='$nom_clase' align='center'>$".number_format($datos["cantidad"],2,".",",")."</td>
				</tr>
				";
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			if (isset($estado) && $estado==1)
				echo "<tr><td colspan='6' align='center'><label class='msje_correcto'>¡Becario Borrado con &Eacute;xito!</label></td></tr>";
			echo "</table>"; 
		}
		else{
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Becarios Registrados de <em><u>$nombre</u></em></p>";
		}
		echo "</div>";?>
		<div id="botones" align="center">
			<input type="hidden" name="hdn_bandera" value="si"/>
			<input type="submit" class="botones" value="Agregar" name="sbt_agregar" title="Agregar a un Nuevo Becario" onmouseover="window.status='';return true;" onclick="hdn_bandera.value='no';"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php if (isset($cont)) {?>
				<input type="submit" class="botones" value="Eliminar" name="sbt_eliminar" title="Borrar Becario" onmouseover="window.status='';return true;" onclick="hdn_bandera.value='si';"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<?php }?>
			<input type="button" class="botones" value="Regresar" name="btn_cancelar" title="Regresar a la Ventana de Modificaci&oacute;n de Datos Personales" 
			onclick="location.href='frm_modificarEmpleado.php?rfc=<?php echo $rfc;?>';"/>
		</div><?php
	}
	mysql_close($conn);?>
	</form>
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>