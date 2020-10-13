<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="{{url('/assets/auth/images/icons/favicon.ico')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{url('/assets/auth/vendor/bootstrap/css/bootstrap.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{url('/assets/auth/fonts/font-awesome-4.7.0/css/font-awesome.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{url('/assets/auth/vendor/animate/animate.css')}}">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="{{url('/assets/auth/vendor/css-hamburgers/hamburgers.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{url('/assets/auth/vendor/select2/select2.min.css')}}">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="{{url('/assets/auth/css/util.css')}}">
	<link rel="stylesheet" type="text/css" href="{{url('/assets/auth/css/main.css')}}">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="{{url('/assets/auth/images/img-01.png')}}" alt="IMG">
				</div>        
				<form action="{{url('post-password')}}" method="POST" class="login100-form validate-form">
        {{ csrf_field() }}
          @if(\Session::has('alert'))
              <div class="alert alert-danger">
                  <div>{{ Session::get('alert') }}</div>
              </div>
          @endif
          @if(\Session::has('alert-success'))
              <div class="alert alert-success">
                  <div>{{ Session::get('alert-success') }}</div>
              </div>
          @endif
					<span class="login100-form-title">          
						Login
					</span>

					<div class="input-email">
						<input class="input100" type="hidden" value="{{Session::get('email')}}" name="email" placeholder="Email">					
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Password is required">
						<input class="input100" type="password" name="password" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
            </span>            
          </div>      
					
					<div class="container-login100-form-btn">
          <p id="timer">Password will be expired at <span id="time">120 </span> Second</p>
						<button type="submit" class="login100-form-btn">
							Verify
						</button>
					</div>					

					<div class="text-center p-t-136">
						<a class="txt2" href="{{url('registration')}}">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="{{url('/assets/auth/vendor/jquery/jquery-3.2.1.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{url('/assets/auth/vendor/bootstrap/js/popper.js')}}"></script>
	<script src="{{url('/assets/auth/vendor/bootstrap/js/bootstrap.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{url('/assets/auth/vendor/select2/select2.min.js')}}"></script>
<!--===============================================================================================-->
	<script src="{{url('/assets/auth/vendor/tilt/tilt.jquery.min.js')}}"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="{{url('/assets/auth/js/main.js')}}"></script>
<!--===============================================================================================-->
  <script>
    var counter = 120;
    var interval = setInterval(function() {
        counter--;
        // Display 'counter' wherever you want to display it.
        if (counter <= 0) {
            clearInterval(interval);
            $('#timer').html("<h6>Click <a href='#'>here<a> to regenarate password</h6>");  
            return;
        }else{
          $('#time').text(counter);
          console.log("Timer --> " + counter);
        }
    }, 1000);
  </script>
</body>
</html>