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
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('cerrar');
            setTimeout(()=>{
                
                modal.remove();
            },1000)
            
        }
    })


    document.querySelector('body').appendChild(modal);
}

})();