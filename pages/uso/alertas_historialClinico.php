<?php
	/**
	  * Nombre del Módulo: UYnidad de Salud Ocupacional                                               
	  * Nombre Programador:Nadia Madahi López Hernandez
	  * Fecha: 02/Agosto/2012
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de los Historiales Clinicos a realizar
	  **/	 	 
	 	 	
	//Esta funcion genera la Clave del Historial de acuerdo a los registros en la BD
	function obtenerIdAlertaHistorialClinicoHistorialClinico(){
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		
		//Definir las tres letras la clave del historial
		$id_cadena = "HIS";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT COUNT(id_alerta_exa) AS cant FROM alerta_examen WHERE id_alerta_exa LIKE 'AHC$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}	
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		return $id_cadena;
	}//Fin de la Funcion obtenerIdAlertaHistorialClinicoHistorialClinico()		
	
	
	
	/* Esta función se encarga de monitorear o buscar los examenes medicos que estan proximos a generarse nuevamente*/  
	function monitorearAlertasHistorialClinico(){							
		//las funciones para desplegar las alertas
		$conn = conecta("bd_clinica");
			
		//Determinar que examenes medicos estan proximos a ejecutarse o realizarce
		$fechaBusq = date("d/m/Y");//Obtener fecha actual
		//Crear la Sentencia SQL para obtener todas las fechas de los examenes medicos que han sido programados, las cuales no se les haya generado un nuevo examen
		$stm_sql = "SELECT id_alerta_exa, id_historial, catalogo_departamentos_id_departamento, fecha_programada,fecha_exp, 
			alerta_examen.nom_empleado, alerta_examen.id_empleados_empresa FROM alerta_examen JOIN  historial_clinico ON id_historial = historial_clinico_id_historial 
			WHERE estado = '0'";		 	
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		if($datos = mysql_fetch_array($rs)){
			do{
				//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
				$fechaBdMod = modFecha($datos["fecha_programada"],1);
				$seccFechaBD = split("/",$fechaBdMod);
				$seccFechaBusq = split("/",$fechaBusq);
				//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año), Obtener la cantidad de dias por fecha
				$fechaIni_enDias = gregoriantojd ($seccFechaBD[1], $seccFechaBD[0], $seccFechaBD[2]);
				$fechaFin_enDias = gregoriantojd ($seccFechaBusq[1], $seccFechaBusq[0], $seccFechaBusq[2]);
				//Obtener la diferencia de dias de la fecha programada y la fecha actual
				$diferencia = ($fechaIni_enDias-$fechaFin_enDias);
				
				//Si Faltan 2 o menos dias para realizar el nuevo examen clinico, guardar la alerta para ser emitida
				if($diferencia<=5){
					mysql_query("UPDATE alerta_examen SET estado = '1' WHERE id_alerta_exa = '$datos[id_alerta_exa]'");
				}
			}while($datos=mysql_fetch_array($rs));
		}	
		mysql_close($conn); 
	}//Fin de la funcion monitorearAlertasHistorialClinico()
	
	
	//Esta funcion muestra las alertas registradas en la BD, las alertas se mostraran desde 2 dias antes y hasta que sean registrados los resultados del Historial Clinico
	function desplegarAlertasHistorialClinico(){		
		
		//Llamar a la función para monitoreo las Alertas del examen medico que estan proximas a ser ejecutadas o realizadas
		monitorearAlertasHistorialClinico();									
		
		/*****************************/
		//las funciones para desplegar las alertas
		$conn = conecta("bd_clinica");	
		/*****************************/
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden al historial que se programo  
		$stm_sql = "SELECT id_alerta_exa, fecha_programada FROM alerta_examen WHERE  estado ='1'";		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Sentencia para contar el numero de alertas
		$num_alertas=mysql_num_rows($rs);
																																							
		/******************************************************************************************
		 * DESPLEGAR ALERTAS PARA LOS EXAMENES MEDICOS QUE SE ENCUENTRAN PRÓXIMOS A SER REALIZADOS *
		 ******************************************************************************************/					
		//1 la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas==1){									
			//Extraer el Id del historial de prueba del Result Set
			$datos = mysql_fetch_array($rs);
			//Obtener la Cantidad de dias restantes o exedidos para realizar el historial indicada en la tabla de alertas_examen
			$datos_cantRestante = mysql_fetch_array(mysql_query("SELECT DATEDIFF('$datos[fecha_programada]',NOW()) AS dias_restantes, id_empleados_empresa FROM alerta_examen WHERE id_alerta_exa = '$datos[id_alerta_exa]'"));
			$cantRestante = $datos_cantRestante['dias_restantes'];
			//Deplegar Ventana de Alerta de una sola alerta
			mostrarAlertasHC($datos_cantRestante['id_empleados_empresa'],$cantRestante, $datos['fecha_programada']);
		}
		/*Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de las planes que estan 
		proximas para realizar o ejecutar*/
		else if($num_alertas>1){								
			//Mostrar solo un mensaje de varios planes estan a punto de ejecutar
			notificarAlertaHC($num_alertas);
		}												
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);								
	}//Cierre de la funcion desplegarAlertasHistorialClinico();
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la clave  que recibe como parametro
	function mostrarAlertasHC($numEmp, $cantRestante, $fechaProg){		
		//Obtener el RFC del empleado al cual se le reguistro una historial clinico el cual genero una alert
		$rfcEmp = obtenerDato("bd_recursos", "empleados", "rfc_empleado", "id_empleados_empresa", $numEmp);
		//Obtener el nombre del empleado con el rfc de la funcion anterior para que dicho nombre se despliegue o muestre en la alerta
		$nomEmp = obtenerNombreEmpleado($rfcEmp);
		
		//Variable para almacenar el mensaje que sera desplegado en la alerta
		$msg="";
			//Determinar el Mensaje que sera desplegado dependiendo del numero de dias restantes
			if($cantRestante==0)
				$msg="El Examen Medico al Trabajador <strong>$nomEmp</strong> debe ser realizado <strong>HOY</strong>"; 
			if($cantRestante>0)
				$msg="El Historial Medico del Trabajador <strong> $nomEmp </strong> esta a <strong>$cantRestante </strong>d&iacute;as de Generarse Nuevamente ";
			//Determinar el color de la ventana en base a la cantidad de dias faltantes o restantes
			$nom_form = "";
			if($cantRestante<0){
				$cantRestante = $cantRestante * -1;
				$msg = " Han Pasado <strong>$cantRestante</strong> d&iacute;as para la generaci&oacute;n de un Nuevo Historial Clinico para el Trabajador <strong>$nomEmp</strong>"; 
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
					setTimeout("popup_show('popup_examen', 'popup_drag_examen', 'popup_exit_examen', 'screen-center', 0, 200);",1000);
				</script>
				<!-- ********************************************************* Popup Window **************************************************** -->
				<div class="sample_popup" id="popup_examen" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_drag_examen">
						<img class="menu_form_exit" id="popup_exit_examen" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
								RECORDATORIO HISTORIAL CLINICO
					</div>
		
					<div class="menu_form_body<?php echo $nom_form;?>">
					<form name="frm_mostrarAlerta" action="frm_consultarAlertasHisClinico.php" method="post">				
					<table>
						<tr>
							<td colspan="2" align="center">
								<?php echo $msg;?>																				
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="2">Fecha Programada: <strong><?php echo modFecha($fechaProg,1);?></strong></td>
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><strong>&iquest;Registrar Resultado?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="sbt_aceptar" type="submit" value="Aceptar" class="botones" title="Generar Nuevo Historial Clinico" 
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
	}//Cierre de la funcion mostrarAlertasHC($id_historial, $cantRestante)

	
	/*Esta función despliega en una ventana de Alerta la cantidad de historiales que son candidatos a registrarse o generar nuevamente*/
	function notificarAlertaHC($num_alertas){?>
		<head>				
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="includes/sample.css" />
			<script type="text/javascript" src="includes/popup-window.js"></script>
		</head>								
														
		<body>				
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popup_examen', 'popup_drag_examen', 'popup_exit_examen', 'screen-center', 0, 200);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup_examen" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag_examen">
				<img class="menu_form_exit" id="popup_exit_examen" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					RECORDATORIO HISTORIAL CLINICO
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_consultarAlertasHisClinico.php" method="post">
					<table>
						<tr>
							<td colspan="2" align="center">
									Un Total de <strong><?php echo $num_alertas; ?></strong> Historiales Clinicos Est&aacute;n Pr&oacute;ximos a Realizarse 					
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda Consultar la Programaci&oacute;n de los Historiales Clinicos</u>
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Historiales Clinicos Programados?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Historial Clinico Ahora!" 
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