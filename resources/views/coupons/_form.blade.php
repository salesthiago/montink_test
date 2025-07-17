<form id="couponForm" method="POST"
    action="{{ isset($coupon) ? route('coupons.update', $coupon->id) : route('coupons.store') }}">
    @csrf
    @if (isset($coupon))
        @method('PUT')
    @endif

    <div class="card-body">
        <div class="mb-3">
            <label for="code" class="form-label">Código do Cupom</label>
            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code"
                value="{{ old('code', $coupon->code ?? '') }}" required>
            @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="discount" class="form-label">Valor do Desconto</label>
                <input type="text" class="form-control @error('discount') is-invalid @enderror"
                    id="discount" name="discount" value="{{ old('discount', $coupon->discount ?? '') }}" required>
                @error('discount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label for="type" class="form-label">Tipo de Desconto</label>
                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                    <option value="percent" {{ old('type', $coupon->type ?? '') == 'percent' ? 'selected' : '' }}>
                        Percentual (%)</option>
                    <option value="amount" {{ old('type', $coupon->type ?? '') == 'amount' ? 'selected' : '' }}>Valor
                        Fixo (R$)</option>
                </select>
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        @php
            $deadlineValue = old('deadline_at');
            if (isset($coupon) && $coupon->deadline_at) {
                $deadlineValue = $coupon->deadline_at?->format('Y-m-d');
            }
        @endphp

        <div class="mb-3">
            <label for="deadline_at" class="form-label">Data de Validade</label>
            <input type="date" class="form-control @error('deadline_at') is-invalid @enderror" id="deadline_at"
                name="deadline_at" value="{{ $deadlineValue }}" min="{{ date('Y-m-d') }}" required>
            @error('deadline_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" id="active" name="active" value="1"
                {{ old('active', $coupon->active ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="active">Cupom Ativo</label>
        </div>
    </div>

    <div class="text-end">
        <a href="{{ route('coupons.index') }}" class="btn btn-secondary">Cancelar</a>
        <button type="submit" class="btn btn-primary">
            <i class="fas fa-save me-1"></i> {{ isset($coupon) ? 'Atualizar' : 'Salvar' }}
        </button>
    </div>
</form>
@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.min.js"></script>
    <script>
         $(document).ready(function() {
            $('#discount').mask('R$ #.##0,00', {
                reverse: true,
                translation: {
                    '#': {
                        pattern: /[0-9]/,
                        optional: true
                    }
                }
            });
        });
        </script>
@endpush
