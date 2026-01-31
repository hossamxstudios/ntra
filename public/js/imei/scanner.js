/**
 * WebFXScan Passport Scanner Integration
 */

let scannerClient = null;

function initScanner() {
    scannerClient = new PassportScanner({
        onConnected: () => {
            console.log('[Scanner] Connected to WebFXScan');
            updateScannerUI(true, true);
        },
        onDisconnected: () => {
            console.log('[Scanner] Disconnected from WebFXScan');
            updateScannerUI(false, false);
        },
        onStatusChange: (status) => {
            console.log('[Scanner] Status change:', status);
            updateScannerUI(status.connected, status.ready);
        },
        onScanning: (message) => {
            document.getElementById('scan-preview').innerHTML = `
                <div class="text-center">
                    <div class="mb-2 spinner-border text-primary" role="status"></div>
                    <p class="mb-0">${message}</p>
                </div>
            `;
        },
        onScanResult: (data) => {
            handlePassportScanResult(data);
        },
        onScanError: (error) => {
            document.getElementById('scan-preview').innerHTML = `
                <div class="text-center text-danger">
                    <i data-lucide="alert-circle" style="width:48px;height:48px;"></i>
                    <p class="mb-1">${error}</p>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="startPassportScan()">إعادة المحاولة</button>
                </div>
            `;
            lucide.createIcons();
        }
    });

    // Connect to WebFXScan server
    scannerClient.connect().catch(error => {
        console.error('[Scanner] Connection failed:', error);
        alert('فشل الاتصال بالماسح الضوئي. تأكد من تشغيل Plustek WebFXScan server على المنفذ 17778.');
    });
}

function updateScannerUI(connected, ready) {
    // Update all scanner status indicators
    const statusIds = ['scanner-status', 'arrival-scanner-status', 'boarding-scanner-status'];

    statusIds.forEach(id => {
        const statusEl = document.getElementById(id);
        if (!statusEl) return;

        if (connected && ready) {
            statusEl.className = 'badge bg-success';
            statusEl.innerHTML = '<i data-lucide="check-circle" style="width:12px;height:12px;"></i> الماسح جاهز';
        } else if (connected) {
            statusEl.className = 'badge bg-warning';
            statusEl.innerHTML = '<i data-lucide="loader" style="width:12px;height:12px;"></i> جاري التحضير...';
        } else {
            statusEl.className = 'badge bg-danger';
            statusEl.innerHTML = '<i data-lucide="x-circle" style="width:12px;height:12px;"></i> غير متصل';
        }
    });

    lucide.createIcons();
}

function startPassportScan() {
    if (!scannerClient || !scannerClient.isConnected) {
        alert('الماسح غير متصل. تأكد من تشغيل برنامج SecureScan Agent.');
        return;
    }
    scannerClient.scan();
}

// Format date from YYMMDD to YYYY-MM-DD (for date input)
function formatPassportDate(dateStr) {
    if (!dateStr || dateStr.length !== 6) return '';
    const yy = dateStr.substring(0, 2);
    const mm = dateStr.substring(2, 4);
    const dd = dateStr.substring(4, 6);
    // Assume 20xx for years 00-30, 19xx for 31-99
    const year = parseInt(yy) <= 30 ? '20' + yy : '19' + yy;
    return `${year}-${mm}-${dd}`;
}

// Format sex from M/F to Arabic (for select)
function formatSex(sex) {
    if (sex === 'M') return 'ذكر';
    if (sex === 'F') return 'أنثى';
    return '';
}

// Format document type
function formatDocumentType(type) {
    if (type === 'P') return 'جواز سفر';
    if (type === 'ID') return 'بطاقة هوية';
    return type || '';
}

