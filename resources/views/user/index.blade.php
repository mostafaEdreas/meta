@extends('layout.main')
@section('style')
    <style>
        /* Basic table styling */
        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }
    </style>
@endsection
@section('content')
    <section>
        <div class="d-flex justify-content-between container pt-5">
            <h3 class="myHeading"> المستخدمين</h3>
            <button class="btn btn-primary">
                جديد +
            </button>
        </div>
        <hr>
        <div class="container">
            <form action="{{ route('user.index') }}" method="get">
                <div class="d-flex justify-content-around mt-5">
                    <div class="form-check">
                        <input class="form-check-input " type="radio" name ='active' value="1" id="active" @if (request()->input('active')===null|| request()->input('active')==='1') checked @endif>
                        <label class="form-check-label" for="active">
                            نشط
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name ='active' value="0" id="unActive" @if ( request()->input('active')==='0') checked @endif>
                        <label class="form-check-label " for="unActive">
                            غير نشط
                        </label>
                       
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name ='active' value="2" id="all"@if ( request()->input('active')==='2') checked @endif>
                        <label class="form-check-label" for="all">
                            الكل
                        </label>
                    </div>
                </div>

                <div class="form-control mt-5" >

                    <div class="row">
                        <div class=" mt-5 cal-12">
                            <select name="rule_id" id="rule_id" class="form-control select2">
                                <option class="myHeading form-select" @readonly(true) value="" aria-label="Default select example">اختر نوع المستخدم</option>
                                @foreach ($rules as $rule)
                                    <option class="myHeading" value="{{ $rule->id }}" {{ request()->input('rule_id') == $rule->id ? 'selected' : '' }}>{{ $rule->name }}</option>
                                @endforeach
                            </select>
                             {{-- @dd(request()->input('rule_id')) --}}
                        </div>
                        <div class="col-4 mt-5">
                          <input type="text" name="name" class="form-control" value="{{request()->input('name')}}" placeholder="الاسم" aria-label="First name">
                        </div>
                        <div class="col-4 mt-5">
                          <input type="text" name="phone" class="form-control" placeholder="الهاتف" value="{{request()->input('phone')}}" aria-label="Last name">
                        </div>
                        <div class="col-4 mt-5">
                            <input type="text" name="email" class="form-control" placeholder="الايميل" value="{{request()->input('email')}}" aria-label="Last name">
                        </div>
                        <div class="col-4 mt-5">
                            <input type="text" name="address" class="form-control" placeholder="العنوان" value="{{request()->input('address')}}" aria-label="Last name">
                        </div>
                      </div>

                        <button class="btn btn-primary mt-5" type='submit'>
                            فرز
                        </button>
                </div>
            </form>
        </div>
        <div class="mt-5">
            <table>
                <thead>
                    <tr>
                        <th>م</th>
                        <th>الاسم</th>
                        <th>النوع</th>
                        <th>الايميل</th>
                        <th>الهاتف</th>
                        <th>...</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $i => $user)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->rule->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if ($user->deleted_at)
                                <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#act{{$i}}" >تنشيط</button>
                                @else
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal"data-bs-target="#act{{$i}}" >
                                    ايقاف
                                </button>
                                @endif
                            </td>
                        </tr>
                        {{-- unactive modale --}}
                        <div class="modal fade" id="act{{$i}}" aria-hidden="true" aria-labelledby="exampleModalToggleLabel"
                            tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalToggleLabel">Modal 1</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5 class="">
                                           @if ($user->deleted_at)
                                           هل انت متاكد من تنشيط حساب <span class="text-success">{{ $user->name }}؟</span>
                                           @else
                                           هل انت متاكد من ايقاف تنشيط حساب <span class="text-success">{{ $user->name }}؟</span>
                                           @endif
                                        </h5>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">الغاء</button>
                                        @if ($user->deleted_at)
                                        <form action="{{ route('user.restore',$user->id) }}" method="post">
                                            
                                            <button type="submit" class="btn btn-primary">تنشيط</button>
                                        @else
                                        <form action="{{ route('user.distroy',$user->id) }}" method="post">
                                            <button type="submit" class="btn btn-primary">ايقاف</button>
                                        @endif
                                        @csrf
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- end unactive modal --}}
                    @endforeach
                    <tr>

                            {!! $users->links() !!}
                    </tr>


                    <!-- Add more rows and data as needed -->
                </tbody>
            </table>
        </div>
    </section>
    <div class="text-center container">
       
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
  $('.select2').select2();
});
</script>
@endsection
