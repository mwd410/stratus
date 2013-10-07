@echo off

npm -v >nul 2>&1 && (

    cd %~dp0
    npm install
    %~dp0node_modules\bower\bin\bower.exe install

    echo Finished injecting dependencies.
) || (
    echo You must have node / npm installed. Once completed, run this script again.
    exit /b
)
