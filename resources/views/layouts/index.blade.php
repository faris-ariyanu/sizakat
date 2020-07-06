
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Sistem Informasi Zakat MBR</title>
<!-- Stylesheets -->
<link href="{{url('assets/css/bootstrap.css')}}" rel="stylesheet">
<link href="{{url('assets/css/style.css')}}" rel="stylesheet">
<link href="{{url('assets/css/responsive.css')}}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Fira+Sans&display=swap" rel="stylesheet">
<!--Add Theme Color File To Change Template Color Scheme / Color Scheme Files are Located in root/css/color-themes/ folder-->
<!--<link href="css/color-themes/orange-theme.css" rel="stylesheet">-->

<!--Favicon-->
<link rel="shortcut icon" href="{{url('images/logo.png')}}" type="image/x-icon">
<link rel="icon" href="{{url('images/logo.png')}}" type="image/x-icon">

<!-- Responsive -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<!--[if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.js"></script><![endif]-->
<!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->

@yield('style')
</head>

<body>
<div class="page-wrapper">
 	
    <!-- Preloader -->
    <div class="preloader"></div>

    @include("components.header")

    @yield('content')
    
    @include("components.footer")
    
</div>
<!--End pagewrapper-->

<!--Scroll to top-->
<div class="scroll-to-top scroll-to-target" data-target=".main-header"><span class="icon fa fa-long-arrow-up"></span></div>

<script src="{{url('assets/js/jquery.js')}}"></script> 
<script src="{{url('assets/js/bootstrap.min.js')}}"></script>
<script src="{{url('assets/js/appear.js')}}"></script>
<script src="{{url('assets/js/wow.js')}}"></script>
<script src="{{url('assets/js/jquery-ui.js')}}"></script>
<script src="{{url('assets/js//mixitup.js')}}"></script>
<script src="{{url('assets/js/script.js')}}"></script>

@yield('script')

</body>
</html>