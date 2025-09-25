<?php

//Incluir los Archivos Requeridos para las Conexiones a la BD y la modificacion de Fechas, asi como la Creacion del PDF
//require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
require('rotation.php');


//Declaraci�n de la Clase
class PDF extends PDF_Rotate{
	function Header(){		
		
		//Colocar el Marco a la Pagina
		$this->Image('fondoActa.jpg',1,1,215,278);
		//Colocar el Logo, Image(float x, float y, float ancho, float alto)
	    $this->Image('logo-clf.jpg',10,10,53.9,23);
		
		//Colocar el Color de Texto en Azul Oscuro (RGB)
		$this->SetTextColor(31, 73, 125);		
	    	    
				
	    //Colocar los datos del Titulo del Formato
		$this->SetFont('Arial','B',10);//Colocar el texto en Arial bold 10
		$this->Cell(0,7,"",0,1,"",0);//Colocar un Celda de 7 mm de alto y un ancho de toda la pagina (aprox. 196 mm)
	    $this->Cell(0,5,"CONCRETO LANZADO DE FRESNILLO, S.A. DE C.V.",0,1,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		$this->Cell(50,5,"",0,0,"",0);//Colocar una celda de 50 mm de ancho del inicio de la p�gina a la leyenda de Mantenimiento Depto
		$this->Cell(96,5,"MANTENIMIENTO ".$_GET['nom_depto'],0,0,"C",0);
				
		//Colocar la Fecha de Creacion del Documento
	    $this->SetFont('Arial','B',8);//Colocar el texto en Arial bold 8		
		$this->Cell(25,5,"FECHA: ",0,0,"R",0);		
	    $this->SetFont('Arial','',8);//Colocar el texto en Arial 8
		$this->Cell(25,5,$_GET['fecha_reg'],0,1,"L",0);
				
		//Colocar el No. de la Orden de Trabajo para Servicios Externos
		$this->Cell(146,5,"",0,0,"",0);//Colocar una celda para colocar lel Numero de Orden a la Derecha de La p�gina bajo la Fecha de Creaci�n
		$this->SetFont('Arial','B',8);//Colocar el texto en Arial bold 8
		$this->Cell(25,5,"NO ORDEN: ",0,0,"R",0);
		$this->SetTextColor(192, 0, 0);//Colocar un color Rojo Oscuro para el No. de la orden		
	    $this->SetFont('Arial','',8);//Colocar el texto en Arial 8
		$this->Cell(25,5,$_GET['id_orden'],0,1,"L",0);
		
		
		//Colocar la Leyenda subrayada de Orden de Ttrabajo Para Servicios Externos
	    $this->SetFont('Arial','UB',12);//Definir el tipo y tama�o de la letra
		$this->SetTextColor(31, 73, 125);//Colocar el Color de Texto en Azul Oscuro (RGB)
		$this->Cell(0,5,"ORDEN DE TRABAJO PARA SERVICIOS EXTERNOS",0,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
																
	    //Colocar un espacio de 10 mm de espacion entre el encabezado y el contenido del documento
	    $this->Ln(10);
		parent::Header();
	}//Cierre Header()

	//Page footer
	function Footer(){		
		//Posicionar el puntero a 12mm desde el final de la pagina
	    $this->SetY(-12);
		
		//Definir el Estilo del texto	    
	    $this->SetFont('Arial','B',6);
		 //Numero de Pagina
		$this->Cell(0,5,"Pagina ".$this->PageNo()." de {nb}",0,0,'R',0);
		/******************/
		//Imprimir mensaje si el Pedido se cancelo
		$msje="*El Presente Documento No Representa un Comprobante Valido Para Su Pago";
		//Put the watermark
		$this->SetFont('Arial','B',8);
		//Gris Oscuro
		$this->SetTextColor(157,157,157);
		//Rojo
		//$this->SetTextColor(217,150,148);
		$this->RotatedText(10,270,$msje,0);
		/******************/
	}
	
	//Funcion que gira el Texto a un angulo especificado	
	function RotatedText($x, $y, $txt, $angle){
		//Text rotated around its origin
		$this->Rotate($angle,$x,$y);
		$this->Text($x,$y,$txt);
		$this->Rotate(0);
	}

}//Cierre de la clase PDF	


	//Connect to database 
	$conn = conecta('bd_mantenimiento');
	
	//Crear el PDF y dar las propiedades que tendr� el docuemntos
	$pdf = new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetTextColor(51, 51, 153);//Colocar todo el texto del documento en Azul Oscuro
	$pdf->SetDrawColor(0, 0, 255);//Definir Color de las lineas, rectangulos y bordes de celdas en color Azul
	$pdf->SetAutoPageBreak(true,30);//Indicar que cuando una celda exceda el margen inferior de 3 cm(30mm), esta se dibuje en la siguiente pagina
	$pdf->SetFillColor(217,217,217);//Definir el color del fondo de las celdas que lo lleven en gris claro
	$pdf->SetAutoPageBreak(true,10);//Indicar que cuando una celda exceda el margen inferior de 1cm(10mm), esta se dibuje en la siguiente pagina
	
	
	//Obtener el numero de la Orden de Trabajo para Servicios Externos y el Nombre del Departamento	
	$num_ot = $_GET['id_orden'];								
	//Obtener la Informacion General de la Orden de Trabajo para Servicios Externos
	$sql_stm_datosOT = "SELECT * FROM orden_servicios_externos WHERE id_orden = '$num_ot'";
	//Extraer los datos del Orden del Resulset obtenido, no se valida ya que al llegar a esta p�gina se tiene la certeza de que los datos estan guardado en la BD
	$datosOT = mysql_fetch_array(mysql_query($sql_stm_datosOT));
	
	//Guardar los Datos en la variables que se utilizar�n para mostrarlos en el PDF
	$fechaRegistro = modFecha($datosOT['fecha_creacion'],1);
	$clasificacion = $datosOT['clasificacion'];
	$fechaSolicitud = modFecha($datosOT['fecha_entrega'],1);
	$fechaRecepcion = modFecha($datosOT['fecha_recepcion'],1);	
	$proveedor = $datosOT['nom_proveedor'];
	$direccion = $datosOT['direccion'];
	$repProveedor = $datosOT['rep_proveedor'];
	$encCompras = $datosOT['encargado_compras'];
	$solicito = $datosOT['solicito'];
	$autorizo = $datosOT['autorizo'];
	$costoTotal = $datosOT['costo_total'];
	$moneda = $datosOT['moneda'];
	$factura = $datosOT['factura'];
	$centro_costos = $datosOT['id_control_costos'];
	
	//Identificar cual de las clasificacaciones fue seleccionada
	$fab = ""; $rep = ""; $rec = ""; $gar = ""; $serv = "";
	switch($clasificacion){
		case "FABRICACION":
			$fab = "X";
		break;
		case "REPARACION":
			$rep = "X";
		break;
		case "RECONSTRUCCION":
			$rec = "X";
		break;
		case "GARANTIA":
			$gar = "X";
		break;
		case "SERVICIOS":
			$serv = "X";
		break;
	}//Cierre switch($clasificacion)
	
	
	
		
	/*******************************************************************************************************************************************************************/
	/***************************************************** COLOCAR EL CONTENIDO DEL DOCUMENTO *************************************************************************/
	/*******************************************************************************************************************************************************************/
	
	//En el contenido caben 43 renglones de 5 mm de altura
	$renglones = 43;
	$renActuales = 0;//Contador de renglones
	
	/*********************************************************** CLASIFICACION DEL TRABAJO *****************************************************************************/
	$pdf->SetFont('Arial','B',8);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y las opciones de la clasificaci�n del trabajo
	$pdf->Cell(35,5,"FABRICACION( $fab )",0,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(35,5,"REPARACION( $rep )",0,0,"C",0);
	$pdf->Cell(35,5,"RECONSTRUCCION( $rec )",0,0,"C",0);
	$pdf->Cell(35,5,"GARANTIA( $gar )",0,0,"C",0);
	$pdf->Cell(35,5,"SERVICIOS( $serv )",0,0,"C",0);		
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de las opciones de la clasificaci�n del trabajo y el fin de la p�gina
	$renActuales++;//Contador de renglones
	
	
	/************************************************** TABLA DE TIEMPOS DE SERVICIO Y DATOS DEL PROVEEDOR **************************************************************/
	$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(60,5,"TIEMPOS DE SERVICIO",1,0,"C",1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(115,5,"PROVEEDOR",1,0,"C",1);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	$renActuales++;//Contador de renglones
	
	//Colocar las etiquetas de Fecha de Entrega y Nombre del Proveedor
	$pdf->SetFont('Arial','B',8);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(60,5,"FECHA ENTREGA SOLICITUD","LTR",0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(115,5,"NOMBRE","LTR",0,"L",0);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	$renActuales++;//Contador de renglones
	
	//Antes de colocar el nombre del proveedor, obtener la cantidad de renglones que ser�n dibujados
	$palabras = cortarCadena($proveedor,55);
	$alto = $palabras['cantRenglones'] * 5;
	$renActuales += $palabras['cantRenglones'];//Contador de renglones
	
	//Colocar los datos de Fecha de Entrega y Nombre del Proveedor
	$pdf->SetFont('Arial','',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,$alto,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(60,$alto,$fechaSolicitud,"LBR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->MultiCell(115,5,$proveedor,"LBR","L",0);//MultiCell(float w, float h, string txt, mixed border, string align, int fill)
		
	//Colocar las etiquetas de Fecha de Entrega y Nombre del Proveedor
	$pdf->SetFont('Arial','B',8);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(60,5,"FECHA RECEPCION UNIDAD O PIEZA","LTR",0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(115,5,"DIRECCION","LTR",0,"L",0);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	$renActuales++;//Contador de renglones
	
	//Antes de colocar la Direcci�n, obtener la cantidad de renglones que seran dibujados
	$palabras = cortarCadena($direccion,55);
	$alto = $palabras['cantRenglones'] * 5;
	$renActuales += $palabras['cantRenglones'];//Contador de renglones
	
	//Colocar los datos de Fecha de Entrega y Nombre del Proveedor
	$pdf->SetFont('Arial','',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,$alto,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(60,$alto,$fechaRecepcion,"LBR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->MultiCell(115,5,$direccion,"LBR","L",0);//MultiCell(float w, float h, string txt, mixed border, string align, int fill)
		
	//Colocar espacio entre la Tabla anterior y la siguiente
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$renActuales++;//Contador de renglones
	
	
	/***************************************************** TABLA DESCRIPCION DEL SERVICIO EFECTUADO *****************************************************************/
	$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,10,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(115,10,"DESCRIPCION DEL SERVICIO EFECTUADO",1,0,"L",1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(20,10,"EQUIPO",1,0,"C",1);
	$pdf->Cell(20,5,"COSTO","TR",0,"C",1);
	$pdf->Cell(20,10,"MONEDA",1,1,"C",1);
	$pdf->Cell(145,5,"",0,0,"",0);
	$pdf->Cell(20,-5,"TOTAL","TR",1,"C",1);
	$pdf->Cell(185,5,"",0,1,"",0);
	$renActuales += 2;//Contador de renglones, agregar dos renglones ya que el encabezado ocupa esa cantidad
	
	//Sentencia SQL para Obtener las Actividades a realizar y los equipos en los que se va a trabajar
	$stm_sql_actividades = "SELECT descripcion, equipo, costo_actividad FROM actividades_realizadas WHERE orden_servicios_externos_id_orden = '$num_ot'";
	$rs_actividades = mysql_query($stm_sql_actividades);	
	
	//Verificar si hay actividades parea mostrar
	if($datosActividades=mysql_fetch_array($rs_actividades)){
		do{
			//Antes de colocar la Descripci�n de Cada Actividades, obtener la cantidad de renglones que seran dibujados
			$palabras = cortarCadena($datosActividades['descripcion'],70);
			$alto = $palabras['cantRenglones'] * 5;//Obtener la altura que tendr�n las columnas antes y despu�s de la columna de descripci�n
			$renActuales += $palabras['cantRenglones'];//Contador de renglones
			
			
			
			//Verificar si los renglones que seran dibujados caben en la primera P�gina
			if($renActuales>$renglones){
				$pdf->AddPage();//Agregar una Pagina y agregar el Titulo de la Tabla
				$renActuales = 0;//Reiniciar el Contador de renglones
				
				$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
				$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
				$pdf->Cell(135,5,"DESCRIPCION DEL SERVICIO EFECTUADO",1,0,"L",1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
				$pdf->Cell(20,5,"EQUIPO",1,0,"C",1);
				$pdf->Cell(20,5,"COSTO",1,0,"C",1);
				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
				$renActuales++;//Contador de renglones
			}//Cierre if($renActuales>45)
			
			
			
			//Colocar la Tabla de Tiempos de Servicio y Datos del Proveedor
			$pdf->SetFont('Arial','',8);//Definir el tipo y tama�o de la letra
			$pdf->Cell(10,$alto,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
			
			//Colocar la columna de descripci�n utilizando el Metodo MultiCell(float w, float h, string txt, mixed border, string align, int fill)
			$pdf->MultiCell(115,5,$datosActividades['descripcion'],1,"L",0);
			
			//Hacer negativa la altura para que la Celda dibujada se muestre encima del nivel especificado
			$alto = $alto * -1;
			
			//Colocar la columna del Equipo
			$pdf->Cell(125,0,"",0,0,"",0);//Colocar un espcacio equivalente a las columnas de 10mm y 135mm para adelantar la columna de Equipo
			$pdf->Cell(20,$alto,$datosActividades['equipo'],1,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)			
						
			//Dibujar la columna de Costo
			$costo_actv = "$".number_format($datosActividades['costo_actividad'] / (1 + (16/100) ),2,".",",");

			//Variable para quitar iva mediante la orden de trabajo
			$proveedor_sin_iva = "PROVEEDOR INDIRECTO";

			//Quitar iva en costo activad para una orden en especifico
			if ($proveedor == $proveedor_sin_iva) {
				$costo_actv = "$".number_format($datosActividades['costo_actividad'],2,".",",");
			}

			if($datosActividades['costo_actividad']!=0){
				$pdf->Cell(20,$alto,$costo_actv,1,0,"R",0);
				
			}
			else if($datosActividades['costo_actividad']==0)
				$pdf->Cell(20,$alto,"",1,0,"R",0);				
			
			$pdf->Cell(20,$alto,"",1,0,"R",0);
			//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina y una altura de 0 para que el siguiente renglon se dibuje correctamente
			$pdf->Cell(10,0,"",0,1,"",0);
			
		}while($datosActividades=mysql_fetch_array($rs_actividades));		
	}//Cierre if($datosActividades=mysql_fetch_array($rs_actividades))
	else{
		//Es poco probable que haya un Orden de Trabajo para Servicios Externos sin Actividades, en caso que exista colocar un renglon que indique que no hay actividades		
		$pdf->SetFont('Arial','',10);//Definir el tipo y tama�o de la letra
		$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
		$pdf->Cell(115,5,"NO Hay Actividades Registradas",1,0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		$pdf->Cell(20,5,"",1,0,"C",0);
		$pdf->Cell(20,5,"",1,0,"C",0);
		$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
		$renActuales++;//Contador de renglones				
	}//Cierre else if($datosActividades=mysql_fetch_array($rs_actividades))
	
	//Colocar la Factura y el total del Costo	
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(65,5,"",1,0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(30,5,"NO FACTURA",1,0,"L",1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->SetFont('Arial','',8);//Definir el tipo y tama�o de la letra
	$pdf->Cell(20,5,$factura,1,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	
	$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(20,5,"SUBTOTAL",1,0,"C",1);
	$pdf->SetFont('Arial','B',8);//Definir el tipo y tama�o de la letra
	if($costoTotal==0)
		$pdf->Cell(20,5,"",1,0,"R",0);
	else{
		if ($proveedor == $proveedor_sin_iva) {
			$pdf->Cell(20,5,"$".number_format($costoTotal,2,".",","),1,0,"R",0);
		} else {
			$pdf->Cell(20,5,"$".number_format($costoTotal / (1 + (16/100) ),2,".",","),1,0,"R",0);
		}
	}
	$pdf->Cell(20,5,"",1,0,"R",0);
		
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar un espacion entre la Tabla de Servicio Efectuado y Material Utilizado
	$renActuales++;//Contador de renglones
	
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(115,5,"",1,0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(20,5,"IVA",1,0,"C",1);
	$pdf->SetFont('Arial','B',8);//Definir el tipo y tama�o de la letra
	//Colocar una Celda vacia en el caso de que el costo aun no este incluido
	if($costoTotal==0)
		$pdf->Cell(20,5,"",1,0,"R",0);
	else{
		if ($proveedor == $proveedor_sin_iva) {
			$pdf->Cell(20,5,"$".number_format(0,2,".",","),1,0,"R",0);
		} else {
			$pdf->Cell(20,5,"$".number_format($costoTotal - ($costoTotal / (1 + (16/100) )),2,".",","),1,0,"R",0);
		}
	}
	$pdf->Cell(20,5,"",1,0,"R",0);
		
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	$renActuales++;//Contador de renglones
	
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(115,5,"",1,0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(20,5,"TOTAL",1,0,"C",1);
	$pdf->SetFont('Arial','B',8);//Definir el tipo y tama�o de la letra
	//Colocar una Celda vacia en el caso de que el costo aun no este incluido
	if($costoTotal==0){
		$pdf->Cell(20,5,"",1,0,"R",0);
		$pdf->Cell(20,5,"",1,0,"R",0);
	}
	else{	
		$pdf->Cell(20,5,"$".number_format($costoTotal,2,".",","),1,0,"R",0);
		$pdf->Cell(20,5,$moneda,1,0,"C",0);
	}
		
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	$renActuales++;//Contador de renglones
	
	
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar un espacion entre la Tabla de Servicio Efectuado y Material Utilizado
	$renActuales++;//Contador de renglones
	
	
	//Verificar si los renglones que seran dibujados caben en la primera P�gina antes de colocar la tabla de materiales
	if($renActuales>$renglones){
		$pdf->AddPage();//Agregar una Pagina y agregar el Titulo de la Tabla
		$renActuales = 0;//Reiniciar el Contador de renglones				
	}//Cierre if($renActuales>45)
							
							
	/***************************************************** TABLA MATERIAL UTILIZADO *****************************************************************/
	$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(175,5,"MATERIAL UTILIZADO",1,0,"C",1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	$renActuales++;//Contador de renglones
	
	
	//Sentencia SQL para Obtener las Actividades a realizar y los equipos en los que se va a trabajar
	$stm_sql_materiales = "SELECT descripcion, cantidad FROM materiales_usados WHERE orden_servicios_externos_id_orden = '$num_ot'";
	$rs_materiales = mysql_query($stm_sql_materiales);	
	
	//Verificar si hay actividades parea mostrar
	if($datosMateriales=mysql_fetch_array($rs_materiales)){
		do{						
			//Verificar si los renglones que seran dibujados caben en la primera P�gina
			if($renActuales>$renglones){
				$pdf->AddPage();//Agregar una Pagina y agregar el Titulo de la Tabla
				$renActuales = 0;//Reiniciar el Contador de renglones				
				
				$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
				$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
				$pdf->Cell(175,5,"MATERIAL UTILIZADO",1,0,"C",1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
				$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
				$renActuales++;//Contador de renglones
			}//Cierre if($renActuales>45)
			
			
			//Colocar cada Material
			$pdf->SetFont('Arial','',8);//Definir el tipo y tama�o de la letra
			$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
			$pdf->Cell(175,5,$datosMateriales['cantidad']." ".$datosMateriales['descripcion'],1,0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
			$renActuales++;//Contador de renglones
	
	
		}while($datosMateriales=mysql_fetch_array($rs_materiales));
	}//Cierre if($datosMateriales=mysql_fetch_array($rs_materiales))
	else{		
		//Colocar una Leyenda indicando que no hay Registro de Materiales
		$pdf->SetFont('Arial','',10);//Definir el tipo y tama�o de la letra
		$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
		$pdf->Cell(175,5,"NO Hay Materiales Registrados",1,0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
		$renActuales++;//Contador de renglones				
	}//Cierre else if($datosMateriales=mysql_fetch_array($rs_materiales))
	
	
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar un espacion entre la Tabla de Material Utilizado y la Tabla de Firmas
	$renActuales++;//Contador de renglones
	
	
	//Verificar si los renglones de la Tabla de Firmas(Esta tabla ocupa 9 renglones de 5mm de alto) caben en la P�gina Actual, s� no, agregar una p�gina nueva
	if(($renActuales+9)>$renglones){
		$pdf->AddPage();//Agregar una Pagina y agregar el Titulo de la Tabla
		$renActuales = 0;//Reiniciar el Contador de renglones				
	}//Cierre if($renActuales>45)
	
	
	/***************************************************** TABLA REALIZO/SOLICITO *****************************************************************/
	$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(87.5,5,"REALIZO",1,0,"C",1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(87.5,5,"SOLICITO",1,0,"C",1);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina	
	
	$pdf->SetFont('Arial','B',8);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(87.5,5,"REPRESENTANTE DE SERVICIOS GENERALES","LTR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(87.5,5,"","LTR",0,"C",0);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(87.5,5,"","LR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(87.5,5,"","LR",0,"C",0);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	
	$pdf->SetFont('Arial','',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(87.5,5,$repProveedor,"LBR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(87.5,5,$solicito,"LBR",0,"C",0);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	
	$pdf->SetFont('Arial','B',8);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(87.5,5,"RECEPCION ENCARGADO COMPRAS","LTR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(87.5,5,"RECEPCION DIRECCION GENERAL","LTR",0,"C",0);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(87.5,5,"","LR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(87.5,5,"","LR",0,"C",0);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	
	$pdf->SetFont('Arial','',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(87.5,5,$encCompras,"LBR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(87.5,5,$autorizo,"LBR",0,"C",0);
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina				
	
	/***************************************************** TABLA CENTRO DE COSTOS*****************************************************************/
	$descripcion_cc = obtenerDato("bd_recursos.control_costos", "descripcion", "id_control_costos", $centro_costos);
	
	$pdf->SetFont('Arial','B',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(175,5,"CENTRO DE COSTOS",1,0,"C",1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina	
	
	$pdf->SetFont('Arial','',10);//Definir el tipo y tama�o de la letra
	$pdf->Cell(10,5,"",0,0,"",0);//Colocar una columna de espacio de 10mm entre el inicio de la p�gina y la Tabla
	$pdf->Cell(175,5,$descripcion_cc,"LBR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(10,5,"",0,1,"",0);//Colocar una columna de espacio de 10mm de la tabla y el fin de la p�gina
	
	/***************************************************** PROPIEDADES DEL DOCUMENTO PDF *****************************************************************/
	$pdf->SetAuthor("MANTENIMIENTO ".$_GET['nom_depto']);
	$pdf->SetTitle("ORDEN DE TRABAJO  ".$num_ot);
	$pdf->SetCreator("MANTENIMIENTO ".$_GET['nom_depto']);
	$pdf->SetSubject("Solicitud de Orden de Trabajo para Servicios Externos");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir de la Orden de Trabajo Externa ".$num_ot." en el SISAD");
	$num_ot.='.pdf';

	//Mandar imprimir el PDF en el mismo directorio donde se encuentra este archivo de 'ordenServicioExternbo.php'
	$pdf->Output($num_ot,"F");
	//Direccionar al PDF recien creado en el directorio
	header('Location: '.$num_ot);
	//Borrar todos los PDF ya creados en el directorio para evitar que se acumulen los archivos
	borrarArchivos();
		
		
	/***************************************************** FUNCIONES *****************************************************************/
	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica
	function obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus){		
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE $param_bus='$dato_bus'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
	}//Fin de la funcion obtenerDato($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
	
	//Esta funci�n elimina los archivos PDF que se hayan generado anteriormente
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
?>