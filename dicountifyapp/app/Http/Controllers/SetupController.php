<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\User;
use App\DiscountShop;
use App\DiscountShopProduct;
use Redirect;
use DB;
use Session;

class SetupController extends Controller
{
    
    # Contruct Method
    public function __construct() {
        $this->middleware(['verify.shopify'])->except(['get_discount_details', 'delete_script_tags', 'product_discount_page_content']);
    }

    # Function At Installation Time
    public function installation_time(Request $request) {
        $shop = Auth::user();
        # Begin::Create Product Discount Page
        $discountshop = DiscountShop::where('shop_id', Auth::User()->id)->first();
        if(!$discountshop) {
            $htmlpage = view('product_discount_page');
            $data = array(
                "page" => array(
                    "title" => "Product's Discount",
                    "body_html" => "$htmlpage"
                )
            );
            $page = $shop->api()->rest('POST', '/admin/api/2021-04/pages.json', $data);
        }
        # End::Create Product Discount Page
    }

    public function index(Request $request) {
        $discount_shop = DiscountShop::where('shop_id', Auth::id())->first();
        if($discount_shop) {
            return Redirect::tokenRedirect('setting');
        }

        return view('welcome');
    }

    # Start Setup Page
    public function start_setup(Request $request) {
        return view('start-setup');
    }

    # Setting Page
    public function setting(Request $request) {
        $savedproductscount = DiscountShopProduct::where('shop_id', Auth::id())->count();
        
        $discount_shop = DiscountShop::where('shop_id', Auth::id())->first();
        
        if(!$discount_shop) {        
            $this->installation_time($request);
            $is_saved = DiscountShop::updateOrCreate(
                [  'shop_id' => Auth::id() ],
                [
                    'max_discount_percentage' => $request->max_discount_percent,
                    'discount_step' => $request->desired_descount_step_size,
                    'countdown_duration' => $request->countdown_duration,
                    'widget_text' => '{discount} Discount Ends In:',
                    'countdown_ended_text' => 'You just missed the discount. Come back in {when} hours for next chance.',
                    'after_countdown_ends' => '1',
                    'discount_reactivation' => '',

                    'discount_reactivation_days' => '0',
                    'discount_reactivation_hours' => '0',
                    'discount_reactivation_minutes' => '0',
                    'discount_reactivation_second' => '0',
                    'total_seconds' => '0',

                    'is_active' => 'no',
                    'shop_id' => Auth::id(),
                    'applied_on_all' => 'yes',
                ]
            );

        }

        
        if($request->togglemessage == 'yes') {
            Session::flash('message', 'Discount Enabled Successfully!'); 
            Session::flash('alert-class', 'alert-success');
        } else if($request->togglemessage == 'no') {
            Session::flash('message', 'Discount Disabled Successfully!'); 
            Session::flash('alert-class', 'alert-success');
        }

        if($request->addmessage == 'yes') {
            Session::flash('message', 'Product Added Successfully!'); 
            Session::flash('alert-class', 'alert-success');

            DiscountShop::where('shop_id', Auth::id())->update([
                'applied_on_all' => 'no',
            ]);
        }

        if($request->save_setting == 'yes') {
            Session::flash('message', 'Setting Saved Successfully!'); 
            Session::flash('alert-class', 'alert-success');
        }

        $discount_shop = DiscountShop::where('shop_id', Auth::id())->first();

        return view('setting', compact('discount_shop', 'savedproductscount'));
    }

    # Save Setting Page
    public function save_setting(Request $request) {
        
        $applied_on_all = ($request->product_visibility == 'all') ? 'yes' : 'no';
        // $disactivation = $request->discount_reactivation;
        // $days = intval($disactivation[0].$disactivation[1]) * 24 * 60 * 60;
        // $hours = intval($disactivation[10].$disactivation[11]) * 60 * 60;
        // $mins = intval($disactivation[13].$disactivation[14]) * 60;
        // $second = intval($disactivation[16].$disactivation[17]);

        $days = intval($request->discount_reactivation_days) * 24 * 60 * 60;
        $hours = intval($request->discount_reactivation_hours) * 60 * 60;
        $mins = intval($request->discount_reactivation_minutes) * 60;
        $second = intval($request->discount_reactivation_seconds);
        $discount_reactivation =  $days + $hours + $mins + $second;


        $is_saved = DiscountShop::updateOrCreate(
            [  'shop_id' => Auth::id() ],
            [
                'max_discount_percentage' => $request->maximum_discount,
                'discount_step' => $request->step_discount_percentage,
                'countdown_duration' => $request->countdown_duration,
                'widget_text' => $request->widget_text,
                'countdown_ended_text' => $request->countdown_ended_text,
                'after_countdown_ends' => $request->after_countdown_ends,
                'discount_reactivation' => '',                      //$request->discount_reactivation,
                'discount_reactivation_days' => $request->discount_reactivation_days,
                'discount_reactivation_hours' => $request->discount_reactivation_hours,
                'discount_reactivation_minutes' => $request->discount_reactivation_minutes,
                'discount_reactivation_second' => $request->discount_reactivation_seconds,
                'total_seconds' => $discount_reactivation,
                'shop_id' => Auth::id(),
                'applied_on_all' => $applied_on_all,
            ]
        );

            // return Redirect::tokenRedirect('setting');
       return redirect('setting?save_setting=yes');
    }

