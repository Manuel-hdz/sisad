<?php
	/**
		* Nombre del Módulo: Unidad de Salud Ocupacional
		* Nombre Programador: Nadia Madahí López Hernández
		* Fecha:29/Junio/2012
		* Descripción: Este archivo contiene la funcion que permite Registrar ó Modificar la informacion de las Empresas Externas dentro de la tabla catalogo_empresas 
			en  la BD de la CLINICA
	**/	  
	  /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../../../includes/conexion.inc");
			//Incluimos archivo para modificar fechas
			include("../../../../includes/func_fechas.php");
	/**    
      **/	
	//Conectarse a la BD
	$conn = conecta("bd_clinica");
	
	//Comprobamos que exista la clave en el GET
	if($_GET['clave']){
		
		//Variable que nos permitira almacenar el tipo de Registro del Material que viene definido en el GET
		$clave =$_GET['clave'];
				
		//Creamos la sentencia SQL
		$stm_sql="SELECT * FROM catalogo_empresas WHERE id_empresa = '$clave'";
		
		//Ejecutamos la consulta
		$rs = mysql_query($stm_sql);
		
		//Guardamos el resultado de la consulta en un arreglo de datos
		if($datos=mysql_fetch_array($rs)){
			//Guardamos el valor buscado	
			$claveEmpresa = $datos['id_empresa'];
			$nomEmpresa = $datos['nom_empresa'];
			$razSocial = $datos['razon_social'];
			$tipoEmp = $datos['tipo_empresa'];
			$calle =$datos['calle'];
			$colonia = $datos['colonia'];
			$ciudad = $datos['ciudad'];
			$estado = $datos['estado'];
			$tel = $datos['telefono'];
			$numExt = $datos['numero_ext'];
			$numInt = $datos['numero_int'];
			$color = $datos['color'];
			$color = str_replace("#","",$color);
			
			if($datos['tipo_empresa']!="")
				$tipoEmp = $datos['tipo_empresa'];
			else
				$tipoEmp = "¬ND";
			
			if($datos['calle']!="")
				$calle = $datos['calle'];
			else
				$calle = "¬ND";
		
			if($datos['colonia']!="")
				$colonia = $datos['colonia'];
			else
				$colonia = "¬ND";
	
			if($datos['ciudad']!="")
				$ciudad = $datos['ciudad'];
			else
				$ciudad = "¬ND";
								
			if($datos['estado']!="")
				$estado = $datos['estado'];
			else
				$estado = "¬ND";
										
			if($datos['telefono']!="")
				$tel = $datos['telefono'];
			else
				$tel = "¬ND";
								
			if($datos['numero_ext']!="")
				$numExt = $datos['numero_ext'];
			else
				$numExt = "¬ND";
									
			if($datos['numero_int']!="")
				$numInt = $datos['numero_int'];
			else
				$numInt = "¬ND";

			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<claveEmp>$claveEmpresa</claveEmp>
					<nomEmp>$nomEmpresa</nomEmp>
					<razSocial>$razSocial</razSocial>
					<tipoEmp>$tipoEmp</tipoEmp>
					<calle>$calle</calle>
					<colonia>$colonia</colonia>
					<ciudad>$ciudad</ciudad>
					<estado>$estado</estado>
					<tel>$tel</tel>
					<numExt>$numExt</numExt>
					<numInt>$numInt</numInt>
					<color>$color</color>
				</existe>");
			//Cerrar la conexion a la BD
			mysql_close($conn);
		}
		else{
			header("Content-type: text/xml");	 
			//Crear XML de la clave Generada
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");
		}
	}
?>