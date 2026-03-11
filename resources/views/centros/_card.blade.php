<a href="{{ route('destinos.show', $d->id_destino) }}" class="text-decoration-none">
    <div class="card border-0 shadow-sm rounded-4 h-100 card-hover overflow-hidden">
        <div class="position-relative" style="height: 220px; overflow:hidden;">
            @if ($d->imagen)
                <img src="{{ Storage::url($d->imagen->ruta_archivo) }}"
                     class="w-100 h-100 object-fit-cover card-img-zoom"
                     alt="{{ $d->nombre }}">
            @else
                <div class="w-100 h-100 d-flex align-items-center justify-content-center"
                     style="background:#e2ece9;">
                    <i class="bi bi-image text-muted fs-1"></i>
                </div>
            @endif
            @if ($d->categorias->count() > 0)
                <div class="position-absolute top-0 start-0 m-3">
                    <span class="badge bg-white text-dark shadow-sm">
                        <i class="bi bi-tag me-1 text-success"></i> {{ $d->categorias->first() }}
                    </span>
                </div>
            @endif
        </div>
        <div class="card-body">
            <p class="small text-muted mb-1"><i class="bi bi-geo-alt me-1"></i> Ocosingo, Chiapas</p>
            <h5 class="fw-bold text-dark">{{ $d->nombre }}</h5>
            <p class="text-muted small">{{ Str::limit($d->descripcion, 90) }}</p>
            @if ($d->categorias->count() > 0)
                <div class="d-flex flex-wrap gap-1 mb-3">
                    @foreach ($d->categorias as $cat)
                        <span class="badge badge-eco">{{ $cat }}</span>
                    @endforeach
                </div>
            @endif
            <div class="d-flex justify-content-between align-items-center border-top pt-3 small text-muted">
                @if ($d->telefono)
                    <span><i class="bi bi-telephone me-1"></i>{{ $d->telefono }}</span>
                @else
                    <span></span>
                @endif
                <span class="text-success fw-semibold">Conocer más →</span>
            </div>
        </div>
    </div>
</a>