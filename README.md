# COS10026 Project 2 - SwinEduTech (Group HCM_ThursdayMorning_09)

Our COS10026 website. It starts from the Part 1 pages.

## Folders

```
project2/
├── index.php, jobs.php, apply.php, about.php
├── header.inc, nav.inc, footer.inc   shared page parts
├── settings.php     database settings
├── db_connect.php   opens the database connection
├── sql/             tables and seed data, one file per table group
├── docs/            database design (ERD, data dictionary)
├── .htaccess        stops the browser from opening .inc, .sql and .md files
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

Then load the tables. In phpMyAdmin, select the database and **Import** each file in `sql/`: `eoi.sql`, `jobs.sql`, `users.sql`, `about.sql`. Or run this in **Command Prompt** (not PowerShell) from the project folder:

```
for %f in (eoi jobs users about) do C:\xampp\mysql\bin\mysql.exe -u root --default-character-set=utf8mb4 hcm_thursdaymorning_09 < sql\%f.sql
```

Each file drops and recreates its own tables, so running it again resets that data. The HR login is `admin` / `admin`.

Tables and columns are described in [docs/database.md](docs/database.md).

## How we work

**Branches and commits.** One branch per Jira card, named after the card key, e.g. `HCM-15-header-inc`. Commit messages start with the key too: `HCM-15: add header.inc`.

**Review.** Nobody pushes straight to `main`. Open a pull request and the other person reviews it before it gets merged. GitHub enforces this on `main`.

**Jira.** Move your card as you go: To Do → In Progress → In Review (PR is open) → Done (PR is merged).

**Definition of Done.** A card is done when:

- the page works on `http://localhost/project2/`
- the other member has reviewed the PR
- the PR is merged into `main`
- the Jira card is in Done

## Team

- Nguyen Dang Vinh
- Nguyen Truong Minh Thuy
