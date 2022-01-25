<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discount Page</title>
    <!-- <link rel="stylesheet" href="{{ url('assets/css/discount.css') }}"> -->
</head>
<body>
<div class="loader"><b>Loading! Please wait...</b></div>
<div class="table-responsive" id="table-container" style="display:none;">
    <table style="width:100%;">
        <thead>
            <tr>
                <th style="width:70px;">Image</th>
                <th style="text-align:left;padding-left: 10px;">Title</th>
                <th style="width:83px;text-align:center;">Action</th>
            </tr>
        </thead>
        <tbody class="table-for-products">
        </tbody>
    </table>
</div>

<script
src='https://code.jquery.com/jquery-3.6.0.min.js'
integrity='sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4='
crossorigin='anonymous'></script>
<script>
    var shop = Shopify.shop;
    $.ajax({
        url: `{{ url('product_discount_page_content').'?shop=${shop}' }}`,
        type: 'GET',
        success: function(response) {
            console.log(response, 'product_discount_page_content');
            $('.table-for-products').append(response);
            $('.loader').hide();
            $('#table-container').show();
        },
        error: function(e) {
            console.log('Error: ' + e, 'product_discount_page_content');
            $('.loader').hide();
        }
    });
</script>

</body>
</html>