@extends('layouts.layout')

@section('content')
    <div class="row">
        <form action="{{ route('vacancy.storeWeb', ['id'=>$org->id])}}" method="post" enctype="multipart/form-data">
            @csrf
            <h2>Відкрити вакансію</h2>

            <div class="form-group">
                <input type="text" class="form-control" name="name" value="{{ old('name') ?? $vacansy->name ?? 'Назва' }}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="workers_need" value="{{ old('workers_need') ?? $vacansy->workers_need ?? 'Потрібно працівників' }}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="salary" value="{{ old('salary') ?? $vacansy->salary ?? 'Зарплатня' }}" required>
            </div>
            <input type="submit" class="btn btn-outline-success" value="Відкрити вакансію">
        </form>
    </div>
@endsection
