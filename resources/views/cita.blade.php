
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>How To Add Bootstrap 5 Modal Popup In Laravel 9 - Websolutionstuff</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
<link rel="stylesheet" href="{{ asset('css/calendar.css') }}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- CDN para SweetAlert 2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>


<style>

  
  /* styles.css */
table {
  border-collapse: collapse;
}

td {
  border: 1px solid #ccc;
  padding: 10px;
}

 .menu-citas {
    display: none;
    position: absolute;
    background-color: #f9f9f9;
    border: 1px solid #ccc;
    padding: 8px;
  }
  .menu-citas ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
  }
  .menu-citas li {
    margin-bottom: 4px;
  }


  body {
  font-family: Arial, sans-serif;
}

table {
  border-collapse: collapse;
  width: 50%;
  margin: 20px auto;
}

th, td {
  border: 1px solid black;
  padding: 8px;
  text-align: center;
}

th {
  background-color: #f2f2f2;
}

.menu-asignar {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-radius: 4px;
  padding: 8px;
  z-index: 1;
}

.menu-asignar ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.menu-item {
  cursor: pointer;
  padding: 8px 12px;
}

.menu-item:hover {
  background-color: #f2f2f2;
}


</style>

    </head>
    <body >

<table id="tabla-citas">
  <thead>
    <tr>
      <th>Hora</th>
      <th>Asignada</th>
    </tr>
  </thead>
  <tbody>
       @php
        $hora = '08:00:00';
         $appointments = App\Models\Cita::all();
        $appointmentIndex = 0;
       @endphp

     @for($i = 0; $i < 8; $i++)
      <tr>
        <td   id="hora" data-hora="{{ $hora }}" class="hour-row">{{ $hora }}</td>
       <td class="events" data-id="{{$i}}">
                @foreach($appointments as $appointment)
                    @if($appointment->hora == $hora )
                        <span id="{{ $appointment->paciente->id }}"> {{ $appointment->paciente->nombre}}</span>
                    @endif
                @endforeach
            </td>
      </tr>
       @php
        // Incrementar la hora
        $hora = date('H:i:s', strtotime($hora . ' + 1 hour'));
        @endphp
      @endfor  
  </tbody>
</table>




<div class="modal fade" id="modal" data-hora="" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabel">Pacientes disponibles</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body" id="modal-body">
        <!-- Aquí se mostrarán los nombres de los pacientes disponibles -->

      </div>
      <div class="modal-footer">
        <input type="hidden" id="pacienteIdInput" class="form-control">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
  </div>



 <!-- Menú desplegable para asignar cita -->
  <div id="menu-asignar" class="menu-asignar">
    <ul>
      <li class="menu-item" data-action="asignar">Asignar cita</li>
      <li class="menu-item" data-action="eliminar">Eliminar</li>
    </ul>
  </div>



 </body>
</html>



