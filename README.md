# Description 
Simple portal that allows its users to order food from various restaurants registered in the system.

This demo-portal consists with back-office and API for a mobile application for end-users. 
## Requirements
1. Git
2. Docker
## Installation
- `git clone https://github.com/zuldan-dev/deliveryService.git`
- `cp .env.example .env` or `copy .env.example .env` and fill correct credentials
- `docker-compose up -d --build`
- `docker-compose exec php composer install`
- `docker-compose exec php php artisan vendor:publish --tag=public --force`
- `docker-compose exec php php artisan storage:link`
- `docker-compose exec php php artisan key:generate`
- `docker-compose exec php php artisan migrate --seed` seeder works for **local** and **dev** environments only
- `open http://localhost:8000`
## Usage
For login to back-office needs activate link on main page and fill login and password.

In back-office *SuperAdmin* can add/edit/delete: **Restaurants**, **Dishes** and **Drivers**
and assign **Driver** to the **Order**.

Application API routes:
### GET Routes
- `api/dishes/list` - displays list of dishes for selected restaurant **|** no authorization required
- `api/order/status/view` - displays order status, for *processed* status displays driver info **|** client authorization required
- `api/stats/average/cost_of_orders` - displays average cost of all orders for last 30 days **|** superadmin authorization required
- `api/stats/daily/amount_by_drivers` - displays daily amount by drivers for last 30 days **|** superadmin authorization required
### POST Routes
- `api/login` - login by *email* and *password*
- `api/logout` - logging out
### PUT Routes
- `api/order/create` - creates new order **|** client authorization required
- `api/order/status/update` - updates order status **|** client authorization required

[Download](https://documenter.getpostman.com/view/13008132/2sA2r9WPHH) API documentation.
