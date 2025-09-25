<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<Sistema de Gestion Empresarial, Produccion y Operacion

html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
	<title>Sistema de Gesti&oacute;n Empresarial, Producci&oacute;n y Operaci&oacute;n</title>
	<meta http-equiv="refresh" content="10; url=pages/login.php?usr_sts=accinc">
	
<script language="JavaScript" type="text/JavaScript">
<!--
function disabledKey(e)
{
	var keycode;
	if (window.event){
		keycode = window.event.keyCode;
		if(keycode==116||keycode==122){
			alert ("Tecla Deshabilitada");
			event.keyCode=0;
			return false;
		}
	}
}

function click() {
	if (event.button==2) {
		alert ('Contenido Protegido, �Concreto Lanzado de Fresnillo S.A. de C.V.');
	}
}
document.onmousedown=click;

function MM_timelinePlay(tmLnName, myID) { //v1.2
  //Copyright 1997, 2000 Macromedia, Inc. All rights reserved.
  var i,j,tmLn,props,keyFrm,sprite,numKeyFr,firstKeyFr,propNum,theObj,firstTime=false;
  if (document.MM_Time == null) MM_initTimelines(); //if *very* 1st time
  tmLn = document.MM_Time[tmLnName];
  if (myID == null) { myID = ++tmLn.ID; firstTime=true;}//if new call, incr ID
  if (myID == tmLn.ID) { //if Im newest
    setTimeout('MM_timelinePlay("'+tmLnName+'",'+myID+')',tmLn.delay);
    fNew = ++tmLn.curFrame;
    for (i=0; i<tmLn.length; i++) {
      sprite = tmLn[i];
      if (sprite.charAt(0) == 's') {
        if (sprite.obj) {
          numKeyFr = sprite.keyFrames.length; firstKeyFr = sprite.keyFrames[0];
          if (fNew >= firstKeyFr && fNew <= sprite.keyFrames[numKeyFr-1]) {//in range
            keyFrm=1;
            for (j=0; j<sprite.values.length; j++) {
              props = sprite.values[j]; 
              if (numKeyFr != props.length) {
                if (props.prop2 == null) sprite.obj[props.prop] = props[fNew-firstKeyFr];
                else        sprite.obj[props.prop2][props.prop] = props[fNew-firstKeyFr];
              } else {
                while (keyFrm<numKeyFr && fNew>=sprite.keyFrames[keyFrm]) keyFrm++;
                if (firstTime || fNew==sprite.keyFrames[keyFrm-1]) {
                  if (props.prop2 == null) sprite.obj[props.prop] = props[keyFrm-1];
                  else        sprite.obj[props.prop2][props.prop] = props[keyFrm-1];
        } } } } }
      } else if (sprite.charAt(0)=='b' && fNew == sprite.frame) eval(sprite.value);
      if (fNew > tmLn.lastFrame) tmLn.ID = 0;
  } }
}

