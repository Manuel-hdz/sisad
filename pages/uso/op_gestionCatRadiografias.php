<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:03/Julio/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con los datos de las Radiografias que se realizan dentro de la clinica
	**/
	if(isset($_POST['cmb_proyeccion']) || isset($_POST['ckb_nuevaProyeccion'])){
		gestionarCatalogoRadiografias();	
	}
	
	//Funcion para guardar la informacion del Nuevo Examen
	function gestionarCatalogoRadiografias(){
	
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");	
		
		//Conectar se a la Base de Datos de la Clinica
		$conn = conecta("bd_clinica");
		
		//OBtener las variables que vienen en el POST		
		$claveProy = ($_POST['hdn_claveProyeccion']);
		$nomProy = strtoupper($_POST['txt_nomProyeccion']);
		$comentarios = strtoupper($_POST['txa_comentarios']);
		
		$claveProyAnt = $_POST['hdn_claveProyeccion'];

		//Obtener el id del la Nueva Radiografía
		$claveProy=obtenerIdProyeccion(); 		

		//Variable que se utiliza para verificar que tipo de sentencia sql se esta ejecutando de acuerdo al boton que se le da un clic
		$concepto = "";		
	
		if(isset($_POST['ckb_nuevaProyeccion'])){
			//Crear la Sentencia SQL para Alamcenar los datos de lad Radiografias
			echo $stm_sql= "INSERT INTO catalogo_radiografias (id_proyeccion, nom_proyeccion, comentarios)
				VALUES('$claveProy', '$nomProy', '$comentarios')";
			
			//Si viene definida la opcion ckb_nuevaProyecicon se ejecutara la sentencia anterior y la variable $concepto, tendra un valor de  RegistrarNvaRadiografia
			$concepto = 'RegistrarNvaRadiografia';
		}
		else if(isset($_POST['cmb_proyeccion'])){
			$stm_sql = "UPDATE catalogo_radiografias SET id_proyeccion = '$claveProyAnt', nom_proyeccion = '$nomProy',
						comentarios = '$comentarios' WHERE id_proyeccion = '$claveProyAnt' ";
			//Al contrario si la sentencia ejecutada es la de modificar la variable tomara el siguiente valor => $concepto = 'ModificarRadiografia'
			$concepto = 'ModificarRadiografia';
		}				
		//Ejecutar laS Sentencias previamente Creadas
		$rs=mysql_query($stm_sql);
		
			//Verificar Resultado 
			if ($rs){ 
				/*Guardar la operacion realizad0, ademas de acuerdo a la consulta ejecutada se guardara dentro de la
				 bitacora de movimientos el valor que contenga la variable $concepto */
				registrarOperacion("bd_clinica",$claveProy,$concepto,$_SESSION['usr_reg']);	
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
			}
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
	 }// Fin function registrarEmpExternas()
	 
	 
	 /*Esta funcion genera el id de la Radiografia de acuerdo a los registros en la BD*/
	function obtenerIdProyeccion(){
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		$id="";
		//Crear la sentencia para obtener la clave de la Radiografia o proyeccion
		$stm_sql = "SELECT MAX(id_proyeccion) AS cant FROM catalogo_radiografias";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
				$id .= $cant;
		}
		return $id;
	}//Fin de la function obtenerIdProyeccion()
		
?>	