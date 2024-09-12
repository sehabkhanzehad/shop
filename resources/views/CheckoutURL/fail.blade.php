<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fail</title>
</head>
<body>
    <div style="text-align: center">
         <h1>Sorry !! Payment Failed, Please try again later.</h1>
    </div>
    <br><br>
    <div style="text-align: center; color: red;">
        @if(isset($statusMessage))
           {{ $statusMessage }}
        @endif

        @if(isset($response))
        {{ $response }}
        @endif
            <br>
            <br>
            <a class="btn bg-dark" href="{{route('front.home')}}"> Back To Home  </a><br><br>
            @if(!empty($order_inv->order_phone))
            <a class="btn bg-dark" href="{{route('front.order-list',$order_inv->order_phone)}}" style="color:red"> See all Orders  </a>
            @endif
    </div>
</body>
</html>