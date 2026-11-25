<?php
// Application page. Author: Nguyen Truong Minh Thuy. References: MDN Web Docs, W3C WAI
$page_title = 'Apply Now';
$page_description = 'Apply for a web developer or designer role at SwinEduTech. All fields are required except other skills.';
$page_keywords = 'SwinEduTech, apply, job application, web developer, designer';
$page_author = 'Nguyen Truong Minh Thuy';
$body_id = 'apply-page';
$page_style = <<<'CSS'
    .privacy-note {
      margin: 0 0 var(--space-lg);
      padding: var(--space-md) var(--space-lg);
      background-color: #e4eaf6;
      border-radius: var(--radius);
      color: var(--ink);
      font-weight: 600;
    }
CSS;
include 'header.inc';
?>

  <main id="main-content">
    <h1>Job application form</h1>
    <p>Fields marked with <span class="req">*</span> are required. Find the reference number on the <a href="jobs.php">Job Descriptions</a> page.</p>

    <div class="apply-form-container">
      <!-- Application form: client-side validation disabled so server-side validation runs in process_eoi.php -->
      <form action="process_eoi.php" method="post" novalidate>

        <fieldset>
          <legend>The job</legend>
          <div class="form-group">
            <label for="job-ref">Job reference number <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="job-ref" name="job_ref" aria-describedby="ref-hint" list="job-refs">
            <p class="hint" id="ref-hint">Exactly 5 letters or numbers, e.g. FE001</p>
            <datalist id="job-refs">
              <option value="FE001">Front-End Developer</option>
              <option value="FS002">Full-Stack Developer</option>
              <option value="UX003">UI/UX Designer</option>
            </datalist>
          </div>
        </fieldset>

        <fieldset>
          <legend>Personal details</legend>
          <div class="form-group">
            <label for="first-name">First name <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="first-name" name="first_name" autocomplete="given-name">
          </div>
          <div class="form-group">
            <label for="last-name">Last name <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="last-name" name="last_name" autocomplete="family-name">
          </div>
          <div class="form-group">
            <label for="dob">Date of birth <span class="req" aria-hidden="true">*</span></label>
            <!-- Real dates only, years 1900 to 2010 -->
            <input type="text" id="dob" name="dob" aria-describedby="dob-hint" autocomplete="bday">
            <p class="hint" id="dob-hint">dd/mm/yyyy, e.g. 27/03/2001</p>
          </div>
          <fieldset class="choices">
            <legend>Gender <span class="req" aria-hidden="true">*</span></legend>
            <label><input type="radio" name="gender" value="female"> Female</label>
            <label><input type="radio" name="gender" value="male"> Male</label>
            <label><input type="radio" name="gender" value="other"> Other</label>
            <label><input type="radio" name="gender" value="not_say"> Prefer not to say</label>
          </fieldset>
        </fieldset>

        <fieldset>
          <legend>Address</legend>
          <div class="form-group wide">
            <label for="street">Street address <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="street" name="street_address" autocomplete="address-line1">
          </div>
          <div class="form-group">
            <label for="suburb">Suburb / town <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="suburb" name="suburb" autocomplete="address-level2">
          </div>
          <div class="form-group">
            <label for="state">State <span class="req" aria-hidden="true">*</span></label>
            <select id="state" name="state" autocomplete="address-level1">
              <option value="">Please select</option>
              <option value="VIC">VIC</option>
              <option value="NSW">NSW</option>
              <option value="QLD">QLD</option>
              <option value="NT">NT</option>
              <option value="WA">WA</option>
              <option value="SA">SA</option>
              <option value="TAS">TAS</option>
              <option value="ACT">ACT</option>
            </select>
          </div>
          <div class="form-group">
            <label for="postcode">Postcode <span class="req" aria-hidden="true">*</span></label>
            <input type="text" id="postcode" name="postcode" inputmode="numeric" autocomplete="postal-code">
          </div>
        </fieldset>

        <fieldset>
          <legend>Contact</legend>
          <div class="form-group">
            <label for="email">Email <span class="req" aria-hidden="true">*</span></label>
            <input type="email" id="email" name="email" autocomplete="email">
          </div>
          <div class="form-group">
            <label for="phone">Phone number <span class="req" aria-hidden="true">*</span></label>
            <input type="tel" id="phone" name="phone" aria-describedby="phone-hint" autocomplete="tel">
            <p class="hint" id="phone-hint">8 to 12 digits, no spaces</p>
          </div>
        </fieldset>

        <fieldset>
          <legend>Skills</legend>
          <fieldset class="choices" aria-describedby="skills-hint">
            <legend>Select your skills <span class="req" aria-hidden="true">*</span></legend>
            <p class="hint" id="skills-hint">HTML5 and CSS3 must be ticked. Tick any other skills you have.</p>
            <label><input type="checkbox" name="skills[]" value="html_css"> HTML5 and CSS3 (required for all roles)</label>
            <label><input type="checkbox" name="skills[]" value="javascript"> JavaScript</label>
            <label><input type="checkbox" name="skills[]" value="ui_ux"> UI/UX design</label>
            <label><input type="checkbox" name="skills[]" value="python"> Python</label>
          </fieldset>
          <div class="form-group wide">
            <!-- Inline CSS -->
            <label for="other-skills">Other skills <span style="font-weight: 400; color: #4f5878;">(optional)</span></label>
            <textarea id="other-skills" name="other_skills" rows="4"></textarea>
          </div>
        </fieldset>

        <p class="privacy-note"><strong>Your privacy:</strong> we use your details only to assess this application. We do not share them outside our hiring team, and we handle them under the Australian Privacy Principles.</p>

        <button type="submit" class="btn">Submit application</button>
      </form>
    </div>
  </main>

  <!-- Footer section -->

<?php include 'footer.inc'; ?>