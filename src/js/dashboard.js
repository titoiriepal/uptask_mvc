let proyectos = [];
let proyectosActivos = [];
let proyectosInactivos = [];

document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
})


async function iniciarApp(){

    await traerProyectos();
    crearObjetosProyectos();
    mostrarProyectos(proyectosActivos);
    opcionesDashboard();


}

function mostrarProyectos(proyectos){
    const listado = document.querySelector('.listado-proyectos');
    listado.innerHTML='';
    proyectos.forEach((item)=>{
        const{ proyecto, url} = item;
        const listElement = document.createElement('li');
        listElement.classList.add('proyecto');
        listElement.innerHTML = `<a href="/proyecto?url=${url}">${proyecto}</a>`;
        listado.appendChild(listElement);
    });

}

async function traerProyectos(){
    const propietarioId = document.querySelector('#propietarioId');
    const datos = new FormData();
    datos.append('propietarioId', propietarioId.value);

    try {

        const url = `${location.origin}/api/proyectos`;//URL  de la API a consumir
        const resultado = await fetch(url,{
            method: 'POST',
            body:datos
        });
        proyectos = await resultado.json();
        
    } catch (error) {
        console.log(error);
    }
}

function crearObjetosProyectos(){
    proyectos.forEach(item =>{
        if(item.activo === '1'){
            proyectosActivos.push(item);
        }else{
            proyectosInactivos.push(item);
        }
    })

}


function opcionesDashboard(){
    const options = document.querySelectorAll('input[name="option-dashboard"]');
    options.forEach(option => {
        option.addEventListener("input", ()=>{
            if(option.value === 'inactivas'){
                mostrarProyectos(proyectosInactivos);
            }else if(option.value === 'activas'){
                mostrarProyectos(proyectosActivos);
            }else{
                mostrarProyectos(proyectos);
            }
        });
    });
}