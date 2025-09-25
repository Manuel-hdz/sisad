<?php
require('mysql_table.php');
require("../conexion.inc");
include("../func_fechas.php");


class PDF extends PDF_MySQL_Table{

	function Header(){
		///Logo
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
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(135,4,'',0,1,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(195,5,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,1,'R');
		$this->SetFont('Arial','B',10);
		$this->Ln(10);
		$this->MultiCell(195,5,"REPORTE DE RECORRIDOS DE SEGURIDAD",0,"C",0);
	    //Line break
	    $this->Ln(5);
		parent::Header();
	}

	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
	    $this->SetY(-12);
		//Definir el Estilo del texto	    
	    $this->SetFont('Arial','B',6);
		$this->SetTextColor(0, 0, 255);
		$this->SetDrawColor(0, 0, 255);//Color de los Bordes
		$this->Cell(0,1,'',"B",0,'C');
		$this->Ln(0.5);
		$this->Cell(0,1,'',"B",1,'C');
		 //Numero de Pagina
		$this->Cell(0,5,"Página ".$this->PageNo()." de {nb}",0,0,'R',0);
	}

}//Cierre de la clase PDF	
	
	//Obtener el numero de la Salida
	$idRecorrido = $_GET['id'];

	//Crear el Objeto PDF y Agregar las Caracteristicas Iniciales
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();	
	
	/**************************************************************************************************************/
	/************************************DATOS GENERALES DE LA SALIDA**********************************************/
	/**************************************************************************************************************/
	$responsable=obtenerDato("bd_seguridad","recorridos_seguridad","responsable","id_recorrido",$idRecorrido);
	$fecha=modFecha(obtenerDato("bd_seguridad","recorridos_seguridad","fecha","id_recorrido",$idRecorrido),1);
	$observaciones=obtenerDato("bd_seguridad","recorridos_seguridad","observaciones","id_recorrido",$idRecorrido);
	//NUMERO DE RECORRIDO
	$pdf->SetDrawColor(0, 0, 255);//Color de los Bordes
	$pdf->SetFont('Arial','B',10);//Tipo de Letra
	$pdf->SetTextColor(51, 51, 153);//Color del Texto
	$pdf->Cell(170,5,"Clave Recorrido: ",0,0,"R",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->SetFillColor(243,243,243);//Definir el color de Relleno para las celdas cuyo valor de la propiedad 'fill' sea igual a 1
	$pdf->SetTextColor(128, 0, 0);//Color Rojo
	$pdf->Cell(25,5,$idRecorrido,"B",1,"R",0);
	$pdf->Ln();
	//RESPONSABLE Y FECHA
	$pdf->SetFont('Arial','',10);//Tipo de Letra
	$pdf->SetTextColor(51, 51, 153);//Color del Texto
	$pdf->Cell(25,5,"Responsable: ",0,0,"L",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->Cell(135,5,$responsable,"B",0,"L",0);
	$pdf->Cell(15,5,"Fecha: ",0,0,"R",0);
	$pdf->Cell(20,5,$fecha,"B",1,"R",0);
	$pdf->Ln();
	//OBSERVACIONES
	$pdf->Cell(28,5,"Observaciones: ",0,0,"L",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->MultiCell(167,5,$observaciones,"B","J",0);
	$pdf->Ln();
	
	//Encabezados de la Tabla de Detalle
	$pdf->Cell(195,5,"Registro de Anomalías Correspondientes al Recorrido",1,1,"C",1);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->SetFont('Arial','',8);//Tipo de Letra
	$pdf->Cell(10,5,"No.",1,0,"C",1);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->Cell(25,5,"Área",1,0,"C",1);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->Cell(30,5,"Lugar",1,0,"C",1);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->Cell(55,5,"Anomalía",1,0,"C",1);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->Cell(55,5,"Corrección Anomalía",1,0,"C",1);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->Cell(20,5,"Fecha",1,1,"C",1);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	
	//Connect to database 
	conecta('bd_seguridad');
	$sql="SELECT area,lugar,anomalia,correccion_anomalia,fecha FROM detalle_recorridos_seguridad WHERE recorridos_seguridad_id_recorrido='$idRecorrido' ORDER BY id_detalle_recorrido_seguridad";
	$rs=mysql_query($sql);
	if($datos=mysql_fetch_array($rs)){
		$cant=1;
		do{
			$area=cortarCadena($datos["area"],15);
			$lugar=cortarCadena($datos["lugar"],15);
			$anomalia=cortarCadena($datos["anomalia"],30);
			$correcion=cortarCadena($datos["correccion_anomalia"],30);
			$fecha=modFecha($datos["fecha"],1);
			$maxTam=max(count($area),count($lugar),count($anomalia),count($correcion));
			$cont=0;
			do{
				if($pdf->GetY()==254.00125)
					$borde="LRB";
				elseif($pdf->GetY()>254.00125)
					$borde="LRT";
				else
					$borde="LR";
					
				//Columna de No. Anomalia
				if($cont==0)
					$pdf->Cell(10,5,$cant,$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				else
					$pdf->Cell(10,5,"",$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				//Columna de Area
				if(isset($area[$cont]))
					$pdf->Cell(25,5,$area[$cont],$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				else
					$pdf->Cell(25,5,"",$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				//Columna de Lugar
				if(isset($lugar[$cont]))
					$pdf->Cell(30,5,$lugar[$cont],$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				else
					$pdf->Cell(30,5,"",$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				//Columna de Anomalia
				if(isset($anomalia[$cont]))
					$pdf->Cell(55,5,$anomalia[$cont],$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				else
					$pdf->Cell(55,5,"",$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				//Columna de Correcciones
				if(isset($correcion[$cont]))
					$pdf->Cell(55,5,$correcion[$cont],$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				else
					$pdf->Cell(55,5,"",$borde,0,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				//Columna de Fechas
				if($cont==0)
					$pdf->Cell(20,5,$fecha,$borde,1,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				else
					$pdf->Cell(20,5,"",$borde,1,"C",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
				//Incrementar el comntador de posiciones
				$cont++;
			}while($cont<$maxTam);
			$pdf->Cell(195,0,"","B",1,"C",0);
			$cant++;
		}while($datos=mysql_fetch_array($rs));
	}
	/**************************************************************************************/
	/**************************************************************************************/
	/**************************************************************************************/
	//Especificar Datos del Documento
	$pdf->SetAuthor("Departamento Seguridad");
	$pdf->SetTitle("Reporte Recorrido de Seguridad ".$idRecorrido);
	$pdf->SetCreator("SEGURIDAD INDUSTRIAL");
	$pdf->SetSubject("REPORTE RECORRIDO SEGURIDAD");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Recorrido de Seguridad ".$idRecorrido." en el SISAD");
	$idRecorrido.='.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($idRecorrido,"F");
	header('Location: '.$idRecorrido);
	//Borrar todos los PDF ya creados
	borrarArchivos();	
				
	/***********************************************************************************************/
	/**************************FUNCIONES USADAS EN LA REQUISICION***********************************/
	/***********************************************************************************************/
	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica
	function obtenerDato($nom_base,$nom_tabla, $campo_bus, $param_bus, $dato_bus){
		//Conectarse con la BD que corresponde
		$conn = conecta($nom_base);
		
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