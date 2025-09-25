<?php
	/**
	  * Nombre del M�dulo: USO                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 02/Julio/2012
	  * Descripci�n: En este archivo estan las funciones para asignar los Bonos
	  **/ 
	//M�dulo de conexi�n a la BD
	include ("../../includes/conexion.inc");
	//Operaciones con la Base de Datos
	include("../../includes/op_operacionesBD.php");
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	//Abrir la Sesion para guardar las Radiografias ejecutadas al empleado
	session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion
html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>	
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �Concreto Lanzado de Fresnillo MARCA ');
			}
		}
		//-->
	</script>
	<script type="text/javascript" language="javascript">
		function habilitarBoton(){
			window.opener.document.getElementById("<?php echo $_GET["btn"]?>").disabled=false;
			window.close();
		}
	</script>
</head>

<?php $idBit=$_GET["id_bitacora"];?>

<body onunload="habilitarBoton()">
	<?php
	$conn=conecta("bd_clinica");
	//Sentencia SQL para guardar el registro de Bitacora
	$sql_stm="SELECT nom_proyeccion,comentarios FROM catalogo_radiografias JOIN detalle_radiografia ON catalogo_radiografias_id_proyeccion=id_proyeccion WHERE bitacora_radiografias_id_bit_radiografias='$idBit'";
	$rs=mysql_query($sql_stm);
	if($datos=mysql_fetch_array($rs)){
		//Desplegar los resultados de la consulta en una tabla
		echo "		
			<br>		
			<table cellpadding='5' width='100%'>
			<caption class='titulo_etiqueta'>Registros de Proyecciones Aplicadas en el Registro de Bit&aacute;cora $idBit</caption>
			<tr><td colspan='2'>&nbsp;</td></tr>
			<tr>
				<th class='nombres_columnas' align='center' width='50%'>NOMBRE DE PROYECCI&Oacute;N</th>
				<th class='nombres_columnas' align='center' width='50%'>COMENTARIOS</th>
			</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		do{
			echo "	<tr>
					<td class='$nom_clase' align='center'>$datos[nom_proyeccion]</td>
					<td class='$nom_clase' align='center'>$datos[comentarios]</td>
					</tr>";
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";					
		}while($datos=mysql_fetch_array($rs));
		echo "</table>";
	}
	else{
		echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><p class='msje_correcto' align='center'>No Hay Registros de Radiograf&iacute;as Aplicadas para la Bit&aacute;cora $idBit";
	}
	?>
</body>
</html>