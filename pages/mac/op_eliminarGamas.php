<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 07/Marzo/2011
	  * Descripción: Este archivo contiene funciones para eliminar la información relacionada con las Gamas de los Equipos
	**/
	
	
	/*Esta función elimina la gama seleccionada por el usuario*/
	function borrarGama(){
		//Conectarse con la Base de Datos
		$conn = conecta("bd_mantenimiento");
		
		//Crear la Sentencia SQL para eliminar la gama y las actividades asociadas a ella
		$stm_sql = "DELETE FROM gama WHERE id_gama = '$_POST[cmb_claveGama]'";
		//Ejecutar la Consulta
		$rs = mysql_query($stm_sql);
		//Verificar resultados
		if($rs){
			borrarSistemasGama($_POST['cmb_claveGama']);
			
			//Registrar la Operacion realizada en la tabla de Bitacora de Movimientos
			registrarOperacion("bd_mantenimiento",$_POST['cmb_claveGama'],"EliminarGama",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			
			//Cerrar la conexion con la Base de Datos
			mysql_close($conn);
		}				
				
	}
	
	
	/*Esta funcion borra de las tablas de gama_actividades y actividades la información relacionada con la Gama que se quiere borrar*/
	function borrarSistemasGama($claveGama){
		//Obtener las claves de las actividades pertenecientes a la Gama que se quiere borrar
		$stm_sql = "SELECT actividades_id_actividad FROM gama_actividades WHERE gama_id_gama = '$claveGama'";
		$rs = mysql_query($stm_sql);
		$claves = array();
		while($datos_claves=mysql_fetch_array($rs)){
			$claves[] = $datos_claves['actividades_id_actividad'];
		}
		
		//Proceder a borrar las Actividades y la relacion de las actividades con la Gama registradas en la tabla gama_actividades
		foreach($claves as $ind => $clave){
			//Quitar de la tabla gama_actividades
			mysql_query("DELETE FROM gama_actividades WHERE actividades_id_actividad = '$clave' AND gama_id_gama = '$claveGama'");
			//Quitar de la tabla actividades
			mysql_query("DELETE FROM actividades WHERE id_actividad = '$clave'");
		}			
	}

?>