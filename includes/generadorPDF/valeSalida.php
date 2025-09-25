<?php
require('mysql_table.php');
require("../conexion.inc");
include("../func_fechas.php");


class PDF extends PDF_MySQL_Table{

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
		$this->Cell(70,50,'F. 7.5.5 - 02 VALE DE ALMACEN',0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-70,60,'Calle Tiro San Luis #2, Col. Beleña, Fresnillo Zac.',0,0,'C');
		$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO S.A. DE C.V.',0,0,'R');
	    //Line break
	    $this->Ln(45);
		parent::Header();
	}

	//Page footer
	function Footer(){
		$this->SetTextColor(51, 51, 153);
		//Position at 1.5 cm from bottom
	    $this->SetY(-20);
	    //Arial italic 8
	    $this->SetFont('Arial','',7);
		//SUB TI
		$this->Cell(0,15,'       Fecha Emisión:                                               No. de Revisión:                                               Fecha de Revisión:',0,0,'L');
		$this->Cell(0,15,'',0,0,'L');
	    //Numero de Pagina
		$this->Cell(-20,15,'Página '.$this->PageNo().' de {nb}',0,0,'C');
		$this->SetY(-17);
		$this->Cell(0,15,'            Ago - 09'.'                                                                '.'02'.'                                                                 '.'   Oct - 12',0,0,'L');
		$this->Cell(0,15,'',0,0,'L');
		$this->SetY(-20);
		$this->Cell(0,25,'F. 4.2.1 - 01 / Rev. 01',0,0,'R');
		$this->Cell(0,25,'',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}

}//Cierre de la clase PDF	
	
	//Obtener el numero de la Salida
	$numVale = $_GET['id'];
	//Obtener datos de referencia de la Salida
	$fecha=modFecha(obtenerDato("bd_almacen","salidas","fecha_salida","id_salida",$numVale),1);
	$depto=obtenerDato("bd_almacen","salidas","depto_solicitante","id_salida",$numVale);
	$turno=obtenerDato("bd_almacen","salidas","turno","id_salida",$numVale);
	//Connect to database 
	conecta('bd_almacen');

	//Crear el Objeto PDF y Agregar las Caracteristicas Iniciales
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();	
	
	/**************************************************************************************************************/
	/************************************DATOS GENERALES DE LA SALIDA**********************************************/
	/**************************************************************************************************************/
	//NUMERO DE VALE
	$pdf->SetDrawColor(0, 0, 255);//Color de los Bordes
	$pdf->SetFont('Arial','B',10);//Tipo de Letra
	$pdf->SetTextColor(51, 51, 153);//Color del Texto
	$pdf->Cell(140,5,"N° VALE: ",0,0,"R",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->SetTextColor(128, 0, 0);//Color Rojo
	$pdf->Cell(50,5,$numVale,"B",1,"R",0);
	$pdf->SetFont('Arial','B',10);//Tipo de Letra
	//FECHA DE SALIDA
	$pdf->SetTextColor(51, 51, 153);//Color del Texto
	$pdf->Cell(140,5,"FECHA: ",0,0,"R",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->SetTextColor(128, 0, 0);//Color Rojo
	$pdf->SetFont('Arial','B',8);//Tipo de Letra
	$pdf->Cell(50,5,$fecha,"B",1,"R",0);
	$pdf->SetFont('Arial','B',10);//Tipo de Letra
	//DEPARTAMENTO QUE SOLICITA
	$pdf->SetTextColor(51, 51, 153);//Color del Texto
	$pdf->Cell(140,5,"DEPARTAMENTO: ",0,0,"R",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->SetTextColor(128, 0, 0);//Color Rojo
	$pdf->SetFont('Arial','B',8);//Tipo de Letra
	$pdf->Cell(50,5,$depto,"B",1,"R",0);
	$pdf->SetFont('Arial','B',10);//Tipo de Letra
	//TURNO EN QUE SALE EL MATERIAL
	$pdf->SetTextColor(51, 51, 153);//Color del Texto
	$pdf->Cell(140,5,"TURNO: ",0,0,"R",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
	$pdf->SetFont('Arial','B',8);//Tipo de Letra
	$pdf->SetTextColor(128, 0, 0);//Color Rojo
	$pdf->Cell(50,5,$turno,"B",1,"R",0);
	//SALTO DE LINEA
	$pdf->Ln(10);
	
	/**************************************************************************************************************/
	/**********************************TABLA DEL DETALLE DE LA REQUISICION*****************************************/
	/**************************************************************************************************************/
	//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
	$pdf->SetFillColor(217,217,217);
	$pdf->SetDrawColor(0, 0, 255);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->SetFont('Arial','B',12);
	//Colocar los Nombres de las columnas y el ancho de cada columna
	$pdf->Cell(20,5,'Cantidad','LTRB',0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(20,5,'Unidad','LTRB',0,'C',1);
	$pdf->Cell(92,5,'Nombre Material','LTRB',0,'C',1);
	$pdf->Cell(58,5,'Destino','LTRB',1,'C',1);
	
	//Sentencia para obtener el Detalle de la Salida
	$sql_stm = "SELECT cant_salida,unidad_material,nom_material,id_equipo_destino FROM detalle_salidas WHERE salidas_id_salida = '".$numVale."'";
	$rs = mysql_query($sql_stm);
	//Definir el tipo y tamaño de la letra para el Detalle de la Salida
	$pdf->SetFont('Arial','',9);
	$cant_renglones = 0;
	//Dibujar los registros del Detalle de la salida
	while($registro=mysql_fetch_array($rs)){
		//Obtener la Cantidad de Renglones que Ocuparan la Unidad de Media(10 caracteres), Descripcion(45 caracteres) y la Aplicacion(28 caracteres)
		//$unidad = cortarCadena($registro['unidad_medida'],10);
		$pos=strpos($registro['unidad_material']," ");
		$unidad = $registro['unidad_material'];
		$descripcion = cortarCadena($registro['nom_material'],45);
		$aplicacion = cortarCadena($registro['id_equipo_destino'],28);		
		
		//$costos = obtenerDato("bd_recursos","control_costos","descripcion","id_control_costos",$registro["destino"]);
		//$cuenta = obtenerDato("bd_recursos","cuentas","descripcion","id_cuentas",$registro["cuentas"]);
		//$subcuenta = obtenerDato("bd_recursos","subcuentas","descripcion","id_subcuentas",$registro["subcuentas"]);
		
		//$aplicacion = cortarCadena($registro['id_equipo_destino'],28);
		//$cadena = $costos.", ".$cuenta.", ".$subcuenta;
		//$aplicacion = cortarCadena($cadena,28);
		
		//Obtener la cantidad maxima de renglones que ocupara una de las columnas del registro en turno
		$maxRenglones = max($descripcion['cantRenglones'],$aplicacion['cantRenglones']);			
		
		//Colocar la Cantidad como primer columna y primer renglon
		$pdf->Cell(20,5,number_format($registro['cant_salida'],2,".",","),'LR',0,'C',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		
		//Imprimir la cantidad de renglones correspondientes de acuerdo a la longitud de cada campo
		for($i=0;$i<$maxRenglones;$i++){//Dibjar el Renglon con los datos del Registro
		
			//Colocar la celda correspondiente a la cantidad vacia y sin bordes para que no se note
			if($i>0)
				$pdf->Cell(20,5,'',"LR",0,'',0);

			//Para la Unidad de Medida
			if($i>0)
				$pdf->Cell(20,5,'','LR',0,'C',0);
			else{
				if(strlen($unidad)<10)
					$pdf->Cell(20,5,$unidad,'LR',0,'C',0);
				else{
					$pdf->SetFont('Arial','',5);
					$pdf->Cell(20,5,$unidad,'LR',0,'C',0);
					$pdf->SetFont('Arial','',9);
				}
			}
			//Imprimir la Descripcion
			if(isset($descripcion[$i]))	
				$pdf->Cell(92,5,$descripcion[$i],'LR',0,'L',0);
			else
				$pdf->Cell(92,5,'','LR',0,'',0);
			
			//Imprimir la Apicacion
			if(isset($aplicacion[$i]))
				$pdf->Cell(58,5,$aplicacion[$i],'LR',1,'L',0);
			else
				$pdf->Cell(58,5,'','LR',1,'',0);
				
			//Incrementar la cantidad de renglones
			$cant_renglones++;												
		}//Cierre for($i=0;$i<$maxRenglones;$i++)
		
		//Incrementar los rengones por el renglon en blanco que se dibuja despues de cada registro
		$cant_renglones++;
		
		//Determinar si hay un Salto de pagina para colocar la linea de Cierre
		$borde = "LR";
		if($cant_renglones>=26)
			$borde = "LRB";
				
		//Colocar 1 Renglon de espacio entre cada Registro
		$pdf->Cell(20,5,'',$borde,0,'',0);
		$pdf->Cell(20,5,'',$borde,0,'',0);
		$pdf->Cell(92,5,'',$borde,0,'',0);
		$pdf->Cell(58,5,'',$borde,1,'',0);
		
		
												
		//Monitorear la Cantidad de Renglones para realizar un salto de Pagina
		if($cant_renglones>=26){
			//Agregar una nueva Pagina
			$pdf->AddPage('P');
			//Reiniciar el contador de renglones
			$cant_renglones = 0;
			
			
			/**************************************************************************************************************/
			/**************************************DATOS GENERALES DE LA SALIDA********************************************/
			/**************************************************************************************************************/
			//NUMERO DE VALE
			$pdf->SetDrawColor(0, 0, 255);//Color de los Bordes
			$pdf->SetFont('Arial','B',10);//Tipo de Letra
			$pdf->SetTextColor(51, 51, 153);//Color del Texto
			$pdf->Cell(140,5,"N° VALE: ",0,0,"R",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
			$pdf->SetTextColor(128, 0, 0);//Color Rojo
			$pdf->Cell(50,5,$numVale,"B",1,"R",0);
			$pdf->SetFont('Arial','B',10);//Tipo de Letra
			//FECHA DE SALIDA
			$pdf->SetTextColor(51, 51, 153);//Color del Texto
			$pdf->Cell(140,5,"FECHA: ",0,0,"R",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
			$pdf->SetTextColor(128, 0, 0);//Color Rojo
			$pdf->SetFont('Arial','B',8);//Tipo de Letra
			$pdf->Cell(50,5,$fecha,"B",1,"R",0);
			$pdf->SetFont('Arial','B',10);//Tipo de Letra
			//DEPARTAMENTO QUE SOLICITA
			$pdf->SetTextColor(51, 51, 153);//Color del Texto
			$pdf->Cell(140,5,"DEPARTAMENTO: ",0,0,"R",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
			$pdf->SetTextColor(128, 0, 0);//Color Rojo
			$pdf->SetFont('Arial','B',8);//Tipo de Letra
			$pdf->Cell(50,5,$depto,"B",1,"R",0);
			$pdf->SetFont('Arial','B',10);//Tipo de Letra
			//TURNO EN QUE SALE EL MATERIAL
			$pdf->SetTextColor(51, 51, 153);//Color del Texto
			$pdf->Cell(140,5,"TURNO: ",0,0,"R",0);//Ancho,Alto,Texto,Bordes,SaltoLinea,Alineacion,RellenarFondo
			$pdf->SetFont('Arial','B',8);//Tipo de Letra
			$pdf->SetTextColor(128, 0, 0);//Color Rojo
			$pdf->Cell(50,5,$turno,"B",1,"R",0);
			//SALTO DE LINEA
			$pdf->Ln(10);

			//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
			$pdf->SetFillColor(217,217,217);
			$pdf->SetDrawColor(0, 0, 255);
			$pdf->SetTextColor(51, 51, 153);
			$pdf->SetFont('Arial','B',12);
			//Colocar los Nombres de las columnas y el ancho de cada columna
			$pdf->Cell(20,5,'Cantidad','LTRB',0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(20,5,'Unidad','LTRB',0,'C',1);
			$pdf->Cell(92,5,'Nombre Material','LTRB',0,'C',1);
			$pdf->Cell(58,5,'Destino','LTRB',1,'C',1);
			
			//Definir el tipo y tamaño de la letra para el Detalle de la Requisicion de la Siguiente Pagina
			$pdf->SetFont('Arial','',9);	
		}//Cierre if($cant_renglones>=26)
		
	}//Cierre while($registro=mysql_fetch_array($rs))
	
	//Obtener la Cantidad de Reglones Restantes para completar la Tabla
	$rens_restantes = 26 - $cant_renglones;
	
	//Imprimir renglones en blanco para completar la pagina
	for($i=0;$i<$rens_restantes;$i++){
		$pdf->Cell(20,5,'','LR',0,'C',0);
		$pdf->Cell(20,5,'','LR',0,'C',0);
		$pdf->Cell(92,5,'','LR',0,'C',0);
		$pdf->Cell(58,5,'','LR',1,'C',0);//Colocar el 1 para indicar que las siguientes celdas seran colocadas en la sig linea	
	}
	//Escribir el ultimo renglon
	$pdf->Cell(190,5,'','T',1,'C',0);
	
	/**************************************************************************************************************/
	/**************************************************FIRMA*******************************************************/
	/**************************************************************************************************************/
	//Colocar despues de la tabla los datos de la persona que solicito el material
	//SALTO DE LINEA
	$pdf->Ln(15);
	$pdf->SetTextColor(51, 51, 153);
	
	//Obtener los Datos de la Firma
	$solicita=obtenerDato("bd_almacen","salidas","solicitante","id_salida",$numVale);
	$recibe=obtenerDato("bd_almacen","salidas","solicitante","id_salida",$numVale);
	session_start();
	$entrega=obtenerDato("bd_usuarios","credenciales","nombre","usuarios_usuario",$_SESSION["usr_reg"]);
	
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(10,5,'',0,0,'C',0);
	$pdf->Cell(50,5,$solicita,"B",0,'C');
	$pdf->Cell(10,5,'',0,0,'C',0);
	$pdf->Cell(50,5,$recibe,'B',0,'C');
	$pdf->Cell(10,5,'',0,0,'C',0);
	$pdf->Cell(50,5,$entrega,'B',0,'C');
	$pdf->Cell(10,5,'',0,1,'C',0);
	
	$pdf->SetFont('Arial','',9);
	$pdf->Cell(10,5,'',0,0,'C',0);
	$pdf->Cell(50,5,'Solicita','T',0,'C');
	$pdf->Cell(10,5,'',0,0,'C',0);
	$pdf->Cell(50,5,'Recibe','T',0,'C');
	$pdf->Cell(10,5,'',0,0,'C',0);
	$pdf->Cell(50,5,'Entrega','T',0,'C');
	$pdf->Cell(10,5,'',0,1,'C',0);


	//Especificar Datos del Documento
	$pdf->SetAuthor($solicita);
	$pdf->SetTitle("VALE ".$numVale);
	$pdf->SetCreator("ALMACÉN");
	$pdf->SetSubject("VALE DE SALIDA DE MATERIAL");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Vale ".$numVale." en el SISAD");
	$numVale.='.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($numVale,"F");
	header('Location: '.$numVale);
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