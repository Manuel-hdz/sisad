<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 10/Febrero/2012
	  * Descripción: Este archivo permite consultar la información relacionada con las actas de seguridad e higiene
	  **/
	 	
	//Funcion que permite mostrar las Actas regisatradas  	
	function mostrarActaSH(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		if(isset($_GET['cmb_id'])){
			$_POST['cmb_id'] = $_GET['cmb_id'];
		}
		
		if(isset($_GET['txt_fechaIni'])){
			$_POST['txt_fechaIni'] = $_GET['txt_fechaIni'];
			$_POST['txt_fechaFin'] = $_GET['txt_fechaFin'];
		}
		
		//Si viene definido en el post quiere decir que se consulto por fecha
		if(isset($_POST['txt_fechaIni'])){
			//Modificamos las fechas para poder realizar la consulta en la base de datos
			$fechaIni = modFecha($_POST['txt_fechaIni'],3);
			$fechaFin = modFecha($_POST['txt_fechaFin'],3);
			//Creamos la variable que contendra el radio; esto para poder regresar a ver los elementos seleccionados
			$datosConsulta = $fechaIni.".".$fechaFin;
			
			//Creamos la sentencia SQL correspondiente a las fechas
			$stm_sql ="SELECT *	FROM acta_comision WHERE fecha_registro>='$fechaIni'  AND fecha_registro<='$fechaFin' ORDER BY id_acta_comision";
			
			//Variable para almacenar el titulo
			$titulo= "ACTAS DE SEGURIDAD E HIGIENE REGISTRADAS DE <em><u>".$_POST['txt_fechaIni']."</u></em> AL <em><u>".$_POST['txt_fechaFin']."</u></em>";
			
			//Variable para almacenar el mensaje en caso de no encontrar Resultados
			$noTitulo= "<label class='msje_correcto'>NO EXISTEN ACTAS DE SEGURIDAD E HIGIENE REGISTRADAS DE ".$_POST['txt_fechaIni']." AL ".$_POST['txt_fechaFin']."</label>";
		}
		//De lo contrario se selecciono el id de la Acta de Seguridad e Higiene
		else{
			//Cremos la consulta
			$stm_sql ="SELECT *	FROM acta_comision WHERE id_acta_comision='$_POST[cmb_id]' ORDER BY id_acta_comision";
			
			//Variable para almacenar el titulo
			$titulo= "ACTAS DE SEGURIDAD E HIGIENE REGISTRADAS CON CLAVE <em><u>".$_POST['cmb_id']."</u></em>";
			
			//Variable para almacenar el titulo
			$noTitulo= "<label class='msje_correcto'>NO EXISTEN ACTAS DE SEGURIDAD E HIGIENE REGISTRADAS CON CLAVE <strong>".$_POST['cmb_id']."</strong></label>";
			
			$datosConsulta = $_POST['cmb_id'];
		}
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>$titulo</caption>	";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'>CLAVE ACTA</td>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
						<td class='nombres_columnas' align='center'>PERIODO INICIO</td>
						<td class='nombres_columnas' align='center'>PERIODO FIN</td>
						<td class='nombres_columnas' align='center'>TIPO VERIFICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>HORA INICIO</td>
						<td class='nombres_columnas' align='center'>HORA FIN</td>
						<td class='nombres_columnas' align='center'>FECHA PR&Oacute;XIMA REUNI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>NOMBRE REPRESENTANTE</td>
						<td class='nombres_columnas' align='center'>NOMBRE GERENTE</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{			
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas' align='center'>
						<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_acta_comision].$datosConsulta'
						onclick='document.frm_consultarActa.submit();'/>
					</td>
					<td class='$nom_clase'>$datos[id_acta_comision]</td>"; 
				echo "<td class='$nom_clase'>".modFecha($datos['fecha_registro'],1)."</td>
     		          <td class='$nom_clase'>".modFecha($datos['periodo_ini'],1)."</td>
					  <td class='$nom_clase'>".modFecha($datos['periodo_fin'],1)."</td>
					  <td class='$nom_clase'>$datos[tipo_verificacion]</td>
					  <td class='$nom_clase'>$datos[hora_ini]</td>
					  <td class='$nom_clase'>$datos[hora_fin]</td>
					  <td class='$nom_clase'>".modFecha($datos['fecha_prox'],1)."</td>
					  <td class='$nom_clase'>$datos[nom_representante]</td>
					  <td class='$nom_clase'>$datos[nom_gerente]</td>";?>
					   <td class="<?php echo $nom_clase; ?>" align="center"><input type="button" name="btn_Archivo2" class="botones" value="Descripci&oacute;n" onmouseover="window.estatus='';return true" 
								title="Ver Descripci&oacute;n" 
								onclick="javascript:window.open('verDescripcionActaSH.php?id_registro=<?php echo $datos['id_acta_comision'];?>',
								'_blank','top=300, left=450, width=560, height=250, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/></td>
			<?php 
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
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
	
	//Funcion que permite mostrar las Actas regisatradas  	
	function verDetalleActaSH($clave){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Partimos la clave para tomar solo el id
		$claveSec = explode(".",$clave);
		$clave = $claveSec[0];
		
		//Creamos la sentencia SQL correspondiente a las fechas
		$stm_sql ="SELECT *	FROM acta_comision WHERE id_acta_comision= '$clave'";
		
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>ACTAS DE SEGURIDAD E HIGIENE NO. ".$clave."</caption>	";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>FECHA REGISTRO</td>
						<td class='nombres_columnas' align='center'>PERIODO INICIO</td>
						<td class='nombres_columnas' align='center'>PERIODO FIN</td>
						<td class='nombres_columnas' align='center'>TIPO VERIFICACI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>HORA INICIO</td>
						<td class='nombres_columnas' align='center'>HORA FIN</td>
						<td class='nombres_columnas' align='center'>FECHA PR&Oacute;XIMA REUNI&Oacute;N</td>
						<td class='nombres_columnas' align='center'>NOMBRE REPRESENTANTE</td>
						<td class='nombres_columnas' align='center'>NOMBRE GERENTE</td>
						<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{			
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>";
				echo "<td class='$nom_clase'>".modFecha($datos['fecha_registro'],1)."</td>
     		          <td class='$nom_clase'>".modFecha($datos['periodo_ini'],1)."</td>
					  <td class='$nom_clase'>".modFecha($datos['periodo_fin'],1)."</td>
					  <td class='$nom_clase'>$datos[tipo_verificacion]</td>
					  <td class='$nom_clase'>$datos[hora_ini]</td>
					  <td class='$nom_clase'>$datos[hora_fin]</td>
					  <td class='$nom_clase'>".modFecha($datos['fecha_prox'],1)."</td>
					  <td class='$nom_clase'>$datos[nom_representante]</td>
					  <td class='$nom_clase'>$datos[nom_gerente]</td>";?>
					   <td class="<?php echo $nom_clase; ?>" align="center">
								<input type="button" name="btn_Archivo" class="botones" value="Descripci&oacute;n" onMouseOver="window.estatus='';return true" 
								title="Ver Descripci&oacute;n" 
								onClick="javascript:window.open('verDescripcionActaSH.php?id_registro=<?php echo $datos['id_acta_comision'];?>',
								'_blank','top=300, left=450, width=560, height=250, status=no, menubar=no, resizable=no, scrollbars=yes, toolbar=no, location=no, directories=no');"/>							
			</td><?php 
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>"; 			
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Actas de Seguridad e Higiene Registrados </label>";
		}	?>
			</div>
			 <div id="botonesConsultas" align="center">	
        	<table align="center">
        	<tr>
            	<td>
					<input name="sbt_accidentes" type="submit" class="botones" value="Accidentes" onMouseOver="window.estatus='';return true" 
                	title="Consulta de Accidentes Registrados" />
					&nbsp;&nbsp;
					<input name="sbt_agenda" type="submit"  onclick=""class="botones" value="Agenda" onMouseOver="window.estatus='';return true" 
                	title="Consulta de Puntos Tratados en la Agenda"/>
					&nbsp;&nbsp;
					<input name="sbt_recorridos" type="submit" class="botones" value="Recorridos" onMouseOver="window.estatus='';return true" 
                	title="Consulta de Recorridos Registrados"/>
					&nbsp;&nbsp;
					<input name="sbt_areas" type="submit" class="botones" value="&Aacute;reas Visitadas" onMouseOver="window.estatus='';return true" 
					title="Consulta de &Aacute;reas Visitadas Registradas"/>
					&nbsp;&nbsp;
					<input name="sbt_asistentes" type="submit" class="botones" value="Asistentes" onMouseOver="window.estatus='';return true" 
					title="Consulta de Asistentes Registrados"/>
					&nbsp;&nbsp;
					<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" onMouseOver="window.estatus='';return true" 
					title="Ver PDF Acta Seguridad Higiene"  onclick="window.open('../../includes/generadorPDF/actaSH.php?idActa=<?php echo $clave;?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=no, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>	<?php				
					//Particionamos el post con el radio para tomar iunicamete el valor del id
					$claveSecc=explode(".",$_POST['rdb_id']);
					$totalElementos = count($claveSecc);?>
					<?php if($totalElementos==2){?>
						&nbsp;&nbsp;					
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otra Acta" 
						onMouseOver="window.status='';return true" 
						onclick="location.href='frm_consultarActaSeguridadHigiene2.php?cmb_id=<?php echo $claveSecc[1]; ?>&sbt_consultar=sbt_consultar'"  />	
					<?php } else if($totalElementos==3){?>	
						&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otra Acta" 
						onMouseOver="window.status='';return true" 
						onclick="location.href='frm_consultarActaSeguridadHigiene2.php?txt_fechaIni=<?php echo modFecha($claveSecc[1],1);?>&txt_fechaFin=<?php echo modFecha($claveSecc[2],1);?>&sbt_consultar=sbt_consultar'"  />
					<?php  }?>
				</td>
            </tr>
        </table>
        </div>	
				
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
			
			
	//Esta función se encarga de mostrar el detalle de los Accidentes al presionar El boton referente al mismo
	function mostrarDetalleAccidentes($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		$stm_sql = "SELECT DISTINCT * FROM (accidentes JOIN acta_comision ON acta_comision_id_acta_comision=id_acta_comision) 
					WHERE acta_comision_id_acta_comision='$clave' ";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'> ACCIDENTES INVESTIGADOS EN LA ACTA NO. <em>".$clave."</em></caption>					
				<tr>
				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>FECHA ACCIDENTE</td>
					<td class='nombres_columnas'>NOMBRE ACCIDENTE</td>
					<td class='nombres_columnas'>CAUSA ACCIDENTE</td>
					<td class='nombres_columnas'>ACCIONES PREVENTIVAS</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase' align='left'>".modFecha($datos['fecha_acc'],1)."</td>
						<td class='$nom_clase' align='left'>$datos[nom_acc]</td>
						<td class='$nom_clase' align='left'>$datos[causa_acc]</td>
						<td class='$nom_clase' align='left'>$datos[acciones_prev]</td>
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
		else{
			echo "<label class='msje_correcto'>NO EXISTEN ACCIDENTES REGISTRADOS</label>";
		}		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarDetalleAccidentes($clave)		
	
	//Esta función se encarga de mostrar el detalle de los puntos tratados en la agenda al presionar El boton referente al mismo
	function mostrarDetalleAgenda($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT DISTINCT * FROM (puntos_agenda JOIN acta_comision ON acta_comision_id_acta_comision=id_acta_comision) 
					WHERE acta_comision_id_acta_comision='$clave' ";
		//Ejecumtamos la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Comprobamos la existencia de datos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'> DETALLES DE LOS PUNTOS TOMADOS EN LA AGENDA DE LA ACTA NO. <em>".$clave."</em></caption>					
				<tr>
				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>PUNTO ACORDADO</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase' align='left'>$datos[punto_acordado]</td>
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
		else{
			echo "<label class='msje_correcto'>NO EXISTEN PUNTOS EN LA AGENDA REGISTRADOS</label>";
		}		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarDetalleAgenda($clave)		
	
	
	//Esta función se encarga de mostrar el detalle de los Recorridos al presionar El boton referente al mismo
	function mostrarDetalleRecorridos($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT DISTINCT * FROM (recorridos_verificacion JOIN acta_comision ON acta_comision_id_acta_comision=id_acta_comision) 
					WHERE acta_comision_id_acta_comision='$clave' ";
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		//Comprobamos que existan datos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'> RECORRIDOS DE VERIFICACI&Oacute;N REGISTRADOS EN LA ACTA NO. <em>".$clave."</em></caption>					
				<tr>
				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>ACTO INSEGURO</td>
					<td class='nombres_columnas'>RESPONSABLE</td>
					<td class='nombres_columnas'>FECHA L&Iacute;MITE</td>
					<td class='nombres_columnas'>FECHA CUMPLIDA</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase' align='left'>$datos[acto_inseguro]</td>
						<td class='$nom_clase' align='left'>$datos[responsable]</td>
						<td class='$nom_clase' align='left'>".modFecha($datos['fecha_limite'],1)."</td>
						<td class='$nom_clase' align='left'>".modFecha($datos['fecha_cumplida'],1)."</td>
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
		else{
			echo "<label class='msje_correcto'>NO EXISTEN RECORRIDOS REGISTRADOS</label>";
		}			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarDetalleRecorridos($clave)	
	
	//Funcion que nos permitira m ostrar el detalle de las areas visitadas presionando el boton que hace referencia a dicho concepto
	function mostrarDetallAreasVisitadas($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT DISTINCT * FROM (areas_visitadas JOIN acta_comision ON acta_comision_id_acta_comision=id_acta_comision) 
					WHERE acta_comision_id_acta_comision='$clave' ";
		
		//Ejecutamos la sentencia
		$rs = mysql_query($stm_sql);
		
		//Comprobamos la existencia de datos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'> &Aacute;REAS VUSITADAS CON REGISTRO EN ACTA NO. <em>".$clave."</em></caption>					
				<tr>
				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>&Aacute;REA VISITADA</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase' align='left'>$datos[area_visitada]</td>
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
		else{
			echo "<label class='msje_correcto'>NO EXISTEN ÁREAS REGISTRADAS</label>";
		}			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarDetalleRecorridos($clave)	
	
	
	//Funcion que permite mostrar el detalle del regisatro de los asistentes
	function mostrarDetallAsistentes($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		$stm_sql = "SELECT DISTINCT * FROM (asistentes JOIN acta_comision ON acta_comision_id_acta_comision=id_acta_comision) 
					WHERE acta_comision_id_acta_comision='$clave' ";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'> ASISTENTES CON PARTICIPACI&Oacute;N EN ACTA NO. <em>".$clave."</em></caption>					
				<tr>
				
					<td class='nombres_columnas'>NO.</td>
					<td class='nombres_columnas'>NOMBRE ASISTENTE</td>
					<td class='nombres_columnas'>PUESTO ASISTENTE</td>
				</tr>";
				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
				echo "<tr>		
						<td class='nombres_filas'>$cont</td>
						<td class='$nom_clase' align='left'>$datos[nom_asistente]</td>
						<td class='$nom_clase' align='left'>$datos[puesto_asistente]</td>
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
		else{
			echo "<label class='msje_correcto'>NO EXISTEN ASISTENTES REGISTRADOS</label>";
		}			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarDetallAsistentes($clave)	
?>