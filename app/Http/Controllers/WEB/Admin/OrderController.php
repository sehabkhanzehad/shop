<?php

namespace App\Http\Controllers\WEB\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Setting;
use App\Models\OrderProduct;
use App\Models\OrderProductVariant;
use App\Models\ProductVariant;
use App\Models\OrderAddress;
use App\Models\Information;
use App\Models\Courier;
use App\Models\Shipping;
use App\Models\User;
use App\Models\Size;
use App\Models\Color;
use App\Models\FlashSaleProduct;
use App\Models\FlashSale;
use App\Models\Coupon;
use App\Models\ProductStock;
use App\Models\OrderNote;


use App\Models\Product; 
use App\Models\productColorVariation;

use Illuminate\Support\Facades\Http;
use DB;
use Auth;
class OrderController extends Controller
{
  	private $redx_api_base_url = '';
  	private $redx_api_access_token = '';
  	private $pathao_api_base_url = '';
  	private $pathao_api_access_token = '';
    private $pathao_store_id = '';
    private $steadfast_api_base_url = '';
  	private $steadfast_api_key = '';
    private $steadfast_secret_key = '';
    private $util = '';
    public function __construct()
    {
        // $this->middleware('auth:admin');
      	$info = Information::first();
      	$this->redx_api_base_url = $info->redx_api_base_url.'/';
      	$this->redx_api_access_token = 'Bearer '.$info->redx_api_access_token;
      	$this->pathao_api_base_url = $info->pathao_api_base_url.'/aladdin/api/v1/';
      	$this->pathao_api_access_token = 'Bearer '.$info->pathao_api_access_token;
      	$this->pathao_store_id = $info->pathao_store_id;
      	$this->steadfast_api_base_url = $info->steadfast_api_base_url;
      	$this->steadfast_api_key = $info->steadfast_api_key;
      	$this->steadfast_secret_key = $info->steadfast_secret_key;
    }

