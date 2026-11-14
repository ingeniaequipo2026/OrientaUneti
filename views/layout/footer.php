</main>

<footer class="footer-uneti">
    <div class="container">
        <div class="row g-4 mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="d-flex align-items-center mb-3">
                    <span class="p-2 rounded-3 me-2 text-white bg-gradient-uneti" style="font-size: 1.1rem;">
                        <i class="bi bi-compass-fill"></i>
                    </span>
                    <h5 class="text-white mb-0">Orienta<span style="color: #60a5fa;">Uneti</span></h5>
                </div>
                <p class="text-white-50 small">
                    Plataforma Oficial de Bienestar Estudiantil de la Universidad Nacional Experimental de Telecomunicaciones e Informática (UNETI). Conectando a los futuros profesionales de las TIC con el desarrollo integral, la salud mental y la innovación.
                </p>
                <div class="d-flex gap-2">
                    <span class="badge bg-success"><i class="bi bi-shield-check me-1"></i> Datos Protegidos</span>
                    <span class="badge bg-primary"><i class="bi bi-award me-1"></i> Certificaciones UNETI</span>
                </div>
            </div>

            <div class="col-lg-2 col-md-6">
                <h6 class="text-white fw-bold mb-3">Módulos</h6>
                <ul class="list-unstyled small">
                    <li class="mb-2"><a href="<?= url('/actividades') ?>"><i class="bi bi-chevron-right me-1"></i> Deportes y Cultura</a></li>
                    <li class="mb-2"><a href="<?= url('/salud-mental') ?>"><i class="bi bi-chevron-right me-1"></i> Salud Mental</a></li>
                    <li class="mb-2"><a href="<?= url('/emprendimientos') ?>"><i class="bi bi-chevron-right me-1"></i> Emprendimiento</a></li>
                    <li class="mb-2"><a href="<?= url('/contacto') ?>"><i class="bi bi-chevron-right me-1"></i> Atención al Usuario</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Ética y Privacidad</h6>
                <p class="text-white-50 small mb-2">
                    Garantizamos la privacidad y confidencialidad estudiantil. El módulo de salud mental contiene únicamente directorios institucionales, sin almacenamiento de historiales clínicos.
                </p>
                <p class="text-white-50 small mb-0">
                    <i class="bi bi-envelope-fill me-1"></i> bienestar@uneti.edu.ve
                </p>
            </div>

            <div class="col-lg-3 col-md-6">
                <h6 class="text-white fw-bold mb-3">Sede Institucional</h6>
                <p class="text-white-50 small mb-1">
                    <i class="bi bi-geo-alt-fill me-1 text-danger"></i> Caracas, República Bolivariana de Venezuela.
                </p>
                <p class="text-white-50 small mb-3">
                    <i class="bi bi-clock-fill me-1 text-info"></i> Lunes a Viernes: 8:00 AM - 4:30 PM
                </p>
                <a href="<?= url('/contacto') ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">
                    <i class="bi bi-chat-heart me-1"></i> Enviar Sugerencia o Reclamo
                </a>
            </div>
        </div>

        <hr class="border-secondary opacity-25">

        <div class="row align-items-center small text-white-50">
            <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                &copy; <?= date('Y') ?> OrientaUneti - UNETI. Todos los derechos reservados.
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span>Versión MVP 1.0 | Desarrollado con PHP Puro & PostgreSQL / MySQL</span>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="<?= asset('assets/js/main.js') ?>"></script>
</body>
</html>
