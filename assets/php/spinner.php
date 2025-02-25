<!-- filepath: /assets/php/spinner.php -->
<div id="loadingSpinner">
    <l-hatch size="28" stroke="4" speed="3.5" color="white"></l-hatch>
</div>

<script>
    function showSpinner() {
        const spinner = document.getElementById('loadingSpinner');
        spinner.style.display = 'flex';
    }

    function hideSpinner() {
        const spinner = document.getElementById('loadingSpinner');
        spinner.style.display = 'none';
    }

    document.addEventListener("DOMContentLoaded", function() {
        showSpinner();

        window.addEventListener('load', function() {
            hideSpinner();
        });

        window.addEventListener('beforeunload', function() {
            showSpinner();
        });
    });
</script>