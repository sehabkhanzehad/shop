 <style>
     @media (max-width: 768px) {
         .navbar {
             padding-left: 10px !important;
         }

         .nav_section {
             padding-right: 0px !important;
         }

     }

     @media (max-width: 992px) {
         .p_sm_0 {
             padding: 0 !important;
         }
     }

     @media (min-width: 768px) {
         .navbar_justify_content_sm {
             justify-content: center;
         }
     }

     @media only screen and (min-width: 320px) and (max-width: 375px) {
         header img {
             height: auto !important;
             width: 155px !important;
             padding-left: 0 !important;
         }
     }

     @media only screen and (min-width: 320px) and (max-width: 375px) {
         .logo_section img {
             height: auto !important;
             width: 155px !important;
             padding-left: 0 !important;
         }
     }

     @media only screen and (min-width: 1500px) and (max-width: 2000px) {
         .nav_section {
             width: 1500px !important;
         }

         .bot_menu {
             width: 1500px !important;
         }
     }

     @media only screen and (min-width: 2001px) and (max-width: 2700px) {
         .nav_section {
             width: 1500px !important;
         }

         .bot_menu {
             width: 1500px !important;
         }
     }
 </style>
 <!-- header Start  -->
 <header>

     @php
         $cart = session()->get('cart', []);
     @endphp

     <nav class="navbar navbar_justify_content_sm"
         style="background: linear-gradient(90deg, #002f6c, #00c8ff) !important;">
         <div class="container-fluid row justify-content-between align-items-center nav_section">
             <div class="col-2 d-block d-lg-none p_sm_0">
                 <button class="navbar-toggler bg-light d-xl-none shadow-none" type="button" data-bs-toggle="collapse"
                     data-bs-target="#middlenav" aria-controls="middlenav" aria-expanded="false"
                     aria-label="Toggle navigation">
                     <span class="navbar-toggler-icon"></span>
                 </button>
             </div>
             <div class="col-lg-4 col-md-3 col-6 logo_section" style="">
                 <a href="{{ route('front.home') }}" class="navbar-brand" style="margin: 0px; padding: 0px;">
                     <img src="{{ asset(siteInfo()->logo) }}" alt="logo">
                 </a>
             </div>
             <div class="col-3 d-block d-lg-none" style="padding-right: 0%;">
                 <a style="float: right;padding: 0px;" href="{{ route('front.checkout.index') }}"
                     class="btn pmd-btn-fab pmd-ripple-effect fixed-cart-bottom12" type="button">
                     <span style="color: white;position: absolute;top: 2%;right: 2%;">
                         @if ($cart !== null) {{ count($cart) }}
                         @else
                             0 @endif
                     </span>
                     <i class="fas fa-shopping-cart" style="font-size: 20px;color: #ffffff;"></i>
                 </a>
             </div>


             <div class="col-lg-4 col-md-12 col-12 p_sm_0">
                 <div class="search-container search_four">
                     <form class="searchArea my-0 my-lg-0" action="{{ route('front.product.search') }}">
                         <div class="search_box m-auto">
                             <input placeholder="Search entire store here ..." type="text" name="query">
                             <button type="submit"><i class="fa-solid fa-magnifying-glass"
                                     style="font-size: 18px;"></i></button>
                         </div>
                     </form>
                 </div>
             </div>
             @php
                 $cart = session()->get('cart', []);

             @endphp

             @php $footer = DB::table('footers')->first(); @endphp
             <style>
                 .support-icon-flex {
                     display: flex;
                     align-items: center;
                 }

                 .support-icon-flex .icon {
                     width: 30px;
                     font-size: 20px;
                     padding-right: 5px;
                 }

                 .support-icon-flex .icon i {
                     font-size: 20px;
                 }

                 .support-icon-flex .text p {
                     margin-bottom: 0;
                     text-transform: capitalize;
                     font-size: 12px;
                     color: whitesmoke;
                 }

                 .support-icon-flex .text h5 {
                     margin-bottom: 0;
                     font-size: 13px;
                     font-family: var(--bold);
                 }

                 .toast-success {
                     color: #FFFFFF !important;
                     background: #16166F;
                 }
             </style>
             <div class="col-lg-5 d-none">
                 <div class="navbar navbar-expand-lg justify-content-lg-end justify-content-between">
                     <a class="btn rounded-0 btn-light bg-white m-2 mx-lg-3 p-2"
                         style="display: flex;
    background: linear-gradient(to right, #3383d9, #040434) !important;
    color: white;
    border-radius: 6px !important;"
                         href="#"><i class="fa-solid fa-phone pe-0"></i> <small>{{ $footer->phone }}</small>
                     </a>

                     @guest

                         <a style="display: flex;color: white;" class="rounded-0 bg-white m-2 mx-lg-3 p-2"
                             href="{{ url('login-user') }}">Login</a>
                     @else
                         <a class="btn rounded-0 btn-light bg-white m-2 mx-lg-3 p-2"
                             href="{{ url('order') }}">{{ auth()->user()->name }}</a>

                         <a class="btn rounded-0 btn-light bg-white m-2 mx-lg-3 p-2" style="color: #ffffff;"
                             href="{{ url('logout') }}">Logout</a>
                     @endguest
                     <a style="display: flex;
    background: linear-gradient(to right, #3383d9, #040434) !important;
    color: white;
    border-radius: 6px !important;"
                         class="btn rounded-0 btn-light bg-white m-2 mx-lg-3 p-2" href="#">Offer</a>
                     @auth
                     @else
                         <a style="display: flex;
    background: linear-gradient(to right, #3383d9, #040434) !important;
    color: white;
    border-radius: 6px !important;"
                             class="btn rounded-0 btn-light bg-white m-2 mx-lg-3 p-2" href="#">Contact</a>
                     @endauth
                     <!--<a class="btn rounded-0 btn-light bg-white m-2 p-2" href="#">Menu</a>-->

                     <a class="btn rounded-0 btn-light bg-white m-2 mx-lg-3 p-2"
                         style="display: flex;
    background: linear-gradient(to right, #3383d9, #040434) !important;
    color: white;
    border-radius: 6px !important;"
                         href="{{ route('front.cart.index') }}">
                         Cart @if ($cart !== null) <span
                                 class="badge bg-secondary" style="margin-left: 5px;">{{ count($cart) }}</span>
                         @endif
                     </a>


                 </div>
             </div>
             <div class="col-lg-4 d-none d-md-none d-lg-block d-xlx-block">
                 <div class="navbar navbar-expand-lg justify-content-lg-end justify-content-between">
                     <div class="">
                         <div class="support-icon-flex" style="padding:0px 10px;">
                             <div class="icon white"><i class="fa-solid fa-phone pe-0" style="color: #EF4A23;"></i>
                             </div>
                             <div class="text white">
                                 <p>call us now</p>
                                 <h5>{{ $footer->phone }}</h5>
                             </div>
                         </div>
                     </div>

                     @guest

                         <!--<a style="display: flex;color: white; border-radius: 6px !important;" class="" href="{{ url('login-user') }}">Login</a>-->
                     @else
                         <!--<a class="btn rounded-0 btn-light bg-white m-2 mx-lg-3 p-2" href="{{ url('order') }}">{{ auth()->user()->name }}</a>-->

                         <!--<a class="btn rounded-0 btn-light bg-white m-2 mx-lg-3 p-2" href="{{ url('logout') }}">Logout</a>-->
                     @endguest
                     <div class="offer" style="line-height: 15px;display: flex;padding: 0px 10px;">
                         <div class="icn" style="margin-right: 10px;">
                             <i style="color: #EF4A23;margin-top: 6px;" class="fa-solid fa-user"></i>
                         </div>
                         <div class="txt">
                             @guest
                                 <a href="{{ url('login-user') }}" style="color: #ffffff;"> <span
                                         style="color: white;display:block;font-size: 15px;">Account</span> </a>
                             @else
                                 <a class="" href="{{ url('order') }}" style="color: #ffffff;"><span
                                         style="color: white;display:block;font-size: 15px;">{{ auth()->user()->name }}</span>
                                 </a>
                                 @endif
                                 <div class="display: flex;">
                                     <span style="color: #ffffff;font-size: 11px;">
                                         @guest
                                             <a href="{{ url('login-user') }}" style="color: #ffffff;">Login</a>
                                         @else
                                             <a class="" href="{{ url('order') }}"
                                                 style="color: #ffffff;">{{ auth()->user()->name }}</a>
                                         @endguest
                                     </span>
                                     <span style="color: #ffffff;font-size: 11px;">Or</span>
                                     <span style="color: #ffffff;font-size: 11px;">
                                         @guest
                                             <a href="{{ url('login-user') }}" style="color: #ffffff;">Register</a>
                                         @else
                                             <a class="" href="{{ url('logout') }}" style="color: #ffffff;">Logout</a>
                                         @endguest
                                     </span>
                                 </div>

                             </div>
                         </div>

                         @auth
                         @else
                             <!--<a style="color: white;border-radius: 6px !important;padding:0px 10px;font-size: 15px;" class="" href="#">Contact</a>-->
                         @endauth
                         <!--<a class="btn rounded-0 btn-light bg-white m-2 p-2" href="#">Menu</a>-->

                         <a class=""
                             style="display: flex; color: white;
    border-radius: 6px !important;padding: 0px 10px;"
                             href="{{ route('front.checkout.index') }}">

                             <i class="fas fa-cart-arrow-down"></i>

                             @if ($cart !== null) <span
                                     class="badge bg-secondary"
                                     style="margin-left: 5px;
    position: absolute;
    top: -3%;
    right: -1%;
    background: #EF4A23!important;">{{ count($cart) }}</span>
                             @endif
                         </a>
                     </div>
                 </div>
             </div>
         </nav>
         <!--style="box-shadow: 0px 5px 18px #808080c4; background: #F85606;"-->

         <nav class="navbar navbar-expand-xl navbar-light p-0" style = "background: #F85606;">
             <div class="container-fluid bot_menu">
                 <div class="collapse navbar-collapse middlenav" id="middlenav">
                     <ul class="navbar-nav m-auto mt-2 mt-lg-0 w-100 flex-wrap">
                         @forelse(categories() as $key => $item)
                             <li class="nav-item mx-2 dropdown">
                                 @if (!empty($item->slug))
                                     <a class="nav-link dropdown"
                                         href="{{ route('front.shop', [
                                             'slug' => $item->slug,
                                         ]) }}"
                                         role="button" data-bs-toggle="" aria-expanded="false">
                                         @if (!empty($item->name))
                                             {{ $item->name }}
                                         @endif
                                         @if ($item->activeSubCategories->count())
                                             <i class="fas fa-angle-down" style="margin-left: 2px;margin-top: 3px;"></i>
                                         @endif
                                     </a>
                                 @endif
                                 @if ($item->activeSubCategories->count())
                                     <ul class="dropdown-menu">
                                         @foreach ($item->activeSubCategories as $key => $item)
                                             <li class="dropend">
                                                 @if (!empty($item->slug))
                                                     <a
                                                         class ="dropdown-item text-dark"href="{{ route('front.subcategory', [
                                                             'type' => 'childcategory',
                                                             'slug' => $item->slug,
                                                         ]) }}">
                                                         <span>
                                                             @if (!empty($item->name))
                                                                 {{ $item->name }}
                                                             @endif
                                                         </span>
                                                         @if ($item->activeChildCategories->count())
                                                             <i class="fas fa-angle-right"
                                                                 style="float: right;margin-top: 3px;"></i>
                                                         @endif

                                                     </a>
                                                 @endif


                                                 </a>
                                                 @if ($item->activeChildCategories->count())
                                                     <ul class="dropdown-menu">
                                                         @foreach ($item->activeChildCategories as $key => $item)
                                                             <li class="">
                                                                 @if (!empty($item->slug))
                                                                     <a class="dropdown-item"
                                                                         href="{{ route('front.shop', [
                                                                             'slug' => $item->slug,
                                                                         ]) }}">
                                                                         @if ($item->name)
                                                                             <span>{{ $item->name }}</span>
                                                                         @endif
                                                                     </a>
                                                                 @endif
                                                             </li>
                                                         @endforeach
                                                     </ul>
                                                 @endif
                                             </li>
                                         @endforeach
                                     </ul>
                                 @endif
                             </li>
                         @empty
                             <div>
                                 <strong class="text-center text-danger">
                                     No
                                 </strong>
                             </div>
                         @endforelse

                     </ul>
                 </div>
             </div>
         </nav>
         <style>
             @media (min-width: 1280px) {
                 .d-xlx-block {
                     display: block !important;
                 }
             }
         </style>

     </header>
