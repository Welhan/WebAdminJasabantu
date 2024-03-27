<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ $title }}</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="vendors/feather/feather.css">
    <link rel="stylesheet" href="vendors/ti-icons/css/themify-icons.css">
    <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- Plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/vertical-layout-light/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/logo-jb/logo_jasabantu_mini.png" />
</head>

<body>
    <div class="container-scroller">
        <div class="container-fluid page-body-wrapper full-page-wrapper">
            <div class="content-wrapper d-flex align-items-center auth px-0">
                <div class="row w-100 mx-0">
                    <div class="col-lg-4 mx-auto">
                        <div class="auth-form-light text-left py-5 px-4 px-sm-5">
                            <div class="brand-logo text-center">
                                <img src="images/logo-jb/logo_transparent.png" alt="logo">
                            </div>
                            @if (Session::get('message'))
                            <div class="alert {{ Session::get('alert') }} fade show" role="alert">
                                <strong>{{ Session::get('message') }}</strong>
                            </div>
                            @endif
                            <form class="pt-3" id="formSubmit">
                                @csrf
                                <div class="form-group">
                                    <input type="email" class="form-control form-control-lg" d="Email" name="Email"
                                        placeholder="Email" autofocus>
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control form-control-lg" id="Password"
                                        name="Password" placeholder="Password">
                                </div>
                                <div class="mt-3">
                                    <button type="submit"
                                        class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn"
                                        id="btnProcess">Sign
                                        In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- content-wrapper ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <!-- plugins:js -->
    <script src="vendors/js/vendor.bundle.base.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <!-- endinject -->
    <!-- Plugin js for this page -->
    <!-- End plugin js for this page -->
    <!-- inject:js -->
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <script src="js/settings.js"></script>
    <script src="js/todolist.js"></script>
    <script>
        $('#formSubmit').submit(function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/login',
            data: $('#formSubmit').serialize(),
            beforeSend: function() {
                $('#btnProcess').attr('disabled', 'disabled');
                $('#btnProcess').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if(response.success){
                  window.location = 'mitra-management'
                }else{
                    location.reload()
                }
            },

        })
    })
    </script>
    <!-- endinject -->
</body>

</html>