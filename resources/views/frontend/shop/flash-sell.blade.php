@extends('frontend.app')
@section('title', 'Shop Product List')
@push('css')
    {{-- <link rel="stylesheet" href="{{ asset('frontend/silck/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/silck/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/food.css') }}"> --}}
    <style>
        /*@media (min-width: 1400px) {*/
        /*   .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {*/
        /*       max-width: 1440px !important;*/
        /*   }*/
        /*}*/

        /*@media (min-width: 1200px) {*/
        /*   .container, .container-lg, .container-md, .container-sm, .container-xl {*/
        /*       max-width: 1240px !important;*/
        /*   }*/
        /*}*/



        @media (min-width: 1024px) {
            .prodCatcus {
                background: rgb(255 153 0);
                border-radius: 0px;
                border-top-right-radius: 70px;
                width: 16%;
                padding: 0px;
                margin: 0px;
            }

            .carousel-item img {
                height: 535px;
            }

            /*.product-item {*/
            /*    width: 205px !important;*/
            /*}*/
            .select2-container--default .select2-selection--single {
                height: 40px;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 40px;
                font-size: 13px;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                top: 7px;
            }

            .select2-container {
                width: 100% !important;
            }

            .prd_img {
                margin-bottom: -20px !important;
            }
        }

        @media only screen and (min-width: 250px) and (max-width: 319px) {
            .prodCatcus {
                background: rgb(255 153 0);
                border-radius: 0px;
                border-top-right-radius: 70px;
                width: 24%;
                padding: 0px;
                margin: 0px;
            }

            .col-lg-3 {
                width: 100% !important;
                margin-left: 0% !important;
            }

            .col-lg-9 {
                padding-right: 0px !important;
            }

            .select2-container {
                width: 70% !important;
            }

            .left_side_img img {
                height: auto !important;
            }

            .left_side_img {
                display: none
            }

            .prd_img {
                margin-bottom: -20px !important;
            }

            .sidebar-filter {
                display: none;
            }

        }

        @media only screen and (min-width: 367px) and (max-width: 385px) {
            .semi {
                font-size: 13px !important;
            }
        }

        @media only screen and (min-width: 328px) and (max-width: 391px) {
            .owl-item .product-item .product_content h4 {
                line-height: 12px !important;
                font-size: 0px !important;
            }

            .font-14 {
                /*font-size: 11px !important;*/
            }

            .semi {
                font-size: 11px !important;
            }

            .product-box .product-item .product_content .price_box span.current_price {
                font-size: 13px !important;
            }
        }


        @media only screen and (min-width: 320px) and (max-width: 375px) {

            .prodCatcus {
                background: rgb(255 153 0);
                border-radius: 0px;
                border-top-right-radius: 70px;
                width: 56%;
                padding: 0px;
                margin: 0px;
            }

            .col-lg-3 {
                width: 100% !important;
                margin-left: 0% !important;
            }

            /*.col-lg-9 {*/
            /*    padding-right: 0px !important;*/
            /*}*/
            .select2-container {
                width: 70% !important;
            }

            .left_side_img img {
                height: auto !important;
            }

            .primcusImg {
                margin-left: 0% !important;
            }

            .seccusImg {
                margin-left: 0% !important;
            }

            .bg-orange {
                width: 40% !important;
                height: 33px !important;
                font-size: 12px !important;
            }

            .slick-next .slick-arrow {
                margin-top: -14px !important;
            }

            .product_content {
                margin-top: 1% !important;
            }

            header img {
                height: 70px;
                width: 220px;
                padding-left: 0;
            }

            .left_side_img {
                display: none
            }

            .sidebar-filter {
                display: none;
            }

            .semi {
                font-size: 11px !important;
            }

            .product-box .product-item .product_content .price_box span.current_price {
                font-size: 13px !important;
            }

            .product-box .product-item .product_content h4 {
                min-height: 70px !important;
            }

        }

        @media only screen and (min-width: 376px) and (max-width: 450px) {

            .product_content {
                margin-top: 6% !important;
            }

            .prodCatcus {
                background: rgb(255 153 0);
                border-radius: 0px;
                border-top-right-radius: 70px;
                width: 56%;
                padding: 0px;
                margin: 0px;
            }

            .col-lg-3 {
                width: 100% !important;
                margin-left: 0% !important;
            }

            /*.col-lg-9 {*/
            /*    padding-right: 0px !important;*/
            /*}*/
            .select2-container {
                width: 70% !important;
            }

            .left_side_img img {
                height: auto !important;
            }

            .prd_prc_bx {
                margin-top: 0%;
            }

            .left_side_img {
                display: none
            }

            .prd_img {
                margin-bottom: -20px !important;
            }

            .primcusImg {
                margin-left: 0%;
            }

            .sidebar-filter {
                display: none;
            }

        }

        @media only screen and (min-width: 451px) and (max-width: 575px) {
            .product_content {
                margin-top: 1% !important;
            }
        }

        @media only screen and (min-width: 376px) and (max-width: 379px) {

            .primcusImg {
                margin-left: 0%;
            }

            .sidebar-filter {
                display: none;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 900px) {

            .primcusImg {
                /*margin-left: 15%;*/
            }

            .sidebar-filter {
                display: none;
            }
        }



        @media only screen and (min-width: 576px) and (max-width: 991px) {
            .prodCatcus {
                background: rgb(255 153 0);
                border-radius: 0px;
                border-top-right-radius: 70px;
                width: 24%;
                padding: 0px;
                margin: 0px;
            }

            .col-lg-3 {
                width: 100% !important;
                margin-left: 0% !important;
            }

            /*.col-lg-9 {*/
            /*    padding-right: 0px !important;*/
            /*}*/
            .select2-container {
                width: 60% !important;
            }

            .left_side_img img {
                height: auto !important;
            }

            .prd_img {
                margin-bottom: -20px !important;
            }

            .sidebar-filter {
                display: none;
            }

            .primcusImg {
                /*margin-left: 25% ;	*/
            }

        }

        @media only screen and (min-width: 992px) and (max-width: 1348px) {
            .prodCatcus {
                background: rgb(255 153 0);
                border-radius: 0px;
                border-top-right-radius: 70px;
                width: 24%;
                padding: 0px;
                margin: 0px;
            }

            .col-lg-3 {
                width: 24% !important;
                margin-left: 1% !important;
            }

            .col-lg-9 {
                width: 75% !important;
                padding-right: 0px !important;
            }
        }

        @media only screen and (min-width: 1401px) and (max-width: 1500px) {}

        @media only screen and (min-width: 376px) and (max-width: 575px) {
            .primcusImg {
                /*margin-left: 15%;	*/
            }

            .sidebar-filter {
                display: none;
            }
        }

        @media only screen and (min-width: 378px) and (max-width: 412px) {
            .bg-orange {
                font-size: 8px !important;
            }

            .prd_prc_bx {
                padding-bottom: 6px !important;
                margin-top: 0% !important;
            }

        }

        .product-item {
            transition: box-shadow 0.5s !important;
            border: 1px solid lightgray;
        }

        .product-item:hover {
            box-shadow: 0px 2px 13px -3px gray !important;
        }

        @media only screen and (min-width: 1084px) and (max-width: 1141px) {
            .product_content a {
                font-size: 14px !important;
            }
        }

        @media only screen and (min-width: 992px) and (max-width: 1083px) {
            .product_content a {
                font-size: 12px !important;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 856px) {
            .product_content a {
                font-size: 13px !important;
            }
        }

        @media only screen and (min-width: 576px) and (max-width: 643px) {
            .product_content a {
                font-size: 13px !important;
            }
        }

        @media only screen and (min-width: 367px) and (max-width: 575px) {
            .col-md-3 {
                width: 50% !important;
            }
        }

        @media only screen and (min-width: 1500px) and (max-width: 2000px) {
            .main-section {
                width: 1500px !important;
            }
        }

        @media only screen and (min-width: 2001px) and (max-width: 2600px) {
            .main-section {
                width: 1500px !important;
            }
        }

        .product-box .product-item {
            margin: 0 !important;
            margin-bottom: 20px !important;
        }
    </style>
@endpush
@section('content')
    <div class="categoryHeader">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page" style="background:lightblue;">

                </li>
            </ol>
        </nav>
    </div>

    <style>
        .label_product {
            width: 46%;
            position: absolute;
            top: 5px;
            right: 0px;
            text-align: center;
            height: 20px;
            background: linear-gradient(90deg, #002f6c, #00c8ff) !important;
            border-radius: 20px 5px 5px 20px;
        }

        .label_sale {
            padding-top: 2px;
            color: #fff;
            font-size: 66%;
            font-weight: 600;
            display: block;
        }

        .product-item .product_content h4 {
            line-height: 20px;
            display: block;
            font-size: 14px;
            text-transform: capitalize;
            font-weight: 600;
            margin-bottom: 0;
            min-height: 44px;
            text-align: center;
        }

        .product-item .product_content .price_box span.old_price {
            text-decoration: line-through;
            font-weight: 400;
            font-size: 14px;
            margin-left: 10px;
        }

        .product-item .product_content .price_box span.current_price {
            color: #f30;
            font-weight: 600;
            font-size: 17px;
        }

        .form-check-label {
            color: black !important;
            font-weight: bold;
        }
    </style>

    <style>
        @media (min-width: 576px) {

            .container,
            .container-sm {
                max-width: 600px !important;
            }
        }

        @media (min-width: 768px) {

            .container,
            .container-md,
            .container-sm {
                max-width: 900px !important;
            }

            .col-md-3 {
                padding-right: 5px !important;
                padding-left: 5px !important;
            }

        }

        @media (min-width: 992px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm {
                max-width: 1085px !important;
            }
        }

        @media (min-width: 1200px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl {
                max-width: 1140px !important;
            }
        }

        @media (min-width: 1400px) {

            .container,
            .container-lg,
            .container-md,
            .container-sm,
            .container-xl,
            .container-xxl {
                max-width: 1320px !important;
            }
        }



        /*@media (min-width: 767px) {*/
        /*    .col-md-3 {*/
        /* Adjust these styles based on your specific requirements for mobile devices */
        /*        padding-right: 15px !important;*/
        /*        padding-left: 15px !important;*/
        /*    }*/
        /*}*/
    </style>


    <div class="container-fluid main-section" style="padding-bottom: 30px;
    padding-top: 11px;">
        <div class="row">
            @forelse($flashSell  as $key => $product)
                <div class="col-lg-2 col-md-3 col-sm-4 col-6" style="padding: 5px;">
                    <div class="product-item" style="position: relative;">
                        <div class="product_thumb bg-white prd_img" style="">
                            <a class="primary_img "
                                href="{{ route('front.product.show', [$product->product->id]) }}"><img
                                    src="{{ asset('uploads/custom-images2/' . $product->product->thumb_image) }}"
                                    class="primcusImg" alt="" style="width: 100%;"></a>
                            <a style="display: none;" class="secondary_img"
                                href="{{ route('front.product.show', [$product->product->id]) }}"><img
                                    src="{{ asset('uploads/custom-images2/' . $product->product->thumb_image) }}"
                                    class="seccusImg" alt="" style=""></a>

                            @if ($product->product->offer_price > 0)
                                <div class="label_product" style="width: 46%;">
                                    <span class="label_sale" style="padding-top: 2px;">{{ BanglaText('offer') }}</span>
                                </div>
                            @endif
                            @if ($product->product->is_free_shipping > 0)
                                <div class="label_product"
                                    style="width: 46%; background:#3276B1; left: 0%; border-radius: 5px 30px 30px 5px">
                                    <span class="label_sale" style="padding-top: 2px;">{{ BanglaText('free') }}</span>
                                </div>
                            @endif
                        </div>

                        
                        <div class="product_content "
                            style="border-top:3px solid #EDEDEF; margin-top: 10%;padding: 5px 5px;
    background-color: #fff;">
                            <h4 class="ps-1" style="height: 35px;">
                                <a href="{{ route('front.product.show', [$product->product->id]) }}" class="font-14"
                                    style="font-size:14px;color: #000;
    font-weight: 700;">
                                    {{ \Illuminate\Support\Str::limit($product->product->name, 40) }}</a>
                            </h4>
                            <div class="subText" data-reactid=".1n7kkwy0qp6.b.2.0.0.0.0.2.5.1.0:$14822_Grocery.0.2.3">

                            </div>
                            <div class="price_box ps-1 justify-content-center prd_prc_bx" style="text-align: center;">
                                @if (empty($product->product->offer_price))
                                    <span class="current_price">{{ $product->product->price }} Tk</span>
                                @else
                                    <span class="current_price">{{ $product->product->offer_price }} Tk</span>
                                    <span class="old_price">{{ $product->product->price }} Tk</span>
                                @endif
                            </div>
                            <div class="rounded-0 bg-muted p-2 d-flex justify-content-between">

                                <!--<a data-id="{{ $product->id }}" data-url="{{ route('front.cart.store') }}" style="color: white;font-size: 13px;background: red;border: solid;width: 100%;" class="btn btn-sm btn-warning semi add-to-cart">-->
                                <!--   {{ BanglaText('cart') }}-->
                                <!--</a>-->
                                @if ($product->product->type == 'variable' || $product->product->prod_color == 'varcolor')
                                    <a href="{{ route('front.product.show', [$product->product->id]) }}"
                                        style="color: white; font-size: 16px;background: linear-gradient(90deg, #002f6c, #00c8ff) !important;border: solid;width: 100%;padding-top: 4%;"
                                        class="btn btn-sm btn-warning semi ">
                                        <i class="fas fa-shopping-cart"></i> &ensp; {{ BanglaText('order') }}
                                    </a>
                                @else
                                    <a href="{{ route('front.check.single', ['product_id' => $product->product->id]) }}"
                                        style="color: white; font-size: 15px;background: linear-gradient(90deg, #002f6c, #00c8ff) !important;border: solid;width: 100%;padding-top: 4%;"
                                        class="btn btn-sm btn-warning semi buy-now"
                                        data-price="{{ $product->product->price }}"
                                        data-offerprice="{{ $product->product->offer_price }}"
                                        data-url="{{ route('front.cart.store') }}">
                                        <i class="fas fa-shopping-cart"></i> &nbsp; {{ BanglaText('order') }}
                                    </a>
                                @endif


                            </div>
                        </div>
                    </div>
                </div>

            @empty
                <div align="center">
                    <strong class="text-center text-danger">No products are available</strong>
                </div>
            @endforelse
        </div>


    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {

            $(document).on('click', '.add-to-cart', function(e) {
                let id = $(this).data('id');
                let url = $(this).data('url');
                addToCart(url, id);
            });

            // ... other click event handlers ...

            function addToCart(url, id, variation = "", quantity = 1) {
                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajax({
                    type: "POST",
                    url: url,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    data: {
                        id,
                        quantity,
                        variation
                    },
                    success: function(res) {
                        if (res.status) {
                            //  toastr.success(res.msg);
                            window.location.reload();

                        } else {
                            toastr.error(res.msg);
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error('An error occurred while processing your request.');
                    }
                });
            }

            $('.buy-now').on('click', function(e) {
                e.preventDefault();

                var productId = $(this).attr('href').split('/').pop();
                var proQty = 1;
                var addToCartUrl = $(this).data('url');
                var csrfToken = $('meta[name="csrf-token"]').attr('content');
                var price = Number($(this).data('price'));
                var offerprice = Number($(this).data('offerprice'));
                var retrieve_discount = price - offerprice;

                // Include CSRF token in AJAX request headers
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                // Perform AJAX request to add the product to the cart
                $.post(addToCartUrl, {
                    id: productId,
                    quantity: proQty,
                    retrieve_discount: retrieve_discount
                }, function(response) {
                    // toastr.success(response.msg);
                    if (response.status) {
                        // Redirect to checkout page after adding to cart
                        window.location.href = "{{ route('front.checkout.index') }}";
                    } else {

                    }

                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var urlParams = new URLSearchParams(window.location.search);
            var minPrice = urlParams.get('min_price');
            var maxPrice = urlParams.get('max_price');

            $('#min-price').val(minPrice || 0);
            $('#max-price').val(maxPrice || {{ $maxPrice }});
            $('#min-price-input').val(minPrice || 0);
            $('#max-price-input').val(maxPrice || {{ $maxPrice }});

            $('#availability-filter-form').on('submit', function(e) {
                e.preventDefault();
                var minPrice = $('#min-price').val();
                var maxPrice = $('#max-price').val();
                var inStock = $('#check-1').prop('checked') ? 1 : 0;
                var outOfStock = $('#check-2').prop('checked') ? 1 : 0;

                var redirectUrl = '{{ route('front.shop') }}' +
                    '?min_price=' + minPrice +
                    '&max_price=' + maxPrice +
                    '&in_stock=' + inStock +
                    '&out_of_stock=' + outOfStock;

                window.location.href = redirectUrl;
            });

            $('#clear-filter').on('click', function() {
                window.location.href = '{{ route('front.shop') }}';
            });
        });
    </script>
@endpush
