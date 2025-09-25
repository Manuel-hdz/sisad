<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas, Maurilio Hernández Correa
	  * Fecha: 14/Julio/2011                                      			
	  * Descripción: Este archivo contiene la funcion que valida que el presupuesto no este incluido en otro 
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/func_fechas.php");			
	/**    
      **/	
		 
	//Recuperar los datos a buscar de la URL
	$fecha1 = modFecha($_GET["fecha1"],3);
	$fecha2 = modFecha($_GET["fecha2"],3);
	$clave = $_GET["clave"];
	$idUbicacion = $_GET["ubicacion"];
	
	//Conectarse a la BD
	$conn = conecta("bd_gerencia");
	$bandF1 = 0;
	$bandF2 = 0;
	$bandF3 = 0;//Esta variable indicara si el rango de fechas seleccionado contiene el rango de fechas registrado en la BD para la ubicacion seleccionada
	
	//Cuando la clave esta vacia es porq se esta agregando un registro nuevo de lo contrario es una modificacion
	if($clave==""){
		//Crear la Sentencia SQL
		$sql_stm = "SELECT id_presupuesto
					FROM presupuesto
					WHERE  '$fecha1' >= fecha_inicio
					AND  '$fecha1' <= fecha_fin
					AND id_control_costos =  '$idUbicacion'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		if(mysql_num_rows($rs)>0)
			$bandF1 = 1;
		
		$sql_stm2 = "SELECT id_presupuesto
					FROM presupuesto
					WHERE  '$fecha2' >= fecha_inicio
					AND  '$fecha2' <= fecha_fin
					AND id_control_costos =  '$idUbicacion'";
		//Ejecutar la Sentencia previamente creada
		$rs2 = mysql_query($sql_stm2);
			
		if(mysql_num_rows($rs2)>0)
			$bandF2 = 1;
		
		//Verificar que el rango de fechas seleccionado no contenga el rango de fechas registradas en la BD	
		if($bandF1==0&&$bandF2==0){
			//Crear la Sentencia SQL
			$sql_stm = "SELECT id_presupuesto
						FROM presupuesto
						WHERE  fecha_inicio>='$fecha1' 
						AND fecha_fin<='$fecha2'
						AND id_control_costos =  '$idUbicacion'";
			//Ejecutar la Sentencia previamente creada
			$rs = mysql_query($sql_stm);
			
			if(mysql_num_rows($rs)>0){
				$bandF1 = 1;
				$bandF2 = 1;
				$bandF3 = 1;				
			}	
		}//Cierre if($bandF1==0&&$bandF2==0)
		
	}//FIN if($clave=="")
	else{
		//Crear la Sentencia SQL
		$sql_stm = "SELECT id_presupuesto
					FROM presupuesto
					WHERE  '$fecha1' >= fecha_inicio
					AND  '$fecha1' <= fecha_fin
					AND id_control_costos =  '$idUbicacion'
					AND id_presupuesto!='$clave'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		
		if(mysql_num_rows($rs)>0)
			$bandF1 = 1;
		
		$sql_stm2 = "SELECT id_presupuesto
					FROM presupuesto
					WHERE  '$fecha2' >= fecha_inicio
					AND  '$fecha2' <= fecha_fin
					AND id_control_costos =  '$idUbicacion' 
					AND id_presupuesto!='$clave'";
		//Ejecutar la Sentencia previamente creada
		$rs2 = mysql_query($sql_stm2);
			
		if(mysql_num_rows($rs2)>0)
			$bandF2 = 1;		
		
		//Verificar que el rango de fechas seleccionado no contenga el rango de fechas registradas en la BD	
		if($bandF1==0&&$bandF2==0){
			//Crear la Sentencia SQL
			$sql_stm = "SELECT id_presupuesto
						FROM presupuesto
						WHERE  fecha_inicio>='$fecha1' 
						AND fecha_fin<='$fecha2'
						AND id_control_costos =  '$idUbicacion' 
						AND id_presupuesto!='$clave'";
			//Ejecutar la Sentencia previamente creada
			$rs = mysql_query($sql_stm);
			
			if(mysql_num_rows($rs)>0){
				$bandF1 = 1;
				$bandF2 = 1;
				$bandF3 = 1;
			}	
		}//Cierre if($bandF1==0&&$bandF2==0)
	}
	
		
	//Definir el tipo de contenido que tendra el archivo creado
	header("Content-type: text/xml");	 
	//Comparar los resultados obtenidos 
	if($bandF1==1 || $bandF2==1){
		//indicar el valor que ha tomado hdn_fechas, 1 significa que las 2 fechas estan mal
		if($bandF1==1 && $bandF2==1 && $bandF3==0){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial			
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<caso>1</caso>
				</existe>");
		}	
		//indicar el valor que ha tomado hdn_fechas, 2 significa  que la fecha de inicio esta mal
		if($bandF1==1 && $bandF2==0){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial			
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<caso>2</caso>
				</existe>");
		}	
		//indicar el valor que ha tomado hdn_fechas, 3 significa  que la fecha de fin esta mal
		if($bandF1==0 && $bandF2==1){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial			
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<caso>3</caso>
				</existe>");
		}
		//indicar el valor que ha tomado hdn_fechas, 4 significa el rango seleccionado contiene al rango registrado en la BD
		if($bandF1==1 && $bandF2==1 && $bandF3==1){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial			
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<caso>4</caso>
				</existe>");
		}
	}
	else
		echo "<valor>false</valor>";
		
	//Cerrar la conexion a la BD
	mysql_close($conn);
	
	
?>