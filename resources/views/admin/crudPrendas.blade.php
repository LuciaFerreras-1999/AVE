@extends('layouts.plantilla')

@section('titulo', 'Gestión de Prendas')

@section('contenido')
<div class="w-full mx-auto my-8 px-4 bg-white p-6 shadow rounded">

    <!--<button class="btn btn-accent mb-3" id="crearPrenda">Crear Prenda</button>-->
    <div class="table-responsive">
        <table class="table table-bordered data-table">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Usuario</th>
                    <th>Nombre</th>
                    <th>Categorías</th>
                    <th>Imagen</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="ajaxModal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formPrenda" name="formPrenda" class="modal-content" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="prenda_id" id="prenda_id" />
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitulo">Crear Prenda</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre *</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" required />
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label for="talla" class="form-label">Talla</label>
                    <select name="talla" id="talla" class="form-select">
                        <option value="">Seleccione talla</option>
                        @foreach(\App\Models\Prenda::TALLAS as $talla)
                        <option value="{{ $talla }}">{{ $talla }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="marca" class="form-label">Marca</label>
                    <input type="text" name="marca" id="marca" class="form-control" />
                </div>

                <div class="mb-3">
                    <label for="estado" class="form-label">Estado</label>
                    <select name="estado" id="estado" class="form-select">
                        <option value="">Seleccione estado</option>
                        <option value="nuevo">Nuevo</option>
                        <option value="usado">Usado</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label d-block mb-2">Categorías *</label>
                    <div class="row">
                        @foreach($categorias as $categoria)
                        <div class="col-12 col-sm-6 col-md-3 mb-2">
                            <div class="form-check">
                                <input type="checkbox" name="categorias[]" value="{{ $categoria->id }}"
                                    class="form-check-input categoria-checkbox" id="categoria-{{ $categoria->id }}">
                                <label class="form-check-label" for="categoria-{{ $categoria->id }}">
                                    {{ $categoria->nombre }}
                                </label>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <label for="imagen" class="form-label">Imagen</label>
                    <input type="file" id="imagen" name="imagen" class="form-control" accept="image/*" />
                    <div id="imagenPreview" class="mt-2" style="display: none;">
                        <p><strong>Imagen actual:</strong></p>
                        <img src="" id="imagenActual" class="img-fluid rounded" style="max-height: 150px;">
                        <small class="text-muted">Deja en blanco para mantener la imagen actual.</small>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" id="guardarBtn" class="btn btn-accent">Guardar</button>
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
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ prendas por página",
                info: "Mostrando _START_ a _END_ de _TOTAL_ prendas",
                infoEmpty: "Mostrando 0 a 0 de 0 prendas",
                infoFiltered: "(filtrado de _MAX_ prendas totales)",
                paginate: {
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Último",
                },
            },
            processing: true,
            serverSide: true,
            ajax: "{{ route('prendas-ajax-crud.index') }}",
            columns: [{
                    data: "id",
                    name: "id"
                },
                {
                    data: "user_nombre",
                    name: "user.nombre"
                },
                {
                    data: "nombre",
                    name: "nombre"
                },
                {
                    data: "categorias",
                    name: "categorias.nombre",
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "imagen",
                    name: "imagenes.url",
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return data ? `<img src="${data}" alt="imagen" height="50">` : "Sin imagen";
                    },
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
        });

        $("body").on("click", ".edit", function() {
            let prenda_id = $(this).data("id");

            $.get(`{{ route('prendas-ajax-crud.index') }}/${prenda_id}/edit`, function(data) {
                $("#modalTitulo").text("Editar Prenda");
                $("#ajaxModal").modal("show");
                $("#formPrenda")[0].reset();

                $("#prenda_id").val(data.id);
                $("#nombre").val(data.nombre);
                $("#descripcion").val(data.descripcion);
                $("#talla").val(data.talla);
                $("#marca").val(data.marca);
                $("#estado").val(data.estado);

                $(".categoria-checkbox").prop("checked", false);
                data.categorias_ids.forEach(function(id) {
                    $(`#categoria-${id}`).prop("checked", true);
                });

                if (data.imagen) {
                    $("#imagenPreview").show();
                    $("#imagenActual").attr("src", `/assets/imagenes/${encodeURIComponent(data.imagen)}`);
                } else {
                    $("#imagenPreview").hide();
                    $("#imagenActual").attr("src", "");
                }

                $("#imagen").val("");
            });
        });

        $("#formPrenda").on("submit", function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $("#guardarBtn").prop("disabled", true);

            $.ajax({
                type: "POST",
                url: "{{ route('prendas-ajax-crud.store') }}",
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#ajaxModal").modal("hide");
                    table.ajax.reload();
                    $("#guardarBtn").prop("disabled", false);
                    alert(response.success);
                },
                error: function(xhr) {
                    $("#guardarBtn").prop("disabled", false);
                    if (xhr.responseJSON && xhr.responseJSON.error) {
                        alert("Error: " + xhr.responseJSON.error);
                    } else {
                        alert("Ha ocurrido un error.");
                    }
                },
            });
        });

        $("body").on("click", ".delete", function() {
            let prenda_id = $(this).data("id");
            if (confirm("¿Estás seguro de eliminar esta prenda?")) {
                $.ajax({
                    type: "DELETE",
                    url: `{{ route('prendas-ajax-crud.index') }}/${prenda_id}`,
                    success: function(response) {
                        table.ajax.reload();
                        alert(response.success);
                    },
                    error: function() {
                        alert("Error al eliminar.");
                    },
                });
            }
        });
    });
</script>
@endsection