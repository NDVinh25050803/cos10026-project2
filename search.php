<?php
// Job search results. Author: Nguyen Dang Vinh.
$page_title = 'Job Search';
$page_description = 'Search SwinEduTech jobs by keyword, location, work type or team.';
$page_keywords = 'SwinEduTech, job search, jobs, EdTech, Melbourne';
$page_author = 'Nguyen Dang Vinh';
$body_id = 'search-page';
require_once 'job_render.inc';
$page_style = $job_card_style;
require_once 'db_connect.php';

// A GET value as trimmed text. Arrays (?x[]=) and missing values become ''.
function get_param($name) {
    $v = $_GET[$name] ?? '';
    return is_string($v) ? mb_substr(trim($v), 0, 100) : '';
}

$values = ['keyword' => get_param('keyword')];
foreach (JOB_FILTERS as $col => $label) {
    $values[$col] = get_param($col);
}

// Escape % and _ so they are matched as plain text, not LIKE wildcards.
$like = '%' . addcslashes($values['keyword'], '%_\\') . '%';
$sql = 'SELECT * FROM jobs
        WHERE (title LIKE ? OR job_ref LIKE ? OR summary LIKE ? OR location LIKE ? OR team LIKE ?)';
$params = [$like, $like, $like, $like, $like];
$criteria = $values['keyword'] === '' ? [] : ['"' . $values['keyword'] . '"'];
foreach (JOB_FILTERS as $col => $label) {
    if ($values[$col] !== '') {
        $sql .= " AND $col = ?";   // $col comes from JOB_FILTERS
        $params[] = $values[$col];
        $criteria[] = "$label: " . $values[$col];
    }
}
$stmt = mysqli_prepare($conn, $sql . ' ORDER BY job_ref');
mysqli_stmt_bind_param($stmt, str_repeat('s', count($params)), ...$params);
mysqli_stmt_execute($stmt);
$jobs = attach_job_lists($conn, mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC));
$criteria = h(implode('; ', $criteria));

include 'header.inc';
?>

  <main id="main-content">
    <h1>Search results</h1>

<?php render_search_form($conn, $values); ?>

<?php if ($criteria === ''): ?>
    <p>Showing all <?= count($jobs) ?> jobs.</p>
<?php elseif ($jobs): ?>
    <p><?= count($jobs) ?> <?= count($jobs) === 1 ? 'job matches' : 'jobs match' ?> <?= $criteria ?>.</p>
<?php else: ?>
    <p>No jobs match <?= $criteria ?>. Try another word, fewer filters or <a href="jobs.php">see all jobs</a>.</p>
<?php endif; ?>

<?php foreach ($jobs as $i => $job) render_job($job, $i + 1); ?>
  </main>

<?php include 'footer.inc'; ?>
