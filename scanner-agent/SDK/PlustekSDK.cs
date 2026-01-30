using System;
using System.Runtime.InteropServices;

namespace ScannerAgent.SDK
{
    /// <summary>
    /// Plustek LibWebFXScan SDK P/Invoke declarations.
    /// Based on official AutoCaptureDemo-CSharp from Plustek SDK 2.x
    /// </summary>
    public enum ENUM_LIBWFX_ERRCODE
    {
        LIBWFX_ERRCODE_SUCCESS = 0,
        LIBWFX_ERRCODE_FAIL,
        LIBWFX_ERRCODE_NO_INIT,
        LIBWFX_ERRCODE_NOT_YET_OPEN_DEVICE,
        LIBWFX_ERRCODE_DEVICE_ALREADY_OPEN,
        LIBWFX_ERRCODE_INVALID_SOURCE,
        LIBWFX_ERRCODE_NO_ENABLE_THRESHOLD,
        LIBWFX_ERRCODE_NO_SUPPORT_THRESHOLD,
        LIBWFX_ERRCODE_NOT_YET_SET_SCAN_PROPERTY,
        LIBWFX_ERRCODE_NO_SET_RECOGNIZE_TOOL,
        LIBWFX_ERRCODE_OCR_NOT_SUPPORT_BOTTOMUP,
        LIBWFX_ERRCODE_READ_IMAGE_FAILED,
        LIBWFX_ERRCODE_ONLY_SUPPORT_COLOR_MODE,
        LIBWFX_ERRCODE_ICM_PROFILE_NOT_EXIST,
        LIBWFX_ERRCODE_NO_SUPPORT_EJECT,
        LIBWFX_ERRCODE_NO_SUPPORT_JPEGXFER,
        LIBWFX_ERRCODE_PAPER_NOT_READY,
        LIBWFX_ERRCODE_INVALID_SERIALNUM,
        LIBWFX_ERRCODE_DISCONNECT,
        LIBWFX_ERRCODE_FORMAT_NOT_SUPPORT,
        LIBWFX_ERRCODE_NO_CALIBRATION_DATA,
        LIBWFX_ERRCODE_OCR_TOOL_NOT_SUPPORT,
        LIBWFX_ERRCODE_RECOGNIZE_TYPE_NOT_SUPPORT,
        LIBWFX_ERRCODE_INVALID_CERTIFICATE,
        LIBWFX_ERRCODE_AP_ALREADY_EXISIT,
        LIBWFX_ERRCODE_OPEN_REGISTRY_KEY_FAILED,
        LIBWFX_ERRCODE_LOAD_MRTD_DLL_FAIL,
        LIBWFX_ERRCODE_COVER_OPENED,
        LIBWFX_ERRCODE_CERTIFICATE_EXPIRED,
        LIBWFX_ERRCODE_ALREADY_INIT,
        LIBWFX_ERRCODE_NO_SUPPORT_DUPLEX,
        LIBWFX_ERRCODE_NO_AVI_OCR = 1001,
        LIBWFX_ERRCODE_NO_DOC_OCR,
        LIBWFX_ERRCODE_NO_OCR,
        LIBWFX_ERRCODE_NO_DEVICES,
        LIBWFX_ERRCODE_NO_DEVICE_NAME,
        LIBWFX_ERRCODE_NO_SOURCE,
        LIBWFX_ERRCODE_FILE_NO_EXIST,
        LIBWFX_ERRCODE_PATH_TOO_LONG,
        LIBWFX_ERRCODE_COMMAND_KEY_MISMATCH,
        LIBWFX_ERRCODE_SCANNING,
    }

    public enum ENUM_LIBWFX_EVENT_CODE
    {
        LIBWFX_EVENT_PAPER_DETECTED = 0,
        LIBWFX_EVENT_NO_PAPER,
        LIBWFX_EVENT_PAPER_JAM,
        LIBWFX_EVENT_MULTIFEED,
        LIBWFX_EVENT_NO_CALIBRATION_DATA,
        LIBWFX_EVENT_WARMUP_COUNTDOWN,
        LIBWFX_EVENT_SCAN_PROGRESS,
        LIBWFX_EVENT_BUTTON_DETECTED,
        LIBWFX_EVENT_SCANNING,
        LIBWFX_EVENT_PAPER_FEEDING_ERROR,
        LIBWFX_EVENT_COVER_OPEN,
        LIBWFX_EVENT_LEFT_SENSOR_DETECTED,
        LIBWFX_EVENT_RIGHT_SENSOR_DETECTED,
        LIBWFX_EVENT_ALL_SENSOR_DETECTED,
        LIBWFX_EVENT_UVSECURITY_DETECTED,
        LIBWFX_EVENT_PLUG_UNPLUG,
        LIBWFX_EVENT_OVER_TIME_SCAN,
        LIBWFX_EVENT_CANCEL_SCAN,
        LIBWFX_EVENT_CAMERA_RGB_DISLOCATION
    }

