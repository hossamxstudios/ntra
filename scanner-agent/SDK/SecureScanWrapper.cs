using System;
using System.IO;
using System.Runtime.InteropServices;
using Newtonsoft.Json.Linq;
using ScannerAgent.Models;

namespace ScannerAgent.SDK
{
    /// <summary>
    /// Wrapper for Plustek LibWebFXScan SDK using P/Invoke.
    /// </summary>
    public class SecureScanWrapper : IDisposable
    {
        [DllImport("kernel32.dll", CharSet = CharSet.Unicode, SetLastError = true)]
        private static extern bool SetDllDirectory(string lpPathName);

        [DllImport("kernel32.dll", CharSet = CharSet.Unicode, SetLastError = true)]
        private static extern IntPtr AddDllDirectory(string lpPathName);

        [DllImport("kernel32.dll", SetLastError = true)]
        private static extern bool SetDefaultDllDirectories(uint DirectoryFlags);

        private const uint LOAD_LIBRARY_SEARCH_DEFAULT_DIRS = 0x00001000;

        private bool _isInitialized = false;
        private bool _disposed = false;
        private string _scannerName = "Plustek Scanner";
        private string _sdkVersion = "2.x";
        private string _deviceName = "";
        private PlustekSDK.LIBWFXEVENTCB? _eventCallback;

        // Plustek installation paths
        private static readonly string[] PlustekPaths = new[]
        {
            @"C:\Program Files\Plustek\WebFXScan2",
            @"C:\Program Files\Plustek\AviScanProcess",
            @"C:\Program Files\Plustek\Plustek SecureScan Series V6.4.4.0"
        };

        public string ScannerName => _scannerName;
        public string Version => _sdkVersion;

