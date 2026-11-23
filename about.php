<?php
// About page. References: MDN Web Docs, W3C WAI
$page_title = 'About Us';
$page_description = 'Meet group DVMT, the two Swinburne students who built the SwinEduTech recruitment website for COS10026 Web Technology Project.';
$page_keywords = 'SwinEduTech, DVMT, about, team, COS10026, Swinburne';
$page_author = 'Nguyen Dang Vinh';
$body_id = 'about-page';
$page_style = <<<'CSS'
    .members dd ul {
      padding-left: var(--space-md);
      margin: var(--space-sm) 0 0;
    }
CSS;
include 'header.inc';
?>

  <main id="main-content">
    <h1>About our team</h1>
    <p class="lead">We are group DVMT, two students who built the SwinEduTech recruitment website. We split the four pages evenly and share one stylesheet.</p>

    <!-- Group details (nested list) -->
    <section class="group-info" aria-labelledby="group-heading">
      <h2 id="group-heading">Group details</h2>
      <ul>
        <!-- Inline CSS -->
        <li>Group name: <strong style="color: #b83227; font-family: var(--font-mono); letter-spacing: 1px;">DVMT</strong> (HCM_ThursdayMorning_09)</li>
        <li>Class: COS10026.3
          <ul>
            <li>Day: Thursday</li>
            <li>Time: 7:00 AM to 10:00 AM</li>
          </ul>
        </li>
        <li>Unit: COS10026 - Web Technology Project</li>
        <li>Industry: G04 Educational Technology (EdTech)</li>
      </ul>
    </section>

    <section aria-labelledby="members-heading">
      <h2 id="members-heading">Members and contributions</h2>
      <!-- Members (definition list) -->
      <dl class="members">
        <div class="member">
          <dt>Nguyen Dang Vinh <span class="student-id">SWS00172</span></dt>
          <dd>
            <ul>
              <li>Built jobs.html and about.html</li>
              <li>Wrote the colour variables, typography and layout in style.css used by all four pages</li>
              <li>Ran HTML validation and accessibility checks on all pages</li>
            </ul>
          </dd>
          <dd>
            <blockquote lang="vi">
              <p>Có công mài sắt, có ngày nên kim.</p>
            </blockquote>
            <p class="translation">English: If you keep grinding an iron bar, one day it becomes a needle. Hard work pays off.</p>
          </dd>
        </div>

        <div class="member">
          <dt>Nguyen Truong Minh Thuy <span class="student-id">SWD00450</span></dt>
          <dd>
            <ul>
              <li>Built index.html and apply.html</li>
              <li>Set up the Jira board for the project</li>
              <li>Deployed the website on GitHub Pages</li>
            </ul>
          </dd>
          <dd>
            <blockquote lang="vi">
              <p>Đi một ngày đàng, học một sàng khôn.</p>
            </blockquote>
            <p class="translation">English: Travel for one day and you gain a basket of wisdom. Every new experience teaches you something.</p>
          </dd>
        </div>
      </dl>
    </section>

    <!-- Group photo -->
    <figure class="group-photo">
      <img src="images/group-photo.jpg" alt="Illustration of Nguyen Dang Vinh and Nguyen Truong Minh Thuy working on laptops at the same desk" width="640" height="431" loading="lazy">
      <figcaption>Group DVMT</figcaption>
    </figure>

    <!-- Fun facts table -->
    <section aria-labelledby="facts-heading">
      <h2 id="facts-heading">Fun facts</h2>
      <div class="table-wrap" tabindex="0" role="region" aria-label="Fun facts table">
        <table class="data-table">
          <caption>Fun facts about group DVMT</caption>
          <thead>
            <tr>
              <th scope="col">Name</th>
              <th scope="col">Dream job</th>
              <th scope="col">Coding snack</th>
              <th scope="col">Hometown</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">Nguyen Dang Vinh</th>
              <td>AI Engineer</td>
              <td><span lang="vi">Cà phê sữa đá</span> (iced milk coffee)</td>
              <td>Buon Ma Thuot, Dak Lak</td>
            </tr>
            <tr>
              <th scope="row">Nguyen Truong Minh Thuy</th>
              <td>DevOps Engineer</td>
              <td><span lang="vi">Bánh tráng trộn</span> (rice paper salad)</td>
              <td>Ho Chi Minh City</td>
            </tr>
          </tbody>
        </table>
      </div>
    </section>

    <!-- Acknowledgement of Country -->
    <section class="country" aria-labelledby="country-heading">
      <h2 id="country-heading">Acknowledgement of Country</h2>
      <p>Every page of this site ends with an Acknowledgement of Country to the Wurundjeri People of the Kulin Nation, the Traditional Owners of the land where Swinburne’s Melbourne campuses and our Melbourne office stand. We also pay our respect to all Aboriginal and Torres Strait Islander peoples who use our learning tools.</p>
      <p>An acknowledgement only means something if it changes what we do, so on this site it also shows up in two practical ways:</p>
      <ul>
        <li>The job descriptions page invites Aboriginal and Torres Strait Islander peoples to apply and offers a culturally safe contact person during hiring.</li>
        <li>Our product goal is learning tools that work for every learner, including First Nations students in regional and remote communities with slow internet.</li>
      </ul>
      <p>As Swinburne students we can also point applicants to the Moondani Toombadool Centre, Swinburne’s Aboriginal centre, whose Indigenous Student Service supports Indigenous students on campus, online and in the community.</p>
    </section>
  </main>

  <!-- Footer section -->

<?php include 'footer.inc'; ?>
