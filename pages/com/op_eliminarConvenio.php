<?php
/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 21/Diciembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para guardar en la BD la información acerca de los nuevos clientes que se agregen dentro del sistema.
	  **/
	  
	  /**
      * Listado del contenido del programa                                            
      * Includes: 
	    1. Modulo de conexion con la base de datos*/
		include("../../includes/conexion.inc");
		include("../../includes/op_operacionesBD.php");
	  /**   Código en: pages\com\op_eliminarConvenio.php                                   
       **/
		
	
	if(isset($_POST["hdn_conv"])){
		
				
		$convenio=$_POST["hdn_conv"];
		//Inicializar band en 0, si permanece con este valor, se generaron errores
		$band=0;
		
		//Realizar la conexion a la BD de Compras
		$conn=conecta("bd_compras");
		
		//Crear la sentencia para eliminar el convenio de la lista de convenios
		$stm_sql = "DELETE FROM convenios WHERE id_convenio='$convenio'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Confirmar que el borrado de datos fue realizado con exito.
		if($rs){
			//Crear la sentencia para eliminar el detalle de los convenios
			$stm_sql="DELETE FROM detalles_convenio WHERE convenios_id_convenio='$convenio'";
			//Ejecutar la sentencia previamente creada
			$rs2 = mysql_query($stm_sql);
			if($rs){
				session_start();
				registrarOperacion("bd_compras",$convenio,"EliminarConvenio",$_SESSION['usr_reg']);
				//Activar band, indicando que todo se ejecutó con éxito
				$band=1;
			}
			else{
				$error = mysql_error();			
				echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
			}
		}
		else{
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}
		//Si band tomo el valor de 1, todo se generó correctamente
		if ($band==1)
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		//Cerrar la conexion con la BD
		//La Conexion a la BD se cierra en la función 
	}
?>