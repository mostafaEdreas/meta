@extends('layout.main')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/tables.css') }}">
@endsection
@section('content')
    <form action="{{ route('order.store') }}" method="post">
        @csrf
        <input type="hidden" name="type" value="order">
        <section>
            @php
                $orderNumber = config('site.order_prefix') . date('y') . str_pad(config('site.order_number'), 7, '0', STR_PAD_LEFT);
            @endphp
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
                <h3 class="myHeading">فاتورة مبيعات جديدة</h3>
                <a class="btn btn-primary d-flex " href="{{ route('order.index') }}">
                    @include('includes.show_all_btn')
                </a>
            </div>
            <hr>
            <div class="container-md">
                <div class="row">
                    <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                        <label class="">رقم الفاتورة</label>
                        <input class="form-control form-control-lg" name="reference" type="text" readonly
                            value="{{ $orderNumber }}" aria-label="default input example">
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                        <label class="">الخصم</label>
                        <input class="form-control form-control-lg" id="discount_invoice" name="discount" type="text"
                            value="{{ old('discount') ?? 0 }}" aria-label="default input example">
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                        <label class="">نوع الخصم</label>
                        <select class="form-select form-select-lg " id="discount_type_invoice" name="discount_type"
                            aria-label=".form-select-lg example">
                            <option value="amount">مبلغ</option>
                            <option value="percent">نسبه مئوية</option>
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                        <label class="">المخزن</label>
                        <select class="select2 form-select form-select-lg" name="store_id"
                            aria-label=".form-select-lg example">
                            <option value="">اختر المخزن</option>
                            @foreach ($stores as $store)
                                <option value="{{ $store->id }}">{{ $store->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-12 col-md-4 mt-3  justify-content-between">
                        <label class="">العميل</label>
                        <select class="select2 form-select form-select-lg " name="customer_id"
                            aria-label=".form-select-lg example">
                            <option value="">اختر العميل</option>
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                    <input type="hidden" name="type" value="order">
                </div>
            </div>
        </section>
        <section>
            <div class="container">
                <table border="1" style="text-align: center;" class="mb-5">
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
                            <th>السعر</th>
                            <th>الكمية</th>
                            <th>الخصم</th>
                            <th>نوع الخصم</th>
                            <th>الاجمالى</th>
                            <th>...</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyOrder">
                        @if (old('product_id'))
                        @foreach (old('product_id') as $key => $value)
                            <tr id="r{{ $key + 1 }}">
                                <td class="counter">{{ $key + 1 }}</td>
                                <td>
                                    <select class="selectT pSelect" onchange="" name="product_id[]"
                                        aria-label="form-select-lg example">
                                        <option value="">اختر المنتج</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}"
                                                {{ $value == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="text" name="price[]" value="{{ old('price')[$key] ? old('price')[$key] : '0' }}" class="price form-control form-control-lg"></td>
                                <td><input type="text" name="quantity[]" value="{{ old('quantity')[$key] ? old('quantity')[$key] : '1' }}"class="quantity form-control form-control-lg"></td>
                                <td><input type="text" value="{{ old('discount_p')[$key] ? old('discount_p')[$key] : '0' }}" name="discount_p[]"class="discount form-control form-control-lg"></td>
                                <td>
                                    <select class=" type form-select form-select-lg" name="discount_type_p[]" aria-label=".form-select-lg example">
                                        <option value="amount"
                                            {{ old('discount_type_p')[$key] == 'amount' ? 'selected' : '' }}>مبلغ
                                        </option>
                                        <option value="percent"
                                            {{ old('discount_type_p')[$key] == 'percent' ? 'selected' : '' }}>نسبة
                                            مئوية
                                        </option>
                                    </select>
                                </td>
                                <td>
                                    <p class="total">{{old('discount_type_p')[$key] && old('discount_type_p')[$key] == 'amount' ?number_format( old('price')[$key] * old('quantity')[$key] - old('discount_p')[$key] * old('quantity')[$key] , 2): number_format((old('price')[$key] * old('quantity')[$key]) - (old('price')[$key] * old('discount_p')[$key] * old('quantity')[$key]) / 100, 2) }}</p>
                                </td>
                                <td><a class="btn btn-danger" onclick="deleteRow(this)">حذف</a></td>
                            </tr>
                        @endforeach
                    @endif
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
    @include('script.order.create')
@endsection
