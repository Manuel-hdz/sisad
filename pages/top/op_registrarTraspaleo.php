<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Miguel Angel Garay Castro
	  * Fecha: 01/Junio/2011                                     			
	  * Descripción: Este archivo contiene las funciones para guardar los datos de Traspaleo de las Obras seleccionadas
	  **/

	  
	//Esta función se encarga de generar el Id de los Registros Traspaleo
	function obtenerIdTraspaleo(){
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
		
		//Definir las tres letras en la Id del Traspaleo
		$id_cadena = "TRP";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener los Traspaleos Reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_traspaleo) AS cant FROM traspaleos WHERE id_traspaleo LIKE 'TRP$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras del Id de Traspaleo
			$cant = substr($datos['cant'],-3)+1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id_cadena;
	}//Fin de la function obtenerIdTraspaleo()
	 
	 
	/*Esta funcion registra los datos de Traspaleo en la Base de Datos de la Obra Seleccionada*/
	function guardarRegistrosTraspaleo(){				
		//Obtener los datos para registrar el Traspaleo
		$idTraspaleo = obtenerIdTraspaleo();				
		
		//Variables para saber si las operaciones se realizaron con Exito y manejo de errores
		$status = 0;
		$error = "";
		
		//Obtener los datos Generales del Traspaleo de la SESSION
		$idObra = $_SESSION['datosTraspaleo']['idObra'];
		$noQuincena= strtoupper($_SESSION['datosTraspaleo']['noQuincena']);
		$acumulado = str_replace(",","",$_SESSION['datosTraspaleo']['acumQuincena']);
		$volumen = str_replace(",","",$_SESSION['datosTraspaleo']['volumen']);
		$tasaCambio = str_replace(",","",$_SESSION['datosTraspaleo']['tasaCambio']);
		//Obtener la Tasa de Cambio Original
	 	$tCambioOriginal=obtenerdato("bd_topografia","tasa_cambio","t_cambio","id",1);
		
		//Si la Obra no esta registrada en el Catalogo, obtener el ID de obra siguiente para registrarla
		if($idObra=="OBRA_NR"){
			$idObra = obtenerIdObra();//Obtener nuevo id de obra segun el catalogo de obras registrado
		}
			
		//Conectarse a la BD de Topografia
		$conn = conecta("bd_topografia");

		//Verificar si el valor de la Tasa cambio para actualizar el dato
		if($tasaCambio!=$tCambioOriginal)
			mysql_query("UPDATE tasa_cambio SET t_cambio='$tasaCambio' WHERE id='1'");

		//Crear la Sentencia para almacenar los datos del Traspaleo en la Base de Datos
		$sql_stm = "INSERT INTO traspaleos (id_traspaleo,obras_id_obra,no_quincena,acumulado_quincena,volumen,t_cambio) 
					VALUES('$idTraspaleo','$idObra','$noQuincena',$acumulado,$volumen,$tasaCambio)";
		//Ejecutar la Sentencia
		$rs = mysql_query($sql_stm);
		
		if(!$rs){//Si existe algun error, activar la variable de status			
			$status = 1;
			$error = mysql_error();
		}
		
		if($status==0){//Si no hubo errores, guardar los registros del Traspaleo
			$contRegistros = 1;
			foreach($_SESSION['registrosTraspaleo'] as $ind => $registro){
				//Obtener los datos de los Registro de la SESSION
				$fechaRegistro = modFecha($registro['fecha'],3);
				$origen = strtoupper($registro['origen']);
				$destino = strtoupper($registro['destino']);
				$distancia = str_replace(",","",$registro['distancia']);
				$precioMN = str_replace(",","",$registro['precioMN']);
				$precioUSD = str_replace(",","",$registro['precioUSD']); 		
				$totalMN = str_replace(",","",$registro['totalMN']);
				$totalUSD = str_replace(",","",$registro['totalUSD']);
				$importe = str_replace(",","",$registro['importeTotal']);	
				
				//Crear la Sentencia para guardar cada registro del Traspaleo
				$sql_stm = "INSERT INTO detalle_traspaleos(traspaleos_id_traspaleo,no_registro,origen,destino,distancia,pu_mn,pu_usd,total_mn,
							total_usd,importe_total,fecha_registro) 
							VALUES('$idTraspaleo',$contRegistros,'$origen','$destino',$distancia,$precioMN,$precioUSD,$totalMN,$totalUSD,$importe,'$fechaRegistro')";
							
				$rs = mysql_query($sql_stm);
				
				if(!$rs){
					$status = 1;
					$error = mysql_error();
					break;
				}
				
				//Aumentar en 1 el Contador de Registros
				$contRegistros++;
			}//Cierre foreach($_SESSION['registrosTraspaleo'] as $ind => $registro)
		}
		
		
		//Si no hubo errores, guardar el registro en la Bitacora de Movimientos
		if($status==0){
			//Guardar la operacion realizada
			registrarOperacion("bd_topografia",$idTraspaleo,"RegistrarTraspaleoEnObra",$_SESSION['usr_reg']);			
			
			//Si la obra no esta registrada en el Catalogo(id de obra igual a OBRA_NR), proceder a registrarla
			if($_SESSION['datosTraspaleo']['tipoObra']=="OBRA_NR"){
				//Recuperar la Lista de precios del POST
				$listaPrecios = obtenerDato("bd_topografia", "precios_traspaleo", "id_precios", "tipo", $_POST['cmb_listaPrecios']);
				
				$conn = conecta("bd_topografia");//reconectar con la BD de Topografía
				//Extraer el id de subcategoria que sea similar a DESBORDE
				$datoSubcategoria=mysql_fetch_array(mysql_query("SELECT id FROM subcategorias WHERE subcategoria LIKE '%DESB%'"));
				$idSubcategoria=$datoSubcategoria[0];
				
				mysql_query("INSERT INTO obras (id_obra, subcategorias_id, precios_traspaleo_id_precios, categoria, tipo_obra, nombre_obra, seccion, area, unidad, pumn_estimacion, 
							puusd_estimacion, fecha_registro)
							VALUES ('$idObra','$idSubcategoria','$listaPrecios','".$_SESSION['categoriaObra']."','TEMP','".$_SESSION['datosTraspaleo']['nomObra']."','N/A','0','N/A',
							'N/A','N/A','".date("Y-m-d")."')");													
				//Guardar la operacion realizada
				registrarOperacion("bd_topografia",$idObra,"RegistrarObraTemp",$_SESSION['usr_reg']);
			}
			
			
			//LIberar los datos de la SESSION
			unset($_SESSION['datosTraspaleo']);
			unset($_SESSION['registrosTraspaleo']);
			unset($_SESSION['categoriaObra']);
			
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";			
		}													
		else{			
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}																									
	}//Cierre de la funcion guardarRegistrosTraspaleo()
	
	
	/*Esta funcion cargará los Datos Generales del Traspaleo en la SESSION*/
	function subirDatosSession(){											
		//Obtener el numero de la Quincena
		$numQuincena = $_POST['cmb_noQuincena']." ".$_POST['cmb_Mes']." ".$_POST['cmb_Anio'];
		
		$_SESSION['datosTraspaleo'] = array("tipoObra"=>$_POST['txt_tipoObra'],"nomObra"=>strtoupper($_POST['txt_nombreObra']),"idObra"=>$_POST['hdn_idObra'],
		"acumQuincena"=>$_POST['txt_acumuladoQuincena'],"tasaCambio"=>$_POST['txt_tasaCambio'],"seccion"=>$_POST['txt_seccion'],"area"=>$_POST['txt_area'],
		"volumen"=>$_POST['txt_volumen'],"noQuincena"=>$numQuincena);
	}//Cierre de la funcion subirDatosSession()
	
	
	/*Esta función guarda cada registro del traspaelo en la SESSION*/
	function cargarRegistrosTraspaleo(){			
		//Si el Arreglo ya esta definido en la SESSION, agregar registro
		if(isset($_SESSION['registrosTraspaleo'])){
			//Verificar que el registro no este duplicado mediante los datos de Origen, Destino y Distancia		
			$repetido = 0;				
			foreach($_SESSION["registrosTraspaleo"] as $ind => $registro){
				if($_POST["txt_origen"]==$registro["origen"] && $_POST["txt_destino"]==$registro["destino"] && $_POST["txt_distancia"]==$registro["distancia"]){
					$repetido = 1;
					break;
				}
			}
			//Si el registro no esta 
			if($repetido==0){						
				$_SESSION['registrosTraspaleo'][] = array("fecha"=>$_POST['txt_fechaRegistro'],"origen"=>$_POST['txt_origen'],"destino"=>$_POST['txt_destino'],
														"distancia"=>$_POST['txt_distancia'],"precioMN"=>$_POST['txt_pumn'],"precioUSD"=>$_POST['txt_puusd'],
														"totalMN"=>$_POST['txt_totalMN'],"totalUSD"=>$_POST['txt_totalUSD'],"importeTotal"=>$_POST['txt_importeTotal']);
			}
			else{//Mostrar Mensaje al Usuario de que no se permiten registros identicos?>
				<script type="text/javascript" language="javascript">
					setTimeout("alert('No se Permiten Registros Duplicados');",500);
				</script><?php
			}
		}
		//Si el arreglo no esta definido en la SESSION, crearlos y agregarlo
		else{
			if($_POST['hdn_incluirPrecio']=="no"){
				$_SESSION['registrosTraspaleo'] = array(array("fecha"=>$_POST['txt_fechaRegistro'],"origen"=>$_POST['txt_origen'],"destino"=>$_POST['txt_destino'],
															"distancia"=>$_POST['txt_distancia'],"precioMN"=>0,"precioUSD"=>0,"totalMN"=>0,"totalUSD"=>0,"importeTotal"=>0));
			}
			else if($_POST['hdn_incluirPrecio']=="si"){
				$_SESSION['registrosTraspaleo'] = array(array("fecha"=>$_POST['txt_fechaRegistro'],"origen"=>$_POST['txt_origen'],"destino"=>$_POST['txt_destino'],
															"distancia"=>$_POST['txt_distancia'],"precioMN"=>$_POST['txt_pumn'],"precioUSD"=>$_POST['txt_puusd'],
															"totalMN"=>$_POST['txt_totalMN'],"totalUSD"=>$_POST['txt_totalUSD'],"importeTotal"=>$_POST['txt_importeTotal']));	
			}
		}								
	}//Cierre de la funcion cargarRegistrosTraspaleo()
	
	
	/*Esta funcion muestra los registro de Traspaleo agregados a la SESSION*/
	function mostrarRegistrosTraspaleo(){
		
		echo "
			<table width='100%' cellpadding='5'>
				<tr>
					<td class='nombres_columnas'>FECHA</td>
					<td class='nombres_columnas'>N&Uacute;MERO</td>
					<td class='nombres_columnas'>ORIGEN</td>
					<td class='nombres_columnas'>DESTINO</td>
					<td class='nombres_columnas'>DISTANCIA</td>
					<td class='nombres_columnas'>P.U.M.N.</td>
					<td class='nombres_columnas'>P.U.USD</td>
					<td class='nombres_columnas'>TOTAL M.N.</td>
					<td class='nombres_columnas'>TOTAL USD</td>
					<td class='nombres_columnas'>IMPORTE TOTAL</td>					
				</tr>";
				
		$nom_clase = "renglon_gris";
		$cont = 1;
		$sumatoria = 0;
		foreach($_SESSION['registrosTraspaleo'] as $ind => $registro){
			echo "
				<tr>
					<td class='nombres_filas'>".$registro['fecha']."</td>
					<td class='$nom_clase'>".$cont."</td>
					<td class='$nom_clase'>".$registro['origen']."</td>
					<td class='$nom_clase'>".$registro['destino']."</td>
					<td class='$nom_clase'>".$registro['distancia']."</td>
					<td class='$nom_clase'>".$registro['precioMN']."</td>
					<td class='$nom_clase'>".$registro['precioUSD']."</td>
					<td class='$nom_clase'>".$registro['totalMN']."</td>
					<td class='$nom_clase'>".$registro['totalUSD']."</td>
					<td class='$nom_clase'>".$registro['importeTotal']."</td>
				</tr>";
			//Acumular el Costo total de los Movimeintos realizados en Traspaleo
			$sumatoria += str_replace(",","",$registro['importeTotal']);
			
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			
		}		
		echo "
			<tr>
				<td colspan='9' align='right'><strong>TOTAL</strong></td>
				<td>$ ".number_format($sumatoria,2,".",",")."</td>
			</tr>
		</table>";
	}//Cierre de la funcion mostrarRegistrosTraspaleo()
	
	
?>