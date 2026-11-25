<?php
// Process job application (EOI) submissions. Author: Nguyen Truong Minh Thuy.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['job_ref'])) {
    header('Location: apply.php');
    exit;
}

require_once 'db_connect.php';

function clean_raw(string $value): string
{
    return stripslashes(trim($value));
}

function escape_html(string $value, bool $double_encode = true): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8', $double_encode);
}

function parse_au_dob_and_age(string $dob_raw): ?array
{
    if (!preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dob_raw, $m)) {
        return null;
    }
    $day   = (int) $m[1];
    $month = (int) $m[2];
    $year  = (int) $m[3];

    if (!checkdate($month, $day, $year)) {
        return null;
    }

    $dobDate = DateTimeImmutable::createFromFormat('Y-n-j', sprintf('%d-%d-%d', $year, $month, $day));
    $today   = new DateTimeImmutable('today');

    if (!$dobDate || $dobDate > $today) {
        return null;
    }

    $age = $dobDate->diff($today)->y;
    if ($age < 15 || $age > 80) {
        return null;
    }

    return [
        'sql_date' => $dobDate->format('Y-m-d'),
        'age'      => $age,
    ];
}

function valid_postcode_for_state(string $state, string $postcode): bool
{
    $n = (int) $postcode;
    return match ($state) {
        'VIC' => ($n >= 3000 && $n <= 3999) || ($n >= 8000 && $n <= 8999),
        'NSW' => ($n >= 1000 && $n <= 2599) || ($n >= 2619 && $n <= 2899) || ($n >= 2921 && $n <= 2999) || ($n >= 6000 && $n <= 7999),
        'QLD' => ($n >= 4000 && $n <= 4999) || ($n >= 9000 && $n <= 9999),
        'NT'  => ($n >= 800 && $n <= 999),
        'WA'  => ($n >= 6000 && $n <= 6797) || ($n >= 6800 && $n <= 6999),
        'SA'  => ($n >= 5000 && $n <= 5799) || ($n >= 5800 && $n <= 5999),
        'TAS' => ($n >= 7000 && $n <= 7799) || ($n >= 7800 && $n <= 7999),
        'ACT' => ($n >= 200 && $n <= 299) || ($n >= 2600 && $n <= 2618) || ($n >= 2900 && $n <= 2920),
        default => false,
    };
}

function job_ref_exists_in_jobs_table(mysqli $conn, string $job_ref, array &$errors): bool
{
    $stmt = mysqli_prepare($conn, 'SELECT 1 FROM jobs WHERE job_ref = ? LIMIT 1');
    if (!$stmt) {
        $errors[] = 'Could not verify job reference number (ensure jobs table is imported): ' . htmlspecialchars(mysqli_error($conn));
        return false;
    }

    mysqli_stmt_bind_param($stmt, 's', $job_ref);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $exists = mysqli_stmt_num_rows($stmt) > 0;
    mysqli_stmt_close($stmt);

    if (!$exists) {
        $safe_ref = htmlspecialchars($job_ref, ENT_QUOTES, 'UTF-8');
        $errors[] = "Job reference number '$safe_ref' does not exist in jobs.";
    }
    return $exists;
}

$job_ref_raw        = clean_raw($_POST['job_ref'] ?? '');
$first_name_raw     = clean_raw($_POST['first_name'] ?? '');
$last_name_raw      = clean_raw($_POST['last_name'] ?? '');
$dob_raw            = clean_raw($_POST['dob'] ?? '');
$gender_raw         = clean_raw($_POST['gender'] ?? '');
$street_address_raw = clean_raw($_POST['street_address'] ?? '');
$suburb_raw         = clean_raw($_POST['suburb'] ?? '');
$state_raw          = clean_raw($_POST['state'] ?? '');
$postcode_raw       = clean_raw($_POST['postcode'] ?? '');
$email_raw          = clean_raw($_POST['email'] ?? '');
$phone_raw          = clean_raw($_POST['phone'] ?? '');
$skills_raw         = $_POST['skills'] ?? [];
$other_skills_raw   = clean_raw($_POST['other_skills'] ?? '');

$errors = [];

