-- eoi: one row per job application from apply.php. Author: Nguyen Dang Vinh.
-- Re-running this file deletes all applications.
SET NAMES utf8mb4;
DROP TABLE IF EXISTS eoi;

CREATE TABLE eoi (
  EOInumber        INT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_ref          CHAR(5)      NOT NULL,
  first_name       VARCHAR(20)  NOT NULL,
  last_name        VARCHAR(20)  NOT NULL,
  dob              DATE         NOT NULL,
  gender           ENUM('female','male','other','not_say') NOT NULL,
  -- Address, suburb and email are stored escaped, so they get extra room.
  street_address   VARCHAR(255) NOT NULL,
  suburb           VARCHAR(255) NOT NULL,
  state            ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
  postcode         CHAR(4)      NOT NULL,
  email            VARCHAR(255) NOT NULL,
  phone            VARCHAR(12)  NOT NULL,
  -- One column per skill checkbox: 1 = ticked.
  skill_html_css   TINYINT(1)   NOT NULL DEFAULT 0,
  skill_javascript TINYINT(1)   NOT NULL DEFAULT 0,
  skill_ui_ux      TINYINT(1)   NOT NULL DEFAULT 0,
  skill_python     TINYINT(1)   NOT NULL DEFAULT 0,
  other_skills     TEXT         NULL,
  status           ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
  created_at       DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (EOInumber),
  KEY idx_eoi_job_ref (job_ref),
  KEY idx_eoi_name (last_name, first_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
