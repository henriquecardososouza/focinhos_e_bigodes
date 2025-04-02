<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Homepage for the client
     * @return View
     */
    public function show(): View
    {
        return view("intern.home");
    }
}
