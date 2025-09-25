<?php
	/**
	  * Nombre del Módulo: Compras                                               
	  * Nombre Programador: Miguel Angel Garay Castro                
	  * Fecha: 06/Septiembre/2011                                      			
	  * Descripción: Este archivo genera las alertas a partir del analisis de la información almacenada en la BD, toma la conexion en el archivo conexion.inc incluido
	  * en el archivo head_menu.php
	  **/	 	 
	 	 	
	
	//Genera la Id de la Alerta que será registrada en la tabla de alertas
	function obtenerIdAlerta(){		
		//Definir las tres letras en la Id de la Alerta
		$id_cadena = "ALR";
		
		//Obtener el mes y el año actuales
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
				
		//Obtener el mes actual y el año actual para ser agregados en la consulta y asi obtener las entradas del mes y año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de alertas registradas en la BD
		$stm_sql = "SELECT MAX(id_alerta) AS clave FROM alertas WHERE id_alerta LIKE 'ALR$mes$anio%'";
		//Ejecutar Alerta		
		$rs = mysql_query($stm_sql);		
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
	 * Esta función se ecargara de revisar el estado de las requisiciones emitidas por los diferentes Departamentos incluidos en el SISAD
	 */  
	function monitorearRequisiciones(){
		//Borrar la tabla de alertas para registrar las requisiciones con estado de ENVIADAS y quitar aquellas que ya fueron atendidas
		$conn = conecta_sabinas("bd_compras");
		mysql_query("DELETE FROM alertas WHERE estado = 1");
		mysql_close($conn);
		
		
		//Arreglo que contendra los datos para registrar las Alrtas sobre las requisiciones con estado "ENVIADA"
		$alertasReq = array();
		
		//Arreglo que contendra los Departamentos como clave y el nombre de la BD correspondiente a cada uno como valor, Falta agregar los Deptos que faltan por desarrollar
		$deptos = array("ALM"=>"bd_almacen", "MAN"=>"bd_mantenimiento", "MAC"=>"bd_mantenimiento", "MAM"=>"bd_mantenimiento", "REC"=>"bd_recursos", "TOP"=>"bd_topografia",
						"LAB"=>"bd_laboratorio", "PRO"=>"bd_produccion", "GER"=>"bd_gerencia", "DES"=>"bd_desarrollo", "SEG"=>"bd_seguridad", "ASE"=>"bd_aseguramiento",
						"PAI"=>"bd_paileria","MAE"=>"bd_mantenimientoE","USO"=>"bd_clinica");
		
		//Revisar las Requisiciones de cada Departamento
		foreach($deptos as $depto => $nomBaseDatos){		
				
			//Revisar las Requisiciones de cada Depto
			if($conn = conecta_sabinas($nomBaseDatos)){
				//Crear la sentencia para consultar el estado de las requisiciones
				$stm_sql_req = "SELECT id_requisicion, estado FROM requisiciones";
				//Ejecutar la sentencia creada para las Requisiciones
				$rs_req = mysql_query($stm_sql_req);
				//Verificar si hay datos para procesar
				if($datos_req=mysql_fetch_array($rs_req)){
					do{	
						//Si la requisicion tiene estado de enviada, registrala en el arreglo para ser guardadas en la BD de Compras posteriormente
						if($datos_req['estado']=="ENVIADA"){
							//Agregar el primer elemento al arreglo 'alertasReq' cuando éste esté vacio
							if(count($alertasReq)==0){
								$alertasReq[] = array("idRequisicion"=>$datos_req['id_requisicion'],"depto"=>$depto);
							}
							else{
								//Verificar que la Requisición que será agregada no se encuentra ya en el arreglo
								$reqExistente = 0;
								foreach($alertasReq as $ind => $regRequisicion){
									if($regRequisicion['idRequisicion']==$datos_req['id_requisicion']){
										$reqExistente = 1;
										break;//Romper el ciclo, ya que no tiene caso seguir buscando
									}										
								}//Cierre foreach($alertasReq as $ind => $value)
								
								//Si no existe la Requisición, procedemos a agregarla
								if($reqExistente==0){
									$alertasReq[] = array("idRequisicion"=>$datos_req['id_requisicion'],"depto"=>$depto);	
								}
							}
						}//Cierre if($datos_req['estado']=="ENVIADA")
					}while($datos_req=mysql_fetch_array($rs_req));
				}//Cierre if($datos_req=mysql_fetch_array($rs_req))
				
				//Cerrar la conexion por cada Depto
				mysql_close($conn);
			}//Cierre if ($conn = conecta($nomBaseDatos))
		}//Cierre foreach($deptos as $depto => $nomBaseDatos)
		
		
		//Reconectar con la BD de Compras para registrar las alertas
		$conn = conecta_sabinas("bd_compras");
		$fechaActual = date("Y-m-d");
		//Registrar las Alertas de las Requisiciones encontradas
		foreach($alertasReq as $ind => $datosAlerta){
			$idAlerta = obtenerIdAlerta();
			$sql_stm = "INSERT INTO alertas (id_alerta,requisiciones_id_requisicion,estado,fecha_generacion,depto) 
						VALUES('$idAlerta','$datosAlerta[idRequisicion]',1,'$fechaActual','$datosAlerta[depto]')";
			//Ejecutar la Sentencia para registrar la Alerta
			mysql_query($sql_stm);

		}
		
		//Cerrar la conexion por cada Depto
		mysql_close($conn);

	}//Fin de la funcion monitorearRequisiciones()
	
	
	//Esta funcion muestra las alertas registradas en la BD
	function desplegarAlertas2(){
						
		//Llamar a la funcion que se encargara de monitorear el estado de las Requisiciones de los diferentes Deptos.
		monitorearRequisiciones();
		
		//Conectarse con la BD de Compras y mantener la conexion que será utilizada en la funcion de  mostrarAlertas($id_requisicion)
		$conn = conecta_sabinas("bd_compras");
															
		//Crear la sentencia para obtener las alertas registradas en la BD que aun no han sido atendidas y que corresponden a Requisiciones
		$stm_sql = "SELECT requisiciones_id_requisicion FROM alertas WHERE estado = 1";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Sentencia para contar el numero de alertas
		$num_alertas = mysql_num_rows($rs);
		
		
		//Comprobar el número de Alertas
		if($num_alertas>1){
			
			//Mostrar solo un mensaje de varios materiales alcanzando su punto de reorden
			notificarAlerta($num_alertas);
						
		}
		else{//Ejecutar este codigo cuando se tenga una sola alerta
			
			//Verificar que existan datos en el ResultSet
			if($datos=mysql_fetch_array($rs)){
				mostrarAlertas($datos['requisiciones_id_requisicion']);												
			}	
					
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);		
						
	}//Cierre de la funcion desplegarAlertas();
	
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
				AVISO DE REQUISICI&Oacute;N
			</div>

			<div class="menu_form_body">
				<form name="frm_mostrarAlerta" action="frm_consultarAlertas.php" method="post">
				<table>
					<tr>
						<td colspan="2" align="center">							
							<p>Un total de <strong><?php echo $num_alertas; ?></strong> Requisiciones no Han Sido Atendidas</p>						
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center" bgcolor="#CCFF00"><u>Se Recomienda Revisar la Secci&oacute;n de Requisiciones</u>
						</td>						
					</tr>
						<tr>
							<td colspan="2" align="center"><strong><br>&iquest;Ver Requisiciones?</strong></td>
						</tr>							
						<tr>
							<td align="center" colspan="2">
								<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Mostrar Requisiciones Sin Atender" onMouseOver="window.status='';return true" />								
							</td>
						</tr>
					</table>
					</form>
			</div>
		</div>
		<!-- ********************************************************* Popup Window **************************************************** -->
		</body><?php
	}//Cierre de la Funcion notificarAlerta($num_alertas)
	
	
	//Esta función desplega la ventana de alerta de a una por vez, segun la id que recibe como parametro
	function mostrarAlertas($idRequisicion){
		
		//Arreglo que contendra los Departamentos como clave y el nombre de la BD correspondiente a cada uno como valor, Falta agregar los Deptos que faltan por desarrollar
		$deptos = array("ALM"=>"bd_almacen", "MAN"=>"bd_mantenimiento", "MAC"=>"bd_mantenimiento", "MAM"=>"bd_mantenimiento", "REC"=>"bd_recursos", "TOP"=>"bd_topografia",
						"LAB"=>"bd_laboratorio", "PRO"=>"bd_produccion", "GER"=>"bd_gerencia", "DES"=>"bd_desarrollo", "SEG"=>"bd_seguridad", "ASE"=>"bd_aseguramiento",
						"PAI"=>"bd_paileria","MAE"=>"bd_mantenimientoE","USO"=>"bd_clinica");			
		
		//Identificar el DEPTO al que pertence la Requisición para obtener los datos que serán desplegados al usuario
		$depto = substr($idRequisicion,0,3);
		$nomBaseDatos = $deptos[$depto];
		
		//Reconectar a la BD de la cual serán extraidos los datos de la Requisición
		$conn = conecta_sabinas($nomBaseDatos);		
						
		//Crear la sentencia para obtener los datos de la Requisicion que genero una alerta
		$stm_sql = "SELECT area_solicitante, fecha_req, solicitante_req, estado FROM requisiciones WHERE id_requisicion = '$idRequisicion'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){ ?>
			
			<head>				
				<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
				<link rel="stylesheet" type="text/css" href="includes/sample.css" />
				<script type="text/javascript" src="includes/popup-window.js"></script>
			</head>
			<body>
						
			<script type="text/javascript" language="javascript">				
				setTimeout("popup_show('popup', 'popup_drag', 'popup_exit', 'screen-bottom-right', 0, 0);",1000);				
			</script>
			
			<!-- ********************************************************* Popup Window **************************************************** -->
			<div class="sample_popup" id="popup" style="display: none;">
				<div align="center" class="menu_form_header" id="popup_drag">
					<img class="menu_form_exit" id="popup_exit" src="includes/aviso-form-exit.png" alt="" title="Posponer" />					
					AVISO DE REQUISICI&Oacute;N						
				</div>
	
				<div class="menu_form_body">								
				<form name="frm_mostrarAlerta" action="frm_detallesDelPedido.php?depto=<?php echo $nomBaseDatos; ?>" method="post">				
				<table>
					<tr>
						<td colspan="2" align="center">
							Se ha Detectado una Nueva Requisici&oacute;n
						</td>						
					</tr>
					<tr>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<tr>
						<td width="155" align="center" colspan="2">No. Requisici&oacute;n: <strong><?php echo $idRequisicion; ?></strong></td>
					</tr>
					<tr>
						<td align="center" colspan="2">&Aacute;rea: <strong><?php echo $datos['area_solicitante'];?></strong></td>
					</tr>
					<tr>
						<td align="center" colspan="2">Fecha de la Requisici&oacute;n <strong><?php echo modFecha($datos['fecha_req'],1);?></strong></td>
					</tr>
					<tr>
						<td align="center" colspan="2">Solicit&oacute;: <strong><?php echo $datos['solicitante_req'];?></strong></td>
					</tr>
					<tr>
						<td colspan="2" align="center">&nbsp;</td>
					</tr>
					<!-- <tr>
						<td colspan="2" align="center">&iquest;Generar Pedido?</td>
					</tr>							
					<tr>
						<td align="center" colspan="2">
							<?php //Estas variales ayudaran a mostrar el formulario donde solo se capturan los precios de los articulos de la Requisicion ?>
							<input type="hidden" name="hdn_numero" id="hdn_numero" value="<?php echo $idRequisicion; ?>" />
							<input type="hidden" name="hdn_bd" id="hdn_bd" value="<?php echo $nomBaseDatos; ?>" />
							<input type="hidden" name="hdn_estado" id="hdn_estado" value="<?php echo $datos['estado']; ?>" />
							<input name="btn_aceptar" type="submit" value="Aceptar" class="botones" title="Generar Pedido Ahora!" onMouseOver="window.status='';return true" />						
						</td>
					</tr> -->
				</table>
				</form>
				</div>
			</div>
			<!-- ********************************************************* Popup Window **************************************************** -->						
			<?php					
		} ?>
		</body>
		<?php	
		
				
	}//Cierre de la funcion mostrarAlertas($idRequisicion)
?>