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
		$sql_stm = "SELECT T2.id_empleados_empresa, CONCAT( T2.nombre,  ' ', T2.ape_pat,  ' ', T2.ape_mat ) AS nombre_empl, T1.bono
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
						<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
						<td class='$nom_clase'>$datos[nombre_empl]</td>
						<td class='$nom_clase' align='right'>$".number_format($datos['bono'],2,".",",")."</td>
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
			}
		} else{
			$msg_error = "<label class='msje_correcto' align='center'>No hay empleados registrados con los parametros de busqueda</label>";
			echo $msg_error;
		}
	}
?>