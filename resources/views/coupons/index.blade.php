@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Cupons</h2>
    </div>
    <div class="col-md-6 text-end">
        <a class="btn btn-primary" href="{{ route('coupons.create') }}">Novo Cupom</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-light">
            <tr>
                <th>Código</th>
                <th>Valor</th>
                <th>Tipo</th>
                <th>Validade</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coupons as $coupon)
                <tr>
                    <td>{{ $coupon->code }}</td>
                    <td>{{ $coupon->discount }}</td>
                    <td>{{ $coupon->type == 'percent' ? 'Percentual' : 'Valor Fixo' }}</td>
                    <td>{{ $coupon->deadline_at?->format('d/m/Y') }}</td>
                    <td>
                        <span class="badge bg-{{ $coupon->active ? 'success' : 'danger' }}">
                            {{ $coupon->active ? 'Ativo' : 'Inativo' }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('coupons.edit', $coupon->id) }}" class="btn btn-warning btn-sm mx-1 edit-coupon">
                            <i class="fas fa-edit"></i>
                        </a>
                        <!--<button class="btn btn-danger btn-sm mx-1 delete-coupon"
                                data-id="{{ $coupon->id }}">
                            <i class="fas fa-trash"></i>
                        </button>-->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $coupons->links() }}
</div>

@endsection
