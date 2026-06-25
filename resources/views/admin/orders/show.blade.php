@extends('layouts.header')
@section('content')
<title>@yield('title', 'Order Details | E-commerce')</title>

<div class="container">

    <div class="card shadow">
        <div class="card-header">
            <h4>
                Order #{{ $order->id }}
            </h4>
        </div>

        <div class="card-body">

            <div class="row mb-4">

                <div class="col-md-6">
                    <h5>Customer Information</h5>

                    <p>
                        <strong>Name:</strong>
                        {{ $order->user->name }}
                    </p>

                    <p>
                        <strong>Email:</strong>
                        {{ $order->user->email }}
                    </p>
                </div>

                <div class="col-md-6">
                    <h5>Order Information</h5>

                    <p>
                        <strong>Order ID:</strong>
                        #{{ $order->id }}
                    </p>

                    <p>
                        <strong>Total:</strong>
                        Rs. {{ number_format($order->total, 2) }}
                    </p>

                    <p>
                        <strong>Status:</strong>
                        {{ ucfirst($order->status) }}
                    </p>
                </div>

            </div>

            <hr>

            <h5>Order Items</h5>

            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($order->items as $item)

                    <tr>
                        <td>
                            {{ $item->product->name }}
                        </td>

                        <td>
                            Rs. {{ number_format($item->price, 2) }}
                        </td>

                        <td>
                            {{ $item->quantity }}
                        </td>

                        <td>
                            Rs.
                            {{ number_format($item->price * $item->quantity, 2) }}
                        </td>
                    </tr>

                    @endforeach

                </tbody>
            </table>

            <hr>

            <h5>Update Status</h5>

            <form action="{{ route('orders.status', $order->id) }}"
                method="POST">

                @csrf
                @method('PUT')

                <div class="row">

                    <div class="col-md-4">
                        <select name="status" class="form-control">

                            <option value="pending"
                                {{ $order->status == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="processing"
                                {{ $order->status == 'processing' ? 'selected' : '' }}>
                                Processing
                            </option>

                            <option value="shipped"
                                {{ $order->status == 'shipped' ? 'selected' : '' }}>
                                Shipped
                            </option>

                            <option value="delivered"
                                {{ $order->status == 'delivered' ? 'selected' : '' }}>
                                Delivered
                            </option>

                            <option value="cancelled"
                                {{ $order->status == 'cancelled' ? 'selected' : '' }}>
                                Cancelled
                            </option>

                        </select>
                    </div>

                    <div class="col-md-3">
                        <button type="submit"
                            class="btn btn-success">
                            Update Status
                        </button>
                    </div>

                </div>

            </form>

            <br>

            <a href="{{ route('orders.index') }}"
                class="btn btn-secondary">
                Back
            </a>

        </div>
    </div>

</div>
@endsection