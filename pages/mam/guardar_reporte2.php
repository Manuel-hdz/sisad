<?php 
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas                            
	  * Fecha: 14/Noviembre/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas y reportes generados como lo son:
	  *	1. Reporte Nomina
	  *	2. Reporte Rezagado
	  *	3. Reporte Equipo Utilitario
	  *	4. Reporte Barrenacion con Jumbo
	  *	5. Reporte Barrenacion con Maquina de Pierna
	  *	6. Reporte Voladuras
	  *	7. Reporte Avance
	  *	8. Reporte Servicios con Minera Fresnillo
	  **/
	 
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
	/**   Código en: pages\alm\guardar_reporte.php                                   
      **/
	
	if(isset($_POST['sbt_excel'])){
		if(isset($_POST['hdn_consulta'])){
		
			//Ubicacion de las imagenes que estan contenidas en los encabezados
			define("HOST", $_SERVER['HTTP_HOST']);
			//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
			$raiz = explode("/",$_SERVER['PHP_SELF']);
			define("SISAD",$raiz[1]);
		
		
			switch($hdn_tipoReporte){
				case "exportarNomina":
					guardarRepNomina($hdn_consulta,$hdn_msje,$fecha_ini, $fecha_fin);
			}				
		}
	}
	
	//Esta funcion exporta la Nómina a un archivo de excel
	function guardarRepNomina($hdn_consulta,$hdn_msje,$fecha_ini,$fecha_fin){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Nomina_Mtto_Mina.xls");		
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_mantenimiento");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: medium;
										border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid; border-bottom-style: solid; border-left-style: solid; 
										border-top-color: #000000; border-bottom-color: #000000;}
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
					/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.texto_totales {
						font-family: Calibri; font-size: 19px; color: #000000; background-color: #FFFFFF; font-weight: bold;
						text-decoration: underline; text-align: center; vertical-align: middle;
					}
					.cantidad_topes_total {
						font-family: Calibri; font-size: 16px; color: #000000; background-color: #E7E7E7; font-weight: bold;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					.cantidad_total {
						font-family: Calibri; font-size: 16px; color: #000000; font-weight: normal;
						text-align: center; vertical-align: middle; border-style: solid; border-width: 1px;
					}
					-->
				</style>
			</head>
			<body>
			<div id="tabla">				
				<table width="1100">
					
							<tr></tr>
							<tr>
								<td></td><td></td>
								<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="250" height="100"/>
							</tr>
							<tr>
								<td></td><td></td><td></td>
								<td colspan="13" rowspan="2" class="cantidad_total" style="font-size: 25px; border-style: none; font-weight: bold;">
									NOMINA DE MANTENIMIENTO MINA UNIDAD FRESNILLO
								</td>
								<td class="cantidad_total" style="border-style: none; font-weight: bold;" colspan="2">Semana:</td>
								<td style="border-bottom-style: solid; border-bottom-width: 1px;" colspan="2"><?php echo $fecha_ini." al ".$fecha_fin ?></td>
							</tr>
							<tr></tr>
							<tr>
								<td width="10"></td><td width="60"></td>
								<td width="150"></td><td width="100"></td><td width="50"></td>
								<td width="30"></td><td width="30"></td><td width="30"></td><td width="30"></td>
								<td width="30"></td><td width="30"></td><td width="30"></td><td width="30"></td>
								<td width="120"></td><td width="100"></td><td width="120"></td><td width="50"></td><td width="50">
								</td><td width="50"></td><td width="200"></td>
							</tr>
							<tr></tr>
							<tr>
								<td></td>
								<td class="cantidad_topes_total">N°</td>
								<td colspan=3 class="cantidad_topes_total" style="font-size: 19px;">NOMBRE DEL COLABORADOR</td>
								<td class="cantidad_topes_total">J</td>
								<td class="cantidad_topes_total">V</td>
								<td class="cantidad_topes_total">S</td>
								<td class="cantidad_topes_total">D</td>
								<td class="cantidad_topes_total">L</td>
								<td class="cantidad_topes_total">M</td>
								<td class="cantidad_topes_total">M</td>
								<td class="cantidad_topes_total">E</td>
								<td class="cantidad_topes_total">SUELDO B.</td>
								<td class="cantidad_topes_total">SUELDO DIARIO</td>
								<td class="cantidad_topes_total">TOTAL</td>
								<td class="cantidad_topes_total">HRS. EXTRA</td>
								<td class="cantidad_topes_total">G. 8HRS</td>
								<td class="cantidad_topes_total">G. 12HRS</td>
								<td class="cantidad_topes_total">COMENTARIOS</td>
							</tr>
					<?php 
						$totalsb = 0; 
						$totald = 0; 
						$totalt = 0;
						do{
							if($datos["horas_extra"] > 0) $e = "X"; else $e = "";
							if($datos["guarda_12hrs"] == 1) {
								$g12 = "X";
							}	else $g12 = "";
							if($datos["guarda_8hrs"] == 1) {
								$g8 = "X";
							}	else $g8 = ""; 
							$totalsb += $datos["sueldo_base"];
							$totalt += $datos["total_pagado"]; ?>
							<tr>
								<td></td>
								<td class="cantidad_total" style="font-weight: bold;"><u><?php echo $datos["id_empleados_empresa"]; ?></u></td>
								<td colspan=3 class="cantidad_total" style="font-weight: bold; background-color:yellow;"><u><?php echo $datos["nombre_emp"]; ?></u></td>
								<td class="cantidad_total"><?php if($datos["jueves"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["jueves"]; ?></td>
								<td class="cantidad_total"><?php if($datos["viernes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["viernes"]; ?></td>
								<td class="cantidad_total"><?php if($datos["sabado"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["sabado"]; ?></td>
								<td class="cantidad_total"><?php if($datos["domingo"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["domingo"]; ?></td>
								<td class="cantidad_total"><?php if($datos["lunes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["lunes"]; ?></td>
								<td class="cantidad_total"><?php if($datos["martes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["martes"]; ?></td>
								<td class="cantidad_total"><?php if($datos["miercoles"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["miercoles"]; ?></td>
								<td class="cantidad_total"><?php echo $e; ?></td>
								<td class="cantidad_total" style="background-color:yellow;"><?php echo $datos["sueldo_base"]; ?></td>
								<td class="cantidad_total" style="font-weight: bold;"><u><?php echo $datos["sueldo_diario"]; ?></u></td>
								<td class="cantidad_total" style="mso-number-format:'Currency'; background-color:yellow;"><?php echo $datos["total_pagado"]; ?></td>
								<td class="cantidad_total"><?php echo $datos["horas_extra"]; ?></td>
								<td class="cantidad_total"><?php echo $g8; ?></td>
								<td class="cantidad_total"><?php echo $g12; ?></td>
								<td class="cantidad_total"><?php echo $datos["comentarios"]; ?></td>
							</tr>
					<?php } while($datos=mysql_fetch_array($rs_datos));?>
							<tr>
								<td></td>
								<td class="cantidad_total"></td>
								<td colspan=3 class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
								<td class="cantidad_total"></td>
							</tr>
							<tr>
								<td></td><td></td>
								<td colspan=10 class="cantidad_total" style="font-weight: bold;"><u>TOTAL</u></td>
								<td></td>
								<td class="cantidad_total" style="font-weight: bold;"><u><?php echo $totalsb; ?></u></td>
								<td></td>
								<td class="cantidad_total" style="background-color:#33CC66; font-weight: bold; mso-number-format:'Currency';"><u><?php echo $totalt; ?></u></td>
							</tr>
				</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepNomina($hdn_consulta,$hdn_nomReporte)
?>