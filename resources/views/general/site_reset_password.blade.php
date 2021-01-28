<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Website Title -->
    <title>{{$title}}</title>
    
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&display=swap&subset=latin-ext" rel="stylesheet">
    <link href="{{ URL::asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/fontawesome-all.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/swiper.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/styles.css') }}" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
</head>
<body data-spy="scroll" data-target=".fixed-top">
    <!-- Header -->
    <header id="header" class="ex-2-header" style="background-color: #75C244;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12"><!-- Sign Up Form -->
                    <div class="form-container">
                        @if(Session::get('alert-error'))
                        <h4 style="color:red">ERROR</h4>
                        <p style="color:red">{{ Session::get('alert-error') }}</password_get_info>
                        @endif
                        <form data-toggle="validator" data-focus="false" action="{{ url('/site/reset_password_action') }}" method="post">
                            
                        <div class="form-group">
                                <input type="password" class="form-control-input" id="password" required minlength="8" name="newpassword">
                                <label class="label-control" for="password">Kata Sandi Baru</label>
                                <div class="help-block with-errors"></div>
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control-input" id="confirm_password" required name="confirm_password">
                                <label class="label-control" for="confirm_password">Konfirmasi kata sandi baru</label>
                                <div class="help-block with-errors"></div>
                                <span id='message'></span>
                            </div>
                            <input type="text" value="{{$code}}" name="code" style="display:none">
                            <div class="form-group">
                                <button type="submit" class="form-control-submit-button" id="Button"
                                    style="background-color: #75C244;border-color: #75C244;">Lanjutkan</button>
                            </div>
                        </form>
                    </div> <!-- end of form container -->
                    <!-- end of sign up form -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </header> <!-- end of ex-header -->
    <!-- end of header -->


    <!-- Scripts -->
    <script>
       $('#confirm_password').on('keyup', function () {
        if ($('#password').val() == $('#confirm_password').val()) {
            $('#message').html(' ').css('color', 'green');
            document.getElementById("Button").disabled = false;
        } else 
            $('#message').html('Password tidak sama').css('color', 'red');
            document.getElementById("Button").disabled = true;
        });
    </script>
    <script src="js/jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="js/popper.min.js"></script> <!-- Popper tooltip library for Bootstrap -->
    <script src="js/bootstrap.min.js"></script> <!-- Bootstrap framework -->
    <script src="js/jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="js/swiper.min.js"></script> <!-- Swiper for image and text sliders -->
    <script src="js/jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
    <script src="js/validator.min.js"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
    <script src="js/scripts.js"></script> <!-- Custom scripts -->
</body>
</html>