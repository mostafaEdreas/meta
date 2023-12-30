<!DOCTYPE html>
<html lang="en" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap/bootstrap.rtl.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/auth/login.css') }}">

</head>

<body>
    <!-- partial:index.partial.html -->

    <div class="wrapper">
        <div class="login-text">
          <button class="cta"><span >▼</span></button>
          <div class="text">
            <a href="">تسجيل الدخول</a>
            <hr>
            <br>
            <form action="{{ route('login.check') }}" method="post">
                @csrf
                <input type="text" placeholder="رقم الهاتف" name="phone">
                <br>
                <input type="password" placeholder="الرقم السرى" name="password">
                <br>
                <input type="submit" value="دخول" class="login-btn">
            </form>
          </div>
        </div>
        <div class="call-text">
            @if ($errors->any())
          
            <div class="alert alert-danger text-start" role="alert">
                
                <div>
                    <ul>
    
                    </ul>
                    @foreach ($errors->all() as $error)
                    <li> {{$error}}</li>
                    @endforeach
                </div>
              </div>
            
        @endif
          <h1>تطبيق <span>ميتا </span>لإدارة المشاريع الصغيرة</h1>
          <a href="https://wa.me/01126713126">للتواصل مع مسئول التطبيق  واتساب</a>
        </div>
      
      </div>
    <!-- partial -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <!-- Latest compiled JavaScript -->
    <script src="{{ asset('assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/auth/login.js') }}"></script>
</body>

</html>
