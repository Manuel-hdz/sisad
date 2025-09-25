<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:17/Marzo/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con los datos de las Empresas Externas
	**/
	if(isset($_POST['cmb_empresa']) || isset($_POST['ckb_nuevaEmpresa'])){
		registrarEmpExternas();	
	}

	//Funcion para guardar la informacion de la Nueva Empresa Externa
	function registrarEmpExternas(){
	
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");	
		
		//Conectar se a la Base de Datos de la Clinica
		$conn = conecta("bd_clinica");
		
		//OBtener las variables que vienen en el POST		
		$claveEmpresa = ($_POST['hdn_claveEmpresa']);
		$nomEmpresa = strtoupper($_POST['txt_nomEmpresa']);
		$razSocial = strtoupper($_POST['txt_razonSocial']);
		$tipoEmp = strtoupper($_POST['txt_tipoEmpresa']);
		$calle = strtoupper($_POST['txt_calle']);
		$colonia = strtoupper($_POST['txt_colonia']);
		$ciudad = strtoupper($_POST['txt_ciudad']);
		$estado = strtoupper($_POST['txt_estado']);
		$tel = $_POST['txt_tel'];
		$numExt = $_POST['txt_numExt'];
		$numInt = $_POST['txt_numInt'];
		$claveEmpresaAnt = $_POST['hdn_claveEmpresa'];
		$color = "#".$_POST["txt_color"];
		//Obtener el id del la Nueva Empresa
		$claveEmpresa=obtenerIdEmpresa();

		//Variable que se utiliza para verificar que tipo de sentencia sql se esta ejecutando de acuerdo al boton que se le da un clic
		$concepto = "";		
	
		if(isset($_POST['ckb_nuevaEmpresa'])){
			//Crear la Sentencia SQL para Alamcenar los datos de la Empresa Externa 
			$stm_sql= "INSERT INTO catalogo_empresas (id_empresa, nom_empresa, razon_social, tipo_empresa, calle, numero_ext, numero_int, colonia, ciudad,
			 estado, telefono, color)
			 VALUES('$claveEmpresa', '$nomEmpresa','$razSocial', '$tipoEmp', '$calle', '$numExt', '$numInt',  '$colonia', '$ciudad', '$estado', '$tel', '$color')";
			
			//Si viene definida la opcion ckb_nuevaEmpresa se ejecutara la sentencia anterior y la variable $concepto, tendra un valor de  RegistrarEmpExterna
			$concepto = 'RegistrarEmpExterna';
		}
		else if(isset($_POST['cmb_empresa'])){
			$stm_sql = "UPDATE catalogo_empresas SET id_empresa = '$claveEmpresaAnt' , nom_empresa = '$nomEmpresa', razon_social = '$razSocial',
						tipo_empresa = '$tipoEmp', calle = '$calle', numero_ext = '$numExt', numero_int = '$numInt', colonia = '$colonia', 
						ciudad = '$ciudad', estado = '$estado', telefono = '$tel', color='$color'
						WHERE id_empresa = '$claveEmpresaAnt' ";
			//Al contrario si la sentencia ejecutada es la de modificar la variable tomara el siguiente valor => $concepto = 'ModificarEmpExt'
			$concepto = 'ModificarEmpExt';
		}				
		//Ejecutar laS Sentencias previamente Creadas
		$rs=mysql_query($stm_sql);
		
			//Verificar Resultado 
			if ($rs){ 
				/*Guardar la operacion realizad0, ademas de acuerdo a la consulta ejecutada se guardara dentro de la
				 bitacora de movimientos el valor que contenga la variable $concepto */
				registrarOperacion("bd_clinica",$claveEmpresa,$concepto,$_SESSION['usr_reg']);	
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
			}
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
	 }// Fin function registrarEmpExternas()
	 
	 
	 /*Esta funcion genera el id de la Empresa de acuerdo a los registros en la BD*/
	function obtenerIdEmpresa(){
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		$id="";
		//Crear la sentencia para obtener la clave de la Empresa
		$stm_sql = "SELECT MAX(id_empresa) AS cant FROM catalogo_empresas";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
				$id .= $cant;
		}
		return $id;
	}//Fin de la function obtenerIdPresupuesto()
		
?>	