@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="mb-4">Manage Discounts</h1>
    <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary mb-3">Create New Discount</a>

    <table class="table">
        <thead>
            <tr>
                <th>Code</th>
                <th>Type</th>
                <th>Value</th>
                <th>Valid Period</th>
                <th>Uses / Max Uses</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($discounts as $discount)
            <tr>
                <td>{{ $discount->code }}</td>
                <td>{{ ucfirst($discount->type) }}</td>
                <td>
                    @if($discount->type === 'percentage')
                        {{ $discount->value }}%
                    @else
                        ${{ number_format($discount->value, 2) }}
                    @endif
                </td>
                <td>
                    @if($discount->starts_at && $discount->expires_at)
                        {{ $discount->starts_at->format('Y-m-d') }} to {{ $discount->expires_at->format('Y-m-d') }}
                    @elseif($discount->starts_at)
                        From {{ $discount->starts_at->format('Y-m-d') }}
                    @elseif($discount->expires_at)
                        Until {{ $discount->expires_at->format('Y-m-d') }}
                    @else
                        Always valid
                    @endif
                </td>
                <td>{{ $discount->uses }} / {{ $discount->max_uses ?: 'Unlimited' }}</td>
                <td>
                    @if($discount->is_active)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-danger">Inactive</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-sm btn-primary">Edit</a>
                    <form action="{{ route('admin.discounts.destroy', $discount) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

