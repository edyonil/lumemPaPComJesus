<?php namespace App\Http\Controllers\AppMobile;


use App\Domains\Papcj\Church;
use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class ChurchController extends BaseController
{
    //
    protected $church;


    public function __construct(Church $church)
    {
        $this->church = $church;

    }

    public function getIndex($idUser = null, $id = null, Request $request)
    {

        $input = $request->all();

        if ($id == null) {

            $dados = $this->church->getAllChurch($idUser, $input);

        } else {

            $dados = $this->church->getChurch($idUser, $id);

        }

        return response()->json(['message' => $dados]);
    }

    public function postIndex($idUser, Request $request)
    {

        $dados = $this->church->createChurch($idUser, $request);

        return response()->json(['message' => $dados]);
    }
}
