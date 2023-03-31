/**
 * @author alf
 * @copyright 2022
 * @ver 1.0
 */

class loginGoogle {

  constructor() {}

  handleCredentialResponse(response) {
    //console.log("Encoded JWT ID token: " + response.credential);
    const responsePayload = jwt_decode(response.credential);
    //console.log("Email: " + responsePayload.email);
    //console.log("email_verified: " + responsePayload.email_verified);
    //console.log("name: " + responsePayload.name);
    //console.log("picture: " + responsePayload.picture);


    if (responsePayload.email !== '') {
      var dados = {
        userName: responsePayload.name,
        userImageURL: responsePayload.picture,
        userEmail: responsePayload.email
      };
      $.post('/public/autenticacao/validacaoLogin', dados, function(retorna) {
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
}

window.onload = function() {
  var lg = new loginGoogle();
  google.accounts.id.initialize({
    client_id: "436091069232-6a0okqf1hkplhc13gtbt84c4j3sor18f.apps.googleusercontent.com",
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
