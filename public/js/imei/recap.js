/**
 * Recap Functions
 */

function updateRecap() {
    document.getElementById('recapSerial').textContent = document.getElementById('serialNumber').value || '-';
    document.getElementById('recapName').textContent =
        (document.getElementById('givenName').value || '') + ' ' +
        (document.getElementById('familyName').value || '');
    document.getElementById('recapPassport').textContent = document.getElementById('documentNo').value || '-';
    document.getElementById('recapNationality').textContent = document.getElementById('nationality').value || '-';

    // Show captured photo in recap
    const photoData = document.getElementById('passengerPhotoData').value;
    if (photoData) {
        document.getElementById('recapPhoto').innerHTML = `<img src="${photoData}" style="max-height:60px;border-radius:4px;">`;
    }

    // Show passport image in recap (from base64)
    const passportBase64 = document.getElementById('passport_image_base64').value;
    if (passportBase64) {
        document.getElementById('recapPassportImg').innerHTML = `<img src="data:image/jpeg;base64,${passportBase64}" style="max-height:60px;border-radius:4px;">`;
    }

    // Show arrival stamp in recap (from base64)
    const arrivalBase64 = document.getElementById('arrival_image_base64').value;
    if (arrivalBase64) {
        document.getElementById('recapArrival').innerHTML = `<img src="data:image/jpeg;base64,${arrivalBase64}" style="max-height:60px;border-radius:4px;">`;
    }

    // Show boarding pass in recap (from base64)
    const boardingBase64 = document.getElementById('boarding_image_base64').value;
    if (boardingBase64) {
        document.getElementById('recapBoarding').innerHTML = `<img src="data:image/jpeg;base64,${boardingBase64}" style="max-height:60px;border-radius:4px;">`;
    }
}
