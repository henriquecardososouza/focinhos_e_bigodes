<?php

namespace App\Http\Controllers;

use App\Models\Unidade;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingPageController extends Controller
{
    /**
     * The landing page view
     * @return View
     */
    public function index(): View
    {
        return view('landing');
    }

    /**
     * The units / branchs page
     * @return View
     */
    public function units(): View
    {
        $unidades = Unidade::query()
            ->orderBy("endereco")
            ->get();
        return view('units', compact('unidades'));
    }
}
