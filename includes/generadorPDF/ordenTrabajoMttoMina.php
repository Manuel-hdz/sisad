<?php

//Incluir los Archivos Requeridos para las Conexiones a la BD y la modificacion de Fechas, asi como la Creacion del PDF
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");


//Declaración de la Clase
class PDF extends FPDF{

	function Header(){		
		//Logo
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
		$this->SetFont('Arial','B',10);
		$this->Cell(70,50,'ÓRDEN DE TRABAJO',0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
		$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
	    //Line break
	    $this->Ln(40);
		parent::Header();
	}//Cierre Header()

	//Page footer
	function Footer(){	
		$this->SetTextColor(51, 51, 153);	
		//Position at 1.5 cm from bottom
	    $this->SetY(-20);
	    //Arial italic 8
	    $this->SetFont('Arial','',7);
		//$this->Cell(0,15,'       Fecha Emisión:                                               No. de Revisión:                                               Fecha de Revisión:',0,0,'L');
		$this->Cell(0,15,'',0,0,'L');
	    //Numero de Pagina
		$this->Cell(-20,15,'Página '.$this->PageNo().' de {nb}',0,0,'C');
		$this->SetY(-17);
		//$this->Cell(0,15,'            Abr - 09'.'                                                                '.'01'.'                                                                 '.'   Abr - 09',0,0,'L');
		$this->Cell(0,15,'',0,0,'L');
		$this->SetY(-20);
		//$this->Cell(0,25,'F. 7.4.0 - 02 / Rev. 01',0,0,'R');
		$this->Cell(0,25,'',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}

}//Cierre de la clase PDF	


	//Connect to database 
	$conn = conecta('bd_mantenimiento');
	//Obtener el numero de la Orden de Trabajo para Servicios de Mtto Mina
	$idOrdenTrabajo = $_GET['id'];
	//Obtener la Bitacora Asociada
	$idBitacora=obtenerDato("bitacora_mtto", "id_bitacora", "orden_trabajo_id_orden_trabajo", $idOrdenTrabajo);
	//Obtener el Equipo al que se le dara el servicio
	$idEquipo=obtenerDato("bitacora_mtto", "equipos_id_equipo", "id_bitacora", $idBitacora);
	//Calcular la duracion del servicio
	$duracion=calcularDuracionServicio($idOrdenTrabajo);
	//Obtener el nombre del supervisor
	$supervisor=obtenerDato("orden_trabajo","supervisor","id_orden_trabajo",$idOrdenTrabajo);
	//Nombre de Quien Genera la órden de Trabajo
	$genero=obtenerDato("orden_trabajo","generador","id_orden_trabajo",$idOrdenTrabajo);
	//Nombre de Quien Revisa la Orden de Trabajo
	$revisor=obtenerDato("orden_trabajo","revisor","id_orden_trabajo",$idOrdenTrabajo);
	//Sentencia SQL para extraer los datos de la orden de Trabajo
	$sql="SELECT fecha_creacion,fecha_prog,turno,horometro,odometro,operador_equipo,comentarios,autorizo_ot FROM orden_trabajo WHERE id_orden_trabajo='$idOrdenTrabajo'";
	$rs=mysql_query($sql);
	$datos=mysql_fetch_array($rs);
	//Obtener los datos de forma manejable
	$fechaCreacion=modFecha($datos["fecha_creacion"],1);
	$fechaProg=modFecha($datos["fecha_prog"],1);
	//Crear el PDF y dar las propiedades que tendrá el docuemntos
	$pdf = new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetDrawColor(0, 0, 255);//Definir Color de las lineas, rectangulos y bordes de celdas en color Azul
	$pdf->SetAutoPageBreak(true,30);//Indicar que cuando una celda exceda el margen inferior de 3 cm(30mm), esta se dibuje en la siguiente pagina
	$pdf->SetFillColor(217,217,217);//Definir el color del fondo de las celdas que lo lleven en gris claro
	$pdf->SetAutoPageBreak(true,10);//Indicar que cuando una celda exceda el margen inferior de 1cm(10mm), esta se dibuje en la siguiente pagina
	
	$pdf->SetFont('Arial','B',10);//Colocar el texto en arial tamaño 8
	//RENGLONES DE 190 MM
	//Renglon Título
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(160,5,"Número de Órden de Trabajo",0,0,"R",0);
	$pdf->SetTextColor(128, 0, 0);
	$pdf->Cell(30,5,$idOrdenTrabajo,"B",1,"R",0);
	$pdf->Ln();
	//Renglon 1
	$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 8
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(20,5,"Responsable: ",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(90,5,$supervisor,"B",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(30,5,"Duración Aproximada: ",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(50,5,$duracion,"B",1,"L",0);
	//Renglon 2
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(20,5,"Generó: ",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(90,5,$genero,"B",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(30,5,"Fecha Creación: ",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(50,5,$fechaCreacion,"B",1,"L",0);
	//Renglon 3
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(20,5,"Revisó: ",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(90,5,$revisor,"B",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(30,5,"Fecha Programada: ",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(50,5,$fechaProg,"B",1,"L",0);
	//Renglon 4
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(20,5,"Autorizó: ",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(90,5,$datos["autorizo_ot"],"B",0,"L",0);
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(30,5,"Equipo: ",0,0,"L",0);
	$pdf->SetTextColor(0, 0, 0);//Color del Texto de la "respuesta"
	$pdf->Cell(50,5,$idEquipo,"B",1,"L",0);
	$pdf->Ln();
	//Obtener las actividades por Gama de la Orden de Trabajo
		$sql="SELECT gama_id_gama FROM actividades_ot WHERE orden_trabajo_id_orden_trabajo='$idOrdenTrabajo'";
		$rs=mysql_query($sql);
		if($datosActividades=mysql_fetch_array($rs)){
			$pdf->SetTextColor(51, 51, 153);//Color de Datos del documento
			$pdf->SetFont('Arial','IU',8);//Colocar el texto en arial tamaño 8
			$pdf->Cell(190,5,"ACTIVIDADES DEL SERVICIO DE ".$datosActividades["gama_id_gama"],0,1,"C",0);
			$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 8
			$servicio=$datosActividades["gama_id_gama"];
			do{
				if($servicio!=$datosActividades["gama_id_gama"]){
					$servicio=$datosActividades["gama_id_gama"];
					$pdf->Ln();
					$pdf->SetFont('Arial','IU',8);//Colocar el texto en arial tamaño 8
					$pdf->SetTextColor(51, 51, 153);//Color de Datos del documento
					//Obtener la posicion de Y
					$posYLN=$pdf->GetY()+0.5;
					//Si la posicion es mayor a 240, Agregar una nueva Pagina
					if($posYLN>220){
						$pdf->AddPage('P');
						//Renglon Título
						$pdf->SetFont('Arial','B',10);//Colocar el texto en arial tamaño 8
						$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
						$pdf->Cell(160,5,"Número de Órden de Trabajo",0,0,"R",0);
						$pdf->SetTextColor(128, 0, 0);
						$pdf->Cell(30,5,$idOrdenTrabajo,"B",1,"R",0);
						$pdf->Ln();
						$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 8
					}
					$pdf->Cell(190,5,"ACTIVIDADES DEL SERVICIO DE ".$datosActividades["gama_id_gama"],0,1,"C",0);
					$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 8
				}
				$sql="SELECT tiempo_aprox,descripcion FROM gama_actividades JOIN actividades ON id_actividad=actividades_id_actividad WHERE gama_id_gama='$datosActividades[gama_id_gama]'";
				$rsAct=mysql_query($sql);
				if($datosAct=mysql_fetch_array($rsAct)){
					do{
						//Obtener la posicion de Y
						$posYLN=$pdf->GetY()+0.5;
						//Si la posicion es mayor a 240, Agregar una nueva Pagina
						if($posYLN>220){
							$pdf->AddPage('P');
							//Renglon Título
							$pdf->SetFont('Arial','B',10);//Colocar el texto en arial tamaño 8
							$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
							$pdf->Cell(160,5,"Número de Órden de Trabajo",0,0,"R",0);
							$pdf->SetTextColor(128, 0, 0);
							$pdf->Cell(30,5,$idOrdenTrabajo,"B",1,"R",0);
							$pdf->Ln();
							$pdf->SetFont('Arial','',8);//Colocar el texto en arial tamaño 8
						}
						$pdf->SetTextColor(0, 0, 0);//Color de datos de la BD
						$pdf->MultiCell(190,5,$datosAct["descripcion"],0,"J",0);
						//Datos del servicio
						$pdf->SetTextColor(51, 51, 153);//Color de Datos del documento
						$pdf->Cell(30,5,"Frecuencia: ",0,0,"L",0);
						$pdf->SetTextColor(0, 0, 0);//Color de datos de la BD
						$pdf->Cell(60,5,$datosActividades["gama_id_gama"],0,0,"L",0);
						$pdf->Cell(10,5,"",0,0,"L",0);
						$pdf->SetTextColor(51, 51, 153);//Color de Datos del documento
						$pdf->Cell(20,5,"Prioridad: ",0,0,"L",0);
						$pdf->SetTextColor(0, 0, 0);//Color de datos de la BD
						$pdf->Cell(70,5,"Alta",0,1,"L",0);
						
						$pdf->SetTextColor(51, 51, 153);//Color de Datos del documento
						$pdf->Cell(30,5,"Duración Aproximada: ",0,0,"L",0);
						$pdf->SetTextColor(0, 0, 0);//Color de datos de la BD
						$pdf->Cell(60,5,substr($datosAct["tiempo_aprox"],0,2)." H ".substr($datosAct["tiempo_aprox"],3,2)." M",0,0,"L",0);
						$pdf->Cell(10,5,"",0,0,"L",0);
						$pdf->SetTextColor(51, 51, 153);//Color de Datos del documento
						$pdf->Cell(20,5,"Clasificación: ",0,0,"L",0);
						$pdf->SetTextColor(0, 0, 0);//Color de datos de la BD
						$pdf->Cell(70,5,"Preventivo",0,1,"L",0);
						
						//Renglones con el Check
						$posY=$pdf->GetY()+0.5;
						$posX=$pdf->GetX()+31;
						$pdf->Image('check1.jpg',$posX,$posY,4,3.5);
						
						$pdf->SetTextColor(51, 51, 153);//Color de Datos del documento
						$pdf->Cell(30,5,"Realizado:",0,0,"L",0);
						$pdf->Cell(60,5,"",0,0,"L",0);
						$pdf->Cell(10,5,"",0,0,"L",0);
						$pdf->Cell(30,5,"Fecha Realización: ",0,0,"L",0);
						$pdf->Cell(60,5,"   /            /   ",0,1,"L",0);
						//
						$cont=0;
						$pdf->Cell(20,5,"Comentarios",0,0,"C",0);
						$pdf->SetTextColor(0, 0, 0);//Color de datos de la BD
						$pdf->Cell(170,5,"","B",1,"L",0);
						do{
							$pdf->Cell(190,5,"","B",1,"L",0);
							$cont++;
						}while($cont<2);
					}while($datosAct=mysql_fetch_array($rsAct));
				}
			}while($datosActividades=mysql_fetch_array($rs));
		}
	//Fin del dibujado de Gamas
	$pdf->Ln();
	//Agregar Pagina para ingresar datos de los mecánicos y de los servicios externos realizados
	$pdf->AddPage('P');
	//Renglon Título
	$pdf->SetFont('Arial','B',10);//Colocar el texto en arial tamaño 8
	$pdf->SetTextColor(51, 51, 153);//Color de Texto para Informacion del documento
	$pdf->Cell(160,5,"Número de Órden de Trabajo",0,0,"R",0);
	$pdf->SetTextColor(128, 0, 0);
	$pdf->Cell(30,5,$idOrdenTrabajo,"B",1,"R",0);
	$pdf->Ln();
	$pdf->SetTextColor(51, 51, 153);//Color de Datos del documento
	$pdf->SetFont('Arial','',10);//Colocar el texto en arial tamaño 10
	//Registro de Horómetros
	$pdf->Cell(190,5,"Registro de Lecturas (Hrs)","B",1,"L",0);
	$cont=1;
	do{
		if($cont<=2){
			$pdf->Cell(190,3,"","LR",1,"C",0);
			$pdf->Cell(14,5,"Lectura: ","L",0,"L",0);
			$pdf->Cell(16,5,"","B",0,"C",0);
			$pdf->Cell(12,5,"Fecha: ",0,0,"L",0);
			$pdf->Cell(20,5,"     /     /     ","B",0,"C",0);
			$pdf->Cell(2,5,"",0,0,"C",0);
			
			$pdf->Cell(14,5,"Lectura: ",0,0,"L",0);
			$pdf->Cell(16,5,"","B",0,"C",0);
			$pdf->Cell(12,5,"Fecha: ",0,0,"L",0);
			$pdf->Cell(20,5,"     /     /     ","B",0,"C",0);
			$pdf->Cell(2,5,"",0,0,"C",0);
			
			$pdf->Cell(14,5,"Lectura: ",0,0,"L",0);
			$pdf->Cell(16,5,"","B",0,"C",0);
			$pdf->Cell(12,5,"Fecha: ",0,0,"L",0);
			$pdf->Cell(20,5,"     /     /     ","BR",1,"C",0);
		}
		else{
			$pdf->Cell(190,3,"","LR",1,"C",0);
			$pdf->Cell(14,5,"Lectura: ","L",0,"L",0);
			$pdf->Cell(16,5,"","B",0,"C",0);
			$pdf->Cell(12,5,"Fecha: ",0,0,"L",0);
			$pdf->Cell(20,5,"     /     /     ","B",0,"C",0);
			$pdf->Cell(2,5,"",0,0,"C",0);
			
			$pdf->Cell(14,5,"Lectura: ",0,0,"L",0);
			$pdf->Cell(16,5,"","B",0,"C",0);
			$pdf->Cell(12,5,"Fecha: ",0,0,"L",0);
			$pdf->Cell(20,5,"     /     /     ","B",0,"C",0);
			$pdf->Cell(2,5,"",0,0,"C",0);
			
			$pdf->Cell(14,5,"Lectura: ",0,0,"L",0);
			$pdf->Cell(16,5,"","B",0,"C",0);
			$pdf->Cell(12,5,"Fecha: ",0,0,"L",0);
			$pdf->Cell(20,5,"     /     /     ","BR",1,"C",0);
			$pdf->Cell(190,3,"","LRB",1,"C",0);
		}
		$cont++;
	}while($cont<=3);
	$pdf->Cell(190,2,"","",1,"C",0);
	//Ciclo para dibujar varias veces el espacio para poner a los mecánicos
	$cont=1;
	$pdf->Cell(190,5,"Mano de Obra","B",1,"L",0);
	do{
		$pdf->Cell(190,3,"","LR",1,"C",0);
		$pdf->Cell(8,5,$cont.".-","L",0,"L",0);
		$pdf->Cell(18,5,"Nombre: ",0,0,"L",0);
		$pdf->Cell(102,5,"","B",0,"L",0);
		$pdf->Cell(12,5,"Fecha: ",0,0,"L",0);
		$pdf->Cell(20,5,"     /     /     ","B",0,"C",0);
		$pdf->Cell(10,5,"Hora: ",0,0,"L",0);
		$pdf->Cell(20,5,"      :         ","BR",1,"C",0);
		if($cont<=4)
			$pdf->Cell(190,3,"","LR",1,"C",0);
		else
			$pdf->Cell(190,5,"","LRB",1,"C",0);
		$cont++;
	}while($cont<=5);
	$pdf->Cell(190,2,"","",1,"C",0);
	//Ciclo para dibujar varias veces el espacio para poner a los servicios externos
	$cont=1;
	$pdf->Cell(190,5,"Servicios Externos","B",1,"L",0);
	do{
		$pdf->Cell(190,3,"","LR",1,"C",0);
		$pdf->Cell(8,5,$cont.".-","L",0,"L",0);
		$pdf->Cell(20,5,"Compañía: ",0,0,"L",0);
		$pdf->Cell(162,5,"","BR",1,"L",0);
		
		$pdf->Cell(190,3,"","LR",1,"C",0);
		$pdf->Cell(15,5,"Servicio: ","L",0,"L",0);
		$pdf->Cell(107,5,"","B",0,"L",0);
		$pdf->Cell(12,5,"Fecha: ",0,0,"L",0);
		$pdf->Cell(20,5,"     /     /     ","B",0,"C",0);
		$pdf->Cell(16,5,"Cantidad ",0,0,"L",0);
		$pdf->Cell(20,5,"","RB",1,"C",0);
		if($cont<=4)
			$pdf->Cell(190,3,"","LR",1,"C",0);
		else
			$pdf->Cell(190,5,"","LRB",1,"C",0);
		$cont++;
	}while($cont<=5);
	
	/***************************************************** PROPIEDADES DEL DOCUMENTO PDF *****************************************************************/
	$pdf->SetAuthor("Mantenimiento Mina");
	$pdf->SetTitle("ÓRDEN DE TRABAJO: ".$idOrdenTrabajo);
	$pdf->SetCreator("MANTENIMIENTO MINA");
	$pdf->SetSubject("ÓRDEN DE TRABAJO");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir de la Órden de Trabajo ".$idOrdenTrabajo." en el SISAD");
	$idOrdenTrabajo.='.pdf';

	//Mandar imprimir el PDF en el mismo directorio donde se encuentra este archivo de 'ordenServicioExternbo.php'
	$pdf->Output($idOrdenTrabajo,"F");
	//Direccionar al PDF recien creado en el directorio
	header('Location: '.$idOrdenTrabajo);
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
	
	function calcularDuracionServicio($idOrdenTrabajo){
		$sql="SELECT gama_id_gama FROM actividades_ot WHERE orden_trabajo_id_orden_trabajo='$idOrdenTrabajo'";
		$rs=mysql_query($sql);
		$duracion="";
		$horas=0;
		$minutos=0;
		if($datos=mysql_fetch_array($rs)){
			do{
				$sql="SELECT tiempo_aprox FROM gama_actividades WHERE gama_id_gama='$datos[gama_id_gama]'";
				$rsAct=mysql_query($sql);
				if($datosAct=mysql_fetch_array($rsAct)){
					do{
						$horas+=intval(substr($datosAct["tiempo_aprox"],0,2));
						$minutos+=intval(substr($datosAct["tiempo_aprox"],3,2));
					}while($datosAct=mysql_fetch_array($rsAct));
				}
			}while($datos=mysql_fetch_array($rs));
		}
		if($minutos>60){
			$horas+=intval($minutos/60);
			$minutos=$minutos%60;
		}
		if($horas>0)
			$duracion = $horas." hrs ".$minutos." min";
		else
			$duracion = $minutos." min";
		return $duracion;
	}
?>