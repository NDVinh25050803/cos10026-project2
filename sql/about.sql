-- About page tables, seeded with the Part 1 data. Author: Nguyen Dang Vinh.
-- Re-running this file resets the About page data.
SET NAMES utf8mb4;

DROP TABLE IF EXISTS contributions;
DROP TABLE IF EXISTS fun_facts;
DROP TABLE IF EXISTS members;

CREATE TABLE members (
  member_id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name         VARCHAR(100) NOT NULL,
  student_id        VARCHAR(10)  NOT NULL,
  quote_vi          VARCHAR(255) NOT NULL,
  quote_translation VARCHAR(255) NOT NULL,
  sort_order        TINYINT UNSIGNED NOT NULL,
  PRIMARY KEY (member_id),
  UNIQUE KEY uq_members_student_id (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- part = assignment part (1 or 2), so Part 2 work can be added later.
CREATE TABLE contributions (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  member_id   INT UNSIGNED NOT NULL,
  part        TINYINT UNSIGNED NOT NULL,
  sort_order  TINYINT UNSIGNED NOT NULL,
  description VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_contrib_member (member_id, part, sort_order),
  CONSTRAINT fk_contrib_member FOREIGN KEY (member_id) REFERENCES members (member_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- One row per member. Snack is the Vietnamese name, snack_english the meaning.
CREATE TABLE fun_facts (
  member_id     INT UNSIGNED NOT NULL,
  dream_job     VARCHAR(100) NOT NULL,
  snack         VARCHAR(100) NOT NULL,
  snack_english VARCHAR(100) NOT NULL,
  hometown      VARCHAR(100) NOT NULL,
  PRIMARY KEY (member_id),
  CONSTRAINT fk_facts_member FOREIGN KEY (member_id) REFERENCES members (member_id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO members (member_id, full_name, student_id, quote_vi, quote_translation, sort_order) VALUES
(1, 'Nguyen Dang Vinh', 'SWS00172', 'Có công mài sắt, có ngày nên kim.',
 'If you keep grinding an iron bar, one day it becomes a needle. Hard work pays off.', 1),
(2, 'Nguyen Truong Minh Thuy', 'SWD00450', 'Đi một ngày đàng, học một sàng khôn.',
 'Travel for one day and you gain a basket of wisdom. Every new experience teaches you something.', 2);

INSERT INTO contributions (member_id, part, sort_order, description) VALUES
(1, 1, 1, 'Built jobs.html and about.html'),
(1, 1, 2, 'Wrote the colour variables, typography and layout in style.css used by all four pages'),
(1, 1, 3, 'Ran HTML validation and accessibility checks on all pages'),
(2, 1, 1, 'Built index.html and apply.html'),
(2, 1, 2, 'Set up the Jira board for the project'),
(2, 1, 3, 'Deployed the website on GitHub Pages');

INSERT INTO fun_facts (member_id, dream_job, snack, snack_english, hometown) VALUES
(1, 'AI Engineer',     'Cà phê sữa đá',  'iced milk coffee',  'Buon Ma Thuot, Dak Lak'),
(2, 'DevOps Engineer', 'Bánh tráng trộn', 'rice paper salad', 'Ho Chi Minh City');
