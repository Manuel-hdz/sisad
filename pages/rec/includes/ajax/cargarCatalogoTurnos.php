<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 20/Octubre/2011                                      			
	  * Descripción: Este archivo contiene la función que carga el Catálogo de Salarios en la Sección Sueldo de Desarrollo
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/op_operacionesBD.php");
			include("../../../../includes/func_fechas.php");
		 
	//Recuperar los datos a buscar de la URL
	if (isset($_GET["turno"])){
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		//Recoger los datos
		$turno = $_GET["turno"];
		//Sentencia SQL
		$sql_stm="SELECT * FROM turnos WHERE nom_turno='$turno'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Obtener los datos para manejarlos
		if($datos=mysql_fetch_array($rs)){
			$horaE=modHora($datos["hora_entrada"]);
			$merE=substr($horaE,-2);
			$horaE=str_replace(" am","",$horaE);
			$horaE=str_replace(" pm","",$horaE);
			if (strlen($horaE)==7)
				$horaE="0".substr($horaE,0,4);
			if (strlen($horaE)==8)
				$horaE=substr($horaE,0,5);
			$horaS=modHora($datos["hora_salida"]);
			$merS=substr($horaS,-2);
			$horaS=str_replace(" am","",$horaS);
			$horaS=str_replace(" pm","",$horaS);
			if (strlen($horaS)==7)
				$horaS="0".substr($horaS,0,4);
			if (strlen($horaS)==8)
				$horaS=substr($horaS,0,5);
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<id>$datos[id_turno]</id>
					<nombre>$datos[nom_turno]</nombre>
					<horaE>$horaE</horaE>
					<merE>$merE</merE>
					<horaS>$horaS</horaS>
					<merS>$merS</merS>
					<comentarios>$datos[comentarios]</comentarios>
				</existe>");
		}else{
			//Definir el tipo de contenido que tendra el archivo creado
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");
		}
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
?>
