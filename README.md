<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<h1>leadPops assignment (custom JWT solution)</h1>

<p><strong>Requirements</strong></p>
<p>Local server runing (Apache Web Server and MySql Database)</p>

<br>

<p><strong>Setup enviroment/db</strong></p>
<ol>
    <li>Clone git repository (https://github.com/MladenStanisic/jwt_assignment.git) which contain laravel project</li>
    <li>Open <strong>jwt_assigment</strong> folder in terminal</li>
    <li>Run: php artisan db:create (custom made db creational tool with artisan, by default it will create jwt_assignment db)</li>
    <li>Run: php artisan migrate</li>
    <li>If you already don't have, install desktop version of <a href="https://www.postman.com/downloads/">Postman app</a> to test API calls on your local machine</li>
</ol>

<br>

<p><strong>Endpoints</strong></p>
<ol>
    <li><strong>Create JWT</strong>: Send POST request to http://localhost:8000/api/create-token, with valid email key/value param to create JWT token (only if previous one expired, or no token is created)<br>
     (it would be right to create token after user authentication/login, but i skipped that step to save time)</li>
    <li><strong>Create short url</strong>: (Only if JWT token is validated), Send POST request to http://localhost:8000/api/shorturl, with key name_url_long and valid URL value param to create short url (name_url_short)</li>
    <li><strong>Open long URL</strong>: (Only if JWT token is validated), Send GET request to http://localhost:8000/api/shorturl/{name_url_short}</li>
</ol>

<br>

<p><strong>Postman collection used for testing</strong></p>
<p><a href="postman_tests.json">File</a></p>

