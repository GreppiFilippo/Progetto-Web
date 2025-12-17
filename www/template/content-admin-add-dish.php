<?php if (!empty($errors)): ?>
  <div class="alert alert-danger">
    <ul class="mb-0">
      <?php foreach ($errors as $e): ?>
        <li><?php echo htmlspecialchars($e); ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
<?php endif; ?>

<?php
  // Per mantenere checked dopo submit
  $oldSpecs = $_POST["specs"] ?? [];
  $oldSpecs = array_map("intval", (array)$oldSpecs);
?>

<main class="container my-5">
  <p class="visually-hidden">
    <span class="text-danger">*</span> Campo obbligatorio
  </p>

  <div class="row justify-content-center">
    <div class="col-12">

      <h1 class="h3 mb-3">
        <i class="bi admin-icon bi-plus-circle me-2" aria-hidden="true"></i>
        Aggiungi Nuovo Piatto
      </h1>

      <div class="border-0 shadow-sm p-4">
        <form action="" method="post" enctype="multipart/form-data">

          <!-- Caricamento Immagine -->
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

                <div class="form-text">Formati supportati: JPG, JPEG, PNG, GIF (come da backend).</div>
              </div>

              <div class="col-md-6">
                <label class="form-label">Anteprima</label>

                <div id="imagePreview" class="border rounded p-3 text-center">
                  <i class="bi bi-image text-muted fs-2" id="previewIcon"></i>
                  <p class="text-muted mb-0 mt-2" id="previewText">Nessuna immagine selezionata</p>
                  <img id="previewImg" class="img-fluid rounded d-none mt-2" alt="Anteprima immagine">
                </div>
              </div>
            </div>
          </div>

          <hr class="my-4">

          <!-- Informazioni di Base -->
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
                  value="<?php echo htmlspecialchars($_POST['dishName'] ?? ''); ?>"
                  required
                >
              </div>

              <div class="col-md-6">
                <label for="dishCategory" class="form-label">
                  Categoria <span class="text-danger">*</span>
                </label>

                <select class="form-select" id="dishCategory" name="dishCategory" required>
                  <option value="" disabled <?php echo empty($_POST['dishCategory']) ? 'selected' : ''; ?>>
                    Seleziona categoria...
                  </option>

                  <?php foreach ($templateParams["categories"] as $category): ?>
                    <option
                      value="<?php echo (int)$category['category_id']; ?>"
                      <?php echo ((int)($_POST['dishCategory'] ?? 0) === (int)$category['category_id']) ? 'selected' : ''; ?>
                    >
                      <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>
                  <?php endforeach; ?>
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
                ><?php echo htmlspecialchars($_POST['dishDescription'] ?? ''); ?></textarea>
              </div>
            </div>
          </div>

          <hr class="my-4">

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
                    value="<?php echo htmlspecialchars($_POST['dishPrice'] ?? ''); ?>"
                    required
                  >
                </div>
              </div>

              <div class="col-md-4">
                <label for="dishCalories" class="form-label">Calorie</label>

                <div class="input-group">

                  <input
                    type="number"
                    class="form-control"
                    id="dishCalories"
                    name="dishCalories"
                    min="0"
                    placeholder="300"
                    value="<?php echo htmlspecialchars($_POST['dishCalories'] ?? ''); ?>"
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
                  value="<?php echo htmlspecialchars($_POST['dishAvailability'] ?? '20'); ?>"
                  required
                >
              </div>
            </div>
          </div>

          <hr class="my-4">

          <div class="row mb-4">
            <div class="col-12">
              <h2 class="admin-h2 h5 mb-3">
                <i class="bi admin-icon bi-heart me-2"></i>Informazioni Dietetiche
              </h2>
            </div>

            <div class="row">
              <div class="col-md-6">
                <?php foreach ($templateParams["dietary_specs"] as $dietary_spec): ?>
                  <?php $sid = (int)$dietary_spec["dietary_spec_id"]; ?>

                  <div class="form-check mb-2">
                    <input
                      class="form-check-input"
                      type="checkbox"
                      name="specs[]"
                      value="<?php echo $sid; ?>"
                      id="spec_<?php echo $sid; ?>"
                      <?php echo in_array($sid, $oldSpecs, true) ? 'checked' : ''; ?>
                    >
                    <label class="form-check-label fw-medium" for="spec_<?php echo $sid; ?>">
                      <?php echo htmlspecialchars($dietary_spec["dietary_spec_name"]); ?>
                    </label>
                  </div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-md-6 offset-md-6">
              <div class="d-flex gap-2 justify-content-end">

                <button type="reset" class="admin-btn btn btn-primary w-100" id="btnReset">
                  <i class="bi bi-x-lg me-2"></i>Annulla
                </button>

                <button type="submit" class="admin-btn btn btn-primary w-100">
                  <i class="bi bi-check-lg me-2"></i>Aggiungi Piatto
                </button>

              </div>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</main>
