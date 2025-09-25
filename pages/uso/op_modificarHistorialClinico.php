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
	
	function modificarAntecedentesFamiliares($clave,$existe){
		
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
		
		$conn = conecta("bd_clinica");
		if($existe){
			$stm_sql = "UPDATE antecedentes_fam SET 
							peso_kg = '$peso',
							talla_mts = '$talla',
							historia_familiar = '$hisFam',
							antecedentes = '$ant',
							historia_medica_ant = '$antHisMed',
							antecedentes_pp = '$antPP',
							enf_prof_secuelas = '$secuelas',
							torax_diam_ap = '$diamAP',
							torax_diam_lat = '$diamLAT',
							torax_circ_exp = '$cirEXP',
							torax_circ_insp = '$cirINSP',
							pulso = '$pulso',
							respiracion = '$resp',
							temp = '$temp',
							pres_arterial = '$presArt',
							imc = '$imc',
							spo2 = '$spo' 
						WHERE historial_clinico_id_historial = '$clave'";
		} else {
			$stm_sql = "INSERT INTO antecedentes_fam (
							historial_clinico_id_historial, 
							peso_kg, 
							talla_mts, 
							historia_familiar, 
							antecedentes, 
							historia_medica_ant, 
							antecedentes_pp, 
							enf_prof_secuelas, 
							torax_diam_ap, 
							torax_diam_lat, 
							torax_circ_exp, 
							torax_circ_insp, 
							pulso, 
							respiracion, 
							temp, 
							pres_arterial, 
							imc , 
							spo2
						) VALUES (
							'$clave', 
							'$peso', 
							'$talla', 
							'$hisFam', 
							'$ant', 
							'$antHisMed', 
							'$antPP', 
							'$secuelas', 
							'$diamAP', 
							'$diamLAT', 
							'$cirEXP', 
							'$cirINSP', 
							'$pulso', 
							'$resp', 
							'$temp', 
							'$presArt', 
							'$imc', 
							'$spo'
						)";
		}
		$rs = mysql_query($stm_sql);
		if($rs){
			registrarOperacion("bd_clinica","$clave","ModificarAntFamiliares",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('Se modificaron antecedentes familiares correctamente');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('Hubo un problema al momento de modificar el registro');",1000);
			</script>
			<?php
		}
	}
	
	function modificarAspectosGrales1($clave,$existe){
	 	$conn = conecta("bd_clinica");
	 
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
		
		if($existe){
			$stm_sql = "UPDATE aspectos_grales_1 SET 
							tipo_gral = '$tipoGral',
							nutricion = '$nutricion',
							piel = '$piel',
							lentes = '$lentes',
							ojo_der_vision = '$visDer',
							ojo_der_pterygiones = '$pterDer',
							ojo_der_reflejos = '$refDer',
							ojo_der_otros = '$otDer',
							ojo_izq_vision = '$visIzq',
							ojo_izq_pterygiones = '$pterIzq',
							ojo_izq_reflejos = '$refIzq',
							ojo_izq_otros = '$otIzq',
							oido_der_audicion = '$auDer',
							oido_der_canal = '$canDer',
							oido_izq_audicion = '$auIzq',
							oido_izq_canal = '$canIzq',
							membrana_der = '$mbDer',
							membrana_izq = '$mbIzq',
							porciento_hbc = '$hcb',
							tipo = '$tipo',
							porciento_ipp = '$ipp' 
						WHERE historial_clinico_id_historial = '$clave'";
		} else {
			$stm_sql = "INSERT INTO aspectos_grales_1 (
							historial_clinico_id_historial, 
							tipo_gral, 
							nutricion, 
							piel, 
							lentes, 
							ojo_der_vision, 
							ojo_der_pterygiones, 
							ojo_der_reflejos, 
							ojo_der_otros, 
							ojo_izq_vision, 
							ojo_izq_pterygiones, 
							ojo_izq_reflejos, 
							ojo_izq_otros, 
							oido_der_audicion, 
							oido_der_canal, 
							oido_izq_audicion, 
							oido_izq_canal, 
							membrana_der, 
							membrana_izq, 
							porciento_hbc, 
							tipo, porciento_ipp
						) VALUES (
							'$clave', 
							'$tipoGral', 
							'$nutricion', 
							'$piel', 
							'$lentes', 
							'$visDer', 
							'$pterDer', 
							'$refDer', 
							'$otDer', 
							'$visIzq', 
							'$pterIzq', 
							'$refIzq', 
							'$otIzq', 
							'$auDer', 
							'$canDer', 
							'$auIzq', 
							'$canIzq', 
							'$mbDer', 
							'$mbIzq', 
							'$hcb', 
							'$tipo', 
							'$ipp'
						)";
		}
		
		$rs = mysql_query($stm_sql);
		if($rs){
			registrarOperacion("bd_clinica","$clave","ModificarAspGrals1",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('Se modificaron Aspectos Generales 1 correctamente');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('Hubo un problema al momento de modificar el registro');",1000);
			</script>
			<?php
		}
	}
	
	function modificarAspectosGrales2($clave,$existe){
	 	$conn = conecta("bd_clinica");
	 
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
		
		if($existe){
			$stm_sql = "UPDATE aspectos_grales_2 SET 
							nariz = '$nariz',
							obstruccion = '$obst',
							boca_garganta = '$boca',
							encias = '$encias',
							dientes = '$dientes',
							cuello = '$cuello',
							linfaticos = '$linfa',
							torax = '$torax',
							corazon = '$corazon',
							pulmones = '$pulmones',
							abdomen = '$abdomen',
							higado = '$higado',
							bazo = '$bazo',
							pared_abdominal = '$pared',
							anillo = '$anillo',
							hernias = '$hernias',
							gen_uri = '$genUri',
							hidrocele = '$hidro',
							varicocele = '$vari',
							hemorroides = '$hemo',
							extr_suprs = '$extSup',
							extr_infrs = '$extInf',
							reflejos_ot = '$reflejos',
							psiquismo = '$psi',
							sintoma_actual = '$sintoma' 
						WHERE historial_clinico_id_historial = '$clave'";
		} else {
			$stm_sql = "INSERT INTO aspectos_grales_2 (
							historial_clinico_id_historial, 
							nariz, 
							obstruccion, 
							boca_garganta, 
							encias, 
							dientes, 
							cuello, 
							linfaticos, 
							torax, 
							corazon, 
							pulmones, 
							abdomen, 
							higado, 
							bazo, 
							pared_abdominal, 
							anillo, 
							hernias, 
							gen_uri, 
							hidrocele, 
							varicocele, 
							hemorroides, 
							extr_suprs, 
							extr_infrs, 
							reflejos_ot, 
							psiquismo, 
							sintoma_actual
						) VALUES (
							'$clave', 
							'$nariz', 
							'$obst', 
							'$boca', 
							'$encias', 
							'$dientes', 
							'$cuello', 
							'$linfa', 
							'$torax', 
							'$corazon', 
							'$pulmones', 
							'$abdomen', 
							'$higado', 
							'$bazo', 
							'$pared', 
							'$anillo', 
							'$hernias', 
							'$genUri', 
							'$hidro', 
							'$vari', 
							'$hemo', 
							'$extSup', 
							'$extInf', 
							'$reflejos', 
							'$psi', 
							'$sintoma'
						)";
		}
		
		$rs = mysql_query($stm_sql);
		if($rs){
			registrarOperacion("bd_clinica","$clave","ModificarAspGrals2",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('Se modificaron Aspectos Generales 2 correctamente');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('Hubo un problema al momento de modificar el registro');",1000);
			</script>
			<?php
		}
	}
	
	function modificarAntecedentesPatologicos($clave,$existe){
	 	$conn = conecta("bd_clinica");
	 
	 	$act = $_POST['cmb_actividad'];
		$tab = $_POST['cmb_tabaquismo'];
		$eti = $_POST['cmb_etilismo'];
		$otAdicc = strtoupper($_POST['txt_otrasAdicciones']);
		
		if($existe){
			$stm_sql = "UPDATE ant_no_patologicos SET 
							actividad = '$act',
							etilismo = '$eti',
							tabaquismo = '$tab',
							otras_adicc = '$otAdicc' 
						WHERE historial_clinico_id_historial = '$clave'";
		} else {
			$stm_sql = "INSERT INTO ant_no_patologicos (
							historial_clinico_id_historial, 
							actividad, 
							etilismo, 
							tabaquismo, 
							otras_adicc
						) VALUES (
							'$clave', 
							'$act', 
							'$tab', 
							'$eti', 
							'$otAdicc'
						)";
		}
		
		$rs = mysql_query($stm_sql);
		if($rs){
			registrarOperacion("bd_clinica","$clave","ModificarAntPatologicos",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('Se modificaron Antecedentes Patologicos correctamente');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('Hubo un problema al momento de modificar el registro');",1000);
			</script>
			<?php
		}
	}
	
	function modificarPruebaLaboratorio($clave,$existe){
		$conn = conecta("bd_clinica");
		$idResultado = obtenerIdResultadosEx();
		
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
		
		if($existe){
			$stm_sql = "UPDATE laboratorio SET 
							vdrl = '$vdrl',
							bh = '$bh',
							glicemia = '$glicemia',
							pie = '$pie',
							gral_orina = '$gralOri',
							pb_sang = '$pb',
							hiv = '$hiv',
							cadmio = '$cadmino',
							fosfata_acida = '$fosAcida',
							tg = '$tg',
							fosfata_alcalina = '$fosAlcalina',
							colesterol = '$colesterol',
							espirometria = '$espiro',
							tipo_sanguineo = '$tipoSanguineo',
							b_mglobulin = '$globulin',
							fcr = '$fcr',
							diag_laboratorio = '$diagLab',
							rx_torax = '$rx',
							alcoholimetro = '$alco',
							porcentaje_silicosis = '$silicosis',
							fracc = '$fracc',
							col_lumbrosaca = '$col',
							romberg = '$rom',
							babinsky_weil = '$weil',
							diagnostico = '$diag',
							conclusiones = '$conclusiones',
							edo_salud = '$edoSalud' 
						WHERE historial_clinico_id_historial = '$clave'";
			
			$stm_sql2 = "UPDATE resultados_historiales SET 
							resultado = '$diag',
							recomendacion = '$conclusiones' 
						WHERE historial_clinico_id_historial = '$clave'";
		} else {
			$stm_sql = "INSERT INTO Laboratorio (
							historial_clinico_id_historial, 
							vdrl, 
							bh, 
							glicemia, 
							pie, 
							gral_orina, 
							pb_sang, 
							hiv, 
							cadmio, 
							fosfata_acida, 
							tg, 
							fosfata_alcalina, 
							colesterol, 
							espirometria, 
							tipo_sanguineo, 
							b_mglobulin, 
							fcr, 
							diag_laboratorio, 
							rx_torax, 
							alcoholimetro, 
							porcentaje_silicosis, 
							fracc, 
							col_lumbrosaca, 
							romberg, 
							babinsky_weil, 
							diagnostico, 
							conclusiones, 
							edo_salud
						) VALUES (
							'$clave', 
							'$vdrl', 
							'$bh', 
							'$glicemia', 
							'$pie', 
							'$gralOri', 
							'$pb', 
							'$hiv', 
							'$cadmino', 
							'$fosAcida', 
							'$tg', 
							'$fosAlcalina', 
							'$colesterol', 
							'$espiro', 
							'$tipoSanguineo', 
							'$globulin', 
							'$fcr', 
							'$diagLab', 
							'$rx', 
							'$alco', 
							'$silicosis', 
							'$fracc', 
							'$col', 
							'$rom', 
							'$weil', 
							'$diag', 
							'$conclusiones',
							'$edoSalud'
						)";
			
			$stm_sql2 = "INSERT INTO resultados_historiales (
							id_resultado, 
							historial_clinico_id_historial, 
							resultado, 
							recomendacion, 
							imss
						) VALUES (
							'$idResultado', 
							'$clave', 
							'$diag', 
							'$conclusiones', 
							''
						)";
		}
		
		$rs = mysql_query($stm_sql);
		$rs2 = mysql_query($stm_sql2);
		if($rs){
			registrarOperacion("bd_clinica","$clave","ModificarPruebaLaboratorio",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('Se modifico Prueba de Laboratorio correctamente');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('Hubo un problema al momento de modificar el registro');",1000);
			</script>
			<?php
		}
	}
	
	function modificarPruebaEsfuerzo($clave,$existe){
	 	$conn = conecta("bd_clinica");
	 
	 	$pulsoRes = $_POST['txt_pulsoRep'];
		$resRep = $_POST['txt_respRep'];
		$pulsoImn = $_POST['txt_pulsoInm'];
		$resInm = $_POST['txt_respInm'];
		$pulso1 = $_POST['txt_pulso1Desp'];
		$resp1 = $_POST['txt_resp1Desp'];
		$pulso2 = $_POST['txt_pulso2Desp'];
		$res2 = $_POST['txt_resp2Desp'];
		
		if($existe){
			$stm_sql = "UPDATE prueba_esfuerzo SET 
							pulso_reposo = '$pulsoRes',
							pulso_inm_desp_esfzo = '$pulsoImn',
							pulso_un_min_desp = '$pulso1',
							pulso_dos_min_desp = '$pulso2',
							resp_reposo = '$resRep',
							resp_inm_desp_esfzo = '$resInm',
							resp_un_min_desp = '$resp1',
							resp_dos_min_desp = '$res2' 
						WHERE historial_clinico_id_historial = '$clave'";
		} else {
			$stm_sql = "INSERT INTO prueba_esfuerzo (
							historial_clinico_id_historial, 
							pulso_reposo, 
							pulso_inm_desp_esfzo, 
							pulso_un_min_desp, 
							pulso_dos_min_desp, 
							resp_reposo, 
							resp_inm_desp_esfzo, 
							resp_un_min_desp, 
							resp_dos_min_desp
						) VALUES (
							'$clave', 
							'$pulsoRes', 
							'$pulsoImn', 
							'$pulso1', 
							'$pulso2', 
							'$resRep', 
							'$resInm', 
							'$resp1', 
							'$res2'
						)";
		}
		
		$rs = mysql_query($stm_sql);
		if($rs){
			registrarOperacion("bd_clinica","$clave","ModificarPruebaEsfuerzo",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('Se modifico Prueba de Esfuerzo correctamente');",1000);
			</script>
			<?php
		} else {
			?>
			<script>
				setTimeout("alert('Hubo un problema al momento de modificar el registro');",1000);
			</script>
			<?php
		}
	}
	
	function modificarHistorialTrabajo($clave,$existe){
	 	$conn = conecta("bd_clinica");
		$rs = mysql_query("DELETE FROM historial_trabajo WHERE historial_clinico_id_historial = '$clave'");
		if($rs){
			foreach($_SESSION['HisTrabajo'] as $ind => $value){	
				$cadenaCondEsp = "";
				$condEsp = 1;
				do{
					if($value["condEsp".$condEsp]!="")
						$cadenaCondEsp.=$value["condEsp".$condEsp].", ";
					$condEsp++;
				}while($condEsp<=6);
				
				$cadenaCondEsp  = substr($cadenaCondEsp,0,strlen($cadenaCondEsp)-2);
				
				$stm_sqlHisTrabajo="INSERT INTO historial_trabajo (
										historial_clinico_id_historial, 
										lugar, 
										tipo_trabajo, 
										tiempo, 
										cond_especiales
									) VALUES (
										'$clave', 
										'$value[lugar]', 
										'$value[tipoTrab]', 
										'$value[tiempo]', 
										'$cadenaCondEsp'
									)";
				$rsTrabajo = mysql_query($stm_sqlHisTrabajo);
				
				if(!$rsTrabajo)
					$bandHisTrab = 1;
			}
			
			if($bandHisTrab == 0){
				registrarOperacion("bd_clinica","$clave","ModificarHistorialTrabajo",$_SESSION['usr_reg']);
				?>
				<script>
					setTimeout("alert('Se modifico Historial de Trabajo correctamente');",1000);
				</script>
				<?php
			} else {
				?>
				<script>
					setTimeout("alert('Hubo un problema al momento de modificar el registro');",1000);
				</script>
				<?php
			}
		} else {
			?>
			<script>
				setTimeout("alert('Hubo un problema al momento de modificar el registro');",1000);
			</script>
			<?php
		}			
		unset($_SESSION['HisTrabajo']);
	}
	
	function obtenerIdResultadosEx(){
		$id="";
		//Crear la sentencia para obtener la clave de la Empresa
		$stm_sql = "SELECT MAX(id_resultado) AS cant FROM resultados_historiales";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
				$id = $cant;
		}
		return $id;
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
					<form method="post" action="frm_modificarHistorialTrabajo.php" id="frm_temp<?php echo $key; ?>" name="frm_temp<?php echo $key; ?>" >
						<input type="hidden" name="noRegistro" id="noRegistro" value="<?php echo $key;?>"/>
						<input type="hidden" name="rdb_id" value="<?php echo $_POST['rdb_id']; ?>"/>
						<input type="hidden" name="tipo" value="<?php echo $_POST['tipo']; ?>"/>
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25" border="0" title="Borrar Registro"/>
						</td>
					</form><?php
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