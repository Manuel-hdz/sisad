<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<?php
	//Comprobar que la sesion aun sigue abierta
	include ("../seguridad.php"); 
	//Comprobar que el usuario registrado tenga acceso a esta seccion del Módulo de Laboratorio
	if(!verificarPermiso($usr_reg,$_SERVER['PHP_SELF'])){	
		//Enviar a la pagina de acceso negado
		echo "<meta http-equiv='refresh' content='0;url=error.php?err=AccesoNegado'>";
	}
	else{
		//Este archivo proporciona todo el encabezado de las paginas y la conexion a la BD a traves del archivo conexion.inc y da acceso al archivo op_operacionesBD.php
		include ("head_menu.php");
		include ("op_registrarPruebas.php");?>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<script type="text/javascript" src="../../includes/maxLength.js" ></script>
	<script type="text/javascript" src="../../includes/validacionLaboratorio.js" ></script>
    <link href="../../includes/estilo.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
		<!--
		#titulo-registrar {position:absolute;left:30px;top:146px;width:367px;height:20px;z-index:11;}
		#tabla-registrarAgregados {	position:absolute;left:30px;top:190px;width:934px;height:239px;z-index:14;}
		#detalle {position:absolute;left:30px;top:465px;width:917px;height:177px;z-index:17;overflow:scroll;}
		-->
    </style>
</head>
<body><?php 
	//Comprobamos si existe en el GET la variable noRegistro si es asi quiere decir que se presiono la imagen-boton borrar
	if(isset($_GET["noRegistro"])){
		//Si es asi liberar la sesion
		unset($_SESSION["pruebas"][$_GET["noRegistro"]]);
		//Verificamos que exista la session y el parametro que contiene la ubicacion del registro dentro de la sesion
		if(isset($_SESSION["pruebas"])&&isset($_GET["noRegistro"]))
			//Reacomodamos el Arreglo
			$_SESSION['pruebas'] = array_values($_SESSION['pruebas']);
	
		//Verificamos si exista la sesion
		if(isset($_SESSION["pruebas"])){
			//Si el arreglo de lista_maestra esta vacio, retirarlo de la SESSION
			if(count($_SESSION["pruebas"])==0){
				//Liberamos la sesion
				unset($_SESSION["pruebas"]);
			}
		}
		
	}//Cierre if(isset($_GET["noRegistro"]))
	
	if(isset($_GET['btn_finalizarAgregados2']))
		guardarPruebasAgregados();

	//el boton sbt_continuarAg proviene del formulario frm_registrarAgregados, por lo tanto proceder a guardar los datos en el arreglo de session
	if(isset($_POST['sbt_continuarAg'])){
		//Obtener el id de la prueba de agregados
		$idPruebaAgregados=obtenerIdPruebaAgregados();
		
		$origenMat = strtoupper($_POST["txt_origen"]);
		
		$pvss= str_replace(",","",$_POST["txt_pvss"]);	
		
		$pvsc= str_replace(",","",$_POST["txt_pvsc"]);	
		
		$densidad= str_replace(",","",$_POST["txt_densidad"]);	
		
		$absorcion= str_replace(",","",$_POST["txt_absorcion"]);	
			
		$finura= str_replace(",","",$_POST["txt_finura"]);	
			
		$fecha = $_POST["txt_fecha"];
		
		$granulometria= strtoupper($_POST['txt_granulometria']);
		$granulometria= str_replace(",","",$granulometria);	
		
		$wmPvss= str_replace(",","",$_POST["txt_wmPvss"]);	
		
		$wmPvsc= str_replace(",","",$_POST["txt_wmPvsc"]);	
		
		$msssDensidad= str_replace(",","",$_POST["txt_msssDensidad"]);	

		$msssAbsorcion= str_replace(",","",$_POST["txt_msssAbosrcion"]);	

		$vmPvss= str_replace(",","",$_POST["txt_vmPvss"]);	

		$vmPvsc= str_replace(",","",$_POST["txt_vmPvsc"]);	

		$va= str_replace(",","",$_POST["txt_va"]);	

		$ws= str_replace(",","",$_POST["txt_ws"]);

		$pl= str_replace(",","",$_POST["txt_pl"]);
		
		$wspl= str_replace(",","",$_POST["txt_wspl"]);
		
		$wsc= str_replace(",","",$_POST["txt_wsc"]);

		$cmb_pruebaEjecutada= $_POST["cmb_pruebaEjecutada"];
		
		$cmb_norma= $_POST["cmb_norma"];
			
		//Añadir la informacion al arreglo de session ya exitente
		$infoAgregado= array ("origenMat"=>$origenMat, "pvss"=>$pvss, "pvsc"=>$pvsc ,"densidad"=>$densidad, 
		"absorcion"=>$absorcion, "finura"=>$finura, "fecha"=>$fecha, "granulometria"=>$granulometria, "wmPvss"=>$wmPvss, "wmPvsc"=>$wmPvsc, 
		"msssDensidad"=>$msssDensidad, "msssAbsorcion"=>$msssAbsorcion, "vmPvss"=>$vmPvss, "vmPvsc"=>$vmPvsc,"va"=>$va,"ws"=>$ws,
		"idPruebaAgregados"=>$idPruebaAgregados, "cmb_pruebaEjecutada"=>$cmb_pruebaEjecutada, "cmb_norma"=>$cmb_norma,"pl"=>$pl,"wspl"=>$wspl,"wsc"=>$wsc);
		//Guardar los datos en la SESSION
		$_SESSION['infoAgregado'] = $infoAgregado;		
	}
	
	if(isset($_SESSION['nomAgregado']))
		$nomMat=$_SESSION['nomAgregado'];
	else
		$nomMat="";
	//Verificamos que se haya pulsado el boton de agregar otro para proceder a cargar los datos en el arreglo de sesion
	if(isset($_POST['sbt_agregar'])){
		//Obtenemos el concepto que viene definido en el post
		$concepto= $_POST['cmb_concepto'];
		//Quitamos las comas del retenido
		$retenido= str_replace(",","",$_POST['txt_retenido']);
		//Obtenemos el id del material con la funcion obtenerDato()	
		$idMaterial=obtenerDato('bd_almacen', 'materiales', 'id_material','nom_material', $nomMat);
		//Conectamos con la BD de Laboratorio porque el obtener dato anterior cierra la conexion
		$conn = conecta("bd_laboratorio");
		//Obtenemos el ID de la norma segun el el valor dque se encuentra definido en la session
		$idNorma=obtenerDato('bd_laboratorio', 'catalogo_normas', 'id_norma','catalogo_materiales_id_material',$idMaterial );	
		//Obtenemos los valores de los limites los cuales ya estan regitrados en la BD
		$norma= $_SESSION['infoAgregado']["cmb_norma"];
		$limites = mysql_fetch_array(mysql_query("SELECT lim_inferior, lim_superior FROM (detalle_catalogo_normas JOIN catalogo_normas ON catalogo_normas_id_norma=id_norma)WHERE catalogo_normas_id_norma = '$idNorma' AND concepto= '$concepto' 
												AND norma='$norma'"));
		$limSup=$limites['lim_superior'];
		$limInf=$limites['lim_inferior'];

		//$limSuperior= str_replace(",","",$_POST['txt_limiteSuperior']);	
		$observaciones= strtoupper($_POST['txa_observaciones']);
		
		//Si esta definido el arreglo, añadir el siguiente elemento a el	
		if(isset($_SESSION['pruebas'])){
			//Comprobar que dentro de la tabla donde se registran servicios de mantenimiento no se observen registros duplicados dentro de dicha tabla para el mismo registro de equipo
			$regDuplicado = 0;	
			foreach($_SESSION['pruebas'] as $ind => $registro){
				$concepto= $_POST['cmb_concepto'];
				if($concepto==$registro['concepto']){
					$regDuplicado = 1;
					break;	
				}
			}
			if($regDuplicado==0){
				$pruebas[] = array ( "concepto"=>$concepto, "limInferior"=>$limInf, "fecha"=>$_POST['txt_fecha'], "retenido"=>$retenido, 
				"limSuperior"=>$limSup, "numero"=>$_POST['txt_numero'], "observaciones"=>$observaciones);
				//Guardar los datos en la SESSION
				$_SESSION['pruebas'] = $pruebas;	
			}
			else{
				//Declarar variable que va a almacenar el mensaje cuando ya exista un registro de Mtto para ese Equipo?>
				<script language="javascript" type="text/javascript">
					setTimeout("alert('El Concepto <?php echo $concepto;?> ya se Encuentra Registrado')",500);
				</script>
				<?php 
			}		
		}				
		else{//Si no esta definido el arreglo, definirlo
			//Crear el arreglo con los datos del bono
			$pruebas = array(array ("concepto"=>$concepto, "limInferior"=>$limInf, "fecha"=>$_POST['txt_fecha'], "retenido"=>$retenido,
			 "limSuperior"=>$limSup, "numero"=>$_POST['txt_numero'], "observaciones"=>$observaciones));
			//Guardar los datos en la SESSION
			$_SESSION['pruebas'] = $pruebas;
		}
	}
	
	if(!isset($_SESSION['pruebas']) || isset($_GET['btn_finalizarAgregados2']))
		$cont=1;
	else {
		if(isset($_POST['txt_numero']))
			$cont=count($_SESSION['pruebas'])+1;
	}?>
            
	<div id="barra"><img src="../../images/title-bar-bg.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-registrar">Registrar Resultados de Pruebas a Agregados </div>
    <fieldset class="borde_seccion" id="tabla-registrarAgregados" name="tabla-registrarAgregados">
    <legend class="titulo_etiqueta">Ingrese el Resultado de la Prueba</legend>	
	<br>
	<form onSubmit="return valFormRegAgregados2(this);" name="frm_registrarAgregados2" method="post" action="frm_registrarAgregados2.php">
    <table width="923" cellpadding="5" cellspacing="5" class="tabla_frm">
        <tr>
            <td width="130"><div align="right">*Agregado</div></td>
            <td colspan="2"><input type="text" name="txt_agregado" id="txt_agregado" value="<?php echo $nomMat;?>" size="40" readonly="readonly"/> </td>
            <td width="101"><div align="right">*Fecha</div></td>
            <td width="122"><input name="txt_fecha" id="txt_fecha" type="text" class="caja_de_texto" size="10"
                value="<?php echo date("d/m/Y", strtotime("-30 day"));?>" readonly="readonly"/>            </td>
            <td width="110"><div align="right">*N&uacute;mero</div></td>
            <td width="39"><input type="text" name="txt_numero" id="txt_numero" value="<?php echo $cont;?>" readonly="readonly"size="5"/></td>
        </tr>        
        <tr>
            <td width="130"><div align="right">*Concepto</div></td> 
          <td width="172"><?php
		  				$norma=$_SESSION['infoAgregado']['cmb_norma'];
						$idMaterial=obtenerDato('bd_almacen', 'materiales', 'id_material','nom_material', $nomMat=$_SESSION['nomAgregado']); 
						$cmb_concepto="";
						$conn = conecta("bd_laboratorio");
						$result=mysql_query("SELECT DISTINCT concepto FROM (detalle_catalogo_normas JOIN catalogo_normas ON catalogo_normas_id_norma=id_norma) WHERE 
											catalogo_materiales_id_material='$idMaterial' AND norma='$norma'");
						if($conceptos=mysql_fetch_array($result)){?>
           	 <select name="cmb_concepto" id="cmb_concepto" size="1" class="combo_box">
              <option value="">Conceptos</option>
              <?php 
									do{
										if ($conceptos['concepto'] == $conceptos){
											echo "<option value='$conceptos[concepto]' selected='selected'>$conceptos[concepto]</option>";
										}
										else{
											echo "<option value='$conceptos[concepto]'>$conceptos[concepto]</option>";
										}
									}while($conceptos=mysql_fetch_array($result)); 
							//Cerrar la conexion con la BD		
							mysql_close($conn);?>
            </select>
            <?php
						 }
						else{
							echo "<label class='msje_correcto'> No hay Conceptos Registrados para la Norma Seleccionada</label>
							<input type='hidden' name='cmb_concepto' id='cmb_concepto'/>";
				  		}?></td>
            <td width="137"><div align="right">*Retenido</div></td> 
            <td><input type="text" name="txt_retenido" id="txt_retenido" value="" onkeypress="return permite(event,'num',1);" onchange="formatCurrency(value,'txt_retenido')" size="15"/>            </td>
            <td><div align="right">Observaciones</div></td> 
            <td colspan="2">
                <textarea name="txa_observaciones" id="txa_observaciones" maxlength="120" onkeyup="return ismaxlength(this)" cols="30" rows="2" class="caja_de_texto" 
                onkeypress="return permite(event,'num_car',0);"></textarea>          </td>
        </tr>                
        <tr>
            <td colspan="7"><strong>* Datos marcados con asterisco son <u>obligatorios</u></strong></td>
        </tr>
        <tr>
            <td colspan="7"><div align="center">
                <input name="sbt_agregar" type="submit" class="botones" id="sbt_agregar"  value="Agregar" title="Agregar otra Prueba" 
                onmouseover="window.status='';return true" />
                &nbsp;&nbsp;&nbsp;<?php
                if (isset($_SESSION['pruebas'])){?>
                    <input name="btn_finalizar" type="button" class="botones" id="btn_finalizar"  value="Finalizar" title="Finalizar Registro de Pruebas" 
                    onmouseover="window.status='';return true" onclick="location.href='frm_registrarAgregados2.php?btn_finalizarAgregados2'"/>
                    &nbsp;&nbsp;&nbsp;<?php
                }?>
                <input name="rst_limpiar" type="reset" class="botones" id="rst_limpiar"  value="Limpiar" title="Limpiar Formulario" 
                onmouseover="window.status='';return true"/>
				&nbsp;&nbsp;&nbsp;
          		<input name="btn_regresarAg" type="button" class="botones" id="btn_regresarAg"  value="Regresar" title="Regresar a Reingresar Datos" 
                onmouseover="window.status='';return true" onclick="location.href='frm_registrarAgregados.php?regresar'" />
                &nbsp;&nbsp;&nbsp;
                <input name="btn_cancelar" type="button" class="botones" id="btn_cancelar" value="Cancelar" title="Cancelar y Regresar al Men&uacute; de Mezclas " 
                onmouseover="window.status='';return true" onclick="confirmarSalida('menu_mezclas.php');"/></div>          </td>   
        </tr>
    </table>
    </form>
    </fieldset><?php
	if (isset($_SESSION['pruebas'])){?>
        <div id='detalle' class='borde_seccion2' align="center"><?php
            $control=mostrarResultados();?>
        </div><?php
	}?>	
</body>
<?php }//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>