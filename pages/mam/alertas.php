<?php
	/**
	  * Nombre del Módulo: Mantenimiento                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 16/marzo/2011
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de proximos servicios preventivos de los equipos.
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
		$stm_sql = "SELECT MAX(id_alerta) AS clave FROM alertas WHERE id_alerta LIKE 'ALR$mes$anio%'";
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
	
	/*
	 * Esta función se ecarga de buscar los Equipos que estan proximos a resivir su Mtto. Preventivo, revisando primero los manejados por Kilometraje
	 *  y despues los manejados por Horas.
	 */  
	function monitorearEquipos(){
		//Borrar la tabla de alertas para registrar los nuevos cambios que existan en base a las bajas de los Equipo
		mysql_query("DELETE FROM alertas WHERE estado = 1");
		
		//Obtener la fecha del dia actual
		$fecha = date("Y-m-d");
		/*********************************************************************************
		 * DETERMINAR QUE EQUIPOS CON ODOMETRO ESTAN PROXIMOS A RECIBIR MTTO. PREVENTIVO *
		 *********************************************************************************/
		revisarEquiposOdometro($fecha);				
		
																		
		/**********************************************************************************
		 * DETERMINAR QUE EQUIPOS CON HOROMETRO ESTAN PROXIMOS A RECIBIR MTTO. PREVENTIVO *
		 **********************************************************************************/						
		revisarEquiposHorometro($fecha);
								
		/**********************************************************************************
		 * DETERMINAR QUE EQUIPOS CON HOROMETRO ESTAN PROXIMOS A RECIBIR MTTO. PREVENTIVO *
		 **********************************************************************************/						
		revisarServiciosProg($fecha);
	}//Fin de la funcion monitorearEquipos()
	
	/*Esta función verifica los Equipos con Servicio Programado proximos a recibir mantenimiento*/
	function revisarServiciosProg($fecha){
		$diasDespues=0;
		$diasAntes=1;
		$rsMttoProg=mysql_query("SELECT id_orden_trabajo FROM orden_trabajo JOIN bitacora_mtto ON id_orden_trabajo=orden_trabajo_id_orden_trabajo WHERE DATEDIFF(fecha_prog,'$fecha') BETWEEN $diasDespues AND $diasAntes AND fecha_mtto='0000-00-00'");
		//Contador para ver cuantas ordenes de trabajo estan proximas a ejecutarse
		$cantOrdenes=0;
		$idOrden="";
		if($datosMttoProg=mysql_fetch_array($rsMttoProg)){
			//Obtener la primer orden de Trabajo
			$idOrden=$datosMttoProg["id_orden_trabajo"];
			do{
				//incrementar la variable de cantidad de ordenes de trabajo
				$cantOrdenes++;
			}while($datosMttoProg=mysql_fetch_array($rsMttoProg));
		}
		if($cantOrdenes>=1)
			mostrarAlertaMttoProg($cantOrdenes,$idOrden);
	}
	
	/*Esta función verifica los Equipos con Odometro que estan proximos a recibir su Mtto. Preventivo*/
	function revisarEquiposOdometro($fecha){
		
		/*********************************************************************************
		 * DETERMINAR QUE EQUIPOS CON ODOMETRO ESTAN PROXIMOS A RECIBIR MTTO. PREVENTIVO *
		 *********************************************************************************/				
		
		//Mostrar las alertas con una anticipación de 150 Kms.
		$kms_faltantes = 150;
		$kms_servicio = 5000;
		//Obtener las claves de los Equipos a Evaluar
		$rs_idEquipos_odo = mysql_query("SELECT id_equipo FROM equipos WHERE metrica = 'ODOMETRO' AND estado='ACTIVO' ORDER BY id_equipo");
		if($idEquipos_odo=mysql_fetch_array($rs_idEquipos_odo)){
			do{				
				
				//Obtener el Id del Equipo a evaluar
				$idEquipo = $idEquipos_odo['id_equipo'];
				//Obtener la Cantidad de Kilometros del ultimo Mtto. Registrado en la Bitacora de Mtto.
				$stm_sql_odo = "SELECT odometro FROM bitacora_mtto WHERE equipos_id_equipo = '$idEquipo' 
								AND fecha_mtto = (SELECT MAX(fecha_mtto) FROM bitacora_mtto WHERE equipos_id_equipo = '$idEquipo') AND tipo_mtto = 'PREVENTIVO'";
				
				
				//Si se obtiene un registro de la Bitacora de Mtto se procede a obtener la cant. de Kilometros que faltan para realizar el Mtto.				
				if($dato_odometro=mysql_fetch_array(mysql_query($stm_sql_odo))){	
					//Cuando se registra un OT y el Equipo no tiene una Bitacora registrada previamente, el valor del Odómetro regresado estara vacio y por eso hay que verficarlo
					if($dato_odometro['odometro']!=""){
						$odometro = $dato_odometro['odometro'];
						//Obtener la Cantidad de Kilometros recorridos del vehiculo a la fecha actual
						$stm_sql_dif = "SELECT reg_final,($kms_servicio - (reg_final - $odometro)) AS kms_restantes FROM horometro_odometro 
										WHERE fecha = '$fecha' AND equipos_id_equipo = '$idEquipo' ORDER BY reg_final DESC";					
					}//Cierre if($dato_odometro['odometro']!="")
					else{//Si el odómetro obtenido es igual a vacio, se procede a obtener la diferencia entre los 5,000 Kms y el reg. actual del Odometro
						$stm_sql_dif = "SELECT reg_final,($kms_servicio - reg_final) AS kms_restantes FROM horometro_odometro 
						WHERE fecha = '$fecha' AND equipos_id_equipo = '$idEquipo' ORDER BY reg_final DESC";
					}
				}//Cierre if($dato_odometro=mysql_fetch_array(mysql_query($stm_sql_odo)))
				else{//Si no tiene ningun registro en la Bitacora del Equipo actual, se procede a obtener la diferencia entre los 5,000 Kms y el reg. actual del Odometro
					$stm_sql_dif = "SELECT reg_final,($kms_servicio - reg_final) AS kms_restantes FROM horometro_odometro 
					WHERE fecha = '$fecha' AND equipos_id_equipo = '$idEquipo' ORDER BY reg_final DESC";				
				}
				
				
				//Ejecutar la consulta para obtener la cantidad restante de kilometros para que el Equipo reciba el Mtto. Preventivo
				$rs_KmsRestantes = mysql_query($stm_sql_dif);
				//Verificar los datos Obtenidos
				if($datos_KmsRestantes=mysql_fetch_array($rs_KmsRestantes)){
					//Obtener la cantidad de Kilometros restantes
					$kmsRestantes = $datos_KmsRestantes['kms_restantes'];
					//Si los Kilometros restantes son menores a los kilometros faltantes guardar la Alerta en la tabla de Alertas
					if($kmsRestantes<$kms_faltantes){
						//Antes de registrar la alerta, verificar si esta registrado el equipo en una alerta y si lo estan revisar el estado de la misma.				
						if($alerta=mysql_fetch_array(mysql_query("SELECT * FROM alertas WHERE equipos_id_equipo = '$idEquipo' GROUP BY estado ASC"))){
							//El estado 3 indica que el equipo tenia una alerta registrada previamente y el Mtto Preventivo ya fue realizado a ese equipo
							if($alerta['estado']==3){
								$id_alerta = obtenerIdAlerta();
								mysql_query("INSERT INTO alertas (id_alerta, equipos_id_equipo, estado, fecha_generacion,origen,ultimo_reg,cant_restante) 
											 VALUES('$id_alerta','$idEquipo',1,'$fecha','ODO',$datos_KmsRestantes[reg_final],$kmsRestantes)");
							}
						}
						else{//En el caso de que no exista ninguna alerta registrada para el Vehiculo actual, registrar una por primera vez
							$id_alerta = obtenerIdAlerta();							
							mysql_query("INSERT INTO alertas (id_alerta, equipos_id_equipo, estado, fecha_generacion,origen,ultimo_reg,cant_restante) 
										 VALUES('$id_alerta','$idEquipo',1,'$fecha','ODO',$datos_KmsRestantes[reg_final],$kmsRestantes)");
						}
					}//Cierre if($kmsRestantes<$kms_faltantes)					
				}//Cierre if($datos_KmsRestantes=mysql_fetch_array($rs_KmsRestantes))				
				
			}while($idEquipos_odo=mysql_fetch_array($rs_idEquipos_odo));
		}//Cierre if($datos_idEquipos=mysql_fetch_array($rs_idEquipos))
	}//Cierre de la funcion revisarEquiposOdometro($fecha)
	
	
	/*Esta función verifica los Equipos con Odometro que estan proximos a recibir su Mtto. Preventivo*/
	function revisarEquiposHorometro($fecha){
		/**********************************************************************************
		 * DETERMINAR QUE EQUIPOS CON HOROMETRO ESTAN PROXIMOS A RECIBIR MTTO. PREVENTIVO *
		 **********************************************************************************/
		//Mostrar las alertas con 20 Hrs de anticipacion
		$hrs_faltantes = 20;
		//Obtener las claves de los Equipos a Evaluar
		$rs_idEquipos_horo = mysql_query("SELECT id_equipo FROM equipos WHERE metrica = 'HOROMETRO' AND estado='ACTIVO' AND area='MINA' ORDER BY id_equipo");
		echo "<br><br><br><br><br><br><br><br><br>";
		if($idEquipos_horo=mysql_fetch_array($rs_idEquipos_horo)){
			do{	
				//Obtener el Id del Equipo a evaluar
				$idEquipo = $idEquipos_horo['id_equipo'];
				//Obtener la Cantidad de Horas del ultimo Mtto. Registrado en la Bitacora de Mtto y la Orden de Trabajo Asociada
				$stm_sql_horo = "SELECT hrs_acum,fecha_mtto FROM acumulado_servicios WHERE equipos_id_equipo = '$idEquipo'";
				//Si se obtiene un registro de la Bitacora de Mtto se procede a obtener la cant. de Horas que faltan para realizar el Mtto.
				if($dato_horometro=mysql_fetch_array(mysql_query($stm_sql_horo))){	
					//Obtener el Horometro del equipo registrado en la Bitacora de Mtto.
					$hrs = $dato_horometro['hrs_acum'];
					//Obtener la cantidad de Horas necesarias para el siguiente servicio, se encuentra predispuesto a ciclos de 250 horas
					$horasServicio = 250-$hrs;
					if($horasServicio<=$hrs_faltantes){
						//Antes de registrar la alerta, verificar si esta registrado el equipo en una alerta y si lo estan revisar el estado de la misma.				
						if($alerta=mysql_fetch_array(mysql_query("SELECT * FROM alertas WHERE equipos_id_equipo = '$idEquipo' GROUP BY estado ASC"))){
							//El estado 3 indica que el equipo tenia una alerta registrada previamente y el Mtto Preventivo ya fue realizado a ese equipo
							if($alerta['estado']==3){
								$id_alerta = obtenerIdAlerta();								
								mysql_query("INSERT INTO alertas (id_alerta, equipos_id_equipo, estado, fecha_generacion,origen,ultimo_reg,cant_restante) 
											 VALUES('$id_alerta','$idEquipo',1,'$fecha','HORO',$hrs,$horasServicio)");
							}
						}
						else{//En el caso de que no exista ninguna alerta registrada para el Vehiculo actual, registrar una por primera vez
							$id_alerta = obtenerIdAlerta();							
							mysql_query("INSERT INTO alertas (id_alerta, equipos_id_equipo, estado, fecha_generacion,origen,ultimo_reg,cant_restante) 
										 VALUES('$id_alerta','$idEquipo',1,'$fecha','HORO',$hrs,$horasServicio)");
						}
					}
				}
			}while($idEquipos_horo=mysql_fetch_array($rs_idEquipos_horo));
		}//Cierre if($idEquipos_horo=mysql_fetch_array($rs_idEquipos_horo))
	}//Cierre de funcion revisarEquiposHorometro($fecha)
	
	
	function obtenerHorasSigServicio($horometro,$ordenTrabajo){	
		$horasSigServicio = 0;		
		$opcHoras = array(250,500,750,1000);
		//Obtener las Gamas asociadas a una Orden de Trabajo que este registrada en la Bitacora de Mtto.
		$rs = mysql_query("SELECT gama_id_gama, ciclo_servicio FROM ((bitacora_mtto JOIN orden_trabajo ON orden_trabajo_id_orden_trabajo=id_orden_trabajo)
						   JOIN actividades_ot ON orden_trabajo.id_orden_trabajo=actividades_ot.orden_trabajo_id_orden_trabajo)
						   JOIN gama ON gama_id_gama=id_gama
						   WHERE bitacora_mtto.orden_trabajo_id_orden_trabajo = '$ordenTrabajo' AND tipo_mtto = 'PREVENTIVO'");		
						  
		$cantReg = mysql_num_rows($rs);
		
		//Obtener la Cantidad de Horas correspondiente al ultimo Mtto. Preventivo realizado al Equipo
		if($cantReg==1){
			$datos = mysql_fetch_array($rs);
			$ultimoServicio = $datos['ciclo_servicio'];			
		}
		else{
			$ciclos = array();
			while($datos = mysql_fetch_array($rs))
				$ciclos[] = $datos['ciclo_servicio'];
			$ultimoServicio = max($ciclos);						
		}
		
		
		//Obtener la Cantidad de Horas que se deben cumplir para ralizar el siguiente Mtto. Preventivo
		$clave;
		foreach($opcHoras as $key => $value){
			if($value==$ultimoServicio){
				if($key==3)
					$clave = 0;
				else
					$clave = $key + 1;
									
				$horasSigServicio = $opcHoras[$clave];				
			}
		}
		
		return $horasSigServicio;
	}//Cierre de la funcion obtenerHorasSigServicio($horometro,$ordenTrabajo)
	
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertas(){
		//Conectarse con la BD de Almacen y mantener la conexion para utilizar las funciones de monitorearEquipos(), obtenerIdAlerta() y las funciones para desplegar las alertas
		$conn = conecta("bd_mantenimiento");	
		
		//Llamar a la función para monitoreo de los Equipos que estan proximos a Recibir Mtto. Preventivo
		monitorearEquipos();									
		
		
		/*Determinar cual usuario esta logeado y en base a ello desplegar las alertas que le Corresponden*/
		if($_SESSION['depto']=="MttoConcreto"){
			//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Equipos con ODOMETRO
			$stm_sql_odo = "SELECT id_alerta, equipos_id_equipo FROM alertas JOIN equipos ON equipos_id_equipo=id_equipo WHERE alertas.estado = 1 AND origen='ODO' AND area='CONCRETO'";		
			//Ejecutar la sentencia previamente creada
			$rs_odo = mysql_query($stm_sql_odo);
			//Sentencia para contar el numero de alertas
			$num_alertas_odo=mysql_num_rows($rs_odo);
			
			
			//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Equipos con HOROMETRO
			$stm_sql_horo = "SELECT id_alerta, equipos_id_equipo FROM alertas JOIN equipos ON equipos_id_equipo=id_equipo WHERE alertas.estado = 1 AND origen='HORO' AND area='CONCRETO'";		
			//Ejecutar la sentencia previamente creada
			$rs_horo = mysql_query($stm_sql_horo);
			//Sentencia para contar el numero de alertas
			$num_alertas_horo=mysql_num_rows($rs_horo);									
		}
		else if($_SESSION['depto']=="MttoMina"){//Determinar que alertas mostrar para el usuario AdminMttoMina									
			//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Equipos con ODOMETRO
			$stm_sql_odo = "SELECT id_alerta, equipos_id_equipo FROM alertas JOIN equipos ON equipos_id_equipo=id_equipo WHERE alertas.estado = 1 AND origen='ODO' AND area='MINA'";		
			//Ejecutar la sentencia previamente creada
			$rs_odo = mysql_query($stm_sql_odo);
			//Sentencia para contar el numero de alertas
			$num_alertas_odo=mysql_num_rows($rs_odo);
			
			
			//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Equipos con HOROMETRO
			$stm_sql_horo = "SELECT id_alerta, equipos_id_equipo FROM alertas JOIN equipos ON equipos_id_equipo=id_equipo WHERE alertas.estado = 1 AND origen='HORO' AND area='MINA'";		
			//Ejecutar la sentencia previamente creada
			$rs_horo = mysql_query($stm_sql_horo);
			//Sentencia para contar el numero de alertas
			$num_alertas_horo=mysql_num_rows($rs_horo);									
		}
		else{//Determinar que alertas mostrar para el usuario AuxMtto
			//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Equipos con ODOMETRO
			$stm_sql_odo = "SELECT id_alerta, equipos_id_equipo FROM alertas WHERE estado = 1 AND origen='ODO'";		
			//Ejecutar la sentencia previamente creada
			$rs_odo = mysql_query($stm_sql_odo);
			//Sentencia para contar el numero de alertas
			$num_alertas_odo=mysql_num_rows($rs_odo);
			
			//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Equipos con HOROMETRO
			$stm_sql_horo = "SELECT id_alerta, equipos_id_equipo FROM alertas WHERE estado = 1 AND origen='HORO'";
			//Ejecutar la sentencia previamente creada que corresponde a buscar Órdenes de Compra
			$rs_horo = mysql_query($stm_sql_horo);
			//Sentencia para contar el numero de alertas de Órdenes de Compra
			$num_alertas_horo=mysql_num_rows($rs_horo);
		}																				
			
												
		/***************************************************
		 * DESPLEGAR ALERTAS PARA LOS EQUIPOS CON ODOMETRO *
		 ***************************************************/			
		$ctrl = 0;//Controlar el Id de las ventas de alerta y la inclusion de los archivos necesarios para desplegar las Alertas	
		$origen="odometro";//Especificar el Origen de la alerta
		//Si la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas_odo==1){									
			//Extraer el Id del Equipo del Result Set
			$datos_odo = mysql_fetch_array($rs_odo);
			//Obtener la Cantidad restante
			$datos = mysql_fetch_array(mysql_query("SELECT cant_restante FROM alertas WHERE id_alerta = '$datos_odo[id_alerta]'"));
			$cantRestante = $datos['cant_restante'];
			//Deplegar Ventana de Alerta de un solo Equipo con Odómetro
			mostrarAlertas($datos_odo['equipos_id_equipo'],$origen,$ctrl,$cantRestante);
			$ctrl = 1;//Indicar que ya fueron desplegados los encabezados para desplegar las Alertas
		}
		//Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de Equipos que están proximos a recibir Mtto. Preventivo
		else if($num_alertas_odo>1){								
			//Mostrar solo un mensaje de varios equipos estan a punto de recibir Mtto Preventivo con Odómetro
			notificarAlerta($num_alertas_odo,$origen,$ctrl);
			$ctrl = 1;//Indicar que ya fueron desplegados los encabezados para desplegar las Alertas
		}												
		
		
		/****************************************************
		 * DESPLEGAR ALERTAS PARA LOS EQUIPOS CON HOROMETRO *
		 ****************************************************/					
		$origen="horometro";//Especificar el Origen de la alerta
		//Si la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas_horo==1){						
			//Extraer el Id del Equipo del Result Set
			$datos_horo = mysql_fetch_array($rs_horo);
			//Obtener la Cantidad restante
			$datos = mysql_fetch_array(mysql_query("SELECT cant_restante FROM alertas WHERE id_alerta = '$datos_horo[id_alerta]'"));
			$cantRestante = $datos['cant_restante'];
			//Deplegar Ventana de Alerta de un solo Equipo con Horómetro
			mostrarAlertas($datos_horo['equipos_id_equipo'],$origen,$ctrl,$cantRestante);
		}
		//Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de Equipos que están proximos a recibir Mtto. Preventivo
		else if($num_alertas_horo>1){			
			//Mostrar solo un mensaje de varios equipos estan a punto de recibir Mtto Preventivo con Horómetro
			notificarAlerta($num_alertas_horo,$origen,$ctrl);
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);								
	}//Cierre de la funcion desplegarAlertas();
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertas($id_equipo, $origen, $num, $cantRestante){		
		
		//Crear la sentencia para obtener los datos del Equipo, el cual ha generado una alerta, para ser desplegados en la misma.
		$stm_sql = "SELECT DISTINCT id_equipo, nom_equipo, area, familia, metrica, ultimo_reg FROM (equipos JOIN horometro_odometro ON id_equipo=equipos_id_equipo) 
					JOIN alertas ON id_equipo=alertas.equipos_id_equipo WHERE alertas.equipos_id_equipo='$id_equipo' and alertas.estado = 1";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Desplegar Ventana si los datos son extraidos exitosamente
		if($datos=mysql_fetch_array($rs)){
		
			$nom_form = "";
			$msj2 = "Restantes";
			if($cantRestante<0){ 
				$msj2 = "Sobrepasados"; 
				$nom_form = "_red";
				$cantRestante = $cantRestante * -1;
			}
			
			//Si $ctrl vale 0, ninguna ventana ha sido desplegada, por lo tanto incuir los archivos para hacer el despliegue			
			if ($num==0){?>					
				<head>				
					<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
					<link rel="stylesheet" type="text/css" href="includes/sample.css" />
					<script type="text/javascript" src="includes/popup-window.js"></script>
				</head>
			<?php }?>			
			
			<body>						
				<script type="text/javascript" language="javascript">
					<?php if ($origen=="odometro"){?>
						setTimeout("popup_show('popup<?php echo $num?>', 'popup_drag<?php echo $num?>', 'popup_exit<?php echo $num?>', 'screen-center', 0, 0);",1000);
					<?php }else{?>
						setTimeout("popup_show('popup<?php echo $num?>', 'popup_drag<?php echo $num?>', 'popup_exit<?php echo $num?>', 'screen-bottom-right', 0, 0);",1000);
					<?php }?>
				</script>
				<!-- ********************************************************* Popup Window **************************************************** -->
				<div class="sample_popup" id="popup<?php echo $num?>" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_drag<?php echo $num?>">
						<img class="menu_form_exit" id="popup_exit<?php echo $num?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
						<?php 
							if($origen=="odometro"){
								echo "EQUIPOS CON ODOMETRO";
								$msj = "Kil&oacute;metros";
							}
							else{
								echo "EQUIPOS CON HOROMETRO";
								$msj = "Horas";
							}
						?>
					</div>
		
					<div class="menu_form_body<?php echo $nom_form;?>">
					<form name="frm_mostrarAlerta" action="frm_generarOrdenTrabajo.php" method="post">				
					<table>
						<tr>
							<td colspan="2" align="center">
								El Siguiente Equipo Est&aacute; Pr&oacute;ximo a Recibir su Mtto. Preventivo
								<input type="hidden" name="hdn_area" value="<?php echo $datos['area'];?>" />							
								<input type="hidden" name="hdn_familia" value="<?php echo $datos['familia'];?>" />							
								<input type="hidden" name="hdn_idEquipo" value="<?php echo $datos['id_equipo'];?>" />							
								<input type="hidden" name="hdn_metrica" value="<?php echo $datos['metrica'];?>" />							
								<input type="hidden" name="hdn_ultimoReg" value="<?php echo $datos['ultimo_reg'];?>" />															
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td width="155" align="center" colspan="2">Clave: <strong><?php echo $datos['id_equipo'];?></strong></td>
						</tr>
						<tr>
							<td align="center" colspan="2">Equipo: <strong><?php echo $datos['nom_equipo'];?></strong></td>
						</tr>
						<tr>
							<td align="center" colspan="2">Tipo de Medici&oacute;n: <strong><?php echo $datos['metrica'];?></strong></td>
						</tr>
						<tr>
							<td align="center" colspan="2">&Uacute;ltimo Registro: <strong><?php echo $datos['ultimo_reg']." ".$msj;?></strong></td>
						</tr>
						<tr>							
							<td align="center" colspan="2"><?php echo $msj." ".$msj2;?>: <strong><?php echo $cantRestante; ?></strong></td>
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><strong>&iquest;Generar Orden de Trabajo?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="sbt_aceptar" type="submit" value="Aceptar" class="botones" title="Generar Orden de Trabajo Ahora!" onMouseOver="window.status='';return true" />
								<input type="hidden" name="hdn_org" id="hdn_org" value="<?php echo $origen;?>"/>
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
	}//Cierre de la funcion mostrarAlertas($id_equipo, $origen, $num)

	
	/*Esta función despliega en una ventana de Alerta la cantidad de Vehiculos que estan proximos a recibir Mtt. Preventivo*/
	function notificarAlerta($num_alertas,$origen,$ctrl){
					
		//Si $ctrl vale 0, ninguna ventana ha sido desplegada, por lo tanto incuir los archivos para hacer el despliegue
		if ($ctrl==0){?>
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>			
		<?php }?>						
														
		<body>				
		<script type="text/javascript" language="javascript">
			<?php if ($origen=="odometro"){?>
				setTimeout("popup_show('popup<?php echo $ctrl?>', 'popup_drag<?php echo $ctrl?>', 'popup_exit<?php echo $ctrl?>', 'screen-center', 0, 0);",1000);
			<?php }else{?>
				setTimeout("popup_show('popup<?php echo $ctrl?>', 'popup_drag<?php echo $ctrl?>', 'popup_exit<?php echo $ctrl?>', 'screen-bottom-right', 0, 0);",1000);
			<?php }?>
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup<?php echo $ctrl?>" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag<?php echo $ctrl?>">
				<img class="menu_form_exit" id="popup_exit<?php echo $ctrl?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
				<?php if($origen=="odometro")
							echo "EQUIPOS CON ODOMETRO";
						else
							echo "EQUIPOS CON HOROMETRO";
				?>
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_consultarAlertas.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							Un Total de <strong><?php echo $num_alertas; ?></strong> Equipos Est&aacute;n Pr&oacute;ximos a Recibir su Mtto. Preventivo							
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda generar Ordenes de Trabajo</u>
						</td>						
					</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Equipos?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Generar &Oacute;rdenes de Trabajo Ahora!" onMouseOver="window.status='';return true" />
								<input type="hidden" name="hdn_org" id="hdn_org" value="<?php echo $origen;?>"/>
							</td>
						</tr>
					</table>
					</form>
			</div>
		</div>		
		<!-- ********************************************************* Popup Window **************************************************** -->
		</body><?php 
	}
	
	function mostrarAlertaMttoProg($cantOrdenes,$idOrden){
		$ctrl=21;
		?>
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>
		<body>				
		<script type="text/javascript" language="javascript">
			setTimeout("popup_show('popup<?php echo $ctrl?>', 'popup_drag<?php echo $ctrl?>', 'popup_exit<?php echo $ctrl?>', 'screen-bottom-left', 0, 0);",1000);
		</script>
		<!-- ********************************************************* Popup Window **************************************************** -->
		<div class="sample_popup" id="popup<?php echo $ctrl?>" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_drag<?php echo $ctrl?>">
				<img class="menu_form_exit" id="popup_exit<?php echo $ctrl?>" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
				&Oacute;RDENES PR&Oacute;XIMAS
			</div>
			<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_gestionarProgMtto.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							<?php
							if($cantOrdenes==1)
								echo "La &Oacute;rden de Trabajo <strong>$idOrden</strong> esta Pr&oacute;xima a Llegar a su Fecha de Programaci&oacute;n";
							else
								echo "Existen $cantOrdenes Pr&oacute;ximas a Llegar a su Fecha de Programaci&oacute;n";
							?>
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se Recomienda Tomar Medidas</u>
						</td>						
					</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Programaci&oacute;n?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Programaci&oacute;n de Servicios de Mantenimiento" onMouseOver="window.status='';return true" />
								<?php if ($cantOrdenes==1){?>
									<input type="hidden" name="hdn_orden" id="hdn_orden" value="<?php echo $idOrden?>"/>
								<?php }?>
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