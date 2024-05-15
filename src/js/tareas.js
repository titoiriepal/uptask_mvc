(function(){

    obtenerTareas();
    let tareas = [];

    // Botón para mostrar el Modal de Agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);


async function obtenerTareas(){
    const proyectoUrl = obtenerProyecto();
    try {
        const url = `${location.origin}/api/tareas?url=${proyectoUrl}`;
        const respuesta = await fetch(url);

        const resultado = await respuesta.json();
        tareas = resultado.tareas;

        mostrarTareas(tareas);

        
    } catch (error) {
        console.log(error);
    }
    
}

function mostrarTareas(tareas){
    limpiarTareas();
    const listadoTareas = document.querySelector('#listado-tareas');
    

    if(tareas.length === 0){
        const textoNotareas = document.createElement('LI');
        textoNotareas.textContent = 'No hay tareas';
        textoNotareas.classList.add('no-tareas');
        listadoTareas.appendChild(textoNotareas);
        return;
    }

    const estados = {
        0 : 'Pendiente',
        1 : 'Completa'

    }

    tareas.forEach(tarea => {
        const contenedorTarea = document.createElement('LI');
        contenedorTarea.dataset.tareaId = tarea.id;
        contenedorTarea.classList.add('tarea');


        const nombreTarea = document.createElement('P');
        nombreTarea.textContent = tarea.nombre;

        const opcionesDiv = document.createElement('DIV');
        opcionesDiv.classList.add('opciones');

        //BOTONES

        const btnEstadoTarea = document.createElement('BUTTON');
        btnEstadoTarea.classList.add('estado-tarea');
        btnEstadoTarea.classList.add('boton-opciones');
        btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
        btnEstadoTarea.textContent = estados[tarea.estado];
        btnEstadoTarea.dataset.estadoTarea = tarea.estado;

        const btnEliminarTarea = document.createElement('BUTTON');
        btnEliminarTarea.classList.add('eliminar-tarea');
        btnEliminarTarea.classList.add('boton-opciones');
        btnEliminarTarea.textContent = 'Eliminar';
        btnEliminarTarea.dataset.idTarea = tarea.id;

        opcionesDiv.appendChild(btnEstadoTarea);
        opcionesDiv.appendChild(btnEliminarTarea);

        contenedorTarea.appendChild(nombreTarea);
        contenedorTarea.appendChild(opcionesDiv);

        listadoTareas.appendChild(contenedorTarea);

    });
}


function mostrarFormulario(){
    const modal = document.createElement('DIV');
    modal.classList.add('modal');

    modal.innerHTML = `
    <form class="formulario nueva-tarea">
    <legend>Añade una nueva tarea</legend>
    <div class="campo">
        <label for="tarea">Tarea</label>
        <input 
            type="text"
            name="tarea"
            placeholder="Añadir tarea al proyecto actual"
            id="tarea"
        />
    </div>
    <div class="opciones">
        <input type="submit" class="submit-nueva-tarea" value="Añadir tarea"/>
        <button type="button" class="cerrar-modal">Cancelar</button>
    </div>
    </form>
    `;


    setTimeout(()=>{
        const formulario = document.querySelector('.formulario');
        formulario.classList.add('animar');
    },0);

    modal.addEventListener('click', function(e){
        e.preventDefault();
        if(e.target.classList.contains('cerrar-modal')){
            cerrarModal();
            
        }

        if(e.target.classList.contains('submit-nueva-tarea')){
            submitFormularioNuevaTarea();
        }
    })


    document.querySelector('.dashboard').appendChild(modal);
}

function submitFormularioNuevaTarea(){
    const tarea = document.querySelector('#tarea').value.trim();

    if(tarea === ""){
        //Mostrar alerta de error
        mostrarAlerta('El nombre de la tarea es obligatorio', 'error',document.querySelector('.formulario legend'));
        return;
    }
    if(tarea.length > 60){
        //Mostrar alerta de error
        mostrarAlerta('La tarea no puede superar los 60 caracteres', 'error',document.querySelector('.formulario legend'));
        return;
    }
    agregarTarea(tarea);

}

//Muestra un mensaje de error en la interfaz
function mostrarAlerta(mensaje, tipo, referencia){

    //previene la creación de múltiples alertas
    const alertaPrevia = document.querySelector('.alerta')
    if(alertaPrevia){
        alertaPrevia.remove();
    }

    const alerta = document.createElement('DIV');
    alerta.classList.add('alerta',tipo);
    alerta.textContent = mensaje;
    referencia.insertBefore(alerta, referencia.nextElementSibiling);//Inserta la alerta despues del Legend
    setTimeout(() => {
        alerta.remove();
    }, 5000);
}

//Consultar el servidor para añadir una nueva tarea al proyecto actual
async function agregarTarea(tarea){
    //Construir la petición
    const datos = new FormData();
    datos.append('nombre', tarea);
    datos.append('url',obtenerProyecto())

    try {
        const url = `${location.origin}/api/tarea`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();

        mostrarAlerta(resultado.mensaje, resultado.tipo,document.querySelector('.formulario legend'));
        if(resultado.tipo === 'exito'){
            
            setTimeout(()=>{
        
                cerrarModal();
            },2000)

            //Agregar el objeto de tarea al global de tareas

            const tareaObj = {
                id: String(resultado.id),
                nombre: tarea,
                estado: 0,
                proyectoId: resultado.proyectoId
            }

            tareas = [...tareas, tareaObj]; //Tareas es igual al antiguo arreglo de tareas[...tareas] y le añadimos el nuevo objeto de tareas.

            mostrarTareas(tareas);
        }
    } catch (error) {
        console.log(error);
    }
}

function obtenerProyecto(){
    const proyectoParams = new URLSearchParams(window.location.search);
    const proyecto =Object.fromEntries(proyectoParams.entries());
    return proyecto.url;
}

function cerrarModal(){
    const modal = document.querySelector('.modal');
    const formulario = document.querySelector('.formulario');
    formulario.classList.add('cerrar');
    setTimeout(()=>{
        
        modal.remove();
    },1000)
}

function limpiarTareas(){
    const listadoTareas = document.querySelector('#listado-tareas');
    while(listadoTareas.firstChild){
        listadoTareas.removeChild(listadoTareas.firstChild);
    }
}

})();