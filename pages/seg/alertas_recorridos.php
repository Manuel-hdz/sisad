<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 01/Marzo/2012
	  * Descripción: Este archivo genera alertas de recorridos de seguridada partir del analisis de la información almacenada en la BD, toma la conexion en el 
	  *	archivo conexion.inc incluido
	  * en el archivo head_menu.php, para desplegar alertas de pruebas a realizar.
	  **/	 	 
	
	/* Esta función se encarga de buscar los recorridos que son necesarios a mostrar*/  
	function monitorearRecorridos(){
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		//Obtenemos el id del Departameno
		$idDepto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "usuario", $user);
		//Realizamos nuevamente la conexion a laBD; ya que la funcion anterior de obtener dato cierra dicha conexion
		$conn = conecta("bd_seguridad");
		
	    /******************************************************************************
		*         DETERMINAR QUE RECORRIDOS QUE ESTAN PROXIMOS A SER MOSTRADOS            *
	    ******************************************************************************/
		//Fecha que buscaremos como fecha base para restar la programada contra esta; siempre seta la actual
		$fechaBusq=date("d/m/Y");
		//Creamos la sentencia SQL
	 	$stm_sql = "SELECT * FROM alertas_recorridos_seguridad  WHERE estatus='1' AND catalogo_departamentos_id_departamento='$idDepto'";		 
		 //Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		//Comprobamos la existencia de DAtos
		if($datos = mysql_fetch_array($rs)){
			do{
				//Almacenamos los datos necesarios para trabajar posteriormente con ellos
				$idRecorrido=$datos['id_alerta_recorrido'];
				//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
				$fechaBdMod=modFecha($datos["fecha_programada"],1);
				$seccFechaBD = split("/",$fechaBdMod);
				$seccFechaBusq = split("/",$fechaBusq);
				//Cambiar la fecha Gregoriana a Juliana, parametros(mes,dia,año)
				$fechaIni_enDias = gregoriantojd ($seccFechaBD[1], $seccFechaBD[0], $seccFechaBD[2]);
				$fechaFin_enDias = gregoriantojd ($seccFechaBusq[1], $seccFechaBusq[0], $seccFechaBusq[2]);
				$diferencia = ($fechaIni_enDias-$fechaFin_enDias);				
			}while($datos=mysql_fetch_array($rs));
		}	 
	}//Fin de la funcion monitorearRecordatoriosExternos()
	
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertasRecorridos(){
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		//Obtenemos el id del Departameno
		$idDepto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "usuario", $user);
		//Conectarse con la BD de Seguridad y mantener la conexion para utilizar las funciones de monitorearRecordatoriosInternos() y 
		//las funciones para desplegar las alertas
		$conn = conecta("bd_seguridad");	
		
		//Llamar a la función para monitoreo de las mezclas que estan proximas a ser probadas
		monitorearRecorridos();									
		
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a prueba 
		$stm_sql = "SELECT * FROM alertas_recorridos_seguridad WHERE estatus = '1'  AND catalogo_departamentos_id_departamento='$idDepto'";		
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Sentencia para contar el numero de alertas
		$num_alertas=mysql_num_rows($rs);
		
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);																																					
		/**********************************************************************************
		 * DESPLEGAR ALERTAS PARA LAS PRUEBAS QUE SE ENCUENTRAN PRÓXIMAS A SER REALIZADAS *
		 **********************************************************************************/			
					
		//Si la Cantidad de Alertas es igual a 1, mandar llamar a la Funcion mostrarAlertas, que despliega una alerta por vez
		if($num_alertas==1){		
		
			//Tomamos las fechas del post y las convertimos a formato necesario para la consulta		
			$fechaIni=$datos["fecha_programada"];
			$fechaFin=date("Y-m-d");
		
			//Calculamos la diferencia que existe entre las dos fechas para obtener los dias de diferencia
			$seccFechaIni = split("-",$fechaIni);
			$seccFechaFin = split("-",$fechaFin);
			$fechaIni_enDias=gregoriantojd ($seccFechaIni[1], $seccFechaIni[2], $seccFechaIni[0]);
			$fechaFin_enDias=gregoriantojd ($seccFechaFin[1], $seccFechaFin[2], $seccFechaFin[0]);
			$cantRestante=$fechaFin_enDias-$fechaIni_enDias;
		
			//Deplegar Ventana de Alerta de una sola alerta
			mostrarAlertasRecorridos($cantRestante,$datos['id_alerta_recorrido']);
		}
		
		//Si la cantidad de alertas es mayor que 1, llamar a la función notificarAlerta, la cual despliega un aviso con la cantidad de las mezclas que 
		//estan proximas para realizar pruebas
		else if($num_alertas>1){								
			//Mostrar solo un mensaje de varios prueba estan a punto de realizar las pruebas
			notificarAlertasRecorridos($num_alertas);
		}												
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);								
	}//Cierre de la funcion desplegarAlertas();
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertasRecorridos($cantRestante,$idAlerta){	
		//Guardamos el Departamento Actual
		$user=$_SESSION['usr_reg'];
		
		//Obtenemos el id del Departameno
		$idDepto=obtenerDato("bd_usuarios", "usuarios", "no_depto", "usuario", $user);
			
		//Conectarse con la BD de Seguridad y mantener la conexion para utilizar las funciones de monitorearRecordatoriosInternos() y 
		//las funciones para desplegar las alertas
		$conn = conecta("bd_seguridad");	
		
		//Crear la sentencia para obtener los datos del Equipo, el cual ha generado una alerta
		$stm_sql = "SELECT * FROM alertas_recorridos_seguridad WHERE id_alerta_recorrido='$idAlerta'  AND catalogo_departamentos_id_departamento='$idDepto'";
		
		//Variable para almacenar el mensaje que sera desplegado en la alerta
		$msg="";
					
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Desplegar Ventana si los datos son extraidos exitosamente
		if($datos=mysql_fetch_array($rs)){
			//Determinar el Mensaje que sera desplegado dependiendo del numero de dias restantes
			if($cantRestante==0)
				$msg="El Recorrido de Seguridad<strong> $idAlerta</strong> ha sido Publicado <strong>HOY</strong>"; 
			if($cantRestante>0)
				$msg="El Recorrido de Seguridad <strong> $idAlerta </strong> tiene <strong>$cantRestante </strong>d&iacute;as de haber sido publicado";
			//Determinar el color de la ventana en base a la cantidad de dias faltantes o restantes
			$nom_form = "";
			if($cantRestante<0){
				$cantRestante = $cantRestante * -1;
				$msg = " Han Pasado <strong>$cantRestante</strong> d&iacute;as  desde que el Recorrido de Seguridad <strong> $idAlerta </strong> fue publicado"; 
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
					setTimeout("popup_show('popupRec', 'popup_dragRec', 'popup_exitRec', 'element', 735, 80);",1000);
				</script>
				<!-- ********************************************************* Popup Window **************************************************** -->
				<div class="sample_popup" id="popupRec" style="display: none;">
					<div align="center" class="menu_form_header" id="popup_dragRec">
						<img class="menu_form_exit" id="popup_exitRec" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
								AVISO ACTIVIDAD SEG INDUSTRIAL
					</div>
		
					<div class="menu_form_body<?php echo $nom_form;?>">
					<form name="frm_mostrarAlerta" action="frm_consultarRecSeg.php?idAlerta=<?php echo $idAlerta;?>" method="post">				
					<table>
						<tr>
							<td colspan="2" align="center">
								<?php echo $msg;?>
								<input type="hidden" name="hdn_idAlerta" value="<?php echo $datos['id_alerta_recorrido'];?>" />															
								<input type="hidden" name="hdn_fechaProgramada" value="<?php echo $datos['fecha_programada'];?>" />																						
							</td>						
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td align="center" colspan="2">Fecha Programada: <strong><?php echo modFecha($datos['fecha_programada'],1);?></strong></td>
						</tr>
						<tr>
							<td colspan="2" align="center">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><strong>&iquest;Ver Detalle?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="sbt_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Avisos Ahora!" 
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
	function notificarAlertasRecorridos($num_alertas){?>
		<head>				
			<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
			<link rel="stylesheet" type="text/css" href="includes/sample.css" />
			<script type="text/javascript" src="includes/popup-window.js"></script>
		</head>								
														
		<body>				
			<script type="text/javascript" language="javascript">
				setTimeout("popup_show('popupRec', 'popup_dragRec', 'popup_exitRec', 'element', 735, 80);",1000);
			</script>
			<!-- ********************************************************* Popup Window **************************************************** -->
			<div class="sample_popup" id="popupRec" style="display: none;">
			<div align="center" class="menu_form_header" id="popup_dragRec">
				<img class="menu_form_exit" id="popup_exitRec" src="includes/aviso-form-exit.png" alt="" title="Posponer" />
					ALERTA ACTIVIDAD SEG INDUSTRIAL
			</div>
			<div class="menu_form_body">
				<form name="frm_mostrarAlertaRecordatorio" action="frm_consultarRecSeg.php?idAlerta=1" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">
							Un Total de <strong><?php echo $num_alertas; ?></strong> <strong>Recorridos de Seguridad</strong> Han Sido Publicados						
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se recomienda Consultar la Informaci&oacute;n de los Recorridos de Seguridad</u></td>						
					</tr>
					<tr><td colspan="2" align="center"><strong><br>&iquest;Ver Detalle?</strong></td></tr>							
					<tr>
						<td align="center" colspan="2">
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Consultar Avisos Ahora!" 
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
