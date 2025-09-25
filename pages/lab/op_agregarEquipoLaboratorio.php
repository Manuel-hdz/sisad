<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 18/Junio/2011
	  * Descripción: Contiene las funciones para registrar los datos relacionados con los Equipos
	  **/
	 
	//Verificamos que este definido el botón de guardar en el post
	 if (isset($_POST["sbt_guardar"])){
	 	agregarEquipo();
	 }

		//Esta funcion genera la Clave del de acuerdo a los registros en la BD
	function obtenerIdEquipo(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(no_interno)+1 AS cant FROM equipo_lab";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant=="")
				$id_cadena=001;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()

	
	function agregarEquipo(){
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");	 			
		
		//Guardamos los datos que vienen del post para darles el tratamiento segun la BD
		$noInterno=strtoupper($_POST["txt_noInterno"]);
		$nomEquipo=strtoupper($_POST["txt_nomEquipo"]);
		$responsable=strtoupper($_POST["txt_responsable"]);
		$aplicacion=strtoupper($_POST["txa_aplicacion"]);
		
		//Poner tres guiones medios en caso de que no vengan definidos los datos en el POST
		if($_POST["txt_marca"]!="")
			$marca=strtoupper($_POST["txt_marca"]);
		else
			$marca="---";
		if($_POST["txt_noSerie"]!="")
			$noSerie=strtoupper($_POST["txt_noSerie"]);
		else
			$noSerie="---";
		if($_POST["txt_resolucion"]!="")
			$resolucion=strtoupper($_POST["txt_resolucion"]);
		else
			$resolucion="---";
		if($_POST["txt_escala"]!="")
			$escala=strtoupper($_POST["txt_escala"]);
		else
			$escala="---";
		if($_POST["txt_exactitud"]!="")
			$exactitud=strtoupper($_POST["txt_exactitud"]);
		else
			$exactitud="---";
		if($_POST["cmb_calibrable"]=="SI")
			$servicio=1;
		else
			$servicio=0;
		
	 	
						
		//Crear la sentencia para realizar el registro del nuevo Equipo en la BD de Laboratorio
		$stm_sql = "INSERT INTO equipo_lab VALUES('$noInterno','$nomEquipo','$marca', '$noSerie', '$resolucion', '$escala', '$exactitud','$responsable',
				   '$aplicacion', '$servicio','1')";					
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
									
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			registrarOperacion("bd_Laboratorio",$noInterno,"AgregarEquipo",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";

		}	
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		//La Conexion a la BD se cierra en la funcion registrarOperacion();
	}	
?>
ss