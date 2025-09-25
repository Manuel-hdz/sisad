<?php
	/**
	  * Nombre del Módulo: Topografía
	  * Nombre Programador: Maurilio Hernández Correa
	  * Fecha: 25/Mayo/2011
	  * Descripción: Este archivo contiene funciones para almacenar la información relacionada con el formulario de modificar estimación en la BD
	**/
	
	//Funcion que se encarga de desplegar las estimaciones en el rango de fechas
	function mostrarEstimaciones(){

		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");
		
		//Si viene sbt_consultarQuincena la buqueda de los traspaleos proviene de seleccionar una quincena de una obra específica
		if(isset($_POST["sbt_consultarQuincena"])){ 
					
			//Crear sentencia SQL
			$sql_stm ="SELECT * ,estimaciones.fecha_registro AS fecha_estimacion FROM estimaciones JOIN obras ON id_obra=obras_id_obra  
			WHERE tipo_obra='$_POST[cmb_tipoObra]' AND id_obra='$_POST[cmb_nomObra]' AND no_quincena='$_POST[cmb_numQuincena]'";		
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Estimaciones de <em><u>  $_POST[cmb_tipoObra]    </u></em> de la Obra <em><u>	$_POST[cmb_nomObra]  </u></em> de la Quincena 
			<em><u>	$_POST[cmb_numQuincena]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Estimacione de <em><u>  $_POST[cmb_tipoObra]    
			</u></em> de la Obra <em><u>	$_POST[cmb_nomObra]  </u></em> de la Quincena <em><u>	$_POST[cmb_numQuincena]  </u></em>";
		}	
		
		//Si viene sbt_consultarMes la buqueda de los traspaleos proviene de seleccionar un mes y año
		if(isset($_POST["sbt_consultarMes"])){ 
		
			//Crear sentencia SQL
			$sql_stm ="SELECT * ,estimaciones.fecha_registro AS fecha_estimacion FROM estimaciones JOIN obras ON id_obra=obras_id_obra 
			WHERE no_quincena LIKE'% $_POST[cmb_mes] $_POST[cmb_anios]'";	
					
			//Crear el mensaje que se mostrara en el titulo de la tabla
			$msg= "Estimaciones del mes de <em><u>  $_POST[cmb_mes]    </u></em> del a&ntilde;o<em><u>	$_POST[cmb_anios]  </u></em>";
			
			//Crear el Mensaje en caso de que la consulta no arroje ningún resultado
			$msg_error = "	<label class='msje_correcto' align='center'>No se Encontr&oacute; Ninguna Estimaci&oacute;n del mes de <em><u>  $_POST[cmb_mes]    
			</u></em> del a&ntilde;o<em><u>	$_POST[cmb_anios]  </u></em>";
		}
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
	
		//Confirmar que la consulta de datos fue realizada con exito.
		if($datos=mysql_fetch_array($rs)){
	
			//Desplegar los resultados de la consulta en una tabla
			echo "				
			<table cellpadding='5' width='1500'>				
				<tr>
					<td colspan='18' align='center' class='titulo_etiqueta'>$msg</td>
				</tr>
				<tr>
					<td class='nombres_columnas' align='center'>MODIFICAR</td>
					<td class='nombres_columnas' align='center'>TIPO OBRA</td>
					<td class='nombres_columnas' align='center'>NOMBRE OBRA</td>
					<td class='nombres_columnas' align='center'>SECCI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>CANTIDAD</td>
					<td class='nombres_columnas' align='center'>PRECIO/U M.N</td>
					<td class='nombres_columnas' align='center'>PRECIO/U USD</td>
					<td class='nombres_columnas' align='center'>TASA CAMBIO</td>
					<td class='nombres_columnas' align='center'>TOTAL MN</td>
					<td class='nombres_columnas' align='center'>TOTAL USD</td>
					<td class='nombres_columnas' align='center'>IMPORTE</td>
					<td class='nombres_columnas' align='center'>FECHA ELABORACI&Oacute;N</td>
					<td class='nombres_columnas' align='center'>NO QUINCENA</td>
				</tr>";

			$nom_clase = "renglon_gris";
			$cont = 1;	
			do{	
				//Mostrar todos los registros que han sido completados
				echo "
					<tr>
						<td class='nombres_filas' align='center'><input type='radio' name='rdb' value= $datos[id_estimacion] />
						</td>
						<td class='$nom_clase'>$datos[tipo_obra]</td>
						<td class='$nom_clase'>$datos[nombre_obra]</td>
						<td class='$nom_clase'>$datos[seccion]</td>
						<td class='$nom_clase'>".number_format($datos['cantidad'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['pumn_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['puusd_estimacion'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['t_cambio'],4,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['total_mn'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['total_usd'],2,".",",")."</td>
						<td class='$nom_clase'>$".number_format($datos['importe'],2,".",",")."</td>
						<td class='$nom_clase'>".modFecha($datos['fecha_estimacion'],1)."</td>
						<td class='$nom_clase'>$datos[no_quincena]</td>
					</tr>";
					
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";				
			}while($datos=mysql_fetch_array($rs));
			//Fin de la tabla donde se muestran los resultados de la consulta
			echo "</table>";
			return 1;
			
		}// fin  if($datos=mysql_fetch_array($rs))
		else{//Si no se encuentra ningun resultado desplegar el mensaje de alerta notificando que no hay resultados disponibles
			echo $msg_error;		
			return 0;		
		}
	}
	
	//Funcion que se encarga de  la estimacion seleccionada
	function modificarEstimacionSeleccionada(){
		//Relizar la consulta con el id de la obra seleccionada para poder precargar los datos 
		//Conectar a la BD de Topografía
		$conn = conecta("bd_topografia");

		//Crear sentencia SQL
		$sql_stm ="SELECT * FROM estimaciones JOIN obras ON obras_id_obra=id_obra WHERE id_estimacion = '$_POST[rdb]'";
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($sql_stm);									
		//Confirmar que la consulta de datos fue realizada con exito.
		$datos=mysql_fetch_array($rs);
		if ($datos['no_quincena']!='')
			$fecha= split(' ',$datos['no_quincena']);?>
    
    
        <div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
        <fieldset class="borde_seccion" id="tabla-registrarEstimacion" name="tabla-registrarEstimacion">
        <legend class="titulo_etiqueta">Ingrese la Informaci&oacute;n de la Estimaci&oacute;n</legend>	
        <br>
        <form onSubmit="return valFormRegEstimacion(this);" name="frm_modificarEstimacion" method="post" action="frm_modificarEstimacion.php">
		<table width="843" height="411"  cellpadding="5" cellspacing="5" class="tabla_frm">
            <tr>
                <td><div align="right">Tipo Obra</div></td>
                <td><input name="txt_tipo" id="txt_tipo" type="text" class="caja_de_texto" size="32" value="<?php echo $datos['tipo_obra'] ?>" 
                	readonly="readonly"/>                
                </td>
                <td width="171"><div align="right">Fecha Elaboraci&oacute;n</div></td>
                <td colspan="3">
					<input name="txt_fechaElaborado" id="txt_fechaElaborado" type="text" class="caja_de_texto" size="10" maxlength="10" readonly="readonly"
                    value="<?php echo modFecha($datos['fecha_registro'],1)?>"/>
                </td>
            </tr>
            <tr>
                <td><div align="right">Nombre Obra</div></td>
                <td><input name="txt_nombreObra" id="txt_nombreObra" type="text" class="caja_de_texto" size="40" readonly="readonly" 
                    value="<?php echo $datos['nombre_obra'] ?>"/>
                </td>
                <td align="right">*No. Quincena</td>
                <td>
                    <select name="cmb_noQuincena" id="cmb_noQuincena" class="combo_box" onchange="verificarQuincena('ESTIMACION');">
                        <option value="" <?php if($fecha[0]=='') echo "selected = 'selected'";?> >Num.</option>
                        <option value="1" <?php if($fecha[0]==1) echo "selected = 'selected'";?>>1</option>
                        <option value="2" <?php if($fecha[0]==2) echo "selected = 'selected'";?>>2</option>
                    </select>						
                    <select name="cmb_Mes" id="cmb_Mes" class="combo_box" onchange="verificarQuincena('ESTIMACION');">
                        <option value="" <?php if($fecha[1]=='') echo "selected = 'selected'";?> >Mes</option>
                        <option value="ENERO" <?php if($fecha[1]=='ENERO') echo "selected = 'selected'";?>>Enero</option>
                        <option value="FEBRERO" <?php if($fecha[1]=='FEBRERO') echo "selected = 'selected'";?>>Febrero</option>
                        <option value="MARZO" <?php if($fecha[1]=='MARZO') echo "selected = 'selected'";?>>Marzo</option>
                        <option value="ABRIL" <?php if($fecha[1]=='ABRIL') echo "selected = 'selected'";?>>Abril</option>
                        <option value="MAYO" <?php if($fecha[1]=='MAYO') echo "selected = 'selected'";?>>Mayo</option>
                        <option value="JUNIO" <?php if($fecha[1]=='JUNIO') echo "selected = 'selected'";?>>Junio</option>
                        <option value="JULIO" <?php if($fecha[1]=='JULIO') echo "selected = 'selected'";?>>Julio</option>
                        <option value="AGOSTO" <?php if($fecha[1]=='AGOSTO') echo "selected = 'selected'";?>>Agosto</option>
                        <option value="SEPTIEMBRE" <?php if($fecha[1]=='SEPTIEMBRE') echo "selected = 'selected'";?>>Septiembre</option>
                        <option value="OCTUBRE" <?php if($fecha[1]=='OCTUBRE') echo "selected = 'selected'";?>>Octrube</option>
                        <option value="NOVIEMBRE" <?php if($fecha[1]=='NOVIEMBRE') echo "selected = 'selected'";?>>Noviembre</option>
                        <option value="DICIEMBRE" <?php if($fecha[1]=='DICIEMBRE') echo "selected = 'selected'";?>>Diciembre</option>
                    </select>
                    <select name="cmb_Anio" id="cmb_Anio" class="combo_box" onchange="verificarQuincena('ESTIMACION');">
                        <option value="" <?php if($fecha[2]=='') echo "selected = 'selected'";?>>A&ntilde;o</option><?php
                        //Obtener el Año Actual
                        $anioInicio = intval(date("Y")) - 10;
                        for($i=0;$i<21;$i++){
							if($anioInicio==$fecha[2])
								echo "<option value='$anioInicio' selected= 'selected'>$anioInicio</option>";
							else	
	                            echo "<option value='$anioInicio'>$anioInicio</option>";
							$anioInicio++;
								
                        }?>							
                    </select>
                </td>				
            </tr>
            <tr>
                <td><div align="right">Secci&oacute;n</div></td>
                <td><input name="txt_seccion" id="txt_seccion" type="text" class="caja_de_texto" size="10" readonly="readonly" 
                    value="<?php echo $datos['seccion'] ?>"/>
                </td>
                <td><div align="right">Unidad</div></td>
                <td colspan="3"><input name="txt_unidad" id="txt_unidad" type="text" class="caja_de_texto" size="10" readonly="readonly" 
                	value="<?php echo $datos['unidad'] ?>"/>
                </td>			
            </tr>
            <tr>
                <td><div align="right">*Cantidad</div></td>
                <td><input name="txt_cantidad" id="txt_cantidad" type="text" class="caja_de_texto" size="10" maxlength="10" 
                	value="<?php echo $datos['cantidad'] ?>" 
                    onkeypress="return permite(event,'num',2);" 
                    onchange="txt_totalMN.value= parseFloat(txt_cantidad.value.replace(/,/g,''))*parseFloat(txt_precioMN.value.replace(/,/g,'')); formatCurrency(txt_totalMN.value,'txt_totalMN');  
                    txt_totalUSD.value= parseFloat(txt_cantidad.value.replace(/,/g,''))*parseFloat(txt_precioUSD.value.replace(/,/g,''))*parseFloat(txt_tasaCambio.value.replace(/,/g,'')); formatCurrency(txt_totalUSD.value,'txt_totalUSD'); 
                    txt_importe.value= parseFloat(txt_totalMN.value.replace(/,/g,''))+parseFloat(txt_totalUSD.value.replace(/,/g,'')); formatCurrency(txt_importe.value,'txt_importe');"/>
                </td>			
                <td><div align="right">Total MN</div></td>
                <td colspan="3">$
                    <input name="txt_totalMN" id="txt_totalMN" type="text" class="caja_de_texto" size="10" readonly="readonly" 
                    value="<?php echo number_format($datos['total_mn'],2,".",",") ?>"/>
                </td>
            </tr>	
            <tr>
                <td width="157"><div align="right">Precio Unitario M.N.</div></td>
                <td>$
                    <input name="txt_precioMN" id="txt_precioMN" type="text" class="caja_de_texto" size="10" value="<?php echo $datos['pumn_estimacion'] ?>" 
                    readonly="readonly"/>
                </td>
                <td><div align="right">Total USD</div></td>
                <td colspan="3">$
                    <input name="txt_totalUSD" id="txt_totalUSD" type="text" class="caja_de_texto" size="10" readonly="readonly" 
                    value="<?php echo number_format($datos['total_usd'],2,".",",") ?>"/>
                </td>
                
            </tr>
            <tr>
                <td><div align="right">Precio Unitario USD.</div></td>
                <td>$
                    <input name="txt_precioUSD" id="txt_precioUSD" type="text" class="caja_de_texto" size="10" value="<?php echo $datos['puusd_estimacion']?>" 
                    readonly="readonly"/>
                </td>
                <td><div align="right">Importe</div></td>
                <td colspan="3">$
                    <input name="txt_importe" id="txt_importe" type="text" class="caja_de_texto" size="10" readonly="readonly" 
                    value="<?php echo number_format($datos['importe'],2,".",",") ?>"
                    onchange="formatCurrency(value,'txt_importe');"/>
                </td>
                </tr>
            <tr>
                <td><div align="right">Tasa de Cambio</div></td>
                <td>$
                    <input name="txt_tasaCambio" id="txt_tasaCambio" type="text" class="caja_de_texto" size="10" maxlength="10" 
                    onkeypress="return permite(event,'num',2);" value="<?php echo number_format($datos['t_cambio'],4,".",",") ?>" 
                    onchange="formatTasaCambio(value,'txt_tasaCambio'); txt_totalUSD.value= parseFloat(txt_cantidad.value.replace(/,/g,''))*parseFloat(txt_precioUSD.value.replace(/,/g,''))*parseFloat(txt_tasaCambio.value.replace(/,/g,'')); formatCurrency(txt_totalUSD.value,'txt_totalUSD'); txt_importe.value= parseFloat(txt_totalMN.value.replace(/,/g,''))+parseFloat(txt_totalUSD.value.replace(/,/g,'')); formatCurrency(txt_importe.value,'txt_importe');"/>
                </td>	
            </tr>
            <tr>
                <td colspan="6"><strong>* Los campos marcados con asterisco son <u>obligatorios</u></strong></td>
            </tr>
            <tr>
                <td colspan="6">
                    <div align="center">
                        <input name="sbt_guardar" type="submit" class="botones" id="sbt_guardar"  value="Modificar" title="Modificar Estimación" 
                        onmouseover="window.status='';return true" />
                        <input type="hidden" name="hdn_idEstimacion" id="hdn_idEstimacion" value="<?php echo $datos['id_estimacion']?>"/>
                        <input type="hidden" name="hdn_idObra" id="hdn_idObra" value="<?php echo $datos['id_obra']?>"/>
                        &nbsp;&nbsp;&nbsp;
                        <input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Restablecer" title="Restablecer Formulario" 
                        onmouseover="window.status='';return true"/>
                        &nbsp;&nbsp;&nbsp;
                        <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" 
                        title="Cancelar y Regresar al Men&uacute; de Estimaciones " 
                        onmouseover="window.status='';return true" onclick="confirmarSalida('menu_estimaciones.php');"/>
                    </div>
                </td>
            </tr>
        </table>
        </form>
        </fieldset><?php
	}//Fin de la funcion eliminarEstimacionSeleccionada
	
	//Si viene sbt_guardar 
	if(isset($_POST["sbt_guardar"]))
		guardarModificacion();
		
	//Funcion que se encarga de  guardar las modificaciones
	function guardarModificacion(){
		//Recoger los datos
		$id_estimacion = $_POST['hdn_idEstimacion'];
		$cantidad= str_replace(",","",$_POST['txt_cantidad']);
		$tasaCambio= str_replace(",","",$_POST['txt_tasaCambio']);
		$fechaElaborado= modfecha($_POST['txt_fechaElaborado'],3);
		$totalMN= str_replace(",","",$_POST['txt_totalMN']);
		$totalUSD= str_replace(",","",$_POST['txt_totalUSD']);
		$importe= str_replace(",","",$_POST['txt_importe']);
		$no_quincena = $_POST['cmb_noQuincena'].' '.$_POST['cmb_Mes'].' '.$_POST['cmb_Anio'];

		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_topografia");
		
		//Crear sentencia SQL
		$stm_sql= "UPDATE estimaciones SET cantidad=$cantidad, t_cambio=$tasaCambio, fecha_registro='$fechaElaborado', no_quincena='$no_quincena' ,
		total_mn=$totalMN, total_usd=$totalUSD, importe=$importe WHERE id_estimacion='$id_estimacion'";
		
		//Ejecutar la sentencia previamente creada
		$rs = mysql_query($stm_sql);
		
		//Verificar Resultado
		if ($rs){
			//Guardar el registro de movimientos
			registrarOperacion("bd_topografia",$id_estimacion,"ModificarEstimacion",$_SESSION['usr_reg']);
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
			$conn = conecta("bd_topografia");																			
		}
		else{
			$error = mysql_error();
			echo "<meta http-equiv='refresh' content='10;url=error.php?err=$error'>";
		}
		//Cerrar la conexion con la BD
		mysql_close($conn);									

	}
	
	function cargarAniosDisponible(){
	
		//conectar a bd_topografia
		$conn = conecta('bd_topografia');
		
		$rs_quincenas = mysql_query("SELECT DISTINCT no_quincena FROM estimaciones");
		$anios = array();
		
		while($datos_quincenas=mysql_fetch_array($rs_quincenas)){
			$quincena = $datos_quincenas['no_quincena'];
			$anios[] = substr($quincena, -4); 
		}
		
		$anioUnico = array_unique($anios);?>
		
		<select name="cmb_anios" id="cmb_anios" class="combo_box">  
            <option value="">Seleccione A&ntilde;o</option> <?php
            foreach($anioUnico as $ind => $anio){ ?>
                <option value="<?php echo $anio;?>"><?php echo $anio;?></option><?php
            }?>
		</select><?php
		
		//cerrar conexion
		mysql_close($conn);	
	} //Fin function cargarAniosDisponible()
	
	
?>