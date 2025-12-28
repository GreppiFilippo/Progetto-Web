<main class="container-fluid my-5">
    <!-- INTRO -->
    <section class="mb-2">
        <div class="row align-items-center">
            <div class="col-12 col-md mb-1">
                <h1 class="h3 mb-1">
                    <i class="bi admin-icon bi-book me-1" aria-hidden="true"></i>
                    Gestione menù
                </h1>
                <p class="lead mb-0">Gestisci i piatti disponibili nel menù</p>
            </div>
            
            <div class="col-12 col-md-auto text-md-end">
                <button type="reset" class="btn admin-btn btn-primary w-100 w-md-auto">
                    <i class="bi admin-icon bi-plus-circle me-1"></i>
                    Aggiungi piatto
                </button>
            </div>
        </div>
    </section>

    <!-- FILTERS -->
    <section>
        <form class="row g-2">
            <div class="col-12 col-md-3">
                <label for="cathegory" class="form-label">Categoria</label>
                <select id="cathegory" class="form-select"></select>
            </div>
            <div class="col-12 col-md-3">
                <label for="state" class="form-label">Stato</label>
                <select id="state" class="form-select"></select>
            </div>
            <div class="col-12 col-md-6">
                <label for="search" class="form-label">Cerca</label>
                <input type="text" class="form-control" placeholder="Cerca piatto per nome..." id="search">
            </div>
        </form>
    </section>

    <!-- DISH LIST -->
    <section>
        <table id="dishes">
            
        </table>
    </section>
</main>