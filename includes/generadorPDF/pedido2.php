<?php
require('rotation.php');
require("../conexion.inc");
include("../func_fechas.php");

class PDF extends PDF_Rotate{

	function Header(){
		//Logo
	    $this->Image('logo-clf.jpg',10,6,30);
	    //Arial bold 15
	    $this->SetFont('Arial','B',7.5);
		
		$this->SetTextColor(0, 0, 255);
	    //Move to the right
	    $this->Cell(60);
	    //Title
	    $this->Cell(70,25,utf8_decode("CONFIDENCIAL, PROPIEDAD DE CONCRETO LANZADO DE FRESNILLO MARCA, S.A DE C.V. PROHIBIDA SU PRODUCCIÓN TOTAL O PARCIAL."),0,0,'C');
		$this->SetTextColor(78, 97, 40);
		$this->SetFont('Arial','B',20);
		$this->Cell(-70,14,'__________________________________________________',0,0,'C');
		$this->SetTextColor(0, 0, 255);
		$this->SetFont('Arial','B',10);
		$this->Cell(70,50,'F. 7.4.0 - 02 PEDIDO',0,0,'C');
		$this->SetFont('Arial','B',10);
		$this->Cell(-70,60,utf8_decode("Avenida Enrique Estrada #75, Col. Las Americas, Fresnillo Zac."),0,0,'C');
		$this->Cell(70,70,'Tel./fax. (01 493) 983 90 89',0,0,'C');
		$this->SetFont('Arial','B',8);
		$this->SetTextColor(51, 51, 153);
		$this->Cell(62.5,9,'MANUAL DE PROCEDIMIENTOS DE LA CALIDAD',0,0,'R');
		$this->SetFont('Arial','I',7.5);
		$this->Cell(0.09,15,'CONCRETO LANZADO DE FRESNILLO MARCA S.A. DE C.V.',0,0,'R');
		/******************/
		//Imprimir mensaje si el Pedido se cancelo
		$estado=obtenerDatoCompras("pedido","estado","id_pedido",$_GET["id"]);
		if($estado=="CANCELADO"){
			//Put the watermark
			$this->SetFont('Arial','B',50);
			//Poner un Espacio entre cada letra del Estado
			$cad=chunk_split($estado,1);
			//Gris Oscuro
			//$this->SetTextColor(157,157,157);
			//Rojo
			$this->SetTextColor(217,150,148);
			$this->RotatedText(25,202,$cad,30);
		}
		/******************/
		
		/***********************************************************/
		//Imprimir marca de agua original o copia segun sea el caso//
		/***********************************************************/
		if($estado!="CANCELADO"){
			//Obtiene si el pedido ya ha sido impreso alguna vez
			$impreso=obtenerDatoCompras("pedido","impreso","id_pedido",$_GET["id"]);
			if($impreso == 0){
				$impreso = " ORIGINAL";
			} else{
				$impreso = "   COPIA";
			}
			//Poner la marca de agua
			$this->SetFont('Arial','B',50);
			//Poner un Espacio entre cada letra del Estado
			$cad=chunk_split($impreso,1);
			//Cambia el color del texto a rojo oscuro
			$this->SetTextColor(217,150,148);
			//Rota el texto
			$this->RotatedText(25,202,$cad,30);
			/**********************/
			//Fin de marca de agua//
			/**********************/
		}
		
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
		//SUB TI
		$this->Cell(0,15,utf8_decode("       Fecha Emisión:                                               No. de Revisión:                                               Fecha de Revisión:"),0,0,'L');
	    //Numero de Pagina
		$this->Cell(-20,15,'Pagina '.$this->PageNo().' de {nb}',0,0,'C');
		$this->SetY(-17);
		$this->Cell(0,15,'            Abr - 09'.'                                                                '.'03'.'                                                                 '.'   Mar - 12',0,0,'L');
		$this->SetY(-20);
		$this->Cell(0,25,'F. 7.4.0 - 02 / Rev. 01',0,0,'R');
		$this->SetFont('Arial','B',5);
		$this->Cell(0,5,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
		$this->Cell(0,6,'__________________________________________________________________________________________________________________________________________________________________________________________________',0,0,'R');
	}

	//Funcion que gira el Texto a un angulo especificado	
	function RotatedText($x, $y, $txt, $angle){
		//Text rotated around its origin
		$this->Rotate($angle,$x,$y);
		$this->Text($x,$y,$txt);
		$this->Rotate(0);
	}

}//Cierre de la clase PDF	

	//Conectarse a la Base de Datos de Compras
	conecta('bd_compras');
	//Crear el Objeto PDF y dar las Caracteristicas Iniciales
	$pdf=new PDF('P','mm','Letter');
	$pdf->AliasNbPages();
	$pdf->AddPage();
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->SetDrawColor(0, 0, 255);
	//Obtener el numero del Pedido
	$num_pedido = $_GET['id'];
	
	//Crear e introducir codigo QR en el pedido
	crearCodigoQR($num_pedido);
	$pdf->Image('qrcode_pedido.png',180,30,20,20,'PNG','');
	
