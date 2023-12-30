@extends('layout.main')
@section('style')
@endsection
@section('content')
    <section>
        <div class="d-flex justify-content-between container pt-5">
            <h3 class="myHeading"> المستخدمين</h3>
            <a class="btn btn-primary d-flex " href="{{ route('user.index') }}">
                رجوع  <span class="align-self-end ps-1"> &#x21A9;</span>
            </a>
        </div>
        <hr>
        <div class="container">
            @if ($errors->any())
          
            <div class="alert alert-danger text-start" role="alert">
                
                <div>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li> {{$error}}</li>
                        @endforeach
                    </ul>
                </div>
              </div>
            
        @endif
        @if (session()->has('succ'))
        <div class="alert alert-success text-start" role="alert">
                
            <div>
                <ul>
                    <li> {{session()->get('succ')}}</li>
                </ul>
            </div>
          </div> 
        @endif
            <form action="{{ route('user.store') }}" method="get">
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
                          <input type="text" name="name" class="form-control" value="{{old('name')}}" placeholder="الاسم" aria-label="First name">
                        </div>
                        <div class="col-4 mt-5">
                          <input type="text" name="phone" class="form-control" placeholder="الهاتف" value="{{old('phone')}}" aria-label="Last name">
                        </div>
                        <div class="col-4 mt-5">
                            <input type="text" name="email" class="form-control" placeholder="الايميل" value="{{old('email')}}" aria-label="Last name">
                        </div>
                        <div class="col-4 mt-5">
                            <input type="text" name="address" class="form-control" placeholder="العنوان" value="{{old('address')}}" aria-label="Last name">
                        </div>
                        <div class="col-4 mt-5">
                            <input type="password" name="password" class="form-control" placeholder="الرقم السرى" value="" aria-label="Last name">
                        </div>
                        <div class="col-4 mt-5">
                            <input type="password" name="password_confirmation" class="form-control" placeholder="تاكيد الرقم السرى" value="" aria-label="Last name">
                        </div>
                      </div>

                        <button class="btn btn-primary mt-5" type='submit'>
                           حـفـظ
                        </button>
                </div>
            </form>
        </div>
    </section>
@endsection
@section('script')
<script>
    $(document).ready(function() {
  $('.select2').select2();
});
</script>
@endsection
