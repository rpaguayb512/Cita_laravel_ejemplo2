
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
    </head>
    <body >

<table>
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
        <td data-hora="{{ $hora }}" class="hour-row">{{ $hora }}</td>
        <td class="events">
                @foreach($appointments as $appointment)
                    @if($appointment->hora == $hora )
                        {{ $appointment->paciente->nombre}}
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


 </body>
</html>



<script type="text/javascript">
      // Cuando se hace clic en un campo de hora
      $('td[data-hora]').on('click', function() {
        var hora = $(this).data('hora');
        var pacienteCell = $(this).siblings('[data-paciente]');
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
             +'<td> <button onclick="obtenerValor(this)">Asignar Cita</button></td>'
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
      });




        function obtenerValor(button) {
          var inputElement = button.parentNode.parentNode.querySelector('input');
          var valorInput = inputElement.value;
          //alert(valorInput);
           var inputElement = document.getElementById('pacienteIdInput');
           var valor = valorInput;
          inputElement.value = valor;
          asignarCita();
        }


        // Cuando se hace ejecuta la funcion "asignar cita" en el modal se ejcuta obtenerValor y este ejecuta esta funcion
       function asignarCita(){
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
              alert(response.message);
              //$('#modal').modal('hide');
              // Recargar la página para eliminar la caché
                setTimeout(function() { location.reload();}, 5000);
             
            },
            error: function(xhr, status, error) {
              console.log(error); // Manejar el error de manera apropiada
              setTimeout(function() { location.reload();}, 5000);

            }
          });
        }

</script>
