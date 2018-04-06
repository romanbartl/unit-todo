
$('#registerHref').click(
    function(e) {
        e.preventDefault();
        var url = window.location.pathname;
        url = url.substring(0, url.indexOf('/login'));
        window.history.replaceState('', '', url + '/register');

        return false; }
        );
