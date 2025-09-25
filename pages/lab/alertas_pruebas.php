<?php
	/**
	  * Nombre del Módulo: Laboratorio                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 23/junio/2011
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de pruebas a realizar.
	  **/	 	 
	 	 	
	
	//Genera la Id de la Alerta que será registrada en la tabla de alertas
	function obtenerIdAlerta(){		
		//Definir las tres letras en la Id de la Alerta
		$id_cadena = "ALR";
		
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el año actual para ser agregados en la consulta y asi obtener las entradas del mes y año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de alertas registradas en la BD
		$stm_sql = "SELECT MAX(id_alertas_prueba) AS clave FROM alertas_prueba WHERE id_alertas_prueba LIKE 'ALR$mes$anio%'";
		//Ejecutar Sentencia
		$rs = mysql_query($stm_sql);		
		//Evaluar Resultados y Generar Id a partir de ellos
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}		
		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdAlerta()
	
	
	/* Esta función se ecarga de buscar las pruebas que tienee que ser programadas*/  
	function monitorearPruebas(){
		//Borrar la tabla de alertas para guardar los registros de las alertas
		mysql_query("DELETE FROM alertas_prueba WHERE estado = 1");							
		
		/*********************************************************************************
		 * DETERMINAR QUE PRUEBAS ESTAN PROXIMAS A SER EJECUTADAS                        *
		 *********************************************************************************/
		$fechaBusq = date("d/m/Y");//Obtener fecha actual
		//Crear la Sentencia SQL para obtener todas las fechas de prueba programadas a las Muestras, las cuales no se les haya registrado los resultados correspondientes
		$stm_sql = "SELECT id_plan_prueba,fecha_programada FROM plan_pruebas WHERE estado = 0";		 
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			do{
				$idPlanPrueba = $datos['id_plan_prueba'];
				//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
				$fechaBdMod = modFecha($datos["fecha_programada"],1);
				$seccFechaBD = split("/",$fechaBdMod);
				$seccFechaBusq = split("/",$fechaBusq);
				//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año), Obtener la cantidad de dias por fecha
				$fechaIni_enDias = gregoriantojd ($seccFechaBD[1], $seccFechaBD[0], $seccFechaBD[2]);
				$fechaFin_enDias = gregoriantojd ($seccFechaBusq[1], $seccFechaBusq[0], $seccFechaBusq[2]);
				//Obtener la diferencia de dias de la fecha programada y la fecha actual
				$diferencia = ($fechaIni_enDias-$fechaFin_enDias);
				
				//Si Faltan 2 o menos dias para realizar la prueba, guardar la alerta para ser emitida
				if($diferencia<=2){
					$fecha = date("Y-m-d");
					//Antes de registrar la alerta, verificar si esta registrado el equipo en una alerta y si lo estan revisar el estado de la misma.				
					if($alerta=mysql_fetch_array(mysql_query("SELECT * FROM alertas_prueba WHERE plan_pruebas_id_plan_prueba = '$idPlanPrueba' GROUP BY estado ASC"))){							
						//El estado 2 indica que la alerta ya fue atendida; 
						if($alerta['estado']==2){
							$idAlerta = obtenerIdAlerta();
							$stm_sqlAlerta = "INSERT INTO alertas_prueba VALUES('$idAlerta' , '$datos[id_plan_prueba]', '1', '$fecha', '$diferencia')";
							$rs2 = mysql_query($stm_sqlAlerta);	
						}
					}
					//De lo contrario registrarlo en la Base de datos por primera ves
					else{
						$idAlerta=obtenerIdAlerta();
						$stm_sqlAlerta = "INSERT INTO alertas_prueba VALUES('$idAlerta' , '$datos[id_plan_prueba]', '1', '$fecha', '$diferencia')";
						$rs2 = mysql_query($stm_sqlAlerta);	
					}
				}
				
			}while($datos=mysql_fetch_array($rs));
		}	 
	}//Fin de la funcion monitorearPruebas()
	
	
	//Esta funcion muestra las alertas registradas en la BD, las alertas se mostraran desde dos dias antes y hasta que sean registrados los resultados de la prueba
	function desplegarAlertas(){
		//Conectarse con la BD de Laboratorio y mantener la conexion para utilizar las funciones de monitorearPruebas(), obtenerIdAlerta() y
		//las funciones para desplegar las alertas
		$conn = conecta("bd_laboratorio");	
		
		//Llamar a la función para monitoreo de las mezclas que estan proximas a ser probadas
		monitorearPruebas();									
		
		
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a prueba 
		$stm_sql = "SELECT id_alertas_prueba, plan_pruebas_id_plan_prueba FROM alertas_prueba WHERE estado = 1";		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Sentencia para contar el numero de alertas
		$num_alertas=mysql_num_rows($rs);
																																							
		/**********************************************************************************
		 * DESPLEGAR ALERTAS PARA LAS PRUEBAS QUE SE ENCUENTRAN PRÓXIMAS A SER REALIZADAS *
		 **********************************************************************************/			
					
		//Si la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas==1){									
			//Extraer el Id del plan de prueba del Result Set
			$datos = mysql_fetch_array($rs);
			//Obtener la Cantidad de dias restantes o exedidos para realizar la prueba indicada en el plan de prueba    
			$datos_cantRestante = mysql_fetch_array(mysql_query("SELECT dias_restantes FROM alertas_prueba WHERE id_alertas_prueba = '$datos[id_alertas_prueba]'"));
			$cantRestante = $datos_cantRestante['dias_restantes'];
			//Deplegar Ventana de Alerta de una sola alerta
			mostrarAlertas($datos['plan_pruebas_id_plan_prueba'],$cantRestante);
		}
		//Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de las mezclas que 
		//estan proximas para realizar pruebas
		else if($num_alertas>1){								
			//Mostrar solo un mensaje de varios prueba estan a punto de realizar las pruebas
			notificarAlerta($num_alertas);
		}												
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);								
	}//Cierre de la funcion desplegarAlertas();
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertas($id_plan_prueba, $cantRestante){		
		//Crear la sentencia para obtener los datos del Equipo, el cual ha generado una alerta
		$stm_sql = "SELECT id_muestra, fecha_colado, fecha_programada, nombre 
					FROM (plan_pruebas JOIN muestras ON muestras_id_muestra=id_muestra) JOIN mezclas ON mezclas_id_mezcla = id_mezcla 
					WHERE id_plan_prueba='$id_plan_prueba'";
		//Variable para almacenar el mensaje que sera desplegado en la alerta
		$msg="";
		
			
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Desplegar Ventana si los datos son extraidos exitosamente
		if($datos=mysql_fetch_array($rs)){
			//Determinar el Mensaje que sera desplegado dependiendo del numero de dias restantes
			if($cantRestante==0)
				$msg="La Prueba de la Mezcla <strong>$datos[nombre]</strong> debe ser realizada <strong>HOY</strong>"; 
			if($cantRestante>0)
				$msg="La Mezcla <strong> $datos[nombre] </strong> esta a <strong>$cantRestante </strong>d&iacute;as de Realizaci&oacute;n de Pruebas ";
			//Determinar el color de la ventana en base a la cantidad de dias faltantes o restantes
			$nom_form = "";
			if($cantRestante<0){
				$cantRestante = $cantRestante * -1;
				$msg = " Han Pasado <strong>$cantRestante</strong> d&iacute;as  para la realizaci&oacute;n de Pruebas a la Mezcla  <strong> $datos[nombre] </strong>"; 
				$nom_form = "_red";
				
			}				
			?>				
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>
									
			<body>						
				<script type="text/javascript" language="javascript">
					setTimeout("popup_show('popup', 'popup_drag', 'popup_exit', 'screen-center', 0, 0);",1000);
				</script>
				<!-- ********************************************************* Popup Window **************************************************** -->
				<div class="sample_popup" id="popup" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_drag">
						<img class="menu_form_exit" id="popup_exit" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
								ALERTA DE PRUEBA
					</div>
		
					<div class="menu_form_body<?php echo $nom_form;?>">
					<form name="frm_mostrarAlerta" action="frm_consultarAlertasPruebas.php" method="post">				
					<table>
						<tr>
							<td colspan="2" align="center">
								<?php echo $msg;?>
								<input type="hidden" name="hdn_idPlan" value="<?php echo $id_plan_prueba;?>" />
								<input type="hidden" name="hdn_idMuestra" value="<?php echo $datos['id_muestra'];?>" />
								<input type="hidden" name="hdn_nombre" value="<?php echo $datos['nombre'];?>" />							
								<input type="hidden" name="hdn_fechaColado" value="<?php echo $datos['fecha_colado'];?>" />							
								<input type="hidden" name="hdn_fechaProgramada" value="<?php echo $datos['fecha_programada'];?>" />																						
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="2">Fecha Colado: <strong><?php echo modFecha($datos['fecha_colado'],1);?></strong></td>
						</tr>
						<tr>
							<td align="center" colspan="2">Fecha Programada de Prueba: <strong><?php echo modFecha($datos['fecha_programada'],1);?></strong></td>
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><strong>&iquest;Registrar Resultado?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="sbt_aceptar" type="submit" value="Aceptar" class="botones" title="Registrar Resultado Ahora!" 
								onMouseOver="window.status='';return true" />
								<input type="hidden" name="hdn_org" id="hdn_org" />
							</td>
						</tr>
					</table>
					</form>
					</div>
				</div>
				<!-- ********************************************************* Popup Window **************************************************** -->						
			</body>
			<?php					
		} 
	}//Cierre de la funcion mostrarAlertas($id_plan_prueba, $cantRestante)

	
	/*Esta función despliega en una ventana de Alerta la cantidad de mezclas que son candidatas a recibir pruebas*/
	function notificarAlerta($num_alertas){?>
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>								
														
		<body>				
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popup', 'popup_drag', 'popup_exit', 'screen-center', 0, 0);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag">
				<img class="menu_form_exit" id="popup_exit" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					ALERTA DE PRUEBA
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_consultarAlertasPruebas.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							Un Total de <strong><?php echo $num_alertas; ?></strong> Mezclas Est&aacute;n Pr&oacute;ximas a Realizaci&oacute;n de <strong>Pruebas</strong>						
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda Consultar la Programaci&oacute;n de Pruebas</u>
						</td>						
					</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Mezclas?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Mezclas Ahora!" 
								onMouseOver="window.status='';return true" />
								<input type="hidden" name="hdn_org" id="hdn_org" />
							</td>
						</tr>
					</table>
					</form>
			</div>
		</div>		
		<!-- ********************************************************* Popup Window **************************************************** -->
		</body><?php 
	}
?>