@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Create Discount</h1>

    <form action="{{ route('admin.discounts.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">Discount Code</label>
            <input type="text" class="form-control" id="code" name="code" required>
        </div>
        <div class="mb-3">
            <label for="type" class="form-label">Discount Type</label>
            <select class="form-control" id="type" name="type" required>
                <option value="percentage">Percentage</option>
                <option value="fixed">Fixed Amount</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="value" class="form-label">Discount Value</label>
            <input type="number" class="form-control" id="value" name="value" step="0.01" required>
        </div>
        <div class="mb-3">
            <label for="starts_at" class="form-label">Start Date</label>
            <input type="date" class="form-control" id="starts_at" name="starts_at">
        </div>
        <div class="mb-3">
            <label for="expires_at" class="form-label">Expiry Date</label>
            <input type="date" class="form-control" id="expires_at" name="expires_at">
        </div>
        <div class="mb-3">
            <label for="max_uses" class="form-label">Maximum Uses</label>
            <input type="number" class="form-control" id="max_uses" name="max_uses">
        </div>
        <div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <button type="submit" class="btn btn-primary">Create Discount</button>
    </form>
</div>
@endsection

