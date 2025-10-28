<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    //
    public function alerts(){
        return view('template.basic-ui.alerts');
    }

    public function badge(){
        return view('template.basic-ui.badge');
    }

    public function breadcrumb(){
        return view('template.basic-ui.breadcrumb');
    }
    
    public function buttonGroup(){
        return view('template.basic-ui.button-group');
    }

    public function buttons(){
        return view('template.basic-ui.buttons');
    }

    public function cards(){
        return view('template.basic-ui.cards');
    }

    public function dropdowns(){
        return view('template.basic-ui.dropdowns');
    }

    public function imageFigures(){
        return view('template.basic-ui.image-figures');
    }

    public function linkInteraction(){
        return view('template.basic-ui.link-interactions');
    }

    public function listGroup(){
        return view('template.basic-ui.list-group');
    }

    public function navs(){
        return view('template.basic-ui.navs');
    }

    public function objectFit(){
        return view('template.basic-ui.object-fit');
    }

    public function pagination(){
        return view('template.basic-ui.pagination');
    }

    public function popovers(){
        return view('template.basic-ui.popovers');
    }

    public function progress(){
        return view('template.basic-ui.progress');
    }

    public function spinners(){
        return view('template.basic-ui.spinners');
    }

    public function toasts(){
        return view('template.basic-ui.toasts');
    }

    public function tooltips(){
        return view('template.basic-ui.tooltips');
    }

    public function typography(){
        return view('template.basic-ui.typography');
    }
}
