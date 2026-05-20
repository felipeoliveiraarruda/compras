<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Utils;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::guest())
        {
            return view('welcome');
        }
        else
        {
            Utils::setSession(Auth::user()->id);
            return view('admin.index');
        }

        //dd(Auth::user());

        //Utils::setSession(Auth::user()->id);

        //return view('admin');
    }
}
