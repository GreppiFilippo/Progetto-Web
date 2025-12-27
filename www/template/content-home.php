<?php
if (!defined('IN_APP')) {
  http_response_code(404);
  exit;
}
?>
<main>
    <!-- Intro -->
    <section class="py-5">
        <div class="container">
            <div class="row align-items-center">
            
                <!-- Testo -->
                <div class="col-lg-5 mb-4 mb-lg-0">
                    <h1 class="display-4 fw-bold mb-3" aria-label="Questa è Mensa Campus">
                        <span class="d-block" aria-hidden="true">Questa è</span>
                        <span class="d-block" aria-hidden="true">Mensa Campus</span>
                    </h1>
                    <p class="lead mb-4">
                        Prenota i tuoi pasti in modo facile e veloce. <br/>
                        Menu giornaliero aggiornato, zero code, zero sprechi.
                    </p>

                    <div class="d-flex flex-wrap gap-3">
                        <a class="btn btn-lg text-white" href="menu.php">
                            <i class="bi bi-book me-2" aria-hidden="true"></i>Vedi il Menu
                        </a>
                        <?php if (!isset($_SESSION["user_id"])): ?>
                            <a class="btn btn-lg" href="register.php">
                                <i class="bi bi-person-plus me-2" aria-hidden="true"></i>Registrati ora
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Immagine -->
                <div class="col-lg-7 text-center d-none d-lg-block">
                    <img 
                    src="upload/mensa.jpg" 
                    class="img-fluid rounded shadow-lg"
                    alt="Immagine della mensa universitaria"
                    loading="lazy"
                    decoding="async"
                    />
                </div>
            </div>
        </div>
    </section>

    <!-- Perché scegliere Mensa Campus -->
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-center mb-5">Perché scegliere Mensa Campus?</h2>

            <div class="row row-cols-1 row-cols-md-3 g-4">

            <div class="col">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-clock-history fs-1" aria-hidden="true"></i>
                    </div>
                    <h3 class="h5 card-title">Risparmia Tempo</h3>
                    <p class="card-text">Prenota in anticipo e salta la fila. Il tuo pasto ti aspetta quando arrivi.</p>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-calendar-check fs-1" aria-hidden="true"></i>
                    </div>
                    <h3 class="h5 card-title">Menu Aggiornato</h3>
                    <p class="card-text">Consulta il menu giornaliero sempre aggiornato con disponibilità in tempo reale.</p>
                </div>
            </div>

            <div class="col">
                <div class="card h-100 border-0 shadow-sm text-center p-4">
                    <div class="text-white rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center">
                        <i class="bi bi-star-fill fs-1" aria-hidden="true"></i>
                    </div>
                    <h3 class="h5 card-title">Zero Sprechi</h3>
                    <p class="card-text">La prenotazione aiuta a ridurre gli sprechi alimentari e garantisce la freschezza.</p>
                </div>
            </div>

            </div>
        </div>
    </section>

    <!-- Come Funziona -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">Come Funziona</h2>

            <div class="row mt-4">

                <div class="col-md-3 text-center py-4">
                    <i class="bi bi-1-circle-fill display-2" aria-hidden="true"></i>
                    <span class="visually-hidden">Passo 1: Registrati</span>

                    <h3 class="h5 mt-3" aria-label="Registrati">Registrati</h3>
                    <p>Crea il tuo account gratuito in pochi secondi</p>
                </div>

                <div class="col-md-3 text-center py-4">
                    <i class="bi bi-2-circle-fill display-2" aria-hidden="true"></i>
                    <span class="visually-hidden">Passo 2: Consulta il Menu</span>

                    <h3 class="h5 mt-3">Consulta il Menu</h3>
                    <p>Sfoglia le proposte del giorno</p>
                </div>

                <div class="col-md-3 text-center py-4">
                    <i class="bi bi-3-circle-fill display-2" aria-hidden="true"></i>
                    <span class="visually-hidden">Passo 3: Prenota</span>

                    <h3 class="h5 mt-3">Prenota</h3>
                    <p>Scegli i piatti e conferma la prenotazione</p>
                </div>

                <div class="col-md-3 text-center py-4">
                    <i class="bi bi-4-circle-fill display-2" aria-hidden="true"></i>
                    <span class="visually-hidden">Passo 4: Ritira</span>

                    <h3 class="h5 mt-3">Ritira</h3>
                    <p>Vieni in mensa e ritira il tuo pasto</p>
                </div>

            </div>

        </div>
    </section>
    
</main>