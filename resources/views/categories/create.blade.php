@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Add Category</h1>
        <form action="{{ route('categories.store') }}" method="POST" class="card bg-base-100 shadow-xl p-6">
            @csrf
            <div class="mb-4">
                <label for="name" class="block font-semibold mb-1">Name</label>
                <input type="text" name="name" id="name" class="input input-bordered w-full" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Category</button>
            <a href="{{ route('categories.index') }}" class="ml-4 btn btn-ghost">Cancel</a>
        </form>
    </div>
@endsection
