<?php
// Job search results. Author: Nguyen Dang Vinh.
$page_title = 'Job Search';
$page_description = 'Search SwinEduTech jobs by title, reference, location or team.';
$page_keywords = 'SwinEduTech, job search, jobs, EdTech, Melbourne';
$page_author = 'Nguyen Dang Vinh';
$body_id = 'search-page';
require_once 'job_render.inc';
$page_style = $job_card_style;
require_once 'db_connect.php';

// Ignore ?keyword[]=... arrays; cap the length.
$keyword = $_GET['keyword'] ?? '';
$keyword = is_string($keyword) ? mb_substr(trim($keyword), 0, 100) : '';

// Escape % and _ so they are matched as plain text, not LIKE wildcards.
$like = '%' . addcslashes($keyword, '%_\\') . '%';
$stmt = mysqli_prepare($conn,
    'SELECT * FROM jobs
     WHERE title LIKE ? OR job_ref LIKE ? OR summary LIKE ? OR location LIKE ? OR team LIKE ?
     ORDER BY job_ref');
mysqli_stmt_bind_param($stmt, 'sssss', $like, $like, $like, $like, $like);
mysqli_stmt_execute($stmt);
$jobs = attach_job_lists($conn, mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC));

// Every title, for the search box suggestions.
$titles = mysqli_fetch_all(mysqli_query($conn, 'SELECT title FROM jobs ORDER BY job_ref'), MYSQLI_ASSOC);

include 'header.inc';
?>

  <main id="main-content">
    <h1>Search results</h1>

<?php render_search_form($titles, $keyword); ?>

<?php if ($keyword === ''): ?>
    <p>Showing all <?= count($jobs) ?> jobs.</p>
<?php elseif ($jobs): ?>
    <p><?= count($jobs) ?> <?= count($jobs) === 1 ? 'job matches' : 'jobs match' ?> "<?= h($keyword) ?>".</p>
<?php else: ?>
    <p>No jobs match "<?= h($keyword) ?>". Try another word or <a href="jobs.php">see all jobs</a>.</p>
<?php endif; ?>

<?php foreach ($jobs as $i => $job) render_job($job, $i + 1); ?>
  </main>

<?php include 'footer.inc'; ?>
