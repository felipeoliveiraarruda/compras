<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class Utils extends Model
{
    use HasApiTokens, HasFactory, Notifiable;
    use \Spatie\Permission\Traits\HasRoles;
    use \Uspdev\SenhaunicaSocialite\Traits\HasSenhaunica;

    public static function setSession($id)
    {
        $level = User::obterLevel($id);        
        session(['level' => '']);
        session(['level' => $level]);

        $vinculos = User::obterVinculos($id);
        session(['vinculos' => '']);
        session(['vinculos' => $vinculos]);
    }
}
