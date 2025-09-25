<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 30/Marzo/2011
	  * Descripción: Este archivo contiene funciones para Consultar un Empleado de la BD de Recursos
	**/

	//Funcion que muestra uno o mas equipos segun el formulario frm_consultarEmpleado.php
	/*Valores de PATRON
		1 -> Nombre
		2 -> Todos
		3 -> Area
	*/
	function mostrarEmpleados($patron){
		$stm_sql="";
		//Verificamos bajo que patron se esta pidiendo hacer la consulta
		if ($patron==1){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$stm_sql="SELECT * FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[txt_nombre]' AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado <em>".$_POST["txt_nombre"]."</em>";
			echo "<input type='hidden' name='hdn_nombre' id='hdn_nombre' value='$_POST[txt_nombre]'/>";
		}
		if ($patron==2){
			//Creamos la sentencia SQL para mostrar los datoa de todos los empleados
			$stm_sql="SELECT * FROM empleados WHERE estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de Todos los Empleados";
		}
		if ($patron==3){
			//Creamos la sentencia SQL para mostrar los datos de los empleados que estan en el área que llega via POST
			$stm_sql="SELECT * FROM empleados WHERE area='$_POST[cmb_area]' AND estado_actual = 'ALTA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de los Empleados del &Aacute;rea <em><u>".$_POST["cmb_area"]."</u></em>";
			echo "<input type='hidden' name='hdn_area' id='hdn_area' value='$_POST[cmb_area]'/>";
		}
		if ($patron==4){
			//Creamos la sentencia SQL para mostrar los datos del empleado con el nombre que llega en el txt_nombre via POST
			$stm_sql="SELECT * FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_POST[txt_nombre_baja]' AND estado_actual = 'BAJA'";
			//Creamos el titulo de la tabla
			$titulo="Datos del Empleado <em>".$_POST["txt_nombre_baja"]."</em>";
			echo "<input type='hidden' name='hdn_nombre' id='hdn_nombre' value='$_POST[txt_nombre_baja]'/>";
		}
		if ($patron==5){
			//Creamos la sentencia SQL para mostrar los datoa de todos los empleados
			$stm_sql="SELECT * FROM empleados WHERE estado_actual = 'BAJA'";
			//Creamos el titulo de la tabla
			$titulo="Datos de Todos los Empleados Baja";
		}
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
			
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<br />";
			echo "<table class='tabla_frm' cellpadding='5' id='tabla-resultadosEmpleados'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>
				<thead>";
			echo "<br />";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>TODOS<input type='checkbox' name='ckbTodo' id='ckbTodo' value='TODO' onclick=\"checarTodos(this,'frm_exportarEmpleados')\"/></td>
					</tr>
			";
			echo "	<tr>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col1' id='ckb_col1' value='rfc_empleado' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col2' id='ckb_col2' value='curp' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col3' id='ckb_col3' value='id_empleados_empresa' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col4' id='ckb_col4' value='nombreCompleto' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col5' id='ckb_col5' value='no_ss' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col6' id='ckb_col6' value='antiguedad' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col7' id='ckb_col7' value='puesto' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col8' id='ckb_col8' value='area' onclick=\"desSeleccionar(this)\"/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' name='ckb_col9' id='ckb_col9' value='fechaNacimiento' onclick=\"desSeleccionar(this)\"/></td>
					</tr>
			";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>RFC</th>
						<th class='nombres_columnas' align='center'>CURP</th>
						<th class='nombres_columnas' align='center'>ID EMPRESA</th>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO SEGURO SOCIAL</td>
						<td class='nombres_columnas' align='center'>ANTIG&Uuml;EDAD</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<th class='nombres_columnas' align='center'>&Aacute;REA</th>
						<th class='nombres_columnas' align='center'>FECHA NACIMIENTO</th>
					</tr>
					</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;				
			echo "<tbody>";
			do{	
				echo "	<tr>					
							<td class='nombres_filas' align='center'>$datos[rfc_empleado]</td>
							<td class='$nom_clase' align='left'>$datos[curp]</td>
							<td class='$nom_clase' align='center'>$datos[id_empleados_empresa]</td>
							<td class='$nom_clase' align='center'>$datos[nombre] $datos[ape_pat] $datos[ape_mat]</td>
							<td class='$nom_clase' align='center'>$datos[no_ss]</td>
							<td class='$nom_clase' align='left'>".round((restarFechas($datos["fecha_ingreso"],date("Y-m-d"))/365),2)." a&ntilde;os</td>
							<td class='$nom_clase' align='left'>$datos[puesto]</td>
							<td class='$nom_clase' align='center'>$datos[area]</td>
							<td class='$nom_clase' align='center'>".modFecha(calcularFecha(substr($datos["rfc_empleado"],4,6)),2)."</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "</tbody>";	
			echo "</table>";
			echo "<input type='hidden' name='hdn_patron' id='hdn_patron' value='$patron'/>";
			return $stm_sql;
		}
		else{
			if ($patron==1){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[txt_nombre]</u></em></p>";
			}
			if ($patron==2){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados</p>";
			}
			if ($patron==3){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[cmb_area]</u></em></p>";
			}
			if ($patron==4){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados con el Nombre <em><u>$_POST[txt_nombre_baja]</u></em></p>";
			}
			if ($patron==5){
				echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No hay Trabajadores Registrados</p>";
			}
			return "";
		}

		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarEmpleados
?>