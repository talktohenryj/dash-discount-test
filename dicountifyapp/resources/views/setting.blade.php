<!DOCTYPE html>
<html lang="en">
<head>
    <!-- meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>{{ env('APP_NAME') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous" />
    <!-- Custom CSS -->
    <link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" 
        integrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" 
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        
    <style>
        body {
            background-color: #f4f6f8;
            padding-bottom: 100px;
        }
        h3 {
            font-size: calc(1.325rem + 0.7vw);
            font-weight: bold;
        }
        .card-header {
            /* background-color: rgba(1,1,1,0.03); */
            background-color: white;

        }
        .card-body {
            background-color: white;
        }
        .card-title {
            font-size: 1.1rem;
        }
        .form-label {
            font-size: 0.9rem;
            margin-bottom: 0.2rem;
        }
        li {
            padding: 10px 0px;
        }

        .active-rotate {
            animation: rotate 1.4s ease infinite;
        }
        @keyframes rotate {
            0% {
                transform: rotate(-360deg);
            }
        }
    </style>
</head>
<body>
@extends('shopify-app::layouts.default')

@section('content')
    <div class="midContent" style="align-items: baseline;">
        <div class="container" id="settingcontainer">
            <div class="row">
                <div class="col-md-10 midHeader mt-3 mb-2" style="margin:auto;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <h3>Setting</h3>
                    </div>
                    <div>
                        <!-- <a href="{{ env('APP_URL').'/after-theme-update' }}" id="theme-update" onclick="showloader('theme-update');" title="Click after theme update" class="btn btn-sm btn-light border border-danger">
                            <i class="fas fa-sync-alt" style="color:#DC3545"></i>
                        </a> -->

                        <button class="btn btn-sm btn-danger" onclick="SetupInstruction();" type="button" style="width: 195px;">
                            Show Setup Instructions
                        </button>
                        <button class="btn btn-sm btn-danger" id="toggle-setting" onclick="showloader('toggle-setting');changestatus();" type="button" style="width: 150px;">@if($discount_shop->is_active == 'yes') Disable Discount @else Enable Discount @endif</button>
                    </div>
                </div>
            </div>

            @if(Session::has('message'))
                <div class="row" id="showmessage">
                    <div class="col-md-10" style="margin:auto;">
                        <div class="alert {{ Session::get('alert-class', 'alert-success') }} fade show" style="display:flex;justify-content: space-between;" role="alert">
                            <div>
                                {{ Session::get('message') }}
                            </div>
                            <div>
                                <span aria-hidden="true" id="closeAlert" style="cursor:pointer">&times;</span>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif(session('message'))<div class="row" id="showmessage">
                <div class="col-md-10" style="margin:auto;">
                        <div class="alert alert-success fade show" style="display:flex;justify-content: space-between;" role="alert">
                            <div>
                                {{ session('message') }}
                            </div>
                            <div>
                                <span aria-hidden="true" id="closeAlert" style="cursor:pointer">&times;</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        
            <div class="col-md-10 card text-dark bg-light mb-3" style="margin:auto;">
                <div class="card-body">
                    <form name="setup-form" id="setup-form" method="POST" action="{{ env('APP_URL').'/setting/save' }}">
                        @sessionToken
                        <div class="row">
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="widget_text" class="form-label">Widget Text</label>
                                    <input type="text"  class="form-control form-control-sm" id="widget_text" name="widget_text" aria-describedby="widget_text_help"
                                        value="{{ $discount_shop->widget_text }}" required onfocusout="changehtml()"
                                    />
                                    <p id="widget_text_help" class="form-text text-muted setupSelectHelpText text-secondary">Use the {discount} placeholder for display of current discount and {countdown} placeholder for remaining time of discount.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="row">
                                    <?php $product_visibility = $discount_shop->applied_on_all;  ?>                                                
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="product_visibility" class="form-label">Product Visibility</label>
                                            <select class="form-control form-select-sm" id="product_visibility" name="product_visibility" aria-describedby="widget_text_help">
                                                <option value="all" @if($product_visibility == 'yes') selected @endif >All</option>
                                                <option value="select_products" @if($product_visibility == 'no') selected @endif>Choose Products</option>

                                            </select>
                                            
                                            <p id="product_visibility_help" class="form-text text-muted setupSelectHelpText text-secondary">
                                                Choose if discount is visible for all products or just a selected few.
                                            </p>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">&nbsp;</label>
                                        <button id="add_products" class="form-control btn btn-sm btn-danger" type="button" style="@if($product_visibility == 'yes') display:none; @endif" onclick="show_products();">Add Products</button> 
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="maximum_discount" class="form-label">Maximum discount percentage</label>
                                    <input type="text"  class="form-control form-control-sm" id="maximum_discount" name="maximum_discount" 
                                        value="{{ $discount_shop->max_discount_percentage ?? '' }}" aria-describedby="maximum_discount_help"
                                        onkeypress="return isNumber('maximum_discount', event);" onfocusout="changehtml()" required
                                    />
                                    <p id="maximum_discount_help" class="form-text text-muted setupSelectHelpText text-secondary" >
                                        Set the maximum discount percentage that can be applied to all your products.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="step_discount_percentage" class="form-label">Discount percentage step size</label>
                                    <input type="text"  class="form-control form-control-sm" id="step_discount_percentage" name="step_discount_percentage" 
                                        value="{{ $discount_shop->discount_step ?? '' }}" aria-describedby="step_discount_percentage_help" required
                                        onkeypress="return isNumber('step_discount_percentage', event);" onfocusout="changehtml()"
                                    />
                                    <p id="step_discount_percentage_help" class="form-text text-muted setupSelectHelpText text-secondary">
                                        Set the size of discount decrease that happens each time countdown reaches 0.
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="countdown_duration" class="form-label">Countdown duration</label>
                                    <input type="text"  class="form-control form-control-sm" id="countdown_duration" name="countdown_duration" 
                                        value="{{ $discount_shop->countdown_duration ?? '' }}" aria-describedby="countdown_duration_help"
                                        onkeypress="return isNumber('countdown_duration', event);" onfocusout="changehtml()" required
                                    />
                                    <p id="countdown_duration_help" class="form-text text-muted setupSelectHelpText text-secondary">
                                        Set the duration of each countdown duration can be applied to all your products.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="spHighLightText">
                                    <h4>Currently set behaviour</h4>
                                    <h5 id="current_behaviour"></h5>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="discount_reactivation" class="form-label"><b>Discount Reactivation</b></label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="discount_reactivation_days" class="form-label">Days</label>
                                    <input type="text" placeholder="00"  class="form-control form-control-sm" 
                                        id="discount_reactivation_days" name="discount_reactivation_days" 
                                        onkeypress="return isNumber('discount_reactivation_days', event);"
                                        value="{{ $discount_shop->discount_reactivation_days ?? '' }}"
                                    />
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="discount_reactivation_hours" class="form-label">Hours</label>
                                    <input type="text" placeholder="00"  class="form-control form-control-sm" 
                                        id="discount_reactivation_hours" name="discount_reactivation_hours" 
                                        onkeypress="return isNumber('discount_reactivation_hours', event);"
                                        value="{{ $discount_shop->discount_reactivation_hours ?? '' }}"
                                    />
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="discount_reactivation_minutes" class="form-label">Minutes</label>
                                    <input type="text" placeholder="00"  class="form-control form-control-sm" 
                                        id="discount_reactivation_minutes" name="discount_reactivation_minutes" 
                                        onkeypress="return isNumber('discount_reactivation_minutes', event);"
                                        value="{{ $discount_shop->discount_reactivation_minutes ?? '' }}"
                                    />
                                </div>
                            </div>
                            
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="discount_reactivation_seconds" class="form-label">Seconds</label>
                                    <input type="text" placeholder="00"  class="form-control form-control-sm"
                                        id="discount_reactivation_seconds" name="discount_reactivation_seconds" 
                                        onkeypress="return isNumber('discount_reactivation_seconds', event);"
                                        value="{{ $discount_shop->discount_reactivation_second ?? '' }}"
                                    />
                                </div>
                            </div>
                        </div>
                        <br />
                        
                        <div class="row">                            
                            <div class="col-md-6">
                                <?php $after_countdown_ends = $discount_shop->after_countdown_ends;  ?>     
                                <div class="form-group">
                                    <label for="after_countdown_ends" class="form-label">After Countdown Ends</label>
                                    <select class="form-control form-control-sm" id="after_countdown_ends" name="after_countdown_ends">
                                        <option value="1"  @if($after_countdown_ends == '1') selected @endif>Show Text</option>
                                        <option value="0"  @if($after_countdown_ends == '0') selected @endif>Hide Text</option>
                                    </select>
                                    <p id="after_countdown_ends_help" class="form-text text-muted setupSelectHelpText text-secondary" >
                                        Choose from dropdown to that Expired message will show or not.
                                    </p>
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="countdown_ended_text" class="form-label">Countdown ended text</label>
                                    <input type="text"  class="form-control form-control-sm" id="countdown_ended_text" name="countdown_ended_text"
                                        value="{{ $discount_shop->countdown_ended_text }}" required
                                    />
                                    <p id="countdown_ended_text_help" class="form-text text-muted setupSelectHelpText text-secondary">
                                        This Message can be used to encourage users to return back to your site when next countdown will be available. Use the {when} placeholder for display of when next countdown will be available.
                                    </p>
                                </div>
                            </div>
                        </div>

                        
                        
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-sm btn-danger" style="width:80px;" id="save-setting" onclick="showloader('save-setting')">Save</Button>
                            </div>
                        </div>

                    </form>
                </div>


            </div>

            
            <div class="col-md-10 card text-dark bg-light mb-3" style="margin:auto;">
                <div class="card-body">
                    <div class="row">
                        <b>Live Preview</b>
                        <div class="col-md-5" style="text-align:center; padding: 50px;">
                            <embed src="{{ url('assets/images/product.webp') }}" style="width:100%;"></embed>
                        </div>
                        <div class="col-md-5" style="padding:50px;padding-left:0px;">
                            <style>
                                #live-price {
                                    font-size: 20px;
                                }
                                #live_discount_message {
                                    margin-top: 0px;
                                    margin-bottom:5px;
                                    padding: 5px 10px;
                                    border: 1px solid green;
                                    color:green;
                                }
                                #add_to_cart, #buy_it_now {
                                    width:100%;
                                    margin-top: 5px;
                                    margin-bottom: 5px;
                                }
                                #addtocart {
                                    padding: 10px;
                                    color: gray;
                                    background: white; 
                                    width: 100%;
                                    margin-bottom: 10px;
                                    border: 1px solid #445958;
                                }
                                #buyitnow {
                                    padding: 10px;
                                    color: white;
                                    background: gray;
                                    width: 100%;
                                    border: 1px solid #445958;
                                }
                            </style>
                            <h4 class="live-title">Roadster T-Shirt</h4>
                            <p class="live-price">$10.00</p>
                            <br>

                            <div id="live_discount_message" class="alert alert-success discount_message" role="alert" style="background-color:white;">
                                
                            </div>

                            <div id="add_to_cart">
                                <button id="addtocart" class="btn">Add to cart</button>
                            </div>
                            <div id="buy_it_now">
                                <button id="buyitnow" class="btn" style="background:#445958;">Buy it now</button>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="container" id="setupcontainer" style="display:none;">
            <div class="row">
                <div class="col-md-10 midHeader mt-3 mb-2" style="margin:auto;display:flex;align-items:center;justify-content:space-between;">
                    <div>
                        <h3>Setup Instruction</h3>
                    </div>
                    <div>
                        <button class="btn btn-sm btn-danger" onclick="showSetting();" type="button">
                            Back
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-md-10 card text-dark bg-light mb-3" style="margin:auto;">
                <div class="card-body">
                    <p>After Installing the app we need to follow the instructions to setup the app functionality.</p>
                    <p><b>Configure Settings:</b></p>
                    <ol>
                        <li>Can Enable/Disable the discount after configuring the app settings</li>
                        <li>
                            <b>Product Visibility:</b> 
                            Select all products or choose products from the dropdown. 
                            After that click the add product button to check the products that discount working on.
                        </li>
                        <li>Follow the order as metion in the app.
                            <ol>
                                <li>
                                    <b>Widget Text:</b> 
                                    Modify the text to show the discount message as you want.
                                </li>
                                <li>
                                    <b>Maximum Discount Message:</b>
                                    Set the maximum discount percentage that can be applied to all selected products.
                                </li>
                                <li>
                                    <b>Discount percentage step size:</b> 
                                    Set the size of discount decrease that happens each time countdown reaches 0.
                                </li>
                                <li>
                                    <b>Countdown duration:</b> 
                                    Set the duration of each countdown duration can be applied to all your products.
                                </li>
                                <li>
                                    <b>Discount Reactivation:</b>
                                    Set the time period for when the expired discount should reappear to the user.
                                </li>
                                <li>
                                    <b>After Countdown Ends:</b>
                                    Choose from dropdown to that Expired message will show or not.
                                </li>
                                <li>
                                    <b>Countdown ended text:</b>
                                    This Message can be used to encourage users to return back to your site when next countdown will be available. 
                                </li>
                            </ol>
                        </li>
                    </ol>

                    <div style="width:100%;text-align:center;"><img src="{{ url('assets\images\img2.png') }}" style="width:60%;margin:auto;"/></div>
                    
                    <p><b>The app blocks of our app:</b></p>
                    <ul>
                        <li><b>Discount Timer:</b> That is shown under the navigation header of the shop for all pages and above 
                    the add to cart button on the product page.</li>
                        <li><b>Discount Message:</b> The discount applied message above the checkout button which shows the discount code 
                    with the discount percent and deduct amount.</li>
                    </ul>
                    
                    <p><b>Can activate/deactivate app embed blocks by enable/disable the app</b></p>
                    <p><b>Can Configure functional settings for app blocks from setting page. </b></p>

                    
                    <p><b>Visibility:</b></p>
                    <ol>
                        <li>Behaviour of the discount block will be displayed on the header on all pages and on the Product detail page 
                            whatever settings we choose from our settings for discount.</li>
                    </ol>

                    <div style="width:100%;text-align:center;"><img src="{{ url('assets\images\img1.png') }}" style="width:80%;margin:auto;"/></div>

                </div>
            </div>

        </div>
    </div>

    
    <div id="add_products_modal"></div>

