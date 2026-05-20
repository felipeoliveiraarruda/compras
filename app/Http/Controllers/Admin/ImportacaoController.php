<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreImportacaoRequest;
use App\Http\Requests\Admin\UpdateImportacaoRequest;
use App\Models\Admin\Importacao;

class ImportacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.importacao.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.importacao.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImportacaoRequest $request)
    {
        $file = $request->file('arquivo');

        if (($handle = fopen($file->getPathname(), "r")) !== FALSE) 
        {         
            $data = fgetcsv($handle, 1000, ";");

            echo '<pre>';
            print_r($data);
            echo '</pre>';

            $i = 0;

            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) 
            {
                if ($data[1] == '102169' || $data[1] == '102180')
                {
                    echo '<pre>';
                    print_r($data);
                    echo '</pre>';
                }
            }

            exit;

            fclose($handle);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Importacao $importacao)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Importacao $importacao)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateImportacaoRequest $request, Importacao $importacao)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Importacao $importacao)
    {
        //
    }

    public function atualizar()
    {
        
    }
}
