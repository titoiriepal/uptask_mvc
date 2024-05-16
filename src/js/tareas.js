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



        mostrarTareas();
        opcionesTareas();

        
    } catch (error) {
        console.log(error);
    }
    
}



function mostrarTareas(){
    limpiarTareas();
    const listadoTareas = document.querySelector('#listado-tareas');
    

    if(tareas.length === 0){
        const textoNotareas = document.createElement('LI');
        textoNotareas.textContent = 'No hay tareas';
        textoNotareas.classList.add('no-tareas');
        listadoTareas.appendChild(textoNotareas);
        return;
    }

    const options = document.querySelectorAll('input[name="option-dashboard"]');
    let printValues = '';

    //Leemos el valor seleccionado para mostrar
    options.forEach(option => {
        if(option.checked){
            printValues = option.value;
        }
    });

    tareas.forEach(tarea => {

        if(printValues === 'inactivas' && tarea.activo === '0'){//Si hemos seleccionado borradas y la tarea no está activa
            imprimirTarea(tarea);
        }else if(printValues === 'activas' && tarea.activo === '1'){//Si hemos seleccionado activas y la tarea está activa
            imprimirTarea(tarea);
        }else if(printValues === 'todas'){//Si hemos seleccionado todas
            imprimirTarea(tarea);
        }

    });
}

function imprimirTarea(tarea){
    const estados = {
        0 : 'Pendiente',
        1 : 'Completa'

    }
    const contenedorTarea = document.createElement('LI');
        contenedorTarea.dataset.tareaId = tarea.id;
        contenedorTarea.classList.add('tarea');


        const nombreTarea = document.createElement('P');
        nombreTarea.textContent = tarea.nombre;

        const opcionesDiv = document.createElement('DIV');
        opcionesDiv.classList.add('opciones');

        //BOTONES

        if(tarea.activo === '1'){ //Solo imprimimos las opciones de la tarea si está activa
            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add('boton-opciones');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
            btnEstadoTarea.textContent = estados[tarea.estado];
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function() {
                cambiarEstadoTarea({...tarea});
            }

            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.classList.add('boton-opciones');
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.dataset.idTarea = tarea.id;

            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

        }else{
            const btnActivarTarea = document.createElement('BUTTON');
            btnActivarTarea.classList.add('activar-tarea');
            btnActivarTarea.classList.add('boton-opciones');
            btnActivarTarea.textContent = 'Activar';
            btnActivarTarea.dataset.activoTarea = tarea.activo;
            btnActivarTarea.ondblclick = function() {
                cambiarActivoTarea({...tarea});
            }

            opcionesDiv.appendChild(btnActivarTarea);
        }

        

        contenedorTarea.appendChild(nombreTarea);
        contenedorTarea.appendChild(opcionesDiv);

        const listadoTareas = document.querySelector('#listado-tareas');

        listadoTareas.appendChild(contenedorTarea);
}

function opcionesTareas(){
    const options = document.querySelectorAll('input[name="option-dashboard"]');
    options.forEach(option => {
        option.addEventListener("input", ()=>{
            mostrarTareas();
        });
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
    referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);//Inserta la alerta despues del Legend
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

            mostrarTareas();
        }
    } catch (error) {
        console.log(error);
    }
}

function cambiarEstadoTarea(tarea){
    const nuevoEstado = tarea.estado === "1" ? "0" : "1"; //Si el estado es 1, lo cambiamos a 0, si es 0, lo cambiamos a 1.
    tarea.estado = nuevoEstado

    actualizarTarea(tarea);

}

function cambiarActivoTarea(tarea){
    tarea.activo = "1";

    actualizarTarea(tarea);
}

async function actualizarTarea(tarea){

    const{estado, id, nombre, proyectoId, activo} = tarea;
    const datos = new FormData();
    datos.append('id', id);
    datos.append('nombre', nombre);
    datos.append('estado', estado);
    datos.append('proyectoId', proyectoId);
    datos.append('activo', activo);
    datos.append('url',obtenerProyecto());

    try {
        const url = `${location.origin}/api/tarea/actualizar`;
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();
        if(resultado.tipo === 'exito'){
            //Actualizamos el DOM y mostramos los registros actualizados
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.contenedor-nueva-tarea'));
            
            tareas = tareas.map(tareaMemoria => {
                if(tareaMemoria.id === resultado.tarea.id){
                    tareaMemoria.estado = resultado.tarea.estado;
                    tareaMemoria.activo = resultado.tarea.activo;
                    
                }
                return tareaMemoria;
            });

            mostrarTareas();



            
        }else{
            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.contenedor-nueva-tarea'));
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