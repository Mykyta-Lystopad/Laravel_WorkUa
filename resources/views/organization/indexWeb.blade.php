@extends('layouts.layout')

@section('content')

    <div class="row">

        @if(isset($_GET['search']))
            @if(count($organizations)>0)
                    <h2>По запиту "<?=$_GET['search']?>" знайдено {{ count($organizations)  }} вакансія(ї,й)</h2>
            @else
                <h2>По запиту "<?=htmlspecialchars($_GET['search'])?>" нічого не знайдено</h2>
                <a href="{{ route('organization.indexWeb') }}" class="btn btn-outline-primary">До всих компаній</a>
            @endif
        @endif

        @foreach($organizations as $company)
            <div class="col-6">
                <div class="card  mb-2">
                    <div class="card-header"><h2>{{ $company->orgName  }}</h2></div>
                    <div class="card-body">
                        {{--                    <div class="card-img" style="background-image: url( {{ $post->img ?? asset('img/default.jpg') }} )"></div>--}}
                        <div class="card-author">Країна: {{ $company->country  }}</div>
                        <div class="card-author">Місто: {{ $company->city  }}</div>
                        {{--                    <a href="{{ route('vacancy.', ['id'=> $post->post_id]) }}"--}}
                        {{--                       class="btn btn-outline-primary">Посмотреть вакансии</a>--}}
                        <a href="{{ route('vacancy.showWeb', [$company]) }}"
                           class="btn btn-outline-primary mt-2">
                            Дивитись вакансії
                        </a>
                        <a href="{{ route('vacancy.createWeb', [$company]) }}"
                           class="btn btn-outline-primary mt-2">
                            Відкрити вакансію
                        </a>
                        <form action="{{ route('organization.destroyWeb', ['id'=>$company->id]) }}" method="post"
                              onsubmit="if(confirm('Точно видалити організацію?')){ return true} else {return false}"
                              class="mt-2"
                        >
                            @csrf
                            @method('DELETE')
                            <input type="submit" class="btn btn-outline-danger delete-btn mt-2" value="Видалити організацію">
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
{{--    @if(!isset($_GET['search']))--}}
{{--        {{ $organizations->links()  }}--}}
{{--    @endif--}}

    {{--    @if($errors->any())--}}
    {{--        @foreach($errors->all() as $error)--}}
    {{--            <div class="alert alert-danger alert-dismissible fade show" role="alert">--}}
    {{--                {{ $error }}--}}
    {{--                <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
    {{--                    <span aria-hidden="true">&times;</span>--}}
    {{--                </button>--}}
    {{--            </div>--}}
    {{--        @endforeach--}}
    {{--    @endif--}}
    {{--    @if ( session('success'))--}}
    {{--        <div class="alert alert-success alert-dismissible fade show" role="alert">--}}
    {{--            {{ session('success') }}--}}
    {{--            <button type="button" class="close" data-dismiss="alert" aria-label="Close">--}}
    {{--                <span aria-hidden="true">&times;</span>--}}
    {{--            </button>--}}
    {{--        </div>--}}
    {{--    @endif--}}
    {{--    @yield('content')--}}

@endsection

