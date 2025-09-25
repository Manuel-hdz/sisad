<?php
require('mysql_table.php');
require("../conexion.inc");
include("../func_fechas.php");

class PDF extends PDF_MySQL_Table{

	function Header(){
		//Logo
	    $this->Image('logo-clf.jpg',10,6,30);
	    //Arial bold 15
	    $this->SetFont('Arial','B',7.5);
		$this->SetTextColor(0, 0, 255);
	    //Move to the right
	    $this->Cell(60);
	    //Title
	    //$this->Cell(70,25,'CONFIDENCIAL, PROPIEDAD DE “CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.” PROHIBIDA SU REPRODUCCIÓN TOTAL O PARCIAL.',0,0,'C');
		$this->Cell(70,25,'',0,0,'C');
		$this->SetTextColor(78, 97, 40);
		$this->SetFont('Arial','B',20);
		$this->Cell(-70,14,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);
		$this->SetFont('Arial','B',10);
		$this->Cell(70,50,'ENTRADA DE MATERIAL A ALMACÉN',0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
		$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		//$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->Cell(62.5,9,'',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		//$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
		$this->Cell(0.09,15,'',0,0,'R');
	    //Line break
	    $this->Ln(45);
		//Imagen de Marca de Agua
		$this->Image('logotransp.jpg',60,220,100);
		parent::Header();
	}

	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
	    $this->SetY(-20);
	    //Arial italic 8
	    $this->SetFont('Arial','',7);
		//$this->Cell(0,15,'       Fecha Emisión:                                               No. de Revisión:                                               Fecha de Revisión:',0,0,'L');
		$this->Cell(0,15,'',0,0,'L');
	    //Numero de Pagina
		$this->Cell(-20,15,'Página '.$this->PageNo().' de {nb}',0,0,'C');
		$this->SetY(-17);
		//$this->Cell(0,15,'            Abr - 09'.'                                                                '.'01'.'                                                                 '.'   Abr - 09',0,0,'L');
		$this->Cell(0,15,'',0,0,'L');
		$this->SetY(-20);
		//$this->Cell(0,25,'F. 7.4.0 - 02 / Rev. 01',0,0,'R');
		$this->Cell(0,25,'',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}

}//Cierre de la clase PDF	


	//Connect to database 
	conecta('bd_almacen');

	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(51, 51, 153);

	//Obtener el numero del Pedido
	$num_entrada = $_GET['id'];
	//Obtener la Informacion General del Pedido
	$proveedor = obtenerDatoAlmacen("entradas", "proveedor", "id_entrada", $num_entrada);
	//obtener el numero de origen de la entrada
	$no_origen = obtenerOrigenAlmacen($num_entrada);
	//Declarar por default el origen a compra directa, a menos que se cumpla alguna de las 2 siguientes comparaciones
	$origen="Compra Directa";
	if (substr($no_origen,0,3)=="ALM" || substr($no_origen,0,3)=="MAN" || substr($no_origen,0,3)=="MAC" || substr($no_origen,0,3)=="MAM" || substr($no_origen,0,3)=="ASE" || substr($no_origen,0,3)=="DES" || substr($no_origen,0,3)=="GER" || substr($no_origen,0,3)=="LAB" || substr($no_origen,0,3)=="PAI" || substr($no_origen,0,3)=="PRO" || substr($no_origen,0,3)=="REC" || substr($no_origen,0,3)=="SEG" || substr($no_origen,0,3)=="TOP")
		$origen = "Requisición";
	if (substr($no_origen,0,3)=="PED")
		$origen = "Pedido";
	if (substr($no_origen,0,2)=="OC")
		$origen = "Órden de Compra";
	
	$hora = modHora(obtenerDatoAlmacen("entradas", "hora_entrada", "id_entrada", $num_entrada));
	$factura = obtenerDatoAlmacen("entradas", "no_factura", "id_entrada", $num_entrada);
	$comentarios = obtenerDatoAlmacen("entradas", "comentarios", "id_entrada", $num_entrada);
	$fecha = modFecha(obtenerDatoAlmacen("entradas", "fecha_entrada", "id_entrada", $num_entrada),1);

	if (strlen($proveedor)>60){
		$ren = 2;
		//Antes de dividir la cadena buscar el caracter para separarla
		$pos = strlen($proveedor)/$ren;
		while(substr($proveedor,$pos,1)!=" ")
			$pos++;
		$cad1 = substr($proveedor,0,$pos);
		$fin =  (strlen($proveedor)/$ren) - ($pos - (strlen($proveedor)/$ren));
		$cad2 = substr($proveedor,$pos,$fin);
		$cad2 = ltrim($cad2," ");
	}
	else{
		$cad1=$proveedor;
		$cad2="";
	}

