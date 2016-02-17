<?php
/**
 * Created by PhpStorm.
 * User: ediaimoborges
 * Date: 20/01/16
 * Time: 22:18
 */

namespace App\Domains\Papcj;


use App\Domains\Papcj\Models\Church;


class Visit
{

    protected $churchRepository;

    protected $user;

    public function __construct()
    {

        $this->churchRepository = new Repository\ChurchRepositorio();

    }

    public function userVisit($idUser, $idChurch)
    {

        $church = $this->churchRepository->find($idChurch);


        if ($this->isVisit($church, $idUser)) {

            $visits = $church->visitantes;

            $church->visitantes = null;

            unset($visits[array_search($idUser, $visits)]);

            $church->visitantes = $visits;

            $church->save();

            return true;

        } else {

            if (is_array($church->visitantes)) {

                $visits = array_merge($church->visitantes, [$idUser]);

            } else {

                $visits = [$idUser];

            }

            $church->visitantes = null;

            $church->visitantes = $visits;

            $church->save();

            return true;

        }
    }

    public function isVisit(Church $model, $idUser)
    {

        return (isset($model->visitantes) && in_array($idUser, $model->visitantes));

    }
}