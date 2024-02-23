<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>MIMS</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/libraries/sweetalert2/sweetalert2.min.css">
  <!-- CSS only -->
  <link rel="stylesheet" href="/designs/css/admin-style.css">

  @yield('styles')
</head>
<body>

    <div class="page-container">

        <div class="sidebar-mobile">
            <div class="sidebar-logo">
                <img src="../../assets/logo/mims-logo.png" alt="MIMS"/>
            </div>
            <a href="javascript:void(0);" class="mobilenav-icon" onclick="myFunction()">
                <i class="fa fa-bars"></i>
            </a>
            <div class="sidebar-mobile--topnav">
                <a class="active" href="{{route('admin.dashboard.listing')}}">Dashboard</a>
            </div>
        </div>
        
        <div class="sidebar-desktop">
            <div class="sidebar-logo">
                <img src="../../assets/logo/mims-logo.png" alt="MIMS"/>
            </div>

            <ul>
                <li><a href="{{route('admin.dashboard.listing')}}">Dashboard</a></li>
                
            </ul>
        </div>

    
        <div class="main_content">

            <div class="main_content--wrapper">

                @yield('content')
            </div>

        </div>

    </div>
    <div class="modal">
        <div class="modal-content">
            <span class="close-button">&times;</span>
            <div class="modal-input-cont">
                <input type="text" class="input modal-input" placeholder="Enter quantity">
            </div>
        </div>
    </div>
    
    <script
  src="https://code.jquery.com/jquery-3.6.0.min.js"
  integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
  crossorigin="anonymous"></script>
  <script src="/libraries/sweetalert2/sweetalert2.min.js"></script>
    @yield('scripts')
    <script src="/js/app.js"></script>
</body>
</html>