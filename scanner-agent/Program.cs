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

            Console.WriteLine("[OK] Scanner initialized successfully");

            // Start WebSocket server
            var server = new WebSocketServer("ws://0.0.0.0:9001");
            
            server.Start(socket =>
            {
                socket.OnOpen = () =>
                {
                    Console.WriteLine($"[CONNECTED] Client connected: {socket.ConnectionInfo.ClientIpAddress}");
                    _clients.Add(socket);
                    
                    // Send welcome message
                    socket.Send(JsonConvert.SerializeObject(new
                    {
                        type = "connected",
                        message = "Scanner Agent connected",
                        scannerReady = _scannerService.IsReady
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
            Console.WriteLine("Press 'Q' to quit, 'T' to test scan");
            Console.WriteLine();

            // Keep running
            while (true)
            {
                var key = Console.ReadKey(true);
                if (key.Key == ConsoleKey.Q)
                {
                    Console.WriteLine("Shutting down...");
                    break;
                }
                else if (key.Key == ConsoleKey.T)
                {
                    Console.WriteLine("Testing scan...");
                    TestScan();
                }
            }
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
                scannerName = _scannerService.ScannerName
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