    public function index(Request $request){

        if(!auth()->user()->can('admin.all-order')){
            abort(403, 'Unauthorized action.');
        } 
        
        
        
        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
        })->get();
        
        $q = $request->q;
      
        $query = Order::with('user', 'assign')->whereHas('orderProducts.product', function($q) {
           $q->whereNotNull('name');
        });
          
        if (!empty($q)) {
            $query->where(function ($row) use ($q) {
                $row->where('order_id', 'like', '%' . $q . '%');
                $row->orWhere('total_amount', 'like', '%' . $q . '%');
                $row->orWhereHas('orderAddress', function ($q1) use ($q) {
                    $q1->where('shipping_name', 'like', '%' . $q . '%')
                        ->orWhere('shipping_phone', 'like', '%' . $q . '%')
                        ->orWhere('shipping_address', 'like', '%' . $q . '%');
                });
            });
        }
    
        $orders = $query->latest()->paginate(20);
        $orders->map(function($sale){
            $sale->courier_tracking_link = '';
            $url = '';
            if($sale->courier_id == 1 && $sale->courier_tracking_id) 
            {
                $url = "https://redx.com.bd/track-global-parcel/?trackingId={$sale->courier_tracking_id}";
            }
            
            else if($sale->courier_id == 3 && $sale->courier_tracking_code) 
            {
                $url = "https://steadfast.com.bd/t/{$sale->courier_tracking_code}";
            }
            
            if($url)
            {
               $sale->courier_tracking_link = "<a href='{$url}' target='_blank'>Tracking Link</a>";
            }
            
            return $sale;
            
        });
        
        $title = trans('admin_validation.All Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting', 'users'));
    }
    
    public function index_seller() {
        
        if(!auth()->user()->can('admin.all-order')){
            abort(403, 'Unauthorized action.');
        }
        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
      })->get();
        $orders = Order::with('user', 'assign', 'orderProducts.product')->orderBy('id','desc')->get();
        //  dd($orders); 
        $title = trans('admin_validation.All Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting', 'users'));
    }

    public function deleteAllOrder(){
      if(!auth()->user()->can('admin.deleteAllOrder')){
            abort(403, 'Unauthorized action.');
        }
        DB::beginTransaction();
        try {
            $orders=DB::table('orders')->select('id')->whereIn('id', request('order_ids'))->get();
            
            foreach ($orders as $key => $order) {
                $item=Order::find($order->id);
                if($item->orderProducts()->count()){
                    $item->orderProducts()->delete();
                }
                $item->delete();
            }
            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Order Is Deleted!!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }
    }
    
    public function status_wise_order(Request $request){    
        $setting = Setting::first();
        $query = Order::whereHas('orderProducts.product', function($q){
          			$q->whereNotNull('name');
        		});
      
        if(Auth::user()->hasRole('worker'))
        {
            $received_order = $query->where('order_status', $request->statusValue)
               ->where('assign_user_id', Auth::id())
               ->latest()->get();
        }
      
        else if(Auth::user()->hasRole('admin'))
        {
            $received_order = $query->where('order_status', $request->statusValue)             
               ->latest()->get();
        } else { 
            $received_order = $query->where('order_status', $request->statusValue)             
               ->latest()->get();
        }      
      
        $view = view('admin.received_order', compact('received_order','setting'))->render();
        return response()->json(['success'=>true, 'view'=>$view]);
    }

    public function assignUserStore(){
  	if(!auth()->user()->can('admin.assignUserStore')){
            abort(403, 'Unauthorized action.');
        }
        DB::table('orders')->whereIn('id', request('order_ids'))->update(['assign_user_id'=>request('assign_user_id')]);
        return response()->json(['status'=>true ,'msg'=>'User Assigned!!']);
    }
    
    public function pos_print($id) {
        $order = Order::with('user','orderProducts.orderProductVariants', 'orderAddress')->find($id); 
        return view('admin.pos_print', compact('order'));
    }
    
    public function multi_pos_print() {
        $items=Order::with('user','orderProducts.orderProductVariants','orderAddress')->whereIn('id', request('order_ids'))->get();
        $html=view('admin.multi_pos_print', compact('items'))->render();
        return response()->json(['success'=>true,'html'=>$html]);
    }
    
    public function orderStatus($id){
      
  		if(!auth()->user()->can('admin.orderStatus')){
            abort(403, 'Unauthorized action.');
        }
  
        $item=Order::find($id);
        $status=getOrderStatus();
        return view('admin.status_update', compact('item','status'));
    }
    
    public function orderPrint(Request $request) {
        if(!auth()->user()->can('admin.orderList')){
            abort(403, 'Unauthorized action.');
        }
        
        $item = Order::where('id', $request->order_id)->first();
        
        if($item->order_status == '1') {
            $item->update([
                'order_status' => 2    
            ]);
        } else {
            
        }
        
        return response()->json([
            'status' => true    
        ]) ;
        
    }

    public function orderList()
    {
  		if(!auth()->user()->can('admin.orderList')){
            abort(403, 'Unauthorized action.');
        }
  
        $items=Order::with('user','orderProducts.orderProductVariants','orderAddress')->whereIn('id', request('order_ids'))->get();
        foreach($items as $item) {
            if($item->order_status == '1') {
                $item->update([
                    'order_status' => 2     
                ]);
            } else {
                
            }
        }
        $setting = Setting::first();
        $view = view('admin.print_all', compact('items', 'setting'))->render();
        return response()->json(['status'=> true, 'items'=> $items, 'setting'=> $setting,'view'=>$view]);
    }

    public function multuOrderStatusUpdate(Request $request)
    {
  		if(!auth()->user()->can('admin.multuOrderStatusUpdate')){
            abort(403, 'Unauthorized action.');
        }
  	
        foreach($request->order_ids as $id){
            $item=Order::where('id', $id)->first();
            $item->order_status=$request->status;
            $item->save();
        }
        // DB::table('orders')->whereIn('id', request('order_ids'))->update(['order_status'=>request('order_status')]);
        return response()->json(['status' => true, 'msg' => 'Order status updated successfully']);
        // foreach(request('order_ids') as $id)
        // {

        //     dd($item->order_status);
        //     // $item->save();

        // }
    }

    public function pendingOrder(){
        if(!auth()->user()->can('admin.pending-order')){
            abort(403, 'Unauthorized action.');
        }
        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
      })->get();
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status', 0)->paginate(20);
        // dd($orders);
        $title = trans('admin_validation.Pending Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting', 'users'));
    }

    public function processOrder(){
        if(!auth()->user()->can('admin.pregress-order')){
            abort(403, 'Unauthorized action.');
        }
        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
      })->get();
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',1)->paginate(20);
        // $orders = Order::with('user')->orderBy('id','desc')->where('order_status',1)->active()->get();
        $title = trans('Process Orders');
        $setting = Setting::first();

        return view('admin.order', compact('orders','title','setting', 'users'));
    }

    public function courierOrder(){

        if(!auth()->user()->can('admin.delivered-order')){
            abort(403, 'Unauthorized action.');
        }
        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
      })->get();
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',2)->paginate(20);
        // $orders = Order::with('user')->orderBy('id','desc')->where('order_status',2)->active()->get();
        $title = trans('Courier Orders');
        $setting = Setting::first();

        return view('admin.order', compact('orders','title','setting', 'users'));
    }
    
    public function returnOrder(){
        if(!auth()->user()->can('admin.delivered-order')){
            abort(403, 'Unauthorized action.');
        }
        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
      })->get();
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',6)->paginate(20);
        // $orders = Order::with('user')->orderBy('id','desc')->where('order_status',3)->active()->get();
        $title = trans('Return Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting', 'users'));
    }
    
    public function onholdOrder(){
        if(!auth()->user()->can('admin.delivered-order')){
            abort(403, 'Unauthorized action.');
        }
        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
      })->get();
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',5)->paginate(20);
        // $orders = Order::with('user')->orderBy('id','desc')->where('order_status',3)->active()->get();
        $title = trans('admin_validation.On Hold Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting', 'users'));
    }

    public function completedOrder(){
        if(!auth()->user()->can('admin.completed-order')){
            abort(403, 'Unauthorized action.');
        }
        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
      })->get();
        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',3)->paginate(20);
        // $orders = Order::with('user')->orderBy('id','desc')->where('order_status',3)->active()->get();
        $title = trans('Completed Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting', 'users'));
    }

    public function cancellOrder(){

        if(!auth()->user()->can('admin.completed-order')){
            abort(403, 'Unauthorized action.');
        }

        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
      })->get();

        $orders = Order::with('user')->orderBy('id','desc')->where('order_status',4)->paginate(20);
        // $orders = Order::with('user')->orderBy('id','desc')->where('order_status',4)->active()->get();
        $title = trans('Cancell Orders');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting', 'users'));
    }

    public function cashOnDelivery(){

        if(!auth()->user()->can('admin.cash-on-delivery')){
            abort(403, 'Unauthorized action.');
        }
        $users = $users=User::whereHas('roles', function($q){
            $q->whereNotNull('name');
      })->get();
        $orders = Order::with('user')->orderBy('id','desc')->where('cash_on_delivery',1)->get();
        // $orders = Order::with('user')->orderBy('id','desc')->where('cash_on_delivery',1)->active()->get();
        $title = trans('admin_validation.Cash On Delivery');
        $setting = Setting::first();
        return view('admin.order', compact('orders','title','setting', 'users'));
    }

    public function show($id){

        if(!auth()->user()->can('admin.order-show')){
            abort(403, 'Unauthorized action.');
        }

        $order = Order::with('user','orderProducts.orderProductVariants',  'orderAddress')->find($id); 
      $orderbyNumber = Order::with('orderProducts.Product','assign', 'orderAddress', 'user')->where('user_id', $order->user_id)->get();
        $setting = Setting::first();
        return view('admin.show_order',compact('order','setting' , 'orderbyNumber'));
    }
  
    public function getOrderNewProductadd(Request $request){
        if(!auth()->user()->can('admin.addNewOrderProduct')){
            abort(403, 'Unauthorized action.');
        }
        $data = Product::where('name', 'LIKE', '%'. $request->get('search'). '%')
                    ->get();
        return response()->json($data);
    }
    
    public function addProduct(Request $request){
        if(!auth()->user()->can('admin.addNewOrderProduct')){
            abort(403, 'Unauthorized action.');
        }
        $data = Product::where('name', 'LIKE', '%'. $request->get('search'). '%')
                    ->get();
        return response()->json($data);
    }
    
    public function getOrderNewProduct(Request $request){
    if(!auth()->user()->can('admin.addNewOrderProduct')){
            abort(403, 'Unauthorized action.');
        }
        $data = Product::where('name', 'LIKE', '%'. $request->get('search'). '%')
                    ->get();
        return response()->json($data);
    }
    
    public function addNewOrderProduct() {
        $data = Product::where('name', 'LIKE', '%'. $request->get('search'). '%')->get();
       
        dd($data);
        // $data = Variation::join('products' ,'products.id','variations.product_id')
        //     ->join('sizes' ,'sizes.id','variations.size_id')
        //     ->join('colors' ,'colors.id','variations.color_id')
        //     // ->join('product_stocks' ,'product_stocks.variation_id','variations.id')
        //     ->select("variations.id",

        //         DB::raw("TRIM(CONCAT(products.name,' (',sizes.title,'),(',colors.name,')')) AS value")
        //         )
        //     // ->where('product_stocks.quantity','>',0)
        //     ->where('products.name', 'LIKE', '%'. $request->get('search'). '%')
        //     ->get();
        
        return response()->json($data);
    }
  
  public function addNewProductEntryadd(Request $request){
    if(!auth()->user()->can('admin.addNewProductEntry')){
            abort(403, 'Unauthorized action.');
        }
        
        $id=$request->id;
        $variation=Product::find($id);
    	$pr_id = $variation->id;
    
        if($variation->type == 'variable') {
            $color_array = [];
            $size_array = [];
            
        	foreach($variation->variations as $v)
            {
              $size_array[] = $v;
            }  
            
            foreach($variation->colorVariations as $v) {
                $color_array[] = $v;
            }
          
          $html = view('admin.orders.add_var_product_row', compact('variation','pr_id','color_array','size_array'))->render();  
          return response()->json(['success' => true, 'html' => $html, 'pr_id' => $pr_id]);
        } else {
          
            $stock_check = ProductStock::where('product_id', $pr_id)->where('size_id', '1')->where('color_id', '1')->first();
            
        	$stock_qty = $stock_check->quantity;
            if($stock_qty <= 0) {
                return response()->json([
                    'success' => false      
                ]);
            } 
          $html = view('admin.orders.add_single_product_row', compact('variation','pr_id','stock_qty'))->render();  
          return response()->json(['success' => true, 'html' => $html, 'pr_id' => $pr_id]);
        }
    }
    
    public function addNewProductEntryedit(Request $request) {
        if(!auth()->user()->can('admin.addNewProductEntry')){
            abort(403, 'Unauthorized action.');
        }
        
        $id=$request->id;
        $variation=Product::find($id);
    	$pr_id = $variation->id;
    
        if($variation->type == 'variable') {
            $color_array = [];
            $size_array = [];
            
        	foreach($variation->variations as $v)
            {
              $size_array[] = $v;
            }  
            
            foreach($variation->colorVariations as $v) {
                $color_array[] = $v;
            }
          
          $html = view('admin.orders.edit_var_product_row', compact('variation','pr_id','color_array','size_array'))->render();  
          return response()->json(['success' => true, 'html' => $html, 'pr_id' => $pr_id]);
        } else {
          $html = view('admin.orders.edit_single_product_row', compact('variation','pr_id'))->render();  
          return response()->json(['success' => true, 'html' => $html, 'pr_id' => $pr_id]);
        }
    }
  	
  public function addNewProductEntry(Request $request){
    if(!auth()->user()->can('admin.addNewProductEntry')){
            abort(403, 'Unauthorized action.');
        }
        
        $id=$request->id;
        $variation=Product::find($id);
    	$pr_id = $variation->id;
    
        if($variation->type == 'variable') {
            $color_array = [];
            $size_array = [];
            
        	foreach($variation->variations as $v)
            {
              $color_array[] = $v;
            }  
            
            foreach($variation->colorVariations as $v) {
                $size_array[] = $v;
            }
          
          $html = view('admin.orders.create_table_data2', compact('variation','pr_id','color_array','size_array'))->render();  
          return response()->json(['success' => true, 'html' => $html, 'pr_id' => $pr_id]);
        } else {
          $html = view('admin.orders.create_table_data', compact('variation','pr_id'))->render();  
          return response()->json(['success' => true, 'html' => $html, 'pr_id' => $pr_id]);
        }
        
       // data=Product::all();
    /*
        if ($variation) {    
              
        $html = ' <tr>
            <td><img src="/uploads/custom-images2/' . $variation->thumb_image . '" height="50" width="50"/></td>
            <td>' . $variation->name . '</td>
            <input type="hidden" value="'.$variation->name.'" name="product_name[]">
            <td>
                <input class="form-control price" name="price[]" type="number" value="' . $variation->price . '" required/>
            </td>
            <td>
                <input class="form-control offer_price" name="offer_price[]" type="number" value="' . $variation->offer_price . '" required/>
            </td>
            <td>
                <input class="form-control qty quantity" name="quantity[]" type="number" value="1" required min="1"/>
                <input type="hidden" class="form-control" name="product_id[]" value="' . $pr_id . '" required/>
            </td>
            <td class="row_total">' .$variation->price. '</td>
            <td>
                <a href="#" class="remove btn btn-sm btn-danger"> Delete </a>
            </td>
        </tr>';
    return response()->json(['success' => true, 'html' => $html, 'pr_id' => $pr_id]);
} else {
    return response()->json(['success' => false, 'msg' => 'Product Not Found !!']);
}
    
    */

    }
  	
  public function addNewOrderStoreOld(Request $request){
    if(!auth()->user()->can('admin.addNewOrderStore')){
            abort(403, 'Unauthorized action.');
        }
        $inputs = $request->validate([
            'billing_name' => '',
            'billing_email' => '',
            'billing_phone' => '',
            'billing_address' => '',
            'billing_country' => '',
            'billing_state' => '',
            'billing_city' => '',
            'billing_address_type' => '',
            'shipping_name' => '',
            'shipping_email' => '',
            'shipping_phone' => '',
            'shipping_address' => '',
            'shipping_country' => '',
            'shipping_state' => '',
            'shipping_city' => '',
            'shipping_address_type' => '',
            'payment_method' => '',
            'shipping_method' => '',
            'transection_id' => '',
          	'ip_address' => '',
            'shipping_rule' => ''
        ]);       
        
        $user = Auth::user();      
		$checkinguser = User::where('phone', $inputs['shipping_phone'])->first();
        //dd($checkinguser);
       if(empty(auth()->user()->id)){
            
        	$user = User::create([
              'name' => $request->shipping_name,
              'phone' => $request->shipping_phone,
              'address' => $request->shipping_address,

            ]);
          $data['user_id']=$user->id;

        } else {
        	$data['user_id']=auth()->user()->id;
        }

        $shipping_rule = Shipping::find($inputs['shipping_rule'])->shipping_rule;    
        //$shipping_id = $inputs['shipping_method'];

        $couponCode = isset($coupon['code']) ? $coupon['code'] : null;
        // $total = $this->calculateCartTotal(
        //     $user,
        //     1235,
        //     1
        // );

      	$lastOrder = Order::latest()->first();

    // Calculate the new order number (increment the last order number by 1)
    $newOrderNumber = $lastOrder ? $lastOrder->order_id + 1 : 1;

        $data = [];
        $data['order_id'] = $newOrderNumber;
        $data['user_id'] =  $user->id;
      	$data['order_phone'] =  $user->shipping_phone;
      	$data['ip_address'] =  $request->ip_address;
        // $data['product_qty'] = cartTotalAmount()['total_qty'];
        $data['product_qty'] =  $request->total_qty;
        
        //$data['payment_method'] = $request->payment_method;
        $data['shipping_method'] = $shipping_rule;
    	$data['area_id'] = $request->pathao_area_id ? $request->pathao_area_id : $request->redx_area_id;
        $data['area_name']  = $request->area_name;
        $data['city']  = $request->city;
        $data['state']  = $request->state;
        $data['store_id']  = $request->store_id;
        $data['weight']  = $request->weight;
        $data['courier_id']  = $request->courier_id;
        $data['courier_tracking_id']  = $request->courier_tracking_id;
        $data['ordered_delivery_date'] = $request->ordered_delivery_date;
        $data['ordered_delivery_time'] = $request->ordered_delivery_time;
        $data['total_amount'] = $request->total_amount;
        //$data['coupon_coast'] = $total["coupon_price"];
        //$data['shipping_cost'] = $total["shipping_fee"];
        $data['shipping_cost'] = $request->shipping_charge;
        $data['order_status'] = 0;
        $data['cash_on_delivery'] = 0;
        $data['additional_info'] = 0;
        $data['note'] = $request->note;
        $data['assign_id'] = User::inRandomOrder()->first()->id;
        // Order Assign Among Users Start
		//dd($data);
        $assign_user_id=1;
        $users=User::whereHas('roles', function($query){
                          $query->where('roles.name','Employee');
                        })->where('active_status',1)
                        ->select('id')
                        ->pluck('id')->toArray();

        $ordering=count($users)-1;
        if(count($users)==1){
            $assign_user_id=$users[0];
        }else if($ordering>0){
            $order=Order::latest()->take($ordering)->get()->pluck('assign_user_id')->toArray();

            $output = array_merge(array_diff($order, $users), array_diff($users, $order));

            if(!empty($output)){
                $assign_user_id=$output[0];
                $data['assign_user_id'] = $assign_user_id;
            }

            else {
                $data['assign_user_id'] = $assign_user_id;
            }

        }

        // Order Assign Among Users End.
        try{
            DB::beginTransaction();
            $order = Order::create($data);
            if($order)
            {
              if (isset($request->product_id)) {  
                 
     		 $data = [];
     		 foreach ($request->product_id as $key => $product_id) {
                
                if($request->offer_price[$key] > '0') {
                    $price = $request->offer_price[$key];
                } else {
                    $price = $request->price[$key];
                }
           
            
              $data[] = [
                  'order_id' => $order->id,
                  'product_id' => $product_id,
                  'variation_color_id' => isset($request->variation_color_id[$key]) ? $request->variation_color_id[$key] : '',
                  'variation'  => isset($request->variation_size[$key]) ? $request->variation_size[$key] : '',
                  'product_name' => $request->product_name[$key], 
                  'qty' => $request->quantity[$key],
                  'unit_price' => $price,
                  'total_discount' =>  isset($request->discount_price[$key]) ?  (int) $request->quantity[$key] * (int) $request->discount_price[$key] : '',
              ]; 
              
                    $color_id = isset( $request->variation_color[$key]) ? $request->variation_color[$key] : '1';
                   
                    $size_id = isset( $request->variation_color[$key]) ? $request->variation[$key] : '1';
                    $product_id = $request->product_id[$key];
                    $quantity = $request->quantity[$key];
                    
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                   
                    $ultimate_stock = $stockCheck->quantity - $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
            }
            
      if (!empty($data)) {
          $order->orderProducts()->createMany($data); // Use orderProducts() instead of OrderProduct()
      }
  } 

                 $order->orderAddress()->create([
                    'billing_name' =>  $request->billing_name,
                    'billing_email' => $request->billing_email,
                    'billing_phone' =>  $request->billing_phone,
                    'billing_address' =>  $request->billing_address,
                    'billing_country' =>  $request->billing_country,
                    'billing_state' =>  $request->billing_state,
                    'billing_city' =>  $request->billing_city,
                    'billing_address_type' =>  $request->billing_address_type,
                    'shipping_name' => $request->shipping_name,
                    'shipping_email' => $request->shipping_email,
                    'shipping_phone' =>  $request->shipping_phone,
                    'shipping_address' =>  $request->shipping_address,
                    'shipping_country' =>  $request->shipping_country,
                    'shipping_state' =>  $request->shipping_state,
                    'shipping_city' =>  $request->shipping_city,
                    'shipping_address_type' =>  $request->shipping_address_type,
                    'payment_method' => $request->payment_method,
                    'shipping_method' => $request->shipping_method,
                    'transection_id' => $request->transection_id,
                ]);             
            }

            DB::commit();

            session()->put('cart', []);
            session()->put('coupon', []);
            
            $notification = trans('admin_validation.Created Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('admin.all-order')->with($notification);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ]);
        }
  
  }
  
  public function addNewOrderStore(Request $request){
    if(!auth()->user()->can('admin.addNewOrderStore')){
            abort(403, 'Unauthorized action.');
        }
        $inputs = $request->validate([
            'billing_name' => '',
            'billing_email' => '',
            'billing_phone' => '',
            'billing_address' => '',
            'billing_country' => '',
            'billing_state' => '',
            'billing_city' => '',
            'billing_address_type' => '',
            'shipping_name' => '',
            'shipping_email' => '',
            'shipping_phone' => '',
            'shipping_address' => '',
            'shipping_country' => '',
            'shipping_state' => '',
            'shipping_city' => '',
            'shipping_address_type' => '',
            'payment_method' => '',
            'shipping_method' => '',
            'transection_id' => '',
          	'ip_address' => '',
            'shipping_rule' => ''
        ]);       
        
        $user = Auth::user();      
		$checkinguser = User::where('phone', $inputs['shipping_phone'])->first();
        //dd($checkinguser);
       if(empty(auth()->user()->id)){
            
        	$user = User::create([
              'name' => $request->shipping_name,
              'phone' => $request->shipping_phone,
              'address' => $request->shipping_address,

            ]);
          $data['user_id']=$user->id;

        } else {
        	$data['user_id']=auth()->user()->id;
        }

        $shipping_rule = Shipping::find($inputs['shipping_rule'])->shipping_rule;    
        //$shipping_id = $inputs['shipping_method'];

        $couponCode = isset($coupon['code']) ? $coupon['code'] : null;
        // $total = $this->calculateCartTotal(
        //     $user,
        //     1235,
        //     1
        // );

      	$lastOrder = Order::latest()->first();

    // Calculate the new order number (increment the last order number by 1)
    $newOrderNumber = $lastOrder ? $lastOrder->order_id + 1 : 1;

        $data = [];
        $data['order_id'] = $newOrderNumber;
        $data['user_id'] =  $user->id;
      	$data['order_phone'] =  $user->shipping_phone;
      	$data['ip_address'] =  $request->ip_address;
        // $data['product_qty'] = cartTotalAmount()['total_qty'];
        $data['product_qty'] =  $request->total_qty;
        
        //$data['payment_method'] = $request->payment_method;
        $data['shipping_method'] = $shipping_rule;
    	$data['area_id'] = $request->pathao_area_id ? $request->pathao_area_id : $request->redx_area_id;
        $data['area_name']  = $request->area_name;
        $data['city']  = $request->city;
        $data['state']  = $request->state;
        $data['store_id']  = $request->store_id;
        $data['weight']  = $request->weight;
        $data['courier_id']  = $request->courier_id;
        $data['courier_tracking_id']  = $request->courier_tracking_id;
        $data['ordered_delivery_date'] = $request->ordered_delivery_date;
        $data['ordered_delivery_time'] = $request->ordered_delivery_time;
        $data['total_amount'] = $request->total_amount;
        //$data['coupon_coast'] = $total["coupon_price"];
        //$data['shipping_cost'] = $total["shipping_fee"];
        $data['shipping_cost'] = $request->shipping_charge;
        $data['order_status'] = 0;
        $data['cash_on_delivery'] = 0;
        $data['additional_info'] = 0;
        $data['note'] = $request->note;
        $data['assign_id'] = User::inRandomOrder()->first()->id;
        // Order Assign Among Users Start
		//dd($data);
        $assign_user_id=1;
        $users=User::whereHas('roles', function($query){
                          $query->where('roles.name','Employee');
                        })->where('active_status',1)
                        ->select('id')
                        ->pluck('id')->toArray();

        $ordering=count($users)-1;
        if(count($users)==1){
            $assign_user_id=$users[0];
        }else if($ordering>0){
            $order=Order::latest()->take($ordering)->get()->pluck('assign_user_id')->toArray();

            $output = array_merge(array_diff($order, $users), array_diff($users, $order));

            if(!empty($output)){
                $assign_user_id=$output[0];
                $data['assign_user_id'] = $assign_user_id;
            }

            else {
                $data['assign_user_id'] = $assign_user_id;
            }

        }

        // Order Assign Among Users End.
        try{
            DB::beginTransaction();
            $order = Order::create($data);
            if($order)
            {
              if (isset($request->product_id)) {  
                 
     		 $data = [];
     		 foreach ($request->product_id as $key => $product_id) {
                
                if($request->offer_price[$key] > '0') {
                    $price = $request->offer_price[$key];
                } else {
                    $price = $request->price[$key];
                }
                
                $color_id = isset( $request->variation_color[$key]) ? $request->variation_color[$key] : '1';
                   
                $size_id = isset( $request->variation[$key]) ? $request->variation[$key] : '1';
                
            
              $data[] = [
                  'order_id' => $order->id,
                  'product_id' => $product_id,
                  'variation_color_id' => isset($request->variation_color_id[$key]) ? $request->variation_color_id[$key] : '',
                  'variation'  => isset($request->variation_size[$key]) ? $request->variation_size[$key] : '',
                  'size_id'=>$size_id,
                  'color_id'=>$color_id,
                  'product_name' => $request->product_name[$key], 
                  'qty' => $request->quantity[$key],
                  'unit_price' => $price,
                  'total_discount' =>  isset($request->discount_price[$key]) ?  (int) $request->quantity[$key] * (int) $request->discount_price[$key] : '',
              ]; 
              
                    
                    $product_id = $request->product_id[$key];
                    $quantity = $request->quantity[$key];
                    
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                   
                    $ultimate_stock = $stockCheck->quantity - $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
            }
            
      if (!empty($data)) {
          $order->orderProducts()->createMany($data); // Use orderProducts() instead of OrderProduct()
      }
  } 

                 $order->orderAddress()->create([
                    'billing_name' =>  $request->billing_name,
                    'billing_email' => $request->billing_email,
                    'billing_phone' =>  $request->billing_phone,
                    'billing_address' =>  $request->billing_address,
                    'billing_country' =>  $request->billing_country,
                    'billing_state' =>  $request->billing_state,
                    'billing_city' =>  $request->billing_city,
                    'billing_address_type' =>  $request->billing_address_type,
                    'shipping_name' => $request->shipping_name,
                    'shipping_email' => $request->shipping_email,
                    'shipping_phone' =>  $request->shipping_phone,
                    'shipping_address' =>  $request->shipping_address,
                    'shipping_country' =>  $request->shipping_country,
                    'shipping_state' =>  $request->shipping_state,
                    'shipping_city' =>  $request->shipping_city,
                    'shipping_address_type' =>  $request->shipping_address_type,
                    'payment_method' => $request->payment_method,
                    'shipping_method' => $request->shipping_method,
                    'transection_id' => $request->transection_id,
                ]);             
            }

            DB::commit();

            session()->put('cart', []);
            session()->put('coupon', []);
            
            $notification = trans('admin_validation.Created Successfully');
            $notification=array('messege'=>$notification,'alert-type'=>'success');
            return redirect()->route('admin.all-order')->with($notification);

        }catch(\Exception $e){
            DB::rollback();
            return response()->json([
                'status' => false,
                'msg' => $e->getMessage(),
            ]);
        }
  
  }	
  
  public function add_new_order()
    {
      
      if(!auth()->user()->can('admin.addNewOrder')){
            abort(403, 'Unauthorized action.');
        }
      
        $order = Order::with('orderProducts')->get();
    	//dd($order);
        $areas = $this->getRedxAreaList();
      	// $stores = $this->getPathaoStoreList();
      	$cities = $this->getPathaoCityList();
        $couriers=Courier::all();
        $shippings = Shipping::all();
        return view('admin.add_new_order', compact('order', 'areas', 'cities', 'couriers','shippings'));
    }
  
	
    public function edit($id)
    {
    //   if(!auth()->user()->can('admin.order-edit')){
    //         abort(403, 'Unauthorized action.');
    //     }
      
        $order = Order::with('orderProducts')->where('id', $id)->first();
        // dd($order);
        $orderbyNumber = Order::with('orderProducts')->where('order_phone', $order->order_phone)->get();
        $areas = $this->getRedxAreaList();
      	// $stores = $this->getPathaoStoreList();
      	$cities = $this->getPathaoCityList();
        $couriers=Courier::all();
        $shippings = Shipping::all();
        
        $var_stock = ProductStock::first();
        // dd($var_stock);
        return view('admin.edit_order', compact('order', 'areas', 'cities', 'couriers','shippings','orderbyNumber', 'var_stock'));
    }

    public function updateOld(Request $request, $id)
    {
      if(!auth()->user()->can('admin.order.update')){
            abort(403, 'Unauthorized action.');
        }
      
        $order = Order::find($id);
        $data = [];
        
        if($request->order_status == '0' && $request->courier_id != '0') {
            $data['order_status'] = '1';
        } else {
            $data['order_status'] = $request->order_status;
        }
        
        $data['ordered_delivery_date'] = $request->ordered_delivery_date;
        $data['ordered_delivery_time'] = $request->ordered_delivery_time;
        $data['area_id'] = $request->pathao_area_id ? $request->pathao_area_id : $request->redx_area_id;
        $data['area_name']  = $request->area_name;
        $data['city']  = $request->city;
        $data['state']  = $request->state;
        $data['store_id']  = $request->store_id;
        $data['weight']  = $request->weight;
        $data['courier_id']  = $request->courier_id;
        $data['courier_tracking_id']  = $request->courier_tracking_id;
        $data['total_amount']  = $request->total_amount;
        $data['advance_amount'] = $request->advance_amount;
        $data['product_qty']  = $request->product_qty;
        $data['note'] = $request->note;

        $shipping_data = Shipping::find($request->shipping_charge_id);

        $data['shipping_method']  = $shipping_data->shipping_rule;
        $data['shipping_cost']  = $shipping_data->shipping_fee;
       
        $order->update($data);
        
        if(isset($request->order_product_id)) {
            $delete_line=OrderProduct::where('order_id', $id)
                                ->whereNotIn('id', $request->order_product_id)
                                ->get();
                                
            if($delete_line->count()) {
                foreach($delete_line as $line)  {
                    if($line->variation == null) {
                        $variation_size = 'free';
                    } else {
                        $variation_size = $line->variation;
                    }
                    if($line->variation_color_id == null) {
                        $variation_color = 'default';
                    } else {
                        $variation_color = $line->variation_color_id;
                    }
                    
                    $size = Size::where('title', $variation_size)->first();
                    $color = Color::where('name', $variation_color)->first();
                    
                    $size_id = $size->id;
                    $color_id = $color->id;
                    
                    $product_id = $line->product_id;
                    $quantity = $line->qty;
                    
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    
                    $ultimate_stock = $stockCheck->quantity + $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
                    
                    $line->delete();   
                }
            } 
        } else {
               
                foreach ($order->orderProducts as $key => $line) {
                    
                    $size = Size::where('title', $line->variation)->first();
                    $color = Color::where('name', $line->variation_color_id)->first();
                    
                    $size_id = $size->id;
                    $color_id = $color->id;
                    $product_id = $line->product_id;
                    $quantity = $line->qty;
                    
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    
                    $ultimate_stock = $stockCheck->quantity + $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
                    
                    $line->delete();  
                }
            }
        
       

        if (isset($request->product_id)) {
            foreach ($request->product_id as $key => $product_id) {
               
               if(isset($request->order_product_id[$key])) {
                $ord_pro = OrderProduct::where('id', $request->order_product_id[$key])->first();
                $data2 = [];
                $data2['qty'] = $request->quantity[$key];
                $data2['unit_price'] = $request->unit_price[$key];
                $data2['variation'] = $request->variation[$key];
                if (isset($request->variation_color_id[$key]) && ($request->variation_color_id[$key] !== null) && ($request->variation_color_id[$key] !== '')) {
                $data2['variation_color_id'] = $request->variation_color_id[$key];
                } else {
                    $data2['variation_color_id'] = 'Default';
                }
                
                $data2['total_discount'] = (int)$request->quantity[$key] * (int)$request->discount_price[$key];
                
                $ord_qty = (int)$ord_pro->qty;
                $pro_qty = (int)$request->quantity[$key];
               
                if($ord_qty < $pro_qty) {
                    
                    if (isset($request->color_id[$key]) && ($request->color_id[$key] !== null) && ($request->color_id[$key] !== '')) {
                        $color_id = $request->color_id[$key];
                    } else {
                        $color_id = 1;
                    }
                    
                    if (isset($request->variation_data[$key]) && ($request->variation_data[$key] !== null) && ($request->variation_data[$key] !== '')) {
                        $size_id = $request->variation_data[$key];
                    } else {
                        $size_id = 1;
                    }
                    $product_id = $request->product_id[$key];
                    $quantity = $request->quantity[$key];
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    
                    $quantity = $pro_qty - $ord_qty;
                    
                    $ultimate_stock = $stockCheck->quantity - $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
                    
                } else if($ord_qty > $pro_qty) {
                    
                    $color_id = $request->color_id[$key];
                    $size_id = $request->variation_data[$key];
                    $product_id = $request->product_id[$key];
                    $quantity = $request->quantity[$key];
                    
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    $quantity = $ord_qty - $pro_qty;
                   
                    $ultimate_stock = $stockCheck->quantity + $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
                    
                   
                } 
                 $ord_pro->update($data2);
               } else {
                   
                    if (isset($request->variation_color_id[$key]) && ($request->variation_color_id[$key] !== null) && ($request->variation_color_id[$key] !== '')) {
                        $variation_color_id = $request->variation_color_id[$key];
                    } else {
                        $variation_color_id = 'Default';
                    }
                    
                    if (isset($request->variation[$key]) && ($request->variation[$key] !== null) && ($request->variation[$key] !== '')) {
                        $variation_id = $request->variation[$key];
                    } else {
                        $variation_id = 'free';
                    }
                    
                    $offerPrice=$request->offer_price[$key];
                    if($offerPrice!=='0'){
                        $price=$offerPrice;
                    } else {
                        $price=$request->unit_price[$key];
                    }
                   
                    OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $request->product_id[$key],
                        'variation_color_id' => $variation_color_id,
                        'variation' => $variation_id,
                        'product_name' => $request->product_name[$key],
                        'unit_price' => $price,
                        'total_discount' => isset($request->discount_price[$key]) ?  (int) $request->quantity[$key] * (int) $request->discount_price[$key] : '',
                        'qty' => $request->quantity[$key]
                    ]);
                    
                    $product_id = $request->product_id[$key];
                    $quantity = $request->quantity[$key];
                    $color_id = isset($request->color_id[$key]) ? $request->color_id[$key] : '1';
                    $size_id = isset($request->variation_data[$key]) ? $request->variation_data[$key] : '1';
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    
                    $ultimate_stock = $stockCheck->quantity - $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
               }
            }
        } 
        
        
        
        if($request->order_status == '6' || $request->order_status == '4') {
            
            if (isset($request->order_id)) {
                foreach ($request->order_id as $key => $product_id) {
                    
                
                if (isset($request->color_id[$key]) && ($request->color_id[$key] !== null) && ($request->color_id[$key] !== '')) {
                        $color_id = $request->color_id[$key];
                    } else {
                        $color_id = 1;
                    }
                    
                    if (isset($request->variation_data[$key]) && ($request->variation_data[$key] !== null) && ($request->variation_data[$key] !== '')) {
                        $size_id = $request->variation_data[$key];
                    } else {
                        $size_id = 1;
                    }  
                    
                $product_id = $request->product_id[$key];
               
                $quantity = $request->quantity[$key];
                
                $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                
                $ultimate_stock = $stockCheck->quantity + $quantity;
                $stockCheck->update([
                    'quantity'=> $ultimate_stock,
                ]);
                }
            }
        } else {
            
        }

        $order_address = OrderAddress::where('order_id', $id)->first();
        $order_address_data = [];
        $order_address_data['shipping_name'] = $request->short_name;
      	$order_address_data['shipping_phone'] = $request->shipping_phone;
        $order_address_data['shipping_address'] = $request->shipping_address;
        $order_address->update($order_address_data);

        $notification = trans('admin_validation.Order Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.all-order')->with($notification);

    }
    
    public function update(Request $request, $id)
    {
      if(!auth()->user()->can('admin.order.update')){
            abort(403, 'Unauthorized action.');
        }
      
        $order = Order::find($id);
        $data = [];
        
        if($request->order_status == '0' && $request->courier_id != '0') {
            $data['order_status'] = '1';
        } else {
            $data['order_status'] = $request->order_status;
        }
        
        $data['ordered_delivery_date'] = $request->ordered_delivery_date;
        $data['ordered_delivery_time'] = $request->ordered_delivery_time;
        $data['area_id'] = $request->pathao_area_id ? $request->pathao_area_id : $request->redx_area_id;
        $data['area_name']  = $request->area_name;
        $data['city']  = $request->city;
        $data['state']  = $request->state;
        $data['store_id']  = $request->store_id;
        $data['weight']  = $request->weight;
        $data['courier_id']  = $request->courier_id;
        $data['courier_tracking_id']  = $request->courier_tracking_id;
        $data['total_amount']  = $request->total_amount;
        $data['advance_amount'] = $request->advance_amount;
        $data['product_qty']  = $request->product_qty;
        $data['note'] = $request->note;

        $shipping_data = Shipping::find($request->shipping_charge_id);

        $data['shipping_method']  = $shipping_data->shipping_rule;
        $data['shipping_cost']  = $shipping_data->shipping_fee;
       
        $order->update($data);
        
        if(isset($request->order_product_id)) {
            $delete_line=OrderProduct::where('order_id', $id)
                                ->whereNotIn('id', $request->order_product_id)
                                ->get();
                                
            if($delete_line->count()) {
                foreach($delete_line as $line)  {
                    if($line->variation == null) {
                        $variation_size = 'free';
                    } else {
                        $variation_size = $line->variation;
                    }
                    if($line->variation_color_id == null) {
                        $variation_color = 'default';
                    } else {
                        $variation_color = $line->variation_color_id;
                    }
                    
                    $size = Size::where('title', $variation_size)->first();
                    $color = Color::where('name', $variation_color)->first();
                    
                    $size_id = $size->id;
                    $color_id = $color->id;
                    
                    $product_id = $line->product_id;
                    $quantity = $line->qty;
                    
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    
                    $ultimate_stock = $stockCheck->quantity + $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
                    
                    $line->delete();   
                }
            } 
        } else {
               
                foreach ($order->orderProducts as $key => $line) {
                    
                    $size = Size::where('title', $line->variation)->first();
                    $color = Color::where('name', $line->variation_color_id)->first();
                    
                    $size_id = $size->id;
                    $color_id = $color->id;
                    $product_id = $line->product_id;
                    $quantity = $line->qty;
                    
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    
                    $ultimate_stock = $stockCheck->quantity + $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
                    
                    $line->delete();  
                }
            }
        
       

        if (isset($request->product_id)) {
            foreach ($request->product_id as $key => $product_id) {
               
               if(isset($request->order_product_id[$key])) {
                $ord_pro = OrderProduct::where('id', $request->order_product_id[$key])->first();
                $data2 = [];
                $data2['qty'] = $request->quantity[$key];
                $data2['unit_price'] = $request->unit_price[$key];
                $data2['variation'] = $request->variation[$key];
                if (isset($request->variation_color_id[$key]) && ($request->variation_color_id[$key] !== null) && ($request->variation_color_id[$key] !== '')) {
                $data2['variation_color_id'] = $request->variation_color_id[$key];
                } else {
                    $data2['variation_color_id'] = 'Default';
                }
                
                $data2['total_discount'] = (int)$request->quantity[$key] * (int)$request->discount_price[$key];
                
                $ord_qty = (int)$ord_pro->qty;
                $pro_qty = (int)$request->quantity[$key];
               
                if($ord_qty < $pro_qty) {
                   
                    if (isset($request->color_id[$key]) && ($request->color_id[$key] !== null) && ($request->color_id[$key] !== '')) {
                        $color_id = $request->color_id[$key];
                    } else {
                        $color_id = 1;
                    }
                    
                    if (isset($request->variation_data[$key]) && ($request->variation_data[$key] !== null) && ($request->variation_data[$key] !== '')) {
                        $size_id = $request->variation_data[$key];
                    } else {
                        $size_id = 1;
                    }
                    
                    $product_id = $request->product_id[$key];
                    $quantity = $request->quantity[$key];
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    
                    $quantity = $pro_qty - $ord_qty;
                    $ultimate_stock = $stockCheck->quantity - $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
                    
                } else if($ord_qty > $pro_qty) {
                 
                    $color_id = $request->color_id[$key];
                    $size_id = $request->variation_data[$key];
                    $product_id = $request->product_id[$key];
                    $quantity = $request->quantity[$key];
                    
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    $quantity = $ord_qty - $pro_qty;
                   
                    $ultimate_stock = $stockCheck->quantity + $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
                    
                   
                } 
                 $ord_pro->update($data2);
               } else {
                   
                    if (isset($request->variation_color_id[$key]) && ($request->variation_color_id[$key] !== null) && ($request->variation_color_id[$key] !== '')) {
                        $variation_color_id = $request->variation_color_id[$key];
                    } else {
                        $variation_color_id = 'Default';
                    }
                    
                    if (isset($request->variation[$key]) && ($request->variation[$key] !== null) && ($request->variation[$key] !== '')) {
                        $variation_id = $request->variation[$key];
                    } else {
                        $variation_id = 'free';
                    }
                    
                    $offerPrice=$request->offer_price[$key];
                    if($offerPrice!=='0'){
                        $price=$offerPrice;
                    } else {
                        $price=$request->unit_price[$key];
                    }
                   
                    OrderProduct::create([
                        'order_id' => $order->id,
                        'product_id' => $request->product_id[$key],
                        'variation_color_id' => $variation_color_id,
                        'variation' => $variation_id,
                        'product_name' => $request->product_name[$key],
                        'unit_price' => $price,
                        'total_discount' => isset($request->discount_price[$key]) ?  (int) $request->quantity[$key] * (int) $request->discount_price[$key] : '',
                        'qty' => $request->quantity[$key]
                    ]);
                    
                    $product_id = $request->product_id[$key];
                    $quantity = $request->quantity[$key];
                   
                    // $color_id = isset($request->color_id[$key]) ? $request->color_id[$key] : '1';
                    if (isset($request->color_id[$key]) && ($request->color_id[$key] !== null) && ($request->color_id[$key] !== '')) {
                        $color_id = $request->color_id[$key];
                    } else {
                        $color_id = '1';
                    }
                    
                    if (isset($request->variation_data[$key]) && ($request->variation_data[$key] !== null) && ($request->variation_data[$key] !== '')) {
                        $size_id = $request->variation_data[$key];
                    } else {
                        $size_id = '1';
                    }
                    // dd($size_id);
                    // $size_id = isset($request->variation_data[$key]) ? $request->variation_data[$key] : '1';
                    $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                    
                    $ultimate_stock = $stockCheck->quantity - $quantity;
                    $stockCheck->update([
                        'quantity'=> $ultimate_stock,
                    ]);
               }
            }
        } 
        
        
        
        if($request->order_status == '6' || $request->order_status == '4') {
            
            if (isset($request->order_id)) {
                foreach ($request->order_id as $key => $product_id) {
                    
                
                if (isset($request->color_id[$key]) && ($request->color_id[$key] !== null) && ($request->color_id[$key] !== '')) {
                        $color_id = $request->color_id[$key];
                    } else {
                        $color_id = 1;
                    }
                    
                    if (isset($request->variation_data[$key]) && ($request->variation_data[$key] !== null) && ($request->variation_data[$key] !== '')) {
                        $size_id = $request->variation_data[$key];
                    } else {
                        $size_id = 1;
                    }  
                    
                $product_id = $request->product_id[$key];
               
                $quantity = $request->quantity[$key];
                
                $stockCheck = ProductStock::where('color_id', $color_id)->where('size_id', $size_id)->where('product_id', $product_id)->first();
                
                $ultimate_stock = $stockCheck->quantity + $quantity;
                $stockCheck->update([
                    'quantity'=> $ultimate_stock,
                ]);
                }
            }
        } else {
            
        }

        $order_address = OrderAddress::where('order_id', $id)->first();
        $order_address_data = [];
        $order_address_data['shipping_name'] = $request->short_name;
      	$order_address_data['shipping_phone'] = $request->shipping_phone;
        $order_address_data['shipping_address'] = $request->shipping_address;
        $order_address->update($order_address_data);

        $notification = trans('admin_validation.Order Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.all-order')->with($notification);

    }
    
    public function updateOrderStatus(Request $request , $id){
      
        if(!auth()->user()->can('admin.update-order-status')){
            abort(403, 'Unauthorized action.');
        }
      
        $rules = [
            'order_status' => 'required',
            'payment_status' => 'required',
        ];
        $this->validate($request, $rules);

        $order = Order::find($id);
        if($request->order_status == 0){
            $order->order_status = 0;
            $order->save();
        }else if($request->order_status == 1){
            $order->order_status = 1;
            $order->order_approval_date = date('Y-m-d');
            $order->save();
        }else if($request->order_status == 2){
            $order->order_status = 2;
            $order->order_delivered_date = date('Y-m-d');
            $order->save();
        }else if($request->order_status == 3){
            $order->order_status = 3;
            $order->order_completed_date = date('Y-m-d');
            $order->save();
        }else if($request->order_status == 4){
            $order->order_status = 4;
            $order->order_declined_date = date('Y-m-d');
            $order->save();
        }

        if($request->payment_status == 0){
            $order->payment_status = 0;
            $order->save();
        }elseif($request->payment_status == 1){
            $order->payment_status = 1;
            $order->payment_approval_date = date('Y-m-d');
            $order->save();
        }

        $notification = trans('admin_validation.Order Status Updated successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->back()->with($notification);
    }


    public function destroy($id){
      	if(!auth()->user()->can('admin.delete-order')){
            abort(403, 'Unauthorized action.');
        }
      
        $order = Order::find($id);
        $order->delete();
        $orderProducts = OrderProduct::where('order_id',$id)->get();
        $orderAddress = OrderAddress::where('order_id',$id)->first();
        foreach($orderProducts as $orderProduct){
            
            $size_detail = Size::where('title', $orderProduct->variation)->first();
            $size_id = $size_detail->id;
            $color_detail = Color::where('name', $orderProduct->variation_color_id)->first();
            $color_id = $color_detail->id;
            $stockCheck = ProductStock::where('color_id', $color_id)
                          ->where('size_id', $size_id)->where('product_id', $orderProduct->product_id)->first();
                  
            $quantity = $orderProduct->qty;
           
            $ultimate_stock = $stockCheck->quantity + $quantity;
            $stockCheck->update([
                'quantity'=> $ultimate_stock,
            ]);
            
            
            OrderProductVariant::where('order_product_id',$orderProduct->id)->delete();
            $orderProduct->delete();
        }
        OrderAddress::where('order_id',$id)->delete();

        $notification = trans('admin_validation.Delete successfully');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.all-order')->with($notification);
    }

 //Get Courier Status
  public function getPathaoCourierStatus($sell)
  {
       $response = Http::withHeaders([
            'Authorization' => $this->pathao_api_access_token,
       ])->get($this->pathao_api_base_url.'orders/'.$sell->consignment_id.'/info');

       $status_code = $response->json()['code'];
       $result = [];
       if($status_code == 200)
       {
           $result = $response->json()['data']['order_status'];
       }
       else
       {
           $result = $response->json()['message'];
       }

       return ['status'=>$status_code, 'result'=> $result];
  }
  
  public function getRedxCourierStatus($sell)
  {
       $response = Http::withHeaders([
            'API-ACCESS-TOKEN' => $this->redx_api_access_token,
       ])->get($this->redx_api_base_url."parcel/info/{$sell->consignment_id}"); 
       $result = $response->json()['parcel']['status'];

       return ['result'=> $result];
  }
  
  function updateCourierStatus()
  {
        foreach(request('order_ids') as $id)
        {
           $item=Order::with('user', 'orderAddress')->find($id);
           if($item->courier_id == NULL || $item->courier_id !== 3)
           {
                return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'This order only for Steadfast Courier']);

           }
           else if($item->courier_tracking_code == NULL)
           {
              return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'Steadfast Courier Tracking Code Not Found!']);

           }
           else
           {
               
               $response = Http::withHeaders([
                    'Api-Key' => $this->steadfast_api_key,
                    'Secret-Key' => $this->steadfast_secret_key,
                    'Content-Type' => 'application/json'
                ])->get($this->steadfast_api_base_url.'status_by_trackingcode/'.$item->courier_tracking_code);
        
              $status =  $response->json();
              if($status && $status['status'] == 200  && $status['status']['delivery_status'] )
              {
                $delivery_status = $status['status']['delivery_status'];
                $item->courier_status = $delivery_status;
                if(!$item->save())
                {
                     return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'Something went wrong!']);
                }
              }
              else
              {
                return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'Something went wrong!']);
              }

           }

        }
        
        return response()->json(['status'=>true ,'msg'=>'Courier Status Updated Successfully!!']);
  }
  
  public function getSteadFastCourierStatus($sell)
  {
      $headers = array(
                'Secret-Key:'.$this->secret_key,
                'Api-Key:'.$this->app_key,
                'Content-Type: application/json',
            );
            
    
            $url = $this->api_base_url.'status_by_cid/'.$sell->consignment_id;
            $ch = curl_init($url);
            
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            
            return $result;
  }
  
   //Redx Courier Service
  function OrderSendToRedx()
  {
      $data = "";
        foreach(request('order_ids') as $id)
        {
           $item=Order::with('user', 'orderAddress')->find($id);
           if($item->courier_id == 1 && $item->courier_tracking_id == NULL)
           {
              $status = $this->createRedxParcel($item);
              //$data = $status['tracking_id'];
              if(!empty($status['tracking_id']))
              {
                $item->courier_tracking_id = $status['tracking_id'];
                $item->save();
                $response = $this->getRedxCourierStatus($item);
                $item->courier_status = $response['result'];
                $item->save();
              }
             else if(!empty($status['message']))
             {
               return response()->json(['status'=>false ,'msg'=>'Invoice: '.$item->order_id.' '.$status['message']]);
             }
           }

        }
        return response()->json(['status'=>true ,'msg'=>'Order Send to Redx Successfully!!']);
        //return response()->json(['status'=>true ,'msg'=>$data]);
  }

  function getRedxAreaList($by = null, $value = null)
  {
      try{
          $response = Http::withHeaders([
            'API-ACCESS-TOKEN' => $this->redx_api_access_token,
       ])->get($this->redx_api_base_url.'areas');

       $areas = $response->json()['areas'];
       return $areas;
      }catch(\Exception $e){
          return null;
      } 
  }
  function createRedxParcel($item)
  {
      //$area = $this->getRedxAreaList('post_code', $item->zip_code)[0];

      $response = Http::withHeaders([
            'API-ACCESS-TOKEN' => $this->redx_api_access_token,
            'Content-Type' => 'application/json'
        ])->post($this->redx_api_base_url.'parcel', [
            "customer_name" 		 => $item->orderAddress->shipping_name ?? "Unknown User",
            "customer_phone"         => $item->orderAddress->shipping_phone ?? "01700000000",
            "delivery_area"          => $item->area_name, // delivery area name
            "delivery_area_id"       => $item->area_id, // area id
            "customer_address"       => $item->orderAddress->shipping_address,
            "merchant_invoice_id"    => $item->order_id,
            "cash_collection_amount" => $item->total_amount,
            "parcel_weight"          => "500", //parcel weight in gram
            "instruction"            => "",
            "value"                  => $item->total_amount, //compensation amount
            "pickup_store_id"        => 0, // store id
            "parcel_details_json"    => []
        ]);

    return $response->json();
  }

  //Pathao Courier Service
  function getPathaoStoreList()
  {
      $response = Http::withHeaders([
            'Authorization' => $this->pathao_api_access_token,
       ])->get($this->pathao_api_base_url.'stores');

       $stores = $response->json()['data']['data'];
       return $stores;
  }

  function getPathaoCityList()
  {
      try{
          $response = Http::withHeaders([
            'Authorization' => $this->pathao_api_access_token,
           ])->get($this->pathao_api_base_url.'countries/1/city-list');
    
           $cities = $response->json()['data']['data'];
           return $cities;
      }catch(\Exception $e){
          return null;
      }
  }

  function getPathaoZoneListByCity($city)
  {
      $response = Http::withHeaders([
            'Authorization' => $this->pathao_api_access_token,
       ])->get($this->pathao_api_base_url.'cities/'.$city.'/zone-list');

       $zones = $response->json()['data']['data'];

       return response()->json(['success'=>true, 'zones'=> $zones]);
  }

  function getPathaoAreaListByZone($zone)
  {
      $response = Http::withHeaders([
            'Authorization' => $this->pathao_api_access_token,
       ])->get($this->pathao_api_base_url.'zones/'.$zone.'/area-list');

       $areas = $response->json()['data']['data'];

       return response()->json(['success'=>true, 'areas'=> $areas]);
  }

  function OrderSendToPathao()
  {
        foreach(request('order_ids') as $id)
        {
           $item=Order::with('user', 'orderAddress')->find($id);
           
           if($item->courier_id == 2 && $item->courier_tracking_id == NULL)
           {
              $status = $this->createPathaoParcel($item);
              if(!empty($status['data']['consignment_id']))
              {
                $item->courier_tracking_id = $status['data']['consignment_id'];
                $item->courier_status = $status['data']['order_status'];
                $item->save();
              }
             else if(!empty($status['errors']))
             {
               return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>$status['errors']]);
             }

           }

        } 
        return response()->json(['status'=>true ,'msg'=>'Order Send to Pathao Successfully!!']);
        //return response()->json(['status'=>true ,'msg'=>$data]);
  }

  function createPathaoParcel($item)
  {

      $response = Http::withHeaders([
            'Authorization' => $this->pathao_api_access_token,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ])->post($this->pathao_api_base_url.'orders', [
        	"store_id" 		 		 		=> $this->pathao_store_id,
            "merchant_order_id"      		=> $item->order_id,
            "sender_name"          			=> auth()->user()->name ?? 'Admin',
            "sender_phone"       			=> auth()->user()->phone ?? '01700000000',
            "recipient_name"       			=> $item->orderAddress->shipping_name ?? "Unknown User",
            "recipient_phone"    			=> $item->orderAddress->shipping_phone ?? "01700000000",
            "recipient_address" 			=> $item->orderAddress->shipping_address,
            "recipient_city"          		=> $item->city,
            "recipient_zone"                => $item->state,
            "recipient_area"                => $item->area_id,
            "delivery_type"        			=> 48,
            "item_type"    					=> 2,
            "special_instruction"    		=> "",
            "item_quantity"    				=> 1,
            "item_weight"    				=> $item->weight,
            "amount_to_collect"    			=> (int) $item->total_amount,
            "item_description"    			=> $item->additional_info,

        ]);
    return $response->json();
  }

  //Steadfast Courier Service
  function OrderSendToSteadfast()
  {
        foreach(request('order_ids') as $id)
        {
           $item=Order::with('user', 'orderAddress')->find($id);
           if($item->courier_id == NULL || $item->courier_id !== 3)
           {
                return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'This order only for Steadfast Courier']);

           }
           else if($item->courier_tracking_id != NULL)
           {
              return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'This order already send to Steadfast Courier']);

           }
           else if($item->courier_id == 3 && $item->courier_tracking_id == NULL)
           {
               
              $status = $this->createSteadfastParcel($item);
            
              if(!empty($status['consignment']['consignment_id']) && !empty($status['consignment']['tracking_code']))
              {
                $item->courier_tracking_id   = $status['consignment']['consignment_id'];
                $item->courier_tracking_code = $status['consignment']['tracking_code'];
                $item->courier_status = $status['consignment']['status'];
                $item->save();
              }
              else
              {
                return response()->json(['status'=>false ,'invoice'=>$item->order_id, 'errors'=>'Something went wrong!']);
              }

           }


        }
        return response()->json(['status'=>true ,'msg'=>'Order Send to Steadfast Successfully!!']);
        //return response()->json(['status'=>true ,'msg'=>$data]);
  }


  function createSteadfastParcel($item)
  {
      $response = Http::withHeaders([
            'Api-Key' => $this->steadfast_api_key,
            'Secret-Key' => $this->steadfast_secret_key,
            'Content-Type' => 'application/json'
        ])->post($this->steadfast_api_base_url.'create_order', [
            "invoice"      		=> $item->order_id,
            "recipient_name"    => $item->orderAddress->billing_name ?? $item->orderAddress->shipping_name,
            "recipient_phone"   => $item->orderAddress->billing_phone ?? $item->orderAddress->shipping_phone,
            "recipient_address" => $item->orderAddress->billing_address ?? $item->orderAddress->shipping_address,
            "cod_amount"    	=> (int) $item->total_amount,
            "note"    			=> $item->note,
        ]);

    return $response->json();
  }

  public function viewAccessToken()
  {
   return view('admin.generate-pathao-access-token');
  }

  public function generatePathaoAccessToken(Request $request)
  {
    	$response = Http::withHeaders([
            'content-type' => 'application/json',
            'accept' => 'application/json',
        ])->post($this->pathao_api_base_url.'issue-token', [
        	"client_id" 		 		=> $request->client_id,
            "client_secret"      		=> $request->client_secret,
            "username"          		=> $request->client_email,
            "password"       			=> $request->client_password,
            "grant_type"       			=>  "password"

        ]);

    dd($response->json());
  }
  
  
  public function calculateCartTotal(
        $user,
        $request_coupon,
        $request_shipping_method_id
    )

    {
        $total_price = 0;
        $coupon_price = 0;
        $shipping_fee = 0;
        $productWeight = 0;

        $cart = session()->get('cart', []);

        if (count($cart) == 0) {
            $notification = trans("Your shopping cart is empty");

            return response()->json(["status"=>false, "msg" => $notification]);
        }

        foreach ($cart as $index => $cartProduct) {
            $variantPrice = 0;

            if (!empty($cartProduct['variation'])) {
                    $item = ProductVariant::find(
                        $cartProduct['variation']
                    );

                    if ($item) {
                        $variantPrice = $item->sell_price;
                    }
                }

            $product = Product::select(
                "id",
                "price",
                "offer_price",
                "weight"
            )->find($index);

            $price = $product->offer_price
                ? $product->offer_price
                : $product->price;

            $price = $variantPrice > 0 ? $variantPrice : $price;
            $weight = $product->weight;
            $weight = $weight * $cartProduct['quantity'];
            $productWeight += $weight;
            $isFlashSale = FlashSaleProduct::where([
                "product_id" => $product->id,
                "status" => 1,
            ])->first();

            $today = date("Y-m-d H:i:s");

            if ($isFlashSale) {
                $flashSale = FlashSale::first();
                if ($flashSale->status == 1) {
                    if ($today <= $flashSale->end_time) {
                        $offerPrice = ($flashSale->offer / 100) * $price;

                        $price = $price - $offerPrice;
                    }
                }
            }

            $price = $price * $cartProduct['quantity'];
            $total_price += $price;
        }

        // calculate coupon coast

        if ($request_coupon) {
            $coupon = Coupon::where([
                "code" => $request_coupon,
                "status" => 1,
            ])->first();


            if ($coupon) {
                if ($coupon->expired_date >= date("Y-m-d")) {
                    if ($coupon->apply_qty < $coupon->max_quantity) {
                        if ($coupon->offer_type == 1) {
                            $couponAmount = $coupon->discount;

                            $couponAmount =
                                ($couponAmount / 100) * $total_price;
                        } elseif ($coupon->offer_type == 2) {
                            $couponAmount = $coupon->discount;
                        }

                        $coupon_price = $couponAmount;
                        $qty = $coupon->apply_qty;
                        $qty = $qty + 1;
                        $coupon->apply_qty = $qty;
                        $coupon->save();
                    }
                }
            }

        }

        $shipping = Shipping::find($request_shipping_method_id);

        if (!$shipping) {
            return response()->json(
                ["message" => trans("Shipping method not found")],
                403
            );
        }

        if ($shipping->shipping_fee == 0) {
            $shipping_fee = 0;
        } else {
            $shipping_fee = $shipping->shipping_fee;
        }

        $total_price = $total_price - $coupon_price + $shipping_fee;

        $total_price = str_replace(
            ['\'', '"', ",", ";", "<", ">"],
            "",
            $total_price
        );

        $total_price = number_format($total_price, 2, ".", "");

        $arr = [];
        $arr["total_price"] = $total_price;
        $arr["coupon_price"] = $coupon_price;
        $arr["shipping_fee"] = $shipping_fee;
        $arr["shipping"] = $shipping;
        return $arr;
}
}
