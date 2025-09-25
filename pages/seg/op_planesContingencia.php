<?php
	/**
	  * Nombre del Módulo: Seguridad
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha:15/Febrero/2012
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con los Planes de Contingencia Generados por el departamento
	**/
	
	if(isset($_POST['sbt_finalizar'])){
		registrarPlanContingencia();
		registrarDetallePlanContingencia();
		unset ($_SESSION['datosPlanContingencia']);
		
	}
		
	if(isset($_POST['sbt_finalizarArchivo'])){
		registrarPlanContingencia();
		guardarDctoPlanContingencia();
		unset ($_SESSION['datosPlanContingencia']);		
	}
		
	//Funcion para guardar la informacion del Permiso
	function registrarPlanContingencia(){
	
		include_once("../../includes/conexion.inc");
		include_once("../../includes/op_operacionesBD.php");	
		include_once("../../includes/func_fechas.php");	
		
		//Conectar se a la Base de Datos de Seguridad
		$conn = conecta("bd_seguridad");
		
		//OBtener las variables que vienen en el POST		
		$idPlan = ($_POST['txt_idPlan']);
		$resSim = strtoupper($_POST['txt_resSim']);
		$fechaReg = modFecha($_POST['txt_fechaReg'],3);
		$fechaProg = modFecha($_POST['txt_fechaProg'],3);
		$area = strtoupper($_POST['txt_area']);
		$lugar = strtoupper($_POST['txt_lugar']);
		$nomSim = strtoupper($_POST['txt_nomSimulacro']);		
		$tipoSim = strtoupper($_POST['txt_tipoSimulacro']);

		//Obtener el ID del Plan regostrado
		$idPlan = obtenerIdPlanContingencia();
				
		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	
		//Crear la Sentencia SQL para Alamcenar los datos del Plan de ContinegenciA
		$stm_sql= "INSERT INTO planes_contingencia (id_plan, responsable, area, lugar, nom_simulacro, tipo_simulacro, fecha_reg, fecha_programada, estado)
				VALUES ('$idPlan', '$resSim', '$area', '$lugar', '$nomSim', '$tipoSim', '$fechaReg', '$fechaProg', 'NO')";	
														     
		//Ejecutar laS Sentencias previamente Creadas
		$rs=mysql_query($stm_sql);
		
			//Verificar Resultado  && $rsAlerta
			if ($rs){
			//Guardar la operacion realizad0			
			registrarOperacion("bd_seguridad",$idPlan,"PlanContingencia",$_SESSION['usr_reg']);	
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";	
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarPlanContingencia()	
	 

	//Esta funcion genera la Clave del PLan de contingencia generado
	function obtenerIdPlanContingencia(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las  letras en la Id del plan que sa va registrar.
		$id_cadena = "PLN";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener los registros de los permisos del mes y año en curso 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener el numero de plan registrado
		$stm_sql = "SELECT COUNT(id_plan) AS cant FROM planes_contingencia WHERE id_plan LIKE 'PLN$mes$anio%'";
		$rs = mysql_query($stm_sql);
		
		if($datos=mysql_fetch_array($rs)){
			$cant = $datos['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}	
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		return $id_cadena;
	}//Fin de la Funcion obtenerIdPlanContingencia()			

	
	
//Desplegar los registros de anomalias registradas
	function mostrarPasosPlanContingencia($datosPlanContingencia){
	if(isset($_SESSION["datosPlanContingencia"]))
		echo "<table width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Paso del Plan de Contingencia</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>NO.</td>
        		<td class='nombres_columnas' align='center'>PASO</td>
				<td class='nombres_columnas' align='center'>ACCI&Oacute;N</td>
				<td class='nombres_columnas' align='center'>RESPONSABLE DE LA ACCI&Oacute;N</td>
				<td class='nombres_columnas' align='center'>SIMULACRO</td>
				<td class='nombres_columnas' align='center'>COMENTARIOS</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($datosPlanContingencia as $ind => $registro) {
			echo "<tr>";
			foreach ($registro as $key => $value) {
				switch($key){
					case "paso":
						echo "<td align='center' class='$nom_clase' align='center'>$cont</td>";
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "accion":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "resAccion":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "simulacro":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "comentarios":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
				}				
			}
			//Determinar el color del siguiente renglon a dibujar
			$cont++;
			if($cont%2==0)
				$nom_clase = "renglon_blanco";
			else
				$nom_clase = "renglon_gris";
			echo "</tr>";			
		}
		echo "</table>";
	}//Fin de la funcion 	
	

	//Funcion para guardar los Detalles (Pasos ó Acciones) del Plan de Contingencia
	function registrarDetallePlanContingencia(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Variable que verifica en caso de que haya existido un error durante la ejecucion de la consulta
		$band=0;
		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	 	
		//Obtener el ID del Plan regostrado
		$idPlan =  $_POST['txt_idPlan'];

		//Ciclo que permite registrar los pasos del plan de contingencia registrado
		foreach($_SESSION['datosPlanContingencia'] as $key => $pasosPlan){

			//Crear la Sentencia SQL para almacenar el detalee dle plan de contingencia
			$stm_sql = "INSERT INTO detalle_contingencia (planes_contingencia_id_plan, paso, accion, responsable, simulacro, comentarios, id_documento)
			VALUES ('$idPlan','$pasosPlan[paso]','$pasosPlan[accion]', '$pasosPlan[resAccion]','$pasosPlan[simulacro]', '$pasosPlan[comentarios]', 'N/A' )";
			
			//Ejecutar la Sentencia
			$rs=mysql_query($stm_sql);

			//Verificar Resultado
			if (!$rs){
				$band=1;
			}
			else{
				$band=0;
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				//liberar los datos del arreglo de sesion
				unset ($_SESSION['datosPlanContingencia']);
			}
			if($band==1){
				//Guardar el registro de movimientos
				//registrarOperacion("bd_seguridad",$idPlan,"regPasosPlan",$_SESSION['usr_reg']);
				//$conn = conecta("bd_seguridad");																			
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
				//liberar los datos del arreglo de sesion
				unset ($_SESSION['datosPlanContingencia']);
			}		
		}// Fin foreach($_SESSION['pasosPlanContingencia'] as $ind => $pasosPlan)
		//Cerrar la Conexion con la BD
		mysql_close($conn);			
	}// Fin function registrarDetallePlanContingencia()
	
	

	
	//Funcion que permitira mostrar los archivos que se encuentran almacenadas dentro derl REPOSITORIO
	function mostrarArchivos(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
				
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Creamos la sentencia SQL
		$stm_sql ="SELECT * FROM repositorio_documentos ORDER BY nombre";
					
		//Ejecutamos la sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Si la consulta trajo datos creamos la tabla para mostrarlos
		if($datos = mysql_fetch_array($rs)){		
			echo "				
				<table cellpadding='5' width='100%'>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>SELECCIONAR</td>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>CLAVE</td>
					<td class='nombres_columnas' align='center'>NOMBRE</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				echo "	<tr>
							<td class='nombres_filas' align='center'>
								<input type='radio' id='rdb_opcDocumento' name='rdb_opcDocumento' value='$datos[id_documento]'/>
							</td>	
							<td class='$nom_clase' align='left'>$cont</td>							
							<td class='$nom_clase' align='left'>$datos[id_documento]</td>				
							<td class='$nom_clase' align='left'>$datos[nombre]</td>
							<td class='$nom_clase' align='left'>$datos[descripcion]</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;	
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";	 
			return 1;
		}
		else{
			?>
			<script language="javascript" type="text/javascript">
				setTimeout("alert('No Existen Archivos Registrados; Agregue Archivos en el Menú Repositorio');window.close()",500);
				
			</script>
			<?php 	
			return 0;
		}?>							
		<?php
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}//Cierre de la funcion
	
	
	
	//Funcion para guardar el archivo vinculado al plan de contingencia cuando no se registren los pasos correspondientes al plan
	function guardarDctoPlanContingencia(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");
		
				
		//Variable que verifica en caso de que haya existido un error durante la ejecucion de la consulta
		$band=0;
		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	 	
		//Obtener el ID del Plan registrado
		$idPlan =  $_POST['txt_idPlan'];
		$archivo = $_POST['txt_archivos'];

			//Crear la Sentencia SQL para almacenar el detalee dle plan de contingencia
			$stm_sql = "INSERT INTO detalle_contingencia (planes_contingencia_id_plan, paso, accion, responsable, simulacro, comentarios, id_documento)
				VALUES ('$idPlan', 'N/A', 'N/A', 'N/A', 'N/A', 'N/A', '$archivo')";
				
			//Ejecutar la Sentencia
			$rs=mysql_query($stm_sql);

			//Verificar Resultado
			if (!$rs){
				$band=1;
			}
			else{
				$band=0;
				$error = mysql_error();
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				//liberar los datos del arreglo de sesion
				unset ($_SESSION['datosPlanContingencia']);
			}
			if($band==1){
				//Guardar el registro de movimientos
				//registrarOperacion("bd_seguridad",$idPlan,"regArchPlanCont",$_SESSION['usr_reg']);
				//$conn = conecta("bd_seguridad");																			
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
				//liberar los datos del arreglo de sesion
				unset ($_SESSION['datosPlanContingencia']);
			}		
		//Cerrar la Conexion con la BD
		mysql_close($conn);			
	}// Fin function guardarDctoPlanContingencia()	
?>	