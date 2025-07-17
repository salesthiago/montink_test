@extends('layouts.app')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h2>Pedidos</h2>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead class="table-light">
            <tr>
                <th>Endereço</th>
                <th>Total</th>
                <th>Frete</th>
                <th>Status</th>
                <th>Data</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <td>{{ $order->address }}</td>
                    <td>R$ {{ number_format($order->total, 2, ',', '.') }}</td>
                    <td>R$ {{ number_format($order->delivery, 2, ',', '.') }}</td>
                    <td>
                        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : 'success' }}">
                            {{ $order->status == 'pending' ? 'Pendente' : 'Pago' }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $orders->links() }}
</div>

@endsection

