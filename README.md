# Delay report system

This is a delay report system that user can submit report when their orders delayed.

In this system vendors can have multiple orders, and each order has an expected delivery time. Riders are supposed to
deliver orders to users, and each order just has one and only one trip and this trip can have different statuses. If
delivery time of each order exceeds, user can submit a delay report, and after reporting the delay, base on situation
system will calculate new delivery time, or it will put report in queue to agents handle it.

## Requirements

- Docker and Docker Compose

## Getting started

To run and build, you have to do the following steps:

- Pull the codes
- Move to Docker directory in project root folder.
- Run the following command in the terminal:

```bash
docker-compose up -d --build
```

> **Note** After the first build, containers are built and up, for setting up and installing dependencies run following command:
> ```
> docker exec -it order_delay_app cp .env.example .env
> docker exec -it order_delay_app composer install
> ```


Now your application is running on http://localhost:8080.

- To migrate database run following command :

```bash
docker exec -it order_delay_app php artisan migrate
```

- To seed database run following command:

```bash
docker exec -it order_delay_app php artisan db:seed
```

This command manipulate db with sample data.

- For just create an agent, run following command:

```bash
docker exec -it order_delay_app php artisan db:seed --class=AgentSeeder
```

## Testing

- For running tests run following command in terminal:

```bash
docker exec -it order_delay_app php artisan test
```

This command runs all Integration and unit tests.

## Usage

For using API's there is a swagger file in root of the project, that all the system API's and instruction of using theme
are documented in it.

### Authentication

Just for agents actions, system has authentication, and it uses laravel sanctum to handle authentication. But we can
change authentication method easily by using different implementation for Auth service
