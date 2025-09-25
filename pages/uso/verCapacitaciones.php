	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �Concreto Lanzado de Fresnillo MARCA ');
			}
		}
		document.onmousedown=click;						
		//-->
	</script>	
<?php

	/**
	  * Nombre del M�dulo: Recursos Humanos                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 05/Abril/2011
	  * Descripci�n: Este archivo contiene funciones para Ver las Capacitaciones de los Empleados
	  **/ 

if(isset($_GET['id_empleado']))
	mostrarCapacitaciones();

function mostrarCapacitaciones(){	
	include ("../../includes/conexion.inc");
	include ("../../includes/op_operacionesBD.php");
	include ("../../includes/func_fechas.php");
	//Hoja de estilo para la ventana emergente
	echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	
	//Recuperar el ID del empleado
	$id_empleado=$_GET["id_empleado"];

	//Recuperar los datos de la capacitacion almacenada en la tabla correspondiente
	$sql = "SELECT id_capacitacion,nom_capacitacion,hrs_capacitacion,descripcion,fecha_inicio,fecha_fin,instructor FROM capacitaciones
			JOIN empleados_reciben_capacitaciones ON id_capacitacion=capacitaciones_id_capacitacion WHERE empleados_rfc_empleado = '$id_empleado'";
	
	//Obtener nombre Recursos Humanos
	$nombre=obtenerNombreEmpleado($id_empleado);
	
	//Abrir conexion a la Base de Datos
	$conn = conecta("bd_recursos");
	//Ejecutar la consulta
	$rs = mysql_query($sql);
	if($datos = mysql_fetch_array($rs)){
		echo "				
		<table cellpadding='5' width='100%' align='center'> 
		<caption class='titulo_etiqueta'>Capacitaciones Recibidas por $nombre</caption></br>";
		echo "
		<tr>
			<td class='nombres_columnas' align='center'>ID CAPACITACI&Oacute;N</td>
			<td class='nombres_columnas' align='center'>NOMBRE CAPACITACI&Oacute;N</td>
			<td class='nombres_columnas' align='center'>HRS CAPACITACI&Oacute;N</td>
			<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
			<td class='nombres_columnas' align='center'>PERIODO</td>
			<td class='nombres_columnas' align='center'>INSTRUCTOR</td>
		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;	
		do{
			echo "
			<tr>					
				<td class='$nom_clase' align='center'>$datos[id_capacitacion]</td>					
				<td class='$nom_clase' align='center'>$datos[nom_capacitacion]</td>
				<td class='$nom_clase' align='center'>$datos[hrs_capacitacion] </td>
				<td class='$nom_clase' align='center'>$datos[descripcion]</td>
				<td class='$nom_clase' align='center'>Del ".modFecha($datos["fecha_inicio"],2)." al ".modFecha($datos["fecha_fin"],2)."</td>
				<td class='$nom_clase' align='center'>$datos[instructor]</td>
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
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		echo  "<p align='center'> <img src='../../images/no-capacitacion.png' align='center' width='350' height='350' border='0'/></p>";
		echo "<p align='center' class='titulo_etiqueta'><b>El Trabajador $nombre No tiene Capacitaciones Registradas</b></p>";?>
		
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
