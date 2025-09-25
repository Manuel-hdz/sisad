<?php

//Incluir los Archivos Requeridos para las Conexiones a la BD y la modificacion de Fechas, asi como la Creacion del PDF
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");


//class PDF extends PDF_MySQL_Table{

//Declaración de la Clase
class PDF extends FPDF{

	function Header(){		
		//Colocar el Logo, Image(float x, float y, float ancho, float alto)
	    $this->Image('logo-clf.jpg',8,8,53.9,23);
		//Colocar el Color de Texto en Azul Oscuro (RGB)
		$this->SetTextColor(31, 73, 125);		
	    $this->Cell(0,2,"",0,1,0,0);
	    //Colocar los datos del Titulo del Formato
		$this->SetFont('Arial','B',15);//Colocar el texto en Arial bold 15
		$this->Cell(50,5,"",0,0,0,0);
	    $this->Cell(140,5,"CONCRETO LANZADO DE FRESNILLO, S.A. DE C.V.",0,1,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		$this->Ln(1);
		$this->SetFont('Arial','B',12);//Colocar el texto en Arial bold 15
		$this->Cell(50,5,"",0,0,"",0);//Colocar una celda de 55 mm de ancho desde inicio de la página
		$this->Cell(140,5,"UNIDAD DE SALUD OCUPACIONAL",0,1,"C",0);
		$this->Ln(1);
		$this->SetFont('Arial','B',10);//Colocar el texto en Arial bold 15
		$this->Cell(50,5,"",0,0,"",0);//Colocar una celda de 55 mm de ancho desde inicio de la página
		$this->Cell(140,5,"SOLICITUD DE EXÁMEN MÉDICO",0,1,"C",0);
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
		$this->Cell(0,5,"Página ".$this->PageNo()." de {nb}",0,0,'R',0);
	}

}//Cierre de la clase PDF	

	//Crear el PDF y dar las propiedades que tendrá el docuemntos
	$pdf = new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetDrawColor(0, 0, 255);//Definir Color de las lineas, rectangulos y bordes de celdas en color Azul
	$pdf->SetAutoPageBreak(true,30);//Indicar que cuando una celda exceda el margen inferior de 3 cm(30mm), esta se dibuje en la siguiente pagina
	$pdf->SetFillColor(217,217,217);//Definir el color del fondo de las celdas que lo lleven en gris claro

	//Obtener el numero de la Orden de Trabajo para Servicios Externos y el Nombre del Departamento	
	$claveSolMed = $_GET['id'];
	//Connect to database 
	$conn = conecta('bd_clinica');
	//Obtener el nombre de la empresa
	$empresa=obtenerDato("solicitud_examen", "catalogo_empresas_id_empresa", "num_solicitud", $claveSolMed);
	$empresa=obtenerDato("catalogo_empresas", "nom_empresa", "id_empresa", $empresa);
	//Obtener la Fecha de Solicitud
	$fecha=obtenerDato("solicitud_examen", "fecha", "num_solicitud", $claveSolMed);
	$fecha=modFecha($fecha,1);
	//Definir el tipo y tamaño de Fuente
	$pdf->SetFont('Arial','B',10);//Colocar el texto en arial tamaño 10
	//RENGLONES DE 190 MM
	//Renglon Título
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(160,5,"Número de Solicitud:",0,0,"R",0);
	$pdf->SetTextColor(128, 0, 0);
	$pdf->Cell(30,5,$claveSolMed,"B",1,"R",0);
	$pdf->Ln();
	//Definir el tipo y tamaño de Fuente
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 10
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(43,5,"NOMBRE DEL CONTRATISTA:",0,0,"L",0);
	$pdf->Cell(115,5,$empresa,"B",0,"L",0);
	$pdf->Cell(12,5,"FECHA:",0,0,"L",0);
	$pdf->Cell(20,5,$fecha,"B",1,"R",0);
	$pdf->Ln();
	//RENGLONES DE 190 MM
	//Extraer los empleados
	$sql="SELECT id_registro,nom_empleado_ext,forma_pago FROM empleados_externos WHERE solicitud_examen_num_solicitud='$claveSolMed'";
	$rs=mysql_query($sql);
	if($datos=mysql_fetch_array($rs)){
		do{
			//Obtener la posicion de Y
			$posYLN=$pdf->GetY()+0.5;
			//Si la posicion es mayor a 240, Agregar una nueva Pagina
			if($posYLN>230){
				$pdf->AddPage('P');
				//Renglon Título
				$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
				//Definir el tipo y tamaño de Fuente
				$pdf->SetFont('Arial','B',10);//Colocar el texto en arial tamaño 10
				$pdf->Cell(160,5,"Número de Solicitud:",0,0,"R",0);
				$pdf->SetTextColor(128, 0, 0);
				$pdf->Cell(30,5,$claveSolMed,"B",1,"R",0);
				$pdf->Ln();
				//Definir el tipo y tamaño de Fuente
				$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 10
				$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
			}
			$pdf->Cell(43,5,"NOMBRE DEL TRABAJADOR:",1,0,"L",1);
			$pdf->Cell(100,5,$datos["nom_empleado_ext"],1,0,"L",0);
			$pdf->Cell(27,5,"FORMA PAGO",1,0,"L",1);
			$pdf->Cell(20,5,$datos["forma_pago"],1,1,"L",0);
			
			$pdf->Cell(95,5,"TIPO DE EXÁMEN",1,0,"C",1);
			$pdf->Cell(95,5,"REALIZAR",1,1,"C",1);
			//Extraer los examenes almacenados
			$sql_exa="SELECT id_examen,nom_examen FROM catalogo_examen";
			$rsExa=mysql_query($sql_exa);
			if($datosExa=mysql_fetch_array($rsExa)){
				do{
					/*
					//Obtener la posicion de Y
					$posYLN=$pdf->GetY()+0.5;
					//Si la posicion es mayor a 240, Agregar una nueva Pagina
					if($posYLN>230){
						$pdf->AddPage('P');
						//Renglon Título
						$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
						//Definir el tipo y tamaño de Fuente
						$pdf->SetFont('Arial','B',10);//Colocar el texto en arial tamaño 10
						$pdf->Cell(160,5,"Número de Solicitud:",0,0,"R",0);
						$pdf->SetTextColor(128, 0, 0);
						$pdf->Cell(30,5,$claveSolMed,"B",1,"R",0);
						$pdf->Ln();
						//Definir el tipo y tamaño de Fuente
						$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 10
						$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
					}
					*/
					$pdf->Cell(95,5,$datosExa["nom_examen"],1,0,"C",0);
					
					$posY=$pdf->GetY()+0.5;
					$pdf->Image('check1.jpg',150,$posY,4,3.5);
					if(aplicarExamen($datos["id_registro"],$claveSolMed,$datosExa["id_examen"])==1){//Aplicar el Examen
						$pdf->Image('check2.jpg',150,$posY,4,3.5);
						$pdf->Cell(95,5,"",1,1,"C",0);
					}
					else{//No aplicar el Examen
						$pdf->Image('check1.jpg',150,$posY,4,3.5);
						$pdf->Cell(95,5,"",1,1,"C",0);
					}
				}while($datosExa=mysql_fetch_array($rsExa));
			}
			$pdf->Ln();
		}while($datos=mysql_fetch_array($rs));
	}
	//Obtener la posicion de Y
	$posYLN=$pdf->GetY()+0.5;
	//Si la posicion es mayor a 240, Agregar una nueva Pagina
	if($posYLN>230){
		$pdf->AddPage('P');
		//Renglon Título
		$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
		//Definir el tipo y tamaño de Fuente
		$pdf->SetFont('Arial','B',10);//Colocar el texto en arial tamaño 10
		$pdf->Cell(160,5,"Número de Solicitud:",0,0,"R",0);
		$pdf->SetTextColor(128, 0, 0);
		$pdf->Cell(30,5,$claveSolMed,"B",1,"R",0);
		$pdf->Ln();
		//Definir el tipo y tamaño de Fuente
		$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 10
		$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	}
	//Obtener las observaciones
	$observaciones=obtenerDato("solicitud_examen", "observaciones", "num_solicitud", $claveSolMed);
	$pdf->Cell(190,5,"OBSERVACIONES",1,1,"C",1);
	$pdf->MultiCell(190,5,$observaciones,1,"J",0);
	
