	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;						
		//-->
	</script>	
<?php

	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 31/Marzo/2011
	  * Descripción: Este archivo contiene funciones para Ver los Beneficiarios de los Empleados
	  **/

if(isset($_GET['id_empleado']))
	mostrarBecarios();

function mostrarBecarios(){	
	
	include ("../../includes/conexion.inc");
	include ("../../includes/op_operacionesBD.php");
	//Hoja de estilo para la ventana emergente
	echo "<link rel='stylesheet' type='text/css' href='includes/estiloGerencia.css' />";
	
	//Recuperar el ID del empleado
	$id_empleado=$_GET["id_empleado"];

	//Recuperar los datos de los becarios almacenados en la tabla correspondiente
	$sql = "SELECT * FROM becas WHERE empleados_rfc_empleado = '$id_empleado'";
	
	//Obtener nombre Recursos Humanos
	$nombre=obtenerNombreEmpleado($id_empleado);
	
	//Abrir conexion a la Base de Datos
	$conn = conecta("bd_recursos");
	//Ejecutar la consulta
	$rs = mysql_query($sql);
	if($datos = mysql_fetch_array($rs)){
		echo "				
		<table cellpadding='5' width='100%' align='center'> 
		<caption class='titulo_etiqueta' style='color:#FFF'>Becarios Registrados de $nombre</caption></br>";
		echo "
		<tr>
			<td class='nombres_columnas' align='center'>NOMBRE</td>
			<td class='nombres_columnas' align='center'>PARENTESCO</td>
			<td class='nombres_columnas' align='center'>GRADO ESTUDIO</td>
			<td class='nombres_columnas' align='center'>PROMEDIO</td>
			<td class='nombres_columnas' align='center'>MONTO</td>
		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;	
		do{
			echo "
			<tr>					
				<td class='$nom_clase' align='center'>$datos[nom_becario]</td>					
				<td class='$nom_clase' align='center'>$datos[parentesco]</td>
				<td class='$nom_clase' align='center'>$datos[grado_estudio] </td>
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
		?>
		<tr><td>&nbsp;</td></tr>
		<tr>
			<td colspan="5" align="center">
				<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
			</td>
		</tr>
		<?php
	}
	else{
		echo  "<p align='center'> <img src='../../images/no-becas.png' align='center' width='350' height='350' border='0'/></p>";
		echo "<p align='center' class='titulo_etiqueta'><b>No hay Becarios de $nombre</b></p>";?>
		
		<br /><br />
		<p align="center">
			<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
		</p>
		<?php
	}
	//Cerrar la conexion
	mysql_close($conn);
}
?>
