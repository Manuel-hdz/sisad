<?php
require('fpdf.php');

class PDF_MySQL_Table extends FPDF
{
var $ProcessingTable=false;
var $aCols=array();
var $TableX;
var $HeaderColor;
var $RowColors;
var $ColorIndex;

var $numRows;
var $ctrl = 1;//Esta variable controla cuando se da un salto de pagina

function Header()
{
	//Print the table header if necessary
	if($this->ProcessingTable)
		$this->TableHeader();
}

function TableHeader()
{
	$this->SetFont('Arial','B',12);
	$this->SetX($this->TableX);
	$fill=!empty($this->HeaderColor);
	if($fill)
		$this->SetFillColor($this->HeaderColor[0],$this->HeaderColor[1],$this->HeaderColor[2]);
	foreach($this->aCols as $col)
		$this->Cell($col['w'],6,$col['c'],1,0,'C',$fill);
	$this->Ln();
}

function Row($data,$origen)
{							
	$this->SetX($this->TableX);
	$ci=$this->ColorIndex;
	$fill=!empty($this->RowColors[$ci]);
	if($fill)
		$this->SetFillColor($this->RowColors[$ci][0],$this->RowColors[$ci][1],$this->RowColors[$ci][2]);
			
			
	//Definir el alto de las celdas en base a la longitud del campo de aplicacion en el Formato de Requsición
	if($origen=="req"){
		//Determinar el Alto del renglon de acuerdo a la cantidad de renglones en la columna de Aplicación
		$long_app = strlen($data['aplicacion']);
		if($long_app<22){//22 es el numero de caracteres aprox. por cada renglon en la columna de Aplicación
			$alto = 5;			
			$this->numRows++;//Incrementar el contador de renglones
		}
		if($long_app>=22 && $long_app<44){
			$alto = 10;
			$this->numRows+=2;//Incrementar el contador de renglones
		}
		if($long_app>=44){
			$alto = 15;		
			$this->numRows+=3;//Incrementar el contador de renglones
		}			
	}
	
	//Definir cuando se debe dibujar la linea de abajo en el Formato de Requisición
	$borde = "LR";
	//Incrementar el contador de renglones por el renglon en blanco que se imprime en el Formato de Requisición
	$this->numRows++;	
	//Cuando se llegue al fin de la pagina colocar el borde de cierre a los 28,29 o 30 Renglones Renglones de altura '5'
	if(($this->numRows==28||$this->numRows==29||$this->numRows==30) && $this->ctrl==1)
		$borde = "LRB";			

	
	//Dibjar cada tabla de los diferentes formatos creados en PDF					
	foreach($this->aCols as $col){
		
		//Dibujar la Tabla del Detalle de la Requisicion
		if($origen=="req"){
			//Manejo de la Requisicion
			if($col['f']=="cant_req")
				$this->Cell($col['w'],$alto,$data[$col['f']],'LR',0,$col['a'],$fill);							
			if($col['f']=="unidad_medida")
				$this->Cell($col['w'],$alto,$data[$col['f']],'LR',0,$col['a'],$fill);
			if($col['f']=="descripcion")
				$this->Cell($col['w'],$alto,$data[$col['f']],'LR',0,$col['a'],$fill);	
			if($col['f']=="aplicacion"){
				$this->MultiCell($col['w'],5,$data[$col['f']],'LR',$col['a'],$fill);
												
				$this->Cell(3);//Adelandar las celdas 3 unidades para dibujar las celdas al inicio de la tabla
				//Colocar un renglon en Blanco despues de dibujar la celda de Aplicación				
				$this->Cell(20,5,'',$borde,0,'',0);	
				$this->Cell(20,5,'',$borde,0,'',0);	
				$this->Cell(92,5,'',$borde,0,'',0);
				$this->Cell(58,5,'',$borde,1,'',0);
				
				if($borde=="LRB" && $this->ctrl==1){
					$this->Ln(30);
					$this->ctrl = 0;
				}
			}
		}
		
		
		//Dibujar la tabla del Detalle de la Orden de Compra
		if($origen=="oc"){
			//Manejo de la Orden de Compra
			if($col['f']=="catalogo_mf_codigo_mf")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);							
			if($col['f']=="cant_oc")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);
			if($col['f']=="descripcion")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',1,$col['a'],$fill);
		}
				
