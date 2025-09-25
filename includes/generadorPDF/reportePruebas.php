<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
include ("../../includes/op_operacionesBD.php");


///Clase para poner los encabezados y los pies de pagina
class PDF extends FPDF{ 

	var $tipo_obra;
		
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
		$this->Cell(70,50,"F 5.10.0 -01 REPORTE DE CONTROL DE CALIDAD DE ".$this->tipo_obra,0,0,"C");
		$this->SetFont('Arial','B',10);
		//$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
		//$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(62.5,9,'LABORATORIO DE CONTROL DE CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
	    //Line break
	    $this->Ln(35);
		parent::Header();
		
	}

	//Page footer 
	/*
	function Footer(){
		//Position at 1.5 cm from bottom
	    $this->SetY(-20);
	    //Arial italic 8
	    $this->SetFont('Arial','',7);
		$this->Cell(0,15,'       Fecha Emisión:                                               No. de Revisión:                                               Fecha de Revisión:',0,0,'L');
	    //Numero de Pagina
		$this->Cell(0,15,'Página '.$this->PageNo().' de {nb}',0,0,'R');
		$this->SetY(-17);
		$this->Cell(0,15,'            Abril - 09'.'                                                                '.'02'.'                                                                 '.'    Abril - 11',0,0,'L');
		$this->SetY(-20);
		$this->Cell(0,25,'F. 4.2.1 - 01 / Rev. 02',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}*/
}//Cierre de class PDF extends FPDF  


	//************************************************************************************************//
	//*****************    SECCIÓN PARA OBTENER INFORMACIÓN POR MEDIO DE CONSULTAS     ***************// 
	//************************************************************************************************//
	
	//Conectar a la BD de Recursos Humanos
	$conn = conecta("bd_recursos");

	//Consulta para obtener el nombre del laboratorista
	$stm_sql = ("SELECT empleados_rfc_empleado FROM organigrama WHERE departamento='LABORATORIO'");
	$rs = mysql_query($stm_sql);
	$datos = mysql_fetch_array($rs);
	
	//Consulta para obtener el nombre gerencia tecnica			
	$stm_sql1 = ("SELECT empleados_rfc_empleado FROM organigrama WHERE departamento='GERENCIA TECNICA'");
	$rs = mysql_query($stm_sql1);
	$datos1 = mysql_fetch_array($rs);
	
	//Obtener nombre Recursos Humanos
	$nomLaboratorista = obtenerNombreEmpleado($datos["empleados_rfc_empleado"]);
	$nomGerTec = obtenerNombreEmpleado($datos1["empleados_rfc_empleado"]);
	
	//Conectar con la base de datos de Laboratorio
	$conne=conecta('bd_laboratorio');

	//recuperar el id
	$id=$_GET['id'];
	
	//recuperar el nombre
	if(isset($_GET['nombre']))
		$nombre=strtoupper($_GET['nombre']);
	else 
		$nombre="";
	//recuperar el puesto
	if(isset($_GET['puesto']))	
		$puesto=strtoupper($_GET['puesto']);
	else 
		$puesto="";
	//recuperar el empresa
	if(isset($_GET['empresa']))	
		$empresa=strtoupper($_GET['empresa']);
	else 
		$empresa="";

	//Obtener la fecha del dia
	$fechaDia= modFecha(date("Y-m-d"),2);
	
	$sql_stm = ("SELECT DISTINCT tipo_prueba,expediente,codigo_localizacion,equipo_mezclado,num_muestra,revenimiento,fprimac_proyecto,fecha_colado 
				FROM prueba_calidad JOIN muestras ON muestras_id_muestra=id_muestra JOIN mezclas ON mezclas_id_mezcla=id_mezcla 
				WHERE id_prueba_calidad = '$id'");
	
	$rs = mysql_query($sql_stm);
	$info = mysql_fetch_array($rs);	
	
			
	//Creacion del Objeto para Generar el PDF
	$pdf=new PDF('P','mm','Letter');
	$pdf->tipo_obra = $info['tipo_prueba'];
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',11);//Definir el Estilo de la fuente
	$pdf->SetTextColor(51, 51, 153);//Definir el Color del Texto en formato RGB
	$pdf->SetFillColor(243,243,243);//Definir el color de Relleno para las celdas cuyo valor de la propiedad 'fill' sea igual a 1
	$pdf->SetDrawColor(0, 0, 255);
	
	
	//Dibujar las Fechas de Muestreo y de Reporte
	$pdf->SetFont('Arial','B',7);//Definir el estilo de Fuente para las Fechas	
	$pdf->Cell(115,5);//Colocar una celda vacia de 11.5 cm de ancho y 0.5 cm de alto antes de colocar la Etiqueta de la Fecha de Muestra			          
	$pdf->Cell(30,5,'FECHA DE MUESTREO:',0,0,'R');
	$pdf->Cell(30,5,modFecha($info['fecha_colado'],2),0,1,'L');	
	$pdf->Cell(115,5);//Colocar una celda vacia de 11.5 cm de ancho y 0.5 cm de alto antes de colocar la Etiqueta de la Fecha de Muestra			          
	$pdf->Cell(30,5,'FECHA DE REPORTE:' ,0,0,'R');
	$pdf->Cell(30,5,$fechaDia,0,1,'L');


	//Datos de la persona a la que va dirigido el Reporte (Nombre, Puesto y Empresa)
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10,5);//Colocar una celda vacia de 1 cm de ancho y 0.5 cm de alto antes de colocar el Nombre
	$pdf->Cell(60,5,$nombre,0,1,'L');
	$pdf->Cell(10,5);//Colocar una celda vacia de 1 cm de ancho y 0.5 cm de alto antes de colocar el Nombre
	$pdf->Cell(60,5,$puesto,'',1,'L',0);
	$pdf->Cell(10,5);//Colocar una celda vacia de 1 cm de ancho y 0.5 cm de alto antes de colocar el Nombre	
	$pdf->Cell(60,5,$empresa,'',1,'L',0);			
	
	
	//Colocar una celda del ancho de la hoja con altura de 0.5 cm entre los datos de la persona y la sig. sección de datos
	$pdf->Cell(0,5,'',0,1,'',0);//Cell(float w, float h, string txt, mixed border, int ln, string align, int fill)


	//Dibujar el cuadro donde se colocara la leyenda de EXPEDIENTE		
	$pdf->Cell(10,5);//Mover 10 mm hacia la derecha
	$pdf->Cell(46,5,'EXPEDIENTE:',1,0,'C',1);
	$pdf->SetFont('Arial','',10);	
	$pdf->Cell(60,5,$info['expediente'],0,0,'C');
	//$pdf->Cell(20,5);//Colocar un espacio de 2 cm entre el dato desplegado y la siguiente etiqueta a la derecha
	
	//Dibujar el cuadro donde se colocara la leyenda de N. MUESTRA	
	$pdf->SetFont('Arial','B',10);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(46,5,'N. MUESTRA:',1,0,'C',1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(30,5,$info['num_muestra'],0,1,'C');
	
	
	//Colocar un  renglon de espacio, el cual tiene un ancho de toda la pagina y una altura de 5 mm
	$pdf->Cell(0,5,'',0,1,'');


	//Verificar si aplica el codigo o localización segun el Tipo de Prueba (Concreto, Obra Zarpeo y Obra Externa)
	//Concreto => Código
	//Obra de Zarpeo y Externa => Localización 
	if($info['tipo_prueba']!='CONCRETO')
		$codigo_loc='LOCALIZACIÓN';
	else
		$codigo_loc='CÓDIGO';
	//Obtener la Cantidad de renglones que conformará la cadena, admitiendo 29 caracteres por renglon
	$cadSeg = cortarCadena($info['codigo_localizacion'],29);	
	$cantRens = $cadSeg['cantRenglones'];		
	
	//Definir el tipo de letra del Código o Localización de la Muestra
	$pdf->SetFont('Arial','B',10);		
	$pdf->Cell(10,5);//Mover 10 mm hacia la derecha						
	//Colocar la etiqueta con un alto de acuerdo a la cantidad de renglones que serán dibujados
	$pdf->Cell(46,$cantRens*5,$codigo_loc.':',1,0,'C',1);
	$pdf->SetFont('Arial','',10);
	//Colocar la cadena con N cantidad de renglones con un alto de 5 mm c/uno
	$pdf->MultiCell(60,5,$info['codigo_localizacion'],0,'C');//MultiCell(float w, float h, string txt, mixed border, string align, int fill)		
	$pdf->SetFont('Arial','B',10);	
	//Colocar un celda de 11.6 cm de ancho y una altura negativa de 5 mm para alinear la siguiente etiqueta con el ultimop renglon dibujado por MultiCell
	$pdf->Cell(116,-5);
	//Coloar la Etiqueta de Revenimiento con un ancho de 4.6 cm y una altura negativa dada por la cantidad de renglones multiplicada por 5 mm
	$pdf->Cell(46,($cantRens*5)*(-1),'REVENIMIENTO:',1,0,'C',1);
	$pdf->SetFont('Arial','',10);
	//Coloar el valor del Revenimiento con un ancho de 4.6 cm y una altura negativa dada por la cantidad de renglones multiplicada por 5 mm
	$pdf->Cell(30,($cantRens*5)*(-1),$info['revenimiento']." CM",0,1,'C');


	//Colocar un  renglon de espacio, el cual tiene un ancho de toda la pagina y una altura de la cantidad de renglones de la Localización por 5 mm mas 5mm de espacion extra
	$pdf->Cell(0,($cantRens*5)+5,'',0,1,'');			
		
	
	//Dibujar el cuadro donde se colocara la leyenda de EQUIPO DE MEZCLADO	
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10,5);//Mover 10 mm hacia la derecha	
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(46,5,'EQUIPO DE MEZCLADO:',1,0,'C',1);	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(60,5,$info['equipo_mezclado'],0,0,'C');	
	//$pdf->Cell(20,5);//Colocar un espacio de 2 cm entre el dato desplegado y la siguiente etiqueta a la derecha
	//Dibujar el cuadro donde se colocara la leyenda de F C PROYECTO	
	$pdf->SetFont('Arial','B',10);	
	$pdf->Cell(46,5,'F´ C PROYECTO :',1,0,'C',1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(30,5,$info['fprimac_proyecto']." KG/CM²",0,1,'C',0);
	
	
	//Colocar un  renglon de espacio, el cual tiene un ancho de toda la pagina y una altura de 5 mm
	$pdf->Cell(0,10,'',0,1,'');												
	
	
	
	/*****************************************************************************************************************************
	 *******************************************************DATOS DEL ESPECIMEN***************************************************
	 *****************************************************************************************************************************/
	//Dibujar el cuadro donde se colocara la leyenda de DATOS DEL ESPECÍMEN	
	$pdf->Cell(10,5);//Mover 10 mm hacia la derecha	
	$pdf->SetFont('Arial','B',10);	
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(182,5,'DATOS DEL ESPÉCIMEN','',1,'C');
	//Colocar un espacio de 5 mm entre el Titulo y el Inicio de la tabla
	$pdf->Cell(0,5,'',0,1,'L');	
	
	//Colocar los Titulos de las columnas de la Tabla	
	$pdf->SetFont('Arial','',5);
	$pdf->Cell(9,7);//Mover 9 mm hacia la derecha	
	$pdf->Cell(17,7,'N.M.',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(20,7,'FECHA COLADO',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(18,7,'EDAD EN DÍAS',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(20,7,'FECHA RUPTURA',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(17,7,'F´C',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(18,7,'DIÁMETRO cm',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(21,7,'CARGAR RUPTURA kg',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(17,7,'ÁREA cm2',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(17,7,'Kg/cm2',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(17,7,'%',1,1,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	
	
	//Ejecutar la Sentencia para Obtener la Cantidad de Registros del Detalle de la Prueba de Calidad
	$rs_detalle = mysql_query("SELECT edad,fecha_ruptura,fprima_c,carga_ruptura,kg_cm2,porcentaje,fecha_colado,diametro,area,observaciones
								FROM (detalle_prueba_calidad JOIN prueba_calidad ON prueba_calidad_id_prueba_calidad=id_prueba_calidad) 
								JOIN muestras ON muestras_id_muestra=id_muestra WHERE id_prueba_calidad = '$id' ORDER BY fecha_ruptura");
	//Obtener cantidad de renglones
	$renglones = mysql_num_rows($rs_detalle);
	$altoCelda = 7 * $renglones;
	
	//Dibujar el Detalle de la Prueba de Calidad que sera mostrada en el PDF
	$primerReg = 0;
	$observaciones = "";
	$noObs = 1;
	while($datos_detalle=mysql_fetch_array($rs_detalle)){
		//Guardar las Observaciones en una variable tipo cadena
		if($datos_detalle['observaciones']!=""){
			$observaciones .= $noObs.".- ".$datos_detalle['observaciones']."\n";
			$noObs++;
		}
		
		//Dibujar el Primer Renglon con las Celdas Combinadas
		if($primerReg==0){
			//Definir el Color de los bordes de las celdas, el color y tipo de letra
			$pdf->SetDrawColor(0, 0, 255);
			$pdf->SetTextColor(51, 51, 153);
			$pdf->SetFont('Arial','',5);
					
			//Mover 9 mm el inicio de la celda para alinearla
			$pdf->Cell(9);
			$pdf->Cell(17,$altoCelda,$info['num_muestra'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(20,$altoCelda,modfecha($datos_detalle['fecha_colado'],5),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(18,7,$datos_detalle['edad'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(20,7,modfecha($datos_detalle['fecha_ruptura'],5),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,$altoCelda,$datos_detalle['fprima_c'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(18,$altoCelda,$datos_detalle['diametro'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(21,7,number_format($datos_detalle['carga_ruptura'],2,".",","),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,$altoCelda,$datos_detalle['area'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,$datos_detalle['kg_cm2'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,$datos_detalle['porcentaje'].'%',1,1,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			
			$primerReg = 1;
		}
		else{//Dibujar el resto de los renglones			
						
			//Mover 9 mm el inicio de la celda para alinearla
			$pdf->Cell(9);
			$pdf->Cell(17,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(20,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(18,7,$datos_detalle['edad'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(20,7,modfecha($datos_detalle['fecha_ruptura'],5),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(18,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(21,7,number_format($datos_detalle['carga_ruptura'],2,".",","),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,$datos_detalle['kg_cm2'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,$datos_detalle['porcentaje'].'%',1,1,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		}
	
	}//Cierre while($datos_detalle=mysql_fetch_array($rs_detalle))				
		
	//Colocar un Espacio entre la tabla de Datos del Espécimen y la de Observaciones con un ancho de toda la pagina y una altura de 5 mm
	$pdf->Cell(0,5,'',0,1); 
		

	/******************************************************************
	 ***********************OBSERVACIONES******************************
	 ******************************************************************/	
	//Lo necesario para las observaciones
	$pdf->SetFont('Arial','',10);
	//Move to 8 cm to the right
	$pdf->Cell(9,5);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(182,5,'OBSERVACIONES','LRT',1,'L',0);		
	//Ciclo que permite recorrer las observaciones que han sido almacenadas en la consulta del detalle		
	$pdf->Cell(9,5);
	$pdf->MultiCell(182,5,$observaciones,'LRB','L',0);		
	
	
	
	//Colocar un espacio de 10 mm entre la tabla de Obserbaciones y la Lista de Normas
	$pdf->Cell(0,10,'',0,1);
	
	
	
	/******************************************************************
	 ***************************NORMAS*********************************
	 ******************************************************************/		
	// Realizar una consulta para obterer el catalogo de pruegas
	$sql_normas = "SELECT DISTINCT catalogo_pruebas_id_prueba, norma, nombre 
					FROM pruebas_realizadas JOIN catalogo_pruebas ON catalogo_pruebas_id_prueba=id_prueba
					WHERE prueba_calidad_id_prueba_calidad = '$id'";
	$rs_normas = mysql_query($sql_normas);
	
	//Definir el Estilo de la letra
	$pdf->SetFont('Arial','',8);
	
	if($datos_normas=mysql_fetch_array($rs_normas)){
		do{	
			//Obtener el nombre de la norma 
			$nomNorma = $datos_normas['norma'];
			//Obtener la descripcion de la norma obtenida
			$norma = $datos_normas['nombre'];
				
			
			//Mover 1 cm a la derecha
			$pdf->Cell(10);
			//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
			$pdf->Cell(160,4,$nomNorma.",  ".$norma,0,1,'L');//Cell(float w, float h, string txt, mixed border, int ln, string align, int fill)		
		}while($datos_normas=mysql_fetch_array($rs_normas));
	}//Cierre if($datos_normas=mysql_fetch_array($rs_normas))
	
	
	//Colocar un Espacio entre la tabla dibujada y las suiguiente
	$pdf->Cell(180,14,'',0,1); 
	
	//Dibujar el cuadro donde se colocara la leyenda de ATENTAMENTE	
	$pdf->Cell(30);
	$pdf->SetFont('Arial','B',10);	
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(140,4,'ATENTAMENTE','',1,'C');
	//poner el espacio entre este ultimo renglon y el siguiente
	$pdf->Cell(180,0,'',0,1,'L'); 
		
	//Lo necesario para el nombre y firma de cada uno de los interesados
	$pdf->Cell(9);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(180,20,' ','',1,'C',0);
	$pdf->Cell(0,0,'',0,1);
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->SetFont('Arial','',7);
	//$pdf->Cell(10);
	$pdf->Cell(-1);
	$pdf->Cell(110,0,'_________________________________','',1,'C',0);
	$pdf->Cell(290,0,'_________________________________','',1,'C',0);
	
	//Move to 8 cm to the right
	$pdf->Cell(9);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(180,4,' ','',1,'C',0);
	$pdf->Cell(0,0,'',0,1);
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(-1);
	$pdf->Cell(110,0,"ING. ".$nomLaboratorista, '',1,'C',0);
	$pdf->Cell(110,5,'JEFE DE LABORATORIO', '',1,'C',0);
	$pdf->Cell(290,-5,'GERENTE TÉCNICO','',1,'C',0); 
	$pdf->Cell(290,0,"ING. ".$nomGerTec,'',1,'C',0); 

	//**************************************************************//
	//*********************Fin de las tablas************************//
	//**************************************************************//
	
	/***************************************************************/
	/*****************1° PAGINA CON IMAGENES************************/
	/***************************************************************/
	
	//Dar un salto de pagina
	$pdf-> Addpage("p");
	//Dibujar el cuadro donde se colocara la leyenda de ATENTAMENTE	
	$pdf->SetFont('Arial','B',10);	
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(180,-10,'INFORME FOTOGRÁFICO',0,0,'C');
	
		
	//Estos contadores nos ayudaran a colocar únicamente 4 imagenes en cada pagina
	$contP = 0; $contE = 0; $contO = 0;
	//Estos contadores nos ayudaran a saber cuantas imagenes mas hay disponibles
	$imgsP_extra = 0; $imgsE_extra = 0; $imgsO_extra = 0;			
	
	
	//Definir el Tamaño de la fuente de las Etiquetas que seran mostradas en las Imagenes
	$pdf -> SetFontSize(8);
	//Crear la variable que guardara la ruta donde se encuentran las imagenes de la prueba que se inluye en el Reporte
	$ruta="../../pages/lab/documentos/".$id;
	//Vericar que el Directorio proporcionado sea valido.
	if(is_dir($ruta)){
		//Abrir el Directorio dado para leer los archivos que contenga
		if($gestor=opendir($ruta)){
			//Leer cada uno de los archivs dentro del directorio
			$py = 50; $ey = 50; $oy = 50;
			while(false!==($nomFoto=readdir($gestor))){				
		   		if ($nomFoto != "." && $nomFoto != "..") {			
					//Analizar el nombre de cada Foto para colocarla en la posición correspondiente
					//P -> Presentada, E -> Ensayada, 0 -> Obtenida										
					$subfijo = substr($nomFoto,0,1);
					switch($subfijo){						
						case "P":
							if($contP<4){
								//Obtener la cantidad de Dias
								$dias = substr($nomFoto,1,2);
								//Imprimir la imagen en una posición dada
								$pdf -> Image($ruta."/".$nomFoto,20,$py,55,40);
								//Colocar el texto debajo de la Imagen como Etiqueta
								$pdf -> Text(25, $py+45, "Muestra Presentada a los ".$dias." días");
								$py += 55;
								
								$contP++;
							}
							else
								$imgsP_extra++;
						break;
						case "E":						
							if($contE<4){
								//Obtener la cantidad de Dias
								$dias = substr($nomFoto,1,2);	
								//Imprimir la imagen en una posición dada
								$pdf -> Image($ruta."/".$nomFoto,80,$ey,55,40);
								//Colocar el texto debajo de la Imagen como Etiqueta
								$pdf -> Text(85, $ey+45, "Muestra Ensayada a los ".$dias." días");
								$ey += 55;
								
								$contE++;
							}
							else
								$imgsE_extra++;
						break;
						case "O":
							if($contO<4){
								//Obtener la cantidad de Dias
								$dias = substr($nomFoto,1,2);
								//Imprimir la imagen en una posición dada
								$pdf -> Image($ruta."/".$nomFoto,140,$oy,55,40);
								//Colocar el texto debajo de la Imagen como Etiqueta
								$pdf -> Text(145, $oy+45, "Muestra Obtenida a los ".$dias." días");
								$oy += 55;
								
								$contO++;
							}
							else
								$imgsO_extra++;
						break;
					}
		   		}
	    	}	   	
			closedir($gestor);	
		}
	}
	
	
	
	/***************************************************************/
	/*****************2° PAGINA CON IMAGENES************************/
	/***************************************************************/
	
	//Verificar si es necesario colocar una pagima extra para las imagenes
	if($imgsP_extra>0 || $imgsE_extra>0 || $imgsO_extra>0){
		//Dar un salto de pagina
		$pdf->Addpage("p");
		//Dibujar el cuadro donde se colocara la leyenda de ATENTAMENTE	
		$pdf->SetFont('Arial','B',10);	
		//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
		$pdf->Cell(180,-20,'INFORME FOTOGRÁFICO',0,0,'C');
		
		//Estos contadores nos ayudaran a Imprimir a partir de la Imagen 5 de cada categoria
		$imgsP = 0; $imgsE = 0; $imgsO = 0;
		
		
		//Definir el Tamaño de la fuente de las Etiquetas que seran mostradas en las Imagenes
		$pdf -> SetFontSize(8);
		//Crear la variable que guardara la ruta donde se encuentran las imagenes de la prueba que se inluye en el Reporte
		$ruta="../../pages/lab/documentos/".$id;
		//Vericar que el Directorio proporcionado sea valido.
		if(is_dir($ruta)){
			//Abrir el Directorio dado para leer los archivos que contenga
			if($gestor=opendir($ruta)){
				//Leer cada uno de los archivs dentro del directorio
				$py = 50; $ey = 50; $oy = 50;
				while(false!==($nomFoto=readdir($gestor))){				
					if ($nomFoto != "." && $nomFoto != "..") {			
						//Analizar el nombre de cada Foto para colocarla en la posición correspondiente
						//P -> Presentada, E -> Ensayada, 0 -> Obtenida										
						$subfijo = substr($nomFoto,0,1);
						switch($subfijo){						
							case "P":
								if($imgsP>=4){
									//Obtener la cantidad de Dias
									$dias = substr($nomFoto,1,2);
									//Imprimir la imagen en una posición dada
									$pdf -> Image($ruta."/".$nomFoto,20,$py,55,40);
									//Colocar el texto debajo de la Imagen como Etiqueta
									$pdf -> Text(25, $py+45, "Muestra Presentada a los ".$dias." días");
									$py += 55;									
								}
								else
									$imgsP++;
							break;
							case "E":						
								if($imgsE>=4){
									//Obtener la cantidad de Dias
									$dias = substr($nomFoto,1,2);	
									//Imprimir la imagen en una posición dada
									$pdf -> Image($ruta."/".$nomFoto,80,$ey,55,40);
									//Colocar el texto debajo de la Imagen como Etiqueta
									$pdf -> Text(85, $ey+45, "Muestra Ensayada a los ".$dias." días");
									$ey += 55;								
								}
								else
									$imgsE++;
							break;
							case "O":
								if($imgsO>=4){
									//Obtener la cantidad de Dias
									$dias = substr($nomFoto,1,2);
									//Imprimir la imagen en una posición dada
									$pdf -> Image($ruta."/".$nomFoto,140,$oy,55,40);
									//Colocar el texto debajo de la Imagen como Etiqueta
									$pdf -> Text(145, $oy+45, "Muestra Obtenida a los ".$dias." días");
									$oy += 55;									
								}
								else
									$imgsO++;
							break;
						}
					}
				}	   	
				closedir($gestor);	
			}
		}						
	}
	
	
	
	//****************************************
	//Especificar las Propiedades del PDF que se esta creando
	$pdf->SetAuthor("");
	$pdf->SetTitle("MUESTRAS".$id);
	$pdf->SetCreator($id);
	$pdf->SetSubject("Muestras");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir de la Muestra ".$id." en el SISAD");
	$id.='.pdf';

	//Mandar imprimir el PDF
	$pdf->Output($id,"F");
	header('Location: '.$id);
	//Borrar todos los PDF ya creados
	borrarArchivos();
		
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
	
	
	/*Esta funcion divide una cadena en la cantidad exacta de Caracteres o menor de acuerdo al acomodo de palabras*/
	function cortarCadena($cadena,$carsPorLinea){	
		//Variable para Almacenar la Nueva Cadena
		$datosCadena = array("cantRenglones"=>0);
		//Obtener el Tamaño de la Cadena Original
		$tamCadena = strlen($cadena);
		//Separar la Cadena Original en un Arreglo de Caracteres, donde cada posición del Arreglo contiene un solo caracter de la cadena original
		$caracteres = str_split($cadena);
			
		//Si el tamaño de la Cadena excede la cantidad de caracteres especificada, proceder a separarla
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
					
					//El siguiente caracter de la posición del ultimo espacio en blanco encontrado se vuelve la posición inicial
					$carInicial = $posBlank+1;
					
					//Colocar el contador del Ciclo for a la posición del ultimo espacio en blanco encontrado
					$i = $posBlank;
									
					//Resetear el contador de caracteres para no exceder el tamaño del renglon
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
	
	
?>