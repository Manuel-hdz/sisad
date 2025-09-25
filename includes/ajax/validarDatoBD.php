<?php
	/**
	  * Nombre del Módulo: Almacén                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 23/Noviembre/2010                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda del dato indicado para saber si ya esta registrado o no
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos*/
			include("../conexion.inc");			
	/**   Código en: pages\alm\includes\validarDatoBD.php                                   
      **/	
	  		
	//Verificar la Opcion que se quiere realizar dependiendo los datos existentes
	if(isset($_GET["opcRealizar"]) && $_GET["opcRealizar"]=="validarVale"){//Verificar que un vale de Mtto. no sea registrado en el mismo equipo 2 o más veces
		$datoBusq = $_GET["datoBusq"];
		$idEquipo = $_GET["idEquipo"];	
		
		//Conectarse a la BD
		$conn = conecta("bd_mantenimiento");
		
		//Crear la Sentencia SQL
		$sql_stm = "SELECT bitacora_mtto_id_bitacora,id_vale,id_equipo FROM (materiales_mtto JOIN bitacora_mtto ON bitacora_mtto_id_bitacora=id_bitacora) JOIN equipos ON equipos_id_equipo=id_equipo
					WHERE id_vale = '$datoBusq' AND id_equipo = '$idEquipo'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<idVale>$datos[id_vale]</idVale>
					<idEquipo>$datos[id_equipo]</idEquipo>
					<idBitacora>$datos[bitacora_mtto_id_bitacora]</idBitacora>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
				
	}
	else if(isset($_GET["BD"])){//Validar una clave en la BD			 
		//Recuperar los datos a buscar de la URL
		$datoBusq = $_GET["datoBusq"];
		$BD = $_GET["BD"];
		$nomTabla = $_GET["nomTabla"];
		$campoClave = $_GET["campoClave"];
		$campoNombre = $_GET["campoNombre"];
		
		$pedido=0;
		$noPedido=0;
		//Verificar si se busca especificamente el Pedido
		if($BD=='bd_compras' && $nomTabla=='pedido' && $campoClave=='requisiciones_id_requisicion' && $campoNombre=='id_pedido'){
			$res=split("<->",verificarRequisicion($datoBusq));
			$noPedido=$res[0];
			$pedido=$res[1];
		}

		//Crear la Sentencia SQL
		$sql_stm = "SELECT $campoClave, $campoNombre FROM $nomTabla WHERE $campoClave='$datoBusq'";
		//Conectarse a la BD
		$conn = conecta("$BD");
		
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<clave>$datos[$campoClave]</clave>
					<nombre>$datos[$campoNombre]</nombre>
					<matNoPedido>$noPedido</matNoPedido>
					<matPedido>$pedido</matPedido>
				</existe>");
		}
		else{
			echo "<valor>false</valor>";
		}
		
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}//Cierre ELSE de if(isset($_GET['opcRealizar']) && $_GET['opcRealizar']=="validarVale")
	//Obtener la clave que sera asigana al Empleado de acuerdo al Area en la que será registrado
	else if(isset($_GET['opcRealizar']) && $_GET['opcRealizar']=="validarNorma"){
		//Recuperar los datos a buscar de la URL
		$norma = $_GET["datoBusq1"];
		$idMaterial = $_GET["datoBusq2"];				
		
		//Conectarse a la BD
		$conn = conecta("bd_laboratorio");
		//Crear la Sentencia SQL
		$sql_stm = "SELECT catalogo_materiales_id_material, norma FROM catalogo_normas WHERE norma = '$norma' AND catalogo_materiales_id_material='$idMaterial'";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs)){
			echo ("
				<existe>
					<valor>true</valor>																		
					<norma>$datos[norma]</norma>
					<idMaterial>$datos[catalogo_materiales_id_material]</idMaterial>
				</existe>");	
		}
		else
			echo "<valor>false</valor>";
		//Cerrar la conexion a la BD
		mysql_close($conn);
	}
	
	function verificarRequisicion($req){
		$req;
		switch(substr($req,0,3)){
			case "ALM":
				$base="bd_almacen";
			break;
			case "GER":
				$base="bd_gerencia";
			break;
			case "REC":
				$base="bd_recursos";
			break;
			case "PRO":
				$base="bd_produccion";
			break;
			case "ASE":
				$base="bd_aseguramiento";
			break;
			case "DES":
				$base="bd_desarrollo";
			break;
			case "MAN":
				$base="bd_mantenimiento";
			break;
			case "MAC":
				$base="bd_mantenimiento";
			break;
			case "MAM":
				$base="bd_mantenimiento";
			break;
			case "TOP":
				$base="bd_topografia";
			break;
			case "LAB":
				$base="bd_laboratorio";
			break;
			case "SEG":
				$base="bd_seguridad";
			break;
			case "PAI":
				$base="bd_paileria";
			break;
			default:
				$base="";
		}
		//Variables para contar Mat Pedidos y NO Pedidos
		$noPedido=0;
		$pedido=0;
		
		if($base!=""){
			//Conectar a la base elegida
			$conn=conecta($base);
			//Sentencia SQL para verificar a detalle los materiales de la requisicion
			$stm_sql = "SELECT COUNT(mat_pedido) FROM detalle_requisicion WHERE requisiciones_id_requisicion='$req' AND mat_pedido='1'";
			//Ejecutar la sentencia de verificacion de Materiales NO PEDIDOS
			$rs=mysql_query($stm_sql);
			//Extraer los datos para su manejo
			$res=mysql_fetch_array($rs);
			//Variable con la cantidad de Materiales NO Pedidos
			$noPedido=$res[0];
			//Sentencia SQL para verificar a detalle los materiales de la requisicion
			$stm_sql = "SELECT COUNT(mat_pedido) FROM detalle_requisicion WHERE requisiciones_id_requisicion='$req' AND mat_pedido='2'";
			//Ejecutar la sentencia de verificacion de Materiales NO PEDIDOS
			$rs=mysql_query($stm_sql);
			//Extraer los datos para su manejo
			$res=mysql_fetch_array($rs);
			//Variable con la cantidad de Materiales NO Pedidos
			$pedido=$res[0];
			//Nos aseguramos de cerrar la conexion
			mysql_close($conn);
		}
		//Retornar la cantidad de Materiales no pedidos
		return $noPedido."<->".$pedido;
	}
?>