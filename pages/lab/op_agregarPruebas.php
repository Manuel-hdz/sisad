<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 17/Junio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de AgregarPrueba en la BD
	  **/
	 
	 //Verificar que el boton de Guardar venga en el POST, de esta manera podemos mandar llamar a la funcion que 
	 //Realiza el registro de la Prueba
	 if (isset($_POST["sbt_guardar"])){
	 	agregarPrueba();
	 }

	//Funcion que registra la Prueba en la Base de Datos	 
	function agregarPrueba(){
		include_once("../../includes/conexion.inc");			
		//Obtener el ID que le corresponde
		$id=calcularIdPrueba();
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");	 
		//Convertir los caracteres de los campos de texto en Mayúsculas
		$txt_nombre = strtoupper($_POST["txt_nombre"]);
		//Convertir los caracteres de los campos de texto en Mayúsculas
		$txt_norma = strtoupper($_POST["txt_norma"]);
		//Si no se ha incluido la norma, asignamos el valor N/A automaticamente
		if ($txt_norma=="")
			$txt_norma="N/A";
		//Definir de donde se obtendra el valor del tipo de Prueba
		$txt_tipo="";
		//Si se encuentra definido un nuevo tipo, tomar el valor de la caja de texto correspondiente
		if (isset($_POST["txt_nuevoTipo"]))
			$txt_tipo=strtoupper($_POST["txt_nuevoTipo"]);
		else
			$txt_tipo=$_POST["cmb_tipo"];
		$txa_descripcion = strtoupper($_POST["txa_descripcion"]);
						
		//Crear la sentencia para realizar el registro de la Nueva Prueba en la Base de Datos, no se carga el estado ya que por default toma el valor de 1
		$stm_sql = "INSERT INTO catalogo_pruebas (id_prueba,norma,tipo,nombre,descripcion) VALUES('$id','$txt_norma','$txt_tipo','$txt_nombre','$txa_descripcion')";					
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
									
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs)
			//registrarOperacion("bd_laboratorio",$id,"AgregarPrueba",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";

		}	
	}
	
	function calcularIdPrueba(){
		//Realizar la conexion a la BD de Laboratorio
		$conn = conecta("bd_laboratorio");	
		//Crear la sentencia para obtener el numero de Requisicion registradas 
		$stm_sql = "SELECT MAX(id_prueba) AS max FROM catalogo_pruebas";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$id_cadena=$datos["max"]+1;
		}
		else
			$id_cadena=1;
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id_cadena;
	}
?>
