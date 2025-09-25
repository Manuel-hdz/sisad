<?php
	/**
	  * Nombre del Módulo: Topografía                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 25/Mayo/2011                                      			
	  * Descripción: Este archivo permite guardar los planos en el servidor asi como en la Base de datos
	  **/

	//Verificamos que el boton de guardar este definido en el post (que haya sido presionado) 	
	if(isset($_POST["sbt_guardar"])){
		//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			$archivo=$_FILES["file_documento"]["name"];
			$resSubirArch = subirPlanos($archivo);
		}	
		//Si fue asi Realizar el registro de los planos
		registrarPlanos($archivo);											
	}
	
	//Función que permite obtener el id del plano
	function obtenerIdPlano(){
		//Realizar la conexion a la BD de Topografia
		$conn = conecta("bd_topografia");
					
		//Definir las tres letras la clave del plano
		$id_cadena = "PNO";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_plano) AS cant FROM planos WHERE id_plano LIKE 'PNO$mes$anio%'";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras de la Bitacora Registrado en la BD y sumarle 1
			$cant = substr($datos['cant'],-3)+1;
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
	
	
	//Esta funcion permite registrar los planos en la BD
	function registrarPlanos($archivo){
		//Incluimos archivo para modificar fechas segun sea requerido	
		include_once("../../includes/func_fechas.php");
		
		//Realizar la conexion a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//Verificar si esta definido el Arreglo de Archivos y tiene registro agregado; para recuperar el nombre en la variable $archivo
		if (isset($_FILES["file_documento"]["name"])&&$_FILES["file_documento"]["name"]!=""){
			$archivo=$_FILES["file_documento"]["name"];
		}
		
		//Declaramos $band para verificar si hubo errores	
		$band=0;
		
		//Creamos las variables para la sentencia SQL
		$fecha=modFecha($_POST["txt_fecha"],3);
		$hora=date("H:i");
		
			//Crear la sentencia para realizar el registro de los datos
			$stm_sql = "INSERT INTO planos(id_plano,nom_archivo, nom_plano, descripcion, fecha, hora)
			VALUES('$_POST[txt_idPlano]','$archivo','$_POST[txt_nomPlano]','$_POST[txa_descripcion]', '$fecha','$hora' )";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de registro_fotografico
			$rs = mysql_query($stm_sql);
			if(!$rs)
				$band = 1;						
		if ($band==1)
			echo "<meta http-equiv='refresh' content='0;url=error.php'>";
		else{
			//Registrar la Operacion en la Bitácora de Movimientos
			registrarOperacion("bd_topografia",$_POST['txt_idPlano'],"AgregarPlano",$_SESSION['usr_reg']);
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		//Cerrar la conexion con la BD		
		//mysql_close($conn);
	}
		
	//Esta funcion permite guardas los planos en el SERVIDOR
	function subirPlanos($archivo){
		//Permite verificar que el archivo es valido y evita que se suban mas archivos al actualizar la pagina
		$band=true;
		//Verificamos el tipo de archivo
		if(substr($_FILES['file_documento']['name'],(strlen($_FILES['file_documento']['name'])-4),4) != ".dwg"){
			?>
				<script>
					window.location.href='frm_agregarPlanos.php?error';
				</script>
			<?php
			exit('');
			 
			$band = false;
			
		}
		//De lo contrario el archivo es valido y se procedera a subir el archivo en el lugar correspondiente
		else{
			//Creamos la variable ruta que servira para abrir la misma	
			$Ruta='';
			$hora=str_replace(":","",date("H:i"));			
			$carpeta2="";
			$carpeta3="";
			$fecha= str_replace("/","",$_POST["txt_fecha"]);
			//Creamos la carpeta inicial dentro de documentos
			$carpeta='documentos/'.$fecha;
			
			//Verificamos que opcion viene definida en el post
			$carpeta2=$carpeta."/".$hora;
			
			//Abrimos la ruta para crear la carpeta
   			$dir = opendir($Ruta); 
			//Verificamos que el archivo haya sido updated
		   	if (is_uploaded_file($_FILES['file_documento']['tmp_name'])) { 
				//Si $carpeta no ah sido creado se crea con mkdir
				if (!file_exists($carpeta."/")){
					mkdir($carpeta."/", 0777);
				}
				if (!file_exists($carpeta2."/")){
					mkdir($carpeta2."/", 0777);
				}
				//Si existen movemos el archivo que fue subido y lo movemos a la ruta deseada
				if (!file_exists($carpeta2."/".$_FILES['file_documento']['name'])){
		 	    move_uploaded_file($_FILES['file_documento']['tmp_name'], $carpeta2."/".$_FILES['file_documento']['name']);     	    	 	
				?>
				<script>
					setTimeout("alert('Archivo <?php echo $_FILES['file_documento']['name'];?> Cargado Correctamente');",500);
				</script>
				<?php
				}
				else{
					$band=false;
					?>
					<script>
						setTimeout("alert('El Archivo <?php echo $_FILES['file_documento']['name'];?> ya existe');",500);
					</script>
					<?php
				}				
			}
		}
		
		return $band;
		
	}//Fin de la funcion subirFotos($clave)
	
?>