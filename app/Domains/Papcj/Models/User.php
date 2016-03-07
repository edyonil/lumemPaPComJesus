<?php namespace App\Domains\Papcj\Models;

use Jenssegers\Mongodb\Model as Eloquent;


Class User extends Eloquent{

    protected $collection = 'usuario';


    public function frequenta()
    {
        return $this->belongsTo('\App\Domains\Papcj\Models\Church', 'igreja_frequenta');
    }

}

