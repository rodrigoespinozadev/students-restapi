<?php

$router->get('/users', 'user@index');
$router->post('/auth', 'auth@dologin');
$router->delete('/auth', 'auth@signout');