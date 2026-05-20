@extends('layouts.app')

@section('content')

<main role="main" class="container-fluid">
    <div class="row">
        <div class="col-sm-2"> 
            <div class="list-group">
                <a href="{{ route('importacao.index') }}" class="list-group-item list-group-item-action">Voltar</a>
            </div>
        </div>

        <div class="col-sm-10"> 
            <div class="card bg-default">
                <h5 class="card-header">
                    <div class="row">
                        <div class="col-sm">Nova Importação</div>
                    </div>
                </h5>
                
                <div class="card-body">
                    <!-- Validation Errors -->
                                                                    
                    <form class="needs-validation" novalidate method="POST" action="{{ route('importacao.store') }}" enctype="multipart/form-data">
                        @csrf

                        @include('admin.importacao.partials.form')

                        <button type="submit" class="btn btn-primary btn-lg btn-block" name="cadastrar" value="cadastrar" style="background-color: #26385C;">Importar</button><br/> 
                    </form>  
                </div>
            </div>
        </div>
    </div>
</main>
  
@endsection

