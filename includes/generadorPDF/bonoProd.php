<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");

class PDF extends FPDF{

	function Header(){
		/*//Logo
	    $this->Image('logo-clf.jpg',12,6,30);
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
		//$this->Cell(70,50,'F. 7.4.0 - 01 REQUISICIÓN DE COMPRAS',0,0,'C');
		$this->Cell(70,50,'CONTROL DIARIO DE ASISTENCIA',0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
		$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
		//Line break
	    $this->Ln(45);
		parent::Header();*/
	}

	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
	    /*$this->SetY(-20);
	    //Arial italic 8
	    $this->SetFont('Arial','',7);
		//SUB TI
		//$this->Cell(0,15,'       Fecha Emisión:                                               No. de Revisión:                                               Fecha de Revisión:',0,0,'L');
		$this->Cell(0,15,'',0,0,'L');
	    //Numero de Pagina
		$this->Cell(-20,15,'Página '.$this->PageNo().' de {nb}',0,0,'C');
		$this->SetY(-17);
		//$this->Cell(0,15,'            Abr - 09'.'                                                                '.'02'.'                                                                 '.'   May - 10',0,0,'L');
		$this->SetY(-20);
		//$this->Cell(0,25,'F. 4.2.1 - 01 / Rev. 01',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');*/
	}
}//Cierre de la clase PDF	

	//Crear el Objeto PDF y Agregar las Caracteristicas Iniciales
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	//$pdf->AddPage();
	$pdf->SetDrawColor(0, 0, 0);
	
	//Recuperar las variables que vienen como parametros en el GET
	$id_bono=$_GET["id"];
	
	$sql = "SELECT T1. * , T2. * , CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_empl, T3.curp, T3.no_ss, T3.fecha_ingreso
			FROM bono_prod AS T1
			JOIN detalle_bono_prod AS T2
			USING ( id_bono ) 
			JOIN bd_recursos.empleados AS T3
			USING ( rfc_empleado ) 
			WHERE id_bono =  '$id_bono'";
	
	//Conectarse a la BD de Recursos
	$conn=conecta("bd_recursos");
	$rs = mysql_query($sql);
	if($rs){
		while($datos=mysql_fetch_array($rs)){
			if($datos['bono'] > 0){
				$pdf->AddPage();
				for($num=0; $num<2; $num++){
					$impreso = $num;
					if($impreso == 0){
						$impreso = " ORIGINAL";
					} else{
						$impreso = "   COPIA";
					}
					//Poner la marca de agua
					$pdf->SetFont('Arial','B',40);
					//Poner un Espacio entre cada letra del Estado
					$cad=chunk_split($impreso,1);
					//Cambia el color del texto a rojo oscuro
					$pdf->SetTextColor(217,150,148);
					
					if($num == 0){
						$pdf->Text(33,97,$cad);
						//Colocar el Logo, Image(float x, float y, float ancho, float alto)
						$pdf->Image('logo-clf.jpg',12,12,53.9,23);
					} else{
						$pdf->Text(33,224,$cad);
						$pdf->Ln(15);
						//Colocar el Logo, Image(float x, float y, float ancho, float alto)
						$pdf->Image('logo-clf.jpg',12,140,53.9,23);
					}
					//Colocar el Color de Texto (RGB)
					$pdf->SetTextColor(0, 0, 0);		
					$pdf->Cell(0,3,"","LRT",1,0,0);
					//Colocar los datos del Titulo del Formato
					$pdf->SetFont('Arial','B',10);//Colocar el texto en Arial bold 15
					$pdf->Cell(0,5,"CONCRETO LANZADO DE FRESNILLO, S.A. DE C.V.","LR",0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
					$pdf->Ln();
					
					$pdf->SetFont('Arial','',8);//Colocar el texto en Arial bold 15
					$pdf->Cell(60,5,"","L",0,"L",0);
					$pdf->Cell(60,5,"RFC: CLF020304CB8",0,0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
					$pdf->Cell(0,5,"","R",1,0,0);
					
					$pdf->Cell(60,5,"","L",0,"L",0);
					$pdf->Cell(60,5,"IMSS: H0118661108",0,0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
					$pdf->SetFont('Arial','B',8);//Colocar el texto en Arial bold 15
					$pdf->Cell(33,5,"Semana",1,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
					$pdf->SetFont('Arial','',8);//Colocar el texto en Arial bold 15
					$pdf->Cell(38,5,"Del ".modFecha($datos['fecha_inicial'],1),1,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
					$pdf->Cell(0,5,"","R",1,0,0);
					
					$pdf->SetFont('Arial','B',9);//Colocar el texto en Arial bold 15
					$pdf->Cell(60,5,"","L",0,"L",0);
					$pdf->Cell(60,5,"Recibo de Nómina",0,0,"L",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
					$pdf->SetFont('Arial','',8);//Colocar el texto en Arial bold 15
					$pdf->Cell(33,5,$datos['semana'],1,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
					$pdf->Cell(38,5,"Al ".modFecha($datos['fecha_final'],1),1,0,"C",0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
					$pdf->Cell(0,5,"","R",1,0,0);
					$pdf->Cell(0,3,"","LR",1,0,0);
					
					$pdf->Cell(3,5,"","L",0,0,0);
					$pdf->SetFont('Arial','B',8);//Colocar el texto en Arial bold 15
					$pdf->Cell(114,5,"Empleado",1,0,"C",0);
					$pdf->Cell(3,5,"","L",0,0,0);
					$pdf->Cell(71,5,"Seguridad Social",1,0,"C",0);
					$pdf->Cell(0,5,"","R",1,0,0);
					
					$pdf->Cell(3,5,"","L",0,0,0);
					$pdf->SetFont('Arial','',8);//Colocar el texto en Arial bold 15
					$pdf->Cell(24,5,"Nombre:","L",0,"L",0);
					$pdf->Cell(90,5,$datos['nombre_empl'],"R",0,"L",0);
					$pdf->Cell(3,5,"",0,0,0,0);
					$pdf->Cell(33,5,"N.S.S","L",0,"L",0);
					$pdf->Cell(38,5,$datos['no_ss'],"R",0,"L",0);
					$pdf->Cell(0,5,"","R",1,0,0);
					
					$pdf->Cell(3,5,"","L",0,0,0);
					$pdf->Cell(24,5,"Puesto:","L",0,"L",0);
					$pdf->Cell(90,5,$datos['puesto'],"R",0,"L",0);
					$pdf->Cell(3,5,"",0,0,0,0);
					$pdf->Cell(33,5,"Fecha de Ingreso:","LB",0,"L",0);
					$pdf->Cell(38,5,modFecha($datos['fecha_ingreso'],2),"RB",0,"L",0);
					$pdf->Cell(0,5,"","R",1,0,0);
					
					$cc = obtenerDatosCentroCostos($datos['id_control_costos'],'control_costos','id_control_costos');
					$pdf->Cell(3,5,"","L",0,0,0);
					$pdf->Cell(24,5,"Depto:","L",0,"L",0);
					$pdf->Cell(90,5,$cc,"R",0,"L",0);
					$pdf->Cell(0,5,"","R",1,0,0);
					
					$pdf->Cell(3,5,"","L",0,0,0);
					$pdf->Cell(24,5,"RFC:","L",0,"L",0);
					$pdf->Cell(90,5,$datos['rfc_empleado'],"R",0,"L",0);
					$pdf->Cell(0,5,"","R",1,0,0);
					
					$pdf->Cell(3,5,"","L",0,0,0);
					$pdf->Cell(24,5,"CURP:","LB",0,"L",0);
					$pdf->Cell(90,5,$datos['curp'],"RB",0,"L",0);
					$pdf->Cell(0,5,"","R",1,0,0);
					$pdf->Cell(0,3,"","LR",1,0,0);
					
					$pdf->Cell(3,5,"","L",0,0,0);
					$pdf->SetFont('Arial','B',8);//Colocar el texto en Arial bold 15
					$pdf->Cell(60,5,"Percepción",1,0,"C",0);
					$pdf->Cell(4,5,"",0,0,"C",0);
					$pdf->Cell(50,5,"Monto",1,0,"C",0);
					$pdf->Cell(0,5,"","R",1,0,0);
					$pdf->Cell(0,5,"","LR",1,0,0);
					
					$pdf->Cell(3,5,"","L",0,0,0);
					$pdf->SetFont('Arial','',8);//Colocar el texto en Arial bold 15
					$pdf->Cell(60,5,"Bono por Producción",0,0,"L",0);
					$pdf->Cell(4,5,"",0,0,"C",0);
					$pdf->Cell(7,5,"$",0,0,"L",0);
					$pdf->Cell(43,5,number_format($datos['bono'],2,".",","),0,0,"R",0);
					$pdf->Cell(0,5,"","R",1,0,0);
					$pdf->Cell(0,15,"","LRB",1,0,0);
					
					$pdf->Cell(2,3,"","L",0,0,0);
					$pdf->SetFont('Arial','',5);//Colocar el texto en Arial bold 15
					$pdf->Cell(122,3,"Recibí de esta empresa la cantidad que señala este recibo de pago, estando conforme con las percepciones y las retenciones","R",0,"L",0);
					$pdf->Cell(0,3,"","R",1,0,0);
					
					$pdf->Cell(2,3,"","L",0,0,0);
					$pdf->Cell(122,3,"descritas, por lo que certifico que no se me adeuda cantidad alguna por ningun concepto. Así mismo, manifiesto no se me adeudan","R",0,"L",0);
					$pdf->Cell(0,3,"","R",1,0,0);
					
					$pdf->Cell(2,3,"","L",0,0,0);
					$pdf->Cell(122,3,"horas extras ya que han sido cubiertas en su totalidad","R",0,"L",0);
					$pdf->Cell(0,3,"","R",1,0,0);
					$pdf->Cell(124,5,"","LR",0,0,0);
					$pdf->Cell(0,5,"","R",1,0,0);
					
					$pdf->SetFont('Arial','',8);//Colocar el texto en Arial bold 15
					$pdf->Cell(124,5,"","LR",0,0,0);
					$pdf->Cell(33,5,"Total:",0,0,"C",0);
					$pdf->Cell(4,5,"$","T",0,"L",0);
					$pdf->Cell(26,5,number_format($datos['bono'],2,".",","),"T",0,"R",0);
					$pdf->Cell(0,5,"","R",1,0,0);
					
					$pdf->Cell(22,5,"","LB",0,0,0);
					$pdf->Cell(80,5,"Firma del Empleado","TB",0,"C",0);
					$pdf->Cell(22,5,"","RB",0,0,0);
					$pdf->Cell(0,5,"","RB",1,0,0);
				}
			}
		}
	}
	
	$pdf->SetAuthor("CLF Fresnillo");
	$pdf->SetTitle("Bonos Productividad");
	$pdf->SetCreator("RECURSOS HUMANOS");
	$pdf->SetSubject("Bonos Productividad");
	$pdf->SetKeywords("CLF. \nDocumento Generado a Partir del Bono de Productividad ".$id_bono." en el SISAD");
	$num_req='BonosProductividad.pdf';

	//Mandar imprimir el PDF
	$pdf->Output($num_req,"F");
	header('Location: '.$num_req);
	//Borrar todos los PDF ya creados
	borrarArchivos();
	/***********************************************************************************************/
	/**************************FUNCIONES USADAS EN LA REQUISICION***********************************/
	/***********************************************************************************************/	
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
	
	function obtenerDatosCentroCostos($valor,$tabla,$busq){
		$conn_rec=conecta("bd_recursos");
		$dato="N/A";
		$sql_stm_rec="SELECT descripcion FROM $tabla WHERE $busq='$valor'";
		$rs_rec=mysql_query($sql_stm_rec);
		$datos_rec=mysql_fetch_array($rs_rec);
		if ($datos_rec[0]!="")
			$dato=$datos_rec[0];
		return $dato;
		mysql_close($conn_rec);
	}
?>