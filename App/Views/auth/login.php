<?= $this->extend('base') ?>

<?= $this->section('title') ?>
    <?= esc($title ?? 'Iniciar Sesión') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4">
            <div class="card mt-5 shadow-sm">
                <div class="card-header text-center">
                    <h3><?= esc($title ?? 'Iniciar Sesión') ?></h3>
                </div>
                <div class="card-body p-4">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <?php
                        $validation_errors = session()->getFlashdata('errors'); // From redirect()->with('errors', $this->validator->getErrors())
                    ?>

                    <?= form_open(site_url('auth/attemptLogin')) ?>
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="email_doc" class="form-label">Correo Electrónico</label>
                            <input type="email" name="email_doc" id="email_doc"
                                   class="form-control <?= isset($validation_errors['email_doc']) ? 'is-invalid' : '' ?>"
                                   value="<?= old('email_doc') ?>" required autofocus>
                            <?php if (isset($validation_errors['email_doc'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($validation_errors['email_doc']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="cedula_doc" class="form-label">Cédula (Contraseña)</label>
                            <input type="password" name="cedula_doc" id="cedula_doc"
                                   class="form-control <?= isset($validation_errors['cedula_doc']) ? 'is-invalid' : '' ?>"
                                   required>
                            <?php if (isset($validation_errors['cedula_doc'])): ?>
                                <div class="invalid-feedback">
                                    <?= esc($validation_errors['cedula_doc']) ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Ingresar</button>
                        </div>
                    <?= form_close() ?>
                </div>
                <div class="card-footer text-center py-3">
                    <a href="<?= site_url('/') ?>">Volver al Inicio</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<style>
    /* Optional: Custom styles for centering if needed, though Bootstrap rows/cols should handle it */
    /* For example, to ensure vertical centering for very short pages: */
    /* body, html { height: 100%; }
       .container { display: flex; align-items: center; justify-content: center; min-height: 80vh; } */
</style>
<?= $this->endSection() ?>