if ($job_ref_raw === '') {
    $errors[] = 'Job reference number is required.';
} elseif (!preg_match('/^[A-Za-z0-9]{5}$/', $job_ref_raw)) {
    $errors[] = 'Job reference number must be 5 alphanumeric characters.';
} else {
    job_ref_exists_in_jobs_table($conn, $job_ref_raw, $errors);
}

if ($first_name_raw === '') {
    $errors[] = 'First name is required.';
} elseif (!preg_match('/^[A-Za-z]{1,20}$/', $first_name_raw)) {
    $errors[] = 'First name must contain letters only, maximum 20 characters.';
}

if ($last_name_raw === '') {
    $errors[] = 'Last name is required.';
} elseif (!preg_match('/^[A-Za-z]{1,20}$/', $last_name_raw)) {
    $errors[] = 'Last name must contain letters only, maximum 20 characters.';
}

$dob_sql = null;
if ($dob_raw === '') {
    $errors[] = 'Date of birth is required.';
} else {
    $dob_parsed = parse_au_dob_and_age($dob_raw);
    if ($dob_parsed === null) {
        $errors[] = 'Date of birth must be a valid dd/mm/yyyy date and age must be between 15 and 80.';
    } else {
        $dob_sql = $dob_parsed['sql_date'];
    }
}

$allowed_genders = ['female', 'male', 'other', 'not_say'];
if (!in_array($gender_raw, $allowed_genders, true)) {
    $errors[] = 'Please select a valid gender option (female, male, other, or not_say).';
}

if ($street_address_raw === '') {
    $errors[] = 'Street address is required.';
} elseif (mb_strlen($street_address_raw) > 40) {
    $errors[] = 'Street address must be at most 40 characters.';
}

if ($suburb_raw === '') {
    $errors[] = 'Suburb / town is required.';
} elseif (mb_strlen($suburb_raw) > 40) {
    $errors[] = 'Suburb / town must be at most 40 characters.';
}

$allowed_states = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
if (!in_array($state_raw, $allowed_states, true)) {
    $errors[] = 'Please select a valid Australian state or territory.';
}

if (!preg_match('/^\d{4}$/', $postcode_raw)) {
    $errors[] = 'Postcode must be 4 digits.';
} elseif (in_array($state_raw, $allowed_states, true) && !valid_postcode_for_state($state_raw, $postcode_raw)) {
    $safe_postcode = htmlspecialchars($postcode_raw, ENT_QUOTES, 'UTF-8');
    $safe_state    = htmlspecialchars($state_raw, ENT_QUOTES, 'UTF-8');
    $errors[] = "Postcode $safe_postcode does not match state $safe_state.";
}