function MM_initTimelines() { //v4.0
    //MM_initTimelines() Copyright 1997 Macromedia, Inc. All rights reserved.
    var ns = navigator.appName == "Netscape";
    var ns4 = (ns && parseInt(navigator.appVersion) == 4);
    var ns5 = (ns && parseInt(navigator.appVersion) > 4);
    var macIE5 = (navigator.platform ? (navigator.platform == "MacPPC") : false) && (navigator.appName == "Microsoft Internet Explorer") && (parseInt(navigator.appVersion) >= 4);
    document.MM_Time = new Array(1);
    document.MM_Time[0] = new Array(1);
    document.MM_Time["Timeline1"] = document.MM_Time[0];
    document.MM_Time[0].MM_Name = "Timeline1";
    document.MM_Time[0].fps = 15;
    document.MM_Time[0][0] = new String("sprite");
    document.MM_Time[0][0].slot = 1;
    if (ns4)
        document.MM_Time[0][0].obj = document["Layer4"];
    else if (ns5)
        document.MM_Time[0][0].obj = document.getElementById("Layer4");
    else
        document.MM_Time[0][0].obj = document.all ? document.all["Layer4"] : null;
    document.MM_Time[0][0].keyFrames = new Array(1, 60);
    document.MM_Time[0][0].values = new Array(2);
    if (ns5 || macIE5)
        document.MM_Time[0][0].values[0] = new Array("320px", "320px", "319px", "319px", "319px", "318px", "318px", "318px", "317px", "317px", "317px", "316px", "316px", "316px", "315px", "315px", "315px", "314px", "314px", "314px", "313px", "313px", "313px", "312px", "312px", "312px", "311px", "311px", "311px", "310px", "310px", "309px", "309px", "309px", "308px", "308px", "308px", "307px", "307px", "307px", "306px", "306px", "306px", "305px", "305px", "305px", "304px", "304px", "304px", "303px", "303px", "303px", "302px", "302px", "302px", "301px", "301px", "301px", "300px", "300px");
    else
        document.MM_Time[0][0].values[0] = new Array(320,320,319,319,319,318,318,318,317,317,317,316,316,316,315,315,315,314,314,314,313,313,313,312,312,312,311,311,311,310,310,309,309,309,308,308,308,307,307,307,306,306,306,305,305,305,304,304,304,303,303,303,302,302,302,301,301,301,300,300);
    document.MM_Time[0][0].values[0].prop = "left";
    if (ns5 || macIE5)
        document.MM_Time[0][0].values[1] = new Array("-233px", "-227px", "-220px", "-214px", "-208px", "-201px", "-195px", "-189px", "-182px", "-176px", "-170px", "-163px", "-157px", "-151px", "-144px", "-138px", "-132px", "-126px", "-119px", "-113px", "-107px", "-100px", "-94px", "-88px", "-81px", "-75px", "-69px", "-62px", "-56px", "-50px", "-43px", "-37px", "-31px", "-24px", "-18px", "-12px", "-5px", "1px", "7px", "14px", "20px", "26px", "33px", "39px", "45px", "51px", "58px", "64px", "70px", "77px", "83px", "89px", "96px", "102px", "108px", "115px", "121px", "127px", "134px", "140px");
    else
        document.MM_Time[0][0].values[1] = new Array(-233,-227,-220,-214,-208,-201,-195,-189,-182,-176,-170,-163,-157,-151,-144,-138,-132,-126,-119,-113,-107,-100,-94,-88,-81,-75,-69,-62,-56,-50,-43,-37,-31,-24,-18,-12,-5,1,7,14,20,26,33,39,45,51,58,64,70,77,83,89,96,102,108,115,121,127,134,140);
    document.MM_Time[0][0].values[1].prop = "top";
    if (!ns4) {
        document.MM_Time[0][0].values[0].prop2 = "style";
        document.MM_Time[0][0].values[1].prop2 = "style";
    }
    document.MM_Time[0].lastFrame = 60;
    for (i=0; i<document.MM_Time.length; i++) {
        document.MM_Time[i].ID = null;
        document.MM_Time[i].curFrame = 0;
        document.MM_Time[i].delay = 1000/document.MM_Time[i].fps;
    }
}
//-->
</script>
<style type="text/css">
<!--
body {
	background-image: url(images/bk2.jpg);
	background-color: #FFFFFF;
}
.style1 {color: #006666;font-weight: bold; }
-->
</style>
<link href="pages/styles.css" rel="stylesheet" type="text/css">
</head>

<body onKeyDown="return disabledKey(event);" onLoad="MM_timelinePlay('Timeline1')" text="#FFFFFF" link="#FFFFFF" vlink="#FFFFFF" alink="#FFFFFF">
<div id="Layer7" style="position:absolute; left:260px; top:446px; width:382px; height:18px; z-index:7"> 
  <p align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><strong><font color="#000033" size="2" face="Arial, Helvetica, sans-serif"><img src="images/line.gif" width="500" height="20"></font></strong></font></p>
</div>
<div id="Layer4" style="position:absolute; left:320px; top:-233px; width:241px; height:238px; z-index:4"> 
  <div align="center"> 
    <p><font color="#000000"><b><font face="Arial, Helvetica, sans-serif"><font size="6"><i><img src="images/logo3d.gif" width="392" height="330"></i></font></font></b></font></p>
    <p>&nbsp;</p>
  </div>
</div>
<div id="Layer2" style="position:absolute; left:410px; top:463px; width:224px; height:18px; z-index:11"> 
  <div align="center"><font color="#FFFFFF" size="2" face="Arial, Helvetica, sans-serif"><strong>Cargando el Sistema...</strong></font></div>
</div>
<div id="Layer3" style="position:absolute; left:700px; top:486px; width:52px; height:10px; z-index:12"> 
  <div align="right" class="style1"><font size="2" face="Arial, Helvetica, sans-serif">Ver 0.01</font></div>
</div>
</body>
</html>
