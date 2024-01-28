@extends('layout.main')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/paginate.css') }}">
    <style>
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* width: 300px; */
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        .select2{
            margin-bottom: 16px;
            width: calc(100% - 16px);
            border-radius: 4px;
            box-sizing: border-box;
        }
        input {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
@endsection

@section('content')
    <section>
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-3 col-md-12">
                    <form action="{{ route('order.index') }}" method="GET">
                        <label for="fromDate">من فترة:</label>
                        <input type="date" id="fromDate" name="from" >

                        <label for="toDate">الى فترة:</label>
                        <input type="date" id="toDate" name="to" >

                        <label for="invoiceNumber">رقم الفاتورة:</label>
                        <input type="text" id="invoiceNumber" name="reference">

                        <label for="user_id">للبائع</label>
                        <select class="select2"  name="user_id" id="user_id"
                            aria-label="form-select-lg example">
                            <option value="">اختر البائع</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>

                        <label for="totalGreater">بتكلفة اكبر</label>
                        <input type="number" id="totalGreater" name="greater_price" min="0">

                        <label for="totalLess">بتكلفة اقل:</label>
                        <input type="number" id="totalLess" name="less_price" min="0">

                        <button type="submit" class="btn btn-primary">بحـث</button>
                    </form>
                </div>
                <div class="col-lg-9 col-md-12">
                    <div>
                        <div class="row p-2 align-items-center ">
                            <h5 class="mb-0 bg-light p-2 border-end col-4">عدد الفواتير</h5>
                            <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ count($orders) }}</h5>
                        </div>
                        <div class="row p-2 align-items-center">
                            <h5 class="mb-0 bg-light p-2 border-end col-4">اجمالى الفواتير</h5>
                            @php
                                $total = 0;
                                foreach ($orders as $order) {
                                    $total += $order->totalInvoiceWithoutDiscount;
                                }
                            @endphp
                            <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ $total }}</h5>
                        </div>
                        <div class="row p-2 align-items-center ">
                            <h5 class="mb-0 bg-light p-2 border-end col-4">الكميات المباعة</h5>
                            @php
                                $quantity = 0;
                                foreach ($orders as $order) {
                                    $quantity += $order->quantitiesNumber;
                                }
                            @endphp
                            <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ $quantity }}</h5>
                        </div>
                        <div class="row p-2 align-items-center">
                            <h5 class="mb-0 bg-light p-2 border-end col-4">اجمالى خصومات المنتجات</h5>
                            @php
                                $discountOnProducts = 0;
                                foreach ($orders as $order) {
                                    $discountOnProducts += $order->discountOnProducts->amount;
                                }
                            @endphp
                            <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ $discountOnProducts }}</h5>
                        </div>
                        <div class="row p-2 align-items-center">
                            <h5 class="mb-0 bg-light p-2 border-end col-4">اجمالى خصومات الفواتير</h5>
                            @php
                                $discountOnInvoices = 0;
                                foreach ($orders as $order) {
                                    $discountOnInvoices += $order->discountOnInvoice->amount;
                                }
                            @endphp
                            <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ $discountOnInvoices }}</h5>
                        </div>
                        <div class="row p-2 align-items-center">
                            <h5 class="mb-0 bg-light p-2 border-end col-4">صافى الفواتير</h5>
                            @php
                                $invoiceNets = 0;
                                foreach ($orders as $order) {
                                    $invoiceNets += $order->invoiceNet;
                                }
                            @endphp
                            <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ $invoiceNets }}</h5>
                        </div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>رقم الفاتوره</th>
                                <th>اجمالى الفاتوره</th>
                                <th>الكمية</th>
                                <th colspan="2">خصم المنتجات</th>
                                <th colspan="2">خصم الفاتورة</th>
                                <th>صافى الفاتورة</th>
                                <th>التاريخ</th>
                                <th>انشاء بوسطة</th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th>قيمة</th>
                                <th>نسبة</th>
                                <th>قيمة</th>
                                <th>نسبة</th>
                                <th></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $invoice)
                                <tr>
                                    <td><a href="{{ route('order.show', [$invoice->id]) }}">{{ $invoice->reference }}</a>
                                    </td>
                                    <td><a
                                            href="{{ route('order.edit', [$invoice->id]) }}">{{ $invoice->totalInvoiceWithoutDiscount }}</a>
                                    </td>
                                    <td><a
                                            href="{{ route('order.show', [$invoice->id]) }}">{{ $invoice->quantitiesNumber }}</a>
                                    </td>
                                    <td><a
                                            href="{{ route('order.show', [$invoice->id]) }}">{{ $invoice->discountOnProducts->amount }}</a>
                                    </td>
                                    <td><a
                                            href="{{ route('order.show', [$invoice->id]) }}">{{ $invoice->discountOnProducts->percent }}</a>
                                    </td>
                                    <td><a
                                            href="{{ route('order.show', [$invoice->id]) }}">{{ $invoice->discountOnInvoice->amount }}</a>
                                    </td>
                                    <td><a
                                            href="{{ route('order.show', [$invoice->id]) }}">{{ $invoice->discountOnInvoice->percent }}</a>
                                    </td>
                                    <td><a href="{{ route('order.show', [$invoice->id]) }}">{{ $invoice->invoiceNet }}</a>
                                    </td>
                                    <td><a href="{{ route('order.show', [$invoice->id]) }}">{{ $invoice->created_at }}</a>
                                    </td>
                                    <td><a href="{{ route('order.show', [$invoice->id]) }}">{{ $invoice->user->name }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
@endsection
