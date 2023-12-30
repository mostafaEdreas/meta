@extends('layout.main')
@section('content')
<section>
    <div class="container">
        <div class="home">
            @foreach ($carts as $cart )
            <div class="shadow  p-3 m-3 d-flex flex-column align-items-center homeCarts d-inline-block">
                <h4 class="text-center">{{$cart->name}}</h4>
                <h5 class="text-center">{{$cart->count}}</h5>
                <a href="{{route($cart->route)}}" class="shadow p-2 btnCycl btnPrimary btnHover">
                    عرض
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
