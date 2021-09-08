# Simple GitHub Users Profile API
This is a simple API to fetch GitHub user's profile with the foloowing details:
- User's name
- Username or login
- Company
- Number for followers
- Number of public repositories
- Average number of followers per repositories

## 🚀 Installation
This application is designed to run in the Laravel Sail environment. 
So it is required to install the latest [Docker](https://www.docker.com) 
and [Composer](https://getcomposer.org/) in your machine.

To learn more about Laravel Sail, checkout their [documentation.](https://laravel.com/docs/8.x/sail)


Clone the repo in your machine
```
git clone git@github.com:souinhua/simple-github-wrapper.git simple-github-wrapper
```
Then cd into the newly created directory
```
cd simple-github-wrapper
```
Copy `.env.example` to `.env`
```
cd .env.example .env
```
Install all dependencies through composer
```
composer install
```
Boot up Laravel Sail. This will create the docker containers and images
```
./vendor/bin/sail up
```
_This may take a few minutes._

