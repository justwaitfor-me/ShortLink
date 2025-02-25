// filepath: /d:/short/assets/js/toast.js
function showToast(message, type) {
    console.log(message);
    const toastLiveExample = document.getElementById('liveToast')
    const toastLiveType = document.getElementById('toast-type')
    const toastLiveIcon = document.getElementById('toast-icon')
    const toastLiveMessage = document.querySelector('.toast-body')

    switch (type) {
        case 'success':
            toastLiveIcon.classList.add('text-success')
            toastLiveType.innerHTML = 'Success'
            break
        case 'error':
            toastLiveIcon.classList.add('text-danger')
            toastLiveType.innerHTML = 'Error'
            break
        case 'info':
            toastLiveIcon.classList.add('text-info')
            toastLiveType.innerHTML = 'Info'
            break
        case 'warning':
            toastLiveIcon.classList.add('text-warning')
            toastLiveType.innerHTML = 'Warning'
            break
        default:
            toastLiveIcon.classList.add('text-dark')
            toastLiveType.innerHTML = 'Notice'
            break
    }

    const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample)
    toastLiveMessage.innerHTML = message
    toastBootstrap.show()
}

var toast = `    
<!-- Toast -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-square-fill text-primary" style="margin-right: 5px;" id="toast-icon"></i>
            <strong class="me-auto">Short Link</strong>
            <small id="toast-type"><!-- Toast Type --></small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body text-dark">
            <!-- Toast Body -->
        </div>
    </div>
</div>`;

document.body.insertAdjacentHTML('beforeend', toast);