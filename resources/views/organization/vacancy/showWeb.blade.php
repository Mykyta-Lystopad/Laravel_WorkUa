@extends('layouts.layout')

@section('content')

    <div class="row">
        @foreach($vacancies as $vacancy)
            <div class="col-6">
                <div class="card  mb-2">
                    <div class="card-header"><h2>{{ $vacancy->name  }}</h2></div>
                    <div class="card-body">
                        {{--                    <div class="card-img" style="background-image: url( {{ $post->img ?? asset('img/default.jpg') }} )"></div>--}}
                        <div class="card-author">Вакансій всього: {{ $vacancy->workers_need  }}</div>
                        <div class="card-author">Вакансій доступно: {{ $counter = $vacancy->workers_need  }}</div>
                        <div class="card-author">Зарплатня: {{ $vacancy->salary  }}</div>
                        {{--                    <a href="{{ route('vacancy.', ['id'=> $post->post_id]) }}"--}}
                        {{--                       class="btn btn-outline-primary">Посмотреть вакансии</a>--}}
                        <a href="#" class="btn btn-outline-primary mt-2">Підписатися</a>
                        <a href="#" class="btn btn-outline-primary mt-2">Відписатися</a>
                        <a href="#" class="btn btn-outline-primary mt-2">Редагувати</a>
                        <form action="{{ route('vacancy.destroyWeb', ['id'=>$vacancy->id]) }}" method="post"
                              onsubmit="if(confirm('Точно видалити вакансію?')){ return true} else {return false}">
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-outline-danger delete-btn mt-2" value="Видалити вакансію?">
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

@endsection

