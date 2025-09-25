<?php
	/**
	  * Nombre del Módulo: Desarrollo                                               
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 07/Noviembre/2011
	  * Descripción: En este archivo estan las funciones para asignar los Bonos
	  **/ 
	
	//Módulo de conexión a la BD
	include ("../../includes/conexion.inc");
	//Operaciones con la Base de Datos
	include("../../includes/op_operacionesBD.php");
	//Manejo de fechas
	include ("../../includes/func_fechas.php");
	?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<link rel="stylesheet" type="text/css" href="../../includes/estilo.css"/>	
	<script type="text/javascript" src="../../includes/validacionDesarrollo.js"></script>
	<script type="text/javascript" src="../../includes/formatoNumeros.js"></script>
	<script type="text/javascript" language="javascript">
		<!--
		function click() {
			if (event.button==2) {
				alert ('Contenido Protegido, ©Concreto Lanzado de Fresnillo S.A. de C.V.');
			}
		}
		function habilitarCheck(){
			window.opener.document.getElementById("ckb_catalogoBonos").checked = false;
			window.opener.document.getElementById("ckb_catalogoBonos").style.visibility = "visible";
		}
		//-->
	</script>
	
	<style type="text/css">
		<!--
		#tabla-resultados{position:absolute; overflow:scroll; width:90%; height:410px;}
		#botones{position:absolute; left:30px;top:569px; width:90%;}
		-->
	</style>
	
