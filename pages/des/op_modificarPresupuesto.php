<?php
	/**
	  * Nombre del Módulo: Desarrollo
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 08/Diciembre/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de Modificar Presupuesto en la BD de Desarrollo
	**/
	
	 //Funcion para mostrar la informacion del presupuesto
	function mostrarPresupuestos(){
		//Conectar a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		$periodo=$_POST['cmb_periodo'];
		$cliente=$_POST['cmb_cliente'];
		
		//Obtener el nombre de la ubicacion para colocarlo en el titulo de la tabla
		$Nomubicacion= obtenerDato("bd_desarrollo","catalogo_clientes","nom_cliente","id_cliente",$cliente);
		
		$Nomubicacion2= obtenerDato("bd_desarrollo","catalogo_clientes","nom_cliente","nom_cliente",$cliente);
		//function obtenerDatoBD(datoBusq,nomBD,nomTabla,nomCampoBusq,nomCampoRef,nomTxtCargar){

		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM presupuesto WHERE periodo= '$periodo' AND catalogo_clientes_id_cliente='$cliente'";	
				
		//Crear el mensaje que se mostrara en el titulo de la tabla
		$msg= "Presupuestos del periodo de <em><u>  $periodo </u></em> del Cliente <em><u>  $Nomubicacion </u></em>";
		
		//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
		$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ningun Presupuesto Registrado del periodo de <em><u>  '$periodo'    
		 </u></em> del Cliente <em><u>  '$Nomubicacion2' </u></em>";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='120%'>				
				<tr>
					<td colspan='12' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>PERIODO</td>
					<td class='nombres_columnas' align='center'>FECHA INICIO</td>
					<td class='nombres_columnas' align='center'>FECHA FIN</td>
					<td class='nombres_columnas' align='center'>MTS. AL MES</td>
					<td class='nombres_columnas' align='center'>MTS. PRESUPUESTADOS DIARIOS</td>
					<td class='nombres_columnas' align='center'>MTS. QUINCENA 1</td>
					<td class='nombres_columnas' align='center'>MTS. QUINCENA 2</td>
					<td class='nombres_columnas' align='center'>DISPAROS DIA</td>
					<td class='nombres_columnas' align='center'>DISPAROS TURNO</td>
					<td class='nombres_columnas' align='center'>DIAS HABILES</td>
					<td class='nombres_columnas' align='center'>DIAS INHABILES</td>			
				</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='checkbox' name='ckb_idPresupuesto' value= '$datos[id_presupuesto]'
						onClick='javascript:document.frm_seleccionarPresupuesto.submit();' />
						</td>
						<td class='$nom_clase' align='center'>$datos[periodo]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_inicio'],1)."</td>
						<td class='$nom_clase' align='center'>".modFecha($datos['fecha_fin'],1)."</td>
						<td class='$nom_clase' align='center'>".number_format($datos['mts_mes'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>".number_format($datos['mts_mes_dia'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>".number_format($datos['mts_quincena1'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>".number_format($datos['mts_quincena2'],2,".",",")."m&sup3;</td>
						<td class='$nom_clase' align='center'>".number_format($datos['disparos_dia'])."</td>																		
						<td class='$nom_clase' align='center'>".number_format($datos['disparos_turno'])."</td>																		
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
		$conn = conecta("bd_desarrollo");
		//Recuperar la informacion del post
		$diasHabiles = str_replace(",","",$_POST['txt_diasLaborales']);
		$diasInhabiles = str_replace(",","",$_POST['txt_domingos']);
		$fechaInicio = modFecha($_POST['txt_fechaIni'],3);
		$fechaFin = modFecha($_POST['txt_fechaFin'],3);
		$mtsPres = str_replace(",","",$_POST['txt_mtsPresupuestados']);
		$mtsPresDiario = str_replace(",","",$_POST['txt_mtsPresupuestadosDiarios']);								
		$mtsQuin1 = str_replace(",","",$_POST['txt_mtsQuincena1']);
		$mtsQuin2 = str_replace(",","",$_POST['txt_mtsQuincena2']);
		$disDia = str_replace(",","",$_POST['txt_disparosDia']);
		$disTurno = str_replace(",","",$_POST['txt_disparosTurno']);
		$idPresupuesto=$_POST['hdn_claveDefinida'];
		$idViejo =$_POST['hdn_claveDefinida'];
		
		
		//Obtener el id del presupuesto
		$idPresupuesto=obtenerIdPresupuesto();					
				
		//Verificamos si viene el combo Activo el cliente o viene de la caja de texto para proceder a almacenarlo
		if (isset($_POST["cmb_cliente"]) && $_POST["cmb_cliente"]!=""){
			$cliente=$_POST["cmb_cliente"];
			$cliente=obtenerDato("bd_desarrollo", "catalogo_clientes", "id_cliente", "nom_cliente", $cliente);
		}
		else{
			$cliente=strtoupper($_POST["txt_nuevoCliente"]);
			$idCliente=generarIdCliente();
			//Mandar llamar la funcion que guarda el nuevo destino
			guardarCliente($idCliente,$cliente);
			$cliente=$idCliente;	 	
		}	
		
		//Obtener el periodo que corresponde
		$periodo=substr($_POST['txt_fechaFin'],-4)."-";		
		$periodo.=obtenerPeriodo($fechaInicio,$fechaFin);

		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");

			$stm_sql= "UPDATE presupuesto SET catalogo_clientes_id_cliente='$cliente', dias_habiles='$diasHabiles', dias_inhabiles='$diasInhabiles',
			periodo='$periodo', fecha_inicio='$fechaInicio', fecha_fin='$fechaFin', mts_mes='$mtsPres', mts_mes_dia='$mtsPresDiario', 
			mts_quincena1='$mtsQuin1', mts_quincena2='$mtsQuin2', disparos_dia='$disDia', disparos_turno='$disTurno' WHERE id_presupuesto='$idViejo'";
		//}
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_desarrollo",$idPresupuesto,"ModificarPresupuesto",$_SESSION['usr_reg']);
			$conn = conecta("bd_desarrollo");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
 		//Cerrar la conexion con la BD		
		//mysql_close($conn); 
	 }// Fin function guardarMezcla()	
	
	
	/*Esta funcion genera el id del presupuesto de acuerdo a los registros en la BD*/
	function obtenerIdPresupuesto(){
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		$id="";
		//Crear la sentencia para obtener el presupuesto Reciente
		$stm_sql = "SELECT MAX(id_presupuesto) AS cant FROM presupuesto";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
				$id .= $cant;
		}
		return $id;
	}//Fin de la function obtenerIdPresupuesto()
	
	
	
	function guardarCliente($id,$cliente){
		//Conectar se a la Base de Datos
		$conn = conecta("bd_desarrollo");

		//Crear la Sentencia SQL para Almacenar la nueva ubicacion 
		$stm_sql= "INSERT INTO catalogo_clientes(id_cliente, nom_cliente)	VALUES ('$id','$cliente')";		
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);

		//Cerrar la conexion con la BD		
		mysql_close($conn); 
	}
	
	function generarIdCliente(){
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_desarrollo");
		$id="";
		//Crear la sentencia para obtener el presupuesto Reciente
		$stm_sql = "SELECT COUNT(id_cliente) AS num, MAX(id_cliente)+1 AS cant FROM catalogo_clientes";
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
	

?>