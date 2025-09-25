<?php
	/**
	  * Nombre del Módulo: Topografia                                               
	  * Nombre Programador: Miguel Angel Garay Castro                            
	  * Fecha: 12/Julio/2011                                      			
	  * Descripción: Este archivo se encarga de consultar la BD en busqueda de los datos necesarios para llenar un comboBox
	  **/


	 	
	 /**
      * Listado del contenido del programa                                            
      *   Includes: 
	        1. Modulo de conexion con la base de datos
			2. Funciones para Manipular Fechas*/
			include("../../../../includes/conexion.inc");
			include("../../../../includes/func_fechas.php");			
	/**   Código en: \includes\ajax\cargarCombo.php                                   
      **/	
	  
	
	
	//Obtener los datos para cargar el combo con el Id en la Propiedad value del ComboBox
	if(isset($_GET['datoBusq'])){
		//Recuperar los datos a buscar de la URL
		$idObra = $_GET["datoBusq"];			
		$tabla = $_GET["tabla"];

		//Conectarse con la BD de Topografia
		$conn = conecta("bd_topografia");
		
		//Variable de Control para saber si hay datos disponibles
		$ctrl_datos = 0;

		//Obtener los Anios Disponibles en las Quincenas Registradas
		$aniosDisponibles = array();
		$rs = mysql_query("SELECT no_quincena FROM $tabla WHERE obras_id_obra = '$idObra'");
		while($datos=mysql_fetch_array($rs)){
			//Separar el noQuincena y colocar el anio como indice
			$seccionesQuincena = split(" ",$datos['no_quincena']);
			$aniosDisponibles[$seccionesQuincena[2]] = array();
			
			//Activar la varible cuando se encuentren datos
			if($ctrl_datos==0)
				$ctrl_datos = 1;
		}
		
		
		//Definir el tipo de contenido que tendra el archivo creado
		header("Content-type: text/xml");
		
		
		if($ctrl_datos==1){		
			//Ordenar las claves del Arreglo que contiene los Anios en la clave y no en el valor
			ksort($aniosDisponibles);
			
			//Obtener los meses disponibles por Anio
			foreach($aniosDisponibles as $anio => $meses){
				$rs_meses = mysql_query("SELECT no_quincena FROM $tabla WHERE obras_id_obra = '$idObra' AND no_quincena LIKE '%$anio'");
				while($datos_meses=mysql_fetch_array($rs_meses)){
					//Separar el noQuincena y colocar el mes como indice
					$seccionesQuincena = split(" ",$datos_meses['no_quincena']);
					$aniosDisponibles[$anio][$seccionesQuincena[1]] = array();
				}	
			}
			
			
			//Ordenar los meses de cada Anio
			foreach($aniosDisponibles as $anio => $meses)
				$aniosDisponibles[$anio] = ordenarMesesClaves($meses);
						
			
			//Obtener las Quincenas disponibles en los meses disponibles por Anio
			$cantQuincenas = 0;
			foreach($aniosDisponibles as $anio => $meses){		
				foreach($meses as $mes => $quincenas){
					$rs_quincenas = mysql_query("SELECT DISTINCT no_quincena FROM $tabla WHERE obras_id_obra = '$idObra' AND no_quincena LIKE '%$mes $anio'");
					while($datos_quincenas=mysql_fetch_array($rs_quincenas)){
						//Separar el noQuincena y colocar el numero de la quincena como valor
						$seccionesQuincena = split(" ",$datos_quincenas['no_quincena']);
						$aniosDisponibles[$anio][$mes][] = $seccionesQuincena[0];
						$cantQuincenas++;
					}
					//Ordenar las Quincenas Obtenidas por mes
					sort($aniosDisponibles[$anio][$mes]);
				}
			}
					
			
			//Mostrar el contenido del Arreglo Ordenado en codigo XML
			echo "<existe><valor>true</valor><cant>$cantQuincenas</cant>";
			$cont = 1;
			foreach($aniosDisponibles as $anio => $meses){				
				foreach($meses as $mes => $quincenas){
					foreach($quincenas as $ind => $noQuincena){
						echo utf8_encode("<quincena$cont>$noQuincena $mes $anio</quincena$cont>");
						$cont++;
					}
				}
			}											
			echo "</existe>";
			
			
		}//Cierre if($ctrl_datos==1)
		else{
			echo "<valor>false</valor>";
		}
		
						
		//Cerrar la conexion a la BD
		mysql_close($conn);			
	}//Cierre if(isset($_GET['datoBusq']))
?>
