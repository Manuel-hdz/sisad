<?php
	if (isset($_POST['btn_buscar'])){ 
		if((isset($_POST['hdn_numero']))){
			$hdn_bd="";
			$requisicion=htmlspecialchars($_POST['hdn_numero']);	
			echo $requisicion;	
			$rest = substr($requisicion, 0, 3); // separa"
			echo "clave: ".$rest;
			switch ($rest){			
			case "ALM":	
				$departamento="ALMACEN";
				$hdn_bd="bd_almacen";
				
				break;
			case "GER":
				$departamento="GERENCIA TECNICA";
				$hdn_bd="bd_gerencia";
				break;
			case "REC":
				$departamento="RECURSOS HUMANOS";
				$hdn_bd="bd_recursos";
				break;
			case "PRO":
				$departamento="PRODUCCION";
				$hdn_bd="bd_produccion";
				break;
			case "ASE":
				$departamento="ASEGURAMIENTO DE CALIDAD";
				$hdn_bd="bd_aseguramiento";
				break;
			case "DES":
				$departamento="DESARROLLO";
				$hdn_bd="bd_desarrollo";
				break;
			case "MAN":
				$departamento="MANTENIMIENTO";
				$hdn_bd="bd_mantenimiento";
				break;
			case "TOP":
				$departamento="TOPOGRAFIA";
				$hdn_bd="bd_topografia";
				break;
			case "LAB":
				$departamento="LAB";
				$hdn_bd="bd_laboratorio";
				break;
			case "SEG":
				$departamento="SEGURIDAD INDUSTRIAL";
				$hdn_bd="bd_seguridad";
				break;
			case "PAI":
				$departamento="PAILERIA";
				$hdn_bd="bd_paileria";
				break;
			case "MAE":
				$departamento="MANTENIMIENTO ELECTRICO";
				$hdn_bd="bd_mantenimientoE";
				break;
			case "USO":
				$departamento="UNIDAD DE SALUD OCUPACIONAL";
				$hdn_bd="bd_clinica";
				break;
				
				return $hdn_bd;
		}//Cierre switch ($REST])
			
			echo "base de datos: ".$hdn_bd;
			//CONEXION A BD CORRESPONDIENTE A CADA DEPARTAMENTO
			$conn = conecta($_POST["hdn_bd"]);
			 //Crear sentencia SQL
			$stm_sql = "SELECT cant_req,unidad_medida,descripcion,aplicacion,partida,precio_unit,id_control_costos,id_cuentas,id_subcuentas FROM detalle_requisicion WHERE requisiciones_id_requisicion='$requisicion' AND mat_pedido='1'";
				 
			
		}
	}
?>