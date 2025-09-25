<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");

class PDF extends FPDF{ 

	function Header(){
	    //Line break
	    $this->Ln(5);
		parent::Header();
	}
	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
		$this->SetY(-20);
	    //Arial italic 8
	    $this->SetFont('Arial','B',7);
		$this->Cell(0,25,'May/09 F.7.4.1 - 01',0,0,'R');
	}
}	
	//Recuperar el RFC del Proveedor
	$rfc=$_GET["rfc"];
	//Connect to database 
	conecta('bd_compras');
	//Sentencia para obtener el Detalle de la Requisicion
	$sql_stm = "SELECT razon_social,calle,numero_ext,numero_int,colonia,cp,ciudad,estado,telefono,telefono2,fax,correo,correo2,contacto,mat_servicio,observaciones,id_prov FROM proveedores WHERE rfc = '".$rfc."'";
	$rs = mysql_query($sql_stm);
	$datos=mysql_fetch_array($rs);

	//Crear el Objeto PDF y dar las Caracteristicas Iniciales
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	//Logo
	$pdf->Image('logo-clf.jpg',10,14,50);
	//Cuadro vacio para colocar la imagen
	$pdf->Cell(51,20,"",1,0,"R");
	//Definir el Tipo de Letra
	$pdf->SetFont('Arial','B',12);//PONER LA LETRA EN NEGRITA
	$pdf->Cell(100,20,"EXPEDIENTE DE PROVEEDOR",1,0,"C");
	$pdf->Cell(18,20," FOLIO:","TB",0,"L");
	$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
	$pdf->SetTextColor(128, 0, 0);
	$pdf->Cell(27,20,$datos["id_prov"],"TBR",1,"L");
	$pdf->SetTextColor(0, 0, 0);
	//NOMBRE Y RFC DEL PROVEEDOR
	$pdf->SetFont('Arial','',12);//Volver el tipo de Letra a NORMAL
	$pdf->Cell(126,5,"NOMBRE O RAZÓN SOCIAL:","LR",0,"L");
	$pdf->Cell(70,5,"RFC:","R",1,"L");
	$pdf->Cell(126,5,"","LR",0,"L");
	$pdf->Cell(70,5,"","R",1,"L");
	$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
	if(strlen($datos["razon_social"])>60)
		$pdf->SetFont('Arial','B',8);//Tipo de Letra para escribir datos de la consulta
	$pdf->Cell(126,5,$datos["razon_social"],"LR",0,"C");
	$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
	$pdf->Cell(70,5,$rfc,"R",1,"C");
	$pdf->Cell(126,5,"","LRB",0,"L");
	$pdf->Cell(70,5,"","RB",1,"L");
	//DIRECCION DEL PROVEEDOR
	$pdf->SetFont('Arial','',12);//Tipo de Letra para escribir títulos
	$pdf->Cell(196,5,"DIRECCIÓN","LR",1,"L");//
	$pdf->Cell(196,5,"","LR",1,0);
	$numInt="";
	if($datos["numero_int"]!="")
		$numInt=$datos["numero_int"]." ";
	$direccion=$datos["calle"]." ".$datos["numero_ext"]." ".$numInt." ".$datos["colonia"]." ".$datos["cp"]." ".$datos["ciudad"]." ".$datos["estado"];
	if(strlen($direccion)<=80)
		$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
	else
		$pdf->SetFont('Arial','B',8);//Tipo de Letra para escribir datos de la consulta
	$pdf->Cell(196,5,$direccion,"LR",1,"C");
	$pdf->Cell(196,5,"","LRB",1,0);
	//DATOS DE CONTACTO
	$pdf->SetFont('Arial','',12);//Tipo de Letra para escribir títulos
	$pdf->Cell(65,5,"TELÉFONO","LR",0,"L");
	$pdf->Cell(65,5,"FAX","LR",0,"L");
	$pdf->Cell(66,5,"CORREO","LR",1,"L");
	$pdf->Cell(65,5,"","LR",0,"C");
	$pdf->Cell(65,5,"","LR",0,"C");
	$pdf->Cell(66,5,"","LR",1,"C");
	$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
	$pdf->Cell(65,5,$datos["telefono"],"LR",0,"C");
	$pdf->Cell(65,5,$datos["fax"],"LR",0,"C");
	if(strlen($datos["correo"])>30)
		$pdf->SetFont('Arial','B',8);//Tipo de Letra para escribir datos de la consulta
	$pdf->Cell(66,5,$datos["correo"],"LR",1,"C");
	if($datos["telefono2"]!="" || $datos["correo2"]){
		$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
		$pdf->Cell(65,5,$datos["telefono2"],"LR",0,"C");
		$pdf->Cell(65,5,"","LR",0,"C");
		if(strlen($datos["correo"])>30)
			$pdf->SetFont('Arial','B',8);//Tipo de Letra para escribir datos de la consulta
		$pdf->Cell(66,5,$datos["correo2"],"LR",1,"C");
	}
	$pdf->Cell(65,5,"","LRB",0,"C");
	$pdf->Cell(65,5,"","LRB",0,"C");
	$pdf->Cell(66,5,"","LRB",1,"C");
	//NOMBRE DE CONTACTO DEL PROVEEDOR
	$pdf->SetFont('Arial','',12);//Tipo de Letra para escribir títulos
	$pdf->Cell(196,5,"NOMBRE DEL CONTACTO DEL PROVEEDOR","LR",1,"L");//
	$pdf->Cell(196,5,"","LR",1,0);
	$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
	$pdf->Cell(196,5,$datos["contacto"],"LR",1,"C");
	$pdf->Cell(196,5,"","LRB",1,0);
	//MATERIALES Y/O SERVICIOS
	$pdf->SetFont('Arial','',12);//Tipo de Letra para escribir títulos
	$pdf->Cell(196,5,"MATERIALES Y/O SERVICIOS QUE SUMINISTRA","LR",1,"L");
	$pdf->Cell(196,5,"","LR",1,0);
	$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
	$pdf->MultiCell(196,5,$datos["mat_servicio"],"LR");
	$posY=$pdf->GetY();
	if($posY==130.00125)
		//Variable para controlar la cantidad de renglones vacios
		$cont=0;
	else
		//Variable para controlar la cantidad de renglones vacios
		$cont=1;
	do{
		$pdf->Cell(196,5,"","LR",1,0);
		$cont++;
	}while($cont<15);
	$pdf->Cell(196,5,"","LRB",1,0);
	//DOCUMENTACION DEL PROVEEDOR
	//Sentencia para obtener la documentación entregada por los Proveedores
	$sql_stm = "SELECT nombre_docto FROM expediente_proveedor WHERE proveedores_rfc = '".$rfc."'";
	$rs = mysql_query($sql_stm);
	$doctos="";
	$cantRes=0;
	if($datos2=mysql_fetch_array($rs)){
		do{
			if($cantRes==0)
				$doctos=$datos2["nombre_docto"];
			else
				$doctos=", ".$datos2["nombre_docto"];
			$cantRes++;
		}while($datos2=mysql_fetch_array($rs));
	}
	$pdf->SetFont('Arial','',12);//Tipo de Letra para escribir títulos
	$pdf->Cell(196,5,"DOCUMENTACIÓN DEL PROVEEDOR","LR",1,"L");//
	$pdf->Cell(196,5,"","LR",1,0);
	$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
	$pdf->Cell(196,5,$doctos,"LR",1,"C");
	$pdf->Cell(196,5,"","LRB",1,0);
	//OBSERVACIONES
	$pdf->SetFont('Arial','',12);//Tipo de Letra para escribir títulos
	$pdf->Cell(196,5,"OBSERVACIONES","LR",1,"L");//
	$pdf->Cell(196,5,"","LR",1,0);
	if(strlen($datos["observaciones"])<80)
		$pdf->SetFont('Arial','B',10);//Tipo de Letra para escribir datos de la consulta
	else
		$pdf->SetFont('Arial','B',8);//Tipo de Letra para escribir datos de la consulta
	$pdf->Cell(196,5,$datos["observaciones"],"LR",1,"L");
	$pdf->Cell(196,5,"","LRB",1,0);
	//**************************************************************//
	//*********************Fin de las tablas************************//
	//**************************************************************//
	
	//****************************************
	//Especificar Datos del Documento
	$pdf->SetAuthor("Compras");
	$pdf->SetTitle("Expediente de Proveedor");
	$pdf->SetCreator("SISAD - Compras");
	$pdf->SetSubject("Carátula de Proveedor");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Registro del Proveedor ".$rfc." - ".$datos["razon_social"]." en el SISAD");
	$rfc.='.pdf';

	//Mandar imprimir el PDF
	$pdf->Output($rfc,"F");
	header('Location: '.$rfc);
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