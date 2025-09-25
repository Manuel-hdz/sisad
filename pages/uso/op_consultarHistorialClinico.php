<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional                                               
	  * Nombre Programador:
	  * Fecha: 10/Febrero/2012
	  * Descripción: Este archivo permite consultar la información relacionada con los Historiales Clinicos
	  **/
	 	
	//Funcion que permite mostrar los historiales medicos registrados dentro del sistema	
	function mostrarHistorialClinico(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		if(isset($_GET['cmb_tipoClasificacion'])){
			$_POST['cmb_tipoClasificacion'] = $_GET['cmb_tipoClasificacion'];
		}
		
		if(isset($_GET['txt_fechaIni'])){
			$_POST['txt_fechaIni'] = $_GET['txt_fechaIni'];
			$_POST['txt_fechaFin'] = $_GET['txt_fechaFin'];
		}
		if(isset($_GET['txt_fechaTipoIni'])){
			$_POST['txt_fechaTipoIni'] = $_GET['txt_fechaTipoIni'];
			$_POST['txt_fechaTipoFin'] = $_GET['txt_fechaTipoFin'];
		}
		//Si viene definido en el post quiere decir que se consulto por fecha
		//if(isset($_POST['txt_fechaIni'])){
		if(isset($_POST['cmb_tipoClasificacion'])=="INTERNO"){
			//Modificamos las fechas para poder realizar la consulta en la base de datos
			$fechaTipoIni = modFecha($_POST['txt_fechaTipoIni'],3);
			$fechaTipoFin = modFecha($_POST['txt_fechaTipoFin'],3);
			//Creamos la variable que contendra el radio; esto para poder regresar a ver los elementos seleccionados
			$datosConsulta = $fechaTipoIni.".".$fechaTipoFin;
			
			//Creamos la sentencia SQL correspondiente a las fechas
			$stm_sql ="SELECT * FROM historial_clinico WHERE tipo_clasificacion = '$_POST[cmb_tipoClasificacion]' AND fecha_exp>='$fechaTipoIni'  
			AND fecha_exp<='$fechaTipoFin' ORDER BY id_historial";
			
			//Variable para almacenar el titulo
			$titulo= "HISTORIALES CLINICOS DE TIPO  <em><u>".$_POST['cmb_tipoClasificacion']."</u></em> DEL <em><u>".$_POST['txt_fechaTipoIni']."</u></em> 
			AL <em><u>".$_POST['txt_fechaTipoFin']."</u></em>";			
			
			//Variable para almacenar el mensaje en caso de no encontrar Resultados
			$noTitulo= "<label class='msje_correcto'>NO EXISTEN HISTORIALES CLINICOS DEL ".$_POST['txt_fechaTipoIni']." AL ".$_POST['txt_fechaTipoFin']."</label>";
		}
		//De lo contrario se selecciono el id de la Acta de Seguridad e Higiene
		else if(isset($_POST['cmb_tipoClasificacion'])=="EXTERNO"){	
			//Cremos la consulta
			$stm_sql ="SELECT * FROM historial_clinico WHERE tipo_clasificacion = '$_POST[cmb_tipoClasificacion]' AND fecha_exp>='$fechaTipoIni'  
			AND fecha_exp<='$fechaTipoFin' ORDER BY id_historial";
			
			//Variable para almacenar el titulo
			$titulo= "HISTORIALES CLINICOS DE TIPO  <em><u>".$_POST['cmb_tipoClasificacion']."</u></em> DEL <em><u>".$_POST['fechaTipoIni']."</u></em> AL <em><u>".$_POST['fechaTipoFin']."</u></em>";

			//Variable para almacenar el titulo
			$noTitulo= "<label class='msje_correcto'>NO EXISTEN HISTORIALES CLINICOS DEL <strong>".$_POST['cmb_tipoClasificacion']."</strong></label>";
			
			//$datosConsulta = $_POST['cmb_tipoClasificacion'];

		}
		else if(isset($_POST['txt_fechaIni'])){
			//Modificamos las fechas para poder realizar la consulta en la base de datos
			$fechaIni = modFecha($_POST['txt_fechaIni'],3);
			$fechaFin = modFecha($_POST['txt_fechaFin'],3);
			//Creamos la variable que contendra el radio; esto para poder regresar a ver los elementos seleccionados
			$datosConsulta = $fechaIni.".".$fechaFin;
			
			//Creamos la sentencia SQL correspondiente a las fechas
			$stm_sql ="SELECT * FROM historial_clinico WHERE fecha_exp>='$fechaIni'  AND fecha_exp<='$fechaFin' ORDER BY id_historial";
			
			//Variable para almacenar el titulo
			$titulo= "HISTORIALES CLINICOS <em><u>".$_POST['txt_fechaIni']."</u></em> AL <em><u>".$_POST['txt_fechaFin']."</u></em>";
			
			//Variable para almacenar el mensaje en caso de no encontrar Resultados
			$noTitulo= "<label class='msje_correcto'>NO EXISTEN HISTORIALES CLINICOS DEL ".$_POST['txt_fechaIni']." AL ".$_POST['txt_fechaFin']."</label>";
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
						<td class='nombres_columnas' align='center'>CLAVE HISTORIAL</td>
						<td class='nombres_columnas' align='center'>CLASIFICACI&Oacute;N EXAMEN</td>
						<td class='nombres_columnas' align='center'>NOMBRE</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>EMPRESA</td>
						<td class='nombres_columnas' align='center'>FECHA EXPEDIENTE</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{			
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>
					<td class='nombres_filas' align='center'>
						<input type='radio' id='rdb_id' name='rdb_id' value='$datos[id_historial].$datosConsulta'
						onclick='document.frm_consultarExamen.submit();'/>
					</td>
					<td class='$nom_clase'>$datos[id_historial]</td>"; 
				echo "
					  <td class='$nom_clase'>$datos[clasificacion_exa]</td>
					  <td class='$nom_clase'>$datos[nom_empleado]</td>	
					  <td class='$nom_clase'>$datos[puesto_realizar]</td>			
					  <td class='$nom_clase'>$datos[nom_empresa]</td>					  		  				  
					  <td class='$nom_clase'>".modFecha($datos['fecha_exp'],1)."</td>";?>
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
			echo $noTitulo;
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	//Funcion que permite mostrar el historial medico	
	function verDetalleHistorialClinico($clave){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		//Partimos la clave para tomar solo el id
		$claveSec = explode(".",$clave);
		$clave = $claveSec[0];
		
		//Creamos la sentencia SQL correspondiente a las fechas
		$stm_sql ="SELECT *	FROM historial_clinico WHERE id_historial= '$clave'";
			
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>
					<caption class='titulo_etiqueta'>HISTORIAL CL&Iacute;NICO NO. ".$clave."</caption>	";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>CLASIFICACI&Oacute;N EXAMEN</td>
						<td class='nombres_columnas' align='center'>NOMBRE EMPRESA</td>
						<td class='nombres_columnas' align='center'>ID EMPLEADOS</td>
						<td class='nombres_columnas' align='center'>NOMBRE EMPLEADO</td>
						<td class='nombres_columnas' align='center'>PUESTO</td>
						<td class='nombres_columnas' align='center'>NSS</td>
						<td class='nombres_columnas' align='center'>FECHA EXPEDIENTE</td>
						<td class='nombres_columnas' align='center'>SEXO</td>
						<td class='nombres_columnas' align='center'>EDAD</td>
						<td class='nombres_columnas' align='center'>FECHA NACIMIENTO</td>
						<td class='nombres_columnas' align='center'>EDO. CIVIL</td>																		
						<td colspan='2' class='nombres_columnas' align='center'>DOMICILIO</td>						
						<td colspan='2' class='nombres_columnas' align='center'>RESIDEN EN</td>
						<td colspan='2' class='nombres_columnas' align='center'>ORIGINARIO</td>
						<td class='nombres_columnas' align='center'>ESCOLARIDAD</td>						
						<td class='nombres_columnas' align='center'>CLAVE ESCOLARIDAD</td>						
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{			
				//Mostrar todos los registros que han sido completados
				echo "
				<tr>";	
				echo "
					  <td class='$nom_clase'>$datos[clasificacion_exa]</td>
					  <td class='$nom_clase'>$datos[nom_empresa]</td>					  
					  <td class='$nom_clase'>$datos[id_empleados_empresa]</td>
					  <td class='$nom_clase'>$datos[nom_empleado]</td>
					  <td class='$nom_clase'>$datos[puesto_realizar]</td>
					  <td class='$nom_clase'>$datos[num_afiliacion]</td>
					  <td class='$nom_clase'>".modFecha($datos['fecha_exp'],1)."</td>
					  <td class='$nom_clase'>$datos[sexo]</td>
					  <td class='$nom_clase'>$datos[edad]</td>
					  <td class='$nom_clase'>".modFecha($datos['fecha_nac'],1)."</td>
					  <td class='$nom_clase'>$datos[edo_civil]</td>
					  <td colspan='2' class='$nom_clase'>$datos[domicilio]</td>
					  <td colspan='2' class='$nom_clase'>$datos[reside_en]</td>
					  <td colspan='2'  class='$nom_clase'>$datos[originario_de]</td>
					  <td class='$nom_clase'>$datos[escolaridad]</td>
  					  <td class='$nom_clase'>$datos[clave_escolaridad]</td>"; 
			echo "</tr>";
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>"; 			
		}
		else{
			//Si no se encuentra ningun resultado desplegar un mensaje					
			echo "<label class='msje_correcto'>  No existen Historiales Clinicos Registrados </label>";
		}	?>
			</div>
			 <div id="botonesConsultas" align="center">	
        	<table align="center">
        	<tr>
            	<td align="center">
					<input name="sbt_historialFam" type="submit" class="botones" value="Antecedentes" onMouseOver="window.estatus='';return true" 
                	title="Consultar los Antecendentes Familiares del Trabajador" />
					&nbsp;
					<input name="sbt_aspGrales1" type="submit"  onclick="" class="botones" value="Asp. Grales/1" onMouseOver="window.estatus='';return true" 
                	title="Consultar los Aspectos Generales/1 del Trabajador"/>
					&nbsp;
					<input name="sbt_aspGrales2" type="submit" class="botones" value="Asp. Grales/2" onMouseOver="window.estatus='';return true" 
                	title="Consulta los Aspectos Generales/2 del Trabajador"/>
					&nbsp;
					<input name="sbt_antPato" type="submit" class="botones" value="Ant. Patologicos" onMouseOver="window.estatus='';return true" 
					title="Consultar los Antecedentes No Patologicos"/>
					&nbsp;
					<input name="sbt_hisTrab" type="submit" class="botones" value="Historial" onMouseOver="window.estatus='';return true" 
					title="Consultar el Historial de Trabajo del Empleado"/>
					&nbsp;
					<input name="sbt_prueEsfzo" type="submit" class="botones" value="Esfuerzo" onMouseOver="window.estatus='';return true" 
					title="Consultar las Pruebas de Esfuerzo"/>
					&nbsp;
					<input name="sbt_prueLab" type="submit" class="botones" value="Laboratorio" onMouseOver="window.estatus='';return true" 
					title="Consultar las Pruebas de Laboratorio"/>
					&nbsp;
					<input name="btn_verPDF" type="button" class="botones" value="Ver PDF" onMouseOver="window.estatus='';return true" 
					title="Ver PDF Historial Clinico"  onclick="window.open('../../includes/generadorPDF/historialClinico.php?idHistorial=<?php echo $clave;?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=no, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>	<?php				
					//Particionamos el post con el radio para tomar iunicamete el valor del id
					$claveSecc=explode(".",$_POST['rdb_id']);
					$totalElementos = count($claveSecc);?>
					<?php if($totalElementos==2){?>
						&nbsp;&nbsp;					
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otro Historial" 
						onMouseOver="window.status='';return true" 
						onclick="location.href='frm_consultarHistorialClinico2.php?cmb_tipoClasificacion=<?php echo $claveSecc[1]; ?>&sbt_consultarTipo=sbt_consultarTipo'"  />	
					<?php } else if($totalElementos==3){?>	
						&nbsp;&nbsp;
						<input name="btn_regresar" type="button" class="botones" value="Regresar" title="Regresar a Seleccionar Otro Historial" 
						onMouseOver="window.status='';return true" 
						onclick="location.href='frm_consultarHistorialClinico2.php?txt_fechaIni=<?php echo modFecha($claveSecc[1],1);?>&txt_fechaFin=<?php echo modFecha($claveSecc[2],1);?>&sbt_consultarFechas=sbt_consultarFechas'"  />
					<?php  }?>
				</td>
            </tr>
        </table>
        </div>	
				
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
			
			
	//Esta función se encarga de mostrar el detallede los antecedntes medicos al presionar El boton referente al mismo
	function mostrarAntecedentesHistorial($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		$stm_sql = "SELECT DISTINCT * FROM (antecedentes_fam JOIN historial_clinico ON id_historial=historial_clinico_id_historial) 
					WHERE historial_clinico_id_historial='$clave'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'> ANTECEDENTES FAMILIARES DEL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></caption>					
				<tr>	
					<td class='nombres_columnas'>PESO</td>
					<td class='$nom_clase'>$datos[peso_kg]</td>
				</tr>
					<td class='nombres_columnas'>TALLA</td>
				  	<td class='$nom_clase'>$datos[talla_mts]</td>					  
				</tr>
				<tr>	
					<td class='nombres_columnas'>HISTORIA FAMILIAR</td>
					<td class='$nom_clase'>$datos[historia_familiar]</td>
				</tr>
					<td class='nombres_columnas'>ANTECEDENTES</td>								  
					<td class='$nom_clase'>$datos[antecedentes]</td>
				<tr>	
					<td class='nombres_columnas'>ANTECEDENTES HISTORIAL MEDICA</td>
					<td class='$nom_clase'>$datos[historia_medica_ant]</td>					
				</tr>
				<tr>	
					<td class='nombres_columnas'>ANTECEDENTES P.P</td>
					<td class='$nom_clase'>$datos[antecedentes_pp]</td>					
				</tr>
				<tr>		
					<td class='nombres_columnas'>SECUELAS</td>															
					<td class='$nom_clase'>$datos[enf_prof_secuelas]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>TORAX DIAM. A.P.</td>
					<td class='$nom_clase'>$datos[torax_diam_ap]</td>
				</tr>	
				<tr>
					<td class='nombres_columnas'>TORAX DIAM. LAT</td>
					<td class='$nom_clase'>$datos[torax_diam_lat]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>TORAX CIRC. EXP.</td>					
					<td class='$nom_clase'>$datos[torax_circ_exp]</td>			
				</tr>
				<tr>
					<td class='nombres_columnas'>TORAX CIRC. INSP.</td>			
					<td colspan='2' class='$nom_clase'>$datos[torax_circ_insp]</td>				
				</tr>		
				<tr>
					<td class='nombres_columnas'>PULSO</td>
					<td colspan='2' class='$nom_clase'>$datos[pulso]</td>					
				</tr>
				<tr>
					<td class='nombres_columnas'>RESPIRACI&Oacute;N</td>															
					<td colspan='2'  class='$nom_clase'>$datos[respiracion]</td>	
				</tr>
				<tr>
					<td class='nombres_columnas'>TEMP</td>
					<td colspan='2'  class='$nom_clase'>$datos[temp]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>PRES. ART</td>
					<td class='$nom_clase'>$datos[pres_arterial]</td>			
				</tr>
				<tr>	
					<td class='nombres_columnas'>IMC</td>					
					<td class='$nom_clase'>$datos[imc]</td>																								
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
			echo "<label class='msje_correcto'>NO EXISTEN ANTECEDENTES FAMILIARES REGISTRADOS EN EL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></label>";
		}		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarAntecedentesHistorial($clave)		
	
	
	
	//Esta función se encarga de el detalle de los aspectos generales/1 al presionar El boton referente al mismo
	function mostrarAspectosGrales1($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT DISTINCT * FROM (aspectos_grales_1 JOIN historial_clinico ON id_historial=historial_clinico_id_historial) 
					WHERE historial_clinico_id_historial='$clave' ";
		//Ejecumtamos la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		//Comprobamos la existencia de datos
		if($datos=mysql_fetch_array($rs)){						
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'> ASPECTOS GENERALES/1 DEL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></caption>									
				<tr>
					<td rowspan='4' class='nombres_columnas'><div align='right'><strong>*OJOS</strong></div></td>	
					<td class='nombres_columnas'>&nbsp;</td>
					<td class='nombres_columnas'><div align='center'>DER</div></td>
					<td class='nombres_columnas'><div align='center'>*IZQ</div></td>
					<td class='nombres_columnas'>&nbsp;</td>
					<td class='nombres_columnas'><div align='center'>*DER</div></td>
					<td class='nombres_columnas'><div align='center'>*IZQ</div></td>
				</tr>
				<tr>
					<td class='nombres_columnas'><div align='right'>*Visi&oacute;n</div></td>
					<td class='$nom_clase'>$datos[ojo_der_vision]</td>
					<td class='$nom_clase'>$datos[ojo_izq_vision]</td>
					<td class='nombres_columnas'><div align='right'>*Reflejos</div></td>
					<td class='$nom_clase'>$datos[ojo_der_reflejos]</td>
					<td class='$nom_clase'>$datos[ojo_izq_reflejos]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'><div align='right'>*Pterygiones</div></td>
					<td class='$nom_clase'>$datos[ojo_der_pterygiones]</td>
					<td class='$nom_clase'>$datos[ojo_izq_pterygiones]</td>
					<td class='nombres_columnas'><div align='right'>*Otros</div></td>
					<td class='$nom_clase'>$datos[ojo_der_otros]</td>
					<td class='$nom_clase'>$datos[ojo_izq_otros]</td>
				</tr>
				<tr>
					<td class='$nom_clase'>&nbsp;</td>
					<td class='$nom_clase'>&nbsp;</td>
					<td class='$nom_clase'>&nbsp;</td>
					<td class='$nom_clase'>&nbsp;</td>		
					<td class='$nom_clase'>&nbsp;</td>
					<td class='$nom_clase'>&nbsp;</td>																	
				</tr>
				<tr>
					<td rowspan='4'class='nombres_columnas'><div align='right'><strong>*OIDOS</strong></div></td>	
				</tr>
				<tr>
					<td class='nombres_columnas'><div align='right'>*Audici&oacute;n</div></td>
					<td class='$nom_clase'>$datos[oido_der_audicion]</td>
					<td class='$nom_clase'>$datos[oido_izq_audicion]</td>	
					<td class='nombres_columnas'><div align='right'>*Canal</div></td>
					<td class='$nom_clase'>$datos[oido_der_canal]</td>
					<td class='$nom_clase'>$datos[oido_izq_canal]</td>
				</tr>	
				<tr>
					<td class='nombres_columnas'><div align='right'>*Membrana</div></td>
					<td class='$nom_clase'>$datos[membrana_der]</td>
					<td class='$nom_clase'>$datos[membrana_izq]</td>
					<td class='$nom_clase'>&nbsp;</td>
					<td class='$nom_clase'>&nbsp;</td>	
					<td class='$nom_clase'>&nbsp;</td>	
				<tr>
					<td class='nombres_columnas'><div align='right'>*HBC</div></td>
					<td class='$nom_clase'>$datos[porciento_hbc]</td>
					<td class='nombres_columnas'><div align='right'>*Tipo</div></td>
					<td class='$nom_clase'>$datos[tipo]</td>
					<td class='nombres_columnas'><div align='right'>*% IPP</div></td> 
					<td class='$nom_clase'>$datos[porciento_ipp]</td>	
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
			echo "<label class='msje_correcto'>NO EXISTEN ASPECTOS GENERALES/1 DEL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></label>";
		}		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarAspectosGrales1($clave)		
	
	
	//Esta función se encarga de mostrar el detalle de los aspectos generales/2 al presionar El boton referente al mismo
	function mostrarAspectosGrales2($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT DISTINCT * FROM (aspectos_grales_2 JOIN historial_clinico ON id_historial=historial_clinico_id_historial) 
					WHERE historial_clinico_id_historial='$clave' ";
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		//Comprobamos que existan datos
		if($datos=mysql_fetch_array($rs)){						
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
			echo "							
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>  ASPECTOS GENERALES/2 DEL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></caption>					
				<tr>	
					<td class='nombres_columnas' width='93'>NARIZ</td>
	  		  		<td colspan='5' class='$nom_clase'>$datos[obstruccion]</td>					  
					<td class='nombres_columnas' width='82'>OBSTRUCCI&Oacute;N</td>
				  	<td class='$nom_clase'>$datos[obstruccion]</td>					  
				</tr>
				<tr>
					<td class='nombres_columnas'>BOCA Y GARGANTA</td>
					<td colspan='2' class='$nom_clase'>$datos[boca_garganta]</td>
					<td class='nombres_columnas'>ENCIAS</td>								  
					<td colspan='2' class='$nom_clase'>$datos[encias]</td>
					<td class='nombres_columnas'>DIENTES</td>
					<td class='$nom_clase'>$datos[dientes]</td>					
				</tr>
				<tr>
					<td class='nombres_columnas'>CUELLO</td>	
					<td  colspan='2' class='$nom_clase'>$datos[cuello]</td>							
					<td class='nombres_columnas'>LINFATICOS</td>
					<td colspan='5' class='$nom_clase'>$datos[linfaticos]</td>
				</tr>
				<tr>
				<td class='nombres_columnas'>TORAX</td>
					<td colspan='7' class='$nom_clase'>$datos[torax]</td>
				</tr>	
				<tr>
					<td class='nombres_columnas'>CORAZ&Oacute;N</td>
					<td colspan='7' class='$nom_clase'>$datos[corazon]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>PULMONES</td>					
					<td colspan='7' class='$nom_clase'>$datos[pulmones]</td>			
				</tr>
				<tr>
					<td class='nombres_columnas'>ABDOMEN</td>			
					<td colspan='2' class='$nom_clase'>$datos[abdomen]</td>				
					<td class='nombres_columnas'>PULSO</td>
					<td colspan='2' class='$nom_clase'>$datos[higado]</td>					
					<td class='nombres_columnas'>BAZO</td>
					<td colspan='2'  class='$nom_clase'>$datos[bazo]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>PARED ADBDOMINAL</td>
					<td colspan='2' class='$nom_clase'>$datos[pared_abdominal]</td>			
					<td class='nombres_columnas'>ANILLOS</td>
					<td colspan='2' class='$nom_clase'>$datos[anillo]</td>																								
					<td class='nombres_columnas'>HERNIAS</td>
					<td colspan='2' class='$nom_clase'>$datos[hernias]</td>			
				</tr>
				<tr>
				<td class='nombres_columnas'>GEN. URI.</td>
					<td colspan='2' class='$nom_clase'>$datos[gen_uri]</td>			
					<td class='nombres_columnas'>HIDROCELE</td>
					<td colspan='2' class='$nom_clase'>$datos[hidrocele]</td>			
					<td class='nombres_columnas'>VARICOCELE</td>
					<td colspan='2' class='$nom_clase'>$datos[varicocele]</td>			
				</tr>
				<tr>
				<td class='nombres_columnas'>HEMORROIDES</td>
					<td  colspan = '7' class='$nom_clase'>$datos[hemorroides]</td>			
				</tr>
				<tr>
					<td class='nombres_columnas'>EXTR. SUPRS.</td>
					<td  colspan = '7' class='$nom_clase'>$datos[extr_suprs]</td>			
				</tr>
				<tr>
					<td class='nombres_columnas'>EXTR. INFRS.</td>
					<td colspan = '7' class='$nom_clase'>$datos[extr_infrs]</td>			
				</tr>
				<tr>
					<td class='nombres_columnas'>REFLEJOS O.T.</td>
					<td colspan = '4' class='$nom_clase'>$datos[reflejos_ot]</td>		
					<td class='nombres_columnas'>PSIQUISMO</td>
					<td colspan = '4' class='$nom_clase'>$datos[psiquismo]</td>			
				</tr>
				<tr>
					<td class='nombres_columnas'>SINTOMA ACTUAL</td>
					<td colspan = '7' class='$nom_clase'>$datos[sintoma_actual]</td>			
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
			echo "<label class='msje_correcto'>NO EXISTEN ASPECTOS GENERALES/2 REGISTRADOS EN EL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></label>";
		}			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarAspectosGrales2($clave)	
	
	//Funcion que nos permitira mostrar el detalle de las pruebas de esfuerzo presionando el boton que hace referencia a dicho concepto
	function mostrarPruebasEsfuerzo($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT DISTINCT * FROM (prueba_esfuerzo JOIN historial_clinico ON id_historial=historial_clinico_id_historial) 
					WHERE historial_clinico_id_historial='$clave' ";
		
		//Ejecutamos la sentencia
		$rs = mysql_query($stm_sql);
		
		//Comprobamos la existencia de datos
		if($datos=mysql_fetch_array($rs)){						
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>  PRUEBAS DE ESFUERZO REGISTRADAS EN EL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></caption>					
				<tr>	
					<td>&nbsp;</td>			  
					<td class='nombres_columnas'>PULSO</td>
					<td class='nombres_columnas'>RESPIRACI&Oacute;N</td>	  
				</tr>
				<tr>
					<td class='nombres_columnas'>EN REPOSO</td>
					<td class='$nom_clase'>$datos[pulso_reposo]</td>
					<td class='$nom_clase'>$datos[resp_reposo]</td>
				</tr>				
				<tr>
					<td class='nombres_columnas'>INM. DESP. DE ESFZO.</td>	
					<td class='$nom_clase'>$datos[pulso_inm_desp_esfzo]</td>							
					<td class='$nom_clase'>$datos[resp_inm_desp_esfzo]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>1 MIN. DESPU&Eacute;S</td>
					<td class='$nom_clase'>$datos[pulso_un_min_desp]</td>
					<td class='$nom_clase'>$datos[resp_un_min_desp]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>2 MIN. DESPU&Eacute;S</td>								
					<td class='$nom_clase'>$datos[pulso_dos_min_desp]</td>			
					<td class='$nom_clase'>$datos[resp_dos_min_desp]</td>					
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
			echo "<label class='msje_correcto'>NO EXISTEN PRUEBAS DE ESFUERZO REGISTRADAS EN EL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></label>";
		}			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarPruebasEsfuerzo($clave)	
	
	
	//Funcion que permite mostrar el detalle dfe las pruebas de laboratorio realizadas a un trabajador en particular
	function mostrarPruebasLaboratorio($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		$stm_sql = "SELECT DISTINCT * FROM (laboratorio JOIN historial_clinico ON id_historial=historial_clinico_id_historial) 
					WHERE historial_clinico_id_historial='$clave'";
					
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){						
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>  PRUEBAS DE LABORATORIO DEL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></caption>					
				<tr>
					<td class='nombres_columnas'>VDRL</td>
					<td class='$nom_clase'>$datos[vdrl]</td>
					<td class='nombres_columnas'>B.H</td>
					<td class='$nom_clase'>$datos[bh]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>GLICEMIA</td>
					<td class='$nom_clase'>$datos[glicemia]</td>
					<td class='nombres_columnas'>PIE</td>
					<td class='$nom_clase'>$datos[pie]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>GRAL. ORINA</td>								
					<td class='$nom_clase'>$datos[gral_orina]</td>			
					<td class='nombres_columnas'>PB. EN SANG</td>													
					<td class='$nom_clase'>$datos[pb_sang]</td>					
				<tr>
					<td class='nombres_columnas'>HIV</td>
					<td class='$nom_clase'>$datos[hiv]</td>
					<td class='nombres_columnas'>CADMIO</td>
					<td class='$nom_clase'>$datos[cadmio]</td>
				</tr>	
				<tr>
					<td class='nombres_columnas'>FOSFATA ACIDA</td>
					<td class='$nom_clase'>$datos[fosfata_acida]</td>
					<td class='nombres_columnas'>TG</td>
					<td class='$nom_clase'>$datos[tg]</td>
				</tr>	
				<tr>
					<td class='nombres_columnas'>FOSFATA ALCALINA</td>
					<td class='$nom_clase'>$datos[fosfata_alcalina]</td>
					<td class='nombres_columnas'>COLESTEROL</td>
					<td class='$nom_clase'>$datos[colesterol]</td>
				</tr>	
				<tr>
					<td class='nombres_columnas'>ESPIROMETRIA</td>
					<td class='$nom_clase'>$datos[espirometria]</td>
					<td class='nombres_columnas'>TIPO SANGUINEO</td>
					<td class='$nom_clase'>$datos[tipo_sanguineo]</td>
					<td class='nombres_columnas'>B MGLOBULIN</td>
					<td class='$nom_clase'>$datos[b_mglobulin]</td>
				</tr>	
				<tr>
					<td class='nombres_columnas'>FCR</td>
					<td class='$nom_clase'>$datos[fcr]</td>
					<td class='nombres_columnas'>DIAG. LABORATORIO</td>
					<td class='$nom_clase'>$datos[diag_laboratorio]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>RX DE T&Oacute;RAX</td>
					<td class='$nom_clase'>$datos[rx_torax]</td>
					<td class='nombres_columnas'>ALCOHOLIMETRO</td>
					<td class='$nom_clase'>$datos[alcoholimetro]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>% SILICOSIS</td>
					<td class='$nom_clase'>$datos[porcentaje_silicosis]</td>
					<td class='nombres_columnas'>FRACC.</td>
					<td class='$nom_clase'>$datos[fracc]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>COL LUMBROSACA</td>
					<td colspan='5' class='$nom_clase'>$datos[col_lumbrosaca]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>ROMBERG</td>
					<td class='$nom_clase'>$datos[romberg]</td>
					<td class='nombres_columnas'>BABINSKY WEIL.</td>
					<td class='$nom_clase'>$datos[babinsky_weil]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>DIAGN&Oacute;STICO</td>
					<td colspan='5' class='$nom_clase'>$datos[diagnostico]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>CONCLUSIONES</td>
					<td colspan='5' class='$nom_clase'>$datos[conclusiones]</td>
				</tr>
				<tr>
					<td class='nombres_columnas'>EDO. SALUD</td>
					<td colspan='5' class='$nom_clase'>$datos[edo_salud]</td>
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
			echo "<label class='msje_correcto'>NO EXISTEN PRUEBAS DE LABORATORIO REGISTRADAS EN EL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></label>";
		}			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarDetallAsistentes($clave)
	
	
	//Funcion que nos permitira mostrar el detallae de los antecedetes patologicos presionando el boton que hace referencia a dicho concepto
	function mostrarAntecedentesPatologicos($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT DISTINCT * FROM (ant_no_patologicos JOIN historial_clinico ON id_historial=historial_clinico_id_historial) 
					WHERE historial_clinico_id_historial='$clave' ";
		
		//Ejecutamos la sentencia
		$rs = mysql_query($stm_sql);
		
		//Comprobamos la existencia de datos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>  ANTECEDENTES PATOLOGICOS DEL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></caption>					
				<tr>	
					<td class='nombres_columnas'>ACTIVIDAD</td>
					<td class='nombres_columnas'>ETILISMO</td>	  
					<td class='nombres_columnas'>TABAQUISMO</td>	  
					<td class='nombres_columnas'>OTRAS ADICCIONES</td>	  
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "<tr>
						<td class='$nom_clase'>$datos[actividad]</td>
						<td class='$nom_clase'>$datos[etilismo]</td>
						<td class='$nom_clase'>$datos[tabaquismo]</td>			
						<td class='$nom_clase'>$datos[otras_adicc]</td>					
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
			echo "<label class='msje_correcto'>NO EXISTEN ANTECEDENTES PATOLOGICOS REGISTRADOS EN EL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></label>";
		}			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarAntecedentesPatologicos($clave)	
	
	
	
	//Funcion que nos permitira mostrar el detalle de los antecedentes de trabajo presionando el boton que hace referencia a dicho concepto
	function mostrarHistorialTrabajo($clave){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_clinica");
		
		//Creamos la sentencia SQL
		$stm_sql = "SELECT DISTINCT * FROM (historial_trabajo JOIN historial_clinico ON id_historial=historial_clinico_id_historial) 
					WHERE historial_clinico_id_historial='$clave' ";
		
		//Ejecutamos la sentencia
		$rs = mysql_query($stm_sql);
		
		//Comprobamos la existencia de datos
		if($datos=mysql_fetch_array($rs)){						
			echo "								
			<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>  ANTECEDENTES DE TRABAJO DEL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></caption>					
				<tr>	
					<td class='nombres_columnas'>LUGAR</td>
					<td class='nombres_columnas'>TIPO TRABAJO</td>	  
					<td class='nombres_columnas'>TIEMPO</td>	  
					<td class='nombres_columnas'>CONDICIONES ESPECIALES</td>	  
				</tr>";				
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "<tr>
						<td class='$nom_clase'>$datos[lugar]</td>
						<td class='$nom_clase'>$datos[tipo_trabajo]</td>
						<td class='$nom_clase'>$datos[tiempo]</td>			
						<td class='$nom_clase'>$datos[cond_especiales]</td>					
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
			echo "<label class='msje_correcto'>NO EXISTE HISTORIAL DE TRABAJO REGISTRADO EN EL EX&Aacute;MEN M&Eacute;DICO NO. <em>".$clave."</em></label>";
		}			
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion	 mostrarHistorialTrabajo($clave)		
?>