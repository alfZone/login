<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Login da Google">
  <meta name="author" content="António Lira Fernandes">
  <meta name="google-signin-client_id" content="436091069232-g9n82uk2iua4hpa3unrfrt3iq0gkntb7.apps.googleusercontent.com">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="icon" type="images/png" sizes="16x16" href="/images/looglemin.png">
  <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script-->
  <!--script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script-->
  
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
                <img src="/images/loogle.png"/>
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
  
  <!-- Bootstrap core JavaScript-->
  <script src="https://apis.google.com/js/platform.js?onload=renderButton" async defer></script>
  <!--script src="/templates/startbootstrap_admin_2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script-->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <script src="https://apis.google.com/js/platform.js" async defer></script>
  
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
       alert(dados.userID);
       $.post('/public/validacaoLogin', dados, function(retorna) {
          alert(retorna);
          if (retorna === "erro") {
            var msg = "O/A " + userName + " não tem acesso ao sistema!";
            document.getElementById('msg').innerHTML = msg;
            document.getElementById('msg').setAttribute("class", "text-danger")
          } else {
            window.location.href = "/public/admin";
          }
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
  

</body>

</html>
