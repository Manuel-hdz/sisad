<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 28/Febrero/2011
	  * Descripción: Este archivo contiene funciones para eliminar un Equipo de la BD de Mantenimiento
	**/

	//Esta funcion Da de Baja el Equipo en la Base de Datos
	function bajaEquipo($clave){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		
		//Creamos la sentencia SQL para mostrar los datos en Equipo
		$stm_sql="UPDATE equipos SET estado='BAJA' WHERE id_equipo='$clave'";
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($rs){
			//Si el equipo se dio de baja, eliminar su carpeta de documentos del sistema
			borrarDocumentos($clave);
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_mantenimiento","$clave","BajaEquipo",$_SESSION['usr_reg']);
			//Si los datos fueron eliminados, se redirecciona a la pagina de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php?'>";
		}
		else
			//Si no se encontraron Equipos con la clave proporcionada, mostrar mensaje
			echo "<label class='msje_correcto'>No se encontr&oacute; ning&uacute;n equipo con la Clave: <u><em>".$clave."</u></em></label>";
		
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);
		
	}//Fin de la funcion para dar de Baja el equipo
	
	//Funcion que borra la carpeta de Documentos de los Equipos Eliminados
	function borrarDocumentos($carpeta){
		//Obtener la carpeta donde se deben almacenar los documentos
		$carpeta="documentos/".$carpeta;
		//Verificar que exista carpeta de documentos para el equipo a Eliminar
		if (is_dir($carpeta)){
			$dir=dir($carpeta);
			while (false!==$entry=$dir->read()){
				//Evitar apuntadores y punteros
				if ($entry=='.' || $entry=='..'){
					continue;
				}
				//Borrar Directorios internos
				if (is_dir("$carpeta/$entry"))
					rmdir("$carpeta/$entry");
				else
					unlink("$carpeta/$entry");
			}
			$dir->close();
			return rmdir($carpeta);
		}
	}//Fin de la funcion de borrado de documentos
	
?>