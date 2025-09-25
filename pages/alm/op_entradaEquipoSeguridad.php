<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                            
	  * Fecha: 12/Abril/2011                                      			
	  * Descripción: Este archivo contiene funciones para Entrada de  la información relacionada con el formulario de entrada equipo de seguridad
	  **/ 
	//Verificamos si viene definido sbt_registrar en el post 
	if(isset($_POST["sbt_registrar"])){
		guardarMateriales();
	}
	
	//Funcion que guarda los cambios en los registros seleccionados
	function guardarMateriales(){
		//Se incluye el archivo de conexión para manejo de bd
		include_once("../../includes/conexion.inc");		
		
		//Conectamos con la BD
		$conn = conecta("bd_almacen");
		//Variable bandera para la insercion de datos
		$flag=0;
		//Variable para almacenar el error en caso de generarse
		$error="";
		//Creamos la variable cantidad de la function mostrarEquiposHorometro() para saber el numero de registros
		$cantidad=$_POST["hdn_cant"]-1;
		$rfc=$_POST["cmb_nombre"];
		//Iniciamos la variable de control interna
		$ctrl=0;
		do{
			//Incrementamos el contador interno; para saber el numero de registro
			$ctrl++;
			//Verificamos que este definido el horometro inicial y agregamos variable ctrl para saber que registro sera insertado en la BD
			if(isset($_POST["ckb_equipo$ctrl"])){
				//Creamos variables para guardar lo que viene el el post
				if(isset($_POST["txt_observaciones$ctrl"]))
					$observaciones=strtoupper($_POST["txt_observaciones$ctrl"]);
				else
					$observaciones="";
				//Tomamos la fecha
				$fecha = date("Y-m-d");
				$ckb_material = $_POST["ckb_equipo$ctrl"];
				$estado=$_POST["cmb_estado$ctrl"];
				//Creamos la sentencia SQL
				$stm_sql="INSERT INTO devoluciones_es (materiales_id_material,empleados_rfc_empleado,fecha_entrada,estado, observaciones)
						VALUES('$ckb_material','$rfc','$fecha','$estado','$observaciones')";
				//Ejecutar la sentencia previamente creada
				$rs=mysql_query($stm_sql);
				//verificamos que la sentencia sea ejecutada con exito
				if (!$rs){
					$flag=1;
				}
			}
		//Mientras que control sea menor a la cantidad se registraran los datos en la BD	
		}while($ctrl<=$cantidad);
			
		//verificamos que la sentencia sea ejecutada con exito
		if ($flag==0){
			//Guardar el registro de movimientos
			registrarOperacion("bd_almacen",$ckb_material,"RegistrarDevolucionEquipoSeg",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Si los datos no se agregaron correctamente, se redirecciona a la pagina de error
			$error=mysql_error();
				echo "<meta http-equiv='refresh' content='7;url=error.php?err=$error'>";
		}	
		//Cerramos la conexion con la Base de Datos
		//mysql_close($conn);	
	
	}// Fin de la funcion 

?> 