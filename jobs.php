<?php
// Job descriptions page. Author: Nguyen Dang Vinh. References: see Sources section
$page_title = 'Job Descriptions';
$page_description = 'Front-end developer, full-stack developer and UI/UX designer jobs at SwinEduTech, an EdTech company building accessible online learning tools.';
$page_keywords = 'SwinEduTech, jobs, front-end developer, full-stack developer, UI/UX designer, EdTech, accessibility, Melbourne';
$page_author = 'Nguyen Dang Vinh';
$body_id = 'jobs-page';
$page_style = <<<'CSS'
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
CSS;
require_once 'db_connect.php';

function h($s) { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

// Column => label for the job-meta list (Reference is printed separately).
$meta = ['team' => 'Team', 'location' => 'Location', 'work_type' => 'Work type',
         'salary' => 'Salary', 'reports_to' => 'Reports to'];

$jobs = mysqli_fetch_all(mysqli_query($conn, 'SELECT * FROM jobs ORDER BY job_ref'), MYSQLI_ASSOC);

$resps = [];
foreach (mysqli_query($conn, 'SELECT job_ref, description FROM job_responsibilities ORDER BY job_ref, sort_order') as $r) {
    $resps[$r['job_ref']][] = $r['description'];
}

$reqs = [];
foreach (mysqli_query($conn, 'SELECT job_ref, type, description FROM job_requirements ORDER BY job_ref, type, sort_order') as $r) {
    $reqs[$r['job_ref']][$r['type']][] = $r['description'];
}

$perks = array_column(mysqli_fetch_all(mysqli_query($conn, 'SELECT description FROM perks ORDER BY sort_order'), MYSQLI_ASSOC), 'description');

// Show "All roles close on" only when every job shares one date.
$dates = array_unique(array_column($jobs, 'closing_date'));
$close = count($dates) === 1 ? reset($dates) : null;

include 'header.inc';
?>

  <main id="main-content">
    <h1>Open positions</h1>
    <p class="lead">SwinEduTech builds digital learning tools and inclusive online education platforms for schools and TAFEs across Australia. We have <?= count($jobs) ?> roles open in our engineering and design teams. Each one helps our platform work for every learner, including people who use screen readers, keyboards or slow internet.</p>

    <!-- Aside -->
    <aside aria-labelledby="perks-heading">
      <h2 id="perks-heading">Working at SwinEduTech</h2>
      <ul>
<?php foreach ($perks as $perk): ?>
        <li><?= h($perk) ?></li>
<?php endforeach; ?>
      </ul>
<?php if ($close): ?>
      <!-- Inline CSS -->
      <p style="font-weight: 700;">All roles close on <time datetime="<?= h($close) ?>"><?= date('j F Y', strtotime($close)) ?></time>.</p>
<?php endif; ?>
      <h3>Need an adjustment</h3>
      <p>We can give you this ad in another format or change how the interview runs. Email <a href="mailto:SWD00450@swin.edu.au">SWD00450@swin.edu.au</a>.</p>
    </aside>

<?php foreach ($jobs as $i => $job):
      $ref = h($job['job_ref']);
      $title_id = 'job' . ($i + 1) . '-title'; ?>
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
<?php foreach ($resps[$job['job_ref']] ?? [] as $item): ?>
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
<?php foreach ($reqs[$job['job_ref']][$type] ?? [] as $item): ?>
              <li><?= h($item) ?></li>
<?php endforeach; ?>
            </ul>
          </div>
<?php endforeach; ?>
        </div>
      </section>

      <a class="btn" href="apply.php">Apply now (reference <?= $ref ?>)</a>
    </section>
<?php endforeach; ?>

    <!-- Inclusive employment -->
    <section class="inclusion" aria-labelledby="inclusion-heading">
      <h2 id="inclusion-heading">Inclusive employment</h2>
      <p>We strongly encourage Aboriginal and Torres Strait Islander peoples to apply for these roles. If you would like a culturally safe contact person during the hiring process, say so in your application and we will arrange one.</p>
      <p>We include this statement because First Nations students are still under-served by many online learning tools. People with lived experience help us build products that respect their cultures and work for their communities.</p>
    </section>

    <!-- Sources -->
    <section class="sources" aria-labelledby="sources-heading">
      <h2 id="sources-heading">Sources</h2>
      <p>Salary ranges are based on 2026 Melbourne market data. References use APA 7.</p>
      <ul>
        <li>Robert Half. (2026). <cite>Front-end developer in Melbourne</cite>. <a href="https://www.roberthalf.com/au/en/job-details/front-end-developer/melbourne">https://www.roberthalf.com/au/en/job-details/front-end-developer/melbourne</a></li>
        <li>Robert Half. (2026). <cite>Full stack JavaScript developer in Melbourne</cite>. <a href="https://www.roberthalf.com/au/en/job-details/full-stack-javascript-developer/melbourne">https://www.roberthalf.com/au/en/job-details/full-stack-javascript-developer/melbourne</a></li>
        <li>Robert Half. (2026). <cite>UX designer in Melbourne</cite>. <a href="https://www.roberthalf.com/au/en/job-details/ux-designer/melbourne">https://www.roberthalf.com/au/en/job-details/ux-designer/melbourne</a></li>
        <li>World Wide Web Consortium. (2023). <cite>Web content accessibility guidelines (WCAG) 2.2</cite>. <a href="https://www.w3.org/TR/WCAG22/">https://www.w3.org/TR/WCAG22/</a></li>
        <li>Braille Institute. (2025). <cite>Atkinson Hyperlegible Next and Atkinson Hyperlegible Mono</cite> [Typefaces]. Google Fonts. <a href="https://fonts.google.com/specimen/Atkinson+Hyperlegible+Next">https://fonts.google.com/specimen/Atkinson+Hyperlegible+Next</a></li>
      </ul>
    </section>
  </main>

  <!-- Footer section -->

<?php include 'footer.inc'; ?>
