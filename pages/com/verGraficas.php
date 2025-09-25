<?php
	/**
	  * Nombre del Módulo: Compras
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 12/Febrero/2011                                      			
	  * Descripción: Este archivo contiene funciones para Ver las Graficas de Compras, Ventas y Comparativa de Amabas
	  **/	
	
	include ("../../includes/conexion.inc");
	include("../../includes/op_operacionesBD.php");
	include("../../includes/func_fechas.php");

	if(isset($_GET['graph'])){
		if($graph=="Compra")
			generarGraficaCompra();
		if($graph=="Venta")
			generarGraficaVenta();
		if($graph=="CompraVenta")
			generarGraficaCompraVenta();
	}
	
	
		/*Esta funcion se encarga de dibjar la grafica comparativa de Compras en el periodo de tiempo seleccionado*/
	function generarGraficaCompra(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
				
		//Iniciar la SESSION
		session_start();
		//Obtener los datos de la SESSION
		$hdn_consulta = $_SESSION['datosGrapCompras']['hdn_consulta'];
		$hdn_msg = $_SESSION['datosGrapCompras']['hdn_msg'];
		
		$fechasCompras = array(0=>"posInicial");
		$cantCompras = array(0=>"posInicial");
		//Ejecutar la Consulta
		$rs = mysql_query($hdn_consulta." ORDER BY fecha_entrega");
		//Verificar los resultados obtenidos
		if($datos=mysql_fetch_array($rs)){
			do{
				//Cuando el arreglo este vacio agregar el primer registro directamente
				if(count($fechasCompras)==0){
					/*Mostrar todos los pedidos independientemente de su estado, sí solo se quisieran mostrar los pagados cambiar la condicion a *** if($datos['estado']=="PAGADO")   *** y tomar la fecha del 
					 *campo de 'fecha_pago' en lugar del campo 'fecha'*/
					if($datos['estado']=="PAGADO" || $datos['estado']=="NO PAGADO"){
						$fechasCompras[] = modFecha($datos['fecha'],1);
						$cantCompras[] = doubleval($datos['total']);
					}
				}
				else{
					/*Mostrar todos los pedidos independientemente de su estado, sí solo se quisieran mostrar los pagados cambiar la condicion a *** if($datos['estado']=="PAGADO")   *** y tomar la fecha del 
					 *campo de 'fecha_pago' en lugar del campo 'fecha'*/
					if($datos['estado']=="PAGADO" || $datos['estado']=="NO PAGADO"){
						//Verificar que la fecha no este repetida en el arreglo						
						$pos = array_search(modFecha($datos['fecha'],1),$fechasCompras);			
						if($pos==""){//Si no esta repetida agregar el registro al arreglo																				
							$fechasCompras[] = modFecha($datos['fecha'],1);
							$cantCompras[] = doubleval($datos['total']);						
						}
						else{//Sumar la cantidad de la fecha repetida al registro previo de la misma fecha					
							$cantCompras[$pos] += doubleval($datos['total']);
						}
					}//Cierre if($datos['estado']=="PAGADO")
				}				
			}while($datos=mysql_fetch_array($rs));
		}
		
		//Antes de comparar el tamanio de los arreglos, quitar la posicion 0 de cada uno
		unset($fechasCompras[0]); $fechasCompras = array_values($fechasCompras); //Vaciar la posicion 0 y Rectificar los indices
		unset($cantCompras[0]); $cantCompras = array_values($cantCompras); //Vaciar la posicion 0 y Rectificar los indices		
						
			
		//Verificar la Cantidad de Compras encontradas
		if(count($fechasCompras)==1){
			$fechasCompras[] = "";
			$cantCompras[] = "null";				
		}					
												
		//Cerrar la conexion con la BD
		mysql_close($conn);?>
		
		
		<html>
		<head>
			<title>Gr&aacute;fica de Compras</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-compras{ position:absolute; width:940px; height:450px; left:30px; top:80px; z-index: 1;} 
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body style="cursor:url('../../images/cursor.cur');">
			<div id="grafica-compras" align="center"></div>			
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			<script type="text/javascript">
					
				//Grafica de Compras - Manipuar el Tamaño
				var maximo = <?php echo max($cantCompras); ?>;
				var cantDatos = <?php echo count($fechasCompras); ?>;
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
				so.addVariable("title","COMPRAS,{font-size:22px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("y_legend","Cantidad $,16,0xC93438");
				so.addVariable("y_label_size","15");
				so.addVariable("y_ticks","5,10,4");
				so.addVariable("bar","50,0xC93438,<?php echo $hdn_msg;?>,10");
				so.addVariable("values","<?php foreach($cantCompras as $key => $value){ if($key==count($cantCompras)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_legend","Fechas,16,0xC93438");
				so.addVariable("x_labels","<?php foreach($fechasCompras as $key => $value){ if($key==count($fechasCompras)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_label_style",tamLetraX+",#000,2,1");					
				so.addVariable("x_axis_steps","1");
				so.addVariable("y_max",maximo);
				so.addVariable("bg_colour","0xA5D8B6");
	
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-compras");								
			</script>
		</body>
		</html><?php		
	}//Cierre de la funcion generarGraficaCompra()
	
	
	/*Esta funcion se encarga de dibjar la grafica comparativa de Ventas en el periodo de tiempo seleccionado*/
	function generarGraficaVenta(){
		//Realizar la conexion a la BD de Compras
		$conn = conecta("bd_compras");
		
		//Iniciar la SESSION
		session_start();
		//Obtener los datos de la SESSION
		$hdn_consulta = $_SESSION['datosGrapVentas']['hdn_consulta'];
		$hdn_msg = $_SESSION['datosGrapVentas']['hdn_msg'];
		
		
		$fechasVentas = array(0=>"posInicial");
		$cantVentas = array(0=>"posInicial");
		//Ejecutar la Consulta
		$rs = mysql_query($hdn_consulta.", fecha");
		//Verificar los resultados obtenidos
		if($datos=mysql_fetch_array($rs)){
			do{
				//Cuando el arreglo este vacio agregar el primer registro directamente
				if(count($fechasVentas)==0){
					$fechasVentas[] = modFecha($datos['fecha'],1);
					$cantVentas[] = doubleval($datos['total']);
				}
				else{
					//Verificar que la fecha no este repetida en el arreglo
					$pos = array_search(modFecha($datos['fecha'],1),$fechasVentas);			
					if($pos==""){//Si no esta repetida agregar el registro al arreglo						
						$fechasVentas[] = modFecha($datos['fecha'],1);
						$cantVentas[] = doubleval($datos['total']);
					}
					else{//Sumar la cantidad de la fecha repetida al registro previo de la misma fecha					
						$cantVentas[$pos] += doubleval($datos['total']);
					}
				}				
			}while($datos=mysql_fetch_array($rs));
		}
		
		//Antes de comparar el tamanio de los arreglos, quitar la posicion 0 de cada uno
		unset($fechasVentas[0]); $fechasVentas = array_values($fechasVentas); //Vaciar la posicion 0 y Rectificar los indices
		unset($cantVentas[0]); $cantVentas = array_values($cantVentas); //Vaciar la posicion 0 y Rectificar los indices		
			
			
		//Verificar la Cantidad de Ventas encontradas
		if(count($fechasVentas)==1){
			$fechasVentas[] = "";
			$cantVentas[] = "null";				
		}					
												
		//Cerrar la conexion con la BD
		mysql_close($conn);?>
		
		
		<html>
		<head>
			<title>Gr&aacute;fica de Ventas</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-ventas{ position:absolute; width:940px; height:450px; left:30px; top:80px; z-index: 1;} 
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body style="cursor:url('../../images/cursor.cur');">
			<div id="grafica-ventas" align="center"></div>			
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			<script type="text/javascript">
					
				
				//Grafica de Ventas - Manipuar el Tamaño
				var maximo = <?php echo max($cantVentas); ?>;
				var cantDatos = <?php echo count($fechasVentas); ?>;
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
				so.addVariable("title","VENTAS,{font-size:22px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("y_legend","Cantidad $,16,0x1031A3");
				so.addVariable("y_label_size","15");
				so.addVariable("y_ticks","5,10,4");
				so.addVariable("bar","50,0x1031A3,<?php echo $hdn_msg;?>,10");
				so.addVariable("values","<?php foreach($cantVentas as $key => $value){ if($key==count($cantVentas)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_legend","Fechas,16,0x1031A3");
				so.addVariable("x_labels","<?php foreach($fechasVentas as $key => $value){ if($key==count($fechasVentas)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_label_style",tamLetraX+",#000,2,1");
				so.addVariable("x_axis_steps","1");
				so.addVariable("y_max",maximo);
				so.addVariable("bg_colour","0xA5D8B6");
	
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-ventas");								
			</script>
		</body>
		</html><?php	
	}//Cierre de la funcion generarGraficaVenta()
	

	/*Esta funcion se encarga de dibjar la grafica comparativa de Compras y Ventas del periodo de tiempo seleccionado*/
	function generarGraficaCompraVenta(){
		//Iniciar la SESSION
		session_start();
		//Recuperar los datos de la SESSION - COMPRAS
		$fechas_compras = $_SESSION['datosGrafica']['fechas_compras']; $cant_compras = $_SESSION['datosGrafica']['cant_compras']; 
		$max_compras = $_SESSION['datosGrafica']['max_compras']; $msg_compras = $_SESSION['datosGrafica']['msg_compras'];
		//Recuperar los datos de la SESSION - VENTAS		
		$fechas_ventas = $_SESSION['datosGrafica']['fechas_ventas']; $cant_ventas = $_SESSION['datosGrafica']['cant_ventas'];
		$max_ventas = $_SESSION['datosGrafica']['max_ventas']; $msg_ventas = $_SESSION['datosGrafica']['msg_ventas'];				
		
		
		//Obtener el valor maximo del conjunto de datos
		$valMaximo = 0;
		if($max_compras>$max_ventas)
			$valMaximo = $max_compras;
		else
			$valMaximo = $max_ventas;
							
		//Combinar y Quitar valores repetidos del arreglo final de fechas
		$fechas = array_unique(array_merge($fechas_compras,$fechas_ventas));		
		//sort($fechas);//Ordenar el Arreglo de Final de las Fechas
		$fechas = ordenarArregloFechas($fechas);
		
		//Crear el Arreglo con las cantidades de Compras, Si el arreglo Final de Fechas es igual al 
		//arreglo de las Fechas de Compras, entonces el arreglo de Cantidades de Compras queda igual
		$temCantCompras = array();
		if(count($fechas_compras)!=count($fechas)){
			foreach($fechas as $key => $value){
				for($i=0;$i<count($fechas_compras);$i++){
					if($fechas_compras[$i]==$value){//Si la comparacion es verdadera, copiar el valor de la cantidad de Compras al arreglo temporal
						$temCantCompras[$key] = $cant_compras[$i];
						break;
					}
					else
						$temCantCompras[$key] = "null";					
				}//Cierre for
			}//Cierre foreach
			
			//Copiar el nuevo arreglo de cantidades al arreglo de Cantidades de Compras
			$cant_compras = $temCantCompras;
		}
		
		
		//Crear el Arreglo con las cantidades de Ventas, Si el arreglo Final de Fechas es igual al 
		//arreglo de las Fechas de Compras, entonces el arreglo de Cantidades de Ventas queda igual
		$temCantVentas = array();
		if(count($fechas_ventas)!=count($fechas)){
			foreach($fechas as $key => $value){
				for($i=0;$i<count($fechas_ventas);$i++){
					if($fechas_ventas[$i]==$value){//Si la comparacion es verdadera, copiar el valor de la cantidad de Compras al arreglo temporal
						$temCantVentas[$key] = $cant_ventas[$i];
						break;
					}
					else
						$temCantVentas[$key] = "null";					
				}//Cierre for
			}//Cierre foreach
			
			//Copiar el nuevo arreglo de cantidades al arreglo de Cantidades de Compras
			$cant_ventas = $temCantVentas;
		}?>
		<html>
		<head>
			<title>Gr&aacute;fica Comparativa de Compras/Ventas</title>
			<link rel="stylesheet" type="text/css" href="../../includes/estilo.css" />
			<style>
				<!--
				#grafica-comprasVentas{ position:absolute; width:940px; height:450px; left:30px; top:80px; z-index: 1;} 
				#boton-cerrar { position:absolute; left:30px; top:560px; width:940px; height:45px; z-index:2; }
				-->
			</style>
		</head>		
		<body style="cursor:url('../../images/cursor.cur');">
			<div id="grafica-comprasVentas" align="center"></div>			
			<div id="boton-cerrar" align="center">
			  <input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onClick="window.close();" />
			</div>
			<script type="text/javascript" src="../../includes/graficas/rollout.js"></script>
			<script type="text/javascript" src="../../includes/graficas/swfobject.js"></script>
			<script type="text/javascript">
				var so = new SWFObject("../../includes/graficas/open-flash-chart.swf", "ofc", "940", "450", "9", "#FFFFFF");								
				
				so.addVariable("variables","true");
				so.addVariable("bg_colour","0xA5D8B6");						
				so.addVariable("title","Gráfica Comparativa Compras/Ventas,{font-size:22px; color:#000; margin:5px; padding:5px; padding-left: 20px; padding-right: 20px;}");
				so.addVariable("x_axis_steps","1");
				so.addVariable("x_axis_3d","12");
				so.addVariable("y_legend","Cantidad $,16,#000");
				so.addVariable("x_legend","Fechas,16,#000");
				so.addVariable("y_ticks","5,10,5");
				so.addVariable("x_labels","<?php foreach($fechas as $key => $value){ if($key==count($fechas)-1) echo $value; else echo $value.","; }?>");
				so.addVariable("x_label_style","10,#000,2,1");
				so.addVariable("y_min","0");
				so.addVariable("y_max","<?php echo $valMaximo; ?>");
				so.addVariable("x_axis_colour","#909090");
				so.addVariable("x_grid_colour","#ADB5C7");
				so.addVariable("y_axis_colour","#909090");
				so.addVariable("y_grid_colour","#ADB5C7");
				so.addVariable("bar_3d","75,#C93438,Compras,10");			
				so.addVariable("values","<?php if(count($cant_compras)==0){ echo "null"; } else{ foreach($cant_compras as $key => $value){ if($key==count($cant_compras)-1) echo $value; else echo $value.","; } } ?>");
				so.addVariable("bar_3d_2","75,#1031A3,Ventas,10");			
				so.addVariable("values_2","<?php if(count($cant_ventas)==0){ echo "null"; } else{ foreach($cant_ventas as $key => $value){ if($key==count($cant_ventas)-1) echo $value; else echo $value.","; } } ?>");
				
				
				so.addParam("allowScriptAccess", "always" );//"sameDomain");
				so.addParam("onmouseout", "onrollout2();" );
				so.write("grafica-comprasVentas");																							
			</script>
		</body>
		</html><?php		
	}//Cierre funcion generarGraficaCompraVenta()
	
?>