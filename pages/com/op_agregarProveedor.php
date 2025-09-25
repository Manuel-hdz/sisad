<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 9/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de AgregarProveedor en la BD
	  **/

	//Funcion que agrega al Proveedor a la BD
	function agregarProveedor(){	 			
		//Convertir los caracteres de los campos de texto en Mayúsculas
		$txt_rfc = strtoupper($_POST["txt_rfc"]); 
		$txt_razonSoc = strtoupper($_POST["txt_razonSoc"]); 
		$txt_calle=strtoupper($_POST["txt_calle"]);
		$txt_numInt = strtoupper($_POST["txt_numInt"]); 
		$txt_numExt = strtoupper($_POST["txt_numExt"]); 
		$txt_col=strtoupper($_POST["txt_col"]);
		$txt_cp=$_POST["txt_cp"];
		$txt_ciudad = strtoupper($_POST["txt_ciudad"]); 
		$txt_estado = strtoupper($_POST["txt_estado"]); 
		$txt_tel=$_POST["txt_tel"];
		$txt_tel2=$_POST["txt_tel2"];
		$txt_fax=$_POST["txt_fax"];
		$cmb_relevancia=$_POST["cmb_relevancia"];
		$txt_correo=$_POST["txt_correo"];
		$txt_correo2=$_POST["txt_correo2"];
		$txt_contacto=strtoupper($_POST["txt_contacto"]);
		$txa_matServ = strtoupper($_POST["txa_matServ"]); 
		$txa_observaciones = strtoupper($_POST["txa_observaciones"]);
		//Obtener el ID del proveedor
		$idProv=calcularFolioProveedor();
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		//Crear la sentencia para realizar el registro del nuevo Proveedor en la BD de Compras en la tabla de Proveedores
		$stm_sql = "INSERT INTO proveedores   
		(rfc,razon_social,calle,numero_ext,numero_int,colonia,cp,ciudad,estado,telefono,telefono2,fax,relevancia,correo,correo2,contacto,mat_servicio,observaciones,id_prov)
		VALUES('$txt_rfc','$txt_razonSoc','$txt_calle','$txt_numExt','$txt_numInt','$txt_col','$txt_cp','$txt_ciudad','$txt_estado','$txt_tel','$txt_tel2','$txt_fax',
		'$cmb_relevancia','$txt_correo','$txt_correo2','$txt_contacto','$txa_matServ','$txa_observaciones','$idProv')";
		
		
		//Ejecutar la sentencia previamente creada		
		$rs = mysql_query($stm_sql);									
									
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			registrarOperacion("bd_compras",$txt_rfc,"AgregarProveedor",$_SESSION['usr_reg']);
			return $msg="¡El proveedor <u><em>".$txt_razonSoc."</u></em> ha sido agregado con &eacute;xito!";}
		else{
			//Redireccionar a una pagina de error en el caso de que no se haya guardado el Nuevo Material
			if(mysql_errno()=="1062")
				$error="La clave <em><u>$txt_rfc</u></em> ya esta asignada a otro Proveedor";
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
			
	}
	
	//Funcion que agregar documentos al Expediente de Proveedor
	function agregarDocumentos($rfc,$documentos){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		$band=0;
		//Registrar todos los materiales dados de alta en el arreglo $datosEntrada
		foreach ($_SESSION["documentos"] as $ind => $documento){		
			//Crear la sentencia para realizar el registro de los datos del detalle de la Entrada de Material
			$stm_sql = "INSERT INTO expediente_proveedor (proveedores_rfc,nombre_docto,estatus,ubicacion)
			VALUES('$rfc','$documento[nombre]','$documento[estatus]','$documento[ubicacion]')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_entradas
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
			//Romper el proceso de registro del detalle de la entrada en el caso de que existan errores	
			if($band==1)
				break;
			else
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
	
	//Funcion que calcula un ID para agregarselo al Proveedor
	function calcularFolioProveedor(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		//Definir las tres letras en el ID del proveedor
		$id_cadena = "PROV";
		//Crear la sentencia para obtener el Id mas reciente
		$stm_sql = "SELECT MAX(id_prov) AS cant FROM proveedores WHERE id_prov LIKE 'PROV%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 4 cifras del Proveedor Registrado en la BD y sumarle 1
			$cant = substr($datos['cant'],-4)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "00".$cant;
			if($cant>100 && $cant<1000)
				$id_cadena .= "0".$cant;
			if($cant>1000)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		//Retornar el ID del Proveedor
		return $id_cadena;
	}
?>