@endsection

@section('scripts')
    @parent

    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    
    <script type="text/javascript">
        actions.TitleBar.create(app, { title: 'Setting' });
        var loaderhtml = '<i class="fas fa-sync-alt active-rotate" style="color:white"></i>';
        function showloader(id) {
            if(id == 'theme-update') {
                var loader = '<i class="fas fa-sync-alt active-rotate" style="color:#dc3545"></i>';
            } else {            
                var loader = '<i class="fas fa-sync-alt active-rotate" style="color:white"></i>';
            }
            $(`#${id}`).html(loader);
        }

        function SetupInstruction() {
            $('#settingcontainer').hide();
            $('#setupcontainer').show();
        }

        function showSetting() {
            $('#setupcontainer').hide();
            $('#settingcontainer').show();
        }

        $('#closeAlert').on('click', function() {
            $('#showmessage').hide();
        });

        function changehtml() {
            var max_discount_percent = $('#maximum_discount').val();
            var desired_descount_step_size = $('#step_discount_percentage').val();
            var countdown_duration = $('#countdown_duration').val();
            var widget_text = $('#widget_text').val();


            var t = parseInt($('#countdown_duration').val()) * 1000;
            var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
            var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((t % (1000 * 60)) / 1000);
            var showtimer = '';
            var showhours, showminutes, showseconds = '';
            if(hours > 0) { if(hours == '01' || hours == '1') { showhours += `${hours} Hour `; } else {  showhours += `${hours} Hours `; } }
            if(minutes > 0) { if(minutes == '01' || minutes == '1') { showminutes += `${minutes} Minute `; } else {  showminutes += `${minutes} Minutes `; } }
            if(seconds > 0) { if(seconds == '01' || seconds == '1') { showseconds += `${seconds} Second `; } else {  showseconds += `${seconds} Seconds `; } }

            if(!hours) { hours = false }
            if(!minutes) { minutes = false; }
            if(!seconds) { seconds = false }

            showtimer = `<div style="display:flex;text-align:center;">
                <div>&nbsp;&nbsp;&nbsp;</div>`;

            if(hours) {
            showtimer += `
                <div>
                    <span>${hours} HRS</span>
                </div>&nbsp;`;
            }
                
                
            if(minutes) {
            showtimer += `
                <div>
                    <span>${minutes} MINS</span>
                </div>&nbsp;`;
            }
                
            if(seconds) {
                showtimer += `
                <div>
                    <span>${seconds} SECS</span>
                </div>`;
            }
                
                showtimer += `
            </div>`;
            // var showtimer = hours+':'+minutes+':'+seconds;
            var discount_message = widget_text.replace('{discount}', max_discount_percent+'%').replace('{countdown}', showtimer);

            $('#live_discount_message').empty().append(`<div style="display:flex;align-items:center;">&nbsp;&nbsp;&nbsp;${discount_message}</div>`);
            
            var html = `
                User will initinally get <strong>${max_discount_percent}%</strong> discount that will decrease for <strong>${desired_descount_step_size}%</strong> 
                every <strong>${countdown_duration} Sec</strong>.
            `;
            $('#current_behaviour').empty().append(html);
        }
        changehtml();

        function isNumber(id, evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;
            
            if(evt.key == ".") return false;
            
            if(id == 'maximum_discount') {
                var total = $(`#${id}`).val() + evt.key;
                if(total >= 0 && total <= 100) {
                    return true;
                }
            }

            if(id == 'step_discount_percentage') {
                var total = $(`#${id}`).val() + evt.key;
                if(total > 0 && total <= 100) {
                    return true;
                }
            }

            if(id == 'countdown_duration') {
                var total = $(`#${id}`).val() + evt.key;
                if(total > 0) {
                    return true;
                }
            }
            
            if(id == 'discount_reactivation_days') {
                var total = $(`#${id}`).val() + evt.key;
                if(total >= 0) return true;
            }
            
            if(id == 'discount_reactivation_hours') {
                var total = $(`#${id}`).val() + evt.key;
                if(total >= 0 && total < 24) return true;

            }

            if(id == 'discount_reactivation_minutes') {
                var total = $(`#${id}`).val() + evt.key;
                if(total >= 0 && total < 60) return true;
            }

            if(id == 'discount_reactivation_seconds') {
                var total = $(`#${id}`).val() + evt.key;
                if(total >= 0 && total < 60) return true;
            }

            
            return false;
        }

        function checkhour(id, evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;
            
            var data = $(`#${id}`).val();
            if(data['10'] == 2 && (data['11'] != '_' || data['11'] > 4)) {
                return false;
            }
            console.log(data);
            console.log(data['10'], 'hour');

            return true;
        }

        


        if($('#product_visibility').val() == 'all') {
            $('#add_products').hide();
        } else if($('#product_visibility').val() == 'select_products') {
            $('#add_products').show();
        }
        $('#product_visibility').on('change', function (e) {
            if(this.value == 'all') {
                $('#add_products').hide();
            }
            if(this.value == 'select_products') {
                $('#add_products').show();
            }
        });

        function changestatus() {
            var status = "<?php $togglestatus = ($discount_shop->is_active == 'yes') ? 'no' : 'yes'; echo $togglestatus;?>";
            $.ajax({
                url: `{{ env('APP_URL').'/toogle-status' }}`,
                method: 'GET',
                data: {
                    status : `@if($discount_shop->is_active == 'yes') no  @else yes @endif`,
                },
                success: function(res) {
                    // alert('changestatus');
                    console.log(res, 'toggle');
                    if(res == 'true') {
                        window.location.href = `{{ env('APP_URL').'/setting?togglemessage=' }}${status}`;
                    } else {
                        alert('Something Went Wrong!');
                    }
                },
                error: function(err) {
                    alert('Something Went Wrong!');
                }
            });
        }
    
        var closeModal = () => {
            $(".modal").modal('hide');
            // $('#add_products_modal').empty();
        };

        function show_products() {
            $('#add_products').html(loaderhtml);
            $('#add_products_modal').empty();
            $(".modal").modal('hide');
            $.ajax({
                url: `{{ env('APP_URL').'/product-list' }}`,
                method: 'POST',
                // data: {
                //     _token: "{{ csrf_token() }}",
                //     shop: `{{ Auth::user()->name }}` 
                // },
                success: function (res) {
                    $('#add_products').html('Add Products');
                    console.log(res);
                    if(res) {        
                        $(".modal").modal('hide');
                        $('#add_products_modal').empty().append(res);
                        $("#product_list_modal").modal('show');
                    } else {
                        $(".modal").modal('hide');
                        alert('Something Wrong. Please Refresh Page');
                    }
                },
                error: function() {
                    $('#add_products').html('Add Products');
                    alert('Something Wrong. Please Refresh Page');
                }
            });
        }

        function addproducts() {
            var products = $('input[type="checkbox"][name="product_id[]"]:checked').map(function() { return this.value; }).get();
            var loader = '<i class="fas fa-sync-alt active-rotate" style="color:white"></i>';
            $(`#addproducts`).html(loader);

            $.ajax({
                url: `{{ env('APP_URL').'/update-product-list' }}`,
                method: 'GET',
                data: {
                    products: products,
                },
                success: function(res) {
                    if(res == 'true') {
                        window.location.href =  `{{ env('APP_URL').'/setting?addmessage=yes' }}`;
                    } else {
                        alert('Something Went Wrong!');
                    }
                },
                error: function(err) {
                    $(`#addproducts`).html('Update');
                    alert('Something Went Wrong!');
                }
            });

        }






        (function( jQuery, window, undefined ) {
  "use strict";

  var matched, browser;

  jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(opr)[\/]([\w.]+)/.exec( ua ) ||
      /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
      /(version)[ \/]([\w.]+).*(safari)[ \/]([\w.]+)/.exec( ua ) ||
      /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
      /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
      /(msie) ([\w.]+)/.exec( ua ) ||
      ua.indexOf("trident") >= 0 && /(rv)(?::| )([\w.]+)/.exec( ua ) ||
      ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
      [];

    var platform_match = /(ipad)/.exec( ua ) ||
      /(iphone)/.exec( ua ) ||
      /(android)/.exec( ua ) ||
      /(windows phone)/.exec( ua ) ||
      /(win)/.exec( ua ) ||
      /(mac)/.exec( ua ) ||
      /(linux)/.exec( ua ) ||
      /(cros)/i.exec( ua ) ||
      [];

    return {
      browser: match[ 3 ] || match[ 1 ] || "",
      version: match[ 2 ] || "0",
      platform: platform_match[ 0 ] || ""
    };
  };

  matched = jQuery.uaMatch( window.navigator.userAgent );
  browser = {};

  if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
    browser.versionNumber = parseInt(matched.version);
  }

  if ( matched.platform ) {
    browser[ matched.platform ] = true;
  }

  // These are all considered mobile platforms, meaning they run a mobile browser
  if ( browser.android || browser.ipad || browser.iphone || browser[ "windows phone" ] ) {
    browser.mobile = true;
  }

  // These are all considered desktop platforms, meaning they run a desktop browser
  if ( browser.cros || browser.mac || browser.linux || browser.win ) {
    browser.desktop = true;
  }

  // Chrome, Opera 15+ and Safari are webkit based browsers
  if ( browser.chrome || browser.opr || browser.safari ) {
    browser.webkit = true;
  }

  // IE11 has a new token so we will assign it msie to avoid breaking changes
  if ( browser.rv )
  {
    var ie = "msie";

    matched.browser = ie;
    browser[ie] = true;
  }

  // Opera 15+ are identified as opr
  if ( browser.opr )
  {
    var opera = "opera";

    matched.browser = opera;
    browser[opera] = true;
  }

  // Stock Android browsers are marked as Safari on Android.
  if ( browser.safari && browser.android )
  {
    var android = "android";

    matched.browser = android;
    browser[android] = true;
  }

  // Assign the name and platform variable
  browser.name = matched.browser;
  browser.platform = matched.platform;


  jQuery.browser = browser;
})( jQuery, window );

