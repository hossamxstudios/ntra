namespace ScannerAgent.Models
{
    public class ScanResult
    {
        public bool Success { get; set; }
        public string? ErrorMessage { get; set; }
        
        // Passport Data (from MRZ)
        public string? FirstName { get; set; }
        public string? LastName { get; set; }
        public string? PassportNumber { get; set; }
        public string? Nationality { get; set; }
        public string? DateOfBirth { get; set; }
        public string? ExpiryDate { get; set; }
        public string? Gender { get; set; }
        
        // Scanned Image
        public string? ImageBase64 { get; set; }
        public string? ImagePath { get; set; }

        public static ScanResult Failed(string error)
        {
            return new ScanResult
            {
                Success = false,
                ErrorMessage = error
            };
        }
    }
}
