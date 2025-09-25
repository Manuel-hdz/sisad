<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");


class PDF extends FPDF{
	function Header(){
		$this->Image('fondoActa.jpg',1,1,215,268);
	    //Line break
	    $this->Ln(45);
		parent::Header();
	}
	//Page footer
	function Footer(){
		//Position at 1.5 cm from bottom
	    $this->SetY(-20);
	    //Arial italic 9
	    $this->SetFont('Arial','',9);
	    //Pie de pagina
		$this->Cell(0,15,'F-CSH-1',0,0,'R');
		
		$this->SetFont('Arial','B',5);
		//$this->
	}

}//Cierre de la clase PDF	

	//Crear el Objeto PDF y Agregar las Caracteristicas Iniciales
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();	
			

	
	/**************************************************************************************************************/
	/**********************************DATOS GENERALES DE LA ACTA*******************************************/
	/**************************************************************************************************************/
	//Definir los datos que se encuentran sobre la tabla y antes del encabezado
	//Imagen de Fondo
	//
	//$pdf->Image('fondoActa.jpg',1,1,215,268);
	//Logo
	$pdf->Image('logo-clf.jpg',60,15,90);
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(135,7,'ACTA DE VERIFICACIÓN DE LA COMISIÓN DE SEGURIDAD E HIGIENE',1,0,'C',1);
	$pdf->Ln();
	$pdf->Ln();
	$pdf->Cell(160,5,'ACTA No.',0,0,'R',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Cell(24,5,$_GET['idActa'],'B',1,'C',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Ln();
	
			
	/**************************************************************************************************************/
	/**********************************FECHAS DE REGISTRO**********************************************************/
	/**************************************************************************************************************/
	$conn=conecta("bd_seguridad");
	$idActa=$_GET['idActa'];
	//Sentencia para obtener el Detalle de la Requisicion
	$sql_stm = "SELECT * FROM acta_comision WHERE id_acta_comision = '".$idActa."'";
	$rs = mysql_query($sql_stm);
	$datos=mysql_fetch_array($rs);
	//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
	$pdf->SetFillColor(0,0,0);
	$pdf->SetDrawColor(0, 0, 0);
	$pdf->SetTextColor(0, 0, 0);
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(30,7,'FECHA: ',0,0,'R',0);
	//Seccionamos la fecha de registro para obtener los diferentes datos para la generacion del PDF
	$anio =array();
	$cont=0;
	do{
		$anio[]=substr($datos['fecha_registro'],$cont,1);
		$cont++;
	}while($cont<4);
	$dia =array();
	$cont=8;
	do{
		$dia[]=substr($datos['fecha_registro'],$cont,1);
		$cont++;
	}while($cont<10);
	$mes =array();
	$cont=5;
	do{
		$mes[]=substr($datos['fecha_registro'],$cont,1);
		$cont++;
	}while($cont<7);
	//Dibujamos casilla por casilla la fecha indicada
	$pdf->Cell(4,5,$dia[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$dia[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$mes[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$mes[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anio[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anio[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anio[2],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anio[3],'LBR',0,'C',0);
	
	$pdf->Cell(35,7,'PERIODO DE',0,0,'C',0);	
	
	//Seccionamos la fecha de registro para obtener los diferentes datos para la generacion del PDF
	$anioPerIni =array();
	$cont=0;
	do{
		$anioPerIni[]=substr($datos['periodo_ini'],$cont,1);
		$cont++;
	}while($cont<4);
	$diaPerIni =array();
	$cont=8;
	do{
		$diaPerIni[]=substr($datos['periodo_ini'],$cont,1);
		$cont++;
	}while($cont<10);
	$mesPerIni =array();
	$cont=5;
	do{
		$mesPerIni[]=substr($datos['periodo_ini'],$cont,1);
		$cont++;
	}while($cont<7);
	//Dibujamos casilla por casilla la fecha indicada
	$pdf->Cell(4,5,$diaPerIni[0],'LBR',0,'C',0);//(w, h, var, alineacion, 0, centro, 0)
	$pdf->Cell(4,5,$diaPerIni[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$mesPerIni[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$mesPerIni[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPerIni[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPerIni[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPerIni[2],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPerIni[3],'LBR',0,'C',0);
	
	$pdf->Cell(10,7,'AL: ',0,0,'R',0);	
	//Seccionamos la fecha de periodo_fin para obtener los diferentes datos para la generacion del PDF
	$anioPerFin =array();
	$cont=0;
	do{
		$anioPerFin[]=substr($datos['periodo_fin'],$cont,1);
		$cont++;
	}while($cont<4);
	$diaPerFin =array();
	$cont=8;
	do{
		$diaPerFin[]=substr($datos['periodo_fin'],$cont,1);
		$cont++;
	}while($cont<10);
	$mesPerFin =array();
	$cont=5;
	do{
		$mesPerFin[]=substr($datos['periodo_fin'],$cont,1);
		$cont++;
	}while($cont<7);
	//Dibujamos casilla por casilla la fecha indicada
	$pdf->Cell(4,5,$diaPerFin[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$diaPerFin[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$mesPerFin[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$mesPerFin[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPerFin[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPerFin[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPerFin[2],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPerFin[3],'LBR',1,'C',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	
	//Dibujamos los pies de las fechas 
	$pdf->Cell(30);//Ponemos un espacio (sagria)
	$pdf->Cell(8,5,"DIA",'LR',0,'C',0);//dia de registro	
	$pdf->Cell(8,5,"MES",'LR',0,'C',0);//mes de registro
	$pdf->Cell(16,5,"AÑO",'LR',0,'C',0);//año de registro	
	
	//Dibujamos el periodo de verificacion	
	$pdf->Cell(35,5,"VERIFICACIÓN",'0',0,'C',0);//parte inferior del periodo de inicio		
	$pdf->Cell(8,5,"DIA",'LR',0,'C',0);//dia del periodo de inicio	
	$pdf->Cell(8,5,"MES",'LR',0,'C',0);//mes de periodo de inicio
	$pdf->Cell(16,5,"AÑO",'LR',0,'C',0);//año de periodo de inicio
	
	
	//Dibujamos los pies de las fechas 
	$pdf->Cell(10);//Ponemos un espacio (sagria)
	$pdf->Cell(8,5,"DIA",'LR',0,'C',0);//dia de periodo de fin
	$pdf->Cell(8,5,"MES",'LR',0,'C',0);//mes de periodo de fin
	$pdf->Cell(16,5,"AÑO",'LR',1,'C',0);//año de periodo de fin	//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	
	//Salto de Linea
	$pdf->Ln();
	
	/**************************************************************************************************************/
	/**************************************DESCRIPCION*************************************************************/
	/**************************************************************************************************************/
	//Dibujamos la descripcion
	$pdf->Cell(15);
	$pdf->MultiCell(160,5,$datos['descripcion_acta'],0,'J',0);//Ancho, alto,texto, borde, alineacion, relleno
	
	
	/**************************************************************************************************************/
	/**************************************TIPO DE ACTA************************************************************/
	/**************************************************************************************************************/
	//Dibujamos el tipo de Acta
	//Salto de Linea
	$pdf->Ln();
	//Estilo y Tipo de Texto	
	$pdf->SetFont('Arial','B',8);//Tipo de Letra
	$pdf->Cell(15);//Sangria
	//Dibujamos la verificacion ordinaria
	if($datos['tipo_verificacion']=="N/A"){
		$pdf->Cell(20,5,"Ordinaria",0,0,'C',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
		$pdf->Cell(4,4,"X",'LTBR',0,'C',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	}
	//Dibujamos Verificacion Extraordinaria
	$pdf->Cell(30,5,"Extraordinaria Por: ",0,0,'C',0);
	if($datos['tipo_verificacion']!="N/A"){
		$pdf->Cell(110,5,$datos['tipo_verificacion'],'B',0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	}
	else{
		$pdf->Cell(110,5," ",'B',1,'L',0);
	}
	$pdf->Ln();
	
	/**************************************************************************************************************/
	/**************************************ASISTENTES**************************************************************/
	/**************************************************************************************************************/
	//Dibujamos el encabezado de  los Asistentens
	$pdf->Ln();
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(35);
	$pdf->Cell(60,7,'I. ASISTENTES',1,0,'C',1);
	
	//Dibujamos los asistentes
	$pdf->Ln();
	$pdf->Ln();
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(40);
	$pdf->Cell(100,5,'NOMBRE',0,0,'C',0);
	$pdf->Cell(40,5,'FIRMA',0,0,'C',0);
	$pdf->Ln();
	$stm_sqlAsist = "SELECT * FROM asistentes WHERE acta_comision_id_acta_comision='".$idActa."'";//Creamos la consulta
	$rsAsist = mysql_query($stm_sqlAsist);//Ejecutamos la consulta
	$cont=1;
	if($datosAsist=mysql_fetch_array($rsAsist)){
		do{
			$pdf->Cell(10);
			$pdf->Cell(8,5,$cont.")",0,0,'C',0);
			$pdf->Cell(23,5,$datosAsist['puesto_asistente'],0,0,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
			$pdf->Cell(95,5,$datosAsist['nom_asistente'],'B',0,'L',0);//Dibujamos el nombre
			$pdf->Cell(5);
			$pdf->Cell(40,5,"",'B',0,'L',0);//Dibujamos lineas para la firma
			
			$pdf->Ln();
			$cont++;
		}while($datosAsist=mysql_fetch_array($rsAsist));
	}
	//Agregamos la nueva pagina
	$pdf->AddPage();
	
	/**************************************************************************************************************/
	/**************************************AGENDA******************************************************************/
	/**************************************************************************************************************/
	//Dibujamos el encabezado de los puntos de la agenda
	$pdf->Ln();
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->SetY(15);//Mueve la absicisa actual de regreso al margen izquierto y establece la ordenada
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(25);
	$pdf->Cell(90,7,'II. PUNTOS TRATADOS/AGENDA',1,1,'C',1);
	
	//Dibujamos el contenido de la agenda
	$pdf->Ln();
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$stm_sqlAgenda = "SELECT * FROM puntos_agenda WHERE acta_comision_id_acta_comision='".$idActa."'";//Creamos la consulta
	$rsAgenda = mysql_query($stm_sqlAgenda);//Ejecutamos la consulta
	$cont=1;
	
	//Dibujamos el margen Superior
	$pdf->Cell(30);
	$pdf->Cell(135,0," ", "T",0, 0, 0);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	if($datosAgenda=mysql_fetch_array($rsAgenda)){
		do{
			$pdf->Cell(30,0,"",'L',0,'C',0);
			$pdf->MultiCell(135,5,$cont.".-".$datosAgenda['punto_acordado'],'LR','J',0);//Ancho, alto,texto, borde, alineacion, relleno		
			$cont++;
		}while($datosAgenda=mysql_fetch_array($rsAgenda));
	}
	//Dibujamos el Margen Inferior
	$pdf->Cell(30);
	$pdf->Cell(135,0," ", "B",1, 0, 0);
	$pdf->Ln();
	$pdf->Cell(0,7,'', 0,1);
	
	
	/**************************************************************************************************************/
	/**************************************AREAS VISITADAS*********************************************************/
	/**************************************************************************************************************/
	//Dibujamos el encabezado de los puntos de las areas visitadas
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(25);
	$pdf->Cell(90,7,'III. ÁREAS VISITADAS',1,1,'C',1);
	
	//Dibujamos el contenido de la de las areas visitadas
	$pdf->Ln();
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$stm_sqlAreaVisit = "SELECT * FROM areas_visitadas WHERE acta_comision_id_acta_comision='".$idActa."'";//Creamos la consulta
	$rsAreaVisit = mysql_query($stm_sqlAreaVisit);//Ejecutamos la consulta
	$cont=1;
	
	//Dibujamos el margen Superior
	$pdf->Cell(30);
	$pdf->Cell(135,0," ", "T",0, 0, 0);
	$pdf->Ln();
	if($datosAreaVisit=mysql_fetch_array($rsAreaVisit)){
		do{
			$pdf->Cell(30);
			$pdf->MultiCell(135,5,$cont.".-  ".$datosAreaVisit['area_visitada'],'RL','J');//Ancho, alto,texto, borde, alineacion, relleno	
			$cont++;
		}while($datosAreaVisit=mysql_fetch_array($rsAreaVisit));
	}
	//Dibujamos el Margen Inferior
	$pdf->Cell(30);
	$pdf->Cell(135,0," ", "B",1, 0, 0);
	$pdf->Ln();
	$pdf->Cell(0,7,'', 0,1);
	
	
	/**************************************************************************************************************/
	/**************************************ACCIDENTES INVESTIGADOS*************************************************/
	/**************************************************************************************************************/
	//Dibujamos el encabezado de los puntos de los accidentes Investigados
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(25);
	$pdf->Cell(90,7,'IV. ACCIDENTES  INVESTIGADOS',1,1,'C',1);
	$pdf->Cell(0,7,"",0,1);
	
	//Dibujamos el contenido de la de los accidentes investigados
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$stm_sqlAcc = "SELECT * FROM accidentes WHERE acta_comision_id_acta_comision='".$idActa."'";//Creamos la consulta
	$rsAcc = mysql_query($stm_sqlAcc);//Ejecutamos la consulta
	//Dibujamos encabezados
	$pdf->Cell(12);
	$pdf->Cell(8,5,"NO.",'LTR',0,'C',0);
	$pdf->Cell(15,5,"FECHA",'LTRB',0,'C',0);
	$pdf->Cell(50,5,"NOMBRE",'LTRB',0,'C',0);
	$pdf->Cell(50,5,"CAUSAS DEL ACCIDENTE",'LTRB',0,'C',0);
	$pdf->Cell(50,5,"MEDIDAS PREVENTIVAS",'LTRB',1,'C',0);
	$pdf->SetFont('Arial','',7);//Tipo de Letra
	$cont=1;
	if($datosAcc=mysql_fetch_array($rsAcc)){
		do{
			//Obtener la cantidad de renglones que ocupara cada registro de la BD, ya que las columnas de Nombre, Causas y Medidas pueden contener mucho texto
			$lineasNombre = cortarCadena($datosAcc['nom_acc'],30);
			$lineasCausas = cortarCadena($datosAcc['causa_acc'],30);
			$lineasMedidas = cortarCadena($datosAcc['acciones_prev'],30);
			//Obtener el numero maximo de renglones por columna
			$renglones = max($lineasNombre['cantRenglones'],$lineasCausas['cantRenglones'],$lineasMedidas['cantRenglones']);
			//Colocar el Numero y la Fecha con el Alto del Registro de acuerdo a la cantidad de renglones			
			$alto = $renglones * 5;
			$pdf->Cell(12,$alto);//Poner sangria de 10 mm para iniciar a dibujar la tabla
			$pdf->Cell(8,$alto,$cont,1,0,'C',0);
			$pdf->Cell(15,$alto,modFecha($datosAcc['fecha_acc'],1),1,0,'L',0);
			for($i=0;$i<$renglones;$i++){				
				if($i==0){
					//Escibir el primer renglon en la columna de Nombre del Accidente
					$pdf->Cell(50,5,$lineasNombre[$i],'LTR',0,'L',0);
					//Escibir el primer renglon en la columna de Causas del Accidente
					$pdf->Cell(50,5,$lineasCausas[$i],'LTR',0,'L',0);
					//Escibir el primer renglon en la columna de Acciones Preventivas
					$pdf->Cell(50,5,$lineasMedidas[$i],'LTR',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno								
				}
				else if($i>0&&$i<$renglones){
					//Dibujar las columnas de No, Fecha y la sangria en blanco para alinear las sig columnas
					$pdf->Cell(12,5);
					$pdf->Cell(8,5,"",'LR');
					$pdf->Cell(15,5,"",'LR');
						
					if(isset($lineasNombre[$i])){ 
						$pdf->Cell(50,5,$lineasNombre[$i],'LR',0,'L',0); 
					}
					else{
						$pdf->Cell(50,5,"",'LR',0,'L',0);
					}					
					if(isset($lineasCausas[$i])){
						$pdf->Cell(50,5,$lineasCausas[$i],'LR',0,'L',0);
					}
					else{
						$pdf->Cell(50,5,"",'LR',0,'L',0);
					}	
					if(isset($lineasMedidas[$i])){
						$pdf->Cell(50,5,$lineasMedidas[$i],'LR',1,'L',0);					
					}
					else{
						$pdf->Cell(50,5,"",'LR',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
					}	
				}
				else if($i==$renglones){
					//Dibujar las columnas de No, Fecha y la sangria en blanco para alinear las sig columnas
					$pdf->Cell(12,5);
					$pdf->Cell(8,5,"",'LRB');
					$pdf->Cell(15,5,"",'LRB');
					
					if(isset($lineasNombre[$i])){						
						$pdf->Cell(50,5,$lineasNombre[$i],'LRB',0,'L',0);
					}
					else{
						$pdf->Cell(50,5,"",'LRB',0,'L',0);
					}
					if(isset($lineasCausas[$i])){
						$pdf->Cell(50,5,$lineasCausas[$i],'LRB',0,'L',0);
					}
					else{
						$pdf->Cell(50,5,"",'LRB',0,'L',0);
					}
					if(isset($lineasMedidas[$i])){
						$pdf->Cell(50,5,$lineasMedidas[$i],'LRB',1,'L',0);
					}
					else{
						$pdf->Cell(50,5,"",'LRB',1,'L',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
					}
				}				
			}
											
			$cont++;
		}while($datosAcc=mysql_fetch_array($rsAcc));
		$pdf->Cell(12,5);
		$pdf->Cell(173,5,"",'T',1,'L',0);
	}
	else{
		//Dibujamos encabezados
		$pdf->Cell(12);
		$pdf->SetFont('Arial','B',7);//Tipo de Letra
		$pdf->Cell(173,5,"NO EXISTEN ACCIDENTES REGISTRADOS",'LTRB',0,'C',0);
	}
	
	/**************************************************************************************************************/
	/**************************************RECORRIDOS DE VERIFICACIÓN**********************************************/
	/**************************************************************************************************************/
	//Dibujamos el encabezado de los puntos de recorridos de verificacion
	$pdf->SetFont('Arial','B',11);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$pdf->Cell(30);
	$pdf->SetFillColor(255,255,153);
	$pdf->Cell(5);
	$pdf->Cell(130,7,'V. COMPROMISOS RECORRIDOS DE VERIFICACIÓN/EVALUACIÓN',1,1,'C',1);
	$pdf->Cell(0,7,"",0,1);
	
	
	//Dibujamos el contenido de la de los recorridos de verificacion
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->SetTextColor(0, 0, 0);//Color del Texto
	$pdf->SetDrawColor(0, 0, 0);//Color de los Bordes
	$stm_sqlRec = "SELECT * FROM recorridos_verificacion WHERE acta_comision_id_acta_comision='".$idActa."'";//Creamos la consulta
	$rsRec = mysql_query($stm_sqlRec);//Ejecutamos la consulta
	//Dibujamos encabezados
	$pdf->Cell(12);
	$pdf->Cell(8,10,"NO.",'LTR',0,'C',0);
	$pdf->Cell(50,10,"ACCIÓN(ES)",'LTRB',0,'C',0);
	$pdf->Cell(60,10,"RESPONSABLE(ES)",'LTRB',0,'C',0);
	$pdf->Cell(25,10,"FECHA LÍMITE",'LTRB',0,'C',0);
	$pdf->Cell(30,5,"FECHA",'LTR',1,'C',0);
	$pdf->Cell(155);
	$pdf->Cell(30,5,"PUNTO CUMPLIDO",'LRB',1,'C',0);
	$pdf->SetFont('Arial','',7);//Tipo de Letra
	$cont=1;
	if($datosRec=mysql_fetch_array($rsRec)){
		do{
			
			//Obtener la cantidad de renglones que ocupara cada registro de la BD
			$lineasActo = cortarCadena($datosRec['acto_inseguro'],30);
			//Obtener el numero maximo de renglones por columna
			$renglones = $lineasActo['cantRenglones'];
			$alto = $renglones * 5;
			for($i=0;$i<$renglones;$i++){
				if($i==0){
					//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
					$pdf->Cell(12);//Poner sangria de 12 mm para iniciar a dibujar la tabla
					$pdf->Cell(8,5,$cont,'LRT',0,'C',0);
					$pdf->Cell(50,5,$lineasActo[$i],'LRT',0,'L',0); 			
					$pdf->Cell(60,5,$datosRec['responsable'],"LRT",0,'L',0);	
					$pdf->Cell(25,5,modFecha($datosRec['fecha_limite'],1),"LRT",0,'C',0);	
					$pdf->Cell(30,5,modFecha($datosRec['fecha_cumplida'],1),"LRT",1,'C',0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
				}
				else if($i>0&&$i<$renglones-1){
					//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
					$pdf->Cell(12);//Poner sangria de 12 mm para iniciar a dibujar la tabla
					$pdf->Cell(8,5,"",'LR',0,'C',0);
					$pdf->Cell(50,5,$lineasActo[$i],'LR',0,'L',0); 			
					$pdf->Cell(60,5,"",'LR',0,'L',0);	
					$pdf->Cell(25,5,"",'LR',0,'L',0);	
					$pdf->Cell(30,5,"",'LR',1,'L',0);	//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
				}
				else if($i==$renglones-1){
					//Colocar el Numero con el Alto del Registro de acuerdo a la cantidad de renglones								
					$pdf->Cell(12);//Poner sangria de 12 mm para iniciar a dibujar la tabla
					$pdf->Cell(8,5,"",'LRB',0,'C',0);
					$pdf->Cell(50,5,$lineasActo[$i],'LRB',0,'L',0); 			
					$pdf->Cell(60,5,"",'LRB',0,'L',0);	
					$pdf->Cell(25,5,"",'LRB',0,'L',0);	
					$pdf->Cell(30,5,"",'LRB',1,'L',0);	//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
				}
			}
			$cont++;							
		}while($datosRec=mysql_fetch_array($rsRec));
		$pdf->Cell(12,5);
		$pdf->Cell(173,5,"",'T',1,'L',0);
	}
	//Agregamos la nueva pagina
	$pdf->AddPage();
	/**************************************************************************************************************/
	/**************************************DATOS GENERALES FINALES*************************************************/
	/**************************************************************************************************************/
	$pdf->SetY(15);//Mueve la absicisa actual de regreso al margen izquierto y establece la ordenada
	$pdf->Cell(12);//Poner sangria de 12 mm para iniciar a dibujar la tabla
	$pdf->Cell(173,5,"",'LTR',1,'L',0);
	$pdf->Cell(12);//Poner sangria de 12 mm para iniciar a dibujar la tabla
	$pdf->Cell(18,5,"HORA INICIO",'L',0,'L',0);
	//Seccionamos la hora para ubicarla en las casillas correspondientes
	$seccHoraIni=explode(":",$datos['hora_ini']);
	$pdf->Cell(5,5,$seccHoraIni['0'],'LRB',0,'C',0);	//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Cell(5,5,":",'LRB',0,'C',0);
	$pdf->Cell(5,5,$seccHoraIni['1'],'LRB',0,'C',0);
	//Hora de Terminacion
	$pdf->Cell(60);//Poner sangria de 12 mm para iniciar a dibujar la tabla
	$pdf->Cell(30,5,"HORA TERMINACION",'',0,'L',0);
	//Seccionamos la hora para ubicarla en las casillas correspondientes
	$seccHoraFin=explode(":",$datos['hora_fin']);
	$pdf->Cell(5,5,$seccHoraFin['0'],'LRB',0,'C',0);	//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Cell(5,5,":",'LRB',0,'C',0);
	$pdf->Cell(5,5,$seccHoraFin['1'],'LRB',0,'C',0);
	$pdf->Cell(35,5, "", 'R',1);
	//Saslto de Linea
	$pdf->Cell(5,0, "",'LR');
	//Sangria para el prox renglon
	$pdf->Cell(7);
	//Fecha Proxima Reunion
	$pdf->Cell(36,7,'FECHA PRÓXIMA REUNIÓN: ','L',0,'R',0);
	//Seccionamos la fecha de registro para obtener los diferentes datos para la generacion del PDF
	$anioPR =array();
	$cont=0;
	do{
		$anioPR[]=substr($datos['fecha_prox'],$cont,1);
		$cont++;
	}while($cont<4);
	$diaPR =array();
	$cont=8;
	do{
		$diaPR[]=substr($datos['fecha_prox'],$cont,1);
		$cont++;
	}while($cont<10);
	$mesPR =array();
	$cont=5;
	do{
		$mesPR[]=substr($datos['fecha_prox'],$cont,1);
		$cont++;
	}while($cont<7);
	//Dibujamos casilla por casilla la fecha indicada
	$pdf->Cell(4,5,$diaPR[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$diaPR[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$mesPR[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$mesPR[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPR[0],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPR[1],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPR[2],'LBR',0,'C',0);
	$pdf->Cell(4,5,$anioPR[3],'LBR',0,'C',0);
	$pdf->Cell(105,5, "", 'R',1);
	

	//Dibujamos los pies de las fechas 
	$pdf->Cell(12,5,"");
	$pdf->Cell(36,5,"","L");//Ponemos un espacio (sagria)
	$pdf->Cell(8,5,"DIA",'LR',0,'C',0);//dia de registro	
	$pdf->Cell(8,5,"MES",'LR',0,'C',0);//mes de registro
	$pdf->Cell(16,5,"AÑO",'LR',0,'C',0);//año de registro
	$pdf->Cell(105,5, "", 'R',1);	
	
	//Dibujamos firmas
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->Cell(12,5);
	$pdf->Cell(173,5,"","RL",1);
	$pdf->Cell(12,5);
	$pdf->Cell(173,5,"FIRMA DEL REPRESENTANTE DE TRABAJADORES DE RECIBIR, COPIA DE LA PRESENTE ACTA",'LR',1,'C',0);//dia de registro	
	
	//Dibujamos la linea para la firma
	$pdf->Cell(12,5,"","R",0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Cell(173,5,"","R",1);
	$pdf->SetFont('Arial','',7);//Tipo de Letra
	$pdf->Cell(12,5,"","R",0);
	$pdf->Cell(50,5,"","",0);
	$pdf->Cell(70,5,"NOMBRE Y FIRMA",'T',0,'C',0);	
	$pdf->Cell(41,5,"","",0);
	
	//Dibujamos firmas
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->Cell(12,5);
	$pdf->Cell(173,5,"","RL",1);
	$pdf->Cell(12,5);
	$pdf->Cell(173,5,"FIRMA DEL GERENTE GENERAL DE RECIBIDO  COPIA DE LA PRESENTE ACTA",'LR',1,'C',0);//dia de registro	
	
	//Dibujamos la linea para la firma
	$pdf->Cell(12,5,"","R",0);//Ancho, alto,texto, borde, salto de linea, alineacion, relleno
	$pdf->Cell(173,5,"","R",1);
	$pdf->SetFont('Arial','',7);//Tipo de Letra
	$pdf->Cell(12,5,"","R",0);
	$pdf->Cell(50,5,"","",0);
	$pdf->Cell(70,5,"NOMBRE Y FIRMA",'T',0,'C',0);	
	$pdf->Cell(53,5,"","R",1);
	$pdf->Cell(12,5);
	$pdf->Cell(173,5,"","LRB",0);
	
	
	/**************************************************************************************************************/
	/**************************************NORMAS******************************************************************/
	/**************************************************************************************************************/
	$pdf->AddPage();
	$pdf->Image('logo-clf.jpg',60,15,90);
	$pdf->SetFont('Arial','B',12);//Tipo de Letra
	$pdf->Ln();
	$pdf->Cell(11);
	$pdf->Cell(173,5,"NORMAS QUE APLICAN",0,1,'C',0);
	$pdf->Ln();
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->Cell(140,5,"FECHA ACTUALIZACIÓN",0,0,'R',0);
	$pdf->Cell(5);	
	$pdf->Cell(26,5,"7 DE MARZO DEL 2011","B",1,'C',0);	
	$pdf->Ln();
	$pdf->Cell(30,5,"RF SHMAT:",0,0,'R',0);
	$pdf->SetFont('Arial','',7);//Tipo de Letra
	$pdf->Cell(80,5,"Reglamento federal de seguridad, higiene y medio ambiente de trabajo.",0,1,'L',0);
	$pdf->SetFont('Arial','B',7);//Tipo de Letra
	$pdf->Cell(30,5,"RISH:",0,0,'R',0);
	$pdf->SetFont('Arial','',7);//Tipo de Letra
	$pdf->Cell(80,5,"Reglamentación interna de seguridad e higiene; instrucción de trabajo por escrito o procedimiento de seguridad establecido, Reglas de ",0,1,'L',0);
	$pdf->Cell(30,5,"",0,0,'R',0);
	$pdf->Cell(80,5,"Cero Tolerancia.",0,1,'L',0);
	
	$pdf->Cell(11);
	$pdf->MultiCell(173,5,
		"
		1.	NOM-001-STPS-1999, Edificios, locales, instalaciones y áreas de los centros de trabajo-Condiciones de seguridad e higiene.
		2.	NOM-002-STPS-2000, Condiciones de seguridad-Prevención, protección y combate de incendios en los centros de trabajo.
		3.	NOM-003-STPS-1999, Actividades agrícolas-Uso de insumos fitosanitarios plaguicidas e insumos de nutrición vegetal o fertilizantes-Condiciones 
				de Seguridad e Higiene. NO APLICA
		4.	NOM-004-STPS-1999, Sistemas de protección y dispositivos de seguridad de la maquinaria y equipo que se utilice en los centros de trabajo.
		5.	NOM-005-STPS-1998, Relativa a las condiciones de seguridad e higiene en los centros de trabajo para el manejo, transporte y almacenamiento de 
		 		sustancias químicas peligrosas.
		6.	NOM-006-STPS-2000, Manejo y almacenamiento de materiales-Condiciones y procedimientos de seguridad.
		7.  NOM-007-STPS-2000, Actividades agrícolas – Instalaciones, maquinaria, equipo y herramientas- NO APLICA
		8.	NOM-008-STPS-2001, Actividades de aprovechamiento forestal maderable y de aserraderos -Condiciones de seguridad e higiene. NO APLICA
		9.	NOM-009-STPS-1999, Equipo suspendido de acceso - Instalación, operación y mantenimiento- Condiciones de seguridad.
		10.	NOM-010-STPS-1999, Condiciones de seguridad e higiene en los centros de trabajo donde se manejen, transporten, procesen o almacenen 
				sustancias químicas capaces de generar contaminación en el medio ambiente laboral.
		11.	NOM-011-STPS-2001, Condiciones de seguridad e higiene en los centros de trabajo donde se genere ruido.
		12.	NOM-012-STPS-1999, Condiciones de seguridad e higiene en los centros de trabajo donde se produzcan, usen, manejen, almacenen o 
				transporten fuentes de radiaciones ionizantes. NO APLICA
		13.	NOM-013-STPS-1993, Relativa a las condiciones de seguridad e higiene en los centros de trabajo donde se generen radiaciones 
				electromagnéticas no ionizantes. NO APLICA
		14.	NOM-014-STPS-2000, Exposición laboral a presiones ambientales anormales- NO APLICA
		15.	NOM-015-STPS-2001, Condiciones térmicas elevadas o abatidas de- Condiciones de seguridad e higiene.
		16.	NOM-016-STPS-2001, Operación y mantenimiento de ferrocarriles- NO APLICA
		17.	NOM-017-STPS-2001, Equipo de protección personal-Selección, uso y manejo en los centros de trabajo.
		18.	NOM-018-STPS-2000, Sistema para la identificación y comunicación de peligros y riesgos por sustancias químicas peligrosas.
		19.	NOM-019-STPS-2004, Constitución, organización y funcionamiento de las comisiones de seguridad e higiene.
		20.	NOM-020-STPS-2002, recipientes sujetos a presión y calderas-Funcionamiento-Condiciones de seguridad. 
		21.	NOM-021-STPS-1993, Relativa a los requerimientos y características de los informes de los riesgos de trabajo que ocurran, para 
				integrar las estadísticas.
		22.	NOM-022-STPS-1999, Electricidad estática en los centros de trabajo-Condiciones de seguridad e higiene.
		23.	NOM-023-STPS-2003 Trabajos en Minas-Condiciones de seguridad y salud en el Trabajo.",0,'J',0);//Ancho, alto,texto, borde, alineacion, relleno
	
		$pdf->AddPage();
		$pdf->SetY(15);//Mueve la absicisa actual de regreso al margen izquierto y establece la ordenada
		$pdf->Cell(11);
		$pdf->MultiCell(173,5,
		" 
		24.	NOM-024-STPS-2001, Vibraciones-Condiciones de seguridad e higiene en los centros de trabajo.
		25.	NOM-025-STPS-1999, Condiciones de iluminación en los centros de trabajo.
		26.	NOM-026-STPS-1998 Colores y señales de seguridad e higiene, e identificación de riesgos por fluidos conducidos en tuberías.
		27.	NOM-027-STPS-2000, Soldadura y corte–Condiciones de seguridad e higiene.
		28.	NOM-028-STPS-2005, Organización del Trabajo-Seguridad en los Procesos de sustancias químicas.
		29.	NOM-029-STPS-2005, Mantenimiento de las instalaciones eléctricas en los centros de trabajo-Condiciones de seguridad.
		30.	NOM-030-STPS-2006. Servicios preventivos de Seguridad y Salud en el Trabajo
		31.	NOM-032-STPS-2008 Seguridad Minas de Carbón, NO APLICA",0,'J',0);//Ancho, alto,texto, borde, alineacion, relleno
	
	
	//Especificar Datos del Documento, propiedades del documento
	$pdf->SetAuthor("SEGURIDAD INDUSTRIAL");
	$pdf->SetTitle("ACTA ".$idActa);
	$pdf->SetCreator("SEGURIDAD INDUSTRIAL");
	$pdf->SetSubject("ACTA DE SEGURIDAD E HIGIENE NÚMERO ".$idActa);
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Registro de Acta de Seguridad e Higiene en el SISAD");
	$idActa.='.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($idActa,"F");
	header('Location: '.$idActa);
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