@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Expense Reports</h1>
        <form method="POST" action="{{ route('reports.filter') }}" class="mb-4 flex flex-wrap gap-2">
            @csrf
            <select name="month" class="border rounded px-2 py-1">
                <option value="">Month</option>
                @for ($m = 1; $m <= 12; $m++)
                    <option value="{{ $m }}">{{ date('F', mktime(0, 0, 0, $m, 1)) }}</option>
                @endfor
            </select>
            <select name="category_id" class="border rounded px-2 py-1">
                <option value="">Category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Filter</button>
        </form>
        <table class="min-w-full bg-white rounded shadow">
            <thead>
                <tr>
                    <th class="py-2 px-4">Amount</th>
                    <th class="py-2 px-4">Category</th>
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td class="py-2 px-4">${{ $expense->amount }}</td>
                        <td class="py-2 px-4">{{ $expense->category->name }}</td>
                        <td class="py-2 px-4">{{ $expense->date }}</td>
                        <td class="py-2 px-4">{{ $expense->note }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
