<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Auth;
use App\User;

class DiscountController extends Controller
{
    public function create_price_rule(Request $request) {
        
        $shop_name = $request->shop;
        $pid = $request->pid;
        $value = $request->value;
        $user_detail = DB::table('users')->where('name', $shop_name)->first();
        
        if(!Auth::check()) { if(!Auth::loginUsingId($user_detail->id)) { return false; } } 
        
        $shop = Auth::user();
        try {

            
            # Delete All Price Rules if want uncomment
            // return $this->delete_price_rule();
            

            if($request->is_create) {

                $discountinfo = User::where('name', $shop_name)->with('discountshop')->with('DiscountShopProduct')->first();
                if($discountinfo->discountshop->applied_on_all == 'yes') {                
                    $products = $shop->api()->rest('GET', "/admin/api/2020-04/products.json");
                    $productlistid = '';
                    if($products) { 
                        $products = $products['body']['products'];
                        foreach($products as $p) {
                            if($productlistid == '') {
                                $productlistid .= $p['id'];
                            } else {
                                $productlistid .= ','.$p['id'];
                            }
                        }
                    }
                } else {
                    $productlistid = '';
                    $productids = $discountinfo->DiscountShopProduct;
                    foreach($productids as $p) {
                        if($productlistid == '') {
                            $productlistid .= $p['product_id'];
                        } else {
                            $productlistid .= ','.$p['product_id'];
                        }
                    }
                }

                $applied_on_products = explode(',', $productlistid);
                $productlistid = explode(',', $productlistid);

                $uniqid = uniqid();
                $title = "DISCOUNTY".$uniqid;
                $data = array(
                    "price_rule" => array(                    
                        "title" => $title,
                        "target_type" => "line_item",
                        "target_selection" => "entitled",
                        "allocation_method" => "each",
                        "value_type" => "percentage",
                        "value" => "-$value",
                        "customer_selection" => "all",
                        
                        "starts_at" => date('Y-m-d',time())."T".date('H:i:s', time())."Z",
                        'entitled_product_ids' => $productlistid,
                    ),
                );
                $price_rule = $shop->api()->rest('POST', "/admin/api/2021-04/price_rules.json", $data);
                $price_rule['body']['price_rule']['id'];
                if($price_rule['body']['price_rule']['id']) {
                    $price_rule_id = $price_rule['body']['price_rule']['id'];
                    $price_discount_code = $price_rule['body']['price_rule']['title'];
                    $discount_code = $this->create_discount_code($price_rule_id, $price_discount_code);
                    return response()->json([
                        'status' => true,
                        'price_rule' => $price_rule['body']['price_rule'],
                        'discount_code' => $discount_code,
                        'price_rule_id' => $price_rule_id,
                        'discountify_code' => $title,
                        'applied_on_products' => $applied_on_products,
                    ]);
                }

            } else if($request->is_update) {
                return $price_rule = $this->update_price_rule($request->price_rule_id, $value);

            }
            
            return response()->json([
                'status' => false
            ]);
        } catch (Exception $e) { return response()->json([ 'status' => false ]); }

    }

    public function create_discount_code($price_rule_id, $price_discount_code) {
        $shop = Auth::user();
        try {
            $uniqid = uniqid();
            $data = array(
                "discount_code" => array(
                    "code"=> $price_discount_code,
                )
            );
            $discount_code = $shop->api()->rest('POST', "/admin/api/2021-07/price_rules/$price_rule_id/discount_codes.json", $data);
            if($discount_code['body']['discount_code']['id']) {
                return $discount_code['body']['discount_code'];
            }

            return false;
        } catch (Exception $e) { return false; }
    }

    public function delete_price_rule() {
        
        $shop = Auth::user();
        $price_rule = $shop->api()->rest('GET', "/admin/api/2021-04/price_rules.json");
        if($price_rule['body']['price_rules']) {
            $price_rule = $price_rule['body']['price_rules'];
            foreach($price_rule as $p) {
                $shop->api()->rest('DELETE', "/admin/api/2021-07/price_rules/$price_rule_id.json");
            }
        }
            
    }

    public function update_price_rule($price_rule_id ,$value) {
        $shop = Auth::user();
        $data = array(
            "price_rule" => array(
                'id' => $price_rule_id,
                "value" => "-$value",
            ),
        );
        if($value) {
            $price_rule = $shop->api()->rest('PUT', "/admin/api/2021-04/price_rules/$price_rule_id.json", $data);
            return $price_rule;
        } else {
            $shop->api()->rest('DELETE', "/admin/api/2021-07/price_rules/$price_rule_id.json");
        }
    }
}
