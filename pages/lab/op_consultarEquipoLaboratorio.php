<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 18/Junio/2011                                      			
	  * Descripción: Este archivo permite consultar los Equipos de la Base de datos
	  **/
	 	

	//Función que permite mostrar los Equipos Registrados en las fechas especificadas
	function mostrarEquipos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_Laboratorio");
	
		if(isset($_POST["cmb_marca"])){
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM equipo_lab WHERE marca='$_POST[cmb_marca]' AND estado=1 ORDER BY no_interno";
			
			//Variable que guarda el titulo de la tabla
			$titulo="Equipos Registrados con la Marca <u><em>".$_POST["cmb_marca"] ."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Equipos Registrados con la Marca <u><em>". $_POST["cmb_marca"]."</u></em>";
		}
		else{
			//Creamos la sentencia SQL
			$stm_sql ="SELECT * FROM equipo_lab WHERE no_interno='$_POST[txt_noInterno]' AND estado=1 ORDER BY no_interno";
			
			//Variable que guarda el titulo de la tabla
			$titulo="Equipos Registrados con el N&uacute;mero Interno <u><em>".$_POST["txt_noInterno"]."</u></em>";
			
			//Variable que almacena el msj de error
			$error="No existen Equipos Registrados el N&uacute;mero Interno <u><em>". $_POST["txt_noInterno"]."</u></em>";
		}
			
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>$titulo</caption>					
				<tr>
					<td class='nombres_columnas'>NO INTERNO</td>
					<td class='nombres_columnas'>NOMBRE</td>
					<td class='nombres_columnas'>MARCA</td>
					<td class='nombres_columnas'>NO SERIE</td>
					<td class='nombres_columnas'>RESOLUCI&Oacute;N</td>
					<td class='nombres_columnas'>ESCALA</td>
					<td class='nombres_columnas'>EXACTITUD</td>
					<td class='nombres_columnas'>ENCARGADO</td>
					<td class='nombres_columnas'>APLICACI&Oacute;N</td>
					<td class='nombres_columnas'>CALIBRABLE</td>
			</tr>";		
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td align='center' class='$nom_clase'>$datos[no_interno]</td>
						<td align='center' class='$nom_clase'>$datos[nombre]</td>
						<td align='center' class='$nom_clase'>$datos[marca]</td>
						<td align='center' class='$nom_clase'>$datos[no_serie]</td>
						<td align='center' class='$nom_clase'>$datos[resolucion]</td>					
						<td align='center' class='$nom_clase'>$datos[escala]</td>
						<td align='center' class='$nom_clase'>$datos[exactitud]</td>
						<td align='center' class='$nom_clase'>$datos[encargado]</td>
						<td align='center' class='$nom_clase'>$datos[aplicacion]</td>";
						if($datos['calibrable']== '1'){
							echo "<td align='center' class='$nom_clase'>SI</td>";
						}
						else{
							echo "<td align='center' class='$nom_clase'>NO</td>";
						}
				echo "</tr>";									
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));

			echo "</table>";
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>$error</label>";
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
?>