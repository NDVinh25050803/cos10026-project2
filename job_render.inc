<?php
/* Job card HTML, shared by jobs.php and search.php. Author: Nguyen Dang Vinh.
 * Usage: $jobs = attach_job_lists($conn, $jobs); then render_job($job, $n) for each. */

// Style for the job reference stamp. Pages showing job cards use it as $page_style.
$job_card_style = <<<'CSS'
    .job-meta .ref-no {
      display: inline-block;
      margin-top: 2px;
      padding: 0 0.55rem;
      border: 2px solid var(--red);
      border-radius: 4px;
      color: var(--red);
      font-family: var(--font-mono);
      letter-spacing: 1px;
      transform: rotate(-2deg);
    }
    .job .closed {
      color: var(--red);
      font-weight: 700;
    }
    .job .btn + .hint {
      margin-top: var(--space-md);
    }
CSS;

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// <time> tag showing a DATE column as e.g. "31 December 2026".
function long_date($date) {
    return '<time datetime="' . h($date) . '">' . date('j F Y', strtotime($date)) . '</time>';
}

// Open until the end of the closing day, Melbourne time.
function job_is_closed($closing_date) {
    return $closing_date < (new DateTime('now', new DateTimeZone('Australia/Melbourne')))->format('Y-m-d');
}

// Adds 'resps' and 'reqs' lists to each job row from the child tables.
function attach_job_lists($conn, $jobs) {
    $resps = [];
    foreach (mysqli_query($conn, 'SELECT job_ref, description FROM job_responsibilities ORDER BY job_ref, sort_order') as $r) {
        $resps[$r['job_ref']][] = $r['description'];
    }
    $reqs = [];
    foreach (mysqli_query($conn, 'SELECT job_ref, type, description FROM job_requirements ORDER BY job_ref, type, sort_order') as $r) {
        $reqs[$r['job_ref']][$r['type']][] = $r['description'];
    }
    foreach ($jobs as &$job) {
        $job['resps'] = $resps[$job['job_ref']] ?? [];
        $job['reqs'] = $reqs[$job['job_ref']] ?? [];
    }
    return $jobs;
}

// $n numbers the heading id (job1-title, job2-title...), so it must be unique on the page.
function render_job($job, $n) {
    $meta = ['team' => 'Team', 'location' => 'Location', 'work_type' => 'Work type',
             'salary' => 'Salary', 'reports_to' => 'Reports to'];
    $ref = h($job['job_ref']);
    $title_id = 'job' . $n . '-title';
?>
    <section class="job" id="<?= strtolower($ref) ?>" aria-labelledby="<?= $title_id ?>">
      <h2 id="<?= $title_id ?>"><?= h($job['title']) ?></h2>
      <dl class="job-meta">
        <div>
          <dt>Reference</dt>
          <dd class="ref-no"><?= $ref ?></dd>
        </div>
<?php foreach ($meta as $col => $label): ?>
        <div>
          <dt><?= $label ?></dt>
          <dd><?= h($job[$col]) ?></dd>
        </div>
<?php endforeach; ?>
      </dl>
      <p><?= h($job['summary']) ?></p>

      <section>
        <h3>Key responsibilities</h3>
        <ol>
<?php foreach ($job['resps'] as $item): ?>
          <li><?= h($item) ?></li>
<?php endforeach; ?>
        </ol>
      </section>

      <section>
        <h3>Requirements</h3>
        <div class="req-cols">
<?php foreach (['essential' => 'Essential', 'preferable' => 'Preferable'] as $type => $label): ?>
          <div>
            <h4><?= $label ?></h4>
            <ul>
<?php foreach ($job['reqs'][$type] ?? [] as $item): ?>
              <li><?= h($item) ?></li>
<?php endforeach; ?>
            </ul>
          </div>
<?php endforeach; ?>
        </div>
      </section>

<?php if (job_is_closed($job['closing_date'])): ?>
      <p class="closed">Applications closed on <?= long_date($job['closing_date']) ?>.</p>
<?php else: ?>
      <a class="btn" href="apply.php?ref=<?= urlencode($job['job_ref']) ?>">Apply now (reference <?= $ref ?>)</a>
      <p class="hint">Applications close on <?= long_date($job['closing_date']) ?>.</p>
<?php endif; ?>
    </section>
<?php
}

// Columns the search form can filter on, with their labels.
const JOB_FILTERS = ['location' => 'Location', 'work_type' => 'Work type', 'team' => 'Team'];

// Search box for search.php. $values holds the current keyword and filter choices.
// No datalist: with one, NVDA in Edge only says "Suggestion available" on focus.
function render_search_form($conn, $values = []) {
?>
    <!-- Search box -->
    <section class="search-container" aria-labelledby="search-heading">
      <h2 id="search-heading">Search jobs</h2>
      <form action="search.php" method="get" role="search">
        <label for="keyword">Keyword</label>
        <p class="hint" id="search-hint">Type a job title, team or place. Pick filters to narrow the list.</p>
        <div class="search-row">
          <input type="search" id="keyword" name="keyword" value="<?= h($values['keyword'] ?? '') ?>" placeholder="e.g. designer" aria-describedby="search-hint">
        </div>
        <div class="search-filters">
<?php foreach (JOB_FILTERS as $col => $label):
    // $col comes from JOB_FILTERS, never from the request.
    $options = mysqli_fetch_all(mysqli_query($conn, "SELECT DISTINCT $col FROM jobs ORDER BY $col"), MYSQLI_NUM); ?>
          <div>
            <label for="filter-<?= $col ?>"><?= $label ?></label>
            <select id="filter-<?= $col ?>" name="<?= $col ?>">
              <option value="">Any</option>
<?php foreach ($options as [$opt]): ?>
              <option value="<?= h($opt) ?>"<?= ($values[$col] ?? '') === $opt ? ' selected' : '' ?>><?= h($opt) ?></option>
<?php endforeach; ?>
            </select>
          </div>
<?php endforeach; ?>
          <button type="submit" class="btn">Search</button>
        </div>
      </form>
    </section>
<?php
}
