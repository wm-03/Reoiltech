$(document).ready(function() {
    var formData = new FormData();
    var opcion = 1;
        
    tablaUsuarios = $('#tablaUsuarios').DataTable({  
        "ajax": {            
            "url": "crudCr.php", 
            "method": 'POST',
            "data": { opcion: opcion },
            "dataSrc": ""
        },
        "columns": [
            { "data": "cantidadAceite" },
            { "data": "fecha" },
            { "data": "nombreUsuario" }
        ]
    });     
});
