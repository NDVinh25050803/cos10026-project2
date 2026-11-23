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
