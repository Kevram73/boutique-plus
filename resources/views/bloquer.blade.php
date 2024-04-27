<!doctype html>
<html class="fixed">
<head>

    <!-- Basic -->
    <meta charset="UTF-8">

    <meta name="keywords" content="HTML5 Admin Template" />
    <meta name="description" content="Porto Admin - Responsive HTML5 Template">
    <meta name="author" content="okler.net">

    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

    <!-- Web Fonts  -->

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="octopus/assets/vendor/bootstrap/css/bootstrap.css" />
    <link rel="stylesheet" href="octopus/assets/vendor/font-awesome/css/font-awesome.css" />
    <link rel="stylesheet" href="octopus/assets/vendor/magnific-popup/magnific-popup.css" />
    <link rel="stylesheet" href="octopus/assets/vendor/bootstrap-datepicker/css/datepicker3.css" />

    <!-- Theme CSS -->
    <link rel="stylesheet" href="octopus/assets/stylesheets/theme.css" />

    <!-- Skin CSS -->
    <link rel="stylesheet" href="octopus/assets/stylesheets/skins/default.css" />

    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="octopus/assets/stylesheets/theme-custom.css">

    <!-- Head Libs -->
    <script src="octopus/assets/vendor/modernizr/modernizr.js"></script>

</head>
<body>
<!-- start: page -->
<section class="body-sign">
    <div class="center-sign">
        <a  class="logo pull-left">
            <img src="image/IMG-2014.PNG" height="54" alt="BOUTIQUE+" />
        </a>

        <div class="panel panel-sign">
            <div class="panel-title-sign mt-xl text-right">
                <h2 class="title text-uppercase text-bold m-none"><i class="fa fa-user mr-xs"></i> CONNEXION</h2>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group mb-lg">
                        <label>Email</label>
                        <div class="input-group input-group-icon">
                            <input id="email"  name="email" type="email" class="form-control input-lg  @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" />
                            <span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-user"></i>
										</span>
                            </span>
                        </div>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-group mb-lg">
                        <div class="clearfix">
                            <label class="pull-left">Mot de passe</label>
                        </div>
                        <div class="input-group input-group-icon">
                            <input  id="password" name="password" type="password" class="form-control input-lg  @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" />
                            <span class="input-group-addon">
										<span class="icon icon-lg">
											<i class="fa fa-lock"></i>
										</span>
									</span>
                        </div>
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-sm-4 text-right">
                            <button type="submit" class="btn btn-primary hidden-xs">Connexion</button>
                        </div>
                    </div>

                    <span class="mt-lg mb-lg line-thru text-center text-uppercase">
								<span>BOUTIQUE+</span>
							</span>



                </form>
            </div>
        </div>

        <p class="text-center text-muted mt-md mb-md">&copy; Copyright 2019. All rights reserved. by  BOUTIQUE+.</p>
    </div>

</section>
<!-- end: page -->

<!-- Vendor -->
<script src="octopus/assets/vendor/jquery/jquery.js"></script>
<script src="octopus/assets/vendor/jquery-browser-mobile/jquery.browser.mobile.js"></script>
<script src="octopus/assets/vendor/bootstrap/js/bootstrap.js"></script>
<script src="octopus/assets/vendor/nanoscroller/nanoscroller.js"></script>
<script src="octopus/assets/vendor/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
<script src="octopus/assets/vendor/magnific-popup/magnific-popup.js"></script>
<script src="octopus/assets/vendor/jquery-placeholder/jquery.placeholder.js"></script>

<!-- Theme Base, Components and Settings -->
<script src="octopus/assets/javascripts/theme.js"></script>

<!-- Theme Custom -->
<script src="octopus/assets/javascripts/theme.custom.js"></script>

<!-- Theme Initialization Files -->
<script src="octopus/assets/javascripts/theme.init.js"></script>
@include('sweetalert::alert')
</body>
</html>
