<?php
// Exercise legacy manager navigation without booting the CMS or sending mail.
function __($key)
{
    return 'Розсилки';
}

function csrf_token()
{
    return $GLOBALS['testManagerToken'];
}

require dirname(__DIR__) . '/src/Controllers/sMailerController.php';

foreach (['initial-session', 'rotated-session', 'token+with&reserved=characters'] as $token) {
    $GLOBALS['testManagerToken'] = $token;
    $controller = new \Seiger\sMailer\Controllers\sMailerController();
    foreach (['&get=configure', '&get=periodic', '&get=once', '&get=subscribers', '&get=subscribers&page=2', '&get=subscribers&sort=email&order=asc', '&get=subscriber&i=7', '&get=unsubscribe&i=7'] as $route) {
        parse_str(parse_url($controller->url . $route, PHP_URL_QUERY), $query);
        if (($query['_token'] ?? null) !== $token || ($query['a'] ?? null) !== '112'
            || ($query['id'] ?? null) !== md5('Розсилки') || !isset($query['get'])) {
            throw new \RuntimeException('Manager URL lost its token or routing parameters.');
        }
    }
}

echo "Manager module URL checks passed (24 routes).\n";
