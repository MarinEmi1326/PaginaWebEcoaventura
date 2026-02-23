@extends('layouts.app')

@section('content')

<div class="container py-5">
    
    <div class="text-center mb-5">
        <h2 class="fw-bold">📘 Publicaciones de Facebook</h2>
        <p class="text-muted">Últimas novedades de nuestra página</p>
    </div>

    @if(isset($posts['data']) && count($posts['data']) > 0)

        <div class="row justify-content-center">
            @foreach($posts['data'] as $post)
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-body">

                            <p class="card-text fs-5">
                                {{ $post['message'] ?? 'Sin texto disponible.' }}
                            </p>

                            <hr>

                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted">
                                    📅 
                                    {{ \Carbon\Carbon::parse($post['created_time'])->format('d M Y - h:i A') }}
                                </small>

                                <span class="badge bg-primary">
                                    Facebook
                                </span>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

    @else

        <div class="alert alert-info text-center">
            No hay publicaciones disponibles.
        </div>

    @endif

</div>

@endsection