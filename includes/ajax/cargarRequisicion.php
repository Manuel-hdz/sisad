<?php
	/**
	  * Nombre del Módulo: Requisiciones
	  * Nombre Programador: Daisy Adriana Martínez Fernandez
	  * Fecha: 24/Abril/2012                                      			
	  * Descripción: Este archivo contiene la función que muestra las requisiciones de acuerdo a los parametros seleccionados
	  **/
	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	  *      1. Modulo de conexion con la base de datos*/
			include("../conexion.inc");
			include("../op_operacionesBD.php");
			include("../func_fechas.php");
			
			
	//Comprobar que existe la fechaInicial y la bd seleccionada
	if(isset($_GET["fechaIni"])&&isset($_GET["bd"])){
		//Convertimos las fechas a formato aaaa-mm-dd para BD
		$fechaIni = modFecha($_GET['fechaIni'],3);
		$fechaFin = modFecha($_GET['fechaFin'],3);
		//Variable que nos permitira decidir el tipo de consulta
		$parametro ="";
		//Comprobamos si esta definido el buscarPor; si es asi asignarle un valor al parametro
		if(isset($_GET['buscarPor'])){
			$parametro = strtolower($_GET['buscarPor']);
			$texto=strtoupper($_GET["notas"]);
		}
		//Seleccionamos la BD de acuerdo al parametro que viene en el GET
		$bd = obtenerBD($_GET['bd']);
		if($_GET['bd']==1)
			$area = "ALMACEN";
		if($_GET['bd']==3)
			$area = "MANTENIMIENTO CONCRETO";
		if($_GET['bd']==4)
			$area = "MANTENIMIENTO MINA' OR area_solicitante LIKE 'MANTENIMIENTO DESARROLLO";
		if($_GET['bd']==5)
			$area = "RECURSOS HUMANOS";
		if($_GET['bd']==6)
			$area = "TOPOGRAFÍA";
		if($_GET['bd']==7)
			$area = "LABORATORIO";
		if($_GET['bd']==8)
			$area = "PRODUCCIÓN";
		if($_GET['bd']==9)
			$area = "GERENCIA TÉCNICA";
		if($_GET['bd']==10)
			$area = "DESARROLLO";
		if($_GET['bd']==11)
			$area = "SEGURIDAD AMBIENTAL";
		if($_GET['bd']==13)
			$area = "ASEGURAMIENTO CALIDAD";
		if($_GET['bd']==16)
			$area = "PAILERIA";
		if($_GET['bd']==17)
			$area = "MANTENIMIENTO ELÉCTRICO";
		if($_GET['bd']==18)
			$area = "UNIDAD DE SALUD OCUPACIONAL";
		if($_GET['bd']==19)
			$area = "MANTENIMIENTO CONCRETO' OR area_solicitante='MANTENIMIENTO MINA";
		//Conectamos a la Base de Datos
		 $conn = conecta($bd);
		 //Iniciamos el contador
		 $cont=1;
		 
		 //Buscar la requisicion por el parametro seleccionado; si el parametro es igual a vacio no viene definido y entonces solo se contemplaran las fechas
		if($parametro!=""){
			$rs_reqs = mysql_query("SELECT DISTINCT id_requisicion FROM (requisiciones JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion)
									WHERE fecha_req BETWEEN '$fechaIni' AND '$fechaFin' AND $parametro LIKE '%$texto%' AND (area_solicitante = '$area') 
									ORDER BY id_requisicion");
			if($area == "PAILERIA" || $area == "ALMACEN"){
				$rs_reqs = mysql_query("SELECT DISTINCT id_requisicion FROM (requisiciones JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion)
									WHERE fecha_req BETWEEN '$fechaIni' AND '$fechaFin' AND $parametro LIKE '%$texto%' 
									ORDER BY id_requisicion");
			}
		}
		else{		
			$rs_reqs = mysql_query("SELECT DISTINCT id_requisicion FROM (requisiciones JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion)
									WHERE fecha_req BETWEEN '$fechaIni' AND '$fechaFin' AND (area_solicitante = '$area') ORDER BY id_requisicion");
			if($area == "PAILERIA" || $area == "ALMACEN"){
				$rs_reqs = mysql_query("SELECT DISTINCT id_requisicion FROM (requisiciones JOIN detalle_requisicion ON id_requisicion=requisiciones_id_requisicion)
										WHERE fecha_req BETWEEN '$fechaIni' AND '$fechaFin' ORDER BY id_requisicion");
			}
		}
		//Obtenemos el numero de registros obtenidos
		$tam = mysql_num_rows($rs_reqs);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");	 
		//Comparar los resultados obtenidos 
		if($datos=mysql_fetch_array($rs_reqs)){
			echo "<existe><valor>true</valor><tam>$tam</tam>";
			do{
				//Prevenir errores en el archivo XML generado, cuando el texto contenga acentos o algun caracter especial
				echo utf8_encode("<dato$cont>$datos[id_requisicion]</dato$cont>");
				$cont++;
			}while($datos=mysql_fetch_array($rs_reqs));
			echo "</existe>";
		}
		else{
			echo "<valor>false</valor>";
		}
		mysql_close($conn);
	}//if (isset($_GET["fechaIni"])&&isset($_GET["bd"])){	
	
	//Verificamos si esta definida la clave de la requision
	if(isset($_GET['idReq'])){
		//Almacenamos el valor de la clave de requisicion
		$idReq = $_GET["idReq"];
		//Seleccionamos la BD
		$bd = $_GET['bd'];
		//Obtener el codigo HTML de la tabla
		$codigoHTMLTabla = crearTablaReq($idReq, $bd);
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		//Si el codigo HTML de la tabla es diferente de vacio proceder a crear el código XML 
		if($codigoHTMLTabla!=""){
			//Obtenemos el estado de la requisicion
			$secEstado =split("/#/",$codigoHTMLTabla);
			$estado = $secEstado[0];
			$tabla =$secEstado[1];
			//Sustituir el tag de apertura '<' por el simbolo '¬' para que no tenga conflictos con los tags del codigo XML que serán generados
			$tabla = str_replace("<","¬",$tabla);
			//Crear XML con el codigo HTML de la tabla
			echo utf8_encode("
				<existe>
					<valor>true</valor>
					<tabla>$tabla</tabla>}
					<estado>$estado</estado>					
				</existe>");
		}
		else{
			//Crear XML que indica que no se produjeron resultados
			echo utf8_encode("
				<existe>
					<valor>false</valor>
				</existe>");
		}
	}//Cierre if(isset($_GET["idReq"]))	

	/*Esta función genera el codigo de la tabla que mostrará los datos de la(s) Obra(s) Seleccionada(s)*/
	function crearTablaReq($idReq, $bd){
		//Funcion que nos permite obtener la base de datos segun corresponda
		$base = obtenerBD($bd);
		//Obtenemos la Hora de Publicación sin Formato
		$horaSF = obtenerDato($base, "bitacora_movimientos", "hora", "id_operacion", $idReq);
		//Formateamos la hora obtenida
		$hora = modHora($horaSF);		
		//Conectarse a la BD que corresponda
		$conn = conecta($base);		
		//Esta variable contendrá el código HTML de la tabla
		$codHtmlTabla = "";
		$sql_stmGral = "";
		$stm_sqlDetalles = "";
		$msg = "";
		//Crear la Sentencia SQL que permite obtener los datos generales de la requisicion
		$sql_stmGral = "SELECT fecha_req, requisiciones.estado, comentario_compras,cant_req, unidad_medida,descripcion, aplicacion FROM (requisiciones JOIN 		
						detalle_requisicion ON requisiciones_id_requisicion=id_requisicion) WHERE id_requisicion='$idReq'";
		//Ejecutar Sentencia			
		$rsGral = mysql_query($sql_stmGral);		
		//Asignamos el color al renglon inicial
		$nom_clase = "renglon_gris";
		//Verificamos la existencia de datos
		if($datosGral=mysql_fetch_array($rsGral)){
			//Definir el Titulo de la tabla de acuerdo ala requisicion seleccionada
			$msg = "Datos de la Requisicion $idReq";
			//Obtenemos el estado y lo guardamos en una variable; ya que esta variable se envia para verificar si se muestra o no el boton Generar Pedido en 
			//Gerencia Técnica
			$estado = $datosGral['estado'];
			$estilo_colum = "";
			$estilo_fila = "";
			if($base != "bd_paileria"){
				$estilo_colum = "nombres_columnas";
				$estilo_fila = "nombres_filas";
			}
			else{
				$estilo_colum = "nombres_columnas_gomar";
				$estilo_fila = "nombres_filas_gomar";
			}
			$codHtmlTabla = "
				<table width='100%' cellpadding='5'>
					<caption class='titulo_etiqueta'>$msg</caption>								
					<tr>
						<td align='center' class='$estilo_colum'>REQUISICION</td>
						<td align='center' class='$estilo_colum'>FECHA</td>
						<td align='center' class='$estilo_colum'>ESTADO</td>
						<td align='center' class='$estilo_colum'>HORA DE CREACIÓN</td>
						<td colspan='2' align='center' class='$estilo_colum'>COMENTARIOS</td>
					</tr>
					<tr>
						<td align='center' class='$nom_clase'>$idReq</td>
						<td align='center' class='$nom_clase'>".modFecha($datosGral['fecha_req'],1)."</td>
						<td align='center' class='$nom_clase'>$datosGral[estado]</td>
						<td align='center' class='$nom_clase'>$hora</td>
						<td colspan='2' align='center' class='$nom_clase'>$datosGral[comentario_compras]</td>
					</tr>
					<tr>
						<td colspan='5'>
							<p class='titulo_etiqueta' align='center'>Detalle de la Requisición $idReq</p>
						</td>
					</tr>
					<tr>
						<td class='$estilo_colum' align='center'>NO.</td>
						<td class='$estilo_colum' align='center'>CANTIDAD</td>
						<td class='$estilo_colum' align='center'>UNIDAD</td>
						<td class='$estilo_colum' align='center'>DESCRIPCIÓN</td>
						<td class='$estilo_colum' align='center'>APLICACIÓN</td>
					</tr>";
			//Definir el estilo de los renglones que compondran la tabla generada
			$nom_clase = "renglon_gris";
			$cont = 1;
			do{
				$codHtmlTabla .= "
					<tr>
						<td class='$estilo_fila' align='center'>$cont</td>
						<td class='$nom_clase' align='center'>$datosGral[cant_req]</td>
						<td class='$nom_clase' align='center'>$datosGral[unidad_medida]</td>
						<td class='$nom_clase' align='left'>$datosGral[descripcion]</td>
						<td class='$nom_clase' align='center'>$datosGral[aplicacion]</td>
					</tr>";
								
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datosGral=mysql_fetch_array($rsGral));
			//Cerrar la Tabla
			$codHtmlTabla .= "</table>";
			
		}//Cierre ($datos=mysql_fetch_array($rs))
		//Retornar el codigo de la tabla generada
		return $estado."/#/".$codHtmlTabla;
				
	}//Cierre de la función crearCodigoTabla($idObra)
	
	
	//Funcion que permitr obtener la base de datos correspondiente dependiendo del número de BD que viene definido en el GET
	function  obtenerBD($bd){
		$base = "";		
		switch($bd){
			case 1:		$base = "bd_almacen";		 break;
			case 3:		$base = "bd_mantenimiento";	 break;
			case 4:		$base = "bd_mantenimiento";	 break;
			case 5:		$base = "bd_recursos";	     break;
			case 6:		$base = "bd_topografia";	 break;
			case 7:		$base = "bd_laboratorio";	 break;
			case 8:		$base = "bd_produccion";	 break;
			case 9:		$base = "bd_gerencia";	     break;
			case 10:	$base = "bd_desarrollo";	 break;
			case 11:	$base = "bd_seguridad";	     break;
			case 13:	$base = "bd_aseguramiento";	 break;
			case 16:	$base = "bd_paileria";	     break;
			case 17:	$base = "bd_mantenimientoe"; break;
			case 18:	$base = "bd_clinica";        break;
			case 19:	$base = "bd_mantenimiento";	 break;
		}		
		//Regresar el mes correspondiente
		return $base;
	}//Cierre de la funcion obtenerBD($bd)
?>