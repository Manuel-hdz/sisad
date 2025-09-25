<?php
require('mem_image.php');
require("../conexion.inc");
include("../func_fechas.php");
include("../op_operacionesBD.php");
include("../verCodBarrasEmpleado.php");

class PDF extends PDF_MemImage{

	function Header(){
		//Logo
	    $this->Image('logo-clf.jpg',10,6,30);
	    //Arial bold 15
	    $this->SetFont('Arial','B',7.5);
		$this->SetTextColor(0, 0, 255);
	    //Move to the right
	    $this->Cell(60);
	    //Title
	    $this->Cell(70,25,'CONFIDENCIAL, PROPIEDAD DE �CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.� PROHIBIDA SU REPRODUCCI�N TOTAL O PARCIAL.',0,0,'C');
		$this->SetTextColor(78, 97, 50);
		$this->SetFont('Arial','B',20);
		$this->Cell(-70,15,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);
		$this->SetFont('Arial','B',10);
		$this->Cell(70,50,'CODIGO DE BARRAS DE PERSONAL',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
	    //Line break
	    $this->Ln(28);
		parent::Header();
	}

	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
	   $this->SetY(-17);
	    //Arial italic 8
	    $this->SetFont('Arial','',7);
		//Numero de Pagina
		$this->Cell(176,15,'        '.'                                                                                                   '.'',0,0,'L');
		$this->Cell(20,15,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
		$this->SetY(-27);
		$this->Cell(0,25,'',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->SetY(-16);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}

}//Cierre de la clase PDF	

	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();

	$opcion = $_GET["opcion"];
	$stm_sql = "";

	switch ($opcion) {
		case 1:
			$stm_sql = "SELECT * FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_GET[nombre]' AND estado_actual = 'ALTA' ORDER BY id_empleados_empresa";
		break;
		case 2:
			$stm_sql = "SELECT * FROM empleados WHERE estado_actual = 'ALTA' ORDER BY id_empleados_empresa";
		break;
		case 3:
			$stm_sql = "SELECT * FROM empleados WHERE area='$_GET[area]' AND estado_actual = 'ALTA' ORDER BY id_empleados_empresa";
		break;
		case 4:
			$stm_sql = "SELECT * FROM empleados WHERE CONCAT(nombre,' ',ape_pat,' ',ape_mat)='$_GET[nombre]' AND estado_actual = 'BAJA' ORDER BY id_empleados_empresa";
		break;
		case 5:
			$stm_sql = "SELECT * FROM empleados WHERE estado_actual = 'BAJA' ORDER BY id_empleados_empresa";
		break;
	}

	$conn = conecta("bd_recursos");
	$res = mysql_query($stm_sql);
	$num_reg = mysql_num_rows($res);
	if ($datos = mysql_fetch_array($res)) {
		$reg = 1;
		$cont = 1;
		$col = 1;
		do{
			$id_empl = $datos["id_empleados_empresa"];
			
			$cant_num = strlen($id_empl);

			if($cant_num == 1)
				$id_empl = "0000".$id_empl;
			else if($cant_num == 2)
				$id_empl = "000".$id_empl;
			else if($cant_num == 3)
				$id_empl = "00".$id_empl;
			else if($cant_num == 4)
				$id_empl = "0".$id_empl;
			
			if($cont==1){
				if($col == 1){
					$pdf->Code39(22*$col,45,$id_empl,1,8);
				} else {
					$separacion = 22+(43*($col-1));
					$pdf->Code39($separacion,(15*$cont)+30,$id_empl,1,8);
				}
			} else {
				if($col == 1){
					$pdf->Code39(22*$col,(15*$cont)+30,$id_empl,1,8);
				} else {
					$separacion = 22+(43*($col-1));
					$pdf->Code39($separacion,(15*$cont)+30,$id_empl,1,8);
				}
			}
			
			$col++;

			if($col == 5 && $reg%15 != 0){
				$col = 1;
				$cont++;
				$reg++;
			}
			if($reg%15 == 0 && $reg != $num_reg){
				$pdf->AddPage();
				$col = 1;
				$cont = 1;
				$reg++;
			}
		} while ($datos = mysql_fetch_array($res));
	}

	$pdf->SetAuthor("RECURSOS HUMANOS");
	$pdf->SetTitle("CODIGO DE BARRAS PERSONAL CLF");
	$pdf->SetCreator("RECURSOS HUMANOS");
	$pdf->SetSubject("REGISTRO DE POERSONAL");
	$pdf->SetKeywords("CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.\n CODIGO DE BARRAS PERSONAL CLF");
	$num_req ='codigo_barras.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($num_req,"F");
	header('Location: '.$num_req);
	//Borrar todos los PDF ya creados
	borrarArchivos();

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
?>
	