<!-- Debug Panel (Press Ctrl+Shift+D to toggle) -->
<div id="debug-panel" style="display:none; position:fixed; bottom:10px; right:10px; background:#222; color:#0f0; padding:15px; border-radius:8px; font-family:monospace; font-size:12px; z-index:9999; max-width:350px;">
    <div style="margin-bottom:8px; border-bottom:1px solid #444; padding-bottom:5px;">
        <strong>🔧 Scanner Debug Panel</strong> <small>(Ctrl+Shift+D)</small>
    </div>
    <div id="debug-info">Loading...</div>
    <div style="margin-top:10px; border-top:1px solid #444; padding-top:8px;">
        <button onclick="scannerClient && scannerClient.getStatus()" style="margin-right:5px; padding:3px 8px;">Get Status</button>
        <button onclick="scannerClient && scannerClient.ping()" style="margin-right:5px; padding:3px 8px;">Ping</button>
        <button onclick="scannerClient && scannerClient.scan()" style="padding:3px 8px;">Test Scan</button>
    </div>
</div>
