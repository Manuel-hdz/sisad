<?php
/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Nadia Madahí López Hernández                            
	  * Fecha: 12/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para guardar en la BD la información acerca de los nuevos clientes que se agregen dentro del sistema.
	  **/
	  
	  /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			include("../../includes/func_fechas.php");
	/**   Código en: pages\com\op_agregarCliente.php                                   
      **/	
	
	if(isset($_POST['txt_rfc'])){
		//Transformar el texto de los campos obligatorios a mayusculas
		$txt_fecha=modFecha($txt_fecha,3);
		//Campos con el Check desactivado
		$txt_rfc = strtoupper($txt_rfc); 
		$txt_idFiscal = strtoupper($txt_idFiscal);
		$txt_correo = strtolower($txt_correo);
		$txt_curp = strtoupper($txt_curp);
		$txt_nomContacto = strtoupper($txt_nomContacto);
		$txt_apPat = strtoupper($txt_apPat);
		$txt_apMat = strtoupper($txt_apMat);
		$txa_referencia = strtoupper($txa_referencia);
		//Campos con el Check activado
		$txt_razon = strtoupper($txt_razon);  
		$txt_calle = strtoupper($txt_calle);
		$txt_numeroExt = strtoupper($txt_numeroExt); 
		$txt_numeroInt = strtoupper($txt_numeroInt);
		$txt_colonia = strtoupper($txt_colonia); 
		$txt_ciudad = strtoupper($txt_ciudad); 
		$txt_municipio = strtoupper($txt_municipio); 
		$txt_estado = strtoupper($txt_estado); 
		$txa_observaciones = strtoupper($txa_observaciones); 
		
		if(isset($_POST["ckb_factura"])){
			$txt_rfc=obtenerRFCFacturaNo();
			$txt_idFiscal = "N/A";
			$txt_curp = "N/A";
			$txt_nomContacto = "N/A";
			$txt_apPat = "N/A";
			$txt_apMat = "N/A";
			$txa_referencia = "N/A";
		}
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Crear la sentencia para realizar el registro de los nuevos clientes en la BD de Compras en la tabla de Clientes
		$stm_sql = "INSERT INTO clientes(rfc,id_fiscal,fecha_alta,razon_social,calle,numero_ext,numero_int,colonia,ciudad,municipio,estado,telefono,telefono2,fax,
		correo,comentarios,curp_contacto,nom_contacto,ap_contacto,am_contacto,cp,referencia)															
		VALUES('$txt_rfc','$txt_idFiscal','$txt_fecha','$txt_razon','$txt_calle','$txt_numeroExt','$txt_numeroInt','$txt_colonia','$txt_ciudad',														
		'$txt_municipio','$txt_estado','$txt_telefono','$txt_telefono2','$txt_fax','$txt_correo','$txa_observaciones','$txt_curp','$txt_nomContacto',
		'$txt_apPat','$txt_apMat','$txt_cp','$txa_referencia')";

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
	
		//Confirmar que la inserción de datos fue realizada con exito.
		if($rs){ 
			session_start();
			registrarOperacion("bd_compras",$txt_rfc,"AgregarCliente",$_SESSION['usr_reg']);
			header("Location:exito.php");
		}
		else{			
			echo $error = mysql_error();			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
		}
		//La Conexion a la BD se cierra en la funcion registrarOperacion("bd_compras",$txt_rfc,"AgregarCliente",$_SESSION['usr_reg']);
	}
	
	function obtenerRFCFacturaNo(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		//Definir las tres letras en la Id del Pedido
		$id_cadena = "RFCTEMP";
		//Crear la sentencia para obtener el Pedido Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(rfc) AS cant FROM clientes WHERE rfc LIKE 'RFCTEMP%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			if ($datos["cant"]!=NULL){
				//Obtener las ultimas 3 cifras del Pedido Registrado en la BD y sumarle 1
				$cant = substr($datos['cant'],-4)+1;
				if($cant>0 && $cant<10)
					$id_cadena .= "000".$cant;
				if($cant>=9 && $cant<100)
					$id_cadena .= "00".$cant;
				if($cant>=100 && $cant<1000)
					$id_cadena .= "0".$cant;
				if($cant>=1000)
					$id_cadena .= $cant;
			}
			else
				$id_cadena.= "0000";
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		//Retornar el Id de la Cadena
		return $id_cadena;
	}
?>