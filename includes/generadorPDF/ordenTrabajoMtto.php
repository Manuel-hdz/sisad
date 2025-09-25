<?php
require('mysql_table.php');
require("../conexion.inc");
include("../func_fechas.php");

class PDF extends PDF_MySQL_Table{

	function Header(){
		//Colocar el Logo
	    $this->Image('logo-clf.jpg',10,6,30);
	    //Colocar el texto en Arial bold 15
	    $this->SetFont('Arial','B',7.5);
		$this->SetTextColor(0, 0, 255);//Colocar el Color de Texto en Azul
	    //Crear una celda de 60 mm para colocar la siguiente celda a la deracha
	    $this->Cell(60);
	    //Title
	    $this->Cell(70,25,'CONFIDENCIAL, PROPIEDAD DE “CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.” PROHIBIDA SU REPRODUCCIÓN TOTAL O PARCIAL.',0,0,'C');
		$this->SetTextColor(78, 97, 40);//Colocar el texto en color verde para colocar la linea que va debajo del Logo 
		$this->SetFont('Arial','B',20);
		$this->Cell(-70,14,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);//Colocar nuevamente el texto en color azul
		$this->SetFont('Arial','B',10);
		$this->Cell(70,50,'F. 6.3.0 - 04 ORDEN DE TRABAJO MANTENIMIENTO PREVENTIVO',0,0,'C');
		$this->SetFont('Arial','B',10);		
		$this->SetFont('Arial','B',8);
		$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
	    //Line break
	    $this->Ln(35);
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
		$this->Cell(0,15,'            Abril - 09'.'                                                                '.'02'.'                                                                 '.'    Marzo - 11',0,0,'L');
		$this->SetY(-20);
		$this->Cell(0,25,'F. 4.2.1 - 01 / Rev. 02',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}

}//Cierre de la clase PDF	


	//Connect to database 
	$conn = conecta('bd_mantenimiento');
	

	$pdf = new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetTextColor(51, 51, 153);//Colocar todo el texto del documento en Azul Oscuro
	$pdf->SetDrawColor(0, 0, 255);//Definir Color de las lineas, rectangulos y bordes de celdas en color Azul
	$pdf->SetAutoPageBreak(true,30);//Indicar que cuando una celda exceda el margen inferior de 3 cm(30mm), esta se dibuje en la siguiente pagina
	
	//Obtener el numero de la Orden de Trabajo
	$num_ot = $_GET['id'];
	
	//Obtener la Informacion General de la Orden de Trabajo
	$equipo = obtenerDato("bitacora_mtto", "equipos_id_equipo", "orden_trabajo_id_orden_trabajo", $num_ot);
	$f = obtenerDato("orden_trabajo", "fecha_creacion", "id_orden_trabajo", $num_ot);
	$fecha = modFecha($f,1);
	$autorizo_ot = obtenerDato("orden_trabajo", "autorizo_ot", "id_orden_trabajo", $num_ot);
	$descripcion= obtenerDato("equipos", "nom_equipo", "id_equipo", $equipo);
	$operador= obtenerDato("orden_trabajo", "operador_equipo", "id_orden_trabajo", $num_ot);
	$mantenimiento= "PREVENTIVO"; 
	$horometro= obtenerDato("orden_trabajo", "horometro", "id_orden_trabajo", $num_ot);
	$odometro= obtenerDato("orden_trabajo", "odometro", "id_orden_trabajo", $num_ot);
	$turno= obtenerDato("orden_trabajo", "turno", "id_orden_trabajo", $num_ot);
	$fprog = obtenerDato("orden_trabajo", "fecha_prog", "id_orden_trabajo", $num_ot);
	$fecha_programada = modFecha($fprog,1);
	$hrs_mtto= obtenerDato("bitacora_mtto", "tiempo_total", "orden_trabajo_id_orden_trabajo", $num_ot);
	$fecha_Realizada= modFecha(obtenerDato("bitacora_mtto", "fecha_mtto", "orden_trabajo_id_orden_trabajo", $num_ot),1);
	$servicio = obtenerDato("orden_trabajo", "servicio", "id_orden_trabajo", $num_ot);
	$proveedor = obtenerDato("orden_trabajo", "proveedor_servicio", "id_orden_trabajo", $num_ot);
		
	//////***************************  CONSULTAS PARA OBTENER EL ID DEL VALE *******************************///
	//////**************************************************************************************************///

