@echo off
color 2F
echo.
echo.
echo.1.Office 2013 激活
echo.
echo.2.Office 2010 激活
echo.
echo.3.Windows 7 及以上版本激活
echo.
echo.
set KMS_Server=222.195.158.9
set /p c=请输入数字并回车:
if %c%==1 goto 1
if %c%==2 goto 2
if %c%==3 goto 3
:office
setlocal EnableDelayedExpansion
reg query %strRegKey% >nul 2>nul
if %errorlevel%==0 (set strCurrentKey=%strRegKey%) else (set strCurrentKey=%strRegKey6432%)
for /f "delims=" %%i in ('reg query %strCurrentKey%') do (
set strInstPath=%%i
set strInstPath=!strInstPath:*REG_SZ=!
)
:LTrim
if "%strInstPath:~0,1%"==" " set "strInstPath=%strInstPath:~1%" && goto LTrim
:RTrim
if "%strInstPath:~-1%"==" " set "strInstPath=%strInstPath:~0,-1%" && goto RTrim
if "%strInstPath:~-1%" neq "\" set strInstPath=%strInstPath%\
echo office安装目录为%strInstPath% 
cd /d %strInstPath%
cscript ospp.vbs /sethst:%KMS_Server%
cscript ospp.vbs /act
pause
exit

:1
set "strRegKey=HKEY_LOCAL_MACHINE\Software\Microsoft\Office\15.0\Common\InstallRoot /v Path"
set "strRegKey6432=HKEY_LOCAL_MACHINE\Software\Wow6432Node\Microsoft\Office\15.0\Common\InstallRoot /v Path"
goto office

:2
set "strRegKey=HKEY_LOCAL_MACHINE\Software\Microsoft\Office\14.0\Common\InstallRoot /v Path"
set "strRegKey6432=HKEY_LOCAL_MACHINE\Software\Wow6432Node\Microsoft\Office\14.0\Common\InstallRoot /v Path"
goto office

:3
cscript "%SystemRoot%\system32\slmgr.vbs" /skms %KMS_Server%
cscript "%SystemRoot%\system32\slmgr.vbs" -ato
pause
exit