	//Definir los datos que se encuentran sobre la tabla y antes del encabezado
	$pdf->Cell(138,10,"PROVEEDOR:",0,0);
	$pdf->Cell(30,10,'ENTRADA: ',0,0);
	$pdf->SetTextColor(255, 0, 0);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(0,10,$num_entrada,0,1);
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->Cell(138,0,$cad1,0,0);
	$pdf->Cell(30,0,'FECHA: ',0,0);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(0,0,$fecha,0,1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(138,10,$cad2,0,0);
	$pdf->Cell(30,10,'HORA:',0,0);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(0,10,$hora,0,1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(140,-5,'',0,1);
	$pdf->Cell(20,10,"ORIGEN:",0,0);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(60,10,$origen,0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(30,10,"No. ORIGEN:",0,0);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(28,10,$no_origen,0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(30,10,'FACTURA: ',0,0);
	$pdf->SetFont('Arial','BU',10);
	$pdf->Cell(0,10,$factura,0,1);
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->Cell(0,0,'',0,1);
	
	//Colocar lineas de espacio entre parrafos, tablas, etc.
	$pdf->Cell(0,2,'',0,1);
	
	//Columnas que aparecen en el detalle del PDF, el primer atributo debe ser el mismo nombre del campo que esta en la BD
	$pdf->AddCol('unidad_material',20,'UNIDAD','C');
	$pdf->AddCol('cant_entrada',23,'CANTIDAD','C');
	$pdf->AddCol('nom_material',117,'DESCRIPCIÓN','L');
	$pdf->AddCol('costo_unidad',40,'PRECIO UNITARIO','C');
	//Propiedades de las columnas columnas de la tabla 
	$prop=array('HeaderColor'=>array(243,243,243), 'LineColor'=>array(0, 0, 255), 'color1'=>array(255,255,255), 'color2'=>array(255,255,255), 'padding'=>2);
	//Consulta que agrega los datos en la tabla que aparece en el PDF	
	$pdf->Table('SELECT nom_material,cant_entrada,costo_unidad,unidad_material
				FROM entradas JOIN detalle_entradas ON id_entrada = entradas_id_entrada WHERE id_entrada="'.$num_entrada.'"',$prop,"entradaM");
	
	//Completar los renglones dependiendo de la cantidad de registros que se agreguen en la requisicion
	$datos = mysql_fetch_array(mysql_query("SELECT COUNT(materiales_id_material) AS cant FROM detalle_entradas WHERE entradas_id_entrada='$num_entrada'"));

	$renglones = 20 - $datos['cant'];
	for($i=0;$i<$renglones;$i++){
		$pdf->Cell(-2.1);//Regresar las celdas 2.1 unidades para que coincidan con las celdas dibujadas por la funcion Table
		$pdf->Cell(20,5,'','LR',0);
		$pdf->Cell(23.1,5,'','LR',0);
		$pdf->Cell(117.1,5,'','LR',0);
		$pdf->Cell(40,5,'','LR',1);//Colocar el 1 para indicar que las siguientes celdas seran colocadas en la sig linea	
	}
	
	//Definimos el color de relleno de los valores numéricos en la tabla generada
	$pdf->SetFillColor(243,243,243);

	$pdf->Cell(-2.1);//Regresar las celdas 2.1 unidades para que coincidan con las celdas dibujadas por la funcion Table
	$pdf->Cell(20,5,'','LRB',0);
	$pdf->Cell(23,5,'','LRB',0);
	$pdf->Cell(117.1,5,'','LRB',0);
	$pdf->Cell(40.1,5,'','LRB',0);
	
	
	//Salto de Linea
	$pdf->Ln();
	//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
	$pdf->Cell(-2.1,0,"",0,0);
	$pdf->Cell(200.1,17,"",1,0);
	$pdf->Cell(-198,10,"",0,0);
	$pdf->Cell(130,10,"COMENTARIOS",0,1);
	if (strlen($comentarios)>80){
		$ren = 2;
		//Antes de dividir la cadena buscar el caracter para separarla
		$pos = strlen($comentarios)/$ren;
		while(substr($comentarios,$pos,1)!=" ")
			$pos++;
		$cad1 = substr($comentarios,0,$pos);
		$fin =  (strlen($comentarios)/$ren) - ($pos - (strlen($comentarios)/$ren));
		$cad2 = substr($comentarios,$pos);
		$cad2 = ltrim($cad2," ");
		$pdf->Cell(130,0,$cad1,0,1);
		$pdf->Cell(130,10,$cad2,0,1);
	}
	else{
		$pdf->Cell(130,0,$comentarios,0,1);
		$pdf->Cell(130,5,"",0,1);
		$pdf->Cell(130,5,"",0,1);
	}
	//Salto de Linea
	$pdf->Ln();
	
	$pdf->Cell(200,5,"FIRMA DE RECIBIDO POR ALMACÉN:",0,1,"C");
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(200,5,"_________________________________________",0,0,"C");
	

	//Especificar Datos del Documento
	$pdf->SetAuthor("Lic. Gustavo Alonso Menchaca Elicerio");
	$pdf->SetTitle("ENTRADA ".$num_entrada);
	$pdf->SetCreator("Departamento de Almacén");
	$pdf->SetSubject("Entrada de Material a Almacén");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir de la Entrada ".$num_entrada." en el SISAD");
	$num_entrada.='.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($num_entrada,"F");
	header('Location: '.$num_entrada);
	//Borrar todos los PDF ya creados
	borrarArchivos();

	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica
	function obtenerDatoAlmacen($nom_tabla, $campo_bus, $param_bus, $dato_bus){
		//Conectarse con la BD de Almacen
		$conn = conecta("bd_almacen");
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE $param_bus='$dato_bus'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDatoAlmacen($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
	function obtenerOrigenAlmacen($entrada){
		//Conectarse con la BD de Almacen
		$conn = conecta("bd_almacen");
		
		$stm_sql = "SELECT requisiciones_id_requisicion,orden_compra_id_orden_compra,comp_directa FROM entradas WHERE id_entrada='$entrada'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		if ($datos["requisiciones_id_requisicion"]=="" && $datos["orden_compra_id_orden_compra"]==""){
			return $datos["comp_directa"];
		}
		
		if ($datos["requisiciones_id_requisicion"]=="" && $datos["comp_directa"]==""){
			return $datos["orden_compra_id_orden_compra"];
		}
		
		if ($datos["orden_compra_id_orden_compra"]=="" && $datos["comp_directa"]==""){
			return $datos["requisiciones_id_requisicion"];
		}
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDatoAlmacen($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
	//Esta función elimina los archivos PDF que se hayan generado anteriormente
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
?>