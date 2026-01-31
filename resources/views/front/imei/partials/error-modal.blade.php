<!-- Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white border-0">
                <h5 class="modal-title" id="errorModalLabel">
                    <i data-lucide="alert-circle" style="width:20px;height:20px;"></i>
                    <span class="ms-2">تنبيه</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="error-icon mb-3">
                    <i data-lucide="x-circle" class="text-danger" style="width:64px;height:64px;"></i>
                </div>
                <p class="mb-0 fs-5" id="errorModalMessage">حدث خطأ</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-danger px-4" data-bs-dismiss="modal">
                    <i data-lucide="check" style="width:16px;height:16px;"></i>
                    <span class="ms-1">حسناً</span>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* Error input indicator styles */
    .input-error {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25) !important;
        animation: shake 0.5s ease-in-out;
    }
    
    .scan-area-error {
        border-color: #dc3545 !important;
        background-color: rgba(220, 53, 69, 0.05) !important;
        animation: shake 0.5s ease-in-out;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .error-label {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: block;
    }
    
    #errorModal .modal-content {
        border-radius: 12px;
        overflow: hidden;
    }
    
    #errorModal .error-icon {
        animation: pulse 1s ease-in-out;
    }
    
    @keyframes pulse {
        0% { transform: scale(0.8); opacity: 0; }
        50% { transform: scale(1.1); }
        100% { transform: scale(1); opacity: 1; }
    }
</style>
