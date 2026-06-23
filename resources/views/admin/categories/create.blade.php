@extends('layouts.header')
@section('content')
<title>@yield('title', 'Category | E-commerce')</title>

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
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Create Category</h4>
                    <a class="btn btn-secondary btn-sm" href="{{ route('category.index') }}">Back</a>
                </div>
                <div class="card-body">
                    <form action="{{ route('category.store') }}" method="POST">
                        @csrf
                        
                        <!-- Name input -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label fw-bold">Category Name <span class="text-danger">*</span></label>
                            <input type="text" id="name" name="name" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   placeholder="Enter category name"
                                   value="{{ old('name') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Slug input -->
                        <div class="form-group mb-3">
                            <label for="slug" class="form-label fw-bold">Category Slug <span class="text-danger">*</span></label>
                            <input type="text" id="slug" name="slug" 
                                   class="form-control @error('slug') is-invalid @enderror" 
                                   placeholder="Enter category slug (e.g., electronic-gadgets)"
                                   value="{{ old('slug') }}"
                                   required>
                            @error('slug')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Slug will be used in URL. Use lowercase letters, numbers, and hyphens only.</small>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                            <button type="reset" class="btn btn-secondary me-md-2">Reset</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection