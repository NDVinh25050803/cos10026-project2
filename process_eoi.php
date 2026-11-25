<?php
// Process job application (EOI) submissions. Author: Nguyen Truong Minh Thuy.
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST)) {
    header('Location: apply.php');
    exit;
}

require_once 'db_connect.php';

function clean_input(string $value): string
{
    return htmlspecialchars(stripslashes(trim($value)), ENT_QUOTES, 'UTF-8');
}

function parse_au_date(string $dob_raw): ?string
{
    if (!preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $dob_raw, $m)) {
        return null;
    }
    $day = (int) $m[1];
    $month = (int) $m[2];
    $year = (int) $m[3];

    if ($year < 1900 || $year > 2010 || !checkdate($month, $day, $year)) {
        return null;
    }
    return sprintf('%04d-%02d-%02d', $year, $month, $day);
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

$job_ref        = clean_input($_POST['job_ref'] ?? '');
$first_name     = clean_input($_POST['first_name'] ?? '');
$last_name      = clean_input($_POST['last_name'] ?? '');
$dob_raw        = trim($_POST['dob'] ?? '');
$gender_raw     = trim($_POST['gender'] ?? '');
$street_address = clean_input($_POST['street_address'] ?? '');
$suburb         = clean_input($_POST['suburb'] ?? '');
$state          = clean_input($_POST['state'] ?? '');
$postcode       = clean_input($_POST['postcode'] ?? '');
$email_raw      = trim($_POST['email'] ?? '');
$phone          = clean_input($_POST['phone'] ?? '');
$skills_raw     = $_POST['skills'] ?? [];
$other_skills   = clean_input($_POST['other_skills'] ?? '');

$errors = [];

if ($job_ref === '') {
    $errors[] = 'Job reference number is required.';
} elseif (!preg_match('/^[A-Za-z0-9]{5}$/', $job_ref)) {
    $errors[] = 'Job reference number must be exactly 5 letters or numbers.';
}

if ($first_name === '') {
    $errors[] = 'First name is required.';
} elseif (!preg_match('/^[A-Za-z]{1,20}$/', $first_name)) {
    $errors[] = 'First name must contain letters only (up to 20 characters).';
}

if ($last_name === '') {
    $errors[] = 'Last name is required.';
} elseif (!preg_match('/^[A-Za-z]{1,20}$/', $last_name)) {
    $errors[] = 'Last name must contain letters only (up to 20 characters).';
}

$dob_sql = null;
if ($dob_raw === '') {
    $errors[] = 'Date of birth is required.';
} else {
    $dob_sql = parse_au_date($dob_raw);
    if ($dob_sql === null) {
        $errors[] = 'Date of birth must be a valid dd/mm/yyyy date between 1900 and 2010.';
    }
}

$allowed_genders = ['female', 'male', 'other', 'not_say'];
$gender = in_array($gender_raw, $allowed_genders, true) ? $gender_raw : '';
if ($gender === '') {
    $errors[] = 'Please select a valid gender option.';
}

if ($street_address === '') {
    $errors[] = 'Street address is required.';
} elseif (mb_strlen($street_address) > 40) {
    $errors[] = 'Street address must be 40 characters or fewer.';
}

if ($suburb === '') {
    $errors[] = 'Suburb / town is required.';
} elseif (mb_strlen($suburb) > 40) {
    $errors[] = 'Suburb / town must be 40 characters or fewer.';
}

$allowed_states = ['VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT'];
if (!in_array($state, $allowed_states, true)) {
    $errors[] = 'Please select a valid Australian state or territory.';
}

if (!preg_match('/^\d{4}$/', $postcode)) {
    $errors[] = 'Postcode must be exactly 4 digits.';
} elseif (in_array($state, $allowed_states, true) && !valid_postcode_for_state($state, $postcode)) {
    $errors[] = "Postcode $postcode does not match state $state.";
}

$email = clean_input($email_raw);
if ($email_raw === '') {
    $errors[] = 'Email address is required.';
} elseif (!filter_var($email_raw, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email address.';
}

if ($phone === '') {
    $errors[] = 'Phone number is required.';
} elseif (!preg_match('/^\d{8,12}$/', $phone)) {
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

$skill1 = in_array('html_css', $selected_skills, true) ? 'HTML5 and CSS3' : '';
$skill2 = in_array('javascript', $selected_skills, true) ? 'JavaScript' : '';
$skill3 = in_array('ui_ux', $selected_skills, true) ? 'UI/UX design' : '';
$skill4 = in_array('python', $selected_skills, true) ? 'Python' : '';

$eoi_number = null;

if (empty($errors)) {
    $create_sql = "CREATE TABLE IF NOT EXISTS eoi (
        EOInumber INT AUTO_INCREMENT PRIMARY KEY,
        job_ref VARCHAR(10) NOT NULL,
        first_name VARCHAR(20) NOT NULL,
        last_name VARCHAR(20) NOT NULL,
        dob DATE NOT NULL,
        gender VARCHAR(20) NOT NULL,
        street_address VARCHAR(40) NOT NULL,
        suburb VARCHAR(40) NOT NULL,
        state VARCHAR(10) NOT NULL,
        postcode CHAR(4) NOT NULL,
        email VARCHAR(100) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        skill1 VARCHAR(30) DEFAULT '',
        skill2 VARCHAR(30) DEFAULT '',
        skill3 VARCHAR(30) DEFAULT '',
        skill4 VARCHAR(30) DEFAULT '',
        other_skills TEXT,
        status ENUM('New', 'Current', 'Final') NOT NULL DEFAULT 'New',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    if (!mysqli_query($conn, $create_sql)) {
        $errors[] = 'Could not prepare EOI table: ' . htmlspecialchars(mysqli_error($conn));
    } else {
        $insert_sql = "INSERT INTO eoi (
            job_ref, first_name, last_name, dob, gender,
            street_address, suburb, state, postcode,
            email, phone, skill1, skill2, skill3, skill4, other_skills, status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'New')";

        $stmt = mysqli_prepare($conn, $insert_sql);
        if (!$stmt) {
            $errors[] = 'Database statement error: ' . htmlspecialchars(mysqli_error($conn));
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                'ssssssssssssssss',
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
                $skill1,
                $skill2,
                $skill3,
                $skill4,
                $other_skills
            );

            if (mysqli_stmt_execute($stmt)) {
                $eoi_number = mysqli_insert_id($conn);
            } else {
                $errors[] = 'Failed to save application: ' . htmlspecialchars(mysqli_stmt_error($stmt));
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
      <p>Thank you, <strong><?php echo $first_name . ' ' . $last_name; ?></strong>. Your Expression of Interest has been recorded.</p>
      <p>Your EOI reference number is: <strong>#<?php echo (int) $eoi_number; ?></strong> (Status: <strong>New</strong>).</p>
      <p><a class="btn" href="apply.php">Submit another application</a></p>
    <?php endif; ?>
  </main>

<?php include 'footer.inc'; ?>