<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 12/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de generar nombramiento en la BD
	**/
	
	/*Esta funcion genera el id del nombramiento de acuerdo a los registros en la BD*/
	function obtenerIdNombramiento(){
		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		$id="";
		//Crear la sentencia para obtener el id del nombramiento Reciente
		$stm_sql = "SELECT MAX(id) AS cant FROM nombramientos";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
			$id .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id;
	}//Fin de la function obtenerIdNombramiento()

	 //Funcion para guardar el nombramiento
	 function guardarNombramiento(){
	 
		//Recoger los datos
		$id= obtenerIdNombramiento();
		$fecha= modfecha($_POST['txt_fechaNombramiento'],3);
		$nombre= strtoupper ($_POST['txt_nombre']);
		$objetivo= strtoupper($_POST['txa_objetivo']);
		
		//Verificamos si viene el combo Activo de AREA o proviene de la caja de texto de nueva area para preparar la Sentencia SQL
		if (isset($_POST["cmb_area"]))
			$area=$_POST["cmb_area"];
		else
			$area=strtoupper($_POST["txt_nuevaArea"]);
			
		//Verificamos si viene el combo Activo de PUESTO o proviene de la caja de texto de nuevo puesto para preparar la Sentencia SQL
		if (isset($_POST["cmb_puesto"]))
			$puesto=$_POST["cmb_puesto"];
		else
			$puesto=strtoupper($_POST["txt_nuevoPuesto"]);

 		//Realizar la conexion a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		
		//Creamos la sentencia SQL para obtener el rfc del empleado seleccionado y poderlo almacenar en la tabla de nombramientos
		$stm_sql1= "SELECT rfc_empleado FROM empleados 	WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$nombre'";
		//Ejecutar la sentencia 
		$rs1 = mysql_query($stm_sql1);
		$datos1 = mysql_fetch_array($rs1);
		
		//Crear la Sentencias SQL para Alamcenar los datos del nombramiento
		$stm_sql= "INSERT INTO nombramientos (id, fecha, empleados_rfc_empleado, area, puesto, objetivo)
		VALUES ('$id','$fecha', '$datos1[rfc_empleado]' ,'$area','$puesto', '$objetivo')";
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){				
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			//Guardar el registro de movimientos
			registrarOperacion("bd_recursos",$datos1['rfc_empleado'],"GenerarNombramiento",$_SESSION['usr_reg']);
			//Guardar y Generar el PDF?>
			<script type='text/javascript' language='javascript'>
			setTimeout("window.open('../../includes/generadorPDF/nombramiento.php? id=<?php echo $id;  ?>','_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",1);</script><?php	
			echo "<meta http-equiv='refresh' content='2;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			//Cerrar la conexion con la BD		
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function guardarNombramiento()
?>