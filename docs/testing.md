# Validation & Security Test Plan (`process_eoi.php`)

| ID | Test Case | Input | Expected | Actual |
| :--- | :--- | :--- | :--- | :--- |
| TC-01 | Valid application | Valid `job_ref` from `jobs`, valid name, valid DOB (`dd/mm/yyyy`, age 15–80), matching `state` & `postcode`, valid email, 8–12 digit phone, `HTML5 & CSS3` checked | Inserts row into `eoi` with status `New` and shows confirmation with `EOInumber` | Inserted into `eoi` with status `New` and displayed confirmation page with assigned `EOInumber`. |
| TC-02 | Empty form | All required fields left blank / unchecked | Error page lists every missing/invalid required field with link back to `apply.php` | Displayed `Application Not Submitted` listing all required field errors with `Back to application form` link. |
| TC-03 | Postcode not matching state | Mismatched `state` and `postcode` (e.g., `VIC` with `2000`) | Rejected with clear state/postcode mismatch error message | Rejected on error page with postcode/state mismatch validation error. |
| TC-04 | Invalid calendar DOB | DOB = `31/02/2000` | Rejected as non-existent / invalid calendar date | Rejected on error page as invalid date of birth (`dd/mm/yyyy`). |
| TC-05 | Underage DOB | DOB corresponding to age `12` | Rejected as outside allowed age range (15–80) | Rejected on error page as outside age range 15–80. |
| TC-06 | Bad email format | Invalid email (e.g., `invalid-email` or `user@`) | Rejected with email format error message | Rejected on error page with invalid email format error. |
| TC-07 | 7-digit phone number | Phone = `1234567` (7 digits) | Rejected because phone must be 8–12 digits | Rejected on error page because phone number must be 8–12 digits. |
| TC-08 | Unknown `job_ref` | `job_ref` not present in `jobs` table (e.g., `ZZ999`) | Rejected because job reference does not exist in `jobs` | Rejected on error page because job reference number does not exist in `jobs`. |
| TC-09 | HTML/CSS skill unticked | `HTML5 & CSS3` checkbox unchecked | Rejected requiring `HTML5 and CSS3` selection | Rejected on error page with `HTML5 and CSS3 must be selected.` |
| TC-10 | Name with apostrophe | Name = `O'Brien` | Rejected with clear "letters only" error message | Rejected on error page with `First name must contain letters only, maximum 20 characters.` |
| TC-11 | Street address with apostrophe | Street address = `12 O'Connell St` (valid submission) | Accepted and displayed as typed on result / `manage.php` (without raw `&#039;`) | Accepted when all other fields are valid; stored/escaped safely via prepared statement and `escape_html`. |
| TC-12 | SQL injection in name | Name = `' OR '1'='1` | Stored as literal text or rejected; never breaks SQL query | Rejected with `First name must contain letters only, maximum 20 characters.` without SQL error. |
| TC-13 | XSS payload | `<script>alert(1)</script>` in text / other skills | No script execution (`alert(1)`); rejected or escaped safely in output / `manage.php` | No `alert(1)` popup executed; rejected or safely escaped in output. |
| TC-14 | Direct GET to `process_eoi.php` | Direct `GET` request to `/project2/process_eoi.php` | Redirects back to `apply.php` | Redirected to `http://localhost/project2/apply.php`. |

