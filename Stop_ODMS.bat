@echo off
title Stop DENR Finance OBMS

echo Stopping Laravel...
taskkill /F /IM php.exe >nul 2>&1

echo Stopping Apache...
taskkill /F /IM httpd.exe >nul 2>&1

echo Stopping MySQL...
taskkill /F /IM mysqld.exe >nul 2>&1

echo.
echo DENR Finance OBMS has been stopped.
pause