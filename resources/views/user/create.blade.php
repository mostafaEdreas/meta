@extends('layout.main')
@section('style')
@endsection
@section('content')
    <section>
        <div class="d-flex justify-content-between container-md">
            <h3 class="myHeading"> المستخدمين</h3>
            <a class="btn btn-primary d-flex " href="{{ route('user.index') }}">
                رجوع  <span class="align-self-end ps-1"> &#x21A9;</span>
            </a>
        </div>
        <hr>
        <div class="container-md">
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
            <form action="{{ route('user.store') }}" method="post">
            @csrf
                <div class=" container-md" >
                    <div class="row">
                        <div class=" col-sm-12 col-md-4 mt-3 justify-content-between">
                            <select name="rule_id" class="form-select form-select-lg select2">
                                <option  value="" >اختر نوع المستخدم</option>
                                @foreach ($rules as $rule)
                                    <option  value="{{ $rule->id }}" {{ request()->input('rule_id') == $rule->id ? 'selected' : '' }}>{{ $rule->name }}</option>
                                @endforeach
                            </select>
                             {{-- @dd(request()->input('rule_id')) --}}
                        </div>
                        <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                          <input type="text" name="name" class="form-control form-control-lg" value="{{old('name')}}" placeholder="الاسم" aria-label="First name">
                        </div>
                        <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                          <input type="text" name="phone" class="form-control form-control-lg" placeholder="الهاتف" value="{{old('phone')}}" aria-label="Last name">
                        </div>
                        <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                            <input type="text" name="email" class="form-control form-control-lg" placeholder="الايميل" value="{{old('email')}}" aria-label="Last name">
                        </div>
                        <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                            <input type="text" name="address" class="form-control form-control-lg" placeholder="العنوان" value="{{old('address')}}" aria-label="Last name">
                        </div>
                        <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                            <input type="password" name="password" class="form-control form-control-lg" placeholder="الرقم السرى" value="" aria-label="Last name">
                        </div>
                        <div class="col-sm-12 col-md-4 mt-3 justify-content-between">
                            <input type="password" name="password_confirmation" class="form-control form-control-lg" placeholder="تاكيد الرقم السرى" value="" aria-label="Last name">
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
@endsection
