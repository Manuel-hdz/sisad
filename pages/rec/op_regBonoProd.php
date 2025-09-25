<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 18/Abril/2011
	  * Descripción: Permite generar reportes de asistencia de los empleados 
	**/
	
	function mostrarEmpleadosBonoProd(){
		$cc = obtenerDatosCentroCostos($_POST['cmb_con_cos'],'control_costos','id_control_costos');
		$cuenta = obtenerDatosCentroCostos($_POST['cmb_cuenta'],'cuentas','id_cuentas');
		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		//Crear sentencia SQL
		$sql_stm = "SELECT rfc_empleado, id_empleados_empresa, CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre_empl, puesto 
					FROM empleados 
					WHERE id_control_costos = '$_POST[cmb_con_cos]' 
					AND id_cuentas = '$_POST[cmb_cuenta]' 
					AND estado_actual = 'ALTA'
					ORDER BY nombre_empl";
		$rs = mysql_query($sql_stm);
		$msg = "Asignar Bonos de Productividad del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em> </br>
				<em><u>$cc</u></em> - <em><u>$cuenta</u></em>";
		if($rs){
			if($datos=mysql_fetch_array($rs)){
				echo "				
				<table cellpadding='5' width='100%'>				
					<tr>
						<td colspan='9' align='center' class='titulo_etiqueta'>$msg</td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>ID EMPLEADO</td>
						<td class='nombres_columnas' align='center'>NOMBRE DEL TRABAJADOR</td>
						<td class='nombres_columnas' align='center'>BONO PRODUCTIVIDAD</td>
					</tr>";
				$nom_clase = "renglon_gris";
				$cont = 1;
				echo "<input type=hidden name='fecha_ini' id='fecha_ini' value='".modFecha($_POST['txt_fechaIni'],3)."'/>";
				echo "<input type=hidden name='fecha_fin' id='fecha_fin' value='".modFecha($_POST['txt_fechaFin'],3)."'/>";
				echo "<input type=hidden name='semana' id='semana' value='".$_POST['txt_semana']."'/>";
				echo "<input type=hidden name='centro_costos' id='centro_costos' value='".$_POST['cmb_con_cos']."'/>";
				echo "<input type=hidden name='cuenta' id='cuenta' value='".$_POST['cmb_cuenta']."'/>";
				do{
					echo "<input type=hidden name='rfc$cont' id='rfc$cont' value='".$datos['rfc_empleado']."'/>";
					echo "<input type=hidden name='puesto$cont' id='puesto$cont' value='".$datos['puesto']."'/>";
					echo "
					<tr>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase'>$datos[nombre_empl]</td>
						<td class='$nom_clase' align='center'>$";
						?>
							<input type="text" class="caja_de_num" id="txt_bono<?php echo $cont; ?>" name="txt_bono<?php echo $cont; ?>" size="20" maxlength="10" value="0.00" onkeypress="return permite(event,'num',2);" 
							onchange="formatCurrency(value,'txt_bono<?php echo $cont?>');"/>
						</td>
					</tr>
					<?php
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					if($cont%2==0)
						$nom_clase = "renglon_blanco";
					else
						$nom_clase = "renglon_gris";
				}while($datos=mysql_fetch_array($rs));
				echo "<input type=hidden name='num_reg' id='num_reg' value='$cont'/>";
				echo "
				</table>";
			}
		} else{
			$msg_error = "<label class='msje_correcto' align='center'>No hay empleados registrados con los parametros de busqueda</label>";
			echo $msg_error;
		}
	}
	
	function guardarBonoProductividad(){
		echo "<br><br><br><br><br><br><br><br><br>";
		$id = obtenerIdBonoProd();
		$fecha_ini = $_POST["fecha_ini"];
		$fecha_fin = $_POST["fecha_fin"];
		$fechaActual = date("Y-m-d");
		$semana = $_POST["semana"];
		$cc = $_POST["centro_costos"];
		$cuenta = $_POST["cuenta"];
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		$stm_sql = "INSERT INTO bono_prod (`id_bono`, `fecha_inicial`, `fecha_final`, `fecha_registro`, `semana`, `id_control_costos`, `id_cuentas`)
					VALUES ('$id','$fecha_ini','$fecha_fin','$fechaActual','$semana','$cc','$cuenta')";
		$rs = mysql_query($stm_sql);
		if($rs){
			$bandera = guardarDetalleBonoProductividad($id);
			if($bandera == 0){
				mysql_close($conn);
				registrarOperacion("bd_recursos","$id","RegistroBonosProductividad",$_SESSION['usr_reg']);?>
				<script type='text/javascript' language='javascript'>
					//Crear el Codigo Javascript para abrir la ventana emergente con el PDF del Pedido
					var codAbrirPedido = "window.open('../../includes/generadorPDF/bonoProd.php?id=<?php echo $id; ?>', '_blank', ";
					codAbrirPedido += "'top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')";				
					
					//Retrasar la apertura de la ventana 2 segundos
					setTimeout(codAbrirPedido,2000);
				</script><?php
				echo "<meta http-equiv='refresh' content='3;url=exito.php'>";
			} else{
				mysql_close($conn);
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		} else {
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	function guardarDetalleBonoProductividad($id_bono){
		$band = 0;
		$conn=conecta("bd_recursos");
		for($i=1; $i<$_POST['num_reg']; $i++){
			$rfc = $_POST["rfc".$i];
			$puesto = $_POST["puesto".$i];
			$bono = str_replace(",","",$_POST["txt_bono$i"]);
			$stm_sql = "INSERT INTO detalle_bono_prod (`id_bono`, `rfc_empleado`, `puesto`, `bono`)
						VALUES ('$id_bono','$rfc','$puesto','$bono')";
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$band = 1;
				mysql_query("DELETE FROM bono_prod WHERE id_bono = '$id_bono'");
				mysql_query("DELETE FROM detalle_bono_prod WHERE id_bono = '$id_bono'");
				$i=$_POST['num_reg'];
			}
		}
		return $band;
	}
	
	//Funcion que calcula el id para la bitacora de Gasolina
	function obtenerIdBonoProd(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_recursos");
		
		//Definir las tres letras en la Id de la Orden de Trabajo
		$id_cadena = "BON";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Orden de Trabajo Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_bono) AS cant FROM bono_prod WHERE id_bono LIKE 'BON$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la orden de trabajo registrada en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		
		return $id_cadena;
	}//Fin de obtenerIdBitacoraGasolina()
	
	function obtenerDatosCentroCostos($valor,$tabla,$busq){
		$conn_rec=conecta("bd_recursos");
		$dato="N/A";
		$sql_stm_rec="SELECT descripcion FROM $tabla WHERE $busq='$valor'";
		$rs_rec=mysql_query($sql_stm_rec);
		$datos_rec=mysql_fetch_array($rs_rec);
		if ($datos_rec[0]!="")
			$dato=$datos_rec[0];
		return $dato;
		mysql_close($conn_rec);
	}
?>