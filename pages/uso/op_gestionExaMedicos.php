<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:02/Julio/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con los datos de los Examenes Médicos
	**/
	if(isset($_POST['cmb_examen']) || isset($_POST['ckb_nuevoExamen'])){
		registrarExamenesMedicos();	
	}
	
	//Funcion para guardar la informacion del Nuevo Examen
	function registrarExamenesMedicos(){
	
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");	
		
		//Conectar se a la Base de Datos de la Clinica
		$conn = conecta("bd_clinica");
		
		//OBtener las variables que vienen en el POST		
		$claveExamen = ($_POST['hdn_claveExamen']);
		$nomExamen = strtoupper($_POST['txt_nomExamen']);
		$tipoExamen = strtoupper($_POST['txt_tipoExamen']);
		$costoExamen = $_POST['txt_costoExamen'];
		$comentarios = strtoupper($_POST['txa_comentarios']);
		
		$claveExamenAnt = $_POST['hdn_claveExamen'];

		//Obtener el id del la Nuevo Examen
		$claveExamen=obtenerIdExamen(); 		

		//Variable que se utiliza para verificar que tipo de sentencia sql se esta ejecutando de acuerdo al boton que se le da un clic
		$concepto = "";		
	
		if(isset($_POST['ckb_nuevoExamen'])){
			//Crear la Sentencia SQL para Alamcenar los datos del Examen Medico
			echo $stm_sql= "INSERT INTO catalogo_examen (id_examen, tipo_examen, nom_examen, costo_exa, comentarios)
				VALUES('$claveExamen', '$tipoExamen', '$nomExamen', '$costoExamen', '$comentarios')";
			
			//Si viene definida la opcion ckb_nuevaEmpresa se ejecutara la sentencia anterior y la variable $concepto, tendra un valor de  RegistrarExamenMed
			$concepto = 'RegistrarExamenMed';
		}
		else if(isset($_POST['cmb_examen'])){
			$stm_sql = "UPDATE catalogo_examen SET id_examen = '$claveExamenAnt' , tipo_examen = '$tipoExamen', nom_examen = '$nomExamen',
						costo_exa = '$costoExamen', comentarios = '$comentarios' WHERE id_examen = '$claveExamenAnt' ";
			//Al contrario si la sentencia ejecutada es la de modificar la variable tomara el siguiente valor => $concepto = 'ModificarExamenMed'
			$concepto = 'ModificarExamenMed';
		}				
		//Ejecutar laS Sentencias previamente Creadas
		$rs=mysql_query($stm_sql);
		
			//Verificar Resultado 
			if ($rs){ 
				/*Guardar la operacion realizad0, ademas de acuerdo a la consulta ejecutada se guardara dentro de la
				 bitacora de movimientos el valor que contenga la variable $concepto */
				registrarOperacion("bd_clinica",$claveExamen,$concepto,$_SESSION['usr_reg']);	
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
			}
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
	 }// Fin function registrarEmpExternas()
	 
	 
	 /*Esta funcion genera el id del Examen de acuerdo a los registros en la BD*/
	function obtenerIdExamen(){
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		$id="";
		//Crear la sentencia para obtener la clave de la Empresa
		$stm_sql = "SELECT MAX(id_examen) AS cant FROM catalogo_examen";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
				$id .= $cant;
		}
		return $id;
	}//Fin de la function obtenerIdExamen()
		
?>	