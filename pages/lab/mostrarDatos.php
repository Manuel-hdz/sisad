<?php 
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 25/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario frm_registrarPruebasMezclas2.php
	**/


	if(isset($_GET['mostrar'])){
		mostrarDatosPrueba();	
	}

	function mostrarDatosPrueba(){
		//Hoja de estilos para la ventana emergente
		echo"<link rel='stylesheet' type='text/css' href='../../includes/estilo.css'/>";
		
		session_start();
		
		echo "				
		<table cellpadding='5' align='center' width='100%'>
			<caption >RESULTADOS DE LA PRUEBA</caption>      			
			<tr>
				<td class='nombres_columnas' align='center'>ID MEZCLA</td>
        		<td class='nombres_columnas' align='center'>F'c</td>
				<td class='nombres_columnas' align='center'>CARGA RUPTURA</td>
			    <td class='nombres_columnas' align='center'>PORCENTAJE</td>
				<td class='nombres_columnas' align='center'>FECHA RUPTURA</td>
				<td class='nombres_columnas' align='center'>EDAD</td>
				<td class='nombres_columnas' align='center'>KG/CM&sup2;</td>
				<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($_SESSION['resPruebas'] as $ind => $concepto) {
			echo "<tr>";
			foreach ($concepto as $key => $value) {
				switch($key){
					case "idMuestra":
						echo "<td class='nombres_filas' align='center'>$value</td>";
					break;
					case "fc":
						echo "<td class='$nom_clase'>$value</td>";
					break;
					case "cargaRuptura":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "porcentaje":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "fechaRuptura":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "edad":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "kgCm":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
					case "observaciones":
						echo "<td class='$nom_clase' align='center'>$value</td>";
					break;
				}				
			}
			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";?>
        
			<script type="text/javascript" language="javascript">
				setTimeout("window.close()",14000);  
            </script><?php
			
	}//FIN function mostrarDatosPrueba()

?>