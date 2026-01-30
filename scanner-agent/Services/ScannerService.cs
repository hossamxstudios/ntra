using System;
using System.IO;
using ScannerAgent.Models;
using ScannerAgent.SDK;

namespace ScannerAgent.Services
{
    /// <summary>
    /// Scanner service that manages SecureScan SDK operations.
    /// Automatically falls back to demo mode if SDK is not available.
    /// </summary>
    public class ScannerService : IDisposable
    {
        private SecureScanWrapper? _sdk;
        private bool _isInitialized = false;
        private bool _isDemoMode = false;
        private string _scannerName = "SecureScan X50";
        private bool _disposed = false;

        public bool IsReady => _isInitialized;
        public string ScannerName => _isDemoMode ? $"{_scannerName} (Demo)" : _scannerName;
        public bool IsDemoMode => _isDemoMode;

        public bool Initialize()
        {
            try
            {
                Console.WriteLine("[INIT] Initializing SecureScan SDK...");
                
                // Try to initialize real SDK
                _sdk = new SecureScanWrapper();
                
                if (_sdk.Initialize())
                {
                    _isInitialized = true;
                    _isDemoMode = false;
                    _scannerName = _sdk.ScannerName;
                    Console.WriteLine($"[INIT] SDK initialized successfully - Scanner: {_scannerName}");
                    return true;
                }
                else
                {
                    Console.WriteLine("[INIT] SDK initialization failed, falling back to demo mode");
                    _sdk.Dispose();
                    _sdk = null;
                }
            }
            catch (DllNotFoundException ex)
            {
                Console.WriteLine($"[INIT] SDK DLL not found: {ex.Message}");
                Console.WriteLine("[INIT] Falling back to demo mode");
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[INIT] SDK error: {ex.Message}");
                Console.WriteLine("[INIT] Falling back to demo mode");
            }

            // Fall back to demo mode
            return InitializeDemoMode();
        }

        private bool InitializeDemoMode()
        {
            Console.WriteLine("[INIT] Starting in DEMO MODE");
            Console.WriteLine("[INIT] Demo mode returns test data - connect scanner for real scanning");
            
            System.Threading.Thread.Sleep(500); // Simulate init delay
            
            _isInitialized = true;
            _isDemoMode = true;
            _scannerName = "SecureScan X50";
            
            Console.WriteLine($"[INIT] Demo mode ready: {_scannerName}");
            return true;
        }

        public ScanResult ScanPassport()
        {
            if (!_isInitialized)
            {
                return ScanResult.Failed("Scanner not initialized");
            }

            // Use real SDK if available
            if (!_isDemoMode && _sdk != null)
            {
                return ScanWithSDK();
            }

            // Demo mode
            return ScanDemoMode();
        }

        private ScanResult ScanWithSDK()
        {
            try
            {
                Console.WriteLine("[SCAN] Starting real passport scan...");
                
                if (_sdk == null)
                {
                    return ScanResult.Failed("SDK not initialized");
                }

                var result = _sdk.ScanPassport();
                
                if (result.Success)
                {
                    Console.WriteLine($"[SCAN] Success - {result.FirstName} {result.LastName}");
                }
                else
                {
                    Console.WriteLine($"[SCAN] Failed - {result.ErrorMessage}");
                }

                return result;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[SCAN] SDK exception: {ex.Message}");
                return ScanResult.Failed($"Scan error: {ex.Message}");
            }
        }

        private ScanResult ScanDemoMode()
        {
            try
            {
                Console.WriteLine("[SCAN] Demo mode - returning test data");
                System.Threading.Thread.Sleep(1500); // Simulate scan time

                string demoImageBase64 = GetDemoPassportImage();

                return new ScanResult
                {
                    Success = true,
                    FirstName = "AHMED",
                    LastName = "MOHAMED",
                    PassportNumber = "A12345678",
                    Nationality = "EGY",
                    DateOfBirth = "1990-05-15",
                    ExpiryDate = "2028-05-14",
                    Gender = "M",
                    ImageBase64 = demoImageBase64
                };
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[SCAN] Demo mode error: {ex.Message}");
                return ScanResult.Failed(ex.Message);
            }
        }

        private string GetDemoPassportImage()
        {
            // Check if demo image exists
            string demoImagePath = Path.Combine(AppDomain.CurrentDomain.BaseDirectory, "demo-passport.jpg");
            
            if (File.Exists(demoImagePath))
            {
                byte[] imageBytes = File.ReadAllBytes(demoImagePath);
                return Convert.ToBase64String(imageBytes);
            }

            // Return a minimal placeholder image (100x60 gray PNG)
            return "iVBORw0KGgoAAAANSUhEUgAAAGQAAAA8CAIAAAArtuOmAAAACXBIWXMAAA" +
                   "sTAAALEwEAmpwYAAAAB3RJTUUH6AEeAgMwNxMHBgAAAB1pVFh0Q29tbWV" +
                   "udAAAAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAAAPklEQVR42u3BAQEA" +
                   "AAgCoPr/tDcBCwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" +
                   "AAAAAAAAAAAA7wYV5AAB3znEzAAAAABJRU5ErkJggg==";
        }

        /// <summary>
        /// Check if document is present on scanner
        /// </summary>
        public bool IsDocumentPresent()
        {
            if (_isDemoMode || _sdk == null)
            {
                return true; // Always ready in demo mode
            }
            return _sdk.IsDocumentPresent();
        }

        /// <summary>
        /// Check if scanner hardware is ready
        /// </summary>
        public bool IsScannerReady()
        {
            if (_isDemoMode)
            {
                return true;
            }
            return _sdk?.IsScannerReady() ?? false;
        }

        /// <summary>
        /// Get SDK version information
        /// </summary>
        public string GetSDKVersion()
        {
            if (_isDemoMode)
            {
                return "Demo Mode";
            }
            return _sdk?.GetVersion() ?? "Unknown";
        }

        public void Dispose()
        {
            if (!_disposed)
            {
                _sdk?.Dispose();
                _sdk = null;
                _isInitialized = false;
                _disposed = true;
            }
        }
    }
}