</head>
<body onunload="habilitarCheck()">
	<?php 
	if (isset($_POST["sbt_asignar"]) || isset($_POST["sbt_quitar"])){
		$sueldo=str_replace(",","",$_GET["sueldoBase"]);
		if ($_POST["hdn_accion"]=="Add"){
			$bono=str_replace(",","",$_POST["txt_bono"]);
			$bono=$bono*($_GET["pctje"]/100);
			$numbonoTotal=$bono+$sueldo;
			//Variable que tendra el valor del Bono de Metros
			$bonoMetros=0;
			//Obtener el valor que le corresponderia al otro puesto del area con el bono asignado siempre y cuando el avance sea mayor a 18
			if($_POST["hdn_avance"]>18){
				$pctjeAct=$_GET["pctje"];
				//Sumar el Sueldo mas el Total de TODO el Bono (aun cuando no se seleccionen todos)
				$sumaBono=$sueldo+($_POST["hdn_acumulado"]*($pctjeAct/100));
				//Obtener la cantidad de Metros avanzados
				$cantMetrosAvance=$_POST["hdn_avance"]-18;
				//Conectarse a la BD de Desarrollo
				$conn=conecta("bd_desarrollo");
				//Sentencia SQL para extraer los porcentajes por Actividades y por Metros
				$sql_stm="SELECT sueldo_base,pctje_inc_act,pctje_inc_mts FROM catalogo_salarios WHERE area='$_POST[hdn_area]' AND puesto!='$_POST[hdn_puesto]'";
				//Ejecutar la sentencia SQL
				$rs=mysql_query($sql_stm);
				//Si se encontraron resultados proceder de los contrario cerrar la conexion
				if($datos=mysql_fetch_array($rs)){
					do{
						//Obtener el porcentaje por actividad
						$pctjeAct=$datos["pctje_inc_act"];
						//Incrementar la suma de los Bonos
						$sumaBono+=$datos["sueldo_base"]+($_POST["hdn_acumulado"]*($pctjeAct/100));
					}while($datos=mysql_fetch_array($rs));
				}
				else{
					$sumaBono=$sueldo+$_POST["hdn_acumulado"];
				}
				//Cerrar la conexion con la BD
				mysql_close($conn);
				//Obtener el costo por Metro
				$costoXMetro=round($sumaBono/18,2);
				//Obtener el Total del bono por metro
				$bonoMetros=$cantMetrosAvance*$costoXMetro;
				//Obtener el porcentaje correspondiente por Metro 
				$pctjeMetros=obtenerDatoBicondicional("bd_desarrollo","catalogo_salarios","pctje_inc_mts", "area", $_POST["hdn_area"], "puesto", $_POST["hdn_puesto"]);
				//Calcular el bono por Metros correspondiente
				$bonoMetros=$bonoMetros*($pctjeMetros/100);
			}
			//Sumar el Bono de Metros al Bono Total
			$numbonoTotal+=$bonoMetros;
			?>
			<script type="text/javascript" language="javascript">
				window.opener.document.getElementById("txt_bonoMetros").value = "<?php echo number_format($bonoMetros,2,".",",");?>";
				window.opener.document.getElementById("txt_bono").value = "<?php echo number_format($bono,2,".",",");?>";
				window.opener.document.getElementById("ckb_catalogoBonos").checked = false;
				window.opener.document.getElementById("ckb_catalogoBonos").style.visibility = "visible";
				window.opener.document.getElementById("txt_bono").title = "Total Correspondiente del Total del Bono según el Puesto";
				window.opener.document.getElementById("txt_sueldoTotal").title = "Total del Sueldo calculado mediante el Sueldo Base, el Bono por Actividades y el Bono por Metros";
				window.opener.document.getElementById("txt_sueldoTotal").value="<?php echo number_format($numbonoTotal,2,".",",");?>";
				<?php if($_POST["hdn_avance"]>18){?>
					window.opener.document.getElementById("etiqMetrosAvance").innerHTML="Avance de <?php echo $_POST["hdn_avance"]?> Mts, Bono Calculado Sobre <?php echo $_POST["hdn_avance"]-18?> Mts";
				<?php }else{?>
					window.opener.document.getElementById("etiqMetrosAvance").innerHTML="Avance de <?php echo $_POST["hdn_avance"]?> Mts, NO se puede Registrar Bono de Avance por Metros";
				<?php }?>
				window.opener.focus();
				window.close();
			</script>
			<?php
		}
		else{
			?>
			<script type="text/javascript" language="javascript">
				window.opener.document.getElementById("txt_bonoMetros").value = "";
				window.opener.document.getElementById("txt_bono").value = "Click Bono ->";
				window.opener.document.getElementById("ckb_catalogoBonos").checked = false;
				window.opener.document.getElementById("ckb_catalogoBonos").style.visibility = "visible";
				window.opener.document.getElementById("txt_sueldoTotal").value="<?php echo number_format($sueldo,2,".",",");?>";
				window.opener.document.getElementById("etiqMetrosAvance").innerHTML="";
				window.opener.focus();
				window.close();
			</script>
			<?php
		}
	}
	else{
			$area=$_GET["area"];
			$puesto=$_GET["puesto"];
			$nombre=$_GET["nombre"];
			$fechaI=modFecha($_GET["fechaI"],3);
			$fechaF=modFecha($_GET["fechaF"],3);
			$conn=conecta("bd_desarrollo");
			$stm_sql="SELECT estandar,concepto,costo FROM incentivos_actividades JOIN detalle_incentivos ON id_incentivo=incentivos_actividades_id_incentivo WHERE area='$area' ORDER BY estandar";
			
			$mensaje="&Aacute;rea: <u class='msje_correcto'>$area</u> Puesto: <u class='msje_correcto'>$puesto</u> Nombre: <u class='msje_correcto'>$nombre</u>";
			
			//Verificar el area con JUMBO
			if ($area=="JUMBO"){
				
				//Crear la sentencia SQL con las tablas de Barrenacion Jumbo, Personal, Avance
				$stm_sql2="SELECT id_bitacora,personal.bitacora_avance_id_bitacora,puesto,nombre,turno,barrenos_dados,barrenos_disp,barrenos_long,reanclaje,broca_nva,broca_afil,
							coples,zancos,anclas,barrenacion_jumbo.observaciones AS obsJum,avance,bitacora_avance.observaciones AS obsAva 
							FROM personal JOIN barrenacion_jumbo ON personal.bitacora_avance_id_bitacora=barrenacion_jumbo.bitacora_avance_id_bitacora 
							JOIN bitacora_avance ON personal.bitacora_avance_id_bitacora=id_bitacora WHERE area='$area' AND fecha>='$fechaI' AND fecha<='$fechaF' AND nombre='$nombre' AND puesto='$puesto'";
				//Ejecutar la sentencia SQL
				$resultado=mysql_query($stm_sql2);
				//Variables acumulativas
				$barrenos_dados=0;
				$barrenos_disp=0;
				$barrenos_long=0;
				$barrenos_desborde=0;
				$barrenos_encapille=0;
				$barrenos_despate=0;
				$reanclaje=0;
				$broca_nva=0;
				$broca_afil=0;
				$coples=0;
				$zancos=0;
				$anclas=0;
				$avance=0;
				$obsBit="";
				$obsAva="";
				//Verificar resultados
				if ($jumbo=mysql_fetch_array($resultado)){
					do{	
						$barrenos_dados+=$jumbo["barrenos_dados"];
						$barrenos_disp+=$jumbo["barrenos_disp"];
						$barrenos_long+=$jumbo["barrenos_long"];
						//Verificar si hay barrenos de Desborde registrados para acumularlos
						$desborde=obtenerDatoBicondicional("bd_desarrollo", "barrenos", "desborde", "bitacora_avance_id_bitacora", $jumbo["id_bitacora"],"area",$area);
						if ($desborde!="")
							$barrenos_desborde+=$desborde;
						//Verificar si hay barrenos de Encapille registrados para acumularlos
						$encapille=obtenerDatoBicondicional("bd_desarrollo", "barrenos", "encapille", "bitacora_avance_id_bitacora", $jumbo["id_bitacora"],"area",$area);
						if ($encapille!="")
							$barrenos_encapille+=$encapille;
						//Verificar si hay barrenos de Despate registrados para acumularlos
						$despate=obtenerDatoBicondicional("bd_desarrollo", "barrenos", "despate", "bitacora_avance_id_bitacora", $jumbo["id_bitacora"],"area",$area);
						if ($despate!="")
							$barrenos_despate+=$despate;
						$reanclaje+=$jumbo["reanclaje"];
						$broca_nva+=$jumbo["broca_nva"];
						$broca_afil+=$jumbo["broca_afil"];
						$coples+=$jumbo["coples"];
						$zancos+=$jumbo["zancos"];
						$anclas+=$jumbo["anclas"];
						$avance+=$jumbo["avance"];
						if ($jumbo["obsJum"]!="")
							$obsBit.=$jumbo["obsJum"].",";
						if ($jumbo["obsAva"]!="")
							$obsAva.=$jumbo["obsAva"].",";
					}while($jumbo=mysql_fetch_array($resultado));
					$mensaje.="<br> 
					Avance: <u class='msje_correcto'>$avance MTS</u> 
					Barrenos Dados: <u class='msje_correcto'>$barrenos_dados</u> 
					Barrenos Disp: <u class='msje_correcto'>$barrenos_disp</u> 
					Barrenos Long: <u class='msje_correcto'>$barrenos_long</u>
					Barrenos Desborde: <u class='msje_correcto'>$barrenos_desborde</u> 
					Barrenos Encapille: <u class='msje_correcto'>$barrenos_encapille</u> 
					Barrenos Despate: <u class='msje_correcto'>$barrenos_despate</u>
					Reanclaje: <u class='msje_correcto'>$reanclaje</u> 
					Brocas Nuevas: <u class='msje_correcto'>$broca_nva</u> 
					Brocas Afiladas: <u class='msje_correcto'>$broca_afil</u> 
					Coples: <u class='msje_correcto'>$coples</u> 
					Zancos: <u class='msje_correcto'>$zancos</u> 
					Anclas: <u class='msje_correcto'>$anclas</u>
					";
				}
				else{
					$mensaje.="<br>
					El Trabajador <u class='msje_correcto'>NO</u> Tiene Avance con los Datos Especificados, 
					se Recomienda Verificar el <u class='msje_correcto'>PUESTO</u> y las <u class='msje_correcto'>FECHAS</u>";
				}
			}//Fin de if ($area=="JUMBO")
			
			//Verificar el area con SCOOP
			if ($area=="SCOOP"){
				//Crear la sentencia SQL con las tablas de Barrenacion Jumbo, Personal y Avance
				$stm_sql2="SELECT personal.bitacora_avance_id_bitacora,puesto,nombre,turno,min_cuch,tep_cuch,rezagado.observaciones AS obsST,avance,bitacora_avance.observaciones AS obsAva 
							FROM personal JOIN rezagado ON personal.bitacora_avance_id_bitacora=rezagado.bitacora_avance_id_bitacora JOIN bitacora_avance ON personal.bitacora_avance_id_bitacora=id_bitacora 
							WHERE area='$area' AND fecha>='$fechaI' AND fecha<='$fechaF' AND nombre='$nombre' AND puesto='$puesto'";
				//Ejecutar la sentencia SQL
				$resultado=mysql_query($stm_sql2);
				//Variables acumulativas
				$avance=0;
				$cuch_min=0;
				$cuch_tep=0;
				$obsBit="";
				$obsAva="";
				//Verificar resultados
				if ($st=mysql_fetch_array($resultado)){
					do{	
						$avance+=$st["avance"];
						$cuch_min+=$st["min_cuch"];
						$cuch_tep+=$st["tep_cuch"];
						if ($st["obsST"]!="")
							$obsBit.=$st["obsST"].",";
						if ($st["obsAva"]!="")
							$obsAva.=$vol["obsAva"].",";
					}while($st=mysql_fetch_array($resultado));
					$mensaje.="<br> 
					Avance: <u class='msje_correcto'>$avance MTS</u> 
					Cucharones Mineral: <u class='msje_correcto'>$cuch_min</u> 
					Cucharones Tepetate: <u class='msje_correcto'>$cuch_tep</u> 
					";
				}
				else{
					$mensaje.="<br>
					El Trabajador <u class='msje_correcto'>NO</u> Tiene Avance con los Datos Especificados, 
					se Recomienda Verificar el <u class='msje_correcto'>PUESTO</u> y las <u class='msje_correcto'>FECHAS</u>";
				}
			}//Fin de if ($area=="SCOOP")
			
			//Verificar el area con VOLADURAS
			if ($area=="VOLADURAS"){
				//Crear la sentencia SQL con las tablas de Barrenacion Jumbo, Personal y Avance
				$stm_sql2="SELECT id_bitacora,personal.bitacora_avance_id_bitacora,puesto,nombre,turno,long_barreno_carg,factor_carga,voladuras.observaciones AS obsVol,
							avance,bitacora_avance.observaciones AS obsAva 
							FROM personal JOIN voladuras ON personal.bitacora_avance_id_bitacora=voladuras.bitacora_avance_id_bitacora JOIN bitacora_avance ON personal.bitacora_avance_id_bitacora=id_bitacora 
							WHERE area='VOLADURAS' AND fecha>='$fechaI' AND fecha<='$fechaF' AND nombre='$nombre' AND puesto='$puesto'";
				//Ejecutar la sentencia SQL
				$resultado=mysql_query($stm_sql2);
				//Variables acumulativas
				$avance=0;
				$barrenos_desborde=0;
				$barrenos_encapille=0;
				$barrenos_despate=0;
				$long_barreno=0;
				$factor_carga=0;
				$obsBit="";
				$obsAva="";
				//Verificar resultados
				if ($vol=mysql_fetch_array($resultado)){
					do{	
						$avance+=$vol["avance"];
						//Verificar si hay barrenos de Desborde registrados para acumularlos
						$desborde=obtenerDatoBicondicional("bd_desarrollo", "barrenos", "desborde", "bitacora_avance_id_bitacora", $vol["id_bitacora"],"area",$area);
						if ($desborde!="")
							$barrenos_desborde+=$desborde;
						//Verificar si hay barrenos de Encapille registrados para acumularlos
						$encapille=obtenerDatoBicondicional("bd_desarrollo", "barrenos", "encapille", "bitacora_avance_id_bitacora", $vol["id_bitacora"],"area",$area);
						if ($encapille!="")
							$barrenos_encapille+=$encapille;
						//Verificar si hay barrenos de Despate registrados para acumularlos
						$despate=obtenerDatoBicondicional("bd_desarrollo", "barrenos", "despate", "bitacora_avance_id_bitacora", $vol["id_bitacora"],"area",$area);
						if ($despate!="")
							$barrenos_despate+=$despate;
							
						$long_barreno+=$vol["long_barreno_carg"];
						$factor_carga+=$vol["factor_carga"];
						if ($vol["obsVol"]!="")
							$obsBit.=$vol["obsVol"].",";
						if ($vol["obsAva"]!="")
							$obsAva.=$vol["obsAva"].",";
					}while($vol=mysql_fetch_array($resultado));
					$mensaje.="<br> 
					Avance: <u class='msje_correcto'>$avance MTS</u> 
					Barrenos Desborde: <u class='msje_correcto'>$barrenos_desborde</u> 
					Barrenos Encapille: <u class='msje_correcto'>$barrenos_encapille</u> 
					Barrenos Despate: <u class='msje_correcto'>$barrenos_despate</u>
					Longitud Total de Barrenos Cargados: <u class='msje_correcto'>$long_barreno</u> 
					Factor Acumulado de Carga Total: <u class='msje_correcto'>$factor_carga</u> 
					";
				}
				else{
					$mensaje.="<br>
					El Trabajador <u class='msje_correcto'>NO</u> Tiene Avance con los Datos Especificados, 
					se Recomienda Verificar el <u class='msje_correcto'>PUESTO</u> y las <u class='msje_correcto'>FECHAS</u>";
				}
			}//Fin de if ($area=="VOLADURAS")
		?>
		<label class="titulo_etiqueta">Seleccione las Actividades a Bonificar</label>
		<br />
		<table class="tabla_frm" width="100%">
			<tr>
				<td><label><?php echo $mensaje;?></label></td>
			</tr>				
		</table>
		<form onSubmit="return valFormBonoEspecial(this);" name="frm_bonoNomina" method="post" action="">
		<input type="hidden" name="hdn_obsBit" id="hdn_obsBit" value="<?php echo $obsBit ?>"/>
		<input type="hidden" name="hdn_obsAva" id="hdn_obsAva" value="<?php echo $obsAva ?>"/>
		<table width="100%" class="tabla_frm">
			<tr>
				<td align="right"><strong>Consultar <u>Observaciones</u></strong><input type="radio" name="rdb_obs" id="rdb_obs" title="Consultar las Observaciones Generadas en las Bit&aacute;coras" onclick="consultarObservaciones(this,hdn_obsBit.value,hdn_obsAva.value,'<?php echo $area;?>');"/></td>
				<td align="right" class="nombres_columnas" width="30%">TOTAL BONO $<input type="text" class="caja_de_num" readonly="readonly" size="10" name="txt_bono" id="txt_bono" value="0.00"/></td>
			</tr>
		</table>
		<div id='tabla-resultados' class="borde_seccion2">
		<table class="tabla_frm" cellpadding="5" cellspacing="5" width="100%">
			<tr>
				<td class='nombres_columnas' align='center'>EST&Aacute;NDAR</td>
				<td class='nombres_columnas' width="15%" align='center'>ASIGNAR<input type="checkbox" id="ckb_sumaTodo" name="ckb_sumaTodo" onclick="checarTodos(this);sumarTodosBono(this);"/></td>
				<td class='nombres_columnas' align='center'>ACTIVIDAD</td>
				<td class='nombres_columnas' align='center'>COSTO</td>
			</tr>
			<?php
				$rs=mysql_query($stm_sql);
				if ($datos=mysql_fetch_array($rs)){
					$nom_clase = "renglon_gris";
					$cont = 1;
					$acumulado=0;
					do{
						echo "	<tr>";
						echo "
								<td class='$nom_clase' align='center'>$datos[estandar]</td>";
								?>
								<td class='<?php echo $nom_clase;?>' align='center'><input type="checkbox" name="ckb_actividad<?php echo $cont;?>" id="ckb_actividad<?php echo $cont;?>" value="<?php echo $datos["costo"];?>" onclick="sumaBono(this);"/>
								<?php
						echo "
								<td class='$nom_clase' align='center'>$datos[concepto]</td>
								<td class='$nom_clase' align='center'>$".number_format($datos["costo"],2,".",",")."</td>
								</tr>";
						//Determinar el color del siguiente renglon a dibujar
						$cont++;
						if($cont%2==0)
							$nom_clase = "renglon_blanco";
						else
							$nom_clase = "renglon_gris";
						$acumulado=$acumulado+$datos["costo"];
					}while($datos=mysql_fetch_array($rs));
					$cant_ckbs=$cont;
				}
				else{
					$cant_ckbs=0;
				}
			?>
		</table>
		</div>
		<div id="botones" align="center">
			<input type="hidden" name="cant_ckbs" id="cant_ckbs" value="<?php echo $cant_ckbs?>"/>
			<input type="hidden" name="hdn_avance" id="hdn_avance" value="<?php echo $avance?>"/>
			<input type="hidden" name="hdn_acumulado" id="hdn_acumulado" value="<?php echo $acumulado;?>"/>
			<input type="hidden" name="hdn_puesto" id="hdn_puesto" value="<?php echo $puesto?>" />
			<input type="hidden" name="hdn_area" id="hdn_area" value="<?php echo $area?>" />
			<input type="hidden" name="hdn_accion" value=""/>
			<input type="submit" name="sbt_asignar" id="sbt_asignar" class="botones" value="Asignar Bono" title="Asignar la Bonificaci&oacute;n al Empleado" onclick="hdn_accion.value='Add'"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name="sbt_quitar" id="sbt_quitar" class="botones" value="Quitar Bonos" title="Quitar la Bonificaci&oacute;n al Empleado" onclick="hdn_accion.value='Del'"/>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" class="botones" value="Cancelar" title="Cancelar y Cerrar la Ventana" onclick="habilitarCheck();window.close();"/>
		</div>
		</form>
	<?php
	}
	?>
</body>
</html>