<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Nadia Madahí López Hernandez
	  * Fecha: 
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Registrar el contacto del Aspirante en la sección de Bolsa de Trabajo en la BD
	**/
	include_once("../../includes/conexion.inc");
	include_once("../../includes/op_operacionesBD.php");	
	include_once("../../includes/func_fechas.php");
	
	//Haciendo referencia al indice
	//Verificar si la variable 'txt_folioAspirante' esta definida en el arreglo POST
	if(isset ($_POST['txt_folioAspirante']))
		//Se manda llamar la funcion de registrarAspirante la cual contienen las consultas para almacenar los datos del aspirante
		registrarContacto();
	


	function registrarContacto(){
		//Abrimos la Conexión a la bd de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		//Nos traemos la información que se va agregar a a la BD desde el vector $_POST[] ya que esta información vienen desde el formulario frm_registrarAspirante en el $_POST[]
		//$folioAspirante = strtoupper($_POST['txt_folioAspirante']);
		$nombreCont = strtoupper($_POST['txt_nombreCont']);
		$calle = strtoupper($_POST['txt_calle']);
		$colonia = strtoupper($_POST['txt_colonia']);
		$estado = strtoupper($_POST['txt_estado']);
		$pais = strtoupper($_POST['txt_pais']);

		
		//Por el alcance de las variables se tiene que hacer referencia a estas variables con el vector $_POST[]   aunque ya vengan en el POST
		$tel = $_POST['txt_tel'];
			
		//Creamos la sentencia SQL para guardar el agregar el Contacto del Aspirante en BD de recursos
		$stm_sql = "INSERT INTO bolsa_trabajo 
		(bolsa_trabajo_folio_aspirante, nom_contacto, calle, num_ext, num_int, colonia, estado, pais, telefono) 
		VALUES ('$bolsa_trabajo_folio_aspirante', '$nom_contacto', '$calle', '$num_ext', '$num_int', $colonia, '$estado', '$pais', '$telefono')";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
	
		//Confirmar que la inserción de datos fue realizada con exito.
		if($rs){ 
			
			if($rs){
				header("Location:exito.php");			
			}
			else{
				$error = mysql_error();			
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		mysql_close($conn);
	}
?>



