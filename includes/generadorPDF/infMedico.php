<?php

//Incluir los Archivos Requeridos para las Conexiones a la BD y la modificacion de Fechas, asi como la Creacion del PDF
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");


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
		$this->Cell(140,5,"INFORME MÉDICO INICIAL DE PROBABLE RIESGO DE TRABAJO",0,1,"C",0);
	    //Colocar un espacio de 10 mm de espacion entre el encabezado y el contenido del documento
	    $this->Ln(5);
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


	//Connect to database 
	$conn = conecta('bd_clinica');
	//Obtener el numero de la Orden de Trabajo para Servicios Externos y el Nombre del Departamento	
	$idInfMed = $_GET['id'];
	$sql="SELECT nom_empleado,id_empleados_empresa,bitacora_consultas_id_bit_consultas,edad,depto,informe_medico.area,informe_medico.lugar,informe_medico.puesto,antig_puesto,antig_empresa,
		fecha_rt,hora_rt,fecha_consulta,hora_consultada,padecimiento,des_accidente,diagnostico,informe_medico.tratamiento,auxiliares_diag,nom_supervisor,nom_facilitador,
		aviso_a,cond_ninguna,cond_intox,cond_rina,cond_sim,cond_ener,cond_lesion,manejo_aux,manejo_medico,manejo_imss,tras_amb,especifique_tras,cal_incidente,num_dias,
		informe_medico.observaciones,nom_res FROM informe_medico JOIN bitacora_consultas ON id_bit_consultas=bitacora_consultas_id_bit_consultas WHERE id_informe='$idInfMed'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	//Crear el PDF y dar las propiedades que tendrá el docuemntos
	$pdf = new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetDrawColor(0, 0, 255);//Definir Color de las lineas, rectangulos y bordes de celdas en color Azul
	$pdf->SetFillColor(217,217,217);//Definir el color del fondo de las celdas que lo lleven en gris claro
	$pdf->SetAutoPageBreak(true,10);//Indicar que cuando una celda exceda el margen inferior de 1cm(10mm), esta se dibuje en la siguiente pagina
	
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 8
	//RENGLONES DE 195 MM
	//Renglon 1
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(35,5,"1) NOMBRE COMPLETO ","LTB",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(160,5,$datos["nom_empleado"],"RTB",1,"L",0);
	//Renglon 2
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(35,5,"2) No. DE CONTROL ","LTB",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(45,5,$datos["id_empleados_empresa"],"TB",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(15,5,"EDAD ","BT",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(40,5,$datos["edad"],"BTR",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(60,5,"8) FECHA DE PROBABLE R.T.",1,1,"C",1);
	//Renglon 3
	$pdf->Cell(35,5,"3) DEPARTAMENTO ","LTB",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(100,5,$datos["depto"],"RTB",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(15,5,"DIA",1,0,"C",0);
	$pdf->Cell(15,5,"MES",1,0,"C",0);
	$pdf->Cell(15,5,"AÑO",1,0,"C",0);
	$pdf->Cell(15,5,"HORA",1,1,"C",0);
	//Renglon 4
	$pdf->Cell(12,5,"4) ÁREA ","LTB",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	if(strlen($datos["area"])>25)
		$pdf->SetFont('Arial','',6);//Colocar el texto en arial tamaño 6
	$pdf->Cell(45,5,$datos["area"],"TB",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	if(strlen($datos["area"])>25)
		$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 6
	$pdf->Cell(11,5,"LUGAR ","BT",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	if(strlen($datos["lugar"])>10)
		$pdf->SetFont('Arial','',6);//Colocar el texto en arial tamaño 6
	$pdf->Cell(67,5,$datos["lugar"],"TBR",0,"L",0);
	if(strlen($datos["lugar"])>10)
		$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 6
	//Dividir la Fecha
	$fechaRT=split("-",$datos["fecha_rt"]);
	$pdf->Cell(15,5,$fechaRT[2],1,0,"C",0);
	$pdf->Cell(15,5,$fechaRT[1],1,0,"C",0);
	$pdf->Cell(15,5,$fechaRT[0],1,0,"C",0);
	$pdf->Cell(15,5,substr($datos["hora_rt"],0,5),1,1,"C",0);
	//Renglon 4
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(35,5,"5) ACTIVIDAD ","LTB",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(100,5,$datos["puesto"],"RTB",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(60,5,"9) FECHA DE LA CONSULTA",1,1,"C",1);
	//Renglon 5
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(50,5,"6) ANTIGÜEDAD EN EL PUESTO ","LTB",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(85,5,$datos["antig_puesto"]." AÑOS","RTB",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(15,5,"DIA",1,0,"C",0);
	$pdf->Cell(15,5,"MES",1,0,"C",0);
	$pdf->Cell(15,5,"AÑO",1,0,"C",0);
	$pdf->Cell(15,5,"HORA",1,1,"C",0);
	//Renglon 6
	$pdf->Cell(50,5,"7) ANTIGÜEDAD EN LA EMPRESA ","LTB",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(85,5,$datos["antig_empresa"]." AÑOS","RTB",0,"L",0);
	//Dividir la Fecha
	$fechaCons=split("-",$datos["fecha_consulta"]);
	$pdf->Cell(15,5,$fechaCons[2],1,0,"C",0);
	$pdf->Cell(15,5,$fechaCons[1],1,0,"C",0);
	$pdf->Cell(15,5,$fechaCons[0],1,0,"C",0);
	$pdf->Cell(15,5,substr($datos["hora_consultada"],0,5),1,1,"C",0);
	//Punto 10
	$pdf->Ln(1);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(195,5,"10) MECANISMO DEL ACCIDENTE O PADECIMIENTO",1,1,"C",1);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->MultiCell(195,5,$datos["padecimiento"],1,"J",0);
	//Punto 11
	$pdf->Ln(1);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(195,5,"11) DESCRIPCIÓN DE LA(S) LESION(ES)",1,1,"C",1);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->MultiCell(195,5,$datos["des_accidente"],1,"J",0);
	//Punto 12
	$pdf->Ln(1);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(195,5,"12) DIAGNÓSTICO(S)",1,1,"C",1);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->MultiCell(195,5,$datos["diagnostico"],1,"J",0);
	//Punto 13
	$pdf->Ln(1);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(195,5,"13) TRATAMIENTOS",1,1,"C",1);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->MultiCell(195,5,$datos["tratamiento"],1,"J",0);
	//Punto 14
	$pdf->Ln(1);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(195,5,"14) AUXILIARES DIAGNÓSTICOS UTILIZADOS",1,1,"C",1);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->MultiCell(195,5,$datos["auxiliares_diag"],1,"J",0);
	//Renglon Titulo del punto 15,16,17
	$pdf->Ln(1);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(65,5,"15) NOMBRE DEL SUPERVISOR",1,0,"C",1);
	$pdf->Cell(65,5,"16) NOMBRE DEL FACILITADOR",1,0,"C",1);
	$pdf->Cell(65,5,"17) AVISO A:",1,1,"C",1);
	//Renglon Respuesta del punto 15,16,17
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(65,5,$datos["nom_supervisor"],1,0,"C",0);
	$pdf->Cell(65,5,$datos["nom_facilitador"],1,0,"C",0);
	$pdf->Cell(65,5,$datos["aviso_a"],1,1,"C",0);
	//Renglon Titulo del punto 18
	$pdf->Ln(1);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(195,5,"18) OTRAS CONDICIONES",1,1,"C",1);
	//Renglones con el Check
	$posY=$pdf->GetY()+0.5;
	$posX=$pdf->GetX()+1;
	//Si los valores para las condiciones son 0, no mostrarlas checadas de lo contrario, mostrarlas checadas
	if($datos["cond_ninguna"]==0)
		$pdf->Image('check1.jpg',$posX,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',$posX,$posY,4,3.5);
	$pdf->Cell(5,5,"","L",0,"C",0);
	$pdf->Cell(54,5,"NINGUNA",0,0,"L",0);
	if($datos["cond_sim"]==0)
		$pdf->Image('check1.jpg',71,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',71,$posY,4,3.5);
	$pdf->Cell(5,5,"",0,0,"C",0);
	$pdf->Cell(54,5,"EXISTE SIMULACIÓN",0,0,"L",0);
	if($datos["cond_lesion"]==0)
		$pdf->Image('check1.jpg',130,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',130,$posY,4,3.5);
	$pdf->Cell(5,5,"",0,0,"C",0);
	$pdf->Cell(72,5,"SE PROVOCÓ LAS LESIONES INTENCIONALMENTE","R",1,"L",0);
	//2 Renglon con el Check
	$posY=$pdf->GetY()+0.5;
	$posX=$pdf->GetX()+1;
	if($datos["cond_intox"]==0)
		$pdf->Image('check1.jpg',$posX,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',$posX,$posY,4,3.5);
	$pdf->Cell(5,5,"","LB",0,"C",0);
	$pdf->Cell(54,5,"INTOXICACIÓN ALCOHÓLICA","B",0,"L",0);
	if($datos["cond_ener"]==0)
		$pdf->Image('check1.jpg',71,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',71,$posY,4,3.5);
	$pdf->Cell(5,5,"","B",0,"C",0);
	$pdf->Cell(54,5,"INTOXICACIÓN POR ENERVANTES","B",0,"L",0);
	if($datos["cond_rina"]==0)
		$pdf->Image('check1.jpg',130,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',130,$posY,4,3.5);
	$pdf->Cell(5,5,"","B",0,"C",0);
	$pdf->Cell(72,5,"HUBO RIÑA","RB",1,"L",0);
	//Renglon titulo del punto 19 y 20
	$pdf->Ln(1);
	$pdf->Cell(97.5,5,"19) MANEJO",1,0,"C",1);
	$pdf->Cell(97.5,5,"20) TRASLADO EN AMBULANCIA",1,1,"C",1);
	//1 RENGLON CHECK DEL PUNTO 19
	$posY=$pdf->GetY()+0.5;
	$posX=$pdf->GetX()+1;
	if($datos["manejo_aux"]==0)
		$pdf->Image('check1.jpg',$posX,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',$posX,$posY,4,3.5);
	$pdf->Cell(5,5,"","LB",0,"C",0);
	$pdf->Cell(30,5,"PRIMEROS AUXILIOS","B",0,"L",0);
	if($datos["manejo_medico"]==0)
		$pdf->Image('check1.jpg',46,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',46,$posY,4,3.5);
	$pdf->Cell(5,5,"","B",0,"C",0);
	$pdf->Cell(26,5,"MANEJO MÉDICO","B",0,"L",0);
	if($datos["manejo_imss"]==0)
		$pdf->Image('check1.jpg',77,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',77,$posY,4,3.5);
	$pdf->Cell(5,5,"","B",0,"C",0);
	$pdf->Cell(26.5,5,"ENVÍO IMSS","BR",0,"L",0);
	//1 RENGLON CHECK DEL PUNTO 20
	if($datos["tras_amb"]=="NO")
		$pdf->Image('check1.jpg',109,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',109,$posY,4,3.5);
	$pdf->Cell(5,5,"","B",0,"C",0);
	$pdf->Cell(5,5,"SI","B",0,"L",0);
	if($datos["tras_amb"]=="SI")
		$pdf->Image('check1.jpg',118,$posY,4,3.5);
	else
		$pdf->Image('check2.jpg',118,$posY,4,3.5);
	$pdf->Cell(5,5,"","B",0,"C",0);
	$pdf->Cell(5,5,"NO","B",0,"L",0);
	$pdf->Cell(3,5,"","B",0,"C",0);
	$pdf->Cell(22.5,5,"ESPECIFIQUE:","B",0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(52,5,$datos["especifique_tras"],"BR",1,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	//Renglon titulo del punto 21 y 22
	$pdf->Ln(1);
	$pdf->Cell(97.5,5,"21) CALIFICACIÓN",1,0,"C",1);
	$pdf->Cell(97.5,5,"22) NÚMERO DE DÍAS",1,1,"C",1);
	//Renglon con los Tipos de Incidente
	$pdf->Cell(17.5,5,"INCIDENTE",1,0,"C",0);
	$posY=$pdf->GetY()+0.5;
	switch($datos["cal_incidente"]){
		case "A":
			$pdf->Image('ok.jpg',37,$posY,4,4);
		break;
		case "B":
			$pdf->Image('ok.jpg',51,$posY,4,4);
		break;
		case "C":
			$pdf->Image('ok.jpg',64,$posY,4,4);
		break;
		case "D":
			$pdf->Image('ok.jpg',77,$posY,4,4);
		break;
		case "E":
			$pdf->Image('ok.jpg',90,$posY,4,4);
		break;
		case "F":
			$pdf->Image('ok.jpg',103,$posY,4,4);
		break;
	}
	$pdf->Cell(14,5,"A",1,0,"C",0);
	$pdf->Cell(14,5,"B",1,0,"C",0);
	$pdf->Cell(13,5,"C",1,0,"C",0);
	$pdf->Cell(13,5,"D",1,0,"C",0);
	$pdf->Cell(13,5,"E",1,0,"C",0);
	$pdf->Cell(13,5,"F",1,0,"C",0);
	$pdf->Cell(97.5,5,$datos["num_dias"],1,1,"C",0);
	//Renglon titulo del punto 21 y 22
	$pdf->Ln(1);
	$pdf->Cell(195,5,"23) OBSERVACIONES",1,1,"C",1);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->MultiCell(195,5,$datos["observaciones"],1,"J",0);
	//Renglon de la Firma
	$pdf->Ln(10);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(60,5,"EL PACIENTE FUE ATENDIDO POR: ",0,0,"R",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(100,5,$datos["nom_res"],"B",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	
	/***************************************************** PROPIEDADES DEL DOCUMENTO PDF *****************************************************************/
	$pdf->SetAuthor("UNIDAD DE SALUD OCUPACIONAL");
	$pdf->SetTitle("INFORME MÉDICO  ".$idInfMed);
	$pdf->SetCreator("UNIDAD DE SALUD OCUPACIONAL");
	$pdf->SetSubject("Informe Médico");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Informe Médico ".$idInfMed." en el SISAD");
	$idInfMed.='.pdf';

	//Mandar imprimir el PDF en el mismo directorio donde se encuentra este archivo de 'ordenServicioExternbo.php'
	$pdf->Output($idInfMed,"F");
	//Direccionar al PDF recien creado en el directorio
	header('Location: '.$idInfMed);
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