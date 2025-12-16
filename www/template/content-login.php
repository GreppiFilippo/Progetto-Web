<main>
    <section class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="bi bi-person-circle display-3" aria-hidden="true"></i>
                            <h1 class="h3 mt-3">Accedi al tuo account</h1>
                            <p class="text-muted">Bentornato! Inserisci le tue credenziali</p>
                        </div>

                        <!-- Login Form -->
                        <form action="#" method="post">

                            <!-- email field -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-envelope" aria-hidden="true"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Inserisci email" autocomplete="username" required />
                                </div>
                            </div>

                            <!-- password field -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="bi bi-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Inserisci password" autocomplete="current-password" required />
                                </div>
                            </div>

                            <?php if (isset($templateParams["login_error"])): ?>
                                <p class="text-danger"><?php echo $templateParams["login_error"]; ?></p>
                            <?php endif; ?>

                            <!-- button submit -->
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn text-white btn-lg">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>
                                    Accedi
                                </button>                    
                            </div>

                        </form>

                        <hr class="my-4">

                        <!-- registration link -->
                        <div class="text-center">
                            <p class="mb-0">
                            Non hai un account?
                            <a href="register.php" class="fw-bold text-decoration-none">Registrati qui</a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>
</main>