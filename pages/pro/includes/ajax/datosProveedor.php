<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 17/Febrero/2011                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");			
	/**   Código en: ../man/includes/ajax/cargarComboEquipoMtto.php
      **/	
	  
	
	//Codigo para obtener los Equipos activos de una Familia de una de las Áreas (Mina o Concreto)
	if(isset($_GET['razonSocial'])){	
		//Recuperar los datos a buscar de la URL
		$razonSocial = $_GET["razonSocial"];		

		//Conectarse a la BD
		$conn = conecta("bd_compras");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT calle, numero_ext, numero_int, colonia, ciudad, estado, cp FROM proveedores WHERE razon_social = '$razonSocial'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Armar la Direccion, colocar Calle y numero
			$direccion = $datos['calle']." #".$datos['numero_ext'];
			
			//Verificar si hay numero interior y colocarlo
			if($datos['numero_int']!="")
				$direccion .= " INT. ".$datos['numero_int'].", ";
			else
				$direccion .= ", ";
			
			//Agregar la Colonia
			$direccion .= $datos['colonia'].", ";
			//Agregar la Ciudad
			$direccion .= $datos['ciudad'].", ";
			//Agregar la Estado
			$direccion .= $datos['estado'];
			
			//Verificar si hay Codigo Postal
			if($datos['cp']!="")
				$direccion .= " C.P. ".$datos['cp'];
			else
				$direccion .= ".";
			
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<direccion>$direccion</direccion>	
				</existe>");
		}//Cierre if($datos=mysql_fetch_array($rs))
		else{
			echo "<valor>false</valor>";
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
		
	}//Cierre if(isset($_GET['razonSocial']))


?>