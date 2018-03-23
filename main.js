// global variables

authorsNumber = 1;

// extend standart string class
String.prototype.replaceAll = function(search, replacement) {
    var target = this;
    return target.split(search).join(replacement);
};

function toggleLoginForm() {
	$('#loginform').toggle();
}

function showModal(header, message) {
	alert(header + " " + message);
}


function setCookieForever(name, value) {
    var d = new Date();
    d.setTime(0x7FFFFFFFFFFF); // a very long time :)
	new_cookie = name + "=" + value + ";expires=" + d.toUTCString() + ";path=/";
	alert(new_cookie);
	document.cookie = new_cookie;
}


$(function () {
	
	$('#loginform form').submit(function(ev){

		// prevent submit
		ev.preventDefault();
		
		if ($("#loginform form").get(0).checkValidity() == false) {
			// do nothing if form reported as invalid
			return false;
		};
		
		//collect form data and urlencode it
		accumulator = ["ajax=true"];
		var formInputs = $("#loginform input").each(
			function (index, elem) {
				accumulator.push(elem.name+"="+encodeURIComponent(elem.value));
			}
		);
		
	
		var url = $("#loginform form").get(0).action;
		var params = accumulator.join("&");
		var xhr = new XMLHttpRequest();		
		
		xhr.open("POST", url, true);

		//Send the proper header information along with the request
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

		xhr.onreadystatechange = function() {
			//Call a function when the state changes.
			if(xhr.readyState == 4 && xhr.status == 200) {
				var result = (JSON.parse(xhr.responseText));
				if (!result.authenticated) {
					showModal('Invalid email or password','');
					console.log(result);
				} else {
					setCookieForever("session_id",result.session_id);
					window.location.href=result.redirect;
				};
			}
		}
		xhr.send(params);
		
		//showModal("Сообщение отправляется...",'<img src="/images/circular.gif"/>');

		
		// ad-hoc prevent submit
		return false;
	});

});
