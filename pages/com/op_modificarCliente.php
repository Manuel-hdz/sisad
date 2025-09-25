<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 17/Noviembre/2010                                      			
	  * Descripción: Este archivo contiene funciones para  modificar informacion  del formulario de  Cliente  en la BD
	  **/		 
 
	  
	//Esta funcion se encarga de guardar los cambios realizados al Provedor seleccionado por el usuario
	function guardarCambios($txt_rfc,$txt_idFiscal,$txt_razon,$txt_calle,$txt_numeroExt,$txt_numeroInt,$txt_colonia,$txt_ciudad,$txt_municipio,$txt_estado,
	$txt_telefono,$txt_telefono2,$txt_fax,$txt_correo,$txa_observaciones,$txt_curp,$txt_nomContacto,$txt_apPat,$txt_apMat,$txa_referencia,$txt_cp){	
		  
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");	
		
		//Transformar el texto de los campos obligatorios a mayusculas
		$txt_rfc = strtoupper($txt_rfc); 
		$txt_idFiscal = strtoupper($txt_idFiscal);		
		$txt_razon = strtoupper($txt_razon);  
		$txt_calle = strtoupper($txt_calle);
		$txt_numeroExt = strtoupper($txt_numeroExt); 
		$txt_numeroInt = strtoupper($txt_numeroInt);
		$txt_colonia = strtoupper($txt_colonia); 
		$txt_ciudad = strtoupper($txt_ciudad); 
		$txt_municipio = strtoupper($txt_municipio); 
		$txt_estado = strtoupper($txt_estado); 
		$txt_correo = strtolower($txt_correo);
		$txa_observaciones = strtoupper($txa_observaciones); 
		$txt_curp = strtoupper($txt_curp);
		$txt_nomContacto = strtoupper($txt_nomContacto);
		$txt_apPat = strtoupper($txt_apPat);
		$txt_apMat = strtoupper($txt_apMat);
		$txa_referencia = strtoupper($txa_referencia);
		
		//Crear la sentencia para modificar los Proveedores en la BD de comprasen la tabla de Proveedores
		$stm_sql = "UPDATE clientes SET	rfc='$txt_rfc',id_fiscal='$txt_idFiscal',razon_social='$txt_razon',calle='$txt_calle',numero_ext='$txt_numeroExt',
		numero_int='$txt_numeroInt',colonia='$txt_colonia', ciudad='$txt_ciudad', municipio='$txt_municipio',estado='$txt_estado',telefono='$txt_telefono',
		telefono2='$txt_telefono2',fax='$txt_fax',correo='$txt_correo',comentarios='$txa_observaciones',curp_contacto='$txt_curp',nom_contacto='$txt_nomContacto',
		ap_contacto='$txt_apPat',am_contacto='$txt_apMat',cp='$txt_cp',referencia='$txa_referencia' WHERE rfc='".$_SESSION['rfc']."'";
		
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);									
										
		//Confirmar que la insercion de datos fue realizada con exito.
		if($rs){
			registrarOperacion("bd_compras",$txt_rfc,"ModificarCliente",$_SESSION['usr_reg']);			
			//Quitar el rfc del Proveedor que se esta modificando de la SESSION
			unset($_SESSION['rfc']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";											
		}
		else{			
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error>";			
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);		
	}	
								
?> 
