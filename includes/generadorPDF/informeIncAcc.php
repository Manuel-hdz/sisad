<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");


class PDF extends FPDF{
	function Header(){
		$this->Image('fondoActa.jpg',1,1,215,268);
		$this->Image('frepaiac.jpg',13,15,190);
	    //Line break
	    $this->Ln(45);
		parent::Header();
	}
	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
	    $this->SetY(-20);
	    //Arial italic 9
	    $this->SetFont('Arial','',8);
	    //Pie de pagina
		$this->Cell(0,15,'Informe de Accidentes/Incidentes',0,0,'R');
		
		$this->SetFont('Arial','B',5);
		//$this->
	}

}//Cierre de la clase PDF	

	//Crear el Objeto PDF y Agregar las Caracteristicas Iniciales
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();	
			

	
	/**************************************************************************************************************/
	/****************************************DATOS GENERALES DEL INFORME*******************************************/
	/**************************************************************************************************************/
	//Definir los datos que se encuentran sobre la tabla y antes del encabezado
	//Imagen de Fondo
	//
	//$pdf->Image('fondoActa.jpg',1,1,215,268);
	//Logo
	//$pdf->Image('logo-clf.jpg',60,15,30);
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(135,7,'I. DATOS GENERALES',1,0,'C',1);
	$pdf->Ln();
	$pdf->Ln();		
	$conn=conecta("bd_seguridad");
	$idInforme = $_GET['id_registro'];
	//Sentencia para obtener el Detalle de la Requisicion
	$sql_stm = "SELECT * FROM accidentes_incidentes WHERE id_informe = '".$idInforme."'";
	$rs = mysql_query($sql_stm);
	$datos=mysql_fetch_array($rs);
	//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
	$pdf->SetFillColor(0,0,0);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(8);
	$lineasLugar = cortarCadena($datos['lugar'],35);
	$renglonesLugar = $lineasLugar['cantRenglones']*7;
	/**************DEPARTAMENTO**************/
	$pdf->Cell(30,$renglonesLugar,'DEPARTAMENTO:','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(60,$renglonesLugar,$datos['area'],'TBR',0,'L',0);
	/******************LUGAR*****************/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,$renglonesLugar,'LUGAR:','LTB',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	for($i=0;$i<$lineasLugar['cantRenglones'];$i++){
		if($i==0){
			if($renglonesLugar!=7){
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(75,7,$lineasLugar[$i],'TR',1,'L',0);	
			}
			else{
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(75,7,$lineasLugar[$i],'TLRB',0,'L',0);		
			}
		}
		else if($i>0&&$i<$lineasLugar['cantRenglones']-1){
			$pdf->Cell(113);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(75,7,$lineasLugar[$i],'R',1,'L',0);			
		}
		else if($i==$lineasLugar['cantRenglones']-1){
			$pdf->Cell(113);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(75,7,$lineasLugar[$i],'BR',0,'L',0);					
		}
	}
	$pdf->Ln();
	$pdf->Cell(8);
	
	/**********************NIVEL***********************/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,7,'NIVEL:','LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(75,7,$datos['nivel'],'TBR',0,'L',0);
	/**********************ÁREA ACCIDENTE**************/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,7,'ÁREA:','LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(75,7,$datos['area_acci'],'TBR',1,'L',0);
	$pdf->Cell(8);
	/**************NOMBRE DEL FACILITADOR**************/
	$lineasNomFac = cortarCadena($datos['nom_facilitador'],29);
	$renglonesNomFac = $lineasNomFac['cantRenglones']*7;
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(38,$renglonesNomFac,'NOMBRE FACILITADOR:','LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	for($i=0;$i<$lineasNomFac['cantRenglones'];$i++){
		if($i==0){
			if($renglonesNomFac!=7){
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(52,7,$lineasNomFac[$i],'TR',1,'L',0);	
			}
			else{
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(52,7,$lineasNomFac[$i],'TLRB',1,'L',0);		
			}
		}
		else if($i>0&&$i<$lineasNomFac['cantRenglones']-1){
			$pdf->Cell(46);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(52,7,$lineasNomFac[$i],'R',1,'L',0);			
		}
		else if($i==$lineasNomFac['cantRenglones']-1){
			$pdf->Cell(46);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(52,7,$lineasNomFac[$i],'BR',1,'L',0);					
		}
	}
	/**************FECHA DEL ACCIDENTE**************/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(98,7);
	$pdf->Cell(48,-1*$renglonesNomFac,'FECHA DEL ACCIDENTE:','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(42,-1*$renglonesNomFac,modFecha($datos['fecha_accidente'],2),'BTR',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Cell(8);
	$pdf->Ln();
	
	/**************HORA DEL ACCIDENTE**************/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(8);
	$pdf->Cell(38,7,'HORA DEL ACCIDENTE:','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(52,7,$datos['hora_accidente'],'BTR',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	/**********HORA DE AVISO AL FACILITADOR********/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(50,7,'HORA DE AVISO AL FACILITADOR:','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(40,7,$datos['hora_aviso'],'BTR',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	
	/**********HORA EN QUE DEJO DE LABORAR*********/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(8);
	$pdf->Cell(52,7,'HORA EN QUE DEJO DE LABORAR:','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(38,7,$datos['hora_termino'],'BTR',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	/************************TURNO******************/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(15,7,'TURNO:','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(75,7,$datos['turno'],'BTR',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Ln();	
	
	/**************************************************************************************************************/
	/****************************************DATOS DEL TRABAJADOR**************************************************/
	/**************************************************************************************************************/
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(135,7,'II. DATOS DEL TRABAJADOR',1,0,'C',1);
	$pdf->Ln();
	$pdf->Ln();	

	/***************NOMBRE DEL ACCIDENTADO****************/
	$nombreEmpleado =  $datos['nom_accidentado'];
	$lineasNomAcc = cortarCadena($nombreEmpleado,75);
	$renglonesNomAcc = $lineasNomAcc['cantRenglones']*7;
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(8);
	$pdf->Cell(15,$renglonesNomAcc,'NOMBRE:','LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	for($i=0;$i<$lineasNomAcc['cantRenglones'];$i++){
		if($i==0){
			if($renglonesNomAcc!=7){
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(75,7,$lineasNomAcc[$i],'TR',1,'L',0);	
			}
			else{
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(75,7,$lineasNomAcc[$i],'TRB',0,'L',0);		
			}
		}
		else if($i>0&&$i<$lineasNomAcc['cantRenglones']-1){
			$pdf->Cell(46);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(52,7,$lineasNomAcc[$i],'R',1,'L',0);			
		}
		else if($i==$lineasNomAcc['cantRenglones']-1){
			$pdf->Cell(46);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(52,7,$lineasNomAcc[$i],'BR',1,'L',0);					
		}
	}
	/************************FICHA******************/	
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(10,$renglonesNomAcc,'RFC','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(80,$renglonesNomAcc,$datos['empleados_rfc_empleado'],'BTR',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	
	
	/**********************EDAD********************/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(8);
	$pdf->Cell(10,7,'EDAD:','LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(80,$renglonesNomAcc,$datos['edad'],'BTR',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	/*******************CATEGORIA*****************/	
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(20,$renglonesNomAcc,'CATEGORÍA:','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(70,$renglonesNomAcc,$datos['puesto'],'BTR',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	
	/**********************AREA********************/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(8);
	$pdf->Cell(10,7,'ÁREA:','LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(80,7,$datos['area'],'BTR',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	/*****************EQUIPO DE TRABAJO************/	
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(35,7,'EQUIPO DE TRABAJO:','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(55,7,$datos['equipos_id_equipos'],'BTR',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	
	/**********************FICHA********************/
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(8);
	$pdf->Cell(10,7,'FICHA:','LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(80,7,$datos['ficha'],'BTR',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	/**************ANTIGUEDAD EN LA EMPRESA************/	
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(45,7,'ANTIGÜEDAD EN EL TRABAJO','LBT',0,'L',0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(45,7,$datos['antiguedad_empresa'],'BTR',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	
	
	/***************ACTIVIDAD HABITUAL******************/
	$lineasActHab = cortarCadena($datos['actividad_habitual'],80);
	$renglonesActHab = $lineasActHab['cantRenglones']*7;
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(8);
	$pdf->Cell(40,$renglonesActHab,'ACTIVIDAD HABITUAL:','LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	for($i=0;$i<$lineasActHab['cantRenglones'];$i++){
		if($i==0){
			if($renglonesActHab!=7){
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(140,7,$lineasActHab[$i],'TR',1,'L',0);	
			}
			else{
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(140,7,$lineasActHab[$i],'TRB',0,'L',0);		
			}
		}
		else if($i>0&&$i<$lineasActHab['cantRenglones']-1){
			$pdf->Cell(48);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(140,7,$lineasActHab[$i],'R',1,'L',0);			
		}
		else if($i==$lineasActHab['cantRenglones']-1){
			$pdf->Cell(48);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(140,7,$lineasActHab[$i],'BR',0,'L',0);					
		}
	}
	$pdf->Ln();
	/********ACTIVIDAD DESEMPEÑADA AL MOMENTO DEL ACCIDENTE***********/
	$lineasActMom = cortarCadena($datos['act_mom_acci'],55);
	$renglonesActMom = $lineasActMom['cantRenglones']*7;
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(8);
	$pdf->Cell(85,$renglonesActMom,'ACTIVIDAD DESEMPEÑADA AL MOMENTO DEL ACCIDENTE:','LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	for($i=0;$i<$lineasActMom['cantRenglones'];$i++){
		if($i==0){
			if($renglonesActMom!=7){
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(95,7,$lineasActMom[$i],'TR',1,'L',0);	
			}
			else{
				//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
				$pdf->Cell(95,7,$lineasActMom[$i],'TRB',0,'L',0);		
			}
		}
		else if($i>0&&$i<$lineasActMom['cantRenglones']-1){
			$pdf->Cell(93);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(95,7,$lineasActMom[$i],'R',1,'L',0);			
		}
		else if($i==$lineasActMom['cantRenglones']-1){
			$pdf->Cell(93);
			//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
			$pdf->Cell(95,7,$lineasActMom[$i],'BR',0,'L',0);					
		}
	}
	
	$pdf->Ln();
	$pdf->Cell(8);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(42,7,"NO. DE ". $datos['tipo_informe']." AL AÑO:",'LBT',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(138,7,$datos['num_total_acci']."° ACCIDENTE OCURRIDO EN EL AÑO",'LBTR',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Ln();
	$pdf->Ln();	
	
	/**************************************************************************************************************/
	/*************************************************DESCRIPCIÓN**************************************************/
	/**************************************************************************************************************/
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(135,7,'III. DESCRIPCIÓN DE LOS HECHOS',1,0,'C',1);
	$pdf->Ln();
	$pdf->Ln();	
	
	$pdf->Cell(8);
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(180,5,$datos['descripcion'],'TBLR','J',0);//Ancho, alto,texto, borde, alineacion, relleno
	$pdf->Ln();
	$pdf->Ln();
	
	/**************************************************************************************************************/
	/*************************************************TIPO DE LESIÓN***********************************************/
	/**************************************************************************************************************/
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(135,7,'IV. TIPO DE LESIÓN',1,0,'C',1);
	$pdf->Ln();
	$pdf->Ln();	
	
	$pdf->Cell(8);
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(180,5,$datos['tipo_lesion'],'TBLR','J',0);//Ancho, alto,texto, borde, alineacion, relleno
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	
	
	
	/**************************************************************************************************************/
	/*****************************************ANALISIS DEL ACCIDENTE***********************************************/
	/**************************************************************************************************************/
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(135,7,'V. ANÁLISIS DEL INCIDENTE',1,0,'C',1);
	$pdf->Ln();
	$pdf->Ln();	
	
	$pdf->Cell(8);
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(180,5,$datos['porque_paso'],'TBLR','J',0);//Ancho, alto,texto, borde, alineacion, relleno
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	
	/**************************************************************************************************************/
	/*****************************************CAUSAS DEL  ACCIDENTE***********************************************/
	/**************************************************************************************************************/
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(135,7,'VI. CAUSAS DEL INCIDENTE',1,0,'C',1);
	$pdf->Ln();
	$pdf->Ln();	
	
	$pdf->Cell(8);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(180,5,'ACTOS INSEGUROS','LRT',1,'C',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Cell(8);
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(180,5,$datos['actos_inseguros'],'LBR','J',0);//Ancho, alto,texto, borde, alineacion, relleno	
	$pdf->Cell(8);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(180,5,'CONDICIONES INSEGURAS','LRT',1,'C',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Cell(8);
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(180,5,$datos['cond_inseguras'],'LBR','J',0);//Ancho, alto,texto, borde, alineacion, relleno
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	
	/**************************************************************************************************************/
	/****************************************ACCIONES CORRECTIVAS Y PREVENTIVAS************************************/
	/**************************************************************************************************************/
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(135,7,'VII. ACCIONES PREVENTIVAS Y CORRECTIVAS',1,0,'C',1);
	$pdf->Ln();
	$pdf->Ln();	
	$pdf->SetFont('Arial','',8);//Tipo de Letra
	$conn=conecta("bd_seguridad");
	$stm_sqlAcc = "SELECT * FROM acciones_pre_corr WHERE accidentes_incidentes_id_informe = '".$idInforme."'";
	$rsDet = mysql_query($stm_sqlAcc);
	$cont = 1;
	if($datosDet = mysql_fetch_array($rsDet)){
		do{	
			$lineasAccion = cortarCadena($datosDet['accion'],50);
			$renglonesAcciones = $lineasAccion['cantRenglones']*7; 
			for($i=0;$i<$lineasAccion['cantRenglones'];$i++){
				if($i==0){
					if($renglonesAcciones!=7){
						//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
						$pdf->Cell(12);//Poner sangria de 12 mm para iniciar a dibujar la tabla
						$pdf->Cell(5,$renglonesAcciones,$cont,'LRT',0,'C',0);
						$pdf->Cell(17,$renglonesAcciones,modFecha($datosDet['fecha'],1),'LTBR',0,'L',0);
						$pdf->Cell(96,7,$lineasAccion[$i],'TLR',0,'L',0);		
						$pdf->Cell(60,7,$datosDet['responsable'],"LRT",1,'L',0);	
					}
					else{
						//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
						$pdf->Cell(12);//Poner sangria de 12 mm para iniciar a dibujar la tabla
						$pdf->Cell(5,$renglonesAcciones,$cont,'LRTB',0,'C',0);
						$pdf->Cell(17,7,modFecha($datosDet['fecha'],1),'LTRB',0,'L',0);
						$pdf->Cell(96,7,$lineasAccion[$i],'TLRB',0,'L',0);		
						$pdf->Cell(60,7,$datosDet['responsable'],"LRTB",1,'L',0);	
					}
				}
				else if($i>0&&$i<$lineasAccion['cantRenglones']-1){
					//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
					$pdf->Cell(12);//Poner sangria de 12 mm para iniciar a dibujar la tabla
					$pdf->Cell(5,7,"",'',0,'C',0);
					$pdf->Cell(17,7,"",'',0,'C',0);
					$pdf->Cell(96,7,$lineasAccion[$i],'LR',0,'L',0);			
					$pdf->Cell(60,7,"",'LR',1,'L',0);	
				}
				else if($i==$lineasAccion['cantRenglones']-1){
					//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
					$pdf->Cell(12);//Poner sangria de 12 mm para iniciar a dibujar la tabla
					$pdf->Cell(5,7,"",'LRB',0,'C',0);
					$pdf->Cell(17,7,"",'LR',0,'C',0);
					$pdf->Cell(96,7,$lineasAccion[$i],'LBR',0,'L',0);					
					$pdf->Cell(60,7,"",'LRB',1,'L',0);	
				}
			}
			$cont++;
		}while($datosDet = mysql_fetch_array($rsDet));
	}
	
	$pdf->Ln();
	$pdf->Ln();
	/**************************************************************************************************************/
	/*********************************************OBSERVACIONES****************************************************/
	/**************************************************************************************************************/
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(135,7,'VIII. OBSERVACIONES',1,0,'C',1);
	$pdf->Ln();
	$pdf->Ln();	
	
	$pdf->Cell(8);
	$pdf->SetFont('Arial','',8);
	$pdf->MultiCell(180,5,$datos['observaciones'],'LTBR','J',0);//Ancho, alto,texto, borde, alineacion, relleno	
	
	//Agregamos la nueva pagina
	$pdf->AddPage();
	$sql_stm = "SELECT * FROM accidentes_incidentes WHERE id_informe = '".$idInforme."'";
	$rs = mysql_query($sql_stm);
	$datos=mysql_fetch_array($rs);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(0,7,'ATENTAMENTE',0,0,'C',0);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(0,7,'_____________________________________________',0,1,'C',0);
	$pdf->Cell(0,7,'FACILITADOR',0,1,'C',0);
	$pdf->Cell(0,7,$datos['nom_facilitador'],0,1,'C',0);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(30);
	$pdf->Cell(23,7,'_____________________________________________',0,0,'C',0);
	$pdf->Cell(90,7,'');
	$pdf->Cell(23,7,'_____________________________________________',0,1,'C',0);
	$pdf->Cell(30);
	$pdf->Cell(23,7,$datos['coordinador_csh'],0,0,'C',0);
	$pdf->Cell(90,7,'');
	$pdf->Cell(23,7,$datos['secretario_csh'],0,1,'C',0);
	$pdf->Cell(30);
	$pdf->Cell(23,7,'COORDINADOR DE LA CSH',0,0,'C',0);
	$pdf->Cell(90,7,'');
	$pdf->Cell(23,7,'SECRETARIO DE LA CSH',0,1,'C',0);
	$pdf->Cell(8);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln(20);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(30);
	$pdf->Cell(23,7,'_____________________________________________',0,0,'C',0);
	$pdf->Cell(90,7,'');
	$pdf->Cell(23,7,'_____________________________________________',0,1,'C',0);
	$pdf->Cell(30);
	$pdf->Cell(23,7,$datos['jefe_seguridad'],0,0,'C',0);
	$pdf->Cell(90,7,'');
	$pdf->Cell(23,7,$datos['dpto_seguridad'],0,1,'C',0);
	$pdf->Cell(30);
	$pdf->Cell(23,7,'JEFE DE SEGURIDAD Y CONTROL AMBIENTAL',0,0,'C',0);
	$pdf->Cell(90,7,'');
	$pdf->Cell(23,7,'DEPARTAMENTO DE SEGURIDAD',0,1,'C',0);
	$pdf->Cell(8);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Ln();
	
	
	$pdf->Ln(20);
	$pdf->SetFont('Arial','B',8);
	$pdf->Cell(30);
	$pdf->Cell(113,7,'');
	$pdf->Cell(23,7,'_____________________________________________',0,1,'C',0);
	$pdf->Cell(30);
	$pdf->Cell(113,7,'');
	$pdf->Cell(23,7,$datos['testigo'],0,1,'C',0);
	$pdf->Cell(30);
	$pdf->Cell(113,7,'');
	$pdf->Cell(23,7,'TESTIGO',0,1,'C',0);
	

	
	
	
	//Salto de Linea
	$pdf->Ln();
	
	
	//Especificar Datos del Documento, propiedades del documento
	$pdf->SetAuthor("SEGURIDAD INDUSTRIAL");
	$pdf->SetCreator("SEGURIDAD INDUSTRIAL");
	$pdf->SetSubject("INFORME ACCIDENTES INCIDENTES ".$idInforme);
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Informe de Accidentes/Incidentes ".$idInforme." en el SISAD");
	$idInforme.='.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($idInforme,"F");
	header('Location: '.$idInforme);
	//Borrar todos los PDF ya creados
	borrarArchivos();
	
	
				
	/***********************************************************************************************/
	/**************************FUNCIONES USADAS EN EL INFORME***************************************/
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


	/*Esta funcion regresa el nombre de los trabajadores concatenado en forma Nombre Apellido_Paterno Apellido Materno*/
	function obtenerNombreEmpleado($rfc_empleado){
		$conn=conecta("bd_recursos");
		$stm_sql="SELECT CONCAT(nombre,' ',ape_pat,' ',ape_mat) AS nombreEmpleado FROM empleados WHERE rfc_empleado='$rfc_empleado'";
		$rs=mysql_query($stm_sql);
		$datos=mysql_fetch_array($rs);
		return $datos["nombreEmpleado"];
		mysql_close($conn);
	}
?>