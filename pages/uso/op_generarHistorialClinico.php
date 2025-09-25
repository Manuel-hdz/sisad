<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional
	  * Nombre Programador: Nadia Madahí López Hernandez
	  * Fecha: 18/Julio/2012
	  * Descripción: Este archivo permite registrar la informacion relacionada con el historial clinico de los trabajadores
	  **/
	 	
	//Esta funcion genera la Clave del Historial de acuerdo a los registros en la BD
	function obtenerIdHistorialClinico(){
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
		$stm_sql = "SELECT COUNT(id_historial) AS cant FROM historial_clinico WHERE id_historial LIKE 'HIS$mes$anio%'";
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
	}//Fin de la Funcion obtenerId()		
	
	//Esta funcion genera la Clave del Historial de acuerdo a los registros en la BD
	function obtenerIdAlertaHistorialClinicoHistorialClinico(){
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		
		//Definir las tres letras la clave del historial
		$id_cadena = "AHC";
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
	
	
	//Funcion para guardar la informacion del Historial Clinico de cada uno de los trabajadores 
	function registrarHistorialClinico(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		$bandPrincipal = 0;
		$idHistorial = obtenerIdHistorialClinico();
		$idAlertaHis = obtenerIdAlertaHistorialClinicoHistorialClinico();
		//Recuperar la informacion del post		
		$empleado = strtoupper($_POST['txt_nombre']);
		$numEmp = $_POST['txt_numEmp'];
		$nss = $_POST['txt_numSS'];
		$puesto = strtoupper($_POST['txt_puesto']);
		$fechaReg = modFecha($_POST['txt_fechaReg'],3);
		$sexo = $_POST['cmb_sexo'];
		$edad = $_POST['txt_edad'];
		$reside = strtoupper($_POST['txt_reside']);
		$originario = strtoupper($_POST['txt_originario']);
		$edoCivil = $_POST['cmb_edoCivil'];
		$domicilio = strtoupper($_POST['txt_domicilio']);
		$fechaNac = modFecha($_POST['txt_fechaNac'],3);
		$tel = $_POST['txt_tel'];
		$escolaridad = strtoupper($_POST['txt_escolaridad']);
		$claveEsc = $_POST['cmb_claveEsc'];
		$nomEmpresa = $_POST['txt_empresa'];
		$razSocial = $_POST['txt_razSocial'];
		$tipoClas = strtoupper($_POST["txt_tipoClas"]);
		$clasExa = strtoupper($_POST["txt_clasExa"]);
		
		$result = $_POST['txt_resUSO'];
		$result_explode = explode(',', $result);
		$resUSO = strtoupper($result_explode[1]);
		//Obterner la clave del del departamento al cual se le mostraran las alertas de Examen Medico
		$idDepto = obtenerDato('bd_recursos', 'empleados', 'id_depto', 'id_empleados_empresa', $numEmp);
		
		$fechaProg = intval(substr($fechaReg,0,4))+1;
		$fechaProg.=substr($fechaReg,-6);
		
		//Conectar se a la Base de Datos de la clinica
		$conn = conecta("bd_clinica");
		//Crear la Sentencia SQL para Alamcenar la informacion general y principal del historial clinico
		$stm_sql= "INSERT INTO historial_clinico (id_historial, clasificacion_exa, tipo_clasificacion, puesto_realizar, num_afiliacion, fecha_exp, 
			nom_empleado, id_empleados_empresa, sexo, edad, reside_en, originario_de, edo_civil, domicilio, telefono, fecha_nac, escolaridad, clave_escolaridad, 
			nom_empresa, razon_social, nom_dr) 
			VALUES( '$idHistorial',  '$clasExa', '$tipoClas', '$puesto', '$nss',  '$fechaReg', '$empleado', '$numEmp', '$sexo', '$edad', 
			'$reside', '$originario', '$edoCivil', '$domicilio', '$tel', '$fechaNac', '$escolaridad', '$claveEsc', '$nomEmpresa', '$razSocial', '$resUSO')";
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			//Si el Tipo de clasificacion es igual a externo entonces que ejecute la siguiente consulta que almnacenara los datos del trabajador en la tabla de alerta_examen
			if($tipoClas!="EXTERNO"){
				//Borrar las alertas del trabajador en caso de eque exista
				mysql_query("DELETE FROM alerta_examen WHERE id_empleados_empresa = '$numEmp'");			

				//Sentencia que guardara los datso necesarios dentro de la tabla id_alerta_exa, donde se podra generar la informacion de la alerta del empleado
				$stm_sql_alerta = "INSERT INTO alerta_examen (id_alerta_exa, historial_clinico_id_historial, catalogo_departamentos_id_departamento, estado, 
				fecha_programada, nom_empleado, id_empleados_empresa) VALUES('$idAlertaHis', '$idHistorial', '$idDepto', '0', '$fechaProg', 
				'$empleado', '$numEmp')";
	
				//Ejecutar la Sentencia
				$rsAlerta=mysql_query($stm_sql_alerta);	
			}
			else{		
				//Obtenemos la clave del empleado externo mediante un POST ya que esta se envio mediante una variable typo hidden desde frm_generarHCExterno
				$idEmpleadoExt = $_POST["hdn_idEmpleadoExt"];
				//Ejecutar la sentencia que guarda los datos del empleado externo dentro de la tabla historial_externo
				mysql_query("INSERT INTO historial_externos (historial_clinico_id_historial, empleados_externos_id_registro) VALUES('$idHistorial', '$idEmpleadoExt')");			
			}
			//unset ($_SESSION['']);
			registrarOperacion("bd_clinica","$idHistorial","GeneracionHistorialClinico",$_SESSION['usr_reg']);?>
					<script type='text/javascript' language='javascript'>
						setTimeout("window.open('../../includes/generadorPDF/historialClinico.php?idHistorial=<?php echo $idHistorial; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);
					</script>
										
				<?php
			echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
		}
		else{
			echo $error = mysql_error();
			$bandPrincipal = 1;
			eliminarRegistroCancelado();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarHistorialClinico()
		
	 
	 
	 //Esta funcion se encarga de registrar los antecedentes familiares del trabajor dentro de la tabla antecedentes_fam
	  function registrarHistorialFamiliar(){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandHistorialFam = 0;
	 	$idHistorial=obtenerIdHistorialClinico();
		
			//Recuperar la informacion del post		
			$peso = $_POST['txt_peso'];
			$talla =$_POST['txt_talla'];
			$diamAP = $_POST['txt_diamAP'];
			$hisFam = strtoupper($_POST['txa_hisFam']);
			$diamLAT = $_POST['txt_diamLAT'];
			$ant = strtoupper($_POST['txa_ant']);
			$cirEXP = $_POST['txt_circEXP'];
			$antHisMed = strtoupper($_POST['txa_hisMedicaAnt']);
			$cirINSP = $_POST['txt_circINSP'];
			$pulso = $_POST['txt_pulso'];
			$resp = $_POST['txt_resp'];
			$antPP = strtoupper($_POST['txa_antPP']);
			$temp = $_POST['txt_temp'];
			$presArt = $_POST['txt_presArt'];
			$imc =$_POST['txt_imc'];
			$secuelas = strtoupper($_POST['txt_secuelas']);
			$spo =$_POST['txt_spo2'];
			
			//Realizamos la consulta que insertara los datos del historial familiar en la tabla antecendentes familiares
			$stm_sqlHisFam="INSERT INTO antecedentes_fam (historial_clinico_id_historial, peso_kg, talla_mts, historia_familiar, antecedentes, 
			historia_medica_ant, antecedentes_pp, enf_prof_secuelas, torax_diam_ap, torax_diam_lat, torax_circ_exp, torax_circ_insp, pulso, respiracion, 
			temp, pres_arterial, imc , spo2)
			VALUES('$idHistorial', '$peso', '$talla', '$hisFam', '$ant', '$antHisMed', '$antPP', '$secuelas', '$diamAP', '$diamLAT', '$cirEXP', '$cirINSP', '$pulso',  
			'$resp', '$temp', '$presArt', '$imc', '$spo')";
			
			//Ejecutamos la sentencia previamante creada
			$rsHisFam = mysql_query($stm_sqlHisFam);
			if(!$rsHisFam){
				$bandHistorialFam = 1;
				eliminarRegistroCancelado();
			} else {
				return true;
			}
		}
	
	
	 function registrarAspectosGrales1(){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandAspGrales = 0;
	 	$idHistorial=obtenerIdHistorialClinico();
		
			//Recuperar la informacion del post		
			$tipoGral = strtoupper($_POST['txt_tipoGral']);
			$nutricion = strtoupper($_POST['txt_nutricion']);
			$piel = strtoupper($_POST['txt_piel']);
			$lentes = $_POST['cmb_lentes'];
			$visDer = $_POST['txt_visionDer'];
			$visIzq = $_POST['txt_visionIzq'];
			$refDer = strtoupper($_POST['txt_refDer']);
			$refIzq = strtoupper($_POST['txt_refIzq']);
			$pterDer = strtoupper($_POST['txt_pterDer']);
			$pterIzq = strtoupper($_POST['txt_pterIzq']);
			$otDer = strtoupper($_POST['txt_otrosDer']);
			$otIzq = strtoupper($_POST['txt_otrosIzq']);
			$auDer = $_POST['txt_audDer'];
			$auIzq = $_POST['txt_audIzq'];
			$canDer = strtoupper($_POST['txt_canalDer']);
			$canIzq = strtoupper($_POST['txt_canalIzq']);
			$mbDer = strtoupper($_POST['txt_memDer']);
			$mbIzq = strtoupper($_POST['txt_memIzq']);
			$hcb = $_POST['txt_hbc'];
			$tipo = strtoupper($_POST['cmb_tipo']);
			$ipp = $_POST['txt_ipp'];
		
			//Realizamos la consulta que insertara los datos de los aspectos generales 1 que son parte del historial clinico
			$stm_sqlAspGrales1="INSERT INTO aspectos_grales_1 (historial_clinico_id_historial, tipo_gral, nutricion, piel, lentes, ojo_der_vision, 
				ojo_der_pterygiones, ojo_der_reflejos, ojo_der_otros, ojo_izq_vision, ojo_izq_pterygiones, ojo_izq_reflejos, ojo_izq_otros, oido_der_audicion, 
				oido_der_canal, oido_izq_audicion, oido_izq_canal, membrana_der, membrana_izq, porciento_hbc, tipo, porciento_ipp) 
			VALUES('$idHistorial', '$tipoGral', '$nutricion', '$piel', '$lentes', '$visDer', '$pterDer', '$refDer', '$otDer', '$visIzq', '$pterIzq', '$refIzq',
				 '$otIzq', '$auDer', '$canDer', '$auIzq', '$canIzq', '$mbDer', '$mbIzq', '$hcb', '$tipo', '$ipp');";
			
			//Ejecutamos la sentencia previamante creada
			$rsAspGrales1 = mysql_query($stm_sqlAspGrales1);
			if(!$rsAspGrales1){
				$bandAspGrales = 1;
				eliminarRegistroCancelado();
			} else {
				return true;
			}
		}
	
	
	
	 function registrarAspectosGrales2(){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandAspGrales2 = 0;
	 	$idHistorial=obtenerIdHistorialClinico();
		
			//Recuperar la informacion del post		
			$nariz = strtoupper($_POST['txt_nariz']);
			$obst = strtoupper($_POST['txt_obstruccion']);
			$boca = strtoupper($_POST['txt_boca']);
			$encias = strtoupper($_POST['txt_encias']);
			$dientes = strtoupper($_POST['txt_dientes']);
			$cuello = strtoupper($_POST['txt_cuello']);
			$linfa = strtoupper($_POST['txt_linfaticos']);
			$torax = strtoupper($_POST['txt_torax']);
			$corazon = strtoupper($_POST['txt_corazon']);
			$pulmones = strtoupper($_POST['txt_pulmones']);
			$abdomen = strtoupper($_POST['txt_abdomen']);
			$higado = strtoupper($_POST['txt_higado']);
			$bazo = strtoupper($_POST['txt_bazo']);
			$pared = strtoupper($_POST['txt_pared']);
			$anillo = strtoupper($_POST['txt_anillos']);
			$hernias = strtoupper($_POST['txt_hernias']);
			$genUri = strtoupper($_POST['txt_genUri']);
			$hidro = strtoupper($_POST['txt_hidro']);
			$vari = strtoupper($_POST['txt_vari']);
			$hemo = strtoupper($_POST['txt_hemo']);
			$extSup = strtoupper($_POST['txt_extSup']);
			$extInf = strtoupper($_POST['txt_extInf']);
			$reflejos = strtoupper($_POST['txt_reflejos']); 
			$psi = strtoupper($_POST['txt_psiquismo']);
			$sintoma = strtoupper($_POST['txt_sintoma']);
		
			//Realizamos la consulta que insertara los datos del historial familiar en la tabla antecendentes familiares
			$stm_sqlAspGrales2="INSERT INTO aspectos_grales_2 (historial_clinico_id_historial, nariz, obstruccion, boca_garganta, encias, dientes, cuello, linfaticos, 
				torax, corazon, pulmones, abdomen, higado, bazo, pared_abdominal, anillo, hernias, gen_uri, hidrocele, varicocele, hemorroides, extr_suprs, extr_infrs, 
				reflejos_ot, psiquismo, sintoma_actual) 
				VALUES('$idHistorial', '$nariz', '$obst', '$boca', '$encias', '$dientes', '$cuello', '$linfa', '$torax', '$corazon', '$pulmones', '$abdomen','$higado', 
				'$bazo', '$pared', '$anillo', '$hernias', '$genUri', '$hidro', '$vari', '$hemo', '$extSup', '$extInf', '$reflejos', '$psi', '$sintoma')";
			
			//Ejecutamos la sentencia previamante creada
			$rsAspGrales2 = mysql_query($stm_sqlAspGrales2);
			if(!$rsAspGrales2 ){
				$bandAspGrales2 = 1;
				eliminarRegistroCancelado();
			} else {
				return true;
			}
	}
	
	
	 function registrarAntPatologicos(){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandAntPat = 0;
	 	$idHistorial=obtenerIdHistorialClinico();
		
			//Recuperar la informacion del post		
			$act = $_POST['cmb_actividad'];
			$tab = $_POST['cmb_tabaquismo'];
			$eti = $_POST['cmb_etilismo'];
			$otAdicc = strtoupper($_POST['txt_otrasAdicciones']);
		
			//Realizamos la consulta que insertara los datos del historial familiar en la tabla antecendentes familiares
			$stm_sqlAntPat="INSERT INTO ant_no_patologicos (historial_clinico_id_historial, actividad, etilismo, tabaquismo, otras_adicc) 
			VALUES('$idHistorial', '$act', '$tab', '$eti', '$otAdicc')";
			
			//Ejecutamos la sentencia previamante creada
			$rsAntPat = mysql_query($stm_sqlAntPat);
			if(!$rsAntPat){
				$bandAntPat = 1;
				eliminarRegistroCancelado();
			} else {
				return true;
			}
	}
	
	
	 function registrarPruebasEsfuerzo(){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandPruEsfzo = 0;
	 	$idHistorial=obtenerIdHistorialClinico();
		
			//Recuperar la informacion del post		
			$pulsoRes = $_POST['txt_pulsoRep'];
			$resRep = $_POST['txt_respRep'];
			$pulsoImn = $_POST['txt_pulsoInm'];
			$resInm = $_POST['txt_respInm'];
			$pulso1 = $_POST['txt_pulso1Desp'];
			$resp1 = $_POST['txt_resp1Desp'];
			$pulso2 = $_POST['txt_pulso2Desp'];
			$res2 = $_POST['txt_resp2Desp'];
			
			//Realizamos la consulta que insertara los datos del historial familiar en la tabla antecendentes familiares
			$stm_sqlPruEsfzo="INSERT INTO prueba_esfuerzo (historial_clinico_id_historial, pulso_reposo, pulso_inm_desp_esfzo, pulso_un_min_desp, pulso_dos_min_desp, 
				resp_reposo, resp_inm_desp_esfzo, resp_un_min_desp, resp_dos_min_desp)
				 VALUES('$idHistorial', '$pulsoRes', '$resRep', '$pulsoImn', '$resInm', '$pulso1', '$resp1', '$pulso2', '$res2')";
			
			//Ejecutamos la sentencia previamante creada
			$rsPruEsfzo = mysql_query($stm_sqlPruEsfzo);
			if(!$rsPruEsfzo){
				$bandPruEsfzo = 1;
				eliminarRegistroCancelado();
			} else {
				return true;
			}
	}
	
	
	 function registrarPruebasLaboratorio(){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandPruLab = 0;
	 	$idHistorial=obtenerIdHistorialClinico();
		$idResultado = obtenerIdResultadosExa();
			//Recuperar la informacion del post		
			$vdrl = strtoupper($_POST['txt_vdrl']);
			$bh = strtoupper($_POST['txt_bh']);
			$glicemia = strtoupper($_POST['txt_glicemia']);
			$pie = strtoupper($_POST['txt_pie']);
			$gralOri = strtoupper($_POST['txt_gralOrina']);
			$pb = strtoupper($_POST['txt_pbSang']);
			$hiv = strtoupper($_POST['txt_hiv']);
			$cadmino = strtoupper($_POST['txt_cadmio']);
			$fosAcida = strtoupper($_POST['txt_fosAcida']);
			$tg = strtoupper($_POST['txt_tg']);
			$fosAlcalina = strtoupper($_POST['txt_fosAlcalina']);
			$colesterol = strtoupper($_POST['txt_colesterol']);
			$espiro = strtoupper($_POST['txt_espirometria']);
			$tipoSanguineo = strtoupper($_POST['txt_tipoSanguineo']);
			$globulin = strtoupper($_POST['txt_bMglobulin']);
			$fcr = strtoupper($_POST['txt_fcr']);
			$diagLab = strtoupper($_POST['txt_diagLab']);
			$rx = strtoupper($_POST['txt_rxTorax']);
			$alco = strtoupper($_POST['txt_alcoholimetro']);
			$silicosis = strtoupper($_POST['txt_silicosis']);
			$fracc = strtoupper($_POST['txt_fracc']);
			$col = strtoupper($_POST['txt_colLum']);
			$rom = strtoupper($_POST['txt_romberg']);
			$weil = strtoupper($_POST['txt_weil']);
			$diag = strtoupper($_POST['txt_diagnostico']);
			$conclusiones = strtoupper($_POST['txt_conclusiones']);
			$edoSalud = strtoupper($_POST['txt_edoSalud']);
			
		
			//Realizamos la consulta que insertara los datos del historial familiar en la tabla antecendentes familiares
			$stm_sqlPruLab="INSERT INTO Laboratorio (historial_clinico_id_historial, vdrl, bh, glicemia, pie, gral_orina, pb_sang, hiv, cadmio, fosfata_acida, tg, 
			fosfata_alcalina, colesterol, espirometria, tipo_sanguineo, b_mglobulin, fcr, diag_laboratorio, rx_torax, alcoholimetro, porcentaje_silicosis, fracc,
			 col_lumbrosaca, romberg, babinsky_weil, diagnostico, conclusiones, edo_salud) 
			VALUES('$idHistorial', '$vdrl', '$bh', '$glicemia', '$pie','$gralOri', '$pb','$hiv', '$cadmino','$fosAcida', '$tg', '$fosAlcalina','$colesterol', 
			'$espiro', '$tipoSanguineo', '$globulin', '$fcr', '$diagLab', '$rx', '$alco', '$silicosis', '$fracc', '$col', '$rom', '$weil', '$diag', 
			'$conclusiones','$edoSalud')";
			
			//Ejecutamos la sentencia previamante creada
			$rsPruLab = mysql_query($stm_sqlPruLab);
			
			mysql_query("INSERT INTO resultados_historiales (id_resultado, historial_clinico_id_historial, resultado, recomendacion, imss) 
				VALUES('$idResultado', '$idHistorial', '$diag', '$conclusiones', '')");
			
			if(!$rsPruLab){
				$bandPruLab = 1;
				eliminarRegistroCancelado();
			} else {
				return true;
			}
		}
	
	
		
	 /*Esta funcion genera el id de  los registros de resultadosde HC en la BD y los cuales pueden ser modificados por el usuario*/
	function obtenerIdResultadosExa(){
		$id="";
		//Crear la sentencia para obtener la clave de la Empresa
		$stm_sql = "SELECT MAX(id_resultado) AS cant FROM resultados_historiales";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
				$id .= $cant;
		}
		return $id;
	}//Fin de la function obtenerIdResultadosExa()
	
	
	 
	function registrarHisTrabajo(){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
	 	
		//Obtener el id del historial del examen medico a registrar
		$idHistorial=obtenerIdHistorialClinico();

		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandHisTrab = 0;
		
	 	//Recorremos el arreglo  para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['HisTrabajo'] as $ind => $value){	
			$cadenaCondEsp = "";//Variable que contendra nuestra cadena de carateres, declarada como vacia
			$condEsp = 1;//Variable contador declarada en 1
			do{
				if($value["condEsp".$condEsp]!="")/*Si nuestro $arrTrab(arreglo); contiene los indices de cada una de los valores del arreglo y es 
				representado por $condEsp es diferente de vacio, que se muestre la cadena que contiene cada uno de nuestras opciones*/
					$cadenaCondEsp.=$value["condEsp".$condEsp].", ";//
				$condEsp++;//Se debe de incrementar el contador de acuerdo a la eleccion de cada una de las opciones
			}while($condEsp<=6);//Miestras nuestro $condEsp(Contador) sea menor o igual a 6 se va estar realizando o ejecutando el ciclo.
			/*A la variable $cadenaCondEsp la cual contiene nuestra cadena de caraceteres se le quitara la ultima coma(,) y espacio que se le coloca 
			al final de las $condEsp seleccionadas por el ususrio*/
			$cadenaCondEsp  = substr($cadenaCondEsp,0,strlen($cadenaCondEsp)-2);
											
			//Creamos la sentencia SQL para insertar los datos en la tabla catalogo_procedimientos
			$stm_sqlHisTrabajo="INSERT INTO historial_trabajo (historial_clinico_id_historial, lugar, tipo_trabajo, tiempo, cond_especiales) 
				VALUES('$idHistorial', '$value[lugar]', '$value[tipoTrab]', '$value[tiempo]', '$cadenaCondEsp')";
			
			//Ejecutamos la sentencia previamante creada
			$rsTrabajo = mysql_query($stm_sqlHisTrabajo);
			
			if(!$rsTrabajo)
				$bandHisTrab = 1;
					
		}//foreach($_SESSION['trabajo'] as $ind => $trabajo
		
		if($bandHisTrab == 0){
			return true;
		}
	}
	
	function mostrarRegistrosHisTrabajo($trabajo){
		//Verificamos que exista la session
		if($_SESSION['HisTrabajo']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Historial de Trabajo del Empleado</caption>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>LUGAR</td>
					<td class='nombres_columnas' align='center'>TIPO TRABAJO</td>
					<td class='nombres_columnas' align='center'>TIEMPO</td>
					<td class='nombres_columnas' align='center'>CONDICIONES ESPCIALES</td>					
					<td class='nombres_columnas' align='center'>BORRAR</td>					
        		</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			foreach($_SESSION['HisTrabajo'] as $key => $arrTrab){
				$cadenaCondEsp = "";//Variable que contendra nuestra cadena de carateres, declarada como vacia
				$condEsp = 1;//Variable contador declarada en 1
				do{
					if($arrTrab["condEsp".$condEsp]!="")//Si nuestro $arrTrab(arreglo); contiene los indices de cada una de los valores del arreglo y es representado por $condEsp es diferente de vacio, que se muestre la cadena que contiene cada uno de nuestras opciones
						$cadenaCondEsp.=$arrTrab["condEsp".$condEsp].", ";//
					$condEsp++;//Se debe de incrementar el contador de acuerdo a la eleccion de cada una de las opciones
				}while($condEsp<=6);//Miestras nuestro $condEsp(Contador) sea menor o igual a 6 se va estar realizando o ejecutando el ciclo.
				//A la variable $cadenaCondEsp la cual contiene nuestra cadena de caraceteres se le quitara la ultima coma(,) y espacio que se le coloca al final de las $condEsp seleccionadas por el ususrio
				$cadenaCondEsp  = substr($cadenaCondEsp,0,strlen($cadenaCondEsp)-2);
				
				echo "<tr>
						<td align='center'  class='nombres_filas'>$cont</td>
						<td align='center'  class='$nom_clase'>$arrTrab[lugar]</td>
						<td align='center'  class='$nom_clase'>$arrTrab[tipoTrab]</td>
						<td align='center'  class='$nom_clase'>$arrTrab[tiempo]</td>
						<td align='center'  class='$nom_clase'>$cadenaCondEsp</td>";?>
						
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="location.href='verHistorialTrabajo.php?noRegistro=<?php echo $key;?>'"/>
					</td><?php				
			echo "</tr>";					
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			};
			echo " </table>";
		}
	}

	//Funcion que borra los registro en caso de que el usuario cancele el registro
	function eliminarRegistroCancelado(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");

	 	$idHistorial=obtenerIdHistorialClinico();

		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
		//Variable que nos permitira conocer si hubo errores en el registro
		$bandPrinc= 0;
		
		//Sentencia que obtendra cada uno de los registros de cauerdo al historial familiar que se desee eliminar en caso de que el usuario cancele el registro
		$sql_stmConPrincipal = "SELECT * from historial_clinico 
			JOIN antecedentes_fam ON id_historial = antecedentes_fam.historial_clinico_id_historial 
			JOIN ant_no_patologicos ON id_historial = ant_no_patologicos.historial_clinico_id_historial 
			JOIN aspectos_grales_1 ON id_historial = aspectos_grales_1.historial_clinico_id_historial 
			JOIN aspectos_grales_2 ON id_historial = aspectos_grales_2.historial_clinico_id_historial 
			JOIN historial_trabajo ON id_historial = historial_trabajo.historial_clinico_id_historial 
			JOIN laboratorio ON id_historial = laboratorio.historial_clinico_id_historial
			JOIN  resultados_historiales ON id_historial = resultados_historiales.historial_clinico_id_historial";
			
		//Ejecutamos la sentencia previamente declarada
		$rs_prin = mysql_query($sql_stmConPrincipal);
		
		if($rs_prin){
			mysql_query("SELECT * from historial_clinico JOIN prueba_esfuerzo ON id_historial = prueba_esfuerzo.historial_clinico_id_historial");
			$bandPrinc = 1;
			//Crear las sentencias SQL para eliminar de la bd_clinica de la tabla ant_no_patalogicos, los datos almacenados en caso de que haya sido cancelado el registro
			$stm_sqlAntPat = "DELETE FROM ant_no_patologicos WHERE historial_clinico_id_historial = '$idHistorial'";
			//Ejecutamos la sentencia previamente creada
			$rsAntPat = mysql_query($stm_sqlAntPat);
		
			//Crear las sentencias SQL para eliminar de la bd_clinica de la tabla antecendentes_fam, los datos almacenados en caso de que haya sido cancelado el registro	
			$stm_sqlAntFam = "DELETE FROM antecedentes_fam WHERE historial_clinico_id_historial = '$idHistorial'";
			//Ejecutamos la sentencia previamente creada
			$rsAntFam = mysql_query($stm_sqlAntFam);
			
			//Crear las sentencias SQL para eliminar de la bd_clinica de la tabla aspectos_grales_1, los datos almacenados en caso de que haya sido cancelado el registro				
			$stm_sqlAspGrales1 = "DELETE FROM aspectos_grales_1 WHERE historial_clinico_id_historial = '$idHistorial'";
			$rs_AspGrales1 = mysql_query($stm_sqlAspGrales1);
			
			//Crear las sentencias SQL para eliminar de la bd_clinica de la tabla aspectos_grales_2, los datos almacenados en caso de que haya sido cancelado el registro				
			$stm_sqlAspGrales2 = "DELETE FROM aspectos_grales_2 WHERE historial_clinico_id_historial = '$idHistorial'";
			$rs_AspGrales2 = mysql_query($stm_sqlAspGrales2);
			
			//Crear las sentencias SQL para eliminar de la bd_clinica de la tabla laboratorio, los datos almacenados en caso de que haya sido cancelado el registro	
			$stm_sqlPrueLab = "DELETE FROM laboratorio WHERE historial_clinico_id_historial = '$idHistorial'";
			$rs_prueLab = mysql_query($stm_sqlPrueLab);

			//Crear las sentencias SQL para eliminar de la bd_clinica de la tabla prueba_esfuerzo, los datos almacenados en caso de que haya sido cancelado el registro				
			$stm_sqlPrueEsfzo = "DELETE FROM prueba_esfuerzo WHERE historial_clinico_id_historial = '$idHistorial'";
			$rs_pruEsfzo = mysql_query($stm_sqlPrueEsfzo);
			
			//Crear las sentencias SQL para eliminar de la bd_clinica de la tabla historial_trabajo, los datos almacenados en caso de que haya sido cancelado el registro				
			$stm_sqlHisTrab = "DELETE FROM historial_trabajo WHERE historial_clinico_id_historial = '$idHistorial'";
			$rs_hisTrab = mysql_query($stm_sqlHisTrab);
			
			//Crear las sentencias SQL para eliminar de la bd_clinica de la tabla historial_trabajo, los datos almacenados en caso de que haya sido cancelado el registro				
			$stm_sqlResHC = "DELETE FROM resultados_historiales WHERE historial_clinico_id_historial = '$idHistorial'";
			$rs_resHC = mysql_query($stm_sqlResHC);
		}
		else{
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
	}
	
?>