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
var activeClasses = ['text-white', 'bg-yellow-700'];
var inactiveClasses = ['text-gray-700', 'hover:bg-gray-50'];

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

function loadStudents(page, callback) {
	fetch(apiEndpoints.users + '?page=' + page)
	.then(function(response) {
		if (response.status == 200) {
			return response.json();
		}
		return default_response;
	})
	.then(function(result) {
		callback(result)
	});
}

function buildTableResults(rows) {
	for (row of rows) {
		table_users.innerHTML += tableTemplate(row);
	}
}

function buildPagination(pager) {
	var pagination = document.getElementById('pagination');

	for (let i=1; i<=pager.pages; i++) {
		pagination.innerHTML += paginationLinks(i);
	}
}

function validateLogged() {
	if (!readCookie('is_logged')) {
		return redirect('/index.html');
	}

	loadStudents(page, function(result) {
		buildTableResults(result['results']);
		buildPagination(result['pager']);
		var element = document.getElementById('page_' + page);
		addClasses(element, activeClasses);
	});
}

function paginateResults(page_num) {
	loadStudents(page_num, function(result) {
		table_users.innerHTML = '';
		buildTableResults(result['results']);
		var pages = document.querySelectorAll("a[id*='page_']");
		let page_string;
		for (p of pages) {
			page_string = `page_${page_num}`;
			if (page_string == p.id) {
				toggleClasses(p, activeClasses, inactiveClasses);
			} else {
				removeClasses(p, activeClasses);
			}
		}
	})
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

function paginationLinks(page_num) {
	return (`
		<a 
			id="page_${page_num}"
			href="#" 
			onclick="paginateResults(${page_num}); return false;"
			class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium"
		>
			${page_num}
		</a>
	`)
}

function removeClasses(element, classes) {
	element.classList.remove(...classes);
}

function addClasses(element, classes) {
	element.classList.add(...classes);
}

function toggleClasses(element, add, remove) {
	element.classList.remove(...remove);
	element.classList.add(...add);
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