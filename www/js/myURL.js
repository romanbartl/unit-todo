function toLogin() {
    var url = window.location.pathname;
    url = url.substring(0, url.indexOf('/register'));
    window.history.replaceState('', '', url + '/login');
}

function toRegister() {
    var url = window.location.pathname;
    url = url.substring(0, url.indexOf('/login'));
    window.history.replaceState('', '', url + '/register');
}

function logOut() {
    var url = window.location.pathname;
    url = url.substring(0, url.indexOf('homepage/account'));
    window.history.replaceState('', '', url);
}

function toAccount() {
    var url = window.location.pathname;
    url = url.substring(0, url.indexOf(''));
    window.history.replaceState('', '', url + '/homepage/account');
}

function toLogin2() {
    var url = window.location.pathname;
    url = url.substring(0, url.indexOf(''));
    window.history.replaceState('', '', url + '/homepage/login');
}