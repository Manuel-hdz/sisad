<?php
	function sumarDiasFechaNomina($fecha,$dias){
		list($day,$mon,$year) = explode('/',$fecha);
		return date('d/m/Y',mktime(0,0,0,$mon,$day+$dias,$year));
	}
	
	function dias_transcurridos($fecha_i,$fecha_f){
		$dias	= (strtotime($fecha_i)-strtotime($fecha_f))/86400;
		$dias 	= abs($dias); 
		$dias = floor($dias);		
		return $dias;
	}
	
	function obtenerIdNomina(){
		
		$id_cadena = "NOMZAR";
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		$stm_sql_nom = "SELECT MAX( CAST( SUBSTR( id_nomina, 11 ) AS UNSIGNED ) ) AS cant
						FROM nominas
						WHERE id_nomina LIKE  'NOMZAR$mes$anio%'";
		$rs_nom = mysql_query($stm_sql_nom);
		if($datos_nom=mysql_fetch_array($rs_nom)){
			$cant = $datos_nom['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		return $id_cadena;
	}
	
	function guardarNomina(){
		$conn = conecta("bd_gerencia");
		$id_nomina = obtenerIdNomina();
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		$fechaReg = date("Y-m-d");
		$id_cc = $_POST["cmb_ubicacion"];
		$dias_transcurridos = dias_transcurridos($fechaI,$fechaF);
		$dias_transcurridos += 1;
		if(isset($_POST["btn_avance"])){
			$finalizada = 0;
		} else if(isset($_POST["btn_continuar"])){
			$finalizada = 1;
		}
		
		$stm_sql = "INSERT INTO  nominas (
						id_nomina,
						fecha_registro,
						fecha_inicio,
						fecha_fin,
						id_control_costos,
						num_dias,
						finalizada
					) VALUES (
						'$id_nomina',
						'$fechaReg',
						'$fechaI',
						'$fechaF',
						'$id_cc',
						$dias_transcurridos,
						$finalizada
					)";
		$rs = mysql_query($stm_sql);
		if($rs){
			guardarDetalleNomina($id_nomina);
		} else {
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	function guardarDetalleNomina($id_nomina){
		$num_empl = $_POST["hdn_cont"];
		$correcto = true;
		for($i = 1; $i <= $num_empl; $i++){
			$rfc = $_POST["rfc_empl".$i];
			$sueldo_diario = str_replace(",","",$_POST["sueldo_diario".$i]);
			$he = $_POST["txt_he".$i];
			$guardia = 0;
			if(isset($_POST["chk_8hrs_".$i]))
				$guardia = 8;
			else if(isset($_POST["chk_12hrs_".$i]))
				$guardia = 12;
			$bono = str_replace(",","",$_POST["txt_bono".$i]);
			$total = str_replace(",","",$_POST["txt_total".$i]);
			$comentarios = strtoupper($_POST["txt_comentario".$i]);
			
			$stm_sql = "INSERT INTO  detalle_nominas (
							id_nomina,
							rfc_trabajador,
							sueldo_diario,
							horas_extra,
							guardia,
							bonificacion_empl,
							total_pagado,
							comentarios
						) VALUES (
							'$id_nomina',
							'$rfc',
							$sueldo_diario,
							$he,
							$guardia,
							$bono,
							$total,
							'$comentarios'
						)";
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$correcto = false;
		}
		if($correcto){
			asistenciaNomina($id_nomina);
		} else {
			mysql_query("DELETE FROM nominas WHERE id_nomina = '$id_nomina'");
			mysql_query("DELETE FROM detalle_nominas WHERE id_nomina = '$id_nomina'");
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	function asistenciaNomina($id_nomina){
		$num_empl = $_POST["hdn_cont"];
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		$dias_transcurridos = dias_transcurridos($fechaI,$fechaF);
		$correcto = true;
		for($i = 1; $i <= $num_empl; $i++){
			for($j = 0; $j <= $dias_transcurridos; $j++){
				$rfc = $_POST["rfc_empl".$i];
				$fecha_dia = $_POST["fecha_dias".$i."_".$j];
				$asistencia = "FALTA";
				if(isset($_POST["chk_asistencia_".$i."_".$j]))
					$asistencia = "ASISTENCIA";
				else if(isset($_POST["chk_incapacidad_".$i."_".$j]))
					$asistencia = "INCAPACIDAD";
				else if(isset($_POST["chk_descanso_".$i."_".$j]))
					$asistencia = "DESCANSO";
				else if(isset($_POST["chk_alcohol_".$i."_".$j]))
					$asistencia = "ALCOHOLIMETRIA";
				
				$stm_sql = "INSERT INTO  asistencia_empl_nom (
								id_nomina,
								rfc_trabajador,
								fecha_asistencia,
								asistencia
							) VALUES (
								'$id_nomina',
								'$rfc',
								'$fecha_dia',
								'$asistencia'
							)";
				
				$rs = mysql_query($stm_sql);
				if(!$rs){
					$correcto = false;
				}
			}
		}
		if($correcto){
			if(isset($_POST["btn_avance"])){
				registrarOperacion("bd_gerencia",$id_nomina,"AvanceNomina",$_SESSION['usr_reg']);
			} else if(isset($_POST["btn_continuar"])){
				registrarOperacion("bd_gerencia",$id_nomina,"RegistrarNomina",$_SESSION['usr_reg']);
			}
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		} else {
			mysql_query("DELETE FROM nominas WHERE id_nomina = '$id_nomina'");
			mysql_query("DELETE FROM detalle_nominas WHERE id_nomina = '$id_nomina'");
			mysql_query("DELETE FROM asistencia_empl_nom WHERE id_nomina = '$id_nomina'");
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	function continuarNomina(){
		$conn = conecta("bd_gerencia");
		$id_nomina = $_POST["cmb_nomina"];
		if(isset($_POST["btn_avance"])){
			$finalizada = 0;
		} else if(isset($_POST["btn_continuar"])){
			$finalizada = 1;
		}
		$stm_sql_upd_nomina  = "UPDATE nominas SET
									finalizada = '$finalizada'
								WHERE id_nomina = '$id_nomina'";
		$rs_upd_nomina = mysql_query($stm_sql_upd_nomina);
		
		if($rs_upd_nomina){
			continuarDetalleNomina($id_nomina);
		} else {
			$error = "Error al actualizar la nomina";
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	function continuarDetalleNomina($id_nomina){
		$num_empl = $_POST["hdn_cont"];
		$actualizacion = true;
		for($i = 1; $i <= $num_empl; $i++){
			$rfc_empl = $_POST["rfc_empl".$i];
			$sueldo_diario = str_replace(",","",$_POST["sueldo_diario".$i]);
			$he = $_POST["txt_he".$i];
			$guardia = 0;
			if(isset($_POST["chk_8hrs_".$i]))
				$guardia = 8;
			else if(isset($_POST["chk_12hrs_".$i]))
				$guardia = 12;
			$bono = str_replace(",","",$_POST["txt_bono".$i]);
			$total = str_replace(",","",$_POST["txt_total".$i]);
			$comentarios = strtoupper($_POST["txt_comentario".$i]);
			
			$stm_sql_upd_detalle_nomina  = "UPDATE detalle_nominas SET
												sueldo_diario = $sueldo_diario,
												horas_extra = $he,
												guardia = $guardia,
												bonificacion_empl = $bono,
												total_pagado = $total,
												comentarios = '$comentarios'
											WHERE 
												id_nomina = '$id_nomina' 
												AND rfc_trabajador = '$rfc_empl'";
			$rs_upd_detalle_nomina = mysql_query($stm_sql_upd_detalle_nomina);
			
			if(!$rs_upd_detalle_nomina){
				$actualizacion = false;
			}
		}
		if($actualizacion){
			continuarAsistenciaNomina($id_nomina);
		} else {
			mysql_query("UPDATE nominas SET finalizada = '0' WHERE id_nomina = '$id_nomina'");
			$error = "Error al actualizar el detalle de la nomina";
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	function continuarAsistenciaNomina($id_nomina){
		$num_empl = $_POST["hdn_cont"];
		$dias_transcurridos = $_POST["hdn_dias"];
		$actualizacion = true;
		for($i = 1; $i <= $num_empl; $i++){
			for($j = 0; $j < $dias_transcurridos; $j++){
				$rfc_empl = $_POST["rfc_empl".$i];
				$fecha_dia = $_POST["fecha_dias".$i."_".$j];
				$asistencia = "FALTA";
				if(isset($_POST["chk_asistencia_".$i."_".$j]))
					$asistencia = "ASISTENCIA";
				else if(isset($_POST["chk_incapacidad_".$i."_".$j]))
					$asistencia = "INCAPACIDAD";
				else if(isset($_POST["chk_descanso_".$i."_".$j]))
					$asistencia = "DESCANSO";
				else if(isset($_POST["chk_alcohol_".$i."_".$j]))
					$asistencia = "ALCOHOLIMETRIA";
				
				$stm_sql_upd_asistencia  = "UPDATE asistencia_empl_nom SET
												asistencia = '$asistencia' 
											WHERE 
												id_nomina = '$id_nomina' 
												AND rfc_trabajador = '$rfc_empl' 
												AND fecha_asistencia = '$fecha_dia'";
				$rs_upd_asistencia = mysql_query($stm_sql_upd_asistencia);
				
				if(!$rs_upd_asistencia){
					$actualizacion = false;
				}
			}
		}
		
		if($actualizacion){
			if(isset($_POST["btn_avance"])){
				registrarOperacion("bd_gerencia",$id_nomina,"AvanceNomina",$_SESSION['usr_reg']);
			} else if(isset($_POST["btn_continuar"])){
				registrarOperacion("bd_gerencia",$id_nomina,"RegistrarNomina",$_SESSION['usr_reg']);
			}
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		} else {
			mysql_query("UPDATE nominas SET finalizada = '0' WHERE id_nomina = '$id_nomina'");
			$error = "Error al actualizar asistencias de la nomina";
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
?>