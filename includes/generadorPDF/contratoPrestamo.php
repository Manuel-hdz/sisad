<?php

//Incluir los Archivos Requeridos para las Conexiones a la BD y la modificacion de Fechas, asi como la Creacion del PDF y la conversión de numeros en letras
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
include("../op_operacionesBD.php");


//Declaración de la Clase
class PDF extends FPDF{

	function Header(){		
		
		//El Header no lleva nada de contenido para este archivo
		
	}//Cierre Header()

	//Page footer
	function Footer(){		
		//El Footer no lleva nada de contenido para este archivo
	}

}//Cierre de la clase PDF	


	//Connect to database 
	$conn = conecta('bd_recursos');
	
	//Crear el PDF y dar las propiedades que tendrá el docuemntos
	$pdf = new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetLeftMargin(20);//Margen Izquierdo
	$pdf->SetRightMargin(20);//Margen Derecho
	
	
	//Definir propiedades del contenido del documento
	$pdf->SetTextColor(0, 0, 0);//Colocar todo el texto del documento en Negro
	$pdf->SetDrawColor(0, 0, 255);//Definir Color de las lineas, rectangulos y bordes de celdas en color Azul	
	$pdf->SetFillColor(217,217,217);//Definir el color del fondo de las celdas que lo lleven en gris claro
	
	
	//Obtener el numero del prestamo
	$numPrestamo = $_GET['idPrestamo'];								
	//Obtener la Informacion General de la Orden de Trabajo para Servicios Externos
	$sql_datosPrestamo = "SELECT * FROM deducciones WHERE id_deduccion = '$numPrestamo'";
	//Extraer los datos del prestamo concedido, no se valida ya que al llegar a esta página se tiene la certeza de que los datos estan guardado en la BD
	$datosPrestamo = mysql_fetch_array(mysql_query($sql_datosPrestamo));
	
					
	//Guardar los Datos en la variables que se utilizarán para mostrarlos en el PDF
	$hora = date("h:i A");//Obtener la Hora en formato "05:45 PM"
	$fecha = verFecha(1);//Obtener la Fecha en formato "Domingo 01 de Enero de 2012"
	$rfcEmpleado = $datosPrestamo['empleados_rfc_empleado'];
	$montoPrestamo = number_format($datosPrestamo['total'],2,".",",");
	$abono = number_format($datosPrestamo['pago_periodo'],2,".",",");
	$periodo = $datosPrestamo['periodo'];
	$cadMontoPrestamo = convierteNumeroLetra($montoPrestamo);
	$cadAbono = convierteNumeroLetra($abono);
	
	//Verificar si existe ultimo pago
	$ultimoPago = $datosPrestamo['total'] - ($datosPrestamo['pago_periodo']*$datosPrestamo['cant_pagos']);
	$cadUltimoPago = "";
	if($ultimoPago>0){
		$cadUltimoPago = "y un último pago de $ ".number_format($ultimoPago,2,".",",")." (".convierteNumeroLetra($ultimoPago).")";
	}
			
	//Obtener los datos del Empleado al cual esta asignado el prestamo
	$sql_datosEmpleado = "SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombre, edo_civil, CONCAT(calle,' ',num_ext,' ',num_int) AS direccion, colonia, localidad, estado
							FROM empleados WHERE rfc_empleado = '$rfcEmpleado'";
	//Ejecutar Sentencia
	$datosEmpleado = mysql_fetch_array(mysql_query($sql_datosEmpleado));
	//Almacenar los datos del Empleado en variables
	$nombre = $datosEmpleado['nombre'];
	$estadoCivil = $datosEmpleado['edo_civil'];
	$direccion = $datosEmpleado['direccion']; 
	$colonia = $datosEmpleado['colonia'];
	$localidad = $datosEmpleado['localidad'];
	$estado = $datosEmpleado['estado'];
		
		
	/*******************************************************************************************************************************************************************/
	/***************************************************** COLOCAR EL CONTENIDO DEL DOCUMENTO *************************************************************************/
	/*******************************************************************************************************************************************************************/	
	//Colocar un espacio de 1 renglon de 5 mm de alto cada uno
	//$pdf->Cell(0,5,"",0,1,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	
	
	/*********************************************************** COLOCAR TITULO DEL CONTRATO ***************************************************************************/
	$pdf->SetFont('Arial','B',12);//Definir el tipo y tamaño de la letra
	$pdf->Cell(0,5,"CONTRATO DE MUTUO SIMPLE",0,1,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar renglon vacio de 5 mm de altura con ancho de toda la pagina
	
	
	/********************************************************** COLOCAR EL PRIMER PARRAFO DEL CONTRATO *****************************************************************/
	$primerParrafo = "En la ciudad de Fresnillo, estado de Zacatecas, siendo las $hora del día $fecha acuerdan el Ing. José Guillermo Martínez Román, en su carácter de representante legal de la empresa dominada Concreto Lanzado de Fresnillo S.A. de C.V. personalidad que acredita con documento notarial numero 3368 volumen 56; empresa originaria y vecina de Fresnillo, Zacatecas con domicilio fiscal en Calle TIRO SAN LUIS No. 2 Colonia Beleña de esta ciudad, y el Sr(a). $nombre estado civil $estadoCivil ocupación empleado de Concreto Lanzado, S.A. de C.V., originario y vecino de Calle $direccion, de la Colonia $colonia en $localidad, $estado. Con el objeto de celebrar en este momento un contrato de mutuo simple que se regirá de acuerdo en las siguientes:";
		
	$pdf->SetFont('Arial','',10);//Definir el tipo y tamaño de la letra
	$pdf->MultiCell(0,5,$primerParrafo,"","J",0);//MultiCell(float w, float h, string txt, mixed border, string align, int fill)	
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar renglon vacio de 5 mm de altura con ancho de toda la pagina
	
	
	/********************************************************** COLOCAR TITULO DE LAS CLAUSULAS ************************************************************************/
	$pdf->SetFont('Arial','B',12);//Definir el tipo y tamaño de la letra
	$pdf->Cell(0,5,"CLÁUSULAS",0,1,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar renglon vacio de 5 mm de altura con ancho de toda la pagina
	
	
	/**************************************************************** COLOCAR 1° CLAUSULA ******************************************************************************/
	$primeraClausula = "PRIMERA. Manifiestan las partes contratantes estar de acuerdo en que la empresa Concreto Lanzado de Fresnillo S.A. de C.V. en lo sucesivo el mutuante, entregue al Sr(a). $nombre, en lo sucesivo el mutuatario la cantidad de $ $montoPrestamo ($cadMontoPrestamo) con la obligación de que este ultimo restituya la misma cantidad y especie en un periodo máximo de un año que comenzara a contar a partir del día siguiente en que se celebre el contrato.";
	//Agregar el parrafo de la 1° clausula al archivo PDF
	$pdf->SetFont('Arial','',10);//Definir el tipo y tamaño de la letra
	$pdf->MultiCell(0,5,$primeraClausula,"","J",0);//MultiCell(float w, float h, string txt, mixed border, string align, int fill)	
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar renglon vacio de 5 mm de altura con ancho de toda la pagina
	
	
	/**************************************************************** COLOCAR 2° CLAUSULA ******************************************************************************/			
	$segundaClausula = "SEGUNDA. Los pagos serán por la cantidad de $ $abono ($cadAbono) de forma $periodo $cadUltimoPago vía nómina.";
	//Agregar el parrafo de la 2° clausula al archivo PDF
	$pdf->MultiCell(0,5,$segundaClausula,"","J",0);//MultiCell(float w, float h, string txt, mixed border, string align, int fill)	
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar renglon vacio de 5 mm de altura con ancho de toda la pagina
	
	
	/**************************************************************** COLOCAR 3° CLAUSULA ******************************************************************************/			
	$terceraClausula = "TERCERA. Ambas partes están de acuerdo es la C. TIRO SAN LUIS #2 de la Col. Beleña de la ciudad de Fresnillo Zac.";
	//Agregar el parrafo de la 3° clausula al archivo PDF
	$pdf->MultiCell(0,5,$terceraClausula,"","J",0);//MultiCell(float w, float h, string txt, mixed border, string align, int fill)
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar renglon vacio de 5 mm de altura con ancho de toda la pagina
	
	
	/**************************************************************** COLOCAR 4° CLAUSULA ******************************************************************************/			
	$cuartaClausula = "CUARTA. El mutuatario se obliga a hacer saber el señalar como domicilio para el cumplimiento de la obligación el ubicado en la calle Tiro San Luis No. 02, mutuante con 07 (siete) días de anticipación a la fecha en que se cumplirá su adeudo, en caso de optar por liquidar su obligación en fecha anterior al plazo de la cláusula primera o al periodo de pago señalado en la cláusula segunda de este contrato.";
	//Agregar el parrafo de la 4° clausula al archivo PDF
	$pdf->MultiCell(0,5,$cuartaClausula,"","J",0);//MultiCell(float w, float h, string txt, mixed border, string align, int fill)
	$pdf->Cell(0,5,"",0,1,"",0);//Colocar renglon vacio de 5 mm de altura con ancho de toda la pagina
	
	
	/**************************************************************** COLOCAR 5° CLAUSULA ******************************************************************************/			
	$quintaClausula = "QUINTA. Se firma el presenta el contrato, original y dos copias ante los testigos C. AURORA LEDESMA MACIAS y C. JOSE DE JESUS CARRILLO SANTACRUZ mayores de edad, casados, de nacionalidad mexicana, originarios y vecinos de esta ciudad, así como conocer personalmente a las partes contratantes constándoles además, que los mismos son perfectamente capaces para celebrar el contrato de mutuo simple que aquí se establece, firmándose dos tantos por todas la personas que en el mismo aparecen bajo diferentes caracteres. Damos fe.";
	//Agregar el parrafo de la 4° clausula al archivo PDF
	$pdf->MultiCell(0,5,$quintaClausula,"","J",0);//MultiCell(float w, float h, string txt, mixed border, string align, int fill)
	
	
	/************************************************************* COLOCAR SECCION DE FIRMAS ***************************************************************************/			
	//Colocar un espacio de 2 renglones de 5 mm de alto cada uno
	$pdf->Cell(0,10,"",0,1,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	
	$pdf->Cell(90,5,"________________________________________",0,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(90,5,"________________________________________",0,1,"C",0);
	
	$pdf->Cell(90,5,"MUTUANTE",0,0,"C",0);
	$pdf->Cell(90,5,"MUTUATARIO",0,1,"C",0);
	
	$pdf->Cell(90,5,"Concreto Lanzado de Fresnillo S.A. de C.V.",0,0,"C",0);
	$pdf->Cell(90,5,"Sr(a). ".$nombre,0,1,"C",0);
	
	$pdf->Cell(90,5,"Ing. José Guillermo Martínez Román",0,1,"C",0);
	$pdf->Cell(90,5,"Representante Legal",0,1,"C",0);
	
	//Colocar un espacio de 2 renglones de 5 mm de alto cada uno
	$pdf->Cell(180,10,"",0,1,"C",0);
	
	$pdf->Cell(90,5,"________________________________________",0,0,"C",0);
	$pdf->Cell(90,5,"________________________________________",0,1,"C",0);
	
	$pdf->Cell(90,5,"TESTIGO",0,0,"C",0);
	$pdf->Cell(90,5,"TESTIGO",0,1,"C",0);						


	/***************************************************** PROPIEDADES DEL DOCUMENTO PDF *****************************************************************/
	$pdf->SetAuthor("LIC. JOSÉ DE JESÚS CARRILLO SANTACRUZ");
	$pdf->SetTitle("CONTRATO PRÉSTAMO ".$numPrestamo);
	$pdf->SetCreator("RECURSOS HUMANOS");
	$pdf->SetSubject("Contrato Mutuo Simple de Préstamo");
	$pdf->SetKeywords("Qubic Tech, Contrato Elaborado a Partir de los Datos Registros en el SISAD para el Préstamo ".$numPrestamo);
	$numPrestamo.='.pdf';

	//Mandar imprimir el PDF en el mismo directorio donde se encuentra este archivo de 'contratoPrestamo.php'
	$pdf->Output($numPrestamo,"F");
	//Direccionar al PDF recien creado en el directorio
	header('Location: '.$numPrestamo);
	//Borrar todos los PDF ya creados en el directorio para evitar que se acumulen los archivos
	borrarArchivos();
		
		
	/***************************************************** FUNCIONES *****************************************************************/		
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
	}//Cierre de la función borrarArchivos()


?>