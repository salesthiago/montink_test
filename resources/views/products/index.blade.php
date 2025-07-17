@extends('layouts.app')

@section('content')
    <div class="row mb-4">
        <div class="col-md-6">
            <h2>Produtos</h2>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Novo Produto</a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Preço</th>
                                <th>Estoque</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>R$ {{ number_format($product->price, 2, ',', '.') }}</td>
                                    <td>
                                        @if ($product->variations->isEmpty())
                                            {{ $product->stocks->sum('quantity') }}
                                        @else
                                            @foreach ($product->variations as $variation)
                                                {{ $variation->name }}: {{ $variation->stocks->quantity ?? 0 }}<br>
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('products.edit', $product->id) }}"
                                            class="btn btn-sm btn-warning">Editar</a>
                                        <button class="btn btn-sm btn-success btn-buy"
                                            data-id="{{ $product->id }}">Comprar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Carrinho de Compras</h5>
                </div>
                <div class="card-body">
                    @if (session('montinks_cart'))
                        <form action="{{ route('cart.update') }}" method="POST" id="cart-form">
                            @csrf
                            <table class="table">
                                <tbody>
                                    @php $total = 0 @endphp
                                    @foreach (session('montinks_cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity'] @endphp
                                        <tr>
                                            <td>
                                                {{ $details['name'] }}
                                                @if (!empty($details['variation_name']))
                                                    <br><small>{{ $details['variation_name'] }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <input type="number" name="quantity[{{ $id }}]"
                                                    value="{{ $details['quantity'] }}" min="1"
                                                    class="form-control form-control-sm quantity-input"
                                                    style="width: 60px;">
                                            </td>
                                            <td>R$
                                                {{ number_format($details['price'] * $details['quantity'], 2, ',', '.') }}
                                            </td>
                                            <td>
                                                <a href="{{ route('cart.destroy', $id) }}"
                                                    class="btn btn-sm btn-danger">×</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><strong>Subtotal</strong></td>
                                        <td colspan="2">R$ <span
                                                id="subtotal">{{ number_format($total, 2, ',', '.') }}</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Frete</strong></td>
                                        <td colspan="2">R$ <span id="delivery">0,00</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"><strong>Total</strong></td>
                                        <td colspan="2">R$ <span
                                                id="total">{{ number_format($total, 2, ',', '.') }}</span></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <button type="submit" class="btn btn-sm btn-warning">Atualizar Carrinho</button>
                        </form>

                        <hr>

                        <form id="checkout-form" action="{{ route('checkout') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="cep" class="form-label">CEP</label>
                                <input type="text" class="form-control" id="cep" name="cep" required>
                                <button type="button" id="btn-cep" class="btn btn-sm btn-secondary mt-2">Consultar
                                    CEP</button>
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Endereço</label>
                                <input type="text" class="form-control" id="address" name="address" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="coupon_code" class="form-label">Cupom de Desconto</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="coupon_code" name="coupon_code">
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="apply-coupon">Aplicar</button>
                                </div>
                                <div id="coupon-feedback" class="mt-2"></div>
                            </div>

                            <button type="submit" class="btn btn-primary">Finalizar Pedido</button>
                        </form>
                    @else
                        <p>Seu carrinho está vazio</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para comprar produto -->
    <div class="modal fade" id="modal-buy" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="buyForm" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Adicionar ao Carrinho</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Adicionar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        window.cartData = @json(session('montinks_cart', []));
        $('#cep').mask('00000-000');
        $(document).ready(function() {

            $('.btn-buy').click(function() {
                console.log('btn-buy clicked');
                const productId = $(this).data('id');

                $.get(`/products/${productId}/edit`, function(data) {
                    const form = $(data).find('form').html();
                    let modalContent = `
            <input type="hidden" name="product_id" value="${productId}">
            <div class="mb-3">
                <label class="form-label">Quantidade</label>
                <input type="number" name="quantity" class="form-control" value="1" min="1" required>
            </div>
        `;

                    const variationsContainer = $(data).find('#variations-container');
                    if (variationsContainer.length && variationsContainer.find('option').length >
                        0) {
                        modalContent += `
                <div class="mb-3">
                    <label class="form-label">Variação</label>
                    <select name="variation_id" class="form-select">
                        ${variationsContainer.find('select').html()}
                    </select>
                </div>
            `;
                    }

                    $('#modal-body').html(modalContent);
                    $('#modal-buy').modal('show');
                }).fail(function(error) {
                    console.error('Error loading product data:', error);
                });
            });

            // Submeter formulário de compra
            $('#buyForm').submit(function(e) {
                e.preventDefault();
                const formData = $(this).serialize();
                const productId = $('input[name="product_id"]').val();

                $.post(`/cart/add/${productId}`, formData, function() {
                    location.reload();
                }).fail(function(response) {
                    alert(response.responseJSON.message || 'Erro ao adicionar ao carrinho');
                });
            });

            // Consultar CEP
            $('#btn-cep').click(function() {
                const cep = $('#cep').val().replace(/\D/g, '');

                if (cep.length !== 8) {
                    alert('CEP inválido');
                    return;
                }

                $.post('{{ route('findAddress') }}', {
                    cep: cep,
                    _token: '{{ csrf_token() }}'
                }, function(data) {
                    console.log(data, 'findAddress')
                    $('#address').val(data.address).removeAttr('readonly');
                }).fail(function() {
                    alert('CEP não encontrado');
                });
            });

            // Calcular frete quando o subtotal mudar
            $('#cart-form').on('change', '.quantity-input', function() {
                setTimeout(deliverySimulator, 300);
            });

            function deliverySimulator() {
                let subtotal = 0;
                $('.quantity-input').each(function() {
                    const id = $(this).attr('name').match(/\[(.*?)\]/)[1];

                    const cartItem = window.cartData[id];
                    if (cartItem) {
                        const price = parseFloat(cartItem.price);
                        const quantity = parseInt($(this).val());
                        subtotal += price * quantity;
                    }
                });

                $('#subtotal').text(subtotal.toFixed(2).replace('.', ','));

                $.post('{{ route('findAddress') }}', {
                    subtotal: subtotal
                }, function(data) {
                    $('#delivery').text(data.delivery);
                    const frete = parseFloat(data.delivery.replace('.', '').replace(',', '.'));
                    const total = subtotal + frete;
                    $('#total').text(total.toFixed(2).replace('.', ','));
                });
            }

            $('#apply-coupon').click(function() {
                const couponCode = $('#coupon_code').val();
                const subtotal = parseFloat($('#subtotal').text().replace(',', '.'));

                if (!couponCode) {
                    $('#coupon-feedback').html(
                        '<div class="text-danger">Por favor, insira um código de cupom</div>');
                    return;
                }

                $.post('{{ route('coupons.validate') }}', {
                    code: couponCode,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    if (response.valid) {
                        let discountAmount = 0;
                        let newTotal = parseFloat($('#total').text().replace(',', '.'));

                        if (response.coupon.type === 'percent') {
                            discountAmount = subtotal * (response.coupon.discount / 100);
                        } else {
                            discountAmount = response.coupon.discount;
                        }

                        newTotal = subtotal - discountAmount + parseFloat($('#delivery').text()
                            .replace(',', '.'));

                        // Atualiza a exibição
                        $('#coupon-feedback').html(`
                <div class="text-success">
                    Cupom aplicado: ${response.coupon.code} (${response.coupon.type === 'percent' ? response.coupon.discount + '%' : 'R$ ' + response.coupon.discount})
                    <button type="button" class="btn btn-sm btn-link text-danger" id="remove-coupon">Remover</button>
                </div>
            `);

                        // Adiciona linha de desconto
                        if (!$('#discount-row').length) {
                            $('tfoot').prepend(`
                    <tr id="discount-row">
                        <td colspan="2"><strong>Desconto</strong></td>
                        <td colspan="2">- R$ <span id="discount-amount">${discountAmount.toFixed(2).replace('.', ',')}</span></td>
                    </tr>
                `);
                        } else {
                            $('#discount-amount').text(discountAmount.toFixed(2).replace('.', ','));
                        }

                        $('#total').text(newTotal.toFixed(2).replace('.', ','));

                        // Adiciona o cupom ao formulário de checkout
                        $('#checkout-form').append(
                            `<input type="hidden" name="applied_coupon" value="${response.coupon.code}">`
                            );

                    } else {
                        $('#coupon-feedback').html(
                            '<div class="text-danger">Cupom inválido ou expirado</div>');
                    }
                }).fail(function() {
                    $('#coupon-feedback').html(
                        '<div class="text-danger">Erro ao validar cupom</div>');
                });
            });

            // Remover cupom
            $(document).on('click', '#remove-coupon', function() {
                const subtotal = parseFloat($('#subtotal').text().replace(',', '.'));
                const delivery = parseFloat($('#delivery').text().replace(',', '.'));
                const total = subtotal + delivery;

                $('#discount-row').remove();
                $('#total').text(total.toFixed(2).replace('.', ','));
                $('input[name="applied_coupon"]').remove();
                $('#coupon-feedback').html('');
                $('#coupon_code').val('');
            });
            // Calcular frete ao carregar a página
            if ($('#subtotal').text()) {
                calculateFrete();
            }
        });
    </script>
@endsection