if ($email_raw === '') {
    $errors[] = 'Email address is required.';
} elseif (!filter_var($email_raw, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if ($phone_raw === '') {
    $errors[] = 'Phone number is required.';
} elseif (!preg_match('/^\d{8,12}$/', $phone_raw)) {
    $errors[] = 'Phone number must be 8 to 12 digits with no spaces.';
}

$allowed_skills = ['html_css', 'javascript', 'ui_ux', 'python'];
$selected_skills = [];
if (is_array($skills_raw)) {
    foreach ($skills_raw as $sk) {
        if (is_string($sk) && in_array($sk, $allowed_skills, true)) {
            $selected_skills[] = $sk;
        }
    }
}
if (!in_array('html_css', $selected_skills, true)) {
    $errors[] = 'HTML5 and CSS3 must be selected.';
}

if ($other_skills_raw !== '' && mb_strlen($other_skills_raw) > 500) {
    $errors[] = 'Other skills must be 500 characters or fewer.';
}

$job_ref        = escape_html($job_ref_raw);
$first_name     = escape_html($first_name_raw);
$last_name      = escape_html($last_name_raw);
$gender         = escape_html($gender_raw);
$street_address = escape_html($street_address_raw);
$suburb         = escape_html($suburb_raw);
$state          = escape_html($state_raw);
$postcode       = escape_html($postcode_raw);
$email          = escape_html($email_raw);
$phone          = escape_html($phone_raw);
$other_skills   = escape_html($other_skills_raw);

$skill_html_css   = in_array('html_css', $selected_skills, true) ? 1 : 0;
$skill_javascript = in_array('javascript', $selected_skills, true) ? 1 : 0;
$skill_ui_ux      = in_array('ui_ux', $selected_skills, true) ? 1 : 0;
$skill_python     = in_array('python', $selected_skills, true) ? 1 : 0;

$eoi_number = null;

if (empty($errors)) {
    $create_sql = "CREATE TABLE IF NOT EXISTS eoi (
        EOInumber INT UNSIGNED NOT NULL AUTO_INCREMENT,
        job_ref CHAR(5) NOT NULL,
        first_name VARCHAR(20) NOT NULL,
        last_name VARCHAR(20) NOT NULL,
        dob DATE NOT NULL,
        gender ENUM('female','male','other','not_say') NOT NULL,
        street_address VARCHAR(255) NOT NULL,
        suburb VARCHAR(255) NOT NULL,
        state ENUM('VIC','NSW','QLD','NT','WA','SA','TAS','ACT') NOT NULL,
        postcode CHAR(4) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(12) NOT NULL,
        skill_html_css TINYINT(1) NOT NULL DEFAULT 0,
        skill_javascript TINYINT(1) NOT NULL DEFAULT 0,
        skill_ui_ux TINYINT(1) NOT NULL DEFAULT 0,
        skill_python TINYINT(1) NOT NULL DEFAULT 0,
        other_skills TEXT NULL,
        status ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (EOInumber),
        KEY idx_eoi_job_ref (job_ref),
        KEY idx_eoi_name (last_name, first_name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

if (!mysqli_query($conn, $create_sql)) {
        error_log('EOI table creation failed: ' . mysqli_error($conn));
        $errors[] = 'We could not process your application right now due to a system error. Please try again later.';
    } else {
        $insert_sql = "INSERT INTO eoi (
            job_ref, first_name, last_name, dob, gender,
            street_address, suburb, state, postcode,
            email, phone, skill_html_css, skill_javascript,
            skill_ui_ux, skill_python, other_skills, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')";

        $stmt = mysqli_prepare($conn, $insert_sql);
        if (!$stmt) {
            error_log('EOI prepare failed: ' . mysqli_error($conn));
            $errors[] = 'We could not process your application right now due to a system error. Please try again later.';
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                'sssssssssssiiiis',
                $job_ref,
                $first_name,
                $last_name,
                $dob_sql,
                $gender,
                $street_address,
                $suburb,
                $state,
                $postcode,
                $email,
                $phone,
                $skill_html_css,
                $skill_javascript,
                $skill_ui_ux,
                $skill_python,
                $other_skills
            );

            if (mysqli_stmt_execute($stmt)) {
                $eoi_number = mysqli_insert_id($conn);
            } else {
                error_log('EOI insert failed: ' . mysqli_stmt_error($stmt));
                $errors[] = 'We could not save your application right now due to a system error. Please try again later.';
            }
            mysqli_stmt_close($stmt);
        }
    }
}
$page_title = empty($errors) ? 'Application Submitted' : 'Application Error';
$page_description = 'Expression of Interest submission result for SwinEduTech.';
$page_keywords = 'SwinEduTech, EOI, application status';
$page_author = 'Nguyen Truong Minh Thuy';
$body_id = 'process-eoi-page';
include 'header.inc';
?>

  <main id="main-content">
    <?php if (!empty($errors)): ?>
      <h1>Please check your application</h1>
      <p>Your application could not be submitted due to the following issues:</p>
      <ul>
        <?php foreach ($errors as $err): ?>
          <li><?php echo $err; ?></li>
        <?php endforeach; ?>
      </ul>
      <p><a class="btn" href="apply.php">Back to application form</a></p>
    <?php else: ?>
      <h1>Application received</h1>
      <p>Thank you, <strong><?php echo escape_html($first_name, false) . ' ' . escape_html($last_name, false); ?></strong>. Your EOInumber is <strong>#<?php echo (int) $eoi_number; ?></strong> (Status: <strong>New</strong>).</p>
      <p><a class="btn" href="apply.php">Submit another application</a></p>
    <?php endif; ?>
  </main>

<?php include 'footer.inc'; ?>