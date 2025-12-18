<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
include 'ConexionBD.php';
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denuncias Ciudadanas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="container">
        <div class="complaint-card">
            <h3>Sistema de Denuncias Ciudadanas</h3>
            <a href="reportes.php" class="btn btn-primary mb-3">Ver Reportes</a>

            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-nueva-tab" data-bs-toggle="tab" data-bs-target="#nav-nueva"
                        type="button" role="tab" aria-controls="nav-nueva" aria-selected="true">Nueva Denuncia</button>
                    <button class="nav-link" id="nav-lista-tab" data-bs-toggle="tab" data-bs-target="#nav-lista"
                        type="button" role="tab" aria-controls="nav-lista" aria-selected="false">Mis Denuncias</button>
                </div>
            </nav>

            <div class="tab-content" id="nav-tabContent">
                
                <div class="tab-pane fade show active" id="nav-nueva" role="tabpanel" aria-labelledby="nav-nueva-tab">
                    <form id="formDenuncia" class="mt-4">
                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Denuncia</label>
                            <select class="form-select" id="tipo" name="tipo" required>
                                <option value="">Seleccione un tipo</option>
                                <option value="Denuncia Ambiental">Denuncia Ambiental</option>
                                <option value="Infraestructura">Infraestructura</option>
                                <option value="Servicios Públicos">Servicios Públicos</option>
                                <option value="Seguridad">Seguridad</option>
                                <option value="Otro">Otro</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="descripcion" class="form-label">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="4" required
                                placeholder="Describa detalladamente la situación..."></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="municipalidad" class="form-label">Municipalidad</label>
                            <input type="text" class="form-control" id="municipalidad" name="municipalidad" required
                                placeholder="Ingrese la municipalidad correspondiente">
                        </div>

                        <div class="mb-3">
                            <label for="ubicacion" class="form-label">Ubicación</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="ubicacion" name="ubicacion" required readonly
                                    placeholder="Haga clic en obtener ubicación">
                                <button class="btn btn-outline-secondary" type="button" id="btnUbicacion">
                                    Obtener Ubicación
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="evidencia" class="form-label">Evidencia Fotográfica</label>
                            <input class="form-control" type="file" id="evidencia" name="evidencia" accept="image/*" required>
                            <div id="previewContainer" class="mt-2 d-none">
                                <img id="verImagen" class="img-fluid rounded" style="max-height: 200px;" src="" alt="Vista previa">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Enviar Denuncia</button>
                    </form>
                </div>

                
                <div class="tab-pane fade" id="nav-lista" role="tabpanel" aria-labelledby="nav-lista-tab">
                    <div id="listaDenuncias" class="mt-4">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalExito" tabindex="-1" aria-labelledby="modalExitoLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalExitoLabel">¡Denuncia Registrada!</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="success-icon mb-3">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <p>Su denuncia ha sido registrada exitosamente.</p>
                    <p>Número de seguimiento: <strong id="numeroDenuncia"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
    <script src="Scripts.js"></script>
</body>

</html>