<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
	
class PDF extends FPDF{
	function Header(){
		///////////////////////////////////////////////////
		///				ENCABEZADO DEL PDF 				///
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$this->Image('logo-clf.jpg',80,6,50);

		$this->SetFont('Arial','B',8);
		$this->Ln(18);
		$this->Cell(195,5,'FORMATO DC-3',0,1,'C');
		$this->Cell(195,5,'CONSTANCIA DE HABILIDADES LABORALES',0,1,'C');
		////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
}

	$conn = conecta("bd_recursos");
	///////////////////////////////////////////////////
	///				CREACION DE PAGINA 				///
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$id_cap = $_GET["cap"];
	if (isset($_GET["tipo"])) {
		$stm_sql = "SELECT T1. * , T3.curp, CONCAT( T3.ape_pat,  ' ', T3.ape_mat,  ' ', T3.nombre ) AS nombre_empl
					FROM capacitaciones AS T1
					JOIN empleados_reciben_capacitaciones AS T2 ON T1.id_capacitacion = T2.capacitaciones_id_capacitacion
					JOIN empleados AS T3 ON T2.empleados_rfc_empleado = T3.rfc_empleado
					WHERE T1.id_capacitacion LIKE  '$id_cap'";
	} else {
		$rfc_empl = $_GET["id"];
		$stm_sql = "SELECT T1. * , T3.curp, CONCAT( T3.ape_pat,  ' ', T3.ape_mat,  ' ', T3.nombre ) AS nombre_empl
					FROM capacitaciones AS T1
					JOIN empleados_reciben_capacitaciones AS T2 ON T1.id_capacitacion = T2.capacitaciones_id_capacitacion
					JOIN empleados AS T3 ON T2.empleados_rfc_empleado = T3.rfc_empleado
					WHERE T1.id_capacitacion LIKE  '$id_cap'
					AND T2.empleados_rfc_empleado LIKE  '$rfc_empl'";
	}

	$rs = mysql_query($stm_sql);
	if ($datos = mysql_fetch_array($rs)) {
		$nombre_curso = $datos["nom_capacitacion"];
		$horas = $datos["hrs_capacitacion"];
		$fecha_ini = $datos["fecha_inicio"];
		$fecha_fin = $datos["fecha_fin"];
		$insturctor = $datos["instructor"];
		$tema = $datos["tema"];
		do{
			$pdf->AddPage();
			///////////////////////////////////////////////////////
			///				DATOS DEL TRABAJADOR 				///
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$pdf->SetFont('Arial','B',8);
			$pdf->SetTextColor(255, 255, 255);
			$pdf->SetFillColor(0, 0, 0);
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,'DATOS DEL TRABJADOR',1,1,'C',true);

			$pdf->SetFont('Arial','',8);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,'Nombre (Anotar apellido paterno, apellido materno y nombre (s))','LR',1,'L');

