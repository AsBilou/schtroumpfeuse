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

$app->get('/api/texts', function () use ($app) {

    $code = file_get_contents('http://10.40.75.150:9001/api/1.2.7/listAllPads?apikey=Kobtnw4ZwOD3ztsO5Zv764so4CMe8yFk');
    $data = json_decode($code, TRUE);
    $n=0;
    $json='{"pad":{';
    foreach ($data['data']['padIDs'] as $name){
        $json.= '"name":"'.$name.'",';
        $n++;
    }
    $json=substr_replace($json ,"",-1);
    $json.='}';
    return $json;
})
->bind('texts');

$app->get('/api/texts/trans', function () use ($app) {



    return 'hello';
})
->bind('textstranslist');

$app->get('/api/texts/{id}', function ($id) use ($app) {

    $json_url = "http://10.40.75.150:9001/api/1/getText?apikey=Kobtnw4ZwOD3ztsO5Zv764so4CMe8yFk&padID=".$id;
    $json = file_get_contents($json_url);


    return $json;
})
->bind('textspad');

$app->get('/api/texts/{id}/original/{mots}', function () use ($app) {



    return 'hello';
})
->bind('textsoriginal');

$app->get('/api/texts/{id}/trans/{mots}', function () use ($app) {



    return 'hello';
})
->bind('textstrans');

$app->error(function (\Exception $e, $code) use ($app) {


    $page = 404 == $code ? 'templates/404.twig' : 'templates/500.twig';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
