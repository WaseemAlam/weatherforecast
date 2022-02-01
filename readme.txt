Project Setup Using Docker and PHP8:
Step 1:
For windows users
Download docker desktop and wsl_update package from docker official website and Run as Administrator.
After successfully installed the docker restart your system.

For Ubuntu/Linux Users 
Please follow the below link and execute all the steps in the order.
https://www.digitalocean.com/community/tutorials/how-to-install-and-use-docker-on-ubuntu-18-04 

Step 2: Modify in the .env file ports of APP_PORT,APP_DB_ADMIN_PORT and DB_PORT if these ports are 
already used by other processes.

Step 3: Make sure you have a good and stable internet connection.
Run the below command in your root directory
docker-compose up -d --build
Step 4:
Go to your browser and hit the below url
http://127.0.0.1:8101 or any port that you specify in your .env file and then select a country
Step 5:
In order to run the test you need to go into the container via below

docker ps
The above command will gave you list of all container with your container id then execute below command

docker exec -it container_id bash
Now the below command is used to run the unittesting.

./vendor/bin/phpunit --testdox
if unit testing will fail.then first go to browser and copy the country city weather from browser and add 
it to the tests/ForecastTest.php cityWeather array.
