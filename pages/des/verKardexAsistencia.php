<?php

	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 18/Octubre/2011
	  * Descripción: Este archivo contiene funciones para Ver el Kardex de Asistencia del Enmpleado
	  **/ 
	//Verificamos si viene el id del empleado en el get; de ser asi llamar la funcion mostrarKardex()
	if(isset($_GET['id_empleado']))
		mostrarKardex();
	//Función que permite mostrar el kardex del empleado
	function mostrarKardex(){	
		//Incluimos arrchivo de conexion
		include ("../../includes/conexion.inc");
		//Incluimos archivo para realizar operaciones de obtener nombre empleado
		include ("../../includes/op_operacionesBD.php");
		//Incluimos el archivo para modificar las fechas para la consulta
		include ("../../includes/func_fechas.php");
		//Hoja de estilo para la ventana emergente
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	
		//Recuperar el ID del empleado
		$id_empleado=$_GET["id_empleado"];
		//Recuperamos las fechas
		$fechaIni=$_GET["fechaIni"];
		$fechaFin=$_GET["fechaFin"];

		//Creamos la consulta SQL
		$sql = "SELECT fecha_entrada,  estado FROM kardex WHERE empleados_rfc_empleado = '$id_empleado' 
							AND estado='A' AND fecha_entrada>='$fechaIni' AND fecha_entrada<='$fechaFin' ORDER BY fecha_entrada";
	
		//Obtener nombre Recursos Humanos
		$nombre=obtenerNombreEmpleado($id_empleado);
	
		//Abrir conexion a la Base de Datos
		$conn = conecta("bd_recursos");
		//Ejecutar la consulta
		$rs = mysql_query($sql);
		if($datos = mysql_fetch_array($rs)){
			echo "				
			<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>Kardex del Empleado  $nombre</caption></br>";
				echo "
					<tr>
						<td class='nombres_columnas' align='center'>NO</td>
						<td class='nombres_columnas' align='center'>D&Iacute;A</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
					<tr>					
						<td class='$nom_clase' align='center'>$cont</td>					
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_entrada'],1)."</td>";
						//Determinamos el color de las letras a mostrar
						if($datos['estado']=='A')
							echo"<td class='$nom_clase' align='center'> <label  class='msje_correcto'>A</label></td>";
						elseif($datos['estado']=='F')
							echo"<td class='$nom_clase' align='center'><label  class='msje_incorrecto'>F</label></td>";
						else
							echo" <td class='$nom_clase' align='center'><strong>$datos[estado]</strong></td>";
						
			echo "</tr>";
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
					<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" 
					onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
				</td>
			</tr>
			<?php
		}
		else{
			echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
			echo  "<p align='center'> <img src='../../images/no-kardex.png' align='center' width='250' height='250' border='0'/></p>";
			echo "<p align='center' class='titulo_etiqueta'><b>No hay registro del Kardex para <br />
				$nombre</b></p>";?>
		<br /><br />
		<p align="center">
			<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" 
			onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
		</p>
		<?php
	}
	//Cerrar la conexion
	mysql_close($conn);
}
?>
