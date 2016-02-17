<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {

    return $app->version();
});

$app->get('visit/{idUser}/{idChurch}', function($idUser, $idChurch) {

    $dados = new \App\Domains\Papcj\VisitOrFrequent(true);

    $message = $dados->userVisit($idUser, $idChurch);

    return response()->json(['message' => $message]);

});

$app->get('visit/{idUser}/{idChurch}', function($idUser, $idChurch) {

    $dados = new \App\Domains\Papcj\Visit();

    $message = $dados->userVisit($idUser, $idChurch);

    return response()->json(['message' => $message]);

});

$app->get('frequent/{idUser}/{idChurch}', function($idUser, $idChurch, Illuminate\Http\Request $request) {

    //dd(\App\Domains\Papcj\Models\Church::find('56a046ddbffebc670e8b4568'));

    $force = 0;

    $dados = new \App\Domains\Papcj\Frequent();

    if ($request->get('force')) {

        $force = $request->get('force');

    }

    $codeStatus = 200;

    $message = $dados->userFrequent($idUser, $idChurch, $force);

    if (!$message) {
        $codeStatus = 400;
    }

    return response()->json(['message' => ['result' => $message]], $codeStatus);

});

/*$app->get('user', function() {

    $user = new \App\Domains\Papcj\Models\User();

    dd($user->find('56a5201bbffebc8c048b456b'));

});*/


$app->get('/my-church/{id}', function($id) {

    $usuario = App\Domains\Papcj\Models\User::find($id);
    //$usuario = App\Domains\Papcj\Models\User::orderBy('created_at', 'DESC')->first();
    //dd($usuario);

    $igreja = new \App\Domains\Papcj\Church();

    $dados = $igreja->getChurch($usuario->id, $usuario->igreja_frequenta);

    if (!$dados) {
        return response()->json(['result' => 'Você não marcou nenhuma igreja como frequenta'], 400);
    }

    return ['message' => $dados];

});

$app->post('/login','AppMobile\LoginController@postIndex');

$app->post('/user','AppMobile\UserController@postIndex');
$app->put('/user/{id}','AppMobile\UserController@putIndex');

$app->get('/church', 'AppMobile\ChurchController@getIndex');
$app->get('/church/{idUser}', 'AppMobile\ChurchController@getIndex');
$app->post('/church/{idUser}', 'AppMobile\ChurchController@postIndex');
$app->get('/church/{idUser}/{id}', 'AppMobile\ChurchController@getIndex');