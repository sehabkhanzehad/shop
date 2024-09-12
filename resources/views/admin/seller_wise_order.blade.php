@extends('admin.master_layout')
@section('title')
<title>{{ $title }}</title>
@endsection
<style>

  .sortingid{
  	width: 0px !important;
  }
  .sortingAc{
  	width : 50px !important;
  }
  
  .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 20px !important;
  }
  
</style>

@section('admin-content')
      <!-- Main Content -->
      <div class="main-content">
        <section class="section">
          <div class="section-header">
            <h1>{{ $title }}</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="{{ route('admin.dashboard') }}">{{__('admin.Dashboard')}}</a></div>
              <div class="breadcrumb-item">{{ $title }}</div>
            </div>
          </div>

          <div class="section-body">
            <div class="row mt-4">
                <div class="col">
                  <div class="card">
                    <div class="card-body">
                        
                    <div class="row">
                        <div class="col-md-8">   
                          <a class="send_to_redx btn btn-sm btn-dark mb-1" href="{{ route('admin.createRedxParcel')}}">
                                Send to Redx
                          </a>
                          <a class="send_to_pathao btn btn-sm btn-primary mb-1" href="{{ route('admin.createPathaoParcel')}}">
                                Send to Pathao
                          </a>
                          <a class="send_to_steadfast btn btn-sm btn-success mb-1" href="{{ route('admin.createSteadfastParcel')}}">
                                Send to Steadfast
                          </a>
                          <br>
    
                          <a class="btn btn-sm btn-success mb-1" href="{{ route('admin.addNewOrder')}}">
                                Add New Order
                          </a>
                          
                          	<a class="btn_modal btn btn-sm btn-warning mb-1" href="#" data-toggle="modal" data-target="#demoModal">
                                Assign User
                          </a>
                          
                          <a class="btn_modal btn btn-sm btn-warning mb-1" href="#" data-toggle="modal" data-target="#statusModal">
                                Status Change
                          </a>
    
                          <a class="multi_order_delete btn btn-sm btn-danger mb-1" href="{{ route('admin.deleteAllOrder')}}">
                                Delete All
                          </a>
                         
                          <a class="multi_order_print btn btn-sm btn-success mb-1" href="{{ route('admin.orderList')}}">
                                Print
                          </a>
                          
                          <a class="multi_order_generate btn btn-sm btn-success mb-1" href="{{ route('admin.generate_invoice')}}">
                                Generate Invoice
                          </a>
                      </div>
                      <div class="col-md-4">
                          <div class="form-group">
                            <label>Select A Seller</label>                                    
                            <select name="seller_name" id="seller_name" class="form-control select2" id="brand">
                              
                              @foreach($sellers as $seller)
                                  <option value="{{ $seller->id }}">{{ $seller->name }}</option>
                              @endforeach
                              
                            </select>
                        </div>
                      </div>
                    </div> 

                      <div class="table-responsive table-invoice">
                        <table class="table table-striped table-responsive" id="dataTable">
                            <thead style="font-size:12px">
                                <tr>
                                    <th width="" style="width:0px">
                                         <input type="checkbox" class="check_all" value="">
                                    <!--<div class="form-check">-->
                                    <!--  <label class="form-check-label">-->
                                    <!--    <input type="checkbox" class="form-check-input check_all" value="">-->
                                    <!--  </label>-->
                                    <!--</div>-->
                                  </th>
                                    <th width="" class="sortingid">ID</th>
                                    <th width="" >{{__('admin.Customer')}}</th>

                                    <th width="" >{{__('admin.Date')}}</th>
                                    <!--<th width="10%">Order Delivery Date</th>-->
                                  	<th width="" >SKU</th>
                                    <th width="" >QTY</th>
                                    <th width="" >{{__('admin.Amount')}}</th>
                                    <th width="" >{{__('admin.Order Status')}}</th>
                                    <th width="" >Order Note</th>
									<th>Courier</th>
                                    <th width="" >Assign Order id</th>
                                    <th width="" class="sortingAc">{{__('admin.Action')}}</th>
                                  </tr>
                            </thead>

                            <tbody style="color:black; font-size:12px" >

                                @foreach ($individual_orders as $index => $order)

                                   @if($order->is_invoiced == '1')
                                    <tr style="background: darkgray;">
                                        <td>
                                        <input type="checkbox" class="order_checkbox" value="{{ $order->id}}">

                                      </td>
                                        <td>{{ $order->order_id }}</td>
                                       @if(!empty($order->user->name))
                                      	 <td>
                                          {{ $order->orderAddress->shipping_name }}
                                          {{ $order->orderAddress->shipping_phone }}
                                          {{ $order->orderAddress->shipping_address }}

                                      	</td>
                                      @endif

                                        <td>
                                            @php
                                                $now = \Carbon\Carbon::now(); // Get the current time
                                                $orderCreatedAt = \Carbon\Carbon::parse($order->created_at); // Parse the order creation time
                                                $timeDifference = $now->diffInHours($orderCreatedAt); // Calculate the time difference in hours
                                            @endphp
                                      		 @if ($timeDifference <= 24)
                                           @if ($timeDifference == 0)

												 <span class="badge badge-success">Less than an hour ago</span>
                                              @elseif ($timeDifference == 1)
                              					<span class="badge badge-success">1 hr ago</span>

                                              @else
                              					<span class="badge badge-success">{{ $timeDifference }} hrs ago</span>

                                              @endif
                                            @elseif ($timeDifference <= 12)
                                            <span class="badge badge-success">{{ $orderCreatedAt->format('h:i A') }}</span>

                                            @else
                                            <span class="badge badge-danger">{{ $orderCreatedAt->format('d M, Y') }}</span>
                                            @endif
                                      	</td>
                                        <!--<td>{{ $order->ordered_delivery_date }}</td>-->
                                        <td>
                                        @foreach ($order->orderProducts as $orderProduct)
                                            @if(!empty($orderProduct->product->sku)) 
                                            {{ $orderProduct->product->sku }},
                                            @else
                                           
                                            @endif
                                        @endforeach
                                        </td>

                                        <td>{{ $order->product_qty }}</td>

                                        <td>{{ $setting->currency_icon }}{{ round($order->total_amount) }}</td>
                                        <td>
                                            @if ($order->order_status == 1)
                                            <span class="badge badge-success">Process</span>
                                            @elseif ($order->order_status == 2)
                                            <span class="badge badge-success">Courier</span>
                                            @elseif ($order->order_status == 3)
                                            <span class="badge badge-success">Completed</span>
                                            @elseif ($order->order_status == 4)
                                            <span class="badge badge-danger">Cancell</span>
                                            @elseif($order->order_status == 5)
                                            <span class="badge badge-danger">On Hold</span>
                                            @elseif($order->order_status == 6)
                                            <span class="badge badge-danger">Return</span>
                                            @else
                                            <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->note ? $order->note : '' }}</td>                                      
                                        <td>
                                            @if($order->courier_id == '3')
                                            SteadFast
                                            {{ $order->courier_tracking_id }}
                                            @elseif($order->courier_id == '2')
                                            Pathao
                                            {{ $order->courier_tracking_id }}
                                            @elseif($order->courier_id == '1')
                                            Redx
                                            {{ $order->courier_tracking_id }}
                                            @else
                                            @endif
                                            
                                        </td>
                                        <td>
                                          @if(!empty($order->assign->name))
                                          {{$order->assign->name}}
                                          @endif
                                        </td>
                                        <td>

                                        <a href="{{ route('admin.order-show',$order->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
										<a href="{{ route('admin.order-edit',$order->id) }}" class="btn btn-success btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $order->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        <a href="javascript:;" data-toggle="modal" data-target="#orderModalId-{{ $order->id }}" class="btn btn-warning btn-sm"><i class="fas fa-truck" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                   @else
                                    <tr>
                                        <td>
                                        <input type="checkbox" class="order_checkbox" value="{{ $order->id}}">

                                      </td>
                                        <td>{{ $order->order_id }}</td>
                                       @if(!empty($order->user->name))
                                      	 <td>
                                          {{ $order->orderAddress->shipping_name }}
                                          {{ $order->orderAddress->shipping_phone }}
                                          {{ $order->orderAddress->shipping_address }}

                                      	</td>
                                      @endif

                                        <td>
                                            @php
                                                $now = \Carbon\Carbon::now(); // Get the current time
                                                $orderCreatedAt = \Carbon\Carbon::parse($order->created_at); // Parse the order creation time
                                                $timeDifference = $now->diffInHours($orderCreatedAt); // Calculate the time difference in hours
                                            @endphp
                                      		 @if ($timeDifference <= 24)
                                           @if ($timeDifference == 0)

												 <span class="badge badge-success">Less than an hour ago</span>
                                              @elseif ($timeDifference == 1)
                              					<span class="badge badge-success">1 hr ago</span>

                                              @else
                              					<span class="badge badge-success">{{ $timeDifference }} hrs ago</span>

                                              @endif
                                            @elseif ($timeDifference <= 12)
                                            <span class="badge badge-success">{{ $orderCreatedAt->format('h:i A') }}</span>

                                            @else
                                            <span class="badge badge-danger">{{ $orderCreatedAt->format('d M, Y') }}</span>
                                            @endif
                                      	</td>
                                        <!--<td>{{ $order->ordered_delivery_date }}</td>-->
                                        <td>
                                        @foreach ($order->orderProducts as $orderProduct)
                                            @if(!empty($orderProduct->product->sku)) 
                                            {{ $orderProduct->product->sku }},
                                            @else
                                           
                                            @endif
                                        @endforeach
                                        </td>

                                        <td>{{ $order->product_qty }}</td>

                                        <td>{{ $setting->currency_icon }}{{ round($order->total_amount) }}</td>
                                        <td>
                                            @if ($order->order_status == 1)
                                            <span class="badge badge-success">Process</span>
                                            @elseif ($order->order_status == 2)
                                            <span class="badge badge-success">Courier</span>
                                            @elseif ($order->order_status == 3)
                                            <span class="badge badge-success">Completed</span>
                                            @elseif ($order->order_status == 4)
                                            <span class="badge badge-danger">Cancell</span>
                                            @elseif($order->order_status == 5)
                                            <span class="badge badge-danger">On Hold</span>
                                            @elseif($order->order_status == 6)
                                            <span class="badge badge-danger">Return</span>
                                            @else
                                            <span class="badge badge-danger">{{__('admin.Pending')}}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->note ? $order->note : '' }}</td>                                      
                                        <td>
                                            @if($order->courier_id == '3')
                                            SteadFast
                                            {{ $order->courier_tracking_id }}
                                            @elseif($order->courier_id == '2')
                                            Pathao
                                            {{ $order->courier_tracking_id }}
                                            @elseif($order->courier_id == '1')
                                            Redx
                                            {{ $order->courier_tracking_id }}
                                            @else
                                            @endif
                                            
                                        </td>
                                        <td>
                                          @if(!empty($order->assign->name))
                                          {{$order->assign->name}}
                                          @endif
                                        </td>
                                        <td>

                                        <a href="{{ route('admin.order-show',$order->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-eye" aria-hidden="true"></i></a>
										<a href="{{ route('admin.order-edit',$order->id) }}" class="btn btn-success btn-sm"><i class="fa fa-edit" aria-hidden="true"></i></a>
                                        <a href="javascript:;" data-toggle="modal" data-target="#deleteModal" class="btn btn-danger btn-sm" onclick="deleteData({{ $order->id }})"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                        <a href="javascript:;" data-toggle="modal" data-target="#orderModalId-{{ $order->id }}" class="btn btn-warning btn-sm"><i class="fas fa-truck" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                   @endif
                                  @endforeach
                            </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
          </div>
        </section>
      </div>


      <!-- Modal -->
      @foreach ($seller_orders as $index => $order)
      <div class="modal fade" id="orderModalId-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                      <div class="modal-header">
                              <h5 class="modal-title">{{__('admin.Order Status')}}</h5>
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                          </div>
                  <div class="modal-body">
                      <div class="container-fluid">
                          <form action="{{ route('admin.update-order-status',$order->id) }}" method="POST">
                            @method('PUT')
                              @csrf
                              <div class="form-group">
                                  <label for="">{{__('admin.Payment')}}</label>
                                  <select name="payment_status" id="" class="form-control">
                                      <option {{ $order->payment_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                      <option {{ $order->payment_status == 1 ? 'selected' : '' }} value="1">{{__('admin.Success')}}</option>
                                  </select>
                              </div>
                              <div class="form-group">
                                  <label for="">{{__('admin.Order')}}</label>
                                  <select name="order_status" id="" class="form-control">
                                    <option {{ $order->order_status == 0 ? 'selected' : '' }} value="0">{{__('admin.Pending')}}</option>
                                    <option {{ $order->order_status == 1 ? 'selected' : '' }} value="1">{{__('admin.In Progress')}}</option>
                                    <option {{ $order->order_status == 2 ? 'selected' : '' }}  value="2">{{__('admin.Delivered')}}</option>
                                    <option {{ $order->order_status == 3 ? 'selected' : '' }} value="3">{{__('admin.Completed')}}</option>
                                    <option {{ $order->order_status == 4 ? 'selected' : '' }} value="4">{{__('admin.Declined')}}</option>
                                  </select>
                              </div>


                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('admin.Close')}}</button>
                      <button type="submit" class="btn btn-primary">{{__('admin.Update Status')}}</button>
                  </div>
                </form>
              </div>
          </div>
      </div>


      <div class="modal fade" id="demoModal" tabindex="-1" role="dialog" aria-
            labelledby="demoModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="demoModalLabel">Assign User</h5>
								<button type="button" class="close" data-dismiss="modal" aria-
                                label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
            <form action="{{ route('admin.assignUserStore')}}" method="POST" id="order_assign_form">
            @csrf
            <div class="modal-body">

                <div class="div-group">
                    <label>Status</label>
                      <select class="form-control" name="assign_user_id" id="assign_user_id">
                        @foreach($users as $user)
                          <option value="{{ $user->id}}"> {{ $user->name}} </option>
                        @endforeach
                      </select>
                </div>

                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  <button type="submit" class="btn btn-primary"> Submit</button>
                </div>
    </form>
						</div>

					</div>
				</div>
			</div>


			</div>
      <!-- selected status modal -->

      <div class="modal fade" id="statusModal" tabindex="-1" role="dialog" aria-
            labelledby="demoModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="demoModalLabel">Change Status</h5>
								<button type="button" class="close" data-dismiss="modal" aria-
                                label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
						</div>
						<div class="modal-body">
            <form action="{{ route('admin.multuOrderStatusUpdate')}}" method="POST" id="order_status_update_form">
            @csrf


          <div class="div-group">

          <select class="form-control" name="order_status" id="multi_status" required>

                   <option  value="3">{{__('admin.Completed')}}</option>
                   <option  value="0">{{__('admin.Pending')}}</option>
                   <option  value="1">{{__('admin.In Progress')}}</option>
                   <option  value="2">{{__('admin.Delivered')}}</option>

                   <option  value="4">{{__('admin.Declined')}}</option>

