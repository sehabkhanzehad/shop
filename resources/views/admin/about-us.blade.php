@extends('admin.master_layout')
@section('title')
<title>{{__('admin.About Us')}}</title>
@endsection
@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>Home Page Setting</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">Home Page Setting</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row mt-4">
                
                
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                            <form action="{{ route('admin.about-us.update',$aboutUs->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">

                                    <div class="col-12">
                                        <h5 class="header_title">Home Page Settings</h5>
                                        <hr>
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="">{{__('admin.Description')}} <span class="text-danger">*</span></label>
                                        <textarea required name="description_three" cols="30" rows="10" class="form-control text-area-3">{{ $aboutUs->description_three }}</textarea>
                                    </div>
                                    
                                    <div class="form-group col-12">
                                        <label for="">Hot Item Link<span class="text-danger">*</span></label>
                                        <input required type="text" name="title_three" class="form-control" value="{{ $aboutUs->title_three }}">
                                    </div>
                                    
                                    <div class="form-group col-12">
                                        <label for="">Hot Item Image</label>
                                        <div>
                                            <img src="{{ asset($aboutUs->video_background) }}" alt="" class="w_300">
                                        </div>
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="">{{__('admin.New Image')}}</label>
                                        <input type="file" name="video_background" class="form-control-file">
                                    </div>

                                    <div class="form-group col-12">
                                        <label>About Products <span class="text-danger">*</span></label>
                                        <textarea required name="about_us" cols="30" rows="10" class="summernote">{{ $aboutUs->about_us }}</textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                    </div>
                                </div>
                            </form>
                    </div>
                  </div>
                </div>
                
                <div class="col-12">
                  <div class="card">
                    <div class="card-body">
                                <div class="row">

                                    <div class="col-12">
                                        <h5 class="header_title">Home Bottom Settings</h5>
                                        <hr>
                                    </div>
                                    <div class="col-12">
                                        
                      <div class="table-responsive table-invoice">
                        <table class="table table-bordered" id="">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                  	<th>Icon</th>
                                    <th>Action</th>
                                  </tr>
                            </thead>
                            <tbody>
                                @foreach($hbs as $key=>$item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td>
                                            <img src="{{ asset('uploads/home-bottom-icon/'.$item->icon) }}" alt="" class="category_image">
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.home-bottom.edit',[$item->id]) }}" class="btn btn-primary btn-sm edit"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @endforeach  
                            </tbody>
                        </table>
                      </div>
                    
                                    </div>
                                </div>
                            
                    </div>
                  </div>
                </div>
                
                
                
                <div class="col-12 d-none">
                  <div class="card">
                    <div class="card-body">
                            <form action="{{ route('admin.about-us.update',$item->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    
                                    <div class="col-12">
                                        <h5 class="header_title">Popup Offer Settings</h5>
                                        <hr>
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="">{{__('admin.Description')}} <span class="text-danger">*</span></label>
                                        <textarea required name="description_three" cols="30" rows="10" class="form-control text-area-3">{{ $item->description_three }}</textarea>
                                    </div>
                                    
                                    <div class="form-group col-12">
                                        <label for="">Hot Item Link<span class="text-danger">*</span></label>
                                        <input required type="text" name="title_three" class="form-control" value="{{ $item->title_three }}">
                                    </div>
                                    
                                    <div class="form-group col-12">
                                        <label for="">Hot Item Image</label>
                                        <div>
                                            <img src="{{ asset($item->video_background) }}" alt="" class="w_300">
                                        </div>
                                    </div>

                                    <div class="form-group col-12">
                                        <label for="">{{__('admin.New Image')}}</label>
                                        <input type="file" name="video_background" class="form-control-file">
                                    </div>

                                    <div class="form-group col-12">
                                        <label>About Products <span class="text-danger">*</span></label>
                                        <textarea required name="about_us" cols="30" rows="10" class="summernote">{{ $item->about_us }}</textarea>
                                    </div>
                                    
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <button class="btn btn-primary">{{__('admin.Update')}}</button>
                                    </div>
                                </div>
                            </form>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>
      
      <div class="modal fade" id="homeBottom" tabindex="-1" role="dialog" aria-labelledby="demoModalLabel" aria-hidden="true">
          
      </div>      
      
      <script>
    $(document).on('click','a.edit',function(e){
        e.preventDefault();
        let url=$(this).attr('href');
        let method='GET';
        $.ajax({
            url:url,
            method:method,
            data:{},
            success:function(res){
                if(res.success){
                   $('div#homeBottom').html(res.html).modal('show'); 
                }
            }
        });
        
    });
    
    $(document).on('submit','form#home_bottom_setting',function(e){
        e.preventDefault();
        let method=$(this).attr('method');
        let url=$(this).attr('action');
        var formData = new FormData($(this)[0]);
       
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        $.ajax({
            url:url,
            method:method,
            data:formData,
            async:false,
            processData:false,
            contentType:false,
            success:function(res){
                if(res.success){
                    toastr.success(res.msg);
                    window.location.reload();
                }
            }
        });
    });
    
</script>
@endsection





