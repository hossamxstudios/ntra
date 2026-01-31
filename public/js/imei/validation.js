/**
 * Form Validation with Custom Error Modal
 */

// Show error modal with message
function showErrorModal(message) {
    const modalEl = document.getElementById('errorModal');
    const messageEl = document.getElementById('errorModalMessage');

    if (modalEl && messageEl) {
        messageEl.textContent = message;
        const modal = new bootstrap.Modal(modalEl);
        modal.show();

        // Refresh Lucide icons in modal
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
}

// Add error indicator to element
function addErrorIndicator(elementId, isScanArea = false) {
    const element = document.getElementById(elementId);
    if (element) {
        element.classList.add(isScanArea ? 'scan-area-error' : 'input-error');
        element.focus();

        // Remove error class after 3 seconds or on input
        setTimeout(() => {
            element.classList.remove('input-error', 'scan-area-error');
        }, 3000);

        element.addEventListener('input', function handler() {
            element.classList.remove('input-error', 'scan-area-error');
            element.removeEventListener('input', handler);
        }, { once: true });

        element.addEventListener('click', function handler() {
            element.classList.remove('input-error', 'scan-area-error');
            element.removeEventListener('click', handler);
        }, { once: true });
    }
}

// Clear all error indicators
function clearErrorIndicators() {
    document.querySelectorAll('.input-error, .scan-area-error').forEach(el => {
        el.classList.remove('input-error', 'scan-area-error');
    });
}

function validateAndNext(step) {
    let isValid = true;
    let errorMsg = '';
    let errorElementId = '';
    let isScanArea = false;

    // Clear previous errors
    clearErrorIndicators();

    switch(step) {
        case 1:
            if (!document.getElementById('serialNumber').value.trim()) {
                isValid = false;
                errorMsg = 'يرجى إدخال الرقم التسلسلي';
                errorElementId = 'serialNumber';
            }
            break;
        case 2:
            if (!document.getElementById('passengerPhotoData').value) {
                isValid = false;
                errorMsg = 'يرجى التقاط صورة المسافر';
                errorElementId = 'cameraPreview';
                isScanArea = true;
            }
            break;
        case 3:
            // Allow proceeding if form fields are filled (even without scanner)
            const givenName = document.getElementById('givenName').value.trim();
            const familyName = document.getElementById('familyName').value.trim();
            const documentNo = document.getElementById('documentNo').value.trim();
            const nationality = document.getElementById('nationality').value.trim();

            if (!givenName || !familyName || !documentNo || !nationality) {
                isValid = false;
                errorMsg = 'يرجى ملء بيانات جواز السفر أو مسحه ضوئياً';
                errorElementId = !givenName ? 'givenName' : (!familyName ? 'familyName' : (!documentNo ? 'documentNo' : 'nationality'));
                isScanArea = false;
            }
            break;
        case 4:
            if (!document.getElementById('arrival_image_base64').value) {
                isValid = false;
                errorMsg = 'يرجى مسح ختم الوصول';
                errorElementId = 'arrivalScanArea';
                isScanArea = true;
            }
            break;
        case 5:
            if (!document.getElementById('boarding_image_base64').value) {
                isValid = false;
                errorMsg = 'يرجى مسح بطاقة الصعود';
                errorElementId = 'boardingScanArea';
                isScanArea = true;
            }
            break;
    }

    if (isValid) {
        nextStep();
    } else {
        showErrorModal(errorMsg);
        if (errorElementId) {
            addErrorIndicator(errorElementId, isScanArea);
        }
    }
}
