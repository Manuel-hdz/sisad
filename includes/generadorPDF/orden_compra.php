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
	    $this->Cell(70,25,'CONFIDENCIAL, PROPIEDAD DE “CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.” PROHIBIDA SU REPRODUCCIÓN TOTAL O PARCIAL.',0,0,'C');
		$this->SetTextColor(78, 97, 40);
		$this->SetFont('Arial','B',20);
		$this->Cell(-70,14,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);
		$this->SetFont('Arial','B',10);
		$this->Cell(70,50,'F. 7.5.5 - 03 ORDEN DE COMPRA',0,0,'C');
		$this->SetFont('Arial','B',10);
		//$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
		//$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
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
	    //Arial italic 8
	    $this->SetFont('Arial','',7);
		$this->Cell(0,15,'       Fecha Emisión:                                               No. de Revisión:                                               Fecha de Revisión:',0,0,'L');
	    //Numero de Pagina
		$this->Cell(0,15,'Página '.$this->PageNo().' de {nb}',0,0,'R');
		$this->SetY(-17);
		$this->Cell(0,15,'            Dic - 10'.'                                                                '.'01'.'                                                                 '.'    Dic - 10',0,0,'L');
		$this->SetY(-20);
		$this->Cell(0,25,'F. 4.2.1 - 01 / Rev. 01',0,0,'R');
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
	$pdf->SetFont('Arial','B',11);
	$pdf->SetTextColor(51, 51, 153);
	
	//Obtener el numero de la requisicion
	$num_oc = $_GET['id'];
	//Obtener la Informacion General de la Requisicion
	$a_solicita = obtenerDato("orden_compra", "a_solicitante_oc", "id_orden_compra", $num_oc);
	$f = obtenerDato("orden_compra", "fecha_oc", "id_orden_compra", $num_oc);
	$fecha = modFecha($f,2);
	$solicitante = obtenerDato("orden_compra", "solicitante_oc", "id_orden_compra", $num_oc);

	//Definir los datos que se encuentran sobre la tabla y antes del encabezado
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->Cell(15);
	$pdf->Cell(30,10,'FORMATO INTERNO',0,0);
	$pdf->Cell(100);
	$pdf->Cell(10,10,'ORDEN DE COMPRA',0,0,'R');
	$pdf->Cell(5);
	$pdf->SetTextColor(128, 0, 0);
	$pdf->SetFont('Arial','B',10);
	$pdf->SetDrawColor(0, 0, 0);
	//Insertar una imagen con bordes redondos para escribir el siguiente contenido
	$pdf->Image('fondo.jpg',167,56,25);
	$pdf->Cell(25,10,$num_oc,0,0);
	$pdf->Cell(0,10,'',0,1);
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(18);
	$pdf->Cell(30,10,'Área Solicitante:',0,0);
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

	//Columnas que aparecen en el detalle del PDF, el primer atributo debe ser el mismo nombre del campo que esta en la BD
	$pdf->AddCol('catalogo_mf_codigo_mf',25,'Clave.','C');
	$pdf->AddCol('cant_oc',20,'Cantidad.','C');
	$pdf->AddCol('descripcion',135,'Descripción.','L');

	//Propiedades de las columnas columnas de la tabla 
	$prop=array('HeaderColor'=>array(217,217,217), 'LineColor'=>array(0, 0, 255), 'color1'=>array(255,255,255), 'color2'=>array(255,255,255), 'padding'=>2);

	//Consulta que agrega los datos en la tabla que aparece en el PDF, El ultimo parametro ->2 es para diferenciar que tipo de documento es
	$pdf->Table('SELECT catalogo_mf_codigo_mf,cant_oc,descripcion FROM detalle_oc WHERE orden_compra_id_orden_compra="'.$num_oc.'"',$prop,"oc");
	
	//Completar los renglones dependiendo de la cantidad de registros que se agreguen en la Orden de Compra
	$datos = mysql_fetch_array(mysql_query("SELECT COUNT(descripcion) AS cant FROM detalle_oc WHERE orden_compra_id_orden_compra='$num_oc'"));
	$renglones = 24 - $datos['cant'];
	for($i=0;$i<$renglones;$i++){
		$pdf->Cell(7.95);//Adelandar las celdas 7.95 unidades para que coincidan con las celdas dibujadas por la funcion Table
		$pdf->Cell(25,5,'','LR',0);
		$pdf->Cell(20,5,'','LR',0);
		$pdf->Cell(135,5,'','LR',1);//Colocar el 1 para indicar que las siguientes celdas seran colocadas en la sig linea
	}
	//Colocar el ultimo renglon de la Orden de Compra para colocar el borde de abajo y cerrar la tabla
	$pdf->Cell(7.95);//Adelandar las celdas 7.95 unidades para que coisidan con las celdas dibujadas por la funcion Table
	$pdf->Cell(25,5,'','LRB',0);
	$pdf->Cell(20,5,'','LRB',0);
	$pdf->Cell(135,5,'','LRB',1);

	
	//Colocar despues de la tabla los datos de la persona que solicito el material
	$pdf->SetTextColor(51, 51, 153);
	$pdf->Cell(0,10,'',0,1);
	$pdf->Cell(145,0,'',0,0);
	$pdf->Cell(15,0,'Solicitó',0,0, 'C');
	$pdf->Cell(-20);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(0,5,'',0,1);
	$pdf->Cell(145,0,'',0,0);
	$pdf->Cell(15,0,$solicitante,0,0,'C');

	//Especificar Datos del Documento
	$pdf->SetAuthor($solicitante);
	$pdf->SetTitle("ÓRDEN DE COMPRA  ".$num_oc);
	$pdf->SetCreator($a_solicita);
	$pdf->SetSubject("Solicitud de Compra");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir de la Órden de Compra ".$num_oc." en el SISAD");
	$num_oc.='.pdf';

	//Mandar imprimir el PDF
	$pdf->Output($num_oc,"F");
	header('Location: '.$num_oc);
	//Borrar todos los PDF ya creados
	borrarArchivos();
		


	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica
	function obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus){
		//Conectarse con la BD de Almacen
		$conn = conecta("bd_almacen");
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE $param_bus='$dato_bus'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
	//Esta función elimina los archivos PDF que se hayan generado anteriormente
	function borrarArchivos(){
		//Borrar los ficheros temporales
		$t=time();
		$h=opendir('.');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.pdf')
			{
				if($t-filemtime($file)>1)
					@unlink($file);
			}
		}
		closedir($h);
	}

?>