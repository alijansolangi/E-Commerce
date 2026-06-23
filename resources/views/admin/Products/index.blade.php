@extends('layouts.header')
@section('content')
<title>@yield('title', 'Products | E-commerce')</title>

<div class="container mt-4">
    {{-- Success Alert --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Error Alert --}}
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Error!</strong> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0">📦 Products</h3>
        <a class="btn btn-primary" href="{{ route('products.create') }}">
            <i class="fas fa-plus me-1"></i> Add New Product
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                @if($product->image && file_exists(public_path('storage/' . $product->image)))
                                    <img src="{{ asset('storage/' . $product->image) }}" 
                                         alt="{{ $product->name }}" 
                                         width="50" 
                                         height="50" 
                                         class="rounded">
                                @else
                                    <img src="{{ asset('images/no-image.png') }}" 
                                         alt="No Image" 
                                         width="50" 
                                         height="50" 
                                         class="rounded">
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td><code>{{ $product->slug }}</code></td>
                            <td>
                                <span class="badge bg-info">
                                    {{ $product->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->stock > 10)
                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning">{{ $product->stock }}</span>
                                @else
                                    <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                @if($product->status == 'active')
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('products.show', $product->id) }}" 
                                       class="btn btn-info text-white" 
                                       title="View">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $product->id) }}" 
                                       class="btn btn-warning text-white" 
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('products.destroy', $product->id) }}" 
                                          method="POST" 
                                          style="display:inline-block;"
                                          onsubmit="return confirm('Are you sure you want to delete this product?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center py-4">
                                <i class="fas fa-box-open fa-2x text-muted mb-2 d-block"></i>
                                <span class="text-muted">No products found. Click "Add New Product" to create one.</span>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="d-flex justify-content-end mt-3">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection