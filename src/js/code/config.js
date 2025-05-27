/**
 * @author alf
 * @copyright 2022
 * @ver 2.0
 */


 class Config {
  
  
    constructor() {
    }
    
    
    color(pos){
      let cores= ["bg-info", "bg-primary", "bg-success","bg-warning", "bg-danger", "bg-black","bg-indigo", "bg-fuchsia", "bg-navy","bg-info", "bg-primary", "bg-success","bg-warning", "bg-danger", "bg-black","bg-indigo", "bg-fuchsia", "bg-navy"];  
      if (pos>cores.length){
        pos=0;
      }
      return cores[pos];
    }
    
    
    get url() {
      return  window.location.protocol + "//" + window.location.host + "/";
    }

    get urlRenderAutentication() {
      return  this.url() + "/public/autenticacao/getAutentication";
    }
  }
