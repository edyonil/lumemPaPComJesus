<?php
/**
 * Created by PhpStorm.
 * User: ediaimoborges
 * Date: 20/01/16
 * Time: 22:18
 */

namespace App\Domains\Papcj;

use App\Domains\Papcj\Models\Church as ModelChurch;
use App\Domains\Papcj\Models\User;


class Visit
{

    protected $church;

    protected $user;

    public function __construct()
    {

        $this->church = new Church();
        $this->user = new User();

    }

    /**
     * Gerenciar o processo de visitante
     *
     * @param $idUser
     * @param $idChurch
     * @return bool
     * @throws \Exception
     */
    public function userVisit($idUser, $idChurch)
    {

        $church = $this->church->getChurch($idUser, $idChurch);

        if (!$church) {
            throw new \Exception("Igreja não encontrada");
        }

        $church = $church['result'];

        $user = $this->user->find($idUser);

        if (!$user) {
            throw new \Exception("Usuáro não encontrado");
        }


        if ($this->isVisit($church, $user)) {


            unset($church->visitantes[array_search($user->id, $church->visitantes)]);

            $this->saveOrUpdateVisit($church->id, $church->visitantes);

            $churchVisits = $user->igreja_visita;

            $user->igreja_visita = $this->removeUserChurch($churchVisits, $church->id);

            $user->save();

            return true;

        } else {

            if (is_array($church->visitantes)) {

                $visits = array_merge($church->visitantes, [$idUser]);

            } else {

                $visits = [$idUser];

            }

            $this->saveOrUpdateVisit($church->id, $visits);

            $newChurchVisit = [
                'data' => date('Y-m-d H:i:s'),
                'igreja' => $church
            ];

            $arrayChurchVisits = array_merge([$newChurchVisit], $user->igreja_visita);

            $user->igreja_visita = $arrayChurchVisits;

            $user->save();

            return true;

        }
    }

    protected function isVisit($chuch, User $idUser)
    {

        return (isset($chuch->visitantes) && in_array($idUser->id, $chuch->visitantes));

    }


    protected function saveOrUpdateVisit($id, $array)
    {
        $model = ModelChurch::find($id);

        $model->visitantes = $array;

        $model->save();

        return $model;

    }


    protected function removeUserChurch($array, $idChurch)
    {

        $newArray = [];

        foreach($array as $a) {
            if(!($a['igreja']['id'] == $idChurch)){
                $newArray[] = $a;
            }
        }

        return $newArray;

    }
}