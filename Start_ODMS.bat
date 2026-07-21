@echo off
title DENR Finance OBMS
color 0A

echo ============================================
echo      DENR FINANCE OBMS STARTER
echo ============================================
echo.

:: Project Location :: edit
set PROJECT=C:\Users\kyla5\denr-finance-obms

:: XAMPP Location :: edit
set XAMPP=C:\xampp

echo [1/5] Starting Apache...
start "" "%XAMPP%\apache_start.bat"

timeout /t 3 >nul

echo [2/5] Starting MySQL...
start "" "%XAMPP%\mysql_start.bat"

timeout /t 5 >nul

echo [3/5] Opening Project...
cd /d "%PROJECT%"

echo [4/5] Clearing cache...
php artisan optimize:clear

:: edit
echo [5/5] Starting Laravel Server...
start "Laravel" cmd /k "cd /d %PROJECT% && php artisan serve --host=10.100.42.89 --port=8000"

timeout /t 5 >nul

:: edit
echo Opening browser...
start http://10.100.42.89:8000

echo.
echo ============================================
echo DENR Finance OBMS is now running!
echo ============================================
pause