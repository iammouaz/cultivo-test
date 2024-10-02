# M Cultivo Auction [WIP]
M Cultivo is a web-based ERP system. This repository contains the back-end code for the M Cultivo ERP 2.0.

## Table of Contents. 
 
- [Getting Started](#getting-started)
  - [Prerequisites](#prerequisites)
  - [Installation](#installation)
- [Usage](#usage)
- [License](#license)


## Getting Started

To set up and run the M Cultivo auction locally, follow the below steps. Before you begin, make sure you have the following prerequisites installed:

- Docker
- A code editor (e.g., Visual Studio Code)

## Installation

1. Clone the project repository:

   ```sh
   git clone https://gitlab.mcultivo.com/mcultivo/auction.git
   ```


### Run using Docker

We recommend using Docker to run the project, as it is a simpler approach not requiring you to install local packages. To run the frontend using docker, simply run the following when you are in the repository directory:

   ```sh
   docker-compose up -d
   ```

We are almost there - update your `.env` file with the following parameters:

`DB_USERNAME=auction_staging`
`DB_DATABASE=auction_staging`
`DB_PASSWORD=auction_staging`

`DB_HOST` is supposed to be `mysql` and `DB_PORT` is `3306` but for some reason the docker compose bridge network is not working on my machine so you have to set `DB_HOST` to your machines ip and `DB_PORT=8082`.
```sh
DB_HOST=your_machine_ip
DB_PORT=8082
```

Please reach out to a project administrator to get these values, or they can be derived simply from looking at the Docker files.

Now, with DB credentials set, run the following commands:

   ```sh
   docker compose exec php-fpm composer update

   docker compose exec php-fpm php artisan config:cache

   docker compose exec php-fpm php artisan migrate --seed
   ```

You should now be able to load the project at [http://localhost:8080](http://localhost:8080). You will simply see the Laravel home page, and to confirm the actual APIs are working you can load [http://localhost:8080/api/version](http://localhost:8080/api/version) to see what version of the application you are running.
