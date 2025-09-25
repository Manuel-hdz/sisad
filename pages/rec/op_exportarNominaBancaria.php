<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 13/Junio/2011
	  * Descripción: Este archivo permite exportar datos de la nómina bancaria
	**/
	
	//funcion que permita consultar los empleados registrados en la nomina bancaria
	function mostrarNominaBancaria(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_recursos");
		
		//Variable para verificar si hubo rRS en la consulta y poder mostrar los botones
		$flag=0;
		
		//Tomamos las variables del post	
		$anio=$_POST["cmb_anio"];
		$mes=$_POST["cmb_mes"];	
		$semana=$_POST["cmb_semana"];	
		
		//Creamos la sentencia sql
		$stm_sql="SELECT * FROM (nomina_bancaria JOIN empleados ON rfc_trabajador=rfc_empleado) WHERE anio_insercion='$anio' AND mes='$mes' AND semana='$semana'";
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		//Declaramos el titulo para mostrar en el encabezado
		$msg_titulo="Empleados Registrados en la N&oacute;mina Bancaria en la Semana <u><em>".$_POST["cmb_semana"]."</u></em> Mes <u><em>".$_POST["cmb_mes"]."</u></em> A&ntilde;o <u><em>".$_POST["cmb_anio"]."</u></em>";
		
		if($datos=mysql_fetch_array($rs)){
		//Guardamos las fechas para exportar el archivo de texto con los datos de la nómina
		$flag=1;
			echo "<table class='tabla_frm' width='100%'>";
			echo "<caption class='titulo_etiqueta'>$msg_titulo</caption>
					<tr>";?>
						<td align='left' colspan="3" class='nombres_columnas'>
						<input name='ckbTodo' align='center'  type='checkbox' id='ckbTodo' onclick="checarTodos(this,'frm_resultadosNomina');" value='Todos'/>SELECCIONAR TODOS	</td>					
					</tr>
		<?php echo" <tr>
						<td rowspan='2' class='nombres_columnas' align='center'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto1' name='ckb_concepto1' value='mes'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto2' name='ckb_concepto2' value='semana'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto3' name='ckb_concepto3' value='num'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto4' name='ckb_concepto4' value='nombre_trabajador'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto5' name='ckb_concepto5' value='rfc_trabajador'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto6' name='ckb_concepto6' value='imss'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto7' name='ckb_concepto7' value='curp'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto8' name='ckb_concepto8' value='jornada'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto9' name='ckb_concepto9' value='fecha_ingreso'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto10' name='ckb_concepto10' value='tipo_salario'/></td>
						
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto11' name='ckb_concepto11' value='hrs_laboradas'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto12' name='ckb_concepto12' value='dias_trabajados'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto13' name='ckb_concepto13' value='septimo_dia'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto14' name='ckb_concepto14' value='hrs_tiempo_extra'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto15' name='ckb_concepto15' value='dias_domingos'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto16' name='ckb_concepto16' value='dias_descanso'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto17' name='ckb_concepto17' value='dias_festivos'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto18' name='ckb_concepto18' value='dias_vacacion'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto19' name='ckb_concepto19' value='sueldo_diario'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto20' name='ckb_concepto20' value='sueldo_integrado'/></td>
						
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto21' name='ckb_concepto21' value='percepcion_normal'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto22' name='ckb_concepto22' value='importe_septimo_dia'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto23' name='ckb_concepto23' value='tiempo_extra'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto24' name='ckb_concepto24' value='prima_dominical'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto25' name='ckb_concepto25' value='p_comision'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto26' name='ckb_concepto26' value='trabajo_dias_descanso'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto27' name='ckb_concepto27' value='trabajo_dias_festivos'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto28' name='ckb_concepto28' value='prima_vacacional'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto29' name='ckb_concepto29' value='aguinaldo'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto30' name='ckb_concepto30' value='ptu'/></td>
						
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto31' name='ckb_concepto31' value='premio_asistencia'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto32' name='ckb_concepto32' value='premio_puntualidad'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto33' name='ckb_concepto33' value='despensas'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto34' name='ckb_concepto34' value='prima_antiguo'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto35' name='ckb_concepto35' value='anios_antiguo'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto36' name='ckb_concepto36' value='otras_percepciones'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto37' name='ckb_concepto37' value='clave_op'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto38' name='ckb_concepto38' value='total_percepciones'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto39' name='ckb_concepto39' value='retencion_imss'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto40' name='ckb_concepto40' value='retencion_ispt'/></td>
						
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto41' name='ckb_concepto41' value='neto_percepciones'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto42' name='ckb_concepto42' value='abono_infonavit'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto43' name='ckb_concepto43' value='otras_retenciones'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto44' name='ckb_concepto44' value='fonacot'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto45' name='ckb_concepto45' value='clave_or'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto46' name='ckb_concepto46' value='total_retenido'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto47' name='ckb_concepto47' value='neto_salarios'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto48' name='ckb_concepto48' value='subsidio_empleo'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto49' name='ckb_concepto49' value='neto_pagar'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox' id'ckb_concepto50' name='ckb_concepto50' value='numero'/></td>
						
						<td class='nombres_columnas' align='center'><input type='checkbox'  id'ckb_concepto51' name='ckb_concepto51' value='ingravado'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox'  id'ckb_concepto52' name='ckb_concepto52' value='depto'/></td>
						<td class='nombres_columnas' align='center'><input type='checkbox'  id'ckb_concepto53' name='ckb_concepto53' value='anio_insercion'/></td>
					</tr>
					<tr>
						<td class='nombres_columnas' align='center'>MES</td>
						<td class='nombres_columnas' align='center'>SEMANA</td>
						<td class='nombres_columnas' align='center'>N&Uacute;MERO</td>
						<td class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</td>
						<td class='nombres_columnas' align='center'>RFC</td>
						<td class='nombres_columnas' align='center'>IMSS</td>
						<td class='nombres_columnas' align='center'>CURP</td>
						<td class='nombres_columnas' align='center'>JORNADA</td>
						<td class='nombres_columnas' align='center'>FECHA INGRESO</td>
						<td class='nombres_columnas' align='center'>TIPO SALARIO</td>
						
						<td class='nombres_columnas' align='center'>HORAS LABORADAS</td>
						<td class='nombres_columnas' align='center'>D&Iacute;AS TRABAJADOS</td>
						<td class='nombres_columnas' align='center'>SEPTIMO D&Iacute;A</td>
						<td class='nombres_columnas' align='center'>HORAS EXTRA</td>
						<td class='nombres_columnas' align='center'>DOMINGOS</td>
						<td class='nombres_columnas' align='center'>D&Iacute;AS DESCANSO</td>
						<td class='nombres_columnas' align='center'>D&Iacute;AS FESTIVOS</td>
						<td class='nombres_columnas' align='center'>D&Iacute;AS VACACIONES</td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>SUELDO INTEGRADO</td>
						
						<td class='nombres_columnas' align='center'>PERCEPCI&Oacute;N NORMAL</td>
						<td class='nombres_columnas' align='center'>IMPORTE 7 D&Iacute;A</td>
						<td class='nombres_columnas' align='center'>TIEMPO EXTRA</td>
						<td class='nombres_columnas' align='center'>PRIMA DOMINICAL</td>
						<td class='nombres_columnas' align='center'>PRIMA COMISIÓN</td>
						<td class='nombres_columnas' align='center'>D&Iacute;AS DESCANSO</td>
						<td class='nombres_columnas' align='center'>D&Iacute;AS FESTIVO</td>
						<td class='nombres_columnas' align='center'>PTIMA VACACIONAL</td>
						<td class='nombres_columnas' align='center'>AGUINALDO</td>
						<td class='nombres_columnas' align='center'>PTU</td>
						
						<td class='nombres_columnas' align='center'>PREMIO ASISTENCIA</td>
						<td class='nombres_columnas' align='center'>PREMIO PUNTUALIDAD</td>
						<td class='nombres_columnas' align='center'>DESPENSAS</td>
						<td class='nombres_columnas' align='center'>PRIMA ANTIG&Uuml;EDAD</td>
						<td class='nombres_columnas' align='center'>ANTIG&Uuml;EDAD</td>
						<td class='nombres_columnas' align='center'>OTRAS PERCEPCIONES</td>
						<td class='nombres_columnas' align='center'>CLAVE OP</td>
						<td class='nombres_columnas' align='center'>TOTAL PERCEPCIONES</td>
						<td class='nombres_columnas' align='center'>RETENCI&Oacute;N IMSS</td>
						<td class='nombres_columnas' align='center'>RETENCI&Oacute;N ISPT</td>
						
						<td class='nombres_columnas' align='center'>NETO PERCEPCIONES</td>
						<td class='nombres_columnas' align='center'>INFONAVIT</td>
						<td class='nombres_columnas' align='center'>OTRAS RETENCIONES</td>
						<td class='nombres_columnas' align='center'>FONACOT</td>
						<td class='nombres_columnas' align='center'>CLAVE OR</td>
						<td class='nombres_columnas' align='center'>TOTAL RETENIDO</td>
						<td class='nombres_columnas' align='center'>NETO SALARIO</td>
						<td class='nombres_columnas' align='center'>SUBSIDIO EMPLEO</td>
						<td class='nombres_columnas' align='center'>NETO PAGAR</td>
						
						<td class='nombres_columnas' align='center'>N&Uacute;MERO</td>
						<td class='nombres_columnas' align='center'>INGRAVADO</td>
						<td class='nombres_columnas' align='center'>DEPTO</td>
						<td class='nombres_columnas' align='center'>A&Ntilde;O</td>
					</tr>
					</tdead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			//Variable para guardar el total de nomina
			$total_nomina=0;	
			do{								
			echo "	<tr>";?>
						<td class='nombres_filas' align='center'>
							<input type="checkbox" id="ckb_emp<?php echo $cont;?>" name="ckb_emp<?php echo $cont;?>" onclick="desSeleccionar(this);"
							value="<?php echo $datos['rfc_trabajador']?>"/>
						</td>
			<?php echo "
						<td class='$nom_clase' align='center'>$datos[mes]</td>
						<td class='$nom_clase' align='center'>$datos[semana]</td>
						<td class='$nom_clase' align='center'>$datos[num]</td>
						<td class='$nom_clase' align='center'>$datos[nombre_trabajador]</td>
						<td class='$nom_clase' align='center'>$datos[rfc_trabajador]</td>
						<td class='$nom_clase' align='center'>$datos[imss]</td>
						<td class='$nom_clase' align='center'>$datos[curp]</td>
						<td class='$nom_clase' align='center'>$datos[jornada]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_ingreso'],1)."</td>
						<td class='$nom_clase' align='center'>$datos[tipo_salario]</td>
						
						<td class='$nom_clase' align='center'>$datos[hrs_laboradas]</td>
						<td class='$nom_clase' align='center'>$datos[dias_trabajados]</td>
						<td class='$nom_clase' align='center'>$datos[septimo_dia]</td>
						<td class='$nom_clase' align='center'>$datos[hrs_tiempo_extra]</td>
						<td class='$nom_clase' align='left'>$datos[dias_domingos]</td>
						<td class='$nom_clase' align='left'>$datos[dias_descanso]</td>
						<td class='$nom_clase' align='left'>$datos[dias_festivos]</td>
						<td class='$nom_clase' align='left'>$datos[dias_vacacion]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["sueldo_diario"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["sueldo_integrado"],2,".",",")."</td>
						
						<td class='$nom_clase' align='center'>$".number_format($datos["percepcion_normal"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["importe_septimo_dia"],2,".",",")."</td>
						<td class='$nom_clase' align='left'>$datos[tiempo_extra]</td>
						<td class='$nom_clase' align='left'>$datos[prima_dominical]</td>
						<td class='$nom_clase' align='left'>$datos[p_comision]</td>
						<td class='$nom_clase' align='left'>$datos[trabajo_dias_descanso]</td>
						<td class='$nom_clase' align='left'>$datos[trabajo_dias_festivos]</td>
						<td class='$nom_clase' align='left'>$datos[prima_vacacional]</td>
						<td class='$nom_clase' align='left'>$datos[aguinaldo]</td>
						<td class='$nom_clase' align='left'>$datos[ptu]</td>
						
						<td class='$nom_clase' align='center'>$".number_format($datos["premio_asistencia"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["premio_puntualidad"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["despensas"],2,".",",")."</td>
						<td class='$nom_clase' align='left'>$datos[prima_antiguo]</td>
						<td class='$nom_clase' align='left'>$datos[anios_antiguo]</td>
						<td class='$nom_clase' align='left'>$datos[otras_percepciones]</td>
						<td class='$nom_clase' align='left'>$datos[clave_op]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["total_percepciones"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["retencion_imss"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["retencion_ispt"],2,".",",")."</td>
						
						<td class='$nom_clase' align='center'>$".number_format($datos["neto_percepciones"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$datos[abono_infonavit]</td>
						<td class='$nom_clase' align='left'>$datos[otras_retenciones]</td>
						<td class='$nom_clase' align='left'>$datos[fonacot]</td>
						<td class='$nom_clase' align='left'>$datos[clave_or]</td>
						<td class='$nom_clase' align='left'>$datos[total_retenido]</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["neto_salarios"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["subsidio_empleo"],2,".",",")."</td>
						<td class='$nom_clase' align='center'>$".number_format($datos["neto_pagar"],2,".",",")."</td>
						<td class='$nom_clase' align='left'>$datos[numero]</td>

						<td class='$nom_clase' align='center'>$".number_format($datos["ingravado"],2,".",",")."</td>
						<td class='$nom_clase' align='left'>$datos[depto]</td>
						<td class='$nom_clase' align='left'>$datos[anio_insercion]</td>
						";
						
				//Sumar el monto de la nomina para obtener el total
				$total_nomina += $datos['neto_pagar'];						
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 
			echo "
					<tr><td colspan='49'>&nbsp;</td><td align='center' class='nombres_columnas'>$".number_format($total_nomina,2,".",",")."</td></tr>
				</table>
				</div>";
		}
		else{	
			echo "<br><br><br><br><br><br><br><br><p align='center' class='msje_correcto'>No se Encontraron Empleados Registrados en la N&oacute;mina Bancaria en la <u><em>".$_POST["semana"]."</u></em> Mes <u><em>".$_POST["mes"]."</u></em> A&ntilde;o <u><em>".$_POST["cmb_anio"]."</u></em></p>";
		} ?>
		<div id="btns-regpdf" align="center" >
			<table width="30%" cellpadding="12">
				<tr>
					<td width="19%" align="center">
			  			<input type="button" name="btn_regresar" value="Regresar" class="botones" title="Regresar Exportar N&oacute;mina Bancaria" 
               			onMouseOver="window.estatus='';return true" 
			  			onclick="location.href='frm_exportarNominaBancaria.php'" />			  
					</td>
			  	<?php 
				if($flag==1){
					//Declaramos las variables para guardar el resultado de las consultas y mostrarlo en Excel?>			
					<td width="29%" align="center">
						<input name="hdn_nomReporte" type="hidden" 
						value="Reporte_Nomina_Bancaria_<?php echo $semana."_".$mes."_".$anio;?>"/>
						<input name="hdn_consulta" type="hidden"/>
						<input name="hdn_msg" type="hidden" value="<?php echo $msg_titulo; ?>" />
						<input name="hdn_origen" type="hidden" value="reporteNominaBancaria" />	
						<input name="hdn_cant"  id="hdn_cant" type="hidden" value="<?php echo $cont; ?>" />	
						<input name="sbt_exportar" type="submit" class="botones" value="Exportar a Excel" 
						title="Exportar a Excel los Datos de la Consulta Realizada" 
						onMouseOver="window.estatus='';return true"  />
					</td><?php 
				}//definimos el arreglo de sessión para generar las graficas?>
			</tr>
		</table>
		</div>
		<?php
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion de mostrarNomina _Bancaria

?>
