/**
 * Debug Panel Functions
 */

let debugVisible = false;

document.addEventListener('keydown', function(e) {
    if (e.ctrlKey && e.shiftKey && e.key === 'D') {
        debugVisible = !debugVisible;
        document.getElementById('debug-panel').style.display = debugVisible ? 'block' : 'none';
    }
});

function updateDebugPanel() {
    const panel = document.getElementById('debug-info');
    if (panel && scannerClient) {
        panel.innerHTML = `
            <strong>WebSocket:</strong> ${scannerClient.wsUrl}<br>
            <strong>Connected:</strong> ${scannerClient.isConnected ? '✅ Yes' : '❌ No'}<br>
            <strong>Scanner Ready:</strong> ${scannerClient.scannerReady ? '✅ Yes' : '❌ No'}<br>
            <strong>Scanner Name:</strong> ${scannerClient.scannerName || 'N/A'}<br>
            <strong>Demo Mode:</strong> ${scannerClient.demoMode ? 'Yes' : 'No'}<br>
            <strong>SDK Version:</strong> ${scannerClient.sdkVersion || 'N/A'}<br>
            <strong>Last Update:</strong> ${new Date().toLocaleTimeString()}
        `;
    }
}

setInterval(updateDebugPanel, 1000);
