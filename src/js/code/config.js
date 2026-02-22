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
    
    
     get urlRenderAutentication() {
       return  this.url + "PATH_TO_GET_AUTHETICATION";
     }
 
     get urlLogout() {
       return this.url + "PATH_TO_LOGOUT";
     }
 
     get urlLogin() {
       return this.url + "PATH_TO_LOGIN";
     }
  }