			$pdf->SetFont('Arial','',10);
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,$datos["nombre_empl"],'LRB',1,'L');

			$pdf->SetFont('Arial','',8);
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(80,5,utf8_decode('Clave Única de Registro de Población'),'L',0,'L');
			$pdf->MultiCell(75,4,utf8_decode("Ocupación específica (Catálogo Nacional de Ocupaciones)"),'LR','L');

			$pdf->SetFont('Arial','',10);
			$pdf->SetY(58.4);
			$pdf->Cell(20,8,'',0,0,'C');
			$curp_empl = $datos["curp"];
			$curp_empl_arr = str_split($curp_empl);
			for ($i=0; $i < 20; $i++) { 
				if ($i > 0 && $i < 19) {
					$pdf->Cell(4,7.4,$curp_empl_arr[$i-1],'L',0,'C');
				} else {
					$pdf->Cell(4,7.4,'','L',0,'C');
				}
			}

			$pdf->SetFont('Arial','',8);
			$pdf->SetY(61);
			$pdf->Cell(100,5,'',0,0,'C');
			$pdf->Cell(75,5,utf8_decode('03         Construcción'),'LR',1,'L');

			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,"Puesto*",'LRT',1,'L');
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,"OPERADOR",'LR',1,'L');
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			///////////////////////////////////////////////////////
			///				DATOS DE LA EMPRESA 				///
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$pdf->SetFont('Arial','B',8);
			$pdf->SetTextColor(255, 255, 255);
			$pdf->SetFillColor(0, 0, 0);
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,'DATOS DE LA EMPRESA',1,1,'C',true);

			$pdf->SetFont('Arial','',8);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,utf8_decode("Nombre o razón social (En caso de persona física, anotar apellido paterno, apellido materno y nombre(s))"),'LR',1,'J');

			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,"CONCRETO LANZADO DE FRESNILLO MARCA",'LR',1,'J');

			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(80,8,"Registro Federal de Contribuyentes con homoclave (SHCP)",'LRT',0,'L');
			$pdf->MultiCell(75,4,utf8_decode("Registro patronal ante el I.M.S.S. (Una letra o número y 10 dígitos)"),'RT','L');

			$rfc_empresa = "CLF220325CT4";
			$rfc_empresa_arr = str_split($rfc_empresa);
			$pdf->Cell(20,5,'',0,0,'C');
			for ($i=0; $i < 14; $i++) { 
				if ($i > 0 && $i < 13) {
					$pdf->Cell(5.7,5,$rfc_empresa_arr[$i-1],'L',0,'C');
				} else {
					$pdf->Cell(5.7,5,'','L',0,'C');
				}
			}


			$nss_empresa = "H0129070109";
			$nss_empresa_arr = str_split($nss_empresa);
			$pdf->Cell(0.2,5,'',0,0,'C');
			for ($i=0; $i < 13; $i++) { 
				if ($i > 0 && $i < 12) {
					$pdf->Cell(5.75,5,$nss_empresa_arr[$i-1],'L',0,'C');
				} else {
					$pdf->Cell(5.75,5,'','L',0,'C');
				}
			}
			$pdf->Cell(0.3,5,'','R',1,'C');

			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,"Actividad o giro principal",'LRT',1,'L');
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,"LANZADO DE CONCRETO DE OBRA MINERA",'LR',1,'L');
			$pdf->Cell(20,10,'',0,0,'C');
			$pdf->Cell(155,10,'',"T",1,'L');
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			///////////////////////////////////////////////////////////////////
			///				DATOS DEL PROGRAMA DE CAPACITACION 				///
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$pdf->SetFont('Arial','B',8);
			$pdf->SetTextColor(255, 255, 255);
			$pdf->SetFillColor(0, 0, 0);
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,"DATOS DEL PROGRAMA DE CAPACITACION Y ADIESTRAMIENTO",1,1,'C',true);

			$pdf->SetFont('Arial','',8);
			$pdf->SetTextColor(0, 0, 0);
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,"Nombre del curso",'LR',1,'L');
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,utf8_decode(strtoupper($nombre_curso)),'LRB',1,'L');

			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(30,5,utf8_decode('Duración en horas'),"LR",0,'C');
			$pdf->Cell(15,5,"Periodo",0,0,'C');
			$pdf->Cell(5,5,'',"R",0,'C');
			$pdf->Cell(24.6,5,utf8_decode('Año'),"R",0,'C');
			$pdf->Cell(12.3,5,utf8_decode('Mes'),"R",0,'C');
			$pdf->Cell(12.3,5,utf8_decode('Día'),"R",0,'C');
			$pdf->Cell(6.15,5,'',"R",0,'C');
			$pdf->Cell(24.6,5,utf8_decode('Año'),"R",0,'C');
			$pdf->Cell(12.3,5,utf8_decode('Mes'),"R",0,'C');
			$pdf->Cell(12.8,5,utf8_decode('Día'),"R",1,'C');

			$pdf->Cell(20,8,'',0,0,'C');
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(30,8,$horas,"LR",0,'C');
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(15,4,'de',0,0,'C');
			$pdf->Cell(5,8,'De',0,0,'C');


			$f_ini = explode("-", $fecha_ini);
			$pdf->SetFont('Arial','',10);
			$year = $f_ini[0];
			$year_arr = str_split($year);
			for ($i=0; $i < 4; $i++) { 
				$pdf->Cell(6.15,8,$year_arr[$i],"L",0,'C');
			}
			$mes = $f_ini[1];
			$mes_arr = str_split($mes);
			for ($i=0; $i < 2; $i++) { 
				$pdf->Cell(6.15,8,$mes_arr[$i],"L",0,'C');
			}
			$dia = $f_ini[2];
			$dia_arr = str_split($dia);
			for ($i=0; $i < 2; $i++) { 
				$pdf->Cell(6.15,8,$dia_arr[$i],"L",0,'C');
			}

			$pdf->SetFont('Arial','',8);
			$pdf->Cell(6.15,8,'a',"L",0,'C');

			$f_fin = explode("-", $fecha_fin);
			$pdf->SetFont('Arial','',10);
			$year = $f_fin[0];
			$year_arr = str_split($year);
			for ($i=0; $i < 4; $i++) { 
				$pdf->Cell(6.15,8,$year_arr[$i],"L",0,'C');
			}
			$mes = $f_fin[1];
			$mes_arr = str_split($mes);
			for ($i=0; $i < 2; $i++) { 
				$pdf->Cell(6.15,8,$mes_arr[$i],"L",0,'C');
			}
			$dia = $f_fin[2];
			$dia_arr = str_split($dia);
			for ($i=0; $i < 2; $i++) { 
				$pdf->Cell(6.15,8,$dia_arr[$i],"L",0,'C');
			}
			$pdf->Cell(0.5,8,'',"R",1,'C');
			
			$pdf->SetY(148);
			$pdf->SetFont('Arial','',8);
			$pdf->Cell(50,4,'',0,0,'C');
			$pdf->Cell(15,4,utf8_decode('ejecución'),"",0,'C');
			$pdf->Cell(5,4,'',0,1,'C');

			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,utf8_decode('Área temática del curso'),"TLR",1,'L');
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,8,utf8_decode($tema),"LR",1,'L');

			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,utf8_decode("Agente capacitador (Externo o interno, según corresponda)"),"TLR",1,'L');
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,8,utf8_decode($insturctor),"LR",1,'L');
			$pdf->Cell(20,10,'',0,0,'C');
			$pdf->Cell(155,10,'',"T",1,'L');
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

			///////////////////////////////////////
			///				FIRMAS 				///
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			$pdf->SetFont('Arial','B',9);
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->MultiCell(155,5,utf8_decode('Los datos se asientan en esta constancia bajo protesta de decir verdad, apercibidos de la responsabilidad en que incurre todo aquel que no se conduce con verdad.'),"LRT",'C');
			$pdf->Cell(20,10,'',0,0,'C');
			$pdf->Cell(155,10,'',"LR",1,'C');

			$pdf->SetFont('Arial','',8);
			$pdf->Cell(20,8,'',0,0,'C');
			$pdf->Cell(65,8,'Capacitador',"L",0,'C');
			$pdf->MultiCell(90,4,utf8_decode('Representantes de la Comisión Mixta de Capacitación y Adiestramiento'),"R",'C');

			$pdf->Cell(20,3,'',0,0,'C');
			$pdf->Cell(155,3,'',"LR",1,'C');

			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(65,5,'',"L",0,'C');
			$pdf->Cell(45,5,'Por la empresa',0,0,'C');
			$pdf->Cell(45,5,'Por los trabajadores',"R",1,'C');

			$pdf->Cell(20,8,'',0,0,'C');
			$pdf->Cell(155,8,'',"LR",1,'C');

			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(15,5,'',"L",0,'C');
			$pdf->Cell(35,5,'Nombre y Firma',"T",0,'C');
			$pdf->Cell(15,5,'',0,0,'C');

			$pdf->Cell(5,5,'',0,0,'C');
			$pdf->Cell(35,5,'Nombre y Firma',"T",0,'C');
			$pdf->Cell(5,5,'',0,0,'C');

			$pdf->Cell(5,5,'',0,0,'C');
			$pdf->Cell(35,5,'Nombre y Firma',"T",0,'C');
			$pdf->Cell(5,5,'',"R",1,'C');
			
			$pdf->Cell(20,5,'',0,0,'C');
			$pdf->Cell(155,5,'',"LRB",1,'C');
			////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}while($datos = mysql_fetch_array($rs));
	}
	
	
	

	$pdf->SetAuthor("Concreto Lanzado de Fresnillo Marca");
	$pdf->SetTitle("CONSTANCIA DC-3");
	$pdf->SetCreator("RECURSOS HUMANOS");
	$pdf->SetSubject("CONSTANCIA DC-3");
	$pdf->SetKeywords("Concreto Lanzado de Fresnillo Marca");

	$nom_pdf='CONSTANCIA_DC3.pdf';

	$pdf->Output($nom_pdf,"F");
	header('Location: '.$nom_pdf);
?>