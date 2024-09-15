@extends('frontend2.layouts.master')
@section('content')
    <!--no slider start-->
    <section class="section-big-py-space ratio_asos bg-light">
        <div class="custom-container">
            <div class="row">
                <div class="col">
                    <div class="no-slider row product">
                        @forelse($filteredProducts  as $key => $product)
                            <div class="product-box">
                                <div class="product-imgbox">
                                    <div class="product-front">
                                        <img src="{{ asset('uploads/custom-images2/' . $product->thumb_image) }}"
                                            class="img-fluid" alt="product">
                                    </div>
                                    <div class="product-back">
                                        <img src="{{ asset('uploads/custom-images2/' . $product->thumb_image) }}"
                                            class="img-fluid" alt="product">
                                    </div>
                                    <div class="product-icon">
                                        <button data-toggle="modal" data-target="#addtocart" title="Add to cart">
                                            <i class="ti-bag"></i>
                                        </button>
                                        <a href="javascript:void(0)" title="Add to Wishlist">
                                            <i class="ti-heart" aria-hidden="true"></i>
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#quick-view" title="Quick View">
                                            <i class="ti-search" aria-hidden="true"></i>
                                        </a>
                                        <a href="compare.html" title="Compare">
                                            <i class="fa fa-exchange" aria-hidden="true"></i>
                                        </a>
                                    </div>

                                    @if ($product->is_free_shipping > 0)
                                        <div class="new-label">
                                            <div>{{ BanglaText('free') }}</div>
                                        </div>
                                    @endif

                                    @if ($product->offer_price > 0)
                                        <div class="on-sale">
                                            {{ BanglaText('offer') }}
                                        </div>
                                    @endif


                                </div>
                                <div class="product-detail">
                                    <div class="detail-title">
                                        <div class="detail-left">
                                            <div class="rating-star">
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                                <i class="fa fa-star"></i>
                                            </div>
                                            <a href="{{ route('front.product.show', [$product->id]) }}">
                                                <h6 class="price-title">
                                                    {{ \Illuminate\Support\Str::limit($product->name, 30) }}
                                                </h6>
                                            </a>
                                        </div>
                                        <div class="detail-right">
                                            @if (empty($product->offer_price))
                                                <div class="price">

                                                    <div class="price">৳{{ $product->price }}</div>
                                                </div>
                                            @else
                                                <div class="check-price">৳{{ $product->price }}</div>
                                                <div class="price">

                                                    <div class="price">৳{{ $product->offer_price }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <strong class="text-center text-danger">No products are available</strong>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--no slider end-->
@endsection
