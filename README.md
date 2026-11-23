# COS10026 Project 2 - SwinEduTech (Group DVMT)

Our COS10026 website. It starts from the Part 1 pages.

## Folders

```
project2/
├── index.php, about.html, jobs.html, apply.html
├── images/          page images, logo, icons
└── styles/
    ├── style.css    all the CSS
    └── images/      images that style.css loads with url()
```

## Run the site

You need XAMPP with PHP 8 and MySQL/MariaDB.

1. Clone the repo into `C:\xampp\htdocs\project2`. If you keep it somewhere else, link it in instead (PowerShell):
   `New-Item -ItemType Junction -Path C:\xampp\htdocs\project2 -Target <your-clone-folder>`
2. Start Apache and MySQL in the XAMPP Control Panel.
3. Go to `http://localhost/project2/index.php`.

## Database

- User: `root`, empty password (the XAMPP default)
- Database: `hcm_thursdaymorning_09`

Create it once in phpMyAdmin (`http://localhost/phpmyadmin`), or from the command line:

```
C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE IF NOT EXISTS hcm_thursdaymorning_09 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

When the `.sql` file is added, select the database in phpMyAdmin and use **Import** to load it.

## Team

- Nguyen Dang Vinh
- Nguyen Truong Minh Thuy
