Set WinScriptHost = CreateObject("WScript.Shell")
WinScriptHost.Run Chr(34) & "C:\xampp\htdocs\sync_script\sync_data.bat" & Chr(34), 0
Set WinScriptHost = Nothing
