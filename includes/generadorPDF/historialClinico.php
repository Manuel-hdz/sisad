<?php

//Incluir los Archivos Requeridos para las Conexiones a la BD y la modificacion de Fechas, asi como la Creacion del PDF
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");


//class PDF extends PDF_MySQL_Table{

//Declaraci�n de la Clase
class PDF extends FPDF{

	function Header(){

		//Colocar el Logo, Image(float x, float y, float ancho, float alto)
	    //$this->Image('logo-clf.jpg',8,8,53.9,23);
		//Colocar el Color de Texto en Azul Oscuro (RGB)
		$this->SetTextColor(0, 0, 0);		
	    $this->Cell(0,2,"",0,1,0,0);
	    //Colocar los datos del Titulo del Formato
		$this->SetFont('Arial','B',12);//Colocar el texto en Arial bold 15
		$this->Cell(50,5,"",0,0,0,0);
		$conn=conecta("bd_clinica");
		$nomEmpresa=obtenerDato("historial_clinico", "razon_social", "id_historial", $_GET['idHistorial']);
		mysql_close($conn);
	    $this->Cell(115,5,$nomEmpresa,0,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		$this->Cell(25,5,"",0,1,0,0);
		$this->Ln(1);
		$this->SetFont('Arial','B',12);//Colocar el texto en Arial bold 15
		$this->Cell(50,5,"",0,0,"",0);//Colocar una celda de 55 mm de ancho desde inicio de la p�gina
		$this->Cell(115,5,utf8_decode("HISTORIA CLÍNICA"),0,1,"C",0);
		$this->Cell(25,5,"",0,1,0,0);
		$this->Ln(1);
		//Colocar el Logo, Image(float x, float y, float ancho, float alto)
	    //$this->Image('ssmarc.jpg',178,8,27,23);
	    //Colocar un espacio de 10 mm de espacion entre el encabezado y el contenido del documento
	    $this->Ln(8);
		
		parent::Header();
	}//Cierre Header()

	//Page footer
	function Footer(){		
		//Posicionar el puntero a 12mm desde el final de la pagina
	    $this->SetY(-12);
		//Colocar el Color de Texto en Azul Oscuro (RGB)
		$this->SetTextColor(0, 0, 0);
		//Definir el Estilo del texto	    
	    $this->SetFont('Arial','B',6);
		 //Numero de Pagina
		$this->Cell(0,5,"Pagina ".$this->PageNo()." de {nb}",0,0,'R',0);
	}

}//Cierre de la clase PDF	


	//Connect to database 
	$conn = conecta('bd_clinica');
		//Obtener la clave del historial que se esta generando
		$idHistorial = $_GET['idHistorial'];
		$sql="SELECT * from historial_clinico WHERE id_historial = '$idHistorial'";
	
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	//Crear el PDF y dar las propiedades que tendr� el docuemntos
	$pdf = new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetDrawColor(0, 0, 0);//Definir Color de las lineas, rectangulos y bordes de celdas en color Azul
	$pdf->SetAutoPageBreak(true,10);//Indicar que cuando una celda exceda el margen inferior de 1cm(10mm), esta se dibuje en la siguiente pagina
	
	//Tipo, Estilo y tama�o para todo el documento
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8
	//RENGLONES DE 195 MM
	//Renglon 1
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Tipo Examen",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["clasificacion_exa"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(25,4,"Puesto a Realizar",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(45,4,$datos["puesto_realizar"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(15,4,utf8_decode("Afiliación"),0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(25,4,$datos["num_afiliacion"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(17,4,"Fecha Ex.",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(18,4,modFecha($datos["fecha_exp"],1),"B",1,"C",0);
	
	//Renglon 2
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Nombre",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(100,4,$datos["nom_empleado"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Sexo",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(20,4,$datos["sexo"],"B",0,"C",0);

	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(17,4,"Edad",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(18,4,$datos["edad"]." AÑOS","B",1,"R",0);
	
	//Renglon 3
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Reside en",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(95,4,$datos["reside_en"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(18,4,"Originario de",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(27,4,$datos["originario_de"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(17,4,"Edo. Civil",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	switch($datos["edo_civil"]){
		case "SOLTERO":
			$edoCivil="S";
		break;
		case "CASADO":
			$edoCivil="C";
		break;
		case "DIVORCIADO":
			$edoCivil="D";
		break;
		case "VIUDO":
			$edoCivil="V";
		break;
		case "UNIÓN LIBRE":
			$edoCivil="UL";
		break;
	}
	$pdf->Cell(18,4,$edoCivil,"B",1,"C",0);
	
