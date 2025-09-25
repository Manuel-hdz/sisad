	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �CONCRETO LANZADO DE FRESNILLO MARCA');
			}
		}
		document.onmousedown=click;						
		//-->
	</script>
<?php

	/**
	  * Nombre del M�dulo: Recursos Humanos                                               
	  * Nombre Programador: Daisy Adriana Mart�nez Fern�ndez	
	  * Fecha: 12/Mayo/2011
	  * Descripci�n: Este archivo contiene funciones para Ver los Puestos de los Aspirantes
	  **/ 
	//Verificamos si viene el id del aspirante en el get; de ser asi llamar la funcion mostrarPuestos()
	if(isset($_GET['id_aspirante']))
		mostrarPuestos();
	//Funci�n que permite mostrar los puestos del empleado
	function mostrarPuestos(){	
		//Incluimos arrchivo de conexion
		include ("../../includes/conexion.inc");
		//Incluimos el archivo para modificar las fechas para la consulta
		include ("../../includes/func_fechas.php");
		//Hoja de estilo para la ventana emergente
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
	
		//Recuperar el ID del aspirante
		$id_aspirante=$_GET["id_aspirante"];
		//Recuperamos las fechas
		$fechaIni=$_GET["fechaIni"];
		$fechaFin=$_GET["fechaFin"];

		//Creamos la consulta SQL
		$sql = "SELECT puesto FROM (area_puesto JOIN bolsa_trabajo ON bolsa_trabajo_folio_aspirante=folio_aspirante)
				WHERE folio_aspirante = '$id_aspirante' AND fecha_solicitud>='$fechaIni' AND fecha_solicitud<='$fechaFin'";
	
		//Abrir conexion a la Base de Datos
		$conn = conecta("bd_recursos");
		//Ejecutar la consulta
		$rs = mysql_query($sql);
		if($datos = mysql_fetch_array($rs)){
			echo "				
			<table cellpadding='5' width='100%' align='center'> 
				<caption class='titulo_etiqueta'>Puestos del Aspirante  $id_aspirante</caption></br>";
				echo "
					<tr>
						<td class='nombres_columnas' align='center'>NO</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
					<tr>					
						<td class='$nom_clase' align='center'>$cont</td>
						<td class='$nom_clase' align='center'>$datos[puesto]</td>";						
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
			echo "<p align='center' class='titulo_etiqueta'><b>No hay  Puestos registrados para <br />
				$id_aspirante</b></p>";?>
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
