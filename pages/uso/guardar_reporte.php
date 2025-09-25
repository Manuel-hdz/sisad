<?php 
	/**
	  * Nombre del M�dulo: USO                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas                            
	  * Fecha: 02/Julio/2012
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n en una hoja de calculo de excel de las consultas realizadas y reportes generados como lo son:
	  *	1. Reporte Bitacora Radiografias
	  **/
	 
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
	/**   C�digo en: pages\alm\guardar_reporte.php                                   
      **/
	  			
	//Ubicacion de las imagenes que estan contenidas en los encabezados
	define("HOST", $_SERVER['HTTP_HOST']);
	//Obtener el nombre del Nombre de la Carpeta Ra�z donde se encontrar� almacenado el SISAD
	$raiz = explode("/",$_SERVER['PHP_SELF']);
	define("SISAD",$raiz[1]);
	
	if(isset($_GET["tipoRep"])){
		switch($_GET["tipoRep"]){
			case "RepBitRadio":
				guardarRepRadiografias($_GET["fechaI"],$_GET["fechaF"]);
			break;
			case "catalogoEmpExt":
				mostrarCatalogoEmpExt();
			break;
			case "catalogoExaMed":
				mostrarCatalogoExaMedicos();
			break;
			case "catalogoRadiografias":
				mostrarCatalogoRadiografias();
			break;
			case "RepCatMedicamento":
				mostrarRepCatMedicamento();
			break;
			case "RepBitConsultas":
				guardarRepConsMedicas($_GET["fechaI"],$_GET["fechaF"],$_GET["clasificacion"],$_GET["tipo"]);
			break;
			case "RepCensosConsultas":
				mostrarReporteCensosConsultas($_GET["fechaI"],$_GET["fechaF"],$_GET["clasificacion"],$_GET["tipo"]);
			break;
			case "RepHistorialesClinicos":
				mostrarReporteHistorialesClinicos($_GET["fechaI"],$_GET["fechaF"],$_GET["clasificacion"],$_GET["tipo"]);
			break;
			case "RepIncapacidadesEmpleados":
				mostrarRepIncapacidadesEmpleados($_GET["fechaI"],$_GET["fechaF"],$_GET["tipo"]);
			break;
			case "RepResultadosExa":
				mostrarRepResultadosExamenMedico($_GET["fechaI"],$_GET["fechaF"]);
			break;
		}
	}
	
	if(isset($_POST['sbt_excel'])){
		if(isset($_POST['hdn_consulta'])){
			
			define("HOST", $_SERVER['HTTP_HOST']);
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
		
	if(isset($_POST["hdn_divExpRepActSemanales"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		//define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Ra�z donde se encontrar� almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		//define("SISAD",$raiz[1]);
		guardarRepActSemanalesUSO();
	}
	
	//Esta funcion exporta a Excel el Reporte de consultas Medicas realizadas
	function guardarRepConsMedicas($fechaIni,$fechaFin,$clasificacion,$tipo){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=BitacoraConsultas.xls");		
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		//Convertir las Fechas
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		if($clasificacion=="" && $tipo=="")
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas,catalogo_empresas_id_empresa,id_empleados_empresa,nom_empleado,area,puesto,tipo_consulta,consulta,nom_familiar,parentesco,fecha,hora,lugar,pb_diagnostico,tratamiento,observaciones
						FROM bitacora_consultas WHERE fecha BETWEEN '$fechaI' AND '$fechaF' ORDER BY catalogo_empresas_id_empresa,nom_empleado";
		else if($clasificacion!="" && $tipo!="")
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas,catalogo_empresas_id_empresa,id_empleados_empresa,nom_empleado,area,puesto,tipo_consulta,consulta,nom_familiar,parentesco,fecha,hora,lugar,pb_diagnostico,tratamiento,observaciones
						FROM bitacora_consultas WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND consulta='$clasificacion' AND tipo_consulta='$tipo' ORDER BY catalogo_empresas_id_empresa,nom_empleado";
		else if($clasificacion!="" && $tipo=="")
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas,catalogo_empresas_id_empresa,id_empleados_empresa,nom_empleado,area,puesto,tipo_consulta,consulta,nom_familiar,parentesco,fecha,hora,lugar,pb_diagnostico,tratamiento,observaciones
						FROM bitacora_consultas WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND consulta='$clasificacion' ORDER BY catalogo_empresas_id_empresa,nom_empleado";
		else if($clasificacion=="" && $tipo!="")
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas,catalogo_empresas_id_empresa,id_empleados_empresa,nom_empleado,area,puesto,tipo_consulta,consulta,nom_familiar,parentesco,fecha,hora,lugar,pb_diagnostico,tratamiento,observaciones
						FROM bitacora_consultas WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND tipo_consulta='$tipo' ORDER BY catalogo_empresas_id_empresa,nom_empleado";
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="9">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="15" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="15">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="15">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="15" align="center" class="titulo_tabla"><?php echo "Registros de la Bit&aacute;cora de Consultas M&eacute;dicas del $fechaIni al $fechaFin"?></td>
					</tr>
					<tr>
						<td colspan="15">&nbsp;</td>
					</tr>			
					<tr>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>HORA</td>
						<td class='nombres_columnas' align='center'>EMPRESA</td>
						<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO<br>TRABAJADOR</td>
        				<td class='nombres_columnas' align='center'>NOMBRE<br>TRABAJADOR</td>
				        <td class='nombres_columnas' align='center'>&Aacute;REA</td>
        				<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>FAMILIAR</td>
						<td class='nombres_columnas' align='center'>PARENTESCO</td>
						<td class='nombres_columnas' align='center'>LUGAR</td>
						<td class='nombres_columnas' align='center'>DIAGN&Oacute;STICO</td>
						<td class='nombres_columnas' align='center'>TRATAMIENTO</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
						<td class='nombres_columnas' align='center'>MEDICAMENTO SUMINISTRADO</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				if($datos["catalogo_empresas_id_empresa"]==0)
					$empresa="CONCRETO LANZADO DE FRESNILLO MARCA";
				else
					$empresa=obtenerDato("bd_clinica","catalogo_empresas", "nom_empresa", "id_empresa", $datos['catalogo_empresas_id_empresa']);
				
				//Variable con las proyecciones realizadas
				$medicamentos="";
				$idBit=$datos["id_bit_consultas"];
				$sql="SELECT nombre_med,cant_salida,unidad_despacho FROM catalogo_medicamento JOIN bitacora_medicamentos ON id_med=catalogo_medicamento_id_med WHERE bitacora_consultas_id_bit_consultas='$idBit'";
				$rs2=mysql_query($sql);
				if($datosMed=mysql_fetch_array($rs2)){
					do{
						$medicamentos.="$datosMed[cant_salida] $datosMed[unidad_despacho](S) DE ".$datosMed["nombre_med"].", ";
					}while($datosMed=mysql_fetch_array($rs2));
					$medicamentos=substr($medicamentos,0,(strlen($medicamentos)-2));
				}
				
			?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha"],1)?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modHora($datos["hora"]); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $empresa;?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["tipo_consulta"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["id_empleados_empresa"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nom_empleado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["area"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["puesto"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nom_familiar"];?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["parentesco"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["lugar"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["pb_diagnostico"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["tratamiento"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["observaciones"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $medicamentos; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}
	
	//Esta funcion exporta el Reporte de Radiografias segun la Bitacora a un archivo de excel
	function guardarRepRadiografias($fechaIni,$fechaFin){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=BitacoraRadiografias.xls");		
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		//Convertir las Fechas
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);		
		//Sentencia SQL para consultar el registro de Bitacora
		$sql_stm="SELECT id_bit_radiografias,catalogo_empresas_id_empresa,categoria,id_empleados_empresa,nom_empleado,area,puesto,fecha,lugar_practicado,cant_proyeccion,nom_solicitante,nom_responsable
					FROM bitacora_radiografias WHERE fecha BETWEEN '$fechaI' AND '$fechaF' ORDER BY fecha,catalogo_empresas_id_empresa,nom_empleado";
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($sql_stm);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="6">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="12" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="12">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="12">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="12" align="center" class="titulo_tabla"><?php echo "Registros de la Bit&aacute;cora de Radiograf&iacute;as del $fechaIni al $fechaFin"?></td>
					</tr>
					<tr>
						<td colspan="12">&nbsp;</td>
					</tr>			
					<tr>
						<td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A</td>
						<td class='nombres_columnas' align='center'>EMPRESA</td>
        				<td class='nombres_columnas' align='center'>NOMBRE<br>TRABAJADOR</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO<br>TRABAJADOR</td>
				        <td class='nombres_columnas' align='center'>&Aacute;REA</td>
        				<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>PROYECCIONES<br>REALIZADAS</td>
						<td class='nombres_columnas' align='center'>CANTIDAD<br>PROYECCIONES</td>
						<td class='nombres_columnas' align='center'>NOMBRE<br>SOLICITANTE</td>
						<td class='nombres_columnas' align='center'>LUGAR EN QUE<br>SE PRACTIC&Oacute;</td>
						<td class='nombres_columnas' align='center'>RADIOGRAF&Iacute;A(S)<br>TOMADA(S) POR</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				if($datos["catalogo_empresas_id_empresa"]==0)
					$empresa="CONCRETO LANZADO DE FRESNILLO MARCA";
				else
					$empresa=obtenerDato("bd_clinica","catalogo_empresas", "nom_empresa", "id_empresa", $datos['catalogo_empresas_id_empresa']);
				//Variable con las proyecciones realizadas
				$proyecciones="";
				$idBit=$datos["id_bit_radiografias"];
				$sql="SELECT nom_proyeccion FROM catalogo_radiografias JOIN detalle_radiografia ON catalogo_radiografias_id_proyeccion=id_proyeccion WHERE bitacora_radiografias_id_bit_radiografias='$idBit'";
				$rs2=mysql_query($sql);
				if($datosRadio=mysql_fetch_array($rs2)){
					do{
						$proyecciones.=$datosRadio["nom_proyeccion"].", ";
					}while($datosRadio=mysql_fetch_array($rs2));
					$proyecciones=substr($proyecciones,0,(strlen($proyecciones)-2));
				}
			?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha"],1)?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["categoria"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $empresa;?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nom_empleado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["id_empleados_empresa"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["area"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["puesto"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $proyecciones;?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["cant_proyeccion"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nom_solicitante"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["lugar_practicado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nom_responsable"]; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepNomina($hdn_consulta,$hdn_nomReporte)



	//Esta funcion exporta el Catalogo de las Empresas Externas
	function mostrarCatalogoEmpExt(){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=CatalogoEmpresasExt.xls");		
		
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		
		//Sentencia SQL para consultar el registro de las empresas externas
		$sql_stm ="SELECT * FROM catalogo_empresas ORDER BY id_empresa ";
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_empExt = mysql_query($sql_stm);
			
		if($datosEmpExt=mysql_fetch_array($rs_empExt)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="5">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em><br>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="11" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="11">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="11">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="11" align="center" class="titulo_tabla">CAT�LOGO DE EMPRESAS EXTERNAS</td>
					</tr>
					<tr>
						<td colspan="11">&nbsp;</td>
					</tr>			
					<tr>						
						<td align='center' class='nombres_columnas'>CLAVE EMPRESA</td>
						<td align='center' class='nombres_columnas'>NOMBRE DE LA EMPRESA</td>							
						<td align='center' class='nombres_columnas'>RAZ&Oacute;N SOCIAL</td>
						<td align='center' class='nombres_columnas'>TIPO EMPRESA</td>	
						<td align='center' class='nombres_columnas'>CALLE</td>
						<td align='center' class='nombres_columnas'>N&Uacute;MERO INTERNO</td>
						<td align='center' class='nombres_columnas'>N&Uacute;MERO EXTERNO</td>	
						<td align='center' class='nombres_columnas'>CIUDAD</td>	
						<td align='center' class='nombres_columnas'>ESTADO</td>
						<td align='center' class='nombres_columnas'>COLONIA</td>	
						<td align='center' class='nombres_columnas'>TELEFONO</td>			
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;						
			do{?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['id_empresa']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['nom_empresa']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['razon_social']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['tipo_empresa']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['calle']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['numero_int']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['numero_ext']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['colonia']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['ciudad']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['estado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpExt['telefono']; ?></td		
					></tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datosEmpExt=mysql_fetch_array($rs_empExt)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion mostrarCatalogoEmpExt($hdn_consulta,$hdn_nomReporte)
	
	
		//Esta funcion exporta el Catalogo de los Examenes Medicos 	que se realizan dentro de la Clinica
	function mostrarCatalogoExaMedicos(){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=CatalogoExamenesMed.xls");		
		
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		
		//Sentencia SQL para consultar el registro de los examenes medicos
		$sql_stm ="SELECT * FROM catalogo_examen ORDER BY id_examen ";
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_exaMed = mysql_query($sql_stm);
			
		if($datosExaMed=mysql_fetch_array($rs_exaMed)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="1">&nbsp;</td>
						<td valign="baseline" colspan="2">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="5" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5" align="center" class="titulo_tabla">CAT�LOGO DE EX&Aacute;MENES M&Eacute;DICOS</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>			
					<tr>						
						<td align='center' class='nombres_columnas'>CLAVE EXAMEN</td>
						<td align='center' class='nombres_columnas'>NOMBRE DEL EXAMEN</td>							
						<td align='center' class='nombres_columnas'>TIPO EXAMEN</td>	
						<td align='center' class='nombres_columnas'>COSTO EXAMEN</td>	
						<td align='center' class='nombres_columnas'>COMENTARIOS</td>			
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;						
			do{?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosExaMed['id_examen']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosExaMed['nom_examen']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosExaMed['tipo_examen']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosExaMed['costo_exa']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosExaMed['comentarios']; ?></td		
					></tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datosExaMed=mysql_fetch_array($rs_exaMed)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion mostrarCatalogoEmpExt($hdn_consulta,$hdn_nomReporte)



	//Esta funcion exporta el Catalogo de las Radiografias que se realizan dentro de la Clinica
	function mostrarCatalogoRadiografias(){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=CatalogoRadiografias.xls");		
		
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		
		//Sentencia SQL para consultar el registro de los examenes medicos
		$sql_stm ="SELECT * FROM catalogo_radiografias ORDER BY id_proyeccion ";
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_catProy = mysql_query($sql_stm);
			
		if($datosCatProy=mysql_fetch_array($rs_catProy)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
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
						<td align="left" valign="baseline" colspan="1"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="1">&nbsp;</td>
						<td valign="baseline" colspan="1">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="3" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="3" align="center" class="titulo_tabla">CAT�LOGO DE Radiograf&Iacute;AS</td>
					</tr>
					<tr>
						<td colspan="3">&nbsp;</td>
					</tr>			
					<tr>						
						<td align='center' class='nombres_columnas'>CLAVE RADIOGRAF&Iacute;AS</td>
						<td align='center' class='nombres_columnas'>NOMBRE DE LA RADIOGRAF&Iacute;A</td>													
						<td align='center' class='nombres_columnas'>COMENTARIOS</td>			
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;						
			do{?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosCatProy['id_proyeccion']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosCatProy['nom_proyeccion']; ?></td>				
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosCatProy['comentarios']; ?></td		
					></tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datosCatProy=mysql_fetch_array($rs_catProy)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion mostrarCatalogoRadiografias()

	//Esta funcion exporta el Reporte de Radiografias segun la Bitacora a un archivo de excel
	function mostrarRepCatMedicamento(){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=CatalogoMedicamento.xls");		
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		//Sentencia SQL para consultar el registro de Bitacora
		$sql_stm="SELECT id_med,codigo_med,nombre_med,descripcion_med,presentacion,clasificacion_med,existencia_actual,unidad_despacho FROM catalogo_medicamento ORDER BY clasificacion_med DESC,codigo_med,nombre_med";
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($sql_stm);
			
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td>&nbsp;</td>
						<td valign="baseline" colspan="2">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="6" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6" align="center" class="titulo_tabla"><?php echo "Registros del Cat&aacute;logo de Medicamentos"?></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>			
					<tr>
						<td class='nombres_columnas' align='center'>C&Oacute;DIGO<br>MEDICAMENTO</td>
						<td class='nombres_columnas' align='center'>NOMBRE<br>MEDICAMENTO</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N MEDICAMENTO</td>
        				<td class='nombres_columnas' align='center'>PRESENTACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>CLASIFICACI&Oacute;N</td>
				        <td class='nombres_columnas' align='center'>EXISTENCIA UNITARIA</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				echo " 
					<tr>
						<td class='$nom_clase' align='center'>&nbsp;$datos[codigo_med]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_med]</td>
						<td class='$nom_clase' align='center'>$datos[descripcion_med]</td>
						<td class='$nom_clase' align='center'>$datos[presentacion]</td>
						<td class='$nom_clase' align='center'>$datos[clasificacion_med]</td>
						<td class='$nom_clase' align='center'>$datos[existencia_actual] $datos[unidad_despacho](S)</td>		
					</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";					
			}while($datos=mysql_fetch_array($rs_datos));
			?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepNomina($hdn_consulta,$hdn_nomReporte)
	

			
	
	//Esta funcion exporta a Excel el Reporte de consultas Medicas realizadas
	function mostrarReporteCensosConsultas($fechaIni,$fechaFin,$clasificacion,$tipo){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteCensosConsultas.xls");		
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		//Convertir las Fechas
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		//Volvemos a convertir las fechas definidas anteriormente para colocar el titulo dentro d ela tabla que mostrara los resultados 
		$fechaIni=modFecha($fechaI,1);
		$fechaFin=modFecha($fechaF,1);
		
		if($clasificacion=="" && $tipo==""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas, catalogo_empresas_id_empresa, empleados_rfc_empleado, id_empleados_empresa, nom_empleado, area, puesto, 
			tipo_consulta, consulta, nom_familiar, parentesco, fecha, hora, lugar, pb_diagnostico, tratamiento, observaciones FROM bitacora_consultas
			 WHERE fecha BETWEEN '$fechaI' AND '$fechaF' ORDER BY id_bit_consultas";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Censos y Consultas Registradas del $fechaIni al $fechaFin";
		}
		else if($clasificacion!="" && $tipo!=""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas, catalogo_empresas_id_empresa, empleados_rfc_empleado, id_empleados_empresa, nom_empleado, area, puesto, 
			tipo_consulta, consulta, nom_familiar, parentesco, fecha, hora, lugar, pb_diagnostico, tratamiento, observaciones FROM bitacora_consultas 
			WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND consulta='$clasificacion' AND tipo_consulta='$tipo' ";
			 
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Censos y Consultas Registradas del $fechaIni al $fechaFin de las Consultas $clasificacion de Tipo $tipo";
		}
		else if($clasificacion!="" && $tipo==""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas, catalogo_empresas_id_empresa, empleados_rfc_empleado, id_empleados_empresa, nom_empleado, area, puesto, 
			tipo_consulta, consulta, nom_familiar, parentesco, fecha, hora, lugar, pb_diagnostico, tratamiento, observaciones FROM bitacora_consultas 
			WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND consulta='$clasificacion' ";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Censos y Consultas Registradas del $fechaIni al $fechaFin de las Consultas $clasificacion";
		}
		else if($clasificacion=="" && $tipo!=""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas, catalogo_empresas_id_empresa, empleados_rfc_empleado, id_empleados_empresa, nom_empleado, area, puesto, 
			tipo_consulta, consulta, nom_familiar, parentesco, fecha, hora, lugar, pb_diagnostico, tratamiento, observaciones FROM bitacora_consultas
			WHERE fecha BETWEEN '$fechaI' AND '$fechaF'  AND tipo_consulta='$tipo' ";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Censos y Consultas Registradas del $fechaIni al $fechaFin de las Consultas de Tipo $tipo";
		} 
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
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
						<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="10">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="16" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="16" align="center" class="titulo_tabla"><?php echo "$titulo"?></td>
					</tr>
					<tr>
						<td colspan="16">&nbsp;</td>
					</tr>			
					<tr>
						<td class='nombres_columnas' align='center'>CLAVE CONSULTA</td>
						<td class='nombres_columnas' align='center'>EMPRESA</td>
						<td class='nombres_columnas' align='center'>NOMBRE EMPLEADO</td>
						<td class='nombres_columnas' align='center'>&Aacute;REA</td>
        				<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>CONSULTA</td>
        				<td class='nombres_columnas' align='center'>TIPO CONSULTA</td>
				        <td class='nombres_columnas' align='center'>FECHA</td>
						<td class='nombres_columnas' align='center'>HORA</td>
						<td class='nombres_columnas' align='center'>LUGAR</td>
						<td class='nombres_columnas' align='center'>PB DIAGN&Oacute;STICO</td>
						<td class='nombres_columnas' align='center'>TRATAMIENTO</td>
						<td class='nombres_columnas' align='center'>OBSERVACIONES</td>
						<td class='nombres_columnas' align='center'>MEDICAMENTO SUMINISTRADO</td>
						<td colspan="2" class='nombres_columnas' align='center'>FAMILIAR DEL TRABAJADOR CONSULTADO</td>
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				if($datos["catalogo_empresas_id_empresa"]==0)
					$empresa="CONCRETO LANZADO DE FRESNILLO MARCA";
				else
					$empresa=obtenerDato("bd_clinica","catalogo_empresas", "nom_empresa", "id_empresa", $datos['catalogo_empresas_id_empresa']);
				
				//Variable con que contendra los medicamentos suministrados de acuerdo a las consultas realizadas
				$medicamentos="";
				$consultaExterna="";
				$idBit=$datos["id_bit_consultas"];
				
				$sql="SELECT bitacora_consultas.fecha, nom_empleado, clasificacion_med, nombre_med, descripcion_med, presentacion, unidad_despacho, unidad_medida, 
				cant_salida, nom_familiar, parentesco FROM catalogo_medicamento JOIN bitacora_medicamentos ON id_med = catalogo_medicamento_id_med 
				JOIN bitacora_consultas ON id_bit_consultas = bitacora_consultas_id_bit_consultas WHERE bitacora_consultas_id_bit_consultas = '$idBit'";
				
				$rs2=mysql_query($sql);
				if($datosMed=mysql_fetch_array($rs2)){
					$consultaExterna.="$datosMed[nom_familiar] $datosMed[parentesco]";
					$medicamentos=substr($medicamentos,0,(strlen($medicamentos)-2));	
					//mysql_query("SELECT nom_familiar, parentesco bitacora_consultas WHERE bitacora_consultas_id_bit_consultas = '$idBit'");
					do{
						$medicamentos.="$datosMed[cant_salida] $datosMed[unidad_despacho](S) DE ".$datosMed["nombre_med"].", ";
					}while($datosMed=mysql_fetch_array($rs2));
								
				}
				else{
						$medicamentos.="No se Suministro Medicamento";
						$consultaExterna.="No se Realizo la Consulta a un Familiar del Trabajador";
					}?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["id_bit_consultas"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $empresa;?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nom_empleado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["area"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["puesto"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["tipo_consulta"];?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["consulta"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha"],1)?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modHora($datos["hora"]); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["lugar"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["pb_diagnostico"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["tratamiento"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["observaciones"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $medicamentos; ?></td>
						<td colspan="2" align="center" class="<?php echo $nom_clase; ?>"><?php echo $consultaExterna; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}
	
	
	//Esta funcion exporta a Excel el Reporte de consultas Medicas realizadas
	function mostrarReporteHistorialesClinicos($fechaIni,$fechaFin,$clasificacion,$tipo){
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteHistorialesClinicos.xls");		
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		//Convertir las Fechas
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		//Volvemos a convertir las fechas definidas anteriormente para colocar el titulo dentro d ela tabla que mostrara los resultados 
		$fechaIni=modFecha($fechaI,1);
		$fechaFin=modFecha($fechaF,1);
		
		if($clasificacion=="" && $tipo==""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT DISTINCT 	`T1`.`tipo_clasificacion` , `T1`.`nom_empresa` ,
										`T1`.`id_historial` , `T1`.`puesto_realizar` , `T1`.`nom_empleado` , `T1`.`escolaridad` , 
										`T4`.`peso_kg` , `T4`.`talla_mts` ,  `T4`.`pres_arterial` , `T4`.`imc` ,
										`T2`.`lentes` ,  `T2`.`ojo_der_vision` , `T2`.`ojo_izq_vision` , `T2`.`membrana_der` , 
										`T2`.`membrana_izq` ,  `T2`.`porciento_hbc`, `T5`.`vdrl`, `T5`.`bh`, `T5`.`glicemia`,
										`T5`.`hiv`, `T5`.`tg`, `T5`.`colesterol`, `T5`.`tipo_sanguineo`,
										`T5`.`diag_laboratorio`, `T5`.`alcoholimetro`, `T5`.`espirometria`,
										`T5`.`rx_torax`, `T5`.`col_lumbrosaca`, `T5`.`diagnostico`
					  FROM  `historial_clinico` AS T1
					  JOIN  `aspectos_grales_1` AS T2 ON  `T1`.`id_historial` =  `T2`.`historial_clinico_id_historial` 
					  JOIN  `aspectos_grales_2` AS T3 ON  `T2`.`historial_clinico_id_historial` =  `T3`.`historial_clinico_id_historial` 
					  JOIN  `antecedentes_fam` AS T4 ON  `T3`.`historial_clinico_id_historial` =  `T4`.`historial_clinico_id_historial` 
					  JOIN  `laboratorio` AS T5 ON  `T4`.`historial_clinico_id_historial` =  `T5`.`historial_clinico_id_historial`
					  WHERE fecha_exp BETWEEN '$fechaI' AND '$fechaF' ORDER BY id_historial";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Historiales Clinicos Registrados del $fechaIni al $fechaFin";
		}
		else if($clasificacion!="" && $tipo!=""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT DISTINCT 	`T1`.`tipo_clasificacion` , `T1`.`nom_empresa` ,
										`T1`.`id_historial` , `T1`.`puesto_realizar` , `T1`.`nom_empleado` , `T1`.`escolaridad` , 
										`T4`.`peso_kg` , `T4`.`talla_mts` ,  `T4`.`pres_arterial` , `T4`.`imc` ,
										`T2`.`lentes` ,  `T2`.`ojo_der_vision` , `T2`.`ojo_izq_vision` , `T2`.`membrana_der` , 
										`T2`.`membrana_izq` ,  `T2`.`porciento_hbc`, `T5`.`vdrl`, `T5`.`bh`, `T5`.`glicemia`,
										`T5`.`hiv`, `T5`.`tg`, `T5`.`colesterol`, `T5`.`tipo_sanguineo`,
										`T5`.`diag_laboratorio`, `T5`.`alcoholimetro`, `T5`.`espirometria`,
										`T5`.`rx_torax`, `T5`.`col_lumbrosaca`, `T5`.`diagnostico`
					  FROM  `historial_clinico` AS T1
					  JOIN  `aspectos_grales_1` AS T2 ON  `T1`.`id_historial` =  `T2`.`historial_clinico_id_historial` 
					  JOIN  `aspectos_grales_2` AS T3 ON  `T2`.`historial_clinico_id_historial` =  `T3`.`historial_clinico_id_historial` 
					  JOIN  `antecedentes_fam` AS T4 ON  `T3`.`historial_clinico_id_historial` =  `T4`.`historial_clinico_id_historial` 
					  JOIN  `laboratorio` AS T5 ON  `T4`.`historial_clinico_id_historial` =  `T5`.`historial_clinico_id_historial`
					  WHERE fecha_exp BETWEEN '$fechaI' AND '$fechaF' AND clasificacion_exa='$clasificacion' AND tipo_clasificacion='$tipo' ";
			 
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Historiales Clinicos del $fechaIni al $fechaFin de Examenes $clasificacion de Tipo $tipo";
		}
		else if($clasificacion!="" && $tipo==""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT DISTINCT 	`T1`.`tipo_clasificacion` , `T1`.`nom_empresa` ,
										`T1`.`id_historial` , `T1`.`puesto_realizar` , `T1`.`nom_empleado` , `T1`.`escolaridad` , 
										`T4`.`peso_kg` , `T4`.`talla_mts` ,  `T4`.`pres_arterial` , `T4`.`imc` ,
										`T2`.`lentes` ,  `T2`.`ojo_der_vision` , `T2`.`ojo_izq_vision` , `T2`.`membrana_der` , 
										`T2`.`membrana_izq` ,  `T2`.`porciento_hbc`, `T5`.`vdrl`, `T5`.`bh`, `T5`.`glicemia`,
										`T5`.`hiv`, `T5`.`tg`, `T5`.`colesterol`, `T5`.`tipo_sanguineo`,
										`T5`.`diag_laboratorio`, `T5`.`alcoholimetro`, `T5`.`espirometria`,
										`T5`.`rx_torax`, `T5`.`col_lumbrosaca`, `T5`.`diagnostico`
					  FROM  `historial_clinico` AS T1
					  JOIN  `aspectos_grales_1` AS T2 ON  `T1`.`id_historial` =  `T2`.`historial_clinico_id_historial` 
					  JOIN  `aspectos_grales_2` AS T3 ON  `T2`.`historial_clinico_id_historial` =  `T3`.`historial_clinico_id_historial` 
					  JOIN  `antecedentes_fam` AS T4 ON  `T3`.`historial_clinico_id_historial` =  `T4`.`historial_clinico_id_historial` 
					  JOIN  `laboratorio` AS T5 ON  `T4`.`historial_clinico_id_historial` =  `T5`.`historial_clinico_id_historial`
					  WHERE fecha_exp BETWEEN '$fechaI' AND '$fechaF' AND clasificacion_exa='$clasificacion' ";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Historiales Clinicos Registradas del $fechaIni al $fechaFin de Examenes $clasificacion";
		}
		else if($clasificacion=="" && $tipo!=""){
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT DISTINCT 	`T1`.`tipo_clasificacion` , `T1`.`nom_empresa` ,
										`T1`.`id_historial` , `T1`.`puesto_realizar` , `T1`.`nom_empleado` , `T1`.`escolaridad` , 
										`T4`.`peso_kg` , `T4`.`talla_mts` ,  `T4`.`pres_arterial` , `T4`.`imc` ,
										`T2`.`lentes` ,  `T2`.`ojo_der_vision` , `T2`.`ojo_izq_vision` , `T2`.`membrana_der` , 
										`T2`.`membrana_izq` ,  `T2`.`porciento_hbc`, `T5`.`vdrl`, `T5`.`bh`, `T5`.`glicemia`,
										`T5`.`hiv`, `T5`.`tg`, `T5`.`colesterol`, `T5`.`tipo_sanguineo`,
										`T5`.`diag_laboratorio`, `T5`.`alcoholimetro`, `T5`.`espirometria`,
										`T5`.`rx_torax`, `T5`.`col_lumbrosaca`, `T5`.`diagnostico`
					  FROM  `historial_clinico` AS T1
					  JOIN  `aspectos_grales_1` AS T2 ON  `T1`.`id_historial` =  `T2`.`historial_clinico_id_historial` 
					  JOIN  `aspectos_grales_2` AS T3 ON  `T2`.`historial_clinico_id_historial` =  `T3`.`historial_clinico_id_historial` 
					  JOIN  `antecedentes_fam` AS T4 ON  `T3`.`historial_clinico_id_historial` =  `T4`.`historial_clinico_id_historial` 
					  JOIN  `laboratorio` AS T5 ON  `T4`.`historial_clinico_id_historial` =  `T5`.`historial_clinico_id_historial`
					  WHERE fecha_exp BETWEEN '$fechaI' AND '$fechaF'  AND tipo_clasificacion='$tipo' ";
			
			 //Titulo para mostrare en el guarda Reporte que contiene el archivo de excel
			$titulo = "Historiales Clinicos Registradas del $fechaIni al $fechaFin de los Examenes de Tipo $tipo";
		}
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($sql_stm);
		
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
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
						<td align="left" valign="baseline" colspan="4"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="5">&nbsp;</td>
						<td valign="baseline" colspan="10">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="19" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="19">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="19">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="19" align="center" class="titulo_tabla"><?php echo "$titulo"?></td>
					</tr>
					<tr>
						<td colspan="19">&nbsp;</td>
					</tr>			
					<tr>				
						<td class='nombres_columnas' align='center'>CLAVE HISTORIAL</td>
						<td class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</td>
				        <td class='nombres_columnas' align='center'>PUESTO</td>
        				<td class='nombres_columnas' align='center'>ESCOLARIDAD</td>
						<td class='nombres_columnas' align='center'>PESO (KG)</td>
						<td class='nombres_columnas' align='center'>TALLA (MTS.)</td>
        				<td class='nombres_columnas' align='center'>PRESION ARTERIAL</td>
        				<td class='nombres_columnas' align='center'>IMC</td>
        				<td class='nombres_columnas' align='center'>LENTES</td>
        				<td class='nombres_columnas' align='center'>VISI&Oacute;N DER.</td>
						<td class='nombres_columnas' align='center'>VISI&Oacute;N IZQ.</td>
						<td class='nombres_columnas' align='center'>MEMBRANA DER.</td>
						<td class='nombres_columnas' align='center'>MEMBRANA IZQ.</td>
						<td class='nombres_columnas' align='center'>HBC %</td>
        				<td class='nombres_columnas' align='center'>VDRL</td>					
        				<td class='nombres_columnas' align='center'>B.H.</td>					
        				<td class='nombres_columnas' align='center'>GLICEMIA</td>					
        				<td class='nombres_columnas' align='center'>HIV</td>					
        				<td class='nombres_columnas' align='center'>TG</td>																							
        				<td class='nombres_columnas' align='center'>COLESTEROL</td>
						<td class='nombres_columnas' align='center'>TIPO SANGUINEO</td>
						<td class='nombres_columnas' align='center'>DIAG. LABORATORIO</td>
						<td class='nombres_columnas' align='center'>ALCOHOLIMETRO</td>
						<td class='nombres_columnas' align='center'>ESPIROMETRIA</td>
						<td class='nombres_columnas' align='center'>RX DE T&Oacute;RAX</td>
						<td class='nombres_columnas' align='center'>COL. LUMBOSACRA</td>
						<td class='nombres_columnas' align='center'>DIAGN&Oacute;STICOS</td>					
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				if($datos["tipo_clasificacion"]=='INTERNO')
					$empresa="CONCRETO LANZADO DE FRESNILLO MARCA";
				else
					$empresa=obtenerDato("bd_clinica","catalogo_empresas", "nom_empresa", "nom_empresa", $datos['nom_empresa']);?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["id_historial"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nom_empleado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["puesto_realizar"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["escolaridad"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["peso_kg"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["talla_mts"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["pres_arterial"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["imc"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["lentes"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["ojo_der_vision"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["ojo_izq_vision"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["membrana_der"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["membrana_izq"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["porciento_hbc"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["vdrl"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["bh"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["glicemia"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["hiv"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["tg"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["colesterol"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["tipo_sanguineo"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["diag_laboratorio"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["alcoholimetro"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["espirometria"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["rx_torax"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["col_lumbrosaca"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["diagnostico"]; ?></td>
					</tr><?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			<!--<tr> 
				<td>&nbsp;</td>
				<td>&nbsp;</td>	
				<td>&nbsp;</td>					
				<td colspan="13" align="center" class="titulo_tabla"><?php // echo "Detalle de los Historiales Cl&iacute;nicos"?></td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class='nombres_columnas' align='center'>N�</td>
				<td class='nombres_columnas' align='center'>ID HISTORIAL</td>
				<td class='nombres_columnas' align='center'>ID SOLICITUD</td>
				<td class='nombres_columnas' align='center'>FECHA EXPEDIENTE</td>
				<td class='nombres_columnas' align='center'>NOMBRE EMPRESA</td>
				<td class='nombres_columnas' align='center'>NOMBRE EMPLEADO</td>						
				<td class='nombres_columnas' align='center'>SEXO</td>						
				<td class='nombres_columnas' align='center'>EDAD</td>						
				<td class='nombres_columnas' align='center'>NOMBRE EXAMEN</td>						
				<td class='nombres_columnas' align='center'>COSTO EXAMEN</td>						
				<td class='nombres_columnas' align='center'>FORMA PAGO</td>						
				<td class='nombres_columnas' align='center'>COSTO TOTAL</td>											
			</tr>--><?php 
				//Declaramos el color de los renglones; empieza en gris
				/*$nom_claseInt = "renglon_gris";
				//Declaramos contador interno
				$contInterno = 1;
				//Consulta que permite verificar las anomalias registradas 
				$stm_sqlAn = "SELECT DISTINCT historial_clinico_id_historial, exa_ext_realizados.solicitud_examen_num_solicitud, fecha_exp, nom_empresa, nom_empleado_ext, sexo, 
					edad, exa_ext_realizados.empleados_externos_id_registro, id_registro, nom_examen, costo_exa, forma_pago, costo_total FROM historial_externos 
					JOIN historial_clinico ON id_historial = historial_clinico_id_historial 
					JOIN empleados_externos ON id_registro = empleados_externos_id_registro
					JOIN exa_ext_realizados ON  exa_ext_realizados.solicitud_examen_num_solicitud = empleados_externos.solicitud_examen_num_solicitud
					JOIN catalogo_examen ON id_examen = catalogo_examen_id_examen
					WHERE  fecha_exp BETWEEN '$fechaI' AND '$fechaF' AND
					clasificacion_exa = 'EMPRESA EXTERNA' AND tipo_clasificacion = 'EXTERNO' AND exa_ext_realizados.empleados_externos_id_registro = id_registro 
					ORDER BY solicitud_examen_num_solicitud";
				

				//Ejecutamos la sentencia Previamente creada
				$rs2=mysql_query($stm_sqlAn);
				//Comprobamos si exisitieron resultados
				if($arrAn=mysql_fetch_array($rs2)){

					do{
					//Variable con que contendra los medicamentos suministrados de acuerdo a las consultas realizadas
					$examen="";
					$costo = "";
					
						$examen.="$arrAn[nom_examen]".", ".$examen;
						$costo.="$arrAn[costo_exa]".", ".$costo;
						?>
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $contInterno;?></td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['historial_clinico_id_historial'];?></td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['solicitud_examen_num_solicitud'];?></td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo modFecha($arrAn["fecha_exp"],1); ?></td>							
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['nom_empresa'];?></td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['nom_empleado_ext'];?></td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['sexo'];?></td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['edad'];?></td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['nom_examen'];?></td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['costo_exa'];?></td>							
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['forma_pago'];?></td>
							<td class='<?php echo $nom_claseInt;?>' align='center'><?php echo $arrAn['costo_total'];?></td>
						</tr><?php 
						//Incrementamos el contador interno
						$contInterno++;
						//Verificamos que color corresponde al Renglon
						if($contInterno%2==0)
							$nom_claseInt = "renglon_blanco";
						else
							$nom_claseInt = "renglon_gris";
					}while($arrAn=mysql_fetch_array($rs2));
					$examen=substr($examen,0,(strlen($examen)-2));
				}*/
			?>	
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}
	
	
	
		
	//Esta funcion exporta el REPORTE de Plan de Contingencia que ya se encuentran Realizados � Ejecutados
	function consultarActividadesSemanalesUSO($hdn_consulta, $hdn_consulta1, $hdn_consulta2, $hdn_nomReporte, $hdn_msg){
		
		include_once("../../includes/func_fechas.php");
		
		//Esribir datos de la pagina en excel
		//header("Content-type: application/vnd.ms-excel");
		//header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		//$rs= mysql_fetch_array($hdn_consulta);
		$rs = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
		//if($datos=mysql_fetch_array($rs)){
		//if($datos = mysql_fetch_array(mysql_query($rs))){
		
		
		echo "CONSULTA".$hdn_consulta;
		//if($datos = mysql_fetch_array($rs)){

			?><head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
					border-top-width: medium; border-right-width: thin;
					border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
					solid; border-left-style: none; 
					border-top-color: #000000; border-bottom-color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					.Estilo1 {color: #FFFFFF;	font-weight: bold;}
					-->
				</style>
			</head>											
			<body>
			<div id="tabla">				
				<table width="1100">					
					<tr>
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" /></td>
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="5">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="10" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="10">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="10" align="center" class="titulo_tabla"><?php echo $hdn_msg;?></td>
					</tr>
					<tr>
						<td colspan="10">&nbsp;</td>
					</tr>								
					<tr>
						<td colspan='8' class='nombres_columnas' align='center'>REPORTE SEMANAL ACTIVIDADES</td>
					</tr>
				<tr>
					<td class='nombres_columnas' align='center'>ACTIVIDAD</td>";<?php 
				$fechaActual = 	$hdn_consulta1;
				do{	
					if(obtenerNombreDia($fechaActual)!="Domingo"){
						echo "<td class='nombres_columnas' align='center'>".obtenerNombreDia($fechaActual)."<br>".modFecha($fechaActual,1)."</td>";																	
						$fechas[]=$fechaActual;				
					}
					$fechaActual = sumarDiasFecha($fechaActual,1);

				}while($fechaActual!=$fechaFin);
				if(obtenerNombreDia($fechaActual)!="Domingo"){
					echo "<td class='nombres_columnas' align='center'>".obtenerNombreDia($fechaActual)."<br>".modFecha($fechaActual,1)."</td>";																	
					$fechas[]=$fechaActual;	
				}
				echo "</tr>";?>
						
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cantDias = restarFechas($fechaFin, $fechaIni);	
			$ctrl = 0;
			do{
					echo "<tr>";
					switch($cont){		
						case 1:
							echo"<td class='nombres_columnas' align='center'>CONSULTAS INTERNAS</td>";
						break;
						case 2:
							echo"<td class='nombres_columnas' align='center'>CONSULTAS EXTERNAS</td>";
						break;
						case 3:
							echo"<td class='nombres_columnas' align='center'>EX&Aacute;MEN PERIODICOS</td>";
						break;
						case 4:
							echo"<td class='nombres_columnas' align='center'>EX&Aacute;MEN INGRESOS</td>";
						break;
						case 5:
							echo"<td class='nombres_columnas' align='center'>EX&Aacute;MEN EMPRESAS EXTERNAS</td>";
						break;												
					}
					foreach($fechas as $ind => $value){	
						switch($cont){
							case 1:
								$sql_stm = "SELECT COUNT(id_bit_consultas) AS cant, fecha FROM bitacora_consultas WHERE consulta = 'INTERNA' AND fecha ='$value'";
							break;
							case 2:
								$sql_stm = "SELECT COUNT(id_bit_consultas) AS cant, fecha FROM bitacora_consultas WHERE consulta = 'EXTERNA' AND fecha ='$value'";														
							break;
							case 3:
								$sql_stm = "SELECT COUNT(clasificacion_exa) AS cant FROM historial_clinico WHERE clasificacion_exa = 'PERIODICO' AND fecha_exp = '$value'";
							break;
							case 4:
								$sql_stm = "SELECT COUNT(clasificacion_exa) AS cant FROM historial_clinico WHERE clasificacion_exa = 'INGRESO' AND fecha_exp = '$value'";
							break;
							case 5:
								$sql_stm = "SELECT COUNT(clasificacion_exa) AS cant FROM historial_clinico WHERE clasificacion_exa = 'EMPRESA EXTERNA' AND fecha_exp = '$value'";
							break;												
						}
						$datos = mysql_fetch_array(mysql_query($sql_stm));
						$cantidad = $datos["cant"];	
						echo "<td class='$nom_clase' align='center'>$cantidad</td>";
					}
					echo "</tr>";?>
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
			</body>
			<?php	//}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion consultarRepPermisoFlama
	
	
	
	
		
	//Esta funcion exporta a Excel el Reporte de consultas Medicas realizadas
	function mostrarRepIncapacidadesEmpleados($fechaIni,$fechaFin,$tipo){
		
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteIncapacidadesEmpleados.xls");		
		//Realizar la conexion a la BD de la Clinica
		//Convertimos las fechas en formato aaa-mm-dd ya que son como se guardan en la BD.
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		$titulo = "";
		//Volvemos a convertir las fechas definidas anteriormente para colocar el titulo dentro d ela tabla que mostrara los resultados 
		$fechaIni=modFecha($fechaI,1);
		$fechaFin=modFecha($fechaF,1);
		
		$conn=conecta("bd_recursos");
		
		//Sentencia SQL para extraer a los Trabajadores del �rea Seleccionada		
		if($tipo==""){
			$stm_sql="SELECT DISTINCT rfc_empleado, fecha_ingreso, fecha_checada, hora_checada, checadas.estado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre 
			FROM empleados JOIN checadas ON rfc_empleado = empleados_rfc_empleado WHERE fecha_checada BETWEEN '$fechaI' AND '$fechaF' 
			AND rfc_empleado = empleados_rfc_empleado AND SUBSTRING(checadas.estado,1,2)!='A' AND SUBSTRING(checadas.estado,1,2)!='F' 
			AND SUBSTRING(checadas.estado,1,2)!='d' AND SUBSTRING(checadas.estado,1,2)!='V' AND SUBSTRING(checadas.estado,1,2)!='r' 
			AND SUBSTRING(checadas.estado,1,3)!='F/J' AND SUBSTRING(checadas.estado,1,2)!='P' AND SUBSTRING(checadas.estado,1,3)!='P/G' 
			AND SUBSTRING(checadas.estado,1,2)!='D' AND SUBSTRING(checadas.estado,1,2)!='R' AND SUBSTRING(checadas.estado,1,2)!='E' AND checadas.estado NOT LIKE  'SALIDA%'";
			
			//Titulo de la consulta correspondiente a la eleccion de informacion de acuerdo a un  rango de fechas
			$titulo = "Reporte de Incapacidades por Empleado del $fechaIni al $fechaFin";

		}
		else if($tipo=="RT"){
			$stm_sql="SELECT DISTINCT rfc_empleado, fecha_ingreso, fecha_checada, hora_checada, checadas.estado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre 
			FROM empleados  JOIN checadas ON rfc_empleado = empleados_rfc_empleado WHERE fecha_checada BETWEEN '$fechaI' AND '$fechaF' AND checadas.estado='RT'
			 AND rfc_empleado = empleados_rfc_empleado";
			 
			 //Titulo de la consulta correspondiente a la eleccion de informacion de acuerdo a un  rango de fechas
			$titulo = "Reporte de Incapacidades por Accidentes de Trabajo  del $fechaIni al $fechaFin";
			 
		}
		else if($tipo=="E"){
			$stm_sql="SELECT rfc_empleado, fecha_ingreso, fecha_checada, hora_checada, checadas.estado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre 
			FROM empleados  JOIN checadas ON rfc_empleado = empleados_rfc_empleado WHERE fecha_checada BETWEEN '$fechaI' AND '$fechaF' AND checadas.estado='E'
			AND rfc_empleado = empleados_rfc_empleado";
			
			 //Titulo de la consulta correspondiente a la eleccion de informacion de acuerdo a un  rango de fechas
			$titulo = "Reporte de Incapacidades por Enfermedad General del $fechaIni al $fechaFin";
		}
		else if($tipo=="T"){
			$stm_sql="SELECT rfc_empleado, fecha_ingreso, fecha_checada, hora_checada, checadas.estado, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre 
			FROM empleados  JOIN checadas ON rfc_empleado = empleados_rfc_empleado WHERE fecha_checada BETWEEN '$fechaI' AND '$fechaF' AND checadas.estado='T'
			AND rfc_empleado = empleados_rfc_empleado";
			
			//Titulo de la consulta correspondiente a la eleccion de informacion de acuerdo a un  rango de fechas
			$titulo = "Reporte de Incapacidades en Trayecto del $fechaIni al $fechaFin";
		}
				
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
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
						<td align="left" valign="baseline" colspan="1"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="1">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="5" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="5" align="center" class="titulo_tabla"><?php echo "$titulo"?></td>
					</tr>
					<tr>
						<td colspan="5">&nbsp;</td>
					</tr>			
					<tr>				
						<td class='nombres_columnas' align='center'>RFC TRABAJADOR</td>
						<td class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</td>
						<td class='nombres_columnas' align='center'>FECHA INGRESO DEL TRABAJADOR</td>
						<td class='nombres_columnas' align='center'>ESTADO</td>
        				<td class='nombres_columnas' align='center'>FECHA DE REGISTRO</td>					
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["rfc_empleado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nombre"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha_ingreso"],1); ?></td><?php 
						
						if($datos['estado']=='RT'){
							echo"<td  class='$nom_clase' align='center'>INCAPACIDAD ACCIDENTE TRABAJO</td>";
						}
						else if($datos['estado']=='E'){
							echo"<td  class='$nom_clase' align='center'>INCAPACIDAD POR ENFERMEDAD GENERAL </td>";								
						} 							
						else if($datos['estado']=='T'){
							echo"<td  class='$nom_clase' align='center'>INCAPACIDAD EN TRAYECTO </td>";								
						}?>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha_checada"],1); ?></td>
					</tr><?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}
	
	//Funcion que guarda directamente
	function guardarRepActSemanalesUSO(){
		header("Content-type: application/vnd.ms-excel; name='excel'");
		header("Content-Disposition: attachment; filename=ReporteSemanal.xls");
		header("Content-Disposition: filename=ReporteSemanal.xls");
		header("Pragma: no-cache");
		header("Expires: 0");
		?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: 
				solid; border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #9BBB59; font-weight:bold; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.titulo_etiqueta {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				-->
			</style>
		</head>	
		<table>
			<tr>
				<td align="left" valign="baseline" colspan="3"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="150" height="70" 
				align="absbottom" /></td>
				<td colspan="2">&nbsp;</td>
				<td valign="baseline" colspan="3">
					<div align="right"><span class="texto_encabezado">
						<strong></strong><br><em></em>
					</span></div>
				</td>
			</tr>											
			<tr>
				<td colspan="8" align="center" class="borde_linea" width="100%">
					<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
				</td>
			</tr>					
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="8">&nbsp;</td>
			</tr>
		</table>
		<?php
		echo $_POST['hdn_divExpRepActSemanales'];
	}
	
	

	//Esta funcion exporta a Excel el Reporte de consultas Medicas realizadas
	function mostrarRepResultadosExamenMedico($fechaIni,$fechaFin){
		
		//Manejo de fechas
		include_once("../../includes/func_fechas.php");
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=RepResultadosExa.xls");		
		//Realizar la conexion a la BD de la Clinica
		//Convertimos las fechas en formato aaa-mm-dd ya que son como se guardan en la BD.
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		$titulo = "";
		//Volvemos a convertir las fechas definidas anteriormente para colocar el titulo dentro d ela tabla que mostrara los resultados 
		$fechaIni=modFecha($fechaI,1);
		$fechaFin=modFecha($fechaF,1);
		
		$conn=conecta("bd_clinica");
		
			//Sentencia SQL para extraer a los Trabajadores del �rea Seleccionada		
			$stm_sql="SELECT historial_clinico_id_historial, nom_empleado, fecha_exp, resultado, recomendacion, imss, tipo_clasificacion 
			FROM historial_clinico JOIN resultados_historiales ON historial_clinico.id_historial = resultados_historiales.historial_clinico_id_historial 
			WHERE fecha_exp BETWEEN  '$fechaI' AND '$fechaF' AND tipo_clasificacion = 'INTERNO'  ORDER BY fecha_exp";
				
			//Titulo de la consulta correspondiente a la eleccion de informacion de acuerdo a un  rango de fechas
			$titulo = "Reporte de Resultados de los Ex&aacute;menes M&eacute;dicos del $fechaIni al $fechaFin";
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs_datos)){
			//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
			<head>
				<style>					
					<!--
					body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
					.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; border-top-width: medium; border-right-width: thin;
										border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; border-left-style: none; 
										border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tebla*/
					.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;}
					/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tebla*/
					.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;} 
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
						<td align="left" valign="baseline" colspan="2"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
						<td colspan="1">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong> </strong><br><em> </em>
							</span></div><br>
						</td>
					</tr>											
					<tr>
						<td colspan="6" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="6" align="center" class="titulo_tabla"><?php echo "$titulo"?></td>
					</tr>
					<tr>
						<td colspan="6">&nbsp;</td>
					</tr>			
					<tr>				
						<td class='nombres_columnas' align='center'>FECHA EXAMEN MEDICO</td>
						<td class='nombres_columnas' align='center'>CLAVE HISTORIAL</td>
						<td class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</td>
						<td class='nombres_columnas' align='center'>RESULTADO</td>
        				<td class='nombres_columnas' align='center'>RECOMENDACION</td>					
        				<td class='nombres_columnas' align='center'>IMSS</td>					
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos["fecha_exp"],1); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["historial_clinico_id_historial"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["nom_empleado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["resultado"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["recomendacion"]; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos["imss"]; ?></td><?php ?>
					</tr><?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";		
			}while($datos=mysql_fetch_array($rs_datos)); ?>
			</table>
			</div>
			</body>
<?php	}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}
	
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
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
				border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
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
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 13px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin;
				border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none; border-bottom-style: solid; 
				border-left-style: none; 
				border-top-color: #000000; border-bottom-color: #000000; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 11px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tama�o,tipo de letra,color de fondo que contendra datos  de una tabla*/
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