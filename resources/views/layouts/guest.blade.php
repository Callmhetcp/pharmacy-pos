<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title>
        {{ $setting->pharmacy_name ?? 'Hypet Pharmacy' }}
    </title>


    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">


    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">



<style>


*{

    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Segoe UI',sans-serif;

}



body{

    min-height:100vh;

    display:flex;

    align-items:center;

    justify-content:center;


    background:


    linear-gradient(

        135deg,

        rgba(13,110,253,.85),

        rgba(8,127,91,.85)

    ),

    url('{{ asset("images/pharm_bg2.jpg") }}');


    background-size:cover;

    background-position:center;

}



/* AUTH WRAPPER */


.auth-wrapper{


    width:100%;

    padding:20px;


}




/* CARD */


.auth-card{


    max-width:480px;

    margin:auto;

    background:rgba(255,255,255,.95);

    border-radius:30px;

    padding:45px 40px;


    box-shadow:

    0 25px 60px rgba(0,0,0,.25);


    animation:slideUp .6s ease;


}





@keyframes slideUp{


from{

    opacity:0;

    transform:translateY(40px);

}


to{

    opacity:1;

    transform:translateY(0);

}


}



/* LOGO */


.auth-logo{


    width:90px;

    height:90px;

    border-radius:50%;

    object-fit:cover;

    border:5px solid #0d6efd;

    padding:5px;

    margin-bottom:20px;


}




.pharmacy-name{


    font-size:28px;

    font-weight:800;

    color:#0d6efd;


}




.auth-title{


    font-size:22px;

    font-weight:700;

    color:#333;


}




.auth-subtitle{


    color:#777;

    font-size:14px;

}





/* INPUTS */


.form-control{


    height:50px;

    border-radius:15px;

    border:1px solid #ddd;

    padding-left:45px;


}



.form-control:focus{


    border-color:#0d6efd;

    box-shadow:

    0 0 0 .2rem rgba(13,110,253,.15);


}



.input-group-custom{


    position:relative;


}



.input-group-custom i{


    position:absolute;

    left:18px;

    top:17px;

    color:#0d6efd;

    z-index:5;


}



/* BUTTON */


.auth-btn{


    width:100%;

    height:52px;

    border-radius:50px;


    border:none;


    background:

    linear-gradient(

        90deg,

        #0d6efd,

        #087f5b

    );


    color:white;

    font-size:17px;

    font-weight:700;


    transition:.3s;


}



.auth-btn:hover{


    transform:translateY(-3px);

    box-shadow:

    0 10px 25px rgba(13,110,253,.3);


}





/* LINKS */


.auth-link{


    text-decoration:none;

    color:#0d6efd;

    font-weight:600;


}



.auth-link:hover{

    text-decoration:underline;

}





/* MOBILE */


@media(max-width:576px){


.auth-card{


    padding:35px 25px;


}



.pharmacy-name{

    font-size:24px;

}



}



</style>


</head>


<body>



<div class="auth-wrapper">


<div class="auth-card text-center">



@if(isset($setting) && $setting->logo)


<img src="{{ asset('storage/'.$setting->logo) }}"
     class="auth-logo">


@else


<img src="{{ asset('images/pharm_logo.png') }}"
     class="auth-logo">


@endif




<div class="pharmacy-name">

{{ $setting->pharmacy_name ?? 'Hypet Pharmacy' }}

</div>



<div class="auth-title mt-3">

Welcome Back

</div>


<div class="auth-subtitle mb-4">

Login to your pharmacy management system

</div>




{{ $slot }}



</div>


</div>



</body>


</html>