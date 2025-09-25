	<?php //Archivos que permtien desabilitar teclas especificas, así como desabilitar el clic derecho?>
	<script language="javascript" type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script language="javascript" type="text/javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		document.onmousedown=click;
		//-->
	</script><?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Nadia Madahí López Hernández                     
	  * Fecha: 23/Febrero/2011                                      			
	  * Descripción: Este archivo contiene funciones para Ver las Graficas de Mantenimientos Preventivos, Correctivos, ambos y Orden de Trabajo
	  **/	
	
	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("../../includes/func_fechas.php");

	if(isset($_GET['graph'])){
		if($graph=="mttoPreventivo")
			generarGraficaPreventivos();
		if($graph=="mttoCorrectivo")
			generarGraficaCorrectivos();
		if($graph=="preventivoCorrectivo")
			generarGraficaPreventivoCorrectivo();
		if($graph=="ordenTrabajo")
			generarGraficaOrdenTrabajo();
	}
	
	
	/*Esta funcion se encarga de dibjar la grafica comparativa de Mantenimientos Preventivos en el periodo de tiempo seleccionado*/
	function generarGraficaPreventivos(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
				
		//Iniciar la SESSION
		session_start();
		//Obtener los datos de la SESSION
		$hdn_consulta = $_SESSION['datosGrapPreventivos']['hdn_consulta'];
		$hdn_msg = $_SESSION['datosGrapPreventivos']['hdn_msg'];
		
		$fechasPreventivo = array(0=>"posInicial");
		$cantPreventivo = array(0=>"posInicial");
		//Ejecutar la Consulta
		$rs = mysql_query($hdn_consulta);
		//Verificar los resultados obtenidos
		if($datos=mysql_fetch_array($rs)){
			do{
				//Cuando el arreglo este vacio agregar el primer registro directamente
				if(count($fechasPreventivo)==0){
					$fechasPreventivo[] = modFecha($datos['fecha_mtto'],1);
					$cantPreventivo[] = doubleval($datos['costo_mtto']);
				}
				else{
					//Verificar que la fecha no este repetida en el arreglo						
					$pos = array_search(modFecha($datos['fecha_mtto'],1),$fechasPreventivo);			
					if($pos==""){//Si no esta repetida agregar el registro al arreglo																				
						$fechasPreventivo[] = modFecha($datos['fecha_mtto'],1);
						$cantPreventivo[] = doubleval($datos['costo_mtto']);						
					}
					else{//Sumar la cantidad de la fecha repetida al registro previo de la misma fecha					
						$cantPreventivo[$pos] += doubleval($datos['costo_mtto']);
					}
				}				
			}while($datos=mysql_fetch_array($rs));
		}
		//Antes de comparar el tamanio de los arreglos, quitar la posicion 0 de cada uno
		unset($fechasPreventivo[0]); $fechasPreventivo = array_values($fechasPreventivo); //Vaciar la posicion 0 y Rectificar los indices
		unset($cantPreventivo[0]); $cantPreventivo = array_values($cantPreventivo); //Vaciar la posicion 0 y Rectificar los indices		
				
		//Verificar la Cantidad de mantenimientos encontrados
		if(count($fechasPreventivo)==1){
			$fechasPreventivo[] = "";
			$cantPreventivo[] = "null";				
		}														
		//Cerrar la conexion con la BD
		mysql_close($conn);?>
		
		<html>
		<head>
			<title>Gr&aacute;fica de Mantenimientos Preventivos</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-preventivos{ position:absolute; width:940px; height:450px; left:30px; top:80px; z-index: 1;} 
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-preventivos" align="center"></div>			
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			<script type="text/javascript">
					
				//Grafica de Mtto Preventivos - Manipular el Tamaño
				var maximo = <?php echo max($cantPreventivo); ?>;
				var cantDatos = <?php echo count($fechasPreventivo); ?>;
				var anchoGrafica = 0;
				var tamLetraX = 0;			
				if(cantDatos<=3){ tamLetraX = 12; anchoGrafica = 500; }
				if(cantDatos>=4 && cantDatos<=8){ tamLetraX = 12; anchoGrafica = 800; }
				if(cantDatos>=9 && cantDatos<=12){ tamLetraX = 10; anchoGrafica = 900; }
				if(cantDatos>=13 && cantDatos<=15){ tamLetraX = 8; anchoGrafica = 1000; }
				if(cantDatos>=16 && cantDatos<=20){ tamLetraX = 8; anchoGrafica = 1100; }
				if(cantDatos>=21){ tamLetraX = 6; anchoGrafica = 1200; }						
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", anchoGrafica, "450", "9", "#FFFFFF");		
					
				so.addVariable("variables","true");
				so.addVariable("title","PREVENTIVOS,{font-size:22px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("y_legend","Cantidad $,16,0xC93438");
				so.addVariable("y_label_size","15");
				so.addVariable("y_ticks","5,10,4");
				so.addVariable("bar","50,0xC93438,<?php echo $hdn_msg;?>,10");
				so.addVariable("values","<?php foreach($cantPreventivo as $key => $value){ if($key==count($cantPreventivo)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_legend","Fechas,16,0xC93438");
				so.addVariable("x_labels","<?php foreach($fechasPreventivo as $key => $value){ if($key==count($fechasPreventivo)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_label_style",tamLetraX+",#000,2,1");					
				so.addVariable("x_axis_steps","1");
				so.addVariable("y_max",maximo);
				so.addVariable("bg_colour","0xA5D8B6");
	
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-preventivos");								
			</script>
		</body>
		</html>
	<?php		
	}//Cierre de la funcion generarGraficaPreventivos()
	
	

	/*Esta funcion se encarga de dibjar la grafica comparativa de Mantenimientos Correctivos en el periodo de tiempo que el usuario haya seleccionado*/
	function generarGraficaCorrectivos(){
		//Realizar la conexion a la BD de Mantenimeinto
		$conn = conecta("bd_mantenimiento");
				
		//Iniciar la SESSION
		session_start();
		//Obtener los datos de la SESSION
		$hdn_consulta = $_SESSION['datosGrapCorrectivos']['hdn_consulta'];
		$hdn_msg = $_SESSION['datosGrapCorrectivos']['hdn_msg'];
		
		$fechasCorrectivo = array(0=>"posInicial");
		$cantCorrectivo = array(0=>"posInicial");
		//Ejecutar la Consulta
		$rs = mysql_query($hdn_consulta);
		//Verificar los resultados obtenidos
		if($datos=mysql_fetch_array($rs)){
			do{
				//Cuando el arreglo este vacio agregar el primer registro directamente
				if(count($fechasCorrectivo)==0){
					$fechasCorrectivo[] = modFecha($datos['fecha_mtto'],1);
					$cantCorrectivo[] = doubleval($datos['costo_mtto']);
				}
				else{					
					$pos = array_search(modFecha($datos['fecha_mtto'],1),$fechasCorrectivo);			
					if($pos==""){//Si no esta repetida agregar el registro al arreglo																				
						$fechasCorrectivo[] = modFecha($datos['fecha_mtto'],1);
						$cantCorrectivo[] = doubleval($datos['costo_mtto']);						
					}
					else{//Sumar la cantidad de la fecha repetida al registro previo de la misma fecha					
						$cantCorrectivo[$pos] += doubleval($datos['costo_mtto']);
					}
				}				
			}while($datos=mysql_fetch_array($rs));
		}
		//Antes de comparar el tamaño de los arreglos, quitar la posicion 0 de cada uno
		unset($fechasCorrectivo[0]); $fechasCorrectivo = array_values($fechasCorrectivo); //Vaciar la posicion 0 y Rectificar los indices
		unset($cantCorrectivo[0]); $cantCorrectivo = array_values($cantCorrectivo); //Vaciar la posicion 0 y Rectificar los indices		
				
		//Verificar la Cantidad de mantenimientos encontrados
		if(count($fechasCorrectivo)==1){
			$fechasCorrectivo[] = "";
			$cantCorrectivo[] = "null";				
		}															
		//Cerrar la conexion con la BD
		mysql_close($conn);?>
		
		<html>
		<head>
			<title>Gr&aacute;fica de Mantenimientos Correctivos</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-correctivos{ position:absolute; width:940px; height:450px; left:30px; top:80px; z-index: 1;} 
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-correctivos" align="center"></div>			
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			<script type="text/javascript">
					
				//Grafica de Mtto Correctivos - Manipuar el Tamaño
				var maximo = <?php echo max($cantCorrectivo); ?>;
				var cantDatos = <?php echo count($fechasCorrectivo); ?>;
				var anchoGrafica = 0;
				var tamLetraX = 0;			
				if(cantDatos<=3){ tamLetraX = 12; anchoGrafica = 500; }
				if(cantDatos>=4 && cantDatos<=8){ tamLetraX = 12; anchoGrafica = 800; }
				if(cantDatos>=9 && cantDatos<=12){ tamLetraX = 10; anchoGrafica = 900; }
				if(cantDatos>=13 && cantDatos<=15){ tamLetraX = 8; anchoGrafica = 1000; }
				if(cantDatos>=16 && cantDatos<=20){ tamLetraX = 8; anchoGrafica = 1100; }
				if(cantDatos>=21){ tamLetraX = 6; anchoGrafica = 1200; }						
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", anchoGrafica, "450", "9", "#FFFFFF");		
		
				so.addVariable("variables","true");
				so.addVariable("title","CORRECTIVOS,{font-size:22px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("y_legend","Cantidad $,16,0xC93438");
				so.addVariable("y_label_size","15");
				so.addVariable("y_ticks","5,10,4");
				so.addVariable("bar","50,0xC93438,<?php echo $hdn_msg;?>,10");
				so.addVariable("values","<?php foreach($cantCorrectivo as $key => $value){ if($key==count($cantCorrectivo)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_legend","Fechas,16,0xC93438");
				so.addVariable("x_labels","<?php foreach($fechasCorrectivo as $key => $value){ if($key==count($fechasCorrectivo)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_label_style",tamLetraX+",#000,2,1");					
				so.addVariable("x_axis_steps","1");
				so.addVariable("y_max",maximo);
				so.addVariable("bg_colour","0xA5D8B6");
	
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-correctivos");								
			</script>
		</body>
		</html>
	<?php		
	}//Cierre de la funcion generarGraficaCorrectivos()
	
	
	
	/*Esta funcion se encarga de dibujar la grafica comparativa de Mtto. Preventivos y Correctivos del periodo de tiempo seleccionado*/
	function generarGraficaPreventivoCorrectivo(){
		//Realizar la conexion a la BD de Mantenimeinto
		$conn = conecta("bd_mantenimiento");
		//Iniciar la SESSION
		
		session_start();
		//Obtener los datos de la SESSION
		$arrCantidades = $_SESSION['datosGrafica']['arrCantidades'];
		$arrCostos = $_SESSION['datosGrafica']['arrCostos'];
		$hdn_msg = $_SESSION['datosGrafica']['hdn_msg'];	
		
		//Obtener los datos para determinar el Maximo de las Cantidades
		if(isset($arrCantidades['concreto']['preventivo'])) $cantConcretoPre = $arrCantidades['concreto']['preventivo']; else $cantConcretoPre = 0;
		if(isset($arrCantidades['concreto']['correctivo'])) $cantConcretoCorr = $arrCantidades['concreto']['correctivo']; else $cantConcretoCorr = 0;
		if(isset($arrCantidades['mina']['preventivo'])) $cantMinaPre = $arrCantidades['mina']['preventivo']; else $cantMinaPre = 0;
		if(isset($arrCantidades['mina']['correctivo'])) $cantMinaCorr = $arrCantidades['mina']['correctivo']; else $cantMinaCorr = 0;
		
		//Obtener los datos para determinar el Maximo de los Costos
		if(isset($arrCostos['concreto']['preventivo'])) $costConcretoPre = $arrCostos['concreto']['preventivo']; else $costConcretoPre = 0;
		if(isset($arrCostos['concreto']['correctivo'])) $costConcretoCorr = $arrCostos['concreto']['correctivo']; else $costConcretoCorr = 0;
		if(isset($arrCostos['mina']['preventivo'])) $costMinaPre = $arrCostos['mina']['preventivo']; else $costMinaPre = 0;
		if(isset($arrCostos['mina']['correctivo'])) $costMinaCorr = $arrCostos['mina']['correctivo']; else $costMinaCorr = 0;
													
		//Obtener los Valores Maximos de Cantidad y Costo	
		$max_cantidad = max($cantConcretoPre,$cantConcretoCorr,$cantMinaPre,$cantMinaCorr);
		$max_costo = max($costConcretoPre,$costConcretoCorr,$costMinaPre,$costMinaCorr);
		
		
		//Si la varible vale cero, colocar un valor de null		
		if($cantConcretoPre==0) $cantConcretoPre = "null"; if($cantConcretoCorr==0) $cantConcretoCorr = "null"; if($cantMinaPre==0) $cantMinaPre = "null"; if($cantMinaCorr==0) $cantMinaCorr = "null";
		if($costConcretoPre==0) $costConcretoPre = "null"; if($costConcretoCorr==0) $costConcretoCorr = "null"; if($costMinaPre==0) $costMinaPre = "null"; if($costMinaCorr==0) $costMinaCorr = "null"; 
		
																	
		//Cerrar la conexion con la BD
		mysql_close($conn);?>
		
		<html>
		<head>
			<title>Gr&aacute;fica Comparativa de Mttos Preventivos/Correctivos</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-cantidades{ position:absolute; width:940px; height:250px; left:30px; top:20px; z-index: 1;} 
				#grafica-costos{ position:absolute; width:940px; height:250px; left:30px; top:290px; z-index: 1;} 
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-cantidades" align="center"></div>			
			<div id="grafica-costos" align="center"></div>			
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
			
			
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			
			<script type="text/javascript">
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "500", "230", "9", "#FFFFFF");												
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");						
				so.addVariable("title","Cantidad de Mttos Preventivos/Correctivos,{font-size:16px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Cantidad,14,#000");
				so.addVariable("x_legend","Tipos de Mantenimiento,14,#000");
				so.addVariable("y_ticks","5,12,5");
				so.addVariable("x_labels","Concreto,Mina");
				//so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","<?php echo $max_cantidad;?>");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("y_grid_colour","#ADB5C7");
				so.addVariable("bar_3d","75,#1031A3,Preventivos,10");			
				so.addVariable("values","<?php echo $cantConcretoPre.",".$cantMinaPre; ?>");
				so.addVariable("bar_3d_2","75,#C93438,Correctivos,10");			
				so.addVariable("values_2","<?php echo $cantConcretoCorr.",".$cantMinaCorr; ?>");
				
				
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-cantidades");																							
			</script>												
			
			
			<script type="text/javascript">
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "500", "230", "9", "#FFFFFF");												
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");						
				so.addVariable("title","Costos de Mttos Preventivos/Correctivos,{font-size:16px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Costos $,14,#000");
				so.addVariable("x_legend","Tipos de Mantenimiento,14,#000");
				so.addVariable("y_ticks","5,12,5");
				so.addVariable("x_labels","Concreto,Mina");
				//so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","<?php echo $max_costo;?>");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("y_grid_colour","#ADB5C7");
				so.addVariable("bar_3d","75,#1031A3,Preventivos,10");			
				so.addVariable("values","<?php echo $costConcretoPre.",".$costMinaPre; ?>");
				so.addVariable("bar_3d_2","75,#C93438,Correctivos,10");			
				so.addVariable("values_2","<?php echo $costConcretoCorr.",".$costMinaCorr; ?>");
				
				
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-costos");																							
			</script>
		</body>
		</html><?php		
	}//Cierre funcion generarGraficaPreventivoCorrectivo()
	
	
	
	
	/*Esta funcion se encarga de dibjar la grafica de Orden de Trabajo*/
	function generarGraficaOrdenTrabajo(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_mantenimiento");
				
		//Iniciar la SESSION
		session_start();
		//Obtener los datos de la SESSION
		$hdn_consulta = $_SESSION['datosGrafica']['hdn_consulta'];
		$hdn_msg = $_SESSION['datosGrafica']['hdn_msg'];
		
		$fechasOrden = array(0=>"posInicial");
		$cantOrden = array(0=>"posInicial");
		//Ejecutar la Consulta
		$rs = mysql_query($hdn_consulta);
		//Verificar los resultados obtenidos
		if($datos=mysql_fetch_array($rs)){
			do{
				//Cuando el arreglo este vacio agregar el primer registro directamente
				if(count($fechasOrden)==0){
					$fechasOrden[] = modFecha($datos['fecha_creacion'],1);
					$cantOrden[] = doubleval($datos['costo_mtto']);
				}
				else{
					//Verificar que la fecha no este repetida en el arreglo						
					$pos = array_search(modFecha($datos['fecha_creacion'],1),$fechasOrden);			
					if($pos==""){//Si no esta repetida agregar el registro al arreglo																				
						$fechasOrden[] = modFecha($datos['fecha_creacion'],1);
						$cantOrden[] = doubleval($datos['costo_mtto']);						
					}
					else{//Sumar la cantidad de la fecha repetida al registro previo de la misma fecha					
						$cantOrden[$pos] += doubleval($datos['costo_mtto']);
					}
				}				
			}while($datos=mysql_fetch_array($rs));
		}
		//Antes de comparar el tamanio de los arreglos, quitar la posicion 0 de cada uno
		unset($fechasOrden[0]); $fechasOrden = array_values($fechasOrden); //Vaciar la posicion 0 y Rectificar los indices
		unset($cantOrden[0]); $cantOrden = array_values($cantOrden); //Vaciar la posicion 0 y Rectificar los indices		
				
		//Verificar la Cantidad de orden de trabajo encontrados
		if(count($fechasOrden)==1){
			$fechasOrden[] = "";
			$cantOrden[] = "null";				
		}														
		//Cerrar la conexion con la BD
		mysql_close($conn);?>
		
		<html>
		<head>
			<title>Gr&aacute;fica Orden de Trabajo</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-orden{ position:absolute; width:940px; height:450px; left:30px; top:80px; z-index: 1;} 
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-orden" align="center"></div>			
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			<script type="text/javascript">
					
				//Grafica Orden Trabajo - Manipular el Tamaño
				var maximo = <?php echo max($cantOrden); ?>;
				var cantDatos = <?php echo count($fechasOrden); ?>;
				var anchoGrafica = 0;
				var tamLetraX = 0;			
				if(cantDatos<=3){ tamLetraX = 12; anchoGrafica = 500; }
				if(cantDatos>=4 && cantDatos<=8){ tamLetraX = 12; anchoGrafica = 800; }
				if(cantDatos>=9 && cantDatos<=12){ tamLetraX = 10; anchoGrafica = 900; }
				if(cantDatos>=13 && cantDatos<=15){ tamLetraX = 8; anchoGrafica = 1000; }
				if(cantDatos>=16 && cantDatos<=20){ tamLetraX = 8; anchoGrafica = 1100; }
				if(cantDatos>=21){ tamLetraX = 6; anchoGrafica = 1200; }						
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", anchoGrafica, "450", "9", "#FFFFFF");		
					
				so.addVariable("variables","true");
				so.addVariable("title","ORDEN DE TRABAJO,{font-size:22px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("y_legend","Cantidad $,16,0xC93438");
				so.addVariable("y_label_size","15");
				so.addVariable("y_ticks","5,10,4");
				so.addVariable("bar","50,0xC93438,<?php echo $hdn_msg;?>,10");
				so.addVariable("values","<?php foreach($cantOrden as $key => $value){ if($key==count($cantOrden)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_legend","Fechas,16,0xC93438");
				so.addVariable("x_labels","<?php foreach($fechasOrden as $key => $value){ if($key==count($fechasOrden)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_label_style",tamLetraX+",#000,2,1");					
				so.addVariable("x_axis_steps","1");
				so.addVariable("y_max",maximo);
				so.addVariable("bg_colour","0xA5D8B6");
	
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-orden");								
			</script>
		</body>
		</html>
	<?php		
	}//Cierre de la funcion generarGraficaOrdenTrabajo()
	
?>

	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	