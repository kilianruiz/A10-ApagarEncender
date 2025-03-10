<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incidencia;
use App\Models\User;

class TecnicoController extends Controller
{
    public function index(){
        return view('/crudTecnico.index');
    }
}
