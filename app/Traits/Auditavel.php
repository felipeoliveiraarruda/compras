<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait Auditavel
{
    protected static function bootAuditavel(): void
    {
        static::creating(function ($model) 
        {
            if (Auth::check()) 
            {
                $model->codigoPessoaCriacao = Auth::user()->codpes;
                $model->codigoPessoaAlteracao = Auth::user()->codpes;
            }
        });

        static::updating(function ($model) 
        {
            if (Auth::check()) 
            {
                $model->codigoPessoaAlteracao = Auth::user()->codpes;
            }
        });
    }
}