# Bahay Ni Kuya - Real Estate Website
This project is developed as a requirement of ITDBADM

# Project Overview:
Bahay Ni Kuya is a website where users can purchase houses and view pertinent details such as location.

## Getting Started

### Dependencies

* XAMPP Control Panel
[https://www.apachefriends.org/download.html](https://www.apachefriends.org/download.html)

### Installation

* Quick tutorial on installing XAMPP on Windows 10/11:
[https://www.youtube.com/watch?v=VCHXCusltqI](https://www.youtube.com/watch?v=VCHXCusltqI)


* Download ZIP of main branch on GitHub
* Extract the project folder to your ```xampp/htdocs``` folder
* To find the htdocs folder, open your XAMPP Control Panel and press [Explorer] to open
the file location of the xampp folder.

### Creating the DATABASE, PROCEDURES, and TRIGGERS
#### Step 1: Find the sql scripts in database/scripts
* Under the ```database/scripts``` subfolder, find the [bnk-schema.sql] script

#### Step 2: Copy paste the script into myphpadmin
1. Open the file in any text editor, then copy all its contents 
2. Open XAMPP Control panel, then [Start] the MySQL and Apache service
3. Once the service started, press the [Admin] button beside MySQL
5. After you're redirected to the admin page on your browser, click [SQL] on the top of the screen
6. Paste the sql file contents in the text box, then press [Go] on the bottom right
7. Refresh the page and the [bahaynikuya_db] schema should be visible on the left side.

#### Step 3: Repeat the steps for stored procedures & triggers
* Repeat the Steps above for the ```procedures.sql``` and ```triggers.sql``` script to complete the DB.

### Populating the DATABASE
1. PREREQUISITE: Ensure the [bahaynikuya_db] schema exists in your phpadmin databases
2. Under the ```database/scripts``` subfolder, find the [bnk-data.sql] script
3. Open the file in any text editor, then copy all its contents 
4. Open XAMPP Control panel, then [Start] the MySQL and Apache service
5. Once the service started, press the [Admin] button beside MySQL
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
localhost/ITDBADM-Bahay-Ni-Kuya/views/login.php
```

## Help

### Issue: Cannot Start MySQL on XAMPP
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

### Issue: Cannot Switch Users (Admin/Staff) on myphpadmin

YouTube Video Tutorial:
[https://youtu.be/lNcdsK-RUyw](https://youtu.be/lNcdsK-RUyw)

#### Step 1: Create Admin and Staff Accounts
* Run the ```database/scripts/privileges.sql``` script to create the Admin and Staff accounts
* Note: the Admin account created will have full privileges to all databases.

#### Step 2: Download myphpadmin 5.2.2
* Download latest version of myphpadmin
[https://www.phpmyadmin.net/files/5.2.2/](https://www.phpmyadmin.net/files/5.2.2/)

* Delete the old ```xampp/phpMyAdmin``` folder

* Unzip the newly downloaded ```phpMyAdmin-5.2.2-all-languages``` folder 

* Rename the folder into ```phpMyAdmin```

* Move the folder into the ```xamp``` folder

* On your browser, enter the following URL:
```
http://localhost/phpmyadmin/index.php?route=/
```

* Login using the Admin user you created in Step 1:
```Username: admin```
```Password: adminpassword```

* To logout of Admin, press the Logout button on the top left corner below the "myphpadmin" logo
(The button to the right of the small house icon)

```Username: staff```
```Password: staffpassword```

### 🛠️ Issue: Cannot Delete Database in phpMyAdmin (XAMPP) — "Directory Not Empty"

If you're using XAMPP and encounter the error **"Cannot delete database, directory not empty"** in phpMyAdmin, this guide will help you resolve it safely.

---

## ⚠️ Problem

When attempting to delete a database via phpMyAdmin, you may see:
```Cannot delete database: Directory not empty```


This typically occurs when MySQL cannot remove the database folder from the file system due to leftover files or locked resources.

---

## ✅ Solution Steps

### 0. Try dropping the Database first

On the SQL tab in myphpadmin, use the following SQL statement:
```  DROP SCHEMA bahaynikuya_db;  ```

## If Schema is not dropping:

### 1. Stop MySQL Service

Before making any changes:

- Open **XAMPP Control Panel**
- Click **Stop** next to **MySQL**

This ensures no files are in use or locked.

---

### 2. Locate the MySQL Data Directory

Navigate to the MySQL data folder:

```C:\xampp\mysql\data\```


Find the folder named after your database (e.g., `bahaynikuya_db`).

---

### 3. Delete the Database Folder Manually

- Right-click the folder (e.g., `bahaynikuya_db`)
- Select **Delete**
- If Windows prevents deletion, restart your computer or confirm MySQL is fully stopped

---

### 4. Restart MySQL

- Return to **XAMPP Control Panel**
- Click **Start** next to **MySQL**

Your database should now be removed from phpMyAdmin.

---

## 🧹 Optional Cleanup

If the folder contains leftover files like:

- `table_name.frm`
- `table_name.ibd`
- `db.opt`

### 5. Run the DB Scripts

- In the myphpadmin SQL menu, copy paste and run the following scripts:
- bnk-schema.sql
- procedures.sql
- triggers.sql

Your database should now be removed from phpMyAdmin.


## Authors
Jeremiah Maxwell Ang
[@jeremiahmaxwellang](https://github.com/jeremiahmaxwellang)

Charles Kevin Duelas
[@Duelly01](https://github.com/Duelly01)

Justin Nicolai Lee
[@juicetice](https://github.com/juiceticedlsu)

Marcus Anton Mendoza
[@makoy1017](https://github.com/makoy1017)
