<?php
/**
 * Created by PhpStorm.
 * User: ediaimoborges
 * Date: 20/01/16
 * Time: 22:18
 */

namespace App\Domains\Papcj;

use App\Domains\Papcj\Models\User;
use App\Domains\Papcj\Repository\ChurchRepositorio;

class Frequent
{

    public function userFrequent($idUser, $idChurch, $force = 0)
    {

        $modelUser = new User();

        $user =  $modelUser->find($idUser);

        if ($this->isFrequent($user)) {

            return $this->saveFrequent($user, $idChurch);

        } else {

            if ($user->igreja_frequenta === $idChurch) {

                $this->deleteFrequent($user, $idChurch);

                return true;

            }

            if ($force == 1) {

                return $this->saveFrequent($user, $idChurch);

            }

        }

        return false;

    }

    protected function saveFrequent(User $user, $idChurch)
    {
        if ($user->igreja_frequenta) {

            $this->deleteFrequent($user, $user->igreja_frequenta);

        }

        $user->igreja_frequenta = $idChurch;

        $user->save();

        $church = new ChurchRepositorio();

        $churchRegister = $church->find($idChurch);

        $listFrequent = $churchRegister->frequentadores;

        if(is_array($listFrequent)) {

            array_push($listFrequent, $user->id);

        } else {

            $listFrequent = [$user->id];
        }

        $churchRegister->frequentadores = $listFrequent;

        $churchRegister->save();

        return true;
    }

    protected function deleteFrequent(User $user, $idChurch)
    {

        $user->igreja_frequenta = null;

        $user->save();

        $church = new ChurchRepositorio();

        $churchRegister = $church->find($idChurch);

        $listFrequent = $churchRegister->frequentadores;

        if (is_array($listFrequent)) {

            $listFrequent = $this->removerUserFrequent($listFrequent, $idChurch);

            $churchRegister->frequentadores = $listFrequent;

            $churchRegister->save();

        }

    }

    public function isFrequent(User $model)
    {

        return (!isset($model->igreja_frequenta) && is_null($model->igreja_frequenta));
    }

    protected function removerUserFrequent(array $array, $idChurch)
    {

        $novaArray = [];
        $totalArray = count($array);

        for($i=0; $i<$totalArray; $i++){

            if (isset($array[$i]) && $array[$i] =! $idChurch) {

                $novaArray[] = $array;

            }

        }

        return $novaArray;


    }

}