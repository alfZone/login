<!doctype html>
<html lang="pt">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="description" content="Guia de programas da Televisão, com várias formas de pesquisar a informação e que funciona no telemóvel. 
                                    Pode procurar filmes e séries automaticamente em todos os canais.
                                    Poderá escolher os seus canais favoritos e ver o que está a dar neste momento nos seus canais favoritos.">
  <meta name="keywords" content="box, na-tv.pt, na tv, natv, guiatv, guia tv, tv, televisão, programação tv, canais portugueses, series, filmes,  zon, meo, cabovisao, programas da tv">
  <meta property="og:type" content="website">
  <meta property="og:url" content="https://na-tv.pt/">
  
  
  <meta name="author" content="António Lira Fernandes">
  
  <meta name="robots" content="index,follow" />
  <meta name="googlebot" content="index,follow" />
  
  <link rel="icon" href="/imagens/retro-tv.png">
  <title>Exemple</title>

  <!-- Bootstrap core CSS -->
  <link href="/templates/bootstrapT/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="/templates/bootstrapT/assets/css/styles.css" rel="stylesheet">
  <link href="/templates/bootstrapT/assets/css/bootstrapT.css" rel="stylesheet">


</head>

<body>
   <script src="https://accounts.google.com/gsi/client" async defer></script>





  <main>
    <div class="album bg-dark">
      <div class="container-fluid">
        <div id="main">
          <h1>Guia Na-Tv e Programação dos Canais</h1>
          <div class="row"><br><br></div>
          <div class="row">
            <div class="col-md-4"></div>
            <div class="col-md-4">
              <div class="card card-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-secondary">
                  <h3 class="widget-user-username">Autenticação</h3>
                  <p id="msgNome"></p>
                </div>
                <div class="widget-user-image" id="msgImg">
                  <img class="img-circle elevation-2" src="/templates/bootstrapT/images/facePrime.jpg" id="userPhoto" alt="User Avatar">
                </div>
                <div class="row"><br><br><br></div>   
                <div class="row text-center">
                    <div id="buttonDiv"></div>
                </div>
                <div class="row"><br></div>  
                <div class="card-footer">
                  <div class="row"><br></div>   
                  <div class="row">
                    <div class="col-sm-12 col-md-12 text-center">
                      <button onclick="" type="button" class="btn btn-primary logPrime btn-block" data-bs-toggle="modal" title="Subscrever Prime" data-bs-target="#frmPrime" size="100%">Registar</button>
                      <button onclick="lg.logout()" type="button" class="btn btn-warning logout btn-block">Logout</button>  
                      <button type="button" class="btn btn-warning btn-block" data-bs-dismiss="modal">Fechar</button>
                    </div>
                  </div>
                  <!-- /.row -->
    
                </div>
              </div>
            </div>
            <div class="col-md-4"></div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <!-- footer-->

  <footer class="text-muted py-5 bg-dark">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-12 text-center py-5">
          <p>Copyright ©
            <ddff id="dthh"></ddff>
            <script>
              document.getElementById("dthh").innerHTML = new Date().getFullYear();
            </script> All rights reserved | This template is made with
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill text-warning" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
</svg> by <a href="http://alfzone.com" target="_blank">alf</a></p>
        </div>
      </div>
    </div>
  </footer>


      
  <div id="overlay">
    <div id="overlay-content">
     Subscreva um conta Prime de borla. <br>Só precisa de um email Gmail.<br><br>
      <button onclick="" type="button" class="btn btn-primary logPrime" data-bs-toggle="modal" title="Subscrever Prime" data-bs-target="#frmPrime">Subscrever Prime</button>
    </div>
  </div>




<script src="https://code.jquery.com/jquery-1.8.0.min.js"></script>
<script src="/templates/bootstrapT/assets/js/bootstrap.bundle.min.js"></script>
<script src="/templates/bootstrapT/assets/js/input-spinner.js"></script>
<script src="/js/code/config.js"></script>
<script src="/js/code/loginGoogle.js?227"></script>
<script src="https://unpkg.com/jwt-decode/build/jwt-decode.js"></script>
<!--script src="/js/alertImproved/alertI.js"></script>
<script src="/js/code/bootstrapT.js"></script>
<script src="/js/code/tmdb.js"></script>
<script src="/js/code/gestor.js"></script>
<script src="/js/code/programas.js"></script>
<script src="/js/code/valida.js"></script-->

<script>

  var lg=new loginGoogle();

  window.onload = function () {
  
  //var lg=new loginGoogle();
  
  lg.getAuthentication();
  
  google.accounts.id.initialize({
    client_id: "YOUR GOOGLE ID",
    callback: lg.handleCredentialResponse
  });
 google.accounts.id.renderButton(
  document.getElementById("buttonDiv"),   //name of the div within the button
  { theme: "filled_blue", size: "large"}  // customization attributes
 );
 google.accounts.id.prompt(); // also display the One Tap dialog
}
  
</script>

</body>

</html>
