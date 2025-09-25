<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 07/Febrero/2012
	  * Descripción: Este archivo permite registrar la informacion relacionada con el acta de accidentes e incidentes
	  **/
	 	
	//Esta funcion genera la Clave de la acta de acuerdo a los registros en la BD
	function obtenerIdRegAccInc(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las tres letras la clave de la Bitacora
		$id_cadena = "INF";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		//Variable que nos permitira regresar el valor seleccionado
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_informe) AS cant FROM accidentes_incidentes WHERE id_informe LIKE 'INF$mes$anio%'";
		//Ejecutar la Sentencia SQL
		$rs = mysql_query($stm_sql);
		
		//Comprobamos la existencia de datos
		if($datos=mysql_fetch_array($rs)){
			//Obtener las ultimas 3 cifras del informe de accidentes Registrado en la BD y sumarle 1
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

	
	//Funcion que nos permite mostrar las acciones preventivas o correctivas registradas en la sesion
	function mostrarAccionesPrevCorr($accionesPrevCorr){
		//Verificamos que exista la session
		if($_SESSION['accionesPrevCorr']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Detalle de Acciones Preventivas/Correctivas</caption>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>FECHA</td>
					<td class='nombres_columnas' align='center'>ACCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>RESPONSABLE</td>
					<td class='nombres_columnas' align='center'>BORRAR</td>
        		</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			foreach($_SESSION['accionesPrevCorr'] as $key => $arrVale){
				echo "<tr>
						<td align='center'  class='nombres_filas'>$cont</td>
						<td align='center'  class='$nom_clase'>$arrVale[fechAcc]</td>
						<td align='center'  class='$nom_clase'>$arrVale[accPrevCorr]</td>
						<td align='center'  class='$nom_clase'>$arrVale[responsable]</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="location.href='verRegAccionesPrevCorr.php?noRegistro=<?php echo $key;?>'"/>
					</td><?php				
			echo "</tr>";					
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			};
			echo " </table>";
		}
	}
	
	
		
	//Funcion para guardar la informacion del informe de Accidentes e incidentes
	function registrarInformeIncAcc(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Variable que nos permitira conocer si hubo fallas en el registro del detelle
		$band=1;
		
		//Recuperar la informacion de la sesion
		$clave = $_SESSION['actaIncAcc']['idActa'];
	 	$equipo = $_SESSION['actaIncAcc']['equipo'];
	 	$nomAcc = $_SESSION['actaIncAcc']['nomAcc'];
		
		//Obtenemos el RFC del empleado 
		$rfcEmpleado = obtenerDatoEmpleadoPorNombre('rfc_empleado',$nomAcc);
		$nomEmp = $_SESSION['actaIncAcc']['nomAcc'];
		
		$tipoAcc = $_SESSION['actaIncAcc']['tipoAcc'];
		$lugar = $_SESSION['actaIncAcc']['lugar'];
		$nivel = $_SESSION['actaIncAcc']['nivel'];
		$areaAcc = $_SESSION['actaIncAcc']['areaAcc'];
		$nomFacilitador = $_SESSION['actaIncAcc']['nomFacilitador'];
		$fecha = modFecha($_SESSION['actaIncAcc']['fecha'],3);
	 	$horaAcc = $_SESSION['actaIncAcc']['horaAcc'];
		$horaAviso = $_SESSION['actaIncAcc']['horaAviso'];
		$horaLaborar = $_SESSION['actaIncAcc']['horaLaborar'];
		$turno = $_SESSION['actaIncAcc']['turno'];
		$ficha = $_SESSION['actaIncAcc']['ficha'];
        $edad = $_SESSION['actaIncAcc']['edad'];
		$antPue = $_SESSION['actaIncAcc']['antPue'];
       	$antEm = $_SESSION['actaIncAcc']['antEm'];
      	$actHab = $_SESSION['actaIncAcc']['actHab'];
		$actividadMomAcc = $_SESSION['actaIncAcc']['actividadMomAcc'];
		$noAcc = $_SESSION['actaIncAcc']['noAcc'];
        $area = $_SESSION['actaIncAcc']['area'];
		$descripcion = $_SESSION['actaIncAcc']['descripcion']; 
        $lesion = $_SESSION['actaIncAcc']['lesion'];
        $porque = $_SESSION['actaIncAcc']['porque'];
        $actosInseguros = $_SESSION['actaIncAcc']['actosInseguros'];
        $condicionesInseguras = $_SESSION['actaIncAcc']['condicionesInseguras'];
       	$observaciones = strtoupper($_POST['txa_observaciones']);
		$coordinadorCSH = strtoupper($_POST['txt_coordinadorCSH']);
		$secretarioCSH = strtoupper($_POST['txt_secretarioCSH']);
     	$jefeSeguridad = strtoupper($_POST['txt_jefeSeguridad']);
        $depto_seguridad = strtoupper($_POST['txt_deptoSeguridad']);
       	$testigo = strtoupper($_POST['txt_testigo']);
        $puesto = strtoupper($_SESSION['actaIncAcc']['puesto']);
		
		
		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
		
       
       	//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "INSERT INTO accidentes_incidentes (id_informe, equipos_id_equipos, empleados_rfc_empleado, tipo_informe, lugar, nivel, area_acci, nom_facilitador, 
					fecha_accidente, hora_accidente, hora_aviso, hora_termino, turno, ficha, edad, antiguedad_puesto, antiguedad_empresa, actividad_habitual, 
					act_mom_acci, num_total_acci, descripcion, tipo_lesion, porque_paso, actos_inseguros, cond_inseguras, observaciones, coordinador_csh, 
					secretario_csh, jefe_seguridad, dpto_seguridad, testigo, puesto, area, nom_accidentado  )
				    VALUES ('$clave','$equipo', '$rfcEmpleado', '$tipoAcc', '$lugar', '$nivel', '$areaAcc', '$nomFacilitador', '$fecha', '$horaAcc', 
	 			   '$horaAviso', '$horaLaborar', '$turno', '$ficha', '$edad', '$antPue', '$antEm', '$actHab', '$actividadMomAcc', '$noAcc', '$descripcion','$lesion',
				   '$porque','$actosInseguros', '$condicionesInseguras', '$observaciones', '$coordinadorCSH' , '$secretarioCSH', '$jefeSeguridad', '$depto_seguridad',
				   '$testigo', '$puesto', '$area','$nomEmp')";
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		
		//Comprobamos que se hayan guardado correctamente
		if($rs){
			//Comprobamos que exista la sesion
			if(isset($_SESSION['accionesPrevCorr'])){
				//Ciclo que nos permite crear la sentencia para almancenar en la Base de datos
				foreach($_SESSION['accionesPrevCorr'] as $key => $value){
					//Convertimos la fecha a formato necesario
					$fechAcc = modFecha($value['fechAcc'],3);
					//Creamos la sentencia SQL
					$stm_sqlDet = "INSERT INTO acciones_pre_corr(accidentes_incidentes_id_informe, accion, fecha, responsable) 
					VALUES ('$clave', '$value[accPrevCorr]', '$fechAcc', '$value[responsable]')";
					//Ejecutamos la sentencia
					$rsDet = mysql_query($stm_sqlDet);
					//Comprobamos que se haya almacenado con exito
					if(!$rs){
						//Si no fue asi, para el ciclo y enviar mensaje de error
						break;
						$error = mysql_error();
						echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
					}
				}
			}
			//DE lo contrario fue con exito y se envia a la pantalla de exito
			if($rsDet){
				//Guardar el registro de movimientos
				registrarOperacion("bd_seguridad",$clave,"GenerarInformeIncAcc",$_SESSION['usr_reg']);?>
				<script type='text/javascript' language='javascript'>
					setTimeout("window.open('../../includes/generadorPDF/informeIncAcc.php?id_registro=<?php echo $clave; ?>', '_blank','top=100, left=100, width=1035, height=723, status=no, menubar=yes, resizable=yes, scrollbars=yes, toolbar=no, location=no, directories=no')",2000);
				</script><?php
				echo "<meta http-equiv='refresh' content='5;url=exito.php'>";;
				
			}
		}
		//Comprobamos que la snentecia de insercion general se haya ejecutado con exito de lo contrario envira a la pantalla de error
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarBitacora()
	
?>