<?php
	function guardarPresupuesto(){
		$idPresupuesto=obtenerIdPresupuesto();
		$conn = conecta("bd_gerencia");

		$fechaInicio = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		$volPresMensual= str_replace(",","",$_POST['txt_volPresupuestado']);
		$volDiario= str_replace(",","",$_POST['txt_presupuestoDiario']);
		$diasHabiles= str_replace(",","",$_POST['txt_diasLaborales']);
		$diasInhabiles= str_replace(",","",$_POST['txt_domingos']);
		$ubicacion=$_POST["txt_ubicacion"];
		
		$periodo=substr($_POST['txt_fechaFin'],-4)."-";		
		$periodo.=obtenerPeriodo($fechaInicio,$fechaFin);
	
		$stm_sql = "INSERT INTO presupuesto(
						id_presupuesto,
						id_control_costos,
						periodo,
						fecha_inicio,
						fecha_fin,
						vol_ppto_mes,
						vol_ppto_dia,
						dias_habiles,
						dias_inhabiles
					) VALUES (
						'$idPresupuesto',
						'$ubicacion',
						'$periodo',
						'$fechaInicio',
						'$fechaFin',
						$volPresMensual,
						$volDiario,
						$diasHabiles,
						$diasInhabiles
					)";		
		
		$rs=mysql_query($stm_sql);
		
		if ($rs){
			registrarOperacion("bd_gerencia",$idPresupuesto,"RegistrarPresupuesto",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('Presupuesto agregado correctamente');",1000);
			</script>
			<?php
		}
		else{
			$error = mysql_error();
			echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>".$stm_sql;
			?>
			<script>
				setTimeout("alert('Error al agregar presupuesto');",1000);
			</script>
			<?php
		}
 		mysql_close($conn); 
	}
	
	function obtenerIdPresupuesto(){
		$conn = conecta("bd_gerencia");
		//Definir las tres letras en la Id del Pedido
		$id_cadena = "PRE";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		$stm_sql = "SELECT MAX( CAST( SUBSTR( id_presupuesto, 8 ) AS UNSIGNED ) ) AS cant
					FROM presupuesto
					WHERE id_presupuesto LIKE  'PRE$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras del Pedido Registrado en la BD y sumarle 1
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "000".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "00".$cant;
			if($cant>99 && $cant<1000)
				$id_cadena .= "0".$cant;
			if($cant>=1000)
				$id_cadena .= $cant;
		}
			
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		
		return $id_cadena;
	}
	
	//Esta funcion Obtiene el periodo correspondiente segun la fecha 
	function obtenerPeriodo($fechaini,$fechafin){
		$inicio=substr($fechaini,5,2);
		switch($inicio){
			case "01":
				$inicio="ENE";
			break;
			case "02":
				$inicio="FEB";
			break;
			case "03":
				$inicio="MAR";
			break;
			case "04":
				$inicio="ABR";
			break;
			case "05":
				$inicio="MAY";
			break;
			case "06":
				$inicio="JUN";
			break;
			case "07":
				$inicio="JUL";
			break;
			case "08":
				$inicio="AGO";
			break;
			case "09":
				$inicio="SEP";
			break;
			case "10":
				$inicio="OCT";
			break;
			case "11":
				$inicio="NOV";
			break;
			case "12":
				$inicio="DIC";
			break;
			default:
				$inicio=""; 
		}
		
		$fin=substr($fechafin,5,2);
			switch($fin){
			case "01":
				$fin="ENE";
			break;
			case "02":
				$fin="FEB";
			break;
			case "03":
				$fin="MAR";
			break;
			case "04":
				$fin="ABR";
			break;
			case "05":
				$fin="MAY";
			break;
			case "06":
				$fin="JUN";
			break;
			case "07":
				$fin="JUL";
			break;
			case "08":
				$fin="AGO";
			break;
			case "09":
				$fin="SEP";
			break;
			case "10":
				$fin="OCT";
			break;
			case "11":
				$fin="NOV";
			break;
			case "12":
				$fin="DIC";
			break;
			default:
				$fin=""; 
		}
		
		return $inicio."-".$fin;
	}// FIN obtenerPeriodo
?>