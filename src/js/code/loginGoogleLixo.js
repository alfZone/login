/**
 * @author alf
 * @copyright 2022
 * @ver 2.0
 */

 function decodeJwtResponse(token) {
  var base64Url = token.split('.')[1];
  var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
  var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function(c) {
    return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
  }).join(''));

    return JSON.parse(jsonPayload);
  }

class loginGoogle {

  constructor() {}

  handleCredentialResponse(response) {
    //console.log(response.credential);
    //console.log("Encoded JWT ID token: " + response.credential);
    const responsePayload = decodeJwtResponse(response.credential);
    //console.log("Email: " + responsePayload.email);
    //console.log("email_verified: " + responsePayload.email_verified);
    //console.log("name: " + responsePayload.name);
    //console.log("picture: " + responsePayload.picture);


    if (responsePayload.email !== '') {
      var dados = {
        userName: responsePayload.name,
        userImageURL: responsePayload.picture,
        userEmail: responsePayload.email,
        userToken: response.credential
      };
      $.post('/api/loginValidation', dados, function(retorna) {
        //alert(retorna);
        //console.log(retorna.localeCompare("erro"));
        //console.log(retorna);
        if (retorna == 0) {
          var msg = "O/A " + responsePayload.name + " não tem acesso ao sistema!";
          document.getElementById('msg').innerHTML = msg;
        } else {
          //alert ("não deu erro não não")
          //console.log(retorna);
          window.location.href = retorna
        }
      });
    } else {
      var msg = "Não está nenhuma utilizador autenticado";
      document.getElementById('msg').innerHTML = msg;
      var x = document.getElementsByClassName("logout");
      for (var i = 0; i < x.length; i++) {
        x[i].disabled = true;
      }
    }
  }

  renderAutentica = async () => {

    //console.log("ver");
    const response = await fetch(c.url +`public/autenticacao/getAutentication`)
    const lv = await response.json()
    for (const v of lv) {
      if (v.user!== null){
         document.getElementById("photo").setAttribute("src", v.foto)
         document.getElementById("name").innerHTML =v.nome
      }else{
        window.location.href = "error";
      }
      
    }
   
  }
}

window.onload = function() {
  var lg = new loginGoogle();
  google.accounts.id.initialize({
    client_id: "ID_GOOGLE_CLIENTE",
    callback: lg.handleCredentialResponse
  });
  google.accounts.id.renderButton(
    document.getElementById("buttonDiv"), //name of the div within the button
    {
      theme: "filled_blue",
      size: "large"
    } // customization attributes
  );
  google.accounts.id.prompt(); // also display the One Tap dialog
}
