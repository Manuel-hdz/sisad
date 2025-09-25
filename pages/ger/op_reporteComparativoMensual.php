<?php
	/**
	  * Nombre del Módulo: Gerencia Técnica
	  * Nombre Programador: Maurilio Hernández Correa & Daisy Adriana Martínez Fernández
	  * Fecha: 25/Julio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Reporte comparativo mensual
	**/
	
	 //Funcion para mostrar la informacion del presupuesto seleccionado mediante los combo box de ubicación y periodo
	function mostrarPresupuestos(){
		//Conectar a la BD de producción
		$conn = conecta("bd_gerencia");
		$periodo=$_POST['cmb_periodo'];
		$ubicacion=$_POST['cmb_ubicacion'];
		
		//Obtener el nombre de la ubicacion para colocarlo en el titulo de la tabla
		$Nomubicacion= obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$ubicacion);
		
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM presupuesto WHERE periodo= '$periodo' AND catalogo_ubicaciones_id_ubicacion='$ubicacion'";	
				
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Presupuestos del periodo de <em><u>  $periodo </u></em> de Ubicaci&oacute;n <em><u>  $Nomubicacion </u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Presupuesto Registrado del periodo de <em><u>  $periodo    
		 </u></em> de Ubicaci&oacute;n  <em><u>  $Nomubicacion </u></em>";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='120%'>				
				<tr>
					<td colspan='8' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>PERIODO</td>
					<td class='nombres_columnas' align='center'>FECHA INICIO</td>
					<td class='nombres_columnas' align='center'>FECHA FIN</td>
					<td class='nombres_columnas' align='center'>VOLUMEN PRESUPUESTADO MENSUAL</td>
					<td class='nombres_columnas' align='center'>VOLUMEN PRESUPUESTADO DIARIO</td>
					<td class='nombres_columnas' align='center'>D&Iacute;AS LABORABLES</td>
					<td class='nombres_columnas' align='center'>DOMINGOS</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>";?>
						<td class="nombres_filas" align="center">
						<input type="checkbox" name="ckb_idPresupuesto" id="ckb_idPresupuesto" value="<?php $datos['periodo'];?>"
						onClick="complementarReporteComparativo();" /><?php
				 echo " </td>
						<td class='$nom_clase' align='center'>$datos[periodo]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_inicio'],1)."</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_fin'],1)."</td>
						<td class='$nom_clase' align='center'>".number_format($datos['vol_ppto_mes'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>".number_format($datos['vol_ppto_dia'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>$datos[dias_habiles] D&iacute;as</td>
						<td class='$nom_clase' align='center'>$datos[dias_inhabiles] Domingos</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>
			<input type='hidden' name='cmb_periodo' value='$_POST[cmb_periodo]'/>";
			echo "<input type='hidden' id='hdn_ubicacion' name='hdn_ubicacion' value='$ubicacion'/>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	
	 //Funcion para mostrar el comparativo en el periodo seleccionado
	function mostrarComparativo(){
		//Recuperar los datos del POST
		$periodo=$_POST['cmb_periodo'];
		$ubicacion=$_POST['hdn_ubicacion'];
		$anio=substr($periodo,0,4);
		$mesIniPer=substr($periodo,5,3);
		$mesFinPer=substr($periodo,-3);
		if($mesFinPer=="ENE")
			$anio-=1;
		//Obtenemos la fecha inicial y final de la BD para tomarla como parametro al realizar las operaciones para realizar los calculos
		$fechaIniMesActual=obtenerDatoBicondicional("bd_gerencia", "presupuesto", "fecha_inicio", "periodo", $_POST['cmb_periodo'], "catalogo_ubicaciones_id_ubicacion", $ubicacion);
		$fechaFinMesActual=obtenerDatoBicondicional("bd_gerencia", "presupuesto", "fecha_fin", "periodo", $_POST['cmb_periodo'], "catalogo_ubicaciones_id_ubicacion", $ubicacion);
		//Obtener el nombre de la ubicacion para colocarlo en el titulo de la tabla
		$nomUbicacion = obtenerDato("bd_gerencia","catalogo_ubicaciones","ubicacion","id_ubicacion",$_POST['hdn_ubicacion']);
		//Conectar a la BD de gerencia
		$conn = conecta("bd_gerencia");
		//Ejecutar la sentencia que extrae las fechas de inicio y fin del periodo anterior al actual
		$rsPeriodo=mysql_query("SELECT SUBSTRING(periodo,6,3) AS mes,fecha_inicio,fecha_fin,periodo FROM presupuesto WHERE catalogo_ubicaciones_id_ubicacion='$ubicacion' 
							AND periodo LIKE '$anio%$mesIniPer' ORDER BY fecha_inicio");
		//Extraer los Datos del periodo anterior cuando este existe
		if($datosPeriodo=mysql_fetch_array($rsPeriodo)){
			$fechaIniMesAnterior=$datosPeriodo["fecha_inicio"];
			$fechaFinMesAnterior=$datosPeriodo["fecha_fin"];
			$mesAnterior=$datosPeriodo["mes"];
			$periodo2=$datosPeriodo["periodo"];
		}
		//Si el periodo no existe, obtener el mes que le corresponde y las Fechas quedan como cadenas vacias
		else{
			$fechaIniMesAnterior="";
			$fechaFinMesAnterior="";
			$secCombo=split("-",$periodo);
			$mesAnterior=obtenerNombreCompletoMes($secCombo[1]);
			$mesAnterior=substr(obtenerMesAnterior($mesAnterior),0,3);
			$periodo2=$anio."-".$mesAnterior."-".$mesIniPer;
		}
		//Crear el periodo 3 y obtener las fechas de la BD, si no existe el periodo, no se toman los registros asociados a el
		//Ejecutar la sentencia que extrae las fechas de inicio y fin del periodo anterior de inicio
		$rsPeriodo2=mysql_query("SELECT SUBSTRING(periodo,6,3) AS mes,fecha_inicio,fecha_fin,periodo FROM presupuesto WHERE catalogo_ubicaciones_id_ubicacion='$ubicacion' 
							AND periodo LIKE '$anio%$mesAnterior' ORDER BY fecha_inicio");
		//Extraer los Datos del periodo anterior cuando este existe
		if($datosPeriodo2=mysql_fetch_array($rsPeriodo2)){
			$fechaIniMesAnterior3=$datosPeriodo2["fecha_inicio"];
			$fechaFinMesAnterior3=$datosPeriodo2["fecha_fin"];
			$mesAnterior3=$datosPeriodo2["mes"];
			$periodo3=$datosPeriodo2["periodo"];
		}
		//Si el periodo no existe, obtener el mes que le corresponde y las Fechas quedan como cadenas vacias
		else{
			$fechaIniMesAnterior3="";
			$fechaFinMesAnterior3="";
			//Obtener el nombre completo del Mes
			$mesAnterior3=obtenerNombreCompletoMes($mesAnterior);
			//Obtener el Mes Anterior
			$mesAnterior3=substr(obtenerMesAnterior($mesAnterior3),0,3);
			//Obtener el Periodo
			$periodo3=$anio."-".$mesAnterior3."-".$mesAnterior;
		}
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg_titulo= "Comparativo Mensual <em><u>$nomUbicacion</em></u> en el Periodo <em><u>$_POST[cmb_periodo]</u></em>";
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ning&uacute;n Registro en el Presupuesto del Periodo <u><em>$periodo</u></em> para <u><em>$nomUbicacion</u></em></label>";
		//Extraer los conceptos de la bitacora que se tengan registrados para cualquiera de los 3 periodos a buscar
		$rsConceptos=mysql_query("SELECT DISTINCT aplicacion FROM bitacora_zarpeo WHERE destino='$nomUbicacion' AND ((fecha BETWEEN '$fechaIniMesAnterior3' AND '$fechaFinMesAnterior3') OR (fecha BETWEEN '$fechaIniMesAnterior' AND '$fechaFinMesAnterior') OR (fecha BETWEEN '$fechaIniMesActual' AND '$fechaFinMesActual')) ORDER BY fecha");		
		//Verificar la consulta y extraer los datos
		if($conceptos=mysql_fetch_array($rsConceptos)){
			//Desplegar los encabezados de la Tabla
			echo "				
			<table cellpadding='5' width='100%'>				
				<tr>
					<td colspan='6' align='center' class='titulo_etiqueta'>$msg_titulo</td>
				</tr>
				<tr>
					<td rowspan='2' class='nombres_columnas' align='center'>CONCEPTO</td>
					<td rowspan='2' class='nombres_columnas' align='center'>UNIDAD</td>
					<td colspan='3' class='nombres_columnas' align='center'>MES</td>
					<td rowspan='2' class='nombres_columnas' align='center'>PROMEDIO</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>$mesAnterior</td>
					<td class='nombres_columnas' align='center'>$mesIniPer</td>
					<td class='nombres_columnas' align='center'>$mesFinPer</td>
				</tr>";
			$flag=1;
			//Variables que acumulan la Produccion por Mes NO APLICA PARA INSTALACION DE MALLAS
			$totalMes3=0;//Mes Actual
			$totalMes2=0;//Mes Anterior
			$totalMes1=0;//Mes Primero(2 Meses Anterior al Actual)
			$totalPromedio=0;
			//Nombre de la Clase
			$nom_clase = "renglon_gris";
			//contador
			$cont = 1;
			do{
				//Produccion Mes Actual
				$prodMes3=0;
				//Produccion Mes Anterior
				$prodMes2=0;
				//Produccion del Primer Mes (calculo hecho en base al mes actual y los 2 anteriores)
				$prodMes1=0;
				//Extraer la cantidad de produccion realizada en el concepto indicado en el Primer Mes
				$produccion=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM bitacora_zarpeo WHERE destino='$nomUbicacion' AND fecha BETWEEN '$fechaIniMesAnterior3' AND '$fechaFinMesAnterior3' AND aplicacion='$conceptos[aplicacion]'"));
				if($produccion["total"]!=NULL)
					$prodMes1=$produccion["total"];
				//Extraer la cantidad de produccion realizada en el concepto indicado en el Segundo Mes
				$produccion=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM bitacora_zarpeo WHERE destino='$nomUbicacion' AND fecha BETWEEN '$fechaIniMesAnterior' AND '$fechaFinMesAnterior' AND aplicacion='$conceptos[aplicacion]'"));
				if($produccion["total"]!=NULL)
					$prodMes2=$produccion["total"];
				//Extraer la cantidad de produccion realizada en el concepto indicado en el Tercer Mes
				$produccion=mysql_fetch_array(mysql_query("SELECT SUM(cantidad) AS total FROM bitacora_zarpeo WHERE destino='$nomUbicacion' AND fecha BETWEEN '$fechaIniMesActual' AND '$fechaFinMesActual' AND aplicacion='$conceptos[aplicacion]'"));
				if($produccion["total"]!=NULL)
					$prodMes3=$produccion["total"];
				
				if($conceptos["aplicacion"]=="INSTALACION MALLA")
					$unidadMedida="M&sup2;";
				else{
					$unidadMedida="M&sup3;";
					$totalMes3+=$prodMes3;
					$totalMes2+=$prodMes2;
					$totalMes1+=$prodMes1;
					$totalPromedio+=(($prodMes1+$prodMes2+$prodMes3)/3);
				}
				echo "<tr>";
					echo "<td class='nombres_columnas' align='center'>$conceptos[aplicacion]</td>";
					echo "<td class='$nom_clase' align='center'>$unidadMedida</td>";
					echo "<td class='$nom_clase' align='center'>".number_format($prodMes1,2,".",",")."</td>";
					echo "<td class='$nom_clase' align='center'>".number_format($prodMes2,2,".",",")."</td>";
					echo "<td class='$nom_clase' align='center'>".number_format($prodMes3,2,".",",")."</td>";
					echo "<td class='$nom_clase' align='center'>".number_format((($prodMes1+$prodMes2+$prodMes3)/3),2,".",",")."</td>";
				echo "</tr>";
				
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";	
			}while($conceptos=mysql_fetch_array($rsConceptos));
			echo "<tr>";
				echo "<td class='nombres_columnas' align='center'>Total Mes</td>";
				echo "<td class='nombres_filas' align='center'>M&sup3;</td>";
				echo "<td class='nombres_filas' align='center'>".number_format($totalMes1,2,".",",")."</td>";
				echo "<td class='nombres_filas' align='center'>".number_format($totalMes2,2,".",",")."</td>";
				echo "<td class='nombres_filas' align='center'>".number_format($totalMes3,2,".",",")."</td>";
				echo "<td class='nombres_filas' align='center'>".number_format($totalPromedio,2,".",",")."</td>";				
			echo "</tr>";
			//Calcular y Mostrar la Productividad
			$prod1=$totalMes1/$_POST["hdn_empleados"]/26;
			$prod2=$totalMes2/$_POST["hdn_empleados"]/26;
			$prod3=$totalMes3/$_POST["hdn_empleados"]/26;
			$prodPromedio=$totalPromedio/$_POST["hdn_empleados"]/26;
			echo"
				<tr><td colspan='6'>&nbsp;</td></tr>
				<tr>
					<td class='nombres_columnas' align='center'>PRODUCTIVIDAD</td>
					<td class='nombres_columnas' align='center'>M&sup3;/PERSONA/D&Iacute;A</td>
					<td class='nombres_columnas' align='center'>".number_format($prod1,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>".number_format($prod2,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>".number_format($prod3,2,".",",")."</td>
					<td class='nombres_columnas' align='center'>".number_format($prodPromedio,2,".",",")."</td>
				</tr>";
			echo "</table>";
		}
		else{
			$flag=0;
			echo "<br /><br /><br /><br /><br /><br /><br /><br /><br /><br />";
			echo "<p align='center'>$msg_error</p>";
		}
		return $flag;
	}
?>