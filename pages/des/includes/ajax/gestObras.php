<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 03/Marzo/2012
	  * Descripción: Este archivo genera el codigo HTML de la tabla que mostrar los datos de la obra seleccionada
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
			include("../../../../includes/func_fechas.php");
		 
		 
	//Recuperar los datos a buscar de la URL
	if(isset($_GET["idObra"])){
		$idObra = $_GET["idObra"];
		
		//Obtener el codigo HTML de la tabla
		$codigoHTMLTabla = crearCodigoTabla($idObra);
		
		header("Content-type: text/xml");
		//Si el codigo HTML de la tabla es diferente de vacio proceder a crear el código XML 
		if($codigoHTMLTabla!=""){
			//Sustituir el tag de apertura '<' por el simbolo '¬' para que no tenga conflictos con los tags del codigo XML que serán generados
			$tabla = str_replace("<","¬",$codigoHTMLTabla);
			//Crear XML con el codigo HTML de la tabla
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<tabla>$tabla</tabla>					
				</existe>");
		}
		else{
			//Crear XML que indica que no se produjeron resultados
			echo utf8_encode("
			<existe>
				<valor>false</valor>
			</existe>");
		}
		
	}//Cierre if(isset($_GET["idObra"]))	
	
	
	//Recuperar los datos a buscar de la URL
	if(isset($_GET['opcCargar'])){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Crear sentencia SQL para obtener las obras registradas en el Catálogo de Desarrollo
		$sql_stm = "SELECT id_ubicacion, obra FROM catalogo_ubicaciones ORDER BY id_ubicacion";
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
		//Obtener la cantidad de Obras registradas		
		$cantObras = mysql_num_rows($rs);		
		$cont = 1;
		
		//Verificar si hay registros
		header("Content-type: text/xml");
		if($datos=mysql_fetch_array($rs)){
			//Crear XML con el codigo HTML de la tabla
			echo "<existe>
					<valor>true</valor>
					<tam>$cantObras</tam>";
			do{
				//Crear XML con el codigo HTML de la tabla
				echo utf8_encode("
					<clave$cont>$datos[id_ubicacion]</clave$cont>
					<obra$cont>$datos[obra]</obra$cont>");
					$cont++;
			}while($datos=mysql_fetch_array($rs));
			echo "</existe>";
		}
		else{
			//Crear XML que indica que no se produjeron resultados
			echo utf8_encode("
			<existe>
				<valor>false</valor>
			</existe>");
		}						
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre if(isset($_GET['opcCargar']))
	
	
	
	
	
	/****************************************************************************************************************************************/
	/************************************************SECCION DE FUNCIONES********************************************************************/
	/****************************************************************************************************************************************/
	
	/*Esta función genera el codigo de la tabla que mostrará los datos de la(s) Obra(s) Seleccionada(s)*/
	function crearCodigoTabla($idObra){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Esta variable contendrá el código HTML de la tabla
		$codHtmlTabla = "";
		$sql_stm = "";
		$msg = "";
		
		if($idObra=="TODAS"){
			//Crear la Sentencia SQL para obtener los datos de todas las Obras
			$sql_stm = "SELECT * FROM catalogo_ubicaciones";			
		}
		else{
			//Crear la Sentencia SQL para obtener los datos de la Obra indicada
			$sql_stm = "SELECT * FROM catalogo_ubicaciones WHERE id_ubicacion = $idObra";			
		}
		
					
		//Ejecutar Sentencia			
		$rs = mysql_query($sql_stm);
		
		
		if($datos=mysql_fetch_array($rs)){
			//Definir el Titulo de la tabla de acuerdo a la opción seleccionada en las lista desplegable de Obras
			if($idObra=="TODAS") $msg = "Datos de las Obras Registradas en el Catálogo de Obras";
			else $msg = "Datos de las Obra $datos[obra]";
		
		
			$codHtmlTabla = "
				<table width='100%' cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg</caption>								
					<tr>
						<td align='center' class='nombres_columnas'>CLAVE OBRA</td>
						<td align='center' class='nombres_columnas'>OBRA</td>
						<td align='center' class='nombres_columnas'>AREA</td>
						<td align='center' class='nombres_columnas'>BLOQUE</td>
						<td align='center' class='nombres_columnas'>CLIENTE</td>
					</tr>";
			//Definir el estilo de los renglones que compondran la tabla generada
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				
				//Obtener el Nombre del Cliente				
				$cliente = obtenerDato("bd_desarrollo", "catalogo_clientes", "nom_cliente", "id_cliente", $datos['catalogo_clientes_id_cliente']);
				if($cliente=="")
					$cliente = "N/D";
				
				$codHtmlTabla .= "
					<tr>
						<td align='center' class='nombres_filas'>$datos[id_ubicacion]</td>
						<td align='center' class='$nom_clase'>$datos[obra]</td>
						<td align='center' class='$nom_clase'>$datos[area]</td>
						<td align='center' class='$nom_clase'>$datos[bloque]</td>
						<td align='center' class='$nom_clase'>$cliente</td>
					</tr>";
								
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			//Cerrar la Tabla
			$codHtmlTabla .= "</table>";
			
		}//Cierre ($datos=mysql_fetch_array($rs))
		
		
		//Retornar el codigo de la tabla generada
		return $codHtmlTabla;
				
	}//Cierre de la función crearCodigoTabla($idObra)
	
	
?>