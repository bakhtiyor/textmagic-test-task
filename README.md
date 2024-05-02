# Online testing platform

## Prerequisites

Before you begin, ensure you have met the following requirements:

- You have installed the latest version of [Git](https://git-scm.com/) and [Docker](https://www.docker.com/).

## Deploying Project Name

To deploy the Project, follow these steps:

1. Clone the repository:
    ```bash
    git clone https://github.com/bakhtiyor/textmagic-test-task
    ```

2. Navigate to the project directory:
    ```bash
    cd textmagic-test-task
    ```

3. Start the Docker container as a daemon (it would take a bit of time if you're running it for the first time):
    ```bash
    docker-compose up -d
    ```

4. Create the database schema:
    ```bash
    docker-compose exec php-fpm bash
    php bin/console doctrine:schema:create
    ```   

5. Create a copy of .env.local file:
    ```bash
    cp .env .env.local
    ```

6. Specify the database connection in the .env.local file:
    ```bash
    DATABASE_URL="postgresql://test_user:linux1@db/postgres?serverVersion=16&charset=utf8"
    ```

7. Run following data fixture in order to fill up the tables with some data (Note: that after running this command all the data in the database will be lost):
    ```bash
    php bin/console doctrine:fixtures:load
    ```
      
8. Access the application via: `http://localhost` in your web browser.

## Contact

If you want to contact me you can reach me at [@bakhtiyorbs](https://t.me/bakhtiyorbs).
