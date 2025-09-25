<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
include("../op_operacionesBD.php");

class PDF extends FPDF{

	function Header(){
		//Logo
	    $this->Image('logo-clf.jpg',12,6,30);
	    //Arial bold 15
	    $this->SetFont('Arial','B',7.5);
		$this->SetTextColor(0, 0, 255);
	    //Move to the right
	    $this->Cell(60);
	    //Title
	    $this->Cell(140,25,'CONFIDENCIAL, PROPIEDAD DE “CONCRETO LANZADO DE FRESNILLO, S.A DE C.V.” PROHIBIDA SU REPRODUCCIÓN TOTAL O PARCIAL.',0,0,'C');
		$this->SetTextColor(78, 97, 40);
		$this->SetFont('Arial','B',20);
		$this->Cell(-140,14,'_________________________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);
		$this->SetFont('Arial','B',10);
		//$this->Cell(70,50,'F. 7.4.0 - 01 REQUISICIÓN DE COMPRAS',0,0,'C');
		$this->Cell(140,50,'KARDEX',0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-140,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
		$this->Cell(140,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(58,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
	    //Line break
	    $this->Ln(45);
		parent::Header();
	}

	//Page footer
	function Footer(){
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
		$this->Cell(0,5,'_____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'_____________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}

}//Cierre de la clase PDF	

	//Crear el Objeto PDF y Agregar las Caracteristicas Iniciales
	$pdf=new PDF('L','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	
	//Recuperar las variables que vienen como parametros en el GET
	$anio=$_GET["anio"];
	$nombre=$_GET["nombre"];
	$fecha=date("d/m/Y");
	
	//Obtener la clave de empleado
	$clave=obtenerDatoEmpleadoPorNombre("id_empleados_empresa",$nombre);
	//Obtener el Puesto
	$puesto=obtenerDatoEmpleadoPorNombre("puesto",$nombre);
	//Obtener el área
	$area=obtenerDatoEmpleadoPorNombre("area",$nombre);
	//Obtener el RFC
	$rfc=obtenerDatoEmpleadoPorNombre("rfc_empleado",$nombre);
	/**************************************************************************************************************/
	/**********************************DATOS GENERALES DE LA REQUISICION*******************************************/
	/**************************************************************************************************************/
	//Definir los datos que se encuentran sobre la tabla y antes del encabezado
	$pdf->SetFont('Arial','',11);//Tipo de Letra
	$pdf->SetTextColor(51, 51, 153);//Color del Texto
	$pdf->SetDrawColor(0, 0, 255);//Color de los Bordes
	$pdf->Cell(210,6,'',0,0);//Espacio Vacio para Sangria
	$pdf->Cell(18,6,'Ejercicio:',0,0,'R');//Etiqueta Ejercicio
	$pdf->SetFont('Arial','B',11);//Para poner la linea y el Año en Negritas
	$pdf->Cell(20,6,$anio,'B',1,'R');//Año del Ejercicio

	$pdf->SetFont('Arial','',11);//Quitar la Letra Negrita
	$pdf->Cell(210,6,'',0,0);//Espacio Vacio para Sangria
	$pdf->Cell(18,6,'Fecha:',0,0,'R');//Etiqueta Fecha
	$pdf->SetFont('Arial','B',11);//Para poner la linea y el Año en Negritas
	$pdf->Cell(20,6,$fecha,'B',1,'R');//Fecha de Realizacion del Documento
	
	$pdf->Ln();//Salto de Linea

	//Seccion del Encabezado con las etiquetas y datos del Trabajador
	$pdf->SetFont('Arial','',11);//Para quitar la propiedad Negritas de los encabezados
	$pdf->Cell(20,6,'CLAVE:',0,0,'R');//Etiqueta de la Clave en el encabezado
	$pdf->Cell(100,6,$clave,'B',0,'L');//Clave del Empleado
	$pdf->Cell(20,6,'NOMBRE:',0,0,'R');//Etiqueta de la Clave en el encabezado
	$pdf->Cell(100,6,$nombre,'B',1,'L');//Clave del Empleado
	
	$pdf->Cell(20,6,'ÁREA:',0,0,'R');//Etiqueta de la Clave en el encabezado
	$pdf->Cell(100,6,$area,'B',0,'L');//Clave del Empleado
	$pdf->Cell(20,6,'PUESTO:',0,0,'R');//Etiqueta de la Clave en el encabezado
	$pdf->Cell(100,6,$puesto,'B',1,'L');//Clave del Empleado

	$pdf->Ln();//Salto de Linea
	
	//Para dibujar el encabezado de la Tabla
	//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
	$pdf->SetFillColor(217,217,217);
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->SetFont('Arial','B',12);
	//Colocar los Nombres de las columnas y el ancho de cada columna
	$pdf->Cell(25,10,'MESES','LTRB',0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(235,5,'DIAS','LTRB',1,'C',1);//Titulo DIAS en la columna
	$pdf->Cell(25,5,'',0,0);//Sangria para empezar a partir de la columna Dias en lugar de la de Meses
	
	$pdf->SetFont('Arial','B',10);//Estilo de Fuente mas pequeño y en Negritas para colocar los Dias
	$cont=1;//Variable para dibujar y controlar la cantidad de Dias
	do{
		$pdf->Cell(7.58,5,$cont,'LTRB',0,'C',1);//Columnas con los Dias
		$cont++;
	}while($cont<=31);
	
	/****************************************************/
	$pdf->Ln();//Salto de Linea
	//Declarar la variable mes vacia, esta variable contendra el nombre de Cada mes
	$mes="";
	//Reiniciar esta variable para obtener el nombre de cada mes en un ciclo
	$cont=1;
	$pdf->SetFont('Arial','',8);//Estilo de Fuente mas pequeño y en Negritas para colocar los Dias
	//Ciclo que dibuja cada renglon con los meses y en su momento el respectivo estado
	do{
		$mes=obtenerNombreMes($cont);
		
		//Redeclarar el color de llenado de la columna con los Meses de forma que no interfiera con el relleno de las incidencias de Dias
		$pdf->SetFillColor(217,217,217);
		//Dibujar Renglon con el nombre de Mes
		$pdf->Cell(25,5,$mes,'LTRB',0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		
		//Comprobar el Color de Relleno del renglon
		if($cont%2==0)
			//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
			$pdf->SetFillColor(255,255,255);
		else
			//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
			$pdf->SetFillColor(182,221,232);
				
		//Si el valor de $cont es menor a 10, concatenarle un 0 a la izquierda, de forma que podamos realizar la comparacion con los datos de la Fecha de Checada
		//por ejemplo 01,02,03,...,09, a partir del 10, el numero queda tal cual esta
		if ($cont<10)
			$mesFecha="0".$cont;
		else
			$mesFecha=$cont;
		
		$conn=conecta("bd_recursos");
		//Obtener los dias del Mes segun el registro seleccionado
		//p.e. Enero->31,Febrero->29(si es bisiesto),...Diciembre->31
		$diasMes=diasMes($mesFecha,$anio);
		//Preparar la sentencia SQL por cada mes dejando una consulta de la siguiente manera
		//p.e. SELECT estado,fecha_checada,hora_checada FROM checadas WHERE empleados_rfc_empleado='$trabajador' AND fecha_checada BETWEEN '2012-01-01' AND '2012-01-31'
		$stm_sql="SELECT estado,fecha_checada,hora_checada FROM checadas WHERE empleados_rfc_empleado='$rfc' AND fecha_checada BETWEEN '".$anio."-".$mesFecha."-01' AND '".$anio."-".$mesFecha."-".$diasMes."' AND estado!='SALIDA' ORDER BY fecha_checada,hora_checada";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		//Si la consulta regresa resultados, verificarlos
		if ($datos=mysql_fetch_array($rs)){
			//Obtener el dia de la primer Checada encontrada
			$mesCda=substr($datos["fecha_checada"],5,2);
			//Si el mes de la Fecha es igual al Mes de la checada, verificar datos
			if ($mesFecha==$mesCda){
				//Variable que representa cada dia en las columnas de meses
				$diasCols=1;
				do{
					//Obtener el Dia en formato XX
					//p.e. 01,02,03,...,09, a partir del 10, el numero queda tal cual esta
					if ($diasCols<10)
						$diaFecha="0".$diasCols;
					else
						$diaFecha=$diasCols;
					//Obtener el nombre del Dia de la Semana
					$nomDia=obtenerNombreDia($anio."-".$cont."-".$diasCols);
					//Obtener el dia de la Checada
					$diaCda=substr($datos["fecha_checada"],-2);
					//Esta variable dibuja un cuadro de Texto vacio con el nombre y ID correspondientes a la mezcla del mes y del dia
					//p.e. Enero 21 -> name='0121'
					$estado="";
					//Verificar si la fecha es es la misma que la del dia de la Checada
					if ($diaFecha==$diaCda){
						//Ingresar en la variable estado el valor del Estado tomado por la Fecha
						$estado=$datos["estado"];
						//Adelantar al siguiente registro el resultado de la consulta
						$datos=mysql_fetch_array($rs);
						//Obtener el dia de la siguiente Checada y compararla para verificar la posible salida
						$diaCda=substr($datos["fecha_checada"],-2);
						//Verificar si la fecha de la checada siguiente recogida es ahora la misma que la de la fecha actual, para verificar la posible salida
						if ($diaFecha==$diaCda)
							//Adelantar al siguiente registro el resultado de la consulta para buscar el dato que no corresponda a la salida
							$datos=mysql_fetch_array($rs);
					}
					if($estado!="A"){
						$pdf->SetTextColor(111, 25, 23);
						$pdf->SetFont('Arial','B',9);//Estilo de Fuente mas pequeño y en Negritas para colocar los Dias
						//Dibujar en una columna el valor que $estado tomó segun lo verificado
						$pdf->Cell(7.58,5,$estado,'LTRB',0,'C',1);
						$pdf->SetFont('Arial','',8);//Estilo de Fuente mas pequeño y en Negritas para colocar los Dias
						$pdf->SetTextColor(51, 51, 153);
					}
					else
						//Dibujar en una columna el valor que $estado tomó segun lo verificado
						$pdf->Cell(7.58,5,$estado,'LTRB',0,'C',1);
					//Incrementar el contados de los dias de la columna
					$diasCols++;
				}while($diasCols<=$diasMes);//Mientras que los dias de la columna sean menos a los días que tiene el mes en dicho año
			}//Fin del if ($mesFecha==$mesCda), en caso de no haber datos, continuar al siguiente registro
		}//Fin del IF que revisa si se encontraron resultados, en caso de no haberlos, mostrar cajas de Texto vacias
		else{
			//Variable de control de dias por cada mes
			$sinDato=1;
			do{
				if ($sinDato<10)
					$nomSinDato="0".$sinDato;
				else
					$nomSinDato=$sinDato;
				//Dibujar en una columna el valor que $estado tomó segun lo verificado
				$pdf->Cell(7.58,5,'','LTRB',0,'C',1);
				$sinDato++;
			}while($sinDato<=$diasMes);//Mientras que la variable sea menor o igual a los dias del Mes Seleccionado
		}
		$pdf->Ln();
		$cont++;
	}while($cont<=12);//Mientras que la variable sea menor o igual a la cantidad de Meses, es decir, 12
	/****************************************************/
		
	$pdf->Ln();//Salto de Linea
	$pdf->SetFont('Arial','',11);//Aumentar el Tamaño de Letra en la firma de Constancia
	$leyenda1="HAGO CONSTAR QUE LO DESCRITO EN ESTE REPORTE FUE CHECADO";
	$leyenda2="POR MI Y CORRESPONDE A MI REGISTRO DIARIO DEL PERIODO.";
	
	//Colocar los Nombres de las columnas y el ancho de cada columna
	$pdf->Cell(260,5,$leyenda1,'',1,'C',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(260,5,$leyenda2,'',1,'C',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Ln();//Salto de Linea
	$pdf->Cell(260,5,'_____________________________________','',1,'C',0);
	$pdf->Cell(260,5,$nombre,'',1,'C',0);
	
	//Especificar Datos del Documento
	$pdf->SetAuthor("LIC. JOSE DE JESUS CARRILLO SANTACRUZ");
	$pdf->SetTitle("KARDEX");
	$pdf->SetCreator("RECURSOS HUMANOS");
	$pdf->SetSubject("Kardex Individual de ".$nombre);
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Registro de Kardex Individual de ".$nombre." en el SISAD");
	$num_req='Kardex.pdf';
	
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

	 //Segun el valor de $cont, obtener el nombre de cada mes
	function obtenerNombreMes($cont){
		switch($cont){
			case 1:
				$mes="ENERO";
				break;
			case 2:
				$mes="FEBRERO";
				break;
			case 3:
				$mes="MARZO";
				break;
			case 4:
				$mes="ABRIL";
				break;
			case 5:
				$mes="MAYO";
				break;
			case 6:
				$mes="JUNIO";
				break;
			case 7:
				$mes="JULIO";
				break;
			case 8:
				$mes="AGOSTO";
				break;
			case 9:
				$mes="SEPTIEMBRE";
				break;
			case 10:
				$mes="OCTUBRE";
				break;
			case 11:
				$mes="NOVIEMBRE";
				break;
			case 12:
				$mes="DICIEMBRE";
				break;
		}
		return $mes;
	}

?>