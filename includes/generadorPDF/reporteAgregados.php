<?php
require('fpdf.php');
require("../conexion.inc");
include("../func_fechas.php");
include ("../../includes/op_operacionesBD.php");


///Clase para poner los encabezados y los pies de pagina
class PDF extends FPDF{ 

	var $tipo_obra;
		
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
		//$this->MultiCell(70,50,"F 4.6.0 - 03 REPORTE DE ESTUDIO DE AGREGADOS PARA CONCRETO - ".$agregado." ".$origen,0,0,"C");
		$this->MultiCell(195,5,"F 4.6.0 - 03 REPORTE DE ESTUDIO DE AGREGADOS PARA CONCRETO - ".$agregado." ".$origen,0,"C",0);
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


	//************************************************************************************************//
	//*****************    SECCIÓN PARA OBTENER INFORMACIÓN POR MEDIO DE CONSULTAS     ***************// 
	//************************************************************************************************//
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
	//Conectar con la base de datos de Laboratorio
	$conne=conecta('bd_laboratorio');
	//recuperar el id
	$id=$_GET['id'];
	//recuperar el nombre
	if(isset($_GET['nombre']))
		$nombre=strtoupper($_GET['nombre']);
	else 
		$nombre="";
	//recuperar el puesto
	if(isset($_GET['puesto']))	
		$puesto=strtoupper($_GET['puesto']);
	else 
		$puesto="";
	//recuperar el empresa
	if(isset($_GET['empresa']))	
		$empresa=strtoupper($_GET['empresa']);
	else 
		$empresa="";
	//Obtener la fecha del dia
	$fechaDia= modFecha(date("Y-m-d"),2);
	$sql_stm = ("SELECT pvss_wm, pvss_vm, pvsc_wm ,pvsc_vm, densidad_msss, densidad_va, absorcion_msss, absorcion_ws, granulometria, origen_material, 
		                modulo_finura, nom_material, pl_wsc, pl_ws, fecha FROM (pruebas_agregados JOIN bd_almacen.materiales ON id_material=catalogo_materiales_id_material)
						WHERE id_pruebas_agregados = '$id'");
	$rs = mysql_query($sql_stm);
	$info = mysql_fetch_array($rs);	
	//Creacion del Objeto para Generar el PDF
	$pdf=new PDF('P','mm','Letter');
	$pdf->tipo_obra = "AGREGADOS";
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','B',11);//Definir el Estilo de la fuente
	$pdf->SetTextColor(51, 51, 153);//Definir el Color del Texto en formato RGB
	$pdf->SetFillColor(243,243,243);//Definir el color de Relleno para las celdas cuyo valor de la propiedad 'fill' sea igual a 1
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->SetAutoPageBreak(true,10);//Indicar que cuando una celda exceda el margen inferior de 1cm(10mm), esta se dibuje en la siguiente pagina
	
	$pdf->Ln(5);
	
	//Dibujar El encabezado, Fechas de Muestreo y Reporte
	$pdf->SetFont('Arial','B',7);//Definir el estilo de Fuente para las Fechas	
	$pdf->Cell(115,5,'Dirigido a:');//Colocar una celda vacia de 11.5 cm de ancho y 0.5 cm de alto antes de colocar la Etiqueta de la Fecha de Muestra			          
	$pdf->Cell(30,5,'FECHA DE MUESTREO:',0,0,'L');
	$pdf->Cell(30,5,modFecha($info['fecha'],2),0,1,'L');	
	$pdf->Cell(15,5);//Colocar una celda vacia de 11.5 cm de ancho y 0.5 cm de alto antes de colocar la Etiqueta de la Fecha de Muestra
	$pdf->Cell(100,5,'Ing. Guillermo Martínez');//Colocar una celda vacia de 11.5 cm de ancho y 0.5 cm de alto antes de colocar la Etiqueta de la Fecha de Muestra			          
	$pdf->Cell(30,5,'FECHA DE REPORTE:' ,0,0,'R');
	$pdf->Cell(30,5,$fechaDia,0,1,'L');
	$pdf->Cell(15,5);//Colocar una celda vacia de 11.5 cm de ancho y 0.5 cm de alto antes de colocar la Etiqueta de la Fecha de Muestra
	$pdf->Cell(180,5,'Gerente General',0,1);//Colocar una celda vacia de 11.5 cm de ancho y 0.5 cm de alto antes de colocar la Etiqueta de la Fecha de Muestra
	$pdf->Cell(15,5);//Colocar una celda vacia de 11.5 cm de ancho y 0.5 cm de alto antes de colocar la Etiqueta de la Fecha de Muestra
	$pdf->Cell(180,5,'Concreto Lanzado de Fresnillo S.A. de C.V.',0,1);//Colocar una celda vacia de 11.5 cm de ancho y 0.5 cm de alto antes de colocar la Etiqueta de la Fecha de Muestra
	$pdf->Ln(3);

	//Datos de la persona a la que va dirigido el Reporte (Nombre, Puesto y Empresa)
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(15,5);//Colocar una celda vacia
	$pdf->Cell(30,5,'PVSS (Kg/m³) :',0,0,'C');//Colocar una celda vacia de 1 cm de ancho y 0.5 cm de alto antes de colocar el Nombre
	$pdf->Cell(30,5,round(($info["pvss_wm"]/$info["pvss_vm"])*1000,2),0,0,'C');
	$pdf->Cell(45,5);//Colocar una celda vacia de 1 cm de ancho y 0.5 cm de alto antes de colocar el Nombre
	$pdf->Cell(30,5,'PVSC (Kg/m³) :',0,0,'R');//Colocar una celda vacia de 1 cm de ancho y 0.5 cm de alto antes de colocar el Nombre
	$pdf->Cell(30,5,round(($info["pvsc_wm"]/$info["pvsc_vm"])*1000,2),0,1,'C');

	$pdf->Cell(15,5,'Wm:',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(32,5,$info["pvss_wm"].' Kg',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(60,5);//Colocar una celda vacia
	$pdf->Cell(15,5,'Wm:',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(32,5,$info["pvsc_wm"].' Kg',0,1,'R');//Colocar una celda vacia
	
	$pdf->Cell(15,5,'Vm:',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(32,5,$info["pvss_vm"].' Lts',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(60,5);//Colocar una celda vacia
	$pdf->Cell(15,5,'Vm:',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(32,5,$info["pvsc_vm"].' Lts',0,1,'R');//Colocar una celda vacia
	$pdf->Ln(3);
	
	$pdf->Cell(15,5);//Colocar una celda vacia
	$pdf->Cell(30,5,'DENSIDAD (gr/m³) :',0,0,'C');//Colocar una celda vacia de 1 cm de ancho y 0.5 cm de alto antes de colocar el Nombre
	$pdf->Cell(30,5,round(($info["densidad_msss"]/$info["densidad_va"]),2),0,0,'C');
	$pdf->Cell(45,5);//Colocar una celda vacia de 1 cm de ancho y 0.5 cm de alto antes de colocar el Nombre
	$pdf->Cell(30,5,'ABOSRCIÓN (%) :',0,0,'R');//Colocar una celda vacia de 1 cm de ancho y 0.5 cm de alto antes de colocar el Nombre
	$pdf->Cell(30,5,round((($info["absorcion_msss"]-$info["absorcion_ws"])/$info["absorcion_ws"])*100,2),0,1,'C');
	
	$pdf->Cell(15,5,'Msss:',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(32,5,$info["densidad_msss"].' gr',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(60,5);//Colocar una celda vacia
	$pdf->Cell(15,5,'Msss:',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(32,5,$info["absorcion_msss"].' gr',0,1,'R');//Colocar una celda vacia
	
	$pdf->Cell(15,5,'Va:',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(32,5,$info["densidad_va"].' cm³',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(60,5);//Colocar una celda vacia
	$pdf->Cell(15,5,'Ws:',0,0,'R');//Colocar una celda vacia
	$pdf->Cell(32,5,$info["absorcion_ws"].' gr',0,1,'R');//Colocar una celda vacia
	$pdf->Ln(3);
	
	//Creamos la variable cadena para almacenar el nombre del material
	$cadena=$info["nom_material"];
	//Creamos la varialble la cual contendra el concepto a buscar
	$cadenaBusq="ARENA";
	//Comparamos si viene ARENA en $cadena entonces dibujamos el renglon
	if(stristr($cadena, $cadenaBusq)==true){
		$pdf->Cell(180,5,'MÓDULO FINURA:',0,0,'R');//Colocar una celda vacia
		$pdf->Cell(15,5,$info["modulo_finura"],0,1,'L');
		$pdf->Cell(180,5,'PÉRDIDA POR LAVADO(%):',0,0,'R');//Colocar una celda vacia
		$pdf->Cell(15,5,round(((bcsub($info["pl_wsc"],$info["pl_ws"]))/$info["pl_ws"])*100,2),0,1,'L');
	}
	
	$pdf->Cell(15,5);//Colocar una celda vacia
	$pdf->Cell(180,5,'GRANULOMETRÍA:',0,1,'L');
	$pdf->Ln(3);
	$pdf->Cell(30,5);//Colocar una celda vacia
	$pdf->Cell(165,5,$info["granulometria"],0,1,'L');
	
	$pdf->Cell(12.5,5);//Colocar una celda vacia
	$pdf->Cell(55,5,'MALLAS',1,0,'C');
	$pdf->Cell(55,5,'% QUE PASA',1,0,'C');
	$pdf->Cell(55,5,'% RETENIDO ACUMULADO',1,1,'C');

	//Cerrar las base abiertas, aparentemente, esta conectado a bd almacen
	mysql_close($conne);
	//Conectar con la base de datos de Laboratorio
	$conne=conecta('bd_laboratorio');
	//Consulta para obtener conceptos, retenido, limite inferior asu como limite superior para realizar las operacionesq ue permiten los calculos en el reporte
	$sql_detalle="SELECT concepto, retenido,limite_inferior, limite_superior FROM detalle_prueba_agregados 
				  WHERE pruebas_agregados_id_pruebas_agregados='$id' ORDER BY numero DESC";
	//Ejecutar la sentencia y almacena los 	datos de la consulta 
	$rs_detalle = mysql_query($sql_detalle);
	//Variable para guardar el total retenido
	$totalRetenido=0;
	//Arreglo para guardar la consulta; y asi permitir mostrar todos los registros al mismo tiempo
	$consultaConceptos=array();
	//Arreglo para Almacenar el limite inferior
	$limiteInferior=array();
	//Arreglo para Almacenar el limite superior
	$limiteSuperior=array();
	//Verificar que la consulta tenga datos
	if($datos=mysql_fetch_array($rs_detalle)){					
		do{
			//Acumulamos el total retenido
			$totalRetenido+=$datos['retenido'];
			//Almacenamos los conceptos
			$consultaConceptos[]=$datos['concepto'];
			//Almacenamos los limites_inferiores
			$limiteInferior[]=$datos['limite_inferior'];
			//Almacenamos los limites Superiores
			$limiteSuperior[]=$datos['limite_superior'];	
		}while($datos=mysql_fetch_array($rs_detalle));
	}
	//Consulta que permite obtener el numero y el retenido de cada agregado
	$sql_detalleASC="SELECT numero, retenido FROM detalle_prueba_agregados WHERE pruebas_agregados_id_pruebas_agregados='$id' ORDER BY numero";
	//Ejecutar la sentencia y almacena los 	datos de la consulta 
	$rs_detalleASC = mysql_query($sql_detalleASC);
	//Comprobamos que la consulta tiene datos
	if($datos=mysql_fetch_array($rs_detalleASC)){
		//Creamos el arreglo para guardar el porcentaje retenido
		$porcentajeRetenido=array();
		//Igualamos el total retenido 
		$totalRetenido=$totalRetenido;
		do{	
			//Almacenamos la operación necesaria para obtener el porcentaje Retenido	
			$porcentajeRetenido[]=(($datos['retenido']/$totalRetenido)*100);
		}while($datos=mysql_fetch_array($rs_detalleASC));
	}
	//Variable para controlar la cantidad de datos
	$tam=count($porcentajeRetenido);
	//Arrreglo para obtener el porcentaje retenido acumulado
	$porcentajeRetenidoAcumulado=array();
	//Guardamos el porcentaje retenido en su ultima posición como la primera posición del porcentaje retenido acumulado
	$porcentajeRetenidoAcumulado[]=$porcentajeRetenido[$tam-1];
	//Variable para controlar internamente el ciclo
	$band=0;
	//Variable para controlar la posicion inicial del arreglo (segun formula)
	$ctrl=$tam-2;
	do{
		//Almacenamos en el porcentaje retenido Acumulado la suma del porcentaje retenido mas el pocentaje retenido acumulado, bcadd tiene como 
		//objetivo obtener el resultado con un punto de presicion
		$porcentajeRetenidoAcumulado[]=bcadd($porcentajeRetenidoAcumulado[$band],$porcentajeRetenido[$ctrl],2);					
		//Disminuimos ctrl 
		$ctrl--;
		$band++;
	}while($ctrl>=0);
	//Arreglo que almacena el porcentaje retenido Acumulado de manera invertida
	$pRAInvertido=array();
	//Arreglo que almacena el porcentaje retenido acumuñlado sin invertir
	$porcentajeRetenidoSIN=array();					
	foreach($porcentajeRetenidoAcumulado as $ind =>$porcentaje){
		$pRAInvertido[]=round($porcentaje);
		$porcentajeRetenidoSIN[]=round($porcentaje);
	}
	//Arreglo que guarda el portentaje Retenido Acumulado pero de manera invertida
	$pRAInvertido=array_reverse($pRAInvertido);
	//Arreglo para Almacenar el porcentaje que pasa
	$porcentajePasa=array();
	//Realizamos la operación indicada por el cliente 100- el porcentajeRetenido en la ultima posicion
	$porcentajePasa[]=100-$porcentajeRetenido[$tam-1];
	$band=0;
	$ctrl=$tam-2;
	do{
		$porcentajePasa[]=bcsub($porcentajePasa[$band],$porcentajeRetenido[$ctrl],2);
		$band++;
		$ctrl--;
	}while($ctrl>=0);
	//Arrelgo para almacenar el porcentaje que pasa
	$pPasa=array();
	//Recorrremos para almacenar el pocentaje que pasa y a su vez redondearlo
	foreach($porcentajePasa as $ind =>$porcentajeP){
			$pPasa[]=abs(round($porcentajeP));	
	}
	$band=0;
	do{
		$pdf->Cell(12.5,5);//Colocar una celda vacia
		$pdf->Cell(55,5,$consultaConceptos[$band],1,0,'C');
		$pdf->Cell(55,5,$pPasa[$band],1,0,'C');
		$pdf->Cell(55,5,$porcentajeRetenidoSIN[$band],1,1,'C');
		$band++;
	}while($band<$tam);
	
	//GRAFICA DE AGREGADOS
//	$pdf->Ln(3);
	$pdf->AddPage();
	$pdf->Cell(40,7);
	$pdf->Cell(115,7,'GRÁFICA DE COMPOSICIÓN GRANULOMÉTRICA',1,0,'C');
	$pdf->Cell(40,7,'',0,1);
	//Crear la grafica
	$grafica=dibujarGrafica($consultaConceptos,$pPasa,$pRAInvertido, $limiteInferior, $limiteSuperior);
	//Obtener las posiciones de X y Y
	$posY=$pdf->GetY()+1;
	$posX=$pdf->GetX()+20;
	//Escribir la Grafica en el PDF
	$pdf->Image($grafica,$posX,$posY,155,90);

	$pdf->Ln(95);//Colocar una celda vacia
	//Cerrar las base abiertas, aparentemente, esta conectado a bd almacen
	mysql_close($conne);
	//Conectar con la base de datos de Laboratorio
	$conne=conecta('bd_laboratorio');
	//Escribir las observaciones
	$pdf->Cell(15,5);//Colocar una celda vacia
	$pdf->Cell(165,5,'OBSERVACIONES:','LRT',1,'L');
	//Consulta que permite extraer la norma asi como la descripcion de la misma
	$stm_observaciones="SELECT observaciones FROM (detalle_prueba_agregados JOIN pruebas_agregados ON 
						id_pruebas_agregados=pruebas_agregados_id_pruebas_agregados) WHERE id_pruebas_agregados='$id'";
	$rs_observaciones = mysql_query($stm_observaciones);
	$cont=1;
	if($datos=mysql_fetch_array($rs_observaciones)){
		do{
			if($datos['observaciones']!=""){
				$pdf->Cell(15,5);//Colocar una celda vacia
				$pdf->MultiCell(165,5,$cont.".-".$datos["observaciones"],"LR","J",0);
				$cont++;
			}
		}while($datos=mysql_fetch_array($rs_observaciones));
	}
	else{
		do{
			$pdf->Cell(15,5);//Colocar una celda vacia
			$pdf->Cell(165,5,'',"LR",1,'C');
			$cont++;
		}while($cont<5);
	}
	$pdf->Cell(15,5);//Colocar una celda vacia
	$pdf->Cell(165,5,mysql_error(),"LRB",1,'C');
	
	$pdf->Ln(10);
	//Cerrar las base abiertas, aparentemente, esta conectado a bd almacen
	mysql_close($conne);
	//Conectar con la base de datos de Laboratorio
	$conne=conecta('bd_laboratorio');
	//Consulta que permite extraer la norma asi como la descripcion de la misma
	$stm_catalogoMat="SELECT norma, nombre FROM ((catalogo_pruebas JOIN pruebas_realizadas ON catalogo_pruebas_id_prueba=id_prueba)
					  JOIN pruebas_agregados ON id_pruebas_agregados=pruebas_agregados_id_pruebas_agregados)
					  WHERE id_pruebas_agregados='$id'";
	$rs_catalogoMat = mysql_query($stm_catalogoMat);
	if($datos=mysql_fetch_array($rs_catalogoMat)){
		$pdf->Cell(15,5);//Colocar una celda vacia
		$pdf->Cell(165,5,$datos['norma']." ".$datos['nombre'],0,1,'L');
	}
	else
		$pdf->Cell(30,5,mysql_error());//Colocar una celda vacia
	$pdf->Ln(20);
	
	$pdf->Cell(30,5);//Colocar una celda vacia
	$pdf->Cell(52.5,5,'',"B",0,'C');
	$pdf->Cell(30,5);//Colocar una celda vacia
	$pdf->Cell(52.5,5,'',"B",1,'C');

	$pdf->Cell(30,5);//Colocar una celda vacia
	$pdf->Cell(52.5,5,'JEFE DE LABORATORIO', 0,0,'C',0);
	$pdf->Cell(30,5);//Colocar una celda vacia
	$pdf->Cell(52.5,5,'GERENTE TÉCNICO',0,1,'C',0); 
	
	$pdf->Cell(30,5);//Colocar una celda vacia
	$pdf->Cell(52.5,5,"ING. ".$nomLaboratorista, 0,0,'C',0);
	$pdf->Cell(30,5);//Colocar una celda vacia
	$pdf->Cell(52.5,5,"ING. ".$nomGerTec,0,1,'C',0); 
	/*****************************************************************************************************************************
	 *******************************************************DATOS DEL ESPECIMEN***************************************************
	 *****************************************************************************************************************************
	//Dibujar el cuadro donde se colocara la leyenda de DATOS DEL ESPECÍMEN	
	$pdf->Cell(10,5);//Mover 10 mm hacia la derecha	
	$pdf->SetFont('Arial','B',10);	
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(182,5,'DATOS DEL ESPÉCIMEN','',1,'C');
	//Colocar un espacio de 5 mm entre el Titulo y el Inicio de la tabla
	$pdf->Cell(0,5,'',0,1,'L');	
	
	//Colocar los Titulos de las columnas de la Tabla	
	$pdf->SetFont('Arial','',5);
	$pdf->Cell(9,7);//Mover 9 mm hacia la derecha	
	$pdf->Cell(17,7,'N.M.',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(20,7,'FECHA COLADO',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(18,7,'EDAD EN DÍAS',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(20,7,'FECHA RUPTURA',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(17,7,'F´C',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(18,7,'DIÁMETRO cm',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(21,7,'CARGAR RUPTURA kg',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(17,7,'ÁREA cm2',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(17,7,'Kg/cm2',1,0,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	$pdf->Cell(17,7,'%',1,1,'C',1); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
	
	
	//Ejecutar la Sentencia para Obtener la Cantidad de Registros del Detalle de la Prueba de Calidad
	$rs_detalle = mysql_query("SELECT edad,fecha_ruptura,fprima_c,carga_ruptura,kg_cm2,porcentaje,fecha_colado,diametro,area,observaciones
								FROM (detalle_prueba_calidad JOIN prueba_calidad ON prueba_calidad_id_prueba_calidad=id_prueba_calidad) 
								JOIN muestras ON muestras_id_muestra=id_muestra WHERE id_prueba_calidad = '$id' ORDER BY fecha_ruptura");
	//Obtener cantidad de renglones
	$renglones = mysql_num_rows($rs_detalle);
	$altoCelda = 7 * $renglones;
	
	//Dibujar el Detalle de la Prueba de Calidad que sera mostrada en el PDF
	$primerReg = 0;
	$observaciones = "";
	$noObs = 1;
	while($datos_detalle=mysql_fetch_array($rs_detalle)){
		//Guardar las Observaciones en una variable tipo cadena
		if($datos_detalle['observaciones']!=""){
			$observaciones .= $noObs.".- ".$datos_detalle['observaciones']."\n";
			$noObs++;
		}
		
		//Dibujar el Primer Renglon con las Celdas Combinadas
		if($primerReg==0){
			//Definir el Color de los bordes de las celdas, el color y tipo de letra
			$pdf->SetDrawColor(0, 0, 255);
			$pdf->SetTextColor(51, 51, 153);
			$pdf->SetFont('Arial','',5);
					
			//Mover 9 mm el inicio de la celda para alinearla
			$pdf->Cell(9);
			$pdf->Cell(17,$altoCelda,$info['num_muestra'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(20,$altoCelda,modfecha($datos_detalle['fecha_colado'],5),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(18,7,$datos_detalle['edad'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(20,7,modfecha($datos_detalle['fecha_ruptura'],5),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,$altoCelda,$datos_detalle['fprima_c'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(18,$altoCelda,$datos_detalle['diametro'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(21,7,number_format($datos_detalle['carga_ruptura'],2,".",","),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,$altoCelda,$datos_detalle['area'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,$datos_detalle['kg_cm2'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,$datos_detalle['porcentaje'].'%',1,1,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			
			$primerReg = 1;
		}
		else{//Dibujar el resto de los renglones			
						
			//Mover 9 mm el inicio de la celda para alinearla
			$pdf->Cell(9);
			$pdf->Cell(17,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(20,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(18,7,$datos_detalle['edad'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(20,7,modfecha($datos_detalle['fecha_ruptura'],5),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(18,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(21,7,number_format($datos_detalle['carga_ruptura'],2,".",","),1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,'','LR',0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,$datos_detalle['kg_cm2'],1,0,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
			$pdf->Cell(17,7,$datos_detalle['porcentaje'].'%',1,1,'C',0); // L-->Izq R-->Der T-->Arriba B-->Abajo 1-->Todos
		}
	
	}//Cierre while($datos_detalle=mysql_fetch_array($rs_detalle))				
		
	//Colocar un Espacio entre la tabla de Datos del Espécimen y la de Observaciones con un ancho de toda la pagina y una altura de 5 mm
	$pdf->Cell(0,5,'',0,1); 
		

	/******************************************************************
	 ***********************OBSERVACIONES******************************
	 ******************************************************************	
	//Lo necesario para las observaciones
	$pdf->SetFont('Arial','',10);
	//Move to 8 cm to the right
	$pdf->Cell(9,5);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(182,5,'OBSERVACIONES','LRT',1,'L',0);		
	//Ciclo que permite recorrer las observaciones que han sido almacenadas en la consulta del detalle		
	$pdf->Cell(9,5);
	$pdf->MultiCell(182,5,$observaciones,'LRB','L',0);		
	
	
	
	//Colocar un espacio de 10 mm entre la tabla de Obserbaciones y la Lista de Normas
	$pdf->Cell(0,10,'',0,1);
	
	
	
	/******************************************************************
	 ***************************NORMAS*********************************
	 ******************************************************************		
	// Realizar una consulta para obterer el catalogo de pruegas
	$sql_normas = "SELECT DISTINCT catalogo_pruebas_id_prueba, norma, nombre 
					FROM pruebas_realizadas JOIN catalogo_pruebas ON catalogo_pruebas_id_prueba=id_prueba
					WHERE prueba_calidad_id_prueba_calidad = '$id'";
	$rs_normas = mysql_query($sql_normas);
	
	//Definir el Estilo de la letra
	$pdf->SetFont('Arial','',8);
	
	if($datos_normas=mysql_fetch_array($rs_normas)){
		do{	
			//Obtener el nombre de la norma 
			$nomNorma = $datos_normas['norma'];
			//Obtener la descripcion de la norma obtenida
			$norma = $datos_normas['nombre'];
				
			
			//Mover 1 cm a la derecha
			$pdf->Cell(10);
			//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
			$pdf->Cell(160,4,$nomNorma.",  ".$norma,0,1,'L');//Cell(float w, float h, string txt, mixed border, int ln, string align, int fill)		
		}while($datos_normas=mysql_fetch_array($rs_normas));
	}//Cierre if($datos_normas=mysql_fetch_array($rs_normas))
	
	
	//Colocar un Espacio entre la tabla dibujada y las suiguiente
	$pdf->Cell(180,14,'',0,1); 
	
	//Dibujar el cuadro donde se colocara la leyenda de ATENTAMENTE	
	$pdf->Cell(30);
	$pdf->SetFont('Arial','B',10);	
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(140,4,'ATENTAMENTE','',1,'C');
	//poner el espacio entre este ultimo renglon y el siguiente
	$pdf->Cell(180,0,'',0,1,'L'); 
		
	//Lo necesario para el nombre y firma de cada uno de los interesados
	$pdf->Cell(9);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(180,20,' ','',1,'C',0);
	$pdf->Cell(0,0,'',0,1);
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->SetFont('Arial','',7);
	//$pdf->Cell(10);
	$pdf->Cell(-1);
	$pdf->Cell(110,0,'_________________________________','',1,'C',0);
	$pdf->Cell(290,0,'_________________________________','',1,'C',0);
	
	//Move to 8 cm to the right
	$pdf->Cell(9);
	//Texto centrado en una celda con cuadro 20*10 mm y salto de línea
	$pdf->Cell(180,4,' ','',1,'C',0);
	$pdf->Cell(0,0,'',0,1);
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->SetFont('Arial','',7);
	$pdf->Cell(-1);
	$pdf->Cell(110,0,"ING. ".$nomLaboratorista, '',1,'C',0);
	$pdf->Cell(110,5,'JEFE DE LABORATORIO', '',1,'C',0);
	$pdf->Cell(290,-5,'GERENTE TÉCNICO','',1,'C',0); 
	$pdf->Cell(290,0,"ING. ".$nomGerTec,'',1,'C',0); 

	//**************************************************************
	//*********************Fin de las tablas************************
	//**************************************************************/

	
	
	
	//****************************************
	//Especificar las Propiedades del PDF que se esta creando
	$pdf->SetAuthor("");
	$pdf->SetTitle("AGREGADO ".$id);
	$pdf->SetCreator($id);
	$pdf->SetSubject("Reporte de Agregados");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Agregado ".$id." en el SISAD");
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