	$pdf->Ln();
	//Obtener el Pie de Pagina
	$autorizo=obtenerDato("solicitud_examen", "autorizo", "num_solicitud", $claveSolMed);
	$gerenciaAdmin=obtenerDato("solicitud_examen", "gerencia_admin", "num_solicitud", $claveSolMed);
	$respUso=obtenerDato("solicitud_examen", "resp_uso", "num_solicitud", $claveSolMed);
	$pdf->Cell(63,5,"AUTORIZÓ",0,0,"C",0);
	$pdf->Cell(64,5,"GERENCIA ADMINISTRATIVA",0,0,"C",0);
	$pdf->Cell(63,5,"UNIDAD DE SALUD OCUPACIONAL CLF",0,1,"C",0);
	$pdf->Ln(3);
	$pdf->Cell(63,5,"__________________________________",0,0,"C",0);
	$pdf->Cell(64,5,"__________________________________",0,0,"C",0);
	$pdf->Cell(63,5,"__________________________________",0,1,"C",0);
	$pdf->Cell(63,5,$autorizo,0,0,"C",0);
	$pdf->Cell(64,5,$gerenciaAdmin,0,0,"C",0);
	$pdf->Cell(63,5,$respUso,0,1,"C",0);
	
	/***************************************************** PROPIEDADES DEL DOCUMENTO PDF *****************************************************************/
	$pdf->SetAuthor("UNIDAD DE SALUD OCUPACIONAL");
	$pdf->SetTitle("SOLICITUD MÉDICA ".$claveSolMed);
	$pdf->SetCreator("UNIDAD DE SALUD OCUPACIONAL");
	$pdf->SetSubject("Solicitud Médica");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir de la Solicitud Médica ".$claveSolMed." en el SISAD");
	$claveSolMed.='.pdf';

	//Mandar imprimir el PDF en el mismo directorio donde se encuentra este archivo de 'ordenServicioExternbo.php'
	$pdf->Output($claveSolMed,"F");
	//Direccionar al PDF recien creado en el directorio
	header('Location: '.$claveSolMed);
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
	
	function aplicarExamen($idRegistro,$claveSolMed,$idExamen){
		$sql="SELECT catalogo_examen_id_examen FROM exa_ext_realizados WHERE solicitud_examen_num_solicitud='$claveSolMed' AND catalogo_examen_id_examen='$idExamen' AND empleados_externos_id_registro='$idRegistro'";
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs))
			return 1;
		else
			return 0;
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