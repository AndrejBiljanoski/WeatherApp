<div id="top"></div>

# About

![image](https://user-images.githubusercontent.com/95134939/173934243-dc47e804-50dc-4e98-aa80-cd8c572297a0.png)

Weather App is a Laravel application used for fetcing data using the [openweather](https://openweathermap.org) API.
* It includes CRUD functionalities for cities and weather data.
* It runs a scheduled command hourly thet fetches data for each city.
* [Laravel Jetstream + Livewire](https://jetstream.laravel.com/2.x/introduction.html) <br>
For authorization with Sanctum.
* [Laravel Sail](https://laravel.com/docs/9.x/sail) <br>
For creating docker containers.
* [Laravel Horizon + Redis](https://laravel.com/docs/9.x/horizon) <br>
For running queues.

<p align="right">(<a href="#top">back to top</a>)</p>

# Installation

_Below is an example of how you can instruct your audience on installing and setting up your app. This template doesn't rely on any external dependencies or services._

1. Get a free API Key at https://openweathermap.org
2. Clone the repo
   ```sh
   git clone https://github.com/AndrejBiljanoski/WeatherApp.git
   ```
3. Copy .env.example to .env
   ```sh
   cp .env.example .env
   ```
4. Enter your API in `.env`
   ```sh
   OPEN_WEATHER_API_KEY = 'ENTER YOUR API KEY';
   ```
5. Enter a username and a password for the database in `.env`
   ```sh
    DB_DATABASE='YOUR DATABASE NAME'
    DB_USERNAME='YOUR USERNAME'
    DB_PASSWORD='YOUR PASSWORD'
   ```
6. Install vendor folder
   ```sh
    composer install
   ```
7. Create APP KEY 
   ```sh
    php artisan key:generate
   ```
7. Build docker containers
   ```sh
   ./vendor/bin/sail up
   ```
8. Migrate and seed in the laravel container
    ```sh
       docker exec -it 'YOUR CONTAINER NAME' bash
       php artisan migrate:fresh --seed
   ```

# Available Commands

### If you wish to get the latest data available from https://openweathermap.org run:
```sh
    php artisan openweather:get
```
### If you wish to only update certain cities, include the optional id parameter:
```sh
    php artisan openweather:get --id=1 --id=2 ...
```
### If you wish to fetch information for single city run:
```sh
    php artisan ciy:get "CITY_ID"
```
Example Response:
![Screenshot from 2022-06-15 23-24-10](https://user-images.githubusercontent.com/95134939/173932858-31cf8bfc-a086-4ea4-aa67-2f15824f0c2a.png)
<p align="right">(<a href="#top">back to top</a>)</p>

### If you wish to make API calls to CRUD methods:
    1. Register a user.
    2. Go to Manage Account -> API Tokens
    3. Create an API token for you desired CRUD functionalities.
    4. Insert the token in the header of the request - Bearer "YOUR TOKEN"
