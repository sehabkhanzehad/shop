<!DOCTYPE html>
<html lang="en">
@include('frontend2.components.layout.head')

<body class="bg-light ">
    <!-- progress bar -->
    <div id="loader" class="LoadingOverlay d-none">
        <div class="Line-Progress">
            <div class="indeterminate"></div>
        </div>
    </div>

    <!-- loader start -->
    <div class="loader-wrapper">
        <div>
            <img src="{{ asset('assets') }}/images/loader.gif" alt="loader">
        </div>
    </div>
    <!-- loader end -->

    <!--header start-->
    @include('frontend2.components.layout.header')
    <!--header end-->

    @yield('content')

    <!--footer start-->
    @include('frontend2.components.layout.footer')
    <!--footer end-->


    <!-- tap to top -->
    <div class="tap-top">
        <div>
            <i class="fa fa-angle-double-up"></i>
        </div>
    </div>
    <!-- tap to top End -->

    <!-- Add to cart bar -->
    {{-- <div id="cart_side" class=" add_to_cart bottom ">
        <a href="javascript:void(0)" class="overlay" onclick="closeCart()"></a>
        <div class="cart-inner">
            <div class="cart_top">
                <h3>my cart</h3>
                <div class="close-cart">
                    <a href="javascript:void(0)" onclick="closeCart()">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="cart_media">
                <ul class="cart_product">
                    <li>
                        <div class="media">
                            <a href="#">
                                <img alt="" class="mr-3"
                                    src="{{ asset('assets') }}/images/layout-1/product/1.jpg">
                            </a>
                            <div class="media-body">
                                <a href="#">
                                    <h4>item name</h4>
                                </a>
                                <h4>
                                    <span>1 x $ 299.00</span>
                                </h4>
                            </div>
                        </div>
                        <div class="close-circle">
                            <a href="#">
                                <i class="ti-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="media">
                            <a href="#">
                                <img alt="" class="mr-3"
                                    src="{{ asset('assets') }}/images/layout-1/product/2.jpg">
                            </a>
                            <div class="media-body">
                                <a href="#">
                                    <h4>item name</h4>
                                </a>
                                <h4>
                                    <span>1 x $ 299.00</span>
                                </h4>
                            </div>
                        </div>
                        <div class="close-circle">
                            <a href="#">
                                <i class="ti-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="media">
                            <a href="#"><img alt="" class="mr-3"
                                    src="{{ asset('assets') }}/images/layout-1/product/3.jpg"></a>
                            <div class="media-body">
                                <a href="#">
                                    <h4>item name</h4>
                                </a>
                                <h4><span>1 x $ 299.00</span></h4>
                            </div>
                        </div>
                        <div class="close-circle">
                            <a href="#">
                                <i class="ti-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>
                </ul>
                <ul class="cart_total">
                    <li>
                        <div class="total">
                            <h5>subtotal : <span>$299.00</span></h5>
                        </div>
                    </li>
                    <li>
                        <div class="buttons">
                            <a href="cart.html" class="btn btn-normal btn-xs view-cart">view cart</a>
                            <a href="#" class="btn btn-normal btn-xs checkout">checkout</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div> --}}
    <!-- Add to cart bar end-->


    <!--Newsletter modal popup start-->
    {{-- <div class="modal fade bd-example-modal-lg theme-modal" id="exampleModal" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="news-latter">
                        <div class="modal-bg">
                            <div class="offer-content">
                                <div>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">×</span></button>
                                    <h2>newsletter</h2>
                                    <p>Subscribe to our website mailling list <br> and get a Offer, Just for you!</p>
                                    <form
                                        action="https://pixelstrap.us19.list-manage.com/subscribe/post?u=5a128856334b598b395f1fc9b&amp;id=082f74cbda"
                                        class="auth-form needs-validation" method="post"
                                        id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                                        target="_blank">
                                        <div class="form-group mx-sm-3">
                                            <input type="email" class="form-control" name="EMAIL" id="mce-EMAIL"
                                                placeholder="Enter your email" required="required">
                                            <button type="submit" class="btn btn-theme btn-normal btn-sm "
                                                id="mc-submit">subscribe</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}
    <!--Newsletter Modal popup end-->


    <!-- Quick-view modal popup start-->
    <div class="modal fade bd-example-modal-lg theme-modal" id="quickViewModal" tabindex="-1" role="dialog"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content quick-view-modal">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                    <div class="row">
                        <div class="col-lg-6 col-xs-12">
                            <div class="quick-view-img"><img id="quickViewImage"
                                     alt="quick"
                                    class="img-fluid "></div>
                        </div>
                        <div class="col-lg-6 rtl-text">
                            <div class="product-right">
                                <h2 id="productName"></h2>
                                <h3 id="productPrice"></h3>
                                <ul class="color-variant">
                                    <li class="bg-light0"></li>
                                    <li class="bg-light1"></li>
                                    <li class="bg-light2"></li>
                                </ul>
                                <div class="border-product">
                                    <h6 class="product-title">product details</h6>
                                    <p id="ProductDetails"></p>
                                </div>
                                <div class="product-description border-product">
                                    <div class="size-box">
                                        <ul>
                                            <li class="active"><a href="#">s</a></li>
                                            <li><a href="#">m</a></li>
                                            <li><a href="#">l</a></li>
                                            <li><a href="#">xl</a></li>
                                        </ul>
                                    </div>
                                    <h6 class="product-title">quantity</h6>
                                    <div class="qty-box">
                                        <div class="input-group"><span class="input-group-prepend"><button
                                                    type="button" class="btn quantity-left-minus" data-type="minus"
                                                    data-field=""><i class="ti-angle-left"></i></button> </span>
                                            <input type="text" name="quantity" class="form-control input-number"
                                                value="1"> <span class="input-group-prepend"><button
                                                    type="button" class="btn quantity-right-plus" data-type="plus"
                                                    data-field=""><i class="ti-angle-right"></i></button></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-buttons"><a href="#" class="btn btn-normal">add to cart</a>
                                    <a href="#" class="btn btn-normal">view detail</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick-view modal popup end-->


    <!-- My account bar start-->
    <div id="myAccount" class="add_to_cart right account-bar">
        <a href="javascript:void(0)" class="overlay" onclick="closeAccount()"></a>
        <div class="cart-inner">
            <div class="cart_top">
                <h3>my account</h3>
                <div class="close-cart">
                    <a href="javascript:void(0)" onclick="closeAccount()">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <form class="theme-form">
                <div class="form-group">
                    <label for="">Email</label>
                    <input type="email" class="form-control" id="userEmail" name="email" required
                        placeholder="Email or mobile">
                </div>
                <div class="form-group">
                    <label for="">Password</label>
                    <input type="password" name="password" class="form-control" id="userPassword"
                        placeholder="Enter your password" required>
                </div>

                {{-- <div class="form-group">
                    <input type="checkbox" for="rem" name="remember" id="remember" class=""> &nbsp; <span id = "rem">Remember me</span>
                </div> --}}

                <div class="form-group">
                    <button onclick="login()" class="btn btn-rounded btn-block" type="button">Login</button>
                </div>
                <div>
                    <h5 class="forget-class"><a href="" class="d-block">forget password?</a>
                    </h5>
                    <h6 class="forget-class"><a href="{{ route('front.user-reg') }}" class="d-block">new to
                            store? Signup now</a>
                    </h6>
                </div>
            </form>
        </div>
    </div>
    <!-- Add to account bar end-->


    <!-- Add to wishlist bar -->
    <div id="wishlist_side" class="add_to_cart right">
        <a href="javascript:void(0)" class="overlay" onclick="closeWishlist()"></a>
        <div class="cart-inner">
            <div class="cart_top">
                <h3>my wishlist</h3>
                <div class="close-cart">
                    <a href="javascript:void(0)" onclick="closeWishlist()">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="cart_media">
                <ul class="cart_product">
                    <li>
                        <div class="media">
                            <a href="#">
                                <img alt="" class="mr-3"
                                    src="{{ asset('assets') }}/images/layout-1/media-banner/1.jpg">
                            </a>
                            <div class="media-body">
                                <a href="#">
                                    <h4>item name</h4>
                                </a>
                                <h4>
                                    <span>sm</span>
                                    <span>, blue</span>
                                </h4>
                                <h4>
                                    <span>$ 299.00</span>
                                </h4>
                            </div>
                        </div>
                        <div class="close-circle">
                            <a href="#">
                                <i class="ti-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="media">
                            <a href="#">
                                <img alt="" class="mr-3"
                                    src="{{ asset('assets') }}/images/layout-1/media-banner/2.jpg">
                            </a>
                            <div class="media-body">
                                <a href="#">
                                    <h4>item name</h4>
                                </a>
                                <h4>
                                    <span>sm</span>
                                    <span>, blue</span>
                                </h4>
                                <h4>
                                    <span>$ 299.00</span>
                                </h4>
                            </div>
                        </div>
                        <div class="close-circle">
                            <a href="#">
                                <i class="ti-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="media">
                            <a href="#"><img alt="" class="mr-3"
                                    src="{{ asset('assets') }}/images/layout-1/media-banner/3.jpg"></a>
                            <div class="media-body">
                                <a href="#">
                                    <h4>item name</h4>
                                </a>
                                <h4>
                                    <span>sm</span>
                                    <span>, blue</span>
                                </h4>
                                <h4><span>$ 299.00</span></h4>
                            </div>
                        </div>
                        <div class="close-circle">
                            <a href="#">
                                <i class="ti-trash" aria-hidden="true"></i>
                            </a>
                        </div>
                    </li>
                </ul>
                <ul class="cart_total">
                    <li>
                        <div class="total">
                            <h5>subtotal : <span>$299.00</span></h5>
                        </div>
                    </li>
                    <li>
                        <div class="buttons">
                            <a href="wishlist.html" class="btn btn-normal btn-block  view-cart">view wislist</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Add to wishlist bar end-->

    <!-- add to  setting bar  start-->
    <div id="mySetting" class="add_to_cart right">
        <a href="javascript:void(0)" class="overlay" onclick="closeSetting()"></a>
        <div class="cart-inner">
            <div class="cart_top">
                <h3>my setting</h3>
                <div class="close-cart">
                    <a href="javascript:void(0)" onclick="closeSetting()">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="setting-block">
                <div>
                    <h5>language</h5>
                    <ul>
                        <li><a href="#">english</a></li>
                        <li><a href="#">french</a></li>
                    </ul>
                    <h5>currency</h5>
                    <ul>
                        <li><a href="#">uro</a></li>
                        <li><a href="#">rupees</a></li>
                        <li><a href="#">pound</a></li>
                        <li><a href="#">doller</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- Add to setting bar end-->


    <!-- notification product -->
    <div class="product-notification" id="dismiss">
        <span onclick="dismiss();" class="close" aria-hidden="true">×</span>
        <div class="media">
            <img class="mr-2" src="{{ asset('assets') }}/images/layout-1/product/5.jpg"
                alt="Generic placeholder image">
            <div class="media-body">
                <h5 class="mt-0 mb-1">Latest trending</h5>
                Cras sit amet nibh libero, in gravida nulla.
            </div>
        </div>
    </div>
    <!-- notification product -->

    <!-- latest jquery-->
    {{-- <script src="{{ asset('assets/js/jquery-3.3.1.min.js') }}"></script> --}}
    <script src="{{asset('frontend/assets/js/jquery.min.js')}}"></script>


    <!-- script -->
    <script src="{{ asset('assets/js/include/config.js') }}"></script>
    <script src="{{ asset('assets/js/include/axios.min.js') }}"></script>
    <script src="{{ asset('assets/js/include/toastify-js.js') }}"></script>
    <script src="{{ asset('assets/js/include/custom.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.0/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>


    @stack('js')




    @yield('script')

    <!-- slick js-->
    <script src="{{ asset('assets/js/slick.js') }}"></script>

    <!-- popper js-->
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>

    <!-- Timer js-->
    <script src="{{ asset('assets/js/menu.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap.js') }}"></script>

    <!-- Bootstrap js-->
    <script src="{{ asset('assets/js/bootstrap-notify.min.js') }}"></script>

    <!-- Theme js-->
    <script src="{{ asset('assets/js/slider-animat-three.js') }}"></script>
    <script src="{{ asset('assets/js/script.js') }}"></script>
    <script src="{{ asset('assets/js/modal.js') }}"></script>
</body>

</html>
