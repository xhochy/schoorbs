var schoorbsREST = {};

// When defining an endpoint, use the URL where we can find your Schoorbs installation,
// do not append /REST/ to it. The last slash should be omitted, but if it is added, it 
// just makes ugly URLs but should work without any problems.
schoorbsREST.endPoint = "http://localhost/schoorbs";

// The username used to authenticate 
schoorbsREST.user = "";
// The password used to authenticate
schoorbsREST.passwd = "";

schoorbsREST.getRoomID = function (roomName) {
	var req = new XMLHttpRequest();
	req.open('GET', schoorbsREST.endPoint + '/REST/getRoomID?name=' + escape(roomName), false, 
        schoorbsREST.user, schoorbsREST.passwd);
    // it's not sure, that Schoorbs returns the content with a MimeType-Header
    // (depends on the server on which Schoorbs is running) 
    req.overrideMimeType('text/xml');
	req.send(null);
	if(req.status != 200) {
        alert('Couldn\'t get RoomID!');
		return false;
	}
	var xml = req.responseXML;
    var result = xml.getElementsByTagName('room_id');
    if (result.length > 0) {
        return result[0];
    } else {
        return false;
    }
}

schoorbsREST.getPeriodID = function (periodName) {
	var req = new XMLHttpRequest();
	req.open('GET', schoorbsREST.endPoint + '/REST/getPeriodID?name=' + escape(periodName), false);
	// it's not sure, that Schoorbs returns the content with a MimeType-Header
    // (depends on the server on which Schoorbs is running) 
    req.overrideMimeType('text/xml');
	req.send(null);
	if (req.status != 200) {
		alert('Konnte Period-ID nicht herausfinden');
        return false;
	}
    var xml = req.responseXML;
    var result = xml.getElementsByTagName('period_id');
    if (result.length > 0) {
        return result[0];
    } else {
        return false;
    }
}

schoorbsREST.login = function() {
    var req = new XMLHttpRequest();
	req.open('GET', schoorbsREST.endPoint + '/REST/login/', false); 
	req.send(null); 
	if(req.status != 200) {
  		return false;
    }
    return true;
}