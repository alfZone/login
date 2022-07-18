/**
 * @author alf
 * @copyright 2022
 * @ver 2.0
 */

var c = c || new config();

//console.log(_SERVIDORaut)

//####################################### Autenticação ######################################################


const verificaAutentica = async () => {

  //console.log("ver");
  //alert("ddd");
  const response = await fetch(c.url  +`public/autenticacao/getAutentication`)
  const lv = await response.json()
  for (const v of lv) {
    if (v.user!== null){
       //document.getElementById("photo").setAttribute("src", v.foto)
       //document.getElementById("name").innerHTML =v.nome
    }else{
      window.location.href = "/error.html";
    }
    
  }
 
}

const renderAutentica = async () => {

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


//####################################### Autenticação ######################################################

function logout(){
  $.get( c.url +`public/autenticacao/logout`, function( data ) {
  window.location.href = "/notlogged.html";
  //$( ".result" ).html( data );
  //alert( "Load was performed." );
});
  
}


function signOut() {
    var auth2 = gapi.auth2.getAuthInstance();
    auth2.signOut().then(function () {
      console.log('User signed out.');
    });
    gapi.auth2.getAuthInstance().disconnect();
  }

