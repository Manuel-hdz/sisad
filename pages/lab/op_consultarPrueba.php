<?php
	/**
	  * Nombre del Módulo: Laboratorio
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 20/Junio/2011
	  * Descripción: Este archivo contiene funciones para Consultar las Pruebas de la BD de Laboratorio
	**/

	/*
		Valores de Patron
		1 => Busqueda por Tipo de Prueba
		2 => Busqueda por Norma de Prueba
		3 => Busqueda de Todas
	*/
	//Funcion que muestra las Pruebas segun los criterios especificados
	function mostrarPruebas($criterio,$patron){
		if ($patron==1){
			//Creamos la sentencia SQL para mostrar las pruebas por TIPO
			$stm_sql="SELECT * FROM catalogo_pruebas WHERE tipo='$criterio' AND estado='1'";	
			//Creamos el titulo de la tabla
			$titulo="Pruebas de Tipo <em>$criterio</em>";
		}
		if ($patron==2){
			//Creamos la sentencia SQL para mostrar las pruebas por NORMA
			$stm_sql="SELECT * FROM catalogo_pruebas WHERE norma='$criterio' AND estado='1'";	
			if ($criterio!="N/A")
				//Creamos el titulo de la tabla
				$titulo="Pruebas de la Norma <em>$criterio</em>";
			else
				//Creamos el titulo de la tabla
				$titulo="Pruebas Sin Norma de Referencia";
		}
		if ($patron==3){
			//Creamos la sentencia SQL para mostrar el catalogo de Pruebas
			$stm_sql="SELECT * FROM catalogo_pruebas WHERE estado='1'";	
			//Creamos el titulo de la tabla
			$titulo="Cat&aacute;logo de Pruebas";
		}
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_laboratorio");
			
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>";
			echo "<br />";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>NORMA</td>
						<td class='nombres_columnas' align='center'>TIPO</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{										
				echo "	<tr>		
							<td class='nombres_filas' align='center'>$datos[norma]</td>
							<td class='$nom_clase' align='left'>$datos[tipo]</td>
							<td class='$nom_clase' align='left'>$datos[nombre]</td>
							<td class='$nom_clase' align='center'>$datos[descripcion]</td>
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
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarPruebas	
?>