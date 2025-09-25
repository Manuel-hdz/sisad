<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 05/Abril/2011
	  * Descripción: Este archivo permite mostrar los empleados registrados en la nómina bancaria
	**/
	
	//funcion que permita consultar los empleados registrados en la nomina bancaria
	function mostrarNominaBancaria(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		
		//Variable para verificar si hubo rRS en la consulta y poder mostrar los botones
		$flag=0;
		
		//Tomamos las variables del post	
		$area=$_POST["cmb_area"];
		$fechaIni=modFecha($_POST["txt_fechaIni"],3);
		$fechaFin=modFecha($_POST["txt_fechaFin"],3);		
			
		//Creamos la sentencia sql
		$stm_sql="SELECT rfc_trabajador, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, no_cta, fecha_insercion, neto_pagar 
			FROM (nomina_bancaria JOIN empleados ON rfc_trabajador=rfc_empleado)WHERE fecha_insercion>='$fechaIni' AND fecha_insercion<='$fechaFin'
			AND area='$area'";
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		//Declaramos el titulo para mostrar en el encabezado
		$titulo="Empleados Registrados en la N&oacute;mina Bancaria de <u><em>".$_POST["txt_fechaIni"]."</u></em> a <u><em>".$_POST["txt_fechaFin"]."</u></em> &Aacute;rea <u><em>".$area."</u></em>";
		
		if($datos=mysql_fetch_array($rs)){
		//Guardamos las fechas para exportar el archivo de texto con los datos de la nómina
		$flag=1;
			echo "<table class='tabla_frm' width='100%'>";
			echo "<caption class='titulo_etiqueta'>$titulo</caption>
					<tr>";?>
						<td align='left' colspan="6" class='nombres_columnas'>
						<input name='ckbTodo' align='center'  type='checkbox' id='ckbTodo' onclick="checarTodosNB(this,'frm_resultadosNomina');" value='Todos'/>SELECCIONAR TODOS	</td>					
					</tr>
		<?php echo" <tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR </td>
						<td class='nombres_columnas' align='center'>RFC </td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>NO. CUENTA</td>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
						<td class='nombres_columnas' align='center'>SALARIO</td>
					</tr>
					</tdead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Variable para guardar el total de nomina
			$total_nomina=0;	
			do{								
			echo "	<tr>";?>
						<td class='nombres_filas' align='center'>
							<input type="checkbox" id"ckb_emp<?php echo $cont;?>" name="ckb_emp<?php echo $cont;?>" onclick="desSeleccionarNB(this);"
							value="<?php echo $datos['rfc_trabajador']?>"/>
						</td>
			<?php echo "
						<td class='$nom_clase' align='center'>$datos[rfc_trabajador]</td>
						<td class='$nom_clase' align='center'>$datos[nombre]</td>
						<td class='$nom_clase' align='center'>$datos[no_cta]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_insercion'],1)."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["neto_pagar"],2,".",",")."</td>
					</tr>";
				//Sumar el monto de la nomina para obtener el total
				$total_nomina += $datos['neto_pagar'];						
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "<input type='hidden' name='hdn_cant' id='hdn_cant' value='$cont'/>";
			echo "
					<td colspan='5'>&nbsp;</td><td align='center' class='nombres_columnas'>$".number_format($total_nomina,2,".",",")."</td>
				</table>";		
			return 1;
		}
		else{	
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No se Encontraron Empleados Registrados en la N&oacute;mina Bancaria de <u><em>".$_POST["txt_fechaIni"]."</u></em> a <u><em>".$_POST["txt_fechaFin"]."</u></em> &Aacute;rea <u><em>".$area."</u></em></p>";
			return 0;
		} 
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarNomina _Bancaria

?>
