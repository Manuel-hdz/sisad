<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>

	<?php
		session_start();
		$h=opendir('.');
		while ($file=readdir($h)){
			if (substr($file,0,6)=='inicio'){
				break;
			}
		}
		closedir($h);
	?>

	<link rel="stylesheet" type="text/css" href="includes/estiloGerencia.css" />
	<script type="text/javascript" src="../../includes/jquery-1.5.1.js"></script>
	<script type="text/javascript" language="javascript">
		setTimeout("document.getElementById('usermsg').focus();",1000);
	</script>

	<script type="text/javascript">
	// jQuery Document
	$(document).ready(function(){
		//If user submits the form
		$("#submitmsg").click(function(){	
			var clientmsg = $("#usermsg").val();
			$.post("op_guardarMsje.php", {text: clientmsg});			
			$("#usermsg").attr("value", "");
			return false;
		});
		
		//Load the file containing the chat log
		function loadLog(){		
			var oldscrollHeight = $("#tabla-mensajes").attr("scrollHeight") - 20;
			$.ajax({
				url: "../../includes/chat/log.html",
				cache: false,
				success: function(html){		
					$("#tabla-mensajes").html(html); //Insert chat log into the #chatbox div				
					var newscrollHeight = $("#tabla-mensajes").attr("scrollHeight") - 20;
					if(newscrollHeight > oldscrollHeight){
						$("#tabla-mensajes").animate({ scrollTop: newscrollHeight }, 'normal'); //Autoscroll to bottom of div
					}				
				},
			});
		}

		//setInterval (loadLog, 2500);	//Reload file every 2.5 seconds
		setInterval (verificarChat, 2500);	//Verificar la ventana Padre cada 2.5 Segundos
		
		function verificarChat(){
			//Si la ventana que abrio la ventana Chat no esta cerrada, continuar
			if(!window.opener.closed){
				//Obtener la URL de la Ventana Padre
				var urlPadre=""+window.opener.location;
				//Si en la url se encuentra la pagina de Salir o de Login, cerrar la ventana de Chat
				if(urlPadre.indexOf("salir.php")>=0 || urlPadre.indexOf("login.php")>=0)
					window.close();
				else
					//Si la ventana de chat no fue cerrada durante el proceso de revision, cargar el contenido del Chat
					loadLog();
			}
			//Si la ventana padre esta cerrada, cerrar el chat, la sesion ya se destruyo y no se pueden enviar msjes, sin embargo se pueden revisar, por esto
			//cerramos la ventana padre
			else
				window.close();
		}
		//If user wants to end session
		$("#exit").click(function(){
			window.close();
		});
	});
	</script>
	
	<script type="text/javascript" language="javascript">
		setTimeout("acomodarScroll()",500);
		function acomodarScroll(){
			var finScroll = $("#tabla-mensajes").attr("scrollHeight") - 20;
			$("#tabla-mensajes").animate({ scrollTop: finScroll }, 'normal'); //Autoscroll to bottom of div
		}
		
		setInterval(cerrada,1000);
		
		function cerrada(){
			if(window.parent.closed)
				window.close();
		}
	</script>
    <style type="text/css">
		<!--
		#tabla-mensajes {position:absolute; left:30px; top:82px; width:940px; height:420px; z-index:12; overflow:scroll;}
		#titulo-chat {position:absolute; left:30px; top:35px; width:191px; height:19px; z-index:12; }
		#botones { position: absolute; left:30px; top:545px; width:940px; height:40px; z-index:23; }
		#barraEmergente{position: absolute; left:26px; top:29px; width:940px; height:40px; z-index:11; }
		-->
    </style>
</head>
<body>
	<div id="barraEmergente"><img src="../../images/title-bar-bg-Gerencia.gif" width="999" height="30" /></div>
    <div class="titulo_barra" id="titulo-chat">Sala de Comunicaci&oacute;n </div>
	<form name="message" action="">
	<div id="tabla-mensajes" class="borde_seccion2" align="left" style="background-color:#EBF4FB">
		<?php
		if(file_exists("../../includes/chat/log.html") && filesize("../../includes/chat/log.html") > 0){
			$handle = fopen("../../includes/chat/log.html", "r");
			$contents = fread($handle, filesize("../../includes/chat/log.html"));
			fclose($handle);
			echo $contents;
		}
		?>
	</div>
	<div id="botones" align="center">
	<table width="100%" cellpadding="12">		
		<tr>
			<td align="center">
			<label style="color:#FFF;">Escriba su Mensaje:</label>
			<input name="usermsg" id="usermsg" type="text" class="caja_de_texto" maxlength="120" size="63" />
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input name="submitmsg" id="submitmsg" type="submit" value="Enviar" class="botones"/>
			&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="button" onclick="window.close()" value="Cerrar" title="Cerrar Chat" class="botones"/>
			</td>
		</tr>
	</table>
	</div>
	</form>
</body>
<?php //}//Cierre del Else donde se comprueba el usuario que esta registrado ?>
</html>