	$stm_sql= "SELECT id_vale FROM materiales_mtto JOIN bitacora_mtto ON bitacora_mtto_id_bitacora=id_bitacora WHERE orden_trabajo_id_orden_trabajo='$num_ot'";
	$rs=mysql_query($stm_sql);
	$datosId = mysql_fetch_array($rs);
	//Realizar la comparacion si el id es igual a vacion asignarle un valor cualquiera para que la consulta no arroje ningun resultado
	if($datosId['id_vale']=="")
		$datosId['id_vale']='NuLl';

	//Reasignar el valor de NA (No Aplica)al horometro o el odometro que tenga valor 0
	if ($horometro == 0){
		$horometro= 'NA';// si el horometro es igual a 0, cambiar su valor por NA
		$odometro= number_format($odometro,2,".",",")." Kms."; // darle el formato de número al odometro
	}
	
	else{ 
		$odometro= 'NA'; //si el odometro es igual a 0, cambiar su valor por NA
		$horometro= number_format($horometro,2,".",",")." Hrs."; // darle el formato de número al horometro
	}	
	
	
	//Definir las propiedades y la información del 1° renglon despues del encabezado
	$pdf->Cell(10);//Colocar una columna de 10 px de ancho para alinear las etiquetas
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(18,7,'EQUIPO:',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(5,7,$equipo,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(99);//Poner una columna de 99px entre el nombre del equipo y la etiqueta de Orden de Trabajo
	$pdf->Cell(26,7,'ORDEN DE TRABAJO:',0,0,'R');
	$pdf->SetTextColor(128, 0, 0);//Dar color rojo al numero de la Orden de Trabajo
	$pdf->SetFont('Arial','B',10);
	$pdf->Image('fondo.jpg',167,44,25);//Insertar una imagen con bordes redondos para escribir el siguiente contenido
	$pdf->Cell(10,7,$num_ot,0,1);//Colocar el No. de la Orden de Trabajo e indicar que la siguiente celda se excribirá en el siguiente renglon
		
	//Definir las propiedades y la información del 2° renglon despues del encabezado
	$pdf->SetTextColor(51, 51, 153);//Colocar todo el texto del documento en Azul Oscuro
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10);//Colocar una columna de 10 px de ancho para alinear las etiquetas
	$pdf->Cell(28,7,'DESCRIPCIÓN:',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(3,7,$descripcion,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(101);//Poner una columna de 101px entre el nombre del equipo y la etiqueta de Orden de Trabajo
	$pdf->Cell(15,7,'FECHA: ',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(30,7,$fecha,0,1);//Colocar la fecha e indicar que la siguiente celda se excribirá en el siguiente renglon
			
	//Definir las propiedades y la información del 3° renglon despues del encabezado
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10);//Colocar una columna de 10 px de ancho para alinear las etiquetas
	$pdf->Cell(25,7,'OPERADOR:',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(3,7,$operador,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(77);//Poner una columna de 77px entre el nombre del equipo y la etiqueta de Orden de Trabajo
	$pdf->Cell(42,7,'TIPO MANTENIMIENTO: ',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(30,7,$mantenimiento,0,1);//Colocar el tipo de mantenimiento e indicar que la siguiente celda se excribirá en el siguiente renglon
			
	//Definir las propiedades y la información del 4° renglon despues del encabezado
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10);//Colocar una columna de 10px de ancho para alinear las etiquetas
	$pdf->Cell(15,7,'TURNO:',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(3,7,$turno,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(100);//Colocar una columna de 10px de ancho para alinear las etiquetas
	$pdf->Cell(29,7,'TIPO SERVICIO:',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(3,7,$servicio,0,1);
	
	//Definir las propiedades y la información del 5° renglon despues del encabezado	
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10);//Colocar una columna de 130px de ancho para alinear las etiquetas
	$pdf->Cell(27,7,'HORÓMETRO: ',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(30,7,$horometro,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(66);//Colocar una columna de 105px de ancho para alinear las etiquetas
	$pdf->Cell(24,7,'ODÓMETRO: ',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(30,7,$odometro,0,1);

	//Definir las propiedades y la información del 6° renglon despues del encabezado
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10);//Colocar una columna de 10px de ancho para alinear las etiquetas
	$pdf->Cell(42,7,'FECHA PROGRAMADA:',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(3,7,$fecha_programada,0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(66);//Colocar una columna de 65px de ancho para alinear las etiquetas
	$pdf->Cell(36,7,'FECHA REALIZADA: ',0,0);
	if($fecha_Realizada!= '00/00/0000'){
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(3,7,$fecha_Realizada,0,1);
		$pdf->SetFont('Arial','U',10);
	}
	else{
		$pdf->Cell(3,7,'_____________',0,1);	
	}
		
	//Definir las propiedades y la información del 6° renglon despues del encabezado
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10);
	$pdf->Cell(27,5,'PROVEEDOR: ',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->MultiCell(140,5,$proveedor);
	
		
		
			/*****************************************TABLA DE MANTENIMIENTOS QUE CORRESPONDEN**********************************************/
	//Definimos el color de relleno de los valores numéricos en la tabla generada
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(243,243,243);
	//Colocar los titulos de las columnas de la tabla de Materiales
	$pdf->AddCol('nom_gama',180,'MANTENIMIENTOS QUE CORRESPONDEN ','L');
	
	//Propiedades de las columnas columnas de la tabla 
	$prop=array('HeaderColor'=>array(243,243,243), 'LineColor'=>array(0, 0, 255), 'color1'=>array(255,255,255), 'color2'=>array(255,255,255), 'padding'=>1);

	//Consulta que agrega los datos en la tabla que aparece en el PDF, El ultimo parametro ->2 es para diferenciar que tipo de documento es
	$pdf->Table("SELECT DISTINCT nom_gama FROM (gama JOIN actividades_ot ON gama_id_gama = id_gama) WHERE orden_trabajo_id_orden_trabajo='".$num_ot."'",$prop,"ot");

	//Completar los renglones dependiendo de la cantidad de registros que se agreguen en la Orden de Trabajo
	$datos = mysql_fetch_array(mysql_query("SELECT COUNT(nom_gama) AS cant FROM (gama JOIN actividades_ot ON gama_id_gama = id_gama)
	WHERE orden_trabajo_id_orden_trabajo='$num_ot'"));
	//Colocar el ultimo renglon para colocar la linea de cierre de la tabla
	$renglones = 1 - $datos['cant'];
	for($i=0;$i<$renglones;$i++){ 
		$pdf->Cell(7.95);//Adelandar las celdas 7.95 unidades para que coincidan con las celdas dibujadas por la funcion Table
		$pdf->Cell(180,5,'','LR',1);
	}
		
	//Colocar el ultimo renglon para colocar la linea de cierre de la tabla
	$pdf->Cell(7.95);//Adelandar las celdas 7.95 unidades para que coincidan con las celdas dibujadas por la funcion Table
	$pdf->Cell(180,5,'','LRB',1);
	
	//Colocar una celda de 6mm de alto como espacio entre parrafos, tablas, etc.
	$pdf->Cell(0,6,'',0,1);
	
		
			
	/*********************************************TABLA DE ACTIVIDADES REALIZADAS SEGUN LA GAMA*******************************************************/
	//**************************************************************//
	
	
	$pdf->Cell(7.95);//Adelandar las celdas 7.95 unidades para que coincidan con las celdas dibujadas por la funcion Table
	//Definimos el color de relleno de los valores numéricos en la tabla generada
	$pdf->SetFont('Arial','B',12);
	$pdf->SetFillColor(243,243,243);
	$pdf->Cell(140,5,'ACTIVIDADES REALIZADAS SEGÚN LA GAMA',1,0,"C",1);
	$pdf->Cell(40,5,'Realizado',1,1,"C",1);
	$pdf->Cell(7.95);
	$pdf->Cell(140,5,'',1,0,"C",1);
	$pdf->Cell(20,5,'SI',1,0,"C",1);
	$pdf->Cell(20,5,'NO',1,1,"C",1);
	$pdf->SetFont('Arial','',6);
	
	
	
	
	//Sentencia para extraer las gamas Aplicadas
	$stm_sql= "SELECT gama_id_gama FROM actividades_ot WHERE orden_trabajo_id_orden_trabajo='$num_ot'";
	$rsGama=mysql_query($stm_sql);
	if($datosGama = mysql_fetch_array($rsGama)){
		$cont=0;
		do{
			//Sentencia para extraer las gamas Aplicadas
			$stm_descGama= "SELECT descripcion FROM gama_actividades JOIN actividades ON actividades_id_actividad=id_actividad WHERE gama_id_gama='$datosGama[gama_id_gama]'";
			$rsGamaAct=mysql_query($stm_descGama);
			if($actGama = mysql_fetch_array($rsGamaAct)){
				
				do{
					
					
					$pdf->Cell(7.95);
					$pdf->Cell(140,5,++$cont.".- ".$actGama["descripcion"],1,0,"L",1);
					
					
					$pdf->Cell(20,5,'',1,0,"C",1);
					$pdf->Cell(20,5,'',1,1,"C",1);
					
										
					
					
					
				}while($actGama = mysql_fetch_array($rsGamaAct));
			}
		}while($datosGama = mysql_fetch_array($rsGama));
		$pdf->Cell(7.95);
		$pdf->Cell(180,0,'',"T",1,"C",0);
	}
	
	//Colocar una celda de 6mm de alto como espacio entre parrafos, tablas, etc.
	$pdf->Cell(0,6,'',0,1);
	
	$pdf->AddPage();

	/*********************************************TABLA DE REPARADOS POR (MECANICOS)*******************************************************/
	//**************************************************************//
	//Definimos el color de relleno de los valores numéricos en la tabla generada
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(243,243,243);
	$pdf->AddCol('nom_mecanico',180,'REPARADO POR ','L');
	
	//Propiedades de las columnas columnas de la tabla 
	$prop=array('HeaderColor'=>array(243,243,243), 'LineColor'=>array(0, 0, 255), 'color1'=>array(255,255,255), 'color2'=>array(255,255,255), 'padding'=>1);

	//Consulta que agrega los datos en la tabla que aparece en el PDF, El ultimo parametro ->2 es para diferenciar que tipo de documento es
	$consulta=("SELECT nom_mecanico  FROM (mecanicos JOIN bitacora_mtto ON bitacora_mtto_id_bitacora = id_bitacora)
	WHERE orden_trabajo_id_orden_trabajo='$num_ot'");

	$pdf->Table($consulta,$prop,"ot");

	
	//Colocar el ultimo renglon para colocar la linea de cierre de la tabla
	$pdf->Cell(7.95);//Adelandar las celdas 7.95 unidades para que coincidan con las celdas dibujadas por la funcion Table
	$pdf->Cell(180,5,'','LRB',1);
		
	//Colocar una celda de 6mm de alto como espacio entre parrafos, tablas, etc.
	$pdf->Cell(0,6,'',0,1);
	
	
	/*********************************************TABLA DE OBSERVACIONES*******************************************************/
	//**************************************************************//
	$pdf->SetFont('Arial','B',10);
	$pdf->SetFillColor(243,243,243);
	$pdf->AddCol('comentarios',180,'OBSERVACIONES','L');

	//Propiedades de las columnas columnas de la tabla 
	$prop=array('HeaderColor'=>array(243,243,243), 'LineColor'=>array(0, 0, 255), 'color1'=>array(255,255,255), 'color2'=>array(255,255,255), 'padding'=>1);

	//Consulta que agrega los datos en la tabla que aparece en el PDF, El ultimo parametro ->2 es para diferenciar que tipo de documento es
	$consulta=("SELECT comentarios FROM bitacora_mtto WHERE orden_trabajo_id_orden_trabajo='$num_ot'");

	$pdf->Table($consulta,$prop,"ot");

	//Colocar el ultimo renglon para colocar la linea de cierre de la tabla
	$pdf->Cell(7.95);//Adelandar las celdas 7.95 unidades para que coincidan con las celdas dibujadas por la funcion Table
	$pdf->Cell(180,5,'','LRB',1);
		
	//Colocar una celda de 10mm de alto como espacio entre parrafos, tablas, etc.
	$pdf->Cell(0,5,'',0,1);

	//**************************************************************//
	//*********************Fin de las tablas************************//
	//**************************************************************//
	
	//Colocar despues de la tabla los datos de la persona que autorizo la OT y las horas de mantenimiento
	//****************************************
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(45);
	$pdf->Cell(65,10,'TIEMPO TOTAL DE MANTENIMIENTO:',0,0);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(3,10,$hrs_mtto." Hrs:Min",0,0);
	//****************************************
	$pdf->Cell(0,15,'',0,1);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(50);
	$pdf->Cell(22,10,'AUTORIZÓ:',0,0);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(3,10,$autorizo_ot,0,1);
	$pdf->Cell(81);
	$pdf->Cell(40,-10,'_______________________________',0,1,'C');
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(0,20,'(Nombre y firma)',0,0,'C');
	//****************************************
	//Especificar Datos del Documento
	$pdf->SetAuthor($autorizo_ot);
	$pdf->SetTitle("ÓRDEN DE TRABAJO  ".$num_ot);
	$pdf->SetCreator($equipo);
	$pdf->SetSubject("Solicitud de Orden de Trabajo");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir de la Órden de Trabajo ".$num_ot." en el SISAD");
	$num_ot.='.pdf';

	//Mandar imprimir el PDF
	$pdf->Output($num_ot,"F");
	header('Location: '.$num_ot);
	//Borrar todos los PDF ya creados
	borrarArchivos();
		

	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica
	function obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus){		
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE $param_bus='$dato_bus'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
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