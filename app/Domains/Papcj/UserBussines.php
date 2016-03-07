<?php
/**
 * Created by PhpStorm.
 * User: ediaimoborges
 * Date: 07/02/16
 * Time: 12:59
 */

namespace App\Domains\Papcj;


use App\Domains\Papcj\Models\User;

class UserBussines
{

    protected $user;


    public function __construct()
    {
        $this->user = new User();
    }

    public function createUser($input)
    {

        $dados = $this->getUserFacebook($input['email']);

        if ($dados) {
            return $dados;
        }

        $this->user->nome = $input['nome'];
        $this->user->sobrenome = $input['sobrenome'];
        $this->user->email = $input['email'];
        $this->user->tipo = $input['tipo'];
        $this->user->igreja_frequenta = null;
        $this->user->igreja_visita = [];
        $this->user->codigo = rand(0, 5);

        if ($input['tipo'] == 'email') {
            $this->user->senha = md5(sha1(($input['password'])));
        } else {
            $this->user->token = $input['token'];
        }

        $this->user->save();

        return $this->resolveNames($this->user);

    }

    public function updateUser($id, $input)
    {

        $this->user = $this->user->findOrNew($id);

        $this->user->nome = $input['nome'];
        $this->user->sobrenome = $input['sobrenome'];
        $this->user->email = $input['email'];

        $this->user->save();

        return $this->resolveNames($this->user);

    }

    public function getLoginUser($email, $password)
    {

        $user = $this->user->where('email', '=', $email)->where('senha', '=', md5(sha1(($password))))->first();

        if ($user) {
            return $this->resolveNames($user);
        }

        return false;



    }

    public function getUser($id)
    {
        $user = $this->user->find($id);

        return $this->resolveNames($user);

    }

    private function resolveNames(User $user)
    {
        $newData = new \stdClass();
        $newData->id = $user->_id;
        $newData->nome = $user->nome;
        $newData->sobrenome = $user->sobrenome;
        $newData->email = $user->email;
        $newData->tipo = $user->tipo;
        $newData->igrejasVisitadas = isset($user->igreja_visita) ? $user->igreja_visita : null;
        $newData->igreja = isset($user->igreja_frequenta) ? $user->igreja_frequenta : null;

        return $newData;

    }

    private function getUserFacebook($email)
    {

        $dados = $this->user->where('email', '=', $email)->first();

        if ($dados) {

            return $this->resolveNames($dados);

        };

        return false;

    }
}