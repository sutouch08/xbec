Set WinScriptHost = CreateObject("WScript.Shell")
WinScriptHost.Run Chr(34) & "C:\Apache24\htdocs\web\sync_script\auto_cancel_draft_order.bat" & Chr(34), 0
Set WinScriptHost = Nothing
