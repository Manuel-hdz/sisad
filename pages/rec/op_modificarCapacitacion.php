<?php
	/**
	  * Nombre del Módulo: Recursos Humanos
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 09/Abril/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de modificar Capacitacion en la BD
	**/
	
	if(isset($_POST['sbt_modificar'])){
		guardarModificacion();
	}
	
	//Funcion que se encarga de desplegar las capacitaciones en el rango de fechas
	function mostrarCapacitaciones(){
		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
		if(isset($_POST['sbt_consultar']) || (isset($_GET["crt2"]) && $_GET["crt2"]!="X")){
			if(isset($_POST['sbt_consultar'])){
				//Obtener las fechas en formato aaaa-mm-dd a partir de dd/mm/aaaa
				$f1 = modFecha($_POST['txt_fechaIni'],3);
				$f2 = modFecha($_POST['txt_fechaFin'],3);
				$fechaI=$_POST["txt_fechaIni"];
				$fechaF=$_POST["txt_fechaFin"];
			}
			else{
				$f1 = $_GET["crt1"];
				$f2 = $_GET["crt2"];
				$fechaI=modFecha($f1,1);
				$fechaF=modFecha($f2,1);
			}
			//Crear sentencia SQL
			$sql_stm ="SELECT * FROM capacitaciones	WHERE fecha_inicio>='$f1' AND fecha_inicio<='$f2' ORDER BY id_capacitacion";	
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg = "Capacitaciones en el Periodo del <em><u>$fechaI</u></em> al <em><u>$fechaF</u></em>";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Capacitaci&oacute;n del <em><u>$fechaI
			</u></em> al <em><u>$fechaF</u></em></label>";	
			$criterio1=$f1;
			$criterio2=$f2;
		}
		//Verificar el boton para consultar la informacion
		if(isset($_POST['sbt_consultar2']) || (isset($_GET["crt2"]) && $_GET["crt2"]=="X")){
			if(isset($_POST["sbt_consultar2"]))
				$id_cap=$_POST["cmb_claveCapacitacion"];
			else
				$id_cap=$_GET["crt1"];
			//Crear sentencia SQL
			$sql_stm = "SELECT * FROM capacitaciones WHERE id_capacitacion = '$id_cap'";
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Datos de la Capacitaci&oacute;n  <em><u> $id_cap</u></em>";
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Capacitaci&oacute;n </label>";
			$criterio1=$id_cap;
			$criterio2="X";
		}

		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);		
		
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "
			<table cellpadding='5' width='100%'>								
				<caption align='center' class='titulo_etiqueta'>$msg</caption>
				<tr>
					<td class='nombres_columnas' align='center'>MODIFICAR</td>
					<td class='nombres_columnas' align='center'>ID CAPACITACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>NOMBRE CAPACITACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>ASISTENTES</td>
					<td class='nombres_columnas' align='center'>DURACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>INSTRUCTOR</td>
					<td class='nombres_columnas' align='center'>DESCRIPCI&Oacute;N</td>
				</tr>				
				<form name='frm_detalleCapacitacion' method='post' action='frm_modificarCapacitacion.php'>
				<input type='hidden' name='verDetalle' value='si' />";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Preparar la sentencia para obtener el numero de personas que asistieron a cada capacitacion
				$sql_stm2 ="SELECT COUNT(capacitaciones_id_capacitacion) AS cant FROM empleados_reciben_capacitaciones 
				WHERE capacitaciones_id_capacitacion='$datos[id_capacitacion]'";	
				
				//Ejecutar la sentencia previamente creada
				$tot_asist = mysql_fetch_array(mysql_query($sql_stm2));
				
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' width='6%' align='center'><input type='checkbox' name='ckb' value='$datos[id_capacitacion]' 
							onClick='javascript:document.frm_detalleCapacitacion.submit();'/></td>
						<td class='$nom_clase' width='11%'>$datos[id_capacitacion]</td>
						<td class='$nom_clase' width='17%'>$datos[nom_capacitacion]</td>
						<td class='$nom_clase' width='6%'align='center'>$tot_asist[cant]</td>
						<td class='$nom_clase' width='10%'>$datos[hrs_capacitacion] HORAS</td>
						<td class='$nom_clase' width='20%'>$datos[instructor]</td>
						<td class='$nom_clase'>$datos[descripcion]</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			?>
			<input type="hidden" name="hdn_criterio1" value="<?php echo $criterio1;?>"/>
			<input type="hidden" name="hdn_criterio2" value="<?php echo $criterio2;?>"/>
			<?php
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</form>	
			</table>";
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;					
		}
	}
	
	//Funcion que permite mostrar  la capacitacion seleccionada en un checkbox
	function modificarCapacitacion($ckb){
		$criterio1=$_POST["hdn_criterio1"];
		$criterio2=$_POST["hdn_criterio2"];

		//Preparar la consulta para recopilar la informacion que puede ser modificada de la capacitacion 
		$sql_stm = "SELECT  norma,nom_capacitacion,modalidad,hrs_capacitacion,descripcion,tema,fecha_inicio,fecha_fin,instructor,objetivo,tipo_instructor,reg_instructor_stps
					FROM capacitaciones	WHERE id_capacitacion = '$ckb'";
	
		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
	
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);		
		
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	
		//Convertir la fecha al formato adecuado para mostrarlo
		$f1 = modFecha($datos['fecha_inicio'],1);
		$f2 = modFecha($datos['fecha_fin'],1);
		
		// Tabla para la modificacion de datos en la capacitacion
		?>
		<table width="100%" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
			<td width="154"><div align="right">Clave Capacitaci&oacute;n:</div></td>
            <td width="200">
				<input name="txt_claveCapacitacion" id="txt_claveCapacitacion" type="text" class="caja_de_texto" size="10" 
				value="<?php echo $ckb;?>" readonly="readonly" />
			</td>
			<td><div align="right">*Horas de Capacitaci&oacute;n:</div></td>
			<td>
				<input name="txt_hrsCapacitacion" id="txt_hrsCapacitacion" type="text" class="caja_de_texto" size="15" maxlength="10" 
                onkeypress="return permite(event,'num',2);" value="<?php echo $datos["hrs_capacitacion"];?>"/>
			</td>
        </tr>
        <tr>
            <td><div align="right">Fecha Inicio</div></td>
            <td>
				<input name="txt_fechaIni" id="txt_fechaIni" type="text" class="caja_de_texto" size="10" value="<?php echo $f1;?>" readonly="readonly"/>
            </td>
          <td width="226"><div align="right">Fecha de Fin:</div></td>
            <td width="203">
				<input name="txt_fechaFin" id="txt_fechaFin" type="text" class="caja_de_texto" size="10" value="<?php echo $f2;?>" readonly="readonly"/>
			</td>
        </tr>
		<tr>
			<td><div align="right">*Tema Capacitaci&oacute;n</div></td>
			<td colspan="3">
				<input type="text" name="txt_tema" id="txt_tema" class="caja_de_texto" size="60" maxlength="60" onkeypress="return permite(event,'num_car', 0);" value="<?php echo $datos["tema"];?>"/>
			</td>
		</tr>
		<tr>
			<td><div align="right">*Norma Capacitaci&oacute;n</div></td>
			<td>
				<input name="txt_normaCapacitacion" id="txt_normaCapacitacion" type="text" class="caja_de_texto" size="30" maxlength="30" 
				onkeypress="return permite(event,'num_car',0);" value="<?php echo $datos["norma"];?>"/>
			</td>
			<td><div align="right">*Nombre Capacitaci&oacute;n</div></td>
			<td>
				<input name="txt_nomCapacitacion" id="txt_nomCapacitacion" type="text" class="caja_de_texto" size="40" maxlength="60" 
				onkeypress="return permite(event,'num_car',0);" value="<?php echo $datos["nom_capacitacion"];?>"/>
			</td>
		</tr>
        <tr>
        <td><div align="right">*Modalidad Capacitaci&oacute;n:</div></td>
          <td>
			  <select name="cmb_modo" id="cmb_modo" class="combo_box">
				<option value=""<?php if($datos["modalidad"]=="") echo " selected='selected'";?>>Modalidad</option>
				<option value="1"<?php if($datos["modalidad"]=="1") echo " selected='selected'";?>>PRESENCIAL</option>
				<option value="2"<?php if($datos["modalidad"]=="2") echo " selected='selected'";?>>EN L&Iacute;NEA</option>
				<option value="3"<?php if($datos["modalidad"]=="3") echo " selected='selected'";?>>MIXTA</option>
			  </select>
		  </td>
          <td valign="top"><div align="right">*Descripci&oacute;n:</div></td>
          <td valign="top" >
		  	<textarea name="txa_descripcion" id="txa_descripcion"  maxlength="120" onkeyup="return ismaxlength(this)" 
            class="caja_de_texto" rows="2" cols="37" onkeypress="return permite(event,'num_car', 0);" ><?php echo $datos["descripcion"];?></textarea>
            </td>
        </tr>
		<tr>
			<td><div align="right">*Objetivo Capacitaci&oacute;n:</div></td>
			<td colspan="4">
				<select name="cmb_objetivo" id="cmb_objetivo" class="combo_box">
					<option value=""<?php if($datos["objetivo"]=="") echo " selected='selected'";?>>Objetivo</option>
					<option value="1"<?php if($datos["objetivo"]=="1") echo " selected='selected'";?>>ACTUALIZAR Y PERFECCIONAR CONOCIMIENTOS Y HABILIDADES</option>
					<option value="2"<?php if($datos["objetivo"]=="2") echo " selected='selected'";?>>PROPORCIONAR INFORMACI&Oacute;N DE NUEVAS TECNOLOG&Iacute;AS</option>
					<option value="3"<?php if($datos["objetivo"]=="3") echo " selected='selected'";?>>PREPARAR PARA OCUPAR VACANTES O PUESTOS DE NUEVA CREACI&Oacute;N</option>
					<option value="4"<?php if($datos["objetivo"]=="4") echo " selected='selected'";?>>PREVENIR RIESGOS DE TRABAJO</option>
					<option value="5"<?php if($datos["objetivo"]=="5") echo " selected='selected'";?>>INCREMENTAR LA PRODUCTIVIDAD</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="4"><strong>Datos del Instructor</strong></td>
		</tr>
		<tr>
            <td><div align="right">*Tipo Instructor:</div></td>
            <td>
				Interno<input type="radio" name="rdb_tipoIns" id="rdb_tipoIns" value="INTERNO" onclick="txt_numRegSTPS.readOnly=true;txt_numRegSTPS.value='';"<?php if($datos["tipo_instructor"]=="INTERNO") echo " checked='checked'";?>/>
				Externo<input type="radio" name="rdb_tipoIns" id="rdb_tipoIns" value="EXTERNO" onclick="txt_numRegSTPS.readOnly=false;txt_numRegSTPS.value=hdn_regSTPS.value"<?php if($datos["tipo_instructor"]=="EXTERNO") echo " checked='checked'";?>/>
			</td>
		</tr>
        <tr>
            <td><div align="right">*Nombre Instructor:</div></td>
            <td><input name="txt_instructor" id="txt_instructor" type="text" class="caja_de_texto" size="40" maxlength="60" 
                onkeypress="return permite(event,'car',0);" value="<?php echo $datos["instructor"];?>"/>
            </td>
			<td><div align="right">**N&uacute;mero Registro Instructor Externo en STPS:</div></td>
            <td><input name="txt_numRegSTPS" id="txt_numRegSTPS" type="text" class="caja_de_texto" size="20" maxlength="20" 
                onkeypress="return permite(event,'num_car',0);" value="<?php echo $datos["reg_instructor_stps"];?>" <?php if($datos["reg_instructor_stps"]=="") echo "readonly='readonly'";?>/>
				<input type="hidden" id="hdn_regSTPS" name="hdn_regSTPS" value="<?php echo $datos["reg_instructor_stps"];?>"
            </td>
		</tr>
        <tr>	   
        	<td colspan="4">
				<strong>
				* Los campos marcados con asterisco son <u>obligatorios</u>.<br>
				** Datos Obligatorios Dependiendo de lo Seleccionado.
				</strong>
			</td>
		</tr>            
        <tr>
            <td colspan="4"><div align="center">
                <input name="sbt_modificar" type="submit" class="botones" id="sbt_agregar"  value="Modificar" title="Modificar Capacitaci&oacute;n" 
                onmouseover="window.status='';return true"/>
                &nbsp;&nbsp;&nbsp;
                <input name="rst_limpiar" type="reset" class="botones"  value="Limpiar" title="Limpiar Formulario"
				onmouseover="window.status='';return true" <?php if($datos["reg_instructor_stps"]=="") echo "onclick='txt_numRegSTPS.readOnly=true;'"; else echo "onclick='txt_numRegSTPS.readOnly=false;'";?>/>
                &nbsp;&nbsp;&nbsp;
                <input name="btn_cancelar" type="button" class="botones" value="Cancelar" title="Cancelar" 
                onmouseover="window.status='';return true" onclick="confirmarSalida('frm_modificarCapacitacion.php?crt1=<?php echo $criterio1;?>&crt2=<?php echo $criterio2;?>');"/></div>
            </td>							               
        </tr>
   	 	</table>
		<?php 	
	}// Fin de 	function modificarCapacitacion($ckb)
	
	
	function guardarModificacion(){
		//Recoger los datos
		$nom_capacitacion= strtoupper($_POST['txt_nomCapacitacion']);
		$hrs_capacitacion=($_POST['txt_hrsCapacitacion']);
		$instructor= strtoupper($_POST['txt_instructor']);
		$fecha_ini= modfecha($_POST['txt_fechaIni'],3);
		$fecha_fin= modfecha($_POST['txt_fechaFin'],3);
		$descripcion= strtoupper($_POST['txa_descripcion']);
	 	//Formato DC-4
		$norma=strtoupper($_POST["txt_normaCapacitacion"]);
		$tema=strtoupper($_POST["txt_tema"]);
		$modalidad=$_POST["cmb_modo"];
		$objetivo=$_POST["cmb_objetivo"];
		$tipoInstructor=$_POST["rdb_tipoIns"];
		$regInsSTPS=strtoupper($_POST["txt_numRegSTPS"]);
		
		//Preparar la consulta para recopilar la informacion que puede ser modificada de la capacitacion 
		$sql_stm = "UPDATE capacitaciones SET nom_capacitacion='$nom_capacitacion', hrs_capacitacion='$hrs_capacitacion', descripcion='$descripcion', 
					fecha_inicio='$fecha_ini', fecha_fin='$fecha_fin', instructor='$instructor', norma='$norma', tema='$tema', modalidad='$modalidad', objetivo='$objetivo',
					tipo_instructor='$tipoInstructor',reg_instructor_stps='$regInsSTPS' 
					WHERE id_capacitacion = '$_POST[txt_claveCapacitacion]'";

		//Conectar a la BD de Recursos Humanos
		$conn = conecta("bd_recursos");
	
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);		
		
		//Verificar Resultado, Si no es favorable
		if ($rs){
			//Guardar la operacion realizada
			registrarOperacion("bd_recursos",$_POST['txt_claveCapacitacion'],"ModificarCapacitacion",$_SESSION['usr_reg']);																			
			$conn = conecta("bd_recursos");																			
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
		}
		
		//Cerrar la conexion con la BD
		mysql_close($conn);
	}
?>