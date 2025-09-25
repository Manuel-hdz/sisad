	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �CONCRETO LANZADO DE FRESNILLO MARCA');
			}
		}
		document.onmousedown=click;						
		//-->
	</script>
<?php
	/**
	  * Nombre del M�dulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Mart�nez Fern�ndez                     
	  * Fecha: 19/Abril/2011                                      			
	  * Descripci�n: Este archivo contiene funciones para Ver las Graficas de loa Reportes del correspondiente M�dulo
	  **/	
	
	//Incluimos archivos para realizar conexcion, operaciones con la BD,  y func_fechas para modificar fechas segun fuere necesario
	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("../../includes/func_fechas.php");

	if(isset($_GET['graph'])){
		if($graph=="asistenciaArea")
			generarGraficaAsistencias();
		if($graph=="asistenciaFecha")
			generarGraficaAsistenciasFechas();
		if($graph=="incapacidadesArea")
			generarGraficaIncapacidades();
		if($graph=="incapacidadesFecha")
			generarGraficaIncapacidadesFechas();
		if($graph=="ausentismoArea")
			generarGraficaAusentismo();
		if($graph=="ausentismoFecha")
			generarGraficaAusentismoFechas();	
		if($graph=="Altas")
			generarGraficaAltasBajasFechas();	
	}
	
	
	
	/*Esta funcion se encarga de dibjar la grafica de area y fechas especificas*/
	function generarGraficaAsistencias(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
				
		//Iniciar la SESSION
		session_start();
		//Obtener los datos de la SESSION
		//Variable que guarda la cantidad de asistencias que tuvo el empleado
		$asistencias = $_SESSION['datosGrapAsistencias']['asistencias'];
		//Guarda el msj que sera mostrado en la grafica
		$msg = $_SESSION['datosGrapAsistencias']['hdn_msg'];
		//Guarda el area que sera mostrada en la pantalla
		$area = $_SESSION['datosGrapAsistencias']['area'];
		//Muestra el total de asistencia que debio tener
		$totalAsistencia = $_SESSION['datosGrapAsistencias']['diferencia'];
		//Obtener los procentajes para mostrar en la Grafica
		$porcentAsistencia = intval( ($asistencias/$totalAsistencia) * 100 );
		?>
		<html>
		<head>
			<title>Gr&aacute;fica Comparativa de Asistencias</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-asistencias{ position:absolute; width:1000px; height:460px; left:30px; top:20px; z-index: 1;}
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-asistencias" align="center"></div>
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
						
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			
			<script type="text/javascript">				
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "600", "460", "9", "#FFFFFF");
				
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");
				//Definir el Titulo del grafico
				so.addVariable("title","<?php echo $msg; ?>,{font-size:16px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Porcentaje de Asistencia %25,14,#000");
				so.addVariable("x_legend","�reas,14,#000");
				so.addVariable("y_ticks","5,18,5");
				so.addVariable("x_labels","<?php echo $area;?>");
				//so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","100");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("y_grid_colour","#ADB5C7");
				so.addVariable("bar_3d","75,#1031A3,<?php echo $totalAsistencia;?>D�as Representa el 100 %25 de Asistencia,10");			
				so.addVariable("values","<?php echo $porcentAsistencia; ?>");
				so.addVariable("tool_tip","#x_label#<br>#val#%25 Asistencia");
				so.addVariable("y_format","#val#%25");
								
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-asistencias");
			</script>
		</body>
		</html><?php		
			
	}//Cierre de la funcion generarGraficaPreventivos()
	
	
	/*Esta funcion se encarga de dibjar la grafica  de fechas especificas*/
	function generarGraficaAsistenciasFechas(){

		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
						
		//Iniciar la SESSION
		session_start();
		
		//Creamos arreglo en el cual se guardara la asistencia por area
		$asistenciasXArea = array();
		//Guardamos los datos que vienen en la sessi�n
		//Guardamos el mensaje que sera mostrado en la gr�fica
		$msg = $_SESSION['datosGrapAsistencias']['hdn_msg'];
		//Variable que guarda el total de asistencia que se debio cumplor por area
		$totalAsistencia = $_SESSION['datosGrapAsistencias']['diferencia'];
		
		//Calcular el Porcentaje de asistencia por cada Area; tenemos que reccorrer el arreglo que viene en la sesi�n y que tiene las asistencias
		foreach($_SESSION['datosGrapAsistencias']['asistencias'] as $key => $value)
			//Convertimos  el valor de la asistencia por area en numero y lo convertimos en porcentaje
			$asistenciasXArea[$key] = intval( ($value/$totalAsistencia) * 100 );
		
		//Obtener la Cantidad de Areas a graficar
		$tam = count($asistenciasXArea);
		?>
		
		<html>
		<head>
			<title>Gr&aacute;fica Comparativa de Asistencias</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-asistencias{ position:absolute; width:1000px; height:460px; left:30px; top:20px; z-index: 1;}
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-asistencias" align="center"></div>
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
						
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			
			<script type="text/javascript">				
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "800", "460", "9", "#FFFFFF");
				
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");						
				so.addVariable("title","<?php echo $msg; ?>,{font-size:16px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Porcentaje de Asistencia %25,14,#000");
				so.addVariable("x_legend","�reas,14,#000");
				so.addVariable("y_ticks","5,18,5");
				so.addVariable("x_labels","<?php 
					//Variable para controlar el fin del IF					
					$cont = 1;
					//Recorremos el arreglo asistencia por area para mostrar los rotulos
					foreach($asistenciasXArea as $key => $value){ 
						if($cont==$tam) 
							echo $key; 
						else 
							echo $key.","; 
						$cont++;
					}?>");
				//so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","100");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("bar_3d","75,#1031A3,<?php echo $totalAsistencia;?> D�as Representan el 100%25 de Asistencia,10");			
				so.addVariable("values","<?php 					
					//Variable para controlar el fin del IF en el foreach
					$cont = 1;
					//Recorremos el arreglo para asignar el valor real de las asistencias
					foreach($asistenciasXArea as $key => $value){ 
						if($cont==$tam) 
							echo $value; 
						else 
							echo $value.","; 
						$cont++;
					}?>");
				so.addVariable("tool_tip","#x_label#<br>#val#%25 Asistencia");
				so.addVariable("y_format","#val#%25");
								
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-asistencias");
			</script>
			</body>
		</html><?php		
				
			
	}//Cierre de la funcion 


	/*Esta funcion se encarga de dibjar la grafica de area y fechas especificas*/
	function generarGraficaIncapacidades(){
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
				
		//Iniciar la SESSION
		session_start();
		//Obtener los datos de la SESSION
		//Variable que almacenara el total de incapacidades
		$incapacidades = $_SESSION['datosGrapIncapacidades']['Incapacidades'];
		//Variable donde se guardara el msj que despues sera desplegado en la grafica
		$msg = $_SESSION['datosGrapIncapacidades']['hdn_msg'];
		//Guarda el area donde se busco originar incapacidades
		$area = $_SESSION['datosGrapIncapacidades']['area'];
		//Variable que indica las asistencias que devio tener
		$totalIncapacidades = $_SESSION['datosGrapIncapacidades']['diferencia'];
		//Obtener los procentajes para mostrar en la Grafica
		$porcentIncapacidades = intval( ($incapacidades/$totalIncapacidades) * 100 );
		?>
		
		<html>
		<head>
			<title>Gr&aacute;fica Comparativa de Incapacidades</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-incapacidades{ position:absolute; width:1000px; height:460px; left:30px; top:20px; z-index: 1;}
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-incapacidades" align="center"></div>
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
						
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>		
			<script type="text/javascript">				
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "700", "460", "9", "#FFFFFF");
				
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");
				//Definir el Titulo del grafico
				so.addVariable("title","<?php echo $msg;?>,{font-size:16px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Porcentaje de Incapacidades %25,14,#000");
				so.addVariable("x_legend","�reas,14,#000");
				so.addVariable("y_ticks","5,18,5");
				so.addVariable("x_labels","<?php echo $area;?>");
				//so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","100");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("y_grid_colour","#ADB5C7");
				so.addVariable("bar_3d","75,#C93438,Porcentaje Incapacidades en un periodo de <?php echo $totalIncapacidades; ?> D�as,10");			
				so.addVariable("values","<?php echo $porcentIncapacidades; ?>");
				so.addVariable("tool_tip","#x_label#<br>#val#%25");
				so.addVariable("y_format","#val#%25");
								
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-incapacidades");
			</script>
			</body>
		</html><?php		
				
			
	}//Cierre de la funcion generarGraficaPreventivos()

	
	/*Esta funcion se encarga de dibjar la grafica  de fechas especificas*/
	function generarGraficaIncapacidadesFechas(){

		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
						
		//Iniciar la SESSION
		session_start();
		
		//Creamos arreglo en el cual se guardara la incapacidades por area
		$IncapacidadesXArea = array();
		//Guardamos los datos que vienen en la sessi�n
		//Guarda el msj que despues sera desplegado en la pantalla
		$msg = $_SESSION['datosGrapIncapacidades']['hdn_msg'];
		//Muestra el total de asistencia 
		$totalIncapacidades = $_SESSION['datosGrapIncapacidades']['diferencia'];
		
		//Calcular el Porcentaje de incapacidades por cada Area; tenemos que reccorrer el arreglo que viene en la sesi�n y que tiene las incapacidades
		foreach($_SESSION['datosGrapIncapacidades']['Incapacidades'] as $key => $value)
			//Convertimos  el valor de la asistencia por area en numero y lo convertimos en porcentaje
			$IncapacidadesXArea[$key] = intval( ($value/$totalIncapacidades) * 100 );
		
		//Obtener la Cantidad de Areas a graficar
		$tam = count($IncapacidadesXArea);
		?>
		
		<html>
		<head>
			<title>Gr&aacute;fica Comparativa de Incapacidades</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-incapacidades{ position:absolute; width:1000px; height:460px; left:30px; top:20px; z-index: 1;}
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-incapacidades" align="center"></div>
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
						
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			
			<script type="text/javascript">				
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "1000", "460", "9", "#FFFFFF");
				
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");						
				so.addVariable("title","<?php echo $msg;?>,{font-size:16px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Porcentaje de Incapacidades %25,14,#000");
				so.addVariable("x_legend","�reas,14,#000");
				so.addVariable("y_ticks","5,18,5");
				so.addVariable("x_labels","<?php 
					//Variable para controlar el fin del IF					
					$cont = 1;
					//Recorremos el arreglo asistencia por area para mostrar los rotulos
					foreach($IncapacidadesXArea as $key => $value){ 
						if($cont==$tam) 
							echo $key; 
						else 
							echo $key.","; 
						$cont++;
					}?>");
				//so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","100");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("y_grid_colour","#ADB5C7");
				so.addVariable("bar_3d","75,#C93438,Porcentaje de Incapacidades en un periodo de <?php echo $totalIncapacidades; ?> D�as,10");			
				so.addVariable("values","<?php 					
					//Variable para controlar el fin del IF en el foreach
					$cont = 1;
					//Recorremos el arreglo para asignar el valor real de las asistencias
					foreach($IncapacidadesXArea as $key => $value){ 
						if($cont==$tam) 
							echo $value; 
						else 
							echo $value.","; 
						$cont++;
					}?>");
				so.addVariable("tool_tip","#x_label#<br>#val#%25");
				so.addVariable("y_format","#val#%25");
								
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-incapacidades");
			</script>
			</body>
		</html><?php		
				
			
	}//Cierre de la funcion 
	
	
	
	/*Esta funcion se encarga de dibjar la grafica de area y fechas especificas*/
	function generarGraficaAusentismo(){
		
		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
				
		//Iniciar la SESSION
		session_start();
		//Obtener los datos de la SESSION
		//Guarda el total de ausentismo generado por area
		$Ausentismo = $_SESSION['datosGrapAusentismo']['Ausentismo'];
		//Guarda el msj que despues sera mostrado en la grafica
		$msg = $_SESSION['datosGrapAusentismo']['hdn_msg'];
		//Indica las areas
		$area = $_SESSION['datosGrapAusentismo']['area'];
		//mnuestra el total de ausentismo que se debio tener
		$totalAusentismo = $_SESSION['datosGrapAusentismo']['diferencia'];
		//Obtener los procentajes para mostrar en la Grafica
		$porcentAusentismo = intval( ($Ausentismo/$totalAusentismo) * 100 );
		
		?>
		
		<html>
		<head>
			<title>Gr&aacute;fica Comparativa de Ausentismo</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-ausentismo{ position:absolute; width:1000px; height:460px; left:30px; top:20px; z-index: 1;}
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-ausentismo" align="center"></div>
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
						
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>		
			<script type="text/javascript">				
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "700", "460", "9", "#FFFFFF");
				
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");
				//Definir el Titulo del grafico
				so.addVariable("title","<?php echo $msg; ?>,{font-size:16px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Porcentaje de Ausentismo %25,14,#000");
				so.addVariable("x_legend","�reas,14,#000");
				so.addVariable("y_ticks","5,18,5");
				so.addVariable("x_labels","<?php echo $area;?>");
				//so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","100");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("y_grid_colour","#ADB5C7");
				so.addVariable("bar_3d","75,#C93438,Porcentaje de Ausentismo en un periodo de <?php echo $totalAusentismo;?> D�as,10");			
				so.addVariable("values","<?php echo $porcentAusentismo; ?>");
				so.addVariable("tool_tip","#x_label#<br>#val#%25");
				so.addVariable("y_format","#val#%25");
								
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-ausentismo");
			</script>
			</body>
		</html><?php		
				
			
	}//Cierre de la funcion 
	
	
	/*Esta funcion se encarga de dibjar la grafica  de fechas especificas*/
	function generarGraficaAusentismoFechas(){

		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
						
		//Iniciar la SESSION
		session_start();
		
		//Creamos arreglo en el cual se guardara el ausentismo por area
		$AusentismoXArea = array();
		//Guardamos los datos que vienen en la sessi�n
		//Variable que almacena el msj para despues sea mostrado en la grafica
		$msg = $_SESSION['datosGrapAusentismo']['hdn_msg'];
		//Guarda el total de asistenci que sebio tener
		$totalAusentismo = $_SESSION['datosGrapAusentismo']['diferencia'];
		
		//Calcular el Porcentaje de ausentismo por cada Area; tenemos que reccorrer el arreglo que viene en la sesi�n y que tiene las ausentismo
		foreach($_SESSION['datosGrapAusentismo']['Ausentismo'] as $key => $value)
			//Convertimos  el valor del ausentismo por area en numero y lo convertimos en porcentaje
			$AusentismoXArea[$key] = intval( ($value/$totalAusentismo) * 100 );
		
		//Obtener la Cantidad de Areas a graficar
		$tam = count($AusentismoXArea);
		?>
		
		<html>
		<head>
			<title>Gr&aacute;fica Comparativa de Ausentismo</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-ausentismo{ position:absolute; width:1000px; height:460px; left:30px; top:20px; z-index: 1;}
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-ausentismo" align="center"></div>
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
						
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			
			<script type="text/javascript">				
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "1000", "460", "9", "#FFFFFF");
				
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");						
				so.addVariable("title","Gr�fica de Ausentismo,{font-size:16px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Porcentaje de Ausentismo %25,14,#000");
				so.addVariable("x_legend","�reas,14,#000");
				so.addVariable("y_ticks","5,18,5");
				so.addVariable("x_labels","<?php 
					//Variable para controlar el fin del IF					
					$cont = 1;
					//Recorremos el arreglo ausentismo por area para mostrar los rotulos
					foreach($AusentismoXArea as $key => $value){ 
						if($cont==$tam) 
							echo $key; 
						else 
							echo $key.","; 
						$cont++;
					}?>");
				//so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","100");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("y_grid_colour","#ADB5C7");
				so.addVariable("bar_3d","75,#C93438,Porcentaje de Ausentismo en un periodo de <?php echo $totalAusentismo;?> D�as,10");			
				so.addVariable("values","<?php 					
					//Variable para controlar el fin del IF en el foreach
					$cont = 1;
					//Recorremos el arreglo para asignar el valor real del ausentismo
					foreach($AusentismoXArea as $key => $value){ 
						if($cont==$tam) 
							echo $value; 
						else 
							echo $value.","; 
						$cont++;
					}?>");
				so.addVariable("tool_tip","#x_label#<br>#val#%25");
				so.addVariable("y_format","#val#%25");
								
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-ausentismo");
			</script>
			</body>
		</html><?php		
				
			
	}//Cierre de la funcion 


	/*Esta funcion se encarga de dibjar la grafica  de fechas especificas*/
	function generarGraficaAltasBajasFechas(){

		//Realizar la conexion a la BD 
		$conn = conecta("bd_recursos");
								
		//Iniciar la SESSION
		session_start();
		
		//Obtener los datos que vienen en la sessi�n
		//Guarda el msj que despues sera desplegado en la grafica
		$msg = $_SESSION['datosGrapAltas']['hdn_msg'];
		//Guarda la cantida de altas por area
		$cantAltas = $_SESSION['datosGrapAltas']['arrAreas'];
		//Guarda la cantidad de bajas por area
		$cantBajas = $_SESSION['datosGrapBajas'];		
		//Obtener el valor maximo entre las Altas y las Bajas		
		$yMax = max(max($cantAltas), max($cantBajas));
		//Combinar los Indices de los Arreglos de Altas y Bajas y quitar los valores repetidos y Ordenar el Resultado
		$indices = array_unique(array_merge(array_keys($cantAltas),array_keys($cantBajas)));
		sort($indices);
				
		//Crear el Arreglo con las cantidades de Altas, Si el arreglo Final de las Areas es igual al 
		//arreglo de las cantidades de Altas, entonces dicho arreglo queda igual
		$temCantAltas = array();
		if(count($cantAltas)!=count($indices)){
			//Recorrer los indices del Arreglo Base(Rotulos del Eje de las X's)
			foreach($indices as $key => $nomArea){
				//Recorrer los valores del Arreglo de Altas para Compararlo con los indices del Arreglo Base
				foreach($cantAltas as $ind => $valor){					
					if($ind==$nomArea){//Si la comparacion es verdadera, copiar la cantidad de Altas al arreglo temporal
						$temCantAltas[$nomArea] = $valor;
						break;//Si la clave fue encontrada, romper el ciclo, ya que dicha clave no se repetira dentro del arreglo de Altas
					}
					else
						$temCantAltas[$nomArea] = "0";					
				}//Cierre foreach
			}//Cierre foreach
			
			//Copiar el nuevo arreglo de cantidades al arreglo de Cantidades de Compras
			$cantAltas = $temCantAltas;
		}
		
		
		//Crear el Arreglo con las cantidades de Bajas, Si el arreglo Final de las Areas es igual al 
		//arreglo de las cantidades de Bajas, entonces dicho arreglo queda igual
		$temCantBajas = array();
		if(count($cantBajas)!=count($indices)){
			//Recorrer los indices del Arreglo Base(Rotulos del Eje de las X's)
			foreach($indices as $key => $nomArea){
				//Recorrer los valores del Arreglo de Altas para Compararlo con los indices del Arreglo Base
				foreach($cantBajas as $ind => $valor){					
					if($ind==$nomArea){//Si la comparacion es verdadera, copiar la cantidad de Altas al arreglo temporal
						$temCantBajas[$nomArea] = $valor;
						break;//Si la clave fue encontrada, romper el ciclo, ya que dicha clave no se repetira dentro del arreglo de Altas
					}
					else
						$temCantBajas[$nomArea] = "0";					
				}//Cierre foreach
			}//Cierre foreach
			
			//Copiar el nuevo arreglo de cantidades al arreglo de Cantidades de Compras
			$cantBajas = $temCantBajas;
		}?>
		
		
		<html>
		<head>
			<title>Gr&aacute;fica Comparativa de Altas VS Bajas</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-altasBajas{ position:absolute; width:1000px; height:460px; left:30px; top:20px; z-index: 1;}
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body>
			<div id="grafica-altasBajas" align="center"></div>
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
						
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			
			<script type="text/javascript">				
				
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "1000", "460", "9", "#FFFFFF");
				
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");						
				so.addVariable("title","<?php echo $msg;?>,{font-size:16px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Altas/Bajas por �rea,14,#000");
				so.addVariable("x_legend","�reas,14,#000");
				so.addVariable("y_ticks","5,18,5");
				so.addVariable("x_labels","<?php 
					$tamInds = count($indices);
					//Variable para controlar el fin del IF dentro del foreach
					$cont = 1;
					//Recorremos el arreglo Indices para colocarlos como rotulos en el Eje de las X's
					foreach($indices as $key => $value){ 
						if($cont==$tamInds) 
							echo $value; 
						else 
							echo $value.","; 
						$cont++;
					}?>");
				//so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","<?php echo $yMax;?>");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("y_grid_colour","#ADB5C7");
				so.addVariable("bar_3d","75,#1031A3,Altas,10");			
				so.addVariable("values","<?php 					
					//Variable para controlar el fin del IF					
					$cont = 1;
					//Recorremos el arreglo altas por area para mostrar los rotulos
					foreach($cantAltas as $key => $value){ 
						if($cont==$tamInds) 
							echo $value; 
						else 
							echo $value.","; 
						$cont++;
						}?>");
				so.addVariable("bar_3d_2","75,#C93438,Bajas,10");			
				so.addVariable("values_2","<?php 					
					//Variable para controlar el fin del IF					
					$cont = 1;
					//Recorremos el arreglo bajas por area para mostrar los rotulos
					foreach($cantBajas as $key => $value){ 
						if($cont==$tamInds) 
							echo $value; 
						else 
							echo $value.","; 
						$cont++;
					}?>");								
										
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-altasBajas");
			</script>
			</body>
		</html><?php		
				
			
	}//Cierre de la funcion 


?>