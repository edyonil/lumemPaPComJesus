<?php namespace App\Domains\Papcj\Models;

use Jenssegers\Mongodb\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;


Class Church extends Eloquent{

    use SoftDeletes;

    protected $collection = 'igreja';

}