    # Toggle Status 
    public function toogle_status(Request $request) {
        
        $is_update = DiscountShop::where('shop_id', Auth::id())->update([
            'is_active' => $request->status,
        ]);
        if($is_update) {
            return 'true';
        } else {
            return 'false';
        }
    }

    public function getproducts() {
        $shop = Auth::user();
        try {
            $products = $shop->api()->rest('GET', "/admin/api/2020-04/products.json");
            if($products) { 
                $products = $products['body']['products'];
                return $products;
            }
            return false;
        } catch (Exception $e) {
            return false;
        }
    }

    public function show_products(Request $request) {
        $products = $this->getproducts();
        if($products) {
            $savedproducts = DiscountShopProduct::where('shop_id', Auth::id())->select('product_id')->get();
            $sp = array();
            foreach($savedproducts as $s) {
                $sp[] = $s->product_id;
            } 
            $savedproducts = explode(',',implode(',', $sp));
            $html = '
                <div id="product_list_modal" class="modal fade">
                    <div class="modal-dialog  modal-lg " role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modal_title" style="font-weight: 600;">Add Products</h5>
                                <button type="button" class=" btn btn-sm btn-secondary close"  onclick="closeModal()" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <style>
                                    input[type="date"] {
                                        padding: 0px 1rem !important;
                                    }
                                    select {
                                        height: 30px !important;
                                        padding: 0px 1rem !important;
                                    }
                                </style>
                                <table class="table table-striped- table-bordered table-hover table-checkable">
                                    <tbody>
                                ';
                                $i = 1;
                                foreach($products as $p) {
                                    $is_checked = (in_array($p['id'], $savedproducts)) ? 'checked' : '';
                                    $image = ($p['image']) ? $p['image']['src'] : '';
                                    $html .= '
                                    <tr>
                                        <td style="width:20px;"><input type="checkbox" name="product_id[]" id="product_id'.$i++.'" '.$is_checked.' value="'.$p['id'].'" data-id="'.$p['id'].'" /></td>
                                        <td style="width:40px;padding:4px;"><embed src="'.$image.'" style="height:40px;width:40px;"></embed></td>
                                        <td>'.$p['title'].'</td>
                                    </tr>';
                                }

                $html .=    '   
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-secondary"  onclick="closeModal()">Close</button>
                                <button type="button" class="btn btn-sm btn-danger" id="addproducts" onclick="addproducts()">Update</button>
                            </div>
                        </div>
                        <!-- /.modal-content -->
                    </div>  
                </div>
            ';

            return $html;
        }

        return 'false';
    }

    public function update_product_list(Request $request) {
        $products = $request->products;

        DiscountShopProduct::where('shop_id', Auth::id())->delete();
        if($products) {
            foreach($products as $key=>$value) {
                DiscountShopProduct::create(
                    [
                        'shop_id' => Auth::id(),
                        'product_id' => $value,
                    ]
                );
            }
        }

        return 'true';
    }

    # GET Discount Details
    public function get_discount_details(Request $request) {
        $shop = $request->shop;
        $product = $request->product;
        $discount  = User::where('name', $shop)->with('discountshop');
        
        if($product) {
            $discount = $discount->with(['DiscountShopProduct' => function($q) use($product) {
                $q->where('product_id', '=', $product); 
            }]);
        } else {
            $discount = $discount->with('DiscountShopProduct');
        }
        
        $discount = $discount->first();

        return response()->json($discount);
    }
    
    # Product Discount Page Content
    public function product_discount_page_content(Request $request) {
        $shop_name = $request->shop;
        $user_detail = DB::table('users')->where('name', $shop_name)->first();

        // attempt to do the login
        if(!Auth::check()) {
            if(!Auth::loginUsingId($user_detail->id)) {
                return false;
            }
        }

        $shop = Auth::user();

        $products = $this->getproducts();

        $data = array();
        if($products) {
            $savedproducts = DiscountShopProduct::where('shop_id', Auth::id())->select('product_id')->get();
            $discountshop = DiscountShop::where('shop_id', Auth::id())->select('applied_on_all')->first();
            $sp = array();
            foreach($savedproducts as $s) {
                $sp[] = $s->product_id;
            }
            $savedproducts = explode(',',implode(',', $sp));
            $html = '';
            $i = 0;
            foreach($products as $p) {
                $is_checked = (in_array($p['id'], $savedproducts)) ? 'checked' : '';
                $image = ($p['image']) ? $p['image']['src'] : '';
                if($is_checked == 'checked' || $discountshop->applied_on_all == 'yes') {
                    $title = implode('-',explode(' ',$p['title']));

                    $data[$i++] = [
                        'image' => $image,
                        'title' => $title,
                        'action' => 'https://'.$shop_name.'/products/'.strtolower($title)
                    ];

                    $html .= '
                    <tr>
                        <td style="width:40px;padding:4px;text-align:center;"><embed src="'.$image.'" style="height:40px;width:40px;"></embed></td>
                        <td>'.$p['title'].'</td>
                        <td style="text-align:center;"><a href="https://'.$shop_name.'/products/'.strtolower($title).'" target="_blank" >view</a></td>
                    </tr>';   
                }
            }
            return $html;
        }
        return false;
    }

}
