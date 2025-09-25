<?php
	/**
	  * Nombre del Módulo: Aseguramiento Calidad                                           
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 28/Octubre/2011                                      			
	  * Descripción: Este archivo permite guardar los documentos en el servidor asi como en la Base de datos
	  **/

	//Verificamos que el boton de guardar este definido en el post (que haya sido presionado) 	
	if(isset($_POST["sbt_guardar"])){
		//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			//Dividimos el nombre del archivo para conservar solo la extensión y cambiarlo por el id del documento; esto para evitar los nombres largos 
			//en el manejo de archivos. Se usa explode ya que split no permite dividir con punto
			$archivoSec=explode('.',$_FILES["file_documento"]["name"]);
			//Guardamos el tamaño del arreglo
			$tam = count($archivoSec)-1;
			//Ubicamos el tipo en la posisicion final dela arreglo para obtener el tipo de archivo
			$tipo=$archivoSec[$tam];
			$archivo=$_POST['txt_idDocumento'].'.'.$tipo;
			$resSubirArch = subirArchivo($archivo);
		}	
		//Si fue asi Realizar el registro de los documentos
		registrarArchivo($archivo);											
	}
	
		
	//Esta funcion permite registrar los Archivos en la BD
	function registrarArchivo($archivo){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Aseguramiento Calidad
		$conn = conecta("bd_aseguramiento");
		
		//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado; para recuperar el nombre en la variable $archivo
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			//Dividimos el nombre del archivo para conservar solo la extensión y cambiarlo por el id del documento; esto para evitar los nombres largos 
			//en el manejo de archivos. Se usa explode ya que split no permite dividir con punto
			$archivoSec=explode('.',$_FILES["file_documento"]["name"]);
			//Guardamos el tamaño del arreglo
			$tam = count($archivoSec)-1;
			//Ubicamos el tipo en la posisicion final dela arreglo para obtener el tipo de archivo
			$tipo=$archivoSec[$tam];$archivo=$_POST['txt_idDocumento'].'.'.$tipo;
			
		}
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Declaramos variables para insercion de valores en la BD si estos permanecen nulos, no se selecciono norma ni, clasificacion
		$id_norma="";
		$id_clasificacion="";
		
		//Verificamos que el combo norma este definido y que no venga vacio. Para generar la ruta donde se guardara dicho archivo
		if(isset($_POST["cmb_norma"])&&$_POST["cmb_norma"]!=""){
			$norma = $_POST["cmb_norma"];
			$ruta='documentos/SGC/'.$norma;
			//Obtenemos el id de la norma que se encuentra almacenado en la BD para realizar la inserción
			$id_norma=obtenerDato("bd_aseguramiento", "catalogo_norma", "id_norma", "norma", $_POST["cmb_norma"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_norma
		if(isset($_POST["txt_norma"])&&$_POST["txt_norma"]!=""){
			$valNor=obtenerDato("bd_aseguramiento", "catalogo_norma", "norma", "norma", $_POST["txt_norma"]);
			if($valNor==""){
				//Obtenemos el id de la norma para realizar la insercion en la BD
				$id_norma = obtenerIdNorma();
				$stm_sql2 = "INSERT INTO catalogo_norma(id_norma,norma) VALUES('$id_norma','$_POST[txt_norma]')";
				//Ejecutar la sentencia previamente creada
				$rs2 = mysql_query($stm_sql2);
			}
			$norma=$_POST['txt_norma'];
			$ruta='documentos/SGC/'.$norma;
		}
		//Verificamos que se encuentre definido el combo clasificacion de ser asi obtenemos el dato correspondiente
		if(isset($_POST["cmb_clasificacion"])&&$_POST["cmb_clasificacion"]!=""){
			$clasificacion = $_POST["cmb_clasificacion"];
			$ruta='documentos/SGC/'.$norma.'/'.$clasificacion;
			$id_clasificacion=obtenerDato("bd_aseguramiento", "catalogo_clasificacion", "id_clasificacion", "clasificacion", $_POST["cmb_clasificacion"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_clasificacion
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$valCla=obtenerDato("bd_aseguramiento", "catalogo_clasificacion", "clasificacion", "clasificacion", $_POST["txt_clasificacion"]);
			if($valCla==""){
				$id_clasificacion = obtenerIdClasifcacion();
				$stm_sql1 = "INSERT INTO catalogo_clasificacion(id_clasificacion,clasificacion) VALUES('$id_clasificacion','$_POST[txt_clasificacion]')";
				//Ejecutar la sentencia previamente creada 
				$rs1 = mysql_query($stm_sql1);
			}
			$clasificacion=$_POST['txt_clasificacion'];
			$ruta='documentos/SGC/'.$norma.'/'.$clasificacion;
		}
		
		//Si los campos y los combos permanecen vacios quiere decir que el archivo se guardara en la raiz
		if($_POST["txt_norma"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_norma"]==""&&$_POST["cmb_clasificacion"]==""){
			$ruta='documentos/SGC';
		}
		
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$fecha=modFecha($_POST["txt_fecha"],3);
		$nombre=strtoupper($_POST["txt_nomDoc"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		
		//Crear la sentencia para realizar el registro de los datos
		 $stm_sql = "INSERT INTO repositorio_documentos(id_documento,catalogo_norma_id_norma, catalogo_clasificacion_id_clasificacion, nombre, fecha,  
					nom_archivo, descripcion, ruta, tipo_archivo) VALUES('$_POST[txt_idDocumento]','$id_norma','$id_clasificacion','$nombre', '$fecha','$archivo',
					'$descripcion', '$ruta','$tipo')";
						
		//Ejecutar la sentencia previamente creada 
		$rs = mysql_query($stm_sql);
		if(!$rs)
			$band = 1;						
		if ($band==1){
			//echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		}
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_aseguramiento",$_POST['txt_idDocumento'],"AgregarDocumento",$_SESSION['usr_reg']);
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
		
	//Esta funcion permite guardas los Documentos en el SERVIDOR
	function subirArchivo($archivo){
		//Incluimos archivos para realizar las operaciones con la BAse de datos
		include_once("../../includes/op_operacionesBD.php");
		
		//Creamos la variable ruta que servira para abrir la misma	
		$Ruta='';
		
		//Declaramos las variables que nos serviran para el proceso de la carga de documentos
		$carpeta2="";
		$carpeta3="";
		$norma="";
		$clasificacion="";
		/***************************************CASOS EN LOS CUALES SE PODRAN GUARDAR LOS DOCUMENTOS*******************************************************/
		
		//Caso 1.- en el cual si no esta definida la norma ni la clasificacion se quedaran en la raiz
		//Solo verificamos que se encuentren vacias las cajas de texto y los combos, pues si no se selecciona ninguno iran en el post
		if($_POST["txt_norma"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_norma"]==""&&$_POST["cmb_clasificacion"]==""){
			$carpeta2='documentos/SGC';
		}
		
		//Caso 2.-Cuando se haya seleccionado solo norma 
		if(isset($_POST["txt_norma"])&&$_POST["txt_norma"]!=""){
			$norma=strtoupper($_POST["txt_norma"]);
			$carpeta2='documentos/SGC/'.$norma;
		}
		if(isset($_POST['cmb_norma'])){
			$norma=$_POST["cmb_norma"];
			$carpeta2='documentos/SGC/'.$norma;
		}
		
		//Caso 3.- Cuando se haya seleccionado la Norma y Ademas se haya Seleccionado una clasificacion
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$clasificacion=strtoupper($_POST["txt_clasificacion"]);
			$carpeta3='documentos/SGC/'.$norma.'/'.$clasificacion;
		}
		if(isset($_POST['cmb_clasificacion'])){
			$clasificacion=$_POST["cmb_clasificacion"];
			$carpeta3='documentos/SGC/'.$norma.'/'.$clasificacion;
		}
		
		//Abrimos la ruta para crear la carpeta
		$dir = opendir($Ruta); 
		
		//Verificamos que el archivo haya sido updated
		if (is_uploaded_file($_FILES['file_documento']['tmp_name'])) { 
			//Si $carpeta no ah sido creado se crea con mkdir
			if (!file_exists($carpeta2."/")){
				mkdir($carpeta2."/", 0777);
				//Si la carpeta 3 se encuentra vacia quiere decir que no se selecciono la clasificacion
				if($carpeta3!=""){
					if (!file_exists($carpeta3."/")){
						mkdir($carpeta3."/", 0777);
						$carpeta2=$carpeta3;
					}
				}
			}
			//Si la carpeta 3 se encuentra vacia quiere decir que no se selecciono la clasificacion
			if($carpeta3!=""){
				if(!file_exists($carpeta3."/")){
					mkdir($carpeta3."/", 0777);
				}
				$carpeta2=$carpeta3;
			}
			//Si existen movemos el archivo que fue subido y lo movemos a la ruta deseada
			if(!file_exists($carpeta2."/".$archivo)){		
				move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta2."/".$archivo);     	    	 	
				?>
				<script>
					setTimeout("alert('Archivo <?php echo $_FILES['file_documento']['name'];?> Cargado Correctamente');",100);
				</script>
				<?php //Redireccionar a la pantalla de Exito
					echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
					?>
				<?php
			}
			else{
				?>
				<script>
					setTimeout("alert('La Clave Utilizada Para El Archivo <?php echo $_FILES['file_documento']['name'];?> ya existe, Ingrese Otra');",500);
				</script>
				<?php
			}				
		}
	}//Fin de la funcion 
	
	//Funcion que permite obtener el id de la norma
	function obtenerIdNorma(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_norma)+1 AS cant FROM catalogo_norma";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant=="")
				$id_cadena=001;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()


	//Funcion que permite obtener el id de la clasificacion
	function obtenerIdClasifcacion(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_aseguramiento");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_clasificacion)+1 AS cant FROM catalogo_clasificacion";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant=$datos["cant"];
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			if($cant=="")
				$id_cadena=001;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
		
		return $id_cadena;
	}//Fin de la Funcion obtenerId()
	
?>