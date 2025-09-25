<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                           
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 19/Enero/2012                                      			
	  * Descripción: Este archivo permite guardar los documentos en el servidor asi como en la Base de datos
	  **/

	//Verificamos que el boton de guardar este definido en el post (que haya sido presionado) 	
	if(isset($_POST["sbt_guardar"])){
		//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			//Dividimos el nombre del archivo para conservar solo la extensión y cambiarlo por el id del documento; esto para evitar los nombres largos 
			//en el manejo de archivos. Se usa explode ya que split no permite dividir con punto
			$archivoSec=explode('.',$_FILES["file_documento"]["name"]);
			$archivo=$_POST['txt_idDocumento'].'.'.$archivoSec[1];
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
		
		//Realizar la conexion a la BD de Seguridada Industrial
		$conn = conecta("bd_seguridad");
		
		//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado; para recuperar el nombre en la variable $archivo
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			//Dividimos el nombre del archivo para conservar solo la extensión y cambiarlo por el id del documento; esto para evitar los nombres largos 
			//en el manejo de archivos. Se usa explode ya que split no permite dividir con punto
			$archivoSec=explode('.',$_FILES["file_documento"]["name"]);
			$archivo=$_POST['txt_idDocumento'].'.'.$archivoSec[1];
			$tipo=$archivoSec[1];
		}
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		//Declaramos variables para insercion de valores en la BD si estos permanecen nulos, no se selecciono norma ni, clasificacion
		$id_carpeta="";
		$id_clasificacion="";
		
		//Verificamos que el combo norma este definido y que no venga vacio. Para generar la ruta donde se guardara dicho archivo
		if(isset($_POST["cmb_clasificacion"])&&$_POST["cmb_clasificacion"]!=""){
			$clasificacion = $_POST["cmb_clasificacion"];
			$ruta='documentos/SI/'.$clasificacion;
			//Obtenemos el id de la norma que se encuentra almacenado en la BD para realizar la inserción
			$id_clasificacion=obtenerDato("bd_seguridad", "catalogo_clasificacion", "id_clasificacion", "clasificacion", $_POST["cmb_clasificacion"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_norma
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$validarCla=obtenerDato("bd_seguridad", "catalogo_clasificacion", "clasificacion", "clasificacion", $_POST["txt_clasificacion"]);
			if($validarCla==""){
				//Obtenemos el id de la norma para realizar la insercion en la BD
				$id_clasificacion = obtenerIdClasificacion();
				$stm_sql2 = "INSERT INTO catalogo_clasificacion(id_clasificacion,clasificacion) VALUES('$id_clasificacion','$_POST[txt_clasificacion]')";
				//Ejecutar la sentencia previamente creada
				$rs2 = mysql_query($stm_sql2);
			}
			$clasificacion=$_POST['txt_clasificacion'];
			$ruta='documentos/SI/'.$clasificacion;
		}
		//Verificamos que se encuentre definido el combo clasificacion de ser asi obtenemos el dato correspondiente
		if(isset($_POST["cmb_carpeta"])&&$_POST["cmb_carpeta"]!=""){
			$carpeta = $_POST["cmb_carpeta"];
			$ruta='documentos/SI/'.$clasificacion.'/'.$carpeta;
			$id_carpeta=obtenerDato("bd_seguridad", "catalogo_carpetas", "id_carpeta", "carpeta", $_POST["cmb_carpeta"]);
		}
		
		//Verificamos que se ecuentre definida la caja de texto txt_clasificacion
		if(isset($_POST["txt_carpeta"])&&$_POST["txt_carpeta"]!=""){
			$validarCar=obtenerDato("bd_seguridad", "catalogo_carpetas", "carpeta", "carpeta", $_POST["txt_carpeta"]);
			if($validarCar==""){
				$id_carpeta = obtenerIdCarpeta();
				$stm_sql1 = "INSERT INTO catalogo_carpetas(id_carpeta,carpeta) VALUES('$id_carpeta','$_POST[txt_carpeta]')";
				//Ejecutar la sentencia previamente creada 
				$rs1 = mysql_query($stm_sql1);
			}
			$carpeta=$_POST['txt_carpeta'];
			$ruta='documentos/SI/'.$clasificacion.'/'.$carpeta;
		}
		
		//Si los campos y los combos permanecen vacios quiere decir que el archivo se guardara en la raiz
		if($_POST["txt_carpeta"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_carpeta"]==""&&$_POST["cmb_clasificacion"]==""){
			$ruta='documentos/SI';
		}
		
		//Creamos las variables para realizar los cambios en las mismas y permitir el correcto almacenamiento en la BD
		$fecha=modFecha($_POST["txt_fecha"],3);
		$nombre=strtoupper($_POST["txt_nomDoc"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		
		//Crear la sentencia para realizar el registro de los datos
		 $stm_sql = "INSERT INTO repositorio_documentos(id_documento,catalogo_clasificacion_id_clasificacion, catalogo_carpetas_id_carpeta, nombre, fecha,  
					nom_archivo, descripcion, ruta, tipo_archivo) VALUES('$_POST[txt_idDocumento]','$id_clasificacion','$id_carpeta','$nombre', '$fecha','$archivo',
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
			registrarOperacion("bd_seguridad",$_POST['txt_idDocumento'],"AgregarDocumento",$_SESSION['usr_reg']);
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
		$carpeta="";
		$clasificacion="";
		/***************************************CASOS EN LOS CUALES SE PODRAN GUARDAR LOS DOCUMENTOS*******************************************************/
		
		//Caso 1.- en el cual si no esta definida la norma ni la clasificacion se quedaran en la raiz
		//Solo verificamos que se encuentren vacias las cajas de texto y los combos, pues si no se selecciona ninguno iran en el post
		if($_POST["txt_carpeta"]==""&&$_POST["txt_clasificacion"]==""&&$_POST["cmb_carpeta"]==""&&$_POST["cmb_clasificacion"]==""){
			$carpeta2='documentos/SI';
		}
		
		//Caso 2.-Cuando se haya seleccionado solo norma 
		if(isset($_POST["txt_clasificacion"])&&$_POST["txt_clasificacion"]!=""){
			$clasificacion=strtoupper($_POST["txt_clasificacion"]);
			$carpeta2='documentos/SI/'.$clasificacion;
		}
		if(isset($_POST['cmb_clasificacion'])&&$_POST['cmb_clasificacion']!=""){
			$clasificacion=$_POST["cmb_clasificacion"];
			$carpeta2='documentos/SI/'.$clasificacion;
		}
		
		//Caso 3.- Cuando se haya seleccionado la Norma y Ademas se haya Seleccionado una clasificacion
		if(isset($_POST["txt_carpeta"])&&$_POST["txt_carpeta"]!=""){
			$carpeta=strtoupper($_POST["txt_carpeta"]);
			$carpeta3='documentos/SI/'.$clasificacion.'/'.$carpeta;
		}
		if(isset($_POST['cmb_carpeta'])&&$_POST['cmb_carpeta']!=""){
			$carpeta=$_POST["cmb_carpeta"];
			$carpeta3='documentos/SI/'.$clasificacion.'/'.$carpeta;
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
	
	//Funcion que permite obtener el id de la carpeta
	function obtenerIdCarpeta(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
		$id_cadena="";
		//Crear la sentencia para obtener la Clave reciente acorde al ultimo registro
		$stm_sql = "SELECT MAX(id_carpeta)+1 AS cant FROM catalogo_carpetas";
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
	function obtenerIdClasificacion(){
		//Realizar la conexion a la BD 
		$conn = conecta("bd_seguridad");
		
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