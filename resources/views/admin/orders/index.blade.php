@extends('layouts.header')
@section('content')
<title>@yield('title', 'Orders | E-commerce')</title>

<div class="container">

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <h3 class="mb-3">Orders List</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>

                    <td>
                        {{ $order->user->name }}
                    </td>

                    <td>
                        Rs. {{ number_format($order->total, 2) }}
                    </td>

                    <td>
                        @if($order->status == 'pending')
                            <span class="badge bg-warning">
                                Pending
                            </span>

                        @elseif($order->status == 'processing')
                            <span class="badge bg-info">
                                Processing
                            </span>

                        @elseif($order->status == 'shipped')
                            <span class="badge bg-primary">
                                Shipped
                            </span>

                        @elseif($order->status == 'delivered')
                            <span class="badge bg-success">
                                Delivered
                            </span>

                        @else
                            <span class="badge bg-danger">
                                Cancelled
                            </span>
                        @endif
                    </td>

                    <td>
                        {{ $order->created_at->format('d M Y') }}
                    </td>

                    <td>
                        <a href="{{ route('orders.show', $order->id) }}"
                            class="btn btn-primary btn-sm">
                            View
                        </a>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="6" class="text-center">
                        No Orders Found
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $orders->links() }}

</div>
@endsection