		//Dibujar la tabla del Detalle del Pedido
		if($origen=="pedido"){
			//Manejo del Pedido
			if($col['f']=="partida")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);							
			if($col['f']=="unidad")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);
			if($col['f']=="cantidad")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);	
			if($col['f']=="descripcion")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);
			if($col['f']=="precio_unitario")
				$this->Cell($col['w'],5,"$".number_format($data[$col['f']],2,".",","),'LR',0,$col['a'],$fill);	
			if($col['f']=="importe")
				$this->Cell($col['w'],5,"$".number_format($data[$col['f']],2,".",","),'LR',1,$col['a'],$fill);	
		}
		
		
		//Dibujar el detalle de la Entrada de Material en el Formato de Orden de Trabajo		
		if($origen=="entradaM"){
			if($col['f']=="unidad_material")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);
			if($col['f']=="cant_entrada")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);
			if($col['f']=="nom_material")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);	
			if($col['f']=="costo_unidad")
				$this->Cell($col['w'],5,"$".number_format($data[$col['f']],2,".",","),'LR',1,$col['a'],$fill);
		}
		
		
		//Dibujar la tabla con las Actividades Correctivas 
		if($origen=="ot"){
			$this->SetFont('Arial','',8);
			//Dibujar Tabla para los Mantenimientos Correspondientes
			if($col['f']=="nom_gama")
				$this->Cell($col['w'],5,$data[$col['f']],'LR',1,$col['a'],$fill);							
												
			//Dibujar Tabla de lo materiales
			if($col['f']=="cant_salida"){				
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);
			}
			
			if($col['f']=="unidad_material"){
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);
			}
			
			if($col['f']=="nom_material"){				
				$this->MultiCell($col['w'],5,$data[$col['f']],'LR',$col['a'],$fill);						
			}
			
			//Dibujar Tabla de actividades adicionales correctivas
			if($col['f']=="sistema"){				
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);
			}
			
			if($col['f']=="aplicacion"){
				$this->Cell($col['w'],5,$data[$col['f']],'LR',0,$col['a'],$fill);
			}
			
			if($col['f']=="descripcion"){				
				$this->MultiCell($col['w'],5,$data[$col['f']],'LR',$col['a'],$fill);						
			}

			if($col['f']=="nom_mecanico"){				
				$this->MultiCell($col['w'],5,$data[$col['f']],'LR',$col['a'],$fill);						
			}

			if($col['f']=="comentarios"){				
				$this->MultiCell($col['w'],5,$data[$col['f']],'LR',$col['a'],$fill);						
			}
			$this->SetFont('Arial','',12);
		}
				
	}//Fin del foreach($this->aCols as $col)
	
	
	$this->ColorIndex=1-$ci;
}

function CalcWidths($width,$align)
{
	//Compute the widths of the columns
	$TableWidth=0;
	foreach($this->aCols as $i=>$col)
	{
		$w=$col['w'];
		if($w==-1)
			$w=$width/count($this->aCols);
		elseif(substr($w,-1)=='%')
			$w=$w/100*$width;
		$this->aCols[$i]['w']=$w;
		$TableWidth+=$w;
	}
	//Compute the abscissa of the table
	if($align=='C')
		$this->TableX=max(($this->w-$TableWidth)/2,0);
	elseif($align=='R')
		$this->TableX=max($this->w-$this->rMargin-$TableWidth,0);
	else
		$this->TableX=$this->lMargin;
}

function AddCol($field=-1,$width=-1,$caption='',$align='L')
{
	//Add a column to the table
	if($field==-1)
		$field=count($this->aCols);
	$this->aCols[]=array('f'=>$field,'c'=>$caption,'w'=>$width,'a'=>$align);
}

function Table($query,$prop=array(),$origen)
{
	//Issue query
	$res=mysql_query($query) or die('Error: '.mysql_error()."<BR>Query: $query");
	
	echo "Renglones: ".mysql_num_rows($res);
	//Add all columns if none was specified
	if(count($this->aCols)==0)
	{
		$nb=mysql_num_fields($res);
		for($i=0;$i<$nb;$i++)
			$this->AddCol();
	}
	//Retrieve column names when not specified
	foreach($this->aCols as $i=>$col)
	{
		if($col['c']=='')
		{
			if(is_string($col['f']))
				$this->aCols[$i]['c']=ucfirst($col['f']);
			else
				$this->aCols[$i]['c']=ucfirst(mysql_field_name($res,$col['f']));
		}
	}
	//Handle properties
	if(!isset($prop['width']))
		$prop['width']=0;
	if($prop['width']==0)
		$prop['width']=$this->w-$this->lMargin-$this->rMargin;
	if(!isset($prop['align']))
		$prop['align']='C';
	if(!isset($prop['padding']))
		$prop['padding']=$this->cMargin;
	$cMargin=$this->cMargin;
	$this->cMargin=$prop['padding'];
	if(!isset($prop['HeaderColor']))
		$prop['HeaderColor']=array();
	$this->HeaderColor=$prop['HeaderColor'];
	if(!isset($prop['color1']))
		$prop['color1']=array();
	if(!isset($prop['color2']))
		$prop['color2']=array();
	$this->RowColors=array($prop['color1'],$prop['color2']);
	//Compute column widths
	$this->CalcWidths($prop['width'],$prop['align']);
	//Print header
	$this->TableHeader();
	//Print rows
	$this->SetFont('Arial','',11);
	$this->ColorIndex=0;
	$this->ProcessingTable=true;	
	while($row=mysql_fetch_array($res))
		$this->Row($row,$origen);					
	$this->ProcessingTable=false;
	$this->cMargin=$cMargin;
	$this->aCols=array();
}
}
?>
