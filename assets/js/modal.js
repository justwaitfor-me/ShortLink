// filepath: /d:/short/assets/js/modal.js
function showModal(title, content, type) {
    console.log(title);
    const modalLiveExample = document.getElementById('liveModal');
    const modalLiveTitle = document.getElementById('modal-title');
    const modalLiveBody = document.getElementById('modal-body');
    const modalLiveIcon = document.getElementById('modal-icon');

    // Reset modal icon classes
    modalLiveIcon.className = 'bi bi-square-fill';

    switch (type) {
        case 'success':
            modalLiveIcon.classList.add('text-success');
            modalLiveTitle.innerHTML = 'Success';
            break;
        case 'error':
            modalLiveIcon.classList.add('text-danger');
            modalLiveTitle.innerHTML = 'Error';
            break;
        case 'info':
            modalLiveIcon.classList.add('text-info');
            modalLiveTitle.innerHTML = 'Info';
            break;
        case 'warning':
            modalLiveIcon.classList.add('text-warning');
            modalLiveTitle.innerHTML = 'Warning';
            break;
        default:
            modalLiveIcon.classList.add('text-dark');
            modalLiveTitle.innerHTML = 'Notice';
            break;
    }

    const modalBootstrap = bootstrap.Modal.getOrCreateInstance(modalLiveExample);
    modalLiveTitle.innerHTML = title;

    // Clear previous content
    modalLiveBody.innerHTML = '';

    // Append new content
    if (Array.isArray(content)) {
        content.forEach(element => {
            if (typeof element === 'string') {
                modalLiveBody.insertAdjacentHTML('beforeend', element);
            } else if (element instanceof HTMLElement) {
                modalLiveBody.appendChild(element);
            }
        });
    } else if (typeof content === 'string') {
        modalLiveBody.innerHTML = content;
    } else if (content instanceof HTMLElement) {
        modalLiveBody.appendChild(content);
    }

    modalBootstrap.show();
}

function hideModal() {
    const modalLiveExample = document.getElementById('liveModal');
    const modalBootstrap = bootstrap.Modal.getOrCreateInstance(modalLiveExample);
    modalBootstrap.hide();
}

var modal = `
<!-- Modal -->
<div class="modal fad text-dark" id="liveModal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <i class="bi bi-square-fill text-primary" style="margin-right: 5px;" id="modal-icon"></i>
                <h5 class="modal-title" id="modal-title"><!-- Modal Title --></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Modal Body -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>`;

document.body.insertAdjacentHTML('beforeend', modal);