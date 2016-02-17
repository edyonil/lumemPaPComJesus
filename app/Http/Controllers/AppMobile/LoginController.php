<?php
/**
 * Created by PhpStorm.
 * User: ediaimoborges
 * Date: 07/02/16
 * Time: 21:21
 */

namespace App\Http\Controllers\AppMobile;

use App\Domains\Papcj\UserBussines;
use Illuminate\Http\Request;

class LoginController
{

    protected $user;

    public function __construct(UserBussines $userBussines)
    {
        $this->user = $userBussines;
    }

    public function postIndex(Request $request)
    {

        $rules = [
            'email' => ['required', 'email'],
            'password' => ['required']
        ];

        $message = [
            'email.required' => 'Campo email obrigatório',
            'password.required' => 'Campo senha obrigatório',
            'email' => 'Email inválido'
        ];

        $input = $request->all();

        $validacao = \Validator::make($input, $rules, $message);

        $message = [
            'result' => ""
        ];

        if ($validacao->fails()) {

            $message['result'] = $validacao->errors()->first();
            return response()->json($message, 400);

        };

        $user = $this->user->getLoginUser($input['email'], $input['password']);

        if ($user) {
            $message['result'] = $user;
            return response()->json($message, 200);
        }

        $message['result'] = 'Email ou senha inválido!';
        return response()->json($message, 400);


    }

}