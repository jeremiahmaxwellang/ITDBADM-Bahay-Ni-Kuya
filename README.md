# Bahay Ni Kuya - Real Estate Website
This project is developed as a requirement of ITDBADM

# Project Overview:
Bahay Ni Kuya is a website where users can purchase houses and view pertinent details such as location.

## Getting Started

### Dependencies

* XAMPP Control Panel
[https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)

### Installation

* Download ZIP of main branch on GitHub
* Extract the project folder to your ```xampp/htdocs``` folder
* To find the htdocs folder, open your XAMPP Control Panel and press [Explorer] to open
the file location of the xampp folder.

### Running the CREATE SCHEMA SQL script
1. Under the database/db-design subfolder, find the [bnk-schema.sql] script
2. Open the file in any text editor, then copy all its contents 
3. Open XAMPP Control panel, then [Start] the MySQL service
4. Once the service started, press the [Admin] button
5. After you're redirected to the admin page on your browser, click [SQL] on the top of the screen
6. Paste the sql file contents in the text box, then press [Go] on the bottom right
7. Now the [bahaynikuya_db] schema should be visible on the left side.

### Populating the DATABASE
1. PREREQUISITE: Ensure the [bahaynikuya_db] schema exists in your phpadmin databases
2. Under the database/db-design subfolder, find the [bnk-data.sql] script
3. Open the file in any text editor, then copy all its contents 
4. Open XAMPP Control panel, then [Start] the MySQL service
5. Once the service started, press the [Admin] button
6. After you're redirected to the admin page on your browser, click [SQL] on the top of the screen
7. Paste the sql file contents in the text box, then press [Go] on the bottom right
8. Click on any of the tables in [bahaynikuya_db] schema to verify that the sample data was inserted.


### Executing program

* Open XAMPP Control Panel

* Open the Apache service
- Wait for the "Apache" label on the left to be highlighted green

* Open the MySQL service
- Wait for the "MySQL" label on the left to be highlighted green

* On your browser, enter the following URL to access the Bahay Ni Kuya login page
```
localhost/ITDBADM-Bahay-Ni-Kuya-main/views/login.php
localhost/ITDBADM-Bahay-Ni-Kuya/views/login.php
```

## Help

* If XAMPP cannot start the MySQL service, try:
- Opening Task Manager
```
CTRL + SHIFT + ESC
```

- Pressing Services
- Right click the [MySQL] service and press "Stop"
- Go back to XAMPP and start the MySQL service, 
then wait for the terminal to display the following:
```
2:22:04 PM  [mysql] 	Attempting to start MySQL app...
2:22:04 PM  [mysql] 	Status change detected: running
```


## Authors
Jeremiah Maxwell Ang
[@jeremiahmaxwellang](https://github.com/jeremiahmaxwellang)

Charles Kevin Duelas
[@Duelly01](https://github.com/Duelly01)

Justin Nicolai Lee
[@juicetice](https://github.com/juiceticedlsu)

Marcus Anton Mendoza
[@makoy1017](https://github.com/makoy1017)
