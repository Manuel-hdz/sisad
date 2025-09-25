<?php
	/**
	  * Nombre del Módulo: Direccion General                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 09/Marzo/2012
	  * Descripción: En este archivo se muestra el Detalle de la Caja Chica segun los criterios consultados
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
	$conn = conecta('bd_compras');

	//Convertir Formato Fecha Sistema a formato Fecha Base de Datos para consultas
	$f1 = $_GET["fechaI"];
	$f2 = $_GET["fechaF"];
	$orden=$_GET["combo"];

	//Crear la sentencia para mostrar el Reporte de Orden de Compra dadas las fechas escritas
	$stm_sql = "SELECT fecha,responsable,descripcion,total_gastos,factura FROM detalle_caja_chica WHERE estado=1 AND fecha BETWEEN '$f1' AND '$f2' ORDER BY $orden";
	
	//Ejecutar la sentencia previamente creada
	$rs = mysql_query($stm_sql);
	$msg = "DETALLE DEL REPORTE GENERADO";

	if($row = mysql_fetch_array($rs)){
		echo "
		<table class='tabla_frm' cellpadding='5' width='100%'>
		<caption class='titulo_etiqueta' style='color:#FFFFFF'>$msg</caption>
		<tr>
			<td class='nombres_columnas' align='center'>FECHA</td>
			<td class='nombres_columnas' align='center'>RESPONSABLE</td>	
			<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
			<td class='nombres_columnas' align='center'>FACTURA</td>
			<td class='nombres_columnas' align='center'>GASTO</td>
		</tr>
		";			
		//Mostramos los registros
		$nom_clase = "renglon_gris";
		$cont = 1;
		/*Variable para realizar la operacion y obtener el total de costo de los materiales a los cuales se le registro una salida*/
		$cant_total = 0;
		$sumaFacturado=0;
		$sumaNoFacturado=0;
		do{
			echo "
				<tr>
				<td class='$nom_clase' title='Fecha del Retiro'>".modFecha($row["fecha"],1)."</td>
				<td class='$nom_clase' title='Responsable Solicitante del Retiro'>$row[responsable]</td>
				<td class='$nom_clase' title='Descripci&oacute;n del Retiro'>$row[descripcion]</td>
				<td class='$nom_clase' title='Factura'>$row[factura]</td>
				<td class='$nom_clase' title='Gasto Total del Retiro'>$".number_format($row["total_gastos"],2,".",",")."</td>
				";
				//Operación que mostrara el total del costo de los materiales			
				$cant_total += $row['total_gastos'];
				if($row["factura"]!="")
					$sumaFacturado+=$row["total_gastos"];
				else
					$sumaNoFacturado+=$row["total_gastos"];
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";						
		}while($row = mysql_fetch_array($rs));
		echo "
			<tr>
				<td colspan='3'>&nbsp;</td>
				<td class='nombres_columnas'>TOTAL NO FACTURADO</td>
				<td class='nombres_columnas'>$".number_format($sumaNoFacturado,2,".",",")."</td>									
			</tr>
			<tr>
				<td colspan='3'>&nbsp;</td>
				<td class='nombres_columnas'>TOTAL FACTURADO</td>
				<td class='nombres_columnas'>$".number_format($sumaFacturado,2,".",",")."</td>									
			</tr>
			<tr>
				<td colspan='3'>&nbsp;</td>
				<td class='nombres_columnas'>TOTAL</td>
				<td class='nombres_columnas'>$".number_format($cant_total,2,".",",")."</td>									
			</tr>
			</table>";
	}
	?>
</body>
</html>