@extends('layouts.admin')

@section('title', 'Categorías')

@section('content')

    <div class="container mt-4">

        <h3 class="mb-4">Categorías de Destinos</h3>

        <div class="row g-4">

            @foreach ($categorias as $categoria)
                <div class="col-md-4">
                    <div class="card shadow-sm border-0 p-3 d-flex flex-row justify-content-between align-items-center">

                        <div class="d-flex align-items-center">

                            <div class="rounded-circle bg-light p-3 me-3">
                                <i class="bi bi-tag"></i>
                            </div>

                            <div>
                                <h5 class="mb-0">{{ $categoria->nombre }}</h5>
                            </div>

                        </div>

                        <form action="{{ route('admin.categorias.destroy', $categoria->id_categoria) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="btn btn-link text-danger p-0">
                                <i class="bi bi-trash"></i>
                            </button>

                        </form>

                    </div>
                </div>
            @endforeach

        </div>


        <!-- Crear categoria -->

        <div class="card mt-5 shadow-sm border-0">

            <div class="card-body">

                <h5 class="mb-3">Agregar nueva categoría</h5>

                <form action="{{ route('admin.categorias.store') }}" method="POST">

                    @csrf

                    <div class="row">

                        <div class="col-md-6">
                            <input type="text" id="nombreCategoria" name="nombre" class="form-control"
                                placeholder="Nombre de la categoría" required>
                        </div>

                        <div class="col-md-3">
                            <button class="btn btn-success w-100">
                                <i class="bi bi-plus"></i> Crear
                            </button>
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

    {{-- SCRIPT --}}
    <script>
        // SOLO LETRAS (incluye acentos y espacios)
        document.getElementById('nombreCategoria').addEventListener('input', function() {
            this.value = this.value.replace(/[^a-zA-ZÁÉÍÓÚáéíóúÑñ\s]/g, '');
        });
    </script>

@endsection
