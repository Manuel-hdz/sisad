<?php
/**
	  * Nombre del Módulo: Compras                                              
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 17/Febrero/2011
	  * Descripción: Este archivo contiene funciones para exportar datos de una tabla a un archivo CSV.
	  **/
	  
	  /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include_once("../../includes/conexion.inc");
	/**   Código en: pages\com\op_exportarCSV.php
      **/	
	if ($_POST["sbt_enviar"]){
		exportarSeleccionados();
	}	
	
	function exportarSeleccionados(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");	
		//Se inicializa la variable que almacenara cada linea en el Archivo CSV
		$csv="";
		foreach($_POST as $key => $value){
			if(substr($key,0,5)!="ckb_t" && $key != "sbt_enviar"){
				//Crear la sentencia para realizar el registro de los nuevos clientes en la BD de Compras en la tabla de Clientes
				$stm_sql = "SELECT * FROM clientes WHERE rfc='$value'";
				//Ejecutar la sentencia previamente creada
				$rs = mysql_query($stm_sql);
				//Confirmar que la inserción de datos fue realizada con exito.
				if($row=mysql_fetch_array($rs)){
					//Se declara la clave de cliente vacía al no ser tomado en cuenta por la BD de Compras
					$cve_cte="";
					//El tipo tambien se declara vacio ->Persona Moral, Persona Física
					$tipo="";
					//El tipo tambien se declara vacio ->NACIONAL, INTERNACIONAL
					$tipoC="";
					//Nacionalidad del Contacto
					$nacionalidad="";
					//Pais de Residencia del Contacto
					$pais="";
					//Clave del Vendedor
					$cve_vend="";
					//Vendedor
					$vendedor="";
					//Datos según el orden que necesita el sistema de Facturacion Electrónica empleado en la empresa
					$csv.=$cve_cte.",".$row["razon_social"].",".$tipo.",".$tipoC.",".$row["curp_contacto"].",".$row["nom_contacto"].",".$row["ap_contacto"].",".$row[
					"am_contacto"].",";
					$csv.=$nacionalidad.",".$pais.",".$row["id_fiscal"].",".$row["rfc"].",".$row["calle"].",".$row["numero_ext"].",".$row["numero_int"].",".$row["estado"].
					",";
					$csv.=$row["ciudad"].",".$row["municipio"].",".$row["colonia"].",".$row["cp"].",".$cve_vend.",".$vendedor."\n";
				}
			}
		}//cierre foreach
		$fecha=date("dmY");
		header("Content-Description: File Transfer");
		header("Content-Type: application/force-download");
		header("Content-Disposition: attachment; filename=clientes".$fecha.".csv");
		echo $csv;	
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
?>