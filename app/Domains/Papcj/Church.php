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

    protected $longitude;

    protected $latitude;

    protected $limit = 10;


    public function __construct()
    {
        $this->churchRepository = new Repository\ChurchRepositorio();

    }

    /**
     * @param $idUser
     * @param Request $request
     * @return mixed
     */
    public function createChurch($idUser, Request $request)
    {

        $input = $request->all();

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $file->move("foto/", $input['foto']);
        }

        $input['cod_usuario'] = $idUser;

        $dados = $this->churchRepository->save($input);

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

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @param mixed $limit
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getAllChurch($id = null, $input = null)
    {

        if (!is_null($id)) {
            $this->setUser($id);
        }

        $itens = [
            'result' => [],
            'hasMorePages' => false
        ];

        $input['skip'] = $this->calulatePages();
        $input['take'] = $this->getLimit();

        if (isset($input['page'])) {

            $input['skip'] = $this->calulatePages($input['page']);
            $input['take'] = $this->getLimit();

        }


        $dados = $this->churchRepository->all($input);

        if (!$dados) {
            return  [
                'result' => [],
                'hasMorePages' => false
            ];
        }

        if (isset($input['longitude']) && isset($input['latitude'])) {
            $this->setLongitude($input['longitude']);
            $this->setLatitude($input['latitude']);
        }

        if ($dados) {

            foreach($dados as $d) {

                $itens['result'][] = self::names($d);

            }

        }

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
        $registro->endereco = $item->endereco;
        $registro->numero = $item->numero;
        $registro->bairro = $item->bairro;
        $registro->cidade = $item->cidade;
        $registro->estado_nome = $item->estado_nome;
        $registro->estado_sigla = $item->estado_sigla;
        $registro->pais = $item->pais;
        $registro->latitude = $item->loc['coordinates'][1];
        $registro->longitude = $item->loc['coordinates'][0];
        $registro->foto = $item->foto;
        $registro->total_frequenta = count($item->frequentadores);
        $registro->total_visita = count($item->visitantes);
        $registro->visita = self::userIsChurch($item->visitantes);
        $registro->frequenta = self::userIsChurch($item->frequentadores);
        $registro->visitantes = $item->visitantes;
        $registro->frequentadores = $item->frequentadores;
        $registro->distancia = $this->distance($item->loc['coordinates'][0], $item->loc['coordinates'][1]);

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

    protected function distance($lat2, $lon2, $unit = "K")
    {

        if (!$this->getLatitude()) {

            return 0;

        }

        $lat1 = $this->getLatitude();
        $lon1 = $this->getLongitude();

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    protected function calulatePages($page = 1)
    {
        return ($page * $this->getLimit() - $this->getLimit());

    }


}