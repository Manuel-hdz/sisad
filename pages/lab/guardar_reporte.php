<?php 
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 25/Junio/2011                                      			
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
		
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		

	switch($hdn_origen){
		case "reporteAgregado":
			guardarRepAgregados($hdn_tituloTabla, $hdn_nomReporte, $hdn_PBM);				
		break;	
		case "reporteMezclas":
			guardarRepMezclas($hdn_nomReporte, $hdn_msg, $hdn_consulta);
		break;	
		case "reporteMttoEquipoLab":
			guardarRepMttoEquipoLab($hdn_nomReporte,$hdn_msg);
		break;
		case "reporteRendimiento":
			guardarRepRendimiento($hdn_nomReporte, $hdn_consulta, $hdn_idRegRendimiento);
		break;	
	}
	
	if(isset($_POST['sbt_excel'])){
		if(isset($_POST['hdn_consulta'])){
		
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
	
		//Esta funcion exporte el REPORTE  a un archivo de excel
	function guardarRepRendimiento($hdn_nomReporte, $hdn_consulta, $hdn_idRegRendimiento){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		
		//Separar el Nombre del Reporte (ReporteRendimiento_1/300-20-NT) para obtener el ID del Registro de Rendimiento y el ID de la Mezcla				
		$seccNomReporte = split("/", $hdn_nomReporte);
		$idMezcla = $seccNomReporte[1];
		//Obtener el Nombre de la Mezcla
		$nomMezcla = obtenerDato("bd_laboratorio", "mezclas", "nombre", "id_mezcla", $idMezcla);
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
			
																						
		/********************************************OBTENER LOS DATOS DEL DISEÑO DE LA MEZCLA********************************************/
		//Verificar si el diseño original fue modificado, buscar en la tabla de Cambios Diseño Mezcla primero
		$sql_stm_mat1 = "SELECT * FROM cambios_disenio_mezcla WHERE rendimiento_id_registro_rendimiento = $hdn_idRegRendimiento AND mezclas_id_mezcla = '$idMezcla'";
		$sql_stm_mat2 = "SELECT * FROM materiales_de_mezclas WHERE mezclas_id_mezcla='$idMezcla'";
		
		$sql_stm_materiales = "";
		
		//Verificar si la primera consulta regresa datos, para tomar el diseño de la mezcla de ahi
		if($datos=mysql_fetch_array(mysql_query($sql_stm_mat1)))
			$sql_stm_materiales = $sql_stm_mat1;		
		else//Si el diseño no fue modificado, tomar los datos de la segunda consulta
			$sql_stm_materiales = $sql_stm_mat2;		
																
		//Ejecutar la Sentencia SQL para obtener los datos del Diseño de la Mezcla seleccionada
		$rs_materiales = mysql_query($sql_stm_materiales);			
		//Cerrar la Conexion con la BD de Laboratorio
		mysql_close($conn);
		
		
		//Arreglo que almacena los nombres de los materiales
		$nombresMat = array();
		//Arreglo para Almacenar los volumenes de los Materiales
		$cantidadesMat = array();
		//Arreglo que permite guardar las unidades
		$unidadesMat = array();		
		
		$cont=1;	
		//Verificar que la consulta tenga datos
		if($datosMat=mysql_fetch_array($rs_materiales)){
			do{						
				//Recuperar datos adicionales del los materiales de la mezcla seleccionada
				$nomMaterial = obtenerDato('bd_almacen', 'materiales', 'nom_material', 'id_material', $datosMat['catalogo_materiales_id_material']);
											
				//Guardamos los nombres de los materiales en el arreglo; se obtiene en obtener dato $nomMaterial
				$nombresMat[] = $nomMaterial;
				//Almacenamos los volumenes
				$cantidadesMat[] = $datosMat['cantidad'];
				//Almacenamos las unidades
				$unidadesMat[] = $datosMat['unidad_medida'];
				//incrementamos el contador
				$cont++;
			}while($datosMat=mysql_fetch_array($rs_materiales));
		}//Cierre if($datosMat=mysql_fetch_array($rs_materiales))
				
		
		/********************************************OBTENER LOS DATOS DEL RENDIMIENTO Y LA MEZCLA********************************************/
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
				
		$rs_rendimiento = mysql_query("SELECT * FROM rendimiento JOIN mezclas ON mezclas_id_mezcla=id_mezcla 
									WHERE id_registro_rendimiento = $hdn_idRegRendimiento AND id_mezcla = '$idMezcla'");		
		//Guardamos los datos del Detalle del Rendimiento en las variables que serán mostradas
		if($datos_rend=mysql_fetch_array($rs_rendimiento)){					
			//Recuperar los datos de la Mezcla						
			$expediente = $datos_rend['expediente'];
			$equipo_mezclado = $datos_rend['equipo_mezclado'];
			//Recuperar los datos generales del Rendimiento
			$num_muestra = $datos_rend['num_muestra'];
			$localizacion = $datos_rend['localizacion'];
			$revenimiento = $datos_rend['revenimiento'];		
			$temperatura = $datos_rend['temperatura'];
			$hora = $datos_rend['hora'];
			$fechaRegistro = $datos_rend['fecha_registro'];
			$observaciones = $datos_rend['observaciones'];
			$notas = $datos_rend['comentarios'];
		}										
		
		/********************************************OBTENER LOS DATOS DEL DETALLE DEL RENDIMIENTO********************************************/
		//Ejecutamos la consulta para obtener el Detalle del Rendimiento de la Mezcla Seleccionada que viene en el POST
		$rs_detalleRend = mysql_query($hdn_consulta);		
		//Guardamos los datos del Detalle del Rendimiento en las variables que serán mostradas
		if($datos_detalleRend=mysql_fetch_array($rs_detalleRend)){			
			$pvol_bruto = round($datos_detalleRend['pvol_bruto'],5);
			$pvol_molde = round($datos_detalleRend['pvol_molde'],5);
			$pvol_unit = round($datos_detalleRend['pvol_unit'],5);
			$factor_recipiente = round($datos_detalleRend['factor_recipiente'],5);
			$pvol_teorico_rend = round($datos_detalleRend['pvol_teorico_rend'],5);
			$pvol_rend = round($datos_detalleRend['pvol_rend'],5);
			$pvol_teorico_caire = round($datos_detalleRend['pvol_teorico_caire'],5);
			$pvol_caire = round($datos_detalleRend['pvol_caire'],5);
			$cb = round($datos_detalleRend['cb'],5);
			$r = round($datos_detalleRend['r'],5);				
			$caireReal = round($datos_detalleRend['caire_real'],5);
		}
				
		/********************************************OBTENER LAS PRUEBAS REALIZADAS********************************************/		
		$normas = array();
		$rs_pruebasEjec = mysql_query("SELECT catalogo_pruebas_id_prueba, norma, nombre 
										FROM pruebas_realizadas JOIN catalogo_pruebas ON catalogo_pruebas_id_prueba=id_prueba										
										WHERE rendimiento_id_registro_rendimiento = $hdn_idRegRendimiento");
		//Guardamos los datos del Detalle del Rendimiento en las variables que serán mostradas
		if($datos_pruebasEjec=mysql_fetch_array($rs_pruebasEjec)){
			do{
				$normas[] = $datos_pruebasEjec['norma'].", ".$datos_pruebasEjec['nombre'];
			}while($datos_pruebasEjec=mysql_fetch_array($rs_pruebasEjec));
		}
				
				
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }				
				.Estilo9 {font-size: 14px; color:#0000CC; font-weight: bold;}
				.Estilo10 {font-size: 14px; color:#000000; font-weight: bold;}
				.borde_linea {border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_celda {
					border-top-width: medium; border-top-style: solid; border-top-color: #000000;	
					border-right-width: thin; border-right-style: solid; border-right-color: #000000;
					border-left-width: thin; border-left-style: solid; border-left-color: #000000;
					border-bottom-width: medium; border-bottom-style: solid; border-bottom-color: #000000;						
				}								
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }				
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-size: 12px; font-weight:bold; }
			-->
			</style>
		</head>	
		<body>		
		<table width="1020">
        	<tr>
            	<td colspan="5">
					<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />				
				</td>
            	<td colspan="6">
					<div align="right"> 
						<span class="texto_encabezado">
							<strong>LABORATORIO DE CONTROL DE CALIDAD</strong><br>
							<em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V</em>
					  </span>						
				  </div>
				</td>
          	</tr>
          	<tr>
            	<td colspan="11" align="center" class="borde_linea">
					<span class="sub_encabezado"> 
						CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI&Oacute;N TOTAL O PARCIAL
					</span>				
				</td>
          	</tr>
          	<tr>
		  		<?php //Aqui se especifica el ancho que tendra cada columna de acuerdo al diseño?>
				<td width="80">&nbsp;</td>
            	<td width="150">&nbsp;</td>
            	<td width="80">&nbsp;</td>
            	<td width="80">&nbsp;</td>
            	<td width="80">&nbsp;</td>
				<td width="80">&nbsp;</td>
				<td width="80">&nbsp;</td>
				<td width="150">&nbsp;</td>
				<td width="80">&nbsp;</td>
				<td width="80">&nbsp;</td>
				<td width="80">&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11" class="borde_celda"><div align="center"  class="Estilo10">REPORTE DE RENDIMIENTO EN OBRA PARA OBRA EN INTERIOR MINA</div></td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
            	<td>&nbsp;</td>
            	<td colspan="5" class="titulo_tabla"><?php echo strtoupper($_GET['nombre']); ?></td>
				<td class="titulo_tabla" align="right">Fecha:</td>
				<td colspan="2" class="titulo_tabla"><?php echo verFecha(1);?></td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
         	</tr>
          	<tr>
            	<td>&nbsp;</td>
           		<td colspan="10" class="titulo_tabla"><?php echo strtoupper($_GET['puesto']); ?></td>
          	</tr>
          	<tr>
          		<td>&nbsp;</td>
            	<td colspan="10" class="titulo_tabla"><?php echo strtoupper($_GET['empresa']); ?></td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
           	  	<td>&nbsp;</td>
           	  	<td class="borde_celda"><div align="center" class="Estilo9">EXPEDIENTE:</div></td>
            	<td colspan="3"><div align="center" class="titulo_tabla"><?php echo $expediente;?></div></td>
				<td colspan="2">&nbsp;</td>
           	  	<td class="borde_celda"><div align="center" class="Estilo9">N. MUESTRA:</div></td>
            	<td colspan="2"><div align="center" class="titulo_tabla"><?php echo $num_muestra;?></div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
			  	<td><div align="center"></div></td>
			  	<td class="borde_celda"><div align="center" class="Estilo9">LOCALIZACI&Oacute;N:</div></td>
				<td colspan="3"><div align="center" class="titulo_tabla"><?php echo $localizacion;?></div></td>
				<td colspan="2">&nbsp;</td>
				<td class="borde_celda"><div align="center" class="Estilo9">REVENIMIENTO:</div></td>
				<td colspan="2"><div align="center" class="titulo_tabla"><?php echo $revenimiento;?> CM.</div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
			  	<td><div align="center"></div></td>
			  	<td class="borde_celda"><div align="center" class="Estilo9">EQUIPO DE MEZCLADO: </div></td>
				<td colspan="3"><div align="center" class="titulo_tabla"><?php echo $equipo_mezclado;?></div></td>
				<td colspan="2">&nbsp;</td>
				<td class="borde_celda"><div align="center" class="Estilo9">HORA:</div></td>
				<td colspan="2"><div align="center" class="titulo_tabla"><?php echo substr($hora,0,5);?> HRS.</div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
			  	<td><div align="center"></div></td>
			  	<td class="borde_celda"><div align="center" class="Estilo9">TEMPERATURA:</div></td>				
				<td colspan="3"><div align="center" class="titulo_tabla"><?php echo $temperatura;?>&deg;C</div></td>
				<td colspan="2">&nbsp;</td>
				<td class="borde_celda"><div align="center" class="Estilo9">TIPO DE MEZCLA: </div></td>
				<td colspan="2"><div align="center" class="titulo_tabla"><?php echo $nomMezcla;?></div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="5" class="Estilo10"><div align="center">DOSIFICACI&Oacute;N</div></td>
				<td colspan="4">&nbsp;</td>
			</tr>
          	<tr>
				<td>&nbsp;</td>
				<td colspan="7" class="titulo_tabla" align="center">El análisis del rendimiento presentado, es en base al diseño que se muestra a continuación:</td>
				<td colspan="3">&nbsp;</td>
			</tr>
		   	<tr><td colspan="11">&nbsp;</td></tr>
		   	<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">MATERIALES</div></td>
				<td colspan="2"class="borde_celda"><div align="center" class="Estilo9">1 m&sup3;</div></td>
				<td class="borde_celda"><div align="center" class="Estilo9">UNIDAD</div></td>
				<td colspan="4">&nbsp;</td>
          	</tr><?php		
				//Este ciclo nos permite recorrer el arreglo de cantidades y el de los nombres de los materiales; para dibujar la tabla de manera dinamica				
				$totales = 0;
				foreach($cantidadesMat as $ind => $cantidad){
					//Formatear la cantidad del material que va a ser desplegado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales($cantidadesMat[$ind]);
					$cantFormat = number_format($cantidadesMat[$ind],$decs,".",",");?>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td colspan="2" class="borde_celda"><div align="center" class="Estilo9"><?php echo $nombresMat[$ind];?></div></td>
						<td colspan="2" class="borde_celda"><div align="center" class="Estilo9"><?php echo $cantFormat;?></div></td>
						<td class="borde_celda"><div align="center" class="Estilo9"><?php echo $unidadesMat[$ind];?></div></td>
						<td colspan="4">&nbsp;</td><?php 
						//Obtener el total de las cantidades de los materiales listados
						$totales = $totales+str_replace(",","",$cantidadesMat[$ind]);?>
					</tr><?php					 
				}//Cierre foreach($cantidadesMat as $ind => $cantidad)?>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">TOTALES</div></td><?php
					//Formatear el Total de la Suma de los pesos de los materiales de la mezcla, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($totales,5));
					$totalFormat = number_format($totales,$decs,".",",");?>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9"><?php echo $totalFormat;?></div></td>
				<td class="borde_celda">&nbsp;</td>
				<td colspan="4">&nbsp;</td>
			</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
				<td>&nbsp;</td>
				<td class="borde_celda"><div align="center" class="Estilo9">P. VOL. (KG/M&sup3;) </div></td><?php 
					$pVol = ($pvol_bruto-$pvol_molde)*$factor_recipiente; 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pVol,5));
					$pVolFormat = number_format($pVol,$decs,".",",");?>
			  	<td colspan="2"><div align="center" class="Estilo10"><?php echo $pVolFormat; ?> KG/M&sup3;</div></td>
				<td colspan="3">&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">RENDIMIENTO (M&sup3;) </div></td><?php 
					$rendimiento = $pvol_teorico_rend/$pvol_rend; 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($rendimiento,5));
					$rendFormat = number_format($rendimiento,$decs,".",",");?>
			  	<td colspan="2"><div align="center" class="Estilo10"><?php echo $rendFormat; ?> M&sup3;</div></td>
         	</tr>
         	<tr>
				<td>&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO BRUTO  </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_bruto,5));
					$pvolBrutoFormat = number_format($pvol_bruto,$decs,".",",");
					echo $pvolBrutoFormat;?></div>
				</td>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO VOL. TE&Oacute;RICO </div></td>
			 	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_teorico_rend,5));
					$pvolTeoricoFormat = number_format($pvol_teorico_rend,$decs,".",",");
					echo $pvolTeoricoFormat;?></div>
				</td>
				<td colspan="2">&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO MOLDE</div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_molde,5));
					$pvolMoldeFormat = number_format($pvol_molde,$decs,".",",");
					echo $pvolMoldeFormat;?></div></td>
				<td>&nbsp;</td>
				<td colspan="3">&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO VOL. </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_rend,5));
					$pvolRendFormat = number_format($pvol_rend,$decs,".",",");
					echo $pvolRendFormat;?></div>
				</td>
				<td colspan="2">&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>
				<td><div align="left" class="titulo_tabla">PESO UNITARIO </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($pvol_unit,5));
					$pvolUnitFormat = number_format($pvol_unit,$decs,".",",");
					echo $pvolUnitFormat;?></div>
				</td>
				<td>&nbsp;</td>
				<td colspan="7">&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>
				<td><div align="left" class="titulo_tabla">FACTOR RECIPIENTE</div></td>
			 	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
					$decs = contarDecimales(round($factor_recipiente,5));
					$factorFormat = number_format($factor_recipiente,$decs,".",",");
					echo $factorFormat;?></div>
				</td>
				<td colspan="4">&nbsp;</td>          					
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">CONTENIDO REAL DE CEMENTO (KG)</div></td>
			  	<td colspan="2"><div align="center" class="Estilo10"><?php 
					$contRealCemento = $cb/$r;
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($contRealCemento,5));
					$contRealFormat = number_format($contRealCemento,$decs,".",",");
					echo $contRealFormat; ?> KG</div></td>				
			</tr>	
			<tr>
				<td colspan="7">&nbsp;</td>
				<td><div align="left" class="titulo_tabla">Cb</div></td>
			  	<td><div align="right" class="titulo_tabla"><?php echo $cb;?></div></td>
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="7">&nbsp;</td>
				<td><div align="left" class="titulo_tabla">R </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php echo $r;?></div></td>	
				<td colspan="2">&nbsp;</td>
			</tr>	
			<tr>
				<td>&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">CONTENIDO DE AIRE (%)</div></td><?php 				
					$contAire = (($pvol_rend-$pvol_teorico_rend)/$pvol_rend)*100; 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($contAire,5));
					$contAireFormat = number_format($contAire,$decs,".",",");?>					
			  	<td><div align="center" class="Estilo10"><?php echo $contAireFormat; ?> %</div></td>								
				<td colspan="7" width="40">&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>				
				<td><div align="left" class="titulo_tabla">PESO VOLUMETRICO</div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($pvol_rend,5));
					$pvolRendFormat = number_format($pvol_rend,$decs,".",",");
					echo $pvolRendFormat;?></div></td>
				<td colspan="4">&nbsp;</td>
				<td colspan="2" class="borde_celda"><div align="center" class="Estilo9">CONTENIDO REAL DE AIRE (%)</div></td>
			  	<td><div align="center" class="Estilo10"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($caireReal,5));
					$cAireFormat = number_format($caireReal,$decs,".",",");
					echo $cAireFormat; ?> %</div></td>
				<td>&nbsp;</td>
          	</tr>
          	<tr>
				<td>&nbsp;</td>								
				<td><div align="left" class="titulo_tabla">PESO MEZCLA </div></td>
			  	<td><div align="right" class="titulo_tabla"><?php 
					//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
					$decs = contarDecimales(round($pvol_teorico_rend,5));
					$pVolTeoFormat = number_format($pvol_teorico_rend,$decs,".",",");
					echo $pVolTeoFormat;?></div></td>										
				<td colspan="8">&nbsp;</td>
          	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>          	
          	<tr>
				<td>&nbsp;</td>
            	<td colspan="9" rowspan="5" class="borde_celda" valign="top">
					<span class="Estilo9">OBSERVACIONES:</span>
					<br>
              		<span class="titulo_tabla"><?php echo $observaciones;?></span>				
				</td>
				<td>&nbsp;</td>
            </tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
		  		<td>&nbsp;</td>
            	<td colspan="9" rowspan="2" class="titulo_tabla">					
					NOTA: EL CÁLCULO DE RENDIMIENTO SE HACE PARA 1m³, UTILIZANDO TODOS LOS PESOS DE DOSIFICACIÓN QUE SE REQUIEREN PARA LA MEZCLA.
					<br>
					CON LA SIGUIENTE FORMULA:
				</td>
				<td>&nbsp;</td>
         	</tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="10"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/lab/images/rpt-rendimiento-formula.png" ></td>
			</tr>
			<tr><td colspan="11">&nbsp;</td></tr>
			<tr><td colspan="11">&nbsp;</td></tr><?php
			//Colocar cada norma en un renglon
			foreach($normas as $ind => $norma){?>
				<tr>
					<td>&nbsp;</td>
					<td colspan="9" class="titulo_tabla">
						<?php echo $norma; ?>
					</td>
					<td>&nbsp;</td>
				</tr><?php
			}?>								          	
          	<tr><td colspan="11">&nbsp;</td></tr>
		  	<tr><td colspan="11">&nbsp;</td></tr>
		  	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
				<td>&nbsp;</td>
				<td colspan="4">__________________________________________</td>
				<td colspan="6">&nbsp;</td>
			</tr>
          	<tr>
				<td>&nbsp;</td>
				<td align="center"colspan="4"><div class="titulo_tabla" align="center">JEFE DE LABORATORIO</div></td>
				<td colspan="6">&nbsp;</td>
			</tr>
          	<tr>
				<td>&nbsp;</td>
				<td align="center"colspan="4"><div class="titulo_tabla" align="center">ING. EDGAR ALAN GARCIA CRUZ</div></td>
				<td colspan="6">&nbsp;</td>
			</tr>
		  	<tr><td colspan="11">&nbsp;</td></tr>
		  	<tr><td colspan="11">&nbsp;</td></tr>
          	<tr>
				<td>&nbsp;</td>
				<td colspan="5">
					<div class="Estilo9" align="left">C.C.P. ING. JAVIER AGUAYO SANCHEZ. GERENTE T&Eacute;CNICO.</div>
				</td>
				<td colspan="5">&nbsp;</td>
			</tr>
          	<tr>
				<td>&nbsp;</td>
				<td colspan="3"><div class="Estilo9" align="left">C.C.P. ARCHIVO.</div></td>
				<td colspan="7">&nbsp;</td>
			</tr>
        </table>
		</body><?php					
	}//Fin de la Funcion guardarRepRendimiento($hdn_nomReporte, $hdn_consulta, $hdn_idRegRendimiento)
	
	
	//Esta funcion exporte el REPORTE  a un archivo de excel
	function guardarRepAgregados($hdn_tituloTabla, $hdn_nomReporte, $hdn_PBM ){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls"); 

		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
		
		//Creamos la consulta que nos permitira guardar los conceptos necesarios para la correctar elaboración del reporte
		$sql_base="SELECT pvss_wm, pvss_vm, pvsc_wm ,pvsc_vm, densidad_msss, densidad_va, absorcion_msss, absorcion_ws, granulometria, origen_material, 
		                modulo_finura, nom_material, pl_wsc, pl_ws, fecha FROM (pruebas_agregados JOIN bd_almacen.materiales ON id_material=catalogo_materiales_id_material)
						WHERE id_pruebas_agregados='$hdn_PBM'";
		//Ejecutar la sentencia y almacena los 	datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($sql_base);

		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs_datos)){
			//Almacenamos los datos en variables para manejarlos dentro del reporte
			$pvss_wm=$datos['pvss_wm'];
			$pvss_vm=$datos['pvss_vm'];
			$pvsc_wm=$datos['pvsc_wm'];
			$pvsc_vm=$datos['pvsc_vm'];
			$densidad_msss=$datos['densidad_msss'];
			$densidad_va=$datos['densidad_va'];
			$absorcion_msss=$datos['absorcion_msss'];
			$absorcion_ws=$datos['absorcion_ws'];
			$granulometria=$datos['granulometria'];
			$origen_material=$datos['origen_material'];
			$modulo_finura=$datos['modulo_finura'];
			$nom_material=$datos['nom_material'];
			$fecha=$datos['fecha'];
			$pl_wsc=$datos['pl_wsc'];
			$pl_ws=$datos['pl_ws'];
		}
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
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_tablas {font-family: Arial, Helvetica, sans-serif;	font-size: 12px;	font-weight: normal;	border-top-width: medium;	
				border-right-width:  thin;	border-bottom-width: medium;	border-left-width: thin;	border-top-style: solid;	border-right-style: solid;
				border-bottom-style: solid;	border-left-style: solid;	border-top-color: #000000;	border-bottom-color: #000000;	border-left-color: #000000;
				border-right-color: #000000;}
				.Estilo6 {font-size: 14;color:#0000CC;font-weight: bold;}
				.Estilo4 {font-size: 14px; color:#0000CC; font-weight: bold;}
				.Estilo5 {font-size: 14px; color:#000000; font-weight: bold;}
				.Estilo7 {font-size: 14px; color:#0000CC;  font-weight:lighter;}
				.caracter{color:#FFFFFF;}
				.Estilo12 {font-size: 14px; color:#000000; font-weight: bold;}
				.Estilo12 {font-size: 12px}
				.Estilo13 {font-size: 14px; color:#000000; font-weight: bold;}
				.Estilo13 {font-size: 12px}
			-->
			</style>
		</head>													
		<body>
			<table width="949" border="0" >
            	<tr>
                	<td align="left" valign="baseline" colspan="2">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" /></td>
                	<td width="82"></td>
                	<td width="75"></td>
                	<td width="80"></td>
                	<td width="75"></td>
                	<td width="84"></td>
					<td colspan="5">
						<div align="right" class="sub_encabezado"> 
						<span class="texto_encabezado"><strong>LABORATORIO DE CONTROL DE CALIDAD</strong><br>
						<em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V</em></span><span class="texto_encabezado1 Estilo2">
						<span class="Estilo3"><em>.</em></span> </span></div>					
					</td>
			  	</tr>
              	<tr>
              		<td colspan="12" align="center" class="borde_linea">
						<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                 		&Oacute;N TOTAL O PARCIAL</span>					
					</td>
              	</tr>
              	<tr><td colspan="12">&nbsp;</td></tr>
              	<tr><td colspan="12">&nbsp;</td></tr>
			  	<tr><td colspan="12">&nbsp;</td></tr>
              	<tr>
			  		<td>&nbsp;</td>
              		<td colspan="10" align="center" class="nombres_tablas">
			  			<span class="Estilo4">F 4.6.0 - 03 REPORTE DE ESTUDIO DE AGREGADOS PARA CONCRETO - <?php echo  $hdn_tituloTabla." ".$origen_material; ?></span>
					</td>
					<td>&nbsp;</td>
              	</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td width="72">&nbsp;</td>
					<td class="Estilo5">Dirigido a:</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td colspan="2" class="Estilo5"><div align="right">Fecha Muestreo: &nbsp;</div></td>
					<td colspan="4" class="Estilo5"><div align="left"><span class="Estilo13"><?php echo modFecha($fecha,2); ?></span></div></td>
	 	  	    </tr>
			 	<tr>
					<td>&nbsp;</td>
					<td width="82" class="Estilo5">&nbsp;</td>
					<td colspan="3" class="Estilo5"><em>Ing. Guillermo Mart&iacute;nez</em></td>
				    <td class="Estilo5">&nbsp;</td>
				    <td colspan="2" class="Estilo5"><div align="right">Fecha Reporte:&nbsp; </div></td>
				    <td colspan="3" class="Estilo5"><span class="Estilo12"><span class="Estilo11">Fllo, Zacatecas <?php echo modFecha(date("Y-m-d"),2);?></span></span></td>
				    <td class="Estilo5">&nbsp;</td>
				    <td class="Estilo5">&nbsp;</td>
			 	</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="10" class="Estilo5"><em>Gerente General </em></td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="10" class="Estilo5"><em>Concreto Lanzado de Fresnillo  S.A. de C.V. </em></td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3" class="Estilo5"><div align="center"><strong class="Estilo5"><em>PVSS (Kg/m&sup3;)  :</em></strong></div></td>
					<td class="Estilo5"><div align="center"><strong class="Estilo5"><?php echo round(($pvss_wm/$pvss_vm)*1000,2);?></strong></div></td>
					<td colspan="2">&nbsp;</td>
					<td colspan="3" class="Estilo5"><div align="center"><em>PVSC (Kg/m&sup3;)  :</em></div></td>
					<td width="83" class="Estilo5"><div align="center"><?php echo round(($pvsc_wm/$pvsc_vm)*1000,2);?></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><div align="center" class="Estilo5">Wm:</div></td>
					<td><div align="right"><span class="Estilo5"><?php echo $pvss_wm;?></span></div></td>
					<td class="Estilo5">Kg</td>
					<td><div align="center"></div></td>
					<td colspan="2">&nbsp;</td>
					<td width="81" class="Estilo5"><div align="center" class="Estilo5">Wm:</div></td>
					<td width="83" class="Estilo5"><div align="right"><?php echo $pvsc_wm;?></div></td>
					<td width="83" class="Estilo5">Kg</td>
					<td width="83" class="Estilo4"><div align="center"></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><div align="center" class="Estilo5">Vm:</div></td>
					<td><div align="right"><span class="Estilo5"><?php echo $pvss_vm; ?></span></div></td>
					<td class="Estilo5">Lts</td>
					<td><div align="center"></div></td>
					<td colspan="2">&nbsp;</td>
					<td class="Estilo5"><div align="center" class="Estilo5">Vm:</div></td>
					<td width="83" class="Estilo5"><div align="right"><?php echo $pvsc_vm;?></div></td>
					<td width="83" class="Estilo5">Lts</td>
					<td width="83" class="Estilo4"><div align="center"></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr><td>&nbsp;</td>
					<td>&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td colspan="2">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td class="Estilo5">&nbsp;</td>
					<td width="83" class="Estilo5">&nbsp;</td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3" class="Estilo5"><div align="center"><strong class="Estilo5"><em>DENSIDAD (gr/cm&sup3;)  :</em></strong></div></td>
					<td class="Estilo5"><div align="center"><strong class="Estilo5"><?php echo round(($densidad_msss/$densidad_va),2);?></strong></div></td>
					<td colspan="2">&nbsp;</td>
					<td colspan="3" class="Estilo5"><div align="center"><em>ABSORCI&Oacute;N (%)  :</em></div></td>
					<td width="83" class="Estilo5"><div align="center"><?php echo round((($absorcion_msss-$absorcion_ws)/$absorcion_ws)*100,2);?></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><div align="center" class="Estilo5">Msss:</div></td>
					<td><div align="right"><span class="Estilo5"><?php echo $densidad_msss;?></span></div></td>
					<td class="Estilo5">gr</td>
					<td><div align="center"></div></td>
					<td colspan="2">&nbsp;</td>
					<td width="81" class="Estilo5"><div align="center" class="Estilo5">Msss:</div></td>
					<td width="83" class="Estilo5"><div align="right"><?php echo $absorcion_msss;?></div></td>
					<td width="83" class="Estilo5">gr</td>
					<td width="83" class="Estilo4"><div align="center"></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td><div align="center" class="Estilo5">Va:</div></td>
					<td><div align="right"><span class="Estilo5"><?php echo $densidad_va; ?></span></div></td>
					<td class="Estilo5">cm&sup3;</td>
					<td><div align="center"></div></td>
					<td colspan="2">&nbsp;</td>
					<td class="Estilo5"><div align="center" class="Estilo5">Ws:</div></td>
					<td width="83" class="Estilo5"><div align="right"><?php echo $absorcion_ws;?></div></td>
					<td width="83" class="Estilo5">gr</td>
					<td width="83" class="Estilo4"><div align="center"></div></td>
					<td width="19">&nbsp;</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr><?php 
				//Creamos la variable cadena para almacenar el nombre del material
				$cadena=$nom_material;
				//Creamos la varialble la cual contendra el concepto a buscar
				$cadenaBusq="ARENA";
				//Comparamos si viene ARENA en $cadena entonces dibujamos el renglon
				if(stristr($cadena, $cadenaBusq)==true){?>
					<tr>
					  <td colspan="12" align="right" class="Estilo5"><em>M&Oacute;DULO FINURA :</em> <?php echo $modulo_finura;?></td>
					</tr>
					<tr>
					  <td colspan="12" align="right" class="Estilo5">
					  	<em>P&Eacute;RDIDA POR LAVADO(%):</em> <?php echo round(((bcsub($pl_wsc,$pl_ws))/$pl_ws)*100,2);?>
					  </td>
					</tr><?php 
				}?>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="2" class="Estilo5">GRANULOMETR&Iacute;A:</td>
					<td colspan="9">&nbsp;</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="2" class="Estilo5"><?php echo $granulometria;?> </td>

					<td colspan="7">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="3" class="nombres_tablas"><div align="center" class="Estilo5">MALLAS</div></td>
				
					<td colspan="3" class="nombres_tablas"><div align="center" class="Estilo5">% QUE PASA </div></td>
					<td colspan="3" class="nombres_tablas"><div align="center" class="Estilo5">% RETENIDO ACUMULADO </div></td>
					<td>&nbsp;</td>
				</tr><?php 
				
				//Consulta para obtener conceptos, retenido, limite inferior asu como limite superior para realizar las operacionesq ue permiten los calculos en el reporte
				$sql_detalle="SELECT  concepto, retenido,limite_inferior, limite_superior FROM detalle_prueba_agregados 
							  WHERE pruebas_agregados_id_pruebas_agregados='$hdn_PBM' ORDER BY numero DESC";
							  
				//Ejecutar la sentencia y almacena los 	datos de la consulta 
				$rs_detalle = mysql_query($sql_detalle);
		
				//Variable para guardar el total retenido
				$totalRetenido=0;
				//Arreglo para guardar la consulta; y asi permitir mostrar todos los registros al mismo tiempo
				$consultaConceptos=array();
				//Arreglo para Almacenar el limite inferior
				$limiteInferior=array();
				//Arreglo para Almacenar el limite superior
				$limiteSuperior=array();
				//Verificar que la consulta tenga datos
				if($datos=mysql_fetch_array($rs_detalle)){					
					do{
						//Acumulamos el total retenido
						$totalRetenido+=$datos['retenido'];
						//Almacenamos los conceptos
						$consultaConceptos[]=$datos['concepto'];
						//Almacenamos los limites_inferiores
						$limiteInferior[]=$datos['limite_inferior'];
						//Almacenamos los limites Superiores
						$limiteSuperior[]=$datos['limite_superior'];	
					}while($datos=mysql_fetch_array($rs_detalle));
				}
				//Consulta que permite obtener el numero y el retenido de cada agregado
				$sql_detalleASC="SELECT numero, retenido FROM detalle_prueba_agregados WHERE pruebas_agregados_id_pruebas_agregados='$hdn_PBM' ORDER BY numero";

				//Ejecutar la sentencia y almacena los 	datos de la consulta 
				$rs_detalleASC = mysql_query($sql_detalleASC);
				//Comprobamos que la consulta tiene datos
				if($datos=mysql_fetch_array($rs_detalleASC)){
					//Creamos el arreglo para guardar el porcentaje retenido
					$porcentajeRetenido=array();
					//Igualamos el total retenido 
					$totalRetenido=$totalRetenido;
					do{	
						//Almacenamos la operación necesaria para obtener el porcentaje Retenido	
						$porcentajeRetenido[]=(($datos['retenido']/$totalRetenido)*100);
					}while($datos=mysql_fetch_array($rs_detalleASC));
				}
				//Variable para controlar la cantidad de datos
				$tam=count($porcentajeRetenido);
				//Arrreglo para obtener el porcentaje retenido acumulado
				$porcentajeRetenidoAcumulado=array();
				//Guardamos el porcentaje retenido en su ultima posición como la primera posición del porcentaje retenido acumulado
				$porcentajeRetenidoAcumulado[]=$porcentajeRetenido[$tam-1];
				//Variable para controlar internamente el ciclo
				$band=0;
				//Variable para controlar la posicion inicial del arreglo (segun formula)
				$ctrl=$tam-2;
				do{
					//Almacenamos en el porcentaje retenido Acumulado la suma del porcentaje retenido mas el pocentaje retenido acumulado, bcadd tiene como 
					//objetivo obtener el resultado con un punto de presicion
					$porcentajeRetenidoAcumulado[]=bcadd($porcentajeRetenidoAcumulado[$band],$porcentajeRetenido[$ctrl],2);					
					//Disminuimos ctrl 
					$ctrl--;
					$band++;
				}while($ctrl>=0);
				//Arreglo que almacena el porcentaje retenido Acumulado de manera invertida
				$pRAInvertido=array();
				//Arreglo que almacena el porcentaje retenido acumuñlado sin invertir
				$porcentajeRetenidoSIN=array();					
				foreach($porcentajeRetenidoAcumulado as $ind =>$porcentaje){
					$pRAInvertido[]=round($porcentaje);
					$porcentajeRetenidoSIN[]=round($porcentaje);
				}
				//Arreglo que guarda el portentaje Retenido Acumulado pero de manera invertida
				$pRAInvertido=array_reverse($pRAInvertido);
				//Arreglo para Almacenar el porcentaje que pasa
				$porcentajePasa=array();
				//Realizamos la operación indicada por el cliente 100- el porcentajeRetenido en la ultima posicion
				$porcentajePasa[]=100-$porcentajeRetenido[$tam-1];
				$band=0;
				$ctrl=$tam-2;
				do{
					$porcentajePasa[]=bcsub($porcentajePasa[$band],$porcentajeRetenido[$ctrl],2);
					$band++;
					$ctrl--;
				}while($ctrl>=0);
				//Arrelgo para almacenar el porcentaje que pasa
				$pPasa=array();
				//Recorrremos para almacenar el pocentaje que pasa y a su vez redondearlo
				foreach($porcentajePasa as $ind =>$porcentajeP){
						$pPasa[]=abs(round($porcentajeP));	
				}?>
				<tr>
					<td colspan="2">&nbsp;</td><?php 
					$band=0;
					do{
						if($band!=0){?>
							<td colspan="2">&nbsp;</td>
						<?php } ?>
							<td colspan="3"class="nombres_tablas"><div align="center" class="Estilo5"><span class="caracter">'</span><?php echo $consultaConceptos[$band];?></div></td>
							<td colspan="3"class="nombres_tablas"><div align="center" class="Estilo5"><?php echo $pPasa[$band];?></div></td>
							<td colspan="3"class="nombres_tablas"><div align="center" class="Estilo5"><?php echo $porcentajeRetenidoSIN[$band];?></div></td>
			  </tr>
						<?php
						$band++;
					}while($band<$tam);?>					
				</tr>												
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="3">&nbsp;</td>
					<td colspan="6" rowspan="2" class="nombres_tablas">
						<div align="center" class="Estilo5">GR&Aacute;FICA DE COMPOSICI&Oacute;N GRANULOM&Eacute;TRICA</div>					</td>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td colspan="3">&nbsp;</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td colspan="2">&nbsp;</td>
					<td colspan="12"><?php
						//Dibujar la grafica con la información proporcionada
						$nombre=dibujarGrafica($consultaConceptos,$pPasa,$pRAInvertido, $limiteInferior, $limiteSuperior);?>
						<div align="center"><img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/lab/<?php echo $nombre;?>" width="700" height="400" 
							align="absbottom" />						</div>			  		</td>
				</tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="10" rowspan="7" class="nombres_tablas" valign="top"><p class="Estilo5">OBSERVACIONES:</p><?php
						//Consulta que permite extraer la norma asi como la descripcion de la misma
						$stm_observaciones="SELECT observaciones FROM (detalle_prueba_agregados JOIN pruebas_agregados ON 
											id_pruebas_agregados=pruebas_agregados_id_pruebas_agregados) WHERE id_pruebas_agregados='$hdn_PBM'";
						$rs_observaciones = mysql_query($stm_observaciones);
						$cont=1;
						if($datos=mysql_fetch_array($rs_observaciones)){
							do{
								if($datos['observaciones']!=""){
									echo "<p>".$cont.".-".$datos['observaciones']."</p>"; 
									$cont++;
								}
							}while($datos=mysql_fetch_array($rs_observaciones));
						}?>					</td>
					<td>&nbsp;</td>
				</tr>
			  	<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			  	</tr>
			  	<tr>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr><td colspan="12">&nbsp;</td></tr>
			  	<tr><td colspan="12">&nbsp;</td></tr>
			  	<tr><td>&nbsp;</td>
			    	<td colspan="10" class="Estilo5"><?php
					//Consulta que permite extraer la norma asi como la descripcion de la misma
					$stm_catalogoMat="SELECT norma, nombre FROM ((catalogo_pruebas JOIN pruebas_realizadas ON catalogo_pruebas_id_prueba=id_prueba)
									  JOIN pruebas_agregados ON id_pruebas_agregados=pruebas_agregados_id_pruebas_agregados)
									  WHERE id_pruebas_agregados='$hdn_PBM'";
					$rs_catalogoMat = mysql_query($stm_catalogoMat);
					if($datos=mysql_fetch_array($rs_catalogoMat)){
						echo $datos['norma']." ".$datos['nombre']; 
					}?>					</td>
			    	<td>&nbsp;</td>
			  	</tr>
			  	<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr>
					<td>&nbsp;</td>
			    	<td colspan="4" style="border-bottom:solid; border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:thin;">&nbsp;</td>
			    	<td colspan="2">&nbsp;</td>
			    	<td colspan="4" style="border-bottom:solid; border-bottom-style:solid; border-bottom-color:#000000; border-bottom-width:thin;">&nbsp;</td>
			   		<td>&nbsp;</td>
				</tr>
				<tr>
			  		<td>&nbsp;</td>
			    	<td colspan="4" class="Estilo5"><div align="center" class="Estilo5">JEFE DE LABORATORIO</div></td>
			    	<td colspan="2" class="Estilo5">&nbsp;</td>
			    	<td colspan="4" class="Estilo5"><div align="center" class="Estilo5">GERENTE T&Eacute;CNICO </div></td>
			   		<td>&nbsp;</td>
			  	</tr>
			  	<tr>
			  		<td>&nbsp;</td>
					<td colspan="4" class="Estilo5"><div align="center" class="Estilo5">Ing Edgar Alan Garc&iacute;a Cruz </div></td>
					<td colspan="2" class="Estilo5">&nbsp;</td>
					<td colspan="4" class="Estilo5"><div align="center" class="Estilo5">Ing. Javier Aguayo Sanchez </div></td>
					<td>&nbsp;</td>
			  	</tr>
            </table>
		</body><?php					
	}//Fin de la Funcion guardarRepAgregados($hdn_tituloTabla, $hdn_nomReporte, $hdn_PBM )
		
	
	//Esta funcion exporta el reporte de Mtto del Equipo de Laboratorioa Excel
	function guardarRepMttoEquipoLab($hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
		
		
		//Recuperar Datos del $_POST
		$idServicio = $_POST['hdn_idServicio'];
		$fechaIni = $_POST['hdn_fechaIni'];
		$fechaFin = $_POST['hdn_fechaFin'];
		$nombreElaboro = strtoupper($_POST['hdn_nombreElaboro']);
									
		//Ejecutar la Sentencia para obtener los datos del Equipo seleccionado en el Mtto.
		$rs_datos = mysql_query("SELECT DISTINCT nombre,no_interno,marca,calibrable,no_serie,tipo_servicio,fecha_registro,encargado_mtto
								FROM (equipo_lab JOIN cronograma_servicios ON no_interno=equipo_lab_no_interno) JOIN bitacora_mtto ON id_servicio=cronograma_servicios_id_servicio 
								WHERE id_servicio = '$idServicio'");

		//Verificar que la consulta tenga datos
		$datos_equipo = mysql_fetch_array($rs_datos);

				
		//Definir el estilo de la tabla y el encabezado que aparecera sobre la tabla?>
		<head>
			<style>					
				<!--
				body { font-family:Arial, Helvetica, sans-serif; font-size: 12px; color: #000000; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_celda_titulo { color:#0000CC; font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; border-width:thin; border-style:solid; border-color:#0000CC; }
				.borde_celda { border-width:thin; border-style:solid; border-color:#0000CC; }
				.texto_azul_negrito { color:#0000CC; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:bold; }				
				.texto_negro { color:#000000; font-family:Arial, Helvetica, sans-serif; font-size:12px; font-weight:normal; }
				.caracter{color:#FFFFFF;}
			-->
			</style>
		</head>	
		<body>
			<table width="712" border="0" cellpadding="0" cellspacing="0" >
            	<tr>
                	<td colspan="3" valign="baseline" align="left">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" />					
					</td>
                	<td colspan="2"></td>
                	<td colspan="3">
						<div align="right" class="sub_encabezado"> 
						<span class="texto_encabezado"><strong>MANUAL DE PROCEDIMIENTOS DE CALIDAD</strong><br>
				  		<em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V</div>					
					</td>
           	  	</tr>
				<tr>
              		<td colspan="8" class="borde_linea" align="center">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                 			&Oacute;N TOTAL O PARCIAL
						</span>					
					</td>
              	</tr>
              	<tr>
					<td width="110">&nbsp;</td>
					<td width="82">&nbsp;</td>
					<td width="82">&nbsp;</td>
					<td width="82">&nbsp;</td>
					<td width="82">&nbsp;</td>
					<td width="82">&nbsp;</td>
					<td width="82">&nbsp;</td>
					<td width="110">&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
						<td colspan="6" class="borde_celda_titulo" align="center">MANTENIMIENTO DE EQUIPOS DE PRUEBA</td>	
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td colspan="8">&nbsp;</td>
				</tr>
				<tr>
					<td class="texto_azul_negrito" align="right">INSTRUMENTO:</td>
					<td colspan="3" class="borde_celda" align="center"><span class="texto_negro"><?php echo $datos_equipo['nombre']; ?></span></td>
					<td colspan="2" class="texto_azul_negrito" align="right">TIPO SERVICIO:</td>
					<td colspan="2" class="borde_celda" align="center"><span class="texto_negro"><?php echo $datos_equipo['tipo_servicio']; ?></span></td>
				</tr>
				<tr><td colspan="8">&nbsp;</td></tr>				
				<tr>
					<td class="texto_azul_negrito" align="right">NO. INTERNO:</td>
					<td class="borde_celda" align="center"><span class="texto_negro"><?php echo $datos_equipo['no_interno']; ?></span></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>					
					<td colspan="2" class="texto_azul_negrito" align="right">FECHA ELABORACION:</td>
					<td colspan="2" class="borde_celda" align="center"><span class="texto_negro"><?php echo strtoupper(verFecha(5)); ?></span></td>
				</tr>
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr>
					<td class="texto_azul_negrito" align="right">NO. SERIE:</td>
					<td class="borde_celda" align="center"><span class="texto_negro"><?php echo $datos_equipo['no_serie']; ?></span></td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>					
					<td colspan="2" class="texto_azul_negrito" align="right">FECHA MANTENIMIENTO:</td>
					<td colspan="2" class="borde_celda" align="center"><span class="texto_negro"><?php echo strtoupper(modFecha($datos_equipo['fecha_registro'],2)); ?></span></td>
				</tr>
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr>
					<td class="texto_azul_negrito" align="right">MARCA:</td>
					<td colspan="2" class="borde_celda" align="center"><span class="texto_negro"><?php echo $datos_equipo['marca']; ?></span></td>
					<td>&nbsp;</td>					
					<td colspan="2" class="texto_azul_negrito" align="right">REALIZADO POR:</td>
					<td colspan="2" class="borde_celda" align="center"><span class="texto_negro"><?php echo $datos_equipo['encargado_mtto']; ?></span></td>
				</tr><?php
								
				//Imprimir las Actividades Realizadas en el MANTENIMIENTO REALIZADO
				$rs_bitacora = mysql_query("SELECT servicio,detalle_servicio FROM bitacora_mtto WHERE cronograma_servicios_id_servicio = '$idServicio'");
				
				//Variables para almacenar los servicios registrados
				$cambioPiezas = "&nbsp;"; $engrasado = "&nbsp;"; $limpiezaGral = "&nbsp;"; $funcionamiento = "&nbsp;";
				$funcionesDañadas = "&nbsp;"; $sistemaElectrico = "&nbsp;"; $observaciones = "&nbsp;";
				//Extraer los datos del ResultSet
				while($datos_bitacora=mysql_fetch_array($rs_bitacora)){				
					//Asignar la Descripcion correspondiente a cada Variable
					switch($datos_bitacora['servicio']){
						case "CAMBIO DE PIEZAS":
							$cambioPiezas = fragmentarCadena($datos_bitacora['detalle_servicio']);
						break;
						case "ENGRASADO":
							$engrasado = fragmentarCadena($datos_bitacora['detalle_servicio']);
						break;
						case "LIMPIEZA GENERAL":
							$limpiezaGral = fragmentarCadena($datos_bitacora['detalle_servicio']);
						break;
						case "FUNCIONAMIENTO":
							$funcionamiento = fragmentarCadena($datos_bitacora['detalle_servicio']);
						break;
						case "FUNCIONES DAÑADAS":
							$funcionesDañadas = fragmentarCadena($datos_bitacora['detalle_servicio']);
						break;
						case "SISTEMA ELECTRICO":
							$sistemaElectrico = fragmentarCadena($datos_bitacora['detalle_servicio']);
						break;
						case "OBSERVACIONES":
							$observaciones = fragmentarCadena($datos_bitacora['detalle_servicio']);
						break;
					}
				}//Cierre while($datos_bitacora=mysql_fetch_array($rs_bitacora))?>
								
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8" class="texto_azul_negrito" align="LEFT">ENGRASADO:</td></tr>
				<tr><td colspan="8" class="borde_celda" align="left"><span class="texto_negro"><?php echo $engrasado; ?></span></td></tr>
								
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8" class="texto_azul_negrito" align="LEFT">LIMPIEZA GENERAL:</td></tr>
				<tr><td colspan="8" class="borde_celda" align="left"><span class="texto_negro"><?php echo $limpiezaGral; ?></span></td></tr>
								
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8" class="texto_azul_negrito" align="LEFT">FUNCIONAMIENTO:</td></tr>
				<tr><td colspan="8" class="borde_celda" align="justify"><span class="texto_negro"><?php echo $funcionamiento; ?></span></td></tr>
				
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8" class="texto_azul_negrito" align="LEFT">FUNCIONES DAÑADAS:</td></tr>
				<tr><td colspan="8" class="borde_celda" align="left"><span class="texto_negro"><?php echo $funcionesDañadas; ?></span></td></tr>
				
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8" class="texto_azul_negrito" align="LEFT">CAMBIO DE PIEZAS:</td></tr>
				<tr><td colspan="8" class="borde_celda" align="left"><span class="texto_negro"><?php echo $cambioPiezas; ?></span></td></tr>
				
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8" class="texto_azul_negrito" align="LEFT">SISTEMA ELECTRICO:</td></tr>
				<tr><td colspan="8" class="borde_celda" align="left"><span class="texto_negro"><?php echo $sistemaElectrico; ?></span></td></tr>
				
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8" class="texto_azul_negrito" align="LEFT">OBSERVACIONES:</td></tr>
				<tr><td colspan="8" class="borde_celda" align="left"><span class="texto_negro"><?php echo $observaciones; ?></span></td></tr>				
			</table>
			
			
			<br><br>
			
			
			<table width="712" border="0" cellpadding="0" cellspacing="0">					
				<tr>
					<td>&nbsp;</td>
					<td colspan="6" class="borde_celda" align="center"><span class="texto_azul_negrito">REGISTRO FOTOGR&Aacute;FICO</span></td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
					<td colspan="3" class="borde_celda" width="200" height="180" align="center" valign="bottom">			
						<br>
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/lab/verFoto.php?idServicio=<?php echo $idServicio; ?>&foto=antes" width="200" height="150" />
						<br>
						<span class="texto_negro">ANTES MTTO.</span>
					</td>
					<td colspan="3" class="borde_celda" width="200" height="180" align="center" valign="bottom">
						<br>
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/pages/lab/verFoto.php?idServicio=<?php echo $idServicio; ?>&foto=despues" width="200" height="150" />
						<br>
						<span class="texto_negro">DESPU&Eacute;S MTTO.</span>
					</td>
					<td>&nbsp;</td>
				</tr>
				
				
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr>
					<td colspan="5">&nbsp;</td>
					<td colspan="3" align="center" class="texto_azul_negrito">ELABORO</td>
				</tr>
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr><td colspan="8">&nbsp;</td></tr>
				<tr>
					<td colspan="5">&nbsp;</td>
					<td colspan="3" style="border-top-width:thin; border-top-style:solid; border-top-color:#000000;" class="texto_azul_negrito" align="center"><?php echo $nombreElaboro; ?></td>
				</tr>
            </table>
		</body><?php					
	}//Fin de la Funcion guardarRepMttoEquipoLab($hdn_nomReporte,$hdn_msg)
	
	
	/*Estafuncion divide una cadena en renglones con no mas de 110 caracteres, oara que no exceda el tamaño de la columna cuando el reporte es exportado*/
	function fragmentarCadena($cadena){		
		//Cantidad de caracteres por fragmento
		$tamRenglon = 100;
		
		//Variable para Almacenar la Nueva Cadena
		$nuevaCadena = "";
		//Obtener el Tamaño de la Cadena Original
		$tamCadena = strlen($cadena);
		//Separar la Cadena Original en un Arreglo de Caracteres, donde cada posición del Arreglo contiene un solo caracter de la cadena original
		$caracteres = str_split($cadena);
		
		//Si el tamaño de la Cadena excede la Cantidad de Caracteres por Fragmento, proceder a separarla
		if($tamCadena>$tamRenglon){
			$cantCaracteres = 0;
			$carInicial = 0;
			for($i=0;$i<$tamCadena;$i++){
				//Obtener cada caracter de la cadena
				$car = $caracteres[$i];
				
				//Separar cada fragmento por palabra recorrida y no por tamaño exacto
				if($cantCaracteres>=$tamRenglon && $car==" "){				
					//Integrar los fragmentos en una nueva cadena con un salto de linea al final
					$nuevaCadena .= substr($cadena,$carInicial,$cantCaracteres)."<br>";
					//Colocar el caracter inicial del siguiente fragmento
					$carInicial += $cantCaracteres;									
					//Resetear el contador de caracteres para no exceder el tamaño del renglon
					$cantCaracteres = 0;
				}				
				//Incremenetar el contado de caracteres
				$cantCaracteres++;		
			}
			
			//Terminado el Ciclo, verificar si la cantidad de caracteres es mayor a 1 y agregar el ultimo Fragmento a al cadena
			if($cantCaracteres>1){
				$nuevaCadena .= substr($cadena,$carInicial,$cantCaracteres)."<br>";
			}
			
			
		}//Cierre if($tamCadena>$tamRenglon)
		else//La cadena original queda intacta
			$nuevaCadena = $cadena;
	

		//Retornar la Cadena Fragmentada
		return $nuevaCadena;
	}//Cierre de la funcion fragmentarCadena($cadena)
		

	//Esta funcion exporte el REPORTE  a un archivo de excel
	function guardarRepMezclas($hdn_nomReporte, $hdn_msg, $hdn_consulta){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_laboratorio");
								
		//Consulta para Defeinir los datos que se mostraran en el reporte
		$stm_consulta=("SELECT nombre FROM (mezclas JOIN materiales_de_mezclas on mezclas_id_mezcla=id_mezcla) WHERE mezclas_id_mezcla='$hdn_msg'");
		
		//Ejecutar la sentencia y almacena los 	datos de la consulta en la variable $rs (result set)
		$rs_datos = mysql_query($stm_consulta);

		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs_datos)){
			$nombre=$datos['nombre'];
		}
		
		//Arreglo que almacena las cantidades de los materiales
		$cantidadesMat = array();
		//Arreglo que almacena los nombres de los materiales
		$nombresMat = array();
		//Arreglo para Almacenar los volumenes de los Materiales
		$volumenesMat = array();
		
		//Ejecutamos la consulta que viene predefinida en el POST
		$rs=mysql_query($hdn_consulta);
		//Verificar que la consulta tenga datos
		if($datos=mysql_fetch_array($rs)){
			do{
				//Obtener el nombre del material de la bd de almacen
				$nomMaterial = obtenerDato('bd_almacen', 'materiales', 'nom_material','id_material', $datos['catalogo_materiales_id_material']);				
				//Obtener la categoria del material de la bd de almacen
				$categoriaMat = obtenerDato('bd_almacen', 'materiales', 'linea_articulo','id_material', $datos['catalogo_materiales_id_material']);
				//Guardamos los nombres de los materiales en el arreglo; se obtiene en obtener dato $nomMaterial
				$nombresMat[] = $nomMaterial." (".$datos['unidad_medida'].")";
				//Guardamos las cantidadesde los materiales 
				$cantidadesMat[] = $datos['cantidad'];
				//Almacenamos los volumenes
				$volumenesMat[] = $datos['volumen'];
			}while($datos=mysql_fetch_array($rs));
		}
				
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
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				/*En este formato  se establece el tamaño,tipo de letra,color de fondo que ocupa la columna princiaplal de una tebla*/
				.nombres_tablas { color: #0000CC; font-family: Arial, Helvetica, sans-serif;	font-size: 12px; font-weight: normal;	border-top-width: medium;	
				border-right-width:  thin;	border-bottom-width: medium;	border-left-width: thin;	border-top-style: solid;	border-right-style: solid;
				border-bottom-style: solid;	border-left-style: solid;	border-top-color: #000000;	border-bottom-color: #000000;	border-left-color: #000000;
				border-right-color: #000000;}
				.Estilo3 {font-size: 16px}
				.Estilo4 {font-size: 14px; color:#0000CC; font-weight: bold;}
				.Estilo5 {font-size: 12px}
				.Estilo6 {font-size: 14;color:#0000CC;}
				.caracter{color:#FFFFFF;}
			-->
			</style>
		</head>	
		<body>
			<table width="949" border="0" >
            	<tr>
                	<td align="left" valign="baseline">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" 
                        align="absbottom" />					
					</td>
                	<td colspan="6"></td>
                	<td colspan="4">
						<div align="right" class="sub_encabezado"> 
						<span class="texto_encabezado"><strong>MANUAL DE PROCEDIMIENTOS DE CALIDAD</strong><br>
				  		<em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V</div>					
					</td>
           	  	</tr>
				<tr>
              		<td colspan="11" align="center" class="borde_linea">
						<span class="sub_encabezado">
							CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
                 			&Oacute;N TOTAL O PARCIAL
						</span>					
					</td>
              	</tr>
              	<tr><td colspan="11">&nbsp;</td></tr>
			 	<tr><td colspan="11">&nbsp;</td></tr>
			  	<tr><td colspan="11">&nbsp;</td></tr>
			  	<tr><td colspan="11">&nbsp;</td></tr>
              	<tr><td colspan="11"><div align="center" class="Estilo6">F 7.3.0 -01  DISE&Ntilde;O DE MEZCLAS DE CONCRETO</div></td></tr>
			  	<tr><td colspan="11">&nbsp;</td></tr>
			  	<tr><td colspan="11">&nbsp;</td></tr>
			  	<tr><td colspan="11"><div align="center" class="Estilo6"><strong><?php echo $nombre;?></strong> </div></td></tr>
			  	<tr>
					<td class="Estilo5">&nbsp;</td>
			    	<td colspan="2">&nbsp;</td>
			    	<td width="71">&nbsp;</td>
			    	<td width="71">&nbsp;</td>
			    	<td width="71">&nbsp;</td>
			    	<td width="73" class="Estilo3">&nbsp;</td>
					<td width="73">&nbsp;</td>
					<td width="70">&nbsp;</td>
					<td width="76">&nbsp;</td>
					<td width="77">&nbsp;</td>
				</tr>
			  	<tr><td colspan="11">&nbsp;</td></tr>
			  	<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr>
					<td colspan="2" rowspan="2" class="nombres_tablas"><div align="center" class="Estilo4">ELEMENTO</div></td>
					<td colspan="9" class="nombres_tablas"><div align="center" class="Estilo4">CANTIDAD</div></td>
		      	</tr>
			  	<tr>
					<td class="nombres_tablas"><div align="center" class="Estilo4">1 m&sup3;</div></td>
					<td class="nombres_tablas"><div align="center" class="Estilo4">2 m&sup3;</div></td>
					<td class="nombres_tablas"><div align="center" class="Estilo4">3 m&sup3;</div></td>
					<td class="nombres_tablas"><div align="center" class="Estilo4">4 m&sup3;</div></td>
					<td class="nombres_tablas"><div align="center" class="Estilo4">5 m&sup3;</div></td>
					<td class="nombres_tablas"><div align="center" class="Estilo4">6 m&sup3;</div></td>
					<td class="nombres_tablas"><div align="center" class="Estilo4">7 m&sup3;</div></td>
					<td class="nombres_tablas"><div align="center" class="Estilo4">8 m&sup3;</div></td>
					<td class="nombres_tablas"><div align="center" class="Estilo4">9 m&sup3;</div></td>
			  	</tr><?php					
				//Este ciclo nos permite recorrer el arreglo de volumenes y el de los nombres de los materiales; para dibujar la tabla de manera dinamica				
				foreach($cantidadesMat as $ind => $cantidad){?>
					<tr>
						<td colspan="2" class="nombres_tablas"><div align="center" class="Estilo4"><?php echo $nombresMat[$ind];?></div></td><?php
						for($i=1;$i<=9;$i++){?>
							<td class="nombres_tablas"><div align="center" class="Estilo4"><?php echo number_format(floatval($cantidad * $i),0,".",",");?></div></td><?php
						}?>					
					</tr><?php					 
				}?>	
										  				  	
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
			  	<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="11">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td colspan="12">&nbsp;</td></tr>
				<tr><td height="26" colspan="12">&nbsp;</td></tr>
				<tr>
			  		<td colspan="12"><strong>_________________________________________________________________________________________________________</strong></td>
			  	</tr>
			  	<tr>
					<td class="texto_encabezado"><div align="center">Fecha de Emisi&oacute;n </div></td>
					<td colspan="3" class="texto_encabezado"><div align="center">No. Revisi&oacute;n </div></td>
					<td class="texto_encabezado"><div align="center"></div></td>
					<td colspan="2" class="texto_encabezado"><div align="center">Fecha de Revisi&oacute;n </div></td>
					<td class="texto_encabezado">&nbsp;</td>
					<td colspan="2" class="texto_encabezado"><div align="center">Pag 1 de 1 </div></td>
					<td class="texto_encabezado">&nbsp;</td>
					<td class="texto_encabezado">&nbsp;</td>
			  	</tr>
			  	<tr>
					<td class="texto_encabezado"><div align="center">Feb 2010 </div></td>
					<td colspan="3" class="texto_encabezado"><div align="center">1</div></td>
					<td class="texto_encabezado"><div align="center"></div></td>
					<td colspan="2" class="texto_encabezado"><div align="center">Feb 2010 </div></td>
					<td class="texto_encabezado">&nbsp;</td>
					<td colspan="2" class="texto_encabezado"><div align="center">F 4.2.1 / Rev. 01</div></td>
					<td class="texto_encabezado">&nbsp;</td>
					<td class="texto_encabezado">&nbsp;</td>
			  	</tr>
            </table>
		</body><?php					
	}//Fin de la Funcion guardarRepMezclas($hdn_nomReporte, $hdn_msg, $hdn_consulta)
	
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
	
	//Grafica que es incluida en el reporte de Agregados
	function dibujarGrafica($consultaConceptos,$pPasa,$pRAInvertido, $limiteInferior, $limiteSuperior){	
		require_once ('../../includes/graficas/jpgraph/jpgraph.php');
		require_once ('../../includes/graficas/jpgraph/jpgraph_line.php');
				
		// So
		$wdata = array_reverse($pPasa);
		$ydata = array_reverse($limiteInferior);
		$zdata = array_reverse($limiteSuperior);
			
		// Create the graph. These two calls are always required
		$graph = new Graph(700,450);
		$graph->SetScale('textlin');
		$graph->yaxis->title->Set('%PASA');
		$graph->SetMargin(40,180,20,40);
		//Cambiar color del margen
		$graph->SetMarginColor("silver@0.5");
		//Establecer el margen separación entre etiquetas
		//$graph->xaxis->SetTextLabelInterval(2);
			
		// Crear las caracteristicas para cada una de las lineas
		$lineplot=new LinePlot($wdata);
		$lineplot->SetColor('blue');
		$lineplot->SetLegend('% Pasa');	
		//$lineplot->value->Show();
		
		$lineplot3=new LinePlot($ydata);
		$lineplot3->SetColor('red');
		$lineplot3->SetLegend('Límite Inferior');	
		//Muestra los valores de los datos en las lineas
		//$lineplot3->value->Show();
		
		$lineplot4=new LinePlot($zdata);
		$lineplot4->SetColor('green');
		$lineplot4->SetLegend('Límite Superior');	
		//$lineplot4->value->Show();
		
		//Agregar Nombres de los rotulos
		$graph->xaxis->SetTickLabels(array_reverse($consultaConceptos));
		
		//Agregar las lineas de datos a la grafica
		$graph->Add($lineplot);
		$graph->Add($lineplot3);
		$graph->Add($lineplot4);
		
		//Alinear los rotulos de la leyenda
		$graph->legend->SetPos(0.05,0.5,'right', 'center');
		
		$rnd=rand(0,1000);
		
		$grafica= 'tmp/grafica'.$rnd.'.png';
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		
		return $grafica;
		
			
	}//Cierre de la funcion dibujarGrafica($consultaConceptos,$pPasa,$pRAInvertido, $limiteInferior, $limiteSuperior)
	
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