<script type="text/javascript">
  let idSeleccionada = null;
  
  document.addEventListener("DOMContentLoaded", function() {
  const tabla = document.getElementById("tabla-citas");
  const tbody = tabla.getElementsByTagName("tbody")[0];
  const menuAsignar = document.getElementById("menu-asignar");
   let dataId = null;
 
 
 // Agregar evento click a las celdas de pacientes
  const pacientesCells = document.querySelectorAll(".events");
  pacientesCells.forEach((cell) => {
    cell.addEventListener("contextmenu", function(event) {
      event.preventDefault(); // Evita que aparezca el menú contextual del navegador
      event.stopPropagation();
      const pacienteSeleccionado = this.textContent;
      const horaSeleccionada = this.parentNode.querySelector("td[data-hora]").getAttribute("data-hora");
      const dataId = cell.getAttribute("data-id");
      //console.log("Valor del data-id:", dataId);
     
      // Mostrar el menú desplegable
      mostrarMenuAsignar(event.clientX, event.clientY, horaSeleccionada,dataId);
    });
  });


// Agregar evento click a las opciones del menú
  const menuItems = document.querySelectorAll(".menu-item");
  menuItems.forEach((item) => {
    item.addEventListener("click", function() {
      const horaSeleccionada = menuAsignar.getAttribute("data-hora");
       const idSeleccionada = menuAsignar.getAttribute("data-id");
       //alert(idSeleccionada);
      const accion = this.getAttribute("data-action");
      // Aquí puedes realizar acciones adicionales con la hora y la acción seleccionadas
      // Por ejemplo, llamar a la función para mostrar el modal con la lista de pacientes
      if (accion === "asignar") {
        var hora = horaSeleccionada;
        var pacienteCell = $(this).siblings('[data-paciente]');
         var dataId = idSeleccionada;
        //alert(dataId);
        // Realizar una solicitud AJAX para obtener los pacientes disponibles para la hora seleccionada
        $.ajax({
          url: "{{ route('pacientes.disponibles') }}"+'/'+hora,
          type: 'GET',
          success: function(response) {
            // Mostrar el modal con los nombres de los pacientes disponibles
            var modalBody = '';
            response.forEach(function(paciente) {

             modalBody +=  
             '<table style="border:1px">'
             +'<tr>'
             + '<td>'
             +'<input type="hidden" id="pacienteId" class="paciente_id" value='+ paciente.id +'>'+ paciente.id + '</>'
             +'</td>' 
             +'<td>' + paciente.nombre + '</td>'
             +'<td> <button data-id="' + dataId + '"  data-paciente-nombre="' + paciente.nombre + '" onclick="obtenerValor(this)">Asignar Cita</button></td>'
             +'</tr>'
             +'</table>';

              // Pasar la hora al atributo data-hora del modal
                var modal = document.getElementById('modal');
                modal.setAttribute('data-hora', hora);

            });
            $('#modal-body').html(modalBody);
            $('#modal').modal('show');
                    
          },
          error: function(xhr, status, error) {
            console.log(error); // Manejar el error de manera apropiada
          }
        });

      } else if (accion === "eliminar") {
        // Lógica para eliminar la cita
       var hora = horaSeleccionada;
       var dataId = idSeleccionada;
       //alert(dataId);
        confirm("Desea eliminar la cita "+hora+"!");

        $.ajax({
            type: "get",
            url: "{{ route('eliminar.cita') }}"+'/'+hora,
            data: {
              "_token": "{{ csrf_token() }}",
             "id": hora
            },
            success: function (data) {
                // Recargar TD la página para eliminar la caché  
                eliminarTdPorDataId(dataId)     
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
      }

      // Ocultar el menú desplegable
      ocultarMenuAsignar();
    });
  });

 // Función para mostrar el menú desplegable "Asignar cita"
            function mostrarMenuAsignar(x, y, horaSeleccionada,dataId) {
              menuAsignar.style.display = "block";
              menuAsignar.style.top = y + "px";
              menuAsignar.style.left = x + "px";
              menuAsignar.setAttribute("data-hora", horaSeleccionada);
              menuAsignar.setAttribute("data-id", dataId);
            }

            // Función para ocultar el menú desplegable
            function ocultarMenuAsignar() {
              menuAsignar.style.display = "none";
            }

             // Agregar evento click al documento para ocultar el menú desplegable
            document.addEventListener("click", function(event) {
              const target = event.target;
              if (!menuAsignar.contains(target)) {
                ocultarMenuAsignar();
              }
            });

            // Función para ocultar el menú desplegable
            function ocultarMenuAsignar() {
              menuAsignar.style.display = "none";
            }

          });


        function obtenerValor(button) {
          var inputElement = button.parentNode.parentNode.querySelector('input');
          var valorInput = inputElement.value;
          //alert(valorInput);
           var inputElement = document.getElementById('pacienteIdInput');
           var valor = valorInput;
          inputElement.value = valor;
          // Obtener el valor de dataId del input oculto
           var dataId = button.getAttribute('data-id'); 
           //alert(dataId);
           // Puedes utilizar el valor de dataId aquí
             var nombrePaciente = button.getAttribute('data-paciente-nombre');
           //alert(nombrePaciente);
          asignarCita(dataId,nombrePaciente);
        }


// Cuando se hace ejecuta la funcion "asignar cita" en el modal se ejcuta obtenerValor y este ejecuta esta funcion
      
       function asignarCita(dataId,Nombre){
          var hora = $('#modal').data('hora');
          var pacienteId = $('#pacienteIdInput').val();
          
          // Realizar una solicitud AJAX para asignar la cita
          $.ajax({
            url: "{{ route('asignar.cita') }}",
            type: 'POST',
            data: {
              hora: hora,
              paciente_id: pacienteId,
              "_token": "{{ csrf_token() }}",
            },
            success: function(response) {
              
              $('#modal').modal('hide');
              // Recargar la página para eliminar la caché
              // setTimeout(function() { location.reload();}, 0);
              //alert(response.message);
              // Verificar si la respuesta es un array o convertirla a un array
              const pacientes = Array.isArray(response) ? response : [response];
               pacientes.forEach(function(paciente) {
              const tdALimpiar = document.querySelector(`.events[data-id="${dataId}"]`);
              tdALimpiar.textContent = Nombre;
           });

                // Mostrar un mensaje de éxito con SweetAlert 2
                Swal.fire({
                  icon: 'success',
                  title: 'Cita asignada correctamente',
                  text: response.message,
                });   
            },
            error: function(xhr, status, error) {
              console.log(error); // Manejar el error de manera apropiada
              //setTimeout(function() { location.reload();}, 5000);

              // Mostrar el mensaje de error con SweetAlert 2
                Swal.fire({
                  icon: 'error',
                  title: 'Ya existe una cita asignada a esa hora',
                  text: error,
                });


            }
          });
        }


        // Función para eliminar un <td> específico por su data-id
                function eliminarTdPorDataId(dataId) {
                   // Buscar el td con el data-id correspondiente
              const tdALimpiar = document.querySelector(`.events[data-id="${dataId}"]`);
              
              // Verificar si se encontró el td con el data-id
              if (tdALimpiar) {
                // Limpiar el contenido del td encontrado
                tdALimpiar.textContent = ""; // Esto borra el contenido del td
                console.log("Se ha limpiado el contenido del td con el data-id:", dataId);
              } else {
                console.log("No se encontró ningún td con el data-id:", dataId);
              }
            }

</script>
