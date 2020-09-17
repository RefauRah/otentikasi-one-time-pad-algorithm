<!DOCTYPE html>
<html>
<head>
<title>Login Form</title>
 
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!--Bootsrap 4 CDN-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="{{url('css/style.css')}}">
 
</head>
<body>
<div class="container-fluid">
  <div class="row no-gutter">
    <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div>
    <div class="col-md-8 col-lg-6">
      <div class="login d-flex align-items-center py-5">
        <div class="container">
          <div class="row">
            <div class="col-md-9 col-lg-8 mx-auto">
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
              <p id="timer">Password will be expired at <span id="time">60 </span> Second</p>
               <form action="{{url('post-password')}}" method="POST" id="logForm">
 
                 {{ csrf_field() }}
 
                <div class="form-label-group">
                  <input type="hidden" name="email" value="{{Session::get('email')}}" id="inputEmail" class="form-control" placeholder="Email address" >
                  <label for="inputEmail">Email address</label>
 
                  @if ($errors->has('email'))
                  <span class="error">{{ $errors->first('email') }}</span>
                  @endif    
                </div> 
 
                <div class="form-label-group">
                  <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
                  <label for="inputPassword">Password</label>
                   
                  @if ($errors->has('password'))
                  <span class="error">{{ $errors->first('password') }}</span>
                  @endif  
                </div>
                
                <button class="btn btn-lg btn-primary btn-block btn-login text-uppercase font-weight-bold mb-2" type="submit">Sign In</button>
                <div class="text-center">If you have an account?
                  <a class="small" href="{{url('registration')}}">Sign Up</a></div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
 
<script>
  var counter = 60;
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