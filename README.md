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

3. Build the Docker container (for the first time only):
    ```bash
    docker-compose up --build
    ```
4. Open a new terminal and go to the application directory (where you cloned the project) and run the following command to access the PHP container shell:
    ```bash
    docker-compose exec php-fpm bash
    ```
   
5. Create a copy of .env.local file
   ```bash
    cp .env .env.local
    ```

6. Create a database schema
   ```bash
    php bin/console doctrine:schema:update --force
    ```
   
7. Run following data fixture in order to fill up the tables with some data (Be careful: that after running this command all the data in the database will be lost):
    ```bash
    php bin/console doctrine:fixtures:load --no-interaction
    ```
      
8. Access the application via: [http://localhost](http://localhost) in your web browser.

9. You will be asked to login. Use the following credentials or register a new user:
    - Username: `i@bakhtiyor.tj`
    - Password: `123456`

## Contact

You can reach me at [@bakhtiyorbs](https://t.me/bakhtiyorbs) for any further questions.
