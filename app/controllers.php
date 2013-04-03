<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->get('/', function () use ($app) {
    return $app['twig']->render('templates/site/index.twig', array());
})
->bind('homepage');

$app->error(function (\Exception $e, $code) use ($app) {


    $page = 404 == $code ? 'templates/404.twig' : 'templates/500.twig';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
