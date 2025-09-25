<?php
	//Funcion para mostrar la informacion del presupuesto
	function mostrarPresupuestos(){
		$ubicacion=$_POST['cmb_ubicacion'];
		$fecha_ini=modFecha($_POST['txt_fechaIni'],3);
		$fecha_fin=modFecha($_POST['txt_fechaFin'],3);
		$area=obtenerDato("bd_recursos","control_costos", "descripcion", "id_control_costos", $ubicacion);
		
		$sql_stm = "SELECT * 
					FROM presupuesto
					WHERE fecha_inicio >=  '$fecha_ini'
					AND fecha_fin <=  '$fecha_fin'
					AND id_control_costos =  '$ubicacion'";	
				
		$msg= "Presupuestos entre las fechas <em><u>$_POST[txt_fechaIni] y $_POST[txt_fechaFin]</u></em> de Ubicaci&oacute;n <em><u>  $area </u></em>";
		$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Presupuesto entre las fechas <em><u>$_POST[txt_fechaIni] y $_POST[txt_fechaFin]</u></em> de Ubicaci&oacute;n <em><u>  $area </u></em>";
		
		$conn = conecta("bd_gerencia");
		$rs = mysql_query($sql_stm);									
		if($datos=mysql_fetch_array($rs)){
			echo "				
			<table cellpadding='5' width='120%'>				
				<tr>
					<td colspan='8' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>PERIODO</td>
					<td class='nombres_columnas' align='center'>FECHA INICIO</td>
					<td class='nombres_columnas' align='center'>FECHA FIN</td>
					<td class='nombres_columnas' align='center'>VOLUMEN PRESUPUESTADO MENSUAL</td>
					<td class='nombres_columnas' align='center'>VOLUMEN PRESUPUESTADO DIARIO</td>
					<td class='nombres_columnas' align='center'>D&Iacute;AS LABORABLES</td>
					<td class='nombres_columnas' align='center'>DOMINGOS</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='checkbox' name='ckb_idPresupuesto' value= $datos[id_presupuesto]
						onClick='javascript:document.frm_seleccionarPresupuesto.submit();' />
						</td>
						<td class='$nom_clase' align='center'>$datos[periodo]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_inicio'],1)."</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_fin'],1)."</td>
						<td class='$nom_clase' align='center'>".number_format($datos['vol_ppto_mes'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>".number_format($datos['vol_ppto_dia'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>$datos[dias_habiles] D&iacute;as</td>
						<td class='$nom_clase' align='center'>$datos[dias_inhabiles] Domingos</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			echo "
			</table>";
			return 1;
			
		}
		else{
			echo $msg_error;
			return 0;
		}
	}
	
	
	function guardarModPresupuesto(){
		
		$fechaInicio = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		$volPresMensual= str_replace(",","",$_POST['txt_volPresupuestado']);
		$volDiario= str_replace(",","",$_POST['txt_presupuestoDiario']);
		$idPresupuesto=$_POST['hdn_claveDefinida'];
		$dias_habiles= str_replace(",","",$_POST['txt_diasLaborales']);
		$dias_inhabiles= str_replace(",","",$_POST['txt_domingos']);
		$ubicacion=$_POST["txt_ubicacion"];
			
		$periodo=substr($_POST['txt_fechaFin'],-4)."-";		
		$periodo.=obtenerPeriodo($fechaInicio,$fechaFin);
	
		$conn = conecta("bd_gerencia");

		$stm_sql = "UPDATE presupuesto SET 
						id_control_costos='$ubicacion', 
						periodo='$periodo',
						fecha_inicio='$fechaInicio',
						fecha_fin='$fechaFin',
						vol_ppto_mes='$volPresMensual',
						vol_ppto_dia='$volDiario',
						dias_habiles='$dias_habiles',
						dias_inhabiles='$dias_inhabiles' 
					WHERE id_presupuesto='$idPresupuesto'";		
				
		$rs=mysql_query($stm_sql);
		
		if ($rs){
			registrarOperacion("bd_gerencia",$idPresupuesto,"ModificarPresupuesto",$_SESSION['usr_reg']);
			?>
			<script>
				setTimeout("alert('Presupuesto modificado correctamente');",1000);
			</script>
			<?php
		}
		else{
			$error = mysql_error();
			?>
			<script>
				setTimeout("alert('Error al modificar presupuesto');",1000);
			</script>
			<?php
		}
 		//Cerrar la conexion con la BD		
		mysql_close($conn); 
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
	
	function generarIdNvaUbicacion(){
		//Realizar la conexion a la BD de Gerencia
		$conn = conecta("bd_gerencia");
		$id="";
		//Crear la sentencia para obtener el presupuesto Reciente
		$stm_sql = "SELECT COUNT(id_ubicacion) AS num, MAX(id_ubicacion)+1 AS cant FROM catalogo_ubicaciones";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
		
			if($datos['num']>0){
				$id .= ($datos['cant']);
			}
			else {
				$id .= "1";
			}
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}

	function guardarUbicacion($id,$ubicacion){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_gerencia");

		//Crear la Sentencia SQL para Alamcenar la nueva ubicacion 
		$stm_sql= "INSERT INTO catalogo_ubicaciones(id_ubicacion,ubicacion)	VALUES ('$id','$ubicacion')";		
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);

		//Cerrar la conexion con la BD		
		mysql_close($conn); 

	}	
?>