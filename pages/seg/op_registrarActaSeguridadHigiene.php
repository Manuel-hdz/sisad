<?php
	/**
	  * Nombre del Módulo: Seguridad Industrial                                               
	  * Nombre Programador: Daisy Adriana Martínez Fernández                           
	  * Fecha: 07/Febrero/2012
	  * Descripción: Este archivo permite registrar la informacion relacionada con el acta de seguridad e higiene del departamento
	  **/
	 	
	//Esta funcion genera la Clave de la acta de acuerdo a los registros en la BD
	function obtenerIdRegBitacoraSH(){
		//Realizar la conexion a la BD de Seguridad
		$conn = conecta("bd_seguridad");
		
		//Definir las tres letras la clave de la Bitacora
		$id_cadena = "ACT";
		//Obtener el mes y el año
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);
		//Obtener el mes actual y el año actual 
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		
		//Crear la sentencia para obtener la Clave reciente acorde a la fecha
		$stm_sql = "SELECT MAX(id_acta_comision) AS cant FROM acta_comision WHERE id_acta_comision LIKE 'ACT$mes$anio%'";
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
	
	
	function mostrarRegistrosAsistentes($asistentes){
		//Verificamos que exista la session
		if($_SESSION['asistentes']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Detalle del Registro Nombre y Puesto de Asistentes </caption>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>PUESTO</td>
					<td class='nombres_columnas' align='center'>NOMBRE</td>
					<td class='nombres_columnas' align='center'>BORRAR</td>
        		</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			foreach($_SESSION['asistentes'] as $key => $arrVale){
				echo "<tr>
						<td align='center'  class='nombres_filas'>$cont</td>
						<td align='center'  class='$nom_clase'>$arrVale[puesto]</td>
						<td align='center'  class='$nom_clase'>$arrVale[nombre]</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="location.href='verRegNombrePuestoAsistentes.php?noRegistro=<?php echo $key;?>'"/>
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
	
	
	
	function mostrarRegistrosPuntosAgenda($agenda){
		//Verificamos que exista la session
		if($_SESSION['agenda']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Puntos Tratados en la Agenda </caption>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>PUNTO</td>
					<td class='nombres_columnas' align='center'>BORRAR</td>
        		</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			foreach($_SESSION['agenda'] as $key => $arrVale){
				echo "<tr>
						<td align='center'  class='nombres_filas'>$cont</td>
						<td align='center'  class='$nom_clase'>$arrVale[punto]</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="location.href='verPuntosAgenda.php?noRegistro=<?php echo $key;?>'"/>
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
	
	
	function mostrarRegistrosAreasVisitadas($visitas){
		//Verificamos que exista la session
		if($_SESSION['visitas']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Detalle de Áreas Visitadas </caption>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>ÁREA VISITADA</td>
					<td class='nombres_columnas' align='center'>BORRAR</td>
        		</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			foreach($_SESSION['visitas'] as $key => $arrVale){
				echo "<tr>
						<td align='center'  class='nombres_filas'>$cont</td>
						<td align='center'  class='$nom_clase'>$arrVale[area]</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="location.href='verRegAccidentes.php?noRegistro=<?php echo $key;?>'"/>
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
	
	function mostrarRegistrosAccidentes($accidentes){
		//Verificamos que exista la session
		if($_SESSION['accidentes']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Detalle de Accidentes Investigados </caption>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>FECHA ACCIDENTE</td>
					<td class='nombres_columnas' align='center'>NOMBRE ACCIDENTE</td>
					<td class='nombres_columnas' align='center'>CAUSAS ACCIDENTE</td>
					<td class='nombres_columnas' align='center'>ACCIONES PREVENTIVAS</td>
					<td class='nombres_columnas' align='center'>BORRAR</td>
        		</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			foreach($_SESSION['accidentes'] as $key => $arrVale){
				echo "<tr>
						<td align='center'  class='nombres_filas'>$cont</td>
						<td align='center'  class='$nom_clase'>$arrVale[fechAcc]</td>
						<td align='center'  class='$nom_clase'>$arrVale[nomAcc]</td>
						<td align='center'  class='$nom_clase'>$arrVale[cauAcc]</td>
						<td align='center'  class='$nom_clase'>$arrVale[accPrev]</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="location.href='verRegAccidentes.php?noRegistro=<?php echo $key;?>'"/>
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
	
	
	function mostrarRegistrosRecorridos($recorridos){
		//Verificamos que exista la session
		if($_SESSION['recorridos']){
			echo "<table cellpadding='5' width='100%' align='center'> 
					<caption class='titulo_etiqueta'>Detalle de Recorridos de Verificaci&oacute;n </caption>";
			echo "<tr>
					<td class='nombres_columnas' align='center'>NO.</td>
					<td class='nombres_columnas' align='center'>ACTO INSEGURO</td>
					<td class='nombres_columnas' align='center'>RESPONSABLE</td>
					<td class='nombres_columnas' align='center'>FECHA L&Iacute;MITE</td>
					<td class='nombres_columnas' align='center'>FECHA EN LA QUE SE CUMPLIO EL PUNTO</td>
					<td class='nombres_columnas' align='center'>BORRAR</td>
        		</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			foreach($_SESSION['recorridos'] as $key => $arrVale){
				echo "<tr>
						<td align='center'  class='nombres_filas'>$cont</td>
						<td align='center'  class='$nom_clase'>$arrVale[actoInseguro]</td>
						<td align='center'  class='$nom_clase'>$arrVale[responsable]</td>
						<td align='center'  class='$nom_clase'>$arrVale[fechaLimite]</td>
						<td align='center'  class='$nom_clase'>$arrVale[fechaCumplida]</td>";?>
						<td class="<?php echo $nom_clase;?>" align="center"	><input type="image" src="../../images/borrar.png" width="30" height="25"
						border="0" title="Borrar Registro" 
						onclick="location.href='verRegRecorridosVerificacion.php?noRegistro=<?php echo $key;?>'"/>
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
	
	
	//Funcion para guardar la informacion de la Acta de Seguridad e Higiene 
	function registrarActaGral(){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
		
		//Recuperar la informacion del post
		$clave = $_POST['txt_idActa'];
		$fechaRegistro = modFecha($_POST['txt_fechaRegistro'],3);
		$periodoVerificacion = modFecha($_POST['txt_periodoVer'],3);
		$al = modFecha($_POST['txt_al'],3);
		$descripcion = strtoupper($_POST['txa_descripcion']);
		$tipoVer=strtoupper($_POST['txt_extraordinariaPor']);
		$horaInicio = $_POST['txt_horaInicio'];
		$horaterminacion = ($_POST['txt_horaTerminacion']);
		$proxReunion = modFecha($_POST['txt_proxReunion'],3);
		$representante = strtoupper($_POST['txt_gteGral']);
		$gteGral = strtoupper($_POST['txt_gteGral']);
		
	 
	
		//Crear la Sentencia SQL para Alamcenar los materiales agregados 
		$stm_sql= "INSERT INTO acta_comision (id_acta_comision, fecha_registro, periodo_ini, periodo_fin, descripcion_acta, tipo_verificacion,
				   hora_ini,	hora_fin, fecha_prox, nom_representante, nom_gerente)
				   VALUES ('$clave', '$fechaRegistro', '$periodoVerificacion', '$al', '$descripcion', '$tipoVer', '$horaInicio', '$horaterminacion',	'$proxReunion',
	 			  '$representante', '$gteGral')";
		
		//Ejecutar la Sentencia
		$rs=mysql_query($stm_sql);
		//Verificar Resultado
		if ($rs){
			registrarAsistentes($clave);
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
	 }// Fin function registrarBitacora()
	 
	 
	 
	 
	 function registrarAsistentes($clave){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandAsist = 0;
	 	//Recorremos el arreglo asistentes para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['asistentes'] as $ind => $asistentes){								
			//Creamos la sentencia SQL para insertar los datos en la tabla catalogo_procedimientos
			$stm_sqlAsistentes="INSERT INTO asistentes (acta_comision_id_acta_comision,nom_asistente, puesto_asistente) 
							    VALUES('$clave', '$asistentes[nombre]', '$asistentes[puesto]')";
			//Ejecutamos la sentencia previamante creada
			$rsAsist = mysql_query($stm_sqlAsistentes);
			if(!$rsAsist){
				$bandAsist = 1;						
				$eliminarRegistroFallido($clave);
			}			
		}//foreach($_SESSION['asistentes'] as $ind => $asistentes
		if ($rsAsist){
			registrarAgenda($clave);
		}
	}
	
	
	 function registrarAgenda($clave){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandAgenda = 0;
	 	//Recorremos el arreglo asistentes para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['agenda'] as $ind => $agenda){								
			//Creamos la sentencia SQL para insertar los datos en la tabla catalogo_procedimientos
			$stm_sqlAgenda="INSERT INTO puntos_agenda (acta_comision_id_acta_comision,punto_acordado) VALUES('$clave', '$agenda[punto]')";
			//Ejecutamos la sentencia previamante creada
			$rsAgenda = mysql_query($stm_sqlAgenda);
			if(!$rsAgenda){
				$bandAgenda = 1;
				$eliminarRegistroFallido($clave);
			}			
		}//foreach($_SESSION['asistentes'] as $ind => $asistentes
		if ($rsAgenda){
			registrarVistas($clave);
		}
	}
	
	
	 function registrarVistas($clave){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$banVisitas = 0;
	 	//Recorremos el arreglo asistentes para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['visitas'] as $ind => $visitas){								
			//Creamos la sentencia SQL para insertar los datos en la tabla catalogo_procedimientos
			$stm_sqlVisitas="INSERT INTO areas_visitadas (acta_comision_id_acta_comision,area_visitada) VALUES('$clave', '$visitas[area]')";
			//Ejecutamos la sentencia previamante creada
			$rsVisitas = mysql_query($stm_sqlVisitas);
			if(!$rsVisitas){
				$banVisitas = 1;
				$eliminarRegistroFallido($clave);						
			}			
		}//foreach($_SESSION['asistentes'] as $ind => $asistentes
		if ($rsVisitas){
			if(isset($_SESSION['accidentes'])){
				registrarAccidentes($clave);
			}
			else{
				registrarRecorridos($clave);
			}
		}
	}
	
	 function registrarAccidentes($clave){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");
		//Incluimos archivos para realizar las operaciones con la Base de datos (funcion ObtenerDato)
		include_once("../../includes/op_operacionesBD.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandAcc = 0;
	 	//Recorremos el arreglo asistentes para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['accidentes'] as $ind => $accidentes){							
			$fechaAccidentes = modFecha($accidentes['fechAcc'],3);	
			//Creamos la sentencia SQL para insertar los datos en la tabla catalogo_procedimientos
			$stm_sqlAcc="INSERT INTO accidentes (acta_comision_id_acta_comision,num_accidente,fecha_acc,nom_acc,causa_acc,acciones_prev) 
							 VALUES('$clave', '$accidentes[noAcc]', '$fechaAccidentes', '$accidentes[nomAcc]', '$accidentes[cauAcc]','$accidentes[accPrev]')";
			//Ejecutamos la sentencia previamante creada
			$rsAcc = mysql_query($stm_sqlAcc);
			if(!$rsAcc){
				$bandAcc = 1;
				$eliminarRegistroFallido($clave);						
			}			
		}//foreach($_SESSION['asistentes'] as $ind => $asistentes
		if ($rsAcc){
			registrarRecorridos($clave);
		}
	}
	
	 function registrarRecorridos($clave){
	 	//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	 
	 	//Variable que nos permitira conocer si hubo errores en el registro
		$bandReco = 0;
	 	//Recorremos el arreglo asistentes para insertar en la BD los datos guardados en el mismo
		foreach($_SESSION['recorridos'] as $ind => $recorridos){	
			$fechaLimite = modFecha($recorridos['fechaLimite'],3);
			$fechaCumplida = modFecha($recorridos['fechaCumplida'],3);					
			//Creamos la sentencia SQL para insertar los datos en la tabla catalogo_procedimientos
			$stm_sqlReco="INSERT INTO recorridos_verificacion (acta_comision_id_acta_comision,acto_inseguro,responsable,fecha_limite,fecha_cumplida) 
							 VALUES('$clave', '$recorridos[actoInseguro]', '$recorridos[responsable]', '$fechaLimite', '$fechaCumplida')";
			//Ejecutamos la sentencia previamante creada
			$rsReco= mysql_query($stm_sqlReco);
			if(!$rsReco){
				$bandReco = 1;						
				$eliminarRegistroFallido($clave);
			}			
		}//foreach($_SESSION['asistentes'] as $ind => $asistentes
		if ($rsReco){
			//Guardar la operacion realizada
			registrarOperacion("bd_seguridad",$clave,"RegistrarActaSH",$_SESSION['usr_reg']);
			$conn = conecta("bd_seguridad");
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
	}
	
	function eliminarRegistroFallido($clave){
		//Importamos archivo para realizar la conexion con la BD
		include_once("../../includes/conexion.inc");
		//Incluimos archivo para modificar fechas
		include_once("../../includes/func_fechas.php");

		//Conectar se a la Base de Datos
		$conn = conecta("bd_seguridad");
	 	
		//Sentencia para eliminar Registros de acta_comision
		$stm_sqlActaCom="DELETE FROM acta_comision WHERE id_acta_comision='$clave'";
		
		//Ejecutamos la sentencia previamente creada
		$rsActaCom = mysql_query($stm_sqlActaCom);
		
		//Sentencia para eliminar los Registros de asistentes
		$stm_sqlAsistentes="DELETE FROM asistentes WHERE acta_comision_id_acta_comision='$clave'";
		
		//Ejecutamos la sentencia previamente creada
		$rsAsist = mysql_query($stm_sqlAsistentes);
		
		//Sentencia para eliminar los Registros de puntos_agenda
		$stm_sqlAgenda="DELETE FROM puntos_agenda WHERE acta_comision_id_acta_comision='$clave'";
		
		//Ejecutamos la sentencia previamente creada
		$rsAgenda = mysql_query($stm_sqlAgenda);
		
		//Sentencia para eliminar los Registros de areas_visitadas
		$stm_sqlVisitas="DELETE FROM areas_visitadas WHERE acta_comision_id_acta_comision='$clave'";
		
		//Ejecutamos la sentencia previamente creada
		$rsVisitas = mysql_query($stm_sqlVisitas);
		
		//Sentencia para eliminar los Registros de accidentes
		$stm_sqlAcc="DELETE FROM accidentes WHERE acta_comision_id_acta_comision='$clave'";
		
		//Ejecutamos la sentencia previamente creada
		$rsAcc = mysql_query($stm_sqlAcc);
		
		//Sentencia para eliminar los Registros de recorridos_verificacion
		$stm_sqlRecorridos="DELETE FROM recorridos_verificacion WHERE acta_comision_id_acta_comision='$clave'";
		
		//Ejecutamos la sentencia previamente creada
		$rsRecorridos = mysql_query($stm_sqlRecorridos);
		
		//Enviamos a la pantalla de Error
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=Consulta Err&oacute;nea'>";
	}
	
	
?>