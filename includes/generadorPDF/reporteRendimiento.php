<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
include ("../../includes/op_operacionesBD.php");


///Clase para poner los encabezados y los pies de pagina
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
		//$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
		//$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(135,4,'LABORATORIO DE CONTROL DE CALIDAD',0,1,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(195,5,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,1,'R');
		$this->SetFont('Arial','B',10);
		$this->Ln(10);
		//Obtener el origen del Material
		$origen=obtenerDato("bd_laboratorio","pruebas_agregados","origen_material","id_pruebas_agregados",$_GET['id']);
		//Obtener el origen del Material
		$agregado=obtenerDato("bd_laboratorio","pruebas_agregados","catalogo_materiales_id_material","id_pruebas_agregados",$_GET['id']);
		//Obtener el Id del Material
		$agregado=obtenerDato("bd_almacen","materiales","nom_material","id_material",$agregado);
		$this->Cell(15,10);
		$this->MultiCell(165,10,"REPORTE DE RENDIMIENTO EN OBRA PARA OBRA EN INTERIOR MINA",1,"C",0);
	    //Line break
	    $this->Ln(5);
		parent::Header();
		
	}

	//Page footer 
	function Footer(){
		//Position at 1.5 cm from bottom
	    $this->SetY(-12);
		//Definir el Estilo del texto	    
	    $this->SetFont('Arial','B',6);
		$this->Cell(0,1,'',"B",0,'C');
		$this->Ln(0.5);
		$this->Cell(0,1,'',"B",1,'C');
		 //Numero de Pagina
		$this->Cell(0,5,"Página ".$this->PageNo()." de {nb}",0,0,'R',0);
	}
}//Cierre de class PDF extends FPDF  

	//Creacion del Objeto para Generar el PDF
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',8);//Definir el Estilo de la fuente
	$pdf->SetTextColor(51, 51, 153);//Definir el Color del Texto en formato RGB
	$pdf->SetFillColor(243,243,243);//Definir el color de Relleno para las celdas cuyo valor de la propiedad 'fill' sea igual a 1
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->SetAutoPageBreak(true,10);//Indicar que cuando una celda exceda el margen inferior de 1cm(10mm), esta se dibuje en la siguiente pagina
	
	//recuperar los datos del GET
	$nombre=strtoupper($_GET['nombre']);
	$puesto=strtoupper($_GET['puesto']);
	$empresa=strtoupper($_GET['empresa']);
	$idMezcla=$_GET['id'];
	$idReg=$_GET["idReg"];
	
	$pdf->Cell(10,5);
	$pdf->Cell(65,5,$nombre,0,0,"L");
	$pdf->Cell(60,5,"Fecha: ",0,0,"R");
	$pdf->Cell(60,5,verFecha(1),0,1,"L");
	$pdf->Cell(10,5);
	$pdf->Cell(185,5,$puesto,0,1,"L");
	$pdf->Cell(10,5);
	$pdf->Cell(185,5,$empresa,0,1,"L");
	
	//************************************************************************************************//
	//*****************    SECCIÓN PARA OBTENER INFORMACIÓN POR MEDIO DE CONSULTAS     ***************// 
	//************************************************************************************************//
	//Realizar la conexion a la BD 
	$conn = conecta("bd_laboratorio");
	/********************************************OBTENER LOS DATOS DEL DISEÑO DE LA MEZCLA********************************************/
	//Verificar si el diseño original fue modificado, buscar en la tabla de Cambios Diseño Mezcla primero
	$sql_stm_mat1 = "SELECT * FROM cambios_disenio_mezcla WHERE rendimiento_id_registro_rendimiento = $idReg AND mezclas_id_mezcla = '$idMezcla'";
	$sql_stm_mat2 = "SELECT * FROM materiales_de_mezclas WHERE mezclas_id_mezcla='$idMezcla'";
	$sql_stm_materiales = "";
	//Verificar si la primera consulta regresa datos, para tomar el diseño de la mezcla de ahi
	if($datos=mysql_fetch_array(mysql_query($sql_stm_mat1)))
		$sql_stm_materiales = $sql_stm_mat1;		
	else//Si el diseño no fue modificado, tomar los datos de la segunda consulta
		$sql_stm_materiales = $sql_stm_mat2;										
	//Ejecutar la Sentencia SQL para obtener los datos del Diseño de la Mezcla seleccionada
	$rs_materiales = mysql_query($sql_stm_materiales);			
	//Cerrar la Conexion con la BD de Laboratorio
	mysql_close($conn);
	//Arreglo que almacena los nombres de los materiales
	$nombresMat = array();
	//Arreglo para Almacenar los volumenes de los Materiales
	$cantidadesMat = array();
	//Arreglo que permite guardar las unidades
	$unidadesMat = array();		
	$cont=1;	
	//Verificar que la consulta tenga datos
	if($datosMat=mysql_fetch_array($rs_materiales)){
		do{						
			//Recuperar datos adicionales del los materiales de la mezcla seleccionada
			$nomMaterial = obtenerDato('bd_almacen', 'materiales', 'nom_material', 'id_material', $datosMat['catalogo_materiales_id_material']);
										
			//Guardamos los nombres de los materiales en el arreglo; se obtiene en obtener dato $nomMaterial
			$nombresMat[] = $nomMaterial;
			//Almacenamos los volumenes
			$cantidadesMat[] = $datosMat['cantidad'];
			//Almacenamos las unidades
			$unidadesMat[] = $datosMat['unidad_medida'];
			//incrementamos el contador
			$cont++;
		}while($datosMat=mysql_fetch_array($rs_materiales));
	}//Cierre if($datosMat=mysql_fetch_array($rs_materiales))
	/********************************************OBTENER LOS DATOS DEL RENDIMIENTO Y LA MEZCLA********************************************/
	//Realizar la conexion a la BD 
	$conn = conecta("bd_laboratorio");
	$rs_rendimiento = mysql_query("SELECT * FROM rendimiento JOIN mezclas ON mezclas_id_mezcla=id_mezcla 
								WHERE id_registro_rendimiento = $idReg AND id_mezcla = '$idMezcla'");		
	//Guardamos los datos del Detalle del Rendimiento en las variables que serán mostradas
	if($datos_rend=mysql_fetch_array($rs_rendimiento)){					
		//Recuperar los datos de la Mezcla						
		$expediente = $datos_rend['expediente'];
		$equipo_mezclado = $datos_rend['equipo_mezclado'];
		//Recuperar los datos generales del Rendimiento
		$num_muestra = $datos_rend['num_muestra'];
		$localizacion = $datos_rend['localizacion'];
		$revenimiento = $datos_rend['revenimiento'];		
		$temperatura = $datos_rend['temperatura'];
		$hora = $datos_rend['hora'];
		$fechaRegistro = $datos_rend['fecha_registro'];
		$observaciones = $datos_rend['observaciones'];
		$notas = $datos_rend['comentarios'];
	}
	/********************************************OBTENER LOS DATOS DEL DETALLE DEL RENDIMIENTO********************************************/
	//Ejecutamos la consulta para obtener el Detalle del Rendimiento de la Mezcla Seleccionada que viene en el POST
	$rs_detalleRend = mysql_query("SELECT pvol_bruto,pvol_molde,pvol_unit,factor_recipiente,pvol_teorico_rend,pvol_rend,pvol_teorico_caire,pvol_caire,cb,r, caire_real 
		FROM detalle_rendimiento WHERE rendimiento_id_registro_rendimiento = '$idReg'");		
	//Guardamos los datos del Detalle del Rendimiento en las variables que serán mostradas
	if($datos_detalleRend=mysql_fetch_array($rs_detalleRend)){			
		$pvol_bruto = round($datos_detalleRend['pvol_bruto'],5);
		$pvol_molde = round($datos_detalleRend['pvol_molde'],5);
		$pvol_unit = round($datos_detalleRend['pvol_unit'],5);
		$factor_recipiente = round($datos_detalleRend['factor_recipiente'],5);
		$pvol_teorico_rend = round($datos_detalleRend['pvol_teorico_rend'],5);
		$pvol_rend = round($datos_detalleRend['pvol_rend'],5);
		$pvol_teorico_caire = round($datos_detalleRend['pvol_teorico_caire'],5);
		$pvol_caire = round($datos_detalleRend['pvol_caire'],5);
		$cb = round($datos_detalleRend['cb'],5);
		$r = round($datos_detalleRend['r'],5);				
		$caireReal = round($datos_detalleRend['caire_real'],5);
	}
	/********************************************OBTENER LAS PRUEBAS REALIZADAS********************************************/		
	$normas = array();
	$rs_pruebasEjec = mysql_query("SELECT catalogo_pruebas_id_prueba, norma, nombre 
									FROM pruebas_realizadas JOIN catalogo_pruebas ON catalogo_pruebas_id_prueba=id_prueba										
									WHERE rendimiento_id_registro_rendimiento = $idReg");
	//Guardamos los datos del Detalle del Rendimiento en las variables que serán mostradas
	if($datos_pruebasEjec=mysql_fetch_array($rs_pruebasEjec)){
		do{
			$normas[] = $datos_pruebasEjec['norma'].", ".$datos_pruebasEjec['nombre'];
		}while($datos_pruebasEjec=mysql_fetch_array($rs_pruebasEjec));
	}
	//Obtener el Nombre de la Mezcla
	$nomMezcla = obtenerDato("bd_laboratorio", "mezclas", "nombre", "id_mezcla", $idMezcla);
	/***********************************************************************************************************/
	/*********************************FIN DE LA EXTRACCION DE DATOS*********************************************/
	/***********************************************************************************************************/
	
	$pdf->Ln(5);
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'EXPEDIENTE: ',1,0,"C");
	$pdf->Cell(45,5,$expediente,0,0,"C");
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'N. MUESTRA: ',1,0,"C");
	$pdf->Cell(45,5,$num_muestra,0,0,"C");
	$pdf->Cell(10,5,'',0,1);
	$pdf->Ln(5);
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'LOCALIZACIÓN: ',1,0,"C");
	$pdf->Cell(45,5,$localizacion,0,0,"C");
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'REVENIMIENTO: ',1,0,"C");
	$pdf->Cell(45,5,$revenimiento." CM",0,0,"C");
	$pdf->Cell(10,5,'',0,1);
	$pdf->Ln(5);
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'EQUIPO DE MEZCLADO: ',1,0,"C");
	$pdf->Cell(45,5,$equipo_mezclado,0,0,"C");
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'HORA: ',1,0,"C");
	$pdf->Cell(45,5,substr($hora,0,5)." HRS.",0,0,"C");
	$pdf->Cell(10,5,'',0,1);
	$pdf->Ln(5);
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'TEMPERATURA: ',1,0,"C");
	$pdf->Cell(45,5,$temperatura." °C",0,0,"C");
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'TIPO DE MEZCLA: ',1,0,"C");
	$pdf->Cell(50,5,$nomMezcla,0,0,"C");
	$pdf->Cell(5,5,'',0,1);
	$pdf->Ln(5);
	
	$pdf->Cell(195,5,'DOSIFICACIÓN',0,1,"C");
	$pdf->Cell(195,5,'El análisis del rendimiento presentado, es en base al diseño que se muestra a continuación:',0,1,"C");
	
	$pdf->Cell(22.5,5);
	$pdf->Cell(50,5,"MATERIALES",1,0,"C");
	$pdf->Cell(50,5,"1 m³",1,0,"C");
	$pdf->Cell(50,5,"UNIDAD",1,0,"C");
	$pdf->Cell(22.5,5,'',0,1);
	//Este ciclo nos permite recorrer el arreglo de cantidades y el de los nombres de los materiales; para dibujar la tabla de manera dinamica				
	$totales = 0;
	foreach($cantidadesMat as $ind => $cantidad){
		//Formatear la cantidad del material que va a ser desplegado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
		$decs = contarDecimales($cantidadesMat[$ind]);
		$cantFormat = number_format($cantidadesMat[$ind],$decs,".",",");
		$pdf->Cell(22.5,5);
		$pdf->Cell(50,5,$nombresMat[$ind],1,0,"C");
		$pdf->Cell(50,5,$cantFormat,1,0,"C");
		$pdf->Cell(50,5,$unidadesMat[$ind],1,0,"C");
		$pdf->Cell(22.5,5,'',0,1);
		//Obtener el total de las cantidades de los materiales listados
		$totales = $totales+str_replace(",","",$cantidadesMat[$ind]);
	}//Cierre foreach($cantidadesMat as $ind => $cantidad)
	$decs = contarDecimales(round($totales,5));
	$totalFormat = number_format($totales,$decs,".",",");
	$pdf->Cell(22.5,5);
	$pdf->Cell(50,5,"TOTALES",1,0,"C");
	$pdf->Cell(50,5,$totalFormat,1,0,"C");
	$pdf->Cell(50,5,"",1,0,"C");
	$pdf->Cell(22.5,5,'',0,1);
	$pdf->Ln(5);
	
	$pdf->AddPage();
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'P. VOL. (KG/M³) ',1,0,"C");
	$pVol = ($pvol_bruto-$pvol_molde)*$factor_recipiente; 
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
	$decs = contarDecimales(round($pVol,5));
	$pVolFormat = number_format($pVol,$decs,".",",");
	$pdf->Cell(45,5,$pVolFormat." KG/M³",0,0,"C");
	$pdf->Cell(10,5);
	$pdf->Cell(55,5,'RENDIMIENTO (M³)',1,0,"C");
	$rendimiento = $pvol_teorico_rend/$pvol_rend; 
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
	$decs = contarDecimales(round($rendimiento,5));
	$rendFormat = number_format($rendimiento,$decs,".",",");
	$pdf->Cell(25,5,$rendFormat." M³",0,0,"L");
	$pdf->Cell(5,5,'',0,1);
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'PESO BRUTO',0,0,"L");
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
	$decs = contarDecimales(round($pvol_bruto,5));
	$pvolBrutoFormat = number_format($pvol_bruto,$decs,".",",");
	$pdf->Cell(45,5,$pvolBrutoFormat,0,0,"L");
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'PESO VOL. TEÓRICO',0,0,"L");
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
	$decs = contarDecimales(round($pvol_teorico_rend,5));
	$pvolTeoricoFormat = number_format($pvol_teorico_rend,$decs,".",",");
	$pdf->Cell(50,5,$pvolTeoricoFormat,0,0,"L");
	$pdf->Cell(5,5,'',0,1);
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'PESO MOLDE',0,0,"L");
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
	$decs = contarDecimales(round($pvol_molde,5));
	$pvolMoldeFormat = number_format($pvol_molde,$decs,".",",");
	$pdf->Cell(45,5,$pvolMoldeFormat,0,0,"L");
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'PESO VOL.',0,0,"L");
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
	$decs = contarDecimales(round($pvol_rend,5));
	$pvolRendFormat = number_format($pvol_rend,$decs,".",",");
	$pdf->Cell(50,5,$pvolRendFormat,0,0,"L");
	$pdf->Cell(5,5,'',0,1);
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'PESO UNITARIO',0,0,"L");
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
	$decs = contarDecimales(round($pvol_unit,5));
	$pvolUnitFormat = number_format($pvol_unit,$decs,".",",");
	$pdf->Cell(45,5,$pvolUnitFormat,0,0,"L");
	$pdf->Cell(100,5,'',0,1);
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'FACTOR RECIPIENTE',0,0,"L");
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format
	$decs = contarDecimales(round($factor_recipiente,5));
	$factorFormat = number_format($factor_recipiente,$decs,".",",");
	$pdf->Cell(45,5,$factorFormat,0,0,"L");
	$pdf->Cell(10,5);
	$pdf->Cell(55,5,'CONTENIDO REAL DE CEMENTO (KG)',1,0,"L");
	$contRealCemento = $cb/$r;
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
	$decs = contarDecimales(round($contRealCemento,5));
	$contRealFormat = number_format($contRealCemento,$decs,".",",");
	$pdf->Cell(25,5,$contRealFormat." KG",0,0,"L");
	$pdf->Cell(5,5,'',0,1);
	
	$pdf->Cell(100,5);
	$pdf->Cell(35,5,'Cb',0,0,"L");
	$pdf->Cell(50,5,$cb,0,0,"L");
	$pdf->Cell(5,5,'',0,1);
	$pdf->Cell(100,5);
	$pdf->Cell(35,5,'R',0,0,"L");
	$pdf->Cell(50,5,$r,0,0,"L");
	$pdf->Cell(5,5,'',0,1);
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'CONTENIDO DE AIRE (%)',1,0,"C");
	$contAire = (($pvol_rend-$pvol_teorico_rend)/$pvol_rend)*100; 
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
	$decs = contarDecimales(round($contAire,5));
	$contAireFormat = number_format($contAire,$decs,".",",");
	$pdf->Cell(45,5,$contAireFormat." %",0,0,"C");
	$pdf->Cell(105,5,'',0,1);
	
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'VOLUMETRICO',0,0,"L");
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
	$decs = contarDecimales(round($pvol_rend,5));
	$pvolRendFormat = number_format($pvol_rend,$decs,".",",");
	$pdf->Cell(45,5,$pvolRendFormat,0,0,"L");
	$pdf->Cell(10,5);
	$pdf->Cell(55,5,'CONTENIDO REAL DE AIRE (%)',1,0,"L");
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
	$decs = contarDecimales(round($caireReal,5));
	$cAireFormat = number_format($caireReal,$decs,".",",");
	$pdf->Cell(25,5,$cAireFormat." %",0,0,"L");
	$pdf->Cell(5,5,'',0,1);
	$pdf->Cell(10,5);
	$pdf->Cell(35,5,'PESO MEZCLA',0,0,"L");
	//Formatear el numero indicado, obteniendo la cantidad de decimales y despues aplicar la funcion number_format					
	$decs = contarDecimales(round($pvol_teorico_rend,5));
	$pVolTeoFormat = number_format($pvol_teorico_rend,$decs,".",",");
	$pdf->Cell(45,5,$pVolTeoFormat,0,0,"L");
	$pdf->Cell(105,5,'',0,1);
	
	//Escribir las observaciones
	$pdf->Ln(5);
	$pdf->Cell(10,5);//Colocar una celda vacia
	$pdf->Cell(175,5,'OBSERVACIONES:','LRT',1,'L');
	$pdf->Cell(10,5);//Colocar una celda vacia
	$pdf->MultiCell(175,5,$observaciones,'LRB',"J",0);
	
	$pdf->Ln(5);
	$pdf->Cell(10,5);//Colocar una celda vacia
	$pdf->MultiCell(175,5,'NOTA: EL CÁLCULO DE RENDIMIENTO SE HACE PARA 1m³, UTILIZANDO TODOS LOS PESOS DE DOSIFICACIÓN QUE SE REQUIEREN PARA LA MEZCLA. CON LA SIGUIENTE FÓRMULA',0,"J",0);
	//Obtener las posiciones de X y Y
	$posY=$pdf->GetY()+1;
	$posX=$pdf->GetX()+20;
	//Escribir la Formula en el PDF
	$pdf->Image('rpt-rendimiento-formula.jpg',$posX,$posY,30,8);
	
	$pdf->Ln(20);
	//Colocar cada norma en un renglon
	foreach($normas as $ind => $norma){
		$pdf->Cell(10,5);//Colocar una celda vacia
		$pdf->Cell(175,5,$norma,0,1,'L');
	}
	/****************************************************/
	/*OBTENER NOMBRES DE LABORATORISTA Y GERENTE TECNICO*/
	/****************************************************/
	//Conectar a la BD de Recursos Humanos
	$conn = conecta("bd_recursos");
	//Consulta para obtener el nombre del laboratorista
	$stm_sql = ("SELECT empleados_rfc_empleado FROM organigrama WHERE departamento='LABORATORIO'");
	$rs = mysql_query($stm_sql);
	$datos = mysql_fetch_array($rs);
	//Consulta para obtener el nombre gerencia tecnica			
	$stm_sql1 = ("SELECT empleados_rfc_empleado FROM organigrama WHERE departamento='GERENCIA TECNICA'");
	$rs = mysql_query($stm_sql1);
	$datos1 = mysql_fetch_array($rs);
	//Obtener nombre Recursos Humanos
	$nomLaboratorista = obtenerNombreEmpleado($datos["empleados_rfc_empleado"]);
	$nomGerTec = obtenerNombreEmpleado($datos1["empleados_rfc_empleado"]);
	
	$pdf->Ln(20);
	$pdf->Cell(10,5);
	$pdf->Cell(57.5,5);//Colocar una celda vacia
	$pdf->Cell(60,5,'',"B",0,'C');
	$pdf->Cell(57.5,5);//Colocar una celda vacia
	$pdf->Ln();

	$pdf->Cell(10,5);
	$pdf->Cell(57.5,5);//Colocar una celda vacia
	$pdf->Cell(60,5,'JEFE DE LABORATORIO',0,0,'C');
	$pdf->Cell(57.5,5);//Colocar una celda vacia
	$pdf->Ln();
	
	$pdf->Cell(10,5);
	$pdf->Cell(57.5,5);//Colocar una celda vacia
	$pdf->Cell(60,5,"ING. ".$nomLaboratorista,0,0,'C');
	$pdf->Cell(57.5,5);//Colocar una celda vacia
	$pdf->Ln(20);
	
	$pdf->Cell(10,5);
	$pdf->Cell(175,5,"C.C.P ING. ".$nomGerTec.". GERENTE",0,1,'L');
	$pdf->Cell(10,5);
	$pdf->Cell(175,5,"C.C.P ARCHIVO",0,1,'L');
	//**************************************************************
	//*********************Fin de las tablas************************
	//**************************************************************/
	
	//****************************************
	//Especificar las Propiedades del PDF que se esta creando
	$pdf->SetAuthor("");
	$pdf->SetTitle("RENDIMIENTO ".$id);
	$pdf->SetCreator($id);
	$pdf->SetSubject("Reporte de rendimiento");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir la Prueba de Rendimiento al Material ".$id." en el SISAD");
	$id.='.pdf';

	//Mandar imprimir el PDF
	$pdf->Output($id,"F");
	header('Location: '.$id);
	//Borrar todos los PDF ya creados
	borrarArchivos();
		
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
	
	//Grafica que es incluida en el reporte de Agregados
	function dibujarGrafica($consultaConceptos,$pPasa,$pRAInvertido, $limiteInferior, $limiteSuperior){	
		require_once ('../graficas/jpgraph/jpgraph.php');
		require_once ('../graficas/jpgraph/jpgraph_line.php');
		// So
		$wdata = array_reverse($pPasa);
		$ydata = array_reverse($limiteInferior);
		$zdata = array_reverse($limiteSuperior);
		// Create the graph. These two calls are always required
		$graph = new Graph(700,450);
		$graph->SetScale('textlin');
		$graph->yaxis->title->Set('%PASA');
		$graph->SetMargin(40,180,20,40);
		//Cambiar color del margen
		$graph->SetMarginColor("silver@0.5");
		//Establecer el margen separación entre etiquetas
		//$graph->xaxis->SetTextLabelInterval(2);
		// Crear las caracteristicas para cada una de las lineas
		$lineplot=new LinePlot($wdata);
		$lineplot->SetColor('blue');
		$lineplot->SetLegend('% Pasa');	
		//$lineplot->value->Show();		
		$lineplot3=new LinePlot($ydata);
		$lineplot3->SetColor('red');
		$lineplot3->SetLegend('Límite Inferior');	
		//Muestra los valores de los datos en las lineas
		//$lineplot3->value->Show();
		$lineplot4=new LinePlot($zdata);
		$lineplot4->SetColor('green');
		$lineplot4->SetLegend('Límite Superior');	
		//$lineplot4->value->Show();
		//Agregar Nombres de los rotulos
		$graph->xaxis->SetTickLabels(array_reverse($consultaConceptos));
		//Agregar las lineas de datos a la grafica
		$graph->Add($lineplot);
		$graph->Add($lineplot3);
		$graph->Add($lineplot4);
		//Alinear los rotulos de la leyenda
		$graph->legend->SetPos(0.05,0.5,'right', 'center');
		$rnd=rand(0,1000);
		$grafica= '../../pages/dir/tmp/grafica'.$rnd.'.png';
		//Dibujar la grafica y guardarla en un archivo temporal	
		$graph->Stroke($grafica);
		return $grafica;
	}//Cierre de la funcion dibujarGrafica($consultaConceptos,$pPasa,$pRAInvertido, $limiteInferior, $limiteSuperior)
?>