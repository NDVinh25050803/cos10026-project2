<?php
// Job descriptions page. Author: Nguyen Dang Vinh. References: see Sources section
$page_title = 'Job Descriptions';
$page_description = 'Front-end developer, full-stack developer and UI/UX designer jobs at SwinEduTech, an EdTech company building accessible online learning tools.';
$page_keywords = 'SwinEduTech, jobs, front-end developer, full-stack developer, UI/UX designer, EdTech, accessibility, Melbourne';
$page_author = 'Nguyen Dang Vinh';
$body_id = 'jobs-page';
require_once 'job_render.inc';
$page_style = $job_card_style;
require_once 'db_connect.php';

$jobs = attach_job_lists($conn, mysqli_fetch_all(mysqli_query($conn, 'SELECT * FROM jobs ORDER BY job_ref'), MYSQLI_ASSOC));

$perks = array_column(mysqli_fetch_all(mysqli_query($conn, 'SELECT description FROM perks ORDER BY sort_order'), MYSQLI_ASSOC), 'description');

// Show the closing line only when every job shares one date.
$dates = array_unique(array_column($jobs, 'closing_date'));
$close = count($dates) === 1 ? reset($dates) : null;
$open = count(array_filter($jobs, fn($j) => !job_is_closed($j['closing_date'])));

include 'header.inc';
?>

  <main id="main-content">
    <h1>Open positions</h1>
    <p class="lead">SwinEduTech builds digital learning tools and inclusive online education platforms for schools and TAFEs across Australia. We have <?= $open ?> <?= $open === 1 ? 'role' : 'roles' ?> open in our engineering and design teams. Each one helps our platform work for every learner, including people who use screen readers, keyboards or slow internet.</p>

<?php render_search_form($conn); ?>

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
<?php if (job_is_closed($close)): ?>
      <p style="font-weight: 700;">Applications closed.</p>
<?php else: ?>
      <p style="font-weight: 700;">All roles close on <?= long_date($close) ?>.</p>
<?php endif; ?>
<?php endif; ?>
      <h3>Need an adjustment</h3>
      <p>We can give you this ad in another format or change how the interview runs. Email <a href="mailto:SWD00450@swin.edu.au">SWD00450@swin.edu.au</a>.</p>
    </aside>

<?php if (!$jobs): ?>
    <p>There are no open roles right now. Please check back soon.</p>
<?php endif; ?>
<?php foreach ($jobs as $i => $job) render_job($job, $i + 1); ?>

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
