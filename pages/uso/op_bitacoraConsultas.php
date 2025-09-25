<?php
	/**
	  * Nombre del M�dulo: Unidad de Salud Ocupacional                                              
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas                            
	  * Fecha: 06/Julio/2012                                     			
	  * Descripci�n: Este archivo contiene funciones para almacenar la informaci�n relacionada con el formulario de Guardar Bitacora de Consultas M�dicas 
	  **/
	
	//Esta funci�n se encarga de generar el Id de la Bitacora de Radiografias
	function obtenerIdBitConsultasMedicas(){
		//Realizar la conexion a la BD de USO
		$conn = conecta("bd_clinica");
		//Definir las  letras en la Id de la Bitacora
		$id_cadena = "BCM";
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		//Concatenar al id de la bitacora la fecha segun a�o y mes
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el a�o actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de registros en la Bitacora
		$stm_sql = "SELECT MAX(id_bit_consultas) AS clave FROM bitacora_consultas WHERE id_bit_consultas LIKE 'BCM$mes$anio%'";
		$rs = mysql_query($stm_sql);		
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		//Retornar el id de la Bitacora
		return $id_cadena;
	}//Fin de la Funcion obtenerIdBitConsultasMedicas()
	
	//Esta funci�n se encarga de generar el Id de la Bitacora de Medicamentos
	function obtenerIdBitMedicamentos(){
		//Definir las  letras en la Id de la Bitacora
		$id_cadena = "BMD";
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		//Concatenar al id de la bitacora la fecha segun a�o y mes
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el a�o actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de registros en la Bitacora
		$stm_sql = "SELECT MAX(id_bit_medicamento) AS clave FROM bitacora_medicamentos WHERE id_bit_medicamento LIKE 'BMD$mes$anio%'";
		$rs = mysql_query($stm_sql);		
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Retornar el id de la Bitacora
		return $id_cadena;
	}//Fin de la Funcion obtenerIdBitMedicamentos()
	
	//Esta funci�n se encarga de generar el Id de la Bitacora de Radiografias
	function obtenerIdInfMedico(){
		//Realizar la conexion a la BD de USO
		$conn = conecta("bd_clinica");
		//Definir las  letras en la Id de la Bitacora
		$id_cadena = "INF";
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		//Concatenar al id de la bitacora la fecha segun a�o y mes
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el a�o actual para ser agregado en la consulta y asi obtener las requisiciones del mes en curso del a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de registros en la Bitacora
		$stm_sql = "SELECT MAX(id_informe) AS clave FROM informe_medico WHERE id_informe LIKE 'INF$mes$anio%'";
		$rs = mysql_query($stm_sql);		
		if($datos=mysql_fetch_array($rs)){
			$cant = intval(substr($datos['clave'],7,3));
			$cant += 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		//Retornar el id de la Bitacora
		return $id_cadena;
	}//Fin de la Funcion obtenerIdBitConsultasMedicas()
	
	//Funcion que guarda en la bitacora registros de consultas Internas
	function guardaBitInterna(){
		//Recuperar los datos del POST
		$rfc=$_POST["txt_rfc"];
		$numEmp=$_POST["txt_noEmpleado"];
		$nomEmpleado=$_POST["txt_nombre"];
		$area=$_POST["txt_area"];
		$puesto=$_POST["txt_puesto"];
		$tipoConsulta=$_POST["txt_tipoConsulta"];
		$consulta=$_POST["txt_consulta"];
		$nom_familiar=strtoupper($_POST["txt_nomFamiliar"]);
		$parentesco=strtoupper($_POST["txt_parentesco"]);
		$fecha=modFecha($_POST["txt_fecha"],3);
		//Manejamos la Hora en una cadena de tipo hh:mm AM/PM para que se convierta al formato soportado por MySQL
		$hora=$_POST["txt_hora"]." ".$_POST["cmb_hora"];
		//ESPACIO IMPORTANTE------^
		//Modificar la hora a formato 24hrs
		$hora=modHora24($hora);
		$lugar=strtoupper($_POST["txt_lugar"]);
		$diagnostico=strtoupper($_POST["txa_diagnostico"]);
		$tratamiento=strtoupper($_POST["txa_tratamiento"]);
		$observaciones=strtoupper($_POST["txa_observaciones"]);
		//Cuando el tipo de consulta es por Accidente, meter los datos a un arreglo, ya que se debe generar el Informe Medico antes de guardar la informacion
		if($tipoConsulta=="ACCIDENTE"){
			//$datosConsMedica=array();
			$datosConsMedica["rfc"]=$rfc;
			$datosConsMedica["numEmp"]=$numEmp;
			$datosConsMedica["nomEmpleado"]=$nomEmpleado;
			$datosConsMedica["area"]=$area;
			$datosConsMedica["puesto"]=$puesto;
			$datosConsMedica["tipoConsulta"]=$tipoConsulta;
			$datosConsMedica["consulta"]=$consulta;
			$datosConsMedica["nom_familiar"]=$nom_familiar;
			$datosConsMedica["parentesco"]=$parentesco;
			$datosConsMedica["fecha"]=$fecha;
			$datosConsMedica["hora"]=$hora;
			$datosConsMedica["lugar"]=$lugar;
			$datosConsMedica["diagnostico"]=$diagnostico;
			$datosConsMedica["tratamiento"]=$tratamiento;
			$datosConsMedica["observaciones"]=$observaciones;
			
			$_SESSION["datosConsMedica"]=$datosConsMedica;
			//Redireccionar a la pantalla de Error
			echo "<meta http-equiv='refresh' content='0;url=frm_regBitacoraConsultasMed3.php'>";
		}
		//Cuando el tipo de consulta es General guardar la info en la BD
		else{
			//Obtener un ID para la bitacora
			$idBitacora=obtenerIdBitConsultasMedicas();
			//Abrir la conexion con la BD
			$conn=conecta("bd_clinica");
			//Sentencia SQL para ingresar el registro a la bitacora
			$stm_sql="INSERT INTO bitacora_consultas (id_bit_consultas,catalogo_empresas_id_empresa,empleados_rfc_empleado,id_empleados_empresa,nom_empleado,
					area,puesto,tipo_consulta,consulta,nom_familiar,parentesco,fecha,hora,lugar,pb_diagnostico,tratamiento,observaciones) 
					VALUES ('$idBitacora','','$rfc','$numEmp','$nomEmpleado','$area','$puesto','$tipoConsulta','$consulta','$nom_familiar',
					'$parentesco','$fecha','$hora','$lugar','$diagnostico','$tratamiento','$observaciones')";
			$rs = mysql_query($stm_sql);
			if($rs){
				//Cerrar la conexion con la BD
				mysql_close($conn);	
				//Registrar Operacion de inrecion a la bitacora
				registrarOperacion("bd_clinica",$idBitacora,"RegBitConsultaMedicaInterna",$_SESSION['usr_reg']);
				//Variable de control de exito o error
				$res=1;
				//Verificar si esta definido el arreglo de medicamentos para registrar sus salidas
				if(isset($_SESSION["medicamento"])){
					//Actualizar la existencia de Medicamentos
					$res=actualizarExistenciaMedicamentos($idBitacora,$fecha);
					//Borrar de la Sesion el arreglo de los Medicamentos
					unset($_SESSION["medicamento"]);
				}
				if($res==1){
					registrarOperacion("bd_clinica",$idBitacora,"RegEntregaMedicamento",$_SESSION['usr_reg']);
					echo"<meta http-equiv='refresh' content='0;url=exito.php'>";
				}
				else{
					$error=mysql_error();
					//Redireccionar a la pantalla de Error
					echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				}
			}
			else{
				$error=mysql_error();
				//Redireccionar a la pantalla de Error
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				//Cerrar la conexion con la BD
				mysql_close($conn);
			}
  		}
	}//Fin de function guardaBitInterna()
	
	//Funcion que actualiza la existencia de Medicamentos
	function actualizarExistenciaMedicamentos($idBitConsMed,$fecha){
		$band=1;
		//Abrir la conexion con la BD
		$conn=conecta("bd_clinica");
		foreach($_SESSION["medicamento"] as $ind=>$value){
			$stm_sql="UPDATE catalogo_medicamento SET existencia_actual=existencia_actual-$value WHERE id_med='$ind'";
			$rs = mysql_query($stm_sql);
			if($rs){
				$idBitMedicamento=obtenerIdBitMedicamentos();
				$stm_sql="INSERT INTO bitacora_medicamentos (id_bit_medicamento,bitacora_consultas_id_bit_consultas,catalogo_medicamento_id_med,tipo_movimiento,fecha,cant_salida) 
						VALUES ('$idBitMedicamento','$idBitConsMed','$ind','SALIDA','$fecha','$value')";
				$rs = mysql_query($stm_sql);
				if(!$rs){
					$band=0;
					break;
				}
			}
			else{
				$band=0;
				break;
			}
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);
		//Retornar el valor de la bandera
		return $band;
	}
	
	//Funcion que registra en la bitacora registros de consultas externas
	function guardaBitExterna(){
		//Recuperar los datos del POST
		$idEmpresa=$_POST["cmb_empresa"];
		$rfc=strtoupper($_POST["txt_rfc"]);
		$numEmp=$_POST["txt_noEmpleado"];
		$nomEmpleado=strtoupper($_POST["txt_nombre"]);
		$area=strtoupper($_POST["txt_area"]);
		$puesto=strtoupper($_POST["txt_puesto"]);
		$tipoConsulta=$_POST["txt_tipoConsulta"];
		$consulta=$_POST["txt_consulta"];
		$nom_familiar=strtoupper($_POST["txt_nomFamiliar"]);
		$parentesco=strtoupper($_POST["txt_parentesco"]);
		$fecha=modFecha($_POST["txt_fecha"],3);
		//Manejamos la Hora en una cadena de tipo hh:mm AM/PM para que se convierta al formato soportado por MySQL
		$hora=$_POST["txt_hora"]." ".$_POST["cmb_hora"];
		//ESPACIO IMPORTANTE------^
		//Modificar la hora a formato 24hrs
		$hora=modHora24($hora);
		$lugar=strtoupper($_POST["txt_lugar"]);
		$diagnostico=strtoupper($_POST["txa_diagnostico"]);
		$tratamiento=strtoupper($_POST["txa_tratamiento"]);
		$observaciones=strtoupper($_POST["txa_observaciones"]);
		//Obtener un ID para la bitacora
		$idBitacora=obtenerIdBitConsultasMedicas();
		//Abrir la conexion con la BD
		$conn=conecta("bd_clinica");
		//Sentencia SQL para ingresar el registro a la bitacora
		$stm_sql="INSERT INTO bitacora_consultas (id_bit_consultas,catalogo_empresas_id_empresa,empleados_rfc_empleado,id_empleados_empresa,nom_empleado,
				area,puesto,tipo_consulta,consulta,nom_familiar,parentesco,fecha,hora,lugar,pb_diagnostico,tratamiento,observaciones) 
				VALUES ('$idBitacora','$idEmpresa','$rfc','$numEmp','$nomEmpleado','$area','$puesto','$tipoConsulta','$consulta','$nom_familiar',
				'$parentesco','$fecha','$hora','$lugar','$diagnostico','$tratamiento','$observaciones')";
		$rs = mysql_query($stm_sql);
		if($rs){
			//Cerrar la conexion con la BD
			mysql_close($conn);
			//Registrar Operacion de inrecion a la bitacora
			registrarOperacion("bd_clinica",$idBitacora,"RegBitConsultaMedicaExterna",$_SESSION['usr_reg']);
			//Variable de control de exito o error
			$res=1;
			//Verificar si esta definido el arreglo de medicamentos para registrar sus salidas
			if(isset($_SESSION["medicamento"])){
				//Actualizar la existencia de Medicamentos
				$res=actualizarExistenciaMedicamentos($idBitacora,$fecha);
				//Borrar de la Sesion el arreglo de los Medicamentos
				unset($_SESSION["medicamento"]);
			}
			if($res==1){
				registrarOperacion("bd_clinica",$idBitacora,"RegEntregaMedicamento",$_SESSION['usr_reg']);
				echo"<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
			else{
				$error=mysql_error();
				//Redireccionar a la pantalla de Error
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			$error=mysql_error();
			//Redireccionar a la pantalla de Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			//Cerrar la conexion con la BD
			mysql_close($conn);
		}
	}//Fin de function guardaBitExterna()
	
	function guardarRegInfMedico(){
		//Obtener el Id de la Consulta Medica
		$idConsMed=obtenerIdBitConsultasMedicas();
		//Obtener el ID del informe Medico
		$idInforme=obtenerIdInfMedico();
		$edad=$_POST["txt_edad"];
		$depto=$_POST["txt_depto"];
		$area=$_POST["txt_area"];
		$lugar=strtoupper($_POST["txt_lugar"]);
		$puesto=$_POST["txt_actividad"];
		$antigPuesto=$_POST["txt_antigPuesto"];
		$antigEmp=$_POST["txt_antigEmp"];
		$fechaRT=modFecha($_POST["txt_fechaRT"],3);
		$horaRT=$_POST["txt_horaRT"]." ".$_POST["cmb_horaRT"];
		//ESPACIO IMPORTANTE----------^
		//Modificar la hora a formato 24hrs
		$horaRT=modHora24($horaRT);
		$fechaCons=modFecha($_POST["txt_fechaConsulta"],3);
		$horaCons=$_POST["txt_horaConsulta"]." ".$_POST["cmb_horaConsulta"];
		//ESPACIO IMPORTANTE------------------^
		//Modificar la hora a formato 24hrs
		$horaCons=modHora24($horaCons);
		$padecimiento=strtoupper($_POST["txa_mecanismo"]);
		$descripcion=strtoupper($_POST["txa_descripcion"]);
		$diagnostico=strtoupper($_POST["txa_diagnostico"]);
		$tratamiento=strtoupper($_POST["txa_tratamiento"]);
		$auxDx=strtoupper($_POST["txa_auxDiagnostico"]);
		$supervisor=strtoupper($_POST["txt_supervisor"]);
		$facilitador=strtoupper($_POST["txt_facilitador"]);
		$avisado=strtoupper($_POST["txt_avisado"]);
		$condNada="0";
		$condIntox="0";
		$condRina="0";
		$condSim="0";
		$condEner="0";
		$condLesion="0";
		if(isset($_POST["ckb_ninguna"]))
			$condNada="1";
		if(isset($_POST["ckb_rina"]))
			$condRina="1";
		if(isset($_POST["ckb_intox"]))
			$condIntox="1";
		if(isset($_POST["ckb_sim"]))
			$condSim="1";
		if(isset($_POST["ckb_ener"]))
			$condEner="1";
		if(isset($_POST["ckb_lesion"]))
			$condLesion="1";
		$manejoAux=0;
		$manejoMedico=0;
		$manejoIMSS=0;
		if(isset($_POST["ckb_aux"]))
			$manejoAux="1";
		if(isset($_POST["ckb_medico"]))
			$manejoMedico="1";
		if(isset($_POST["ckb_imss"]))
			$manejoIMSS="1";
		$traslado=$_POST["cmb_ambulancia"];
		$ambulancia=strtoupper($_POST["txt_ambulancia"]);
		$calif=$_POST["cmb_calificacion"];
		$dias=$_POST["txt_dias"];
		$obs=strtoupper($_POST["txa_observaciones"]);
		$responsable=strtoupper($_POST["txt_resposable"]);
		
		//Abrir la conexion a la BD
		$conn=conecta("bd_clinica");
		//Crear sentencia SQL para guardar el Informe Medico
		$sql_stm="INSERT INTO informe_medico 
				(bitacora_consultas_id_bit_consultas,id_informe,edad,depto,area,lugar,puesto,antig_puesto,antig_empresa,fecha_rt,hora_rt,fecha_consulta,hora_consultada,padecimiento,des_accidente,
				diagnostico,tratamiento,auxiliares_diag,nom_supervisor,nom_facilitador,aviso_a,cond_ninguna,cond_intox,cond_rina,cond_sim,cond_ener,cond_lesion,
				manejo_aux,manejo_medico,manejo_imss,tras_amb,especifique_tras,cal_incidente,num_dias,observaciones,nom_res) VALUES
				('$idConsMed','$idInforme','$edad','$depto','$area','$lugar','$puesto','$antigPuesto','$antigEmp','$fechaRT','$horaRT','$fechaCons','$horaCons','$padecimiento','$descripcion',
				'$diagnostico','$tratamiento','$auxDx','$supervisor','$facilitador','$avisado','$condNada','$condIntox','$condRina','$condSim','$condEner','$condLesion',
				'$manejoAux','$manejoMedico','$manejoIMSS','$traslado','$ambulancia','$calif','$dias','$obs','$responsable')";
		//Ejecutar la Sentencia SQL
		$rs=mysql_query($sql_stm);
		//Cerrar la conexion con la BD
		mysql_close($conn);
		if($rs){
			//Recopilar los datos del Arreglo de session para registrar en la bitacora de consultas
			$rfc=$_SESSION["datosConsMedica"]["rfc"];
			$numEmp=$_SESSION["datosConsMedica"]["numEmp"];
			$nomEmpleado=$_SESSION["datosConsMedica"]["nomEmpleado"];
			$area=$_SESSION["datosConsMedica"]["area"];
			$puesto=$_SESSION["datosConsMedica"]["puesto"];
			$tipoConsulta=$_SESSION["datosConsMedica"]["tipoConsulta"];
			$consulta=$_SESSION["datosConsMedica"]["consulta"];
			$nom_familiar=$_SESSION["datosConsMedica"]["nom_familiar"];
			$parentesco=$_SESSION["datosConsMedica"]["parentesco"];
			$fecha=$_SESSION["datosConsMedica"]["fecha"];
			$hora=$_SESSION["datosConsMedica"]["hora"];
			$lugar=$_SESSION["datosConsMedica"]["lugar"];
			$diagnostico=$_SESSION["datosConsMedica"]["diagnostico"];
			$tratamiento=$_SESSION["datosConsMedica"]["tratamiento"];
			$observaciones=$_SESSION["datosConsMedica"]["observaciones"];
			//Abrir la conexion con la BD
			$conn=conecta("bd_clinica");
			//Sentencia SQL para ingresar el registro a la bitacora
			$stm_sql="INSERT INTO bitacora_consultas (id_bit_consultas,catalogo_empresas_id_empresa,empleados_rfc_empleado,id_empleados_empresa,nom_empleado,
					area,puesto,tipo_consulta,consulta,nom_familiar,parentesco,fecha,hora,lugar,pb_diagnostico,tratamiento,observaciones) 
					VALUES ('$idConsMed','','$rfc','$numEmp','$nomEmpleado','$area','$puesto','$tipoConsulta','$consulta','$nom_familiar',
					'$parentesco','$fecha','$hora','$lugar','$diagnostico','$tratamiento','$observaciones')";
			//Ejecutar la sentencia SQL
			$rs = mysql_query($stm_sql);
			//Quitar de la sesion el arreglo de datos de la consulta Medica
			unset($_SESSION["datosConsMedica"]);
			//Cerrar la conexion con la BD
			mysql_close($conn);	
			//Verifricar si la consulta se ejecuto con exito
			if($rs){
				//Registrar Operacion de inrecion a la bitacora
				registrarOperacion("bd_clinica",$idConsMed,"RegBitConsultaMedicaInternaAcc",$_SESSION['usr_reg']);
				//Variable de control de exito o error
				$res=1;
				//Verificar si esta definido el arreglo de medicamentos para registrar sus salidas
				if(isset($_SESSION["medicamento"])){
					//Actualizar la existencia de Medicamentos
					$res=actualizarExistenciaMedicamentos($idConsMed,$fecha);
					//Borrar de la Sesion el arreglo de los Medicamentos
					unset($_SESSION["medicamento"]);
				}
				if($res==1){
					registrarOperacion("bd_clinica",$idConsMed,"RegEntregaMedicamento",$_SESSION['usr_reg']);
					?>
					<script type='text/javascript' language='javascript'>
						setTimeout("window.open('../../includes/generadorPDF/infMedico.php?id=<?php echo $idInforme; ?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",4000);
					</script>
					<?php
					//Redireccionar a la Pagina de exito despues de 5 segundos				
					echo "<meta http-equiv='refresh' content='5;url=exito.php'>";
				}
				else{
					$error=mysql_error();
					//Redireccionar a la pantalla de Error
					echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
				}
			}
			else{
				$error=mysql_error();
				//Redireccionar a la pantalla de Error
				echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
			}
		}
		else{
			$error=mysql_error();
			//Redireccionar a la pantalla de Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	//Funcion que muestra los registros en la bit�cora de Radiografias
	function mostrarBitacoraConsultas($fechaIni,$fechaFin,$clasificacion,$tipo){
		$fechaI=modFecha($fechaIni,3);
		$fechaF=modFecha($fechaFin,3);
		$conn=conecta("bd_clinica");
		if($clasificacion=="" && $tipo=="")
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas,catalogo_empresas_id_empresa,nom_empleado,area,puesto,tipo_consulta,consulta,fecha,hora,lugar
						FROM bitacora_consultas WHERE fecha BETWEEN '$fechaI' AND '$fechaF' ORDER BY catalogo_empresas_id_empresa,fecha,nom_empleado";
		else if($clasificacion!="" && $tipo!="")
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas,catalogo_empresas_id_empresa,nom_empleado,area,puesto,tipo_consulta,consulta,fecha,hora,lugar
						FROM bitacora_consultas WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND consulta='$clasificacion' AND tipo_consulta='$tipo' ORDER BY catalogo_empresas_id_empresa,fecha,nom_empleado";
		else if($clasificacion!="" && $tipo=="")
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas,catalogo_empresas_id_empresa,nom_empleado,area,puesto,tipo_consulta,consulta,fecha,hora,lugar
						FROM bitacora_consultas WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND consulta='$clasificacion' ORDER BY catalogo_empresas_id_empresa,fecha,nom_empleado";
		else if($clasificacion=="" && $tipo!="")
			//Sentencia SQL para guardar el registro de Bitacora por Fechas solamente
			$sql_stm="SELECT id_bit_consultas,catalogo_empresas_id_empresa,nom_empleado,area,puesto,tipo_consulta,consulta,fecha,hora,lugar
						FROM bitacora_consultas WHERE fecha BETWEEN '$fechaI' AND '$fechaF' AND tipo_consulta='$tipo' ORDER BY catalogo_empresas_id_empresa,fecha,nom_empleado";
		//Ejecutar la sentencia SQL				
		$rs=mysql_query($sql_stm);
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosRegBitacora'>
				<caption class='titulo_etiqueta'>Registros de la Bit&aacute;cora de Consultas M&eacute;dicas del $fechaIni al $fechaFin</caption>
				<thead>
					<tr>
						<th class='nombres_columnas' align='center'>SELECCIONAR</th>
						<th class='nombres_columnas' align='center'>EMPRESA</th>
        				<th class='nombres_columnas' align='center'>NOMBRE TRABAJADOR</th>
				        <th class='nombres_columnas' align='center'>&Aacute;REA</th>
        				<th class='nombres_columnas' align='center'>PUESTO</th>
						<th class='nombres_columnas' align='center'>FECHA</th>
						<th class='nombres_columnas' align='center'>HORA</th>
        				<th class='nombres_columnas' align='center'>INFORME M&Eacute;DICO</th>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{
				//Obtener el Id del Informe Medico
				$idInf=obtenerDato("bd_clinica","informe_medico", "id_informe", "bitacora_consultas_id_bit_consultas",$datos['id_bit_consultas']);
				
				if($datos["catalogo_empresas_id_empresa"]==0)
					$empresa="CONCRETO LANZADO DE FRESNILLO MARCA";
				else
					$empresa=obtenerDato("bd_clinica","catalogo_empresas", "nom_empresa", "id_empresa", $datos['catalogo_empresas_id_empresa']);
				
				echo "	<tr>
						<td class='nombres_filas' align='center'>";
						?>
						<input type="radio" name="rdb_bitacora" id="rdb_bitacora" value="<?php echo $datos['id_bit_consultas']?>"/>
						<?php
				echo "</td>
						<td class='$nom_clase' align='center'>$empresa</td>
						<td class='$nom_clase' align='center'>$datos[nom_empleado]</td>
						<td class='$nom_clase' align='center'>$datos[area]</td>
						<td class='$nom_clase' align='center'>$datos[puesto]</td>
						<td class='$nom_clase' align='center'>".modFecha($datos["fecha"],1)."</td>
						<td class='$nom_clase' align='center'>".modHora($datos["hora"])."</td>";
				if($idInf!=""){
						?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verInfMed<?php echo $cont?>" id="btn_verInfMed<?php echo $cont?>" class="botones" value="Informe M&eacute;dico" 
							onMouseOver="window.estatus='';return true" title="Ver Informe M&eacute;dico" 
							onClick="javascript:window.open('../../includes/generadorPDF/infMedico.php?id=<?php echo $idInf?>', '_blank','top=0, left=0, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')"/>							
						</td>						
					</tr>
				<?php
				}
				else{
				?>
						<td class="<?php echo $nom_clase; ?>" align="center">
							<input type="button" name="btn_verInfMed<?php echo $cont?>" id="btn_verInfMed<?php echo $cont?>" class="botones" value="Informe M&eacute;dico" 
							title="El Trabajador no Tiene Informe M&eacute;dico" disabled="disabled"/>
						</td>						
					</tr>
				<?php
				}
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";					
			}while($datos=mysql_fetch_array($rs));
			echo "</tbody>";
			echo "</table>";
		}
		else{
			echo "<meta http-equiv='refresh' content='0;url=frm_modBitacoraConsultasMed.php?noResults'>";
		}
		mysql_close($conn);
	}
	
	//Funcion que borra los registros de la bitacora de Consultas Medicas
	function borrarRegBitacoraMed($idBit){
		//Abrir la conexion a la BD
		$conn=conecta("bd_clinica");
		//Sentencia SQL para borrar el registro de Bitacora
		$sql_stm="DELETE FROM bitacora_consultas WHERE id_bit_consultas='$idBit'";
		$rs=mysql_query($sql_stm);
		if($rs){
			//Sentencia SQL para borrar el registro del posible Informe Medico generado
			$sql_stm="DELETE FROM informe_medico WHERE bitacora_consultas_id_bit_consultas='$idBit'";
			$rs=mysql_query($sql_stm);			
			//Recuperar el Medicamento del Catalogo si se borr� el registro
			$sql_stm="SELECT catalogo_medicamento_id_med,cant_salida FROM bitacora_medicamentos WHERE bitacora_consultas_id_bit_consultas='$idBit'";
			//Ejecutar la sentencia
			$rs=mysql_query($sql_stm);
			//Verificar que si existan resultados
			if($datos=mysql_fetch_array($rs)){
				do{
					//Sentencia de actualizacion de cantidad de Medicamento
					$sql="UPDATE catalogo_medicamento SET existencia_actual=existencia_actual+$datos[cant_salida] WHERE id_med='$datos[catalogo_medicamento_id_med]'";
					$rsUpd=mysql_query($sql);
				}while($datos=mysql_fetch_array($rs));
			}
			//Sentencia SQL para borrar el registro del posible Medicamento Entregado en el Registro de Bitacora
			$sql_stm="DELETE FROM bitacora_medicamentos WHERE bitacora_consultas_id_bit_consultas='$idBit'";
			$rs=mysql_query($sql_stm);
			//Cerrar la conexion con la BD
			mysql_close($conn);
			//Guardar el Movimiento realizado en la tabla de Movimientos
			registrarOperacion("bd_clinica","$idBit","BorrarRegBitConsultas",$_SESSION['usr_reg']);
			//Redireccionar a la pagina de exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			//Cerrar la conexion con la BD
			mysql_close($conn);
			$error=mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	}
	
	//Funcion que actualiza el regisro de la bitacora Medica
	function modificarRegBitacoraMed($idBit){
		$fechaICons1=$_POST["hdn_fechaI"];
		$fechaFCons1=$_POST["hdn_fechaF"];
		$clasificacionCons1=$_POST["hdn_clasificacion"];
		$tipoCons1=$_POST["hdn_tipo"];
		//Abrir la conexion a la BD
		$conn=conecta("bd_clinica");
		//Sentencia SQL para guardar el registro de Bitacora
		$sql_stm="SELECT catalogo_empresas_id_empresa,empleados_rfc_empleado,id_empleados_empresa,nom_empleado,area,puesto,tipo_consulta,consulta,nom_familiar,
					parentesco,fecha,hora,lugar,pb_diagnostico,tratamiento,observaciones FROM bitacora_consultas WHERE id_bit_consultas='$idBit'";
		$rs=mysql_query($sql_stm);
		$datos=mysql_fetch_array($rs);
		$idEmpresa=$datos["catalogo_empresas_id_empresa"];
		$fecha=modFecha($datos["fecha"],1);
		$tipoCons=$datos["tipo_consulta"];
		$horaBD=modHora($datos["hora"]);
		$mer=substr($horaBD,-2);
		$hora=substr($horaBD,0,5);
		$consulta=$datos["consulta"];
		$empleado=$datos["nom_empleado"];
		$rfc=$datos["empleados_rfc_empleado"];
		$numEmp=$datos["id_empleados_empresa"];
		$lugar=$datos["lugar"];
		$area=$datos["area"];
		$puesto=$datos["puesto"];
		$diag=$datos["pb_diagnostico"];
		$tratamiento=$datos["tratamiento"];
		$obs=$datos["observaciones"];
		$nomFamiliar=$datos["nom_familiar"];
		$parentesco=$datos["parentesco"];
		
		if($consulta=="INTERNA"){
			?>
			<div id="calendario">
				<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_regBitConInterna.txt_fecha,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
			</div>
			
			<fieldset id="tabla-consulta" class="borde_seccion">
			<legend class="titulo_etiqueta">Bit&aacute;cora de Consultas M&eacute;dicas a Personal de Concreto Lanzado de Fresnillo Marca</legend>
			<br>	
			<form name="frm_regBitConInterna" method="post" action="frm_modBitacoraConsultasMed.php" onsubmit="return valFormConsMedInterna(this);">
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<tr>
				  <td width="92"><div align="right">Empresa</div></td>
					<td width="267">
						<input type="text" name="txt_consulta" id="txt_consulta" class="caja_de_texto" readonly="readonly" value="CONCRETO LANZADO DE FRESNILLO MARCA" size="40" />
				  </td>
				  <td width="92"><div align="right">Consulta</div></td>
					<td width="150">
						<input type="text" name="txt_consulta" id="txt_consulta" class="caja_de_texto" readonly="readonly" value="INTERNA" size="10" />
				  </td>
				  <td width="91"><div align="right">Tipo de Consulta</div></td>
					<td width="113">
						<input type="text" name="txt_tipoConsulta" id="txt_tipoConsulta" class="caja_de_texto" readonly="readonly" value="<?php echo $tipoCons;?>" size="10" />
				  </td>
				</tr>
				<tr>
					<td><div align="right">Fecha</div></td>
					<td>
						<input name="txt_fecha" type="text" id="txt_fecha" value="<?php echo $fecha;?>" size="10" maxlength="15" readonly="readonly" class="caja_de_texto"/>
					</td>
					<td><div align="right">*Hora</div></td>
					<td>
						<input type="text" class="caja_de_texto" name="txt_hora" id="txt_hora" size="5" onchange="formatHora(this,'cmb_hora');" maxlength="5"
						onkeypress="return permite(event,'num',0);" value="<?php echo $hora; ?>"/>&nbsp;
						<select name="cmb_hora" id="cmb_hora" class="combo_box">
							<option value="AM"<?php if($mer=="AM") echo " selected='selected'";?>>a.m.</option>
							<option value="PM"<?php if($mer=="PM") echo " selected='selected'";?>>p.m.</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><div align="right">Empleado</div></td>
					<td>
						<input type="text" name="txt_nombre" id="txt_nombre" class="caja_de_texto" onkeyup="lookup(this,'empleados','1');" value="<?php echo $empleado;?>" size="50" maxlength="75" onkeypress="return permite(event,'car',0);"/>
						<div id="res-spider">
							<div align="left" class="suggestionsBox" id="suggestions1" style="display: none;">
							<img src="../../images/upArrow.png" style="position: relative; top: -12px; left: 10px;" alt="upArrow"/>
							<div class="suggestionList" id="autoSuggestionsList1">&nbsp;</div>
							</div>
						</div>
					</td>
					<td><div align="right">RFC</div></td>
					<td>
						<input type="text" name="txt_rfc" id="txt_rfc" class="caja_de_texto" readonly="readonly" value="<?php echo $rfc?>" size="15" maxlength="15"/>
					</td>
					<td><div align="right">Num. Empleado</div></td>
					<td>
						<input type="text" name="txt_noEmpleado" id="txt_noEmpleado" class="caja_de_num" readonly="readonly" value="<?php echo $numEmp?>" size="10" maxlength="20"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">
						<?php if($nomFamiliar==""){?>
							<input type="checkbox" name="ckb_familiar" id="ckb_familiar" onclick="activarFamiliarTrabajador(this,txt_nomFamiliar,txt_parentesco);"/>Familiar
						<?php }else{?>
							<input type="checkbox" name="ckb_familiar" id="ckb_familiar" onclick="activarFamiliarTrabajador(this,txt_nomFamiliar,txt_parentesco);" checked="checked"/>Familiar
						<?php }?>
					</div></td>
					<td><input type="text" name="txt_nomFamiliar" id="txt_nomFamiliar" class="caja_de_texto" value="<?php echo $nomFamiliar;?>" size="50" maxlength="75" 
						onkeypress="return permite(event,'car',0);" <?php if($nomFamiliar=="") echo "readonly='readonly'"?>/></td>
					<td><div align="right">Parentesco</div></td>
					<td><input type="text" name="txt_parentesco" id="txt_parentesco" class="caja_de_texto" value="<?php echo $parentesco;?>" size="30" maxlength="30" 
						onkeypress="return permite(event,'car',0);" <?php if($nomFamiliar=="") echo "readonly='readonly'"?>/></td>
				</tr>
				<tr>
					<td><div align="right">*Lugar</div></td>
					<td>
						<input type="text" name="txt_lugar" id="txt_lugar" class="caja_de_texto" value="<?php echo $lugar;?>" size="40" maxlength="60" onkeypress="return permite(event,'car',0);"/>
					</td>
					<td><div align="right">*&Aacute;rea</div></td>
					<td>
						<input type="text" name="txt_area" id="txt_area" class="caja_de_texto" value="<?php echo $area?>" size="20" maxlength="20" onkeypress="return permite(event,'car',0);" readonly="readonly"/>
					</td>
					<td><div align="right">*Puesto</div></td>
					<td>
						<input type="text" name="txt_puesto" id="txt_puesto" class="caja_de_texto" value="<?php echo $puesto?>" size="20" maxlength="30" onkeypress="return permite(event,'car',0);" readonly="readonly"/>
					</td>
				</tr>
			  </table>
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<tr>
					<td valign="top"><div align="right">*PB Diagn&oacute;stico</div></td>
					<td>
						<textarea name="txa_diagnostico" id="txa_diagnostico" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $diag?></textarea>
					</td>
					<td valign="top"><div align="right">*Tratamiento</div></td>
					<td>
						<textarea name="txa_tratamiento" id="txa_tratamiento" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $tratamiento?></textarea>
					</td>
					<td valign="top"><div align="right">Observaciones</div></td>
					<td>
						<textarea name="txa_observaciones" id="txa_observaciones" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $obs?></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="6" align="center">
						<input type="hidden" name="hdn_borrarMed" id="hdn_borrarMed" value="0"/>
						<input type="hidden" name="hdn_regBit" id="hdn_regBit" value="<?php echo $idBit?>"/>
						<?php if(!isset($_SESSION["medicamento"])){?>
							<input type="hidden" name="hdn_medicamento" id="hdn_medicamento" value="0"/>
						<?php }else{?>
							<input type="hidden" name="hdn_medicamento" id="hdn_medicamento" value="1"/>
						<?php }?>
						<input type="button" name="btn_regMedicamento" id="btn_regMedicamento" class="botones_largos" title="Registrar Medicamentos Suministrados al Trabajador" value="Registrar Medicamentos"
						onclick="actualizarMedicamento(this,'<?php echo $idBit?>');" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" name="sbt_guardarMod" id="sbt_guardarMod" class="botones" title="Modificar el Registro en la Bit&aacute;cora de Consultas" value="Modificar" 
						onmouseover="window.status='';return true;"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" title="Limpiar el Formulario" value="Limpiar" onclick="restablecerFormConMedInterno(txt_nomFamiliar,txt_parentesco);"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Cancelar y Volver a Seleccionar Otros Criterios para la Consulta M&eacute;dica" value="Regresar" 
						onclick="location.href='frm_modBitacoraConsultasMed.php?fechaI=<?php echo $fechaICons1?>&fechaF=<?php echo $fechaFCons1?>&clasificacion=<?php echo $clasificacionCons1?>&tipo=<?php echo $tipoCons1?>&cancel'"/>
					</td>
				</tr>
			</table>
			</form>
			</fieldset>
			<?php
		}
		if($consulta=="EXTERNA"){
			?>
			<div id="calendario">
				<input name="calendario_iniRep" type="image" id="calendario_iniRep" onclick="displayCalendar(document.frm_regBitConExterna.txt_fecha,'dd/mm/yyyy',this)" 
				onmouseover="window.status='';return true" src="../../images/calendar.png" align="absbottom" width="25" height="25" border="0" />
			</div>
			
			<fieldset id="tabla-consulta" class="borde_seccion">
			<legend class="titulo_etiqueta">Bit&aacute;cora de Consultas M&eacute;dicas a Empresas Externas</legend>
			<br>	
			<form name="frm_regBitConExterna" method="post" action="frm_modBitacoraConsultasMed.php" onsubmit="return valFormConsMedExterna(this);">
			<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<tr>
				  <td width="92"><div align="right">Empresa</div></td>
					<td width="267">
						<?php 
						$result=cargarComboConId("cmb_empresa","nom_empresa","id_empresa","catalogo_empresas","bd_clinica","Seleccionar",$idEmpresa,"");
						if($result==0){
							echo "<label class='msje_correcto'>No hay Empresas Registradas</label>
							<input type='hidden' name='cmb_empresa' id='cmb_empresa'/>";
						}
						?>
				  </td>
				  <td width="92"><div align="right">Consulta</div></td>
					<td width="150">
						<input type="text" name="txt_consulta" id="txt_consulta" class="caja_de_texto" readonly="readonly" value="EXTERNA" size="10" />
				  </td>
				  <td width="91"><div align="right">Tipo de Consulta</div></td>
					<td width="113">
						<input type="text" name="txt_tipoConsulta" id="txt_tipoConsulta" class="caja_de_texto" readonly="readonly" value="<?php echo $tipoCons;?>" size="10" />
				  </td>
				</tr>
				<tr>
					<td><div align="right">Fecha</div></td>
					<td>
						<input name="txt_fecha" type="text" id="txt_fecha" value="<?php echo $fecha?>" size="10" maxlength="15" readonly="readonly" class="caja_de_texto"/>
					</td>
					<td><div align="right">*Hora</div></td>
					<td>
						<input type="text" class="caja_de_texto" name="txt_hora" id="txt_hora" size="5" onchange="formatHora(this,'cmb_hora');" maxlength="5"
						onkeypress="return permite(event,'num',0);" value="<?php echo $hora; ?>"/>&nbsp;
						<select name="cmb_hora" id="cmb_hora" class="combo_box">
							<option value="AM"<?php if($mer=="AM") echo " selected='selected'";?>>a.m.</option>
							<option value="PM"<?php if($mer=="PM") echo " selected='selected'";?>>p.m.</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><div align="right">Empleado</div></td>
					<td>
						<input type="text" name="txt_nombre" id="txt_nombre" class="caja_de_texto" value="<?php echo $empleado;?>" size="50" maxlength="75" onkeypress="return permite(event,'car',0);"/>
					</td>
					<td><div align="right">RFC</div></td>
					<td>
						<input type="text" name="txt_rfc" id="txt_rfc" class="caja_de_texto" value="<?php echo $rfc;?>" size="15" onkeypress="return permite(event,'num_car', 3);" maxlength="15"/>
					</td>
					<td><div align="right">Num. Empleado</div></td>
					<td>
						<input type="text" name="txt_noEmpleado" id="txt_noEmpleado" class="caja_de_num" value="<?php echo $numEmp;?>" size="10" onkeypress="return permite(event,'num',3);" maxlength="10"/>
					</td>
				</tr>
				<tr>
					<td><div align="right">
						<?php if($nomFamiliar==""){?>
							<input type="checkbox" name="ckb_familiar" id="ckb_familiar" onclick="activarFamiliarTrabajador(this,txt_nomFamiliar,txt_parentesco);"/>Familiar
						<?php }else{?>
							<input type="checkbox" name="ckb_familiar" id="ckb_familiar" onclick="activarFamiliarTrabajador(this,txt_nomFamiliar,txt_parentesco);" checked="checked"/>Familiar
						<?php }?>
					</div></td>
					<td><input type="text" name="txt_nomFamiliar" id="txt_nomFamiliar" class="caja_de_texto" value="<?php echo $nomFamiliar;?>" size="50" maxlength="75" 
						onkeypress="return permite(event,'car',0);" <?php if($nomFamiliar=="") echo "readonly='readonly'"?>/></td>
					<td><div align="right">Parentesco</div></td>
					<td><input type="text" name="txt_parentesco" id="txt_parentesco" class="caja_de_texto" value="<?php echo $parentesco;?>" size="30" maxlength="30" 
						onkeypress="return permite(event,'car',0);" <?php if($nomFamiliar=="") echo "readonly='readonly'"?>/></td>
				</tr>
				<tr>
					<td><div align="right">*Lugar</div></td>
					<td>
						<input type="text" name="txt_lugar" id="txt_lugar" class="caja_de_texto" value="<?php echo $lugar;?>" size="40" maxlength="60" onkeypress="return permite(event,'car',0);"/>
					</td>
					<td><div align="right">*&Aacute;rea</div></td>
					<td>
						<input type="text" name="txt_area" id="txt_area" class="caja_de_texto" value="<?php echo $area?>" size="20" maxlength="20" onkeypress="return permite(event,'car',0);"/>
					</td>
					<td><div align="right">*Puesto</div></td>
					<td>
						<input type="text" name="txt_puesto" id="txt_puesto" class="caja_de_texto" value="<?php echo $puesto?>" size="20" maxlength="30" onkeypress="return permite(event,'car',0);"/>
					</td>
				</tr>
			  </table>
				<table border="0" cellpadding="5" cellspacing="5" class="tabla_frm" width="100%">
				<tr>
					<td valign="top"><div align="right">*PB Diagn&oacute;stico</div></td>
					<td>
						<textarea name="txa_diagnostico" id="txa_diagnostico" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $diag?></textarea>
					</td>
					<td valign="top"><div align="right">*Tratamiento</div></td>
					<td>
						<textarea name="txa_tratamiento" id="txa_tratamiento" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"><?php echo $tratamiento?></textarea>
					</td>
					<td valign="top"><div align="right">Observaciones</div></td>
					<td>
						<textarea name="txa_observaciones" id="txa_observaciones" maxlength="300" onkeyup="return ismaxlength(this)" class="caja_de_texto" 
						rows="4" cols="30" onkeypress="return permite(event,'num_car', 0);"<?php echo $obs?>></textarea>
					</td>
				</tr>
				<tr>
					<td colspan="6" align="center">
						<input type="hidden" name="hdn_borrarMed" id="hdn_borrarMed" value="0"/>
						<input type="hidden" name="hdn_regBit" id="hdn_regBit" value="<?php echo $idBit?>"/>
						<input type="hidden" name="hdn_medicamento" id="hdn_medicamento" value="0"/>
						<input type="button" name="btn_regMedicamento" id="btn_regMedicamento" class="botones_largos" title="Registrar Medicamentos Suministrados al Trabajador" value="Registrar Medicamentos"
						onclick="actualizarMedicamento(this,'<?php echo $idBit?>');" />
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="submit" name="sbt_guardarMod" id="sbt_guardarMod" class="botones" title="Modificar el Registro en la Bit&aacute;cora de Consultas" value="Modificar" 
						onmouseover="window.status='';return true;"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="reset" name="btn_limpiar" id="btn_limpiar" class="botones" title="Limpiar el Formulario" value="Limpiar" onclick="restablecerFormConMedInterno(txt_nomFamiliar,txt_parentesco);"/>
						&nbsp;&nbsp;&nbsp;&nbsp;
						<input type="button" name="btn_regresar" id="btn_regresar" class="botones" title="Cancelar y Volver a Seleccionar Otros Criterios para la Consulta M&eacute;dica" value="Regresar" 
						onclick="location.href='frm_modBitacoraConsultasMed.php?fechaI=<?php echo $fechaICons1?>&fechaF=<?php echo $fechaFCons1?>&clasificacion=<?php echo $clasificacionCons1?>&tipo=<?php echo $tipoCons1?>&cancel'"/>
					</td>
				</tr>
			</table>
			</form>
			</fieldset>
			<?php
		}
	}
	
	//Esta funcion guarda las modificaciones en la bitacora de Medicamentos
	function guardarModBitMed(){
		//Recuperar el ID de la Bitacora de Consultas
		$idBit=$_POST["hdn_regBit"];
		//Verificar el tipo de consulta
		if($_POST["txt_consulta"]=="INTERNA"){
			//Recuperar los datos del POST
			$idEmpresa=0;
			$rfc=$_POST["txt_rfc"];
			$numEmp=$_POST["txt_noEmpleado"];
			$nomEmpleado=$_POST["txt_nombre"];
			$area=$_POST["txt_area"];
			$puesto=$_POST["txt_puesto"];
			$tipoConsulta=$_POST["txt_tipoConsulta"];
			$consulta=$_POST["txt_consulta"];
			$nom_familiar=strtoupper($_POST["txt_nomFamiliar"]);
			$parentesco=strtoupper($_POST["txt_parentesco"]);
			$fecha=modFecha($_POST["txt_fecha"],3);
			//Manejamos la Hora en una cadena de tipo hh:mm AM/PM para que se convierta al formato soportado por MySQL
			$hora=$_POST["txt_hora"]." ".$_POST["cmb_hora"];
			//ESPACIO IMPORTANTE------^
			//Modificar la hora a formato 24hrs
			$hora=modHora24($hora);
			$lugar=strtoupper($_POST["txt_lugar"]);
			$diagnostico=strtoupper($_POST["txa_diagnostico"]);
			$tratamiento=strtoupper($_POST["txa_tratamiento"]);
			$observaciones=strtoupper($_POST["txa_observaciones"]);
		}
		else{
			//Recuperar los datos del POST
			$idEmpresa=$_POST["cmb_empresa"];
			$rfc=strtoupper($_POST["txt_rfc"]);
			$numEmp=$_POST["txt_noEmpleado"];
			$nomEmpleado=strtoupper($_POST["txt_nombre"]);
			$area=strtoupper($_POST["txt_area"]);
			$puesto=strtoupper($_POST["txt_puesto"]);
			$tipoConsulta=$_POST["txt_tipoConsulta"];
			$consulta=$_POST["txt_consulta"];
			$nom_familiar=strtoupper($_POST["txt_nomFamiliar"]);
			$parentesco=strtoupper($_POST["txt_parentesco"]);
			$fecha=modFecha($_POST["txt_fecha"],3);
			//Manejamos la Hora en una cadena de tipo hh:mm AM/PM para que se convierta al formato soportado por MySQL
			$hora=$_POST["txt_hora"]." ".$_POST["cmb_hora"];
			//ESPACIO IMPORTANTE------^
			//Modificar la hora a formato 24hrs
			$hora=modHora24($hora);
			$lugar=strtoupper($_POST["txt_lugar"]);
			$diagnostico=strtoupper($_POST["txa_diagnostico"]);
			$tratamiento=strtoupper($_POST["txa_tratamiento"]);
			$observaciones=strtoupper($_POST["txa_observaciones"]);
		}
		//conectar a la BD de clinica
		$conn=conecta("bd_clinica");
		//Sentencia SQL
		$sql_stm="UPDATE bitacora_consultas SET catalogo_empresas_id_empresa='$idEmpresa', empleados_rfc_empleado='$rfc', id_empleados_empresa='$numEmp', nom_empleado='$nomEmpleado', area='$area',
					puesto='$puesto', tipo_consulta='$tipoConsulta', consulta='$consulta', nom_familiar='$nom_familiar', parentesco='$parentesco', fecha='$fecha', hora='$hora', lugar='$lugar',
					pb_diagnostico='$diagnostico', tratamiento='$tratamiento', observaciones='$observaciones' WHERE id_bit_consultas='$idBit'";
		//Ejecutar la sentencia SQL
		$rs=mysql_query($sql_stm);
		if($rs){
			//Si esta definido el arreglo de Sesion, editar el medicamento Entregado
			if(isset($_SESSION["medicamento"])){
				//Variable bandera para saber si se debe registrar movimiento en la tabla de bitacora_medicamentos
				//siempre y cuando haya medicamentos registrados que salieron anteriormente para el registro de bitacora
				$band=0;
				//Verificar si el Medicamento que sale es el mismo que ya se tenia registrado para actualizar el catalogo de Medicamento
				$sql_stm="SELECT catalogo_medicamento_id_med,cant_salida FROM bitacora_medicamentos WHERE bitacora_consultas_id_bit_consultas='$idBit'";
				//Arreglo que contendra los medicamentos que son parte de la bitacora de medicamentos previa
				$rs=mysql_query($sql_stm);
				//Verificar los medicamentos suministrados en dicho registro de bitacora
				if($datos=mysql_fetch_array($rs)){
					//Si se encontraron medicamentos previos, activar la variable bandera
					$band=1;
					//Recorrer los medicamentos suministrados
					do{
						//Verificar si esta definido el medicamento en el arreglo de sesion para actualizar el catalogo de Medicamento en su existencia
						if(isset($_SESSION["medicamento"][$datos["catalogo_medicamento_id_med"]])){
							//Obtener la cantidad a actualizar
							$cantUpd=$datos["cant_salida"]-$_SESSION["medicamento"][$datos["catalogo_medicamento_id_med"]];
							//Sentencia SQL que actualizar� el catalogo de Medicamento ya registrado
							$sql_upd="UPDATE catalogo_medicamento SET existencia_actual=existencia_actual+($cantUpd) WHERE id_med='$datos[catalogo_medicamento_id_med]'";
						}
						//Si el medicamento anterior no existe en el arreglo de Sesion, entonces se debe borrar y reincrementar la existencia en el catalogo de Medicamentos
						else{
							//Obtener la cantidad a actualizar
							$cantUpd=$datos["cant_salida"];
							//Sentencia SQL que actualizar� el catalogo de Medicamento ya registrado
							$sql_upd="UPDATE catalogo_medicamento SET existencia_actual=existencia_actual+($cantUpd) WHERE id_med='$datos[catalogo_medicamento_id_med]'";
							//Borrar el medicamento de la bitacora de medicamentos
							$sql_del="DELETE FROM bitacora_medicamentos WHERE catalogo_medicamento_id_med='$datos[catalogo_medicamento_id_med]' AND bitacora_consultas_id_bit_consultas='$idBit'";
							//Ejecutar la sentencia de eliminado de medicamentos que ya no se usaron
							$rsDel=mysql_query($sql_del);
						}
						//Ejecutar la sentencia de actualizacion de Medicamento
						$rsUpd=mysql_query($sql_upd);
					}while($datos=mysql_fetch_array($rs));
				}
				//Si no hay resultados, significa que antes no se entregaron medicamentos, actualizar la existencia de cada medicamento
				else{
					//Recorrer el arreglo de Medicamentos
					foreach($_SESSION["medicamento"] as $ind=>$value){
						$stm_sql="UPDATE catalogo_medicamento SET existencia_actual=existencia_actual-$value WHERE id_med='$ind'";
						$rs = mysql_query($stm_sql);
						if($rs){
							$idBitMedicamento=obtenerIdBitMedicamentos();
							$stm_sql="INSERT INTO bitacora_medicamentos (id_bit_medicamento,bitacora_consultas_id_bit_consultas,catalogo_medicamento_id_med,tipo_movimiento,fecha,cant_salida) 
									VALUES ('$idBitMedicamento','$idBit','$ind','CORRECCION','$fecha','$value')";
							$rs = mysql_query($stm_sql);
							if(!$rs){
								$band=0;
								break;
							}
						}
						else{
							$band=0;
							break;
						}
					}
				}
				//Si la variable se activo, se debe verificar el medicamento recientemente actualizado, ya que el sistema hasta este punto
				//puede actualizar la existencia de los materiales, mas no puede registrar una nueva salida, aqui se debe recorrer el arreglo
				//de medicamentos y si en el arreglo de medicamentos hay materiales no registrados aun como salidas en la tabla de catalogo_medicamento
				//se deben de registrar como salidas
				if($band==1){
					//Verificar si el Medicamento que sale es el mismo que ya se tenia registrado para actualizar el catalogo de Medicamento
					$sql_stm="SELECT catalogo_medicamento_id_med,cant_salida FROM bitacora_medicamentos WHERE bitacora_consultas_id_bit_consultas='$idBit'";
					//Arreglo que contendra los medicamentos que son parte de la bitacora de medicamentos actualizada
					$rs=mysql_query($sql_stm);
					//Verificar los medicamentos suministrados hasta este punto
					if($datosReg=mysql_fetch_array($rs)){
						//Arreglo para guardar los resultados de la consulta
						$arrMedReg=array();
						//Arreglo para guardar la cantidad de medicamento que sale
						$arrCantMedReg=array();
						//Recorrer los medicamentos suministrados
						do{
							//Guardar el ID del medicamento que esta registrado
							$arrMedReg[]=$datosReg["catalogo_medicamento_id_med"];
							//Guardar la cantidad de salida
							$arrCantMedReg[$datosReg["catalogo_medicamento_id_med"]]=$datosReg["cant_salida"];
						}while($datosReg=mysql_fetch_array($rs));
					}
					foreach($_SESSION["medicamento"] as $ind=>$value){
						//Variable para definir si el medicamento se agrega o no
						$guardar="si";
						foreach($arrMedReg as $indice=>$idMed){
							//Verificar si el medicamento registrado esta en el arreglo de sesion
							if($idMed==$ind){
								$guardar="no";
								break;
							}
						}
						//Si el medicamento del arreglo de sesion no se encuentra en el de los registrados, guardarlo
						if($guardar=="si"){
							$stm_sql="UPDATE catalogo_medicamento SET existencia_actual=existencia_actual-$value WHERE id_med='$ind'";
							$rs = mysql_query($stm_sql);
							if($rs){
								$idBitMedicamento=obtenerIdBitMedicamentos();
								$stm_sql="INSERT INTO bitacora_medicamentos (id_bit_medicamento,bitacora_consultas_id_bit_consultas,catalogo_medicamento_id_med,tipo_movimiento,fecha,cant_salida) 
										VALUES ('$idBitMedicamento','$idBit','$ind','CORRECCION','$fecha','$value')";
								$rs = mysql_query($stm_sql);
							}							
						}
					}
				}
				if(isset($_SESSION["medicamento"]))
					unset($_SESSION["medicamento"]);
				//Cerrar la conexion
				mysql_close($conn);
				//Guardar el Movimiento realizado en la tabla de Movimientos
				registrarOperacion("bd_clinica","$idBit","ModBitConsMedicas",$_SESSION['usr_reg']);
				//Redireccionar a la pagina de exito
				echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			}
			//Si no hay arreglo de medicamentos
			else{
				//Si no se guardo el arreglo de sesion y encima se borraron los registros de medicamentos, recuperar el Material y borrar el registro de los medicamentos
				if($_POST["hdn_borrarMed"]==1){
					$sql_stm="SELECT catalogo_medicamento_id_med,cant_salida FROM bitacora_medicamentos WHERE bitacora_consultas_id_bit_consultas='$idBit'";
					//Arreglo que contendra los medicamentos que son parte de la bitacora de medicamentos actualizada
					$rs=mysql_query($sql_stm);
					//Verificar los medicamentos suministrados hasta este punto
					if($datosReg=mysql_fetch_array($rs)){
						do{
							
							//Sentencia de actualizacion de cantidad de Medicamento
							$sql="UPDATE catalogo_medicamento SET existencia_actual=existencia_actual+$datosReg[cant_salida] WHERE id_med='$datosReg[catalogo_medicamento_id_med]'";
							$rsUpd=mysql_query($sql);
						}while($datosReg=mysql_fetch_array($rs));
						//Sentencia SQL para borrar el registro del posible Medicamento Entregado en el Registro de Bitacora
						$sql_stm="DELETE FROM bitacora_medicamentos WHERE bitacora_consultas_id_bit_consultas='$idBit'";
						$rs=mysql_query($sql_stm);
					}
					//Cerrar la conexion
					mysql_close($conn);
					//Guardar el Movimiento realizado en la tabla de Movimientos
					registrarOperacion("bd_clinica","$idBit","ModBitConsMedicas",$_SESSION['usr_reg']);
					//Redireccionar a la pagina de exito
					echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
				}
				//Si no se toco la ventana Emrgente ni quitaron los registros, simplemente redireccionar a Exito
				else{
					//Cerrar la conexion
					mysql_close($conn);
					//Guardar el Movimiento realizado en la tabla de Movimientos
					registrarOperacion("bd_clinica","$idBit","ModBitConsMedicas",$_SESSION['usr_reg']);
					//Redireccionar a la pagina de exito
					echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
				}
			}
		}
		else{
			$error=mysql_error();
			//Cerrar la conexion
			mysql_close($conn);
			//Redireccionar a la pantalla de Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";			
		}
	}
?>