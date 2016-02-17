<?php
/**
 * Created by PhpStorm.
 * User: ediaimoborges
 * Date: 07/02/16
 * Time: 14:07
 */

namespace App\Http\Controllers\AppMobile;


use App\Domains\Papcj\UserBussines;
use App\User;
use Laravel\Lumen\Routing\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserBussines $userBussines)
    {
        $this->user = $userBussines;
    }

    public function postIndex(Request $request)
    {

        $validacao = $this->validator($request);

        $message = [
            'result' => ""
        ];

        if ($validacao->fails()) {

            $message['result'] = $validacao->errors()->first();

            return response()->json($message, 422);

        };

        $dados = $this->user->createUser($request->all());

        $codigo = 400;

        $message = [
            'result' => "Ocorreu um erro ao cadastrar o usuário",
        ];

        if ($dados) {

            $codigo = 200;

            $message['result'] = $dados;

        }

        return response()->json($message, $codigo);

    }

    public function putIndex($id, Request $request)
    {

        $validacao = $this->validator($request, true);

        $message = [
            'result' => ""
        ];

        if ($validacao->fails()) {

            return response()->json($message['result'] = $validacao->errors()->first(), 400);

        };

        $dados = $this->user->updateUser($id, $request->all());

        $codigo = 400;

        $message = [
            'result' => "Ocorreu um erro ao cadastrar o usuário",
        ];

        if ($dados) {

            $codigo = 200;

            $message['result'] = $dados;

        }

        return response()->json($message, $codigo);

    }


    private function validator(Request $request, $update = false)
    {

        $unique = "unique:usuario,email";

        $input = $request->all();

        if (isset($input['id'])) {

            $unique .= ',' . $input['id'] . ',_id';

        }

        $rules = [
            'nome' => ['required'],
            'sobrenome' => ['required'],
            'tipo' => ['required'],
            'email' => ['required', 'email', $unique]
        ];

        if ($input['tipo'] == 'facebook') {

            $rules['email'] = ['required', 'email'];

        }

        if ($update == false) {

            $rules['password'] = ['required_if:tipo,email', 'confirmed'];

        };

        $message = [
            'email.required' => 'Campo email obrigatório',
            'email' => 'Email inválido',
            'email.unique' => 'Já existe um usuário cadastro com esse email',
            'password.required_if' => 'Campo senha obrigatório',
            'password.confirmed' => 'O campo confirmar senha não é igual ao senha',
            'nome.required' => 'Campo nome obrigatório',
            'sobrenome.required' => 'Campo sobrenome obrigatório',
            'tipo.required' => 'O tipo de cadastrado é obrigatório',
        ];

        return \Validator::make($input, $rules, $message);
    }

}