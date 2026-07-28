<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <style>

        body{
            font-family: DejaVu Sans;
            font-size:12px;
            color:#000;
        }

        h2{
            text-align:center;
            margin-bottom:5px;
        }

        h4{
            text-align:center;
            margin-top:0;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        th,
        td{
            border:1px solid #000;
            padding:6px;
            font-size:11px;
        }

        th{
            background:#eeeeee;
        }

        .footer{
            margin-top:25px;
            text-align:right;
            font-size:10px;
        }

    </style>

</head>

<body>

<div style="text-align:center; margin-bottom:20px;">

    @if($setting && $setting->logo)

        <img src="{{ public_path('storage/' . $setting->logo) }}"
             width="70"
             style="margin-bottom:10px;">

    @endif

    <h2 style="margin:0;">

        {{ $setting->pharmacy_name ?? config('app.name') }}

    </h2>

    @if(!empty($setting?->address))
        <p style="margin:2px;">{{ $setting->address }}</p>
    @endif

    @if(!empty($setting?->phone))
        <p style="margin:2px;">Tel: {{ $setting->phone }}</p>
    @endif

    @if(!empty($setting?->email))
        <p style="margin:2px;">{{ $setting->email }}</p>
    @endif

</div>

<hr>

<h4>{{ $title }}</h4>

<p>

Generated:
{{ now()->format('d M Y h:i A') }}

</p>
