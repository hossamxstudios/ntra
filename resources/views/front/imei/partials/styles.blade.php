<style>
    body { background: linear-gradient(135deg, #f8fafc 0%, #eeeeee 100%); min-height: 100vh; }
    .kiosk-title { color: #1e3a5f; }
    .wizard-step { display: none; }
    .wizard-step.active { display: block; }
    .step-indicator {
        width: 44px; height: 44px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: bold; font-size: 18px;
        background: #e9ecef; color: #6c757d;
        transition: all 0.3s;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .step-indicator.active { background: #000000; color: #ffffff; font-weight: 800; box-shadow: 0 4px 12px rgba(159, 160, 160, 0.4); }
    .step-indicator.completed { background: #212529; color: white; }
    .step-line { height: 4px; background: #e9ecef; flex: 1; margin: 0 8px; border-radius: 2px; }
    .step-line.completed { background: #212529; }
    .step-label { font-size: 14px; font-weight: 500; margin-top: 8px; }
    .scanner-area {
        border: 3px dashed #dee2e6;
        border-radius: 16px;
        padding: 40px 30px;
        text-align: center;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.3s;
    }
    .scanner-area:hover { border-color: #0d6efd; background: #e7f1ff; }
    .scanner-area.has-data { border-color: #198754; background: #d1e7dd; }
    .scanner-area i { margin-bottom: 15px; }
    .scanner-area p { font-size: 16px; }
    .camera-preview {
        width: 100%; max-width: 320px; height: 240px;
        background: #000; border-radius: 16px;
        margin: 0 auto; overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }
    .camera-preview video { width: 100%; height: 100%; object-fit: cover; }
    .captured-photo { max-width: 200px; max-height: 200px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); }
    .wizard-card { border-radius: 20px; border: none; max-width: 1000px; margin: 0 auto; }
    .wizard-card .card-body { padding: 1.5rem 2rem; }
    .section-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; }
    .info-box { padding: 1rem; border-radius: 12px; }
    .info-box .info-row { padding: 0.5rem 0; font-size: 0.9rem; }
    .form-control { font-size: 0.9rem; padding: 0.5rem 0.75rem; border-radius: 8px; }
    .form-control-sm { font-size: 0.85rem; padding: 0.35rem 0.6rem; }
    .form-select-sm { font-size: 0.85rem; padding: 0.35rem 0.6rem; }
    .form-label { font-size: 0.85rem; font-weight: 600; margin-bottom: 0.25rem; }
    .btn { font-size: 0.9rem; padding: 0.5rem 1rem; border-radius: 8px; }
    .file-input-hidden { position: absolute; opacity: 0; width: 100%; height: 100%; cursor: pointer; top: 0; left: 0; }
    .scanner-area { position: relative; overflow: hidden; }
    .recap-card { background: #fff; border-radius: 12px; padding: 1.25rem; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
    .recap-title { font-size: 1rem; font-weight: 700; border-bottom: 2px solid #e9ecef; padding-bottom: 0.75rem; margin-bottom: 0.75rem; }
    .recap-item { font-size: 0.95rem; padding: 0.4rem 0; }
    .recap-preview { height: 100px; display: flex; align-items: center; justify-content: center; }
</style>
