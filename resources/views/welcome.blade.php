<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>
{{ $setting->pharmacy_name ?? 'Hypet Pharmacy' }}
</title>


<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">


<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">



<style>


*{

    font-family:'Segoe UI',sans-serif;

}



body{


    min-height:100vh;


    background:


    linear-gradient(

        rgba(13,110,253,.80),

        rgba(0,180,255,.65)

    ),


    url('{{ asset("images/pharm_bg.jpg") }}');



    background-size:cover;


    background-position:center;


    background-attachment:fixed;



}





/* PAGE CENTER */


.page-wrapper{


    min-height:100vh;


    display:flex;


    align-items:center;


    justify-content:center;



    padding:40px 15px;


}





/* MAIN CARD */


.welcome-card{


    width:100%;


    max-width:1150px;



    background:



    linear-gradient(

        rgba(255,255,255,.90),

        rgba(240,248,255,.90)

    ),



    url('{{ asset("images/pharm_bg2.jpg") }}');



    background-size:cover;


    background-position:center;



    border-radius:35px;



    padding:55px;



    box-shadow:



    0 30px 80px rgba(0,0,0,.35);



    border:

    1px solid rgba(255,255,255,.7);



    backdrop-filter:blur(15px);



}







/* LOGO */


.logo{


    width:120px;


    height:120px;


    object-fit:cover;


    border-radius:50%;


    background:white;


    padding:6px;



    box-shadow:



    0 15px 35px rgba(0,0,0,.25);



}







/* BRAND */


.brand-title{


    font-size:48px;


    font-weight:900;



    background:


    linear-gradient(

        90deg,

        #0d6efd,

        #00b4ff

    );



    -webkit-background-clip:text;


    color:transparent;



}




.subtitle{


    font-size:20px;


    color:#555;


    max-width:650px;


    margin:auto;


}







/* FEATURES */


.feature-box{


    background:


    rgba(255,255,255,.90);



    border-radius:25px;



    padding:35px 20px;



    height:100%;



    border:

    1px solid #eee;



    transition:.35s;



}





.feature-box:hover{


    transform:translateY(-12px);



    box-shadow:



    0 20px 45px rgba(0,0,0,.18);



}





.feature-icon{


    width:80px;


    height:80px;



    border-radius:50%;



    display:flex;


    justify-content:center;


    align-items:center;



    margin:auto;



    background:


    linear-gradient(

        135deg,

        #0d6efd,

        #00b4ff

    );



    color:white;


    font-size:35px;



}






.feature-box h5{


    margin-top:20px;


    font-weight:800;


    color:#0d6efd;


}





.feature-box p{


    color:#6c757d;


}









/* BUTTONS */


.btn-main{


    padding:16px 45px;


    border-radius:50px;


    font-weight:700;



    transition:.3s;



}




.btn-login{


    background:


    linear-gradient(

        90deg,

        #0d6efd,

        #00b4ff

    );



    color:white;


    border:none;



    box-shadow:


    0 10px 25px rgba(13,110,253,.35);



}





.btn-login:hover{


    color:white;


    transform:translateY(-5px);


}






.btn-register{


    border:2px solid #0d6efd;


    color:#0d6efd;


    background:white;



}





.btn-register:hover{


    background:#0d6efd;


    color:white;


    transform:translateY(-5px);


}









.footer-text{


    color:#6c757d;


    font-size:14px;



}







@media(max-width:768px){



.welcome-card{


    padding:30px 20px;


}



.brand-title{


    font-size:32px;


}



}






</style>


</head>




<body>



<div class="page-wrapper">



<div class="welcome-card">



<div class="text-center">



@if(isset($setting) && $setting->logo)


<img src="{{ asset('storage/'.$setting->logo) }}"
class="logo mb-4">


@else


<img src="{{ asset('images/pharm_logo.png') }}"
class="logo mb-4">


@endif






<h1 class="brand-title">

{{ $setting->pharmacy_name ?? 'Hypet Pharmacy' }}

</h1>





<p class="subtitle">

Smart Pharmacy Inventory,
POS Sales and Business Management System

</p>



</div>







<div class="row g-4 mt-5">





<div class="col-md-4">


<div class="feature-box text-center">



<div class="feature-icon">

<i class="fas fa-pills"></i>

</div>



<h5>

Medicine Management

</h5>



<p>

Control medicines, stock levels,
expiry dates and categories easily.

</p>



</div>


</div>








<div class="col-md-4">


<div class="feature-box text-center">



<div class="feature-icon">

<i class="fas fa-cash-register"></i>

</div>



<h5>

Fast POS System

</h5>



<p>

Process sales quickly with
customers, drafts and receipts.

</p>



</div>


</div>








<div class="col-md-4">


<div class="feature-box text-center">



<div class="feature-icon">

<i class="fas fa-chart-line"></i>

</div>



<h5>

Reports & Analytics

</h5>



<p>

Monitor business performance,
sales and inventory movement.

</p>



</div>


</div>





</div>








<div class="text-center mt-5">


<a href="{{ route('login') }}"
class="btn btn-main btn-login me-2">


<i class="fas fa-sign-in-alt me-2"></i>

Login


</a>





@if(Route::has('register'))


<a href="{{ route('register') }}"
class="btn btn-main btn-register">


<i class="fas fa-user-plus me-2"></i>

Register


</a>


@endif



</div>








<div class="text-center mt-5">


<p class="footer-text mb-0">


© {{ date('Y') }}

{{ $setting->pharmacy_name ?? 'Hypet Pharmacy' }}

. All Rights Reserved.


</p>


</div>






</div>


</div>





<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>