/**
 * @author alf
 * @copyright 2024
 * @ver 3.1
 */

class login {
  constructor() {
    this.c = new Config();
  }

  //render the authenticated user information
  async renderAutentica() {
    //console.log("ver");
    const response = await fetch(this.c.urlRenderAutentication);
    const lv = await response.json();
    for (const v of lv) {
      if (v.user !== null) {
        //document.getElementById("photo").setAttribute("src", v.foto);
        document.getElementById("name").innerHTML = v.nome;
      } else {
        window.location.href = this.c.urlLogin;
      }
    }
  }

  //render the authenticated user information
  async renderAutenticaWithTypeAdmin() {
    //console.log("ver");
    const response = await fetch(this.c.urlRenderAutentication);
    const lv = await response.json();
    for (const v of lv) {
      if (v.user !== null) {
        document.getElementById("photo").setAttribute("src", v.foto);
        document.getElementById("name").innerHTML = v.nome;
        //console.log(v.level);
        if (v.level < 9999) {
          //hide admin menu
          document.getElementById("menu-admin").style.display = "none";
        }
        if (v.level == 0) {
          //hide DC menu
          document.getElementById("dc-menu").style.display = "none";
        }
      }
    }
  }

  //check if there is an authenticated user
  async verificaAutentica() {
    const response = await fetch(this.c.urlRenderAutentication);
    const lv = await response.json();
    for (const v of lv) {
      if (v.user !== null) {
        //dfdsfdsfsdf
      } else {
        window.location.href = this.c.urlLogin;
      }
    }
  }

  async logout() {
    const response = await fetch(this.c.urlLogout);
    window.location.href = this.c.urlLogin;
  }
}

window.onload = function () {
  var c = new Config();
  var lg = new login();

  lg.renderAutentica();
};