        private void SetupDllPaths()
        {
            try
            {
                // Add Plustek directories to DLL search path
                foreach (var path in PlustekPaths)
                {
                    if (Directory.Exists(path))
                    {
                        Console.WriteLine($"[SDK] Adding DLL path: {path}");
                        SetDllDirectory(path);
                        AddDllDirectory(path);
                    }
                }

                // Also try setting PATH environment variable
                var currentPath = Environment.GetEnvironmentVariable("PATH") ?? "";
                var newPaths = string.Join(";", PlustekPaths.Where(Directory.Exists));
                if (!string.IsNullOrEmpty(newPaths))
                {
                    Environment.SetEnvironmentVariable("PATH", newPaths + ";" + currentPath);
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[SDK] Error setting DLL paths: {ex.Message}");
            }
        }

        public bool Initialize()
        {
            try
            {
                Console.WriteLine("[SDK] Initializing Plustek LibWebFXScan SDK...");

                // Setup DLL search paths first
                SetupDllPaths();

                // Initialize SDK
                var result = PlustekSDK.LibWFX_InitEx(ENUM_LIBWFX_INIT_MODE.LIBWFX_INIT_MODE_NORMAL);

                if (result == ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_NO_OCR)
                {
                    Console.WriteLine("[SDK] Warning: No OCR tool installed, continuing without OCR");
                }
                else if (result == ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_NO_AVI_OCR)
                {
                    Console.WriteLine("[SDK] Warning: No AVI OCR tool, continuing");
                }
                else if (result != ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_SUCCESS && 
                         result != ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_ALREADY_INIT)
                {
                    Console.WriteLine($"[SDK] LibWFX_InitEx failed with code: {result}");
                    return false;
                }

                // Get available devices
                IntPtr deviceListPtr;
                result = PlustekSDK.LibWFX_GetDevicesList(out deviceListPtr);
                
                if (result == ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_SUCCESS && deviceListPtr != IntPtr.Zero)
                {
                    string deviceList = Marshal.PtrToStringUni(deviceListPtr) ?? "";
                    Console.WriteLine($"[SDK] Available devices: {deviceList}");
                    
                    if (!string.IsNullOrEmpty(deviceList))
                    {
                        // Try to parse as JSON array first (newer SDK versions)
                        if (deviceList.TrimStart().StartsWith("["))
                        {
                            try
                            {
                                var jsonArray = JArray.Parse(deviceList);
                                if (jsonArray.Count > 0)
                                {
                                    _deviceName = jsonArray[0].ToString();
                                    _scannerName = _deviceName;
                                    Console.WriteLine($"[SDK] Selected device: {_deviceName}");
                                }
                            }
                            catch
                            {
                                // Fallback to string parsing
                                _deviceName = deviceList.Trim('[', ']', '"', ' ');
                                _scannerName = _deviceName;
                                Console.WriteLine($"[SDK] Selected device: {_deviceName}");
                            }
                        }
                        else
                        {
                            // Parse device list (format: device1|&|device2|&|...)
                            var devices = deviceList.Split(new[] { "|&|" }, StringSplitOptions.RemoveEmptyEntries);
                            if (devices.Length > 0)
                            {
                                _deviceName = devices[0].Trim();
                                _scannerName = _deviceName;
                                Console.WriteLine($"[SDK] Selected device: {_deviceName}");
                            }
                        }
                    }
                }
                else if (result == ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_NO_DEVICES)
                {
                    Console.WriteLine("[SDK] No scanner devices found");
                    return false;
                }

                if (string.IsNullOrEmpty(_deviceName))
                {
                    Console.WriteLine("[SDK] No device name available");
                    return false;
                }

                // CRITICAL: Wait for background device initialization to complete
                // GetDevicesList() triggers async getDeviceCap() calls that open the device
                // If we call SetProperty() immediately, device isn't ready yet → error code 3
                Console.WriteLine("[SDK] Waiting 2 seconds for background device initialization...");
                System.Threading.Thread.Sleep(2000);

                // Set up event callback
                _eventCallback = OnScanEvent;

                // Configure scanner properties with minimal working command
                // Note: Complex parameters may not be supported by all SDK versions
                string command = $"{{\"device-name\":\"{_deviceName}\",\"source\":\"Camera\"}}";
                Console.WriteLine($"[SDK] SetProperty command: {command}");
                result = PlustekSDK.LibWFX_SetProperty(command, _eventCallback, IntPtr.Zero);

                if (result != ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_SUCCESS && 
                    result != ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_COMMAND_KEY_MISMATCH)
                {
                    Console.WriteLine($"[SDK] LibWFX_SetProperty failed with error code: {result}");
                    // Don't call LogError - it can cause AccessViolationException
                    return false;
                }
                else if (result == ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_COMMAND_KEY_MISMATCH)
                {
                    Console.WriteLine($"[SDK] SetProperty returned COMMAND_KEY_MISMATCH (may be non-fatal)");
                }

                _isInitialized = true;
                Console.WriteLine("[SDK] Plustek SDK initialized successfully");
                return true;
            }
            catch (DllNotFoundException ex)
            {
                Console.WriteLine($"[SDK] DLL not found: {ex.Message}");
                Console.WriteLine("[SDK] Make sure LibWebFXScan.dll is in the application directory");
                return false;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[SDK] Initialization error: {ex.Message}");
                return false;
            }
        }

        private void OnScanEvent(ENUM_LIBWFX_EVENT_CODE eventCode, int param, IntPtr userData)
        {
            Console.WriteLine($"[SDK] Event: {eventCode} (param: {param})");
        }

        private void LogError(ENUM_LIBWFX_ERRCODE errorCode)
        {
            try
            {
                IntPtr errorMsgPtr = IntPtr.Zero;
                var result = PlustekSDK.LibWFX_GetLastErrorCode(errorCode, out errorMsgPtr);
                
                // Check if the call succeeded and pointer is valid before dereferencing
                if (result == ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_SUCCESS && 
                    errorMsgPtr != IntPtr.Zero)
                {
                    try
                    {
                        string errorMsg = Marshal.PtrToStringUni(errorMsgPtr) ?? "Unknown error";
                        Console.WriteLine($"[SDK] Error details: {errorMsg}");
                    }
                    catch (AccessViolationException)
                    {
                        Console.WriteLine($"[SDK] Error code: {errorCode} (details unavailable)");
                    }
                }
                else
                {
                    Console.WriteLine($"[SDK] Error code: {errorCode}");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[SDK] Failed to get error details: {ex.Message}");
            }
        }

        public ScanResult ScanPassport()
        {
            if (!_isInitialized)
            {
                return ScanResult.Failed("SDK not initialized");
            }

            try
            {
                Console.WriteLine("[SDK] Starting passport scan...");

                // Check if paper is ready
                var paperResult = PlustekSDK.LibWFX_PaperReady();
                if (paperResult != ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_SUCCESS)
                {
                    Console.WriteLine("[SDK] No document detected on scanner");
                    return ScanResult.Failed("No document detected. Please place passport on scanner.");
                }

                // Perform synchronous scan
                string command = $"{{\"device-name\":\"{_deviceName}\",\"source\":\"Camera\",\"recognize-type\":\"passport\"}}";
                
                IntPtr scanImageListPtr, ocrResultListPtr, exceptionRetPtr, eventRetPtr;
                var result = PlustekSDK.LibWFX_SynchronizeScan(
                    command,
                    out scanImageListPtr,
                    out ocrResultListPtr,
                    out exceptionRetPtr,
                    out eventRetPtr
                );

                // Parse event response
                string eventRet = eventRetPtr != IntPtr.Zero ? Marshal.PtrToStringUni(eventRetPtr) ?? "" : "";
                string exceptionRet = exceptionRetPtr != IntPtr.Zero ? Marshal.PtrToStringUni(exceptionRetPtr) ?? "" : "";

                if (result != ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_SUCCESS && 
                    result != ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_COMMAND_KEY_MISMATCH)
                {
                    LogError(result);
                    return ScanResult.Failed($"Scan failed with error: {result}");
                }

                if (!string.IsNullOrEmpty(exceptionRet))
                {
                    Console.WriteLine($"[SDK] Exception: {exceptionRet}");
                }

                // Parse scan results
                string scanImageList = scanImageListPtr != IntPtr.Zero ? Marshal.PtrToStringUni(scanImageListPtr) ?? "" : "";
                string ocrResultList = ocrResultListPtr != IntPtr.Zero ? Marshal.PtrToStringUni(ocrResultListPtr) ?? "" : "";

                Console.WriteLine($"[SDK] Scan images: {scanImageList}");
                Console.WriteLine($"[SDK] OCR results: {ocrResultList}");

                // Parse image paths and OCR data
                var imagePaths = scanImageList.Split(new[] { "|&|" }, StringSplitOptions.RemoveEmptyEntries);
                var ocrResults = ocrResultList.Split(new[] { "|&|" }, StringSplitOptions.RemoveEmptyEntries);

                var scanResult = new ScanResult { Success = true };

                // Get first scanned image
                if (imagePaths.Length > 0)
                {
                    string imagePath = imagePaths[0].Trim();
                    if (File.Exists(imagePath))
                    {
                        byte[] imageBytes = File.ReadAllBytes(imagePath);
                        scanResult.ImageBase64 = Convert.ToBase64String(imageBytes);
                        scanResult.ImagePath = imagePath;
                        Console.WriteLine($"[SDK] Image loaded: {imagePath}");
                    }
                }

                // Parse OCR/MRZ data
                if (ocrResults.Length > 0)
                {
                    string ocrJson = ocrResults[0].Trim();
                    ParseMrzData(ocrJson, scanResult);
                }

                Console.WriteLine($"[SDK] Scan complete - {scanResult.FirstName} {scanResult.LastName}");
                return scanResult;
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[SDK] Scan error: {ex.Message}");
                return ScanResult.Failed($"Scan error: {ex.Message}");
            }
        }

        private void ParseMrzData(string ocrData, ScanResult result)
        {
            try
            {
                if (string.IsNullOrEmpty(ocrData)) return;

                Console.WriteLine($"[SDK] Raw OCR data: {ocrData}");

                // Try to parse as JSON first
                if (ocrData.TrimStart().StartsWith("{"))
                {
                    ParseMrzFromJson(ocrData, result);
                }
                // Try MRZ line format (TD1, TD2, TD3 formats)
                else if (ocrData.Contains("<") || ocrData.Length >= 30)
                {
                    ParseMrzFromLines(ocrData, result);
                }
                else
                {
                    Console.WriteLine($"[SDK] Unknown OCR format: {ocrData}");
                }

                if (!string.IsNullOrEmpty(result.FirstName))
                {
                    Console.WriteLine($"[SDK] MRZ parsed: {result.FirstName} {result.LastName} - {result.PassportNumber}");
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[SDK] MRZ parse error: {ex.Message}");
            }
        }

        private void ParseMrzFromJson(string jsonStr, ScanResult result)
        {
            try
            {
                var json = JObject.Parse(jsonStr);

                // Standard Plustek MRZ fields
                result.FirstName = GetJsonField(json, 
                    "firstName", "givenName", "GivenName", "first_name", "FirstName",
                    "given_name", "forename", "Forename");
                result.LastName = GetJsonField(json, 
                    "lastName", "surname", "Surname", "FamilyName", "last_name", "LastName",
                    "family_name", "familyName");
                result.PassportNumber = GetJsonField(json, 
                    "documentNumber", "DocumentNumber", "passport_number", "docNo", "PassportNumber",
                    "document_number", "idNumber", "IdNumber", "number");
                result.Nationality = GetJsonField(json, 
                    "nationality", "Nationality", "nation", "Country", "country",
                    "issuingState", "IssuingState", "issuing_state");
                result.DateOfBirth = GetJsonField(json, 
                    "dateOfBirth", "DateOfBirth", "birth_date", "BirthDate", "birthDate",
                    "dob", "DOB", "birthday");
                result.ExpiryDate = GetJsonField(json, 
                    "expiryDate", "ExpirationDate", "expiry_date", "DateOfExpiry", "expirationDate",
                    "validUntil", "ValidUntil", "expiry");
                result.Gender = GetJsonField(json, 
                    "sex", "Sex", "gender", "Gender");

                // Try nested structures (Plustek sometimes nests data)
                if (string.IsNullOrEmpty(result.FirstName))
                {
                    var mrzData = json["mrz"] ?? json["MRZ"] ?? json["data"] ?? json["result"];
                    if (mrzData is JObject mrzObj)
                    {
                        result.FirstName = GetJsonField(mrzObj, "firstName", "givenName", "GivenName");
                        result.LastName = GetJsonField(mrzObj, "lastName", "surname", "Surname");
                        result.PassportNumber = GetJsonField(mrzObj, "documentNumber", "DocumentNumber");
                        result.Nationality = GetJsonField(mrzObj, "nationality", "Nationality");
                        result.DateOfBirth = GetJsonField(mrzObj, "dateOfBirth", "DateOfBirth");
                        result.ExpiryDate = GetJsonField(mrzObj, "expiryDate", "ExpirationDate");
                        result.Gender = GetJsonField(mrzObj, "sex", "Sex");
                    }
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[SDK] JSON parse error: {ex.Message}");
            }
        }

        private void ParseMrzFromLines(string mrzLines, ScanResult result)
        {
            try
            {
                // Split into lines and clean
                var lines = mrzLines.Split(new[] { '\n', '\r' }, StringSplitOptions.RemoveEmptyEntries);
                
                if (lines.Length >= 2)
                {
                    // TD3 format (passport) - 2 lines of 44 characters
                    string line1 = lines[0].Replace(" ", "");
                    string line2 = lines.Length > 1 ? lines[1].Replace(" ", "") : "";

                    if (line1.Length >= 44)
                    {
                        // Line 1: Type, Country, Names
                        result.Nationality = line1.Substring(2, 3).Replace("<", "");
                        var names = line1.Substring(5).Split(new[] { "<<" }, StringSplitOptions.RemoveEmptyEntries);
                        if (names.Length >= 2)
                        {
                            result.LastName = names[0].Replace("<", " ").Trim();
                            result.FirstName = names[1].Replace("<", " ").Trim();
                        }
                    }

                    if (line2.Length >= 44)
                    {
                        // Line 2: Document number, DOB, Sex, Expiry
                        result.PassportNumber = line2.Substring(0, 9).Replace("<", "");
                        result.DateOfBirth = FormatMrzDate(line2.Substring(13, 6));
                        result.Gender = line2.Substring(20, 1);
                        result.ExpiryDate = FormatMrzDate(line2.Substring(21, 6));
                    }
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[SDK] MRZ line parse error: {ex.Message}");
            }
        }

        private string FormatMrzDate(string mrzDate)
        {
            if (mrzDate.Length != 6) return mrzDate;
            
            int year = int.Parse(mrzDate.Substring(0, 2));
            int month = int.Parse(mrzDate.Substring(2, 2));
            int day = int.Parse(mrzDate.Substring(4, 2));
            
            // Determine century (assume 00-30 is 2000s, 31-99 is 1900s for DOB)
            year = year > 30 ? 1900 + year : 2000 + year;
            
            return $"{year:D4}-{month:D2}-{day:D2}";
        }

        private string? GetJsonField(JObject json, params string[] fieldNames)
        {
            foreach (var name in fieldNames)
            {
                var value = json[name]?.ToString();
                if (!string.IsNullOrEmpty(value))
                {
                    return value;
                }
            }
            return null;
        }

        public bool IsDocumentPresent()
        {
            if (!_isInitialized) return false;
            
            try
            {
                var result = PlustekSDK.LibWFX_PaperReady();
                return result == ENUM_LIBWFX_ERRCODE.LIBWFX_ERRCODE_SUCCESS;
            }
            catch
            {
                return false;
            }
        }

        public bool IsScannerReady()
        {
            return _isInitialized;
        }

        public string GetVersion()
        {
            return _sdkVersion;
        }

        public void Dispose()
        {
            if (!_disposed)
            {
                try
                {
                    if (_isInitialized)
                    {
                        Console.WriteLine("[SDK] Closing device...");
                        PlustekSDK.LibWFX_CloseDevice();
                        PlustekSDK.LibWFX_DeInit();
                    }
                }
                catch (Exception ex)
                {
                    Console.WriteLine($"[SDK] Dispose error: {ex.Message}");
                }

                _isInitialized = false;
                _disposed = true;
            }
        }
    }
}
