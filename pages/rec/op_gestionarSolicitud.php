<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 26/Septiembre/2012
	  * Descripción: Este archivo contiene las funciones para realizar la Gestion de las Solicitudes Medicas
	  **/ 
	  
	  
	/*Esta funcion genera el id de la Empresa de acuerdo a los registros en la BD*/
	function obtenerIdEmpresa(){
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		$id="";
		//Crear la sentencia para obtener la clave de la Empresa
		$stm_sql = "SELECT MAX(id_empresa) AS cant FROM catalogo_empresas";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			$cant = ($datos['cant'])+1;
				$id .= $cant;
		}
		return $id;
	}//Fin de la function obtenerIdPresupuesto()
	
	
	
	//Esta funcion guardará los datos de la bitácora de rezagado
	function guardarNvaEmpresaExt(){
		//Conectarse a la BD de Desarrollo
		$conn = conecta("bd_clinica");
		
		//Recuperar los datos del POST
		$id = obtenerIdEmpresa();
		$nomEmpresa = strtoupper($_POST['txt_nomEmpresa']);
		$razSocial = strtoupper($_POST['txt_razonSocial']);
		$tipoEmp = strtoupper($_POST['txt_tipoEmpresa']);
		$calle = strtoupper($_POST['txt_calle']);
		$colonia = strtoupper($_POST['txt_colonia']);
		$ciudad = strtoupper($_POST['txt_ciudad']);
		$estado = strtoupper($_POST['txt_estado']);
		$tel = $_POST['txt_tel'];
		$numExt = $_POST['txt_numExt'];
		$numInt = $_POST['txt_numInt'];

		//Crear la Sentencia SQL para alamcenar lo datos en la Base de Datos
		$stm_sql= "INSERT INTO catalogo_empresas (id_empresa, nom_empresa, razon_social, tipo_empresa, calle, numero_ext, numero_int, colonia, ciudad,
			 estado, telefono)
			 VALUES('$id', '$nomEmpresa','$razSocial', '$tipoEmp', '$calle', '$numExt', '$numInt',  '$colonia', '$ciudad', '$estado', '$tel')";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($stm_sql);

			if($rs){			
			//Iniciar la SESSION para tener acceso a los datos registrados del usuario
			session_start();
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_clinica","$id","RegistrarNvaEmpExt",$_SESSION['usr_reg']);
			
			//Mostrar al usuario el mensaje de operacion realizada con éxito en el DIV definido en la pagina verRegistrarObra.php
			echo "<img src='../../images/ok.png' width='376' height='369' />";
			
			//Ocultar El DIV que muestra las Imagenes de Éxito o Error?>
			<script type="text/javascript" language="javascript">
				setTimeout("document.getElementById('resultado-opr').style.visibility='hidden';",3000);
				window.close();
			</script>	
			<?php echo "<meta http-equiv='refresh' content='0;'>";?>
			
			<?php
		}
		else{
			$error = mysql_error();
			echo "
				<img src='../../images/error.png' width='376' height='369' />
				<br>
				<span class='msje_correcto'>***ERROR: No Pudo Registrar La Informaci&oacute;n en la Base de Datos => $error</span>";
				
			//Ocultar El DIV que muestra las Imagenes de Éxito o Error?>
			<script type="text/javascript" language="javascript">
				setTimeout("document.getElementById('resultado-opr').style.visibility='hidden';",3000);
				window.close();
				window.reload();
			</script><?php
			
			//Cerrar la conexicion con la BD
			mysql_close();
		}												
	}//Cierre de la funcion guardarBitAvance()
?>