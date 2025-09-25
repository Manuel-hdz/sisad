<?php
	/**
	  * Nombre del M�dulo: Desarrollo
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 07/Noviembre/2011
	  * Descripci�n: Este archivo contiene funciones para realizar operaciones sobre la N�mina
	**/
	
	//Esta funci�n se encarga de generar el Id de la Nomina de acuerdo a los registros existentes en la BD
	function obtenerIdNomina(){
		
		//Definir las letras en la Id de la Nomina
		$id_cadena = "NOMADM";
		//Obtener el mes y el a�o
		$fecha = date("m-Y");
		$id_cadena .= substr($fecha,0,2).substr($fecha,5,2);	
		//Obtener el mes actual y el a�o actual para ser agregado en la consulta y asi obtener las nominas del mes en curso del a�o en curso
		$mes = substr($fecha,0,2);
		$anio = substr($fecha,5,2);
		//Crear la sentencia para obtener el numero de Nominas registradas 
		$stm_sql_nom = "SELECT COUNT(id_nomina) AS cant FROM nominas WHERE id_nomina LIKE 'NOMADM$mes$anio%'";
		$rs_nom = mysql_query($stm_sql_nom);
		if($datos_nom=mysql_fetch_array($rs_nom)){
			$cant = $datos_nom['cant'] + 1;
			if($cant>0 && $cant<10)
				$id_cadena .= "00".$cant;
			if($cant>9 && $cant<100)
				$id_cadena .= "0".$cant;
			if($cant>=100)
				$id_cadena .= $cant;
		}
		
		return $id_cadena;
	}//Fin de la Funcion obtenerIdNomina()		
	
	//Funcion que guarda la Nomina en la Base de Datos
	function guardarNomina(){
		$conn = conecta("bd_recursos");
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		$fechaReg = date("Y-m-d");
		$num = $_POST["hdn_cont"];
		$id_nomina = $_POST["hdn_idnomina"];
		$area = $_POST["hdn_area"];
		if($area == "ADMINISTRACION"){
			$id_nomina = obtenerIdNomina();
		}
		//Extraer el Mes y el A�o de la Fecha de Inicio del Periodo(Semanal o Quincenal) Seleccionadao
		$meses = array("01"=>"ENERO","02"=>"FEBRERO","03"=>"MARZO","04"=>"ABRIL","05"=>"MAYO","06"=>"JUNIO",
						"07"=>"JULIO","08"=>"AGOSTO","09"=>"SEPTIEMBRE","10"=>"OCTUBRE","11"=>"NOVIEMBRE","12"=>"DICIEMBRE");
		$partesFecha = explode("-",$fechaI);
		$anio = $partesFecha[0];
		$mes = $meses[$partesFecha[1]];
		//Variable de control de insercion de Datos
		$band=0;
		//Registrar la Nomina
		if($num > 0) {
			//Crear la sentencia para realizar el registro de los datos de la N�mina
			$stm_sql = "INSERT INTO  nominas ( id_nomina, periodo, fecha_registro, fecha_inicio, fecha_fin, area, anio, mes)
						VALUES ( '$id_nomina',  'SEMANA',  '$fechaReg',  '$fechaI',  '$fechaF',  '$area',  '$anio',  '$mes' )";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_requisicion
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$band = 1;
				$error = mysql_error();
				echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>".$error;
				BREAK;
			}
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
		//Verificar el valor de band, si permanece en 0, el proceso se completo correctamente
		if ($band==0){
			guardarDetalleNomina($id_nomina);
			//Registrar la Operacion en la tabla de movimientos
			registrarOperacion("bd_recursos",$id_nomina,"registrarNomina",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		//unset($_SESSION['bonoNomina']);
	}//Fin de guardarNomina()
	
	//Funcion que guarda la Nomina en la Base de Datos
	function guardarDetalleNomina($id_nomina){
		$conn = conecta("bd_recursos");
		$num = $_POST["hdn_cont"];
		//Variable de control de insercion de Datos
		$band=0;
		//Registrar el Detalle de la Nomina
		for($i=1; $i<=$num; $i++){
			$id_empl = $_POST['txt_id_emp'.$i];
			if(isset($_POST["ckb_jueves".$i])) $jueves = "A"; else if(isset($_POST["ckb_juevesI".$i])) $jueves = "I"; else if(isset($_POST["ckb_juevesAL".$i])) $jueves = "B"; else if(isset($_POST["ckb_jueves".$i."D"])) $jueves = "D"; else $jueves = "F";
			if(isset($_POST["ckb_viernes".$i])) $viernes = "A"; else if(isset($_POST["ckb_viernesI".$i])) $viernes = "I"; else if(isset($_POST["ckb_viernesAL".$i])) $viernes = "B"; else if(isset($_POST["ckb_viernes".$i."D"])) $viernes = "D"; else $viernes = "F";
			if(isset($_POST["ckb_sabado".$i])) $sabado = "A"; else if(isset($_POST["ckb_sabadoI".$i])) $sabado = "I"; else if(isset($_POST["ckb_sabadoAL".$i])) $sabado = "B"; else if(isset($_POST["ckb_sabado".$i."D"])) $sabado = "D"; else $sabado = "F";
			if(isset($_POST["ckb_domingo".$i])) $domingo = "A"; else if(isset($_POST["ckb_domingoI".$i])) $domingo = "I"; else if(isset($_POST["ckb_domingoAL".$i])) $domingo = "B"; else if(isset($_POST["ckb_domingo".$i."D"])) $domingo = "D"; else $domingo = "F";
			if(isset($_POST["ckb_lunes".$i])) $lunes = "A"; else if(isset($_POST["ckb_lunesI".$i])) $lunes = "I"; else if(isset($_POST["ckb_lunesAL".$i])) $lunes = "B"; else if(isset($_POST["ckb_lunes".$i."D"])) $lunes = "D"; else $lunes = "F";
			if(isset($_POST["ckb_martes".$i])) $martes = "A"; else if(isset($_POST["ckb_martesI".$i])) $martes = "I"; else if(isset($_POST["ckb_martesAL".$i])) $martes = "B"; else if(isset($_POST["ckb_martes".$i."D"])) $martes = "D"; else $martes = "F";
			if(isset($_POST["ckb_miercoles".$i])) $miercoles = "A"; else if(isset($_POST["ckb_miercolesI".$i])) $miercoles = "I"; else if(isset($_POST["ckb_miercolesAL".$i])) $miercoles = "B"; else if(isset($_POST["ckb_miercoles".$i."D"])) $miercoles = "D"; else $miercoles = "F";
			$sueldo_base = $_POST['txt_sb'.$i];
			$sueldo_diario = $_POST['txt_sd'.$i];
			
			if(isset($_POST['txt_cumplimiento'.$i]))
				$cumpl = $_POST['txt_cumplimiento'.$i];
			else
				$cumpl = 0;
			if(isset($_POST['txt_calidadObra'.$i]))
				$calidad = $_POST['txt_calidadObra'.$i];
			else
				$calidad = 0;
			if(isset($_POST['txt_bonificacion'.$i]))
				$bonif = $_POST['txt_bonificacion'.$i];
			else
				$bonif = 0;
				
			$total_pagado = $_POST['txt_total'.$i];
			$horas_extra = $_POST['txt_he'.$i];
			
			if(isset($_POST['txt_destajo'.$i]))
				$destajo = $_POST['txt_destajo'.$i] - ($horas_extra * ($sueldo_diario / 8) * 2);
			else
				$destajo = 0;
			
			$bono = $_POST['txt_bonificaciones'.$i];
			$comentario = $_POST['txt_comentario'.$i];
			if(isset($_POST["ckb_12hrs".$i])){
				if($destajo > 0)
					$destajo = $destajo - 500;
				$g12hrs = 1;
			}	else $g12hrs = 0;
			if(isset($_POST["ckb_8hrs".$i])){
				if($destajo > 0)
					$destajo = $destajo - 350;
				$g8hrs = 1;
			}	else $g8hrs = 0;
			//Crear la sentencia para realizar el registro de los datos de la N�mina
			$stm_sql = "INSERT INTO  detalle_nominas ( id_nomina, id_empleados_empresa, jueves, viernes, 
														sabado, domingo, lunes, martes, miercoles, 
														sueldo_base, sueldo_diario, cumpl, calidad,
														bonif, destajo, total_pagado, horas_extra, 
														guarda_8hrs, guarda_12hrs, bono, comentarios)
						VALUES ( '$id_nomina',  '$id_empl',  '$jueves',  '$viernes',  '$sabado',  '$domingo',  '$lunes',  
								'$martes', '$miercoles', '$sueldo_base', '$sueldo_diario', '$cumpl', '$calidad', '$bonif', 
								'$destajo', '$total_pagado', '$horas_extra', '$g8hrs', '$g12hrs', '$bono', '$comentario')";
			//Ejecutar la sentencia previamente creada para agregar cada material a la tabla de detalle_requisicion
			$rs = mysql_query($stm_sql);
			if(!$rs){
				$band = 1;
				$error = mysql_error();
				echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>".$error;
				break;
			}
		}
		//Cerrar la conexion con la BD		
		mysql_close($conn);
	}//Fin de guardarDetalleNomina()
	
	//Funcion que muestra la N�mina Guardada lista para exportarse a Excel
	function mostrarNominaBD(){
		$fechaI=modFecha($_POST["txt_fechaIni"],3);
		$fechaF=modFecha($_POST["txt_fechaFin"],3);
		//$area=$_POST["cmb_area"];
		$band="false";
		//Realizar la conexion a la BD de Desarrollo
		$conn = conecta("bd_mantenimiento");	
			//Escribimos la consulta a realizarse para la Nomina por completo
			$stm_sql = "SELECT CONCAT( T3.nombre,  ' ', T3.ape_pat,  ' ', T3.ape_mat ) AS nombre_emp, T2. * 
						FROM nominas AS T1
						JOIN detalle_nominas AS T2
						USING ( id_nomina ) 
						JOIN bd_recursos.empleados AS T3
						USING ( id_empleados_empresa ) 
						WHERE T1.fecha_inicio =  '$fechaI'
						AND T1.fecha_fin =  '$fechaF'
						AND T1.area =  'MANTENIMIENTO MINA'";
			//Mensaje de encabezado de la Tabla
			$msje="<strong>N&oacute;mina de <u><em>Mantenimiento Mina</u></em> del <u><em>".$_POST["txt_fechaIni"]."</em></u> al <u><em>".$_POST["txt_fechaFin"]."</em></u></strong>";
			
		//Ejecutar la Sentencia para obtener los datos del proveedor seleccionado
		$rs = mysql_query($stm_sql);
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
			//Desplegar los resultados de la consulta en una tabla
			echo "				
				<table cellpadding='5' width='100%' id='tabla-resultadosNomina'> 
				<caption><p class='msje_correcto'>$msje</p></caption>
				<thead>
					<tr>
        				<th class='nombres_columnas' align='center'>ID EMPLEADO</th>
				        <th class='nombres_columnas' align='center'>NOMBRE</th>
        				<td class='nombres_columnas' align='center'>J</td>
						<td class='nombres_columnas' align='center'>V</td>
						<td class='nombres_columnas' align='center'>S</td>
        				<td class='nombres_columnas' align='center'>D</td>
						<td class='nombres_columnas' align='center'>L</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>M</td>
						<td class='nombres_columnas' align='center'>SUELDO BASE</td>
						<td class='nombres_columnas' align='center'>SUELDO DIARIO</td>
						<td class='nombres_columnas' align='center'>TOTAL PAGADO</td>
						<td class='nombres_columnas' align='center'>HORAS EXTRA</td>
						<td class='nombres_columnas' align='center'>GUARDIA 8HRS</td>
						<td class='nombres_columnas' align='center'>GUARDIA 12HRS</td>
						<td class='nombres_columnas' align='center'>COMENTARIOS</td>
      				</tr>
				</thead>";
			$nom_clase = "renglon_gris";
			$cont = 1;
			echo "<tbody>";
			do{	
				if($datos["guarda_12hrs"] == 1) {
					$g12 = "X";
				}	else $g12 = "";
				if($datos["guarda_8hrs"] == 1) {
					$g8 = "X";
				}	else $g8 = "";
				?> 	<tr>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["id_empleados_empresa"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["nombre_emp"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["jueves"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["jueves"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["viernes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["viernes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["sabado"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["sabado"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["domingo"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["domingo"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["lunes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["lunes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["martes"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["martes"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php if($datos["miercoles"]=="B") echo "<font style='color:darkred;'>AL</font>"; else echo $datos["miercoles"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_base"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["sueldo_diario"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'>$<?php echo number_format($datos["total_pagado"],2,".",","); ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["horas_extra"]; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g8; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $g12; ?></td>
						<td class="<?php echo $nom_clase; ?>" align='center'><?php echo $datos["comentarios"]; ?></td>
					</tr>
				<?php
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
			}while($datos=mysql_fetch_array($rs));		
			echo "</tbody>";
			echo "</table>";
			$band="true";
		}
		else{
			echo "<br><br><br><br><br><br><br><br><br><br><br>
				<p class='msje_correcto' align='center'>No Hay Registros de N&oacute;mina del <u><em>".$_POST["txt_fechaIni"]."</em></u> al <u><em>".$_POST["txt_fechaFin"]."</em></u></p>";
		}
		//Cerrar la conexion con la BD 
		mysql_close($conn);
		if ($band=="false")
			return $band;
		else
			return $stm_sql."<br>".$msje."<br>".$fechaI."<br>".$fechaF;
	}
?>