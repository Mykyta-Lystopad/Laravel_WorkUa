@extends('layouts.layout')

@section('content')
    <div class="row">
        <form action="{{ route('organization.storeWeb') }}" method="post" enctype="multipart/form-data">
            @csrf
            <h2>Створити організацію</h2>

            <div class="form-group">
                <input type="text" class="form-control" name="orgName" value="{{ old('orgName') ?? $organization->orgName ?? 'Назва' }}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="country" value="{{ old('country') ?? $organization->country ?? 'Країна' }}" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="city" value="{{ old('city') ?? $organization->city ?? 'Місто' }}" required>
            </div>
            <input type="submit" class="btn btn-outline-success" value="Створити організацію">
        </form>
    </div>
@endsection
