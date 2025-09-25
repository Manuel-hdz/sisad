<?php 
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Daisy Adriana Martínez Fernández
	  * Fecha: 29/Abril/2011
	  * Descripción: Este archivo permite exportar los empleados registrados en la nómina bancaria a un archivo txt para el pago en el baco
	**/
	
	//Verificamos que el boton de expoportar haya sido presionado
	if(isset($_POST["sbt_exportar"])){
		//Si es asi llamamos la funcion generarTxt()
		generarTxt();
	}
	
	//Funcion que genera el numero de secuencia 000000001, 000000002, etc. (recibe como parametro un contador definido en la funcion generar TXT)
	function secuencia($sec){
		//aumentamos la variable secuencia para generar el siguiente numero
		$sec++;
		$digitos=9-strlen($sec);
		//Variable que acumular el relleno de datos a la izquierda del numero secuencial
		$relleno="";
		//Variable que controlara el ciclo de agregado de digitos
		$bandera=0;
		//Proceso para agregar los ceros que hagan falta para complementar el tamaño del dato
		do{
			//Ponemos el relleno de 0
			$relleno.="0";
			$bandera++;
		}while($bandera<$digitos);
		//Retornar el numero secuencial con el formato necesario
		return $relleno.$sec;
	}

	//Funcion que permite dar formato al rfc recibe como parametro el rfc que viene definido en el post
	function calcularRfc($rfc){
		$rfc=str_replace("-", "", $rfc);
		//Verificar si el tamaño del RFC es menor a 16
		if(strlen($rfc)<16){
			//Obtener la cantidad de digitos que se deben agregar para complementar la cadena
			$digitos=16-strlen($rfc);
			//Variable que controlara el ciclo de agregado de digitos
			$bandera=0;
			//Proceso para agregar los ceros que hagan falta para complementar el tamaño del dato
			do{
				$rfc.="0";
				$bandera++;
			}while($bandera<$digitos);
		}
		return $rfc;
	}

	//Funcion que permite dar formato ala cuenta recibe como parametro el no de cuenta que es arrojado de una consulta en generartxt
	function calcularCuenta($no_cta){
		//Verificar que la cuenta sea menor a 20
		if(strlen($no_cta)<20){
			//Obtener la cantidad de digitos que se deben agregar para poder complementar la cadena de cuenta
			$digitos=20-strlen($no_cta);
			//Variable que controlara el ciclo de agregado de digitos
			$bandera=0;
			//Proceso para agregar los ceros que hagan falta para complementar el tamaño del dato
			do{
				$no_cta.="0";
				$bandera++;
			}while($bandera<$digitos);
		}
		return $no_cta;
	}

	//Funcion que da formato al importa a pagar; recibe como parametro el salario neto de un empleado, este es arrojado por una consulta
	function calculaImporte($importe_pagar){
		//Convertir el importe a formato de numero, con 2 posiciones decimales usando el punto y separando miles con coma
		$importe_pagar=number_format($importe_pagar,2,".",",");
		//Remover las comas en haber aparecido
		if (strlen($importe_pagar)>6)
			$importe_pagar=str_replace(",","",$importe_pagar);
		//Remover los puntos
		$importe_pagar=str_replace(".","",$importe_pagar);
		//Verificar si el tamaño del importe es menor a 15 digitos
		if(strlen($importe_pagar)<15){
			//Obtener la cantidad de digitos que se deben agregar para complementar la cadena
			$digitos=15-strlen($importe_pagar);
			//Variable que controlara el ciclo de agregado de digitos
			$bandera=0;
			//Variable que acumular el relleno de datos a la izquierda del importe a pagar
			$relleno="";
			//Proceso para agregar los ceros que hagan falta para complementar el tamaño del dato
			do{
				$relleno.="0";
				$bandera++;
			}while($bandera<$digitos);
			//Concatenar los datos de relleno con el importe a pagar
			$importe_pagar=$relleno.$importe_pagar;
		}
		return $importe_pagar;
	}
	
	//Funcion que verifica que el nombre sea menor a 40 y rellena con 0 en caso de ser menor; recibe como parametro el nombre que es arrojado de una consulta
	function calculaNombre($nom_trabajador){
		if(strlen($nom_trabajador)<40){
			//Obtener la cantidad de digitos que se deben agregar para complementar la cadena
			$digitos=40-strlen($nom_trabajador);
			//Variable que controlara el ciclo de agregado de digitos
			$bandera=0;
			//Proceso para agregar los ceros que hagan falta para complementar el tamaño del dato
			do{
				$nom_trabajador.="0";
				$bandera++;
			}while($bandera<$digitos);
		}
		return $nom_trabajador;
	}
	
	//Funcion que genera el txr para el pago de la nomina
	function generarTxt(){
		//Incluimos archivo para realizar la conexion
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");
		//Incluimos archivo para modificar fechas segun consulta
		include_once("../../includes/func_fechas.php");
		//Conectamos con la BD de Recursos
		$conn=conecta("bd_recursos");
		
		//Estas 2 constantes corresponden al banco destino y plaza destino, su valor segun el decreto del banco es 001 en ambos casos
		define("BANCO_DEST","001");
		define("PLAZA_DEST","001");
		
		//Definir la constante Tipo de Cuenta, el valor 99 corresponde a nómina
		define("TIPO_CTA","99");
		
		//Variable para verificar que la consulta arrojo datos
		$flag=0;
		
		//Tomamos las fechas del post y las convertimos a formato necesario para la consulta
		$fechaIni=modFecha($_POST["hdn_fechaIni"],3);
		$fechaFin=modFecha($_POST["hdn_fechaFin"],3);
		
		//Creamos arreglo para guardar los empleados con los datos para generar el txt
		$registro=array();
		
		//Creamos las variables para almacenar datos del empleado
		$rfc=0;
		$no_cta=0;
		$importe_pagar=0;
		$nom_trabajador="";
		
		//Variable que almacena la cantidad de registros
		$cantidad=$_POST["hdn_cant"]-1;

		//variable para el control de los registros
		$ctrl=0;
		session_start();
		do{
			//Incrementamos el contador interno; para saber el numero de registro
			$ctrl++;
			//Almacenamos la variable que genera el numero consecutivo
			$secuencia=secuencia($ctrl);
			//Verificamos que este definido el ckb_empleado; es decir verificamos que se haya seleccionado por lo menos un registro
			if(isset($_POST["ckb_emp$ctrl"])){
				$ckb_emp = $_POST["ckb_emp$ctrl"];
				//Creamos la sentencia SQL
				$stm_sql="SELECT rfc_empleado, CONCAT(nombre,' ', ape_pat,' ', ape_mat) AS nombre, no_cta, neto_pagar 
						FROM (nomina_bancaria JOIN empleados  ON rfc_trabajador=rfc_empleado) WHERE fecha_insercion>='$fechaIni'
						AND fecha_insercion<='$fechaFin' AND rfc_empleado='$ckb_emp'";

				//Ejecutar la sentencia previamente creada
				$rs=mysql_query($stm_sql);
				if($datos = mysql_fetch_array($rs)){
					//Variables donde se guardaran los resultados de la consulta para ser modificados y ser exportados
					$rfc=calcularRfc($datos['rfc_empleado']);
					$no_cta=calcularCuenta($datos['no_cta']);
					$importe_pagar=calculaImporte($datos['neto_pagar']);
					$nom_trabajador=calculaNombre($datos['nombre']);
					
					//Variable que hace la union de cadena de datos
					$registro[]=$rfc.TIPO_CTA.$no_cta.$importe_pagar.$nom_trabajador.BANCO_DEST.PLAZA_DEST;
					
					//Guardamos el registro de la operacion
					registrarOperacion("bd_recursos","txt_nomina","ExportarNomina",$_SESSION['usr_reg']);
				}
				//Conectamos con la BD
				$conn = conecta("bd_recursos");
				//verificamos que la sentencia sea ejecutada con exito
				if (!$rs){
					$flag=1;
					//Si los datos no se agregaron correctamente
					$registro[]= "No hay Registros con los parametros Seleccionados";
					break;
				}
			}
		//Mientras que control sea menor a la cantidad se registraran los datos en la BD	
		}while($ctrl<$cantidad);
			
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);		
						
		//Cabeceras que permiten el contenido el archivo como contenido de archivo de texto plano, con la extension TXT
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=Nomina".date('dmy').".txt");
		
		//Recorre el arreglo y se van registrando el el archivo txt
		for ($i=0;$i<count($registro);$i++){
			$secuencia=secuencia($i);
			echo secuencia($i).$registro[$i]."
";
		}		
	}
?>