@extends('layouts.header')
@section('content')
<title>@yield('title', 'Edit Product | E-commerce')</title>

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

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Error!</strong>
            <ul class="mb-0 mt-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-12 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="h5 mb-1 section-title">
                            <i class="bi bi-pencil-square" aria-hidden="true"></i>
                            <span>Edit Product</span>
                        </h2>
                        <p class="text-muted mb-0">Update the product details.</p>
                    </div>
                    <a class="btn btn-secondary btn-sm" href="{{ route('products.index') }}">
                        <i class="bi bi-arrow-left"></i> Back
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        @csrf
                        @method('PUT')
                        
                        <div class="row g-3">
                            <!-- Product Name -->
                            <div class="col-md-6">
                                <label class="form-label" for="name">
                                    Product Name <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       type="text" 
                                       placeholder="Enter product name"
                                       value="{{ old('name', $product->name) }}"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <label class="form-label" for="slug">
                                    Slug <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" 
                                       name="slug" 
                                       type="text" 
                                       placeholder="e.g., electronic-gadgets"
                                       value="{{ old('slug', $product->slug) }}"
                                       required>
                                <small class="text-muted">Use lowercase letters, numbers, and hyphens only.</small>
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div class="col-md-6">
                                <label class="form-label" for="category_id">
                                    Category <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" 
                                        name="category_id" 
                                        required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Price -->
                            <div class="col-md-6">
                                <label class="form-label" for="price">
                                    Price ($) <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('price') is-invalid @enderror" 
                                       id="price" 
                                       name="price" 
                                       type="number" 
                                       step="0.01" 
                                       min="0"
                                       placeholder="0.00"
                                       value="{{ old('price', $product->price) }}"
                                       required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Stock -->
                            <div class="col-md-6">
                                <label class="form-label" for="stock">
                                    Stock Quantity <span class="text-danger">*</span>
                                </label>
                                <input class="form-control @error('stock') is-invalid @enderror" 
                                       id="stock" 
                                       name="stock" 
                                       type="number" 
                                       min="0"
                                       placeholder="0"
                                       value="{{ old('stock', $product->stock) }}"
                                       required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label" for="status">
                                    Status <span class="text-danger">*</span>
                                </label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="">Select Status</option>
                                    <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Image -->
                            <div class="col-12">
                                <label class="form-label" for="image">Product Image</label>
                                
                                {{-- Current Image Preview --}}
                                @if($product->image && file_exists(public_path('storage/' . $product->image)))
                                    <div class="mb-2">
                                        <img src="{{ asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             width="100" 
                                             height="100" 
                                             class="rounded border">
                                        <br>
                                        <small class="text-muted">Current Image</small>
                                    </div>
                                @endif
                                
                                <input class="form-control @error('image') is-invalid @enderror" 
                                       id="image" 
                                       name="image" 
                                       type="file" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif">
                                <small class="text-muted">Allowed: JPEG, PNG, JPG, GIF. Max size: 2MB. Leave empty to keep current image.</small>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="5" 
                                          placeholder="Enter product description">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-check-circle"></i> Update Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection