<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 25/Octubre/2011
	  * Descripción: Este archivo contiene las funciones para registrar la információn en las Bitácoras de Barrenación(Jumbo y Maquina de Pierna), Voladura y Rezagado
	  **/ 	  	  			
	
	
	//Esta función guarda los datos de la Barrenación con Jumbo en la BD de Desarrollo
	function guardarBitBarrenacion(){
		
		$id_empleado_op = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_jumbero']);
		$id_empleado_ay = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_ayudante']);
		if(isset($_POST['ckb_ayudante']))
			$id_empleado_ay2 = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_ayudante2']);
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST para la tabla de 'barrenacion_jumbo'
		$idBit = $_POST['hdn_idBitacora'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);
		$turno = $_POST['cmb_turno'];		
		//$barrDados = $_POST['txt_barrDados'];
		//$disparados = $_POST['txt_disparos'];
		//$longitud = $_POST['txt_longitud'];
		//$reanclaje = $_POST['txt_reanclaje'];
		//$brocaNueva = $_POST['txt_brocasNuevas'];
		//$brocaAfilada = $_POST['txt_brocasAfiladas'];
		//$coples = $_POST['txt_coples'];
		//$zancos = $_POST['txt_zancos'];
		//$anclas = $_POST['txt_anclas'];
		//$observaciones = strtoupper($_POST['txa_observaciones']);
				
		//Recuperar los datos del POST para la tabla de 'barrenos'				
		//$desborde = $_POST['txt_barrDesborde'];
		//$encapille = $_POST['txt_barrEncapille'];
		//$despate = $_POST['txt_barrDespate'];
		$desborde = "";
		$encapille = "";
		$despate = "";
		$area = "JUMBO";
		
		//Recuperar los datos del POST para la tabla de 'registro_brazos'				
		$noBrazo1 = "1";
		$HIB1 = str_replace(",","",$_POST['txt_HIB1']);
		$HFB1 = str_replace(",","",$_POST['txt_HFB1']);
		$HTB1 = str_replace(",","",$_POST['txt_HTB1']);
		$noBrazo2 = ""; $HIB2 = ""; $HFB2 = ""; $HTB2 = "";
		if(isset($_POST['ckb_brazo2'])){
			$noBrazo2 = "2";
			$HIB2 = str_replace(",","",$_POST['txt_HIB2']);
			$HFB2 = str_replace(",","",$_POST['txt_HFB2']);
			$HTB2 = str_replace(",","",$_POST['txt_HTB2']);
		}
		
		//Recuperar los datos del POST para la tabla de 'equipo'				
		$idEquipo = $_POST['cmb_equipo'];
		$horoIni = str_replace(",","",$_POST['txt_HIEquipo']);
		$horoFin = str_replace(",","",$_POST['txt_HFEquipo']);
		$horasTotales = str_replace(",","",$_POST['txt_HTEquipo']);
		
		//Recuperar los datos del POST para la tabla de 'personal'				
		$nomJumbero = strtoupper($_POST['txt_jumbero']);
		$nomAyudante = strtoupper($_POST['txt_ayudante']);
													
		$bd = array(); $disp = array();
		$lo = array(); $bdes = array();
		$benc = array(); $bdtp = array();
		$reanc = array(); $cop = array();
		$zan = array(); $anc = array();
		$esca = array(); $tb = array();
		$obser = array();
		
		for($i=0; $i<2; $i++){
			if(isset($_POST["ckb_activarBarr".$i])){
				$bd[] = $_POST['txt_barrDados'.$i]; $disp[] = $_POST['txt_disparos'.$i];
				$lo[] = $_POST['txt_longitud'.$i]; $bdes[] = $_POST['txt_barrDesborde'.$i];
				$benc[] = $_POST['txt_barrEncapille'.$i]; $bdtp[] = $_POST['txt_barrDespate'.$i];
				$reanc[] = $_POST['txt_reanclaje'.$i]; $cop[] = $_POST['txt_coples'.$i];
				$zan[] = $_POST['txt_zancos'.$i]; $anc[] = $_POST['txt_anclas'.$i];
				$esca[] = $_POST['txt_escareado'.$i]; $tb[] = $_POST['txt_topesBarrenados'.$i];
				$obser[] = strtoupper($_POST['txa_observaciones'.$i]);
				$desborde += $bdes[$i];
				$encapille += $benc[$i];
				$despate += $bdtp[$i];
				//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
				$sql_stm = "INSERT INTO barrenacion_jumbo(bitacora_avance_id_bitacora,fecha,turno,barrenos_dados,barrenos_disp,barrenos_long,reanclaje,broca_nva,
							broca_afil,coples,zancos,anclas,escareado,topes_barrenados,observaciones) 
							VALUES('$idBit','$fecha','$turno',$bd[$i],$disp[$i],$lo[$i],$reanc[$i],0,0,$cop[$i],$zan[$i],$anc[$i],$esca[$i],$tb[$i],'$obser[$i]')";
				//Ejecutar la Sentencia SQL
				$rs = mysql_query($sql_stm);
			}else{
				$bd[] = ""; $disp[] = ""; 
				$lo[] = ""; $bdes[] = ""; 
				$benc[] = ""; $bdtp[] = ""; 
				$reanc[] = ""; $cop[] = ""; 
				$zan[] = ""; $anc[] = ""; 
				$esca[] = ""; $tb[] = ""; 
				$obser[] = "";
			}
		}
		
		//Crear la Sentencia SQL para alamcenar lo datos en la Tabla 'barrenacion_jumbo'
		/*$sql_stm = "INSERT INTO barrenacion_jumbo(bitacora_avance_id_bitacora,fecha,turno,barrenos_dados,barrenos_disp,barrenos_long,reanclaje,broca_nva,
					broca_afil,coples,zancos,anclas,observaciones) 
					VALUES('$idBit','$fecha','$turno',$barrDados,$disparados,$longitud,$reanclaje,$brocaNueva,$brocaAfilada,$coples,$zancos,$anclas,'$observaciones')";*/
		//Ejecutar la Sentencia SQL para la Tabla 'barrenacion_jumbo'
		//$rs = mysql_query($sql_stm);
		
		if($rs){//BARRENACION_JUMBO
			//Crear la Sentencia SQL para alamcenar lo datos en la Tabla 'barrenos'
			$sql_stm = "INSERT INTO barrenos(bitacora_avance_id_bitacora,desborde,encapille,despate,area)
						VALUES('$idBit',$desborde,$encapille,$despate,'$area')";
			//Ejecutar la Sentencia SQL para la Tabla 'barrenos'
			$rs = mysql_query($sql_stm);
			
			if($rs){//BARRENOS
				//Crear la Sentencia SQL para alamcenar lo datos en la Tabla 'registro_brazos'
				$sql_stm = "INSERT INTO registro_brazos(bitacora_avance_id_bitacora,num_brazo,horo_ini,horo_fin,horas_totales)
							VALUES('$idBit','$noBrazo1',$HIB1,$HFB1,$HTB1)";
				//Ejecutar la Sentencia SQL para la Tabla 'registro_brazos'
				$rs = mysql_query($sql_stm);
				if($rs){//REGISTRO BRAZOS
					
					if(isset($_POST['ckb_brazo2'])){//REGISTRO 2° BRAZO
						mysql_query("INSERT INTO registro_brazos(bitacora_avance_id_bitacora,num_brazo,horo_ini,horo_fin,horas_totales)
									VALUES('$idBit','$noBrazo2',$HIB2,$HFB2,$HTB2)");
					}
										
					//Gurdar los datos del Personal (Jumbero y Ayudante)
					mysql_query("INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
								VALUES('N/A','$idBit','OPERADOR','$id_empleado_op','$area')");
					mysql_query("INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
								VALUES('N/A','$idBit','AYUDANTE','$id_empleado_ay','$area')");
					if(isset($_POST['ckb_ayudante']))
						mysql_query("INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
									VALUES('N/A','$idBit','AYUDANTE','$id_empleado_ay2','$area')");
			
					//Gurdar los datos del Equipo que serán guardados en la tabla de Equipo de la BD de Desarrollo
					mysql_query("INSERT INTO equipo (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,area) 
								VALUES('N/A','$idBit','$idEquipo',$horoIni,$horoFin,$horasTotales,'$area')");
								
					//Guardar el Movimiento realizado en la tabla de Movimientos
					registrarOperacion("bd_desarrollo","$idBit","RegistroBitBarrJU",$_SESSION['usr_reg']);
								
					//Guardar en la SESSION la variable que indica que la bitácora de rezagado ha sido registrada
					$_SESSION['bitsAgregadas']['bitBarrenacion'] = 1;
					
					//Redireccionar a la Pagina de Registrar Avance (frm_regAvance.php)
					echo "<meta http-equiv='refresh' content='0;url=frm_regAvance.php'>";
				
				}//Cierre if($rs){//REGISTRO BRAZOS
				else{
					$error = mysql_error();
					echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";			
				}
			}//Cierre if($rs){//BARRENOS
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";			
			}												
		}//Cierre if($rs){//BARRENACION_JUMBO
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
		}
								
		//Cerrar la conexicion con la BD
		//La conexion se cierra en la funcion registrarOperacion("bd_desarrollo","$idBit","RegistroBitBarr",$_SESSION['usr_reg']);
	}//Cierre de la funcion guardarBitBarrenacion()
	
		
	//Esta función guarda los datos de la Barrenación con Maquina de Pierna en la BD de Desarrollo
	function guardarBitBarrenacionMP(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST para la tabla de 'barrenacion_maq_pierna'
		$idBit = $_POST['hdn_idBitacora'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);
		$turno = $_POST['cmb_turno'];		
		$barrDados = $_POST['txt_barrDados'];
		$disparados = $_POST['txt_disparos'];
		$longitud = $_POST['txt_longitud'];		
		$brocaNueva = $_POST['txt_brocasNuevas'];
		$brocaAfilada = $_POST['txt_brocasAfiladas'];
		$barra6 = $_POST['txt_barras6'];
		$barra8 = $_POST['txt_barras8'];
		$anclas = $_POST['txt_anclas'];
		$observaciones = strtoupper($_POST['txa_observaciones']);
		$area = "MP";
				
		//Recuperar los datos del POST para la tabla de 'equipo'				
		$idEquipo = $_POST['cmb_equipo'];
		$horoIni = str_replace(",","",$_POST['txt_HIEquipo']);
		$horoFin = str_replace(",","",$_POST['txt_HFEquipo']);
		$horasTotales = str_replace(",","",$_POST['txt_HTEquipo']);
		
		//Recuperar los datos del POST para la tabla de 'personal'				
		$nomPerforista = strtoupper($_POST['txt_perforista']);
		$nomAyudante = strtoupper($_POST['txt_ayudante']);
													
		
		
		//Crear la Sentencia SQL para alamcenar lo datos en la Tabla 'barrenacion_jumbo'
		$sql_stm = "INSERT INTO barrenacion_maq_pierna(bitacora_avance_id_bitacora,fecha,turno,barrenos_dados,barrenos_disparos,barrenos_longitud,broca_nva,
					broca_afil,barra_6,barra_8,anclas,observaciones) 
					VALUES('$idBit','$fecha','$turno',$barrDados,$disparados,$longitud,$brocaNueva,$brocaAfilada,$barra6,$barra8,$anclas,'$observaciones')";
					
		//Ejecutar la Sentencia SQL para la Tabla 'barrenacion_jumbo'
		$rs = mysql_query($sql_stm);
		
		if($rs){
													
			//Gurdar los datos del Personal (Jumbero y Ayudante)
			mysql_query("INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
						VALUES('N/A','$idBit','OPERADOR','$nomPerforista','$area')");
			mysql_query("INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
						VALUES('N/A','$idBit','AYUDANTE','$nomAyudante','$area')");
			
			//Gurdar los datos del Equipo que serán guardados en la tabla de Equipo de la BD de Desarrollo
			mysql_query("INSERT INTO equipo (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,area) 
						VALUES('N/A','$idBit','$idEquipo',$horoIni,$horoFin,$horasTotales,'$area')");
						
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idBit","RegistroBitBarrMP",$_SESSION['usr_reg']);
								
			//Guardar en la SESSION la variable que indica que la bitácora de rezagado ha sido registrada
			$_SESSION['bitsAgregadas']['bitBarrenacionMP'] = 1;
					
			//Redireccionar a la Pagina de Registrar Avance (frm_regAvance.php)
			echo "<meta http-equiv='refresh' content='0;url=frm_regAvance.php'>";
																		
		}//Cierre if($rs){
		else{
			//Cerrar la conexicion con la BD
			mysql_close();
			//Obtener el error generado
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";						
		}
										
	}//Cierre de la función guardarBitBarrenacionMP()
	
	
	//Esta función guarda los datos de las Voladuras en la BD de Desarrollo
	function guardarBitVoladura(){	
		
		$id_empleado_op = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_volador']);
		$id_empleado_ay = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_ayudante']);
		if(isset($_POST['ckb_ayudante']))
			$id_empleado_ay2 = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_ayudante2']);
		
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST que serán guardados en la tabla de 'voladuras'
		$idBit = $_POST['hdn_idBitacora'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);
		$turno = $_POST['cmb_turno'];
		$longBarrCargado = $_POST['txt_longBarreno'];
		$factorCarga = $_POST['txt_factorCarga'];
		//$topescar = $_POST['txt_TopesCarg'];
		//$obs = strtoupper($_POST['txa_observaciones']);
		$area = "VOLADURAS";
		
		//Recuperar los datos del POST que serán guardados en la tabla de 'personal'				
		$opVoladura = strtoupper($_POST['txt_volador']);
		$ayudante = strtoupper($_POST['txt_ayudante']);
		
		//Recuperar los datos del POST que serán guardados en la tabla de 'equipo'				
		$equipo = $_POST['cmb_equipo'];
		$horoIni = str_replace(",","",$_POST['txt_HIEquipo']);
		$horoFin = str_replace(",","",$_POST['txt_HFEquipo']);
		$horasTotales = str_replace(",","",$_POST['txt_HTEquipo']);		
		
		$lbc = array();
		$fc = array();
		$tc = array();
		$obser = array();
		for($i=0; $i<2; $i++){
			if(isset($_POST["ckb_activarVol".$i])){
				$lbc[] = $_POST['txt_longBarreno'.$i];
				$fc[] = $_POST['txt_factorCarga'.$i];
				$disp[] = $_POST['txt_disparos'.$i];
				$dispNicho[] = $_POST['txt_disparosNicho'.$i];
				$tc[] = $_POST['txt_TopesCarg'.$i];
				$obser[] = strtoupper($_POST['txa_observaciones'.$i]);
				$regis = $i+1;
				//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
				$sql_stm = "INSERT INTO voladuras(bitacora_avance_id_bitacora,fecha,turno,long_barreno_carg,factor_carga,disparos,disparos_nicho,topes_cargados,observaciones,registro) 
							VALUES('$idBit','$fecha','$turno',$lbc[$i],$fc[$i],$disp[$i],$dispNicho[$i],$tc[$i],'$obser[$i]',$regis)";
				//Ejecutar la Sentencia SQL
				$rs = mysql_query($sql_stm);
			}else{
				$lbc[] = "";
				$fc[] = "";
				$disp[] = "";
				$dispNicho[] = "";
				$tc[] = "";
				$obser[] = "";
			}
		}
		
		//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
		/*$sql_stm = "INSERT INTO voladuras(bitacora_avance_id_bitacora,fecha,turno,long_barreno_carg,factor_carga,topes_cargados,observaciones) 
					VALUES('$idBit','$fecha','$turno',$longBarrCargado,$factorCarga,$topescar,'$obs')";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_stm);*/
		
		if($rs){
			//Gurdar los datos del Personal que serán guardados en la tabla de Personal de la BD de Desarrollo
			mysql_query("INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
						VALUES('N/A','$idBit','OPERADOR','$id_empleado_op','$area')");
			mysql_query("INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
						VALUES('N/A','$idBit','AYUDANTE','$id_empleado_ay','$area')");
			if(isset($_POST['ckb_ayudante']))
				mysql_query("INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
							VALUES('N/A','$idBit','AYUDANTE','$id_empleado_ay2','$area')");
			
			//Verificar si el usuario selecciono un equipo
			if($equipo!=""){
				//Gurdar los datos del Equipo que serán guardados en la tabla de Equipo de la BD de Desarrollo
				mysql_query("INSERT INTO equipo (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,area) 
							VALUES('N/A','$idBit','$equipo',$horoIni,$horoFin,$horasTotales,'$area')");
			}
			
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idBit","RegistroBitVoladura",$_SESSION['usr_reg']);
						
			//Guardar en la SESSION la variable que indica que la bitácora de rezagado ha sido registrada
			$_SESSION['bitsAgregadas']['bitVoladura'] = 1;
			
			//Redireccionar a la Pagina de Registrar Avance (frm_regAvance.php)
			echo "<meta http-equiv='refresh' content='0;url=frm_regAvance.php'>";													
		}
		else{
			//Obtener el error generado
			$error = mysql_error();
			//Cerrar la conexicion con la BD
			mysql_close();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
		}
										
	}//Cierre de la función guardarBitVoladura()
	
	
	//Esta funcion guardará los datos de la bitácora de rezagado en la BD de Desarrollo
	function guardarBitRezagado(){
		
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST
		$idBit = $_POST['hdn_idBitacora'];
		$operador = strtoupper($_POST['cmb_operador']);
		$puesto = $_POST['hdn_puesto'];
		$turno = $_POST['cmd_turno'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);
		$equipo = $_POST['cmb_equipo'];
		
		//Quitar las comas que puedan existir en los campos de Horometro Inicial, Final y Total
		$horoIni = str_replace(",","",$_POST['txt_horoIni']);
		$horoFin = str_replace(",","",$_POST['txt_horoFin']);
		$horasTotales = str_replace(",","",$_POST['txt_horasTotales']);
		
		/*$origenTep="";
		$destinoTep="";
		$cuchTep=0;
		if(isset($_POST["ckb_activarTep"])){
			$origenTep = $_POST['cmb_origenTepetate'];
			$destinoTep = $_POST['cmb_destinoTepetate'];
			$cuchTep = $_POST['txt_cucharonesTep'];
		}*/
		
		/*$origenMin = "";
		$destinoMin = "";
		$cuchMin = 0;*/
		$origen = array();
		$destino = array();
		$cuch = array();
		$obs = array();
		for($i=0; $i<$_POST["txt_opRe"]; $i++){
			$trasp = 0;
			$limpio = 0;
			if(isset($_POST["ckb_activarMin".$i])){
				//$origen[] = $_POST['cmb_origenMineral'.$i];
				//$destino[] = $_POST['cmb_destinoMineral'.$i];
				$origen[] = "";
				$destino[] = "";
				$cuch[] = $_POST['txt_cucharonesMin'.$i];
				if(isset($_POST["ckb_activarTrasp".$i])){
					$trasp = 1;
				}
				if(isset($_POST["ckb_activarTLimp".$i])){
					$limpio = 1;
				}
		
				$obs[] = strtoupper($_POST['txa_observaciones'.$i]);
						
				//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
				$sql_stm = "INSERT INTO rezagado(bitacora_avance_id_bitacora,fecha,turno,tep_origen,tep_cuch,tep_destino,min_cuch,min_origen,min_destino,cuch,origen,destino,traspaleo,tope_limpio,observaciones) 
						VALUES('$idBit','$fecha','$turno','',0,'',0,'','','$cuch[$i]','$origen[$i]','$destino[$i]','$trasp','$limpio','$obs[$i]')";										
				//Ejecutar la Sentencia SQL
				$rs = mysql_query($sql_stm);
			}else{
				$origen[] = "";
				$destino[] = "";
				$cuch[] = "";
				$obs[] = "";
			}
		}
		if($rs){
			//Gurdar los datos del Personal que serán guardados en la tabla de Personal de la BD de Desarrollo
			mysql_query("INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area) 
						VALUES('N/A','$idBit','$puesto','$operador','SCOOP')");
		
			//Gurdar los datos del Equipo que serán guardados en la tabla de Equipo de la BD de Desarrollo
			mysql_query("INSERT INTO equipo (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,area) 
						VALUES('N/A','$idBit','$equipo',$horoIni,$horoFin,$horasTotales,'SCOOP')");
					
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idBit","RegistroBitRezagado",$_SESSION['usr_reg']);
					
			//Guardar en la SESSION la variable que indica que la bitácora de rezagado ha sido registrada
			$_SESSION['bitsAgregadas']['bitRezagado'] = 1;
		
			//Redireccionar a la Pagina de Registrar Avance (frm_regAvance.php)
			echo "<meta http-equiv='refresh' content='0;url=frm_regAvance.php'>";													
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
			//Cerrar la conexicion con la BD
			mysql_close();
		}
												
	}//Cierre de la funcion guardarBitRezagado()
	
	
	/*Esta función guardará en un arreglo las obras registradas en el Catálogo de Obras y en la Bitácora de Rezagado*/
	function obtenerObrasRezagado($campoBitRezagado){
		//Conectarse con la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Arreglo que guardará las obras encontradas
		$arrObras = array();
		
		//OBTENER LAS OBRAS REGISTRADAS EN EL CATALOGO DE OBRAS Y EN LA BITACORA DE REZAGADO
		$sql_obrasRezagado = "SELECT DISTINCT $campoBitRezagado FROM rezagado WHERE $campoBitRezagado!=''";//Obras Bitacóra de Rezagado
		$sql_obrasCatalogo = "SELECT obra FROM catalogo_ubicaciones";//Obras Catálogo de Obras
		
		//Ejecutar las Sentencias
		$rs_obrasRezagado = mysql_query($sql_obrasRezagado);
		$rs_obrasCatalogo = mysql_query($sql_obrasCatalogo);
		
		//Obtener las Obras de la Bitácora de Rezagado
		if($datos_obrasRezagado=mysql_fetch_array($rs_obrasRezagado)){
			do{
				$arrObras[] = $datos_obrasRezagado[$campoBitRezagado];
			}while($datos_obrasRezagado=mysql_fetch_array($rs_obrasRezagado));
		}//Cierre if($datos_obrasRezagado=mysql_fetch_array($rs_obrasRezagado))
		
		
		//Obtener las Obras del Catálogo de Obras
		if($datos_obrasCatalogo=mysql_fetch_array($rs_obrasCatalogo)){
			do{
				$arrObras[] = $datos_obrasCatalogo['obra'];
			}while($datos_obrasCatalogo=mysql_fetch_array($rs_obrasCatalogo));
		}//Cierre if($datos_obrasRezagado=mysql_fetch_array($rs_obrasRezagado))
		
		
		//Eliminar las obras repetidas
		$obras = array_unique($arrObras);		
		
		//Ordenar el arreglo recien creado
		sort($obras);
		
		//Cerrar la conexión con la BD
		mysql_close($conn);
		
		//Regresar el Arreglo de obras
		return $obras;
		
	}//Cierre de la función cargarComboObrasRezagado($campoBitRezagado)
	
	
	/**************************************************************************************************************************************/
	/*******************************************************MODIFICAR BITACORAS************************************************************/
	/**************************************************************************************************************************************/
	
	//Esta función modificara la Bitácora de Barrenación con Jumbo asociada a la Bitácora de Avance seleccionada para su edición
	function modificarBitBarrenacion(){
		
		$id_empleado_op = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_jumbero']);
		$id_empleado_ay = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_ayudante']);
		
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST para la tabla de 'barrenacion_jumbo'
		$idBit = $_POST['hdn_idBitacora'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);
		$turno = $_POST['cmb_turno'];		
		/*$barrDados = $_POST['txt_barrDados'];
		$disparados = $_POST['txt_disparos'];
		$longitud = $_POST['txt_longitud'];
		$reanclaje = $_POST['txt_reanclaje'];
		$brocaNueva = $_POST['txt_brocasNuevas'];
		$brocaAfilada = $_POST['txt_brocasAfiladas'];
		$coples = $_POST['txt_coples'];
		$zancos = $_POST['txt_zancos'];
		$anclas = $_POST['txt_anclas'];
		$observaciones = strtoupper($_POST['txa_observaciones']);
				
		//Recuperar los datos del POST para la tabla de 'barrenos'*/				
		$desborde = "";
		$encapille = "";
		$despate = "";
		$area = "JUMBO";
		
		//Recuperar los datos del POST para la tabla de 'registro_brazos'				
		$noBrazo1 = "1";
		$HIB1 = str_replace(",","",$_POST['txt_HIB1']);
		$HFB1 = str_replace(",","",$_POST['txt_HFB1']);
		$HTB1 = str_replace(",","",$_POST['txt_HTB1']);
		$noBrazo2 = ""; $HIB2 = ""; $HFB2 = ""; $HTB2 = "";
		if(isset($_POST['ckb_brazo2'])){
			$noBrazo2 = "2";
			$HIB2 = str_replace(",","",$_POST['txt_HIB2']);
			$HFB2 = str_replace(",","",$_POST['txt_HFB2']);
			$HTB2 = str_replace(",","",$_POST['txt_HTB2']);
		}
		
		//Recuperar los datos del POST para la tabla de 'equipo'				
		$idEquipo = $_POST['cmb_equipo'];
		$horoIni = str_replace(",","",$_POST['txt_HIEquipo']);
		$horoFin = str_replace(",","",$_POST['txt_HFEquipo']);
		$horasTotales = str_replace(",","",$_POST['txt_HTEquipo']);
		
		//Recuperar los datos del POST para la tabla de 'personal'
		$nomJumbero = strtoupper($_POST['txt_jumbero']);
		$nomAyudante = strtoupper($_POST['txt_ayudante']);
		
		$bd = array(); $disp = array();
		$lo = array(); $bdes = array();
		$benc = array(); $bdtp = array();
		$reanc = array(); $cop = array();
		$zan = array(); $anc = array();
		$esca = array(); $tb = array();
		$obser = array();
		
		$stm_sql = "SELECT * 
					FROM  `barrenacion_jumbo` 
					WHERE  `bitacora_avance_id_bitacora` LIKE  '$idBit'";
		
		$rs_bit = mysql_query($stm_sql);
		
		$sql_barrenacion = ""; $sql_barrenos = ""; $sql_brazo1 = ""; $sql_brazo2 = ""; $sql_perOperador = ""; $sql_perAyudante = ""; $sql_equipo = "";
		$descBitMovimientos = "";
		
		if($rs_bit){
			$i=0;
			while($datos_bit = mysql_fetch_array($rs_bit)){
				if(isset($_POST["ckb_activarBarr".$i])){
					$bd[] = $_POST['txt_barrDados'.$i]; $disp[] = $_POST['txt_disparos'.$i];
					$lo[] = $_POST['txt_longitud'.$i]; $reanc[] = $_POST['txt_reanclaje'.$i]; 
					$cop[] = $_POST['txt_coples'.$i]; $zan[] = $_POST['txt_zancos'.$i]; 
					$anc[] = $_POST['txt_anclas'.$i]; $esca[] = $_POST['txt_escareado'.$i]; 
					$tb[] = $_POST['txt_topesBarrenados'.$i]; $obser[] = strtoupper($_POST['txa_observaciones'.$i]);
					
					$sql_barrenacion = "UPDATE `barrenacion_jumbo` SET `fecha`='$fecha',`turno`='$turno',`barrenos_dados`='$bd[$i]',`barrenos_disp`='$disp[$i]',`barrenos_long`='$lo[$i]',
										`reanclaje`='$reanc[$i]',`coples`='$cop[$i]',`zancos`='$zan[$i]',`anclas`='$anc[$i]',`escareado`='$esca[$i]',`topes_barrenados`='$tb[$i]',`observaciones`='$obser[$i]'
										WHERE  `bitacora_avance_id_bitacora`='$datos_bit[bitacora_avance_id_bitacora]' AND  `fecha`='$datos_bit[fecha]' AND  `turno`='$datos_bit[turno]' AND 
										`barrenos_dados`='$datos_bit[barrenos_dados]' AND `barrenos_disp`='$datos_bit[barrenos_disp]' AND `barrenos_long`='$datos_bit[barrenos_long]' AND `reanclaje`='$datos_bit[reanclaje]' AND 
										`broca_nva`='$datos_bit[broca_nva]' AND `broca_afil`='$datos_bit[broca_afil]' AND `coples`='$datos_bit[coples]' AND `zancos`='$datos_bit[zancos]' AND 
										`anclas`='$datos_bit[anclas]' AND `escareado`='$datos_bit[escareado]' AND `topes_barrenados`='$datos_bit[topes_barrenados]' AND  `observaciones`='$datos_bit[observaciones]' LIMIT 1";
					
					$rs_barrenacion = mysql_query($sql_barrenacion);
				}else{
					$bd[] = ""; $disp[] = "";
					$lo[] = ""; $reanc[] = ""; 
					$cop[] = ""; $zan[] = ""; 
					$anc[] = ""; $esca[] = ""; 
					$tb[] = ""; $obser[] = "";
				}
				$bdes[] = $_POST['txt_barrDesborde'.$i]; $benc[] = $_POST['txt_barrEncapille'.$i]; 
				$bdtp[] = $_POST['txt_barrDespate'.$i];
				$desborde += $bdes[$i];
				$encapille += $benc[$i];
				$despate += $bdtp[$i];
				 $i++;
			}
			
			if($rs_barrenacion){
				$sql_barrenos = "UPDATE barrenos SET desborde = $desborde, encapille = $encapille, despate = $despate WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area'";
				$sql_brazo1 = "UPDATE registro_brazos SET horo_ini = $HIB1, horo_fin = $HFB1, horas_totales = $HTB1 WHERE bitacora_avance_id_bitacora = '$idBit' AND num_brazo = 1"; 
				//Sí esta definido el CheckBox 'Brazo 2', crear la Sentencia SQL para actualizar los datos del Brazo 2
				if(isset($_POST['ckb_brazo2'])){
					$sql_brazo2 = "UPDATE registro_brazos SET horo_ini = $HIB2, horo_fin = $HFB2, horas_totales = $HTB2 WHERE bitacora_avance_id_bitacora = '$idBit' AND num_brazo = 2";
				}								
				//Crear la Sentencia SQL para actualizar los datos del Personal
				$sql_perOperador = "UPDATE personal SET nombre='$id_empleado_op' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='OPERADOR'";
				$sql_perAyudante = "UPDATE personal SET nombre='$id_empleado_ay' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='AYUDANTE'";			
				//Crear la Sentencia SQL para actualizar los datos del Equipo
				$sql_equipo = "UPDATE equipo SET id_equipo='$idEquipo',horo_ini=$horoIni, horo_fin=$horoFin, horas_totales=$horasTotales 
								WHERE bitacora_avance_id_bitacora = '$idBit'  AND area = '$area'";			
				//Definir el Mensaje que será guardado en la Bitácora de Movimientos				
				$descBitMovimientos = "ModificarBitBarrJU";
			}
		}
		//Determinar que operaciones serán ejecutada (Actualizar o Registrar) en las tablas de Barrenacion Jumbo, Barrenos, Brazos, Personal y Equipo 
		/*$sql_barrenacion = ""; $sql_barrenos = ""; $sql_brazo1 = ""; $sql_brazo2 = ""; $sql_perOperador = ""; $sql_perAyudante = ""; $sql_equipo = "";
		$descBitMovimientos = "";
		if($_POST['hdn_actualizarBitacora']=="si"){//ACTUALIZAR DATOS																											
			//Crear la Sentencia SQL para actualizar los datos de Barrenacion en la BD
			$sql_barrenacion = "UPDATE barrenacion_jumbo SET fecha = '$fecha', turno = '$turno', barrenos_dados = $barrDados, barrenos_disp = $disparados, 
								barrenos_long = $longitud, reanclaje = $reanclaje, broca_nva = $brocaNueva,	broca_afil = $brocaAfilada, coples = $coples, 
								zancos = $zancos, anclas = $anclas, observaciones = '$observaciones' WHERE bitacora_avance_id_bitacora = '$idBit'";
			//Crear la Sentenicia SQL para actualizar los datos de los Barrenos
			$sql_barrenos = "UPDATE barrenos SET desborde = $desborde, encapille = $encapille, despate = $despate WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area'";
			//Crear la Senetencia SQL para actualizar los datos de los Brazos del Equipo
			$sql_brazo1 = "UPDATE registro_brazos SET horo_ini = $HIB1, horo_fin = $HFB1, horas_totales = $HTB1 WHERE bitacora_avance_id_bitacora = '$idBit' AND num_brazo = 1"; 
			//Sí esta definido el CheckBox 'Brazo 2', crear la Sentencia SQL para actualizar los datos del Brazo 2
			if(isset($_POST['ckb_brazo2'])){
				$sql_brazo2 = "UPDATE registro_brazos SET horo_ini = $HIB2, horo_fin = $HFB2, horas_totales = $HTB2 WHERE bitacora_avance_id_bitacora = '$idBit' AND num_brazo = 2";
			}								
			//Crear la Sentencia SQL para actualizar los datos del Personal
			$sql_perOperador = "UPDATE personal SET nombre='$nomJumbero' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='OPERADOR'";
			$sql_perAyudante = "UPDATE personal SET nombre='$nomAyudante' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='AYUDANTE'";			
			//Crear la Sentencia SQL para actualizar los datos del Equipo
			$sql_equipo = "UPDATE equipo SET id_equipo='$idEquipo',horo_ini=$horoIni, horo_fin=$horoFin, horas_totales=$horasTotales 
							WHERE bitacora_avance_id_bitacora = '$idBit'  AND area = '$area'";			
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos				
			$descBitMovimientos = "ModificarBitBarrJU";
		}//Cierre if($_POST['hdn_actualizarBitacora']=="si")
		else if($_POST['hdn_actualizarBitacora']=="no"){//REGISTRAR DATOS
			//Crear la Sentencia SQL para almacenar los datos de la Bitácora de Rezagado
			$sql_barrenacion = "INSERT INTO barrenacion_jumbo(bitacora_avance_id_bitacora,fecha,turno,barrenos_dados,barrenos_disp,barrenos_long,reanclaje,broca_nva,
								broca_afil,coples,zancos,anclas,observaciones) 
								VALUES('$idBit','$fecha','$turno',$barrDados,$disparados,$longitud,$reanclaje,$brocaNueva,$brocaAfilada,$coples,$zancos,$anclas,'$observaciones')";
			//Crear la Sentencia SQL para guardar los datos de los barrenos
			$sql_barrenos = "INSERT INTO barrenos(bitacora_avance_id_bitacora,desborde,encapille,despate,area) VALUES('$idBit',$desborde,$encapille,$despate,'$area')";
			//Crear la Sentencia SQL para guardar los datos del Brazo 1
			$sql_brazo1 = "INSERT INTO registro_brazos(bitacora_avance_id_bitacora,num_brazo,horo_ini,horo_fin,horas_totales) VALUES('$idBit','$noBrazo1',$HIB1,$HFB1,$HTB1)";
			//Sí esta definido el CheckBox 'Brazo 2', crear la Sentencia SQL para guardar los datos del Brazo 2
			if(isset($_POST['ckb_brazo2']))
				$sql_brazo2 = "INSERT INTO registro_brazos(bitacora_avance_id_bitacora,num_brazo,horo_ini,horo_fin,horas_totales) VALUES('$idBit','$noBrazo2',$HIB2,$HFB2,$HTB2)";
			//Crear la Sentencia SQL para guradar los datos del Personal
			$sql_perOperador = "INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
							 	VALUES('N/A','$idBit','OPERADOR','$nomJumbero','$area')";
			$sql_perAyudante = "INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
								VALUES('N/A','$idBit','AYUDANTE','$nomAyudante','$area')";
			//Crear la Sentencia SQL para guradar los datos del Equipo
			$sql_equipo = "INSERT INTO equipo (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,area) 
							VALUES('N/A','$idBit','$idEquipo',$horoIni,$horoFin,$horasTotales,'$area')";
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos
			$descBitMovimientos = "RegistroBitBarrJU";
		}//Cierre else if($_POST['hdn_actualizarBitacora']=="no")*/
		
		if($rs_barrenacion){//BARRENACION_JUMBO			
			//Ejecutar la Sentencia SQL para la Tabla 'barrenos'
			$rs = mysql_query($sql_barrenos);
			
			if($rs){//BARRENOS
				//Ejecutar la Sentencia SQL para la Tabla 'registro_brazos'
				$rs = mysql_query($sql_brazo1);
				if($rs){//REGISTRO BRAZOS					
					if(isset($_POST['ckb_brazo2'])){//REGISTRO 2° BRAZO
						mysql_query($sql_brazo2);
					}
										
					//Gurdar los datos del Personal (Jumbero y Ayudante)
					mysql_query($sql_perOperador);
					mysql_query($sql_perAyudante);
			
					//Gurdar los datos del Equipo que serán guardados en la tabla de Equipo de la BD de Desarrollo
					mysql_query($sql_equipo);
					
					
					//Obtener el ID del equipo registrado en la Bitácora de Fallas
					$rs_equipo = mysql_query("SELECT DISTINCT equipo FROM bitacora_fallas WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'BARRENACION'");
					if($datosEquipo=mysql_fetch_array($rs_equipo)){
						//Verificar si el equipo seleccionado en el Formulario de Barrenación con Jumbo es el Mismo que fue registrardo en el Formulario de Fallas
						if($idEquipo!=$datosEquipo['equipo']){
							//Actualizar la Clave del equipo registrada en la Bitácora de Fallas
							mysql_query("UPDATE bitacora_fallas SET equipo = '$idEquipo' WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'BARRENACION'");
						}
					}//Cierre if($datosEquipo=mysql_fetch_array($rs_equipo))
					
													
					//Guardar el Movimiento realizado en la tabla de Movimientos
					registrarOperacion("bd_desarrollo","$idBit",$descBitMovimientos,$_SESSION['usr_reg']);
								
					//Guardar en la SESSION la variable que indica que la bitácora de barrenación con jumbo ha sido modificada
					$_SESSION['bitsActualizadas']['barrenacion_jumbo'] = 1;
					
					//Redireccionar a la Pagina de Modificar Avance (frm_modAvance.php)
					echo "<meta http-equiv='refresh' content='0;url=frm_modAvance2.php'>";
				
				}//Cierre if($rs){//REGISTRO BRAZOS
				else{
					$error = mysql_error();
					echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";			
				}
			}//Cierre if($rs){//BARRENOS
			else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='5;url=error.php?err=$error'>";			
			}												
		}//Cierre if($rs){//BARRENACION_JUMBO
		else{
			$error = mysql_error();
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>$error   $_POST[txt_barrDados0]";
			break;
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
		}
								
		//Cerrar la conexicion con la BD
		//La conexion se cierra en la funcion registrarOperacion("bd_desarrollo","$idBit","RegistroBitBarr",$_SESSION['usr_reg']);
	}//Cierre de la función modificarBitBarrenacion()
	
	
	//Esta función modificara la Bitácora de Barrenación con Maq. de Pierna asociada a la Bitácora de Avance seleccionada para su edición
	function modificarBitBarrenacionMP(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST para la tabla de 'barrenacion_maq_pierna'
		$idBit = $_POST['hdn_idBitacora'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);
		$turno = $_POST['cmb_turno'];		
		$barrDados = $_POST['txt_barrDados'];
		$disparados = $_POST['txt_disparos'];
		$longitud = $_POST['txt_longitud'];		
		$brocaNueva = $_POST['txt_brocasNuevas'];
		$brocaAfilada = $_POST['txt_brocasAfiladas'];
		$barra6 = $_POST['txt_barras6'];
		$barra8 = $_POST['txt_barras8'];
		$anclas = $_POST['txt_anclas'];
		$observaciones = strtoupper($_POST['txa_observaciones']);
		$area = "MP";
				
		//Recuperar los datos del POST para la tabla de 'equipo'				
		$idEquipo = $_POST['cmb_equipo'];
		$horoIni = str_replace(",","",$_POST['txt_HIEquipo']);
		$horoFin = str_replace(",","",$_POST['txt_HFEquipo']);
		$horasTotales = str_replace(",","",$_POST['txt_HTEquipo']);
		
		//Recuperar los datos del POST para la tabla de 'personal'				
		$nomPerforista = strtoupper($_POST['txt_perforista']);
		$nomAyudante = strtoupper($_POST['txt_ayudante']);
		
		
		
		//Determinar que operaciones serán ejecutada (Actualizar o Registrar) en las tablas de Barrenacion Jumbo, Barrenos, Brazos, Personal y Equipo 
		$sql_barrenacion = ""; $sql_perOperador = ""; $sql_perAyudante = ""; $sql_equipo = "";
		$descBitMovimientos = "";
		if($_POST['hdn_actualizarBitacora']=="si"){//ACTUALIZAR DATOS
			//Crear la Sentencia SQL para guardar los datos de la Bitácora de Barrenación con Maquina de Pierna			
			$sql_barrenacion = "UPDATE barrenacion_maq_pierna SET fecha = '$fecha', turno = '$turno', barrenos_dados = $barrDados, barrenos_disparos = $disparados,
								barrenos_longitud = $longitud, broca_nva = $brocaNueva,	broca_afil = $brocaAfilada, barra_6 = $barra6, barra_8 = $barra8,
								anclas = $anclas, observaciones = '$observaciones' WHERE bitacora_avance_id_bitacora = '$idBit'";
			//Crear la Sentencia SQL para actualizar los datos del Personal
			$sql_perOperador = "UPDATE personal SET nombre='$nomPerforista' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='OPERADOR'";
			$sql_perAyudante = "UPDATE personal SET nombre='$nomAyudante' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='AYUDANTE'";			
			//Crear la Sentencia SQL para actualizar los datos del Equipo
			$sql_equipo = "UPDATE equipo SET id_equipo='$idEquipo',horo_ini=$horoIni, horo_fin=$horoFin, horas_totales=$horasTotales 
							WHERE bitacora_avance_id_bitacora = '$idBit'  AND area = '$area'";			
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos				
			$descBitMovimientos = "ModificarBitBarrMP";
		}//Cierre if($_POST['hdn_actualizarBitacora']=="si")
		else if($_POST['hdn_actualizarBitacora']=="no"){//REGISTRAR DATOS
			$sql_barrenacion = "INSERT INTO barrenacion_maq_pierna(bitacora_avance_id_bitacora,fecha,turno,barrenos_dados,barrenos_disparos,barrenos_longitud,broca_nva,
								broca_afil,barra_6,barra_8,anclas,observaciones) 
								VALUES('$idBit','$fecha','$turno',$barrDados,$disparados,$longitud,$brocaNueva,$brocaAfilada,$barra6,$barra8,$anclas,'$observaciones')"; 
			$sql_perOperador = "INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
								VALUES('N/A','$idBit','OPERADOR','$nomPerforista','$area')"; 
			$sql_perAyudante = "INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
								VALUES('N/A','$idBit','AYUDANTE','$nomAyudante','$area')"; 
			$sql_equipo = "INSERT INTO equipo (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,area) 
							VALUES('N/A','$idBit','$idEquipo',$horoIni,$horoFin,$horasTotales,'$area')";
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos				
			$descBitMovimientos = "RegistroBitBarrMP";
		}//Cierre else if($_POST['hdn_actualizarBitacora']=="no")
															
					
		//Ejecutar la Sentencia SQL para la Tabla 'barrenacion_jumbo'
		$rs = mysql_query($sql_barrenacion);
		
		if($rs){
													
			//Gurdar los datos del Personal (Jumbero y Ayudante)
			mysql_query($sql_perOperador);
			mysql_query($sql_perAyudante);
			
			//Gurdar los datos del Equipo que serán guardados en la tabla de Equipo de la BD de Desarrollo
			mysql_query($sql_equipo);
			
			
			//Obtener el ID del equipo registrado en la Bitácora de Fallas
			$rs_equipo = mysql_query("SELECT DISTINCT equipo FROM bitacora_fallas WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'BARRENACIONMP'");
			if($datosEquipo=mysql_fetch_array($rs_equipo)){			
				//Verificar si el equipo seleccionado en el Formulario de Barrenaación con Maquina de Pierna es el Mismo que fue registrardo en el Formulario de Fallas
				if($idEquipo!=$datosEquipo['equipo']){
					//Actualizar la Clave del equipo registrada en la Bitácora de Fallas
					mysql_query("UPDATE bitacora_fallas SET equipo = '$idEquipo' WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'BARRENACIONMP'");
				}
			}//Cierre if($datosEquipo=mysql_fetch_array($rs_equipo))
			
						
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idBit",$descBitMovimientos,$_SESSION['usr_reg']);
								
			//Guardar en la SESSION la variable que indica que la bitácora de maquina de pierna ha sido modificada
			$_SESSION['bitsActualizadas']['barrenacion_maq_pierna'] = 1;
					
			//Redireccionar a la Pagina de Registrar Avance (frm_regAvance.php)
			echo "<meta http-equiv='refresh' content='0;url=frm_modAvance2.php'>";
																		
		}//Cierre if($rs){
		else{
			//Cerrar la conexicion con la BD
			mysql_close();
			//Obtener el error generado
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";						
		}
	}//Cierre de la función modificarBitBarrenacionMP()
		
	
	//Esta función modificara la Bitácora de Voladura asociada a la Bitácora de Avance seleccionada para su edición
	function modificarBitVoladura(){
	
		$id_empleado_op = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_volador']);
		$id_empleado_ay = obtenerDatoRecHum("id_empleados_empresa", "CONCAT(  `nombre` ,  ' ',  `ape_pat` ,  ' ',  `ape_mat` )", $_POST['txt_ayudante']);
		
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST que serán guardados en la tabla de 'voladuras'
		$idBit = $_POST['hdn_idBitacora'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);
		$turno = $_POST['cmb_turno'];
		/*$longBarrCargado = $_POST['txt_longBarreno'];
		$factorCarga = $_POST['txt_factorCarga'];
		$obs = strtoupper($_POST['txa_observaciones']);*/
		$area = "VOLADURAS";
		
		//Recuperar los datos del POST que serán guardados en la tabla de 'personal'				
		$opVoladura = strtoupper($_POST['txt_volador']);
		$ayudante = strtoupper($_POST['txt_ayudante']);
		
		//Recuperar los datos del POST que serán guardados en la tabla de 'equipo'				
		$equipo = $_POST['cmb_equipo'];
		$horoIni = str_replace(",","",$_POST['txt_HIEquipo']);
		$horoFin = str_replace(",","",$_POST['txt_HFEquipo']);
		$horasTotales = str_replace(",","",$_POST['txt_HTEquipo']);
		
		$lbc = array();
		$fc = array();
		$tc = array();
		$obser = array();
		
		$stm_sql = "SELECT * 
					FROM  `voladuras` 
					WHERE  `bitacora_avance_id_bitacora` LIKE  '$idBit'";
		
		$rs_bit = mysql_query($stm_sql);
		
		$sql_voladura = ""; $sql_perOperador = ""; $sql_perAyudante = ""; $sql_equipo = "";
		$descBitMovimientos = "";
		
		if($rs_bit){
			$i=0;
			while($datos_bit = mysql_fetch_array($rs_bit)){
				if(isset($_POST["ckb_activarVol".$i])){
					$lbc[] = $_POST['txt_longBarreno'.$i];
					$fc[] = $_POST['txt_factorCarga'.$i];
					$disp[] = $_POST['txt_disparos'.$i];
					$dispNicho[] = $_POST['txt_disparosNicho'.$i];
					$tc[] = $_POST['txt_TopesCarg'.$i];
					$obser[] = strtoupper($_POST['txa_observaciones'.$i]);
					$regis = $i+1;
					//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
					$sql_voladura = "UPDATE voladuras SET fecha='$fecha', turno='$turno',long_barreno_carg='$lbc[$i]',factor_carga='$fc[$i]',
									disparos='$disp[$i]',disparos_nicho='$dispNicho[$i]',topes_cargados='$tc[$i]',observaciones='$obser[$i]' WHERE registro=$regis";
					//Ejecutar la Sentencia SQL
					$rs = mysql_query($sql_voladura);
				}else{
					$lbc[] = "";
					$fc[] = "";
					$disp[] = "";
					$dispNicho[] = "";
					$tc[] = "";
					$obser[] = "";
				}
				$i++;
			}
			
			$sql_perOperador = "UPDATE personal SET nombre='$id_empleado_op' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='OPERADOR'";
			$sql_perAyudante = "UPDATE personal SET nombre='$id_empleado_ay' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='AYUDANTE'";			
			//Crear la Sentencia SQL para actualizar los datos del Equipo
			$sql_equipo = "UPDATE equipo SET id_equipo='$equipo',horo_ini=$horoIni, horo_fin=$horoFin, horas_totales=$horasTotales 
							WHERE bitacora_avance_id_bitacora = '$idBit'  AND area = '$area'";							
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos				
			$descBitMovimientos = "ModificarBitVoladura";
			
			if($rs){
				//Gurdar los datos del Personal que serán guardados en la tabla de Personal de la BD de Desarrollo
				mysql_query($sql_perOperador);
				mysql_query($sql_perAyudante);
				
				//Verificar si el usuario selecciono un equipo
				if($equipo!=""){
					//Gurdar los datos del Equipo que serán guardados en la tabla de Equipo de la BD de Desarrollo
					mysql_query($sql_equipo);
					
					//Obtener el ID del equipo registrado en la Bitácora de Fallas
					$rs_equipo = mysql_query("SELECT DISTINCT equipo FROM bitacora_fallas WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'VOLADURAS'");
					if($datosEquipo=mysql_fetch_array($rs_equipo)){			
						//Verificar si el equipo seleccionado en el Formulario de Barrenaación con Maquina de Pierna es el Mismo que fue registrardo en el Formulario de Fallas
						if($equipo!=$datosEquipo['equipo']){
							//Actualizar la Clave del equipo registrada en la Bitácora de Fallas
							mysql_query("UPDATE bitacora_fallas SET equipo = '$equipo' WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'VOLADURAS'");
						}
					}//Cierre if($datosEquipo=mysql_fetch_array($rs_equipo))
				}
				
				//Guardar el Movimiento realizado en la tabla de Movimientos
				registrarOperacion("bd_desarrollo","$idBit",$descBitMovimientos,$_SESSION['usr_reg']);
				//Guardar en la SESSION la variable que indica que la bitácora de voladuras ha sido modificada
				$_SESSION['bitsActualizadas']['voladuras'] = 1;
				
				//Redireccionar a la Pagina de Registrar Avance (frm_regAvance.php)
				echo "<meta http-equiv='refresh' content='0;url=frm_modAvance2.php'>";													
			}
			else{
				//Cerrar la conexicion con la BD
				mysql_close();
				//Obtener el error generado
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
			}
		}
		
		//Determinar que operaciones serán ejecutada (Actualizar o Registrar) en las tablas de Barrenacion Jumbo, Barrenos, Brazos, Personal y Equipo 
		/*$sql_voladura = ""; $sql_perOperador = ""; $sql_perAyudante = ""; $sql_equipo = "";
		$descBitMovimientos = "";
		if($_POST['hdn_actualizarBitacora']=="si"){//ACTUALIZAR DATOS
			//Crear la Sentencia SQL para Actualizar los datos de la Bitácora de Voladura
			$sql_voladura = "UPDATE voladuras SET fecha = '$fecha', turno = '$turno', long_barreno_carg = $longBarrCargado, factor_carga = $factorCarga, observaciones =  '$obs'
							WHERE bitacora_avance_id_bitacora = '$idBit'";
			//Crear la Sentencia SQL para actualizar los datos del Personal
			$sql_perOperador = "UPDATE personal SET nombre='$opVoladura' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='OPERADOR'";
			$sql_perAyudante = "UPDATE personal SET nombre='$ayudante' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = '$area' AND puesto='AYUDANTE'";			
			//Crear la Sentencia SQL para actualizar los datos del Equipo
			$sql_equipo = "UPDATE equipo SET id_equipo='$equipo',horo_ini=$horoIni, horo_fin=$horoFin, horas_totales=$horasTotales 
							WHERE bitacora_avance_id_bitacora = '$idBit'  AND area = '$area'";							
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos				
			$descBitMovimientos = "ModificarBitVoladura";
		}//Cierre if($_POST['hdn_actualizarBitacora']=="si")
		else if($_POST['hdn_actualizarBitacora']=="no"){//REGISTRAR DATOS
			$sql_voladura = "INSERT INTO voladuras(bitacora_avance_id_bitacora,fecha,turno,long_barreno_carg,factor_carga,observaciones) 
								VALUES('$idBit','$fecha','$turno',$longBarrCargado,$factorCarga,'$obs')"; 
			$sql_perOperador = "INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
								VALUES('N/A','$idBit','OPERADOR','$opVoladura','$area')"; 
			$sql_perAyudante = "INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area)
								VALUES('N/A','$idBit','AYUDANTE','$ayudante','$area')"; 
			$sql_equipo = "INSERT INTO equipo (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,area) 
							VALUES('N/A','$idBit','$equipo',$horoIni,$horoFin,$horasTotales,'$area')";
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos				
			$descBitMovimientos = "RegistroBitVoladura";
		}//Cierre else if($_POST['hdn_actualizarBitacora']=="no")
		
										
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($sql_voladura);
		
		if($rs){
			//Gurdar los datos del Personal que serán guardados en la tabla de Personal de la BD de Desarrollo
			mysql_query($sql_perOperador);
			mysql_query($sql_perAyudante);
			
			//Verificar si el usuario selecciono un equipo
			if($equipo!=""){
				//Gurdar los datos del Equipo que serán guardados en la tabla de Equipo de la BD de Desarrollo
				mysql_query($sql_equipo);
				
				//Obtener el ID del equipo registrado en la Bitácora de Fallas
				$rs_equipo = mysql_query("SELECT DISTINCT equipo FROM bitacora_fallas WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'VOLADURAS'");
				if($datosEquipo=mysql_fetch_array($rs_equipo)){			
					//Verificar si el equipo seleccionado en el Formulario de Barrenaación con Maquina de Pierna es el Mismo que fue registrardo en el Formulario de Fallas
					if($equipo!=$datosEquipo['equipo']){
						//Actualizar la Clave del equipo registrada en la Bitácora de Fallas
						mysql_query("UPDATE bitacora_fallas SET equipo = '$equipo' WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'VOLADURAS'");
					}
				}//Cierre if($datosEquipo=mysql_fetch_array($rs_equipo))
				
			}
			
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idBit",$descBitMovimientos,$_SESSION['usr_reg']);
						
			//Guardar en la SESSION la variable que indica que la bitácora de voladuras ha sido modificada
			$_SESSION['bitsActualizadas']['voladuras'] = 1;
			
			//Redireccionar a la Pagina de Registrar Avance (frm_regAvance.php)
			echo "<meta http-equiv='refresh' content='0;url=frm_modAvance2.php'>";													
		}
		else{
			//Cerrar la conexicion con la BD
			mysql_close();
			//Obtener el error generado
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
		}*/
	}//Cierre de la función modificarBitVoladura()
	
	
	//Esta funcion modifica la bitácora de Rezagado asociada a la Bitácora de Avance seleccionada para su edición
	function modificarBitRezagado(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		
		//Recuperar los datos del POST
		$idBit = $_POST['hdn_idBitacora'];
		$operador = strtoupper($_POST['cmb_operador']);
		$puesto = $_POST['hdn_puesto'];
		$turno = $_POST['cmd_turno'];
		$fecha = modFecha($_POST['txt_fechaRegistro'],3);
		$equipo = $_POST['cmb_equipo'];
		
		$horoIni = str_replace(",","",$_POST['txt_horoIni']);
		$horoFin = str_replace(",","",$_POST['txt_horoFin']);
		$horasTotales = str_replace(",","",$_POST['txt_horasTotales']);
		
		$cuch = array();
		$obs = array();
		
		$stm_sql = "SELECT * 
					FROM  `rezagado` 
					WHERE  `bitacora_avance_id_bitacora` LIKE  '$idBit'";
		
		$rs_bit = mysql_query($stm_sql);
		
		$sql_rezagado = ""; $sql_personal = ""; $sql_equipo = "";
		$descBitMovimientos = "";
		
		if($rs_bit){
			$i=0;
			while($datos_bit = mysql_fetch_array($rs_bit)){
				$trasp = 0;
				$limpio = 0;
				if(isset($_POST["ckb_activarMin".$i])){
					$cuch[] = $_POST['txt_cucharonesMin'.$i];
					if(isset($_POST["ckb_activarTrasp".$i])){
						$trasp = 1;
					}
					if(isset($_POST["ckb_activarTLimp".$i])){
						$limpio = 1;
					}
					
					$obs[] = strtoupper($_POST['txa_observaciones'.$i]);
					
					//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
					$sql_rezagado = "UPDATE rezagado SET cuch='$cuch[$i]',traspaleo='$trasp',tope_limpio='$limpio',observaciones='$obs[$i]'
								WHERE `bitacora_avance_id_bitacora`='$datos_bit[bitacora_avance_id_bitacora]' AND  `fecha`='$datos_bit[fecha]' AND  `turno`='$datos_bit[turno]' AND 
								`cuch`='$datos_bit[cuch]' AND `traspaleo`='$datos_bit[traspaleo]' AND `tope_limpio`='$datos_bit[tope_limpio]' AND `observaciones`='$datos_bit[observaciones]'";
					//Ejecutar la Sentencia SQL
					$rs = mysql_query($sql_rezagado);
				}else{
					$cuch[] = "";
					$obs[] = "";
				}
				$i++;
			}
			//Crear la Sentencia SQL para actualizar los datos del Personal
			$sql_personal = "UPDATE personal SET puesto='$puesto', nombre='$operador' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = 'SCOOP'";
			//Crear la Sentencia SQL para actualizar los datos del Equipo
			$sql_equipo = "UPDATE equipo SET id_equipo='$equipo',horo_ini=$horoIni, horo_fin=$horoFin, horas_totales=$horasTotales 
							WHERE bitacora_avance_id_bitacora = '$idBit'  AND area = 'SCOOP'";			
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos				
			$descBitMovimientos = "ModificarBitRezagado";
		}
		
		/*$origenTep="";
		$destinoTep="";
		$cuchTep=0;
		if(isset($_POST["ckb_activarTep"])){
			$origenTep = $_POST['cmb_origenTepetate'];
			$destinoTep = $_POST['cmb_destinoTepetate'];
			$cuchTep = $_POST['txt_cucharonesTep'];
		}
		
		$origenMin = "";
		$destinoMin = "";
		$cuchMin = 0;
		if(isset($_POST["ckb_activarMin"])){
			$origenMin = $_POST['cmb_origenMineral'];
			$destinoMin = $_POST['cmb_destinoMineral'];
			$cuchMin = $_POST['txt_cucharonesMin'];
		}		
				
		$obs = strtoupper($_POST['txa_observaciones']);
		
		
		
		//Determinar que operaciones serán ejecutada (Actualizar o Registrar) en las tablas de Rezagado, Personal y Equipo
		$sql_rezagado = ""; $sql_personal = ""; $sql_equipo = "";
		$descBitMovimientos = "";
		if($_POST['hdn_actualizarBitacora']=="si"){//ACTUALIZAR DATOS			
			//Crear la Sentencia SQL para actualizar los datos de Rezagado en la BD
			$sql_rezagado = "UPDATE rezagado SET fecha='$fecha', turno='$turno', tep_origen='$origenTep', tep_cuch=$cuchTep, tep_destino='$destinoTep', min_cuch=$cuchMin,
							min_origen='$origenMin', min_destino='$destinoMin', observaciones = '$obs' WHERE bitacora_avance_id_bitacora = '$idBit'";
			//Crear la Sentencia SQL para actualizar los datos del Personal
			$sql_personal = "UPDATE personal SET puesto='$puesto', nombre='$operador' WHERE bitacora_avance_id_bitacora = '$idBit' AND area = 'SCOOP'";
			//Crear la Sentencia SQL para actualizar los datos del Equipo
			$sql_equipo = "UPDATE equipo SET id_equipo='$equipo',horo_ini=$horoIni, horo_fin=$horoFin, horas_totales=$horasTotales 
							WHERE bitacora_avance_id_bitacora = '$idBit'  AND area = 'SCOOP'";			
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos				
			$descBitMovimientos = "ModificarBitRezagado";
		}
		else if($_POST['hdn_actualizarBitacora']=="no"){//REGISTRAR DATOS
			//Crear la Sentencia SQL para alamcenar los datos de la Bitácora de Rezagado
			$sql_rezagado = "INSERT INTO rezagado(bitacora_avance_id_bitacora,fecha,turno,tep_origen,tep_cuch,tep_destino,min_cuch,min_origen,min_destino,observaciones) 
							VALUES('$idBit','$fecha','$turno','$origenTep',$cuchTep,'$destinoTep',$cuchMin,'$origenMin','$destinoMin','$obs')";
			//Crear la Sentencia SQL para gurdar los datos del Personal
			$sql_personal = "INSERT INTO personal (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,puesto,nombre,area) 
							VALUES('N/A','$idBit','$puesto','$operador','SCOOP')";
			//Crear la Sentencia SQL para gurdar los datos del Equipo
			$sql_equipo = "INSERT INTO equipo (bitacora_retro_bull_id_bitacora,bitacora_avance_id_bitacora,id_equipo,horo_ini,horo_fin,horas_totales,area) 
							VALUES('N/A','$idBit','$equipo',$horoIni,$horoFin,$horasTotales,'SCOOP')";
			//Definir el Mensaje que será guardado en la Bitácora de Movimientos
			$descBitMovimientos = "RegistroBitRezagado";
		} */
		
		if($rs){
			//Ejecutar la Sentencia SQL para Actualizar/Registrar los datos del Personal
			mysql_query($sql_personal);
			
			//Ejecutar la Sentencia SQL para Actualizar/Registrar los datos del Equipo
			mysql_query($sql_equipo);
						
			
			//Obtener el ID del equipo registrado en la Bitácora de Fallas
			$rs_equipo = mysql_query("SELECT DISTINCT equipo FROM bitacora_fallas WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'REZAGADO'");
			if($datosEquipo=mysql_fetch_array($rs_equipo)){			
				//Verificar si el equipo seleccionado en el Formulario de Rezagado es el Mismo que fue registrardo en el Formulario de Fallas
				if($equipo!=$datosEquipo['equipo']){
					//Actualizar la Clave del equipo registrada en la Bitácora de Fallas
					mysql_query("UPDATE bitacora_fallas SET equipo = '$equipo' WHERE bitacora_avance_id_bitacora = '$idBit' AND tipo_registro = 'REZAGADO'");
				}
			}
			
						
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_desarrollo","$idBit",$descBitMovimientos,$_SESSION['usr_reg']);
						
			//Guardar en la SESSION la variable que indica que la bitácora de rezagado ha sido modificada
			$_SESSION['bitsActualizadas']['rezagado'] = 1;
			
			//Redireccionar a la Pagina de Modificar Avance (frm_modAvance2.php)
			echo "<meta http-equiv='refresh' content='0;url=frm_modAvance2.php'>";													
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
			//Cerrar la conexicion con la BD
			mysql_close();
		}		
	}//Cierre de la función modificarBitRezagado()
	
	function obtenerDatoRecHum($buscar, $camp_comp, $camp_buscar){
		//Conectarse con la BD de Compras
		$conn = conecta("bd_recursos");
		
		$stm_sql = "SELECT $buscar
					FROM empleados
					WHERE $camp_comp =  '$camp_buscar'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDatoCompras($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
	function obtenerPersonalBitacora($id_empleado){
		$conn_rec = conecta("bd_recursos");
		$stm_sql_rec = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre_emp FROM empleados WHERE id_empleados_empresa = '$id_empleado'";
		$rs_rec = mysql_query($stm_sql_rec);
		if($rs_rec){
			$datos_rec = mysql_fetch_array($rs_rec);
			return $datos_rec["nombre_emp"];
		}
	}
?>