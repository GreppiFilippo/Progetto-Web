<main class="container my-5">
  <p class="visually-hidden">
    <span class="text-danger">*</span> Campo obbligatorio
  </p>

  <div class="row justify-content-center">
    <div class="col-12">

      <?php if (!empty($templateParams["errors"])): ?>
        <div class="alert alert-danger">
          <ul>
            <?php foreach ($templateParams["errors"] as $error): ?>
              <li><?php echo $error; ?></li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
      <h1 class="h3 mb-3" id="title">
      </h1>

      <div class="border-0 shadow-sm p-4">
        <form action="" method="POST" enctype="multipart/form-data" id="dish-form">

          <!-- ===================== -->
          <!-- IMMAGINE -->
          <!-- ===================== -->
          <div class="row mb-4">
            <div class="col-12">
              <h2 class="admin-h2 h5 mb-3">
                <i class="bi admin-icon bi-camera me-2"></i>Immagine del Piatto
              </h2>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label for="dishImage" class="form-label">
                  Carica Immagine <span class="text-danger">*</span>
                </label>
                <input
                  type="file"
                  class="form-control"
                  id="dishImage"
                  name="dishImage"
                  accept="image/*"
                  required
                >
                <div class="form-text">
                  Formati supportati: JPG, JPEG, PNG, GIF.
                </div>
              </div>

              <div class="col-md-6">
                <p class="form-label mb-2">Anteprima</p>
                <div id="imagePreview" class="border rounded p-3 text-center">
                  <i class="bi bi-image text-muted fs-2" id="previewIcon"></i>
                  <p class="text-muted mb-0 mt-2" id="previewText">
                    Nessuna immagine selezionata
                  </p>
                  <img
                    id="previewImg"
                    class="img-fluid rounded d-none mt-2"
                    alt="Anteprima immagine"
                  >
                </div>
              </div>
            </div>
          </div>

          <hr class="my-4">

          <!-- ===================== -->
          <!-- INFORMAZIONI BASE -->
          <!-- ===================== -->
          <div class="row mb-4">
            <div class="col-12">
              <h2 class="admin-h2 h5 mb-3">
                <i class="bi admin-icon bi-info-circle me-2"></i>Informazioni Base
              </h2>
            </div>

            <div class="row">
              <div class="col-md-6">
                <label for="dishName" class="form-label">
                  Nome Piatto <span class="text-danger">*</span>
                </label>
                <input
                  type="text"
                  class="form-control"
                  id="dishName"
                  name="dishName"
                  placeholder="Es. Pasta al Pomodoro"
                  required
                >
              </div>

              <div class="col-md-6">
                <label for="dishCategory" class="form-label">
                  Categoria <span class="text-danger">*</span>
                </label>
                <select
                  class="form-select"
                  id="dishCategory"
                  name="dishCategory"
                  required
                >
                  <option value="" selected disabled>
                    Seleziona categoria...
                  </option>
                  <option value="1">Primi</option>
                  <option value="2">Secondi</option>
                  <option value="3">Contorni</option>
                  <option value="4">Dolci</option>
                </select>
              </div>
            </div>

            <div class="row mb-4 mt-3">
              <div class="col-12">
                <label for="dishDescription" class="form-label">
                  Descrizione <span class="text-danger">*</span>
                </label>
                <textarea
                  class="form-control"
                  id="dishDescription"
                  name="dishDescription"
                  rows="3"
                  placeholder="Descrivi gli ingredienti principali e il metodo di preparazione..."
                  required
                ></textarea>
              </div>
            </div>
          </div>

          <hr class="my-4">

          <!-- ===================== -->
          <!-- PREZZO -->
          <!-- ===================== -->
          <div class="row mb-4">
            <div class="col-12">
              <h2 class="admin-h2 h5 mb-3">
                <i class="bi admin-icon bi-tag me-2"></i>Prezzo e Disponibilità
              </h2>
            </div>

            <div class="row">
              <div class="col-md-4">
                <label for="dishPrice" class="form-label">
                  Prezzo (€) <span class="text-danger">*</span>
                </label>
                <div class="input-group flex-nowrap">
                  <span class="input-group-text">€</span>
                  <input
                    type="number"
                    class="form-control"
                    id="dishPrice"
                    name="dishPrice"
                    min="0"
                    step="0.50"
                    placeholder="0.00"
                    required
                  >
                </div>
              </div>

              <div class="col-md-4">
                <label for="dishCalories" class="form-label">
                  Calorie
                </label>
                <div class="input-group">
                  <input
                    type="number"
                    class="form-control"
                    id="dishCalories"
                    name="dishCalories"
                    min="0"
                    placeholder="300"
                  >
                  <span class="input-group-text">kcal</span>
                </div>
              </div>

              <div class="col-md-4">
                <label for="dishAvailability" class="form-label">
                  Quantità Disponibile <span class="text-danger">*</span>
                </label>
                <input
                  type="number"
                  class="form-control"
                  id="dishAvailability"
                  name="dishAvailability"
                  min="0"
                  value="20"
                  required
                >
              </div>
            </div>
          </div>

          <hr class="my-4">

          <!-- ===================== -->
          <!-- INFO DIETETICHE -->
          <!-- ===================== -->
          <div class="row mb-4">
            <div class="col-12">
              <h2 class="admin-h2 h5 mb-3">
                <i class="bi admin-icon bi-heart me-2"></i>Informazioni Dietetiche
              </h2>
            </div>

            <div class="row">
              <div class="col-md-6">

                <div class="form-check mb-2">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="specs[]"
                    value="1"
                    id="spec_1"
                  >
                  <label class="form-check-label fw-medium" for="spec_1">
                    Vegetariano
                  </label>
                </div>

                <div class="form-check mb-2">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="specs[]"
                    value="2"
                    id="spec_2"
                  >
                  <label class="form-check-label fw-medium" for="spec_2">
                    Vegano
                  </label>
                </div>

                <div class="form-check mb-2">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="specs[]"
                    value="3"
                    id="spec_3"
                  >
                  <label class="form-check-label fw-medium" for="spec_3">
                    Senza glutine
                  </label>
                </div>

                <div class="form-check mb-2">
                  <input
                    class="form-check-input"
                    type="checkbox"
                    name="specs[]"
                    value="4"
                    id="spec_4"
                  >
                  <label class="form-check-label fw-medium" for="spec_4">
                    Senza lattosio
                  </label>
                </div>

              </div>
            </div>
          </div>

          <!-- ===================== -->
          <!-- BOTTONI -->
          <!-- ===================== -->
          <div class="row">
            <div class="col-md-6 offset-md-6">
              <div class="d-flex gap-2 justify-content-end">
                <button
                  type="button"
                  class="admin-btn btn btn-secondary w-100"
                  id="cancel"
                >
                  <i class="bi bi-x-lg me-2"></i>Annulla
                </button>
                <button
                  type="submit"
                  class="admin-btn btn btn-primary w-100"
                  id="confirm"
                >
                </button>
              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>

  <?php
    if (isset($templateParams["js"])) :
      foreach ($templateParams["js"] as $script) :
  ?>
        <script src="<?php echo $script; ?>" type="module"></script>
  <?php
      endforeach;
    endif;
  ?>
</main>
