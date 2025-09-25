// JavaScript Document

function onrollout(){
	tmp = findSWF("ofc");
	x = tmp.rollout();
}

function onrollout2(){
	tmp = findSWF("ofc");
	x = tmp.rollout();
}

function findSWF(movieName) {
	if (navigator.appName.indexOf("Microsoft")!= -1) {
   		return window[movieName];
	} else {
   		return document[movieName];
	}
}