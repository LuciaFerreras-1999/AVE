@extends('layouts.plantilla')

@section('titulo', 'Gestión de Usuarios')

@section('contenido')
<div class="w-full mx-auto my-8 px-4 bg-white p-6 shadow rounded">

    <button class="btn btn-accent mb-3" id="crearUsuario">Crear Usuario</button>
    <div class="table-responsive">
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Strikes</th>
                    <th>Bloqueado</th>
                    <th>Tipo Usuario</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="ajaxModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog">
        <form id="formUsuario" name="formUsuario" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Crear Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="usuario_id" id="usuario_id" />
                <div class="mb-3">
                    <label for="name" class="form-label">Nombre</label>
                    <input type="text" name="name" id="name" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required />
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Contraseña</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Cambiar">
                </div>
                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirma la nueva contraseña">
                </div>

                <div class="mb-3">
                    <label for="bloqueado" class="form-label">Bloqueado</label>
                    <select name="bloqueado" id="bloqueado" class="form-select">
                        <option value="0">No</option>
                        <option value="1">Sí</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-accent" id="guardarBtn">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

<script>
    $(function() {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        let table = $(".data-table").DataTable({
            language: {
                decimal: ",",
                processing: "Procesando...",
                search: "Buscar",
                lengthMenu: "Mostrar _MENU_ usuarios por página",
                info: "Mostrando _START_ a _END_ de _TOTAL_ usuarios",
                infoEmpty: "Mostrando 0 a 0 de 0 usuarios",
                infoFiltered: "(filtrado de _MAX_ usuarios totales)",
                paginate: {
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Último"
                }
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('usuarios-ajax-crud.index') }}",
            columns: [{
                    data: "id"
                },
                {
                    data: "name"
                },
                {
                    data: "email"
                },
                {
                    data: "strikes_count"
                },
                {
                    data: "bloqueado",
                    render: (d) => (d ? "Sí" : "No"),
                },
                {
                    data: "tipo_usuario"
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `
                    <a href="/perfil/${row.name}" class="btn btn-view me-1">Ver</a>
                    <button class="btn btn-edit editUser" data-id="${row.id}">Editar</button>
                    <button class="btn btn-danger deleteUser" data-id="${row.id}">Eliminar</button>
                `;
                    }
                },
            ],
        });

        $("#crearUsuario").click(function() {
            $("#formUsuario").trigger("reset");
            $("#usuario_id").val("");
            $("#modalTitulo").html("Crear Usuario");
            $("#ajaxModal").modal("show");
        });

        $("body").on("click", ".editUser", function() {
            let id = $(this).data("id");
            $.get("{{ route('usuarios-ajax-crud.index') }}/" + id + "/edit", function(data) {
                $("#modalTitulo").html("Editar Usuario");
                $("#usuario_id").val(data.id);
                $("#name").val(data.name);
                $("#email").val(data.email);
                $("#bloqueado").val(data.bloqueado ? 1 : 0);
                $("#password").val('');
                $("#password_confirmation").val('');
                $("#ajaxModal").modal("show");
            });
        });


        $("#formUsuario").submit(function(e) {
            e.preventDefault();
            let formData = $(this).serialize();
            let id = $("#usuario_id").val();
            let url = id ?
                "{{ route('usuarios-ajax-crud.index') }}/" + id :
                "{{ route('usuarios-ajax-crud.store') }}";
            let type = id ? "PUT" : "POST";

            $.ajax({
                type: type,
                url: url,
                data: formData,
                success: function() {
                    $("#ajaxModal").modal("hide");
                    table.draw();
                },
                error: function(data) {
                    alert("Error al guardar");
                    console.log(data.responseJSON);
                },
            });
        });

        $("body").on("click", ".deleteUser", function() {
            if (!confirm("¿Estás seguro?")) return;

            let id = $(this).data("id");
            $.ajax({
                type: "DELETE",
                url: "{{ route('usuarios-ajax-crud.index') }}/" + id,
                success: function() {
                    table.draw();
                },
                error: function() {
                    alert("Error al eliminar");
                },
            });
        });
    });
</script>
@endsection