	//Obtener la Informacion General del Pedido
	$cond_entrega = strtoupper(obtenerDatoCompras("pedido", "cond_entrega", "id_pedido", $num_pedido));
	$cond_pago = strtoupper(obtenerDatoCompras("pedido", "cond_pago", "id_pedido", $num_pedido));
	$comentarios = strtoupper(obtenerDatoCompras("pedido", "comentarios", "id_pedido", $num_pedido));
	$plazo = obtenerDatoCompras("pedido", "plazo_entrega", "id_pedido", $num_pedido);
	$prov_rfc = obtenerDatoCompras("pedido", "proveedores_rfc", "id_pedido", $num_pedido);
	$proveedor = obtenerDatoCompras("proveedores", "razon_social", "rfc", $prov_rfc);
	$req = obtenerDatoCompras("pedido", "requisiciones_id_requisicion", "id_pedido", $num_pedido);
	$subtotal = obtenerDatoCompras("pedido", "subtotal", "id_pedido", $num_pedido);
	$iva = obtenerDatoCompras("pedido", "iva", "id_pedido", $num_pedido);
	$total = obtenerDatoCompras("pedido", "total", "id_pedido", $num_pedido);
	$calle = obtenerDatoCompras("proveedores", "calle", "rfc", $prov_rfc);
	$col = obtenerDatoCompras("proveedores", "colonia", "rfc", $prov_rfc);
	$num = obtenerDatoCompras("proveedores", "numero_ext", "rfc", $prov_rfc);	
	$fecha = modFecha(obtenerDatoCompras("pedido", "fecha", "id_pedido", $num_pedido),1);
	$descto=obtenerDatoCompras("pedido", "pctje_descto", "id_pedido", $num_pedido);	
	
	//Obtener el tipo de Moneda del Pedido
	$tipoMoneda=obtenerDatoCompras("pedido", "tipo_moneda", "id_pedido", $num_pedido);
	if ($tipoMoneda=="PESOS"){
		$tipoMoneda=" M.N.";
		$simbolo="$ ";
	}
	elseif($tipoMoneda=="DOLARES"){
		$tipoMoneda=" USD";
		$simbolo="$ ";
	}
	elseif($tipoMoneda=="EUROS"){
		$tipoMoneda=" EUR";
		$simbolo=chr(128)." ";
	}
	
	/**********************************************************************************************************/
	/*************************DATOS PARTE SUPERIOR DE LA TABLA*************************************************/
	/**********************************************************************************************************/	
	//Segun el formato del estandar, la fecha debe estar en formato dd mm aa
	$dd = substr($fecha,0,2);
	$mm = substr($fecha,3,2);
	$aa = substr($fecha,-2);
		
	if (strlen($proveedor)>60){
		$ren = 2;
		//Antes de dividir la cadena buscar el caracter para separarla
		$pos = strlen($proveedor)/$ren;
		while(substr($proveedor,$pos,1)!=" ")
			$pos++;
		$cad1 = substr($proveedor,0,$pos);
		$fin =  (strlen($proveedor)/$ren) - ($pos - (strlen($proveedor)/$ren));
		$cad2 = substr($proveedor,$pos,$fin);
		$cad2 = ltrim($cad2," ");
	}
	else{
		$cad1=$proveedor;
		$cad2="";
	}
	
	//Datos de complemento para la parte inferior del documento
	$compro=obtenerDatoCompras("pedido", "solicitor", "id_pedido", $num_pedido);
	$reviso=obtenerDatoCompras("pedido", "revisor", "id_pedido", $num_pedido);
	$autorizo=obtenerDatoCompras("pedido", "autorizador", "id_pedido", $num_pedido);
	$solicitor=obtenerDatoCompras("pedido", "solicitor", "id_pedido", $num_pedido);
	$depto_solicitor=obtenerDatoCompras("pedido", "depto_solicitor", "id_pedido", $num_pedido);
	$autorizo = $depto_solicitor." - ".$solicitor;
	$fecha= $dd."   ".$mm."   ".$aa;
	
	//Lineas de cuadro 1
	$pdf->Image('linea.jpg',146,62,62,0.1);
	$pdf->Image('linea.jpg',146,75,62,0.1);
	//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
	$pdf->Cell(-2.1,0,"",0,0);
	//Contornos de Proveedor
	$pdf->Cell(138.2,30,"",1,0);
	//Contornos de No de Pedido
	$pdf->Cell(62,30,"",1,0);
	$pdf->Cell(-198,0,"",0,0);

	//Definir los datos que se encuentran sobre la tabla y antes del encabezado
	$pdf->Cell(138.2,10,"PROVEEDOR:",0,0);
	$pdf->Cell(30,10,'PEDIDO: ',0,0);
	$pdf->SetTextColor(255, 0, 0);
	$pdf->Cell(0,10,'No: '.$num_pedido,0,1);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->Cell(138.2,0,$cad1,0,0);
	$pdf->Cell(30,0,'FECHA: ',0,0);
	$pdf->SetFont('Arial','U',10);
	$pdf->Cell(0,0,$fecha,0,1);
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(168,10,$cad2,0,0);
	$pdf->Cell(10,8,'DD MM AA',0,1);
	$pdf->Cell(140,0,'',0,1);
	$pdf->Cell(140,0,'',0,1);
	$pdf->Cell(138.2,10,"RFC:",0,0);
	$pdf->Cell(30,10,utf8_decode("REQUISICIÓN: "),0,0);
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(0,10,$req,0,1);
	$pdf->SetFont('Arial','',10);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->Cell(0,0,$prov_rfc,0,1);
	
	//Colocar lineas de espacio entre parrafos, tablas, etc.
	$pdf->Cell(0,2,'',0,1);
	
