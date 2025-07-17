@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Editar Produto</h5>
    </div>
    <div class="card-body">
        <div id="variations-container">
            @if($product->variations->isNotEmpty())
                <select class="form-select">
                    @foreach($product->variations as $variation)
                        <option value="{{ $variation->id }}">
                            {{ $variation->name }} - {{ $variation->value }} (Estoque: {{ $variation->stocks->quantity ?? 0 }})
                        </option>
                    @endforeach
                </select>
            @endif
        </div>
        @include('products._form', ['formAction' => route('products.update', $product->id), 'method' => 'PUT'])
    </div>
</div>
@endsection
