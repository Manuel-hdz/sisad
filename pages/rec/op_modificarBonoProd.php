<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martinez Fernandez
	  * Fecha: 18/Abril/2011
	  * Descripción: Permite generar reportes de asistencia de los empleados 
	**/
	
	function mostrarEmpleadosBonoProd(){
		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		//Crear sentencia SQL
		$sql_stm = "SELECT T2.id_empleados_empresa, CONCAT( T2.nombre,  ' ', T2.ape_pat,  ' ', T2.ape_mat ) AS nombre_empl, T1.bono, T1.rfc_empleado
					FROM detalle_bono_prod AS T1
					JOIN empleados AS T2
					USING ( rfc_empleado ) 
					WHERE id_bono =  '$_POST[cmb_bono]'";
		$rs = mysql_query($sql_stm);
		$msg = "Bonos de Productividad del <em><u>$_POST[txt_fechaIni]</u></em> al <em><u>$_POST[txt_fechaFin]</u></em>";
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
				do{
					echo "
					<tr>
						<input type='hidden' value='$datos[rfc_empleado]' name='rfcs$cont' id='rfcs$cont'/>
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase'>$datos[nombre_empl]</td>
						<td class='$nom_clase' align='center'>$";
						?>
							<input type="text" class="caja_de_num" id="txt_bono<?php echo $cont; ?>" name="txt_bono<?php echo $cont; ?>" size="20" maxlength="10" 
							value="<?php echo number_format($datos['bono'],2,".",","); ?>" onkeypress="return permite(event,'num',2);" 
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
				echo "
				<input type='hidden' value='$cont' name='num_bonos' id='num_bonos'/>
				<input type='hidden' value='$_POST[cmb_bono]' name='id_bonos' id='id_bonos'/>
				</table>";
			}
		} else{
			$msg_error = "<label class='msje_correcto' align='center'>No hay empleados registrados con los parametros de busqueda</label>";
			echo $msg_error;
		}
	}
	
	function modificarDetalleBono(){
		$con = conecta("bd_recursos");
		$id = $_POST["id_bonos"];
		$num_bonos = $_POST["num_bonos"];
		$aux = 0;
		for($i=0; $i<$num_bonos; $i++){
			$rfc = $_POST["rfcs".$i];
			$bono = str_replace(",","",$_POST["txt_bono$i"]);
			$upd_sql = "UPDATE detalle_bono_prod SET bono='$bono' WHERE rfc_empleado='$rfc' AND id_bono='$id'";
			$rs = mysql_query($upd_sql);
			if(!$rs){
				$aux = 1;
			}
		}
		if($aux == 1){
			mysql_close($conn);
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		} else{
			registrarOperacion("bd_recursos","$id","ModificarBonosProductividad",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='3;url=exito.php'>";
		}
	}
?>