/*
  Masked Input plugin for jQuery
  Copyright (c) 2007-2011 Josh Bush (digitalbush.com)
  Licensed under the MIT license (http://digitalbush.com/projects/masked-input-plugin/#license) 
  Version: 1.3
  https://cloud.github.com/downloads/digitalBush/jquery.maskedinput/jquery.maskedinput-1.3.min.js
*/
(function(a){var b=(a.browser.msie?"paste":"input")+".mask",c=window.orientation!=undefined;a.mask={definitions:{9:"[0-9]",a:"[A-Za-z]","*":"[A-Za-z0-9]"},dataName:"rawMaskFn"},a.fn.extend({caret:function(a,b){if(this.length!=0){if(typeof a=="number"){b=typeof b=="number"?b:a;return this.each(function(){if(this.setSelectionRange)this.setSelectionRange(a,b);else if(this.createTextRange){var c=this.createTextRange();c.collapse(!0),c.moveEnd("character",b),c.moveStart("character",a),c.select()}})}if(this[0].setSelectionRange)a=this[0].selectionStart,b=this[0].selectionEnd;else if(document.selection&&document.selection.createRange){var c=document.selection.createRange();a=0-c.duplicate().moveStart("character",-1e5),b=a+c.text.length}return{begin:a,end:b}}},unmask:function(){return this.trigger("unmask")},mask:function(d,e){if(!d&&this.length>0){var f=a(this[0]);return f.data(a.mask.dataName)()}e=a.extend({placeholder:"_",completed:null},e);var g=a.mask.definitions,h=[],i=d.length,j=null,k=d.length;a.each(d.split(""),function(a,b){b=="?"?(k--,i=a):g[b]?(h.push(new RegExp(g[b])),j==null&&(j=h.length-1)):h.push(null)});return this.trigger("unmask").each(function(){function v(a){var b=f.val(),c=-1;for(var d=0,g=0;d<k;d++)if(h[d]){l[d]=e.placeholder;while(g++<b.length){var m=b.charAt(g-1);if(h[d].test(m)){l[d]=m,c=d;break}}if(g>b.length)break}else l[d]==b.charAt(g)&&d!=i&&(g++,c=d);if(!a&&c+1<i)f.val(""),t(0,k);else if(a||c+1>=i)u(),a||f.val(f.val().substring(0,c+1));return i?d:j}function u(){return f.val(l.join("")).val()}function t(a,b){for(var c=a;c<b&&c<k;c++)h[c]&&(l[c]=e.placeholder)}function s(a){var b=a.which,c=f.caret();if(a.ctrlKey||a.altKey||a.metaKey||b<32)return!0;if(b){c.end-c.begin!=0&&(t(c.begin,c.end),p(c.begin,c.end-1));var d=n(c.begin-1);if(d<k){var g=String.fromCharCode(b);if(h[d].test(g)){q(d),l[d]=g,u();var i=n(d);f.caret(i),e.completed&&i>=k&&e.completed.call(f)}}return!1}}function r(a){var b=a.which;if(b==8||b==46||c&&b==127){var d=f.caret(),e=d.begin,g=d.end;g-e==0&&(e=b!=46?o(e):g=n(e-1),g=b==46?n(g):g),t(e,g),p(e,g-1);return!1}if(b==27){f.val(m),f.caret(0,v());return!1}}function q(a){for(var b=a,c=e.placeholder;b<k;b++)if(h[b]){var d=n(b),f=l[b];l[b]=c;if(d<k&&h[d].test(f))c=f;else break}}function p(a,b){if(!(a<0)){for(var c=a,d=n(b);c<k;c++)if(h[c]){if(d<k&&h[c].test(l[d]))l[c]=l[d],l[d]=e.placeholder;else break;d=n(d)}u(),f.caret(Math.max(j,a))}}function o(a){while(--a>=0&&!h[a]);return a}function n(a){while(++a<=k&&!h[a]);return a}var f=a(this),l=a.map(d.split(""),function(a,b){if(a!="?")return g[a]?e.placeholder:a}),m=f.val();f.data(a.mask.dataName,function(){return a.map(l,function(a,b){return h[b]&&a!=e.placeholder?a:null}).join("")}),f.attr("readonly")||f.one("unmask",function(){f.unbind(".mask").removeData(a.mask.dataName)}).bind("focus.mask",function(){m=f.val();var b=v();u();var c=function(){b==d.length?f.caret(0,b):f.caret(b)};(a.browser.msie?c:function(){setTimeout(c,0)})()}).bind("blur.mask",function(){v(),f.val()!=m&&f.change()}).bind("keydown.mask",r).bind("keypress.mask",s).bind(b,function(){setTimeout(function(){f.caret(v(!0))},0)}),v()})}})})(jQuery);


$(function(){
    $.mask.definitions['H'] = "[0-2]";
    $.mask.definitions['h'] = "[0-9]";
    $.mask.definitions['M'] = "[0-5]";
    $.mask.definitions['m'] = "[0-9]";
    $.mask.definitions['d'] = "[0-9]";


    $("#discount_reactivation").mask("dd DAY(S) Hh:Mm:Mm");

}); 

$('#setup-form').on('submit', function(e){
    var discount_reactivation = $('#discount_reactivation').val();
    if(discount_reactivation['10'] == '2') {
        if(discount_reactivation['11'] >= 0 && discount_reactivation['11'] < 4) {
        } else {
            e.preventDefault();
            alert('Please Enter valid Discount Reactivation');
        }

    }
});


        
</script>
@endsection

</body>
</html>