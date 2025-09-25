<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 15/Abril/2011
	  * Descripción: Este archivo contiene funciones para Consultar un Empleado de la BD de Recursos y poder Registrar su Kardex correspondiente
	**/
	
	//Esta variable ayuda a determinar cuando se muestra el formulario
	$ctrlAparacionVentana = 1;
	//Si esta definido el boton sbt_modificar guardar los cambios en el Departamento seleccionado
	if(isset($_POST['sbt_modificar'])){
		guardarDepartamento();
		$ctrlAparacionVentana = 0;
	}
	//Si esta definido el boton sbt_eliminar eliminar el registro del departamento
	if(isset($_POST['sbt_eliminar'])){
		borrarDepartamento();
		$ctrlAparacionVentana = 0;
	}
	
	
	/*Esta funcion se encarga de guardar los datos del Departamento modificado o de un Nuevo Departamento*/
	function guardarDepartamento(){
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		
		//Recuperar los datos del Vector $_POST
		if(isset($_POST['cmb_departamento']))
			$rfcEncargadoActual = $_POST['cmb_departamento'];
		else if(isset($_POST['txt_departamento']))
			$departamento = strtoupper($_POST['txt_departamento']);
		$rfcEncargado = $_POST['txt_RFCEmpleado'];
		
		//Verificar si el registro sera agregado por primera vez o si es una actualizacion
		if($_POST['hdn_tipoSentencia']=="UPDATE")
			$sql_stm = "UPDATE organigrama SET empleados_rfc_empleado='$rfcEncargado' WHERE empleados_rfc_empleado = '$rfcEncargadoActual'";
		else if($_POST['hdn_tipoSentencia']=="INSERT")
			$sql_stm = "INSERT INTO organigrama (empleados_rfc_empleado,departamento) VALUES('$rfcEncargado','$departamento')";
		
		$rs = mysql_query($sql_stm);
		
		if($rs){
			if($_POST['hdn_tipoSentencia']=="UPDATE"){
				//Guardar el registro de movimientos
				registrarOperacion("bd_recursos",$rfcEncargadoActual,"ActualizacionDepartamento",$_SESSION['usr_reg']);
			}
			elseif($_POST['hdn_tipoSentencia']=="INSERT"){
				//Guardar el registro de movimientos
				registrarOperacion("bd_recursos",$rfcEncargado,"RegistroDepartamento",$_SESSION['usr_reg']);
			}
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	
	/*Esta funcion se encarga de eliminar los datos del Departamento Seleccionado*/
	function borrarDepartamento(){
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		
		//Obtener el RFC del Encargado del Departamento que se quiere borrar
		$rfcEncargadoActual = $_POST['cmb_departamento'];		
		
		//Crear la Sentencia SQL para borrar el departamento
		$sql_stm = "DELETE FROM organigrama WHERE empleados_rfc_empleado = '$rfcEncargadoActual'";
		
		$rs = mysql_query($sql_stm);
				
		if($rs)
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		else{
			echo $error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
	
	
	/*Esta funcion despliega los encargados de cada departamento*/
	function verDepartamentos(){
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		
		//Crear la Sentencia SQL para obtener los encargados por departamento
		$sql_stm = "SELECT departamento,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre FROM empleados JOIN organigrama ON rfc_empleado=empleados_rfc_empleado ORDER BY departamento";
			 	
		$rs = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs)){
			echo "
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>Departamentos Registrados y Encargados Asignados</caption>
				<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>DEPARTAMENTO</td>
					<td class='nombres_columnas' align='center'>ENCARGADO</td>		
				</tr>";		
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "
				<tr>
					<td class='$nom_clase' align='center'>$cont</td>
					<td class='$nom_clase'align='left'>$datos[departamento]</td>
					<td class='$nom_clase'align='left'>$datos[nombre]</td>										
				</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			echo "</table>";
		}				
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
			
	
?>