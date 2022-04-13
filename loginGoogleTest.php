<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Login da Google">
  <meta name="author" content="António Lira Fernandes">
  <meta name="google-signin-client_id" content="436091069232-g9n82uk2iua4hpa3unrfrt3iq0gkntb7.apps.googleusercontent.com">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="icon" type="images/png" sizes="16x16" href="img/looglemin.png">
  <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script-->
  <!--script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script-->
  <script src="https://apis.google.com/js/platform.js" async defer></script>
  <title>Login</title>
</head>

<body class="bg-gradient-primary">


  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-5 m-2">
                <img src="img/loogle.png"/>
              </div>
              <div class="col-lg-6 m-2 text-center">
                <h1>System Login</h1>
                <!-- <form class="user">  -->
                <hr>
                <div id="my-signin2"></div>
                <div id="msg"></div>
             </div>
              
           </div>
          </div>
        </div>

      </div>

    </div>

  </div>
  <script>
    function onSignIn(googleUser) {
      var profile = googleUser.getBasicProfile();
      var userID = profile.getId();
      var userName = profile.getName();
      var userPicture = profile.getImageUrl();
      var userEmail = profile.getEmail();
      var userToken = googleUser.getAuthResponse().id_token;
      //alert("dffsdfsdfsdfs");
      document.getElementById("msg").innerHTML = userEmail;
      //alert(userEmail)
      if (userEmail !== '') {
        var dados = {
          userID: userID,
          userName: userName,
          userPicture: userPicture,
          userEmail: userEmail
        }
        $.post('public/validacaoLogin', dados, function(retorna) {
          //alert(retorna)
          //retorna=1;
          //console.log("dsdsd")
          if (retorna == 'Erro') {
            document.getElementById("msg").innerHTML = '<div class="alert alert-danger">O utilizador não tem acesso ao Sistema de Votaçōes</div>';
          } else {
            if (retorna == 1) {
              window.location.href = "/public/gestor";
            } else {
              window.location.href = "/public/mesa";
            }
          }

          document.getElementById("msg").innerHTML = retorna;

        });

      } else {
        var msg = "Utilizador não encontrado";
        document.getElementById("msg").innerHTML = msg;
      }


    }

    function renderButton() {
      gapi.signin2.render('my-signin2', {
        'scope': 'profile email',
        'width': '250px',
        'height': 37, 
        'longtitle': true,
        'theme': 'dark',
        'onsuccess': onSignIn

      });
    }
    
    
    
  </script>
  <!-- Bootstrap core JavaScript-->
  <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
  <script src="/templates/startbootstrap_admin_2/vendor/jquery/jquery.min.js"></script>
  <script src="/templates/startbootstrap_admin_2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="/templates/startbootstrap_admin_2/vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="/templates/startbootstrap_admin_2/vendor/jquery-easing/jquery.easing.min.js"></script>

  <!-- Custom scripts for all pages-->
  <script src="/templates/startbootstrap_admin_2/js/sb-admin-2.min.js"></script>

</body>

</html>
