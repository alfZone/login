/**
 * @author alf
 * @copyright 2024
 * @ver 3.1
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

  constructor() {
    this.c=new Config();
  }

//render the authenticated user information
async renderAutentica(){

  //console.log("ver");
  const response = await fetch(this.c.urlRenderAutentication)
  const lv = await response.json()
  for (const v of lv) {
    if (v.user!== null){
       document.getElementById("photo").setAttribute("src", v.foto)
       document.getElementById("name").innerHTML =v.nome
    }else{
      window.location.href = this.c.urlLogin;
    }
    
  }
 
}

//check if there is an authenticated user
async verificaAutentica(){

  const response = await fetch(this.c.urlRenderAutentication)
  const lv = await response.json()
  for (const v of lv) {
    if (v.user!== null){
      //dfdsfdsfsdf
    }else{
      window.location.href = this.c.urlLogin;
    }
    
  }
 
}

async logout(){

    const response = await fetch(this.c.urlLogout);
    window.location.href = this.c.urlLogin;
 
}

}

window.onload = function() {
  var c= new config();
  var lg = new loginGoogle();

  lg.renderAutentica();
}
