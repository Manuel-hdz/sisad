<?php
	/**
	  * Nombre del Módulo: Unidad de Salud Ocupacional                                             
	  * Nombre Programador: Nadia Madahí López Hernández
	  * Fecha: 26/Septiembre/2012
	  * Descripción: Este archivo contiene las funciones para realizar la Gestion de las Solicitudes Medicas
	  **/ 
	 
	//Esta función se encarga de generar el Id de la Requisicion de acurdo a los registros existentes en la BD
	function obtenerIdSolicitudExaMedico(){
		//Realizar la conexion a la BD de Mantenimiento
		$conn = conecta("bd_clinica");
		//Definir las  letras en la Id de la Requisicion
		$id_cadena = "SOL";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el año actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del año en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de Requisicion registradas 
		$stm_sql = "SELECT COUNT(num_solicitud) AS cant FROM solicitud_examen WHERE num_solicitud LIKE 'SOL$mes$anio%'";
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
	}//Fin de la Funcion obtenerIdSolicitudExaMedico()
	
	/*Esta funcion genera el id de la Empresa de acuerdo a los registros en la BD*/
	function obtenerIdEmpleadosExt(){
		//Realizar la conexion a la BD de la Clinica
		$conn = conecta("bd_clinica");
		//Crear la sentencia para obtener la clave de la Empresa
		$stm_sql = "SELECT MAX(id_registro) AS cant FROM empleados_externos";
		$rs = mysql_query($stm_sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos["cant"]!=NULL)
				$id = ($datos['cant'])+1;
			else
				$id=1;
		}
		return $id;
	}//Fin de la function obtenerIdEmpleadosExt()
	
	//Desplegar los registros de los trabajadores a los cusales se les realizara o practicaran los examenes clinicos
	function mostrarEmpleadosExt($datosSolicitudMedica){
		echo "<table width='100%'>";
		echo "<caption><p class='msje_correcto'><strong>Detalle de la Solicitud Medica</strong></p></caption>";
		echo "      			
			<tr>
				<td class='nombres_columnas' align='center'>NO.</td>
        		<td class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</td>
				<td class='nombres_columnas' align='center'>EX&Aacute;MENES A PRACTICAR</td>
				<td class='nombres_columnas' align='center'>COSTO TOTAL</td>
				<td class='nombres_columnas' align='center'>FORMA PAGO</td>
				<td class='nombres_columnas' align='center'>BORRAR</td>
      		</tr>";
		$nom_clase = "renglon_gris";
		$cont = 1;
		foreach ($datosSolicitudMedica as $ind => $registro) {
			echo "<tr>";
			foreach ($registro as $key => $value) {
				switch($key){
					case "numEmp":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "nomEmp":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "examenes":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
					case "costo":
						echo "<td class='$nom_clase' align='center'>$".number_format($value,2,".",",")."</td>";
					break;
					case "formaPago":
						echo "<td align='center' class='$nom_clase' align='center'>$value</td>";
					break;
				}				
			}?>
			<td class="<?php echo $nom_clase;?>" align="center">
			<img src="../../images/borrar.png"
			onclick="frm_borrarRegistroSolicitudMed.action='frm_elaborarSolicitud.php?id_reg=<?php echo $ind?>&id_nomEmp=<?php echo $_POST['hdn_idEmp']?>';frm_borrarRegistroSolicitudMed.submit();" 
			style="cursor:pointer" title="Quitar la Solicitud Registrada"/>
			</td>
			<?php	
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
	
	//Funcion para guardar la informacion de la Solicitud Medica
	function registrarSolicitud(){
		//Obtenermos la clave de la Solicitud y la pasamos a una variable para un manejo mas sencillo dnetro de la consulta
		$idSolicitud = obtenerIdSolicitudExaMedico();
		//Recuperar la informacion del post
		$idEmpresa = $_POST['hdn_idEmp'];
		$nomEmpresa = $_POST['txt_nomEmpresa'];
		$razSocial = $_POST['txt_razSocial'];
		$fecha = modFecha($_POST['txt_fecha'],3);
		$autorizo = strtoupper($_POST['txt_autorizo']);
		$gerAdmin = strtoupper($_POST['txt_gerAdmin']);
		$resUSO = strtoupper($_POST['txt_resUSO']);
		$obs = strtoupper($_POST['txa_obs']);
		//Conectar se a la Base de Datos
		$conn = conecta("bd_clinica");
		//Consulta Principal
 		$stm_sql= "INSERT INTO solicitud_examen (num_solicitud, catalogo_empresas_id_empresa, fecha, nom_empresa, razon_social, autorizo, gerencia_admin, resp_uso, observaciones) 
					VALUES('$idSolicitud', '$idEmpresa', '$fecha', '$nomEmpresa', '$razSocial', '$autorizo', '$gerAdmin', '$resUSO', '$obs')";
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		if(!$rs){
			//Capturar el error
			$error = mysql_error();
			//Quitar el arreglo de la sesion
			unset ($_SESSION['datosSolicitudMedica']);
			//Redireccionar el error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		else{
			//Ciclo que permite registrar los examenes que se realizaran por trabajador
			foreach($_SESSION['datosSolicitudMedica'] as $ind => $datosSol){				
				$nomExa = explode(",", $datosSol['examenes']);
				$idExa = explode(",", $datosSol['idExamenes']);
				$numEmp = $datosSol['numEmp'];
				foreach($idExa as $key => $claveExamen){
					//Crear la sentencia para realizar el registro de los datos
					$stm_sqlExa = "INSERT INTO exa_ext_realizados (solicitud_examen_num_solicitud, catalogo_examen_id_examen, empleados_externos_id_registro)
					 VALUES('$idSolicitud','$claveExamen','$numEmp')";
					//Ejecutar la sentencia previamente creada 
					$rsExa = mysql_query($stm_sqlExa);
					if(!$rsExa){
						$error = mysql_error();
						unset ($_SESSION['datosSolicitudMedica']);
						echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
						break;			
					}//Fin del if(!$rsExa){
				}//Fin del foreach($idExa as $key => $claveExamen){
				//Crear la Sentencia SQL para almacenar el detalle de la solicitud
				$stm_sql = "INSERT INTO empleados_externos (id_registro, catalogo_empresas_id_empresa, solicitud_examen_num_solicitud, nom_empleado_ext, forma_pago, costo_total) 
				VALUES ('$numEmp','$idEmpresa','$idSolicitud','$datosSol[nomEmp]','$datosSol[formaPago]', '$datosSol[costo]')";
				//Ejecutar la Sentencia
				$rs=mysql_query($stm_sql);
				//Verificar Resultado
				if (!$rs){
					$error = mysql_error();
					//liberar los datos del arreglo de sesion
					unset ($_SESSION['datosSolicitudMedica']);
					echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				}//Fin del else
			}//Fin del 	foreach($_SESSION['datosSolicitudMedica'] as $key => $datosSol)
			if(!$rs){
				$error = mysql_error();
				unset ($_SESSION['datosSolicitudMedica']);
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
			else{			
				//liberar los datos del arreglo de sesion
				unset ($_SESSION['datosSolicitudMedica']);
				mysql_close($conn);
				registrarOperacion("bd_clinica","$idSolicitud","RegistrarSolicitudMedica",$_SESSION['usr_reg']);
				?>
					<script type='text/javascript' language='javascript'>
						setTimeout("window.open('../../includes/generadorPDF/solicitudMedica.php?id=<?php echo $idSolicitud; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);
					</script>
				<?php
				echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
			}
		}
	}	
	
		
	function consultarSolicitud(){
		//Extraer la feha y el ID de Empresa
		$fecha=modFecha($_POST["txt_fecha"],3);
		$idEmp=$_POST["cmb_empresa"];
		$nomEmpresa = obtenerDato("bd_clinica","catalogo_empresas", "nom_empresa", "id_empresa", $idEmp);
		//Conectar con la BD
		$conn=conecta("bd_clinica");
		
		//Preparar la consulta
		$sql_stm="SELECT autorizo,num_solicitud FROM solicitud_examen WHERE fecha='$fecha' AND catalogo_empresas_id_empresa='$idEmp'";
		//Ejecutar la consulta
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			echo "				
				<table cellpadding='5' width='100%'>
				<caption class='titulo_etiqueta'>SOLICITUDES M&Eacute;DICAS REGISTRADAS</caption>
				<tr>
					<td class='nombres_columnas' align='center'>SOLICITUD</td>
					<td class='nombres_columnas' align='center'>AUTORIZ&Oacute;</td>
					<td class='nombres_columnas' align='center'>CONSULTAR SOLICITUD</td>
				</tr>";
				?>
			<?php 
			$nom_clase = "renglon_gris";			
			$cont = 1;
			$numSolicitud = $datos['num_solicitud'];
			//Mostrar todos los registros que han sido completados
					echo "	<tr>				
								<td class='$nom_clase' align='center'>$datos[num_solicitud]</td>
								<td class='$nom_clase' align='center'>$datos[autorizo]</td>";
							?>
								<td class="<?php echo $nom_clase; ?>" align="center"><input type="button" name="btn_verPDF2" class="botones" value="Ver Solicitud" onmouseover="window.estatus='';return true" 
									title="Consultar Solicitud <?php echo $datos['num_solicitud'];?>" 
									onclick="window.open('../../includes/generadorPDF/solicitudMedica.php?id=<?php echo $datos["num_solicitud"];?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/></td>
							<?php
					echo "		</tr>";
					
			do{
				if($numSolicitud!=$datos['num_solicitud']){
					//Mostrar todos los registros que han sido completados
					echo "<tr>
							<td class='nombres_columnas' align='center'>SOLICITUD</td>
							<td class='nombres_columnas' align='center'>AUTORIZ&Oacute;</td>
							<td class='nombres_columnas' align='center'>CONSULTAR SOLICITUD</td>
						</tr>";
					echo "	<tr>				
								<td class='$nom_clase' align='center'>$datos[num_solicitud]</td>
								<td class='$nom_clase' align='center'>$datos[autorizo]</td>";
							?>
								<td class="<?php echo $nom_clase; ?>" align="center">
									<input type="button" name="btn_verPDF" class="botones" value="Ver Solicitud" onMouseOver="window.estatus='';return true" 
									title="Consultar Solicitud <?php echo $datos['num_solicitud'];?>" 
									onClick="window.open('../../includes/generadorPDF/solicitudMedica.php?id=<?php echo $datos["num_solicitud"];?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>		
								</td>
							<?php
					echo "		</tr>";
					//Determinar el color del siguiente renglon a dibujar
					$cont++;
					$numSolicitud = $datos['num_solicitud'];
					
					$sql = "SELECT  id_registro, nom_empleado_ext FROM empleados_externos WHERE  solicitud_examen_num_solicitud = '$numSolicitud' ";
					
					$rsEmpExt = mysql_query($sql);
					
					echo "	<td colspan='3' class='nombres_columnas' align='center'>
								EMPLEADOS EXTERNOS DE LA SOLICITUD $numSolicitud
							</td>";
					if($datosEmpExt = mysql_fetch_array($rsEmpExt)){
						echo "<tr>
								<td class='nombres_columnas' align='center'>CLAVE EMPLEADO</td>
								<td colspan='3' class='nombres_columnas' align='center'>NOMBRE EMPLEADO EXTERNO</td>";
							"</tr>";	
						do{
							echo "
								<tr>
										<td class='$nom_clase' align='center'>$datosEmpExt[id_registro]</td>


									<td colspan='3' class='$nom_clase' align='center'>$datosEmpExt[nom_empleado_ext]</td>";
						}while($datosEmpExt = mysql_fetch_array($rsEmpExt));
					echo "</tr>";
					}//Fin del 	if($datosEmpExt = mysql_fetch_array($rsEmpExt)){
				}//Fin de if($numSolicitud!=$datos['num_solicitud']){
				else{
					$sql = "SELECT  id_registro, nom_empleado_ext FROM empleados_externos WHERE  solicitud_examen_num_solicitud = '$numSolicitud' ";
					
					$rsEmpExt = mysql_query($sql);
					
					echo "	<td colspan='3' class='nombres_columnas' align='center'>
								EMPLEADOS EXTERNOS DE LA SOLICITUD $numSolicitud
							</td>";
					if($datosEmpExt = mysql_fetch_array($rsEmpExt)){
						echo "<tr>
								<td class='nombres_columnas' align='center'>CLAVE EMPLEADO</td>
								<td colspan='3' class='nombres_columnas' align='center'>NOMBRE EMPLEADO EXTERNO</td>";
							"</tr>";	
						do{
							echo "
								<tr>
										<td class='$nom_clase' align='center'>$datosEmpExt[id_registro]</td>
									<td colspan='3' class='$nom_clase' align='center'>$datosEmpExt[nom_empleado_ext]</td>";
						}while($datosEmpExt = mysql_fetch_array($rsEmpExt));
					echo "</tr>";
					}//Fin del 	if($datosEmpExt = mysql_fetch_array($rsEmpExt)){	
				}
			}while($datos=mysql_fetch_array($rs)); 
			echo "</table>";
			echo "<input type='hidden' name='hdn_empresa' id='hdn_empresa' value='$idEmp'/>";
		}
		else{
			echo "<br><br><br><br>
					<p align='center' class='msje_correcto'>NO hay Solicitudes de $nomEmpresa en la Fecha $_POST[txt_fecha]</p>";
					
		}
		mysql_close($conn);
		
	}
?>
			