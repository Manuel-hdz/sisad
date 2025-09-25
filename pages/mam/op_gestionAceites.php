<?php
	/**
	  * Nombre del Módulo: Mantenimiento
	  * Nombre Programador: Antonio de Jesús Jiménez Cuevas
	  * Fecha: 03/Agosto/2012
	  * Descripción: Este archivo contiene funciones para Consultar los Aceites y el Catálogo de la BD de Mantenimiento
	**/

	//Esta funcion Muestra los Aceites del Catálogo
	function mostrarAceites(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		
		//Creamos la sentencia SQL para mostrar los datos del Equipo
		$stm_sql="SELECT nom_aceite,cantidad FROM catalogo_aceites_mina";
		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		
		if ($datos=mysql_fetch_array($rs)){
			echo "<table class='tabla_frm' cellpadding='5' width='80%'>";
			echo "<caption class='titulo_etiqueta'>Cat&aacute;logo de Aceites</caption>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>NO.</td>
						<td class='nombres_columnas' align='center'>NOMBRE ACEITE</td>
						<td class='nombres_columnas' align='center'>CANTIDAD DE ACEITE</td>
					</tr>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$estado="";
			do{	
				$estilo="";
				if($datos["cantidad"]==0)
					$estilo="style='color:#FF0000'";
				echo "	<tr>					
						<td class='nombres_filas' align='center'>$cont</td>
						<td class='$nom_clase' align='center'>$datos[nom_aceite]</td>					
						<td class='$nom_clase' align='center' $estilo>".number_format($datos["cantidad"],2,".",",")." LTS</td>
						</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			echo "</table>";
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de la funcion para mostrar el Catálogo de Aceites
	
	//Esta funcion se encargar de Actualizar el catálogo de Aceites
	function guardarActualizacionAceite(){
		//Obtener el ID para la bitacora de Aceite
		$idBitacora=obtenerIdBitacoraAceite();
		//Fecha
		$fecha=date("Y-m-d");
		//Verificar si es un registro Nuevo o una actualizacion mediante el combo del formulario
		//si esta deshabilitado, es un registro nuevo, de lo contrario es una actualizacion
		if(isset($_POST["cmb_aceite"])){
			//Obtener la cantidad de Incremento
			$inc=str_replace(",","",$_POST["txt_incremento"]);
			//Obtener el ID del Aceite
			$idAceite=$_POST["cmb_aceite"];
			//Sentencia para actualizar la cantidad de Aceite en "STOCK"
			$sql_stm_ins="UPDATE catalogo_aceites_mina SET cantidad=cantidad+$inc WHERE id_aceite='$idAceite'";
			//Sentencia para ingresar el Registro del incremento a la Bitácora
			$sql_stm_act="INSERT INTO bitacora_aceite_mina (id_bitacora,catalogo_aceites_id_aceite,equipos_id_equipo,fecha,tipo_mov,cantidad,equipo) VALUES ('$idBitacora','$idAceite','','$fecha','E','$inc','N/A')";
		}
		else{
			//Obtener el ID del Aceite
			$idAceite=obtenerIDAceite();
			//Obtener el nombre del Aceite
			$nomAceite=strtoupper($_POST["txt_nuevoAceite"]);
			//Obtener la cantidad de Aceite
			$cantidad=strtoupper($_POST["txt_cantidad"]);
			//Sentencia para actualizar el catálogo de Aceites
			$sql_stm_ins="INSERT INTO catalogo_aceites_mina (id_aceite,nom_aceite,cantidad) VALUES ('$idAceite','$nomAceite','$cantidad')";
			//Sentencia para ingresar el Registro del incremento a la Bitácora
			$sql_stm_act="INSERT INTO bitacora_aceite_mina (id_bitacora,catalogo_aceites_id_aceite,equipos_id_equipo,fecha,tipo_mov,cantidad,equipo) VALUES ('$idBitacora','$idAceite','','$fecha','I','$cantidad','N/A')";
		}
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Ejecutar la sentencia de catalogo de aceites
		$rs=mysql_query($sql_stm_ins);
		//Ejecutar la sentencia de bitacora de aceite
		$rs=mysql_query($sql_stm_act);
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
		//Registrar el movimiento en la bitácora de Movimientos
		registrarOperacion("bd_mantenimiento","$idBitacora","RegistroBitacoraAceite",$_SESSION['usr_reg']);
	}//Fin de function guardarActualizacionAceite()
	
	//Funcion que calcula el id para la bitacora de Aceites
	function obtenerIdBitacoraAceite(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Sentencia SQl para extraer el id
		$sql="SELECT MAX(id_bitacora)+1 AS id FROM bitacora_aceite_mina";
		//Ejecutar la sentencia
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos["id"]==NULL)
				$id=1;
			else
				$id=$datos["id"];
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
		//Retornar el ID calculado
		return $id;
	}//Fin de obtenerIdBitacoraAceite()
	
	//Funcion que calcula el id para la bitacora de Aceites
	function obtenerIDAceite(){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Sentencia SQl para extraer el id
		$sql="SELECT MAX(id_aceite)+1 AS id FROM catalogo_aceites_mina";
		//Ejecutar la sentencia
		$rs=mysql_query($sql);
		if($datos=mysql_fetch_array($rs)){
			if($datos["id"]==NULL)
				$id=1;
			else
				$id=$datos["id"];
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
		//Retornar el ID calculado
		return $id;
	}//Fin de obtenerIdBitacoraAceite()
	
	//Funcion que muestra los Equipos para registrarles su consumo de aceite
	function mostrarEquipos($fecha){
		//Abrimos la conexion con la Base de datos
		$conn=conecta("bd_mantenimiento");
		//Extraer los aceites del catalogo
		$rs_aceites = mysql_query("SELECT id_aceite,nom_aceite,cantidad FROM catalogo_aceites_mina WHERE cantidad>0 ORDER BY nom_aceite");
		//Cantidad de Aceites
		$cantAceites=mysql_num_rows($rs_aceites);
		//Extraer los Aceites
		if($aceites=mysql_fetch_array($rs_aceites)){
			do{
				$idAceite[]=$aceites["id_aceite"];
				$nomAceite[]=$aceites["nom_aceite"];
				$cantAceite[$aceites["id_aceite"]]=$aceites["cantidad"];
			}while($aceites=mysql_fetch_array($rs_aceites));
		}
		//Creamos la sentencia SQL para mostrar los datos del Equipo
		$stm_sql="SELECT id_equipo,nom_equipo FROM equipos WHERE area='MINA'";		
		//Ejecutamos la sentencia SQL
		$rs=mysql_query($stm_sql);
		//Cantidad de Equipos
		$cantEquipos=mysql_num_rows($rs);
		//Extraer los datos de los Equipos
		if ($datos=mysql_fetch_array($rs)){
			echo "<br>";
			echo "<table class='tabla_frm' cellpadding='5' width='100%'>";
			echo "<caption class='titulo_etiqueta'>Registrar Consumo de Aceites del <em><u>$fecha</em></u> Turno: <em><u>$_POST[cmb_turno]</em></u> Supervisor: <em><u>$_POST[cmb_supervisor]</em></u></caption><br>";
			echo "	<tr>
						<td class='nombres_columnas' align='center' colspan='2'>SELECCIONAR</td>
						<td class='nombres_columnas' align='center' rowspan='2'>ID EQUIPO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>NOMBRE EQUIPO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>ACEITE CONSUMIDO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>CANTIDAD DE ACEITE CONSUMIDO</td>
						<td class='nombres_columnas' align='center' rowspan='2'>COMENTARIOS</td>
					</tr>";
			echo "	<tr>
						<td class='nombres_columnas' align='center'>No.</td>
						<td class='nombres_columnas' align='center'>REGISTRAR</td>
					</tr>";
			echo "<input type='hidden' value='$fecha' name='hdn_fecha'/>";
			$nom_clase = "renglon_gris";
			$cont = 1;	
			$estado="";
			do{	
				echo "	<tr>					
						<td class='$nom_clase' align='center'>$cont.-</td>";
						?>
						<td class="<?php echo $nom_clase;?>">
							<input type="checkbox" name="ckb_equipo<?php echo $cont?>" id="ckb_equipo<?php echo $cont?>" value="<?php echo $datos["id_equipo"];?>" onclick="activarDesactivarRegBitAceiteMina(this,'<?php echo $cont?>');"/>
						</td>
						<?php
				echo "
						<td class='$nom_clase' align='center'>$datos[id_equipo]</td>
						<td class='$nom_clase' align='center'>$datos[nom_equipo]</td>";
						
						if($cont==1 && $cantAceites==0){
						?>
							<td class="<?php echo $nom_clase;?>" align="center" rowspan="<?php echo $cantEquipos;?>">
								<span onclick="location.href='frm_gestionAceites.php'" title="Click para ir a la Secci&oacute;n de Aceites" class="msje_correcto" style="cursor:pointer">NO Tiene Aceites en Existencia o NO se Encuentran Registrados</span>
							</td>
						<?php }
						if($cantAceites>0){
						?>
							<td class="<?php echo $nom_clase;?>" align="center">
								<select name="cmb_aceite<?php echo $cont?>" id="cmb_aceite<?php echo $cont?>" class="combo_box" disabled="disabled" onblur="valRegistrarAceites(this,txt_cantidad<?php echo $cont?>,'<?php echo $cont?>');">
									<option value="">Aceites</option>
									<?php
									foreach($idAceite as $ind => $value)
										echo "<option value='$value' title='ID: $value Nombre: $nomAceite[$ind] Existencia: $cantAceite[$value] LTS'>$nomAceite[$ind]</option>";
									?>
								</select>
							</td>
							<td class="<?php echo $nom_clase;?>">
								<input name="txt_cantidad<?php echo $cont?>" id="txt_cantidad<?php echo $cont?>" type="text" class="caja_de_num" size="15" maxlength="10" onkeypress="return permite(event,'num',2);"
								onchange="formatCurrency(value,'txt_cantidad<?php echo $cont?>');" readonly="readonly" onblur="valRegistrarAceites(cmb_aceite<?php echo $cont?>,this,'<?php echo $cont?>');"/> LTS
							</td>
						<?php
						}
						else{?>
							<td class="<?php echo $nom_clase;?>">
								<input name="txt_cantidad<?php echo $cont?>" id="txt_cantidad<?php echo $cont?>" type="text" class="caja_de_num" size="15" maxlength="10" onkeypress="return permite(event,'num',2);"
								onchange="formatCurrency(value,'txt_cantidad<?php echo $cont?>');" readonly="readonly"/> LTS
							</td>
						<?php }?>
						<td class="<?php echo $nom_clase;?>">
							<textarea name="txa_comentarios<?php echo $cont?>" id="txa_comentarios<?php echo $cont?>" maxlength="160" onkeyup="return ismaxlength(this)" 
							class="caja_de_texto" rows="3" cols="30" onkeypress="return permite(event,'num_car', 0);" disabled='disabled'></textarea>
						</td>
						<?php
				echo"	</tr>";			
				//Determinar el color del siguiente renglon a dibujar
				$cont++;
				if($cont%2==0)
					$nom_clase = "renglon_blanco";
				else
					$nom_clase = "renglon_gris";
					
			}while($datos=mysql_fetch_array($rs)); 	
			$cont--;
			echo "<input type='hidden' value='$cont' name='hdn_cantidad' id='hdn_cantidad'/>";
			echo "<input type='hidden' value='$fecha' name='hdn_fecha' id='hdn_fecha'/>";
			echo "<input type='hidden' value='$_POST[cmb_supervisor]' name='hdn_supervisor' id='hdn_supervisor'/>";
			echo "<input type='hidden' value='$_POST[cmb_turno]' name='hdn_turno' id='hdn_turno'/>";
			if($cantAceites>0){
			foreach($cantAceite as $ind => $value)
				echo "<input type='hidden' value='$value' name='hdn_$ind' id='hdn_$ind'/>";
			}
			echo "</table>";
		}
		//Cerramos la conexion con la Base de Datos
		mysql_close($conn);
	}//Fin de function mostrarEquipos($fecha,$familia)
	
	//Funcion que permite registrar el consumo de Aceites por Equipo
	function guardarRegistroAceites(){
		$cant=1;
		$bandera=0;
		$fecha=modFecha($_POST["hdn_fecha"],3);
		$turno=$_POST["hdn_turno"];
		$supervisor=$_POST["hdn_supervisor"];
		echo "<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>";
		do{
			if(isset($_POST["ckb_equipo$cant"])){
				//Obtener el ID para la bitacora de Aceite
				$idBitacora=obtenerIdBitacoraAceite();
				$equipo=$_POST["ckb_equipo$cant"];
				$aceite=$_POST["cmb_aceite$cant"];
				$cantidad=str_replace(",","",$_POST["txt_cantidad$cant"]);
				$comentarios=strtoupper($_POST["txa_comentarios$cant"]);
				//Sentencia para actualizar la cantidad de Aceite en "STOCK"
				$sql_stm_cat="UPDATE catalogo_aceites_mina SET cantidad=cantidad-$cantidad WHERE id_aceite='$aceite'";
				//Sentencia para ingresar el Registro del incremento a la Bitácora
				$sql_stm_bit="INSERT INTO bitacora_aceite_mina (id_bitacora,catalogo_aceites_id_aceite,equipos_id_equipo,fecha,turno,supervisor_mtto,tipo_mov,cantidad,equipo,comentarios) 
								VALUES ('$idBitacora','$aceite','$equipo','$fecha','$turno','$supervisor','S','$cantidad','$equipo','$comentarios')";
				//Abrimos la conexion con la Base de datos
				$conn=conecta("bd_mantenimiento");
				//Ejecutar la sentencia de catalogo de aceites
				$rs=mysql_query($sql_stm_cat);
				//Verificar el resultado de la sentencia
				if(!$rs){
					$bandera=1;
					$error=mysql_error();
					break;
				}
				//Ejecutar la sentencia de bitacora de aceite
				$rs=mysql_query($sql_stm_bit);
				//Verificar el resultado de la sentencia
				if(!$rs){
					$bandera=1;
					$error=mysql_error();
					break;
				}
				//Cerramos la conexion con la Base de Datos
				mysql_close($conn);
				//Registrar el movimiento en la bitácora de Movimientos
				registrarOperacion("bd_mantenimiento","$idBitacora","RegistroSalidaAceite",$_SESSION['usr_reg']);
			}
			$cant++;
		}while($cant<=count($_POST));
		if($bandera==0)
			//Redireccionar a la pantalla de Exito
			echo "<meta http-equiv='refresh' content='0;url=exito.php'>";
		else
			//Redireccionar a la pantalla de Error
			echo "<meta http-equiv='refresh' content='0;url=error.php?err=$error'>";
	}//fin de function guardarRegistroAceites()
?>