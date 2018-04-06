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

