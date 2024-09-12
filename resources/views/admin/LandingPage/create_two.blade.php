@extends('admin.master_layout')
@section('title')

<title>Create Landing Page</title>

@endsection
@push('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
@endpush
@section('admin-content')



      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Create A Landing Page</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">Create A Landing Page</div>
            </div>
          </div>

          <div class="section-body">
            <a href="{{ route('admin.landing.index') }}" class="btn btn-primary"><i class="fas fa-list"></i> {{__('Landing Pages')}}</a>
            <div class="row mt-4">
                <div class="col-12">
                  <div class="card">
                  <div class="card-body">

<form method="POST" action="{{ route('admin.landing_pages_two.store')}}"  enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-3">
                <label  class="form-label">Page Title</label>
                <input type="text" name="title1" class="form-control" placeholder="Title">
                <input type="hidden" name="page_type" value="2">
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="mb-3">
                <label  class="form-label">Background Image</label>
                <input type="file" name="landing_bg" class="form-control">
            </div>
        </div>

        <div class="col-lg-12">
            <div class="mb-3">
                <label  class="form-label">Video Url (Embedded Code)</label>
                <input type="text" name="video_url" class="form-control" placeholder="Title">
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="mb-3">
                <label  class="form-label">Call Text</label>
                <input type="text" name="call_text" class="form-control" placeholder="Title">
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="mb-3">
                <label  class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" placeholder="Phone">
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="mb-3">
                <label  class="form-label">Left Side Text</label>
                <input type="text" name="left_side_title" class="form-control" placeholder="Title">
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="mb-3">
                <label  class="form-label">Left Side Details</label>
                <textarea class="form-control summernote" name="left_side_desc" rows="10" cols="10"></textarea>
            </div>
        </div>
        
        <div class="col-lg-12">
            <div class="mb-3">
                <label  class="form-label">Slider Top Text</label>
                <input type="text" name="feature" class="form-control" placeholder="Title">
            </div>
        </div>


        <div class="col-lg-12">
            <div class="mb-3">
               <label  class="form-label">Slider Image</label>
               <input type="file" name="sliderimage[]" class="form-control" multiple>
            </div>
        </div>
        
        <!--<div class="col-lg-12">-->
        <!--    <div class="mb-3">-->
        <!--        <label  class="form-label">Review Top Text</label>-->
        <!--         <input type="text" name="review_top_text" class="form-control" placeholder="Review Text">-->
        <!--    </div>-->
        <!--</div>-->
        
        <!--<div class="col-lg-12">-->
        <!--    <div class="mb-3">-->
        <!--        <label  class="form-label">Review Image</label>-->
        <!--       <input type="file" name="review_product_image[]" class="form-control" multiple>-->
        <!--    </div>-->
        <!--</div>-->
       
        <div class="col-lg-12">
            <div class="mb-3">
                <label  class="form-label">Add Product</label>
                <input type="text" id="search2" class="form-control" placeholder="product search here">
            </div>
        </div>

        <input type="hidden" id="product_id" name="product_id" value="">

        <div id="data">

        </div>


        <div class="col-lg-12">
            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </div>
    </div>

</form>

</div> <!-- end card-body-->
                  </div>
                </div>
          </div>
        </section>
      </div>

@push('js')

<script src="https://cdn.ckeditor.com/4.12.1/standard/ckeditor.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>



<script type="text/javascript">
    var path = "{{ route('admin.getOrderProduct') }}";
    var path2 = "{{ route('admin.getOrderProduct2') }}";
    const products=[];

    $( "#search" ).autocomplete({
        selectFirst: true, //here
        minLength: 2,
        source: function( request, response ) {
          $.ajax({
            url: path,
            type: 'GET',
            dataType: "json",
            data: {
               search: request.term
            },
            success: function( data ) {
                if (data.length ==0) {
                    toastr.error('Product Or Stock Not Found');
                }
                else if (data.length ==1) {
                    if(products.indexOf(data[0].id) ==-1){
                        landingProductEntry(data[0]);
                        products.push(data[0].id);
                    }
                    $('#search').val('');
                }else if (data.length >1) {
                    response(data);
                }
            }
          });
        },
        select: function (event, ui) {
           if(products.indexOf(ui.item.id) ==-1){
                landingProductEntry(ui.item);
                products.push(ui.item.id);
            }
           $('#search').val('');
           return false;
        }
      });
      
      $(function () {
    $('#search2').autocomplete({
        source: function (request, response) {
            $.ajax({
                url: path2,
                type: 'GET',
                dataType: "json",
                data: {
                    search: request.term
                },
                success: function (data) {
                    if (data.length == 0) {
                       
                        toastr.error('Product Or Stock Not Found2');
                    } else if (data.length == 1) {
                      
                        if (products.indexOf(data[0].id) == -1) {
                            landingProductEntry(data[0]);
                            products.push(data[0].id);
                        }
                        $('#search2').val('');
                    } else if (data.length > 1) {
                       
                        // response(data); // This line is correct for showing options
                        if (products.indexOf(data[0].id) == -1) {
                            landingProductEntry(data[0]);
                            products.push(data[0].id);
                        }
                    }
                }
            });
        },
        // Optional: customize how results are displayed
        response: function (event, ui) {
            // You can customize the way results are displayed here
            // For example, you can format the data or apply custom styling
        },
        // Optional: handle selection of an item
        select: function (event, ui) {
            // Handle the selected item if needed
        }
    });
});

      
      

      function landingProductEntry(item)
      {
          $.ajax({
            url: '{{ route("admin.landingProductEntry")}}',
            type: 'GET',
            dataType: "json",
            data: {id:item.id},
            success: function( res ) {
                if (res.html) {
                    $('div#data').append(res.html);
                }
                if (res.pr_id)
                {
                    $('#product_id').val(res.pr_id);
                }
            }
          });
      }

      function productEntry(item){
        $.ajax({
            url: '{{ route("admin.orderProductEntry")}}',
            type: 'GET',
            dataType: "json",
            data: {id:item.id},
            success: function( res ) {
                if (res.html) {
                   $('tbody#data').append(res.html);
                    calculateSum();
                }
            }
          });
    }

</script>

@endpush


@endsection
