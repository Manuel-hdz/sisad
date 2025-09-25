<?php

	/**
	  * Nombre del Módulo: Compras
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 17/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para desplegar la informacion de un cliente a consultar
	  **/


	//Esta función muestra el detalle del cliente seleccionado por el usuario
	function mostrarCliente($nombre){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");		
		
		//Crear la consulta para mostrar detalle de los Clientes		
		if($nombre=="todos"){
			$stm_sql = "SELECT * FROM clientes";
			$msj = "Datos de los Clientes Registrados";
		}
		else{
			$stm_sql = "SELECT * FROM clientes WHERE razon_social='$nombre'";
			$msj = "Datos del Cliente: $nombre";
		}
						
		//Ejecutar la Sentencia para obtener los datos del cliente seleccionado
		$rs = mysql_query($stm_sql);		            						
		if($datos = mysql_fetch_array($rs)){		
			echo "				
			<table cellpadding='5' > 
			<caption class='titulo_etiqueta'>$msj</caption>					
				<tr>
					<td class='nombres_columnas' align='center'>RFC</td>
					<td class='nombres_columnas' align='center'>ID FISCAL</td>
					<td class='nombres_columnas' align='center'>RAZÓN SOCIAL</td>
					<td class='nombres_columnas' align='center'>CALLE</td>
					<td class='nombres_columnas' align='center'>NÚMERO EXTERNO</td>
					<td class='nombres_columnas' align='center'>NÚMERO INTERNO</td>
					<td class='nombres_columnas' align='center'>COLONIA</td>
					<td class='nombres_columnas' align='center'>C&Oacute;DIGO POSTAL</td>
					<td class='nombres_columnas' align='center'>CIUDAD</td>
					<td class='nombres_columnas' align='center'>MUNICIPIO</td>
					<td class='nombres_columnas' align='center'>ESTADO</td>
					<td class='nombres_columnas' align='center'>TELÉFONO</td>
					<td class='nombres_columnas' align='center'>TELÉFONO 2</td>
					<td class='nombres_columnas' align='center'>FAX</td>
					<td class='nombres_columnas' align='center'>CORREO</td>
					<td class='nombres_columnas' align='center'>CURP</td>
					<td class='nombres_columnas' align='center'>CONTACTO</td>
					<td class='nombres_columnas' align='center'>REFERENCIA</td>
					<td class='nombres_columnas' align='center'>FECHA DE ALTA</td>
					<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	
					<tr>					
						<td class='nombres_filas' align='center'>$datos[rfc]</td>	
						<td class='$nom_clase' align='center'>$datos[id_fiscal]</td>					
						<td class='$nom_clase' align='left'>$datos[razon_social]</td>
						<td class='$nom_clase' align='left'>$datos[calle]</td>
						<td class='$nom_clase' align='center'>$datos[numero_ext]</td>
						<td class='$nom_clase' align='center'>$datos[numero_int]</td>					
						<td class='$nom_clase' align='left'>$datos[colonia]</td>
						<td class='$nom_clase' align='left'>$datos[cp]</td>
						<td class='$nom_clase' align='left'>$datos[ciudad]</td>
						<td class='$nom_clase' align='left'>$datos[municipio]</td>
						<td class='$nom_clase' align='left'>$datos[estado]</td>					
						<td class='$nom_clase' align='center'>$datos[telefono]</td>
						<td class='$nom_clase' align='center'>$datos[telefono2]</td>
						<td class='$nom_clase' align='center'>$datos[fax]</td>
						<td class='$nom_clase' align='left'>$datos[correo]</td>
						<td class='$nom_clase' align='center'>$datos[curp_contacto]</td>
						<td class='$nom_clase' align='center'>$datos[nom_contacto] $datos[ap_contacto] $datos[am_contacto]</td>
						<td class='$nom_clase' align='left'>$datos[referencia]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos["fecha_alta"],2)."</td>
						<td class='$nom_clase' align='left'>$datos[comentarios]</td>
					</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			echo "
			</table>	
			</form>";
		}
		else {
			if ($nombre!="todos")
				echo "<p  class='msje_correcto'>No se Encontr&oacute; Ning&uacute;n Cliente con el Nombre: <em><u>$nombre</u></em></p>";
			else
				echo "<p  class='msje_correcto'>No Existen Clientes Registrados</p>";
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion mostrarCliente($nombre)

?>