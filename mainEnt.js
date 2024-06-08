$(document).ready(function() {
    var formData = new FormData();
    var idMedalla, opcion;
    opcion = 2;
        
    tablaUsuarios = $('#tablaUsuarios').DataTable({  
        "ajax":{            
            "url": "crudEnt.php", 
            "method": 'POST', //usamos el metodo POST
            "data":{opcion:opcion}, //enviamos opcion 4 para que haga un SELECT
            "dataSrc":""
        },
        "columns":[
            {"data": "idHistorial"},
            {"data": "cantidadAceite"},
            {"data": "fecha"},
            {"data": "estado"}
        ]
    });     

    var fila; //captura la fila, para editar o eliminar
    //submit para el Alta y Actualización
    $('#formUsuarios').submit(function(e){                         
        e.preventDefault(); //evita el comportambiento normal del submit, es decir, recarga total de la página

        var cantidad = $.trim($('#cantidad').val());    
        formData.append('cantidad', cantidad);         
        formData.append('opcion', opcion); 

            $.ajax({
              url: "crudEnt.php",
              type: "POST",
              data: formData,
              processData: false,
              contentType: false, 
              success: function(data) {
                tablaUsuarios.ajax.reload(null, false);
                window.alert("Se registro tú entrega");
                console.log(data);
               }
            });			        
        $('#modalCRUD').modal('hide');							     			
    });
     
    
    //para limpiar los campos antes de dar de Alta una Persona
    $("#btnNuevo").click(function(){
        opcion = 1; //alta           
        idMedalla=null;
        $("#formUsuarios").trigger("reset");
        $(".modal-header").css( "background-color", "#17a2b8");
        $(".modal-header").css( "color", "white" );
        $(".modal-title").text("Agregar nueva entrega");
        $('#modalCRUD').modal('show');	    
    });     
});    