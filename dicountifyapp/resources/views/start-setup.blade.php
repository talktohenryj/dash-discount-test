<!doctype html>
<html lang="en">
<head>
    <!-- meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous" />
    <!-- Custom CSS -->
    <link href="assets/css/custom.css" rel="stylesheet" />
    
    <title>{{ env('APP_NAME') }}</title>
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
            background-color: rgba(1,1,1,0.03);
        }
        .card-body {
            background-color: white;
        }
        .card-title {
            font-size: 1.1rem;
        }
        .form-label {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
@extends('shopify-app::layouts.default')

@section('content')

    <!-- Begin:: Discountify Setup -->
    <div class="midContent">
        <div class="container">
            <div class="row">
                <div class="col-md-10 midHeader mt-3 mb-2" style="margin:auto;">
                    <h3 class="">Basic Setup</h3>
                </div>
            </div>
            <div class="row setupPage">
                <div class="col-md-10" style="margin:auto;">
                    <div id="step_1" class="card text-dark bg-light mb-2">
                        <div class="card-header">
                            1. Step
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Start be selecting maximum discount that will be shown to users for all your items</h5>
                            <form class="mb-3">
                                <label for="max_discount_percent" class="form-label">Maximum discount percentage</label>
                                <input type="number" name="max_discount_percent" id="max_discount_percent" onkeypress="return isNumber('max_discount_percent', event);" onfocusout="changehtml()" value="" placeholder="25" min="0" max="100" pattern="[0-9]+" class="form-control form-control-sm" /> 
                                <small class="setupSelectHelpText text-secondary ">Set the maximum discount percentage that can be applied to all your products.</small>
                            </form>
                            <button id="step_1t" type="button" class="btn btn-outline-dark">Next</button>
                        </div>
                    </div>
                    <div id="step_2" class="card text-dark bg-light mb-2" style="display: none;">
                        <div class="card-header">
                            2. Step
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Choose the desired discount step size</h5>
                            <form class="mb-3">
                                <label for="desired_descount_step_size" class="form-label">Discount percentage step size</label>
                                <input type="number" name="desired_descount_step_size" id="desired_descount_step_size" onkeypress="return isNumber('desired_descount_step_size', event);"   onfocusout="changehtml()"  placeholder="5" min="0" max="100" pattern="[0-9]+" class="form-control form-control-sm" /> 
                                <small class="setupSelectHelpText text-secondary ">Set the size of discount decrease the happens each time countdown reaches 0.</small>
                            </form>
                            <button id="step_2t" type="button" class="btn btn-outline-dark">Next</button>
                        </div>
                    </div>
                    <div id="step_3" class="card text-dark bg-light mb-2" style="display: none;">
                        <div class="card-header">
                            3. Step
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Here you need to select the duration of countdown on each step</h5>
                            <form class="mb-3">
                                <label for="countdown_duration" class="form-label">Countdown duration</label>
                                <input type="number" name="countdown_duration" id="countdown_duration" onkeypress="return isNumber('countdown_duration', event);"  onfocusout="changehtml();" value="" placeholder="120" min="0" pattern="[0-9]+" class="form-control form-control-sm" />
                                <small class="setupSelectHelpText text-secondary ">Set the duration of eact countdown durationin seconds.</small>
                            </form>
                            <button id="step_3t" type="button" class="btn btn-outline-dark">Next</button>
                        </div>
                    </div>
                    <div id="step_4" class="card text-dark bg-light mb-5" style="display: none;">
                        <div class="card-header">
                            4. Step
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">That is all we need at this point for completing the setup</h5>
                            <div class="spHighLightText">
                                <h4>Currently set behaviour</h4>
                                <h5 id="current_behaviour"></h5>
                            </div>
                            <button type="button" class="btn btn-outline-dark" onclick="confirm_setup()">Confirm &amp; Finish Setup</button>
                        </div>
                    </div>
                <!-- </div> -->
            </div>
        </div>
    </div>
    <!-- End:: Discountify Setup -->

@endsection

@section('scripts')
    @parent

    <script>
        actions.TitleBar.create(app, { title: 'Welcome' });
    </script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $("#step_1t").click(function() {
                if(!$('#max_discount_percent').val()) { 
                    alert('Please Enter Maximum Discount Percentage');
                    return false; 
                }
                $("#step_2").slideDown("slow");
                $(this).addClass("d-none");
            });
            
            $("#step_2t").click(function() {
                if(!$('#desired_descount_step_size').val()) { 
                    alert('Please Enter Discount Step Size');
                    return false; 
                }
                $("#step_3").slideDown("slow");
                $(this).addClass("d-none");
            });
            
            $("#step_3t").click(function() {
                if(!$('#countdown_duration').val()) { 
                    alert('Please Enter Countdown Duration');
                    return false; 
                }
                changehtml();
                $("#step_4").slideDown("slow");
                $(this).addClass("d-none");
            });
        });

        function changehtml() {
            var max_discount_percent = $('#max_discount_percent').val();
            var desired_descount_step_size = $('#desired_descount_step_size').val();
            var countdown_duration = $('#countdown_duration').val();
            
            var html = `
                User will initinally get <strong>${max_discount_percent}%</strong> discount that will decrease for <strong>${desired_descount_step_size}%</strong> 
                every <strong>${countdown_duration} Sec</strong>.
            `;
            $('#current_behaviour').empty().append(html);
        }


        
        function isNumber(id, evt) {
            var iKeyCode = (evt.which) ? evt.which : evt.keyCode
            if (iKeyCode != 46 && iKeyCode > 31 && (iKeyCode < 48 || iKeyCode > 57))
                return false;
            
            if(evt.key == ".") return false;
            
            if(id == 'max_discount_percent') {
                var total = $(`#${id}`).val() + evt.key;
                if(total >= 0 && total <= 100) {
                    return true;
                }
            }

            if(id == 'desired_descount_step_size') {
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
            
            return false;
        }

        function confirm_setup() {
            var max_discount_percent = $('#max_discount_percent').val();
            var desired_descount_step_size = $('#desired_descount_step_size').val();
            var countdown_duration = $('#countdown_duration').val();
            var appurl = "{{ env('APP_URL') }}";
            
            var url = appurl+"/setting?max_discount_percent="+max_discount_percent+"&desired_descount_step_size="+desired_descount_step_size+"&countdown_duration="+countdown_duration ;
            
            window.location.href = url;
        }

    </script>

@endsection

</body>
</html>
