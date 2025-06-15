<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Homepage for the client
     * @return View
     */
    public function show(): View
    {
        $user = Cliente::query()->find(auth()->user()->email);
        return view("client.home", compact("user"));
    }
}
