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

    $dados = new \App\Domains\Papcj\Visit();

    $message = $dados->userVisit($idUser, $idChurch);

    return response()->json(['message' => $message]);

});

$app->get('frequent/{idUser}/{idChurch}', function($idUser, $idChurch, Illuminate\Http\Request $request) {

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
/*
$app->get('user', function() {

    $user = new \App\Domains\Papcj\Models\User();

    dd($user->all());

});*/


$app->get('/my-church/{id}', function($id) {

    $usuario = App\Domains\Papcj\Models\User::find($id);

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

$app->get('user/{id}', function($id) {

    $user = new \App\Domains\Papcj\UserBussines();

    return response()->json($user->getUser($id));

});

$app->get('/church', 'AppMobile\ChurchController@getIndex');
$app->get('/church/{idUser}', 'AppMobile\ChurchController@getIndex');
$app->post('/church/{idUser}', 'AppMobile\ChurchController@postIndex');
$app->get('/church/{idUser}/{id}', 'AppMobile\ChurchController@getIndex');

/*$app->get('/geolocation', function() {

    $geolocation = new \App\Domains\Papcj\Models\Geolocation();

//    dd($geolocation->all());

    $geolocation->where('id', '!=', 1)->delete();

    //dd($geolocation->find('536b0a143004b15885c91a38'));


    $arrayData = [
        'loc' => [
            '$near' => [
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [-73.965355,40.782865]
                ],
                '$maxDistance' => 20000
            ],
        ],
    ];

    $arrayData = [
        'loc' => [
            '$nearSphere' => [
                '$geometry' => [
                    'type' => 'Point',
                    'coordinates' => [-73.965355,40.782865]
                ],
                '$maxDistance' => 100000
            ],
        ],
    ];



    $dados = $geolocation->whereRaw($arrayData)->get();

    //dd($dados);

    foreach($dados as $d) {
        var_dump($d);
    }


    //{ location: { $nearSphere: { $geometry: { type: "Point", coordinates: [ -73.93414657, 40.82302903 ] }, $maxDistance: 5 * METERS_PER_MILE } } }


    $geolocation->name = "Ediaimo Sousa Borges";

    $loc = new stdClass();
    $loc->type = 'Point';
    $loc->coordinates = [-20.778889, 20.639722];

    $geolocation->loc = $loc;

    $geolocation->save();

    dd($geolocation);

});*/

$app->get('config-app', function() {

    \App\Domains\Papcj\Models\Church::where('id', '!=', 1)->delete();

    \App\Domains\Papcj\Models\User::where('id', '!=', 1)->delete();

});

