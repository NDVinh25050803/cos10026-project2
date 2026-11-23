<?php
// Home page. References: MDN Web Docs, W3C WAI
$page_title = 'Home';
$page_description = 'SwinEduTech builds digital learning tools and inclusive online education platforms. Search our open web developer and designer roles.';
$page_keywords = 'SwinEduTech, EdTech, careers, web developer, designer, accessible learning';
$page_author = 'Nguyen Truong Minh Thuy';
$body_id = 'index-page';
$page_style = <<<'CSS'
    .slogan {
      margin: var(--space-md) 0 0;
      font-size: var(--text-lg);
      font-weight: 700;
      color: var(--ink-soft);
    }
CSS;
include 'header.inc';
?>

  <main id="main-content">
    <h1>Welcome to SwinEduTech</h1>
    <p class="slogan">Building digital knowledge, leading the way to the future.</p>

    <!-- Company introduction -->
    <section class="welcome-hero" aria-labelledby="about-heading">
      <div>
        <h2 id="about-heading">Digital education solutions and careers</h2>
        <p>We are a technology company that builds digital learning tools and inclusive online education platforms for schools and TAFEs across Australia. We are looking for web developers and designers to help every learner, including people who use screen readers, keyboards or slow internet.</p>
        <a class="btn" href="jobs.php">See open positions</a>
      </div>
      <img src="images/hero.svg" alt="A laptop showing a SwinEduTech video lesson with captions, next to a keyboard Tab key, a text size control and an accessibility symbol" width="960" height="600">
    </section>

    <!-- Search box -->
    <section class="search-container" aria-labelledby="search-heading">
      <h2 id="search-heading">Quick job search</h2>
      <form action="jobs.php" method="get" role="search">
        <label for="keyword">Keyword</label>
        <p class="hint" id="search-hint">Type a job title, then press Search to see all job descriptions.</p>
        <div class="search-row">
          <input type="search" id="keyword" name="keyword" placeholder="e.g. designer" list="search-suggestions" aria-describedby="search-hint">
          <button type="submit" class="btn">Search</button>
        </div>
        <datalist id="search-suggestions">
          <option value="Front-End Developer"></option>
          <option value="Full-Stack Developer"></option>
          <option value="UI/UX Designer"></option>
        </datalist>
      </form>
    </section>

    <!-- Open positions table (rowspan, colspan) -->
    <section aria-labelledby="roles-heading">
      <h2 id="roles-heading">Current open positions</h2>
      <div class="table-wrap" tabindex="0" role="region" aria-label="Current open positions table">
        <table class="data-table">
          <caption>Summary of current open roles at SwinEduTech</caption>
          <thead>
            <tr>
              <th scope="col">Department</th>
              <th scope="col">Job title</th>
              <th scope="col">Location</th>
              <th scope="col">Type</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row" rowspan="2">Engineering</th>
              <td><a href="jobs.php#fe001">Front-End Developer</a></td>
              <td>Melbourne, hybrid</td>
              <td>Full-time</td>
            </tr>
            <tr>
              <td><a href="jobs.php#fs002">Full-Stack Developer</a></td>
              <td>Remote, Australia</td>
              <td>Full-time</td>
            </tr>
            <tr>
              <th scope="row">Design</th>
              <td><a href="jobs.php#ux003">UI/UX Designer</a></td>
              <td>Melbourne, hybrid</td>
              <td>Part-time</td>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th scope="row" colspan="3">Total open positions</th>
              <!-- Inline CSS -->
              <td style="color: #b83227; font-size: 1.2rem;">3</td>
            </tr>
          </tfoot>
        </table>
      </div>
    </section>
  </main>

  <!-- Footer section -->

<?php include 'footer.inc'; ?>
