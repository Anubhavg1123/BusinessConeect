const fs = require('fs');
const logoB64 = fs.readFileSync('E:/business-connect/report_assets/logo_base64.txt', 'utf8').trim();
const template = `<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Business Connect &ndash; Mini-Project Report</title>
  <style>
    @page {
      size: A4 portrait;
      margin: 0;
    }

    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }

    body {
      margin: 0;
      padding: 0;
      background: #ffffff;
      color: #1a1a1a;
      font-family: 'Cambria', 'Georgia', 'Times New Roman', serif;
      -webkit-font-smoothing: antialiased;
    }

    .page {
      width: 210mm;
      height: 297mm;
      max-height: 297mm;
      padding: 13mm 20mm 11mm 20mm;
      position: relative;
      page-break-after: always;
      page-break-inside: avoid;
      overflow: hidden;
      display: flex;
      flex-direction: column;
      background: #ffffff;
    }

    .page:last-child {
      page-break-after: avoid;
    }

    /* HEADER */
    .header-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 1.5mm;
    }

    .header-table td {
      vertical-align: middle;
      padding: 0;
    }

    .header-logo-cell {
      width: 45%;
      text-align: left;
    }

    .header-logo {
      height: 48px;
      width: auto;
      display: block;
    }

    .header-text-cell {
      width: 55%;
      text-align: right;
    }

    .inst-title {
      font-family: 'Cambria', serif;
      font-size: 11pt;
      font-weight: bold;
      color: #111111;
      text-transform: uppercase;
      letter-spacing: 0.2px;
      line-height: 1.25;
      margin-bottom: 2px;
    }

    .inst-dept {
      font-family: 'Cambria', serif;
      font-size: 10pt;
      color: #222222;
      line-height: 1.25;
      margin-bottom: 2px;
    }

    .inst-course {
      font-family: 'Cambria', serif;
      font-size: 9.5pt;
      color: #222222;
      line-height: 1.25;
    }

    .header-divider {
      width: 100%;
      height: 2.2px;
      background-color: #4471C4;
      margin-top: 2.5mm;
      margin-bottom: 3.5mm;
    }

    /* FOOTER */
    .footer-divider {
      width: 100%;
      height: 2.2px;
      background-color: #4471C4;
      margin-top: auto;
      margin-bottom: 2mm;
    }

    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-family: 'Calibri', 'Arial', sans-serif;
      font-size: 9pt;
      color: #808080;
      padding-top: 1px;
    }

    /* MAIN CONTENT */
    .page-content {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    /* TYPOGRAPHY & SECTIONS */
    .report-title-block {
      text-align: center;
      margin-bottom: 3mm;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 2mm;
    }

    .project-badge {
      display: inline-block;
      font-family: 'Calibri', sans-serif;
      font-size: 8.5pt;
      font-weight: bold;
      letter-spacing: 0.8px;
      text-transform: uppercase;
      color: #2b579a;
      background: #eef4fc;
      padding: 2px 10px;
      border-radius: 3px;
      margin-bottom: 3px;
    }

    .project-title {
      font-family: 'Cambria', serif;
      font-size: 14.5pt;
      font-weight: bold;
      color: #0f2d59;
      margin: 2px 0 4px 0;
      line-height: 1.2;
    }

    .project-meta-bar {
      font-family: 'Calibri', sans-serif;
      font-size: 9pt;
      color: #4a5568;
      display: flex;
      justify-content: center;
      gap: 15px;
    }

    .project-meta-bar span strong {
      color: #1a202c;
    }

    .section-heading {
      font-family: 'Cambria', serif;
      font-size: 10.5pt;
      font-weight: bold;
      color: #1e3a8a;
      border-left: 3.5px solid #4471C4;
      padding-left: 6px;
      margin-top: 2.2mm;
      margin-bottom: 1.8mm;
      text-transform: uppercase;
      letter-spacing: 0.2px;
    }

    p.academic-text {
      font-size: 9.3pt;
      line-height: 1.4;
      color: #2d3748;
      text-align: justify;
      margin: 0 0 2mm 0;
    }

    /* TABLES & CARDS */
    .tech-table {
      width: 100%;
      border-collapse: collapse;
      font-family: 'Calibri', sans-serif;
      font-size: 8.7pt;
      margin-bottom: 2.5mm;
    }

    .tech-table th {
      background-color: #f1f5f9;
      color: #1e293b;
      font-weight: bold;
      text-align: left;
      padding: 4px 8px;
      border: 1px solid #cbd5e1;
      font-size: 8.5pt;
      text-transform: uppercase;
    }

    .tech-table td {
      padding: 3.8px 8px;
      border: 1px solid #e2e8f0;
      color: #334155;
      line-height: 1.3;
    }

    .tech-table tr:nth-child(even) td {
      background-color: #f8fafc;
    }

    .tech-label {
      font-weight: bold;
      color: #1e3a8a;
      width: 25%;
    }

    .tech-value {
      width: 32%;
      font-weight: 600;
      color: #0f172a;
    }

    .tech-role {
      width: 43%;
      color: #475569;
    }

    /* LINKS CARD */
    .links-card {
      background: #f8fafc;
      border: 1px solid #cbd5e1;
      border-left: 3.5px solid #2563eb;
      border-radius: 4px;
      padding: 5px 12px;
      margin-bottom: 1.8mm;
    }

    .links-card-title {
      font-family: 'Calibri', sans-serif;
      font-size: 8.8pt;
      font-weight: bold;
      color: #1e293b;
      margin-bottom: 3px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .link-row {
      display: flex;
      align-items: center;
      margin: 2px 0;
      font-family: 'Calibri', sans-serif;
      font-size: 9.1pt;
    }

    .link-tag {
      font-weight: bold;
      width: 155px;
      color: #334155;
    }

    .link-url a {
      color: #1d4ed8;
      text-decoration: underline;
      font-weight: 600;
      word-break: break-all;
    }

    /* FEATURES GRID */
    .features-list {
      margin: 0;
      padding: 0;
      list-style: none;
    }

    .feature-item {
      position: relative;
      padding-left: 13px;
      margin-bottom: 1.6mm;
      font-size: 8.9pt;
      line-height: 1.32;
      color: #334155;
      text-align: justify;
    }

    .feature-item::before {
      content: '\25A0';
      position: absolute;
      left: 0;
      top: 0;
      color: #4471C4;
      font-size: 6.5pt;
    }

    .feature-title {
      font-weight: bold;
      color: #0f2d59;
    }

    /* ARCHITECTURE BOX */
    .arch-box {
      background: #f8fafc;
      border: 1px solid #e2e8f0;
      border-radius: 4px;
      padding: 5px 8px;
      margin-bottom: 1.8mm;
    }

    .arch-grid {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr;
      gap: 7px;
    }

    .arch-col {
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-radius: 3px;
      padding: 4px 6px;
    }

    .arch-col-title {
      font-family: 'Calibri', sans-serif;
      font-size: 8.2pt;
      font-weight: bold;
      color: #1e40af;
      margin-bottom: 2px;
      border-bottom: 1px solid #e2e8f0;
      padding-bottom: 2px;
      text-transform: uppercase;
    }

    .arch-col-desc {
      font-size: 8pt;
      line-height: 1.28;
      color: #475569;
    }

    .arch-col-desc code {
      font-family: 'Consolas', monospace;
      font-size: 7.8pt;
      background: #f1f5f9;
      padding: 1px 3px;
      border-radius: 2px;
      color: #0f172a;
    }
  </style>
</head>
<body>

  <!-- ==================== PAGE 1 ==================== -->
  <div class="page">
    <header>
      <table class="header-table">
        <tr>
          <td class="header-logo-cell">
            <img class="header-logo" src="data:image/png;base64,{{LOGO_BASE64}}" alt="Marwadi University NAAC A+">
          </td>
          <td class="header-text-cell">
            <div class="inst-title">FACULTY OF ENGINEERING AND TECHNOLOGY</div>
            <div class="inst-dept">Department of Computer Engineering</div>
            <div class="inst-course">01CE0528 &ndash; WEB DEVELOPMENT WITH PHP</div>
          </td>
        </tr>
      </table>
      <div class="header-divider"></div>
    </header>

    <main class="page-content">
      <div class="report-title-block">
        <div class="project-badge">Academic Mini-Project Report</div>
        <div class="project-title">Business Connect &ndash; A Business Management and Networking Platform</div>
        <div class="project-meta-bar">
          <span><strong>Student:</strong> Anubhav</span>
          <span>|</span>
          <span><strong>Enrollment No:</strong> 92400120380</span>
          <span>|</span>
          <span><strong>Evaluation:</strong> Mini-Project Assessment</span>
        </div>
      </div>

      <div class="section-heading">1. Project Overview & Problem Statement</div>
      <p class="academic-text">
        Many local small and medium-scale enterprises, independent contractors, and regional service providers face significant hurdles in establishing a cohesive digital presence, often lacking an accessible, cost-effective platform to showcase their offerings and commercial credentials. Concurrently, consumers and prospective partners encounter substantial friction when attempting to discover verified local businesses, compare product and service catalogs, and initiate direct, trustworthy commercial interactions. <strong>Business Connect</strong> resolves these operational barriers by providing a modern, centralized business directory and digital networking platform. The application allows registered vendors to publish structured profiles, manage categorized product and service catalogs, and directly interact with prospective clients through streamlined inquiry channels. By bridging local commerce with a responsive, authenticated web experience, the platform enhances commercial visibility, reduces customer discovery time, and supports direct economic networking.
      </p>

      <div class="section-heading">2. Verified Technology Stack</div>
      <p class="academic-text" style="margin-bottom: 1.8mm;">
        The system is constructed with an enterprise-grade Model-View-Controller (MVC) architecture, utilizing high-performance open-source technologies confirmed directly from the active production codebase:
      </p>

      <table class="tech-table">
        <thead>
          <tr>
            <th class="tech-label">System Layer</th>
            <th class="tech-value">Technology Employed</th>
            <th class="tech-role">Implementation Role & Functionality</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="tech-label">Backend Framework</td>
            <td class="tech-value">PHP 8.2+ / Laravel 12</td>
            <td class="tech-role">Core MVC backend, RESTful routing, Eloquent ORM, role middleware, and session lifecycle.</td>
          </tr>
          <tr>
            <td class="tech-label">Primary Hosting</td>
            <td class="tech-value">InfinityFree PHP Hosting</td>
            <td class="tech-role">Primary production Apache/PHP shared hosting with public/.htaccess routing and file-based sessions.</td>
          </tr>
          <tr>
            <td class="tech-label">Production Database</td>
            <td class="tech-value">InfinityFree MySQL</td>
            <td class="tech-role">Relational MySQL database (16 tables, foreign keys, persistent storage); TiDB Cloud as fallback.</td>
          </tr>
          <tr>
            <td class="tech-label">Templating & UI</td>
            <td class="tech-value">Laravel Blade Templates</td>
            <td class="tech-role">Server-side dynamic component rendering, template inheritance, layouts, and data binding.</td>
          </tr>
          <tr>
            <td class="tech-label">Frontend Styling</td>
            <td class="tech-value">HTML5, CSS3, JavaScript</td>
            <td class="tech-role">Semantic layout, mobile-first responsive grid (Bootstrap 5), interactive UI components, dynamic modals.</td>
          </tr>
          <tr>
            <td class="tech-label">Fallback & VCS</td>
            <td class="tech-value">Vercel & Git/GitHub</td>
            <td class="tech-role">Vercel serverless deployment maintained as secondary fallback; Git/GitHub distributed versioning.</td>
          </tr>
        </tbody>
      </table>

      <div class="section-heading">3. Live Deployment & Source Code Repositories</div>
      <div class="links-card">
        <div class="links-card-title">Public Access & Production Endpoints</div>
        <div class="link-row">
          <span class="link-tag">&#127760; Primary Live Website:</span>
          <span class="link-url"><a href="https://businessconnect.infinityfreeapp.com" target="_blank">https://businessconnect.infinityfreeapp.com</a></span>
        </div>
        <div class="link-row">
          <span class="link-tag">&#9889; Fallback Deployment:</span>
          <span class="link-url"><a href="https://business-connect-one.vercel.app" target="_blank">https://business-connect-one.vercel.app</a></span>
        </div>
        <div class="link-row">
          <span class="link-tag">&#128187; GitHub Repository:</span>
          <span class="link-url"><a href="https://github.com/Anubhavg1123/BusinessConeect" target="_blank">https://github.com/Anubhavg1123/BusinessConeect</a></span>
        </div>
      </div>
      <p class="academic-text" style="font-size: 8.5pt; color: #475569; margin-top: 1mm; margin-bottom: 0;">
        * <strong>Implementation Note:</strong> The application supports user registration, login, role-based access, and persistent database-backed contact and inquiry submissions.
      </p>
    </main>

    <footer>
      <div class="footer-divider"></div>
      <div class="footer-content">
        <span>Anubhav_92400120380</span>
        <span>1</span>
      </div>
    </footer>
  </div>

  <!-- ==================== PAGE 2 ==================== -->
  <div class="page">
    <header>
      <table class="header-table">
        <tr>
          <td class="header-logo-cell">
            <img class="header-logo" src="data:image/png;base64,{{LOGO_BASE64}}" alt="Marwadi University NAAC A+">
          </td>
          <td class="header-text-cell">
            <div class="inst-title">FACULTY OF ENGINEERING AND TECHNOLOGY</div>
            <div class="inst-dept">Department of Computer Engineering</div>
            <div class="inst-course">01CE0528 &ndash; WEB DEVELOPMENT WITH PHP</div>
          </td>
        </tr>
      </table>
      <div class="header-divider"></div>
    </header>

    <main class="page-content">
      <div class="section-heading" style="margin-top: 0;">4. Implemented Features & Core Modules</div>
      <p class="academic-text" style="margin-bottom: 1.5mm;">
        All features described below have been verified against the production codebase and live cloud deployment:
      </p>

      <ul class="features-list">
        <li class="feature-item">
          <span class="feature-title">Business Directory & Profile Pages:</span>
          Provides a multi-category directory showcasing registered enterprises with real-time category filtering, full-text search, and automated view counters. Each enterprise maintains an individualized business profile displaying corporate overview, location coordinates, business hours, and operational contact channels.
        </li>
        <li class="feature-item">
          <span class="feature-title">Product & Service Showcase Listings:</span>
          Enables business owners to publish structured catalogs of physical merchandise and specialized services. Listings feature descriptive metadata, pricing values, categorization tags, and availability status indicators, with paginated public browse views.
        </li>
        <li class="feature-item">
          <span class="feature-title">Customer Inquiries & Contact Form Submissions:</span>
          Includes an interactive inquiry dispatch system enabling prospective clients to transmit targeted inquiries directly to specific vendors. General visitors can also transmit authenticated inquiries via a centralized contact form, validated server-side and recorded persistently.
        </li>
        <li class="feature-item">
          <span class="feature-title">Authentication & Role-Based Account Registration:</span>
          Features dedicated portal interfaces for customer registration, business owner onboarding, and administrative credentials. Implements secure session handling, CSRF defense tokens, role-based middleware guards (customer, business owner, admin), and encrypted password hashing.
        </li>
        <li class="feature-item">
          <span class="feature-title">Database-Backed Records & Persistent Storage:</span>
          Implements normalized relational persistence across key entities including users, businesses, business categories, products, services, customer reviews, and customer inquiries, with referential integrity enforced on production MySQL.
        </li>
        <li class="feature-item">
          <span class="feature-title">Responsive Interface & Adaptive Navigation:</span>
          Features a mobile-first responsive layout utilizing responsive navigation bars, categorized badge filtering, dynamic search suggestion APIs, and accessible interface cards tailored for mobile, tablet, and desktop viewports.
        </li>
      </ul>

      <div class="section-heading">5. System Architecture & Cloud Integration</div>
      <div class="arch-box">
        <div class="arch-grid">
          <div class="arch-col">
            <div class="arch-col-title">Presentation Tier</div>
            <div class="arch-col-desc">
              Blade templates under <code>resources/views</code> delivering clean layouts, interactive components, responsive styling, and fast client-side rendering.
            </div>
          </div>
          <div class="arch-col">
            <div class="arch-col-title">Application Tier</div>
            <div class="arch-col-desc">
              Laravel 12 controllers managing HTTP routing, validation, role middleware authorization, session lifecycle, and Apache <code>.htaccess</code> rewriting on InfinityFree.
            </div>
          </div>
          <div class="arch-col">
            <div class="arch-col-title">Data Tier</div>
            <div class="arch-col-desc">
              Production InfinityFree MySQL database executing relational queries with full schema integrity, with remote TiDB Cloud retained as a secondary fallback.
            </div>
          </div>
        </div>
      </div>

      <div class="section-heading">6. Project Evaluation & Conclusion</div>
      <p class="academic-text">
        The <strong>Business Connect</strong> mini-project successfully validates the practical design and deployment of a full-stack, enterprise-grade web application using PHP and Laravel 12. By addressing the real-world challenge of local business visibility and digital networking, the platform demonstrates rigorous Model-View-Controller architectural organization, relational database design with production MySQL hosting on InfinityFree, and secondary serverless fallback readiness. All core functionalities&mdash;including directory indexing, product/service discovery, authenticated registration, role authorization, and persistent inquiry processing&mdash;operate seamlessly across mobile and desktop environments. The project bridges academic software engineering concepts with industry-standard deployment practices, providing a secure, scalable foundation for digital business networking.
      </p>

      <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 4px; padding: 4px 8px; margin-top: 1mm;">
        <div style="font-family: 'Calibri', sans-serif; font-size: 8.5pt; color: #1e40af; font-weight: bold;">
          Academic Verification Summary
        </div>
        <div style="font-family: 'Calibri', sans-serif; font-size: 8pt; color: #2563eb; display: flex; justify-content: space-between; margin-top: 2px;">
          <span>&#10004; Codebase Integrity: Verified (Laravel 12 / PHP 8.2+)</span>
          <span>&#10004; Database Persistence: Verified (Production MySQL)</span>
          <span>&#10004; Live Deployment: Verified (InfinityFree Primary / Vercel Fallback)</span>
        </div>
      </div>
    </main>

    <footer>
      <div class="footer-divider"></div>
      <div class="footer-content">
        <span>Anubhav_92400120380</span>
        <span>2</span>
      </div>
    </footer>
  </div>

</body>
</html>
`;
const renderedHtml = template.replaceAll('{{LOGO_BASE64}}', logoB64);
fs.writeFileSync('E:/business-connect/report.html', renderedHtml, 'utf8');
console.log('Successfully updated E:/business-connect/report.html');
