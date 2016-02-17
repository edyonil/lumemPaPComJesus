<?php namespace App\Domains\Papcj\Models;

use Jenssegers\Mongodb\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;


Class User extends Eloquent{

    use SoftDeletes;

    protected $collection = 'usuario';


    public function frequenta()
    {
        return $this->belongsTo('\App\Domains\Papcj\Models\Church', 'igreja_frequenta');
    }

}

