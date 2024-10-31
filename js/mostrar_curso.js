function mostrarNuevoCurso() {

    document.querySelector(".curso_mas").style.display = "none";
    document.querySelector(".nuevo_curso").style.display = "block";

    
}

function mostrarNuevoarchivo() {

    document.querySelector(".archivo_mas").style.display = "none";
    document.querySelector(".nuevo_archivo").style.display = "block";

    
}


function mostrarreal() {

    document.querySelector(".contraseña_puntos").style.display = "none";
    document.querySelector(".contraseña_real").style.display = "block";

    document.querySelector(".tachado").style.display = "none";
    document.querySelector(".mostrar").style.display = "block";


}

function mostraroculta() {

    document.querySelector(".contraseña_puntos").style.display = "block";
    document.querySelector(".contraseña_real").style.display = "none";

    document.querySelector(".mostrar").style.display = "none";
    document.querySelector(".tachado").style.display = "block";


}

