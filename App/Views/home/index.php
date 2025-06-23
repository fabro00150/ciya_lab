<?= $this->extend('base') ?>

<?= $this->section('title') ?>
    <?= esc($title ?? 'Inicio - Sistema de Gestión') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container mt-4">

    <?php if (session()->get('isLoggedIn')): ?>
        <div class="alert alert-info d-flex justify-content-between align-items-center alert-dismissible fade show" role="alert">
            <span>Bienvenido, <?= esc(session()->get('nombre_doc') ?? session()->get('email_doc')) ?>!</span>
            <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger btn-sm">Cerrar Sesión</a>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="p-5 mb-4 bg-light rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Bienvenido al Sistema de Gestión</h1>
            <p class="col-md-8 fs-4">
                Este es el panel principal del sistema. Desde aquí puede acceder a las diferentes
                funcionalidades y módulos de gestión disponibles.
            </p>
            <?php if (!session()->get('isLoggedIn')): ?>
                 <p>Por favor, inicie sesión para continuar.</p>
            <?php endif; ?>
        </div>
    </div>

    <?php if (session()->get('isLoggedIn')): ?>
        <div class="row"> {/* Changed from <div class="row mt-4"> as mt-4 is already on container */}
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Gestión de Docentes</h5>
                        <p class="card-text">
                            Administre la información de los docentes, incluyendo datos personales,
                            académicos y de contacto.
                        </p>
                        <div class="mt-auto">
                            <a href="<?= site_url('docente') ?>" class="btn btn-primary">
                                Ir a Docentes <i class="fas fa-chalkboard-teacher ms-1"></i> <!-- Optional: Font Awesome icon -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Gestión de Laboratorios</h5>
                        <p class="card-text">
                            Consulte y administre los laboratorios disponibles, sus equipos,
                            responsables y horarios de funcionamiento.
                        </p>
                        <div class="mt-auto">
                            <a href="<?= site_url('laboratorio') ?>" class="btn btn-primary">
                                Ir a Laboratorios <i class="fas fa-flask ms-1"></i> <!-- Optional: Font Awesome icon -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">Gestión de Reservas</h5>
                        <p class="card-text">
                            Realice, consulte y administre las reservas de los laboratorios
                            para clases, prácticas y eventos académicos.
                        </p>
                        <div class="mt-auto">
                            <a href="<?= site_url('reserva') ?>" class="btn btn-primary">
                                Ir a Reservas <i class="fas fa-calendar-alt ms-1"></i> <!-- Optional: Font Awesome icon -->
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 p-4 bg-light rounded-3 border">
            <h4>Acceso Rápido</h4>
            <p>Otras funcionalidades importantes:</p>
            <ul>
                <li><a href="#">Reportes Generales</a></li>
                <li><a href="#">Configuración del Sistema</a></li>
                <li><a href="#">Ayuda y Soporte Técnico</a></li>
            </ul>
        </div>

    <?php else: ?>
        <div class="alert alert-light text-center mt-4 p-4 border">
            <p class="lead">Por favor, inicie sesión para acceder a las opciones de gestión.</p>
            <a href="<?= site_url('login') ?>" class="btn btn-primary btn-lg">Iniciar Sesión</a>
        </div>
    <?php endif; ?>

</div>
<?= $this->endSection() ?>
