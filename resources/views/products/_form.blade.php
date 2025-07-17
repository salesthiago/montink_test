<form action="{{ $formAction }}" method="POST">
    @if (isset($method))
        @method($method)
    @endif
    @csrf

    <div class="mb-3">
        <label for="name" class="form-label">Nome</label>
        <input type="text" class="form-control" id="name" name="name" value="{{ $product->name ?? old('name') }}"
            required>
    </div>

    <div class="mb-3">
        <label for="price" class="form-label">Preço</label>
        <input type="text" class="form-control price" id="price" name="price"
            value="{{ $product->price ?? old('price') }}" required>
    </div>

    <div class="mb-3">
        <label for="quantity" class="form-label">Estoque</label>
        <input type="number" class="form-control" id="quantity" name="quantity"
            value="{{ isset($product->stocks) ? $product->stocks->where('variation_id', null)->sum('quantity') : old('quantity') }}"
            required>
    </div>

    <div class="mb-3">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="has_variations" name="has_variations"
                {{ isset($product) && $product->variations->isNotEmpty() ? 'checked' : '' }}>
            <label class="form-check-label" for="has_variations">
                Este produto tem variações
            </label>
        </div>
    </div>

    <div id="variations-container"
        style="{{ !isset($product) || $product->variations->isEmpty() ? 'display: none;' : '' }}">
        <h5>Variações</h5>
        <div id="variations-list">
            @if (isset($product) && $product->variations->isNotEmpty())

                @foreach ($product->variations as $index => $variations)
                    <div class="variation-item mb-3 border p-3">
                        <input type="hidden" name="variations[{{ $index }}][id]" value="{{ $variations->id }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Nome</label>
                                <input type="text" class="form-control" name="variations[{{ $index }}][name]"
                                    value="{{ $variations->name }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Valor</label>
                                <input type="text" class="form-control price"
                                    name="variations[{{ $index }}][price]" value="{{ $variations->price }}"
                                    required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Estoque</label>
                                <input type="number" class="form-control"
                                    name="variations[{{ $index }}][quantity]"
                                    value="{{ $variations->stocks->quantity ?? 0 }}" required>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-danger mt-2 remove-variation">Remover</button>
                    </div>
                @endforeach
            @endif
        </div>
        <button type="button" id="add-variations" class="m-4 btn btn-sm btn-secondary">Adicionar Variação</button>
    </div>

    <button type="submit" class="btn btn-primary">Salvar</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancelar</a>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.price').mask('R$ #.##0,00', {
                reverse: true,
                translation: {
                    '#': {
                        pattern: /[0-9]/,
                        optional: true
                    }
                }
            });
            // Mostrar/ocultar seção de variações
            $('#has_variations').change(function() {
                console.log('has_variation')
                if ($(this).is(':checked')) {
                    $('#variations-container').show();
                    $('#quantity').val(0).prop('readonly', true);
                } else {
                    $('#variations-container').hide();
                    $('#quantity').prop('readonly', false);
                }
            });

            // Adicionar nova variação
            $('#add-variations').click(function() {
                console.log('cliquei em add-variations')
                const index = Date.now();
                const variacaoHtml = `
            <div class="variation-item mb-3 border p-3">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Nome</label>
                        <input type="text" class="form-control" name="variations[${index}][name]" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Valor</label>
                        <input type="text" class="form-control price" name="variations[${index}][price]" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Estoque</label>
                        <input type="number" class="form-control" name="variations[${index}][quantity]" required>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-danger mt-2 remove-variation">Remover</button>
            </div>
        `;
                $('#variations-list').append(variacaoHtml);
            });

            // Remover variação
            $(document).on('click', '.remove-variation', function() {
                $(this).closest('.variation-item').remove();
            });
        });
    </script>
@endsection
