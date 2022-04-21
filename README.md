<h2>Tech stack</h2><br/>
Laravel 8<br/>
PHP 8.0 and Above<br/>
MySQL<br/>
Apache<br/><br/>
<h2>Setup</h2><br/>
clone the repo<br/>
save as .env.example as .env<br/>
update the required configuration for .env file<br/>
cd to the project directory<br/>
execute composer install<br/>
execute php artisan key:generate<br/>
execute php artisan migrate<br/><br/>

<h2>API Usage</h2><br>
<b>Create Key Value  </b><br>
<ul>
<li>{host}/api/object { "keyName": "value"} <br/>   Method : POST </li>
</ul><br/>
<b>Get Laest Key Value  </b><br>

<ul><li>{host}/api/object/{keyName} <br/>   Method : GET   </li> </ul><br> 
<b>Get object with timestamp  </b><br> 
<ul><li>{host}api/object/{keyName}?timestamp={unixTimestamp}<br>Method : GET   </li> </ul><br>

<b>Get all records - GET method</b><br>

<ul><li>{host}api/object/get_all_records <br>Method : GET   </li> </ul><br>
<h2>Testing</h2> <br/>
php artisan test --testsuite=Feature
