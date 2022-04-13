const _SERVIDORaut=  window.location.protocol + "//" + window.location.host + "/";

//console.log(_SERVIDORaut)

//####################################### Autenticação ######################################################


const verificaAutentica = async () => {

  //console.log("ver");
  //alert("ddd");
  const response = await fetch(_SERVIDORaut +`public/autenticacao/getAutentication`)
  const lv = await response.json()
  for (const v of lv) {
    if (v.user!== null){
       //document.getElementById("foto").setAttribute("src", v.foto)
       //document.getElementById("nome").innerHTML =v.nome
    }else{
      window.location.href = "/public/?d=k";
    }
    
  }
 
}

const renderAutentica = async () => {

  //console.log("ver");
  const response = await fetch(_SERVIDORaut +`public/autenticacao/getAutentication`)
  const lv = await response.json()
  for (const v of lv) {
    if (v.user!== null){
       document.getElementById("foto").setAttribute("src", v.foto)
       document.getElementById("nome").innerHTML =v.nome
    }else{
      window.location.href = "/public/?d=k";
    }
    
  }
 
}


//####################################### Autenticação ######################################################

function logout(){
  $.get( _SERVIDORaut +`public/autenticacao/logout`, function( data ) {
  window.location.href = "/public/?d=k";
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

