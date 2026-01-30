/**
 * SecureScan WebSocket Client
 * Connects to the local scanner agent and handles passport scanning
 */

class ScannerClient {
    constructor(options = {}) {
        this.wsUrl = options.wsUrl || 'ws://localhost:9001';
        this.ws = null;
        this.isConnected = false;
        this.scannerReady = false;
        this.reconnectAttempts = 0;
        this.maxReconnectAttempts = 5;
        this.reconnectDelay = 2000;

        // Callbacks
        this.onConnected = options.onConnected || (() => {});
        this.onDisconnected = options.onDisconnected || (() => {});
        this.onScanResult = options.onScanResult || (() => {});
        this.onScanError = options.onScanError || (() => {});
        this.onScanning = options.onScanning || (() => {});
        this.onStatusChange = options.onStatusChange || (() => {});
    }

    connect() {
        console.log('[Scanner] Connecting to agent...');

        try {
            this.ws = new WebSocket(this.wsUrl);

            this.ws.onopen = () => {
                console.log('[Scanner] Connected to agent');
                this.isConnected = true;
                this.reconnectAttempts = 0;
                this.onConnected();
                this.onStatusChange({ connected: true, ready: this.scannerReady });
            };

            this.ws.onclose = () => {
                console.log('[Scanner] Disconnected from agent');
                this.isConnected = false;
                this.scannerReady = false;
                this.onDisconnected();
                this.onStatusChange({ connected: false, ready: false });
                this.attemptReconnect();
            };

            this.ws.onerror = (error) => {
                console.error('[Scanner] WebSocket error:', error);
            };

            this.ws.onmessage = (event) => {
                this.handleMessage(event.data);
            };
        } catch (error) {
            console.error('[Scanner] Failed to connect:', error);
            this.attemptReconnect();
        }
    }

    attemptReconnect() {
        if (this.reconnectAttempts < this.maxReconnectAttempts) {
            this.reconnectAttempts++;
            console.log(`[Scanner] Reconnecting in ${this.reconnectDelay}ms (attempt ${this.reconnectAttempts}/${this.maxReconnectAttempts})`);
            setTimeout(() => this.connect(), this.reconnectDelay);
        } else {
            console.error('[Scanner] Max reconnection attempts reached');
        }
    }

    handleMessage(data) {
        try {
            const message = JSON.parse(data);
            console.log('[Scanner] Received:', message.type);

            switch (message.type) {
                case 'connected':
                    this.scannerReady = message.scannerReady;
                    this.onStatusChange({ connected: true, ready: this.scannerReady });
                    break;

                case 'scanning':
                    this.onScanning(message.message);
                    break;

                case 'scan_result':
                    if (message.success) {
                        this.onScanResult(message.data);
                    } else {
                        this.onScanError(message.error);
                    }
                    break;

                case 'status':
                    this.scannerReady = message.scannerReady;
                    this.onStatusChange({
                        connected: true,
                        ready: this.scannerReady,
                        scannerName: message.scannerName
                    });
                    break;

                case 'pong':
                    // Keep-alive response
                    break;

                case 'error':
                    console.error('[Scanner] Agent error:', message.message);
                    this.onScanError(message.message);
                    break;
            }
        } catch (error) {
            console.error('[Scanner] Failed to parse message:', error);
        }
    }

    send(data) {
        if (this.isConnected && this.ws) {
            this.ws.send(JSON.stringify(data));
        } else {
            console.error('[Scanner] Not connected');
        }
    }

    scan() {
        if (!this.isConnected) {
            this.onScanError('غير متصل بجهاز المسح');
            return;
        }
        this.send({ action: 'scan' });
    }

    getStatus() {
        this.send({ action: 'status' });
    }

    ping() {
        this.send({ action: 'ping' });
    }

    disconnect() {
        if (this.ws) {
            this.ws.close();
        }
    }
}

// Export ScannerClient class globally
window.ScannerClient = ScannerClient;
