<!DOCTYPE html>
<html lang="pt">
  
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="icon" type="image/png" sizes="16x16" href="/imagens/66276m.png">
  <meta name="google-signin-client_id" content="436091069232-g9n82uk2iua4hpa3unrfrt3iq0gkntb7.apps.googleusercontent.com">
  <title>Professor| 2do</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/templates/AdminLTE/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="/templates/AdminLTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/templates/AdminLTE/dist/css/adminlte.min.css">

 
</head>

<body class="hold-transition login-page bg-dark ">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="" class="h1 text-dark"><b>Administração ESM</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Autenticação para entrar no sistema</p>

        <form action="" method="post">
          <div class="input-group mb-3">
            <input type="email" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="icheck-primary">
                <input type="checkbox" id="remember">
                <label for="remember" class="text-secondary">
                Lembrar
              </label>
              </div>
            </div>
            <!-- /.col -->
            <div class="col-6">
              <button type="submit" class="btn btn-primary btn-block">Ligar</button>
            </div>
          </div>
          <div class="row">
              <div class="col-6">
                <p for="remember" class="text-secondary">
                usar o email institucional
              </p>
              </div>
              <div class="col-6">
                <div class="g-signin2 btn btn-light btn-block" data-onsuccess="onSignIn" data-width="140" ></div>
              </div>
          </div>
            
            <!-- /.col -->
          
        </form>

        <
        
        <!-- /.social-auth-links -->
        <div id="msg" class="text-secondary">     
        </div>
        <p class="mb-1">
          <a href="">Não sei a password</a>
        </p>
        <p class="mb-0">
          <a href="" class="text-center">Novo Registo</a>
        </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="/templates/AdminLTE/plugins/jquery/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="/templates/AdminLTE/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="/templates/AdminLTE/dist/js/adminlte.min.js"></script>
  
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <script src="/js/code/valida.js"></script>

  <script>
    
    
    function onSignIn(googleUser) {
      
      const queryString = window.location.search;
      const urlParams = new URLSearchParams(queryString);
      const act = urlParams.get('d')
      if (act=="k"){
        //alert(act)
       var auth2 = gapi.auth2.getAuthInstance();
        auth2.signOut().then(function () {
          console.log('User signed out.');
        });
        gapi.auth2.getAuthInstance().disconnect();
        window.location.href = "/public/";
      }else{
         //alert("asdasds")
      //console.log('Logged in as: ' + googleUser.getBasicProfile().getName());
      var profile = googleUser.getBasicProfile();
      //console.log('ID: ' + profile.getId()); // Do not send to your backend! Use an ID token instead.
      //console.log('Name: ' + profile.getName());
      //console.log('Image URL: ' + profile.getImageUrl());
      //console.log('Email: ' + profile.getEmail()); // This is null if the 'email' scope is not present.
      var profile = googleUser.getBasicProfile();
      var userID = profile.getId(); // Do not send to your backend! Use an ID token instead.
      var userName = profile.getName();
      var userImageURL = profile.getImageUrl();
      var userEmail = profile.getEmail();
      var userToken = googleUser.getAuthResponse().id_token;
      //document.getElementById("msgImg").innerHTML = "<img src='" + userImageURL + "' width='80' height='80' class='rounded'>";
      document.getElementById("msg").innerHTML = userName;
      //document.getElementById("btlogin").innerHTML = "<img src='" + userImageURL + "' width='20' height='20' class='rounded'>"

      if (userEmail !== '') {
        var dados = {
          userID: userID,
          userName: userName,
          userImageURL: userImageURL,
          userEmail: userEmail
        };
        $.post('/public/validacaoLogin', dados, function(retorna) {
          //alert(retorna);
          if (retorna === "erro") {
            var msg = "O/A " + userName + " não tem acesso ao sistema!";
            document.getElementById('msg').innerHTML = msg;
            document.getElementById('msg').setAttribute("class", "text-danger")
          } else {
            window.location.href = "/public/admin";
          }
        });
      } else {
        var msg = "Não está nenhuma utilizador autenticado";
        document.getElementById('msg').innerHTML = msg;
        document.getElementById('msg').setAttribute("class", "text-danger")
      }
        
      }
      
      
      
     

    }
  </script>
  
  
</body>

</html>
