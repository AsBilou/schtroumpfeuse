<?php

use Symfony\Component\HttpFoundation\Request;

$app->get('/login', function(Request $request) use ($app) {
    return $app['twig']->render('templates/admin/login.twig', array(
        'error'         => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

$app->get('/admin', function () use ($app) {
    return $app->redirect('admin/');
})
->bind('admin_redirect');

$admin = $app['controllers_factory'];

$admin->get('/', function () use ($app) {

    return $app['twig']->render('templates/admin/index.twig', array(

    ));

})->bind('admin');