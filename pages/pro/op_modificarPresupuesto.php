<?php
	/**
	  * Nombre del Módulo: Producción
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 13/Julio/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Modificar Presupuesto en la BD
	**/
	
	 //Funcion para mostrar la informacion del presupuesto
	function mostrarPresupuestos(){
		//Conectar a la BD de producción
		$conn = conecta("bd_produccion");
		$periodo=$_POST['cmb_periodo'];
		$destino=$_POST['cmb_destino'];
		
		//Obtener el nombre de la ubicacion para colocarlo en el titulo de la tabla
		$Nomdestino= obtenerDato("bd_produccion","catalogo_destino","destino","id_destino",$destino);
		
		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM presupuesto WHERE periodo = '$periodo' AND catalogo_destino_id_destino = '$destino'";
		
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Presupuestos del periodo de <em><u>   $periodo </u></em> del Destino <em><u>  $Nomdestino </u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Presupuesto Registrado del periodo de <em><u>  '$periodo'    
		</u></em> </u></em> de Destino <em><u>  '$Nomdestino' </u></em>";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "
			<table cellpadding='5' width='100%'>				
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
					<td class='nombres_columnas' align='center'>D&Iacute;AS H&Aacute;BILES</td>
					<td class='nombres_columnas' align='center'>D&Iacute;AS INH&Aacute;BILES</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='checkbox' name='ckb_idPresupuesto' value= $datos[id_presupuesto]
						onClick='javascript:document.frm_seleccionarPresupuesto.submit();' />
						</td>
						<td class='$nom_clase' align='center'>$datos[periodo]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_inicio'],1)."</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_fin'],1)."</td>
						<td class='$nom_clase' align='center'>".number_format($datos['vol_ppto_mes'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>".number_format($datos['vol_ppto_dia'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>$datos[dias_habiles]</td>
						<td class='$nom_clase' align='center'>$datos[dias_inhabiles]</td>
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
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	
	 //Funcion para guardar la informacion del presupuesto
	function guardarModPresupuesto(){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");

		//Recuperar la informacion del post
		$fechaInicio = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		$volPresMensual= str_replace(",","",$_POST['txt_volPresupuestado']);
		$volDiario= str_replace(",","",$_POST['txt_presupuestoDiario']);
		$idPresupuesto=$_POST['hdn_claveDefinida'];
		$diasLaborables= str_replace(",","",$_POST['txt_diasLaborales']);
		$domingos= str_replace(",","",$_POST['txt_domingos']);
		
		//Verificamos si viene el combo Activo de ubicación o viene de la caja de texto para proceder a almacenarlo
		if (isset($_POST["cmb_destino"]))
			$destino=$_POST["cmb_destino"];
		else{
		
			$destino=strtoupper($_POST["txt_nuevoDestino"]);
			$idNvoDestino=generarIdNvoDestino();
			//Mandar llamar la funcion que ingresa la nueva ubicacion
			guardarDestino($idNvoDestino,$destino);
			$destino=$idNvoDestino;
		}	
		//Obtener el periodo que corresponde		
		$periodo=substr($_POST['txt_fechaFin'],-4)."-";		
		$periodo.=obtenerPeriodo($fechaInicio,$fechaFin);
		
		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");
	
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "UPDATE  presupuesto SET catalogo_destino_id_destino='$destino', periodo='$periodo',fecha_inicio='$fechaInicio',fecha_fin='$fechaFin',
		vol_ppto_mes='$volPresMensual',vol_ppto_dia='$volDiario',dias_habiles='$diasLaborables',dias_inhabiles='$domingos' WHERE id_presupuesto='$idPresupuesto'";		
			
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_produccion",$idPresupuesto,"ModificarPresupuesto",$_SESSION['usr_reg']);
			$conn = conecta("bd_produccion");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
 		//Cerrar la conexion con la BD		
		mysql_close($conn); 
	 }// Fin function guardarMezcla()	
		
	//Esta funcion Obtiene el periodo correspondiente segun la fecha 
	function obtenerPeriodo($fechaini,$fechafin){
		$inicio=substr($fechaini,5,2);
		switch($inicio){
			case "01":
				$inicio="ENE";
			break;
			case "02":
				$inicio="FEB";
			break;
			case "03":
				$inicio="MAR";
			break;
			case "04":
				$inicio="ABR";
			break;
			case "05":
				$inicio="MAY";
			break;
			case "06":
				$inicio="JUN";
			break;
			case "07":
				$inicio="JUL";
			break;
			case "08":
				$inicio="AGO";
			break;
			case "09":
				$inicio="SEP";
			break;
			case "10":
				$inicio="OCT";
			break;
			case "11":
				$inicio="NOV";
			break;
			case "12":
				$inicio="DIC";
			break;
			default:
				$inicio=""; 
		}
		
		$fin=substr($fechafin,5,2);
			switch($fin){
			case "01":
				$fin="ENE";
			break;
			case "02":
				$fin="FEB";
			break;
			case "03":
				$fin="MAR";
			break;
			case "04":
				$fin="ABR";
			break;
			case "05":
				$fin="MAY";
			break;
			case "06":
				$fin="JUN";
			break;
			case "07":
				$fin="JUL";
			break;
			case "08":
				$fin="AGO";
			break;
			case "09":
				$fin="SEP";
			break;
			case "10":
				$fin="OCT";
			break;
			case "11":
				$fin="NOV";
			break;
			case "12":
				$fin="DIC";
			break;
			default:
				$fin=""; 
		}
		return $inicio."-".$fin;
	}// FIN obtenerPeriodo
	
	
	function generarIdNvoDestino(){
		//Realizar la conexion a la BD de Produccion
		$conn = conecta("bd_produccion");
		$id="";
		//Crear la sentencia para obtener el presupuesto Reciente
		$stm_sql = "SELECT COUNT(id_destino) AS num, MAX(id_destino)+1 AS cant FROM catalogo_destino";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
		
			if($datos['num']>0){
				$id .= ($datos['cant']);
			}
			else {
				$id .= "1";
			}
		}
		else 
			echo"error".mysql_error();		
		return $id;
	}

	function guardarDestino($id,$destino){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_produccion");

		//Crear la Sentencia SQL para Alamcenar la nueva ubicacion  ubicacion
		$stm_sql= "INSERT INTO catalogo_destino(id_destino,destino)	VALUES ('$id','$destino')";		

		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);

		//Cerrar la conexion con la BD		
		mysql_close($conn); 
	}		
?>