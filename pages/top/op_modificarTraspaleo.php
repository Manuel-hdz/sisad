<?php


	/*Esta funcion cargará los Datos Generales del Traspaleo en la SESSION*/
	function subirDatosModTraspaleo(){			
		//Obtener el numero de la Quincena
		$numQuincena = $_POST['cmb_noQuincena']." ".$_POST['cmb_Mes']." ".$_POST['cmb_Anio'];
		
		$_SESSION['datosTraspaleoMod'] = array("tipoObra"=>$_POST['txt_tipoObra'],"nomObra"=>$_POST['txt_nombreObra'],"idObra"=>$_POST['hdn_idObra'],"idTraspaleo"=>$_POST['hdn_idTraspaleo'],
											   "acumQuincena"=>$_POST['txt_acumuladoQuincena'],"tasaCambio"=>$_POST['txt_tasaCambio'],"seccion"=>$_POST['txt_seccion'],"area"=>$_POST['txt_area'],
											   "volumen"=>$_POST['txt_volumen'],"noQuincena"=>$numQuincena,"volumenMod"=>$_POST['hdn_cambioVolumen'],"tasaCambioMod"=>$_POST['hdn_cambioTipoCambio']);
	}//Cierre de la funcion subirDatosModTraspaleo()
	
	
	/*Esta funcion almacena los cambios realizados en el Registro del Trapaleo*/
	function guardarDetalleTraspaleo(){		
		//Obtener Datos de la SESSION
		$idObra = $_SESSION['datosTraspaleoMod']['idObra'];
		$idTraspaleo = $_SESSION['datosTraspaleoMod']['idTraspaleo']; 
		$noQuincena = $_SESSION['datosTraspaleoMod']['noQuincena']; 
		$acumQuincena = str_replace(",","",$_SESSION['datosTraspaleoMod']['acumQuincena']);
		$volumen = str_replace(",","",$_SESSION['datosTraspaleoMod']['volumen']);
		$tipoCambio = str_replace(",","",$_SESSION['datosTraspaleoMod']['tasaCambio']);
		
		//Conectar con la BD de Topografia
		$conn = conecta("bd_topografia");				
		
		//Actualizar los datos de la tabla de Traspaleos
		$sql_stm_upd = "UPDATE traspaleos SET no_quincena='$noQuincena', acumulado_quincena=$acumQuincena, volumen=$volumen, t_cambio=$tipoCambio WHERE id_traspaleo='$idTraspaleo' AND obras_id_obra='$idObra'";
		//Ejecutar la Sentencia
		$rs_upd = mysql_query($sql_stm_upd);
		//Si la Actualización se realizo con exito, proceder a guardar el detalle
		if($rs_upd){
			//Borrar los registros de la Base de Datos y despues Guardar los nuevos registros
			mysql_query("DELETE FROM detalle_traspaleos WHERE traspaleos_id_traspaleo='$idTraspaleo'");
			
			//Guardar el Error en el caso de que se presente en la Inseción del Detalle de Traspaleo
			$error_detalle = "";
			
			//Guardar cada uno de los registros de traspaleo que vienen en el POST
			for($i=1;$i<$_POST['hdn_cantRegistros'];$i++){
				//Obtener los datos del POST para ser Insertados en el Detalle de Traspaleos
				$fechaRegistro = modFecha($_POST["txt_fechaReg".$i],3);
				$origen = strtoupper($_POST["txt_origen".$i]);
				$destino = strtoupper($_POST["txt_destino".$i]);
				$distancia = str_replace(",","",$_POST["txt_distancia".$i]);
				$precioMN = str_replace(",","",$_POST["txt_pumn".$i]);
				$precioUSD = str_replace(",","",$_POST["txt_puusd".$i]);
				$totalMN = str_replace(",","",$_POST["txt_totalMN".$i]);
				$totalUSD = str_replace(",","",$_POST["txt_totalUSD".$i]);
				$importe = str_replace(",","",$_POST["txt_importeTotal".$i]);
				//Crear la Sentencia para Actualizar los registros
				$sql_stm = "INSERT INTO detalle_traspaleos (traspaleos_id_traspaleo,no_registro,origen,destino,distancia,pu_mn,pu_usd,total_mn,total_usd,importe_total,fecha_registro) 
								VALUES('$idTraspaleo',$i,'$origen','$destino',$distancia,$precioMN,$precioUSD,$totalMN,$totalUSD,$importe,'$fechaRegistro')"; 
				$rs = mysql_query($sql_stm);
				
				if(!$rs){
					$error_detalle = mysql_error();
					break;
				}
			}
			
			if($error_detalle==""){
				//Registrar la modificación hecha en la Bitacora de Movimientos
				registrarOperacion("bd_topografia",$idTraspaleo,"ModificarRegistroDeTraspaleo",$_SESSION['usr_reg']);			
				
				//LIberar los datos de la SESSION
				unset($_SESSION['datosTraspaleoMod']);
				
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
			}
			else{
				//Cerrar la Conexion con la BD
				mysql_close($conn);				
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			//Cerrar la Conexion con la BD
				mysql_close($conn);				
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
		
	}//Cierre de la funcion guardarDetalleTraspaleo()

?>