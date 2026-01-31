/**
 * Wizard Navigation & Progress
 */

let currentStep = 1;
const totalSteps = 6;

function updateProgress() {
    const progress = (currentStep / totalSteps) * 100;
    document.getElementById('wizardProgress').style.width = progress + '%';

    // Update step indicators
    for (let i = 1; i <= totalSteps; i++) {
        const indicator = document.querySelector(`.step-indicator[data-step="${i}"]`);
        const line = document.querySelector(`.step-line[data-line="${i - 1}"]`);

        if (i < currentStep) {
            indicator.classList.remove('active');
            indicator.classList.add('completed');
            indicator.innerHTML = '<i data-lucide="check" style="width:16px;height:16px;"></i>';
            if (line) line.classList.add('completed');
        } else if (i === currentStep) {
            indicator.classList.add('active');
            indicator.classList.remove('completed');
            indicator.textContent = i;
        } else {
            indicator.classList.remove('active', 'completed');
            indicator.textContent = i;
        }
    }
    lucide.createIcons();
}

function showStep(step) {
    document.querySelectorAll('.wizard-step').forEach(el => el.classList.remove('active'));
    document.querySelector(`.wizard-step[data-step="${step}"]`).classList.add('active');
    updateProgress();

    // Start camera on step 2
    if (step === 2) {
        startCamera();
    } else {
        stopCamera();
    }

    // Update recap on step 6
    if (step === 6) {
        updateRecap();
    }

    lucide.createIcons();
}

function nextStep() {
    if (currentStep < totalSteps) {
        currentStep++;
        showStep(currentStep);
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}
