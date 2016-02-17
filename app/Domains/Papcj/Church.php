<?php
/**
 * Created by PhpStorm.
 * User: ediaimoborges
 * Date: 20/01/16
 * Time: 22:18
 */

namespace App\Domains\Papcj;

use App\Domains\Papcj\Models\Church as ChurchModel;
use Illuminate\Http\Request;

class Church
{

    protected $churchRepository;

    protected $user;


    public function __construct()
    {
        $this->churchRepository = new Repository\ChurchRepositorio();

    }

    public function createChurch($idUser, Request $request)
    {

        $input = $request->all();

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $file->move("foto/", $input['foto']);
        }

        $dados = $this->churchRepository->save($request->all());

        return $dados->id;

    }

    public function updateChurch($id, Request $request)
    {

        $input = $request->all();

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $file->move("foto/", $input['foto']);
        }

        $dados = $this->churchRepository->update($id, $request->all());

        return $dados->id;

    }

    public function getUser()
    {
        return ($this->user) ? $this->user : null;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function getAllChurch($id = null)
    {

        if (!is_null($id)) {
            $this->setUser($id);
        }

        $itens = [
            'result' => [],
            'total' => 0,
            'hasMorePages' => false
        ];

        $dados = $this->churchRepository->all();

        if ($dados) {

            foreach($dados as $d) {

                $itens['result'][] = self::names($d);

            }

        }

        $itens['total'] = $dados->total();
        $itens['hasMorePages'] = $dados->hasMorePages();

        return $itens;

    }

    public function getChurch($idUser, $id)
    {

        $this->setUser($idUser);

        $dados = $this->churchRepository->find($id);

        if (!$dados) {
            return false;
        }

        return ['result' => $this->names($dados)];

    }

    protected function names(ChurchModel $item)
    {

        $registro = new \stdClass();

        $registro->id = $item->id;
        $registro->nome = $item->nome;
        $registro->resumo = $item->resumo;
        $registro->endereco = $item->endereco;;
        $registro->numero = $item->numero;;
        $registro->bairro = $item->bairro;;
        $registro->cidade = $item->cidade;;
        $registro->estado_nome = $item->estado_nome;;
        $registro->estado_sigla = $item->estado_sigla;;
        $registro->pais = $item->pais;;
        $registro->latitude = $item->latitude;;
        $registro->longitude = $item->longitude;;
        $registro->foto = $item->foto;
        $registro->total_frequenta = count($item->frequentadores);
        $registro->total_visita = count($item->visitantes);
        $registro->visita = self::userIsChurch($item->visitantes);
        $registro->frequenta = self::userIsChurch($item->frequentadores);
        $registro->visitantes = $item->visitantes;
        $registro->frequentadores = $item->frequentadores;

        return $registro;

    }

    protected function userIsChurch($array)
    {

        $user = $this->getUser();

        if (is_null($user)) {
            return false;
        }

        if (!is_array($array)) {
            return false;
        }

        if (in_array($user, $array)) {
            return true;
        }

        return false;

    }


}