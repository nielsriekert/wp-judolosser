//versie 1.0
function createRequest(){
	try {
		
		request = new XMLHttpRequest();
		
	} catch(e){
		try {
			
			request = new ActiveXObject("Msxm12.XMLHTTP");
			
		} catch(e){
			try {
			
				request = new ActiveXObject("Microsoft.XMLHTTP");
				
			} catch(e){
				
				alert("Request object could not be created. Error: " + e);
				request = null;
			}
		}
	}
	
	return request;
}

//auteur: quirksmode.org
function sendRequest(url,callback,postData,async) {
	if (this != window)
		var _this = this;
	var req = createRequest();
	if (!req) return;
	var method = (postData) ? "POST" : "GET";
	var async = (async == undefined) ? true : false;
	//console.log(req.responseType);
	//if(type) req.responseType = type;
	req.open(method,url,async);
	req.setRequestHeader('User-Agent','XMLHTTP/1.0');
	req.page = url;
	if (postData)
		req.setRequestHeader('Content-type','application/x-www-form-urlencoded');
	req.onreadystatechange = function () {
		if (req.readyState != 4) return;
		if (req.status != 200 && req.status != 304) {
			console.log('HTTP error ' + req.status);
			return;
		}
		if (_this && callback)
			callback.call(_this, req);
		else if (callback)
			callback(req);
	}
	if (req.readyState == 4) return;
	req.send(postData);
}

//checkt welke muisknop is ingedrukt
//verbeteringspunten;
//versie 1.0 11 mei 2010
function checkMouseButton(mEvent){
	if(!mEvent){
		var mouseEvent = window.event;
	}
	else {
		mouseEvent = mEvent;
	}
	
	if(!mEvent && mouseEvent.button == 1 || mouseEvent.button == 0){
		return true;
	}
	else {
		return false;	
	}
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}