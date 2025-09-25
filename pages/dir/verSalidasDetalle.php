<?php
	/**
	  * Nombre del Módulo: Direccion General                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 02/Marzo/2011
	  * Descripción: En este archivo se muestra el Detalle de la Salidas segun los criterios consultados
	  **/ 
	
	//Módulo de conexión a la BD
	include ("../../includes/conexion.inc");
	//Operaciones con la Base de Datos
	include("../../includes/op_operacionesBD.php");
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		//-->
	</script>
	
	<style type="text/css">
		<!--
		#tabla-resultados{position:absolute; overflow:scroll; width:90%; height:410px;}
		#botones{position:absolute; left:30px;top:569px; width:90%;}
		-->
	</style>
	
</head>
<body>
	<?php		
	//Realizar la conexion a la BD de Almacen
	$conn = conecta('bd_almacen');

	//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
	$f1 = $_GET["fechaI"];
	$f2 = $_GET["fechaF"];
	$orden=$_GET["combo"];

	//Crear la sentencia para mostrar el Reporte de Orden de Compra dadas las fechas escritas
	$stm_sql = "SELECT id_salida,fecha_salida,solicitante,destino,depto_solicitante,turno,no_vale FROM salidas WHERE fecha_salida BETWEEN '$f1' AND '$f2'";
	
	//Si se selecciono un criterio de ordenación, adjuntarlo a la sentencia SQL
	if($orden!="NADA")
		$stm_sql.= " ORDER BY $orden,fecha_salida";
	else
		$stm_sql.= " ORDER BY fecha_salida";
	
	//Ejecutar la sentencia previamente creada
	$rs = mysql_query($stm_sql);
	$msg = "DETALLE DEL REPORTE GENERADO";

	if($row = mysql_fetch_array($rs)){
		echo "
		<table class='tabla_frm' cellpadding='5' width='100%'>
		<caption class='titulo_etiqueta' style='color:#FFFFFF'>$msg</caption>
		<tr>
			<td class='nombres_columnas'>PEDIDO</td>
			<td class='nombres_columnas'>FECHA PEDIDO</td>
			
			<td class='nombres_columnas'>CLAVE MATERIAL</td>
			<td class='nombres_columnas'>NOMBRE MATERIAL</td>
			<td class='nombres_columnas'>UNIDAD MEDIDA</td>
			<td class='nombres_columnas'>EXISTENCIA EN STOCK</td>
			
			<td class='nombres_columnas'>CLAVE ENTRADA</td>
			<td class='nombres_columnas'>FECHA ENTRADA</td>
			<td class='nombres_columnas'>CANTIDAD ENTRADA</td>
			
			<th class='nombres_columnas'>CLAVE SALIDA</th>
			<th class='nombres_columnas'>FECHA SALIDA</th>
			<th class='nombres_columnas'>DEPARTAMENTO SOLICITANTE</th>
			<th class='nombres_columnas'>SOLICITANTE</th>
			<th class='nombres_columnas'>DESTINO</th>
			<th class='nombres_columnas'>TURNO</th>
			<td class='nombres_columnas'>NO. VALE</td>
			
			<td class='nombres_columnas'>EQUIPO DESTINO</td>
			<td class='nombres_columnas'>CANTIDAD SALIDA</td>
			<td class='nombres_columnas'>COSTO UNITARIO</td>
			<td class='nombres_columnas'>COSTO TOTAL</td>
		</tr>
		";			
		//Mostramos los registros
		$nom_clase = "renglon_gris";
		$cont = 1;
		
		/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida*/
		$cant_total = 0;
		do{
			$id_salida=$row["id_salida"];
			$sql_detalle="SELECT materiales_id_material,nom_material,unidad_material,cant_salida,costo_unidad,costo_total,id_equipo_destino FROM detalle_salidas WHERE salidas_id_salida='$id_salida'";
			$rs_detalle=mysql_query($sql_detalle);
			$reg=mysql_num_rows($rs_detalle);
			if($detalle=mysql_fetch_array($rs_detalle)){
				$ctrl=0;
				do{
					$idEntrada=obtenerDatosEntrada($detalle["materiales_id_material"]);
					$fechaEntrada="<label title='No Aplica al No Haber una Entrada Relacionada'>NA</label>";
					$cantidadEntrada="<label title='No Aplica al No Haber una Entrada Relacionada'>NA</label>";
					$existencia=obtenerDato("bd_almacen", "materiales", "existencia","id_material",$detalle["materiales_id_material"]);
					$req="ND";
					$fechaPedido="ND";
					
					if($idEntrada!="ND"){
						$fechaEntrada=obtenerDato("bd_almacen", "entradas", "fecha_entrada","id_entrada",$idEntrada);
						$fechaEntrada=modFecha($fechaEntrada,1);
						$cantidadEntrada=obtenerDatoBicondicional("bd_almacen","detalle_entradas","cant_entrada","entradas_id_entrada",$idEntrada,"materiales_id_material",$detalle["materiales_id_material"]);
						//Obtener el ID de la Requisicion o del Pedido
						$req=obtenerDato("bd_almacen", "entradas", "requisiciones_id_requisicion","id_entrada",$idEntrada);
						if ($req!=""){
							if (substr($req,0,3)=="PED")
								$fechaPedido=obtenerDato("bd_compras", "pedido", "fecha","id_pedido",$req);
							else{
								$fechaPedido=obtenerDato("bd_compras", "pedido", "fecha","requisiciones_id_requisicion",$req);
								if ($fechaPedido!="")
									$fechaPedido=modFecha($fechaPedido,1);
								$req=obtenerDato("bd_compras", "pedido", "id_pedido","requisiciones_id_requisicion",$req);
							}
							if ($req=="")
								$req="ND";
							if ($fechaPedido=="")
								$fechaPedido="ND";
							//Reabrir la conexion a la BD de almacen
							$conn=conecta("bd_almacen");
						}
						else
							$req="ND";
					}
					echo "
						<tr>
						<td class='$nom_clase' title='Clave del Pedido'>$req</td>
						<td class='$nom_clase' title='Fecha del Pedido'>$fechaPedido</td>
						<td class='$nom_clase' title='Clave del Material'>$detalle[materiales_id_material]</td>
						<td class='$nom_clase' title='Nombre del Material'>$detalle[nom_material]</td>
						<td class='$nom_clase' title='Unidad de Medida del Material'>$detalle[unidad_material]</td>
						<td class='$nom_clase' title='Existencia en Stock del Material'>$existencia</td>
						<td class='$nom_clase' title='Clave de la &Uacute;ltima Entrada de $detalle[nom_material]'>$idEntrada</td>
						<td class='$nom_clase' title='Fecha de la &Uacute;ltima Entrada de $detalle[nom_material]'>$fechaEntrada</td>
						<td class='$nom_clase' title='Cantidad Entrada de $detalle[nom_material]'>$cantidadEntrada</td>";
					if ($ctrl==0){
						echo "
							<td class='$nom_clase' rowspan='$reg' title='N&uacute;mero de Salida'>$row[id_salida]</td>
							<td class='$nom_clase' rowspan='$reg' title='Fecha de Salida'>".modFecha($row['fecha_salida'],1)."</td>
							<td class='$nom_clase' rowspan='$reg' title='Departamento Solicitor'>$row[depto_solicitante]</td>
							<td class='$nom_clase' rowspan='$reg' title='Solicitante'>$row[solicitante]</td>
							<td class='$nom_clase' rowspan='$reg' title='Destino del Material'>$row[destino]</td>
							<td class='$nom_clase' rowspan='$reg' title='Turno en el que el Material Sali&oacute;'>$row[turno]</td>
							<td class='$nom_clase' rowspan='$reg' title='N&uacute;mero de Vale'>$row[no_vale]</td>";
					}
					echo "	
						<td class='$nom_clase' title='Equipo al Que Va Destinado el Material'>$detalle[id_equipo_destino]</td>
						<td class='$nom_clase' title='Cantidad de Salida del Material'>$detalle[cant_salida]</td>
						<td class='$nom_clase' title='Costo Unitario del Material'>$".number_format($detalle["costo_unidad"],2,".",",")."</td>
						<td class='$nom_clase' title='Costo Total del Material'>$".number_format($detalle["costo_total"],2,".",",")."</td>
						</tr>
					";
					$ctrl++;
					//Operación que mostrara el total del costo de los materiales			
					$cant_total += $detalle['costo_total'];
				}while($detalle=mysql_fetch_array($rs_detalle));
			}	
								
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";						
		}while($row = mysql_fetch_array($rs));

		//Colocar el Check para cuando se selecciona VER TODO
		echo "
			<tr>
				<td colspan='18'>&nbsp;</td>
				<td class='nombres_columnas'>TOTAL</td>
				<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>									
			</tr>";
		?>
		</table><?php
		$band=1;
	}
	else{
		echo "</br></br></br></br></br></br></br></br><p align='center' class='msje_correcto'>Del <strong><u>$fechaI</u></strong> al 
				<strong><u>$fechaC</u></strong> NO se registraron Salidas</p>";
	}
	
	function obtenerDatosEntrada($valor){
		$conn=conecta("bd_almacen");
		$dato="ND";
		$sql_stm="SELECT MAX(entradas_id_entrada) AS id_entrada FROM detalle_entradas WHERE materiales_id_material='$valor'";
		$rs=mysql_query($sql_stm);
		$datos=mysql_fetch_array($rs);
		if ($datos[0]!="")
			$dato=$datos[0];
		return $dato;
	}
	?>
</body>
</html>