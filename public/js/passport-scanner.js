/**
 * Passport Scanner Client - WebFXScan Integration
 * Direct JavaScript integration with Plustek WebFXScan SDK
 * Connects to local WebFXScan server at ws://127.0.0.1:17778
 */

class PassportScanner {
    constructor(options = {}) {
        this.sdk = null;
        this.isConnected = false;
        this.isReady = false;
        this.scannerName = '';
        this.demoMode = false;
        this.currentDevice = null;
        
        // Configuration
        this.config = {
            serverIp: '127.0.0.1',
            serverPort: '17778',
            connectionTimeout: 10000,
            ...options
        };
        
        // Callbacks
        this.onConnected = options.onConnected || (() => {});
        this.onDisconnected = options.onDisconnected || (() => {});
        this.onScanResult = options.onScanResult || (() => {});
        this.onScanError = options.onScanError || (() => {});
        this.onScanning = options.onScanning || (() => {});
        this.onStatusChange = options.onStatusChange || (() => {});
    }
    
    /**
     * Initialize and connect to WebFXScan server
     */
    async connect() {
        try {
            console.log('[Scanner] Initializing WebFXScan SDK...');
            
            // Create SDK instance
            this.sdk = new WebFxScan();
            
            // Connect to WebFXScan server
            await this.sdk.connect({
                ip: this.config.serverIp,
                port: this.config.serverPort
            });
            
            console.log('[Scanner] Connected to WebFXScan server');
            
            // Initialize SDK
            await this.sdk.init();
            console.log('[Scanner] SDK initialized');
            
            // Get available devices
            const { data: deviceData } = await this.sdk.getDeviceList();
            const { options: devices } = deviceData;
            
            if (!devices || devices.length === 0) {
                throw new Error('No scanner devices found. Please connect scanner and restart WebFXScan server.');
            }
            
            // Select first device
            const device = devices[0];
            this.currentDevice = device;
            const { deviceName, source } = device;
            const { value: sourceOptions = [] } = source;
            
            // CRITICAL: Explicitly wait for device capability initialization
            // This ensures the device is fully open before we configure it
            console.log('[Scanner] Verifying device readiness...');
            try {
                await this.sdk.getDeviceCap({ deviceName: deviceName });
                console.log('[Scanner] Device capabilities verified and ready');
            } catch (error) {
                console.warn('[Scanner] Device cap check failed, proceeding anyway:', error);
                // Some devices may not support getDeviceCap, continue anyway
            }
            
            if (sourceOptions.length === 0) {
                throw new Error('Scanner model identification failed');
            }
            
            this.scannerName = deviceName;
            const firstSource = sourceOptions[0];
            const isCamera = firstSource === 'Camera';
            
            console.log(`[Scanner] Found device: ${deviceName}, source: ${firstSource}`);
            
            // Configure scanner for passport scanning
            const scannerConfig = {
                deviceName: deviceName,
                source: firstSource,
                resolution: 300,
                mode: 'color',
                brightness: 0,
                contrast: 0,
                quality: 75,
                recognizeType: 'passport'
            };
            
            // Camera devices need special parameters
            if (isCamera) {
                scannerConfig.paperSize = '2592x1944'; // Camera resolution
                scannerConfig.extCapturetype = 'document'; // General document scanning
            } else {
                scannerConfig.paperSize = 'A4';
            }
            
            console.log('[Scanner] Configuring scanner...', scannerConfig);
            await this.sdk.setScanner(scannerConfig);
            
            this.isConnected = true;
            this.isReady = true;
            
            console.log('[Scanner] Scanner ready for passport scanning');
            
            this.onConnected();
            this.onStatusChange({
                connected: true,
                ready: true,
                scannerName: this.scannerName,
                demoMode: false
            });
            
            return {
                success: true,
                scannerName: this.scannerName,
                deviceType: firstSource
            };
            
        } catch (error) {
            console.error('[Scanner] Connection failed:', error);
            this.isConnected = false;
            this.isReady = false;
            
            this.onDisconnected();
            this.onStatusChange({
                connected: false,
                ready: false,
                error: error.message
            });
            
            throw error;
        }
    }
    