    public enum ENUM_LIBWFX_INIT_MODE
    {
        LIBWFX_INIT_MODE_NORMAL = 0x0,
        LIBWFX_INIT_MODE_NOOCR = 0x1,
    }

    public static class PlustekSDK
    {
        public const string LIBWFX_DLLNAME = @"LibWebFXScan.dll";

        [UnmanagedFunctionPointer(CallingConvention.Cdecl)]
        public delegate void LIBWFXEVENTCB(ENUM_LIBWFX_EVENT_CODE enEventCode, int nParam, IntPtr pUserDef);

        [DllImport(LIBWFX_DLLNAME, EntryPoint = "LibWFX_InitEx", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_InitEx(ENUM_LIBWFX_INIT_MODE enInitMode);

        [DllImport(LIBWFX_DLLNAME, EntryPoint = "LibWFX_DeInit", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_DeInit();

        [DllImport(LIBWFX_DLLNAME, EntryPoint = "LibWFX_GetDeviesList", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_GetDevicesList(out IntPtr szDevicesListOut);

        [DllImport(LIBWFX_DLLNAME, CharSet = CharSet.Unicode, EntryPoint = "LibWFX_SetProperty", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_SetProperty(string szRequestCmdIn, [MarshalAs(UnmanagedType.FunctionPtr)] LIBWFXEVENTCB pfnLibWFXEVENTCBIn, IntPtr pUserDefIn);

        [DllImport(LIBWFX_DLLNAME, EntryPoint = "LibWFX_PaperReady", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_PaperReady();

        [DllImport(LIBWFX_DLLNAME, EntryPoint = "LibWFX_CloseDevice", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_CloseDevice();

        [DllImport(LIBWFX_DLLNAME, CharSet = CharSet.Unicode, EntryPoint = "LibWFX_SynchronizeScan", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_SynchronizeScan(string szRequestCmdIn, out IntPtr szScanImageList, out IntPtr szOCRResultList, out IntPtr szExceptionRet, out IntPtr szEventRet);

        [DllImport(LIBWFX_DLLNAME, CharSet = CharSet.Unicode, EntryPoint = "LibWFX_GetLastErrorCode", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_GetLastErrorCode(ENUM_LIBWFX_ERRCODE enErrorCode, out IntPtr szErrorMsg);

        [DllImport(LIBWFX_DLLNAME, EntryPoint = "LibWFX_GetPaperStatus", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_GetPaperStatus(out ENUM_LIBWFX_EVENT_CODE penStatusOut);

        [DllImport(LIBWFX_DLLNAME, EntryPoint = "LibWFX_StartScan", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_StartScan([MarshalAs(UnmanagedType.FunctionPtr)] LIBWFXCB pfnLibWFXCBIn, IntPtr pUserDefIn);

        [DllImport(LIBWFX_DLLNAME, EntryPoint = "LibWFX_Calibrate", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_Calibrate();

        [DllImport(LIBWFX_DLLNAME, EntryPoint = "LibWFX_GetDeviesListWithSerial", CallingConvention = CallingConvention.StdCall)]
        public static extern ENUM_LIBWFX_ERRCODE LibWFX_GetDevicesListWithSerial(out IntPtr szDevicesListOut, out IntPtr szSerialListOut);

        [UnmanagedFunctionPointer(CallingConvention.Cdecl)]
        public delegate void LIBWFXCB(ENUM_LIBWFX_NOTIFY_CODE enNotifyCode, IntPtr pUserDef, IntPtr pParam1, IntPtr pParam2);
    }

    public enum ENUM_LIBWFX_NOTIFY_CODE
    {
        LIBWFX_NOTIFY_IMAGE_DONE = 0,
        LIBWFX_NOTIFY_END,
        LIBWFX_NOTIFY_EXCEPTION,
        LIBWFX_NOTIFY_SHOWPATHONLY,
    }
}
