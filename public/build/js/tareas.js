!function(){!async function(){const a=o();try{const o=`${location.origin}/api/tareas?url=${a}`,n=await fetch(o),r=await n.json();e=r.tareas,t()}catch(e){console.log(e)}}();let e=[];function t(){!function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}();const n=document.querySelector("#listado-tareas");if(0===e.length){const e=document.createElement("LI");return e.textContent="No hay tareas",e.classList.add("no-tareas"),void n.appendChild(e)}const r={0:"Pendiente",1:"Completa"};e.forEach((c=>{const d=document.createElement("LI");d.dataset.tareaId=c.id,d.classList.add("tarea");const s=document.createElement("P");s.textContent=c.nombre;const i=document.createElement("DIV");i.classList.add("opciones");const l=document.createElement("BUTTON");l.classList.add("estado-tarea"),l.classList.add("boton-opciones"),l.classList.add(`${r[c.estado].toLowerCase()}`),l.textContent=r[c.estado],l.dataset.estadoTarea=c.estado,l.ondblclick=function(){!function(n){const r="1"===n.estado?"0":"1";n.estado=r,async function(n){const{estado:r,id:c,nombre:d,proyectoId:s,activo:i}=n,l=new FormData;l.append("id",c),l.append("nombre",d),l.append("estado",r),l.append("proyectoId",s),l.append("activo",i),l.append("url",o());try{const o=`${location.origin}/api/tarea/actualizar`,n=await fetch(o,{method:"POST",body:l}),r=await n.json();"exito"===r.tipo?(a(r.mensaje,r.tipo,document.querySelector(".contenedor-nueva-tarea")),e=e.map((e=>(e.id===r.tarea.id&&(e.estado=r.tarea.estado),e))),t()):a(r.mensaje,r.tipo,document.querySelector(".contenedor-nueva-tarea"))}catch(e){console.log(e)}}(n)}({...c})};const u=document.createElement("BUTTON");u.classList.add("eliminar-tarea"),u.classList.add("boton-opciones"),u.textContent="Eliminar",u.dataset.idTarea=c.id,i.appendChild(l),i.appendChild(u),d.appendChild(s),d.appendChild(i),n.appendChild(d)}))}function a(e,t,a){const o=document.querySelector(".alerta");o&&o.remove();const n=document.createElement("DIV");n.classList.add("alerta",t),n.textContent=e,a.parentElement.insertBefore(n,a.nextElementSibling),setTimeout((()=>{n.remove()}),5e3)}function o(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).url}function n(){const e=document.querySelector(".modal");document.querySelector(".formulario").classList.add("cerrar"),setTimeout((()=>{e.remove()}),1e3)}document.querySelector("#agregar-tarea").addEventListener("click",(function(){const r=document.createElement("DIV");r.classList.add("modal"),r.innerHTML='\n    <form class="formulario nueva-tarea">\n    <legend>Añade una nueva tarea</legend>\n    <div class="campo">\n        <label for="tarea">Tarea</label>\n        <input \n            type="text"\n            name="tarea"\n            placeholder="Añadir tarea al proyecto actual"\n            id="tarea"\n        />\n    </div>\n    <div class="opciones">\n        <input type="submit" class="submit-nueva-tarea" value="Añadir tarea"/>\n        <button type="button" class="cerrar-modal">Cancelar</button>\n    </div>\n    </form>\n    ',setTimeout((()=>{document.querySelector(".formulario").classList.add("animar")}),0),r.addEventListener("click",(function(r){r.preventDefault(),r.target.classList.contains("cerrar-modal")&&n(),r.target.classList.contains("submit-nueva-tarea")&&function(){const r=document.querySelector("#tarea").value.trim();if(""===r)return void a("El nombre de la tarea es obligatorio","error",document.querySelector(".formulario legend"));if(r.length>60)return void a("La tarea no puede superar los 60 caracteres","error",document.querySelector(".formulario legend"));!async function(r){const c=new FormData;c.append("nombre",r),c.append("url",o());try{const o=`${location.origin}/api/tarea`,d=await fetch(o,{method:"POST",body:c}),s=await d.json();if(a(s.mensaje,s.tipo,document.querySelector(".formulario legend")),"exito"===s.tipo){setTimeout((()=>{n()}),2e3);const a={id:String(s.id),nombre:r,estado:0,proyectoId:s.proyectoId};e=[...e,a],t()}}catch(e){console.log(e)}}(r)}()})),document.querySelector(".dashboard").appendChild(r)}))}();