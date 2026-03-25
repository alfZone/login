/**
 * @author alf
 * @copyright 2022
 * @ver 4.2
 * https://github.com/alfZone/htmlJS
 */

//var c = c || new Config();

function UrlExists(url) {
  var http = new XMLHttpRequest();
  http.open("HEAD", url, false);
  http.send();
  return http.status != 404;
}

// Função para atualizar a URL sem recarregar a página
function updateURL(value, parameter = "") {
  if (parameter == 0) {
    const newUrl = `${window.location.pathname}/${value}`;
    window.history.pushState({ value: value }, "", newUrl);
  } else {
    const newUrl = `${window.location.pathname}?${parameter}=${value}`;
    window.history.pushState({ value: value, parameter: parameter }, "", newUrl);
  }
}

function includeHTML(tag) {
  var z, i, elmnt, file, xhttp;
  /*loop through a collection of all HTML elements:*/
  z = document.getElementsByTagName("*");
  for (i = 0; i < z.length; i++) {
    elmnt = z[i];
    /*search for elements with a certain atrribute:*/
    file = elmnt.getAttribute(tag);
    if (file) {
      /*make an HTTP request using the attribute value as the file name:*/
      xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function () {
        if (this.readyState == 4) {
          if (this.status == 200) {
            elmnt.innerHTML = this.responseText;
          }
          if (this.status == 404) {
            elmnt.innerHTML = "Page not found.";
          }
          /*remove the attribute, and call this function once more:*/
          elmnt.removeAttribute(tag);
          includeHTML();
        }
      };
      xhttp.open("GET", file, true);
      xhttp.send();
      /*exit the function:*/
      return;
    }
  }
}

//reload a .js file
function load_js(scriptPath) {
  var head = document.getElementsByTagName("head")[0];
  var script = document.createElement("script");
  script.src = scriptPath;
  head.appendChild(script);
}

//get a url parameter indicate by param
function getURLParam(param) {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get(param);
}

//get a url parte indicate by position
function getURLPos(pos) {
  var queryString = window.location.pathname;
  queryString = queryString.split("/");
  //queryString=queryString[1].split("/")
  console.log(queryString[pos]);
  return queryString[pos];
}

//print a table
function printDiv(tableName) {
  window.frames["print_frame"].document.body.innerHTML = document.getElementById(tableName).innerHTML;
  window.frames["print_frame"].window.focus();
  window.frames["print_frame"].window.print();
}
