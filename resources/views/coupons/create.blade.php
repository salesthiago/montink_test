@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="card-title">Novo Cupon</h5>
    </div>
    <div class="card-body">
        @include('coupons._form', ['formAction' => route('coupons.store')])
    </div>
</div>
@endsection
