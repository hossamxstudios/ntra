using System;
using System.IO;
using ScannerAgent.Models;

namespace ScannerAgent.Services
{
    public class ScannerService
    {
        private bool _isInitialized = false;
        private string _scannerName = "SecureScan X50";

        public bool IsReady => _isInitialized;
        public string ScannerName => _scannerName;

        public bool Initialize()
        {
            try
            {
                Console.WriteLine("[INIT] Initializing SecureScan SDK...");
                
                // TODO: Initialize SecureScan SDK here
                // When you have the SDK DLL, uncomment and modify:
                //
                // SecureScanLib.Initialize();
                // var scanners = SecureScanLib.GetAvailableScanners();
                // if (scanners.Count == 0)
                // {
                //     Console.WriteLine("[ERROR] No scanner found!");
                //     return false;
                // }
                // _scannerName = scanners[0].Name;
                // SecureScanLib.SelectScanner(scanners[0]);

                // For now, simulate initialization
                System.Threading.Thread.Sleep(500);
                _isInitialized = true;
                
                Console.WriteLine($"[INIT] Scanner ready: {_scannerName}");
                return true;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[ERROR] Failed to initialize: {ex.Message}");
                return false;
            }
        }

        public ScanResult ScanPassport()
        {
            if (!_isInitialized)
            {
                return ScanResult.Failed("Scanner not initialized");
            }

            try
            {
                Console.WriteLine("[SCAN] Starting passport scan...");

                // TODO: Use SecureScan SDK to scan
                // When you have the SDK DLL:
                //
                // var scanData = SecureScanLib.Scan();
                // if (scanData == null || !scanData.Success)
                // {
                //     return ScanResult.Failed("Scan failed - no document detected");
                // }
                //
                // var mrzData = SecureScanLib.ReadMRZ(scanData.Image);
                // 
                // return new ScanResult
                // {
                //     Success = true,
                //     FirstName = mrzData.FirstName,
                //     LastName = mrzData.LastName,
                //     PassportNumber = mrzData.DocumentNumber,
                //     Nationality = mrzData.Nationality,
                //     DateOfBirth = mrzData.DateOfBirth,
                //     ExpiryDate = mrzData.ExpiryDate,
                //     Gender = mrzData.Gender,
                //     ImageBase64 = Convert.ToBase64String(scanData.ImageBytes)
                // };

                // ============================================
                // DEMO MODE - Returns fake data for testing
                // Remove this when SDK is integrated
                // ============================================
                Console.WriteLine("[SCAN] Demo mode - returning test data");
                System.Threading.Thread.Sleep(1000); // Simulate scan time

                // Generate a demo image (1x1 white pixel as placeholder)
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
                Console.WriteLine($"[ERROR] Scan failed: {ex.Message}");
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

            // Return a minimal placeholder image
            // This is a tiny 100x60 gray PNG
            return "iVBORw0KGgoAAAANSUhEUgAAAGQAAAA8CAIAAAArtuOmAAAACXBIWXMAAA" +
                   "sTAAALEwEAmpwYAAAAB3RJTUUH6AEeAgMwNxMHBgAAAB1pVFh0Q29tbWV" +
                   "udAAAAAAAQ3JlYXRlZCB3aXRoIEdJTVBkLmUHAAAAPklEQVR42u3BAQEA" +
                   "AAgCoPr/tDcBCwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA" +
                   "AAAAAAAAAAAA7wYV5AAB3znEzAAAAABJRU5ErkJggg==";
        }

        public void Dispose()
        {
            // TODO: Cleanup SDK resources
            // SecureScanLib.Dispose();
            _isInitialized = false;
        }
    }
}
