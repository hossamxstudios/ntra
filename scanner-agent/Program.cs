using System;
using System.Collections.Generic;
using Fleck;
using Newtonsoft.Json;
using ScannerAgent.Services;

namespace ScannerAgent
{
    class Program
    {
        private static List<IWebSocketConnection> _clients = new List<IWebSocketConnection>();
        private static ScannerService _scannerService = new ScannerService();

        static void Main(string[] args)
        {
            Console.WriteLine("===========================================");
            Console.WriteLine("  SecureScan Agent - NTRA Kiosk System");
            Console.WriteLine("  Version 1.0.0");
            Console.WriteLine("===========================================");
            Console.WriteLine();

            // Initialize scanner
            if (!_scannerService.Initialize())
            {
                Console.WriteLine("[ERROR] Failed to initialize scanner!");
                Console.WriteLine("Press any key to exit...");
                Console.ReadKey();
                return;
            }

            // Display scanner status
            Console.WriteLine();
            Console.WriteLine($"[OK] Scanner: {_scannerService.ScannerName}");
            Console.WriteLine($"[OK] SDK Version: {_scannerService.GetSDKVersion()}");
            if (_scannerService.IsDemoMode)
            {
                Console.ForegroundColor = ConsoleColor.Yellow;
                Console.WriteLine("[WARNING] Running in DEMO MODE - Connect scanner for real scanning");
                Console.ResetColor();
            }
            else
            {
                Console.ForegroundColor = ConsoleColor.Green;
                Console.WriteLine("[OK] Real scanner connected and ready");
                Console.ResetColor();
            }

            // Start WebSocket server
            var server = new WebSocketServer("ws://0.0.0.0:9001");
            
            server.Start(socket =>
            {
                socket.OnOpen = () =>
                {
                    Console.WriteLine($"[CONNECTED] Client connected: {socket.ConnectionInfo.ClientIpAddress}");
                    _clients.Add(socket);
                    
                    // Send welcome message with scanner info
                    socket.Send(JsonConvert.SerializeObject(new
                    {
                        type = "connected",
                        message = "Scanner Agent connected",
                        scannerReady = _scannerService.IsReady,
                        scannerName = _scannerService.ScannerName,
                        demoMode = _scannerService.IsDemoMode,
                        sdkVersion = _scannerService.GetSDKVersion()
                    }));
                };

                socket.OnClose = () =>
                {
                    Console.WriteLine($"[DISCONNECTED] Client disconnected: {socket.ConnectionInfo.ClientIpAddress}");
                    _clients.Remove(socket);
                };

                socket.OnMessage = message =>
                {
                    Console.WriteLine($"[MESSAGE] Received: {message}");
                    HandleMessage(socket, message);
                };

                socket.OnError = error =>
                {
                    Console.WriteLine($"[ERROR] WebSocket error: {error.Message}");
                };
            });

            Console.WriteLine();
            Console.WriteLine("[SERVER] WebSocket server running on ws://localhost:9001");
            Console.WriteLine("[INFO] Waiting for connections from Laravel...");
            Console.WriteLine();
            Console.WriteLine("Press 'Q' to quit, 'T' to test scan, 'S' for status");
            Console.WriteLine();

            // Keep running
            while (true)
            {
                var key = Console.ReadKey(true);
                if (key.Key == ConsoleKey.Q)
                {
                    Console.WriteLine("Shutting down...");
                    _scannerService.Dispose();
                    break;
                }
                else if (key.Key == ConsoleKey.T)
                {
                    Console.WriteLine("Testing scan...");
                    TestScan();
                }
                else if (key.Key == ConsoleKey.S)
                {
                    ShowStatus();
                }
            }
        }

        private static void ShowStatus()
        {
            Console.WriteLine();
            Console.WriteLine("--- Scanner Status ---");
            Console.WriteLine($"Scanner: {_scannerService.ScannerName}");
            Console.WriteLine($"Ready: {_scannerService.IsReady}");
            Console.WriteLine($"Demo Mode: {_scannerService.IsDemoMode}");
            Console.WriteLine($"SDK Version: {_scannerService.GetSDKVersion()}");
            Console.WriteLine($"Document Present: {_scannerService.IsDocumentPresent()}");
            Console.WriteLine($"Connected Clients: {_clients.Count}");
            Console.WriteLine("----------------------");
            Console.WriteLine();
        }

        private static void HandleMessage(IWebSocketConnection socket, string message)
        {
            try
            {
                dynamic request = JsonConvert.DeserializeObject(message)!;
                string action = request.action;

                switch (action)
                {
                    case "scan":
                        PerformScan(socket, request);
                        break;

                    case "status":
                        SendStatus(socket);
                        break;

                    case "ping":
                        socket.Send(JsonConvert.SerializeObject(new { type = "pong" }));
                        break;

                    default:
                        socket.Send(JsonConvert.SerializeObject(new
                        {
                            type = "error",
                            message = $"Unknown action: {action}"
                        }));
                        break;
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[ERROR] Failed to handle message: {ex.Message}");
                socket.Send(JsonConvert.SerializeObject(new
                {
                    type = "error",
                    message = ex.Message
                }));
            }
        }

        private static void PerformScan(IWebSocketConnection socket, dynamic request)
        {
            try
            {
                // Notify scanning started
                socket.Send(JsonConvert.SerializeObject(new
                {
                    type = "scanning",
                    message = "جاري مسح جواز السفر..."
                }));

                // Perform scan
                var result = _scannerService.ScanPassport();

                if (result.Success)
                {
                    Console.WriteLine("[OK] Scan completed successfully");
                    socket.Send(JsonConvert.SerializeObject(new
                    {
                        type = "scan_result",
                        success = true,
                        data = new
                        {
                            firstName = result.FirstName,
                            lastName = result.LastName,
                            passportNumber = result.PassportNumber,
                            nationality = result.Nationality,
                            dateOfBirth = result.DateOfBirth,
                            expiryDate = result.ExpiryDate,
                            gender = result.Gender,
                            imageBase64 = result.ImageBase64
                        }
                    }));
                }
                else
                {
                    Console.WriteLine($"[ERROR] Scan failed: {result.ErrorMessage}");
                    socket.Send(JsonConvert.SerializeObject(new
                    {
                        type = "scan_result",
                        success = false,
                        error = result.ErrorMessage
                    }));
                }
            }
            catch (Exception ex)
            {
                Console.WriteLine($"[ERROR] Scan exception: {ex.Message}");
                socket.Send(JsonConvert.SerializeObject(new
                {
                    type = "scan_result",
                    success = false,
                    error = ex.Message
                }));
            }
        }

        private static void SendStatus(IWebSocketConnection socket)
        {
            socket.Send(JsonConvert.SerializeObject(new
            {
                type = "status",
                scannerReady = _scannerService.IsReady,
                scannerName = _scannerService.ScannerName,
                demoMode = _scannerService.IsDemoMode,
                sdkVersion = _scannerService.GetSDKVersion(),
                documentPresent = _scannerService.IsDocumentPresent()
            }));
        }

        private static void TestScan()
        {
            var result = _scannerService.ScanPassport();
            if (result.Success)
            {
                Console.WriteLine($"[TEST] Name: {result.FirstName} {result.LastName}");
                Console.WriteLine($"[TEST] Passport: {result.PassportNumber}");
                Console.WriteLine($"[TEST] Nationality: {result.Nationality}");
            }
            else
            {
                Console.WriteLine($"[TEST] Failed: {result.ErrorMessage}");
            }
        }
    }
}
