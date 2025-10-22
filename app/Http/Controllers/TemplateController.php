<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    //
    public function alerts(){
        return view('template.basic-ui.alerts');
    }
}
