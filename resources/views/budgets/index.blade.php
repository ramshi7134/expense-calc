@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Monthly Budgets</h1>
        <a href="{{ route('budgets.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Set
            Budget</a>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="py-2 px-4">Category</th>
                    <th class="py-2 px-4">Limit</th>
                    <th class="py-2 px-4">Month</th>
                    <th class="py-2 px-4">Year</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($budgets as $budget)
                    <tr>
                        <td class="py-2 px-4">{{ $budget->category->name }}</td>
                        <td class="py-2 px-4">${{ $budget->limit }}</td>
                        <td class="py-2 px-4">{{ $budget->month }}</td>
                        <td class="py-2 px-4">{{ $budget->year }}</td>
                        <td class="py-2 px-4">
                            <a href="{{ route('budgets.edit', $budget) }}" class="text-blue-600">Edit</a>
                            <form action="{{ route('budgets.destroy', $budget) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
