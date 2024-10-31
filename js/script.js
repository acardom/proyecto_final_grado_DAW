document.getElementById("btn_registro").addEventListener("click", register);
document.getElementById("btn_login").addEventListener("click", login);
window.addEventListener("resize", anchoPagina);

var container_login_register = document.querySelector(".container_login_register");
var formulario_login = document.querySelector(".formulario_login");
var formulario_register = document.querySelector(".formulario_register");
var container_back_register = document.querySelector(".container_back_register");
var container_back_login = document.querySelector(".container_back_login");

function register (){

    if(window.innerWidth > 850){
        formulario_register.style.display = "block";
        container_login_register.style.left = "410px";
        formulario_login.style.display = "none";
        container_back_register.style.opacity = "0";
        container_back_login.style.opacity = "1";
    }else{
        formulario_register.style.display = "block";
        container_login_register.style.left = "0px";
        formulario_login.style.display = "none";
        container_back_register.style.display = "none";
        container_back_login.style.display = "block";
        container_back_login.style.opacity = "1";
    }
    
}

function login (){

    if(window.innerWidth > 850){
        formulario_register.style.display = "none";
        container_login_register.style.left = "10px";
        formulario_login.style.display = "block";
        container_back_register.style.opacity = "1";
        container_back_login.style.opacity = "0";
    }else{
        formulario_register.style.display = "none";
        container_login_register.style.left = "0px";
        formulario_login.style.display = "block";
        container_back_register.style.display = "block";
        container_back_login.style.display = "none";
    }
    
}

function anchoPagina (){

    if(window.innerWidth > 850){
        container_back_login.style.display = "block";
        container_back_register.style.display = "block";
    }else{
        container_back_register.style.display = "block";
        container_back_register.style.opacity = "1";
        container_back_login.style.display = "none";
        formulario_login.style.display = "block";
        formulario_register.style.display = "none";
        container_login_register.style.left ="0px";
    }

}

anchoPagina();

