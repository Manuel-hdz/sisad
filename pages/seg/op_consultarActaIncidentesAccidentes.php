<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 06/Marzo/2012
	  * Descripción: Este archivo permite consultar la información relacionada con los informes de incidentes accidentes
	  **/
	 	
	//Funcion que permite mostrar las Actas regisatradas  	
	function mostrarInforme(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
	
	
		//Si viene definido en el post quiere decir que se consulto por fecha
		if(isset($_POST['txt_fechaIni'])){
			//Modificamos las fechas para poder realizar la consulta en la base de datos
			$fechaIni = modFecha($_POST['txt_fechaIni'],3);
			$fechaFin = modFecha($_POST['txt_fechaFin'],3);
			
			//Creamos la sentencia SQL correspondiente a las fechas
			$stm_sql ="SELECT *	FROM accidentes_incidentes WHERE fecha_accidente>='$fechaIni'  AND fecha_accidente<='$fechaFin' ORDER BY id_informe";
			
			//Titulo
			$titulo = "<label class='msje_correcto'>No existen Informes de Accidentes e Incidentes Registrados del $_POST[txt_fechaIni] a $_POST[txt_fechaFin]</label>";
		}
		//De lo contrario se selecciono el id de la Acta de Seguridad e Higiene
		else{
			//Cremos la consulta
			$stm_sql ="SELECT *	FROM accidentes_incidentes WHERE tipo_informe = '$_POST[cmb_tipo]' ORDER BY id_informe";
			
			//Titulo
			$titulo = "<label class='msje_correcto'>  No existen Informes de Accidentes e Incidentes Registrados de Tipo $_POST[cmb_tipo]</label>";
		}
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosDocumentos'> 
				<thead>";
			echo "	<tr>
						<th class='nombres_columnas' align='center'>NO. ACCIDENTE</th>
						<th class='nombres_columnas' align='center'>CLAVE INFORME</th>
						<th class='nombres_columnas' align='center'>EMPLEADO</th>
						<th class='nombres_columnas' align='center'>&Aacute;REA DE TRABAJO</th>
						<th class='nombres_columnas' align='center'>PUESTO</th>
						<th class='nombres_columnas' align='center'>TIPO DE INFORME</th>
						<td class='nombres_columnas' align='center'>PDF</td>
						<td class='nombres_columnas' align='center'>ACCIONES</td>
					</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";	
			do{			
				$nombreEmpleado = obtenerNombreEmpleado($datos['empleados_rfc_empleado']);
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					 <td class='$nom_clase'>$datos[num_total_acci]</td>
					 <td class='$nom_clase'>$datos[id_informe]</td>
					 <td class='$nom_clase'>$nombreEmpleado</td>
					 <td class='$nom_clase'>$datos[area]</td>
					 <td class='$nom_clase'>$datos[puesto]</td>
					 <td class='$nom_clase'>$datos[tipo_informe]</td>";?>
					   <td class="<?php echo $nom_clase; ?>" align="center"><input type="button" name="btn_pdf"  id="btn_pdf" class="botones" 
					   		value="Ver PDF" onmouseover="window.estatus='';return true" 
							title="Ver PDF" 
							onclick="javascript:window.open('../../includes/generadorPDF/informeIncAcc.php?id_registro=<?php echo $datos['id_informe'];?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no');"/></td>
						<td class="<?php echo $nom_clase; ?>" align="center"><input type="button" name="btn_acciones"  id="btn_acciones" class="botones" 
					   		value="Acciones" onmouseover="window.estatus='';return true" 
							title="Ver Acciones Preventivas/Correctivas" 
							onclick="javascript:window.open('verAcciones.php?id_registro=<?php echo $datos['id_informe'];?>',
							'_blank','top=300, left=450, width=560, height=250, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/></td>
			<?php 
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</tbody>";
			echo "</table>";	
			return 1;
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo $titulo;
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion

	
	//Funcion que permite mostrar el detalle del regisatro de las acciones preventivas
	function mostrarDetalleAcciones($clave){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		$stm_sql = "SELECT DISTINCT * FROM acciones_pre_corr  WHERE accidentes_incidentes_id_informe='$clave' ";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'> ACCIONES PREVENTIVAS Y CORRECTIVAS REGISTRADAS INFORME NO. <em>".$clave."</em></caption>					
				<tr>
				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>ACCI&Oacute;N</td>
					<td class='nombres_columnas'>FECHA</td>
					<td class='nombres_columnas'>RESPONSABLE</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase' align='left'>$datos[accion]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos['fecha'],1)."</td>
						<td class='$nom_clase' align='left'>$datos[responsable]</td>
					</tr>";									
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));

			echo "</table>";
		}		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarDetallAsistentes($clave)	
?>