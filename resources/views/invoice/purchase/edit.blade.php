@extends('layout.main')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/tables.css') }}">
@endsection
@section('content')
    <form action="{{ route('purchase.update' ,$purchase->id) }}" method="post">
        @csrf
        <input type="hidden" name="type" value="purchase">
        <section>
            @if ($errors->any())
                <div class="alert alert-danger text-start" role="alert">

                    <div>
                        <ul>

                        </ul>
                        @foreach ($errors->all() as $error)
                            <li> {{ $error }}</li>
                        @endforeach
                    </div>
                </div>
            @endif
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            <div class="d-flex justify-content-between container-md">
                <h3 class="myHeading">فاتورة مشتريات جديدة</h3>
                <a class="btn btn-primary d-flex " href="{{ route('purchase.index') }}">
                    @include('includes.show_all_btn')
                </a>
            </div>
            <hr>
            <div class="container-md">
                <div class="row">
                    <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                        <label class="">رقم الفاتورة</label>
                        <input class="form-control form-control-lg" name="reference" type="text" readonly
                            value="{{ $purchase->reference }}" aria-label="default input example">
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                        <label class="">الخصم</label>
                        <input class="form-control form-control-lg" name="discount" type="text"
                            value="{{ $purchase->discount }}" aria-label="default input example">
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                        <label class="">نوع الخصم</label>
                        <select class="form-select form-select-lg " name="discount_type"
                            aria-label=".form-select-lg example">
                            <option value="amount" {{ $purchase->discount_type == 'amount' ? 'selected' : '' }}>مبلغ</option>
                            <option value="percent" {{ $purchase->discount_type == 'percent' ? 'selected' : '' }}>نسبه مئوية
                            </option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                        <label class="">المخزن</label>
                        <select class="select2 form-select form-select-lg" name="store_id"
                            aria-label=".form-select-lg example">
                            <option value="">اختر المخزن</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}" {{ $purchase->store_id == $store->id ? 'selected' : '' }}>
                                    {{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3  justify-content-between">
                        <label class="">العميل</label>
                        <select class="select2 form-select form-select-lg " name="supplier_id"
                            aria-label=".form-select-lg example">
                            <option value="">اختر العميل</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="type" value="purchase">
                </div>
            </div>
        </section>
        <section>
            <div class="container">
                <table bpurchase="1" style="text-align: center;" class="mb-5">
                    <thead>
                        <tr>
                            <th>اجمال الفاتورة</th>
                            <th>عدد الاصناف</th>
                            <th colspan="2">اجمالى خصم الاصناف</th>
                            <th>صافى الفاتوره بعد خصم الاصناف</th>
                            <th colspan="2">خصم الفاتورة</th>
                            <th>صافى الفاتوره</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>قيمة</th>
                            <th>نسبة</th>
                            <th></th>
                            <th>قيمة</th>
                            <th>نسبة</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <!-- Your data goes here -->
                            <td id="total_invoice">0</td> <!-- Replace with actual data -->
                            <td id="total_quantity">0</td> <!-- Replace with actual data -->
                            <td id="total_discount_on_product_amount">0</td> <!-- Replace with actual data -->
                            <td id="total_discount_on_product_percent">0</td> <!-- Replace with actual data -->
                            <td id="net_on_products">0</td> <!-- Replace with actual data -->
                            <td id="total_discount_on_invoice_amount">0</td> <!-- Replace with actual data -->
                            <td id="total_discount_on_invoice_percent">0</td> <!-- Replace with actual data -->
                            <td id="net">0</td> <!-- Replace with actual data -->
                        </tr>
                        <!-- Add more rows as needed -->
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <a class="btn btn-warning" onclick="setNewRow()">صف جديد +</a>
                            <a class="btn btn-primary" data-bs-toggle="modal"data-bs-target="#appendProduct">عرض
                                المنتجات</a>
                        </tr>
                        <tr>
                            <th>م</th>
                            <th>المنتج</th>
                            <th>سعر الشراء</th>
                            <th>سعر البيع</th>
                            <th>الكمية</th>
                            <th>الخصم</th>
                            <th>نوع الخصم</th>
                            <th>الاجمالى</th>
                            <th>...</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPurchase">
                        @php
                            $num = 1;
                        @endphp
                        @foreach ($purchase->products as $key => $value)
                            <tr id="r{{ $num }}">
                                <td class="counter">{{ $num++ }}</td>
                                <td>
                                    <select class="selectT pSelect" onchange="" name="product_id[]"
                                        aria-label="form-select-lg example">
                                        <option value="">اختر المنتج</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $value->pivot->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="purchase_price[]" value="{{ $value->pivot->purchase_price }}"
                                        class="purchase_price form-control form-control-lg"></td>
                                        <td><input type="number" name="sale_price[]" value="{{ $value->pivot->sale_price }}"
                                            class="sale_price form-control form-control-lg"></td>    
                                <td><input type="number" name="quantity[]"
                                        value="{{ $value->pivot->quantity }}"class="quantity form-control form-control-lg">
                                </td>
                                <td><input type="number" value="{{ $value->pivot->discount_p }}"
                                        name="discount_p[]"class="discount form-control form-control-lg"></td>
                                <td>
                                    <select class=" type form-select form-select-lg" name="discount_type_p[]"
                                        aria-label=".form-select-lg example">
                                        <option value="amount"
                                            {{ $value->pivot->discount_type_p == 'amount' ? 'selected' : '' }}>مبلغ
                                        </option>
                                        <option value="percent"
                                            {{ $value->pivot->discount_type_p == 'percent' ? 'selected' : '' }}>نسبة
                                            مئوية
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <p class="total"> {{ number_format($value->total,2,'.','')}}</p>
                                </td>
                                <td><a class="btn btn-danger" onclick="deleteRow(this)">حذف</a></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </body>
            </div>
            <div class="d-flex justify-content-center align-items-center  mt-5">
                <button class="btn btn-success"> حفــظ</button>
            </div>
        </section>
    </form>
    <div class="modal" tabindex="-1" id="appendProduct">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">المنتجات</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row ">
                        <div class="mt-3">
                            <input type="text" name="name" class="form-control form-control-lg" id="productSearch"
                                onkeyup="getProduct()" value="{{ request()->input('name') }}" placeholder="الاسم"
                                aria-label="First name">
                        </div>
                    </div>
                    <div class="row justify-content-around " id="prodctsItems">
                        {{-- @foreach ($products as $product)
                        @endforeach --}}
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    @include('script.purchase.create')
@endsection
