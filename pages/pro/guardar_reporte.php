<?php 
	/**
	  * Nombre del Módulo: Producción                                               
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 22/Julio/2011                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas y reportes generados de de producción
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			include("../../includes/func_fechas.php");
			 
	  			
	if(isset($_POST['hdn_consulta']) || isset($_POST['hdn_origen'])){
	
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);	
		
		switch($hdn_origen){
			case "ReporteFechas":
				exportarReporteFechas();
			break;
			case "ReportePeriodo":
				exportarReportePeriodo();
			break;
		}										
	}
	
	if(isset($_GET["tipoRep"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);	
		
		switch($_GET["tipoRep"]){
			case "Cte":
				if($_GET["forma"]==1)
					exportarReporteCliente1($_GET["cte"],$_GET["f1"],$_GET["f2"]);
				if($_GET["forma"]==2)
					exportarReporteCliente2($_GET["cte"],$_GET["f1"],$_GET["f2"]);
			break;
		}
		
	}
	
	if(isset($_POST['sbt_excel'])){
		if(isset($_POST['hdn_consulta'])){
		
			//Ubicacion de las imagenes que estan contenidas en los encabezados
			define("HOST", $_SERVER['HTTP_HOST']);
			//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
			$raiz = explode("/",$_SERVER['PHP_SELF']);
			define("SISAD",$raiz[1]);
		
		
			switch($hdn_tipoReporte){
				case "reporte_requisiciones":
					guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);
				break;
				case "reporte_detallerequisiciones":
					guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg);
				break;
			}
		}
	}
	
	//Esta funcion exporta los datos del Reporte de Produccion por Fecha
	function exportarReporteCliente1($cliente,$fechaI,$fechaF){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteCliente.xls");				
				
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
				.renglon_volumen { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_firma { border-top:3px; border-top-color:#000000; border-top-style:solid;}
				-->
			</style>
		</head>											
		<body><?php
		
		//Realizar la conexion a la BD de Producción
		$conn = conecta("bd_produccion");?>
		
        <div id="tabla">				
        <table width="1000">					
            <tr>
                <td align="left" valign="baseline" colspan="3">
                    <img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
                </td>
                <td align="right" colspan="6">
                    <strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em></strong>
                </td>				
            </tr>	
			<tr>
				<td class="borde_linea" colspan="9">&nbsp;</td>
			</tr>
		<?php
		//Sentencia agrupando el volumen por Fecha, Colado y Observaciones
		$sql="	SELECT bitacora_produccion_fecha,volumen,colado,observaciones,factura,tipo_colado,no_remision,pagado,costo FROM detalle_colados 
				WHERE bitacora_produccion_fecha BETWEEN '$fechaI' AND '$fechaF' AND cliente='$cliente' ORDER BY bitacora_produccion_fecha,no_remision";
		
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			$fechaIniTitulo=modFecha($fechaI,2);
			$fechaFinTitulo=modFecha($fechaF,2);
			//Desplegar los resultados de la consulta en una tabla
			echo "								
				<tr>
					<td colspan='9' align='center' class='titulo_etiqueta'>Cliente: <em><u>$cliente</u></em> del <em><u>$fechaIniTitulo</u></em> al <em><u>$fechaFinTitulo</u></em></td>
				</tr>
				<tr>
					<td colspan='9' align='center' class='titulo_etiqueta'>&nbsp;</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>NO. REMISI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>TIPO COLADO</td>
					<td class='nombres_columnas' align='center'>NOMBRE COLADO</td>
					<td class='nombres_columnas' align='center'>VOLUMEN<br>M&sup3;</td>
					<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
					<td class='nombres_columnas' align='center'>FACTURADO</td>
					<td class='nombres_columnas' align='center'>PAGADO</td>
					<td class='nombres_columnas' align='center'>COSTO</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='$nom_clase' align='center'>".modFecha($datos['bitacora_produccion_fecha'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[no_remision]</td>
						<td class='$nom_clase' align='center'>$datos[tipo_colado]</td>
						<td class='$nom_clase' align='center'>$datos[colado]</td>
						<td class='$nom_clase' align='right'>".number_format($datos['volumen'],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[observaciones]</td>
						<td class='$nom_clase' align='center'>$datos[factura]</td>
						<td class='$nom_clase' align='center'>$datos[pagado]</td>
						<td class='$nom_clase' align='right'>$".number_format($datos['costo'],2,".",",")."</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table></div>";
			mysql_close($conn);
		}
	}
	
	//Esta funcion exporta los datos del Reporte de Produccion por Fecha
	function exportarReporteCliente2($cliente,$fechaI,$fechaF){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteCliente.xls");				
				
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
				.renglon_volumen { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_firma { border-top:3px; border-top-color:#000000; border-top-style:solid;}
				-->
			</style>
		</head>											
		<body><?php
		
		//Realizar la conexion a la BD de Producción
		$conn = conecta("bd_produccion");?>
		
        <div id="tabla">				
        <table width="1000">					
            <tr>
                <td align="left" valign="baseline" colspan="3">
                    <img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
                </td>
                <td align="right" colspan="2">
                    <strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em></strong>
                </td>				
            </tr>	
			<tr>
				<td class="borde_linea" colspan="5">&nbsp;</td>
			</tr>										
		<?php
		//Sentencia agrupando el volumen por Fecha, Colado y Observaciones
		$sql="	SELECT bitacora_produccion_fecha,SUM(volumen) AS volumen,SUM(costo) AS total,colado,observaciones FROM detalle_colados WHERE bitacora_produccion_fecha BETWEEN '$fechaI' AND '$fechaF' 
				AND cliente='$cliente' GROUP BY bitacora_produccion_fecha,colado,observaciones ORDER BY bitacora_produccion_fecha,colado";
		
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			$fechaIniTitulo=modFecha($fechaI,2);
			$fechaFinTitulo=modFecha($fechaF,2);
			//Desplegar los resultados de la consulta en una tabla
			echo "
				<tr>
					<td colspan='5' align='center' class='titulo_etiqueta'>Cliente: <em><u>$cliente</u></em> del <em><u>$fechaIniTitulo</u></em> al <em><u>$fechaFinTitulo</u></em></td>
				</tr>
				<tr><td colspan='5'>&nbsp;</td></tr>
				<tr>
					<td class='nombres_columnas' align='center' width='10%'>FECHA</td>
					<td class='nombres_columnas' align='center' width='20%'>NOMBRE COLADO</td>
					<td class='nombres_columnas' align='center' width='10%'>VOLUMEN M&sup3;</td>
					<td class='nombres_columnas' align='center' width='30%'>OBSERVACIONES</td>
					<td class='nombres_columnas' align='center' width='30%'>DETALLE DEL D&Iacute;A</td>
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$colado=$datos['colado'];
			$cantXColado=0;
			$costoXColado=0;
			$volTotal=0;
			$costoTotal=0;
			do{	
				if($colado!=$datos['colado']){
					echo "
						<tr>
							<td colspan='3'>&nbsp;</td>
							<td class='nombres_filas' align='Right'>VOLUMEN</td>
							<td class='nombres_columnas' align='center'>".number_format($cantXColado,2,".",",")." M&sup3;</td>
						</tr>
						<tr>
							<td colspan='3'>&nbsp;</td>
							<td class='nombres_filas' align='Right'>COSTO</td>
							<td class='nombres_columnas' align='center'>$".number_format($costoXColado,2,".",",")."</td>
						</tr>
					";
					$colado=$datos['colado'];
					$cantXColado=0;
					$costoXColado=0;
				}
				//Extraer informacion por Colado y Fecha
				$titulo=obtenerInformacionDia($datos['bitacora_produccion_fecha'],$datos['colado'],$cliente);
				//Mostrar los registros
				echo "
					<tr>
						<td class='$nom_clase' align='center'>".modFecha($datos['bitacora_produccion_fecha'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[colado]</td>
						<td class='$nom_clase' align='center'>".number_format($datos['volumen'],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[observaciones]</td>
						<td class='$nom_clase' align='left'>$titulo</td>
					</tr>";
				$cantXColado+=$datos["volumen"];
				$costoXColado+=$datos["total"];
				$volTotal+=$datos["volumen"];
				$costoTotal+=$datos["total"];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			$iva=$costoTotal*0.16;
			$total=$iva+$costoTotal;
			echo "
				<tr>
					<td colspan='3'>&nbsp;</td>
					<td class='nombres_filas' align='Right'>VOLUMEN</td>
					<td class='nombres_columnas' align='center'>".number_format($cantXColado,2,".",",")." M&sup3;</td>
				</tr>
				<tr>
					<td colspan='3'>&nbsp;</td>
					<td class='nombres_filas' align='Right'>COSTO</td>
					<td class='nombres_columnas' align='center'>$".number_format($costoXColado,2,".",",")."</td>
				</tr>
				
				<tr><td colspan='5'>&nbsp;</td></tr>
				
				<tr>
					<td colspan='3'>&nbsp;</td>
					<td class='nombres_columnas' align='center'>VOLUMEN TOTAL</td>
					<td class='nombres_columnas' align='center'>".number_format($volTotal,2,".",",")." M&sup3;</td>
				</tr>
				<tr>
					<td colspan='3'>&nbsp;</td>
					<td class='nombres_columnas' align='center'>SUBTOTAL</td>
					<td class='nombres_columnas' align='center'>$".number_format($costoTotal,2,".",",")."</td>
				</tr>
				<tr>
					<td colspan='3'>&nbsp;</td>
					<td class='nombres_columnas' align='center'>IVA (16%)</td>
					<td class='nombres_columnas' align='center'>$".number_format($iva,2,".",",")."</td>
				</tr>
				<tr>
					<td colspan='3'>&nbsp;</td>
					<td class='nombres_columnas' align='center'>TOTAL</td>
					<td class='nombres_columnas' align='center'>$".number_format($total,2,".",",")."</td>
				</tr>
			";
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table></div>";
			mysql_close($conn);
		}
	}

	function obtenerInformacionDia($fecha,$colado,$cliente){
		$titulo="";
		$sql="SELECT factura,tipo_colado,no_remision,pagado,costo,volumen FROM detalle_colados WHERE bitacora_produccion_fecha='$fecha' AND colado='$colado' AND cliente='$cliente'";
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			$cont=1;
			do{
				//Verificar si esta facturado
				if($datos["factura"]!="NO")
					$factura=$datos["factura"];
				else
					$factura="<u>NO FACTURADO</u>";
				//Obtener el tipo de colado
				$tipoColado=$datos["tipo_colado"];
				//Verificar si hay una remision asociada
				if($datos["no_remision"]!="")
					$remision=$datos["no_remision"];
				else
					$remision="<u>SIN REMISI&Oacute;N REGISTRADA</u>";
				//Verificar si esta pagado o no
				if($datos["pagado"]=="")
					$pago="<u>SIN PAGO REGISTRADO</u>";
				else
					$pago=$datos["pagado"];
				//Verificar si tiene costo asociado (Diferente de 0)
				if($datos["costo"]!=0){
					$costo="$".number_format($datos["costo"],2,".",",");
				}
				else
					$costo="<u>SIN COSTO REGISTRADO</u>";
				//Obtener el Volumen
				$volumen=$datos["volumen"];
				//Ensamblar el titulo
				$titulo.="<strong>Registro $cont:</strong><br>";
				$titulo.="-Remisión: $remision<br>";
				$titulo.="-Factura: $factura<br>";
				$titulo.="-Tipo Colado: $tipoColado<br>";
				$titulo.="-Volumen Colado: $volumen M&sup3;<br>";
				$titulo.="-Pagado: $pago<br>";
				$titulo.="-Costo: $costo<br>";
				$titulo.="<br>";
				$cont++;
			}while($datos=mysql_fetch_array($rs));
			$titulo=substr($titulo,0,(strlen($titulo)-4));
		}
		//Retornar el titulo tal cual se ensamblo o quedo
		return $titulo;
	}


	//Esta funcion exporta los datos del Reporte de Produccion por Fecha
	function exportarReporteFechas(){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteFechas.xls");				
				
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
				.renglon_volumen { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_firma { border-top:3px; border-top-color:#000000; border-top-style:solid;}
				-->
			</style>
		</head>											
		<body><?php
		
		//Declarar la variables que seran utilizadas en la exportacion
		$msgPro = "";
		$presupuestoDiario = 0;
		$volProDiario = 0;
		$presAcumulado = 0;
		$volumenRealTotal = 0;
		$difVolumen = 0;
		$difVolAcumulado = 0;
		$observaciones = "";
		
		$msgEq = "";
		$consultaEq = "";
		
		$msgSeg="";
		$consultaSeg="";
		$numAcc="";
		$numRegistros="";
		
		$msgCol="";
		$consultaCol="";
		
		//Recuperar la Fecha
		$fecha = $_POST['hdn_fecha'];
		
		
		//Recuperar toda la información que viene en el POST
		if(isset($_POST['hdn_msgPro'])){
			$msgPro = strtoupper($_POST['hdn_msgPro']);
			$presupuestoDiario = $_POST['hdn_presupuestoDiario'];
			$volProDiario = $_POST['hdn_volProDiario'];
			$presAcumulado = $_POST['hdn_presAcumulado'];
			$volumenRealTotal = $_POST['hdn_volumenRealTotal'];						
			$difVolumen = $volProDiario - $presupuestoDiario;	
			$difVolAcumulado = $volumenRealTotal - $presAcumulado;		
			$observaciones = $_POST['hdn_observaciones'];						
		}
		
		if(isset($_POST['hdn_msgEq'])){
			$msgEq = strtoupper($_POST['hdn_msgEq']);
		}	
		
		if(isset($_POST['hdn_msgSeg'])){
			$msgSeg = strtoupper($_POST['hdn_msgSeg']);
			$consultaSeg = $_POST['hdn_consultaSeg'];
			$numAcc = $_POST['hdn_numAcc'];
			$numRegistros = $_POST['hdn_numRegistros'];						
		}
		
		if(isset($_POST['hdn_msgCol'])){
			$msgCol = strtoupper($_POST['hdn_msgCol']);
			$consultaCol = $_POST['hdn_consultaCol'];
		}	
		
		//Realizar la conexion a la BD de Producción
		$conn = conecta("bd_produccion");?>
		
        <div id="tabla">				
        <table width="100%">					
            <tr>
                <td align="left" valign="baseline" colspan="4">
                    <img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
                </td>
                <td align="right" colspan="4">
                    <strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em></strong>
                </td>				
            </tr>	
			<tr>
				<td class="borde_linea" colspan="8">&nbsp;</td>
			</tr>										
        </table>
        </div><?php				
        
		/*****************************************************************/
		/**********************  DATOS PRODUCCIÓN  ***********************/
		/*****************************************************************/
		
  		//Verificar que la consulta tenga datos
		if($difVolumen!=0 && $difVolAcumulado!=0){?>			

            <div id="tabla">				
                <table width="100%">	
                    <tr>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="8" align="center" class="titulo_tabla"><?php echo $msgPro; ?></td>
                    </tr>
                    <tr>
                        <td class='nombres_columnas' align='center'>FECHA</td>
                        <td class='nombres_columnas' align='center'>VOL. PRESUPUESTADO</td>
                        <td class='nombres_columnas' align='center'>VOL. PRODUCIDO</td>
                        <td class='nombres_columnas' align='center'>DIFERENCIA</td>
                        <td class='nombres_columnas' align='center'>ACUMULADO PRESUPUESTADO</td>
                        <td class='nombres_columnas' align='center'>ACUMULADO REAL</td>
                        <td class='nombres_columnas' align='center'>DIFERENCIA</td>
                        <td class='nombres_columnas' align='center'>OBSERVACIONES</td>                        
                    </tr><?php
                $nom_clase = "renglon_gris";
                $cont = 1;
								
				//Revisar si la DIFERENCIA DEL DIA es positiva o negativa y determinar el color de los numeros a mostrar
				$estilo = "style='color:#0000CC; font-weight:bold;'";
				if($difVolumen<0)
					$estilo = "style='color:#FF0000; font-weight:bold;'";												
				//Revisar si la DIFERENCIA DEL PERIODO es positiva o negativa y determinar el color de los numeros a mostrar
				$estilo2 = "style='color:#0000CC; font-weight:bold;'";
				if($difVolAcumulado<0)
					$estilo2 = "style='color:#FF0000; font-weight:bold;'";
                
				//Mostrar todos los registros que han sido completados?>                                
					<tr>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $fecha; ?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($presupuestoDiario,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($volProDiario,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center' <?php echo $estilo; ?>><?php echo number_format($difVolumen,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($presAcumulado,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($volumenRealTotal,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center' <?php echo $estilo2; ?>><?php echo number_format($difVolAcumulado,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo $observaciones;?></td>
					</tr>
                
                </table>
				<br><br><?php
		}//fin if($difVolumen!=0 && $difVolAcumulado!=0)
		
				
		/*****************************************************************/
		/************************  EQUIPOS   *****************************/
		/*****************************************************************/				
		//Colocar el Titulo de la Tabla
		if(isset($_POST['hdn_msgEq'])){?>			
            <table cellpadding="5" width="100%">
            <tr>
                <td colspan="8" align="center" class="titulo_tabla"><?php echo $msgEq; ?></td>
            </tr><?php
			
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			$rs_equipos = mysql_query("SELECT DISTINCT nom_equipo FROM equipos WHERE bitacora_produccion_fecha='$fecha'");
				
			if($datos=mysql_fetch_array($rs_equipos)){
							
				//Colocar los Encabezados para las columnas?>
				<tr>
					<td class='nombres_columnas' align='center' width='20%' colspan="2">EQUIPO</td>
					<td class='nombres_columnas' align='center' width='10%' colspan="2">M&sup3;</td>
					<td class='nombres_columnas' align='center' colspan="4">OBSERVACIONES</td>
				</tr><?php

				do{	
					//Obtener los datos acumulados por cada Equipo, sumar el volumen y concatenar las observaciones
					$volAcumulado = 0;
					$observaciones = "";
					$rs_equipo = mysql_query("SELECT vol_producido,observaciones FROM equipos WHERE bitacora_produccion_fecha='$fecha' AND nom_equipo = '$datos[nom_equipo]'");
					if($datos_equipo=mysql_fetch_array($rs_equipo)){
						do{
							$volAcumulado += $datos_equipo['vol_producido'];
							$observaciones .= $datos_equipo['observaciones'].", ";	
						}while($datos_equipo=mysql_fetch_array($rs_equipo));
					}//Cierre if($datos_equipo=mysql_fetch_array($rs_equipo))
					
					//Retirar la ultima como y espacio en blanco de la cadena
					$observaciones = substr($observaciones,0,(strlen($observaciones)-2));?>			
					<tr>                        
						<td class='<?php echo $nom_clase?>' align="center" colspan="2"><?php echo $datos['nom_equipo']; ?></td>
						<td class='<?php echo $nom_clase?>' align="center" colspan="2"><?php echo number_format($volAcumulado,2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align="left" colspan="4"><?php echo $observaciones;?></td>
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
					
				}while($datos=mysql_fetch_array($rs_equipos));
			}//FIN if($datos=mysql_fetch_array($rs_equipos)){?>
			</table>
			<br><br><?php			
		}//FIN if(isset($_POST['hdn_msgEq'])) (EQUIPOS)        
        
		
		/*****************************************************************/
		/************************  COLADOS   *****************************/
		/*****************************************************************/				
		//Colocar el Titulo de la Tabla
		if(isset($_POST['hdn_msgCol'])){?>           				
            <table cellpadding="5" width="100%">				
            <tr>
                <td colspan="8" align="center" class="titulo_tabla"><?php echo $msgCol; ?></td>
            </tr><?php
			
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			$rs_colados = mysql_query($consultaCol);
				
			if($datos=mysql_fetch_array($rs_colados)){
							
				//Colocar los Encabezados para las columnas?>
				<tr>
					<td class='nombres_columnas' align='center' width='30%' colspan="2">CLIENTE</td>
					<td class='nombres_columnas' align='center' width='10%'>M&sup3;</td>
					<td class='nombres_columnas' align='center' width='20%'>COLADO</td>
					<td class='nombres_columnas' align='center' colspan="4">OBSERVACIONES</td>
				</tr><?php

				do{	?>			
					<tr>                        
						<td class='<?php echo $nom_clase?>' align="center" colspan="2"><?php echo $datos['cliente']; ?></td>
						<td class='<?php echo $nom_clase?>' align='center'><?php echo number_format($datos['volumen'],2,".",",");?></td>
						<td class='<?php echo $nom_clase?>' align="center"><?php echo $datos['colado']; ?></td>
						<td class='<?php echo $nom_clase?>' align="center" colspan="4"><?php echo $datos['observaciones']; ?></td>
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
					
				}while($datos=mysql_fetch_array($rs_colados));
			}//FIN if($datos=mysql_fetch_array($rs_colados)){?>
			</table>
			<br><br><?php
		}//FIN if(isset($_POST['hdn_msgCol'])) (COLADOS)
        

		/*****************************************************************/
		/************************  SEGURIDAD   ***************************/
		/*****************************************************************/				
		//Colocar el Titulo de la Tabla
		if(isset($_POST['hdn_msgSeg'])){?>           				
            <table cellpadding="5" width="100%">				
            <tr>
                <td colspan="8" align="center" class="titulo_tabla"><?php echo $msgSeg; ?></td>
            </tr><?php
			
			$numAcc;
			$numRegistros;
			$nunInc=($numRegistros-$numAcc);
			
			$rs_seguridad = mysql_query($consultaSeg);
				
			if($datos=mysql_fetch_array($rs_seguridad)){
							
				//Colocar los Encabezados para las columnas?>
				<tr>
					<td class='nombres_columnas' align='center' width='1%' colspan="2">INCIDENTES</td>
					<td class='nombres_columnas' align='center' width='1%' colspan="3">ACCIDENTES</td>
					<td class='nombres_columnas' align='center' width='1%' colspan="3">TOTAL MES</td>
                    <td width="70%"></td>
				</tr>
                <tr>
					<td class='<?php echo $nom_clase?>' align='center' colspan="2"><?php echo $nunInc;?></td>
					<td class='<?php echo $nom_clase?>' align='center' colspan="3"><?php echo $numAcc;?></td>
					<td class='<?php echo $nom_clase?>' align='center' colspan="3"><?php echo $numRegistros;?></td>
				</tr>
           	</table>
           	<table cellpadding="5" width="100%">   
				<tr>
					<td class='nombres_columnas' align='center' width='20%' colspan="2">TIPO</td>
					<td class='nombres_columnas' align='center' colspan="6">OBSERVACIONES</td>
				</tr><?php

				do{	?>			
					<tr>                        
						<td class='<?php echo $nom_clase?>' align="center" colspan="2"><?php echo $datos['tipo']; ?></td>
						<td class='<?php echo $nom_clase?>' align="center" colspan="6"><?php echo $datos['observaciones']; ?></td>
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
					
				}while($datos=mysql_fetch_array($rs_seguridad));
			}//FIN if($datos=mysql_fetch_array($rs_colados)){?>
			</table><?php
		}//FIN if(isset($_POST['hdn_msgCol'])) (COLADOS)?>        		
		  
		</body><?php 		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la Funcion consultaReporteFechas()
	
	
	
	/*Esta funcion se encarga de exportar los datos del Reporte por Periodo a una Hoja de Calculo */
	function exportarReportePeriodo(){			
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteFechas.xls");				
				
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
				.renglon_volumen { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; } 				
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_firma { border-top:3px; border-top-color:#000000; border-top-style:solid;}
				-->
			</style>
		</head>											
		<body><?php
		
		
			//Conectarse a la Base de Datos de Produccion
			$conn = conecta("bd_produccion");
			
			//Recuperar datos del POST
			$periodo = $_POST['hdn_periodo'];		
			
			//Verificar si viene el numero de empleados en el post para agregar la Seccion de Productividad al reporte que será exportado
			$noEmpleados = "";
			if(isset($_POST['hdn_numEmpleados']))
				$noEmpleados = $_POST['hdn_numEmpleados'];
			
			//Crear y ejecutar la Sentencia SQL para obtener las fechas del Periodo Seleccionado
			$fechas = mysql_fetch_array(mysql_query("SELECT fecha_inicio, fecha_fin FROM presupuesto WHERE periodo = '$periodo'"));
					
			//Obtener el año de inicio y el año de fin de las fechas que componen el periodo
			$anioInicio = substr($fechas['fecha_inicio'],0,4);
			$anioFin = substr($fechas['fecha_fin'],0,4);
			
			//Seperar el valor del Periodo para obtener los meses, aqui se considera que los periodos son siempre de dos meses consecutivos
			$nomMesInicio = obtenerNombreCompletoMes(substr($periodo,5,3));
			$nomMesFin = obtenerNombreCompletoMes(substr($periodo,9,3));
			
			//Obtener los dias del mes de Inicio del periodo
			$diasMesInicio = diasMes(obtenerNumMes($nomMesInicio), $anioInicio);
							
			
			//Obtener el ancho en dias de los meses que componen el periodo
			$anchoDiasInicio = $diasMesInicio - intval(substr($fechas['fecha_inicio'],-2)) + 1;
			$anchoDiasFin = intval(substr($fechas['fecha_fin'],-2));
			$totalDias = $anchoDiasInicio + $anchoDiasFin;
			
			//Arreglos para almacenar los totales
			$sumPorDia = array();//Arreglo que contendra la suma de todas las ubicaciones por día
			$prodRealPorDia = array();//Arreglo que contendra la produccion real por dia acumulada
			$prodPresPorDia = array();//Arreglo que contendra la produccion presupuestada por dia acumulada
																			
			//Crear la Sentencia para obtener las ubicaciones que tienen registros en el periodo seleccionado
			$sql_stm_ubicaciones = "SELECT DISTINCT id_destino, destino FROM catalogo_destino JOIN datos_bitacora ON id_destino=catalogo_destino_id_destino 
									WHERE bitacora_produccion_fecha>='$fechas[fecha_inicio]' && bitacora_produccion_fecha<='$fechas[fecha_fin]'";
			//Ejecutar la Senetencia para obtener las ubicaciones
			$rs_ubicaciones = mysql_query($sql_stm_ubicaciones);
			
			//Sibujar los registros de cada una de las ubicaciones encontradas, de a renglon por ubicación		
			if($ubicaciones=mysql_fetch_array($rs_ubicaciones)){			
								
				
				/***********************DIBUJAR EL ENCABEZADO DE LA TABLA**********************/?>
				<table border="0" cellpadding="5">					
					<tr>
						<td align="left" valign="baseline" colspan="3">
							<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
						</td>
						<td align="right" colspan="<?php echo ($totalDias - 2) + 2;?>">
							<strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em></strong>
						</td>				
					</tr>	
					<tr>
						<td class="borde_linea" colspan="<?php echo $totalDias + 3;?>">&nbsp;</td>
					</tr>										
				</table>
				<br><br><br>
				<table border="0" cellpadding="5">
				<caption class="titulo_tabla"><strong>Reporte de Producci&oacute;n en el Periodo <em><u><?php echo $periodo;?></u></em></strong></caption>
				<tr>
					<td rowspan="2" class="nombres_columnas">CONCEPTO</td>
					<td colspan="<?php echo $anchoDiasInicio; ?>" class="nombres_columnas" align="center"><?php echo $nomMesInicio." ".$anioInicio; ?></td>	
					<td colspan="<?php echo $anchoDiasFin; ?>" class="nombres_columnas" align="center"><?php echo $nomMesFin." ".$anioFin; ?></td>	
					<td rowspan="2" class="nombres_columnas" align="center">TOTAL MES</td>
					<td rowspan="2" class="nombres_columnas" align="center">PROMEDIO</td>
				</tr>
				<tr><?php
				//Ciclo para Colocar los dias en el encabezado
				$diaActual = substr($fechas['fecha_inicio'],-2);
				for($i=0;$i<$totalDias;$i++){
					//Si el dia es menor a 10 colocar un cero a la izquierda
					if($diaActual<10){?>
						<td class="nombres_columnas" align="center">0<?php echo $diaActual; ?></td><?php
					}else{?>			
						<td class="nombres_columnas" align="center"><?php echo $diaActual; ?></td><?php
					}
					
					//Inicializar cada posición del arreglo que contandrá la suma por día de todas las ubicaciones
					$sumPorDia[$diaActual] = 0; 
						
					if($diaActual==$diasMesInicio)
						$diaActual = 0;
					
					//Incrementar el dia
					$diaActual++;
				}//Cierre for($i=0;$i<$totalDias;$i++)?>
				</tr><?php
			
			
			
				/***************COLOCAR POR RENGLON EL DETALLE DE CADA UBICACIÓN*****************/
				//Manipular el color de los renglones de cada ubicación
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{?>
					<tr>
						<td class="nombres_filas"><strong><?php echo $ubicaciones['destino'];?></strong></td><?php
						//Obtener el dia, mes y año de inicio como actuales
						$diaActual = substr($fechas['fecha_inicio'],-2);
						$mesActual = substr($fechas['fecha_inicio'],5,2);
						$anioActual = $anioInicio;
						
						//Variables para calcular el total y el promedio de cada Concepto
						$sumTotal = 0;
						$sumPromedio = 0;
						$contRegs = 0;
						
						//Ciclo para colocar el valor de cada dia, en el caso de que no haya registro en el dia, se dejara vacio
						for($i=0;$i<$totalDias;$i++){
							//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para hacer la consulta en la BD
							$fechaActual = $anioActual;
							if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
							if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
							
							//Comprobar si la Fecha Actual es Domingo
							$colorFondo = "";
							if(obtenerNombreDia($fechaActual)=="Domingo")
								$colorFondo = "#FFFF00";
													
							//Ejecutar Sentencia SQL para obtener el Volumen del Dia y la Ubicacion Actuales
							$rs_volumen = mysql_query("SELECT SUM(vol_producido) AS vol_producido FROM datos_bitacora 
														WHERE bitacora_produccion_fecha = '$fechaActual' AND catalogo_destino_id_destino = $ubicaciones[id_destino]");
							$volumen = mysql_fetch_array($rs_volumen);
							//Si existe Volumen, imprimirlo
							if($volumen['vol_producido']!=""){?>
								<td align="center" <?php 
									if($colorFondo==""){//Si el dia no es Domingo colocar la clase del Renglon Blanco o Gris segun aplique?> 
										class="<?php echo $nom_clase; ?>"<?php 								
										$sumPromedio += $volumen['vol_producido'];//Obtener la suma total para obtener el Promedio descartando los Domingos
										$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
									} 
									else {//Colocar fondo amarillo cuando sea domingo?> 
										bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000;"<?php 
									}?>
								><?php 
									//Imprimir el volumen de la fecha actual								
									echo $volumen['vol_producido']; ?>
								</td><?php
								
								//Obtener la suma total de la ubicacion que esta siendo impresa
								$sumTotal += $volumen['vol_producido'];
								
								//Sumar los volumenes encontrados por dia
								$sumPorDia[$diaActual] += $volumen['vol_producido']; 
								
							}
							else{//Si no existe volumen colocar la celda vacia?>
								<td align="center" <?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php
							}
							
							//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
							if($diaActual==$diasMesInicio){
								$diaActual = 0;
								$mesActual++;
								
								//Verificar el cambio de año
								if($mesActual==13){
									$mesActual = 1;
									$anioActual++;
								}
							}
							
							//Incrementar el dia
							$diaActual++;
						}//Cierre for($i=0;$i<$totalDias;$i++)?>
						<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($sumTotal,2,".",","); ?></strong></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format(floatval($sumPromedio/$contRegs),2,".",","); ?></strong></td>
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($ubicaciones=mysql_fetch_array($rs_ubicaciones));
				
				
				
				
				/*****************COLOCAR EL TOTAL DE CADA DIA DEL PERIODO*******************/?>
				<tr>
					<td class="nombres_filas"><strong>TOTAL D&Iacute;A</strong></td><?php
					//Obtener el dia, mes y año de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					$mesActual = substr($fechas['fecha_inicio'],5,2);
					$anioActual = $anioInicio;
					
					//Variables para calcular el total y el promedio de cada Concepto
					$sumTotal = 0;
					$sumPromedio = 0;
					$contRegs = 0;
					for($i=0;$i<$totalDias;$i++){
						//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para saber si es domingo o no
						$fechaActual = $anioActual;
						if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
						if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
						
						//Comprobar si la Fecha Actual es Domingo
						$colorFondo = "";
						if(obtenerNombreDia($fechaActual)=="Domingo")
							$colorFondo = "#FFFF00";
							
						//Colocar la suma del dia Actual en el caso que exista
						if($sumPorDia[$diaActual]!=0){?>
							<td align="center" style="font-weight:bold; color:#FF0000;" <?php
								if($colorFondo==""){?> 
									class="<?php echo $nom_clase; ?>"<?php 
									$sumPromedio += $sumPorDia[$diaActual];//Obtener la suma total para obtener el Promedio descartando los Domingos
									$contRegs++;//Saber cuantos registros son sin contar los domingos para obtener el promedio
								} 
								else {?> 
									bgcolor="#FFFF00" style="font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000;" <?php 
								}?>
							><?php 
								echo round($sumPorDia[$diaActual],1); ?>
							</td><?php
							
							//Obtener la suma total de la ubicacion que esta siendo impresa
							$sumTotal += $sumPorDia[$diaActual];
						}
						else{//Si no existe suma del dia colocar un espacio vacio?>
							<td align="center" <?php if($colorFondo==""){?> class="<?php echo $nom_clase; ?>"<?php } else {?> bgcolor="#FFFF00" <?php }?>>&nbsp;</td><?php					
						}
																
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
						if($diaActual==$diasMesInicio){
							$diaActual = 0;				
							$mesActual++;
							
							//Verificar el cambio de año
							if($mesActual==13){
								$mesActual = 1;
								$anioActual++;
							}
						}	
						
						//Incrementar el dia
						$diaActual++;
					}//Cierre for($i=0;$i<$totalDias;$i++)?>
					<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format($sumTotal,2,".",","); ?></strong></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo number_format(floatval($sumPromedio/$contRegs),2,".",","); ?></strong></td>
				</tr><?php	
				
				
				
				/****************COLOCAR LA PRODUCCION REAL DEL PERIODO POR DIA*******************/
				//Hacer cambio del color de renglon
				if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";?>
				<tr>
					<td class="nombres_filas"><strong>REAL</strong></td><?php
					//Obtener el dia y mes de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					$valDiaAnterior = 0;
					for($i=0;$i<$totalDias;$i++){
						if($i==0){?>
							<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($sumPorDia[$diaActual],1); ?></strong></td><?php
							$valDiaAnterior = $sumPorDia[$diaActual];
							//Almacenar la Produccion Real Diaria Acumulada
							$prodRealPorDia[$diaActual] = $sumPorDia[$diaActual];
						}
						else{?>
							<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($sumPorDia[$diaActual]+$valDiaAnterior,1); ?></strong></td><?php
							//Almacenar la Produccion Real Diaria Acumulada
							$prodRealPorDia[$diaActual] = $sumPorDia[$diaActual] + $valDiaAnterior;
							
							//La suma del dia actual se convierte en el valor del dia anterior
							$valDiaAnterior = $sumPorDia[$diaActual] + $valDiaAnterior;						
						}
									
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
						if($diaActual==$diasMesInicio)
							$diaActual = 0;															
						
						//Incrementar el dia
						$diaActual++;
					}//Cierre for($i=0;$i<$totalDias;$i++)?>				
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr><?php
				
				
				
				/****************COLOCAR LA PRODUCCION PRESUPUESTADA DEL PERIODO*******************/
				//Hacer cambio del color de renglon			
				if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";
				//Obtener el Presupusto diario del periodo
				$presupuesto = obtenerDato("bd_produccion", "presupuesto", "vol_ppto_dia", "periodo", $periodo);?>			
				<tr>
					<td class="nombres_filas"><strong>PRESUPUESTO</strong></td><?php
					//Obtener el dia, mes y año de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					$mesActual = substr($fechas['fecha_inicio'],5,2);
					$anioActual = $anioInicio;
					
					//Variable para acumular el presupuesto dia a dia
					$presAnterior = 0;
					for($i=0;$i<$totalDias;$i++){
						//Armar la Fecha del Dia Actual en formato aaaa-mm-dd para saber si es domingo o no
						$fechaActual = $anioActual;
						if($mesActual<10) $fechaActual .= "-0".$mesActual; else $fechaActual .= "-".$mesActual;
						if($diaActual<10) $fechaActual .= "-0".$diaActual; else $fechaActual .= "-".$diaActual;
						
						//Comprobar si la Fecha Actual es Domingo
						$diaDomingo = false;
						if(obtenerNombreDia($fechaActual)=="Domingo")
							$diaDomingo = true;
					
						//Colocar el presupuesto en el dia inicial
						if($i==0){?>
							<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($presupuesto,1); ?></strong></td><?php
							$presAnterior = $presupuesto;
							//Almacenar la produccion presupuestada por dia acumulada
							$prodPresPorDia[$diaActual] = $presupuesto;
						}
						else{
							//Verificar si la fecha actual es domingo y colocar directamente el presupuesto anterior
							if($diaDomingo){?>
								<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($presAnterior,1); ?></strong></td><?php
								//Almacenar la produccion presupuestada por dia acumulada
								$prodPresPorDia[$diaActual] = $presAnterior;
							}
							else{?>						
								<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($presupuesto+$presAnterior,1); ?></strong></td><?php
								//Almacenar la produccion presupuestada por dia acumulada
								$prodPresPorDia[$diaActual] = $presupuesto + $presAnterior;
								
								//La suma del dia actual se convierte en el valor del dia anterior
								$presAnterior = $presupuesto + $presAnterior;
								
							}
						}
									
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
						if($diaActual==$diasMesInicio){
							$diaActual = 0;				
							$mesActual++;
							
							//Verificar el cambio de año
							if($mesActual==13){
								$mesActual = 1;
								$anioActual++;
							}
						}
						
						//Incrementar el dia
						$diaActual++;
					}//Cierre for($i=0;$i<$totalDias;$i++)?>				
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr><?php
				
				
				
				/****************COLOCAR LA DIFERENCIA ENTRE LA PRODUCCION REAL Y LA PRESUPUESTADA*******************/
				//Hacer cambio del color de renglon			
				if($nom_clase=="renglon_blanco") $nom_clase="renglon_gris"; else if($nom_clase=="renglon_gris") $nom_clase="renglon_blanco";?>			
				<tr>
					<td class="nombres_filas"><strong>DIFERENCIA</strong></td><?php
					//Obtener el dia y mes de inicio como actuales
					$diaActual = substr($fechas['fecha_inicio'],-2);
					for($i=0;$i<$totalDias;$i++){
						//Hacer la resta de la Produccion Real menos la Presupuestada, cuando se llega aqui existe un registro de producción real por cada registro de Produccion presupuestada?>
						<td align="center" class="<?php echo $nom_clase; ?>"><strong><?php echo round($prodRealPorDia[$diaActual]-$prodPresPorDia[$diaActual],1);?></strong></td><?php					
						
						//Cuando se llegue al dia final del primer mes, resetear el contador de Dias y cambiar de Mes NOTA: Falta comprobar cuando se cambia de año
						if($diaActual==$diasMesInicio)
							$diaActual = 0;
						
						//Incrementar el dia
						$diaActual++;
					}//Cierre for($i=0;$i<$totalDias;$i++)?>
					<td>&nbsp;</td>
					<td>&nbsp;</td>				
				</tr><?php
				
				
				/****************COLOCAR LA PRODUCTIVIDAD DE CADA DIA*******************/			
				if($noEmpleados!=""){?>
					<tr>
						<td colspan="31" align="right">&nbsp;</td>
						<td colspan="2" align="center" class="nombres_columnas">&nbsp;PRODUCTIVIDAD PROMEDIO</td>
					</tr>
					<tr>
						<td class="nombres_filas"><strong>PRODUCTIVIDAD</strong></td><?php
						//Obtener la Suma Total de la Productividad para sacar el Promedio de la misma
						$totalProductividad = 0;
						$contDias = 0;
						foreach($sumPorDia as $ind => $totalDia){
							//Colocar la suma del dia Actual en el caso que exista
							if($totalDia!=0){
								$productividad = $totalDia/$noEmpleados;?>						
								<td align="center" class="<?php echo $nom_clase; ?>"><?php echo number_format($productividad,2,".","."); ?></td><?php						
								//Obtener la suma total de la ubicacion que esta siendo impresa
								$totalProductividad += $productividad;
								$contDias++;
							}
							else{//Si no existe suma del dia colocar un espacio vacio?>
								<td class="<?php echo $nom_clase; ?>">&nbsp;</td><?php					
							}																				
						}//Cierre foreach($sumPorDia as $ind => $totalDia)?>
						<td colspan="2" class="<?php echo $nom_clase; ?>" align="center"><strong><?php echo number_format($totalProductividad/$contDias,2,".",","); ?></strong></td>
					</tr><?php
				}//Cierre if($noEmpleados!="") ?>												
				
				</table><?php								
				
			}//Cierre if($ubicaciones=mysql_fetch_array($rs_ubicaciones))?>
			
		</body><?php		
	
	}//Cierre de la funcion exportarReportePeriodo()
	
	function guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
				border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				-->
			</style>
		</head>	
		<?php
		$fecha_i = $_POST["hdn_fecha_ini"];
		$fecha_f = $_POST["hdn_fecha_fin"];
		$bd = $_POST["hdn_bd"];
		
		$conn=conecta("$bd");
		$rs = mysql_query($hdn_consulta);
		if($datos = mysql_fetch_array($rs)){?>										
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
						align="absbottom" /></td>
						<td colspan="2">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="8" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
							&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>			
					<tr>
						<td class='nombres_columnas' align="center">ID REQUISICI&Oacute;N</td>
						<td class='nombres_columnas' align="center">DEPARTAMENTO</td>
						<td class='nombres_columnas' align="center">FECHA</td>
						<td class='nombres_columnas' align="center">SOLICIT&Oacute;</td>
						<td class='nombres_columnas' align="center">REALIZ&Oacute;</td>
						<td class='nombres_columnas' align="center">ESTADO</td>
						<td class='nombres_columnas' align="center">PRIORIDAD</td>
						<td class='nombres_columnas' align="center">TIEMPO DE ENTREGA</td>
					</tr>
					<?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{
						$dias_ent = calcularDiasEntregaReq($datos["id_requisicion"],$bd);
					?>			
						<tr>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_requisicion']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area_solicitante']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_req'],1); ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['solicitante_req']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['elaborador_req']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['estado']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['prioridad']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $dias_ent; ?></td>
						</tr>
						<?php
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
						
					}while($datos=mysql_fetch_array($rs)); ?>
				</table>
			</div>
			</body><?php
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	function guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
				border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				-->
			</style>
		</head>
		<?php
		$fecha_i = modFecha($_POST["txt_fecha_ini"],3);
		$fecha_f = modFecha($_POST["txt_fecha_fin"],3);
		$bd = $_POST["cmb_departamento"];
		$clave = $_POST["hdn_clave"];
		
		$conn=conecta("$bd");
		//Ejecutar la consulta
		$rs = mysql_query($hdn_consulta);
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){?>							
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="65" 
						align="absbottom" /></td>
						<td valign="baseline" colspan="4">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="7" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
							&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td class='nombres_columnas' align='center'>ID REQUISICI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>CANTIDAD</td>
						<td class='nombres_columnas' align='center'>UNIDAD DE MEDIDA</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>APLICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
						<td class='nombres_columnas' align='center'>TIEMPO DE ENTREGA</td>
					</tr>
					<?php
					$nom_clase = "renglon_gris";
					$cont = 1;
					do{
						$dias_ent = calcularDiasEntregaDetalleReq($datos["requisiciones_id_requisicion"],$bd,$datos["partida"]);
							if($datos['aplicacion'] != "")
								$aplicacion = $datos['aplicacion'];
							else
								$aplicacion = obtenerCentroCosto('control_costos','id_control_costos',$datos['id_control_costos']);
					?>
						<tr>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['requisiciones_id_requisicion']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['cant_req']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad_medida']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $aplicacion; ?></td>
							<?php
							if($datos['estado'] == 1)
								echo "<td align='center' class='$nom_clase'>ENVIADA</td>";
							else if($datos['estado'] == 2)
								echo "<td align='center' class='$nom_clase'>PEDIDO</td>";
							else if($datos['estado'] == 3)
								echo "<td align='center' class='$nom_clase'>CANCELADA</td>";
							else if($datos['estado'] == 4)
								echo "<td align='center' class='$nom_clase'>COTIZANDO</td>";
							else if($datos['estado'] == 5)
								echo "<td align='center' class='$nom_clase'>EN PROCESO</td>";
							else if($datos['estado'] == 6)
								echo "<td align='center' class='$nom_clase'>EN TRANSITO</td>";
							else if($datos['estado'] == 7)
								echo "<td align='center' class='$nom_clase'>ENTREGADA</td>";
							else if($datos['estado'] == 8)
								echo "<td align='center' class='$nom_clase'>AUTORIZADA</td>";
							else if($datos['estado'] == 9)
								echo "<td align='center' class='$nom_clase'>NO AUTORIZADA</td>";
							echo "	<td align='center' class='$nom_clase'>$dias_ent</td>";
							?>
						</tr>
						<?php
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
						
					}while($datos=mysql_fetch_array($rs)); ?>
				</table>
			</div>
			</body><?php
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}
	
	function calcularDiasEntregaReq($id_requisicion,$bd){
		$dias = "NO APLICA";
		$conec = conecta("$bd");
		$stm_sql = "SELECT *, DATEDIFF( CURDATE( ) , fecha ) AS dias_dif 
					FROM requisiciones
					JOIN bd_compras.bitacora_movimientos ON id_operacion = id_requisicion
					WHERE id_requisicion LIKE  '$id_requisicion'
					AND tipo_operacion LIKE  '%CambiaEstado%'
					AND estado = 'EN TRANSITO'
					ORDER BY fecha DESC 
					LIMIT 1";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dias = $datos["cant_entrega"];
				if($datos["tipo_entrega"] == "SEMANAS")
					$dias = $dias * 7;
				else if($datos["tipo_entrega"] == "MESES")
					$dias = $dias * 30;
				$dias = $dias - $datos["dias_dif"];
				if($dias == 0)
					$dias = "HOY";
				else if($dias < 0)
					$dias = "EXPIRADO HACE ".abs($dias)." DIAS";
				else
					$dias = $dias." DIAS";
			}
		}
		return $dias;
	}
	
	function calcularDiasEntregaDetalleReq($id_requisicion,$bd,$partida){
		$dias = "NO APLICA";
		$conec = conecta("$bd");
		$stm_sql = "SELECT * , DATEDIFF( CURDATE( ) , fecha_estado ) AS dias_dif
					FROM detalle_requisicion
					WHERE requisiciones_id_requisicion LIKE  '$id_requisicion'
					AND estado =  '6'
					AND partida =  '$partida'
					ORDER BY fecha_estado DESC ";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dias = $datos["cant_entrega"];
				if($datos["tipo_entrega"] == "SEMANAS")
					$dias = $dias * 7;
				else if($datos["tipo_entrega"] == "MESES")
					$dias = $dias * 30;
				$dias = $dias - $datos["dias_dif"];
				if($dias == 0)
					$dias = "HOY";
				else if($dias < 0)
					$dias = "EXPIRADO HACE ".abs($dias)." DIAS";
				else
					$dias = $dias." DIAS";
			}
		}
		return $dias;
	}
	
	function obtenerCentroCosto($tabla,$busq,$valor){
		$dat = $valor; 
		$con = conecta("bd_recursos");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		mysql_close($con);
		return $dat;
	}
?>