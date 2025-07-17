@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Novo Produto</h5>
    </div>
    <div class="card-body">
        @include('products._form', ['formAction' => route('products.store')])
    </div>
</div>
@endsection