</select>



               <!-- <input type="text" name="order_status" id="multi_status"> -->
          </div>

              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"> Submit</button>
              </div>
            </form>
						</div>

					</div>
				</div>
			</div>


			</div>

      <!-- change status -->
      @endforeach



<script>
    function deleteData(id){
        
        $("#deleteForm").attr("action",'{{ url("admin/delete-order/") }}'+"/"+id)
    }
    $(document).ready(function(){

    $("select[name='redx_status']").on('change', function(){
      getOrderList();
    });

    $("select[name='courier_type']").on('change', function(){
      getOrderList();
    });

    $('.order_sts').on('click', function(){
      getOrderList();
    });

    $(document).on('change', 'select#seller_name', function() {
        let seller_id = $(this).val();
        
        let url = "{{ route("admin.seller-wise-order") }}"+'?seller_id='+seller_id;
        window.location.href = url;
    });

    $(".check_all").on('change',function(){
      $(".order_checkbox").prop('checked',$(this).is(":checked"));
    });

    $(document).on('submit', 'form#order_status_update_form', function(e){

        e.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        let status=$(document).find('select#multi_status').val();

        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){
            toastr.error('Please Select An Order First !');
            return ;
        }

        $.ajax({
    type: 'POST', // Change the request method to POST
    url: url,
    data: {
        status: status,
        order_ids: order_ids,
        _token: '{{ csrf_token() }}' // Include the CSRF token
    },
    success: function (res) {
        if (res.status == true) {
            toastr.success(res.msg);
            window.location.reload();
        } else if (res.status == false) {
            toastr.error(res.msg);
        }
    },
});



    });

    $(document).on('submit', 'form#order_assign_form', function(e){
        e.preventDefault();
        var url = $(this).attr('action');
        var method = $(this).attr('method');
        let assign_user_id=$(document).find('select#assign_user_id').val();

        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){
            toastr.error('Please Select An Order First !');
            return ;
        }

        $.ajax({
           type:'GET',
           url:url,
           data:{assign_user_id,order_ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                window.location.reload();

            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });

    });

    $(document).on('click', 'a.multi_order_delete', function(e){
        e.preventDefault();
        var url = $(this).attr('href');

        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){
            toastr.error('Please Select An Order First !');
            return ;
        }

        $.ajax({
           type:'GET',
           url:url,
           data:{order_ids},
           success:function(res){
               if(res.status==true){
                toastr.success(res.msg);
                window.location.reload();

            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });

    });
    
    $(document).on('click', 'a.multi_order_generate', function(e){
        e.preventDefault();
        var url = $(this).attr('href');

        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }

        $.ajax({
           type:'GET',
           url,
           data:{order_ids},
           success:function(res){
               if(res.status==true){
                    toastr.success(res.msg);
                    window.location.reload();
            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });

    });

    $(document).on('click', 'a.multi_order_print', function(e){
        e.preventDefault();
        var url = $(this).attr('href');

        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }

        $.ajax({
           type:'GET',
           url,
           data:{order_ids},
           success:function(res){
               if(res.status==true){
                   console.log(res.items, res.info);
                   var myWindow = window.open("", "_blank");
  				   myWindow.document.write(res.view);
                // toastr.success(res.msg);
                // window.location.reload();

            }else if(res.status==false){
                toastr.error(res.msg);
            }
           }
        });

    });


  //Redx Courier Service
  $(document).on('click', 'a.send_to_redx', function(e){
        e.preventDefault();
    	var statusValue = $("input[name='status']:checked").val();
        var url = $(this).attr('href');
    	let link = $(this);
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }

        // else if(statusValue != '0'){
        //     toastr.error('Only On The Way Orders are Accepted!');
        //     return ;
        // }

        $.ajax({
           type:'GET',
           url,
           data:{order_ids},
           beforeSend: function(){
             link.addClass('disable-click');
             link.text('Please wait...');
           },
           success:function(res){
               link.removeClass('disable-click');
               link.text('Send to Redx');
               if(res.status){
                toastr.success(res.msg);

            }else{
                toastr.error(res.msg);
            }
           }
        });

    });

  //Pathao Courier Service
  $(document).on('click', 'a.send_to_pathao', function(e){
        e.preventDefault();
    	var statusValue = $("input[name='status']:checked").val();
        var url = $(this).attr('href');
    	let link = $(this);
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }

        // else if(statusValue != '0'){
        //     toastr.error('Only On The Way Orders are Accepted!');
        //     return ;
        // }


        $.ajax({
           type:'GET',
           url,
           data:{order_ids},
           beforeSend: function(){
             link.addClass('disable-click');
             link.text('Please wait...');
           },
           success:function(res){
               link.removeClass('disable-click');
               link.text('Send to Pathao');
               if(res.status){
                toastr.success(res.msg);

            }else{
                toastr.error(`Invoice :${res.invoice} something went wrong!`);
              	console.log(res.errors);
            }
           }
        });

    });
    
    $(document).on('click',".remove",function(e) {
        var whichtr = $(this).closest("tr");
        whichtr.remove();  
        calculateSum();    
    });

  //Steadfast Courier Service
  $(document).on('click', 'a.send_to_steadfast', function(e){
        e.preventDefault();
    	var statusValue = $("input[name='status']:checked").val();
        var url = $(this).attr('href');
    	let link = $(this);
        var order = $('input.order_checkbox:checked').map(function(){
          return $(this).val();
        });
        var order_ids=order.get();

        if(order_ids.length ==0){
            toastr.error('Please Select Atleast One Order!');
            return ;
        }

        // else if(statusValue != '0'){
        //     toastr.error('Only On The Way Orders are Accepted!');
        //     return ;
        // }


        $.ajax({
           type:'GET',
           url,
           data:{order_ids},
           beforeSend: function(){
             link.addClass('disable-click');
             link.text('Please wait...');
           },
           success:function(res){
               link.removeClass('disable-click');
               link.text('Send to Steadfast');
               if(res.status){
                toastr.success(res.msg);

            }else{
                toastr.error(`Invoice :${res.invoice} something went wrong!`);
              	console.log(res.errors);
            }
           }
        });
    });
})
</script>
@endsection
