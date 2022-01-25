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
  </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand text-danger" href="index.html"><span class="text-uppercase">Dash Discounts</span></a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
                    <div class="navbar-nav">
                        <a class="nav-link active" aria-current="page" href="index.html">Home</a>
                        <a class="nav-link" href="#">Features</a>
                        <a class="nav-link" href="#">Pricing</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="midContent">
            <div class="container">
                <div class="row">
                    <div class="midHeader mt-5">
                        <h2 class="">Welcome</h2>
                    </div>
                </div>
                <div class="row welcomePage align-items-center">
                    <div class="col-md-4">
                        <div class="welcomePLeft">
                            <h2 class="display-4">Welcome to<br /> Dash Discounts</h2>
                            <p>Click Start Setup below, choose your discount settings and you're ready to boost your sales.</p>
                            <a type="button" class="btn btn-danger btn-lg" href="startSetup.html">Start Setup</a>
                        </div>
                    </div>
                    <div class="col-md-5 offset-md-3">
                        <div class="welcomePRight">
                            <img src="assets/images/order-confirmed-concept-illustration_114360-1486.jpg" class="img-fluid"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>






    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-eMNCOe7tC1doHpGoWe/6oMVemdAVTMs2xqW4mwXrXsW0L84Iytr2wi5v2QjrP/xp" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.min.js" integrity="sha384-cn7l7gDp0eyniUwwAZgrzD06kc/tftFf19TOAs2zVinnD/C7E91j9yyk5//jjpt/" crossorigin="anonymous"></script>
    </body>
</html>
