<?php 
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 30/Mayo/2011                                      			
	  * Descripción: Este archivo contiene funciones para almacenar la información en una hoja de calculo de excel de las consultas realizadas y reportes generados de Conciliación
	  **/
	 
	 
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Modulo de operaciones con la BD*/
			include("../../includes/conexion.inc");
			include("../../includes/op_operacionesBD.php");
			include("../../includes/func_fechas.php");
			
	/**   Código en: pages\top\guardar_reporte.php                                   
      **/
	 
	  			
	if(isset($_POST['hdn_consulta'])){
		//Ubicacion de las imagenes que estan contenidas en los encabezados
		define("HOST", $_SERVER['HTTP_HOST']);
		//Obtener el nombre del Nombre de la Carpeta Raíz donde se encontrará almacenado el SISAD
		$raiz = explode("/",$_SERVER['PHP_SELF']);
		define("SISAD",$raiz[1]);
		
		switch($hdn_origen){
			case "obras":
				guardarRepObra($hdn_consulta, $hdn_nomReporte,$hdn_msg);
			break;	
			case "consultarConciliacion":
				guardarConsultaConciliacion($hdn_noQuincena,$hdn_msg,$hdn_msgTrasp);
			break;
			case "reporteAcumulados":
				guardarRepAcumulados();
			break;
		}
		
		switch($hdn_tipoReporte){
			case "reporte_requisiciones":
				guardarRepRequisiciones($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
			case "reporte_detallerequisiciones":
				guardarRepDetalleReq($hdn_consulta,$hdn_nomReporte,$hdn_msg);
			break;
		}
	}


	//Esta funcion exporta la CONCILIACIÓN a un archivo de excel
	function guardarConsultaConciliacion($noQuincena,$msg,$msgTrasp ){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=Conciliacion_Quincena $noQuincena.xls");

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
		

		//Recuperar los nombres de las personas que firmarán este documento
		$contratista = strtoupper($_POST['hdn_contratista']);
		$jefeSeccion = strtoupper($_POST['hdn_jefeSeccion']);
		$reviso = strtoupper($_POST['hdn_revisor']);		
		
		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");		
		
		?>
			<div id="tabla">				
			<table width="1900">					
				<tr>
					<td align="left" valign="baseline" colspan="3">
						<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
					</td>
					<td colspan="6">&nbsp;</td>
					<td valign="baseline" colspan="5">
						<div align="right"><span class="texto_encabezado">
							<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
				</tr>											
				<tr>
					<td colspan="14" align="center" class="borde_linea">
						<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
						&Oacute;N TOTAL O PARCIAL</span>
					</td>
				</tr>					
				<tr>
					<td colspan="10">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="10">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="14" align="center" class="titulo_tabla"><?php echo "CONCILIACI&Oacute;N DEL $noQuincena"?></td>
				</tr>
				<tr>
					<td colspan="10">&nbsp;</td>
				</tr>
		<?php
		/*****************************************************************/
		/*************************ESTIMACIONES****************************/
		/*****************************************************************/
		$ctrl=0;
		do{
			if($ctrl==0){
				//Crear sentencia SQL
				$sql_stm ="SELECT * FROM estimaciones JOIN obras ON obras_id_obra=id_obra JOIN subcategorias ON subcategorias_id=id WHERE no_quincena='$noQuincena' AND categoria='AMORTIZABLE' ORDER BY orden,tipo_obra";
				$titulo="Registro de Estimaciones de Obras Amortizables en la Quincena <em><u>$noQuincena</u></em>";
			}
			else{
				//Crear sentencia SQL
				$sql_stm ="SELECT * FROM estimaciones JOIN obras ON obras_id_obra=id_obra JOIN subcategorias ON subcategorias_id=id WHERE no_quincena='$noQuincena' AND categoria='COSTOS' ORDER BY orden,tipo_obra";
				$titulo="Registro de Estimaciones de Obras De Costos en la Quincena <em><u>$noQuincena</u></em>";
			}	
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro de Estimaci&oacute;n en la Quincena <em><u>$noQuincena</u></em> para <em><u>$titulo</u></em></label>";
			
			
			//Ejecutar la sentencia previamente creada
			$rs = mysql_query($sql_stm);									
										
			//Confirmar que la consulta de datos fue realizada con exito.
			if($datos=mysql_fetch_array($rs)){
		
				//Desplegar los resultados de la consulta en una tabla
				echo "				
				<table cellpadding='5' width='1700'>	
					<tr>
						<td colspan='10' align='center' class='titulo_tabla' width='100%'>$titulo</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center' colspan='10' width='100%'>ESTIMACIONES</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center' width='21%'>CONCEPTO</td>
						<td class='nombres_columnas' align='center' width='9%'>SECCI&Oacute;N</td>
						<td class='nombres_columnas' align='center' width='9%'>UNIDAD</td>
						<td class='nombres_columnas' align='center' width='9%'>CANTIDAD</td>
						<td class='nombres_columnas' align='center' width='9%'>PRECIO/U MN</td>
						<td class='nombres_columnas' align='center' width='9%'>PRECIO/U USD</td>
						<td class='nombres_columnas' align='center' width='9%'>TASA CAMBIO</td>
						<td class='nombres_columnas' align='center' width='9%'>TOTAL MN</td>
						<td class='nombres_columnas' align='center' width='9%'>TOTAL USD</td>
						<td class='nombres_columnas' align='center' >IMPORTE TOTAL</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				//Contadores que nos permiten sumar el total de cada coumna	
				$totalMN=0;
				$totalUSD=0;
				$importe=0;
				
				//Esto se realiza para solo imprimir un solo encabezado del tipo de obra y enseguida del el todos registros
				$tipo_obra= $datos['tipo_obra'];
				$idSubcategoria=$datos["subcategoria"];
				echo "
					<tr>
						<td class='nombres_columnas'>$datos[subcategoria]</td>
					</tr>";
				do{	
					// Mostrar los totales de cada columna para todos los registros excepto el último
					if($idSubcategoria != $datos['subcategoria']){
						echo"
							<tr>
								<td class='$nom_clase' colspan='6' align='right'></td>
								<td class='nombres_columnas' align='right'>TOTALES</td>
								<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
								<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
								<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
							</tr>";
						$idSubcategoria=$datos["subcategoria"];
						echo "
							<tr>
								<td class='nombres_columnas'>$datos[subcategoria]</td>
							</tr>";
						//Reiniciar los contadores para empezar la suma con el siguiente tipo de obra	
						$totalMN=0;
						$totalUSD=0;
						$importe=0;
					}	
	
					//Mostrar todos los registros que han sido completados
					echo "
						<tr>	
							<td class='$nom_clase' align='left'>$datos[nombre_obra]</td>
							<td class='$nom_clase'>$datos[seccion]</td>
							<td class='$nom_clase'>$datos[unidad]</td>					
							<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['pumn_estimacion'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['puusd_estimacion'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['t_cambio'],4,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['total_mn'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['total_usd'],2,".",",")."</td>
							<td class='$nom_clase'>$".number_format($datos['importe'],2,".",",")."</td>
						</tr>";
						//Realizar la suma por cada registro de los totales
						$totalMN += $datos['total_mn'];
						$totalUSD += $datos['total_usd'];
						$importe += $datos['importe'];
						
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";				
				}while($datos=mysql_fetch_array($rs));
				//Fin de la tabla donde se muestran los resultados de la consulta
				// Mostrar los totales de cada columna para el último registro
				echo"
					<tr>
						<td class='$nom_clase' colspan='6' align='right'></td>
						<td class='nombres_columnas' align='right'>TOTALES</td>
						<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
						<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
						<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
					</tr>";
				echo "
					<tr><td colspan='10'>&nbsp;</td></tr>
					<tr><td colspan='10'>&nbsp;</td></tr>
					<tr><td colspan='10'>&nbsp;</td></tr>
					<tr><td colspan='10'>&nbsp;</td></tr>";	
			}//Cierre if($datos=mysql_fetch_array($rs))
			//Incrementar el contador
			$ctrl++;
		}while($ctrl<=1);
		?>
			</table>
		<?php

		/*****************************************************************/
		/**************************TRASPALEOS*****************************/
		/*****************************************************************/				
		//Colocar el Titulo de la Tabla?>
		<br><br><br><br>				
		<table cellpadding="5" width="1900">				
			<tr>
				<td class="nombres_columnas" align="center" colspan="14">TRASPALEOS</td>
			</tr><?php
		
				
		//Crear un ciclo de 2 Iteraciones para obtener las Obras de Amortizaciones y las de Costos		
		for($i=0;$i<2;$i++){
			
			
			//Variables para crear la sentencia y manejar el mensaje de datos no disponibles
			$sql_stm_categoria = "";
			$categoria = "";
			//Crear la Sentencia para obtener el ID de las Obras correspondientes a cada Categoria
			if($i==0){
				$categoria_msg = "OBRAS AMORTIZABLES";
				$sql_stm_categoria = "SELECT id_obra,subcategoria FROM obras JOIN traspaleos ON id_obra=obras_id_obra JOIN subcategorias ON subcategorias_id=id 
									WHERE categoria='AMORTIZABLE' AND no_quincena='$noQuincena' ORDER BY orden,id_obra";
			}
			else if($i==1){
				$categoria_msg = "OBRA DE COSTOS";
				$sql_stm_categoria = "SELECT id_obra,subcategoria FROM obras JOIN traspaleos ON id_obra=obras_id_obra JOIN subcategorias ON subcategorias_id=id 
									WHERE categoria='COSTOS' AND no_quincena='$noQuincena' ORDER BY orden,id_obra";
			}
			//Ejecutar la Consulta
			$rs_categoria = mysql_query($sql_stm_categoria);									
									
			
			//Variables para Acumular los totales de Cada Categoria
			$totalMN = 0;
			$totalUSD = 0;
			$importe = 0;
			if($idObras=mysql_fetch_array($rs_categoria)){			
				
				//Colocar el Encabezados para las Obras Amortizables y Costos?>
				<tr>
					<td class="nombres_columnas" align="center"><?php echo $categoria_msg; ?></td>
					<td class="nombres_columnas" align="center">ACUMULADO</td>
					<td class="nombres_columnas" align="center">SECCI&Oacute;N</td>
					<td class="nombres_columnas" align="center">&Aacute;REA</td>
					<td class="nombres_columnas" align="center">VOLUMEN</td>
					<td class="nombres_columnas" align="center">ORIGEN</td>
					<td class="nombres_columnas" align="center">DESTINO</td>
					<td class="nombres_columnas" align="center">DISTANCIA</td>
					<td class="nombres_columnas" align="center">PRECIO/U M.N</td>
					<td class="nombres_columnas" align="center">PRECIO/U USD</td>
					<td class="nombres_columnas" align="center">TASA CAMBIO</td>
					<td class="nombres_columnas" align="center">TOTAL MN</td>
					<td class="nombres_columnas" align="center">TOTAL USD</td>
					<td class="nombres_columnas" align="center">IMPORTE</td>
				</tr><?php
				$idSubcategoria=$idObras["subcategoria"];
				echo "
				<tr>
					<td class='nombres_filas' align='left'>$idObras[subcategoria]</td>
				</tr>
				";
				//Iterar segun la cantidad de Obras registradas en cada Categoria				
				do{
					//Obtener el ID de cada Obra registrarda en cada Categoria
					$idObra = $idObras['id_obra'];
					//Crear sentencia SQL para Obtener el Traspaleo Registrado a la Obra
					$sql_stm_obra ="SELECT id_obra,nombre_obra,acumulado_quincena,seccion,area,unidad,volumen,origen,destino,distancia,pu_mn,pu_usd,t_cambio,total_mn,total_usd,importe_total 
									FROM traspaleos JOIN detalle_traspaleos ON traspaleos_id_traspaleo = id_traspaleo JOIN obras ON obras_id_obra=id_obra 
									WHERE id_obra='$idObra' AND no_quincena='$noQuincena' ORDER BY no_registro;";									
					//Ejecutar la sentencia previamente creada
					$rs_obra = mysql_query($sql_stm_obra);																								
					
					
					//Confirmar que la consulta de datos fue realizada con exito.
					if($datos_traspaleo=mysql_fetch_array($rs_obra)){												
						
						//Controlar el color de cada renglon
						$nom_clase = "renglon_gris";
						$cont = 1;
						if($idSubcategoria!=$idObras["subcategoria"]){
							echo "
							<tr>
								<td class='nombres_filas' align='left'>$idObras[subcategoria]</td>
							</tr>
							";
						}
						//Iterar Segun la Cantidad de Registros de Traspaleo en la Obra														
						do{											
							//Mostrar todos los registros que han sido completados?>							
							<tr>	
								<td class="<?php echo $nom_clase; ?>" align="left"><?php echo $datos_traspaleo['nombre_obra']; ?></td>
								<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos_traspaleo['acumulado_quincena']; ?></td>
								<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos_traspaleo['seccion']; ?></td>
								<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos_traspaleo['area']; ?></td><?php
							
							//Hacer el Analisis para Clasificar el registro de Traspaleo
							if($datos_traspaleo['importe_total']>0){
								//Colocar la celda con el Color para indicar que se trata de VACIADERO
								if($datos_traspaleo['distancia']<=50){?>
									<td class="renglon_volumen" bgcolor="#00B050" align="center"><?php echo $datos_traspaleo['volumen']; ?></td><?php
								}
								//Colocar la celda con el Color para indicar que se trata de APLANILLE
								else if($datos_traspaleo['destino']=="APLANILLE"){?>
									<td class="renglon_volumen" bgcolor="#948B54" align="center"><?php echo $datos_traspaleo['volumen']; ?></td><?php
								}
								//Colocar la celda con el Color de acuerdo a la distancia
								else{
									$color = obtenerColorDistancia($datos_traspaleo['distancia'],$datos_traspaleo['id_obra']);?>
									<td class="renglon_volumen" bgcolor="#<?php echo $color; ?>" align="center"><?php echo $datos_traspaleo['volumen']; ?></td><?php
								}
							}
							else{ //Colocar la celda del volumen sin fondo?>
								<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos_traspaleo['volumen']; ?></td><?php
							}?>
																																							
							
								<td class="<?php echo $nom_clase; ?>"><?php echo $datos_traspaleo['origen']; ?></td>
								<td class="<?php echo $nom_clase; ?>"><?php echo $datos_traspaleo['destino']; ?></td>
								<td class="<?php echo $nom_clase; ?>" align="center"><?php echo $datos_traspaleo['distancia']; ?></td>
								<td class="<?php echo $nom_clase; ?>" align="center">$ <?php echo number_format($datos_traspaleo['pu_mn'],2,".",","); ?></td>
								<td class="<?php echo $nom_clase; ?>" align="center">$ <?php echo number_format($datos_traspaleo['pu_usd'],2,".",","); ?></td>
								<td class="<?php echo $nom_clase; ?>" align="center">$ <?php echo number_format($datos_traspaleo['t_cambio'],4,".",","); ?></td>
								<td class="<?php echo $nom_clase; ?>" align="right">$ <?php echo number_format($datos_traspaleo['total_mn'],2,".",","); ?></td>
								<td class="<?php echo $nom_clase; ?>" align="right">$ <?php echo number_format($datos_traspaleo['total_usd'],2,".",","); ?></td>
								<td class="<?php echo $nom_clase; ?>" align="right">$ <?php echo number_format($datos_traspaleo['importe_total'],2,".",","); ?></td>
							</tr><?php 
							
							
							//Realizar la suma por cada registro de los totales
							$totalMN += $datos_traspaleo['total_mn'];
							$totalUSD += $datos_traspaleo['total_usd'];
							$importe += $datos_traspaleo['importe_total'];
					
							//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";				
						}while($datos_traspaleo=mysql_fetch_array($rs_obra));
						
						
						//Colocar un Renglon Para Separar los Registros de Cada Obra?>
						<tr><td colspan="14">&nbsp;</td></tr><?php
						
					}//Cierre if($datos_traspaleo=mysql_fetch_array($rs_obra))
					$idSubcategoria=$idObras["subcategoria"];
				}while($idObras=mysql_fetch_array($rs_categoria));
									
				//Mostrar los totales de las obras registrardas en cada Categoria?>
				<tr>
					<td class="<?php echo $nom_clase; ?>" colspan="10" align="right"></td>
					<td class="nombres_columnas" align="right">TOTALES</td>
					<td class="nombres_columnas" align="right">$ <?php echo number_format($totalMN,2,".",","); ?></td>
					<td class="nombres_columnas" align="right">$ <?php echo number_format($totalUSD,2,".",","); ?></td>
					<td class="nombres_columnas" align="right">$ <?php echo number_format($importe,2,".",","); ?></td>
				</tr><?php
								
				//Colocar un Espacio entre la Tabla que muestra la Obras Amortizables y las Obras de Costos?>
				<tr><td colspan="14">&nbsp;</td></tr>
				<tr><td colspan="14">&nbsp;</td></tr>
				<tr><td colspan="14">&nbsp;</td></tr><?php
								
			}//Cierre if($idObras=mysql_fetch_array($rs_categoria))
		}//Cierre for($i=0;$i<2;$i++)
		?></table>
		
		<?php
		/*****************************************************************/
		/*************************EQUIPO PESADO***************************/
		/*****************************************************************/				
		$sentencia=$_POST["hdn_datoEquipo"];
		$msje=$_POST["hdn_msgEquipo"];
		if($sentencia!=""){
			$rsEquipo=mysql_query($sentencia);
			if($datosEquipo=mysql_fetch_array($rsEquipo)){
				//Colocar el Titulo de la Tabla?>
				<table cellpadding="5" width="1900">				
					<tr>
						<td class='nombres_columnas' align='center' colspan='10' width='100%'>REGISTROS DE MAQUINARIA PESADA</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center' width='21%'>CONCEPTO</td>
						<td class='nombres_columnas' align='center' width='9%'>EQUIPO</td>
						<td class='nombres_columnas' align='center' width='9%'>UNIDAD</td>
						<td class='nombres_columnas' align='center' width='9%'>CANTIDAD</td>
						<td class='nombres_columnas' align='center' width='9%'>P.U. M.N.</td>
						<td class='nombres_columnas' align='center' width='9%'>P.U. USD</td>
						<td class='nombres_columnas' align='center' width='9%'>TASA CAMBIO</td>
						<td class='nombres_columnas' align='center' width='9%'>TOTAL MN</td>
						<td class='nombres_columnas' align='center' width='9%'>TOTAL USD</td>
						<td class='nombres_columnas' align='center' >IMPORTE TOTAL M.N.</td>
					</tr>
					<?php
						$nom_clase = "renglon_gris";
						$cont = 1;
						//Contadores que nos permiten sumar el total de cada coumna	
						$totalMN=0;
						$totalUSD=0;
						$importe=0;
						//Esto se realiza para solo imprimir un solo encabezado del tipo de obra y enseguida del el todos registros
						$tipo_obra= $datosEquipo['fam_equipo'];
						echo "
							<tr>
								<td class='nombres_columnas'>$datosEquipo[fam_equipo]</td>
							</tr>";
						do{	
							// Mostrar los totales de cada columna para todos los registros excepto el último
							if($tipo_obra != $datosEquipo['fam_equipo']){
								echo"
									<tr>
										<td class='$nom_clase' colspan='6' align='right'></td>
										<td class='nombres_columnas' align='right'>TOTALES</td>
										<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
										<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
										<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
									</tr>";
								$tipo_obra = $datosEquipo['tipo_obra'];
								echo "
									<tr>
										<td class='nombres_columnas'>$datosEquipo[tipo_obra]</td>
									</tr>";
								//Reiniciar los contadores para empezar la suma con el siguiente tipo de obra	
								$totalMN=0;
								$totalUSD=0;
								$importe=0;
							}
							$subtotalMN=$datosEquipo['cantidad']*$datosEquipo['pumn_estimacion'];
							$subtotalUSD=$datosEquipo['cantidad']*$datosEquipo['puusd_estimacion']*$datosEquipo['t_cambio'];
							$subtotal=$subtotalMN+$subtotalUSD;
							//Mostrar todos los registros que han sido completados
							echo "
								<tr>	
									<td class='$nom_clase' align='left'>$datosEquipo[concepto]</td>
									<td class='$nom_clase'>$datosEquipo[id_equipo]</td>
									<td class='$nom_clase'>$datosEquipo[unidad]</td>					
									<td class='$nom_clase'>".number_format($datosEquipo['cantidad'],2,".",",")."</td>
									<td class='$nom_clase'>$".number_format($datosEquipo['pumn_estimacion'],2,".",",")."</td>
									<td class='$nom_clase'>$".number_format($datosEquipo['puusd_estimacion'],2,".",",")."</td>
									<td class='$nom_clase'>$".number_format($datosEquipo['t_cambio'],4,".",",")."</td>
									<td class='$nom_clase'>$".number_format($subtotalMN,2,".",",")."</td>
									<td class='$nom_clase'>$".number_format($subtotalUSD,2,".",",")."</td>
									<td class='$nom_clase'>$".number_format($subtotal,2,".",",")."</td>
								</tr>";
								//Realizar la suma por cada registro de los totales
								$totalMN += $subtotalMN;
								$totalUSD += $subtotalUSD;
								$importe += $subtotal;
							//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";				
						}while($datosEquipo=mysql_fetch_array($rsEquipo));
						//Fin de la tabla donde se muestran los resultados de la consulta
						// Mostrar los totales de cada columna para el último registro
						echo"
							<tr>
								<td class='$nom_clase' colspan='6' align='right'></td>
								<td class='nombres_columnas' align='right'>TOTALES</td>
								<td class='nombres_columnas' align='center'>$".number_format($totalMN,2,".",",")."</td>
								<td class='nombres_columnas' align='center'>$".number_format($totalUSD,2,".",",")."</td>
								<td class='nombres_columnas' align='center'>$".number_format($importe,2,".",",")."</td>
							</tr>";
						echo "</table>";
					?>
				</table>
			<?php
			}
		}//Fin de verificar si esta declarada la sentencia de Equipo
		//Colocar un espacio entre la Tabla y las Firmas que van al pie del Documento?>								
		<table cellpadding="5" width="1900">
			<tr>
				<td colspan="14">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="14">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="14">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="14">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="14">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="14">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="14">&nbsp;</td>
			</tr>                
			<tr>
				<td>&nbsp;</td>
				<td colspan="3" class='borde_firma'><div align="center" style="font-size:9px;" ><?php echo $contratista; ?></div></td>
				<td>&nbsp;</td>
				<td colspan="3" class='borde_firma'><div align="center" style="font-size:9px;"><?php echo $jefeSeccion; ?></div></td>
				<td>&nbsp;</td>
				<td colspan="3" class='borde_firma'><div align="center" style="font-size:9px;"><?php echo $reviso; ?></div></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td>&nbsp;</td>
				<td colspan="3"><div align="center" style="font-size:9px">CONTRATISTA</div></td>
				<td>&nbsp;</td>
				<td colspan="3"><div align="center" style="font-size:9px">JEFE DE SECCI&Oacute;N</div></td>
				<td>&nbsp;</td>
				<td colspan="3"><div align="center" style="font-size:9px">REVIS&Oacute;</div></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td colspan="14">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="14"><div align="center" style="font-size:9px">¡Lo mejor en estabilizar taludes y obras mineras!</div></td>
			</tr>                
		</table>
		   
		</div>     
		</body><?php 
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);	
	}//Fin de la Funcion guardarConsultaConciliacion()
	
	
	
	/*Obtener el Color correspondiente al Intervalo en la lista de precios Asociada a las Obras de acuerdo a la Distancia*/
	function obtenerColorDistancia($distancia,$idObra){
		//Crear la Sentencia para obtener el color correspondiente al rango de precios asociado a la Obra indicada
		$sql_stm = "SELECT color FROM (lista_precios JOIN precios_traspaleo ON precios_traspaleo_id_precios=id_precios) JOIN obras ON id_precios=obras.precios_traspaleo_id_precios
					WHERE id_obra = '$idObra' AND $distancia>=distancia_inicio AND $distancia<=distancia_fin";
		//Ejecutar la Setencia
		$rs = mysql_query($sql_stm);
		
		//Extraer los datos y retornar el valor encontrado, de lo contrario regresar vacio
		if($datos=mysql_fetch_array($rs))
			return $datos['color'];		
		else
			return "";
	}//Cierre de la funcion obtenerColorDistancia($distancia,$idObra)

	
	/*Esta funcion guarda los datos del Reporte de Acumulados en la Base de Datos*/
	function guardarRepAcumulados(){
		//Iniciar la SESSION para Obtener los Datos del Reporte
		session_start();
		
		
		$noQuincena = $_SESSION['reporteAcumulados']['noQuincena'];
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=ReporteAcumulados-Quincena $noQuincena.xls");						

		
		//Conectarse a la Base de Datos de Topografia
		$conn = conecta("bd_topografia");
				
		
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
				#tabla_rpt { position:absolute; left:0px; top:0px; width:850; height:175px; z-index:5; }
				.texto_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 10px; color: #0000CC; }
				.sub_encabezado {font-family: Arial, Helvetica, sans-serif; font-size: 9px; color: #0000CC; }
				.titulo_tabla {font-family: Arial, Helvetica, sans-serif; font-weight: bold; font-size: 14px; }
				.borde_linea { border-top:3px; border-top-color:#4E6128; border-top-style:solid;}
				.borde_firma { border-top:3px; border-top-color:#000000; border-top-style:solid;}
				-->
			</style>
		</head>	
		
												
		<body>
            <div id="tabla_rpt" align="center">			
                <table width="1000">					
                    <tr>
                        <td align="left" valign="baseline" colspan="3">
                        	<img src="http://<?php echo HOST; ?>/<?php echo SISAD; ?>/images/logo.png" width="118" height="58" align="absbottom" />
                        </td>
                        <td>&nbsp;</td>
                        <td valign="baseline" colspan="3">
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
                        <td colspan="7" align="center" class="titulo_tabla">Reporte del Acumulado de Obras de la Quincena <?php $_SESSION['reporteAcumulados']['noQuincena']?></td>
                    </tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td colspan="3" align="center"><font size="-1" face="Arial, Helvetica, sans-serif"><strong>AMORTIZABLE</strong></font></td>
						<td colspan="2">&nbsp;</td>
					</tr>					
                    <tr>
                        <td align="center" width="190">&nbsp;</td>
						<td align="center" width="190">&nbsp;</td>                        
                        <td class="nombres_columnas" align="center" width="90">DISTANCIA</td>
                        <td class="nombres_columnas" align="center" width="90">M&sup3;</td>
						<td class="nombres_columnas" align="center" width="60">COLOR</td>
                        <td align="center" width="190">&nbsp;</td>
						<td align="center" width="190">&nbsp;</td>
                    </tr><?php					
					
					
					
					/**********************************************************************************/
					/********************************OBRAS AMORTIZABLES********************************/
					/**********************************************************************************/
					$total = 0;
					if(count($_SESSION['reporteAcumulados']['obrasAmortizables'])>0){					
						//Controlar el color del renglon
						$nom_clase = "renglon_gris";
						$cont = 1;	
						foreach($_SESSION['reporteAcumulados']['obrasAmortizables'] as $clave => $valor){
							if($clave==="VACIADERO"){?>
								<tr>
									<td align="center" colspan="2">&nbsp;</td>                 
									<td class="<?php echo $nom_clase?>" align="center">VACIADERO</td>
									<td class="<?php echo $nom_clase?>" align="center"><?php echo number_format($valor,2,".",","); ?></td>
									<td bgcolor="#<?php echo $_SESSION['reporteAcumulados']['obrasAmortizables']['colorVaciadero']; ?>">&nbsp;</td>
									<td align="center" colspan="2">&nbsp;</td>                 
								</tr><?php
								$total += $valor;
							}
							else if($clave==="APLANILLE"){?>
								<tr>
									<td align="center" colspan="2">&nbsp;</td>                       
									<td class="<?php echo $nom_clase?>" align="center">APLANILLE</td>
									<td class="<?php echo $nom_clase?>" align="center"><?php echo number_format($valor,2,".",","); ?></td>
									<td bgcolor="#<?php echo $_SESSION['reporteAcumulados']['obrasAmortizables']['colorAplanille']; ?>">&nbsp;</td>
									<td align="center" colspan="2">&nbsp;</td>
								</tr><?php
								$total += $valor;
							}
							else if( !($clave==="colorVaciadero" || $clave==="colorAplanille") ) {?>
								<tr>
									<td align="center" colspan="2">&nbsp;</td>                        
									<td class="<?php echo $nom_clase?>" align="center"><?php echo $valor['limInferior']." - ".$valor['limSuperior']; ?></td>
									<td class="<?php echo $nom_clase?>" align="center"><?php echo number_format($valor['volumen'],2,".",","); ?></td><?php
									//Si esta disponible el color evaluar si tiene uno asignado y mostrarlo
									if(isset($valor['color'])){
										if($valor['color']=="" || $valor['color']=="FFFFFF"){?>
											<td class="<?php echo $nom_clase; ?>">Sin Color</td><?php
										} else {?>
											<td bgcolor="#<?php echo $valor['color']; ?>">&nbsp;</td><?php
										}
									}else{//Si no esta disponible el color indicar que no hay color registrado?>
										<td class="<?php echo $nom_clase; ?>">Sin Color</td><?php
									}?>
									<td align="center" colspan="2">&nbsp;</td>
								</tr><?php
								$total += $valor['volumen'];
							}
														
							//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";							
						}
						
						
						//Imprimir el Total de Metros Cubicos Movidos?>
						<tr>
							<td align="center" colspan="2">&nbsp;</td>                      
							<td class="<?php echo $nom_clase?>" align="center" width="20%"><strong>TOTAL</strong></td>
							<td class="<?php echo $nom_clase?>" colspan="2" align="left" width="20%"><strong><?php echo number_format($total,2,".",","); ?></strong></td>
							<td align="center" colspan="2">&nbsp;</td>
						</tr><?php
						
																		
						//Obtener el Costo Total de las Obras Amortizables
						$rs_costoTotal = mysql_query("SELECT SUM(importe_total) AS total FROM (obras JOIN traspaleos ON id_obra=obras_id_obra) 
													  JOIN detalle_traspaleos ON id_traspaleo=traspaleos_id_traspaleo 
													  WHERE no_quincena='".$_SESSION['reporteAcumulados']['noQuincena']."' AND categoria='AMORTIZABLE'");
						$datos_costoTotal = mysql_fetch_array($rs_costoTotal);
						//Cambiar el Color del Renglon
						if($nom_clase=="renglon_blanco")
							$nom_clase = "renglon_gris";
						else
							$nom_clase = "renglon_blanco"?>
						<tr>
							<td align="center" colspan="2">&nbsp;</td>
							<td class="<?php echo $nom_clase; ?>" align="center"><strong>COSTO TOTAL</strong></td>
							<td class="<?php echo $nom_clase; ?>" colspan="2" align="left"><strong><?php echo "$ ".number_format($datos_costoTotal['total'],2,".",","); ?></strong></td>
							<td align="center" colspan="2">&nbsp;</td>
						</tr><?php												
					}
					else{?>
						<tr>
							<td align="center" colspan="2">&nbsp;</td>
							<td align="center" colspan="3">No Hay Registros</td>
							<td align="center" colspan="2">&nbsp;</td>
						</tr><?php
					}?>
					
					
					
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="7">&nbsp;</td>
					</tr>
					<tr>
						<td colspan="2">&nbsp;</td>
						<td colspan="3" align="center"><font size="-1" face="Arial, Helvetica, sans-serif"><strong>COSTOS</strong></font></td>
						<td colspan="2">&nbsp;</td>
					</tr>
					 <tr>
                        <td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>                        
                        <td class="nombres_columnas" align="center">DISTANCIA</td>
                        <td class="nombres_columnas" align="center">M&sup3;</td>
						<td class="nombres_columnas" align="center">COLOR</td>
                        <td align="center">&nbsp;</td>
						<td align="center">&nbsp;</td>
                    </tr><?php
					
					
										
					/**********************************************************************************/
					/********************************OBRAS AMORTIZABLES********************************/
					/**********************************************************************************/
					$total = 0;
					if(count($_SESSION['reporteAcumulados']['obrasCostos'])>0){					
						//Controlar el color del renglon
						$nom_clase = "renglon_gris";
						$cont = 1;	
						foreach($_SESSION['reporteAcumulados']['obrasCostos'] as $clave => $valor){
							if($clave==="VACIADERO"){?>
								<tr>
									<td align="center" colspan="2">&nbsp;</td>                 
									<td class="<?php echo $nom_clase?>" align="center">VACIADERO</td>
									<td class="<?php echo $nom_clase?>" align="center"><?php echo number_format($valor,2,".",","); ?></td>
									<td bgcolor="#<?php echo $_SESSION['reporteAcumulados']['obrasAmortizables']['colorVaciadero']; ?>">&nbsp;</td>
									<td align="center" colspan="2">&nbsp;</td>                 
								</tr><?php
								$total += $valor;
							}
							else if($clave==="APLANILLE"){?>
								<tr>
									<td align="center" colspan="2">&nbsp;</td>                       
									<td class="<?php echo $nom_clase?>" align="center">APLANILLE</td>
									<td class="<?php echo $nom_clase?>" align="center"><?php echo number_format($valor,2,".",","); ?></td>
									<td bgcolor="#<?php echo $_SESSION['reporteAcumulados']['obrasAmortizables']['colorAplanille']; ?>">&nbsp;</td>
									<td align="center" colspan="2">&nbsp;</td>
								</tr><?php
								$total += $valor;
							}
							else if( !($clave==="colorVaciadero" || $clave==="colorAplanille") ) {?>
								<tr>
									<td align="center" colspan="2">&nbsp;</td>                        
									<td class="<?php echo $nom_clase?>" align="center"><?php echo $valor['limInferior']." - ".$valor['limSuperior']; ?></td>
									<td class="<?php echo $nom_clase?>" align="center"><?php echo number_format($valor['volumen'],2,".",","); ?></td><?php
									//Si esta disponible el color evaluar si tiene uno asignado y mostrarlo
									if(isset($valor['color'])){
										if($valor['color']=="" || $valor['color']=="FFFFFF"){?>
											<td class="<?php echo $nom_clase; ?>">Sin Color</td><?php
										} else {?>
											<td bgcolor="#<?php echo $valor['color']; ?>">&nbsp;</td><?php
										}
									}else{//Si no esta disponible el color indicar que no hay color registrado?>
										<td class="<?php echo $nom_clase; ?>">Sin Color</td><?php
									}?>
									<td align="center" colspan="2">&nbsp;</td>
								</tr><?php
								$total += $valor['volumen'];
							}
														
							//Determinar el color del siguiente renglon a dibujar
							$cont++;
							if($cont%2==0)
								$nom_clase = "renglon_blanco";
							else
								$nom_clase = "renglon_gris";							
						}
						
						
						//Imprimir el Total de Metros Cubicos Movidos?>
						<tr>
							<td align="center" colspan="2">&nbsp;</td>                      
							<td class="<?php echo $nom_clase?>" align="center" width="20%"><strong>TOTAL</strong></td>
							<td class="<?php echo $nom_clase?>" colspan="2" align="left" width="20%"><strong><?php echo number_format($total,2,".",","); ?></strong></td>
							<td align="center" colspan="2">&nbsp;</td>
						</tr><?php
						
																		
						//Obtener el Costo Total de las Obras Amortizables
						$rs_costoTotal = mysql_query("SELECT SUM(importe_total) AS total FROM (obras JOIN traspaleos ON id_obra=obras_id_obra) 
													  JOIN detalle_traspaleos ON id_traspaleo=traspaleos_id_traspaleo 
													  WHERE no_quincena='".$_SESSION['reporteAcumulados']['noQuincena']."' AND categoria='COSTOS'");
						$datos_costoTotal = mysql_fetch_array($rs_costoTotal);
						//Cambiar el Color del Renglon
						if($nom_clase=="renglon_blanco")
							$nom_clase = "renglon_gris";
						else
							$nom_clase = "renglon_blanco"?>
						<tr>
							<td align="center" colspan="2">&nbsp;</td>
							<td class="<?php echo $nom_clase; ?>" align="center"><strong>COSTO TOTAL</strong></td>
							<td class="<?php echo $nom_clase; ?>" colspan="2" align="left"><strong><?php echo "$ ".number_format($datos_costoTotal['total'],2,".",","); ?></strong></td>
							<td align="center" colspan="2">&nbsp;</td>
						</tr><?php												
					}
					else{?>
						<tr>
							<td align="center" colspan="2">&nbsp;</td>
							<td align="center" colspan="3">No Hay Registros</td>
							<td align="center" colspan="2">&nbsp;</td>
						</tr><?php
					}?>																																																																																									
				</table>
			</div>
		</body><?php
		
		//Cerrar la Conexion de la BD
		mysql_close($conn);	
	}//Cierre de la funcion function guardarRepAcumulados()
	
			
	//Esta funcion exporta el REPORTE DE OBRAS a un archivo de excel
	function guardarRepObra($hdn_consulta, $hdn_nomReporte,$hdn_msg){
		//Esribir datos de la pagina en excel
		header("Content-type: application/vnd.ms-excel");
		header("Content-Disposition: attachment; filename=$hdn_nomReporte.xls");
				
		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");
		
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
					<td valign="baseline" colspan="7">
						<div align="right"><span class="texto_encabezado">
							<strong>MANUAL  DE PROCEDIMIENTOS DE LA CALIDAD</strong><br><em>CONCRETO  LANZADO DE FRESNILLO, S.A DE C.V.</em>
						</span></div>
					</td>
				</tr>											
				<tr>
					<td colspan="12" align="center" class="borde_linea">
						<span class="sub_encabezado">CONFIDENCIAL, PROPIEDAD DE &ldquo;CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.&rdquo; PROHIBIDA SU REPRODUCCI
						&Oacute;N TOTAL O PARCIAL</span>
					</td>
				</tr>					
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="12" align="center" class="titulo_tabla"><?php echo $hdn_msg; ?></td>
				</tr>
				<tr>
					<td colspan="12">&nbsp;</td>
				</tr>			
				<tr>
					<td class='nombres_columnas' align='center'>CLAVE OBRA</td>
					<td class='nombres_columnas' align='center'>TIPO OBRA</td>
					<td class='nombres_columnas' align='center'>NOMBRE OBRA</td>
					<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A DE PRECIOS</td>
					<td class='nombres_columnas' align='center'>CATEGOR&Iacute;A</td>
					<td class='nombres_columnas' align='center'>SUBCATEGOR&Iacute;A</td>			
					<td class='nombres_columnas' align='center'>SECCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>&Aacute;REA</td>
					<td class='nombres_columnas' align='center'>UNIDAD</td>
					<td class='nombres_columnas' align='center'>PRECIO/U M.N. ESTIMACI&Oacute;N </td>
					<td class='nombres_columnas' align='center'>PRECIO/U USD ESTIMACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>																																																																																																																
				</tr><?php
		$nom_clase = "renglon_gris";
		$cont = 1;
		$cant_total = 0;
		do{
			if($datos["subcategorias_id"]==0)
				$idSubcategoria="N/R";
			else
				$idSubcategoria=obtenerDato("bd_topografia","subcategorias","subcategoria","id",$datos["subcategorias_id"]);
				
			if($datos["precios_traspaleo_id_precios"]!="N/A")
				$listaPrecios=obtenerDato("bd_topografia","precios_traspaleo","tipo","id_precios",$datos["precios_traspaleo_id_precios"]);
			else
				$listaPrecios="N/A";
		?>
			<tr>
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['id_obra']; ?></td>
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['tipo_obra']; ?></td>
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['nombre_obra']; ?></td>
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $listaPrecios; ?></td>					
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['categoria']; ?></td>
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $idSubcategoria;?></td>
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['seccion']; ?></td>
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['area']; ?></td>
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo $datos['unidad']; ?></td>
				<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['pumn_estimacion'],2,".",","); ?></td>
				<td align="center" class="<?php echo $nom_clase; ?>">$ <?php echo number_format($datos['puusd_estimacion'],2,".",","); ?></td>										
				<td align="center" class="<?php echo $nom_clase; ?>"><?php echo modFecha($datos['fecha_registro'],1); ?></td>
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
		</body><?php
		}
	}//Fin de la Funcion guardarRepObra()				
	
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