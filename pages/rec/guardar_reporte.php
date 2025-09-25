<?php 
	/**
	  * Nombre del Módulo: Recursos Humanos                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 18/Abril/2011                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas y reportes generados.
	  **/
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			include("../../includes/func_fechas.php");			
	/**   Código en: pages\rec\guardar_reporte.php                                   
      **/
	
	  			
	if(isset($_POST['hdn_consulta']) || isset($_POST['hdn_msg'])){
		
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);		
		
		switch($hdn_origen){
			case "reporteAsistencia":
				guardarRepAsistencia($hdn_consulta,$hdn_nomReporte,$hdn_msg,$hdn_fechaIni, $hdn_fechaFin, $hdn_diferencia);
			break;	
			case "reporteIncapacidades":
				guardarRepIncapacidades($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_fecha,$hdn_fechaIni, $hdn_fechaFin);
			break;	
			case "reporteReclutamiento":
				guardarRepReclutamiento($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;	
			case "reporteAusentismo":
				guardarRepAusentismo($hdn_consulta,$hdn_nomReporte,$hdn_msg,$hdn_fecha, $hdn_fechaIni, $hdn_fechaFin);
			break;
			case "reporteAltasBajas":
				guardarRepAltasBajas($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_consultaBajas);
			break;	
			case "reportePrestamos":
				guardarRepPrestamos($hdn_consulta,$hdn_nomReporte,$hdn_msg);									
			break;
			case "reporteCapacitaciones":
				guardarRepCapacitaciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);								
			break;
			case "reporteNomina":
				guardarRepNomina($hdn_consulta,$hdn_nomReporte,$hdn_msg);								
			break;
			case "reportePagoSS":
				guardarRepPagoSS($hdn_consulta,$hdn_nomReporte,$hdn_msg);								
			break;	
			case "reporteKardex":
				guardarRepKardex($hdn_consulta,$hdn_consultaKardex,$hdn_nomReporte,$hdn_msg, $hdn_cantidad);							
			break;
			case "reporteHistorico":
				guardarRepHistorico($hdn_consulta,$hdn_nomReporte,$hdn_msg);							
			break;	
			case "reporteNominaBancaria":
				guardarRepNomBancaria($hdn_msg, $hdn_nomReporte);		
			break;
			case "exportarNomina":
				exportarNominaInterna($hdn_msg,$hdn_idNomina,$hdn_area);
			break;
			case "reporteAbonos":
				exportarReporteAbonos($hdn_idPrestamo);
			break;			
			
		}//Cierre switch($hdn_origen)	
		
	}//Cierre if(isset($_POST['hdn_consulta']) || isset($_POST['hdn_msg']))
	
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
	
	if(isset($_POST["hdn_patron"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		exportarEmpleados($hdn_patron);
	}
	
	if(isset($_GET["exp_nom"]))
		exportarXML();
	if(isset($_GET["kardeDetalle"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		exportarKardeDetalle($_GET["fechaI"],$_GET["fechaF"],$_GET["tipo"],$_GET["criterio"],$_GET["dias"]);
	}//Cierre if(isset($_GET["kardeDetalle"]))
	
	if(isset($_GET["kardeDetalleChecador"])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		exportarKardeDetalleChecador($_GET["fechaI"],$_GET["fechaF"],$_GET["tipo"],$_GET["criterio"]);
	}//Cierre if(isset($_GET["kardeDetalleChecador"]))
	
	//Esta funcion exporta el catalogo de Empleados segun los datos seleccionados por el usuario
	function exportarEmpleados($patron){
		$sql="SELECT ";
		//array con los nombres de las columnas
		$nomCols=array();
		//array con los nombres de los campos segun la BD
		$nomCampos=array();
		foreach($_POST as $ind => $value){
			if(substr($ind,0,7)=="ckb_col"){
				switch($value){
					case "nombreCompleto":
						//Concatenar a la sentencia
						$sql.="nombre, ape_pat, ape_mat, ";
						//Agregar los titulos de las columnas
						$nomCols[]="NOMBRE";
						$nomCols[]="APELLIDO PATERNO";
						$nomCols[]="APELLIDO MATERNO";
						//Obtener el nombre de los campos
						$nomCampos[]="nombre";
						$nomCampos[]="ape_pat";
						$nomCampos[]="ape_mat";
					break;
					case "antiguedad":
						//Agregar los titulos de las columnas
						$nomCols[]="ANTIG&Uuml;EDAD";
						$nomCampos[]="antiguedad";
					break;
					case "direccion":
						//Concatenar a la sentencia
						$sql.="calle, num_ext, num_int, colonia, cp, ";
						//Agregar los titulos de las columnas
						$nomCols[]="CALLE";
						$nomCols[]="N&Uacute;MERO EXTERIOR";
						$nomCols[]="N&Uacute;MERO INTERIOR";
						$nomCols[]="COLONIA";
						$nomCols[]="C.P.";
						//Obtener el nombre de los campos
						$nomCampos[]="calle";
						$nomCampos[]="num_ext";
						$nomCampos[]="num_int";
						$nomCampos[]="colonia";
						$nomCampos[]="cp";
					break;
					case "fechaNacimiento":
						//Agregar los titulos de las columnas
						$nomCols[]="FECHA DE NACIMIENTO";
						$nomCampos[]="fechaNacimiento";
					break;
					case "contactoAccidente":
						//Concatenar a la sentencia
						$sql.="CONCAT ('Nombre: ',nom_accidente,' <br>Telefono:',tel_accidente,' <br>Celular:',cel_accidente) AS contactoAccidente, ";
						//Agregar los titulos de las columnas
						$nomCols[]="CONTACTO POR ACCIDENTE";
						//Obtener el nombre de los campos
						$nomCampos[]="contactoAccidente";
					break;
					default:
						//Concatenar a la sentencia
						$sql.="$value, ";
						//Obtener el nombre de los campos
						$nomCampos[]="$value";
						//Ciclo para obtener los titulos de las Columnas
						switch($value){
							case "rfc_empleado":
								$nomCols[]="RFC";
							break;
							case "curp":
								$nomCols[]="CURP";
							break;
							case "id_empleados_empresa":
								$nomCols[]="ID EMPRESA";
							break;
							case "id_empleados_area":
								$nomCols[]="ID &Aacute;REA";
							break;
							case "sueldo_diario":
								$nomCols[]="SUELDO DIARIO";
							break;
							case "tipo_sangre":
								$nomCols[]="TIPO SANGRE";
							break;
							case "no_ss":
								$nomCols[]="NO. SEGURO SOCIAL";
							break;
							case "fecha_ingreso":
								$nomCols[]="FECHA INGRESO";
							break;
							case "puesto":
								$nomCols[]="PUESTO";
							break;
							case "no_cta":
								$nomCols[]="NO. CUENTA";
							break;
							case "area":
								$nomCols[]="&Aacute;REA";
							break;
							case "jornada":
								$nomCols[]="JORNADA";
							break;
							case "oc_esp":
								$nomCols[]="OCUPACI&Oacute;N ESPEC&Iacute;FICA";
							break;
							case "nivel_estudio":
								$nomCols[]="NIVEL DE ESTUDIO";
							break;
							case "titulo":
								$nomCols[]="T&Iacute;TULO";
							break;
							case "carrera":
								$nomCols[]="CARRERA";
							break;
							case "tipo_escuela":
								$nomCols[]="TIPO DE ESCUELA";
							break;
							case "localidad":
								$nomCols[]="LOCALIDAD";
							break;
							case "estado":
								$nomCols[]="ESTADO";
							break;
							case "pais":
								$nomCols[]="PA&Iacute;S";
							break;
							case "nacionalidad":
								$nomCols[]="NACIONALIDAD";
							break;
							case "telefono":
								$nomCols[]="TEL&Eacute;FONO";
							break;
							case "edo_civil":
								$nomCols[]="ESTADO CIVIL";
							break;
							case "discapacidad":
								$nomCols[]="DISCAPACIDAD";
							break;
							case "hijos_dep_eco":
								$nomCols[]="DEPENDIENTES ECON&Oacute;MICOS";
							break;
							case "contactoAccidente":
								$nomCols[]="CONTACTO POR ACCIDENTE";
							break;
							case "observaciones":
								$nomCols[]="OBSERVACIONES";
							break;
							case "lugar_nacimiento":
								$nomCols[]="LUGAR DE NACIMIENTO";
							break;
						}
					break;
				}
			}
		}
		//Quitar la ultima ","
		$sql=substr($sql,0,(strlen($sql)-2));
		//Concatenarle la tabla de busqueda
		$sql.=" FROM empleados";
		//Verificamos bajo que patron se esta pidiendo hacer la consulta
		if ($patron==1){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$sql.=" WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[hdn_nombre]' AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado <em>".$_POST["hdn_nombre"]."</em>";
		}
		if ($patron==2){
			$sql.=" WHERE estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de Todos los Empleados";
		}
		if ($patron==3){
			//Creamos la sentencia SQL para mostrar los datos de los empleados que estan en el área que llega via POST
			$sql.=" WHERE area='$_POST[hdn_area]' AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de los Empleados del &Aacute;rea <em><u>".$_POST["hdn_area"]."</u></em>";
		}
		if ($patron==4){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$sql.=" WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[hdn_nombre]' AND estado_actual = 'BAJA'";
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado Baja <em>".$_POST["hdn_nombre"]."</em>";
		}
		if ($patron==5){
			$sql.=" WHERE estado_actual = 'BAJA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de Todos los Empleados Baja";
		}
		$cantCols=count($nomCols);
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=listaTrabajadores.xls");	
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($sql);
		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="<?php echo $cantCols-5;?>">&nbsp;</td>
						<td valign="baseline" colspan="3">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="<?php echo $cantCols?>" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="<?php echo $cantCols?>">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="<?php echo $cantCols?>">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="<?php echo $cantCols?>" align="center" class="titulo_tabla"><?php echo $titulo; ?></td>
					</tr>
					<tr>
						<td colspan="<?php echo $cantCols?>">&nbsp;</td>
					</tr>			
					<tr>
						<?php
							//Dibujar las columnas con sus respectivos nombres
							foreach($nomCols as $ind => $value){
								echo "<td align='center' class='nombres_columnas'>$value</td>";
							}
						?>
      				</tr>
			<?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{
					echo "<tr>";
					foreach($nomCampos as $ind => $value){
						//Antigüedad
						if($value=="antiguedad")
							echo "<td class='$nom_clase' align='center'>".round((restarFechas($datos["fecha_ingreso"],date("Y-m-d"))/365),2)." a&ntilde;os</td>";
						//Fecha de Nacimiento
						elseif($value=="fechaNacimiento")
							echo "<td class='$nom_clase' align='center'>".modFecha(calcularFecha(substr($datos["rfc_empleado"],4,6)),2)."</td>";
						//Maximo nivel de Estudios
						elseif($value=="nivel_estudio"){
							switch($datos[$value]){
								case 1:
									$datos[$value]="PRIMARIA";
									break;
								case 2:
									$datos[$value]="SECUNDARIA";
									break;
								case 3:
									$datos[$value]="BACHILLERATO";
									break;
								case 4:
									$datos[$value]="CARRERA T&Eacute;CNICA";
									break;
								case 5:
									$datos[$value]="LICENCIATURA";
									break;
								case 6:
									$datos[$value]="ESPECIALIDAD";
									break;
								case 7:
									$datos[$value]="MAESTR&Iacute;A";
									break;
								case 8:
									$datos[$value]="DOCTORADO";
									break;
							}
							echo "<td align='center' class='$nom_clase'>$datos[$value]</td>";
						}
						elseif($value=="titulo"){
							switch($datos[$value]){
								case 1:
									$datos[$value]="T&Iacute;TULO";
									break;
								case 2:
									$datos[$value]="CERTIFICADO";
									break;
								case 3:
									$datos[$value]="DIPLOMA";
									break;
								case 4:
									$datos[$value]="OTRO";
									break;
							}
							echo "<td align='center' class='$nom_clase'>$datos[$value]</td>";
						}
						elseif($value=="tipo_escuela"){
							switch($datos[$value]){
								case 1:
									$datos[$value]="P&Uacute;BLICA";
									break;
								case 2:
									$datos[$value]="PRIVADA";
									break;
							}
							echo "<td align='center' class='$nom_clase'>$datos[$value]</td>";
						}
						else{
							//Si el estado civil es CASADO o UNION LIBRE, sumar 1 a los dependientes economicos
							if($value=="edo_civil"){
								if(($datos[$value]=="CASADO" || $datos[$value]=="UNIÓN LIBRE") && isset($datos["hijos_dep_eco"]))
									$datos["hijos_dep_eco"]++;
									
							}
							echo "<td align='center' class='$nom_clase'>$datos[$value]</td>";
						}
					}
					echo "</tr>";
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
			<?php	
			}
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}
	
	//Esta funcion exporte el REPORTE ASISTENCIA a un archivo de excel
	function guardarRepAsistencia($hdn_consulta,$hdn_nomReporte,$hdn_msg ,$hdn_fechaIni, $hdn_fechaFin, $hdn_diferencia){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="1">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>TOTAL ASISTENCIAS</td>
						<td align='center' class='nombres_columnas'>ASISTENCIAS A CUMPLIR</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>																																											
      				</tr>
			<?php
				$nom_clase = "renglon_gris";
				$cont = 1;
				do{	
					//Generamos la consulta que cuenta el total de asistencias del empleado segun rfc
					$stm_sql2 = "SELECT COUNT(estado) AS total_asistencias FROM checadas WHERE empleados_rfc_empleado = '$datos[empleados_rfc_empleado]' 
									AND estado='A' AND fecha_checada>='$hdn_fechaIni' AND fecha_checada<='$hdn_fechaFin' ";
			
					//Ejecutamos la sentencia
					$rs_datos2 = mysql_query($stm_sql2);
					//Guardamos los resultados de la sentencia en el arreglo
					$arrConsulta2 = mysql_fetch_array($rs_datos2);
					
			?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $arrConsulta2['total_asistencias']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $hdn_diferencia; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>
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
			<?php	
			}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepAsistencia($hdn_consulta,$hdn_nomReporte,$hdn_msg ,$hdn_fechaIni, $hdn_fechaFin, $hdn_diferencia)
	
	
	//Esta funcion exporte el REPORTE INCAPACIDADES	 a un archivo de excel
	function guardarRepIncapacidades($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_fecha, $hdn_fechaIni, $hdn_fechaFin){
		
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado resultados	
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="1">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
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
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>INCAPACIDADES</td>
						<td align='center' class='nombres_columnas'>ASISTENCIAS A CUMPLIR</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>																																											
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
			//Generamos la consulta que cuenta el total de asistencias del empleado segun rfc
			$stm_sql2 = "SELECT COUNT(estado) AS incapacidades FROM checadas WHERE empleados_rfc_empleado = '$datos[empleados_rfc_empleado]' 
							AND (checadas.estado='E' OR checadas.estado='RT' OR checadas.estado='T') AND fecha_checada>='$hdn_fechaIni' AND fecha_checada<='$hdn_fechaFin'";
			
			//Ejecutamos la sentencia
			$rs_datos2 = mysql_query($stm_sql2);
			
			//Guardamos los resultados de la consulta en el arreglo
			$arrConsulta2 = mysql_fetch_array($rs_datos2);
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $arrConsulta2['incapacidades']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $hdn_fecha; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>
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
	}//Fin de la Funcion guardarRepIncapacidades($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_fecha, $hdn_fechaIni, $hdn_fechaFin)
	
	
	//Esta funcion exporte el REPORTE RECLUTAMIENTO a un archivo de excel
	function guardarRepReclutamiento($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta contenga datos	
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="3">&nbsp;</td>
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
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>FOLIO ASPIRANTE</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>
						<td align='center' class='nombres_columnas'>ESTADO CIVIL</td>
						<td align='center' class='nombres_columnas'>TEL&Eacute;FONO</td>
						<td align='center' class='nombres_columnas'>EDAD</td>
						<td align='center' class='nombres_columnas'>EXPERIENCIA LABORAL</td>																																										
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				?>
				<tr>		
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['folio_aspirante']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['estado_civil']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['telefono']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['edad']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['experiencia_laboral']; ?></td>
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
	}//Fin de la Funcion guardarRepReclutamiento($hdn_consulta,$hdn_nomReporte,$hdn_msg)
	
	
	//Esta funcion exporte el REPORTE AUSENTISMO a un archivo de excel
	function guardarRepAusentismo($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_fecha, $hdn_fechaIni, $hdn_fechaFin){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta genere resultados	
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="2">
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
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>AUSENCIAS</td>
						<td align='center' class='nombres_columnas'>ASISTENCIAS A CUMPLIR</td>
						<td align='center' class='nombres_columnas'>&Aacute;rea</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>																																								
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
			//Generamos la consulta que cuenta el total de asistencias del empleado segun rfc, se ejecuta esta consulta nuevamente para obtener el estado
			$stm_sql2 = "SELECT COUNT(estado) AS faltas FROM checadas WHERE empleados_rfc_empleado = '$datos[empleados_rfc_empleado]' 
							AND estado='F' AND fecha_checada>='$hdn_fechaIni' AND fecha_checada<='$hdn_fechaFin'";
			//Ejecutamos la sentencia
			$rs_datos2 = mysql_query($stm_sql2);
			//Guardamos los resultados de la consulta en el arreglo
			$arrConsulta2 = mysql_fetch_array($rs_datos2);
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $arrConsulta2['faltas']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $hdn_fecha; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>
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
	}//Fin de la Funcion guardarRepAusentismo($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_fecha, $hdn_fechaIni, $hdn_fechaFin)
	
		
	//Esta funcion exporte el REPORTE ALTAS VS BAJAS a un archivo de excel
	function guardarRepAltasBajas($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_consultaBajas){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
			
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="2">
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
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8" align="center" class="titulo_tabla"> Altas de personal <?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>FECHA INGRESO</td>
						<td align='center' class='nombres_columnas'>&Aacute;REA</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>
						<td align='center' class='nombres_columnas'>OBSERVACIONES</td>																																				
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['rfc_empleado']; ?></td>
					<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_ingreso'],1); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>
					<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['observaciones']; ?></td>
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
			<?php 
			//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
			$rs_datos = mysql_query($hdn_consultaBajas);
			//Verificamos que la consulta genere resultados
			if($datos=mysql_fetch_array($rs_datos)){?>				
				<table width="1100">
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8" align="center" class="titulo_tabla">Bajas de Personal <?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>FECHA BAJA</td>
						<td align='center' class='nombres_columnas'>&Aacute;REA</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>
						<td align='center' class='nombres_columnas'>OBSERVACIONES</td>
					</tr><?php
				$nom_clase = "renglon_gris";
				$cont = 1;				
				do{?>	
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>						
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_baja'],1); ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>
						<td align="left" class="<?php echo $nom_clase; ?>"><?php echo $datos['observaciones']; ?></td>
					</tr><?php 
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";						
				}while($datos=mysql_fetch_array($rs_datos));?>
				</table><br><br><?php
			}?>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepAltasBajas($hdn_consulta,$hdn_nomReporte,$hdn_msg, $hdn_consultaBajas) 


	//Esta funcion exporte el REPORTE PRESTAMOS a un archivo de excel
	function guardarRepPrestamos($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta genere datos para prepararla y asi exportar los datos
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="3">&nbsp;</td>
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
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="8" align="center" class="titulo_tabla"> Reporte de Préstamos <?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>NOMBRE DEDUCI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>TOTAL</td>
						<td align='center' class='nombres_columnas'>AUTORIZO</td>
						<td align='center' class='nombres_columnas'>FECHA ALTA</td>																																						
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_deduccion']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['total'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['autorizo']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_alta'],1); ?></td>
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
	}//Fin de la Funcion guardarRepPrestamos($hdn_consulta,$hdn_nomReporte,$hdn_msg)


	//Esta funcion exporte el REPORTE CAPACITACIÖN a un archivo de excel
	function guardarRepCapacitaciones($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="4">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="9" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="9" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="9">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>NOMBRE CAPACITACI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>HORAS CAPACITACI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>DESCRIPCI&Oacute;N</td>
						<td align='center' class='nombres_columnas'>FECHA INICIO</td>
						<td align='center' class='nombres_columnas'>FECHA FIN</td>
						<td align='center' class='nombres_columnas'>INSTRUCTOR</td>																																						
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nom_capacitacion']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['hrs_capacitacion']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['descripcion']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_inicio'],1); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_fin'],1); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['instructor']; ?></td>
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
	}//Fin de la Funcion guardarRepCapacitaciones($hdn_consulta,$hdn_nomReporte,$hdn_msg)
	

	//Esta funcion exporte el REPORTE NOMINA a un archivo de excel
	function guardarRepNomina($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="3">&nbsp;</td>
						<td valign="baseline" colspan="8">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="13" align="center" class="borde_linea">
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
						<td colspan="13" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="8">&nbsp;</td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>
						<td align='center' class='nombres_columnas'>INICIO REGISTRO N&Oacute;MINA</td>
						<td align='center' class='nombres_columnas'>FIN REGISTRO N&Oacute;MINA</td>
						<td align='center' class='nombres_columnas'>D&Iacute;AS TRABAJADOS</td>
						<td align='center' class='nombres_columnas'>SUELDO DIARIO</td>
						<td align='center' class='nombres_columnas'>SUELDO SEMANAL</td>
						<td align='center' class='nombres_columnas'>TIEMPO EXTRA</td>
						<td align='center' class='nombres_columnas'>DOMINGOS</td>
						<td align='center' class='nombres_columnas'>D&Iacute;A FESTIVO</td>
						<td align='center' class='nombres_columnas'>TOTAL</td>																																					
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total = 0;
			do{	
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>						
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_nomina_inicio'],1); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_nomina_fin'],1); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['dias_trabajados']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['sueldo_diario'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['sueldo_semana'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['tiempo_extra'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['domingo'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['dia_festivo'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['total'],2,".",","); ?></td>
				</tr>
				<?php
				$cant_total += $datos['total'];
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<td colspan='12'>&nbsp;</td><td colspan="1" align="center" class="nombres_columnas">$ <?php echo number_format($cant_total,2,".",","); ?></td>
			</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepNomina($hdn_consulta,$hdn_nomReporte,$hdn_msg)
	
	
	//Esta funcion exporte el REPORTE PAGOSS a un archivo de excel
	function guardarRepPagoSS($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td colspan="3">&nbsp;</td>
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
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE TRABAJADOR</td>
						<td align='center' class='nombres_columnas'>SEMANA</td>
						<td align='center' class='nombres_columnas'>MES</td>
						<td align='center' class='nombres_columnas'>A&Ntilde;O</td>
						<td align='center' class='nombres_columnas'>RETENCI&Oacute;N IMSS</td>										
						<td align='center' class='nombres_columnas'>NETO A APAGAR</td>						
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			$total_imss = 0;
			$total_neto = 0;
			do{	
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['rfc_trabajador']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre_trabajador']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['semana']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['mes']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['anio_insercion']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['retencion_imss'],2,".",","); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['neto_pagar'],2,".",","); ?></td>
				</tr>
				<?php
				$total_imss += $datos['retencion_imss'];
				$total_neto += $datos['neto_pagar'];
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs_datos)); ?>
				<td colspan='6'>&nbsp;</td>
					<td colspan="1" align="center" class="nombres_columnas" > Total IMSS $<?php echo number_format($total_imss,2,".",","); ?></td>
					<td colspan="1" align="center" class="nombres_columnas" > Neto a Pagar $<?php echo number_format($total_neto,2,".",","); ?></td>
				</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepPagoSS($hdn_consulta,$hdn_nomReporte,$hdn_msg)
	
	
	//Esta funcion exporte el REPORTE KARDEX a un archivo de excel
	function guardarRepKardex($hdn_consulta,$hdn_consultaKardex,$hdn_nomReporte,$hdn_msg, $hdn_cantidad){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
		if($datos=mysql_fetch_array($rs_datos)){
		
			//Obtener el rango de fechas que contiene datos para mostrar dentro del periodo seleccionado por el usuario
			$fechas=mysql_fetch_array(mysql_query($hdn_consultaKardex));
			
			$fechaPrinc=$fechas['fechaIni']; 
			$fechaFinal=$fechas['fechaFin'];
						
			//Obtener el dia, mes y año de Inicio del rango de Fechas existentes dentro del Periodo Seleccionado por el Usuario
			$mesIniNum = substr($fechaPrinc,5,2);
			$anioIni = substr($fechaPrinc,0,4);
			$diaIni = substr($fechaPrinc,-2);
			
			//Obtener el dia, mes y año de Fin del rango de Fechas existentes dentro del Periodo Seleccionado por el Usuario
			$mesFinNum = substr($fechaFinal,5,2);
			$anioFin = substr($fechaFinal,0,4);
			$diaFin = substr($fechaFinal,-2);
			
					
			//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año)
			$fechaIni_enDias = gregoriantojd ($mesIniNum, $diaIni, $anioIni);
			$fechaFin_enDias = gregoriantojd ($mesFinNum, $diaFin, $anioFin);
			$totalDias = ($fechaFin_enDias-$fechaIni_enDias)+1;
			
			//DIBUJAR EL ENCABEZADO DE LA TABLA PARA MOSTRAR EL KARDEX
			
			//Variable para verificar si la consulta ejecutada arrojo resultados
			$flag = 1;
			//Esta variable guarda el día de inicio para desplegar el Kardex			
			$diaEnCurso=$diaIni;															
			$mesEnCurso = $mesIniNum;
			$anioEnCurso = $anioIni;
			//Obtener los dias del mes en curso
			$diasMesCurso = date("t", mktime(00, 00, 00, $mesIniNum, 01, $anioIni));
			
			//Variables para controlar el numero de datos del Kardex
			$contAsis=0;
			$contFalt=0;
			$contVaca=0;
			$contRetr=0;
			$contJust=0;
			$contInca=0;
			$contIRT=0;
			$contIE=0;
											
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
						<td valign="baseline" colspan="<?php echo $hdn_cantidad-1;?>">
							<div align="right"><span class="texto_encabezado">
								<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
							</span></div>
						</td>
					</tr>											
					<tr>
						<td colspan="<?php echo $hdn_cantidad+3;?>" align="center" class="borde_linea">
							<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                            &Oacute;N TOTAL O PARCIAL</span>
						</td>
					</tr>					
					<tr>
						<td colspan="<?php echo $hdn_cantidad;?>"></td>
					</tr>
					<tr>
						<td colspan="<?php echo $hdn_cantidad;?>"></td>
					</tr>
					<tr>
						<td colspan="<?php echo $hdn_cantidad;?>" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
					</tr>
					<tr>
						<td colspan="<?php echo $hdn_cantidad;?>"></td>
					</tr>			
					<tr>
						<td align='center' class='nombres_columnas'>NO.</td>
						<td align='center' class='nombres_columnas'>RFC</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>&Aacute;REA</td><?php
					for($i=0; $i<$totalDias; $i++){							
						echo "	<td align='center' class='nombres_columnas'>".regresarMes($mesEnCurso)." $diaEnCurso</td>";
						//Si se llego el dia final del mes en curso, reiniciar contadorDias y obtener los dias del siguiente mes
						if($diaEnCurso==$diasMesCurso){
							//Verificar el Cambio de Año
							if($mesEnCurso==12){
								//Incrementar el Año
								$anioEnCurso++;
								//Reiniciar el mes en curso a Enero (01)
								$mesEnCurso = 0;
							}
							//Incrementar Mes
							$mesEnCurso++;
							//Obtener los dias del mes en curso
							$diasMesCurso=date("t", mktime(00, 00, 00, $mesEnCurso, 01, $anioEnCurso));								
							//Reiniciar contador de dias
							$diaEnCurso=0;															
						}
						$diaEnCurso++;
					}
					echo"</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			$cant_total=0;
			do{	
				//Esta variable guarda el día de inicio para desplegar el Kardex
				$diaEnCurso = $diaIni;															
				$mesEnCurso = $mesIniNum;
				$anioEnCurso = $anioIni;
				//Obtener los dias del mes en curso
				$diasMesCurso = date("t", mktime(00, 00, 00, $mesIniNum, 01, $anioIni));?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont; ?></td>		
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['rfc_empleado']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td><?php 
				
				//Mostrar el Kardex de cada Trabajador
				for($i=0; $i<$totalDias; $i++){
					//Colocarle un cero a al izquierda a los dias y los meses menores a 10
					$numDia = "";
					if($diaEnCurso>=10) $numDia = $diaEnCurso;
					else $numDia = "0".$diaEnCurso;
					
					$numMes = "";
					if($mesEnCurso>=10) $numMes = $mesEnCurso;
					else $numMes = "0".$mesEnCurso;
						
					
					//Componer la Fecha actual para hacer la consulta y obtener el Kardex del Dia en curso
					$fechaEnCurso = $anioEnCurso."-".$numMes."-".$numDia;
					$estado = buscarKardex($datos['rfc_empleado'],$fechaEnCurso);
					
					//Determinamos la cantidad de concepto del Kardex por empleado
					if($estado=='A')
						$contAsis++;
					else if($estado=='F')
						$contFalt++;
					else if($estado=='V')
						$contVaca++;
					else if($estado=='R')
						$contRetr++;
					else if($estado=='J')
						$contJust++;
					else if($estado=='I')
						$contInca++;
					else if($estado=='IRT')
						$contIRT++;
					else if($estado=='IRT')
						$contIE++;
						echo" <td class='$nom_clase' align='center'><strong>$estado</strong></td>";
						
						
						
					//Si se llego el dia final del mes en curso, reiniciar contadorDias y obtener los dias del siguiente mes
					if($diaEnCurso==$diasMesCurso){
						//Verificar el Cambio de Año
						if($mesEnCurso==12){
							//Incrementar el Año
							$anioEnCurso++;
							//Reiniciar el mes en curso a Enero (01)
							$mesEnCurso = 0;
						}
						
						//Incrementar Mes
						$mesEnCurso++;
						//Obtener los dias del mes en curso
						$diasMesCurso=date("t", mktime(00, 00, 00, $mesEnCurso, 01, $anioEnCurso));								
						//Reiniciar contador de dias
						$diaEnCurso=0;															
					}
					$diaEnCurso++;
				}
				echo "</tr>";			
				
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs_datos));?>
				<tr>&nbsp;</tr>
				<tr>&nbsp;</tr>
				<tr>&nbsp;</tr>
				<tr>&nbsp;</tr>
			</table>
			<table>
			<?php 			
			if(isset($_POST["hdn_flag"])){?>
				<tr>
					<td>&nbsp;</td>
					<td colspan="8" align="center" class="nombres_columnas">TABLA DETALLE DEL KARDEX</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="1" align="center" class="nombres_columnas">Asistencias</td>
					<td colspan="1" align="center" class="nombres_columnas">Faltas</td>
					<td colspan="1" align="center" class="nombres_columnas">Vacaciones</td>
					<td colspan="1" align="center" class="nombres_columnas">Retraso</td>
					<td colspan="1" align="center" class="nombres_columnas">Justificadas</td>
					<td colspan="1" align="center" class="nombres_columnas">Incapacidades</td>
					<td colspan="1" align="center" class="nombres_columnas">Incapacidad Riesgo de Trabajo</td>
					<td colspan="1" align="center" class="nombres_columnas">Incapacidad Efermedad</td>			
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="1" align="center" class="<?php echo $nom_clase; ?>"><?php echo $contAsis; ?></td>
					<td colspan="1" align="center" class="<?php echo $nom_clase; ?>"><?php echo $contFalt; ?></td>
					<td colspan="1" align="center" class="<?php echo $nom_clase; ?>"><?php echo $contVaca; ?></td>
					<td colspan="1" align="center" class="<?php echo $nom_clase; ?>"><?php echo $contRetr; ?></td>
					<td colspan="1" align="center" class="<?php echo $nom_clase; ?>"><?php echo $contJust; ?></td>
					<td colspan="1" align="center" class="<?php echo $nom_clase; ?>"><?php echo $contInca; ?></td>
					<td colspan="1" align="center" class="<?php echo $nom_clase; ?>"><?php echo $contIRT; ?></td>
					<td colspan="1" align="center" class="<?php echo $nom_clase; ?>"><?php echo $contIE; ?></td>			
				</tr>
			<?php }?>
			</table>
			</div>
			</body>
			<?php	}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion guardarRepKardex($hdn_consulta,$hdn_consultaKardex,$hdn_nomReporte,$hdn_msg, $hdn_cantidad)
	
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
	
	function regresarMes($numMes){
		$mes = "";
		switch($numMes){
			case "01": $mes = "Ene"; 	break;
			case "02": $mes = "Feb"; 	break;
			case "03": $mes = "Mar"; 	break;
			case "04": $mes = "Abr"; 	break;
			case "05": $mes = "May"; 	break;
			case "06": $mes = "Jun"; 	break;
			case "07": $mes = "Jul"; 	break;
			case "08": $mes = "Ago"; 	break;
			case "09": $mes = "Sep";	break;
			case "10": $mes = "Oct"; 	break;
			case "11": $mes = "Nov"; 	break;
			case "12": $mes = "Dic"; 	break;
		}
		return $mes;
	}//Cierre de la función regresarMes($numMes)
	
	
	/*Esta funcion buscar en el Kardex el estado de un empleado en una fecha determinada*/
	function buscarKardex($rfcEmpleado,$fecha){
		//Creamos la sentencia para buscar el estado del empleado
		$stm_sql = "SELECT estado FROM kardex WHERE empleados_rfc_empleado='$rfcEmpleado' AND fecha_entrada = '$fecha'";
		
		//Ejecutamos la consulta
		$rs = mysql_query($stm_sql);
		
		//Mostrar los resultados obtenidos
		if($datos = mysql_fetch_array($rs)){
			return $datos['estado'];
		}
		else		
			return "N/D";
	}//Cierre de la función buscarKardex($rfcEmpleado,$fecha)
	
	
	//Esta funcion exporta el REPORTE Historico  de los empleados a un archivo de excel
	function guardarRepHistorico($hdn_consulta,$hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Ejecutar la sentencia y almacena los datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($hdn_consulta);
		
		//Verificamos que la consulta haya generado datos	
		if($datos=mysql_fetch_array($rs_datos)){
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
						<td valign="baseline" colspan="4">
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
						<td align='center' class='nombres_columnas'>RFC EMPLEADO</td>
						<td align='center' class='nombres_columnas'>NOMBRE</td>
						<td align='center' class='nombres_columnas'>FECHA INGRESO</td>
						<td align='center' class='nombres_columnas'>FECHA MODIFICACI&Oacute;N DEL PUESTO</td>
						<td align='center' class='nombres_columnas'>FECHA DE BAJA</td>							
						<td align='center' class='nombres_columnas'>&Aacute;REA</td>
						<td align='center' class='nombres_columnas'>PUESTO</td>	
						<td align='center' class='nombres_columnas'>OBSERVACIONES</td>																
      				</tr>
			<?php
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{	
			
			//Revisar que tanto la fecha de baja como la fecha de modificacion no se encuentren vacias
				$fechaMod = "N/D";
				if($datos['fecha_mod_puesto']!='0000-00-00'){
					$fechaMod = modFecha($datos['fecha_mod_puesto'],1); 
				}
				
				$fechaBaja = "N/D";
				if($datos['fecha_baja']!='0000-00-00'){
					$fechaBaja = modFecha($datos['fecha_baja'],1);
				}
				?>
				<tr>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['empleados_rfc_empleado']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_ingreso'],1); ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo "$fechaMod" ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo "$fechaBaja" ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['puesto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['observaciones']; ?></td>
					

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
	}//Fin de la Funcion guardarRepHistorico($hdn_consulta,$hdn_nomReporte,$hdn_msg)
	
	
	//Esta funcion exporta el REPORTE NOMINA BANCARIA  de los empleados a un archivo de excel
	function guardarRepNomBancaria($hdn_msg, $hdn_nomReporte){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
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
					#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
					.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
					.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
					.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
					.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
					-->
				</style>
			</head>											
			<body><?php 
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Arreglos para el Control de los Encabezados
		$correspondencia = array("mes"=>"MES", "semana"=>"SEMANA", "num"=>"N&Uacute;MERO", "nombre_trabajador"=>"NOMBRE EMPLEADO", "rfc_trabajador"=>"RFC", 
								 "imss"=>"NSS", "curp"=>"CURP", "jornada"=>"JORNADA", "fecha_ingreso"=>"FECHA INGRESO", "tipo_salario"=>"TIPO SALARIO", 
								 "hrs_laboradas"=>"HORAS LABORADAS", "dias_trabajados"=>"D&Iacute;AS LABORADOS", "septimo_dia"=>"S&Eacute;PTIMO D&Iacute;A",
								 "hrs_tiempo_extra"=>"HORAS TIEMPO EXTRA", "dias_domingos"=>"D&Iacute;AS DOMINGOS", "dias_descanso"=>"D&Iacute;AS DESCANSO",
								 "dias_festivos"=>"D&Iacute;AS FESTIVOS", "dias_vacacion"=>"D&Iacute;AS VACACI&Oacute;N", "sueldo_diario"=>"SUELDO DIARIO",
								 "sueldo_integrado"=>"SUELDO INTEGRADO", "percepcion_normal"=>"PERCEPCI&Oacute;N NORMAL", 
								 "importe_septimo_dia"=>"S&Eacute;PTIMO D&Iacute;A", "tiempo_extra"=>"TIEMPO EXTRA", "prima_dominical"=>"PRIMA DOMINICAL",
								 "p_comision"=>"COMISI&Oacute;N", "trabajo_dias_descanso"=>"D&Iacute;AS DESCANSO", "trabajo_dias_festivos"=>"D&Iacute;AS EFECTIVOS",
								 "prima_vacacional"=>"PRIMA VACACIONAL", "aguinaldo"=>"AGUINALDO", "ptu"=>"PTU", "premio_asistencia"=>"PREMIO ASISTENCIA",
								 "premio_puntualidad"=>"PREMIO PUNTUALIDAD", "despensas"=>"DESPENSA", "prima_antiguo"=>"PRIMA ANTIG&Uuml;EDAD", 
								 "anios_antiguo"=>"A&Ntilde;OS ANTIG&Uuml;EDAD", "otras_percepciones"=>"OTRAS PERCEPCIONES", "clave_op"=>"CLAVE OP", 
								 "total_percepciones"=>"TOTAL PERCEPCIONES", "retencion_imss"=>"RETENCI&Oacute;N IMSS", "retencion_ispt"=>"RETENCI&Oacute;N ISPT",
								 "neto_percepciones"=>"NETO PERCEPCIONES", "abono_infonavit"=>"ABONO INFONAVIT", "otras_retenciones"=>"OTRAS RETENCIONES", 
								 "fonacot"=>"FONACOT", "clave_or"=>"CLAVE OR", "total_retenido"=>"TOTAL RETENIDO", "neto_salarios"=>"SALARIO NETO", 
								 "subsidio_empleo"=>"SUBSIDIO EMPLEO", "neto_pagar"=>"SALARIO NETO", "numero"=>"N&Uacute;MERO", "ingravado"=>"INGRAVADO",
								 "depto"=>"DEPARTAMENTO", "anio_insercion"=>"A&Ntilde;O INSERCI&Oacute;N");
		$claves = array();
		$contConcepto="";
		//Cremos la consulta 
		$sql_stm = "SELECT ";
		//Obtener los campos que seran exportados
		foreach($_POST as $clave => $dato){
			if(substr($clave,0,12)=="ckb_concepto"){
				$sql_stm .= $dato.",";	
				$claves[] = $dato;
				$contConcepto++;
			}
		}
		//Retirar la Ultima coma agregada en la lista de campos armada en el ciclo anterior
		$sql_stm = substr($sql_stm,0,strlen($sql_stm)-1);
		//Complementar Sentencia SQL
		$sql_stm .= " FROM nomina_bancaria WHERE semana='".$_POST['hdn_semana']."' AND mes='".$_POST['hdn_mes']."' AND anio_insercion='".$_POST['hdn_anio']."'";
		
		//Colocar el Encabezado de la Tabla
		echo "
			<table width='1100' class='tabla_frm' cellpadding='5'>
				<tr>";?>
					<td align="left" valign="baseline" colspan="1">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
            			align="absbottom" />
					</td>
					<td colspan="2">&nbsp;</td>
					<td valign="baseline" colspan="<?php echo $contConcepto-2;?>">
						<div align="right"><span class="texto_encabezado">
						<br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
				</tr>											
				<tr>
					<td colspan="<?php echo $contConcepto+1;?>" align="center" class="borde_linea">
						<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                		&Oacute;N TOTAL O PARCIAL</span>
					</td>
				</tr>					
				<tr>
					<td colspan="<?php echo $contConcepto+1;?>">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="<?php echo $contConcepto+1;?>">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="<?php echo $contConcepto+1;?>" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
				</tr>
				<tr>
					<td colspan="<?php echo $contConcepto+1;?>">&nbsp;</td>
				</tr>
				<tr>
					<td align="center" class="nombres_columnas">NO</td><?php 
		foreach($claves as $ind => $value){
			echo "<td align='center' class='nombres_columnas'>".$correspondencia[$value]."</td>";
		}?>
		<?php		
		$nom_clase = "renglon_gris";
		$cont = 1;
		//Obtener los RFC de los Empleados seleccionados
		foreach($_POST as $clave => $rfcEmpleado){
			if(substr($clave,0,7)=="ckb_emp"){
				//Complementar la consulta con el RFC del Empleado en turno
				$sql_stm_emp = $sql_stm." AND rfc_trabajador='$rfcEmpleado'";
				//Ejecutar Consulta
				$rs = mysql_query($sql_stm_emp);
				
				if($datosEmpleado=mysql_fetch_array($rs)){?>
					<tr>
						<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $cont;?></td><?php
					foreach($claves as $ind => $valor){
						if($valor=="fecha_ingreso"){?>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datosEmpleado[$valor],1);?></td>
				<?php  }
						else{?>
							<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datosEmpleado[$valor];?></td><?php 
						}
					}?>																		
					</tr><?php
					
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}				
			}				
		}?>
		</body><?php	
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
	}//Fin de la Funcion  guardarRepNomBancaria($hdn_msg, $hdn_nomReporte)
	
	
	function exportarXML(){
		//Conectarse a la BD
		$conn = conecta("bd_recursos");
		$sql_stm="SELECT * FROM empleados ORDER BY id_empleados_empresa";
		//Ejecutar la Sentencia previamente creada
		$rs = mysql_query($sql_stm);
		//Obtener la Fecha y la Hora para el Archivo XML
		$fecha=date("Y-m-d");
		$hora=date("H:i:s");
		//Esribir datos de la pagina en excel
		header("Content-type: text/xml");
		header("Content-Disposition: attachment; filename=tblTrabajador.xml");
		//Revisar el resultado de la consulta
		if($datos=mysql_fetch_array($rs)){
			$nom_archivo = "documentos/errores.txt";
			$id="";
			if (file_exists($nom_archivo))
				unlink($nom_archivo);
			echo "<dataroot xmlns:od='urn:schemas-microsoft-com:officedata' generated='".$fecha."T".$hora."'>";
			do{
				if ($datos["id_empleados_empresa"]>0 && $datos["id_empleados_empresa"]!=$id){
					$nip=$datos["id_empleados_empresa"];
					$longNip=strlen($nip);
					if ($longNip==1)
						$nip="000".$nip;
					if ($longNip==2)
						$nip="00".$nip;
					if ($longNip==3)
						$nip="0".$nip;
					$idTrabajador="0100".$nip;
					$tsangre=$datos["tipo_sangre"];
					if($tsangre=="")
						$tsangre="N/D";
					$puesto=$datos["puesto"];
					if($puesto=="")
						$puesto="N/D";
					$curp=$datos["curp"];
					if($curp=="")
						$curp="N/D";
					$nss=$datos["no_ss"];
					if($nss=="")
						$nss="N/D";
					//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
					echo utf8_encode("
						<tblTrabajador>
							<Emp>1</Emp>
							<Trabajador>$datos[id_empleados_empresa]</Trabajador>
							<NIP>$nip</NIP>
							<Clave>$idTrabajador</Clave>
							<Nombre>$datos[nombre] $datos[ape_pat] $datos[ape_mat]</Nombre>
							<Depto>$datos[id_depto]</Depto>
							<FechaNac>0000-00-00T00:00:00</FechaNac>
							<FechaAlta>$datos[fecha_ingreso]T00:00:00</FechaAlta>
							<OpcionTeclado>0</OpcionTeclado> 
							<ChecadaLibre>1</ChecadaLibre> 
							<HuellaDigital>0</HuellaDigital> 
							<Puesto>$puesto</Puesto> 
							<Curp>$curp</Curp> 
							<Rfc>$datos[rfc_empleado]</Rfc> 
							<Imss>$nss</Imss> 
							<TipoSangre>$tsangre</TipoSangre> 
							<SemanaActiva>0</SemanaActiva> 
							<Activo>1</Activo> 
							<Opciones>0</Opciones> 
							<NumRegistro>$datos[id_empleados_empresa]</NumRegistro> 
							<AplicaPP>0</AplicaPP> 
							<AplicaTExt>1</AplicaTExt> 
							<AplicaExpIncidencias>1</AplicaExpIncidencias> 
							<AplicaExpConsComedor>1</AplicaExpConsComedor>
						</tblTrabajador>");
				}
				else{
					if ($datos["id_empleados_empresa"]>0){
						$contenido = "Usuario: ".$datos["id_empleados_empresa"].".-	$datos[nombre] $datos[ape_pat] $datos[ape_mat] No Agregado por ID Duplicado
";
						$fp = fopen($nom_archivo, 'a');
						fwrite($fp, $contenido);
					}
				}
				$id=$datos["id_empleados_empresa"];
			}while($datos=mysql_fetch_array($rs));
			echo "</dataroot>";
		}
	}//Cierre de la función exportarXML()
	
	
	function exportarNominaInterna($msg,$idNomina,$area){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$msg$area.xls");
		
		//Archivo requerido para la consulta de Kardex
		include_once("op_registrarNominaInterna.php");
			
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
		
		//Obtener los datos generales de la nomina
		$datosNomina = mysql_fetch_array(mysql_query("SELECT *,DATEDIFF(fecha_fin,fecha_inicio) AS cant_dias FROM nomina_interna WHERE id_nomina = '$idNomina'"));
		$fechaIni = $datosNomina['fecha_inicio'];
		$fechaFin = $datosNomina['fecha_fin'];
		$cantDias = $datosNomina['cant_dias'] + 1;
		
		$periodo = "SEMANA";//Variable utilizada en los mensajes de la caja de texto de Sueldo Semanal o Quincenal
		$msgSemQuin = "SEMANA DEL ".modFecha($fechaIni,1)." AL ".modFecha($fechaFin,1);
		if($cantDias==15 || $cantDias==16){
			$msgSemQuin = "QUINCENA DEL ".modFecha($fechaIni,1)." AL ".modFecha($fechaFin,1);
			$periodo = "QUINCENA";
		}?>
				
								
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin; border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none;
				border-bottom-style: solid; border-left-style: none; border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
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
					<td align="left" valign="baseline" colspan="2">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
					</td>
					<td colspan="<?php echo $cantDias + 3; ?>">&nbsp;</td>
					<td valign="baseline" colspan="4">
						<div align="right"><span class="texto_encabezado">
							<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
				</tr>											
				<tr>
					<td colspan="<?php echo $cantDias + 9; ?>" align="center" class="borde_linea">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA 
							SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
						</span>
					</td>
				</tr>					
				<tr>
					<td colspan="<?php echo $cantDias + 9; ?>">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="<?php echo $cantDias + 9; ?>">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="<?php echo $cantDias + 9; ?>" align="center" class="titulo_tabla"><?php 
						echo $msg.$area; ?>
					</td>
				</tr>
				<tr>
					<td colspan="<?php echo $cantDias + 9; ?>" align="center" class="titulo_tabla"><?php
						echo $msgSemQuin;?>
					</td>
				</tr><?php
				
				
				//Crear la Sentencia SQL para extraer los trbajadores registrados en la Nomina Seleccionada
				$sql_empleados = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre, puesto, jornada, det_nom_interna.* FROM det_nom_interna JOIN empleados 
									ON empleados_rfc_empleado=rfc_empleado WHERE nomina_interna_id_nomina = '$idNomina' ORDER BY nombre";
			
				//Ejecutamos la sentencia SQL
				$rs_empleado = mysql_query($sql_empleados);
				
				//Si la consulta arrojo datos se crea la tabla para mostrar los resultados
				$datosEmpleados=mysql_fetch_array($rs_empleado);?>								
				<tr>
					<td rowspan="2" align="center" class="nombres_columnas">NO.</td>
					<td rowspan="2" align="center" class="nombres_columnas">NOMBRE</td>
					<td rowspan="2" align="center" class="nombres_columnas">PUESTO</td>
					<td rowspan="2"align="center" class="nombres_columnas">SUELDO DIARIO</td>
					<td colspan="<?php echo $cantDias; ?>" align="center" class="nombres_columnas">KARDEX</td>
					<td rowspan="2" align="center" class="nombres_columnas">SUELDO <?php echo $periodo; ?></td>
					<td rowspan="2" align="center" class="nombres_columnas">T.E.</td>
					<td rowspan="2" align="center" class="nombres_columnas">D.T.</td>
					<td rowspan="2" align="center" class="nombres_columnas">BONIFICACION</td>
					<td rowspan="2" align="center" class="nombres_columnas">TOTAL</td>
				</tr>
				<tr><?php
					//Colocar la letra inicial del dia de la semana que corresponde a la fecha indicada
					$fechaActual = $fechaIni;
					for($i=0;$i<$cantDias;$i++){ 
						//Obtener el nombre del día de la fecha pasada como parámetro en formato aaaa-mm-dd
						$nomDia = obtenerNombreDia($fechaActual);
						//Obtener la letra inicial del dia obtenido
						$letraDia = substr($nomDia,0,1);?>
						
						<td align="center" class="nombres_columnas"><?php echo $letraDia; ?></td><?php
						
						//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
						$seccFecha = split("-",$fechaActual);
						//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y sumar 1 día
						$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + 1;
						//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
						$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
					}?>														
				</tr><?php
				
			//Variables para dar formato a cada renglon de la tabla que será dibujada
			$nom_clase = "renglon_gris";
			$cont = 1;
			
			
			//DESPLEGAR EL REGISTRO DE CADA EMPLEADO
			do{?>
				<tr>		
					<td align="center" class="<?php echo $nom_clase; ?>" rowspan="2" style="vertical-align:middle;"><?php 
						echo $cont; ?>
					</td>
					<td align="left" class="<?php echo $nom_clase; ?>"><?php 
						echo $datosEmpleados['nombre']; ?>						
					</td>
					<td align="left" class="<?php echo $nom_clase; ?>" rowspan="2" style="vertical-align:middle;"><?php echo $datosEmpleados['puesto']; ?></td>
					<td align="center" class="<?php echo $nom_clase; ?>">$<?php 
						echo number_format($datosEmpleados['sueldo_diario'],2,".",","); ?>
					</td><?php				
				//Calcular el costo del tiempo extra de cada trabajador
				$precioTE = ($datosEmpleados['sueldo_diario']/$datosEmpleados['jornada']) * 2;
				//Esta variable guardara el segundo renglon del registro de cada empleado, el cual será desplegado una vez que haya sido cerrado (</tr>) el primero
				$segRenglon = "<tr><td class='$nom_clase'>Tiempo Extra</td><td align='center' class='$nom_clase'>$".number_format($precioTE,2,".",",")."</td>";
				
				
				//Obtener los datos del Kardex de cada empleado que será listado en el arreglo '$datosKardex' el cual contiene los siguientes
				//Indices por fecha: 'incidencia', 'horasTrabajadas', 'horasExtra' y fuera de las fechas 'diasTrabajados'
				$datosKardex = obtenerKardexEmpleado($datosEmpleados['empleados_rfc_empleado'],$fechaIni,$fechaFin,$cantDias,$datosEmpleados['jornada']);							
								
				//Guardar la fecha de inicio como fecha actual.
				$fechaActual = $fechaIni;
				//Colocar los datos del kardex(Incidencia, Horas Trabajas y Horas Extra) de cada empleado
				for($i=0;$i<$cantDias;$i++){?>
					
					<td align="center" class="<?php echo $nom_clase; ?>"><?php 
						echo $datosKardex[$fechaActual]['incidencia']; ?>
					</td><?php			
					
					//Agregar las Horas Extra al segundo renglon							
					$segRenglon .= "<td class='$nom_clase' align='center'>".$datosKardex[$fechaActual]['horasExtra']."</td>";
					
					
					//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
					$seccFecha = split("-",$fechaActual);
					//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y sumar 1 día
					$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) +1;
					//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
					$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
				}//Ciere for($i=0;$i<$cantDias;$i++)?>
								
					<td align="center" class="<?php echo $nom_clase; ?>" title="Sueldo de la <?php echo $periodo; ?>" rowspan="2" style="vertical-align:middle;"><?php 
						echo "$".number_format($datosEmpleados['sueldo_periodo'],2,".",",");?>
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Tiempo Extra de la <?php echo $periodo; ?>" rowspan="2" style="vertical-align:middle;"><?php 
						echo "$".number_format($datosEmpleados['tiempo_extra'],2,".",","); ?>
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Descanso Trabajado" rowspan="2" style="vertical-align:middle;"><?php 
						echo "$".number_format($datosEmpleados['descanso_trabajado'],2,".",","); ?>
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Bonificaci&oacute;n de la <?php echo $periodo; ?>"rowspan="2" style="vertical-align:middle;"><?php 
						echo "$".number_format($datosEmpleados['bonificacion'],2,".",","); ?>
					</td>
					<td align="center" class="<?php echo $nom_clase; ?>" title="Sueldo Total de la <?php echo $periodo; ?>" rowspan="2" style="vertical-align:middle;"><?php 
						echo "$".number_format($datosEmpleados['sueldo_total'],2,".",","); ?>
					</td>
				</tr><?php
				
				//Imprimir el contenido del segundo Renglon
				echo $segRenglon."</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datosEmpleados=mysql_fetch_array($rs_empleado));?>
			</table>
		</div>
		</body><?php
		
	}//Cierre de la función exportarNominaInterna($hdn_msg,$hdn_idNomina)
	
	
	function exportarKardeDetalle($fechaI,$fechaF,$tipo,$criterio,$dias){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=kardexDetallado.xls");
		
		//Archivo requerido para la consulta de Kardex
		include_once("op_registrarNominaInterna.php");
		
		//Titulo
		$msg="PERIODO TERMINADO DEL $fechaI al $fechaF";
		//Recuperar las variables que vienen como parametros en el GET
		$fechaI=modFecha($fechaI,3);
		$fechaF=modFecha($fechaF,3);
		
		//Verificar el tipo de consulta
		if($tipo=="ind"){
			//Obtener la clave de empleado
			$claveTemp=obtenerDatoEmpleadoPorNombre("id_empleados_empresa",$criterio);
			if($claveTemp<10)
				$clave[]="00".$claveTemp;
			if($claveTemp>=10 && $claveTemp<100)
				$clave[]="0".$claveTemp;
			if($claveTemp>=100)
				$clave[]=$claveTemp;
			//Obtener el RFC de empleado
			$rfc[]=obtenerDatoEmpleadoPorNombre("rfc_empleado",$criterio);
			//Obtener el nombre
			$nombre[]=$criterio;
			//Obtener la Jornada del Empleado
			$jornada[]=obtenerDatoEmpleadoPorNombre("jornada",$criterio);
		}
		else{
			//Obtener a los trabajadores del área seleccionada
			$conn=conecta("bd_recursos");
			if($criterio=="TODOS")
				$sql="SELECT id_empleados_empresa,rfc_empleado,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre,jornada FROM empleados WHERE id_empleados_empresa>0 ORDER BY area,nombre";
			else
				$sql="SELECT id_empleados_empresa,rfc_empleado,CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre,jornada FROM empleados WHERE area='$criterio' ORDER BY area,nombre";
			$res=mysql_query($sql);
			if($row=mysql_fetch_array($res)){
				do{
					//Obtener la clave de empleado
					$claveTemp=$row["id_empleados_empresa"];
					if($claveTemp<10)
						$clave[]="00".$claveTemp;
					if($claveTemp>=10 && $claveTemp<100)
						$clave[]="0".$claveTemp;
					if($claveTemp>=100)
						$clave[]=$claveTemp;
					//Obtener el RFC de empleado
					$rfc[]=$row["rfc_empleado"];
					//Obtener el nombre
					$nombre[]=$row["nombre"];
					//Obtener la jornada
					$jornada[]=$row["jornada"];
				}while($row=mysql_fetch_array($res));
			}
			mysql_close($conn);
		}
		?>				
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: medium; border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid;
				border-bottom-style: solid; border-left-style: solid; border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;border-style:solid;border-color:#000000;border-width:thin;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;border-style:solid;border-color:#000000;border-width:thin;}
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
					<td align="left" valign="baseline" colspan="2">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
					</td>
					<td colspan="<?php echo $dias; ?>">&nbsp;</td>
					<td valign="baseline" colspan="4">
						<div align="right"><span class="texto_encabezado">
							<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
				</tr>											
				<tr>
					<td colspan="<?php echo $dias + 6; ?>" align="center" class="borde_linea">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA 
							SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="<?php echo $dias + 6; ?>">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="<?php echo $dias + 6; ?>">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="<?php echo $dias + 6; ?>" align="center" class="titulo_tabla">CONTROL DIARIO DE ASISTENCIA</td>
				</tr>
				<tr>
					<td colspan="<?php echo $dias + 6; ?>" align="center" class="titulo_tabla"><?php echo $msg;?></td>
				</tr>
				<tr>
					<td rowspan="2" align="center" class="nombres_columnas">No.</td>
					<td rowspan="2" align="center" class="nombres_columnas">NOMBRE DEL EMPLEADO</td>
					<td colspan="<?php echo $dias+1;?>" align="center" class="nombres_columnas">DIAS Y HORAS TRABAJADOS </td>
					<td colspan="2" align="center" class="nombres_columnas">TOTAL DE HORAS </td>
					<td rowspan="2" align="center" class="nombres_columnas">FIRMA EMPLEADO </td>
				</tr>
				<tr>
					<td align="center" class="nombres_columnas">HORARIO</td>
					<?php
					//Contador para ver los días y dibujarlos en las celdas correspondientes
					$contDias=0;	
					do{
						$dia=obtenerDia($fechaI,$contDias);
						echo "<td align='center' class='nombres_columnas'>$dia</td>";
						$contDias++;
					}while($contDias<$dias);
					?>
					<td align="center" class="nombres_columnas">NORMAL</td>
					<td align="center" class="nombres_columnas">EXTRA</td>
				</tr>
				<?php
				//Variable de control de registros
				$reg=0;
				//Variables para dar formato a cada renglon de la tabla que será dibujada
				$nom_clase = "renglon_blanco";
				//Conectarse a la BD de Recursos
				$conn=conecta("bd_recursos");
				do{
					/*CONTENIDO DE TABLA*/
					//Horas Totales y de Tiempo Extra
					//Variable Arreglo de Kardex
					$kardex=array();
					//Obtener el arreglo del Kardex del empleado segun las fecha de consulta
					$kardex=obtenerKardexEmpleado($rfc[$reg],$fechaI,$fechaF,$dias,$jornada[$reg]);
					//Variable que acumulara las Horas Trabajadas
					$tiempoNormal=0;
					//Variable que acumulara las Horas Extra
					$tiempoExtra=0;
					//Ciclo que obtiene las Horas
					foreach($kardex as $ind=>$value){
						$tiempoNormal+=$kardex[$ind]["horasTrabajadas"];
						$tiempoExtra+=$kardex[$ind]["horasExtra"];
					}
					$tiempoNormal-=$tiempoExtra;
					//RENGLON DE ENTRADA
					echo "
					<tr>
					<td align='center' rowspan='2' class='$nom_clase'>".($reg+1)."</td>
					<td align='center' rowspan='2' class='$nom_clase'>$nombre[$reg]</td>
					<td align='center' class='$nom_clase'>ENTRADA</td>
					";
					//Contador para ver los días y dibujarlos en las celdas correspondientes para Entradas
					$contDias=0;
					do{
						$entrada=obtenerChecada($fechaI,$contDias,$rfc[$reg],"in");
						$entrada=substr($entrada,0,5);
						echo "<td align='center' class='$nom_clase'>$entrada</td>";
						$contDias++;
					}while($contDias<$dias);
					echo "
					<td align='center' rowspan='2' class='$nom_clase'>$tiempoNormal</td>
					<td align='center' rowspan='2' class='$nom_clase'>$tiempoExtra</td>
					<td align='center' rowspan='2' class='$nom_clase'>&nbsp;</td>
					</tr>
					";
					//RENGLON DE SALIDA
					echo "<tr><td align='center' class='$nom_clase'>SALIDA</td>";
					//Contador para ver los días y dibujarlos en las celdas correspondientes para Salidas
					$contDias=0;
					do{
						$salida=obtenerChecada($fechaI,$contDias,$rfc[$reg],"out");
						$salida=substr($salida,0,5);
						echo "<td align='center' class='$nom_clase'>$salida</td>";
						$contDias++;
					}while($contDias<$dias);
					echo "</tr>";
					
					/*
					//RENGLON DE TOTAL DE HORAS
					echo "<tr><td align='center' class='$nom_clase'>TOT. HORAS</td>";
					//Contador para ver los días y dibujarlos en las celdas correspondientes para Salidas
					$contDias=0;
					do{
						//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
						$seccFecha = split("-",$fechaI);
						//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y sumar 1 día
						$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + $contDias;
						//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
						$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
						//Kardex del Empleado Actual por dia
						$horasTrabajadas=obtenerKardexEmpleado($rfc[$reg],$fechaActual,$fechaActual,1,$jornada[$reg]);
						//Horas por Dia
						$horasXDia=0;
						//Ciclo que obtiene las Horas Trabajadas
						foreach($horasTrabajadas as $ind=>$value){
							$horasXDia+=$horasTrabajadas[$ind]["horasTrabajadas"];
						}
						//Imprimir las Horas por Dia
						echo "<td align='center' class='$nom_clase'>$horasXDia</td>";
						$contDias++;
					}while($contDias<$dias);
					*/
					$reg++;
					if($reg%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($reg<count($clave));
				mysql_close($conn);
				?>
			</table>
		</div>
		</body>
		<?php
	}//Cierre de la funcion exportarKardeDetalle($fechaI,$fechaF,$tipo,$criterio,$dias)
	
	function exportarKardeDetalleChecador($fechaI,$fechaF,$tipo,$criterio){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=kardex".date("Y-m-d").".xls");
		
		//Archivo requerido para la consulta de Kardex
		include_once("op_registrarNominaInterna.php");
		
		//Titulo
		$msg="PERIODO TERMINADO DEL $fechaI al $fechaF";
		?>				
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: medium; border-bottom-width: medium; border-left-width: medium; border-top-style: solid; border-right-style: solid;
				border-bottom-style: solid; border-left-style: solid; border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; vertical-align:middle;border-style:solid;border-color:#000000;border-width:thin;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; vertical-align:middle;border-style:solid;border-color:#000000;border-width:thin;}
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
					<td align="left" valign="baseline" colspan="2">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="170" height="50" align="absbottom" />
					</td>
					<td colspan="4">&nbsp;</td>
					<td valign="baseline" colspan="4">
						<div align="right"><span class="texto_encabezado">
							<strong>MANUAL DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
				</tr>											
				<tr>
					<td colspan="10" align="center" class="borde_linea">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA 
							SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
						</span>
					</td>
				</tr>
				<tr>
					<td colspan="10">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="10">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="10" align="center" class="titulo_tabla">CONTROL DIARIO DE ASISTENCIA</td>
				</tr>
				<tr>
					<td colspan="10" align="center" class="titulo_tabla"><?php echo $msg;?></td>
				</tr>
		<?php //Sumar dias a la fecha de Inicio y usar el formato correspondiente para la consulta
		$fechaI = sumarDiaFecha($fechaI,0);
		//Sumar dias a la fecha de Fin y usar el formato correspondiente para la consulta
		$fechaF = sumarDiaFecha($fechaF,1);
		//Recuperar el área seleccionada siempre y cuando este definida
		if($tipo == "area"){
			$area=$criterio;
			//Verificar si el área es una en especifico o se refiere a todas
			if ($area=="TODOS"){
				//Sentencia SQL para extraer a los Trabajadores del Área Seleccionada
				$sql = "SELECT T1.Userid, T1.name, T2.CheckTime, T2.Sensorid 
						FROM Userinfo AS T1 
						INNER JOIN Checkinout AS T2 
						ON T1.Userid = T2.Userid 
						WHERE T2.CheckTime BETWEEN #$fechaI#
						AND #$fechaF#
						ORDER BY T1.name, T2.CheckTime";
			}
			else{
				//Sentencia SQL para extraer a los Trabajadores del Área Seleccionada
				$sql = "SELECT T1.Userid, T1.name, T2.CheckTime, T2.Sensorid 
						FROM Userinfo AS T1 
						INNER JOIN Checkinout AS T2 
						ON T1.Userid = T2.Userid 
						WHERE T2.CheckTime BETWEEN #$fechaI#
						AND #$fechaF# 
						AND T1.DeptID = $area
						ORDER BY T1.name, T2.CheckTime";
			}
		}
		else{//Consulta usando Filtro por Nombre de Trabajador
			$nombre=$criterio;
			//Conectar a la BD de Recursos
			$conn=conecta("bd_recursos");
			//Sentencia SQL para extraer a los Trabajadores del Área Seleccionada
			$stm_sql = "SELECT id_empleados_empresa FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$nombre'";
			//Cuando exista el filtro de trabajador, modificar el valor de la variable de $incidenciaFechas para poder asignar la incidencia por todo el periodo
			$rs=mysql_query($stm_sql);
			//Verificar que existan registos
			if($datos=mysql_fetch_array($rs)){
				$sql = "SELECT T1.Userid, T1.name, T2.CheckTime, T2.Sensorid 
						FROM Userinfo AS T1 
						INNER JOIN Checkinout AS T2 
						ON T1.Userid = T2.Userid 
						WHERE T2.CheckTime BETWEEN #$fechaI#
						AND #$fechaF# 
						AND T1.Userid = '$datos[id_empleados_empresa]'
						ORDER BY T1.name, T2.CheckTime";
			}
			mysql_close($conn);
		}
		
		$fecha_temp = "0";
		$id_emp = "A";
		$hora_ini = "00:00:00";
		$hora_fin = "00:00:00";
		
		$conn_access = odbc_connect("EasyClocking","","");
		if($rs_access = odbc_exec ($conn_access,$sql)){
			
			$nom_clase = "renglon_gris";
			$cont = 1;
			
			$num_reg = 0;
			while(odbc_fetch_array($rs_access)){
				$num_reg++;
			}
			echo "
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>ID EMPLEADO</th>
						<th class='nombres_columnas' align='center'>NOMBRE</th>
						<th class='nombres_columnas' align='center'>D&Iacute;A</th>
						<th class='nombres_columnas' align='center'>FECHA</th>
						<th class='nombres_columnas' align='center'>ENTRADA</th>
						<th class='nombres_columnas' align='center'>LUGAR</th>
						<th class='nombres_columnas' align='center'>SALIDA</th>
						<th class='nombres_columnas' align='center'>LUGAR</th>
						<th class='nombres_columnas' align='center'>HORAS TRABAJADAS</th>
						<th class='nombres_columnas' align='center'>TIEMPO EXTRA</th>
					</tr>
				</thead>";
			
			echo "<tbody>";
			
			for($i=1; $i<=$num_reg; $i+=2){
				$datos = odbc_fetch_array($rs_access,$i);
				
				echo "<tr>
						<td class='$nom_clase' align='center'>$datos[Userid]</td>
						<td class='$nom_clase' align='center'>$datos[name]</td>
						<td class='$nom_clase' align='center'>".obtenerNombreDia(substr($datos['CheckTime'],0,10))."</td>
						<td class='$nom_clase' align='center'>".substr($datos['CheckTime'],0,10)."</td>
						<td class='$nom_clase' align='center'>".substr($datos['CheckTime'],-8)."</td>";
				
				if($datos["Sensorid"] == "34002474"){
					echo "<td class='$nom_clase' align='center'>Caseta 2</td>";
				}
				else if($datos["Sensorid"] == "34002473"){
					echo "<td class='$nom_clase' align='center'>Caseta 1</td>";
				}
				
				$hora_ini = substr($datos['CheckTime'],-8);
				
				$datos_temp = odbc_fetch_array($rs_access,$i+1);
				
				if(substr($datos['CheckTime'],0,10) == substr($datos_temp['CheckTime'],0,10) && $datos_temp["Userid"] == $datos["Userid"]){
					$hora_fin = substr($datos_temp['CheckTime'],-8);
					echo "<td class='$nom_clase' align='center'>".substr($datos_temp['CheckTime'],-8)."</td>";
				
					if($datos_temp["Sensorid"] == "34002474"){
						echo "<td class='$nom_clase' align='center'>Caseta 2</td>";
					}
					else if($datos_temp["Sensorid"] == "34002473"){
						echo "<td class='$nom_clase' align='center'>Caseta 1</td>";
					} else {
						echo "<td class='$nom_clase' align='center'></td>";
					}
				} else {
					$hora_fin = "00:00:00";
					echo "<td class='$nom_clase' align='center'></td>
							<td class='$nom_clase' align='center'></td>";
					$i--;
				}
					
				if($hora_fin == "00:00:00"){
					$dif = "00:00:00";
				}
				else{
					$dif = diferenciaHoras($hora_ini,$hora_fin);
				}
				if($dif > "08:00:00"){
					$extras = diferenciaHoras("08:00:00",$dif);
					$horas_trab = "08:00:00";
				}
				else{
					$extras = "00:00:00";
					$horas_trab = $dif;
				}
				echo "	<td class='$nom_clase' align='center'>".number_format(horaDecimal($horas_trab),2,".",",")."</td>
						<td class='$nom_clase' align='center'>".number_format(horaDecimal($extras),2,".",",")."</td>
					</tr>";
				
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
				
				if(substr($datos['CheckTime'],0,10) != "")
					$fecha_temp = substr($datos['CheckTime'],0,10);
				else
					$fecha_temp = "0";
				
				$id_emp = $datos["Userid"];
			}
			echo "</tbody>";
		}
		odbc_close($conn_access);
		?>
			</table>
		</div>
		</body>
		<?php
	}//Cierre de la funcion exportarKardeDetalleChecador($fechaI,$fechaF,$tipo,$criterio,$dias)
	
	/*Esta función exporta el detalle de los Abonos relaizados por un empleado al prestamo asignado*/
	function exportarReporteAbonos($idPrestamo){
		//Obtener el RFC asociado al Id del Prestamo
		$rfcEmpleado = obtenerDato("bd_recursos", "deducciones", "empleados_rfc_empleado", "id_deduccion", $idPrestamo);		
		//Obtener nombre completo del Empleado		
		$nombre = obtenerNombreEmpleado($rfcEmpleado);
			
		//Escribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$idPrestamo-$nombre.xls");?>
		
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna principal de una tabla*/
				.nombres_columnas { font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #FFFFFF; background-color: #9BBA59; font-weight: bold; 
				border-top-width: medium; border-right-width: thin; border-bottom-width: medium; border-left-width: thin; border-top-style: solid; border-right-style: none;
				border-bottom-style: solid; border-left-style: none; border-top-color: #000000; border-bottom-color: #000000; vertical-align:middle; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que se ocupa en la fila principal para resaltar datos  de una tabla*/
				.nombres_filas { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #9BBB59; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_gris { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #E7E7E7; }
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que contendra datos  de una tabla*/
				.renglon_blanco { font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #000000; background-color: #FFFFFF; } 
				#tabla { position:absolute; left:0px; top:0px; width:1111px; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 12px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				-->
			</style>
		</head>											
		<body>
		<div id="tabla">				
			<table width="1100">					
				<tr>
					<td align="left" valign="baseline" colspan="3">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
					</td>
					<td colspan="5">&nbsp;</td>
					<td valign="baseline" colspan="3">
						<div align="right"><span class="texto_encabezado">
							<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
				</tr>											
				<tr>
					<td colspan="11" align="center" class="borde_linea">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA 
							SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
						</span>
					</td>
				</tr>					
				<tr>
					<td colspan="11">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="11">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="11">&nbsp;</td>
				</tr><?php									
			
			
			//Conectar a la BD de Recursos Humanos
			$conn = conecta("bd_recursos");
			
											
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM detalle_abonos WHERE deducciones_id_deduccion = '$idPrestamo' ORDER BY fecha_abono";		
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Detalle del Prestamo Asignado al Empleado <em><u>$nombre</u></em><br>No. Prestamo <em><u>$idPrestamo</u></em>";				
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($sql_stm);										
			
			//No se confirma la existencia de datos, ya que eso se hace desde la pagina donde se manda llamar
			$datos=mysql_fetch_array($rs)
			
			//Desplegar los resultados de los prestamos a Empleado encontrados?>			
			<tr>
				<td colspan="3">&nbsp;</td>
				<td colspan="5" align="center" class="titulo_tabla"><?php echo $msg; ?></td>
				<td colspan="3">&nbsp;</td>
			</tr>
			<tr>			
				<td colspan="3">&nbsp;</td>
				<td class="nombres_columnas" align="center">NO.</td>
				<td class="nombres_columnas" align="center">FECHA</td>
				<td class="nombres_columnas" align="center">SALDO INICIAL</td>
				<td class="nombres_columnas" align="center">ABONO</td>
				<td class="nombres_columnas" align="center">SALDO FINAL</td>
				<td colspan="3">&nbsp;</td>
			</tr><?php

			$nom_clase = "renglon_gris";
			$cont = 1;
			$suma_abonos = 0;
	
			do{					
				//Mostrar cada Prestamo encontrado con los parametros seleccionados por el Usuario ?>				
				<tr>						
					<td colspan="3">&nbsp;</td>
					<td class="nombres_filas" align="center"><?php echo $cont; ?></td>
					<td class="<?php echo $nom_clase; ?>" align="center"><?php echo modFecha($datos['fecha_abono'],1); ?></td>
					<td class="<?php echo $nom_clase; ?>" align="right">$<?php echo number_format($datos['saldo_inicial'],2,",","."); ?></td>
					<td class="<?php echo $nom_clase; ?>" align="right">$<?php echo number_format($datos['abono'],2,",","."); ?></td>
					<td class="<?php echo $nom_clase; ?>" align="right">$<?php echo number_format($datos['saldo_final'],2,",","."); ?></td>
					<td colspan="3">&nbsp;</td>
				</tr><?php
			
				$suma_abonos += $datos['abono'];				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta?>								
				<tr>
					<td colspan="5">&nbsp;</td>
					<td class="nombres_columnas" align="right">TOTAL ABONADO</td>
					<td class="nombres_columnas" align="right">$<?php echo number_format($suma_abonos,2,".",","); ?></td>
					<td colspan="4">&nbsp;</td>
				</tr>
			</table>
			
		</div>
		</body><?php												
	}//Cierre exportarReporteAbonos($idPrestamo)
	
	
	//Funcion que obtiene las 3 primeras letras de los nombres de los Días de las fechas seleccionadas
	function obtenerDia($fecha,$contDias){
		//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
		$seccFecha = split("-",$fecha);
		//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y sumar 1 día
		$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + $contDias;
		//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
		$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
		//Obtener el nombre del Dia actual en Mayusculas
		$dia=strtoupper(obtenerNombreDia($fechaActual));
		$dia=str_replace("&AACUTE;","A",$dia);
		$dia=str_replace("&EACUTE;","E",$dia);
		return substr($dia,0,3);
	}//Fin de function obtenerDia($fecha,$contDias)
	
	
	//Funcion que obtiene la hora de checada en la fecha seleccionada segun la Entrada o Salida correspondiente
	function obtenerChecada($fecha,$contDias,$rfc,$tipo){
		//Separar la fecha actual, la cual esta en formato aaaa-mm-dd
		$seccFecha = split("-",$fecha);
		//Cambiar la fecha Gregoriana a Juliana, gregoriantojd(mes,dia,año) y sumar 1 día
		$fecha_enDias = gregoriantojd($seccFecha[1], $seccFecha[2], $seccFecha[0]) + $contDias;
		//Cambiar la fecha Juliana a Gregoriana en formato m/d/aaaa y pasar al formato dd/mm/aaaa y por ultimo pasarla al formarto aaaa-mm-dd
		$fechaActual = modFecha(formatFecha(jdtogregorian($fecha_enDias)),3);
		if($tipo=="in")
			//Sentencia SQL
			$sql="SELECT hora_checada FROM checadas WHERE empleados_rfc_empleado='$rfc' AND fecha_checada='$fechaActual' AND estado!='SALIDA' ORDER BY hora_checada";
		else
			//Sentencia SQL
			$sql="SELECT hora_checada FROM checadas WHERE empleados_rfc_empleado='$rfc' AND fecha_checada='$fechaActual' AND estado='SALIDA' ORDER BY hora_checada";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql);
		//Verificar el resultado
		if($datos=mysql_fetch_array($rs))
			$hora=$datos["hora_checada"];
		else
			$hora="&nbsp;";
		//Retornar el valor de la Hora
		return $hora;
	}//Cierre de la función obtenerChecada($fecha,$contDias,$rfc,$tipo)
	
	function sumarDiaFecha($fecha,$dias){
		list($day,$mon,$year) = explode('/',$fecha);
		return date('m/d/Y',mktime(0,0,0,$mon,$day+$dias,$year));
	}
	
	function numRegEmpleado($id_emp,$fecha_ini,$fecha_fin,$conexion){
		$sql2 = "SELECT T2.CheckTime 
				FROM Userinfo AS T1 
				INNER JOIN Checkinout AS T2 
				ON T1.Userid = T2.Userid 
				WHERE T2.CheckTime BETWEEN #$fecha_ini#
				AND #$fecha_fin# 
				AND T1.Userid = '$id_emp'";
				
		if($rs_access2 = odbc_exec ($conexion,$sql2)){
			$dia = "0";
			$num_dias = 0;
			while($datos2 = odbc_fetch_array($rs_access2)){
				if($dia != obtenerNombreDia(substr($datos2['CheckTime'],0,10)))
					$num_dias++;
				$dia = obtenerNombreDia(substr($datos2['CheckTime'],0,10));
			}
			$num_dias *= 2;
			if($num_dias%2 == 0)
				return $num_dias;
			else
				return $num_dias;
		}
	}
	
	function diferenciaHoras($inicio,$fin){
		$dif = date("H:i:s", strtotime("00:00:00") + strtotime($fin) - strtotime($inicio));
		return $dif;
	}
	
	function horaDecimal($hora){
		$dec = substr($hora,0,2) + (substr($hora,3,2) / 60);
		return $dec;
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