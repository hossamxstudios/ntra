@echo off
title SecureScan Agent - NTRA
echo Starting SecureScan Agent...
cd /d "%~dp0"
dotnet run --project ScannerAgent.csproj
pause
