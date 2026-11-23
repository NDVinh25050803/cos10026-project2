-- users: HR manager logins for manage.php. Author: Nguyen Dang Vinh.
-- Re-running this file resets all accounts.
SET NAMES utf8mb4;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
  id              INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username        VARCHAR(50)  NOT NULL,
  password_hash   VARCHAR(255) NOT NULL,
  -- Used to lock the account after too many wrong passwords.
  failed_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
  locked_until    DATETIME     NULL,
  created_at      DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- admin / admin. The hash comes from PHP password_hash('admin', PASSWORD_DEFAULT).
INSERT INTO users (username, password_hash) VALUES
('admin', '$2y$12$J9U5txMWl6338uc.w4/iLuCGA4Tn9zS/9I2wt9aIk/1G9BsdreyUK');
