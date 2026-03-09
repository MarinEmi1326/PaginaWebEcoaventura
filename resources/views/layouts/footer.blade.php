<footer class="text-white" style="background-color:#064e3b;">

    <div class="container py-5 py-lg-6">
        <div class="row g-4 g-lg-5">

            {{-- COLUMNA 1 --}}
            <div class="col-12 col-md-6 col-lg-3">
                <div class="navbar-brand d-flex align-items-center gap-2">
                    <div class="eco-footer-logo">
                        <img src="{{ asset('img/logo.jpeg') }}" class="rounded-circle">
                    </div>
                    <h5 class="mb-0 fw-semibold eco-footer-brand">Ecoaventura</h5>
                </div>

                <p class="eco-footer-text mb-4">
                    Plataforma de difusión cultural y turística de Ocosingo, Chiapas.
                    Conoce, comprende y valora este territorio antes de visitarlo.
                </p>

                <div class="d-flex gap-3">
                    <a href="#" class="eco-social-link" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="eco-social-link" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    
                </div>
            </div>

            {{-- COLUMNA 2 --}}
            <div class="col-6 col-md-6 col-lg-2">
                <h6 class="eco-footer-title">Centros Turísticos</h6>
                <ul class="list-unstyled eco-footer-links mb-0">
                    <li><a href="#">Turísticos</a></li>
                    <li><a href="#">Ecoturismo</a></li>
                    <li><a href="#">Balnearios</a></li>
                </ul>
            </div>

            {{-- COLUMNA 3 --}}
            <div class="col-6 col-md-6 col-lg-2">
                <h6 class="eco-footer-title">Explora</h6>
                <ul class="list-unstyled eco-footer-links mb-0">
                    <li><a href="#">Cultura y Patrimonio</a></li>
                    <li><a href="#">Turismo Responsable</a></li>
                    <li><a href="#">Rutas y Planificación</a></li>
                </ul>
            </div>

            {{-- COLUMNA 4 --}}
            <div class="col-12 col-md-6 col-lg-2">
                <h6 class="eco-footer-title">Contacto</h6>
                <ul class="list-unstyled eco-footer-contact mb-0">
                    <li>
                        <i class="bi bi-geo-alt"></i>
                        <span>Ocosingo, Chiapas, México</span>
                    </li>
                    <li>
                        <i class="bi bi-telephone"></i>
                        <span>+52 (919) 123-4567</span>
                    </li>
                    <li>
                        <i class="bi bi-envelope"></i>
                        <span>info@ecoaventura-ocosingo.mx</span>
                    </li>
                </ul>
            </div>

            {{-- COLUMNA 5 --}}
            <div class="col-12 col-md-6 col-lg-3">
                <h6 class="eco-footer-title">¿Tienes un negocio?</h6>

                <div class="d-flex flex-column gap-3">
                    <a href="{{ route('registro.destinos') }}" class="eco-business-card text-decoration-none">
                        <div class="d-flex align-items-start gap-3">
                            <div class="eco-business-icon">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div>
                                <div class="eco-business-title">¿Tienes un destino?</div>
                                <div class="eco-business-text">Regístrate como Admin de Destinos</div>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('registro.rutas') }}" class="eco-business-card text-decoration-none">
                        <div class="d-flex align-items-start gap-3">
                            <div class="eco-business-icon">
                                <i class="bi bi-map"></i>
                            </div>
                            <div>
                                <div class="eco-business-title">¿Tienes una ruta?</div>
                                <div class="eco-business-text">Regístrate como Gestor de Rutas</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

        </div>
    </div>

    {{-- BOTTOM --}}
    <div class="eco-footer-bottom">
        <div class="container py-3 d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
            <p class="mb-0 eco-footer-copy">© 2026 Ecoaventura. Todos los derechos reservados.</p>
            <p class="mb-0 eco-footer-copy">Comprometidos con el turismo sostenible </p>
        </div>
    </div>

</footer>