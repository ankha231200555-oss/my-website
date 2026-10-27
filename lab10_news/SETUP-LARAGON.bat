@echo off
setlocal
cd /d "%~dp0"
echo Hay tao database lab10_news trong MySQL truoc khi chay tep nay.
where php >nul 2>nul
if errorlevel 1 goto missing
where composer >nul 2>nul
if errorlevel 1 goto missing
call composer install --prefer-dist --no-interaction
if errorlevel 1 goto failed
if not exist .env copy .env.example .env >nul
findstr /r /c:"^APP_KEY=." .env >nul
if errorlevel 1 php artisan key:generate
if errorlevel 1 goto failed
php artisan optimize:clear
if errorlevel 1 goto failed
php artisan migrate --seed
if errorlevel 1 goto failed
if not exist public\storage php artisan storage:link
if errorlevel 1 goto failed
echo Hoan tat. Chay: php artisan serve
echo Sau do mo http://127.0.0.1:8000/admin/tin
pause
exit /b 0
:missing
echo Khong tim thay PHP hoac Composer. Hay mo Terminal cua Laragon va dung PHP 8.2 tro len.
pause
exit /b 1
:failed
echo Cai dat chua hoan tat. Kiem tra loi o tren, MySQL, database va tep .env.
pause
exit /b 1