    /**
     * Perform passport scan
     */
    async scan() {
        if (!this.isReady) {
            throw new Error('Scanner not ready. Please connect first.');
        }
        
        try {
            console.log('[Scanner] Starting passport scan...');
            this.onScanning('جاري المسح... الرجاء الانتظار');
            
            // Perform scan
            const scanResult = await this.sdk.scan();
            
            if (!scanResult.result) {
                throw new Error(scanResult.message || 'Scan failed');
            }
            
            const { data: scannedFiles } = scanResult;
            
            if (!scannedFiles || scannedFiles.length === 0) {
                throw new Error('No document scanned');
            }
            
            // Process first scanned image
            const firstFile = scannedFiles[0];
            const { base64, ocrText, fileName } = firstFile;
            
            console.log('[Scanner] Scan complete, processing data...');
            
            // Parse MRZ data from OCR text
            const passportData = this._parseMRZData(ocrText);
            
            // Extract clean base64 (remove data URI prefix if present)
            const cleanBase64 = base64.includes('base64,') 
                ? base64.split('base64,')[1] 
                : base64;
            
            const result = {
                success: true,
                image: cleanBase64,
                fileName: fileName,
                passportData: passportData,
                rawOcrText: ocrText
            };
            
            console.log('[Scanner] Passport data extracted:', passportData);
            
            this.onScanResult(result);
            return result;
            
        } catch (error) {
            console.error('[Scanner] Scan error:', error);
            const errorMessage = error.message || 'فشل المسح. الرجاء المحاولة مرة أخرى';
            this.onScanError(errorMessage);
            throw error;
        }
    }
    
    /**
     * Get scanner status
     */
    getStatus() {
        return {
            connected: this.isConnected,
            ready: this.isReady,
            scannerName: this.scannerName,
            demoMode: this.demoMode
        };
    }
    
    /**
     * Disconnect from scanner
     */
    async disconnect() {
        try {
            if (this.sdk) {
                await this.sdk.close();
            }
            this.isConnected = false;
            this.isReady = false;
            this.onDisconnected();
        } catch (error) {
            console.error('[Scanner] Disconnect error:', error);
        }
    }
    
    /**
     * Parse MRZ (Machine Readable Zone) data from OCR text
     */
    _parseMRZData(ocrText) {
        const data = {
            firstName: '',
            lastName: '',
            passportNumber: '',
            nationality: '',
            dateOfBirth: '',
            expiryDate: '',
            gender: ''
        };
        
        console.log('[Scanner] _parseMRZData received ocrText:', ocrText);
        console.log('[Scanner] ocrText type:', typeof ocrText);
        console.log('[Scanner] ocrText keys:', Object.keys(ocrText || {}));
        
        if (!ocrText || typeof ocrText !== 'object') {
            console.warn('[Scanner] Invalid ocrText, returning empty data');
            return data;
        }
        
        try {
            // WebFXScan returns fields directly in ocrText object
            // Extract passport fields using exact WebFXScan field names
            data.passportNumber = this._extractField(ocrText, ['documentno', 'passportno', 'passport_number']);
            data.firstName = this._extractField(ocrText, ['givenname', 'firstname', 'given_name']);
            data.lastName = this._extractField(ocrText, ['familyname', 'lastname', 'surname']);
            data.nationality = this._extractField(ocrText, ['nationality', 'issuestate', 'countrycode']);
            data.dateOfBirth = this._extractField(ocrText, ['birthday', 'dateofbirth', 'birthdate']);
            data.expiryDate = this._extractField(ocrText, ['dateofexpiry', 'expirydate', 'expiry_date']);
            data.gender = this._extractField(ocrText, ['sex', 'gender']);
            
            console.log('[Scanner] After field extraction:', {
                passportNumber: data.passportNumber,
                firstName: data.firstName,
                lastName: data.lastName
            });
            
            // Format dates from YYMMDD to readable format
            data.dateOfBirth = this._formatMRZDate(data.dateOfBirth);
            data.expiryDate = this._formatMRZDate(data.expiryDate);
            
            console.log('[Scanner] Parsed passport data:', data);
        } catch (error) {
            console.warn('[Scanner] MRZ parsing error:', error);
        }
        
        return data;
    }
    
    /**
     * Extract field value from multiple possible field names
     */
    _extractField(fields, possibleNames) {
        for (const name of possibleNames) {
            const lowerName = name.toLowerCase();
            for (const key in fields) {
                if (key.toLowerCase() === lowerName) {
                    const value = fields[key];
                    return typeof value === 'string' ? value.trim() : String(value || '');
                }
            }
        }
        return '';
    }
    
    /**
     * Format MRZ date (YYMMDD) to readable format (DD/MM/YYYY)
     */
    _formatMRZDate(dateStr) {
        if (!dateStr || dateStr.length !== 6) {
            return dateStr;
        }
        
        try {
            const yy = parseInt(dateStr.substring(0, 2));
            const mm = dateStr.substring(2, 4);
            const dd = dateStr.substring(4, 6);
            
            // Determine century (assume < 30 is 2000s, >= 30 is 1900s)
            const yyyy = yy < 30 ? 2000 + yy : 1900 + yy;
            
            return `${dd}/${mm}/${yyyy}`;
        } catch (error) {
            return dateStr;
        }
    }
    
    /**
     * Sleep helper
     */
    _sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }
}

// Make available globally
window.PassportScanner = PassportScanner;