	/**********************************************************************************************************/
	/******************************************DETALLE DEL PEDIDO**********************************************/
	/**********************************************************************************************************/	
	//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
	$pdf->SetFillColor(217,217,217);
	$pdf->SetTextColor(51, 51, 153);
	$pdf->SetFont('Arial','B',10);		
	
	
	//Colocar los Nombres de las columnas y el ancho de cada columna
	$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
	$pdf->Cell(18,10,'PARTIDA',1,0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(21,10,'CANTIDAD',1,0,'C',1);
	$pdf->Cell(18,10,'UNIDAD',1,0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(81.2,10,utf8_decode("DESCRIPCIÓN"),1,0,'C',1);
	$pdf->Cell(27,5,'PRECIO','LTR',0,'C',1);
	$pdf->Cell(35,10,'IMPORTE',1,1,'C',1);
	//Segundo Renglon
	$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
	$pdf->Cell(138.2,0,'',0,0,'',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
	$pdf->Cell(27,-5,'UNITARIO','LTR',0,'C',1);
	$pdf->Cell(35,0,'','',1,'',0);
	
	
	//Sentencia para obtener el Detalle del Pedido
	$sql_stm = "SELECT partida,cantidad,unidad,descripcion,precio_unitario,importe,id_control_costos,cantidad_real FROM detalles_pedido WHERE pedido_id_pedido='".$num_pedido."' ORDER BY id_control_costos";
	$rs = mysql_query($sql_stm);
	//Definir el tipo y tama�o de la letra para el Detalle de la Requisicion
	$pdf->SetFont('Arial','',10);
	$cant_renglones = 0;
	$aux = 0;
	$partidas = 1;
	$cadena_aux = "";
	//Dibujar los registros del Detalle de la Requisici�n
	while($registro=mysql_fetch_array($rs)){
		if($partidas == 1)
			$cadena_aux = $registro['id_control_costos'];
		
		if($registro['id_control_costos'] != $cadena_aux){
			$aux++;
			//Obtener la Cantidad de Reglones Restantes para completar la Tabla
			$rens_restantes = 26 - $cant_renglones;
			
			//Imprimir renglones en blanco para completar la pagina
			for($i=0;$i<$rens_restantes;$i++){
				$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
				$pdf->Cell(18,5,'','LR',0,'',0);
				$pdf->Cell(21,5,'','LR',0,'',0);
				$pdf->Cell(18,5,'','LR',0,'',0);
				$pdf->Cell(81.2,5,'','LR',0,'',0);
				$pdf->Cell(27,5,'','LR',0,'',0);
				$pdf->Cell(35,5,'','LR',1,'',0);//Colocar el 1 para indicar que las siguientes celdas seran colocadas en la sig linea	
			}
			//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
			$pdf->Cell(-2.1,0,"",0,0);
			$pdf->Cell(100.1,17,"",1,0);
			$pdf->Cell(-98,10,"",0,0);
			$pdf->Cell(100,10,"CENTRO DE COSTOS:",0);
			//Escribir el equipo al que va asignado el material pedido
			$pdf->Cell(-2,0,"",0,0);
			$pdf->Cell(100,10,"COMENTARIOS:",'TR',1);
			
			$centros_de_costos = obtenerCentrosDeCosto($_GET["id"]);
			if (strlen($centros_de_costos[$aux])>60){
				$ren = 2;
				//Antes de dividir la cadena buscar el caracter para separarla
				$pos = strlen($centros_de_costos[$aux])/$ren;
				while(substr($centros_de_costos[$aux],$pos,1)!=" ")
					$pos++;
				$cad1 = substr($centros_de_costos[$aux],0,$pos);
				$fin =  (strlen($centros_de_costos[$aux])/$ren) - ($pos - (strlen($centros_de_costos[$aux])/$ren));
				$cad2 = substr($centros_de_costos[$aux],$pos,$fin);
				$cad2 = ltrim($cad2," ");
				$pdf->Cell(98,0,$cad1,0,1);
				$pdf->Cell(98,10,$cad2,0,0);
			}
			else{
				$pdf->Cell(98,0,$centros_de_costos[$aux],0,1);
				$pdf->Cell(98,5,"",0,0);
				$pdf->SetFont('Arial','',7);
				if (strlen($comentarios)>60){
					$ren = 2;
					//Antes de dividir la cadena buscar el caracter para separarla
					$pos = strlen($comentarios)/$ren;
					while(substr($comentarios,$pos,1)!=" ")
						$pos++;
					$cad1 = substr($comentarios,0,$pos);
					$fin =  (strlen($comentarios)/$ren) - ($pos - (strlen($comentarios)/$ren));
					$cad2 = substr($comentarios,$pos,$fin);
					$cad2 = ltrim($cad2," ");
					$pdf->Cell(95,0,$cad1,0,1);
					$pdf->Cell(98,5,"",0,0);
					$pdf->Cell(100,7,$cad2,"BR",0);
				}
				else{
					$pdf->Cell(95,0,$comentarios,0,1);
					$pdf->Cell(98,5,"",0,0);
					$pdf->Cell(100,7,"","BR",0);
				}
			}
			//Agregar Renglon de Cierre cuando se pasa a otra Pagina
			/*$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
			$pdf->Cell(18,5,'','LRB',0,'',0);
			$pdf->Cell(21,5,'','LRB',0,'',0);
			$pdf->Cell(18,5,'','LRB',0,'',0);
			$pdf->Cell(81.2,5,'','LRB',0,'',0);
			$pdf->Cell(27,5,'','LRB',0,'',0);
			$pdf->Cell(35,5,'','LRB',0,'',0);*/
			
			$pdf->Ln();
			agregarFirmas($pdf,$compro,$reviso,$autorizo);
			
			//Agregar una nueva Pagina
			$pdf->AddPage('P');
			//Reiniciar el contador de renglones
			$cant_renglones = 0;
			$pdf->Image('qrcode_pedido.png',180,30,20,20,'PNG','');
			
			/**********************************************************************************************************/
			/*************************DATOS PARTE SUPERIOR DE LA TABLA*************************************************/
			/**********************************************************************************************************/
			//Definir los datos que se encuentran sobre la tabla y antes del encabezado
			$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
			$pdf->Cell(138.2,10,'',0,0,'',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(27,10,'PEDIDO: ','LTB',0,'L',0);
			$pdf->SetTextColor(255, 0, 0);
			$pdf->Cell(35,10,'No: '.$num_pedido,'RTB',1,'L',0);
			
			//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
			$pdf->SetFillColor(217,217,217);
			$pdf->SetTextColor(51, 51, 153);
			$pdf->SetFont('Arial','B',10);

			//Colocar los Nombres de las columnas y el ancho de cada columna
			$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
			$pdf->Cell(18,10,'PARTIDA',1,0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(21,10,'CANTIDAD',1,0,'C',1);
			$pdf->Cell(18,10,'UNIDAD',1,0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(81.2,10,utf8_decode("DESCRIPCIÓN"),1,0,'C',1);
			$pdf->Cell(27,5,'PRECIO','LTR',0,'C',1);
			$pdf->Cell(35,10,'IMPORTE',1,1,'C',1);
			//Segundo Renglon
			$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
			$pdf->Cell(138.2,0,'',0,0,'',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(27,-5,'UNITARIO','LTR',0,'C',1);
			$pdf->Cell(35,0,'','',1,'',0);
			//Definir el tipo y tama�o de la letra para el Detalle de la Requisicion de la Siguiente Pagina
			$pdf->SetFont('Arial','',10);
			
		}
		//Obtener la Cantidad de Renglones que Ocuparan la Descripcion(40 caracteres)
		$descripcion = cortarCadena($registro['descripcion'],38);
		
		//Obtener la cantidad maxima de renglones que ocupara la descripcion del registro en turno
		$maxRenglones = $descripcion['cantRenglones'];			
		
		//Colocar la Partida y la Cantidad como primer columna y primer renglon
		$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
		$pdf->Cell(18,5,$partidas,'LR',0,'C',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		$pdf->Cell(21,5,$registro['cantidad_real'],'LR',0,'C',0);
		//Verificar la longitud de la unidad
		if(strlen($registro['unidad'])>7)
			$pdf->SetFont('Arial','',6);
		$pdf->Cell(18,5,$registro['unidad'],'LR',0,'C',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
		if(strlen($registro['unidad'])>7)
			$pdf->SetFont('Arial','',10);
		
		//Imprimir la cantidad de renglones correspondientes de acuerdo a la longitud de cada campo
		for($i=0;$i<$maxRenglones;$i++){//Dibjar el Renglon con los datos del Registro
		
			//Colocar la celda correspondiente a la Partidad y Cantidad vacias
			if($i>0){
				$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
				$pdf->Cell(18,5,'',"LR",0,'',0);
				$pdf->Cell(21,5,'',"LR",0,'',0);
				$pdf->Cell(18,5,'',"LR",0,'',0);
			}
							
			//Imprimir la Cantidad de Renglones Correspondiente a la Descripcion
			$pdf->Cell(81.2,5,$descripcion[$i],'LR',0,'L',0);

			
			//Imprimir el Precio Unitario y el Importe en el Primer renglon del Registro
			if($i==0){
				$pdf->Cell(27,5,$simbolo.number_format($registro['precio_unitario'],2,".",","),'LR',0,'R',0);
				$pdf->Cell(35,5,$simbolo.number_format($registro['importe'],2,".",","),'LR',1,'R',0);
			}
			else{
				$pdf->Cell(27,5,'','LR',0,'',0);
				$pdf->Cell(35,5,'','LR',1,'',0);
			}
				
			//Incrementar la cantidad de renglones
			$cant_renglones++;
		}//Cierre for($i=0;$i<$maxRenglones;$i++)		
		$partidas++;
		//Monitorear la Cantidad de Renglones para realizar un salto de Pagina
		if($cant_renglones>=21){
			//Agregar Renglon de Cierre cuando se pasa a otra Pagina
			$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
			$pdf->Cell(18,5,'','LRB',0,'',0);
			$pdf->Cell(21,5,'','LRB',0,'',0);
			$pdf->Cell(18,5,'','LRB',0,'',0);
			$pdf->Cell(81.2,5,'','LRB',0,'',0);
			$pdf->Cell(27,5,'','LRB',0,'',0);
			$pdf->Cell(35,5,'','LRB',0,'',0);
			
			$pdf->Ln();
			agregarFirmas($pdf,$compro,$reviso,$autorizo);
		
			//Agregar una nueva Pagina
			$pdf->AddPage('P');
			//Reiniciar el contador de renglones
			$cant_renglones = 0;
			$pdf->Image('qrcode_pedido.png',180,30,20,20,'PNG','');
			
			/**********************************************************************************************************/
			/*************************DATOS PARTE SUPERIOR DE LA TABLA*************************************************/
			/**********************************************************************************************************/
			//Definir los datos que se encuentran sobre la tabla y antes del encabezado
			$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
			$pdf->Cell(138.2,10,'',0,0,'',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(27,10,'PEDIDO: ','LTB',0,'L',0);
			$pdf->SetTextColor(255, 0, 0);
			$pdf->Cell(35,10,'No: '.$num_pedido,'RTB',1,'L',0);
			
			//Colocar las caracteristicas del Formato que llevara la Fila con los nombres de las columnas
			$pdf->SetFillColor(217,217,217);
			$pdf->SetTextColor(51, 51, 153);
			$pdf->SetFont('Arial','B',10);

			//Colocar los Nombres de las columnas y el ancho de cada columna
			$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
			$pdf->Cell(18,10,'PARTIDA',1,0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(21,10,'CANTIDAD',1,0,'C',1);
			$pdf->Cell(18,10,'UNIDAD',1,0,'C',1);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(81.2,10,utf8_decode("DESCRIPCIÓN"),1,0,'C',1);
			$pdf->Cell(27,5,'PRECIO','LTR',0,'C',1);
			$pdf->Cell(35,10,'IMPORTE',1,1,'C',1);
			//Segundo Renglon
			$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
			$pdf->Cell(138.2,0,'',0,0,'',0);//$pdf->Cell(ancho,alto,'texto',borde,saltoLinea,alineacion,relleno)
			$pdf->Cell(27,-5,'UNITARIO','LTR',0,'C',1);
			$pdf->Cell(35,0,'','',1,'',0);
			//Definir el tipo y tama�o de la letra para el Detalle de la Requisicion de la Siguiente Pagina
			$pdf->SetFont('Arial','',10);	
		}//Cierre if($cant_renglones>=17)
		$cadena_aux = $registro['id_control_costos'];
	}//Cierre while($registro=mysql_fetch_array($rs))			
	
	//Obtener la Cantidad de Reglones Restantes para completar la Tabla
	$rens_restantes = 17 - $cant_renglones;
	
	//Imprimir renglones en blanco para completar la pagina
	for($i=0;$i<$rens_restantes;$i++){
		$pdf->Cell(-2.1,0,"",0,0);//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
		$pdf->Cell(18,5,'','LR',0,'',0);
		$pdf->Cell(21,5,'','LR',0,'',0);
		$pdf->Cell(18,5,'','LR',0,'',0);
		$pdf->Cell(81.2,5,'','LR',0,'',0);
		$pdf->Cell(27,5,'','LR',0,'',0);
		$pdf->Cell(35,5,'','LR',1,'',0);//Colocar el 1 para indicar que las siguientes celdas seran colocadas en la sig linea	
	}
					
	
	/**********************************************************************************************************/
	/**********************************DATOS PARTE INFERIOR DE LA TABLA****************************************/
	/**********************************************************************************************************/
	//Definimos el color de relleno de los valores num�ricos en la tabla generada
	$pdf->SetFillColor(243,243,243);
	
	$pdf->Cell(-2.1);//Regresar las celdas 2.1 unidades para que coincidan con las celdas dibujadas por la funcion Table
	$pdf->Cell(18,5,'','LR',0);
	$pdf->Cell(21,5,'','LR',0);
	$pdf->Cell(18,5,'','LR',0);
	if($descto>0)
		$pdf->Cell(81.2,5,'NOTA: Los Precios Incluyen Descuento Aplicado','LR',0,'R',0);//Renglon para colocar la leyenda del descuento
	else
		$pdf->Cell(81.2,5,'','LR',0,'R',0);//Renglon vacio para colocar el subtotal
	//Definir parametros para el SUBTOTAL
	$pdf->Cell(27,5,'SUBTOTAL',0,0,'R',0);
	$pdf->Cell(35,5,$simbolo.number_format($subtotal,2,".",","),'LRT',1,"R",1);//Colocar el 1 para indicar que las siguientes celdas seran colocadas en la sig linea
	
	$pdf->Cell(-2.1);//Regresar las celdas 2.1 unidades para que coincidan con las celdas dibujadas por la funcion Table
	$pdf->Cell(18,5,'','LR',0);
	$pdf->Cell(21,5,'','LR',0);
	$pdf->Cell(18,5,'','LR',0);
	if($descto>0)
		$pdf->Cell(81.2,5,'al '.number_format($descto,2,".",",").'%','LR',0,'R',0);//Renglon para colocar la leyenda del descuento
	else
		$pdf->Cell(81.2,5,'','LR',0,'R',0);//Renglon vacio para colocar el iva
	//Definir parametros para el IVA
	$pdf->Cell(27,5,'IVA',0,0,'R',0);
	$pdf->Cell(35,5,$simbolo.number_format($iva,2,".",","),'LRT',1,"R",1);//Colocar el 1 para indicar que las siguientes celdas seran colocadas en la sig linea
	
	$pdf->Cell(-2.1);//Regresar las celdas 2.1 unidades para que coincidan con las celdas dibujadas por la funcion Table
	$pdf->Cell(18,5,'','LRB',0);
	$pdf->Cell(21,5,'','LRB',0);
	$pdf->Cell(18,5,'','LRB',0);
	$pdf->Cell(81.2,5,'','LRB',0,'C');//Renglon vacio para colocar el Total
	//Definir parametros para el TOTAL
	$pdf->SetFont('Arial','B',12);	
	$pdf->Cell(27,5,'TOTAL'.$tipoMoneda,0,0,'R',0);
	$pdf->Cell(35,5,$simbolo.number_format($total,2,".",","),'LRBT',0,"R",1);
	
	//Salto de Linea
	$pdf->Ln();
	
	//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
	$pdf->Cell(-2.1,0,"",0,0);
	$pdf->Cell(200.1,15,"",1,0);
	$pdf->Cell(-198,0,"",0,0);
	
	$pdf->SetFont('Arial','',10);
	$pdf->Cell(130,10,"CONDICIONES DE ENTREGA:",0,1);
	if (strlen($cond_entrega)>60){
		$ren = 2;
		//Antes de dividir la cadena buscar el caracter para separarla
		$pos = strlen($cond_entrega)/$ren;
		while(substr($cond_entrega,$pos,1)!=" ")
			$pos++;
		$cad1 = substr($cond_entrega,0,$pos);
		$fin =  (strlen($cond_entrega)/$ren) - ($pos - (strlen($cond_entrega)/$ren));
		$cad2 = substr($cond_entrega,$pos,$fin);
		$cad2 = ltrim($cad2," ");
		$pdf->Cell(130,0,$cad1,0,1);
		$pdf->Cell(130,10,$cad2,0,0);
	}
	else{
		$pdf->Cell(130,0,$cond_entrega,0,1);
		$pdf->Cell(130,5,"",0,0);
	}
	//Salto de Linea
	$pdf->Ln();
	//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
	$pdf->Cell(-2.1,0,"",0,0);
	$pdf->Cell(100.1,17,"",1,0);
	$pdf->Cell(-98,10,"",0,0);
	$pdf->Cell(100,10,"CONDICIONES DE PAGO:",0);
	//Escribir el equipo al que va asignado el material pedido
	$pdf->Cell(-2,0,"",0,0);
	$pdf->Cell(100,10,"EQUIPO:",'R',1);
	//Obtener los Equipos del Pedido
	$equipos="";
	$conn=conecta("bd_compras");
	$sql_stm_equipo="SELECT DISTINCT equipo FROM detalles_pedido WHERE pedido_id_pedido='$num_pedido'";
	$rs_equipo=mysql_query($sql_stm_equipo);
	if($datos_equipo=mysql_fetch_array($rs_equipo)){
		do{
			//Variable donde se acumulan los Equipos como cadena de texto
			$equipos.=$datos_equipo["equipo"].", ";
		}while($datos_equipo=mysql_fetch_array($rs_equipo));
		//Quitar la ultima coma [,] con su respectivo espacio posterior
		$equipos=substr($equipos,0,(strlen($equipos)-2));
		if(strlen($equipos)>50)
			$arrEquipos=cortarCadena($equipos,50);
		else
			$arrEquipos[]=$equipos;
	}
	//Dibujar las condiciones de Pago
	if (strlen($cond_pago)>50){
		$ren = 2;
		//Antes de dividir la cadena buscar el caracter para separarla
		$pos = strlen($cond_pago)/$ren;
		while(substr($cond_pago,$pos,1)!=" ")
			$pos++;
		$cad1 = substr($cond_pago,0,$pos);
		$fin = (strlen($cond_pago)/$ren) - ($pos - (strlen($cond_pago)/$ren));
		$cad2 = substr($cond_pago,$pos);
		$cad2 = ltrim($cad2," ");
		$pdf->Cell(100,0,$cad1,0,0);
		
		$pdf->Cell(100,10,$cad2,0,1);
	}
	else{
		$pdf->Cell(100,0,$cond_pago,0,1);
		$pdf->Cell(100,5,"",0,0);
		
		//Si el primer caracter es ", " removerlo
		if(substr($arrEquipos[0],0,2)==", "){
			$tamCad1=strlen($arrEquipos[0]);
			$arrEquipos[0]=substr($arrEquipos[0],2,$tamCad1);
		}
		
		$pdf->Cell(100,0,$arrEquipos[0],'L',1);
		$pdf->Cell(98,5,"",0,0);
		//Contemplado para 2 lineas de Equipos en el mismo pedido
		if(count($arrEquipos)>1)
			$pdf->Cell(100,7.1,"  ".$arrEquipos[1],'BR',1);
		else	
			$pdf->Cell(100,7.1,"  ",'BR',1);
	}
	$aux++;
	//Retornar al margen izquierdo de la tabla generada para dibujar las lineas
	$pdf->Cell(-2.1,0,"",0,0);
	$pdf->Cell(100.1,17,"",1,0);
	$pdf->Cell(-98,10,"",0,0);
	$pdf->Cell(100,10,"CENTRO DE COSTOS:",0);
	//Escribir el equipo al que va asignado el material pedido
	$pdf->Cell(-2,0,"",0,0);
	$pdf->Cell(100,10,"COMENTARIOS:",'TR',1);
	
	$centros_de_costos = obtenerCentrosDeCosto($_GET["id"]);
	if (strlen($centros_de_costos[$aux])>60){
		$ren = 2;
		//Antes de dividir la cadena buscar el caracter para separarla
		$pos = strlen($centros_de_costos[$aux])/$ren;
		while(substr($centros_de_costos[$aux],$pos,1)!=" ")
			$pos++;
		$cad1 = substr($centros_de_costos[$aux],0,$pos);
		$fin =  (strlen($centros_de_costos[$aux])/$ren) - ($pos - (strlen($centros_de_costos[$aux])/$ren));
		$cad2 = substr($centros_de_costos[$aux],$pos,$fin);
		$cad2 = ltrim($cad2," ");
		$pdf->Cell(98,0,$cad1,0,1);
		$pdf->Cell(98,10,$cad2,0,0);
	}
	else{
		$pdf->Cell(98,0,$centros_de_costos[$aux],0,1);
		$pdf->Cell(98,5,"",0,0);
		$pdf->SetFont('Arial','',7);
		if (strlen($comentarios)>60){
			$ren = 2;
			//Antes de dividir la cadena buscar el caracter para separarla
			$pos = strlen($comentarios)/$ren;
			while(substr($comentarios,$pos,1)!=" ")
				$pos++;
			$cad1 = substr($comentarios,0,$pos);
			$fin =  (strlen($comentarios)/$ren) - ($pos - (strlen($comentarios)/$ren));
			$cad2 = substr($comentarios,$pos,$fin);
			$cad2 = ltrim($cad2," ");
			$pdf->Cell(95,0,$cad1,0,1);
			$pdf->Cell(98,5,"",0,0);
			$pdf->Cell(100,7,$cad2,"BR",0);
		}
		else{
			$pdf->Cell(95,0,$comentarios,0,1);
			$pdf->Cell(98,5,"",0,0);
			$pdf->Cell(100,7,"","BR",0);
		}
	}
	//Salto de Linea
	$pdf->Ln();
		
	agregarFirmas($pdf,$compro,$reviso,$autorizo);
	
	//Especificar Datos del Documento
	$pdf->SetAuthor("Sra. Aurora Ledesma Macias");
	$pdf->SetTitle("PEDIDO ".$num_pedido);
	$pdf->SetCreator("Departamento de Compras");
	$pdf->SetSubject("Pedido de Compra");
	$pdf->SetKeywords("Qubic Tech. \nDocumento Generado a Partir del Pedido ".$num_pedido." en el SISAD");
	$num_pedido.='.pdf';
	
	//Mandar imprimir el PDF
	$pdf->Output($num_pedido,"F");
	header('Location: '.$num_pedido);
	readfile($num_pedido);
	//Borrar todos los PDF ya creados
	borrarArchivos();
	//Modifca el valor del pedido para indicar que esta ya ha sido impreso con anterioridad
	modificarImpreso("pedido","impreso","id_pedido",$_GET["id"]);

	/**********************************************************************************************************/
	/*********************************FUNCIONES UTILIZADAS EN EL PEDIDO****************************************/
	/**********************************************************************************************************/
	//Esta funcion se encarga de obtener un dato especifico de una tabla especifica
	function obtenerDatoCompras($nom_tabla, $campo_bus, $param_bus, $dato_bus){
		//Conectarse con la BD de Compras
		$conn = conecta("bd_compras");
		
		$stm_sql = "SELECT $campo_bus FROM $nom_tabla WHERE $param_bus='$dato_bus'";
		$rs = mysql_query($stm_sql);
		$datos = mysql_fetch_array($rs);
		
		return $datos[0];
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de la funcion obtenerDatoCompras($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
	//Esta funci�n elimina los archivos PDF que se hayan generado anteriormente
	function borrarArchivos(){
		//Borrar los ficheros temporales
		$t=time();
		$h=opendir('.');
		while ($file=readdir($h)){
			if (substr($file,-4)=='.pdf'){
				if($t-filemtime($file)>60)
					@unlink($file);
			}
		}
		closedir($h);
	}
	
	
	/*Esta funcion divide una cadena en la cantidad exacta de Caracteres o menor de acuerdo al acomodo de palabras*/
	function cortarCadena($cadena,$carsPorLinea){	
		//Variable para Almacenar la Nueva Cadena
		$datosCadena = array("cantRenglones"=>0);
		//Obtener el Tama�o de la Cadena Original
		$tamCadena = strlen($cadena);
		//Separar la Cadena Original en un Arreglo de Caracteres, donde cada posici�n del Arreglo contiene un solo caracter de la cadena original
		$caracteres = str_split($cadena);
			
		//Si el tama�o de la Cadena excede la cantidad de caracteres especificada, proceder a separarla
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
					
					//El siguiente caracter de la posici�n del ultimo espacio en blanco encontrado se vuelve la posici�n inicial
					$carInicial = $posBlank+1;
					
					//Colocar el contador del Ciclo for a la posici�n del ultimo espacio en blanco encontrado
					$i = $posBlank;
									
					//Resetear el contador de caracteres para no exceder el tama�o del renglon
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
	
	//Esta funcion se encarga de modificar si el documento ya ha sido impreso
	function modificarImpreso($nom_tabla, $campo_bus, $param_bus, $dato_bus){
		//Conectarse con la BD de Compras
		$conn = conecta("bd_compras");
		//Sentencia encargada de modifcar el parametro del documento
		$stm_sql = "UPDATE  $nom_tabla SET  $campo_bus = '1' WHERE $param_bus='$dato_bus'";
		mysql_query($stm_sql);
		
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Cierre de modificarImpreso($nom_tabla, $campo_bus, $param_bus, $dato_bus)
	
	function obtenerCentrosDeCosto($id_pedido){
		$cadena[] = array();
		$num = 0;
		$conecta = conecta("bd_compras");
		$stm_sql_cc  = "SELECT GROUP_CONCAT( CAST( T1.`partida` AS CHAR ) ) AS partidas, T2.`descripcion` 
						FROM  `detalles_pedido` AS T1
						LEFT JOIN  `bd_recursos`.`control_costos` AS T2
						USING (  `id_control_costos` ) 
						LEFT JOIN  `bd_recursos`.`cuentas` AS T3
						USING (  `id_cuentas` ) 
						LEFT JOIN  `bd_recursos`.`subcuentas` AS T4
						USING (  `id_subcuentas` ) 
						WHERE  `pedido_id_pedido` =  '$id_pedido'
						GROUP BY  `id_control_costos` 
						ORDER BY  `id_control_costos`";
		$rs_cc = mysql_query($stm_sql_cc);
		if($rs_cc){
			if($datos_cc = mysql_fetch_array($rs_cc)){
				do{
					//if($num == 0)
						//$cadena .= $datos_cc["partidas"]." ".$datos_cc["descripcion"];
					//else
						//$cadena .= " - ".$datos_cc["partidas"]." ".$datos_cc["descripcion"];
					$cadena[] = $datos_cc["descripcion"];
					$num++;
				}while($datos_cc = mysql_fetch_array($rs_cc));
			}
		}
		
		return $cadena;
	}
	
	function agregarFirmas($pdf,$compro,$reviso,$autorizo){
		/**********************************************************************************************************/
		/**********************************************FIRMAS******************************************************/
		/**********************************************************************************************************/
		//Salto de Linea
		$pdf->Ln();
		//Dividir los nombres en cadenas de 30 caracteres
		//$comprador=cortarCadena($compro,40);
		$revisor=cortarCadena($reviso,40);
		//Verificar el arreglo de mas lineas
		//if(count($comprador)>=count($revisor))
			//$tam=count($comprador);
		//else
		$tam=count($revisor);
		$autorizador=cortarCadena($autorizo,75);
		//Verificar el arreglo de mas lineas
		if($tam<count($autorizador))
			$tam=count($autorizador);
		//Recorrer las celdas 3 milimetros a la izquierda para colocar la leyenda inferior
		//$pdf->Cell("-3",5,"",0,0);	
		//$pdf->Cell(25,5,"SOLICIT�:",0,0,"R");
		//$pdf->SetFont('Arial','U',8);//Cambiar el tipo de Fuente para mostrarlo, el formato sera mas peque�o y subrayado
		//$pdf->Cell(45,5,$comprador[0],0,0,"C");
		$pdf->SetFont('Arial','',10);//Regresar el tipo de fuente al normal para todo el formato de Pedido
		$pdf->Cell(18,5,"REVISO:",0,0,"R");
		$pdf->SetFont('Arial','U',8);//Cambiar el tipo de Fuente para mostrarlo, el formato sera mas peque�o y subrayado
		$pdf->Cell(45,5,$revisor[0],0,0,"C");
		$pdf->Cell("-3",5,"",0,0);	
		$pdf->Cell(15,5,"",0,0,"R");
		$pdf->SetFont('Arial','U',8);//Cambiar el tipo de Fuente para mostrarlo, el formato sera mas peque�o y subrayado
		$pdf->Cell(15,5,"",0,0,"C");
		$pdf->SetFont('Arial','',10);//Regresar el tipo de fuente al normal para todo el formato de Pedido
		$pdf->Cell(15,5,"SOLICITO:",0,0,"R");
		$pdf->SetFont('Arial','U',8);//Cambiar el tipo de Fuente para mostrarlo, el formato sera mas peque�o y subrayado
		$pdf->Cell(90,5,$autorizador[0],0,0,"C");
		
		//Dibujar las demas lineas del nombre
		if($tam>2){
			$cont=1;
			do{
				$pdf->Ln();
				//Recorrer las celdas 3 milimetros a la izquierda para colocar la leyenda inferior
				$pdf->Cell("-3",5,"",0,0);
				//Espacio en blanco del tama�o de la etiqueta COMPRADOR
				//$pdf->Cell(25,5,"",0,0,"L");
				//if(isset($comprador[$cont]))
					//$pdf->Cell(45,5,$comprador[$cont],0,0,"C");
				//else
					//$pdf->Cell(45,5,"",0,0,"C");
				//Espacio en blanco del tama�o de la etiqueta REVISO
				$pdf->Cell(18,5,"",0,0,"L");	
				if(isset($revisor[$cont]))
					$pdf->Cell(45,5,$revisor[$cont],0,0,"C");
				else
					$pdf->Cell(45,5,"",0,0,"C");
				//Espacio en blanco del tama�o de la etiqueta AUTORIZ�
				$pdf->Cell(22,5,"",0,0,"L");
				if(isset($autorizador[$cont]))
					$pdf->Cell(45,5,$autorizador[$cont],0,0,"C");
				else
					$pdf->Cell(45,5,"",0,0,"C");
				$cont++;
			}while($cont<($tam-1));
		}
	}
	
	function crearCodigoQR($cadena){
		include 'phpqrcode/qrlib.php';
		
		$file = 'qrcode_pedido.png';
		$data = $cadena;
		
		// El tama�o de la imagen.
		$size = 10;
		// Capacidad de correcci�n de errores.
		$level = QR_ECLEVEL_H;
		QRcode::png($data, $file, $level, $size);
	}
?>