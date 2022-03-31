<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

<h1>leadPops assignment</h1>

<h4><strong>Requirements</strong></h4>
<p>Local server runing (Apache Web Server and MySql Database)</p>

<br>

<h4><strong>Setup enviroment/db</strong></h4>
<ol>
    <li>1. Clone git repository (https://github.com/MladenStanisic/jwt_assignment.git)</li>
    <li>2. Open <strong>jwt_assigment</strong> folder in terminal</li>
    <li>3. Run: php artisan db:create (custom made db creational tool with artisan)</li>
    <li>4. php artisan migrate</li>
    <li>5. If you already don't have, install desktop version of <a href="https://www.postman.com/downloads/">Postman app</p> to test API calls on your local machine</li>
</ol>

<br>

<h4><strong>Endpoints</strong></h4>
<ol>
    <li><strong>Create JTW token</strong>: Send POST request to http://localhost:8000/api/create-token, with valid email key/value param to create JTW token</li>
    <li><strong>Create short url</strong>: (Only if JTW token is validated), Send POST request to http://localhost:8000/api/shorturl, with key name_url_long and valid URL value param to create short url (name_url_short)</li>
    <li><strong>Open long URL</strong>: (Only if JTW token is validated), Send GET request to http://localhost:8000/api/shorturl/{name_url_short}</li>
</ol>

<h4><strong>Postman collection used for testing</strong></h4>
<p>File: <a href="postman_tests.json">Download</a></p>

