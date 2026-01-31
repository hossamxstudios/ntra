/**
 * Initialize all components when page loads
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Lucide icons
    lucide.createIcons();

    // Serial number scanner listener
    document.getElementById('serialNumber').addEventListener('focus', function() {
        document.getElementById('serialScanArea').classList.add('has-data');
    });

    // Initialize scanner after a short delay
    setTimeout(function() {
        if (typeof PassportScanner !== 'undefined') {
            initScanner();
            console.log('[DEBUG] PassportScanner initialized');
        } else {
            console.error('[DEBUG] PassportScanner class not found! Check if passport-scanner.js is loaded.');
        }
    }, 500);
});
