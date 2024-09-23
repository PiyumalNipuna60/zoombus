<?php

namespace App\Http\Controllers;


class HomePageController extends Controller
{
    public function __invoke()
    {
        $data = Controller::essentialVars();
        return view('home', $data);
    }
}
