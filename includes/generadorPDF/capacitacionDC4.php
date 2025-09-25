<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");

class PDF extends FPDF{ 
}

	//Conectar a la BD de Recursos Humanos
	$conn = conecta("bd_recursos");
	//Informacion para generar el PDF
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();//<--Agregar la primer Pagina

	//Variable para almacenar la cantidad de constancias a imprimir
	$cantConstancias=0;
	//Arreglo con los RFC de los empleados
	$rfc_empleado=array();
	$idCap=$_GET["cap"];
	//Obtener el rfc del empleado si solo se genera una constancia
	if(!isset($_GET["tipo"])){
		$rfc_empleado[] = $_GET['id'];
		$cantConstancias=1;
	}
	else{
		$stm_sql = ("SELECT empleados_rfc_empleado FROM empleados_reciben_capacitaciones WHERE capacitaciones_id_capacitacion='$idCap'");
		$rs = mysql_query($stm_sql);
		$cantConstancias=mysql_num_rows($rs);
		if($datosRFC = mysql_fetch_array($rs)){
			do{
				$rfc_empleado[] = $datosRFC["empleados_rfc_empleado"];
			}while($datosRFC = mysql_fetch_array($rs));
		}
	}
	//Variable de control de cantidad de Hojas a escribir
	$band=0;
	//Escribir Cada hoja del documento
	do{
		//Agregar una nueva Pagina en caso de ser la siguiente Hoja del Capacitado
		if($band>0)
			$pdf->AddPage('P');
		//Ajustar el tipo de letra y color
		$pdf->SetFont('Arial','B',11);
		$pdf->SetTextColor(51, 51, 153);
	
		//Obtener los datos del trabajador
		$stm_sql = "SELECT CONCAT(ape_pat,' ',ape_mat,' ',nombre) AS nombre,curp,no_ss,discapacidad,edo_civil,hijos_dep_eco,estado,localidad,oc_esp,
					nivel_estudio,titulo,carrera,tipo_escuela FROM empleados WHERE rfc_empleado='$rfc_empleado[$band]'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
	
		//Obtener los datos de la capacitacion
		$stm_sql2 ="SELECT norma,nom_capacitacion,hrs_capacitacion,tema,fecha_fin,tipo_instructor,reg_instructor_stps,modalidad,objetivo FROM capacitaciones WHERE id_capacitacion='$idCap'";
		$rs2 = mysql_query($stm_sql2);
		$datos2 = mysql_fetch_array($rs2);
		
		// llamar la funcion que se encarga de separar el rfc en caracteres
		$curp= descomponerCurp($datos["curp"]);
		
		// Verificar si el tamaño del curp es menor a 18 poner '*' para completar el tamaño adecuado
		$tam=strlen($datos["curp"]);
		do {
			if ($tam<18){
				$curp[$tam+1]= '*';
			}
			$tam++;
		}while ($tam<=18);
		//Fecha de Emisión del Certificado
		$fechaEmision=date("Y-m-d");
		//Obtener los digitos del Dia de Emision
		$dEmision= substr ($fechaEmision, -2);
		//Obtener los digitos del Mes de Emision
		$mEmision= substr ($fechaEmision, 5,2);
		//Obtener los digitos del Año de Emision
		$aEmision= substr ($fechaEmision, 2,2);	
		
		//Obtener los digitos de el dia de terminacion de capacitacion
		$diaF= substr ($datos2['fecha_fin'], -2);
		//Obtener los digitos de el mes de terminacion de capacitacion
		$mesF= substr ($datos2['fecha_fin'], 5,2);
		//Obtener los digitos de el año de terminacion de capacitacion
		$anioF= substr ($datos2['fecha_fin'], 2,2);
		
		//Definir los datos que se encuentran sobre la tabla y antes del encabezado
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(0,7,'LISTA DE CONSTANCIA DE HABILIDADES LABORALES',0,1,'C');
		$pdf->SetDrawColor(0, 0, 255);
		$pdf->SetTextColor(51, 51, 153);
		$pdf->Cell(0,5,'Formato DC-4',0,1,'C');
		//******************** Tabla de los DATOS DEL EMPLEADO********************//
		//************************************************************************//
		//Definimos el color de relleno de los valores numéricos en la tabla generada
		$pdf->SetFont('Arial','B',10);
		//Definir el color de Relleno de los encabezados
		$pdf->SetFillColor(220,220,220);
		//Imprimir Salto de Linea
		$pdf->Ln();
		//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
		$pdf->Cell(0,7,'DATOS DEL TRABAJADOR',1,1,'C',1);
		//Para el nombre del trabajador
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(0,7,'Apellido paterno, materno, nombres (s)' ,'LR',1,'L',0);
		//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
		$pdf->SetFont('Arial','',10);
		$pdf->Cell(0,5,$datos['nombre'],'LBR',1,'L',0);//																			<-Nombre en formato Ap Pat, Ap Mat y Nombre(s)
		
		//Para la etiqueta de CURP
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(108,7,'Clave Única de Registro de Población','LR',0,'L',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		//Para el puesto 
		$pdf->Cell(87.8,7,'N° de afiliación al IMSS*','LR',1,'L',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		
		$pdf->SetDrawColor(0, 0, 255);
		$pdf->SetTextColor(51, 51, 153);
		$pdf->SetFont('Arial','',10);
		//Recuadros para la CURP
		$pdf->Cell(6,7,$curp[1] ,'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[2],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[3],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[4],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[5],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[6],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[7],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[8],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[9],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[10],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[11],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[12],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[13],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[14],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[15],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[16],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[17],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,7,$curp[18],'LBR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		//Recuadro para el NSS del IMSS
		$pdf->Cell(87.8,7,$datos['no_ss'],'LBR',1,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos		<-No SS
		//Espacio en Blanco con Bordes LR
		$pdf->Cell(0,3,'','LR',1);
		//Tipo de Fuente para contenido de Texto dentro de las tablas, es decir, las etiquetas
		$pdf->SetFont('Arial','',8);
		$pdf->Cell(40,5,'Tipo de discapacidad*','L',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		//Discapacidad Motriz
		$pdf->Cell(23,5,'1. Motriz',0,0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		if($datos["discapacidad"]=="MOTRIZ"){
			$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Motriz
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Motriz
		//Discapacidad Visual	
		$pdf->Cell(23,5,'2. Visual',0,0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		if($datos["discapacidad"]=="VISUAL"){
			$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Visual
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Visual
		//Discapacidad Mental
		$pdf->Cell(23,5,'3. Mental',0,0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		if($datos["discapacidad"]=="MENTAL"){
			$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Mental
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','LR',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Mental
		//Discapacidad Auditiva
		$pdf->Cell(23,5,'4. Auditiva',0,0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		if($datos["discapacidad"]=="AUDITIVA"){
			$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Auditiva
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Auditiva
		//Discapacidad de Lenguaje
		$pdf->Cell(23,5,'5. De lenguaje',0,0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		if($datos["discapacidad"]=="DE LENGUAJE"){
			$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Lenguaje
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Discapacidad Lenguaje
		//Ultimo Espacio
		$pdf->Cell(10.8,5,'','R',1,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		//Parte Baja del Tipo Discapacidad
		$pdf->Cell(40,5,'','LB',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(23,5,'','B',0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,5,'','LRB',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(23,5,'','B',0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,5,'','LRB',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(23,5,'','B',0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,5,'','LRB',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(23,5,'','B',0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,5,'','LRB',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(23,5,'','B',0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,5,'','LRB',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(10.8,5,'','BR',1,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		//Espacio en Blanco con Bordes LR
		$pdf->Cell(0,3,'','LR',1);
		//Datos edo civil e hijos dependientes económicos
		$pdf->Cell(40,5,'Estado civíl*','L',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		//Verificar si el estado Civil es Casado
		$pdf->Cell(23,5,'1. Casado',0,0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		if($datos["edo_civil"]=="CASADO"){
			$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos										<-Edo Civil Casado
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos										<-Edo Civil Casado
		//Verificar si el estado Civil es Soltero
		$pdf->Cell(23,5,'2. Soltero',0,0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		if($datos["edo_civil"]=="SOLTERO"){
			$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos										<-Edo Civil Soltero
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos										<-Edo Civil Soltero
		//Verificar si el estado Civil es Otro
		$pdf->Cell(23,5,'3. Otro',0,0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		if($datos["edo_civil"]!="SOLTERO" && $datos["edo_civil"]!="CASADO"){
			$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos										<-Edo Civil Otro
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','LR',0,'C',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos										<-Edo Civil Otro
		$pdf->Cell(68.8,5,'N° de hijos dependientes económicos*','R',1,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		//Parte Baja
		$pdf->Cell(40,5,'','LB',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(23,5,'','B',0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,5,'','LRB',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(23,5,'','B',0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,5,'','LRB',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(23,5,'','B',0,'R',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(6,5,'','LRB',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(68.8,5,$datos['hijos_dep_eco'],'RB',1,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos				<-Hijos Dep Economicos
		//Datos Lugar de Residencia
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(20.8,5,'Lugar           de','LR',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(80,5,'Entidad federativa','R',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->Cell(95,5,'Municipio o delegación política','R',1,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		//Parte Baja
		$pdf->Cell(20.8,5,'residencia','LRB',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(80,5,$datos["estado"],'RB',0,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos							<-Estado
		$pdf->Cell(95,5,$datos["localidad"],'RB',1,'L',0);//L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos						<-Municipio
		//Datos ocupación Específica
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(68,5,'Ocupación específica (consultar catálogo al reverso)','LB',0,'L',0);
		$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(127.8,5,$datos["oc_esp"],'BR',1,'L',0);//																	<-Puesto
		/****************************************************************************************/
		//***** Tabla de los DATOS DE CERTIFICACIÓN DE COMPETENCIAS LABORALES********************//
		/****************************************************************************************/
		$pdf->SetFont('Arial','B',10);//<-Texto en Negrita
		$pdf->Cell(0,7,'DATOS DE CERTIFICACIÓN DE COMPETENCIAS LABORALES',1,1,'C',1);
		//Para las etiquetas de los datos de certificacion
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(97.9,7,'Nombre de la Norma*','LR',0,'L',0);
		$pdf->Cell(97.9,7,'Fecha de Emisión del Certificado*','R',1,'L',0);
		//Para el nombre de la Norma y la Fecha de Emision
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(97.9,7,$datos2["norma"],'LR',0,'R',0);//																		<-Norma de Capacitación
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(20,7,'',0,0,'R',0);
		$pdf->Cell(9.65,7,'Año',0,0,'R',0);
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(9.65,7,$aEmision,0,0,'C',0);//																				<-Año de Emisión
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(9.65,7,'Mes',0,0,'R',0);
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(9.65,7,$mEmision,0,0,'C',0);//																				<-Mes de Emisión
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(9.65,7,'Día',0,0,'R',0);
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(9.65,7,$dEmision,0,0,'C',0);//																				<-Día de Emisión
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(20,7,'','R',1,'R',0);
		//Parte baja
		$pdf->Cell(97.9,3,'','LRB',0,'L',0);
		$pdf->Cell(97.9,3,'','RB',1,'L',0);
		/**************************************************************************/
		//********************** Tabla de los DATOS ACADEMICOS********************//
		/**************************************************************************/
		$pdf->SetFont('Arial','B',10);//<-Texto en Negrita
		$pdf->Cell(0,7,'DATOS ACADÉMICOS',1,1,'C',1);
		//Para las etiquetas de los datos Académicos
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(57.9,7,'Nivel máximo de estudios terminados','L',0,'L',0);
		
		/*****/
		$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir datos de la BD
		$nivEstudio="";								//
		if ($datos["nivel_estudio"]>0)				//<-Cuando el nivel sea 0, no escribir nada
			$nivEstudio=$datos["nivel_estudio"];	//
		$pdf->Cell(12,7,$nivEstudio,'LRB',0,'C',0);//																					<-Aqui va el numero de Nivel de Estudios
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		/*****/
		
		$pdf->Cell(28,7,'','R',0,'L',0);
		$pdf->Cell(37.9,7,'Documento probatorio','L',0,'L',0);
		
		/*****/
		$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir datos de la BD
		$titulo="";								//
		if ($datos["titulo"]>0)				//<-Cuando el nivel sea 0, no escribir nada
			$titulo=$datos["titulo"];	//
		$pdf->Cell(10,7,$titulo,'LRB',0,'C',0);//																					<-Aqui va el numero del doc probatorio
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		/*****/
		
		$pdf->Cell(5,7,'',0,0,'L',0);
		$pdf->Cell(45,7,'Año de emisión*','R',1,'L',0);
		//Datos de nivel de estudios, de documento probatorio, carrera e institucion educativa
		$pdf->Cell(49,5,'1. Primaria','L',0,'L',0);
		$pdf->Cell(48.9,5,'5. Licenciatura','R',0,'L',0);
		$pdf->Cell(97.9,5,'1. Título','R',1,'L',0);
		$pdf->Cell(49,5,'2. Secundaria','L',0,'L',0);
		$pdf->Cell(48.9,5,'6. Especialidad','R',0,'L',0);
		$pdf->Cell(97.9,5,'2. Certificado','R',1,'L',0);
		$pdf->Cell(49,5,'3. Bachillerato','L',0,'L',0);
		$pdf->Cell(48.9,5,'7. Maestría','R',0,'L',0);
		$pdf->Cell(97.9,5,'3. Diploma','R',1,'L',0);
		$pdf->Cell(49,5,'4. Carrera Técnica','BL',0,'L',0);
		$pdf->Cell(48.9,5,'8. Doctorado','BR',0,'L',0);
		$pdf->Cell(97.9,5,'4. Otro','BR',1,'L',0);
		$pdf->Cell(97.9,7,'Nombre del estudio / carrera','LR',0,'L',0);
		$pdf->Cell(57.9,7,'Institución Educativa*','L',0,'L',0);
		
		/*****/
		$pdf->SetFont('Arial','',10);//<--Tipo de Letra para escribir datos de la BD
		$tipoEsc="";
		if($datos["tipo_escuela"]>0)
			$tipoEsc=$datos["tipo_escuela"];
		$pdf->Cell(12,7,$tipoEsc,'LRB',0,'C',0);//															<-Aqui va el numero que oorresponde a la institucion educativa
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		/*****/
		
		$pdf->Cell(28,7,'','R',1,'L',0);
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(97.9,7,$datos['carrera'],'LR',0,'L',0);//																<-Estudio o carrera
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(97.9,5,'1. Pública','LR',1,'L',0);
		$pdf->Cell(97.9,5,'','LR',0,'L',0);
		$pdf->Cell(97.9,5,'2. Privada','LR',1,'L',0);
		/****************************************************************************/
		//******************** Tabla de los DATOS DE CAPACITACIÓN********************//
		/****************************************************************************/
		$pdf->SetFont('Arial','B',10);//<-Texto en Negrita
		$pdf->Cell(0,7,'DATOS DE CAPACITACIÓN',1,1,'C',1);
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(97.9,7,'Nombre del curso','LR',0,'L',0);
		$pdf->Cell(97.9,7,'Duración (horas)','R',1,'L',0);
		if(strlen($datos2['nom_capacitacion'])<=40)
			$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		if(strlen($datos2['nom_capacitacion'])>40 && strlen($datos2['nom_capacitacion'])<=48)
			$pdf->SetFont('Arial','',9);//<-Tipo de Letra para escribir contenido desde la BD
		if(strlen($datos2['nom_capacitacion'])>48 && strlen($datos2['nom_capacitacion'])<=60)
			$pdf->SetFont('Arial','',7);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(97.9,5,$datos2['nom_capacitacion'],'LRB',0,'L',0);//														<-Nombre de la Capacitación
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(97.9,5,$datos2['hrs_capacitacion'],'RB',1,'L',0);//														<-Hrs de la Capacitación
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		$pdf->Cell(97.9,7,'Área temática del curso (consultar catálogo al reverso)','LR',0,'L',0);
		$pdf->Cell(50.9,7,'Fecha de término',0,0,'L',0);
		$pdf->Cell(15,7,'Año',0,0,'L',0);
		$pdf->Cell(15,7,'Mes',0,0,'L',0);
		$pdf->Cell(17,7,'Día','R',1,'L',0);
		if(strlen($datos2['tema'])<=40)
			$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		if(strlen($datos2['tema'])>40 && strlen($datos2['tema'])<=48)
			$pdf->SetFont('Arial','',9);//<-Tipo de Letra para escribir contenido desde la BD
		if(strlen($datos2['tema'])>48 && strlen($datos2['tema'])<=60)
			$pdf->SetFont('Arial','',7);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(97.9,5,$datos2['tema'],'LRB',0,'L',0);//																	<-Área Temática del Curso
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(51.9,5,'','B',0,'L',0);
		$pdf->Cell(15,5,$anioF,'B',0,'L',0);//																				<-Año de Termino de Curso
		$pdf->Cell(14,5,$mesF,'B',0,'L',0);//																				<-Mes de Termino de Curso
		$pdf->Cell(12,5,$diaF,'B',0,'L',0);//																				<-día de Termino de Curso
		$pdf->Cell(5,5,'','RB',1,'R',0);
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		//Agente Capacitador y numero en STPS
		$pdf->Cell(97.9,5,'','LR',0,0,0);
		$pdf->Cell(97.9,5,'N° de registro de agente capacitador en la STPS','R',1,'L',0);
		$pdf->Cell(40.9,5,'Agente Capacitador','L',0,'L',0);
		$pdf->Cell(18,5,'1. Interno','R',0,'L',0);
		
		if($datos2["tipo_instructor"]=="INTERNO"){
			$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','R',0,'C',0);//																					<-Capacitador Interno
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','R',0,'C',0);//																					<-Capacitador Interno
		$pdf->Cell(18,5,'2. Externo','R',0,'L',0);
		
		if($datos2["tipo_instructor"]=="EXTERNO"){
			$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
			$pdf->Cell(6,5,'X','R',0,'C',0);//																					<-Capacitador Externo
			$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		}
		else
			$pdf->Cell(6,5,'','R',0,'C',0);//																					<-Capacitador Externo
		$pdf->Cell(9,5,'','R',0,'C',0);
		$pdf->Cell(97.9,5,'','R',1,0,0);
		$pdf->Cell(58.9,5,'','LRB',0,0,0);
		$pdf->Cell(6,5,'','RB',0,'C',0);
		$pdf->Cell(18,5,'','RB',0,'C',0);
		$pdf->Cell(6,5,'','RB',0,'C',0);
		$pdf->Cell(9,5,'','RB',0,'C',0);
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$pdf->Cell(97.9,5,$datos2['reg_instructor_stps'],'RB',1,0,0);//														<-Registro en STPS para EXTERNO
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		//Modalidad capacitacion y Objetivo
		$pdf->Cell(97.9,5,'','LR',0,0,0);
		$pdf->Cell(60.9,5,'Objetivo de capacitación','R',0,'L',0);
		
		/********/
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$objetivo="";
		if($datos2["objetivo"]>0)
			$objetivo=$datos2["objetivo"];
		$pdf->Cell(6,5,$objetivo,'R',0,'C',0);//																					<-Número del Obj de la capacitación
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		/********/
		
		$pdf->Cell(31,5,'','R',1,'C',0);
		$pdf->Cell(57.9,5,'Modalidad de la capacitación','LR',0,'L',0);
		
		/********/
		$pdf->SetFont('Arial','',10);//<-Tipo de Letra para escribir contenido desde la BD
		$modalidad="";
		if($datos2["modalidad"]>0)
			$modalidad=$datos2["modalidad"];
		$pdf->Cell(8,5,$modalidad,'RB',0,'C',0);//																					<-Número de la modalidad de capacitacion
		$pdf->SetFont('Arial','',8);//<--Tipo de Letra para escribir las columas y etiquetas
		/********/
		
		$pdf->Cell(32,5,'','R',0,'C',0);
		$pdf->Cell(97.9,5,'','R',1,0,0);
		$pdf->Cell(97.9,5,'','LR',0,'L',0);
		$pdf->Cell(97.9,5,'1. Actualizar y perfeccionar conocimientos y habilidades','R',1,'L',0);
		$pdf->Cell(97.9,5,'1. Presencial','LR',0,'L',0);
		$pdf->Cell(97.9,5,'2. Proporcionar información de nuevas tecnologías','R',1,'L',0);
		$pdf->Cell(97.9,5,'2. En línea','LR',0,'L',0);
		$pdf->Cell(97.9,5,'3. Preparar para ocupar vacantes o puestos de nueva creación','R',1,'L',0);
		$pdf->Cell(97.9,5,'3. Mixta','LR',0,'L',0);
		$pdf->Cell(97.9,5,'4. Prevenir riesgos de trabajo','R',1,'L',0);
		$pdf->Cell(97.9,5,'','LRB',0,'L',0);
		$pdf->Cell(97.9,5,'5. Incrementar la productividad','RB',1,'L',0);
		$band++;
	}while($band<$cantConstancias);
	//**************************************************************//
	//*********************Fin de las tablas************************//
	//**************************************************************//
	
	//****************************************
	//Especificar Datos del Documento
	$pdf->SetAuthor("Recursos Humanos");
	$pdf->SetTitle("CONSTANCIA ".$cap);
	$pdf->SetCreator("SISAD - Recursos Humanos");
	$pdf->SetSubject("Constancia de Capacitación DC-4");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir de la Elaboración de Constancia de Capacitación Formato DC-4 en el SISAD");
	$cap.='-DC4.pdf';

	//Mandar imprimir el PDF
	$pdf->Output($cap,"F");
	header('Location: '.$cap);
	//Borrar todos los PDF ya creados
	borrarArchivos();
	//}
		
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
	
	// Esta funcion separa el CURP en caracteres
	function descomponerCurp($curp){
		$tam=strlen ($curp);
		$arreglo= array();
		for ($i=0; $i<$tam; $i++){
			$arreglo[$i+1] = substr($curp, $i, 1);
		}	
		return $arreglo;
	}
?>