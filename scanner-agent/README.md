# SecureScan Agent - NTRA Kiosk System

Agent application that connects SecureScan X50 passport scanner to Laravel web application.

## Requirements

- Windows 10/11
- .NET 6.0 Runtime
- SecureScan X50 Scanner + SDK

## Installation

### 1. Install .NET 6.0 Runtime
Download from: https://dotnet.microsoft.com/download/dotnet/6.0

### 2. Add SecureScan SDK
Copy SDK files to the `SDK` folder:
- `SecureScanLib.dll`
- Other required DLLs

### 3. Build the Agent
```bash
dotnet build -c Release
```

### 4. Run the Agent
```bash
dotnet run
```
Or run `start-agent.bat`

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

**Ping:**
```json
{"action": "ping"}
```

### Responses

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
  "error": "No document detected"
}
```

## Auto-Start on Windows

1. Press `Win + R`, type `shell:startup`
2. Create shortcut to `start-agent.bat` in the startup folder

## Troubleshooting

- **Scanner not detected:** Check USB connection and drivers
- **WebSocket connection refused:** Make sure port 9001 is not blocked by firewall
- **SDK error:** Verify SecureScan SDK DLLs are in the correct path
