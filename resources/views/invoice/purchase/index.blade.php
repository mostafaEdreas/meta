@extends('layout.main')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/css/tables.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/paginate.css') }}">
    <style>
        form {
            background-color: #fff;
            padding: 20px;
            bpurchase-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            /* width: 300px; */
        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input {
            width: calc(100% - 16px);
            padding: 8px;
            margin-bottom: 16px;
            bpurchase: 1px solid #ccc;
            bpurchase-radius: 4px;
            box-sizing: bpurchase-box;
        }

    </style>
@endsection

@section('content')
    <section>
        <div class="container-xl">
            <div class="row">
                <div class="col-lg-3 col-md-12">
                    <form action="{{ route('purchase.index') }}" method="GET">
                        <label for="fromDate">من فترة:</label>
                        <input type="date" id="fromDate" name="from" required>

                        <label for="toDate">الى فترة:</label>
                        <input type="date" id="toDate" name="to" required>

                        <label for="totalGreater">بتكلفة اكبر</label>
                        <input type="number" id="totalGreater" name="greater_price" min="0">

                        <label for="totalLess">بتكلفة اقل:</label>
                        <input type="number" id="totalLess" name="less_price" min="0">

                        <label for="invoiceNumber">رقم الفاتورة:</label>
                        <input type="text" id="invoiceNumber" name="reference">

                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <div class="col-lg-9 col-md-12">
                    <div>
                        <div class="row p-2 align-items-center ">
                            <h5 class="mb-0 bg-light p-2 bpurchase-end col-4">عدد الفواتير</h5>
                            <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ count($purchases) }}</h5>
                        </div>
                        <div class="row p-2 align-items-center">
                            <h5 class="mb-0 bg-light p-2 bpurchase-end col-4">اجمالى الفواتير</h5>
                            @php
                                $total = 0;
                                foreach ($purchases as $purchase) {
                                    $total += $purchase->totalInvoiceWithoutDiscount;
                                }
                            @endphp
                            <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ $total }}</h5>
                        </div>
                        <div class="row p-2 align-items-center ">
                            <h5 class="mb-0 bg-light p-2 bpurchase-end col-4">الكميات المباعة</h5>
                            @php
                                $quantity = 0;
                                foreach ($purchases as $purchase) {
                                    $quantity += $purchase->quantitiesNumber;
                                }
                            @endphp
                            <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ $quantity }}</h5>
                        </div>
                        <div class="row p-2 align-items-center">
                            <h5 class="mb-0 bg-light p-2 bpurchase-end col-4">اجمالى خصومات المنتجات</h5>
                            @php
                            $discountOnProducts = 0;
                            foreach ($purchases as $purchase) {
                                $discountOnProducts += $purchase->discountOnProducts->amount;
                            }
                        @endphp
                        <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ $discountOnProducts }}</h5>
                        </div>
                        <div class="row p-2 align-items-center">
                            <h5 class="mb-0 bg-light p-2 bpurchase-end col-4">اجمالى خصومات الفواتير</h5>
                            @php
                            $discountOnInvoices = 0;
                            foreach ($purchases as $purchase) {
                                $discountOnInvoices += $purchase->discountOnInvoice->amount;
                            }
                        @endphp
                        <h5 class="mb-0 ps-3 p-2 bg-info col-6">{{ $discountOnInvoices }}</h5>
                        </div>
                        <div class="row p-2 align-items-center">
                            <h5 class="mb-0 bg-light p-2 bpurchase-end col-4">صافى الفواتير</h5>
                            @php
                            $invoiceNets = 0;
                            foreach ($purchases as $purchase) {
                                $invoiceNets += $purchase->invoiceNet;
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
                            @foreach ($purchases as $invoice)
                                <tr>
                                    <td><a href="{{ route('purchase.show',[$invoice->id]) }}">{{ $invoice->reference }}</a></td>
                                    <td><a href="{{ route('purchase.edit',[$invoice->id]) }}">{{ $invoice->totalInvoiceWithoutDiscount }}</a></td>
                                    <td><a href="{{ route('purchase.show',[$invoice->id]) }}">{{ $invoice->quantitiesNumber }}</a></td>
                                    <td><a href="{{ route('purchase.show',[$invoice->id]) }}">{{ $invoice->discountOnProducts->amount }}</a></td>
                                    <td><a href="{{ route('purchase.show',[$invoice->id]) }}">{{ $invoice->discountOnProducts->percent }}</a></td>
                                    <td><a href="{{ route('purchase.show',[$invoice->id]) }}">{{ $invoice->discountOnInvoice->amount }}</a></td>
                                    <td><a href="{{ route('purchase.show',[$invoice->id]) }}">{{ $invoice->discountOnInvoice->percent }}</a></td>
                                    <td><a href="{{ route('purchase.show',[$invoice->id]) }}">{{ $invoice->invoiceNet }}</a></td>
                                    <td><a href="{{ route('purchase.show',[$invoice->id]) }}">{{ $invoice->created_at }}</a></td>
                                    <td><a href="{{ route('purchase.show',[$invoice->id]) }}">{{ $invoice->user->name }}</a></td>
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
