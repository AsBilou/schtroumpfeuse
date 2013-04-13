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

    $code = file_get_contents('http://biloupad.bilou.net/api/1.2.7/listAllPads?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad');
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

$app->match('/api/texts/create/{id}/{text}', function ($id,$text) use ($app) {
    //Création d'un nouveau pad
    $json_url = file_get_contents('http://biloupad.bilou.net/api/1/createPad?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad&padID='.$id);
    $json_url = file_get_contents("http://biloupad.bilou.net/api/1/setText?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad&padID=".$id."&text=".$text);

    return true;
})
->bind('textscreate');

$app->get('/api/texts/trans', function () use ($app) {

    $code = file_get_contents('http://biloupad.bilou.net/api/1.2.7/listAllPads?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad');
    $data = json_decode($code, TRUE);
    $n=0;
    $json='{"pad":{';
    foreach ($data['data']['padIDs'] as $name){
        $nameExplode = explode('-',$name);
        if($nameExplode[0]=='trans'){
        $json.= '"name":"'.$name.'",';
        $n++;
        }
    }
    $json=substr_replace($json ,"",-1);
    $json.='}';
    return $json;
})
->bind('textstranslist');

$app->get('/api/texts/{id}', function ($id) use ($app) {

    $json_url = "http://biloupad.bilou.net/api/1/getText?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad&padID=".$id;
    $json = file_get_contents($json_url);


    return $json;
})
->bind('textspad');

$app->get('/api/texts/{id}/original/{mot}', function () use ($app) {

    function regHex($word, $mot){
        if (preg_match("/er$|ir$/", $word)) {
            return $mot;
        } else {
            return $word;
        }
    }

    $json_url = file_get_contents('http://biloupad.bilou.net/api/1/getText?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad&padID='.$id);
    $data = json_decode($json_url,TRUE);
    $explode = explode(' ', $data['data']['text']);
    $text = '';
    foreach($explode as $word){
        $word = regHex($word, $mot);
        $text .= $word.'%20';
    }

    //Création d'un nouveau pad
    $json_url = file_get_contents("http://biloupad.bilou.net/api/1/setText?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad&padID=".$id."&text=".$text);

    return true;
})
->bind('textsoriginal');

$app->get('/api/texts/{id}/trans/{mot}', function ($id,$mot) use ($app) {
    function regHex($word, $mot){
        if (preg_match("/er$|ir$/", $word)) {
            return $mot;
        } else {
            return $word;
        }
    }

    $json_url = file_get_contents('http://biloupad.bilou.net/api/1/getText?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad&padID='.$id);
    $data = json_decode($json_url,TRUE);
    $explode = explode(' ', $data['data']['text']);
    $text = '';
    foreach($explode as $word){
        $word = regHex($word, $mot);
            $text .= $word.'%20';
    }
    $namePAd = 'trans-'.$mot.'_'.$id;
    //Création d'un nouveau pad
    $json_url = file_get_contents('http://biloupad.bilou.net/api/1/createPad?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad&padID='.$namePAd);
    $json_url = file_get_contents("http://biloupad.bilou.net/api/1/setText?apikey=ynDc83D1wQa8YEoLVYpOnghaGEkSJkad&padID=".$namePAd."&text=".$text);

    return true;
})
->bind('textstrans');

$app->error(function (\Exception $e, $code) use ($app) {


    $page = 404 == $code ? 'templates/404.twig' : 'templates/500.twig';

    return new Response($app['twig']->render($page, array('code' => $code)), $code);
});
