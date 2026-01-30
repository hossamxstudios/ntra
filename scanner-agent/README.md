# SecureScan Agent - NTRA Kiosk System

Agent application that connects Plustek SecureScan X50 passport scanner to Laravel web application via WebSocket.

## Features

- **Real Scanner Integration**: Full support for Plustek SecureScan X50/X100/X150/X-Mini/X-Cube scanners
- **MRZ Recognition**: Automatic Machine Readable Zone parsing for passport data
- **Demo Mode**: Falls back to demo mode when scanner is not connected (for development)
- **WebSocket API**: Real-time communication with Laravel application
- **Multi-Client Support**: Multiple browser tabs can connect simultaneously

## Requirements

- Windows 10/11 (64-bit)
- .NET 6.0 Runtime
- SecureScan X50 Scanner (optional - demo mode available)

## Installation

### 1. Install .NET 6.0 Runtime
Download from: https://dotnet.microsoft.com/download/dotnet/6.0

### 2. SDK Files (Already Included)
The following SDK files should be in the `SDK` folder:
- `SecureScanRes.dll` - Main SDK library
- `Camera.dll` - Camera interface
- `Scanner.dll` - Scanner control
- `PngLib.dll` - PNG image handling
- `CalibrationRes.dll` - Calibration resources
- `Print.dll` - Printing functions
- `SecureScan.ini` - Configuration
- `Camera.ini` - Camera settings

### 3. Build the Agent
```bash
dotnet build -c Release
```

### 4. Run the Agent
```bash
dotnet run
```
Or double-click `start-agent.bat`

## Operating Modes

### Real Scanner Mode
When a SecureScan scanner is connected and SDK is properly initialized:
- Scans real passport documents
- Extracts MRZ data (name, passport number, nationality, dates)
- Captures passport photo
- Returns scanned image as Base64

### Demo Mode
When scanner is not connected (for development/testing):
- Returns sample test data
- Simulates scan delay
- Allows full application testing without hardware

The agent automatically detects which mode to use on startup.

## WebSocket API

The agent runs a WebSocket server on `ws://localhost:9001`

### Commands

**Scan Passport:**
```json
{"action": "scan"}
```

**Check Status:**
```json
{"action": "status"}
```

**Ping (Keep-alive):**
```json
{"action": "ping"}
```

### Responses

**Connected (on WebSocket open):**
```json
{
  "type": "connected",
  "message": "Scanner Agent connected",
  "scannerReady": true,
  "scannerName": "SecureScan X50",
  "demoMode": false,
  "sdkVersion": "6.4.4.0"
}
```

**Scanning (progress):**
```json
{
  "type": "scanning",
  "message": "جاري مسح جواز السفر..."
}
```

**Scan Result (Success):**
```json
{
  "type": "scan_result",
  "success": true,
  "data": {
    "firstName": "AHMED",
    "lastName": "MOHAMED",
    "passportNumber": "A12345678",
    "nationality": "EGY",
    "dateOfBirth": "1990-05-15",
    "expiryDate": "2028-05-14",
    "gender": "M",
    "imageBase64": "..."
  }
}
```

**Scan Result (Error):**
```json
{
  "type": "scan_result",
  "success": false,
  "error": "لم يتم اكتشاف المستند"
}
```

**Status Response:**
```json
{
  "type": "status",
  "scannerReady": true,
  "scannerName": "SecureScan X50",
  "demoMode": false,
  "sdkVersion": "6.4.4.0",
  "documentPresent": true
}
```

## Console Commands

While the agent is running:
- **Q** - Quit the application
- **T** - Test scan (manual trigger)
- **S** - Show current status

## Auto-Start on Windows

### Option 1: Startup Folder
1. Press `Win + R`, type `shell:startup`
2. Create shortcut to `start-agent.bat` in the startup folder

### Option 2: Task Scheduler
1. Open Task Scheduler
2. Create Basic Task
3. Trigger: "When the computer starts"
4. Action: Start a program → Browse to `ScannerAgent.exe`
5. Enable "Run with highest privileges"

## Project Structure

```
scanner-agent/
├── Program.cs              # Main entry point, WebSocket server
├── ScannerAgent.csproj     # Project configuration
├── start-agent.bat         # Windows startup script
├── Models/
│   └── ScanResult.cs       # Scan result data model
├── Services/
│   └── ScannerService.cs   # Scanner service (SDK + demo mode)
└── SDK/
    ├── SecureScanSDK.cs    # P/Invoke declarations
    ├── SecureScanWrapper.cs # High-level SDK wrapper
    ├── SecureScanRes.dll   # Native SDK library
    ├── Camera.dll          # Camera interface
    ├── Scanner.dll         # Scanner control
    └── *.ini               # Configuration files
```

## Troubleshooting

### Scanner not detected
1. Check USB connection
2. Install Plustek scanner drivers
3. Verify scanner appears in Device Manager
4. Restart the agent

### Demo mode starts instead of real scanner
1. Ensure scanner is connected before starting agent
2. Check SDK DLLs are in the output folder
3. Verify scanner drivers are installed
4. Check console output for initialization errors

### WebSocket connection refused
1. Check if port 9001 is available: `netstat -an | findstr 9001`
2. Add firewall exception for port 9001
3. Ensure agent is running before connecting

### MRZ recognition fails
1. Ensure passport is placed flat on scanner
2. MRZ zone (bottom lines) must be visible
3. Clean scanner glass
4. Check lighting conditions

## Development

### Building from Source
```bash
cd scanner-agent
dotnet restore
dotnet build -c Debug
```

### Running in Development
```bash
dotnet run
```

### Creating Release Build
```bash
dotnet publish -c Release -r win-x64 --self-contained false
```

## License

Proprietary - NTRA Egypt
