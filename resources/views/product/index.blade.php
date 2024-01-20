@extends('layout.main')
@section('style')
@endsection
@section('content')
    <section class="">
        <div class="d-flex justify-content-between container-md">
            <h3 class="myHeading"> المستخدمين</h3>
            <a class="btn btn-primary "onclick="makeSaveModal()" data-bs-toggle="modal" data-bs-target="#dailoge">
                جديد +
            </a>
        </div>
        <div class="container-md shadow">
            <form action="{{ route('product.index') }}" method="get">

                <div class="row justify-content-between">
                    <div class=" col-sm-12 col-md-4 mt-3 ">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0 " type="radio" name ='active' value="1"
                                id="active" @if (request()->input('active') === null || request()->input('active') === '1') checked @endif>
                        </div>
                        <label class="form-control form-control-lg" for="active">
                            نشط
                        </label>
                    </div>
                    <div class=" col-sm-12 col-md-4 mt-3">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="radio" name ='active' value="0" id="unActive"
                                @if (request()->input('active') === '0') checked @endif>
                        </div>
                        <label class="form-control form-control-lg" for="unActive">
                            غير نشط
                        </label>
                    </div>
                    <div class=" col-sm-12 col-md-4 mt-3 ">
                        <div class="input-group-text">
                            <input class="form-check-input mt-0" type="radio" name ='active' value="2"
                                id="all"@if (request()->input('active') === '2') checked @endif>
                        </div>
                        <label class="form-control" for="all">
                            الكل
                        </label>
                    </div>
                </div>
                <div class="row ">
                    <div class="mt-3">
                        <input type="text" name="name" class="form-control form-control-lg"
                            value="{{ request()->input('name') }}" placeholder="الاسم" aria-label="First name">
                    </div>
                </div>

                <button class="btn btn-primary mt-5" type='submit'>
                    فــــرز
                </button>
            </form>
        </div>
    </section>
    <section>
        <div class="container-md shadow">
            <div class="row justify-content-around ">
                @foreach ($products as $product)
                    <div class="card col-xs-12 col-sm-6 col-md-4 col-lg-3 mt-3 p-0" style="width: 18rem;">
                        <div class="card-img-top" style=""> 
                            @if ($product->img)
                                <img src="{{ URL::to('images/product') }}/{{ $product->img }}" alt="...">
                             @else
                                <img src="{{ URL::to('images/product/default.webp') }}" alt="...">
                            @endif
                        </div>
                        <div class="card-body ">
                            <p class="card-text p-2 bg-light">{{ $product->name }}</p>
                            <h5 class="card-title bg-info p-2">السعر: {{ $product->price }}</h5>
                            <div class="d-flex">
                                <button
                                    class="form-control rounded-0 btn {{ $product->deleted_at ? 'btn-success' : 'btn-danger' }}"
                                    onclick="{{ $product->deleted_at ? 'makeRestoreModal(' . json_encode($product) . ')' : 'makeDeleteModal(' . json_encode($product) . ')' }}"
                                    data-bs-toggle="modal" data-bs-target="#dailoge">
                                    @if ($product->deleted_at)
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-arrow-return-left" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M14.5 1.5a.5.5 0 0 1 .5.5v4.8a2.5 2.5 0 0 1-2.5 2.5H2.707l3.347 3.346a.5.5 0 0 1-.708.708l-4.2-4.2a.5.5 0 0 1 0-.708l4-4a.5.5 0 1 1 .708.708L2.707 8.3H12.5A1.5 1.5 0 0 0 14 6.8V2a.5.5 0 0 1 .5-.5" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5m3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0z" />
                                            <path
                                                d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4zM2.5 3h11V2h-11z" />
                                        </svg>
                                    @endif
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="text-center" colspan="6">
            @include('includes.pagination', ['paginator' => $products])
        </div>
    </section>
    <script>
        function setToLocalStorage(product, type) {
            data = JSON.parse(localStorage.getItem('{{ auth()->user()->id }}' + type)) || [];
            var checkIfExisting = data.findIndex(function(existingProduct) {
                return existingProduct.id === product.id;
            });
            if (checkIfExisting > -1) {
                data[checkIfExisting].quantity = data[checkIfExisting].quantity + 1;
            } else {
                newItem = {'id':product.id,'name':product.name,'price':product.price,'discount':0,'discount_type':'amount','quantity':1}
                product.quantity = 1;
                data.push(product)
            }
            localStorage.setItem('{{ auth()->user()->id }}' + type, JSON.stringify(data))
            if (data.length > 0) {
                if (type == 'order') {
                    $('#saleLink').html(`المبيعات<span class="bg-danger pe-1 rounded-3 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                        <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l.5 2H5V5zM6 5v2h2V5zm3 0v2h2V5zm3 0v2h1.36l.5-2zm1.11 3H12v2h.61zM11 8H9v2h2zM8 8H6v2h2zM5 8H3.89l.5 2H5zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
                    </svg>
                    </span>`)
                } else if (type == 'purchase') {
                    $('#purchLink').html(`المشتريات<span class="bg-danger pe-1 rounded-3 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart4" viewBox="0 0 16 16">
                    <path d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5M3.14 5l.5 2H5V5zM6 5v2h2V5zm3 0v2h2V5zm3 0v2h1.36l.5-2zm1.11 3H12v2h.61zM11 8H9v2h2zM8 8H6v2h2zM5 8H3.89l.5 2H5zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0m9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2m-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0"/>
                </svg>
            </span>`)
                }
            }
        }
    </script>
@endsection
