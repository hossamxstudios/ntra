/**
 * Camera Functions for Passenger Photo
 */

let cameraStream = null;

async function startCamera() {
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
        document.getElementById('cameraVideo').srcObject = cameraStream;
        document.getElementById('cameraPreview').style.display = 'block';
        document.getElementById('capturedPhotoContainer').style.display = 'none';
        document.getElementById('captureBtn').style.display = 'inline-block';
        document.getElementById('retakeBtn').style.display = 'none';
    } catch (err) {
        console.error('Camera error:', err);
        alert('لا يمكن الوصول إلى الكاميرا');
    }
}

function stopCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(track => track.stop());
        cameraStream = null;
    }
}

function capturePhoto() {
    const video = document.getElementById('cameraVideo');
    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    canvas.getContext('2d').drawImage(video, 0, 0);

    const dataUrl = canvas.toDataURL('image/jpeg');
    document.getElementById('capturedPhoto').src = dataUrl;
    document.getElementById('passengerPhotoData').value = dataUrl;

    document.getElementById('cameraPreview').style.display = 'none';
    document.getElementById('capturedPhotoContainer').style.display = 'block';
    document.getElementById('captureBtn').style.display = 'none';
    document.getElementById('retakeBtn').style.display = 'inline-block';

    stopCamera();
}

function retakePhoto() {
    startCamera();
}
