var apiEndpoints = {
	'auth': '/auth',
	'users': '/users'
};

var error_message = document.getElementById('error_message');
var loginForm = document.getElementById('login_form');
var username = document.getElementById('username');
var password = document.getElementById('password');
var remember_me = document.getElementById('remember_me');
var alert_message = document.getElementById('alert_message');
var table_users = document.getElementById('table_users');
var logout_btn = document.getElementById('logout_btn');

var page = 1;
var default_response = {'results':[], 'pager': { 'total': 0, 'pages': 0 }};

if (logout_btn) {
	logout_btn.addEventListener('click', function(event) {
		event.preventDefault();
		fetch(apiEndpoints.auth, {
			method: 'DELETE'
		})
		.then(res => {
			if (res.status == 200) {
				eraseCookie('remember_me');
				eraseCookie('is_logged');
				redirect('/index.html');
			}
		})
	});
}

if (loginForm) {
	loginForm.addEventListener('submit', function(event) {
		event.preventDefault();
		alert_message.style.display = 'none';
	
		fetch(apiEndpoints.auth, {
			method: 'POST',
			body: JSON.stringify({
				'username': username.value,
				'password': password.value,
				'remember_me': remember_me.checked,
			})
		})
		.then(function(res) {
			if (res.status == 200) {
				redirect('/users.html');
				return;
			}
			
			return res.json();
		})
		.then(function(response) {
			displayMessage(response.error);
		})
	});
}

function displayMessage(error) {
	alert_message.style.display = 'block';
	error_message.innerText = error;
}

function validateAutologin() {
	if (readCookie('remember_me')) {
		redirect('users.html');
	}
}

function redirect(path) {
	window.location.href = path;
}

function loadStudents() {
	fetch(apiEndpoints.users)
	.then(function(response) {
		if (response.status == 200) {
			return response.json();
		}
		return default_response;
	})
	.then(function(result) {
		buildTableResults(result['results']);
	});
}

function buildTableResults(rows) {
	for (row of rows) {
		table_users.innerHTML += tableTemplate(row);
	}
}

function validateLogged() {
	if (!readCookie('is_logged')) {
		return redirect('/index.html');
	}

	loadStudents();
}

function tableTemplate(data) {
	return (
		'<tr> \
		<td class="px-6 py-4 whitespace-nowrap"> \
		<div class="flex items-center"> \
			<div class="flex-shrink-0 h-10 w-10"> \
				<img class="h-10 w-10 rounded-full" src="/svg/user.svg"> \
			</div> \
		</div> \
		</td> \
		<td class="px-6 py-4 whitespace-nowrap"> \
			<div class="text-sm text-gray-900">'+ data.username +'</div> \
			<div class="text-sm text-gray-500">'+ (data.first_name || '') + ' ' + (data.last_name || '') +'</div> \
		</td> \
		<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"> \
			Default Group \
		</td> \
		</tr>'
	);
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
	createCookie(name, '', -1);
}