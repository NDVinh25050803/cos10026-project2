-- Job tables and the three Part 1 jobs. Author: Nguyen Dang Vinh.
-- Re-running this file resets all job data.
-- Closing dates are later than Part 1 (30 Nov) so the jobs stay open for the demo.
SET NAMES utf8mb4;
DROP TABLE IF EXISTS job_responsibilities;
DROP TABLE IF EXISTS job_requirements;
DROP TABLE IF EXISTS jobs;
DROP TABLE IF EXISTS perks;

CREATE TABLE jobs (
  job_ref      CHAR(5)      NOT NULL,
  title        VARCHAR(100) NOT NULL,
  team         VARCHAR(50)  NOT NULL,
  location     VARCHAR(100) NOT NULL,
  work_type    VARCHAR(50)  NOT NULL,
  salary       VARCHAR(100) NOT NULL,
  reports_to   VARCHAR(100) NOT NULL,
  summary      TEXT         NOT NULL,
  closing_date DATE         NOT NULL,
  PRIMARY KEY (job_ref)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_responsibilities (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_ref     CHAR(5)      NOT NULL,
  sort_order  TINYINT UNSIGNED NOT NULL,
  description VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_resp_job (job_ref, sort_order),
  CONSTRAINT fk_resp_job FOREIGN KEY (job_ref) REFERENCES jobs (job_ref)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE job_requirements (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  job_ref     CHAR(5)      NOT NULL,
  type        ENUM('essential','preferable') NOT NULL,
  sort_order  TINYINT UNSIGNED NOT NULL,
  description VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  KEY idx_req_job (job_ref, type, sort_order),
  CONSTRAINT fk_req_job FOREIGN KEY (job_ref) REFERENCES jobs (job_ref)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Perks apply to every job (the "Working at SwinEduTech" box).
CREATE TABLE perks (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
  sort_order  TINYINT UNSIGNED NOT NULL,
  description VARCHAR(255) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO jobs (job_ref, title, team, location, work_type, salary, reports_to, summary, closing_date) VALUES
('FE001', 'Front-End Developer', 'Engineering', 'Melbourne (Hawthorn), hybrid', 'Full-time, permanent',
 '$95,000 to $120,000 a year plus super', 'Lead Front-End Engineer',
 'You will build the student and teacher pages of our learning platform and keep them fast and accessible to WCAG 2.2 AA. In your first three months you will learn our component library, fix accessibility bugs on the quiz pages and ship your first feature with a senior developer.',
 '2026-12-31'),
('FS002', 'Full-Stack Developer', 'Engineering', 'Remote, Australia', 'Full-time, permanent',
 '$100,000 to $125,000 a year plus super', 'Engineering Manager',
 'You will build features across the whole platform, from the teacher dashboard to the services behind it. Student data is at the centre of this role, so you will help keep it secure and private while the platform grows.',
 '2026-12-31'),
('UX003', 'UI/UX Designer', 'Design', 'Melbourne (Hawthorn), hybrid', 'Part-time, 3 days a week',
 '$90,000 to $115,000 a year pro rata plus super', 'Head of Product Design',
 'You will design new features for our online classroom app. You will talk with teachers and students to find out what they need, then design screens that are clear and inclusive. Your first project is a redesign of the assignment upload screen, which students told us is confusing on phones.',
 '2026-12-31');

INSERT INTO job_responsibilities (job_ref, sort_order, description) VALUES
('FE001', 1, 'Build page layouts and components with HTML, CSS and JavaScript.'),
('FE001', 2, 'Test pages with screen readers and keyboard only navigation.'),
('FE001', 3, 'Fix bugs reported by teachers and students.'),
('FE001', 4, 'Review code from other developers.'),
('FE001', 5, 'Turn designer mock-ups into working pages.'),
('FS002', 1, 'Build features end to end with Node.js, PostgreSQL and our front-end components.'),
('FS002', 2, 'Design and maintain the APIs used by our web and mobile apps.'),
('FS002', 3, 'Protect student data with secure coding and careful access control.'),
('FS002', 4, 'Write automated tests and review pull requests.'),
('UX003', 1, 'Run interviews and user tests with teachers and students.'),
('UX003', 2, 'Create wireframes, prototypes and final designs in Figma.'),
('UX003', 3, 'Check colour contrast and text size in every design.'),
('UX003', 4, 'Keep our design system up to date.');

INSERT INTO job_requirements (job_ref, type, sort_order, description) VALUES
('FE001', 'essential',  1, '2 or more years building websites with HTML, CSS and JavaScript'),
('FE001', 'essential',  2, 'Good understanding of semantic HTML and WCAG'),
('FE001', 'essential',  3, 'Experience using Git in a team'),
('FE001', 'essential',  4, 'Right to work in Australia'),
('FE001', 'preferable', 1, 'Experience with React or Vue'),
('FE001', 'preferable', 2, 'Worked on a learning management system before'),
('FS002', 'essential',  1, '3 or more years of full-stack web development'),
('FS002', 'essential',  2, 'Experience with Node.js or a similar back-end language, and SQL'),
('FS002', 'essential',  3, 'Working knowledge of the OWASP Top 10 security risks'),
('FS002', 'essential',  4, 'Right to work in Australia'),
('FS002', 'preferable', 1, 'Knowledge of the Australian Privacy Principles'),
('FS002', 'preferable', 2, 'Experience deploying to a cloud platform such as AWS'),
('UX003', 'essential',  1, 'A portfolio showing web or app design work'),
('UX003', 'essential',  2, 'Strong skills in Figma'),
('UX003', 'essential',  3, 'Knowledge of accessible design'),
('UX003', 'essential',  4, 'Basic HTML and CSS, so you can work closely with developers'),
('UX003', 'essential',  5, 'Right to work in Australia'),
('UX003', 'preferable', 1, 'Background in teaching or education'),
('UX003', 'preferable', 2, 'Experience testing designs with screen readers');

INSERT INTO perks (sort_order, description) VALUES
(1, 'Hybrid work with 3 days from home'),
(2, '$1,500 yearly learning budget'),
(3, '5 extra days of leave'),
(4, 'Paid accessibility training');
