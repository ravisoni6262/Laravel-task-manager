## Setup
- Create a database in your host
- Open .env and change these attributes :   
	- `APP_URL = http://localhost/foldername` (or site root)  
	- `DB_DATABASE = DatabaseName`   
	- `DB_USERNAME = DatabaseUsername` (or root)
	- `DB_PASSWORD = DatabasePassword` (or blank it)
	
- Run this command : `php artisan migrate:fresh --seed`
- Now you can run the application.

## Instructions
- Click on the project button in nav bar for project lists.
- For selecting a project please click on the name of that.
- If you select a project, the project will select for next run. 
