<?php
/* Main menu, shared by every page.
 * The link for the page you are on gets aria-current="page". */
$current_page = basename($_SERVER['PHP_SELF']);
$nav_links = [
  'index.php' => 'Home',
  'jobs.php'  => 'Job Descriptions',
  'apply.php' => 'Apply Now',
  'about.php' => 'About Us',
];
?>
      <nav class="main-nav" aria-label="Main">
        <ul>
<?php foreach ($nav_links as $file => $label): ?>
          <li><a href="<?= $file ?>"<?= $file === $current_page ? ' aria-current="page"' : '' ?>><?= $label ?></a></li>
<?php endforeach; ?>
        </ul>
      </nav>
