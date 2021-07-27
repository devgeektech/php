<?php $id = $data['id']?>
<!DOCTYPE html>
<html lang="">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>VerkoopApp | Forgot Password</title>
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('public/assets/img/apple-icon.png') }}">
        <link rel="icon" type="image/png" href="{{ asset('public/assets/img/favicon.png') }}">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style type="text/css" media="screen">
            .forgot_password{max-width: 600px;margin-left: auto;margin-right: auto;margin-top: 30px;}
            .card_new{box-shadow: 0 0 3px #666666; padding: 15px;border-radius: 6px;}
            .verkoop_logo{display: block;margin: 30px auto;max-width: 100px;}
        </style>
    </head>
    <body>
        
        <div class="forgot_password">
            <div class="card_new p-2">
                <img src="{{url('/public/images/logo.png')}}" alt="Logo" class="verkoop_logo">
                <h2 class="text-center mb-3">Reset Your Password</h2>
                <form class="forgotpass_form" action="{{url('updatepass')}}" method="post">
                    <div class="mb-4">
                         <div class="input-group">
                         <div class="input-group-prepend">
                           <span class="input-group-text"><i class="fa fa-lock"></i></span>
                         </div>                    
                         <input type="password" class="form-control" placeholder="New Password" id="txtPassword" name="password" required/>
                       </div>
                       <div class="input-group">
                         <p id="password" class="text-danger ml-5"></p>
                       </div> 
                    </div>
                    
                    <div class="mb-4">
                        <div class="input-group">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                          </div>
                          <input type="password" class="form-control" placeholder="Confirm Password" id="txtConfirmPassword" required/>
                        </div>
                        <div class="input-group">
                          <p id="confirmPassword" class="text-danger ml-5"></p>
                        </div>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="userid" ng-modal="userid" value={{$id}}>
                    <button type="submit" class="btn btn-danger mb-4" onclick="return Validate()">Reset Password</button>
                </form>
            </div>
        </div>

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery.js"></script>
        <!-- Bootstrap JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        <script type="text/javascript">
        function Validate() {
            var password = document.getElementById("txtPassword").value;
            var confirmPassword = document.getElementById("txtConfirmPassword").value;
            var passwordId = document.getElementById('password');
            var confirmPasswordId = document.getElementById("confirmPassword");
            if (password != confirmPassword) {
                confirmPasswordId.innerHTML = "Passwords do not match.";
                confirmPasswordId.style.display = "block";
                passwordId.style.display = "none";
                return false;
            }
            if(!password){
                passwordId.innerHTML = "Password can`t be blank.";
                passwordId.style.display = "block";
                confirmPasswordId.style.display = "none";
                return false;
            }
            confirmPasswordId.style.display = "none";
            passwordId.style.display = "none";
            return true;
        }
    </script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    @if (\Session::has('update'))
    <script>
    swal ( "Update" ,  "Password update successfully" ,  "update" );
    </script>
    @endif
    @if (\Session::has('otherupdate'))
    <script>
    swal ( "NotUpdate" ,  "Use another password" ,  "otherupdate" );
    </script>
    @endif  
    </body>
</html>
