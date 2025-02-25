<!-- filepath: /assets/php/error.php -->
<main class="container text-center">
    <div class="row">
        <div class="col-md-12">
            <div class="error-content">
                <h2 class="display-4">Oops!</h2>
                <h1 class="display-1"><?php echo htmlspecialchars($errorCode); ?></h1>
                <p class="lead"><?php echo htmlspecialchars($errorMessage); ?></p>
                <?php if (!empty($errorDescription)): ?>
                    <p><?php echo htmlspecialchars($errorDescription); ?></p>
                <?php endif; ?>
                <small class="text-info">If you believe this is an error, please contact the webmaster.</small>
                <hr>
                <a href="https://s.justwaitforme.de/" class="btn btn-primary">Go to Home</a>
            </div>
            <?php
            include 'assets/php/debug.php';
            ?>
        </div>
    </div>
</main>