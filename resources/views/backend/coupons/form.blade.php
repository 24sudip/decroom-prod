<form action="{{ $coupon ? route('coupons.update', $coupon->id) : route('coupons.store') }}" method="POST">
    @csrf
    @if ($coupon)
        @method('PUT')
    @endif

    <div class="mb-3">
        <label>Coupon Code <span class="text-danger">*</span></label>
        <input type="text" name="code" class="form-control" required value="{{ old('code', $coupon->code ?? '') }}">
    </div>

    <div class="mb-3">
        <label>Type <span class="text-danger">*</span></label>
        <select name="type" class="form-control" required>
            <option value="fixed" {{ old('type', $coupon->type ?? '') == 'fixed' ? 'selected' : '' }}>Fixed</option>
            <option value="percent" {{ old('type', $coupon->type ?? '') == 'percent' ? 'selected' : '' }}>Percent
            </option>
        </select>
    </div>

    <div class="mb-3">
        <label>Amount <span class="text-danger">*</span></label>
        <input type="number" step="0.01" name="amount" class="form-control" required
            value="{{ old('amount', $coupon->amount ?? '') }}">
    </div>

    <div class="mb-3">
        <label>Minimum Purchase</label>
        <input type="number" step="0.01" name="min_purchase" class="form-control"
            value="{{ old('min_purchase', $coupon->min_purchase ?? '') }}">
    </div>

    <div class="mb-3">
        <label>Start Date <span class="text-danger">*</span></label>
        <input type="date" name="start_date" class="form-control" required
            value="{{ old('start_date', optional($coupon?->start_date)->format('Y-m-d')) }}">
    </div>

    <div class="mb-3">
        <label>End Date <span class="text-danger">*</span></label>
        <input type="date" name="end_date" class="form-control" required
            value="{{ old('end_date', optional($coupon?->end_date)->format('Y-m-d')) }}">
    </div>

    <div class="mb-3">
        <label>Status <span class="text-danger">*</span></label>
        <select name="status" class="form-control" required>
            <option value="1" {{ old('status', $coupon->status ?? '') == '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ old('status', $coupon->status ?? '') == '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>

    <button type="submit" class="btn btn-primary">
        {{ $coupon ? 'Update Coupon' : 'Create Coupon' }}
    </button>
</form>
