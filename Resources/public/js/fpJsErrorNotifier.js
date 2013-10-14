/**
 * Created by ymaltsev on 10/10/13.
 */
window.onerror = function (msg, file, line) {
    var url = "/fp_js_error_notifier/";

    var xmlhttp;
    if (window.XMLHttpRequest) {
        //IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else {
        //IE6, IE5
        try { xmlhttp =  new ActiveXObject("Microsoft.XMLHTTP"); }  catch(e) {}
        try { xmlhttp =  new ActiveXObject("Msxml2.XMLHTTP"); }     catch(e) {}
        try { xmlhttp =  new ActiveXObject("Msxml2.XMLHTTP.6.0"); } catch(e) {}
        try { xmlhttp =  new ActiveXObject("Msxml2.XMLHTTP.3.0"); } catch(e) {}
    }

    if (undefined !== xmlhttp) {
        var data = {
            msg: msg,
            file: file,
            line: line,
            url: window.location.href,
            userAgent: window.navigator.userAgent
        };
        var paramsString = '';
        for(var i in data) {
            if(data.hasOwnProperty(i)) {
                paramsString = paramsString + i + "=" + encodeURIComponent(data[i]) + "&";
            }
        }

        xmlhttp.open("POST", url, true);
        xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xmlhttp.send(paramsString);
    }

    return true;
};