	//Renglon 4
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Domicilio",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(100,4,$datos["domicilio"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,utf8_decode("Teléfono"),0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(20,4,$datos["telefono"],"B",0,"C",0);

	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(17,4,"Fecha Nac.",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(18,4,modFecha($datos["fecha_nac"],1),"B",1,"R",0);
	
	//Renglon 5
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Escolaridad",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(100,4,$datos["escolaridad"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Clave",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(20,4,$datos["clave_escolaridad"],"B",0,"C",0);
	$pdf->Cell(35,4,"","B",1,"R",0);
	
	$conn=conecta("bd_clinica");
	//Extraer los datos de la tabla de antecedentes familiares
	$sql="SELECT * from antecedentes_fam WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	//Renglon 6
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Preso(Kg.)",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["peso_kg"],"B",0,"C",0);

	$pdf->Cell(20,4,"",0,0,"C",0);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(25,4,"Historia Familiar",0,0,"R",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$histFam=cortarCadena($datos["historia_familiar"],60);
	$pdf->Cell(100,4,$histFam[0],"LRT",1,"C",0);
	
	//Renglon 7
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Talla(Mts)",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["talla_mts"],"B",0,"C",0);
	
	$pdf->Cell(45,4,"",0,0,"C",0);
	if(isset($histFam[1]))
		$pdf->Cell(100,4,$histFam[1],"LR",1,"C",0);
	else
		$pdf->Cell(100,4,"","LR",1,"C",0);
		
	//Renglon 8
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(20,4,utf8_decode("Tórax"),0,0,"R",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
		$pdf->Cell(30,4,"Diam A.P.",0,0,"R",0);
		$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
		$pdf->Cell(20,4,$datos["torax_diam_ap"],"B",0,"C",0);
	
	$pdf->Cell(25,4,"",0,0,"C",0);
	if(isset($histFam[2]))
		$pdf->Cell(100,4,$histFam[2],"LR",1,"C",0);
	else
		$pdf->Cell(100,4,"","LR",1,"C",0);
	
	//Renglon 9
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"",0,0,"R",0);
		$pdf->Cell(30,4,"Diam LAT.",0,0,"R",0);
		$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
		$pdf->Cell(20,4,$datos["torax_diam_lat"],"B",0,"C",0);
	
	$pdf->Cell(25,4,"",0,0,"C",0);
	if(isset($histFam[3]))
		$pdf->Cell(100,4,$histFam[3],"LR",1,"C",0);
	else
		$pdf->Cell(100,4,"","LR",1,"C",0);
		
	
	//Renglon 10
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"",0,0,"R",0);
		$pdf->Cell(30,4,"Circ EXP.",0,0,"R",0);
		$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
		$pdf->Cell(20,4,$datos["torax_circ_exp"],"B",0,"C",0);
	
	$pdf->Cell(25,4,"",0,0,"C",0);
	if(isset($histFam[4]))
		$pdf->Cell(100,4,$histFam[4],"LR",1,"C",0);
	else
		$pdf->Cell(100,4,"","LR",1,"C",0);
		
	//Renglon 11
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"",0,0,"R",0);
		$pdf->Cell(30,4,"Circ INSP.",0,0,"R",0);
		$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
		$pdf->Cell(20,4,$datos["torax_circ_insp"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(25,4,"Antecedentes",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(100,4,$datos["antecedentes"],1,1,"C",0);
	
	//Renglon 12
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Pulso",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["pulso"],"B",0,"C",0);

	$pdf->Cell(20,4,"",0,0,"C",0);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(25,4,"Historia Medica Ant.",0,0,"R",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$histMedAnt=cortarCadena($datos["historia_medica_ant"],60);
	$pdf->Cell(100,4,$histMedAnt[0],"LRT",1,"L",0);
	
	//Renglon 13
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Resp",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["respiracion"],"B",0,"C",0);
	$pdf->Cell(45,4,"",0,0,"R",0);
	
	if(isset($histMedAnt[1]))
		$pdf->Cell(100,4,$histMedAnt[1],"LR",1,"L",0);
	else
		$pdf->Cell(100,4,"","LR",1,"L",0);
	
	//Renglon 14
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Temp",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["temp"],"B",0,"C",0);
	$pdf->Cell(45,4,"",0,0,"R",0);
	
	if(isset($histMedAnt[2]))
		$pdf->Cell(100,4,$histMedAnt[2],"LR",1,"L",0);
	else
		$pdf->Cell(100,4,"","LR",1,"L",0);
	
	//Renglon 15
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Pres. Art.",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["pres_arterial"],"B",0,"C",0);
	$pdf->Cell(45,4,"",0,0,"R",0);
	
	if(isset($histMedAnt[3]))
		$pdf->Cell(100,4,$histMedAnt[3],"LR",1,"L",0);
	else
		$pdf->Cell(100,4,"","LR",1,"L",0);
	
	//Renglon 16
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"IMC",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["imc"],"B",0,"C",0);
	$pdf->Cell(45,4,"",0,0,"R",0);
	
	if(isset($histMedAnt[4]))
		$pdf->Cell(100,4,$histMedAnt[4],"LR",1,"L",0);
	else
		$pdf->Cell(100,4,"","LR",1,"L",0);
	
	//Renglon 17
	/**/
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"%SpO2",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["spo2"],"B",0,"C",0);//<-----------------------------------------------------CAMBIARLE POR EL SPO2
	$pdf->Cell(10,4,"",0,0,"R",0);
	/**/
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(35,4,"Antecedentes P.P.",0,0,"R",0);
	$pdf->SetTextColor(255, 0, 0);//Color del Texto de la "respuesta"
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	//$pdf->Cell(100,4,$datos["antecedentes_pp"],1,1,"L",0);
	$antPP=cortarCadena($datos["antecedentes_pp"],60);
	$pdf->Cell(100,4,$antPP[0],"LRT",1,"L",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL

	//Renglon 17.1
	$pdf->Cell(95,4,"",0,0,"R",0);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	if(isset($antPP[1]))
		$pdf->Cell(100,4,$antPP[1],"LR",1,"L",0);
	else
		$pdf->Cell(100,4,"","LR",1,"L",0);

	//Renglon 18
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(95,4,"Enf. Prof. y/o Secuelas",0,0,"R",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"	
	$pdf->Cell(100,4,$datos["enf_prof_secuelas"],1,1,"L",0);
	
	//Renglon 19
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(195,4,utf8_decode("ANT. NO PATOLÓGICOS"),0,1,"L",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	
	//Renglon 20
	//Extraer los datos de la tabla de antecedentes NO patologicos
	$sql="SELECT * from ant_no_patologicos WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Actividad",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["actividad"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(15,4,"Etilismo",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["etilismo"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Tabaquismo",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["tabaquismo"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Otras Adicc.",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(30,4,$datos["otras_adicc"],"B",1,"C",0);
	$pdf->Ln(1);
	
	//Renglon 21
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(195,4,"HISTORIA DEL TRABAJO",0,1,"C",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	//Renglon 22
	//Extraer los datos de la tabla de antecedentes NO patologicos
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(50,4,"Lugar",0,0,"C",0);
	$pdf->Cell(50,4,"Tipo de Trabajo",0,0,"C",0);
	$pdf->Cell(40,4,"Tiempo",0,0,"C",0);
	$pdf->Cell(55,4,"Condiciones especiales",0,1,"C",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	///AQUI
	//$condEsp=cortarCadena($datosHistTrab["cond_especiales"],50);
	//$pdf->Cell(50,4,$condEsp[0],"LRT",1,"L",0);
	
	//Extraer los datos de la tabla de antecedentes NO patologicos
	$sql="SELECT * from historial_trabajo WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	if($datosHistTrab=mysql_fetch_array($rs)){
		do{
			$pdf->Cell(50,4,$datosHistTrab["lugar"],1,0,"C",0);
			$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
			$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta	
			
			$pdf->Cell(50,4,$datosHistTrab["tipo_trabajo"],1,0,"C",0);
			$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
			$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta	
			
			$pdf->Cell(40,4,$datosHistTrab["tiempo"],1,0,"C",0);
			$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
			$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta	
			
			//$pdf->Cell(50,4,$datosHistTrab["cond_especiales"],1,1,"L",0);
			$condEsp=cortarCadena($datosHistTrab["cond_especiales"],30);
			$pdf->SetFont('Arial','',6);//Colocar el texto en arial tama�o 8 NORMAL
			$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
				
			//$pdf->Cell(50,4,$condEsp[0],"LRT",1,"L",0);
				$ctrl = 0;
				//do{					
					if($ctrl>0)
						$pdf->Cell(55,4,"",0,0,"L",0);
					if(isset($condEsp[$ctrl]))
						$pdf->Cell(55,4,$condEsp[$ctrl],1,1,"L",0);

					else
						$pdf->Cell(55,4,"","LR",1,"L",0);
					//$ctrl++;		
				//}while($ctrl<count($condEsp));				
		}while($datosHistTrab=mysql_fetch_array($rs));
	}
			
	$pdf->Ln(1);

	//Renglon 22 + la cantidad de trabajos anteriores
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(195,4,"ASPECTO GENERAL",0,1,"L",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	//Siguiente Renglon
	$sql="SELECT * from aspectos_grales_1 WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Tipo",0,0,"C",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(45,4,$datos["tipo_gral"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,utf8_decode("Nutrición"),0,0,"C",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(45,4,$datos["nutricion"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Piel",0,0,"C",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(45,4,$datos["piel"],"B",1,"C",0);
	
	//Siguiente Renglon
	//Extraer los datos de la tabla de antecedentes NO patologicos
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(50,4,"",0,0,"L",0);
	$pdf->Cell(20,4,"Der.",0,0,"C",0);
	$pdf->Cell(20,4,"",0,0,"L",0);
	$pdf->Cell(20,4,"Izq.",0,0,"C",0);
	$pdf->Cell(20,4,"",0,0,"L",0);
	$pdf->Cell(20,4,"Der.",0,0,"C",0);
	$pdf->Cell(20,4,"",0,0,"L",0);
	$pdf->Cell(20,4,"Izq.",0,0,"C",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(10,4,"",0,1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(15,4,"",0,0,"L",0);
	$pdf->Cell(15,4,"OJOS",0,0,"R",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,utf8_decode("Visión"),0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["ojo_der_vision"],"B",0,"C",0);
	$pdf->Cell(20,4,"",0,0,"C",0);
	$pdf->Cell(20,4,$datos["ojo_izq_vision"],"B",0,"C",0);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Reflejos",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["ojo_der_reflejos"],"B",0,"C",0);
	$pdf->Cell(20,4,"",0,0,"C",0);
	$pdf->Cell(20,4,$datos["ojo_izq_reflejos"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Lentes",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(10,4,$datos["lentes"],"B",0,"C",0);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Pterygiones",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["ojo_der_pterygiones"],"B",0,"C",0);
	$pdf->Cell(20,4,"",0,0,"C",0);
	$pdf->Cell(20,4,$datos["ojo_izq_pterygiones"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Otros",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["ojo_der_otros"],"B",0,"C",0);
	$pdf->Cell(20,4,"",0,0,"C",0);
	$pdf->Cell(20,4,$datos["ojo_izq_otros"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(15,4,"",0,0,"L",0);
	$pdf->Cell(15,4,"OIDOS",0,0,"R",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,utf8_decode("Audición"),0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["oido_der_audicion"],"B",0,"C",0);
	$pdf->Cell(20,4,"",0,0,"C",0);
	$pdf->Cell(20,4,$datos["oido_izq_audicion"],"B",0,"C",0);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Canal",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["oido_der_canal"],"B",0,"C",0);
	$pdf->Cell(20,4,"",0,0,"C",0);
	$pdf->Cell(20,4,$datos["oido_izq_canal"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->Cell(30,4,"",0,0,"L",0);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Membrana",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["membrana_der"],"B",0,"C",0);
	$pdf->Cell(20,4,"",0,0,"C",0);
	$pdf->Cell(20,4,$datos["membrana_izq"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->Cell(30,4,"",0,0,"L",0);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"HBC %",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["porciento_hbc"],"B",0,"C",0);
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Tipo",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["tipo"],"B",0,"C",0);
	

	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"% IPP",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["porciento_ipp"]." %","B",1,"C",0);
	

	//Siguiente Renglon
	$sql="SELECT * from aspectos_grales_2 WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Nariz",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(132,4,$datos["nariz"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(18,4,utf8_decode("Obstrucción"),0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(22,4,$datos["obstruccion"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Boca y Garganta",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(100,4,$datos["boca_garganta"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(12,4,"Encias",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(24,4,$datos["encias"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(12,4,"Dientes",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(24,4,$datos["dientes"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Cuello",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(100,4,$datos["cuello"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(15,4,"Linfaticos",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(57,4,$datos["linfaticos"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,utf8_decode("Tórax"),0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(172,4,$datos["torax"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,utf8_decode("Corazón"),0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(172,4,$datos["corazon"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Pulmones",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(172,4,$datos["pulmones"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Abdomen",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(74,4,$datos["abdomen"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(14,4,"Higado",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(33,4,$datos["higado"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(16,4,"Bazo",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(35,4,$datos["bazo"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Pared Abdominal",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(74,4,$datos["pared_abdominal"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(14,4,"Anillos",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(33,4,$datos["anillo"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(16,4,"Hernias",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(35,4,$datos["hernias"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Gen. Urin.",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(74,4,$datos["gen_uri"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(14,4,"Hidrocele",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(33,4,$datos["hidrocele"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(16,4,"Varicocele",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(35,4,$datos["varicocele"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Hemorroides",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(172,4,$datos["hemorroides"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Extr. Suprs.",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(172,4,$datos["extr_suprs"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Extr. Infrs.",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(172,4,$datos["extr_infrs"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Reflejos O.T.",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(83,4,$datos["reflejos_ot"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(15,4,"Psiquismo",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(74,4,$datos["psiquismo"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(23,4,"Sintomat. Actual",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(172,4,$datos["sintoma_actual"],"B",1,"L",0);
	
	$pdf->AddPage();
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(100,4,"PRUEBA DE ESFUERZO",0,0,"L",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->SetFont('Arial','B',8);//Colocar el texto en arial tama�o 8 NEGRITAS
	$pdf->Cell(95,4,"LABORATORIO",0,1,"C",0);
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tama�o 8 NORMAL
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	$conn=conecta("bd_clinica");
	echo $sql="SELECT * FROM prueba_esfuerzo WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,"En reposo",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Pulso",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["pulso_reposo"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,utf8_decode("Respiración"),0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["resp_reposo"],"B",0,"C",0);
	
	$conn=conecta("bd_clinica");
	echo $sql="SELECT * FROM  laboratorio WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(25,4,"VDRL",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["vdrl"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"B.H.",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["bh"],"B",1,"C",0);
	
	$conn=conecta("bd_clinica");
	echo $sql="SELECT * FROM prueba_esfuerzo WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,"Inm. Desp. de Esfzo.",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["pulso_inm_desp_esfzo"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["resp_inm_desp_esfzo"],"B",0,"C",0);
	
	
	$conn=conecta("bd_clinica");
	echo $sql="SELECT * FROM  laboratorio WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);

	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(25,4,"Glicemia",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["glicemia"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"PIE",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["pie"],"B",1,"C",0);
	
	$conn=conecta("bd_clinica");
	echo $sql="SELECT * FROM prueba_esfuerzo WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,utf8_decode("1 min. después"),0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["pulso_un_min_desp"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["resp_un_min_desp"],"B",0,"C",0);
	
	$conn=conecta("bd_clinica");
	echo $sql="SELECT * FROM  laboratorio WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(25,4,"Gral. orina",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["gral_orina"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Pb en Sang",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["pb_sang"],"B",1,"C",0);
	
	
	$conn=conecta("bd_clinica");
	echo $sql="SELECT * FROM prueba_esfuerzo WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,utf8_decode("2 min. después"),0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["pulso_dos_min_desp"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["resp_dos_min_desp"],"B",0,"C",0);
	
	
	$conn=conecta("bd_clinica");
	echo $sql="SELECT * FROM  laboratorio WHERE historial_clinico_id_historial = '$idHistorial'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(25,4,"HIV",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["hiv"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Cadmio",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["cadmio"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(135,4,"Fosfata Acida",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["fosfata_acida"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"TG",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["tg"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(135,4,"Fosfata alcalina",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["fosfata_alcalina"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Colesterol",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["colesterol"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,"Espirometria",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(40,4,$datos["espirometria"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(65,4,"Tipo Sanguineo",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["tipo_sanguineo"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"B Mglobulin",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["b_mglobulin"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,"FCR",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(40,4,$datos["fcr"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(65,4,"Diag. Laboratorio",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(60,4,$datos["diag_laboratorio"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,utf8_decode("Rx de Tórax"),0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(40,4,$datos["rx_torax"],"B",0,"L",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(65,4,"Alcoholimetro",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(60,4,$datos["alcoholimetro"],"B",1,"L",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,"% Silicosis",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["porcentaje_silicosis"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Fracc.",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["fracc"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,"Col. Lumbosacra",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	//$pdf->Cell(165,4,$datos["col_lumbrosaca"],"B",1,"C",0);
	$col=cortarCadena($datos["col_lumbrosaca"],80);
	$ctrl = 0;
	do{
		if($ctrl>0)
			$pdf->Cell(30,4,"",0,0,"L",0);
		if(isset($col[$ctrl]))
			$pdf->Cell(165,4,$col[$ctrl],"B",1,"L",0);
		else
			$pdf->Cell(165,4,"","B",1,"C",0);
		$ctrl++;		
	}while($ctrl<count($col));
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,"Romberg",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["romberg"],"B",0,"C",0);
	
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(20,4,"Babinsky Weil",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(20,4,$datos["babinsky_weil"],"B",1,"C",0);
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,utf8_decode("Diagnósticos"),0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	//$pdf->Cell(165,4,$datos["diagnostico"],"B",1,"L",0);
	$diag=cortarCadena($datos["diagnostico"],80);
	$ctrl = 0;
	do{
		if($ctrl>0)
			$pdf->Cell(30,4,"",0,0,"L",0);
		if(isset($diag[$ctrl]))
			$pdf->Cell(165,4,$diag[$ctrl],"B",1,"L",0);
		else
			$pdf->Cell(165,4,"","B",1,"C",0);
		$ctrl++;		
	}while($ctrl<count($diag));
	
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,"Conclusiones",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	//$pdf->Cell(165,4,$datos["diagnostico"],"B",1,"L",0);
	$conc=cortarCadena($datos["conclusiones"],80);
	$ctrl = 0;
	do{
		if($ctrl>0)
			$pdf->Cell(30,4,"",0,0,"L",0);
		if(isset($conc[$ctrl]))
			$pdf->Cell(165,4,$conc[$ctrl],"B",1,"L",0);
		else
			$pdf->Cell(165,4,"","B",1,"C",0);
		$ctrl++;		
	}while($ctrl<count($conc));
	
	
	
	//Siguiente Renglon
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Cell(30,4,"Edo. Salud",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(40,4,$datos["edo_salud"],"B",1,"L",0);
	
	//Siguiente Renglon
	$conn=conecta("bd_clinica");
	$dr=obtenerDato("historial_clinico","nom_dr","id_historial",$idHistorial);
	$dr = "DRA. GWENDOLYNNN RUCOBO RODRIGUEZ";
	if($dr == "DRA. GWENDOLYNNN RUCOBO RODRIGUEZ")
		$ced="CED. 10052826";
	else if ($dr == "DRA. GWENDOLYNNN RUCOBO RODRIGUEZ")
		$ced="CED. 10052826";
	$pdf->SetTextColor(128, 0, 0);//Color del Texto del documento
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(140,4,"",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(1,4,$dr,"B",1,"L",0);
	$pdf->Cell(140,4,"",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta
	$pdf->Cell(1,4,$ced,"B",1,"L",0);
	/***************************************************** PROPIEDADES DEL DOCUMENTO PDF *****************************************************************/
	$pdf->SetAuthor("UNIDAD DE SALUD OCUPACIONAL");
	$pdf->SetTitle("HISTORIAL CLÍNICO ".$idHistorial);
	$pdf->SetCreator("UNIDAD DE SALUD OCUPACIONAL");
	$pdf->SetSubject("Historial Clínico");
	$pdf->SetKeywords("CLF. \nDocumento Generado a Partir del Historial Clínico ".$idHistorial." en el SISAD");
	$idHistorial.='.pdf';

	//Mandar imprimir el PDF en el mismo directorio donde se encuentra este archivo de 'ordenServicioExternbo.php'
	$pdf->Output($idHistorial,"F");
	//Direccionar al PDF recien creado en el directorio
	header('Location: '.$idHistorial);
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