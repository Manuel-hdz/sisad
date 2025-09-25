<?php

	/**
	  * Nombre del M�dulo: Recursos Humanos                                               
	  * Nombre Programador: Antonio de Jes�s Jim�nez Cuevas
	  * Fecha: 30/Enero/2012
	  * Descripci�n: Este archivo contiene funciones para modificar el Kardex de los Trabajadores
	  **/
	//Archivos de operacion para conexiones
	include_once("../../includes/conexion.inc");
	include_once("../../includes/op_operacionesBD.php");
	include_once("../../includes/func_fechas.php");?>
	<script type="text/javascript" src="../../includes/disableKeys.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		//Funcion para desabilitar el clic derecho en la ventana pop-up
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, �CONCRETO LANZADO DE FRESNILLO MARCA');
			}
		}
		document.onmousedown=click;						
		//-->
	</script><?php 
	
	//Si en el POST se detecta el boton Guardar, mandar llamar a la funcion guardarEstado
	if(isset($_POST["sbt_guardar"]))
		guardarEstado();
	
	//Si en el POST se detecta el boton sbt_eliminarEntrada o el boton sbt_eliminarSalida, mandar llamar a la funcion guardarEstado
	if(isset($_POST["sbt_eliminarEntrada"]) || isset($_POST["sbt_eliminarSalida"]))
		borrarEstado();

	//Si en el GET se detecta la variable DIV, mandar llamar a la funcion que muestra el formulario para ingresar los datos del Kardex
	if(isset($_GET["div"]) && !isset($_POST["sbt_guardar"]))
		asignarKardex();

	//Funcion que muestra el formulario para ingresar los datos de la checada de E/S
	function asignarKardex(){
		//Hoja de estilo para la ventana emergente
		echo "<link rel='stylesheet' type='text/css' href='../../includes/estilo.css' />";
		//Recoger los valores que vienen en el GET
		$valorOriginal=$_GET["valOr"];
		if (isset($_POST["sbt_eliminarEntrada"]))
			$valorOriginal="";
		if (isset($_POST["sbt_eliminarSalida"]) && $_POST["cmb_estado"]=="")
			$valorOriginal="";
		$nombreCaja=$_GET["nom"];
		$trabajador=$_GET["trab"];
		
		if($_GET["div"]=="indiv"){//Kardex Individual
			//A�o del Kardex individual
			$anio=$_GET["anio"];
			//Quitar el prefijo del nombre de la Caja
			$nombreCaja=str_replace("ckb_","",$nombreCaja);
			//Concatenar la Fecha
			$fecha=substr($nombreCaja,-2)."/".substr($nombreCaja,0,2)."/".$anio;
			//Ordenar la Fecha en formato legible para MySQL
			$fecha_sql=$anio."-".substr($nombreCaja,0,2)."-".substr($nombreCaja,-2);
			//Regresar el nombre de la caja a esu estado original
			$nombreCaja="ckb_".$nombreCaja;
			//Mensaje titulo para desplegar en la ventana emergente
			$msg="Ingresar Informaci&oacute;n de la Checada para el <em><u>$fecha</u></em> de <br><em><u>$trabajador</u></em>";
		}
		else{//Kardex por Area
			//Dividir el nombre de la caja por el espacio en blanco
			$datosNombreCaja=split(" ",$nombreCaja);
			//Extraer la fecha quitando el prefijo ckb_ a la primer parte del nombre de la caja
			$fecha_sql=str_replace("ckb_","",$datosNombreCaja[0]);
			//Remplazar el simbolo de grados "�" por el guion medio "-" en el nombre de la Caja
			$fecha_sql=str_replace("�","-",$fecha_sql);
			//Mensaje titulo para desplegar en la ventana emergente
			$msg="Ingresar Informaci&oacute;n de la Checada para el <em><u>".modFecha($fecha_sql,1)."</u></em> de <br><em><u>$trabajador</u></em>";
		}
		
		//Obtener la Clave de Empleado del Trabajador con la siguiente funcion
		$cve_emp=obtenerDatoEmpleadoPorNombre("id_empleados_empresa",$trabajador);
		//Obtener el RFC del Trabajador con la siguiente funcion
		$rfc_emp=obtenerDatoEmpleadoPorNombre("rfc_empleado",$trabajador);
		//Declarar las variables de hora de Entrada y Salida vacias
		$hora_e="";
		$hora_s="";
		$mer_e="";
		$mer_s="";
		//Variable con el tipo de accion a realizar sobre el registro de Entrada
		$accion="Add";
		//Variable para ingresar o actualizar hora de Salida
		$salida="Add";
		//Verificar si se tiene un registro de Entrada
		if($valorOriginal!=""){
			//Si el valor original es diferente de vacio, entonces es una actualizacion
			$accion="Upd";
			//Sentencia SQL para obtener la fecha y hora de la checada
			$stm_sql="SELECT hora_checada,estado FROM checadas WHERE empleados_rfc_empleado='$rfc_emp' AND fecha_checada='$fecha_sql' ORDER BY hora_checada";
			//Ejecutar la sentencia SQL
			$rs=mysql_query($stm_sql);

			//Verificar los resultados regresados por la consulta
			if($datos=mysql_fetch_array($rs)){
				//Recorrer los Registros retornados por la sentencia SQL
				do{
					//En la variable incidencia almacenar el estado del registro actual
					$incidencia=$datos["estado"];
					//Verificar el valor de la incidencia
					if ($incidencia!="SALIDA"){//En caso que sea diferente de Salida es una Entrada, independientemente del estado asignado
						//Recuperar la hora de checada
						$hora_e=$datos["hora_checada"];
						//Convertir la hora a formato legible por el usuario
						$hora_e=modHora($hora_e);
						//Extraer el meridiano de la hora
						$mer_e=substr($hora_e,-2);
						//Extraer la hora agregandole un 0, en caso de que la hora sea menor a 10
						//con tal de dejar la hora en formato de 2 digitos
						if (strlen($hora_e)==10)
							$hora_e="0".substr($hora_e,0,4);
						if (strlen($hora_e)==11)
							$hora_e=substr($hora_e,0,5);
					}
					else{
						//Si la incidencia es Salida, quiere decir que con la hora de salida se hara una actualizacion
						$salida="Upd";
						//Recuperar la hora de la salida
						$hora_s=$datos["hora_checada"];
						//Convertir la hora a formato legible por el usuario
						$hora_s=modHora($hora_s);
						//Extraer el meridiano de la hora
						$mer_s=substr($hora_s,-2);
						//Extraer la hora agregandole un 0, en caso de que la hora sea menor a 10
						//con tal de dejar la hora en formato de 2 digitos
						if (strlen($hora_s)==10)
							$hora_s="0".substr($hora_s,0,4);
						if (strlen($hora_s)==11)
							$hora_s=substr($hora_s,0,5);
					}
				}while($datos=mysql_fetch_array($rs));
			}
		}
		else{//Revisar el registro para posibles Salidas en caso de no haber Registro de Entrada
			//Sentencia SQL para extraer las salidas en caso de que existan
			$stm_sql="SELECT hora_checada FROM checadas WHERE empleados_rfc_empleado='$rfc_emp' AND fecha_checada='$fecha_sql' AND estado='SALIDA' ORDER BY hora_checada";
			//Ejecutar la sentencia SQL
			$rs=mysql_query($stm_sql);
			if($datos=mysql_fetch_array($rs)){
				$salida="Upd";
				$hora_s=$datos["hora_checada"];
				$hora_s=modHora($hora_s);
				$mer_s=substr($hora_s,-2);
				if (strlen($hora_s)==10)
					$hora_s="0".substr($hora_s,0,4);
				if (strlen($hora_s)==11)
					$hora_s=substr($hora_s,0,5);
			}
		}
		?>
		<script type="text/javascript" src="../../includes/validacionRecursosHumanos.js" ></script>
		<script type="text/javascript" src="../../includes/funcionesJS.js" ></script>
		<script type="text/javascript" language="javascript">
			<?php //Funcion que activa los elementos para dar una Salida?>
			function activarSalida(check){
				if (check.checked){
					document.getElementById("txt_horaS").disabled=false;
					document.getElementById("cmb_horaS").disabled=false;
				}
				else{
					document.getElementById("txt_horaS").disabled=true;
					document.getElementById("cmb_horaS").disabled=true;
				}
			}
			<?php //Funcion que activa los elementos para dar una Entrada?>
			function activarEntrada(check){
				if (check.checked){
					document.getElementById("txt_horaE").disabled=false;
					document.getElementById("cmb_horaE").disabled=false;
					document.getElementById("cmb_estado").disabled=false;
				}
				else{
					document.getElementById("txt_horaE").disabled=true;
					document.getElementById("cmb_horaE").disabled=true;
					document.getElementById("cmb_estado").disabled=true;
				}
			}
		</script>
		<form name="frm_asignarEstado" method="post" onsubmit="return valFormAsignarEstado(this);">
		<table cellpadding="5" width="100%" align="center" class="tabla_frm">
		<caption class="titulo_etiqueta"><?php echo $msg;?></caption>
		<tr><td colspan="6">&nbsp;</td></tr>
		<tr>
			<td><div align="right">**Hora Entrada</div></td>
			<td>
				<?php if ($hora_e==""){?>
				<input type="checkbox" name="ckb_activarEntrada" id="ckb_activarEntrada" onclick="activarEntrada(this);"/>
				<input type="text" name="txt_horaE" id="txt_horaE" size="5" onchange="formatHora(this,'cmb_horaE');" maxlength="5" onkeypress="return permite(event,'num',0);" value="" disabled="disabled"/>&nbsp;
				<select name="cmb_horaE" id="cmb_horaE" class="combo_box" disabled="disabled">
					<option value="AM" selected="selected">a.m.</option>
					<option value="PM">p.m.</option>
				</select>
				<?php }
				else{?>
				<input type="checkbox" name="ckb_activarEntrada" id="ckb_activarEntrada" onclick="activarEntrada(this);"/>
				<input type="text" name="txt_horaE" id="txt_horaE" size="5" onchange="formatHora(this,'cmb_horaE');" maxlength="5" onkeypress="return permite(event,'num',0);" value="<?php echo $hora_e; ?>" disabled="disabled"/>&nbsp;
				<select name="cmb_horaE" id="cmb_horaE" class="combo_box" disabled="disabled">
					<option value="AM" <?php if ($mer_e=="am") echo "selected='selected'";?>>a.m.</option>
					<option value="PM" <?php if ($mer_e=="pm") echo "selected='selected'";?>>p.m.</option>
				</select>
				<?php }?>
			</td>
			<td><div align="right">**Hora Salida</div></td>
			<td>
				<?php if ($hora_s==""){?>
				<input type="checkbox" name="ckb_activarSalida" id="ckb_activarSalida" onclick="activarSalida(this);"/>
				<input type="text" name="txt_horaS" id="txt_horaS" size="5" onchange="formatHora(this,'cmb_horaS');" maxlength="5" onkeypress="return permite(event,'num',0);" value="" disabled="disabled"/>&nbsp;
				<select name="cmb_horaS" id="cmb_horaS" class="combo_box" disabled="disabled">
					<option value="AM" selected="selected">a.m.</option>
					<option value="PM">p.m.</option>
				</select>
				<?php }
				else{?>
				<input type="checkbox" name="ckb_activarSalida" id="ckb_activarSalida" onclick="activarSalida(this);"/>
				<input type="text" name="txt_horaS" id="txt_horaS" size="5" onchange="formatHora(this,'cmb_horaE');" maxlength="5" onkeypress="return permite(event,'num',0);" value="<?php echo $hora_s;?>" disabled="disabled"/>&nbsp;
				<select name="cmb_horaS" id="cmb_horaS" class="combo_box" disabled="disabled">
					<option value="AM" <?php if ($mer_s=="am") echo "selected='selected'";?>>a.m.</option>
					<option value="PM" <?php if ($mer_s=="pm") echo "selected='selected'";?>>p.m.</option>
				</select>
				<?php }?>
			</td>
			<td><div align="right">**Estado</div></td>
			<td>
				<select name="cmb_estado" id="cmb_estado" class="combo_box" disabled="disabled">
					<option value="" <?php if($valorOriginal=="") echo "selected='selected'";?>>Estado</option>
					<option value="A" title="Asistencia" <?php if($valorOriginal=="A") echo "selected='selected'";?>>ASISTENCIA</option>
					<option value="F" title="Falta" <?php if($valorOriginal=="F") echo "selected='selected'";?>>FALTA</option>
					<option value="d" title="Descanso" <?php if($valorOriginal=="d") echo "selected='selected'";?>>DESCANSO</option>
					<option value="V" title="Vacaciones" <?php if($valorOriginal=="V") echo "selected='selected'";?>>VACACIONES</option>
					<option value="r" title="Retardo" <?php if($valorOriginal=="r") echo "selected='selected'";?>>RETARDO</option>
					<option value="F/J" title="Falta/Justificada" <?php if($valorOriginal=="F/J") echo "selected='selected'";?>>FALTA JUSTIFICADA</option>
					<option value="P" title="Permiso Sin Goce de Sueldo" <?php if($valorOriginal=="P") echo "selected='selected'";?>>PERMISO SIN GOCE</option>
					<option value="P/G" title="Permiso Con Goce de Sueldo" <?php if($valorOriginal=="P/G") echo "selected='selected'";?>>PERMISO CON GOCE</option>
					<option value="E" title="Incapacidad por Enfermedad General" <?php if($valorOriginal=="E") echo "selected='selected'";?>>INCAPACIDAD ENFERMEDAD GENERAL</option>
					<option value="RT" title="Incapacidad por Accidente de Trabajo" <?php if($valorOriginal=="RT") echo "selected='selected'";?>>INCAPACIDAD ACCIDENTE TRABAJO</option>
					<option value="T" title="Incapacidad en Trayecto" <?php if($valorOriginal=="T") echo "selected='selected'";?>>INCAPACIDAD EN TRAYECTO</option>
					<option value="D" title="Sanci&oacute;n Discplinaria" <?php if($valorOriginal=="D") echo "selected='selected'";?>>SANCION DISCIPLINARIA</option>
					<option value="R" title="Regresaron" <?php if($valorOriginal=="R") echo "selected='selected'";?>>REGRESARON</option>
				</select>
				<input type="hidden" name="hdn_estadoOriginal" id="hdn_estadoOriginal" value="<?php echo $valorOriginal?>"/>
			</td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<tr>
		  <td colspan="6"><strong>**Datos Obligatorios Dependiendo de lo Seleccionado</strong></td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="6" align="center">
				<input type="hidden" name="hdn_accion" id="hdn_accion" value="<?php echo $accion;?>"/>
				<input type="hidden" name="hdn_accionSalida" id="hdn_accionSalida" value="<?php echo $salida;?>"/>
				<input type="hidden" name="hdn_fecha" id="hdn_fecha" value="<?php echo $fecha_sql;?>"/>
				<input type="hidden" name="hdn_cve" id="hdn_cve" value="<?php echo $cve_emp;?>"/>
				<input type="hidden" name="hdn_rfc" id="hdn_rfc" value="<?php echo $rfc_emp;?>"/>
				<input type="hidden" name="hdn_caja" id="hdn_caja" value="<?php echo $nombreCaja?>"/>
				<input type="hidden" name="hdn_validar" id="hdn_validar" value="si"/>
				<input type="submit" name="sbt_guardar" id="sbt_guardar" value="Guardar" class="botones" title="Guarda el Registro en el Kardex" onmouseover="window.estatus='';return true"/>
				<?php 
				if ($valorOriginal!=""){
					?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_eliminarEntrada" id="sbt_eliminarEntrada" value="Borrar Entrada" class="botones" title="Borra el Registro de la Entrada del Kardex" onmouseover="window.estatus='';return true" onclick="hdn_validar.value='no'"/>
					<?php
				}
				?>
				<?php 
				if ($salida=="Upd"){
					?>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<input type="submit" name="sbt_eliminarSalida" id="sbt_eliminarSalida" value="Borrar Salida" class="botones" title="Borra el Registro de la Salida del Kardex" onmouseover="window.estatus='';return true" onclick="cmb_estado.disabled=false;hdn_validar.value='no'"/>
					<?php
				}
				?>
				&nbsp;&nbsp;&nbsp;&nbsp;
				<input align="middle" name="btn_cerrar" type="button" value="Cerrar" class="botones" title="Cerrar" onMouseOver="window.estatus='';return true"  onclick="window.close();"/>
			</td>
		</tr>
		</table>
		</form>
		<?php
	}//Fin de la Funcion asignarKardex()
	
	//Funcion que guarda el Estado ingresado en el formulario mostrado en la funcion asignarKardex()
	function guardarEstado(){
		//Obtener la Fecha
		$fecha=$_POST["hdn_fecha"];
		//Obtener la clave de Empleado
		$cve_emp=$_POST["hdn_cve"];
		//Obtener el RFC de empleado
		$rfc=$_POST["hdn_rfc"];
		//Recuperar el estado asignado
		$estado="";

		//Verificar si se ingresaron los datos para una salida
		if (isset($_POST["ckb_activarEntrada"])){
			//Recuperar la hora de Salida
			$horaE=$_POST["txt_horaE"]." ".$_POST["cmb_horaE"];
			//Modificar la hora a formato compatible con MySQL
			$horaE=modHora24($horaE);
			//Recuperar el estado asignado
			$estado=$_POST["cmb_estado"];
		}
		//Verificar si se ingresaron los datos para una salida
		if (isset($_POST["ckb_activarSalida"])){
			//Recuperar la hora de Salida
			$horaS=$_POST["txt_horaS"]." ".$_POST["cmb_horaS"];
			//Modificar la hora a formato compatible con MySQL
			$horaS=modHora24($horaS);
		}
		//Inicializar la variable $sql_stm vacia para cuando sea una Salida sin Entrada, No marque Error al concatenar las sentencias a ejecutar
		$sql_stm="";
		//Para el caso de estar registrando una Entrada
		if (isset($_POST["ckb_activarEntrada"])){
			//Verificsr si se va a agregar un registro de Entrada
			if($_POST["hdn_accion"]=="Add")
				$sql_stm="INSERT INTO checadas (empleados_rfc_empleado,empleados_id_empleados_empresa,fecha_checada,hora_checada,estado) VALUES ('$rfc','$cve_emp','$fecha','$horaE','$estado')";
			//Verificar si se va a Modificar un registro de Entrada de la Base de Datos
			if($_POST["hdn_accion"]=="Upd")
				$sql_stm="UPDATE checadas SET hora_checada='$horaE',estado='$estado' WHERE empleados_rfc_empleado='$rfc' AND empleados_id_empleados_empresa='$cve_emp' AND fecha_checada='$fecha' AND estado='$_POST[hdn_estadoOriginal]'";
		}
		
		//Para el caso de estar registrando una Salida
		if (isset($_POST["ckb_activarSalida"])){
			//Verificar si se va a agregar un registro de Salida
			if($_POST["hdn_accionSalida"]=="Add")
				$sql_stm.=";INSERT INTO checadas (empleados_rfc_empleado,empleados_id_empleados_empresa,fecha_checada,hora_checada,estado) VALUES ('$rfc','$cve_emp','$fecha','$horaS','SALIDA')";
			//Verificar si se va a Modificar un registro de Salida de la Base de Datos
			if($_POST["hdn_accionSalida"]=="Upd")
				$sql_stm.=";UPDATE checadas SET hora_checada='$horaS' WHERE empleados_rfc_empleado='$rfc' AND empleados_id_empleados_empresa='$cve_emp' AND fecha_checada='$fecha' AND estado='SALIDA'";
		}
		//Abrir la conexion a la Base de Datos
		$conn=conecta("bd_recursos");
		//Si la primer posicion es igual a ";" quiere decir que se esta registrando solamente una salida
		if (substr($sql_stm,0,1)==";"){
			//Quitar el ; de la sentencia SQL al inicio
			$sql_stm=str_replace(";","",$sql_stm);
			//Ejecutar la sentencia SQL
			$rs=mysql_query($sql_stm);
			//Si la consulta se realizo correctamente, cerrar la ventana y pasar el foco a la ventana padre
			if ($rs){
				//Abrir la sesion para extraer el nombre del usuario operando el sistema
				session_start();
				if($_POST["hdn_accionSalida"]=="Add")
					//Registrar la Operacion en la Bit�cora de Movimientos
					registrarOperacion("bd_recursos","$rfc","RegSalidaKardex",$_SESSION['usr_reg']);
				else
					//Registrar la Operacion en la Bit�cora de Movimientos
					registrarOperacion("bd_recursos","$rfc","ModSalidaKardex",$_SESSION['usr_reg']);
				?>
				<script language="javascript" type="text/javascript">
					window.opener.focus();
					window.close();
				</script>
				<?php
			}
			else{//Si la consulta genero errores, mostrarlos en pantalla
				$err=mysql_error();
				echo $err;
			}
		}
		else{//Si el primer caracter de la sentencia es ";", aplicarle el split
			$sentencias=split(";",$sql_stm);
			//Cantidad de Sentencias obtenidas
			$tam=count($sentencias);
			//Contador para controlar las sentencias
			$cont=0;
			do{
				//Obtener la sentencia SQL
				$sentencia=$sentencias[$cont];
				//Ejecutar la sentencia SQL en curso
				$rs=mysql_query($sentencia);
				if ($rs)
					$cont++;
				else
					break;
			}while($cont<$tam);
			//Si el contador es igual al tama�o del arreglo de sentencias, el proceso se llevo a cabo con exito
			if ($cont==$tam){
				//Abrir la sesion para extraer el nombre del usuario operando el sistema
				session_start();
				if($_POST["hdn_accion"]=="Add"){
					//Registrar la Operacion en la Bit�cora de Movimientos
					registrarOperacion("bd_recursos","$rfc","RegIncidenciaKardex",$_SESSION['usr_reg']);
					//Si el contador es igual a 2, entonces tambien el proceso de guardado incluyo una Entrada y una Salida
					if($cont==2){
						if($_POST["hdn_accionSalida"]=="Add")
							//Registrar la Operacion en la Bit�cora de Movimientos
							registrarOperacion("bd_recursos","$rfc","RegSalidaKardex",$_SESSION['usr_reg']);
						else
							//Registrar la Operacion en la Bit�cora de Movimientos
							registrarOperacion("bd_recursos","$rfc","ModSalidaKardex",$_SESSION['usr_reg']);
					}
				}
				else{
					//Registrar la Operacion en la Bit�cora de Movimientos
					registrarOperacion("bd_recursos","$rfc","ModIncidenciaKardex",$_SESSION['usr_reg']);
					//Si el contador es igual a 2, entonces tambien el proceso de guardado incluyo una Entrada y una Salida
					if($cont==2){
						if($_POST["hdn_accionSalida"]=="Add")
							//Registrar la Operacion en la Bit�cora de Movimientos
							registrarOperacion("bd_recursos","$rfc","RegSalidaKardex",$_SESSION['usr_reg']);
						else
							//Registrar la Operacion en la Bit�cora de Movimientos
							registrarOperacion("bd_recursos","$rfc","ModSalidaKardex",$_SESSION['usr_reg']);
					}
				}
				//Recuperar el nombre de la caja de donde se disparo la ventana Emergente
				$caja=$_POST["hdn_caja"];
				//Asignar a la caja iniciadora del evento, el Estado asignado por el Usuario, ademas de resaltarla con el color correspondiente
				?>
				<script language="javascript" type="text/javascript">
					window.opener.document.getElementById("<?php echo $caja;?>").style.background="669900";
					<?php if ($estado=="A"){?>
						window.opener.document.getElementById("<?php echo $caja;?>").style.color="669900";
					<?php }
					else{?>
						window.opener.document.getElementById("<?php echo $caja;?>").style.color="FFF";
					<?php }?>
					window.opener.document.getElementById("<?php echo $caja;?>").value="<?php echo $estado?>";
					window.opener.focus();
					window.close();
				</script>
				<?php
			}
			else{//Si la consulta genero errores, mostrarlos en pantalla
				$err=mysql_error();
				echo $err;
			}
		}
	}//Fin de la funcion guardarEstado()
	
	function borrarEstado(){
		$conn=conecta("bd_recursos");
		$fecha=$_POST["hdn_fecha"];
		$clave=$_POST["hdn_cve"];
		$rfc=$_POST["hdn_rfc"];
		$caja=$_POST["hdn_caja"];
		//Sentencia SQL para extraer las salidas en caso de que existan
		$stm_sql="DELETE FROM checadas WHERE fecha_checada='$fecha' AND empleados_rfc_empleado='$rfc' AND empleados_id_empleados_empresa='$clave' AND estado";
		$estado="Entrada";
		if (isset($_POST["sbt_eliminarEntrada"]))
			$stm_sql.="!='SALIDA'";
		else{
			$stm_sql.="='SALIDA'";
			$estado="Salida";
		}
		//Ejecutar la sentencia SQL
		$rs=mysql_query($stm_sql);
		if ($rs){
			//Abrir la sesion para extraer el nombre del usuario operando el sistema
			session_start();
			if (isset($_POST["sbt_eliminarEntrada"]))
				//Registrar la Operacion en la Bit�cora de Movimientos
				registrarOperacion("bd_recursos","$rfc","BorrarIncidenciaKardex",$_SESSION['usr_reg']);
			else
				//Registrar la Operacion en la Bit�cora de Movimientos
				registrarOperacion("bd_recursos","$rfc","BorrarSalidaKardex",$_SESSION['usr_reg']);
			if ($estado=="Entrada"){
				?>
				<script language="javascript" type="text/javascript">
						window.opener.document.getElementById("<?php echo $caja;?>").value="";
						window.opener.document.getElementById("<?php echo $caja;?>").style.background="808080";
				</script>
				<?php 
			}
			?>
			<script language="javascript" type="text/javascript">
					setTimeout("alert('El Estado <?php echo $estado;?> Ha sido Borrado del Registro')",500);
			</script>
			<?php 
		}//Cierre if ($rs)
		
	}//Cierre de la funcion borrarEstado()
?>