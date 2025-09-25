<?php
require('mysql_table.php');
require("../conexion.inc");
include("../func_fechas.php");


class PDF extends PDF_MySQL_Table{

	function Header(){
		//Logo
		$this->Image('fondo-requisicion.jpg',-1,0,212);
	    $this->Image('logo-clf.jpg',10,6,30);
	    //Arial bold 15
	    $this->SetFont('Arial','B',7.5);
		$this->SetTextColor(0, 0, 0);
	    //Move to the right
	    $this->Cell(60);
	    //Title
	    $this->Cell(70,25,utf8_decode('CONFIDENCIAL, PROPIEDAD DE CONCRETO LANZADO DE FRESNILLO MARCA, S.A DE C.V. PROHIBIDA SU REPRODUCCIÓN TOTAL O PARCIAL.'),0,0,'C');
		$this->SetTextColor(21, 136, 67, 255);

		$this->SetFont('Arial','B',20);
		$this->Cell(-70,14,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 0);
		$this->SetFont('Arial','B',10);
		$this->Cell(70,50,utf8_decode('F. 7.4.0 - 01 REQUISICIÓN DE COMPRAS'),0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-70,60,utf8_decode('Av Enrique Estrada #75, Col. Las Americas, Fresnillo Zac.'),0,0,'C');
		$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(20, 20, 20);
		$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
	    //Line break
	    $this->Ln(45);
		parent::Header();
	}

	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
	    $this->SetY(-20);
	    
	}

}//Cierre de la clase PDF	

	$depto="";
	$base="";
	$comentario="";
	
	//Obtener el numero de la requisicion
	$num_req = $_GET['id'];

	switch (substr($_GET["id"],0,3)){
		case "ALM":
			$depto="almacen";
			$base="bd_almacen";
			break;
		case "SAB":
			$depto="sabinas";
			$base="bd_almacen";
			break;
		case "GER":
			$depto="gerencia_tecnica";
			$base="bd_gerencia";
			break;
		case "REC":
			$depto="recursos_humanos";
			$base="bd_recursos";
			break;
		case "PRO":
			$depto="produccion";
			$base="bd_produccion";
			break;
		case "ASE":
			$depto="aseguramiento_de_calidad";
			$base="bd_aseguramiento";
			break;
		case "DES":
			$depto="desarrollo";
			$base="bd_desarrollo";
			break;
		case "MAN":
			$depto="mantenimiento";
			$base="bd_mantenimiento";
			break;
		case "MAC":
			$depto="mantenimiento";
			$base="bd_mantenimiento";
			break;
		case "MAE":
			$depto="mantenimientoE";
			$base="bd_mantenimientoE";
			break;
		case "MAM":
			$depto="mantenimiento";
			$base="bd_mantenimiento";
			break;
		case "MAI":
			$depto="comaro";
			$base="bd_comaro";
			break;
		case "TOP":
			$depto="topografia";
			$base="bd_topografia";
			break;
		case "LAB":
			$depto="laboratorio";
			$base="bd_laboratorio";
			break;
		case "SEG":
			$depto="seguridad_industrial";
			$base="bd_seguridad";
			break;
		case "PAI":
			$depto="paileria";
			$base="bd_paileria";
			break;
		case "USO":
			$depto="clinica";
			$base="bd_clinica";
			break;
	}
	//Obtener el comentario de la Requisicion seleccionada
	$comentario = obtenerDato($base,"requisiciones", "observaciones", "id_requisicion", $num_req, $depto);
	//Connect to database 
	if($depto == "sabinas")
		conecta_sabinas('$base');
	else
		conecta('$base');
	//conecta('$base');

	//Crear el Objeto PDF y Agregar las Caracteristicas Iniciales
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();	
			
	//Imprimir mensaje si la Requisicion ya se Pidio
	$estado = obtenerDato($base,"requisiciones","estado","id_requisicion",$num_req, $depto);
	if ($estado=="PEDIDO")
		$pdf->Image('pedido.jpg',150,25,50);
	if ($estado=="ENTREGADA")
		$pdf->Image('recibido.jpg',150,25,50);
	if (isset($_GET["copia"]))
		$pdf->Image('copia.jpg',15,30,40);
		
	//Obtener la Informacion General de la Requisicion	
	$a_solicita = obtenerDato($base,"requisiciones", "area_solicitante", "id_requisicion", $num_req, $depto);
	$f = obtenerDato($base,"requisiciones", "fecha_req", "id_requisicion", $num_req, $depto);
	$fecha = modFecha($f,2);
	$solicitante = obtenerDato($base,"requisiciones", "solicitante_req", "id_requisicion", $num_req, $depto);
	$elaboro = obtenerDato($base,"requisiciones", "elaborador_req", "id_requisicion", $num_req, $depto);
	if($elaboro=="")
		$elaboro="                                                                           ";
	$jt = obtenerDato($base,"requisiciones", "justificacion_tec", "id_requisicion", $num_req, $depto);

	
	/**************************************************************************************************************/
	/**********************************DATOS GENERALES DE LA REQUISICION*******************************************/
	/**************************************************************************************************************/
	//Definir los datos que se encuentran sobre la tabla y antes del encabezado
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(20, 20, 20);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(15);
	$pdf->Cell(30,10,'FORMATO INTERNO',0,0);
	$pdf->Cell(60);
	$pdf->Cell(10,10,utf8_decode('REQUISICIÓN DE COMPRA'),0,0);
	$pdf->Cell(43);
	$pdf->SetTextColor(250, 0, 0);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetDrawColor(0, 0, 0);
	
	
	//Insertar una imagen con bordes redondos para escribir el siguiente contenido
	$pdf->Image('fondo.jpg',167,56,25);
	$pdf->Cell(25,10,$num_req,0,0);
	$pdf->Cell(0,10,'',0,1);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(20, 20, 20);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(18);
	$pdf->Cell(30,10,utf8_decode('Área Solicitante:'),0,0);
	$pdf->Cell(-0.5);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(30,10,$a_solicita,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(50);
	$pdf->Cell(30,10,'Fecha: ',0,0);
	$pdf->Cell(-15);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(30,10,$fecha,0,0);
	
	//Colocar lineas de espacio entre parrafos, tablas, etc.
	$pdf->Cell(0,10,'',0,1);
	$pdf->Cell(0,10,'',0,1);
	
			
	/**************************************************************************************************************/
	/**********************************TABLA DEL DETALLE DE LA REQUISICION*****************************************/
	/**************************************************************************************************************/
	//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
	$pdf->SetFillColor(217,217,217);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(20, 20, 20);
	$pdf->SetFont('Arial','B',12);
	//Colocar los Nombres de las columnas y el ancho de cada columna
	$pdf->Cell(20,5,'Cantidad.','LTRB',0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(20,5,'Unidad.','LTRB',0,'C',1);
	$pdf->Cell(110,5,utf8_decode('Descripción.'),'LTRB',0,'C',1);
	$pdf->Cell(27,5,utf8_decode('Aplicación'),'LTRB',0,'C',1);

	$pdf->SetFont('Arial','B',7);
	$pdf->Cell(13,5,'Existencia','LTRB',1,'C',1);
	
	//Sentencia para obtener el Detalle de la Requisicion
	if($depto == "sabinas")
		$sql_stm = "SELECT cant_req,unidad_medida,descripcion,aplicacion FROM detalle_requisicion WHERE requisiciones_id_requisicion = '".$num_req."'";
	else
		$sql_stm = "SELECT cant_req,unidad_medida,descripcion,aplicacion,materiales_id_material,id_control_costos,id_equipo FROM detalle_requisicion WHERE requisiciones_id_requisicion = '".$num_req."'";
	$rs = mysql_query($sql_stm);
	//Definir el tipo y tama�o de la letra para el Detalle de la Requisicion
	$pdf->SetFont('Arial','',9);
	$cant_renglones = 0;
	//Dibujar los registros del Detalle de la Requisici�n
	while($registro=mysql_fetch_array($rs)){
		//Obtener la Cantidad de Renglones que Ocuparan la Unidad de Media(10 caracteres), Descripcion(45 caracteres) y la Aplicacion(28 caracteres)
		//$unidad = cortarCadena($registro['unidad_medida'],10);
		$pos=strpos($registro['unidad_medida']," ");
		$unidad = $registro['unidad_medida'];
		$equipo = $registro['id_equipo'];
		$descripcion = cortarCadena($registro['descripcion'],50);

		if ($registro["id_equipo"] != "N/A") {
			$aplicacion = $registro["id_equipo"];
		} else {
			if (substr($registro["aplicacion"], 0, 3) == "CAT" ) {
				$aplicacion = strtoupper(obtenerDatoTabla("categorias_mat","descripcion","id_categoria",$registro['aplicacion'],"bd_almacen"));
			} else if ($registro["aplicacion"] != "N/A") {
				$aplicacion = $registro["aplicacion"];
			} else {
				$aplicacion = strtoupper(obtenerDatoTabla("control_costos","descripcion","id_control_costos",$registro['id_control_costos'],"bd_recursos"));
			}
		}

		$existencia = obtenerDatoTabla("materiales","existencia","id_material",$registro['materiales_id_material'],"bd_almacen");
		$aplicacion = cortarCadena($aplicacion,13);		
		
		//Obtener la cantidad maxima de renglones que ocupara una de las columnas del registro en turno
		$maxRenglones = max($descripcion['cantRenglones'],$aplicacion['cantRenglones']);			
		
		//Colocar la Cantidad como primer columna y primer renglon
		$pdf->Cell(20,5,number_format($registro['cant_req'],0,".",","),'LR',0,'C',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		
		//Imprimir la cantidad de renglones correspondientes de acuerdo a la longitud de cada campo
		for($i=0;$i<$maxRenglones;$i++){//Dibjar el Renglon con los datos del Registro
		
			//Colocar la celda correspondiente a la cantidad vacia y sin bordes para que no se note
			if($i>0)
				$pdf->Cell(20,5,'',"LR",0,'',0);

			//Para la Unidad de Medida
			if($i>0)
				$pdf->Cell(20,5,'','LR',0,'C',0);
			else{
				if(strlen($unidad)<10)
					$pdf->Cell(20,5,$unidad,'LR',0,'C',0);
				else{
					$pdf->SetFont('Arial','',5);
					$pdf->Cell(20,5,$unidad,'LR',0,'C',0);
					$pdf->SetFont('Arial','',9);
				}
			}
			
			//Imprimir la Descripcion
			if(isset($descripcion[$i]))	
				$pdf->Cell(110,5,$descripcion[$i],'LR',0,'L',0);
			else
				$pdf->Cell(110,5,'','LR',0,'',0);
			
			//Imprimir la Apicacion
			if(isset($aplicacion[$i])){
				$pdf->Cell(27,5,$aplicacion[$i],'LR',0,'C',0);
			}
			else{
				$pdf->Cell(27,5,'','LR',0,'',0);
			}

			if($i>0)
				$pdf->Cell(13,5,'',"LR",1,'',0);
			else
				$pdf->Cell(13,5,number_format($existencia,0,".",","),'LR',1,'C',0);
			
			/*
			if($i>0)
				$pdf->Cell(18,5,'','LR',1,'C',0);
			else{
				if(strlen($equipo)<10)
					$pdf->Cell(18,5,$equipo,'LR',1,'C',0);
				else{
					$pdf->SetFont('Arial','',5);
					$pdf->Cell(18,5,$equipo,'LR',1,'C',0);
					$pdf->SetFont('Arial','',9);
				}
			}
			*/
			
			//Incrementar la cantidad de renglones
			$cant_renglones++;												
		}//Cierre for($i=0;$i<$maxRenglones;$i++)
		
		

		//Incrementar los rengones por el renglon en blanco que se dibuja despues de cada registro
		$cant_renglones++;
		
		//Determinar si hay un Salto de pagina para colocar la linea de Cierre
		$borde = "LR";
		if($cant_renglones>=23)
			$borde = "LRB";
				
		//Colocar 1 Renglon de espacio entre cada Registro
		$pdf->Cell(20,5,'',$borde,0,'',0);
		$pdf->Cell(20,5,'',$borde,0,'',0);
		$pdf->Cell(110,5,'',$borde,0,'',0);
		$pdf->Cell(27,5,'',$borde,0,'',0);
		$pdf->Cell(13,5,'',$borde,1,'',0);
		
		
												
		//Monitorear la Cantidad de Renglones para realizar un salto de Pagina
		if($cant_renglones>=26){
			//Agregar una nueva Pagina
			$pdf->AddPage('P');
			//Reiniciar el contador de renglones
			$cant_renglones = 0;
			
			
			/**************************************************************************************************************/
			/**********************************DATOS GENERALES DE LA REQUISICION*******************************************/
			/**************************************************************************************************************/			
			//Imprimir mensaje si la Requisicion ya se Pidio			
			if ($estado=="PEDIDO")
				$pdf->Image('pedido.jpg',150,25,50);
			if (isset($_GET["copia"]))
				$pdf->Image('copia.jpg',15,30,40);
			
			//Definir los datos que se encuentran sobre la tabla y antes del encabezado
			$pdf->SetFont('Arial','B',11);//Tipo de Letra
			$pdf->SetTextColor(20, 20, 20);//Color del Texto
			$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
			$pdf->Cell(15);
			$pdf->Cell(30,10,'',0,0);
			$pdf->Cell(60);
			$pdf->Cell(10,10,utf8_decode('REQUISICIÓN DE COMPRA'),0,0);
			$pdf->Cell(43);
			$pdf->SetTextColor(250, 0, 0);
			$pdf->SetFont('Arial','B',10);
			$pdf->SetDrawColor(0, 0, 0);
			//Insertar una imagen con bordes redondos para escribir el siguiente contenido
			$pdf->Image('fondo.jpg',167,56,25);
			$pdf->Cell(25,10,$num_req,0,0);
				
								
			$pdf->Cell(0,10,'',0,1);
			$pdf->Cell(0,10,'',0,1);								
			//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
			$pdf->SetFillColor(217,217,217);
			$pdf->SetDrawColor(0, 0, 0);
			$pdf->SetTextColor(20, 20, 20);
			$pdf->SetFont('Arial','B',12);
			//Colocar los Nombres de las columnas y el ancho de cada columna
			$pdf->Cell(20,5,'Cantidad.','LTRB',0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(20,5,'Unidad.','LTRB',0,'C',1);
			$pdf->Cell(110,5,utf8_decode('Descripción.'),'LTRB',0,'C',1);
			$pdf->Cell(27,5,utf8_decode('Aplicación'),'LTRB',0,'C',1);

			$pdf->SetFont('Arial','B',7);
			$pdf->Cell(13,5,'Existencia','LTRB',1,'C',1);
			//$pdf->Cell(18,5,'Equipo.','LTRB',1,'C',1);
			
			//Definir el tipo y tama�o de la letra para el Detalle de la Requisicion de la Siguiente Pagina
			$pdf->SetFont('Arial','',9);	
		}//Cierre if($cant_renglones>=26)
		
	}//Cierre while($registro=mysql_fetch_array($rs))
	
	
	//Verificar si hay comentario para ser agregado	
	if ($comentario!=""){		
		//Obtener la Cantidad de renglones del Comentario
		$datosComentario = cortarCadena($comentario,50);
		
		//Colocar las columnas correspondientes la Cantidad y Unidad
		$pdf->Cell(20,5,'','LR',0,'C',0);
		$pdf->Cell(20,5,'','LR',0,'C',0);
		//Establecer formato para el comentario agregado, la palabra nota va en negritas y subrayada y el resto del comentaario va normal a partir de un sig. renglon
		$pdf->SetFont('Arial','UB',9);
		$pdf->Cell(110,5,"NOTA: ",'LR',0,'L',0);
		//Colocar la columna correspondiente a la Aplicaci�n
		$pdf->Cell(27,5,'','LR',0,'C',0);						
		$pdf->Cell(13,5,'','LR',1,'C',0);						
		//$pdf->Cell(18,5,'','LR',1,'C',0);						
		
		
		for($i=0;$i<$datosComentario['cantRenglones'];$i++){
			//Colocar las columnas correspondientes la Cantidad y Unidad
			$pdf->Cell(20,5,'','LR',0,'C',0);
			$pdf->Cell(20,5,'','LR',0,'C',0);
			if($i==0){
				//Definir el formato del comentario
				$pdf->SetFont('Arial','',9);
				$pdf->Cell(110,5,$datosComentario[$i],'LR',0,'L',0);							
			}
			else
				$pdf->Cell(110,5,$datosComentario[$i],'LR',0,'L',0);
				
			//Colocar la columna correspondiente a la Aplicaci�n
			$pdf->Cell(27,5,'','LR',0,'C',0);
			$pdf->Cell(13,5,'','LR',1,'C',0);
			//$pdf->Cell(18,5,'','LR',1,'C',0);
			$cant_renglones++;
		}
	}//Cierre if ($comentario!="")
	
	
	//Obtener la Cantidad de Reglones Restantes para completar la Tabla
	$rens_restantes = 20 - $cant_renglones;
	
	//Imprimir renglones en blanco para completar la pagina
	for($i=0;$i<$rens_restantes;$i++){
		$pdf->Cell(20,5,'','LR',0,'C',0);
		$pdf->Cell(20,5,'','LR',0,'C',0);
		$pdf->Cell(110,5,'','LR',0,'C',0);
		$pdf->Cell(27,5,'','LR',0,'C',0);
		$pdf->Cell(13,5,'','LR',1,'C',0);//Colocar el 1 para indicar que las siguientes celdas seran colocadas en la sig linea	
		//$pdf->Cell(18,5,'','LR',1,'C',0);
	}
	

	/**************************************************************************************************************/
	/***********************************************JUSTIFICACION**************************************************/
	/**************************************************************************************************************/		
	//Agregar el titulo de la Justificaci�n al renglon en la base de la tabla
	$pdf->SetFont('Arial','B',12);//Formato del Texto del titulo de la Justificacion
	$pdf->Cell(190,6,utf8_decode('JUSTIFICACIÓN TÉCNICA CON REFERENCIAS y/o ANTECEDENTES'),'LTRB',1,'C',1);
	//Aqui se escribre la justificaci�n tecnica obtenida de la BD
	$pdf->SetFont('Arial','',9);//Formato del Texto de la Justificacion
	$pdf->MultiCell(190,5,$jt,1,'J');																
	
	
	/**************************************************************************************************************/
	/**************************************************FIRMA*******************************************************/
	/**************************************************************************************************************/
	//Colocar despues de la tabla los datos de la persona que solicito el material
	$pdf->SetTextColor(20, 20, 20);
	$pdf->Ln();
	/*$pdf->Cell(95,5,'Departamento de Compras',0,0,'C');*/
	$pdf->Cell(95,5,'                                                                                                          Residente',0,1,'C');
	$pdf->Cell(95,5,'  ',0,1,'C');
	$pdf->Cell(95,5,'                                                                                                         __________________________________',0,0,'C');
	/*$pdf->Cell(95,5,'______________________________',0,1,'C');*/
	$pdf->Cell(95,5,'  ',0,1,'C');
	$pdf->Cell(95,5,'  ',0,1,'C');
	$pdf->Cell(95,5,'  ',0,1,'C');
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(95,5,utf8_decode('Elaboró'),0,0,'C');
	$pdf->Cell(95,5,utf8_decode('Solicitó'),0,1,'C');
	$pdf->Cell(95,5,'',0,1,'C');
	$pdf->Cell(95,5,$elaboro,0,0,'C');
	$pdf->Cell(95,5,$solicitante,0,0,'C');
	
	
	$ruta="../../pages/".strtolower(substr($_GET["id"],0,3))."/documentos/".$num_req;
	$contador=1;
	//Vertical
	$py=50;	
	//Horizontal
	$xx=20;
	//Titulos
	$xy=27;
	
		//Vericar que el Directorio proporcionado sea valido.
		if(is_dir($ruta)){
			//Abrir el Directorio dado para leer los archivos que contenga
			if($gestor=opendir($ruta)){
				//Leer cada uno de los archivs dentro del directorio
				while(false!==($nomFoto=readdir($gestor))){				
					//Comprombamos que la foto sea diferente de (.) o (..) al abrir los archivos por primera vez, contiene estos caracteres; asi mismo sustraemos 
					//La extensi�n para verificar que sea JPG y que se pueda  acceder a ellas 
					if ($nomFoto != "." && $nomFoto != ".."&&strtoupper(substr($nomFoto,-3))=='JPG') {
						//Verificamos que el contador se encuentre en 1; esto para verificar que es la primer imagen y que requiere de anexar una nueva pagina
						if($contador==1){
							//Dar un salto de pagina
							$pdf->Addpage("p");
						}
						//Comprobamos que el residuo de dividir $contador/3 sea 0; si es esto entonces en la fila se encuentran 3 imagens  y se requiere dibujar una mas
						if(fmod($contador,3)==0){
							//Imprimir la imagen en una posici�n dada
							$pdf -> Image($ruta."/".$nomFoto,$xx,$py,55,40);
							//Partimos la cadena de texto para almacenarlas en un arreglo caracter por caracter
							$claveMatSplit = str_split($nomFoto);
							//Recorremos el arreglo para conocer si existe alguno de los caracteres especiales
							for($i=0; $i<count($claveMatSplit); $i++){
								//Cambiamos el valor segun corresponda
								if($claveMatSplit[$i]=="�"){
									$claveMatSplit[$i] = '"';
								}
								if($claveMatSplit[$i]=='@'){
									$claveMatSplit[$i] = "/";
								}
								if($claveMatSplit[$i] =="�"){
									$claveMatSplit[$i] = "-";
								}
								if($claveMatSplit[$i]=="+"){
									$claveMatSplit[$i] = "%";
								}
							}
							//Contador 	que nos permitira controlar el ciclo para concatenar el valor del nombre obtenido en un arreglo en el proceso anterior
							$contad = 1;
							//Recorremos el foreach  para almacenar el valor contenido en el Post en una variable y enviarla a una caja de texto
							foreach($claveMatSplit as $key => $valor){
								if($contad==1){
									$nomFoto = $valor;
								}
								if($contad>1){
									$nomFoto .= $valor;
								}
								$contad++;
							}
							//Partimos la cadena de texto para almacenarlas en un arreglo caracter por caracter
							$claveMatSplit = str_split($nomFoto);
							//Recorremos el arreglo para conocer si existe alguno de los caracteres especiales
							for($i=0; $i<count($claveMatSplit); $i++){
								//Cambiamos el valor segun corresponda
								if($claveMatSplit[$i]=="�"){
									$claveMatSplit[$i] = '"';
								}
								if($claveMatSplit[$i]=='@'){
									$claveMatSplit[$i] = "/";
								}
								if($claveMatSplit[$i] =="�"){
									$claveMatSplit[$i] = "-";
								}
								if($claveMatSplit[$i]=="+"){
									$claveMatSplit[$i] = "%";
								}
							}
							//Contador 	que nos permitira controlar el ciclo para concatenar el valor del nombre obtenido en un arreglo en el proceso anterior
							$contad = 1;
							//Recorremos el foreach  para almacenar el valor contenido en el Post en una variable y enviarla a una caja de texto
							foreach($claveMatSplit as $key => $valor){
								if($contad==1){
									$nomFoto = $valor;
								}
								if($contad>1){
									$nomFoto .= $valor;
								}
								$contad++;
							}
							//Sustituimos la extension por un campo vacio y asi poder obtener el nombre del material; asi mismo lo convertimos a mayusculas para 
							//no tener problema al diferenciar JPG o jpg
							$clave=str_replace(".JPG","", strtoupper($nomFoto));
							//Obtenemos el material
							$material=obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $clave, $depto);
							$material=cortarCadena($material,20);
							$lineas=50;
							for($i=0;$i<$material['cantRenglones'];$i++){
								if ($i==0)
									$pdf->Text($xy,$py+45,$material[$i],1,'J');
								else{
									$pdf->Text($xy,$py+$lineas,$material[$i],1,'J');
									$lineas+=5;
								}
							}
							//Colocar el texto debajo de la Imagen como Etiqueta
							//$pdf -> Text($xy, $py+45, $material);
							//Incrementamos nuestras variables que son las que controlan las posiciones de las imagenes
							$py+=62;
							$xx=20;
							//Titulos
							$xy=27;
							//$pdf->Ln();					
						}
						else{
							//Imprimir la imagen en una posici�n dada
							$pdf -> Image($ruta."/".$nomFoto,$xx,$py,55,40);
							//Partimos la cadena de texto para almacenarlas en un arreglo caracter por caracter
							$claveMatSplit = str_split($nomFoto);
							//Recorremos el arreglo para conocer si existe alguno de los caracteres especiales
							for($i=0; $i<count($claveMatSplit); $i++){
								//Cambiamos el valor segun corresponda
								if($claveMatSplit[$i]=="�"){
									$claveMatSplit[$i] = '"';
								}
								if($claveMatSplit[$i]=='@'){
									$claveMatSplit[$i] = "/";
								}
								if($claveMatSplit[$i] =="�"){
									$claveMatSplit[$i] = "-";
								}
								if($claveMatSplit[$i]=="+"){
									$claveMatSplit[$i] = "%";
								}
							}
							//Contador 	que nos permitira controlar el ciclo para concatenar el valor del nombre obtenido en un arreglo en el proceso anterior
							$contad = 1;
							//Recorremos el foreach  para almacenar el valor contenido en el Post en una variable y enviarla a una caja de texto
							foreach($claveMatSplit as $key => $valor){
								if($contad==1){
									$nomFoto = $valor;
								}
								if($contad>1){
									$nomFoto .= $valor;
								}
								$contad++;
							}
							//Sustituimos la extension por un campo vacio y asi poder obtener el nombre del material; asi mismo lo convertimos a mayusculas para 
							//no tener problema al diferenciar JPG o jpg
							$clave=str_replace(".JPG","", strtoupper($nomFoto));
							$material=obtenerDato("bd_almacen","materiales", "nom_material", "id_material", $clave, $depto);
							$material=cortarCadena($material,20);
							$lineas=50;
							for($i=0;$i<$material['cantRenglones'];$i++){
								if ($i==0)
									$pdf->Text($xy,$py+45,$material[$i],1,'J');
								else{
									$pdf->Text($xy,$py+$lineas,$material[$i],1,'J');
									$lineas+=5;
								}
							}
							//Colocar el texto debajo de la Imagen como Etiqueta
							//$pdf -> Text($xy, $py+45, $material);
							$xx+=60;
							$xy+=60;
						}
						//Si el resultado de $cotnador/12 es igual a cero; se ah llenado una pagina y es necesario cambiar a otra; asi mismo reiniciamos las variables
						//de control de posiciones
						if(fmod($contador,12)==0){
							//Vertical
							$py=50;	
							//Horizontal
							$xx=20;
							//Titulos
							$xy=27;	
							//Dar un salto de pagina
							$pdf->Addpage("p");
						}
						//Incrementamos el contador
						$contador++;									
					}
				}
				//Cerramos el directorio	   	
				closedir($gestor);	
			}
		}						
	

	//Especificar Datos del Documento
	$pdf->SetAuthor($solicitante);
	$pdf->SetTitle(utf8_decode("REQUISICIÓN ").$num_req);
	$pdf->SetCreator($a_solicita);
	$pdf->SetSubject("Solicitud de Compra");
	$pdf->SetKeywords(utf8_decode("Qubic Tech. \nDocumento Generado a Partir de la Requisición ").$num_req." en el SISAD");
	$num_req.='.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($num_req,"F");
	header('Location: '.$num_req);
	//Borrar todos los PDF ya creados
	borrarArchivos();
	
	
				
	/***********************************************************************************************/
	/**************************FUNCIONES USADAS EN LA REQUISICION***********************************/
	/***********************************************************************************************/
	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica
	function obtenerDato($nom_base,$nom_tabla, $campo_bus, $param_bus, $dato_bus, $depto){
		//Conectarse con la BD que corresponde
		if($depto == "sabinas")
			$conn=conecta_sabinas($nom_base);
		else
			$conn=conecta($nom_base);
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE $param_bus='$dato_bus'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus, $depto)
	
	//Esta funci�n elimina los archivos PDF que se hayan generado anteriormente
	function borrarArchivos(){
		//Borrar los ficheros temporales
		$t=time();
		$h=opendir('.');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.pdf'){
				if($t-filemtime($file)>1)
					@unlink($file);
			}
		}
		closedir($h);
	}
	
	
	/*Esta funcion divide una cadena en la cantidad exacta de Caracteres o menor de acuerdo al acomodo de palabras*/
	function cortarCadena($cadena,$carsPorLinea){	
		//Variable para Almacenar la Nueva Cadena
		$datosCadena = array("cantRenglones"=>0);
		//Obtener el Tama�o de la Cadena Original
		$tamCadena = strlen($cadena);
		//Separar la Cadena Original en un Arreglo de Caracteres, donde cada posici�n del Arreglo contiene un solo caracter de la cadena original
		$caracteres = str_split($cadena);
			
		//Si el tama�o de la Cadena excede la cantidad de caracteres especificada, proceder a separarla
		if($tamCadena>$carsPorLinea){
			//Variables para controlar el recorrido de la cadena caracter x caracter
			$cantCaracteres = 0;
			$carInicial = 0;
			$posBlank = 0;
			for($i=0;$i<$tamCadena;$i++){
				//Incremenetar el contado de caracteres
				$cantCaracteres++;				
				
				//Obtener cada Caracter de la cadena
				$carActual = $caracteres[$i];
				
				//Guardar la Posicion del ultimo espacio en blanco encontrado
				if($carActual==" ")
					$posBlank = $i;
				
				//Si la cantidad de caracteres recorridos es igual a la cantidad de caracteres por linea, proceder a cortarla
				if($cantCaracteres==$carsPorLinea){					
					//Si el caracter actual es un espacio en blanco cambiar el valor de la variable '$posBlank'
					if($carActual==" ")
						$posBlank = $i;
					
					//Incrementar el contador de renglones
					$datosCadena["cantRenglones"]++;
					//Guardar cada Trozo de la cadena en una posicion dada del arreglo
					$datosCadena[] = substr($cadena,$carInicial,($posBlank-$carInicial));																				
					
					//El siguiente caracter de la posici�n del ultimo espacio en blanco encontrado se vuelve la posici�n inicial
					$carInicial = $posBlank+1;
					
					//Colocar el contador del Ciclo for a la posici�n del ultimo espacio en blanco encontrado
					$i = $posBlank;
									
					//Resetear el contador de caracteres para no exceder el tama�o del renglon
					$cantCaracteres = 0;										
				}				
						
			}//Cierre for($i=0;$i<$tamCadena;$i++)
			
			//Termindao el Ciclo, verificar si la cantidad de caracteres es mayor a cero y agregar el ultimo Fragmento a al cadena
			if($cantCaracteres>1){
				//Incrementar el contador de renglones
				$datosCadena["cantRenglones"]++;
				//Guardar el ultimo Trozo de la cadena en una posicion dada del arreglo
				$datosCadena[] = substr($cadena,$carInicial,strlen($cadena)-1);
			}			
			
		}//Cierre if($tamCadena>$carsPorLinea)
		else{//La cadena original queda intacta			
			$datosCadena["cantRenglones"]++;
			//Guardar la cadena origial tal cual en la posicion del arreglo
			$datosCadena[] = $cadena;
		}
		
		
		return $datosCadena;			
	}//Cierre cortarCadena($cadena,$carsPorLinea)

	function obtenerCentroCosto($tabla,$busq,$valor){
		$dat = $valor; 
		$con = conecta("bd_recursos");
		$stm_sql = "SELECT descripcion
					FROM  `$tabla` 
					WHERE  `$busq` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		mysql_close($con);
		return $dat;
	}
	
	function obtenerDatoTabla($tabla,$campo,$cond,$valor,$bd){
		$dat = $valor; 
		$con = conecta("$bd");
		$stm_sql = "SELECT $campo
					FROM  `$tabla` 
					WHERE  `$cond` LIKE  '$valor'";
		$rs = mysql_query($stm_sql);
		if($rs){
			if($datos = mysql_fetch_array($rs)){
				$dat = $datos[0];
			}
		}
		mysql_close($con);
		return $dat;
	}
?>