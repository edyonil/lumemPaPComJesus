<?php
/**
 * Created by PhpStorm.
 * User: ediaimoborges
 * Date: 20/01/16
 * Time: 21:33
 */

namespace App\Domains\Papcj\Repository;


use App\Domains\Papcj\Models\Church;

class ChurchRepositorio
{

    protected $model;

    public function __construct()
    {
        $this->model = new Church();

    }

    public function save($input)
    {

        $this->model->nome = $input['nome'];
        $this->model->resumo = $input['resumo'];
        $this->model->endereco = $input['endereco'];
        $this->model->numero = $input['numero'];
        $this->model->bairro = $input['bairro'];
        $this->model->cidade = $input['cidade'];
        $this->model->estado_nome = $input['estado_nome'];
        $this->model->estado_sigla = $input['estado_sigla'];
        $this->model->pais = $input['pais'];
        $this->model->loc = [
            'type' => 'Point',
            'coordinates' => [
                (float)$input['longitude'],
                (float)$input['latitude']
            ]
        ];
        $this->model->foto = (isset($input['foto'])) ? $input['foto'] : null;
        $this->model->cod_usuario = $input['cod_usuario'];
        $this->model->frequentadores = [];
        $this->model->visitantes = [];

        $this->model->save();

        return $this->model;

    }

    public function update($id, $input)
    {

        $this->model = $this->model->findOrNew($id);

        $this->model->nome = $input['nome'];
        $this->model->resumo = $input['resumo'];
        $this->model->endereco = $input['endereco'];
        $this->model->numero = $input['numero'];
        $this->model->bairro = $input['bairro'];
        $this->model->cep = $input['cep'];
        $this->model->cidade = $input['cidade'];
        $this->model->estado_nome = $input['estado_nome'];
        $this->model->estado_sigla = $input['estado_sigla'];
        $this->model->pais = $input['pais'];
        $this->model->loc = [
            'type' => 'Point',
            'coordinates' => [
                (float)$input['longitude'],
                (float)$input['latitude'],
            ]
        ];
        $this->model->foto = (isset($input['foto'])) ? $input['foto'] : null;
        $this->model->cod_usuario = $input['cod_usuario'];


        if(!isset($this->model->id)){
            $this->model->model->frequentadores = [];
            $this->model->model->visitantes = [];
        }

        $this->model->save();

        return $this->model;

    }

    public function all(array $input)
    {
        $dados = $this->model;/*
                      ->orderBy('created_at', 'DESC');*/

        if(isset($input['longitude']) && isset($input['latitude'])) {
            $arrayData = [
                'loc' => [
                    '$nearSphere' => [
                        '$geometry' => [
                            'type' => "Point",
                            'coordinates' => [(float)$input['longitude'], (float)$input['latitude']]
                        ],
                        '$maxDistance' => 300000
                    ],
                ],
            ];

            $dados = $dados->whereRaw($arrayData);
        }

        if (isset($input['search'])) {
            $dados = $dados->where('nome', 'like', '%' . $input['search'] . '%');
        };

        if (isset($input['cidade'])) {
            $dados = $dados->where('cidade', '=', $input['cidade']);
        };

        return $dados->skip($input['skip'])
                     ->take($input['take'])
                     ->get();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function delete($id)
    {
        $this->model = $this->model->find($id);

        return $this->model->delete();
    }

}