function handlePassportScanResult(data) {
    console.log('[DEBUG] handlePassportScanResult called with:', data);

    // Extract data from scanner response structure
    // Structure: { API: "scan", return: { result: true, data: [{ base64, ocrText: {...} }] } }
    let imageBase64 = '';
    let ocrText = {};

    // Handle the actual scanner response structure
    if (data.return && data.return.result && data.return.data && data.return.data.length > 0) {
        const scanData = data.return.data[0];
        imageBase64 = scanData.base64 || '';
        ocrText = scanData.ocrText || {};
        console.log('[DEBUG] Extracted from scanner response:', { imageBase64: imageBase64.substring(0, 50) + '...', ocrText });
    }
    // Fallback for other response formats
    else if (data.data && data.data.length > 0) {
        const scanData = data.data[0];
        imageBase64 = scanData.base64 || '';
        ocrText = scanData.ocrText || {};
    }
    // Direct format fallback
    else {
        imageBase64 = data.base64 || data.image || '';
        ocrText = data.ocrText || data.passportData || {};
    }

    // Remove data URI prefix if present for storage, keep for display
    let imageForStorage = imageBase64;
    if (imageBase64.startsWith('data:image')) {
        imageForStorage = imageBase64.split(',')[1] || imageBase64;
    }

    console.log('[DEBUG] ocrText:', ocrText);

    // Show scanned image
    if (imageBase64) {
        const displayImage = imageBase64.startsWith('data:') ? imageBase64 : `data:image/jpeg;base64,${imageBase64}`;
        document.getElementById('scan-preview').innerHTML = `
            <img src="${displayImage}" class="rounded img-fluid" style="max-height:120px;">
            <p class="mt-2 mb-0 text-success small"><i data-lucide="check-circle" style="width:14px;height:14px;"></i> تم المسح بنجاح</p>
        `;
    } else {
        document.getElementById('scan-preview').innerHTML = `
            <div class="text-warning">
                <i data-lucide="alert-triangle" style="width:48px;height:48px;"></i>
                <p class="mb-0">تم المسح ولكن لا توجد صورة</p>
            </div>
        `;
    }
    lucide.createIcons();

    // Store base64 image (without data URI prefix)
    document.getElementById('passport_image_base64').value = imageForStorage;

    // Store MRZ data
    if (ocrText.MRTDs) {
        document.getElementById('passportMRZ').value = ocrText.MRTDs;
    }

    // Fill form fields from ocrText object (matching scanner data structure)
    document.getElementById('givenName').value = ocrText.Givenname || '';
    document.getElementById('familyName').value = ocrText.Familyname || '';
    document.getElementById('documentNo').value = ocrText.DocumentNo || '';
    document.getElementById('nationality').value = ocrText.Nationality || '';
    document.getElementById('birthday').value = formatPassportDate(ocrText.Birthday);
    document.getElementById('sex').value = formatSex(ocrText.Sex);
    document.getElementById('expiryDate').value = formatPassportDate(ocrText.Dateofexpiry);
    document.getElementById('issueState').value = ocrText.IssueState || '';
    document.getElementById('documentType').value = formatDocumentType(ocrText.Type);

    console.log('[DEBUG] Form fields filled:');
    console.log('[DEBUG] - givenName:', document.getElementById('givenName').value);
    console.log('[DEBUG] - familyName:', document.getElementById('familyName').value);
    console.log('[DEBUG] - documentNo:', document.getElementById('documentNo').value);
    console.log('[DEBUG] - nationality:', document.getElementById('nationality').value);
    console.log('[DEBUG] - birthday:', document.getElementById('birthday').value);
    console.log('[DEBUG] - sex:', document.getElementById('sex').value);
    console.log('[DEBUG] - expiryDate:', document.getElementById('expiryDate').value);
    console.log('[DEBUG] - issueState:', document.getElementById('issueState').value);
    console.log('[DEBUG] - documentType:', document.getElementById('documentType').value);

    // Mark scanner area as has-data
    document.getElementById('passportScanArea').classList.add('has-data');

    // Update status
    const checksumStatus = ocrText.Checksum ? '✓ MRZ صحيح' : '⚠ تحقق من البيانات';
    document.getElementById('scan-modal-status').innerHTML = `<span class="text-success">✓ تم قراءة بيانات جواز السفر - ${checksumStatus}</span>`;
}

// Arrival Stamp Scanning
function startArrivalScan() {
    if (!scannerClient || !scannerClient.isConnected) {
        alert('الماسح غير متصل. تأكد من تشغيل برنامج SecureScan Agent.');
        return;
    }

    // Show scanning state
    document.getElementById('arrival-preview').innerHTML = `
        <div class="text-center">
            <div class="mb-2 spinner-border text-primary" role="status"></div>
            <p class="mb-0">جاري مسح ختم الوصول...</p>
        </div>
    `;

    // Use a custom handler for arrival scan
    const originalHandler = scannerClient.onScanResult;
    scannerClient.onScanResult = (data) => {
        handleArrivalScanResult(data);
        scannerClient.onScanResult = originalHandler;
    };

    scannerClient.scan();
}

function handleArrivalScanResult(data) {
    console.log('[DEBUG] Arrival scan result:', data);

    const imageBase64 = data.image || data.imageBase64 || '';

    document.getElementById('arrival-preview').innerHTML = `
        <img src="data:image/jpeg;base64,${imageBase64}" class="rounded img-fluid" style="max-height:120px;">
        <p class="mt-2 mb-0 text-success small"><i data-lucide="check-circle" style="width:14px;height:14px;"></i> تم المسح بنجاح</p>
    `;
    lucide.createIcons();

    document.getElementById('arrival_image_base64').value = imageBase64;
    document.getElementById('arrivalScanArea').classList.add('has-data');
}

// Boarding Pass Scanning
function startBoardingScan() {
    if (!scannerClient || !scannerClient.isConnected) {
        alert('الماسح غير متصل. تأكد من تشغيل برنامج SecureScan Agent.');
        return;
    }

    // Show scanning state
    document.getElementById('boarding-preview').innerHTML = `
        <div class="text-center">
            <div class="mb-2 spinner-border text-primary" role="status"></div>
            <p class="mb-0">جاري مسح بطاقة الصعود...</p>
        </div>
    `;

    // Use a custom handler for boarding scan
    const originalHandler = scannerClient.onScanResult;
    scannerClient.onScanResult = (data) => {
        handleBoardingScanResult(data);
        scannerClient.onScanResult = originalHandler;
    };

    scannerClient.scan();
}

function handleBoardingScanResult(data) {
    console.log('[DEBUG] Boarding card scan result:', data);

    const imageBase64 = data.image || data.imageBase64 || '';

    document.getElementById('boarding-preview').innerHTML = `
        <img src="data:image/jpeg;base64,${imageBase64}" class="rounded img-fluid" style="max-height:120px;">
        <p class="mt-2 mb-0 text-success small"><i data-lucide="check-circle" style="width:14px;height:14px;"></i> تم المسح بنجاح</p>
    `;
    lucide.createIcons();

    document.getElementById('boarding_image_base64').value = imageBase64;
    document.getElementById('boardingScanArea').classList.add('has-data');
}
