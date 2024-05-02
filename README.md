# Online testing platform

## Prerequisites

Before you begin, ensure you have met the following requirements:

- You have installed the latest version of [Git](https://git-scm.com/) and [Docker](https://www.docker.com/).

## Deploying Project Name

To deploy the Project, follow these steps:

1. Clone the repository:
    ```bash
    git clone <repository_url>
    ```

2. Navigate to the project directory:
    ```bash
    cd <project_directory>
    ```
   
3. Build the Docker image (just for the first time):
    ```bash
    docker-compose up --build
    ```

4. Start the Docker container as a daemon:
    ```bash
    docker-compose up -d
    ```

5. Create the database schema:
    ```bash
    docker-compose exec php-fpm bash
    php bin/console doctrine:schema:create
    ```   

6. Create a copy of .env.local file:
    ```bash
    cp .env .env.local
    ```

7. Specify the database connection in the .env.local file:
    ```bash
    DATABASE_URL="postgresql://test_user:linux1@db/postgres?serverVersion=16&charset=utf8"
    ```

8. Run following data fixture in order to fill up the tables with some data (Note: that after running this command all the data in the database will be lost):
    ```bash
    php bin/console doctrine:fixtures:load
    ```
      
9. Access the application via: `http://localhost` in your web browser.

## Contact

If you want to contact me you can reach me at [@bakhtiyorbs](https://t.me/bakhtiyorbs).
