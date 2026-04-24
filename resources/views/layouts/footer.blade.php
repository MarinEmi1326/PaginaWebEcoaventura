<footer class="text-white" style="background-color:#064e3b;">

    <div class="container-fluid py-5 px-5">
        <div class="row g-5">

            {{-- COLUMNA 1 --}}
            <div class="col-md-6 col-lg-3">

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center"
                        style="width:40px; height:40px; background-color:#ffffff;">

                        <img src="{{ asset('img/ecoaventura-logo.png') }}" alt="Ecoaventura"
                            style="width:24px; height:24px;">
                    </div>
                    <h5 class="mb-0 fw-semibold">Ecoaventura</h5>
                </div>

                <p class="small text-white-50">
                    Plataforma de difusión cultural y turística de Ocosingo, Chiapas.
                    Conoce, comprende y valora este territorio antes de visitarlo.
                </p>

                <div class="d-flex gap-3 mt-4">
                    <a href="https://www.facebook.com/share/18B5t3nDes/?mibextid=wwXIfr"
                        class="rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width:36px; height:36px; background-color:#065f46;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="https://www.instagram.com/2025_ecoaventura?igsh=bThxNTAxd3Y3dG0z"
                        class="rounded-circle d-flex align-items-center justify-content-center text-white"
                        style="width:36px; height:36px; background-color:#065f46;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    {{-- <a href="#" class="rounded-circle d-flex align-items-center justify-content-center text-white"
                       style="width:36px; height:36px; background-color:#065f46;">
                        <i class="bi bi-twitter-x"></i>
                    </a>
                    <a href="#" class="rounded-circle d-flex align-items-center justify-content-center text-white"
                       style="width:36px; height:36px; background-color:#065f46;">
                        <i class="bi bi-youtube"></i>
                    </a> --}}
                </div>

            </div>

            {{-- COLUMNA 2 --}}
            <div class="col-md-6 col-lg-1">
                <h6 class="fw-semibold mb-4">Destinos</h6>
                <ul class="list-unstyled small">
                    <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none">Turisticos</a></li>
                    <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none">Ecoturismo</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none">Balnearios</a></li>
                </ul>
            </div>

            {{-- COLUMNA 3 --}}
            <div class="col-md-6 col-lg-2">
                <h6 class="fw-semibold mb-4">Explora</h6>
                <ul class="list-unstyled small">
                    <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none">Cultura y
                            Patrimonio</a></li>
                    <li class="mb-3"><a href="#" class="text-white-50 text-decoration-none">Turismo
                            Responsable</a></li>
                    <li><a href="#" class="text-white-50 text-decoration-none">Rutas y Planificación</a></li>
                </ul>
            </div>

            {{-- COLUMNA 4 --}}
            <div class="col-md-6 col-lg-3">
                <h6 class="fw-semibold mb-4">Contacto</h6>
                <ul class="list-unstyled small text-white-50">

                    <li class="mb-3 d-flex">
                        <i class="bi bi-geo-alt me-2"></i>
                        <span>Ocosingo, Chiapas, México</span>
                    </li>

                    <li class="mb-3 d-flex">
                        <i class="bi bi-telephone me-2"></i>
                        <span>+52 (919) 123-4567</span>
                    </li>

                    <li class="d-flex">
                        <i class="bi bi-envelope me-2"></i>
                        <span>2025ecoaventura@gmail.com</span>
                    </li>

                </ul>
            </div>

            {{-- COLUMNA 5 --}}
            <div class="col-md-6 col-lg-3">
                <h6 class="fw-semibold mb-4">¿Administras un destino o ruta turística?</h6>
                <div class="d-flex flex-column gap-3">
                    <a href="{{ route('registro.destinos') }}" class="text-decoration-none">
                        <div class="rounded-3 p-3" style="background:#065f46;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-geo-alt text-white"></i>
                                <span class="small fw-semibold text-white">¿Tienes un destino?</span>
                            </div>
                            <p class="small text-white-50 mb-0">Regístrate como Administrador de Destinos</p>
                        </div>
                    </a>
                    <a href="{{ route('registro.rutas') }}" class="text-decoration-none">
                        <div class="rounded-3 p-3" style="background:#065f46;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-map text-white"></i>
                                <span class="small fw-semibold text-white">¿Tienes una ruta?</span>
                            </div>
                            <p class="small text-white-50 mb-0">Regístrate como Gestor de Rutas</p>
                        </div>
                    </a>
                </div>
            </div>

            {{-- SECCIÓN DE DESCARGA DE LA APP --}}
                    {{-- SECCIÓN DE DESCARGA DE LA APP --}}
        <div class="row mt-5 pt-3 border-top border-light border-opacity-25">
            <div class="col-12 text-center">
                <h6 class="fw-semibold mb-3">Descarga nuestra App</h6>
                <div class="d-flex flex-wrap gap-3 justify-content-center">
                    <a href="https://drive.google.com/uc?export=download&id=18tVddeiZ6ODJacPCZ_gghBKv5gi-brcW" 
                       class="text-decoration-none" 
                       download>
                        <div class="d-flex align-items-center gap-2 rounded-3 px-4 py-2" style="background:#065f46; border:1px solid rgba(255,255,255,0.2);">
                            <i class="bi bi-google-play fs-4"></i>
                            <div>
                                <div class="small text-white-50">Descargar</div>
                                <div class="fw-semibold">App</div>
                            </div>
                        </div>
                    </a>
                </div>
                <p class="small text-white-50 mt-3">
                    Disponible para Android
                </p>
            </div>
        </div>

        </div>
    </div>

    {{-- BOTTOM --}}
    <div class="border-top border-light border-opacity-25">
        <div
            class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-center small text-white-50">
            <p class="mb-2 mb-md-0">© 2026 Ecoaventura. Todos los dereccios reservados.</p>
            <p class="mb-0">Comprometidos con el turismo sostenible 🌿</p>
        </div>
    </div>

</footer>
