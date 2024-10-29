# TaskBladesystem


## Description
This project is a **task system ** built with **Laravel 10** with blade file  for managing tasks .
 It allows user to create , edit and delete Tasks and the page will show daily task (task for today only) , and the user can change status of task .
in this system we use cron job that allow to send pending daily tasks to all user in system . 
 


### Technologies Used:
- **Laravel 10**
- **PHP**
- **MySQL**
- **XAMPP** (for local development environment)
- **Composer** (PHP dependency manager)


## Features

- Breeze Authentication
- Job , Caching  to enhance DB working 
- Error Handling for all action 




## Setting up the project

1. Clone the repository 

   git clone https://github.com/hiba-altabbal95/BladeTask
   
2. navigate to the project directory
  
    cd BladeTask  

3. install Dependencies: composer install 

4. create environment file  cp .env.example .env
  
5. edit .env file (DB_DATABASE=tb)

6. Generate Application Key php artisan key:generate

7. Run Migrations To set up the database tables, run: php artisan migrate
	
8. Run the Application
   
    php artisan serve

    npm run dev 
	
	then you can open browser and write http://127.0.0.1:8000] in the link 
	
	start laravel page with register and log in button 
	
	you can register then logged in to manage task (daily task page will appear after loggin)
	
9. to start command write in terminal 

  php artisan schedual:work 	


