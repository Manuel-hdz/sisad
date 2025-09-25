<?php
require_once "../../includes/graficas/jpgraph/jpgraph.php";
require_once "../../includes/graficas/jpgraph/jpgraph_canvas.php";
require_once "../../includes/graficas/jpgraph/jpgraph_barcode.php";

	$code=8;
	$backend="IMAGEPNG";
	$pswidth="";
	$modwidth=3;
	$showframe=1;
	$height=70;
	$data=$_GET["id_material"];
	$file="";
	
	$params = array(
			array('code',8),array('data',$_GET["id_material"]),array('modwidth',3),array('info',false),
			array('notext',false),array('checksum',false),array('showframe',1),
			array('vertical',false) , array('backend','IMAGEPNG'), array('file',''),
			array('scale',1), array('height',70), array('pswidth','') 
			);
	
	$encoder = BarcodeFactory::Create($code);
    $b =  $backend=='EPS' ? 'PS' : $backend;
    $b = substr($backend,0,5) == 'IMAGE' ? 'IMAGE' : $b;
    $e = BackendFactory::Create($b,$encoder);
    if( substr($backend,0,5) == 'IMAGE' ) {
	if( substr($backend,5,1) == 'J' ) 
	    $e->SetImgFormat('JPEG');
    }
    if( $e ) {
	if( $backend == 'EPS' )
	    $e->SetEPS();
	if( $pswidth!='' )
	    $modwidth = $pswidth;
	$e->SetModuleWidth($modwidth);
	$e->ShowFrame($showframe);
	$e->SetHeight($height);
	$r = $e->Stroke($data,$file);
	if( $r )
	    echo nl2br(htmlspecialchars($r));

	if( $file != '' )
	    echo "<p>Wrote file $file.";
    }
    else
	echo "<h3>Can't create choosen backend: $backend.</h3>";

?>