<?php 
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 12/Enero/2010                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas y reportes generados como	      * lo son:
	  *			 1. Reporte de Compras
	  *			 2. Reporte de Ventas
	  **/
	 
	 
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			
	/**   Código en: pages\com\guardar_reporte.php                                   
      **/
	  			
	if(isset($_POST['hdn_consulta'])){
		
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		
		switch($hdn_origen){
			case "BitCons":
				guardarRepBitCons($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
		}										
	}
	
	//Esta funcion exporta el REPORTE OTSE a un archivo de excel
	function guardarRepBitCons($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Conectar a la BD de Mantenimiento
		$conn = conecta("bd_sistemas");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="2">&nbsp;</td>
						<td valign="baseline" colspan="2">
							<div align="right"><span class="texto_encabezado">
								<strong>REPORTE BITACORA DE CONSUMIBLES DE IMPRESI&Oacute;N</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="6" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>			
					<tr>
						<td class='nombres_columnas' align="center">DESCRIPCI&Oacute;N</td>
						<td class='nombres_columnas' align="center">FECHA</td>
						<td class='nombres_columnas' align="center">CANTIDAD</td>
						<td class='nombres_columnas' align="center">TIPO</td>
						<td class='nombres_columnas' align="center">DEPARTAMENTO</td>
						<td class='nombres_columnas' align="center">EMPLEADO</td>
					</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				include_once("../../includes/func_fechas.php");
				
				echo "<tr>
						<td class='$nom_clase'>$datos[descripcion] $datos[color]</td>
						<td class='$nom_clase'>".modFecha($datos['fecha'],1)."</td>
						<td class='$nom_clase'>$datos[cantidad]</td>";
				if($datos["tipo"] == "E")
					echo "<td class='$nom_clase'>ENTRADA</td>";
				else
					echo "<td class='$nom_clase'>SALIDA</td>";
				echo 	"<td class='$nom_clase'>$datos[departamento]</td>
						 <td class='$nom_clase'>$datos[empleado]</td>
					  </tr>";
				
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos));?>
			
			</table>
			</div>
			</body><?php	
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepREA($hdn_consulta,$hdn_nomReporte)
?>