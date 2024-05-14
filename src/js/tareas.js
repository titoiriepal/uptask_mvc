(function(){
    // Botón para mostrar el Modal de Agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', mostrarFormulario);





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
        console.log(resultado);

        mostrarAlerta(resultado.mensaje, resultado.tipo,document.querySelector('.formulario legend'));
        if(resultado.tipo === 'exito'){
            
            setTimeout(()=>{
        
                cerrarModal();
            },2000)
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

})();