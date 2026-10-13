const express = require('express');
const session = require('express-session');
const cookieParser = require('cookie-parser');
const path = require('path');
const fs = require('fs');

const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.static(path.join(__dirname, 'public')));
app.use(express.urlencoded({ extended: true }));
app.use(express.json());
app.use(cookieParser());
app.use(session({
  secret: 'business_connect_secret_key_2026',
  resave: false,
  saveUninitialized: false,
  cookie: { maxAge: 24 * 60 * 60 * 1000 }
}));

// In-Memory Stateful Database
let db = {
  roles: [
    { id: 1, name: 'admin', label: 'System Administrator' },
    { id: 2, name: 'business_owner', label: 'Business Owner' },
    { id: 3, name: 'customer', label: 'Customer' }
  ],
  users: [
    { id: 1, name: 'System Admin', email: 'admin@businessconnect.com', phone: '+1-800-555-0199', password: 'password123', role: 'admin', status: 'active' },
    { id: 2, name: 'Alex Carter', email: 'alex@nexustech.com', phone: '+1-555-0147', password: 'password123', role: 'business_owner', status: 'active' },
    { id: 3, name: 'Sophia Bennett', email: 'sophia@greenleaf.com', phone: '+1-555-0188', password: 'password123', role: 'business_owner', status: 'active' },
    { id: 4, name: 'David Miller', email: 'david.customer@gmail.com', phone: '+1-555-0192', password: 'password123', role: 'customer', status: 'active' }
  ],
  categories: [
    { id: 1, name: 'Information Technology', slug: 'technology', icon: 'bi-laptop', description: 'Software development, IT consulting, cloud infrastructure and cybersecurity.' },
    { id: 2, name: 'Healthcare & Wellness', slug: 'healthcare', icon: 'bi-heart-pulse', description: 'Hospitals, clinics, physical therapy, medical diagnostics and wellness centers.' },
    { id: 3, name: 'Real Estate & Living', slug: 'real-estate', icon: 'bi-building-gear', description: 'Commercial properties, luxury apartments, architecture and land development.' },
    { id: 4, name: 'Retail & Consumer Goods', slug: 'retail', icon: 'bi-cart-check', description: 'Electronics, apparel, home decor, lifestyle products and luxury goods.' },
    { id: 5, name: 'Financial Services', slug: 'financial', icon: 'bi-cash-coin', description: 'Banking, investment advisory, tax compliance and accounting solutions.' }
  ],
  businesses: [
    {
      id: 1,
      user_id: 2,
      name: 'NexusTech Solutions Ltd',
      owner_name: 'Alex Carter',
      email: 'contact@nexustech.com',
      phone: '+1 (555) 014-7890',
      category_id: 1,
      category_name: 'Information Technology',
      gst_number: '27ABCDE1234F1Z5',
      website: 'https://nexustech.example.com',
      description: 'NexusTech Solutions is an industry-leading software engineering and cloud transformation consulting firm. We build high-performance web applications, enterprise cloud architecture, and AI-driven automation pipelines.',
      opening_time: '09:00',
      closing_time: '18:00',
      state: 'California',
      city: 'San Francisco',
      address: '100 Silicon Way, Suite 400',
      pin_code: '94105',
      status: 'approved',
      views_count: 1420
    },
    {
      id: 2,
      user_id: 3,
      name: 'Green Leaf Organic Wellness',
      owner_name: 'Sophia Bennett',
      email: 'hello@greenleaf.com',
      phone: '+1 (555) 018-8923',
      category_id: 2,
      category_name: 'Healthcare & Wellness',
      gst_number: '27XYZAB5678G2Z9',
      website: 'https://greenleafwellness.example.com',
      description: 'Green Leaf Organic Wellness offers 100% natural, cold-pressed herbal supplements, holistic therapy, and eco-friendly personal care products crafted for maximum health and vitality.',
      opening_time: '08:30',
      closing_time: '20:00',
      state: 'Texas',
      city: 'Austin',
      address: '450 Organic Boulevard',
      pin_code: '78701',
      status: 'approved',
      views_count: 890
    }
  ],
  products: [
    { id: 1, business_id: 1, name: 'Enterprise CRM Cloud License', description: 'Automate sales pipelines, customer relations, and multi-channel marketing campaigns.', price: 499.00, discount: 10, stock: 100, availability: 'in_stock', status: 'active' },
    { id: 2, business_id: 1, name: 'DevOps Security Scanner', description: 'Automated CI/CD vulnerability scanner and cloud compliance auditor.', price: 299.00, discount: 0, stock: 50, availability: 'in_stock', status: 'active' },
    { id: 3, business_id: 2, name: 'Organic Hemp Herbal Oil Extract', description: 'Cold-pressed 100% pure organic hemp oil formula for stress relief and vitality.', price: 65.00, discount: 5, stock: 250, availability: 'in_stock', status: 'active' }
  ],
  services: [
    { id: 1, business_id: 1, name: 'Custom Cloud Infrastructure Audit', description: 'Complete security audit, cost optimization, and DevOps pipeline setup for AWS / Azure.', price: 1200.00, duration: '3 Days', availability: 'available' },
    { id: 2, business_id: 2, name: 'Holistic Wellness Consultation', description: 'Personalized 1-on-1 diet, nutritional evaluation, and lifestyle optimization plan.', price: 150.00, duration: '60 Minutes', availability: 'available' }
  ],
  inquiries: [
    { id: 1, customer_id: 4, business_id: 1, name: 'David Miller', email: 'david.customer@gmail.com', phone: '+1-555-0192', subject: 'Quote request for enterprise CRM', message: 'Hello, we have a sales team of 40 users and would like a migration quote.', preferred_contact_time: 'Morning (9 AM - 12 PM)', status: 'replied', reply_message: 'Hi David, our senior solutions architect will email you the custom proposal today.' },
    { id: 2, customer_id: 4, business_id: 2, name: 'David Miller', email: 'david.customer@gmail.com', phone: '+1-555-0192', subject: 'Bulk wellness order inquiry', message: 'Do you offer wholesale pricing for corporate wellness hampers?', preferred_contact_time: 'Afternoon', status: 'pending', reply_message: null }
  ],
  reviews: [
    { id: 1, customer_id: 4, customer_name: 'David Miller', business_id: 1, rating: 5, review_text: 'Outstanding IT support and cloud migration service! Saved us over 30% in infrastructure costs.', created_at: '2026-08-01' }
  ],
  favorites: [
    { id: 1, customer_id: 4, business_id: 1 }
  ]
};

// Global View Helper: Calculates average rating for a business
function getAverageRating(businessId) {
  const revs = db.reviews.filter(r => r.business_id === businessId);
  if (revs.length === 0) return '5.0';
  const sum = revs.reduce((acc, curr) => acc + curr.rating, 0);
  return (sum / revs.length).toFixed(1);
}

// Global Layout Builder with Flash Alerts & Navbar
function renderPageLayout(title, user, flashMessage, flashError, contentHtml) {
  return `<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>${title} – Business Connect Platform</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- SweetAlert2 -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/custom.css" rel="stylesheet">
</head>
<body>

    <!-- Header Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-custom py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="/">
                <div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="bi bi-briefcase-fill fs-5"></i>
                </div>
                <span class="brand-font fs-4 text-primary">Business<span class="text-secondary">Connect</span></span>
            </a>

            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">
                <!-- Search Box -->
                <div class="mx-auto my-2 my-lg-0 position-relative style-search" style="max-width: 400px; width: 100%;">
                    <form action="/businesses" method="GET">
                        <div class="input-group">
                            <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="global-search-input" name="q" class="form-control border-start-0 ps-0" placeholder="Search businesses, products, services..." autocomplete="off">
                            <button class="btn btn-primary px-3" type="submit">Search</button>
                        </div>
                    </form>
                    <div id="search-suggestions-dropdown" class="position-absolute w-100 mt-1 glass-card d-none z-3 overflow-hidden"></div>
                </div>

                <ul class="navbar-nav me-auto mb-2 mb-lg-0 fw-semibold">
                    <li class="nav-item"><a class="nav-link" href="/businesses"><i class="bi bi-shop me-1"></i> Businesses</a></li>
                    <li class="nav-item"><a class="nav-link" href="/products"><i class="bi bi-box-seam me-1"></i> Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="/services"><i class="bi bi-tools me-1"></i> Services</a></li>
                    <li class="nav-item"><a class="nav-link" href="/contact"><i class="bi bi-envelope me-1"></i> Contact</a></li>
                </ul>

                <div class="d-flex align-items-center gap-2">
                    <button id="theme-toggle-btn" class="btn btn-outline-secondary border-0 btn-sm me-2">
                        <i class="bi bi-moon-stars-fill text-primary"></i> Dark Mode
                    </button>

                    ${user ? `
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle rounded-pill px-3 fw-bold" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> ${user.name}
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2">
                                <li class="dropdown-header text-uppercase small text-muted">Logged as: ${user.role.replace('_', ' ')}</li>
                                <li><hr class="dropdown-divider"></li>
                                ${user.role === 'admin' ? `<li><a class="dropdown-item py-2" href="/admin/dashboard"><i class="bi bi-speedometer2 me-2 text-primary"></i> Admin Dashboard</a></li>` : ''}
                                ${user.role === 'business_owner' ? `
                                    <li><a class="dropdown-item py-2" href="/business/dashboard"><i class="bi bi-kanban me-2 text-primary"></i> Business Dashboard</a></li>
                                    <li><a class="dropdown-item py-2" href="/business/profile"><i class="bi bi-person-lines-fill me-2 text-info"></i> Edit Business Profile</a></li>
                                ` : ''}
                                ${user.role === 'customer' ? `
                                    <li><a class="dropdown-item py-2" href="/customer/dashboard"><i class="bi bi-person-workspace me-2 text-primary"></i> Customer Portal</a></li>
                                    <li><a class="dropdown-item py-2" href="/customer/favorites"><i class="bi bi-heart me-2 text-danger"></i> Saved Favorites</a></li>
                                ` : ''}
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 text-danger" href="/logout"><i class="bi bi-box-arrow-right me-2"></i> Sign Out</a></li>
                            </ul>
                        </div>
                    ` : `
                        <a href="/login" class="btn btn-outline-primary rounded-pill px-4 fw-semibold">Sign In</a>
                        <div class="dropdown">
                            <button class="btn btn-primary rounded-pill px-4 dropdown-toggle fw-semibold" type="button" data-bs-toggle="dropdown">Register</button>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-4 mt-2">
                                <li><a class="dropdown-item py-2" href="/register-business"><i class="bi bi-building me-2 text-primary"></i> As Business Owner</a></li>
                                <li><a class="dropdown-item py-2" href="/register-customer"><i class="bi bi-person me-2 text-secondary"></i> As Customer</a></li>
                            </ul>
                        </div>
                    `}
                </div>
            </div>
        </div>
    </nav>

    <!-- Session Flash Alerts -->
    ${flashMessage ? `
        <div class="container mt-3">
            <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> ${flashMessage}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    ` : ''}

    ${flashError ? `
        <div class="container mt-3">
            <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 shadow-sm" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> ${flashError}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    ` : ''}

    <!-- Main Content Area -->
    <main>
        ${contentHtml}
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="brand-font text-white mb-3"><i class="bi bi-briefcase-fill me-2 text-primary"></i>Business Connect</h5>
                    <p class="text-white-50 small">Promote your enterprise, market high-quality products and services, and connect seamlessly with customers worldwide on an accredited platform.</p>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-uppercase fw-bold text-light mb-3">Navigation</h6>
                    <ul class="list-unstyled text-white-50 small">
                        <li class="mb-2"><a href="/" class="text-white-50 text-decoration-none">Home</a></li>
                        <li class="mb-2"><a href="/businesses" class="text-white-50 text-decoration-none">Businesses</a></li>
                        <li class="mb-2"><a href="/products" class="text-white-50 text-decoration-none">Products</a></li>
                        <li class="mb-2"><a href="/services" class="text-white-50 text-decoration-none">Services</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold text-light mb-3">Quick Dashboards</h6>
                    <ul class="list-unstyled text-white-50 small">
                        <li class="mb-2"><a href="/admin/dashboard" class="text-white-50 text-decoration-none">Admin Panel</a></li>
                        <li class="mb-2"><a href="/business/dashboard" class="text-white-50 text-decoration-none">Business Owner Portal</a></li>
                        <li class="mb-2"><a href="/customer/dashboard" class="text-white-50 text-decoration-none">Customer Portal</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h6 class="text-uppercase fw-bold text-light mb-3">Contact Support</h6>
                    <p class="text-white-50 small mb-1"><i class="bi bi-geo-alt me-2"></i> Silicon Tower, Suite 400</p>
                    <p class="text-white-50 small mb-1"><i class="bi bi-envelope me-2"></i> support@businessconnect.com</p>
                    <p class="text-white-50 small"><i class="bi bi-telephone me-2"></i> +1 (800) 555-0199</p>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-white-50 small">
                <div>&copy; 2026 Business Connect. All Rights Reserved.</div>
                <div>Production-Ready Full Stack Architecture</div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.7/dist/sweetalert2.all.min.js"></script>
    <!-- Custom Theme & App Scripts -->
    <script src="/js/theme.js"></script>
    <script src="/js/charts.js"></script>
    <script src="/js/app.js"></script>
</body>
</html>`;
}

// Middleware: Role Access Control
function requireAuth(req, res, next) {
  if (!req.session.user) {
    req.session.flashError = 'Authentication required. Please sign in to your account.';
    return res.redirect('/login');
  }
  next();
}

function requireRole(...roles) {
  return (req, res, next) => {
    if (!req.session.user) {
      req.session.flashError = 'Please log in to access this page.';
      return res.redirect('/login');
    }
    if (!roles.includes(req.session.user.role)) {
      req.session.flashError = `Access Denied! Your account role (${req.session.user.role}) does not have permission to access that section.`;
      return res.redirect('/');
    }
    next();
  };
}

// Flash Message Middleware Handler
app.use((req, res, next) => {
  res.locals.flashMessage = req.session.flashMessage || null;
  res.locals.flashError = req.session.flashError || null;
  delete req.session.flashMessage;
  delete req.session.flashError;
  next();
});

// ====================================================
// PUBLIC ROUTES
// ====================================================

// Landing Page
app.get('/', (req, res) => {
  const approvedBusinesses = db.businesses.filter(b => b.status === 'approved');
  const content = `
    <!-- Hero Section -->
    <section class="hero-wrapper py-5 text-center text-lg-start position-relative">
        <div class="container py-5">
            <div class="row align-items-center g-5">
                <div class="col-lg-7 animate-fade-in">
                    <span class="badge bg-white text-primary rounded-pill px-3 py-2 fw-semibold mb-3">
                        <i class="bi bi-stars me-1 text-warning"></i> Empowering Modern Enterprises
                    </span>
                    <h1 class="display-3 text-white fw-bold mb-3">Connect, Grow & Scale Your Business Online</h1>
                    <p class="lead text-white-50 mb-4">Discover verified local and global businesses, explore premium products & services, send direct inquiries, and network seamlessly on Business Connect.</p>
                    <div class="d-flex flex-wrap justify-content-center justify-content-lg-start gap-3">
                        <a href="/businesses" class="btn btn-secondary btn-lg rounded-pill px-4 shadow"><i class="bi bi-search me-2"></i> Explore Directory</a>
                        <a href="/register-business" class="btn btn-outline-light btn-lg rounded-pill px-4"><i class="bi bi-plus-circle me-2"></i> Register Business</a>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <div class="glass-card p-4 text-dark text-start shadow-lg">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary text-white rounded-circle p-3 me-3">
                                <i class="bi bi-search fs-4"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 fw-bold">Quick Directory Search</h5>
                                <small class="text-muted">Find verified partners instantly</small>
                            </div>
                        </div>
                        <form action="/businesses" method="GET">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Keywords</label>
                                <input type="text" name="q" class="form-control rounded-3" placeholder="e.g. NexusTech, Organic Hemp">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Category</label>
                                <select name="category" class="form-select rounded-3">
                                    <option value="">All Categories</option>
                                    ${db.categories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold"><i class="bi bi-funnel me-1"></i> Filter Directory</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Counter -->
    <section class="py-5 bg-body-tertiary">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-6 col-md-3">
                    <div class="glass-card p-4">
                        <h2 class="display-5 text-primary fw-bold mb-0">${approvedBusinesses.length}</h2>
                        <p class="text-muted mb-0 fw-semibold">Approved Businesses</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="glass-card p-4">
                        <h2 class="display-5 text-secondary fw-bold mb-0">${db.products.length}</h2>
                        <p class="text-muted mb-0 fw-semibold">Listed Products</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="glass-card p-4">
                        <h2 class="display-5 text-success fw-bold mb-0">${db.services.length}</h2>
                        <p class="text-muted mb-0 fw-semibold">Professional Services</p>
                    </div>
                </div>
                <div class="col-6 col-md-3">
                    <div class="glass-card p-4">
                        <h2 class="display-5 text-warning fw-bold mb-0">${db.reviews.length}</h2>
                        <p class="text-muted mb-0 fw-semibold">Verified Reviews</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Grid -->
    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="badge badge-category mb-2">Browse by Sector</span>
                <h2 class="display-6 fw-bold">Explore Business Categories</h2>
                <p class="text-muted">Find specialists and accredited companies tailored to your industry</p>
            </div>
            <div class="row g-4">
                ${db.categories.map(c => `
                    <div class="col-md-4 col-lg-2-4">
                        <a href="/businesses?category=${c.id}" class="text-decoration-none text-dark">
                            <div class="glass-card p-4 text-center h-100">
                                <div class="bg-primary-subtle text-primary rounded-circle mx-auto p-3 mb-3 d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                    <i class="bi ${c.icon} fs-3"></i>
                                </div>
                                <h6 class="fw-bold mb-1">${c.name}</h6>
                                <small class="text-muted">${db.businesses.filter(b => b.category_id === c.id).length} Listed</small>
                            </div>
                        </a>
                    </div>
                `).join('')}
            </div>
        </div>
    </section>

    <!-- Featured Businesses Grid -->
    <section class="py-5 bg-body-tertiary">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <span class="badge badge-category mb-2">Verified Partners</span>
                    <h2 class="display-6 fw-bold mb-0">Featured Businesses</h2>
                </div>
                <a href="/businesses" class="btn btn-outline-primary rounded-pill">View All Businesses <i class="bi bi-arrow-right"></i></a>
            </div>
            <div class="row g-4">
                ${approvedBusinesses.map(b => `
                    <div class="col-md-6 col-lg-4">
                        <div class="glass-card h-100 p-4 d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-primary text-white rounded-3 p-3 me-3 d-flex align-items-center justify-content-center fw-bold fs-4" style="width: 55px; height: 55px;">
                                    ${b.name.charAt(0)}
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0 text-truncate" style="max-width: 200px;">${b.name}</h5>
                                    <span class="badge bg-secondary-subtle text-secondary small"><i class="bi bi-tag me-1"></i> ${b.category_name}</span>
                                </div>
                            </div>
                            <p class="text-muted small flex-grow-1">${b.description.substring(0, 110)}...</p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div class="rating-stars small">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <span class="fw-bold text-dark me-1">${getAverageRating(b.id)}</span>
                                </div>
                                <a href="/businesses/${b.id}" class="btn btn-sm btn-primary rounded-pill px-3">View Profile</a>
                            </div>
                        </div>
                    </div>
                `).join('')}
            </div>
        </div>
    </section>
  `;
  res.send(renderPageLayout('Home', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

// Business Directory
app.get('/businesses', (req, res) => {
  let results = db.businesses.filter(b => b.status === 'approved');
  if (req.query.q) {
    const q = req.query.q.toLowerCase();
    results = results.filter(b => b.name.toLowerCase().includes(q) || b.description.toLowerCase().includes(q) || b.city.toLowerCase().includes(q));
  }
  if (req.query.category) {
    const catId = parseInt(req.query.category);
    results = results.filter(b => b.category_id === catId);
  }

  const content = `
    <div class="bg-primary-subtle py-4 border-bottom">
        <div class="container">
            <h2 class="fw-bold mb-1">Business Directory</h2>
            <p class="text-muted mb-0">Browse accredited businesses, filter by category or location</p>
        </div>
    </div>
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-lg-3">
                <div class="glass-card p-4">
                    <h5 class="fw-bold mb-3"><i class="bi bi-funnel me-2 text-primary"></i>Filter Search</h5>
                    <form action="/businesses" method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Keywords</label>
                            <input type="text" name="q" class="form-control rounded-3" value="${req.query.q || ''}" placeholder="Search by name, city...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Category</label>
                            <select name="category" class="form-select rounded-3">
                                <option value="">All Categories</option>
                                ${db.categories.map(c => `<option value="${c.id}" ${req.query.category == c.id ? 'selected' : ''}>${c.name}</option>`).join('')}
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-3 py-2 fw-semibold">Apply Filters</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-9">
                <div class="row g-4">
                    ${results.length > 0 ? results.map(b => `
                        <div class="col-md-6">
                            <div class="glass-card h-100 p-4 d-flex flex-column">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="bg-primary text-white rounded-3 p-3 me-3 d-flex align-items-center justify-content-center fw-bold fs-3" style="width: 55px; height: 55px;">
                                        ${b.name.charAt(0)}
                                    </div>
                                    <div>
                                        <h5 class="fw-bold mb-1">${b.name}</h5>
                                        <span class="badge bg-secondary-subtle text-secondary"><i class="bi bi-geo-alt me-1"></i>${b.city}, ${b.state}</span>
                                    </div>
                                </div>
                                <p class="text-muted small flex-grow-1">${b.description.substring(0, 110)}...</p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <div class="rating-stars small">
                                        <i class="bi bi-star-fill text-warning"></i>
                                        <span class="fw-bold text-dark me-1">${getAverageRating(b.id)}</span>
                                    </div>
                                    <a href="/businesses/${b.id}" class="btn btn-primary rounded-pill px-3 btn-sm">View Profile</a>
                                </div>
                            </div>
                        </div>
                    `).join('') : `<div class="col-12 text-center py-5 text-muted">No businesses found matching your criteria.</div>`}
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Business Directory', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

// Business Detail View (With Tabs for Overview, Products, Services, Reviews & Direct Inquiry Modal)
app.get('/businesses/:id', (req, res) => {
  const bizId = parseInt(req.params.id);
  const b = db.businesses.find(x => x.id === bizId);
  if (!b) {
    req.session.flashError = 'Business not found.';
    return res.redirect('/businesses');
  }

  b.views_count += 1;
  const products = db.products.filter(p => p.business_id === b.id);
  const services = db.services.filter(s => s.business_id === b.id);
  const reviews = db.reviews.filter(r => r.business_id === b.id);

  const content = `
    <div class="bg-dark text-white py-5 position-relative">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-md-2 text-center text-md-start">
                    <div class="bg-primary text-white rounded-4 p-4 d-inline-flex align-items-center justify-content-center fw-bold display-4 shadow" style="width: 100px; height: 100px;">
                        ${b.name.charAt(0)}
                    </div>
                </div>
                <div class="col-md-7">
                    <span class="badge bg-success rounded-pill px-3 py-2 mb-2"><i class="bi bi-patch-check-fill me-1"></i> Verified Business</span>
                    <h1 class="fw-bold mb-2">${b.name}</h1>
                    <p class="text-white-50 mb-2"><i class="bi bi-geo-alt-fill text-danger me-1"></i> ${b.address}, ${b.city}, ${b.state} ${b.pin_code}</p>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rating-stars">
                            <i class="bi bi-star-fill text-warning"></i>
                            <span class="fw-bold text-white fs-5 ms-1">${getAverageRating(b.id)}</span>
                            <span class="text-white-50">(${reviews.length} reviews)</span>
                        </div>
                        <span class="text-white-50">|</span>
                        <span class="text-white-50"><i class="bi bi-eye me-1"></i> ${b.views_count} views</span>
                    </div>
                </div>
                <div class="col-md-3 text-center text-md-end">
                    ${req.session.user ? `
                        <form action="/favorite/toggle/${b.id}" method="POST" class="d-inline">
                            <button type="submit" class="btn btn-outline-light rounded-pill px-4 mb-2 w-100"><i class="bi bi-heart me-1"></i> Bookmark Favorite</button>
                        </form>
                    ` : ''}
                    <button class="btn btn-primary rounded-pill px-4 w-100" data-bs-toggle="modal" data-bs-target="#inquiryModal"><i class="bi bi-chat-dots me-1"></i> Send Inquiry</button>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <ul class="nav nav-pills mb-4 gap-2" id="businessTab" role="tablist">
            <li class="nav-item"><button class="nav-link active rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#overview">Overview</button></li>
            <li class="nav-item"><button class="nav-link rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#products">Products (${products.length})</button></li>
            <li class="nav-item"><button class="nav-link rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#services">Services (${services.length})</button></li>
            <li class="nav-item"><button class="nav-link rounded-pill px-4" data-bs-toggle="tab" data-bs-target="#reviews">Reviews (${reviews.length})</button></li>
        </ul>

        <div class="tab-content" id="businessTabContent">
            <!-- Overview -->
            <div class="tab-pane fade show active" id="overview">
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="glass-card p-4">
                            <h4 class="fw-bold mb-3">About ${b.name}</h4>
                            <p class="text-secondary leading-relaxed">${b.description}</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-telephone me-2 text-primary"></i>Contact Information</h5>
                            <ul class="list-unstyled mb-0 small">
                                <li class="mb-3"><strong>Owner:</strong> ${b.owner_name}</li>
                                <li class="mb-3"><strong>Email:</strong> ${b.email}</li>
                                <li class="mb-3"><strong>Phone:</strong> ${b.phone}</li>
                                <li class="mb-3"><strong>Hours:</strong> ${b.opening_time} - ${b.closing_time}</li>
                                ${b.gst_number ? `<li class="mb-3"><strong>GST No:</strong> ${b.gst_number}</li>` : ''}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Products -->
            <div class="tab-pane fade" id="products">
                <div class="row g-4">
                    ${products.length > 0 ? products.map(p => `
                        <div class="col-md-4">
                            <div class="glass-card p-4 h-100 d-flex flex-column">
                                <h5 class="fw-bold mb-2">${p.name}</h5>
                                <p class="text-muted small flex-grow-1">${p.description}</p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                    <span class="fs-4 fw-bold text-primary">$${parseFloat(p.price).toFixed(2)}</span>
                                    <span class="badge bg-success-subtle text-success">${p.availability}</span>
                                </div>
                            </div>
                        </div>
                    `).join('') : `<div class="col-12 text-muted py-4 text-center">No products cataloged yet.</div>`}
                </div>
            </div>

            <!-- Services -->
            <div class="tab-pane fade" id="services">
                <div class="row g-4">
                    ${services.length > 0 ? services.map(s => `
                        <div class="col-md-4">
                            <div class="glass-card p-4 h-100 d-flex flex-column">
                                <h5 class="fw-bold mb-2">${s.name}</h5>
                                <p class="text-muted small flex-grow-1">${s.description}</p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                                    <span class="fs-4 fw-bold text-success">$${parseFloat(s.price).toFixed(2)}</span>
                                    <span class="badge bg-info-subtle text-info">${s.duration}</span>
                                </div>
                            </div>
                        </div>
                    `).join('') : `<div class="col-12 text-muted py-4 text-center">No services cataloged yet.</div>`}
                </div>
            </div>

            <!-- Reviews -->
            <div class="tab-pane fade" id="reviews">
                <div class="row g-4">
                    <div class="col-lg-8">
                        ${reviews.length > 0 ? reviews.map(r => `
                            <div class="glass-card p-4 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>${r.customer_name}</strong>
                                    <div class="rating-stars small"><i class="bi bi-star-fill text-warning"></i> ${r.rating} / 5 Stars</div>
                                </div>
                                <p class="text-secondary mb-1">${r.review_text}</p>
                                <small class="text-muted">${r.created_at}</small>
                            </div>
                        `).join('') : `<div class="text-muted py-3">No reviews submitted yet.</div>`}

                        ${req.session.user ? `
                            <div class="glass-card p-4 mt-4">
                                <h5 class="fw-bold mb-3">Write a Customer Review</h5>
                                <form action="/reviews/add" method="POST">
                                    <input type="hidden" name="business_id" value="${b.id}">
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Rating</label>
                                        <select name="rating" class="form-select rounded-3">
                                            <option value="5">5 Stars - Excellent</option>
                                            <option value="4">4 Stars - Very Good</option>
                                            <option value="3">3 Stars - Average</option>
                                            <option value="2">2 Stars - Poor</option>
                                            <option value="1">1 Star - Terrible</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-semibold">Review Comment</label>
                                        <textarea name="review_text" rows="3" class="form-control rounded-3" placeholder="Share your experience..." required></textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary rounded-pill px-4">Submit Review</button>
                                </form>
                            </div>
                        ` : '<div class="alert alert-info rounded-4 mt-4 small"><i class="bi bi-info-circle me-1"></i> Please sign in to submit a review.</div>'}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inquiry Modal -->
    <div class="modal fade" id="inquiryModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content glass-card border-0 p-3">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Inquire with ${b.name}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/inquiries/send" method="POST">
                    <input type="hidden" name="business_id" value="${b.id}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Your Name</label>
                            <input type="text" name="name" class="form-control rounded-3" value="${req.session.user ? req.session.user.name : ''}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3" value="${req.session.user ? req.session.user.email : ''}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-3" value="${req.session.user ? req.session.user.phone : ''}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Subject</label>
                            <input type="text" name="subject" class="form-control rounded-3" placeholder="e.g. Custom Quote / Product Inquiry" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Message</label>
                            <textarea name="message" rows="3" class="form-control rounded-3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Submit Inquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout(b.name, req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

// Products Directory
app.get('/products', (req, res) => {
  const allProducts = db.products.map(p => {
    const biz = db.businesses.find(b => b.id === p.business_id) || {};
    return { ...p, business_name: biz.name || 'Business' };
  });

  const content = `
    <div class="bg-primary-subtle py-4 border-bottom">
        <div class="container">
            <h2 class="fw-bold mb-1">Products Showcase</h2>
            <p class="text-muted mb-0">Discover high quality products from verified suppliers</p>
        </div>
    </div>
    <div class="container py-5">
        <div class="row g-4">
            ${allProducts.map(p => `
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card h-100 p-4 d-flex flex-column">
                        <h5 class="fw-bold mb-2">${p.name}</h5>
                        <p class="text-muted small flex-grow-1">${p.description}</p>
                        <div class="mb-3"><small class="text-muted"><i class="bi bi-shop me-1"></i> ${p.business_name}</small></div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <span class="fs-4 fw-bold text-primary">$${parseFloat(p.price).toFixed(2)}</span>
                            <a href="/businesses/${p.business_id}" class="btn btn-outline-primary rounded-pill btn-sm">Contact Seller</a>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    </div>
  `;
  res.send(renderPageLayout('Products Showcase', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

// Services Directory
app.get('/services', (req, res) => {
  const allServices = db.services.map(s => {
    const biz = db.businesses.find(b => b.id === s.business_id) || {};
    return { ...s, business_name: biz.name || 'Business' };
  });

  const content = `
    <div class="bg-primary-subtle py-4 border-bottom">
        <div class="container">
            <h2 class="fw-bold mb-1">Professional Services</h2>
            <p class="text-muted mb-0">Book accredited professional services</p>
        </div>
    </div>
    <div class="container py-5">
        <div class="row g-4">
            ${allServices.map(s => `
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card h-100 p-4 d-flex flex-column">
                        <h5 class="fw-bold mb-2">${s.name}</h5>
                        <p class="text-muted small flex-grow-1">${s.description}</p>
                        <div class="mb-3"><small class="text-muted"><i class="bi bi-building me-1"></i> ${s.business_name}</small></div>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <span class="fs-4 fw-bold text-success">$${parseFloat(s.price).toFixed(2)}</span>
                            <a href="/businesses/${s.business_id}" class="btn btn-primary rounded-pill btn-sm">Book Service</a>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    </div>
  `;
  res.send(renderPageLayout('Professional Services', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

// Contact Page
app.get('/contact', (req, res) => {
  const content = `
    <div class="container py-5" style="max-width: 800px;">
        <div class="glass-card p-5">
            <div class="text-center mb-4">
                <h2 class="fw-bold mb-2">Contact Platform Support</h2>
                <p class="text-muted">Send us a direct message and our team will respond within 24 hours</p>
            </div>
            <form action="/contact" method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Your Name</label>
                        <input type="text" name="name" class="form-control rounded-3 py-2" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email Address</label>
                        <input type="email" name="email" class="form-control rounded-3 py-2" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Subject</label>
                        <input type="text" name="subject" class="form-control rounded-3 py-2" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small fw-semibold">Message</label>
                        <textarea name="message" rows="4" class="form-control rounded-3" required></textarea>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Send Message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Contact Support', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/contact', (req, res) => {
  req.session.flashMessage = 'Thank you! Your contact message has been sent successfully.';
  res.redirect('/contact');
});

// ====================================================
// AUTHENTICATION ROUTES (LOGIN, REGISTER, LOGOUT)
// ====================================================

// Login Form
app.get('/login', (req, res) => {
  if (req.session.user) {
    if (req.session.user.role === 'admin') return res.redirect('/admin/dashboard');
    if (req.session.user.role === 'business_owner') return res.redirect('/business/dashboard');
    return res.redirect('/customer/dashboard');
  }

  const content = `
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="glass-card p-5">
                    <div class="text-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-inline-flex p-3 mb-2">
                            <i class="bi bi-box-arrow-in-right fs-3"></i>
                        </div>
                        <h3 class="fw-bold mb-1">Welcome Back</h3>
                        <p class="text-muted small">Sign in to your Business Connect account</p>
                    </div>

                    <form action="/login" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2" required placeholder="admin@businessconnect.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control rounded-3 py-2" required placeholder="••••••••">
                        </div>
                        <div class="mb-4 form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="rememberCheck">
                            <label class="form-check-label small" for="rememberCheck">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-semibold mb-3">Sign In</button>
                    </form>

                    <div class="alert alert-light border small text-muted rounded-3 mb-3">
                        <strong>Demo Accounts:</strong><br>
                        • Admin: <code>admin@businessconnect.com</code> / <code>password123</code><br>
                        • Business: <code>alex@nexustech.com</code> / <code>password123</code><br>
                        • Customer: <code>david.customer@gmail.com</code> / <code>password123</code>
                    </div>

                    <div class="text-center border-top pt-3">
                        <small class="text-muted">Don't have an account?</small><br>
                        <a href="/register-business" class="small fw-bold text-primary me-2">Register Business Owner</a> | 
                        <a href="/register-customer" class="small fw-bold text-secondary ms-2">Register Customer</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Sign In', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

// Process Login Form Submission
app.post('/login', (req, res) => {
  const { email, password } = req.body;
  const user = db.users.find(u => u.email.toLowerCase() === (email || '').toLowerCase() && u.password === password);

  if (!user) {
    req.session.flashError = 'Invalid email address or password. Please try again.';
    return res.redirect('/login');
  }

  req.session.user = { id: user.id, name: user.name, email: user.email, role: user.role, phone: user.phone };
  req.session.flashMessage = `Welcome back, ${user.name}!`;

  if (user.role === 'admin') return res.redirect('/admin/dashboard');
  if (user.role === 'business_owner') return res.redirect('/business/dashboard');
  return res.redirect('/customer/dashboard');
});

// Register Business Owner Form
app.get('/register-business', (req, res) => {
  const content = `
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="glass-card p-5">
                    <div class="text-center mb-4">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">Business Owner Account</span>
                        <h3 class="fw-bold mb-1">Promote Your Enterprise</h3>
                        <p class="text-muted small">Create a Business Owner account to list products & services</p>
                    </div>

                    <form action="/register-business" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Full Owner Name</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2" required placeholder="Alex Carter">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Business Name</label>
                            <input type="text" name="business_name" class="form-control rounded-3 py-2" required placeholder="NexusTech Solutions">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2" required placeholder="alex@nexustech.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-3 py-2" required placeholder="+1 (555) 014-7890">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control rounded-3 py-2" required placeholder="••••••••">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 rounded-pill py-2.5 fw-semibold mb-3">Create Business Account</button>
                    </form>

                    <div class="text-center border-top pt-3">
                        <small class="text-muted">Already registered?</small>
                        <a href="/login" class="small fw-bold text-primary ms-1">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Register Business Owner', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/register-business', (req, res) => {
  const { name, business_name, email, phone, password } = req.body;
  if (db.users.find(u => u.email.toLowerCase() === email.toLowerCase())) {
    req.session.flashError = 'Email address is already registered. Please sign in.';
    return res.redirect('/register-business');
  }

  const newUser = { id: db.users.length + 1, name, email, phone, password, role: 'business_owner', status: 'active' };
  db.users.push(newUser);

  const newBiz = {
    id: db.businesses.length + 1,
    user_id: newUser.id,
    name: business_name || `${name} Business`,
    owner_name: name,
    email: email,
    phone: phone,
    category_id: 1,
    category_name: 'Information Technology',
    description: 'New verified business profile on Business Connect.',
    opening_time: '09:00',
    closing_time: '18:00',
    state: 'California',
    city: 'San Francisco',
    address: '100 Business Street',
    pin_code: '90001',
    status: 'approved',
    views_count: 1
  };
  db.businesses.push(newBiz);

  req.session.user = { id: newUser.id, name: newUser.name, email: newUser.email, role: newUser.role, phone: newUser.phone };
  req.session.flashMessage = 'Business Account created successfully! Please complete your profile.';
  res.redirect('/business/dashboard');
});

// Register Customer Form
app.get('/register-customer', (req, res) => {
  const content = `
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="glass-card p-5">
                    <div class="text-center mb-4">
                        <span class="badge bg-secondary-subtle text-secondary rounded-pill px-3 py-2 mb-2">Customer Account</span>
                        <h3 class="fw-bold mb-1">Join Business Connect</h3>
                        <p class="text-muted small">Connect with verified businesses and send direct inquiries</p>
                    </div>

                    <form action="/register-customer" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Full Name</label>
                            <input type="text" name="name" class="form-control rounded-3 py-2" required placeholder="David Miller">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Email Address</label>
                            <input type="email" name="email" class="form-control rounded-3 py-2" required placeholder="david@gmail.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Phone Number</label>
                            <input type="text" name="phone" class="form-control rounded-3 py-2" required placeholder="+1 (555) 019-2834">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control rounded-3 py-2" required placeholder="••••••••">
                        </div>
                        <button type="submit" class="btn btn-secondary text-white w-100 rounded-pill py-2.5 fw-semibold mb-3">Create Customer Account</button>
                    </form>

                    <div class="text-center border-top pt-3">
                        <small class="text-muted">Already registered?</small>
                        <a href="/login" class="small fw-bold text-primary ms-1">Sign In</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Register Customer Account', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/register-customer', (req, res) => {
  const { name, email, phone, password } = req.body;
  if (db.users.find(u => u.email.toLowerCase() === email.toLowerCase())) {
    req.session.flashError = 'Email address is already registered. Please sign in.';
    return res.redirect('/register-customer');
  }

  const newUser = { id: db.users.length + 1, name, email, phone, password, role: 'customer', status: 'active' };
  db.users.push(newUser);

  req.session.user = { id: newUser.id, name: newUser.name, email: newUser.email, role: newUser.role, phone: newUser.phone };
  req.session.flashMessage = 'Welcome to Business Connect! Your customer account has been created.';
  res.redirect('/customer/dashboard');
});

// Logout Route
app.get('/logout', (req, res) => {
  req.session.destroy();
  res.redirect('/login');
});

// ====================================================
// ADMIN MODULE (ADMIN ROLE ONLY)
// ====================================================

app.get('/admin/dashboard', requireRole('admin'), (req, res) => {
  const pendingApps = db.businesses.filter(b => b.status === 'pending');
  const approvedApps = db.businesses.filter(b => b.status === 'approved');

  const content = `
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-2">
                <div class="sidebar p-3 glass-card rounded-4">
                    <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Admin Panel</h6>
                    <div class="nav flex-column">
                        <a href="/admin/dashboard" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                        <a href="/admin/businesses" class="nav-link"><i class="bi bi-building me-2"></i> Businesses</a>
                        <a href="/admin/users" class="nav-link"><i class="bi bi-people me-2"></i> Users</a>
                        <a href="/admin/categories" class="nav-link"><i class="bi bi-tags me-2"></i> Categories</a>
                        <a href="/admin/reports" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-0">System Analytics Dashboard</h3>
                        <p class="text-muted small mb-0">System metrics, business approvals, and customer growth analytics</p>
                    </div>
                    <a href="/admin/reports/export" class="btn btn-outline-primary rounded-pill btn-sm px-3"><i class="bi bi-download me-1"></i> Export Platform CSV</a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3"><div class="glass-card p-3"><h4 class="fw-bold mb-0 text-primary">${db.businesses.length}</h4><small class="text-muted">Total Businesses</small></div></div>
                    <div class="col-md-3"><div class="glass-card p-3"><h4 class="fw-bold mb-0 text-success">${db.users.filter(u => u.role === 'customer').length}</h4><small class="text-muted">Total Customers</small></div></div>
                    <div class="col-md-3"><div class="glass-card p-3"><h4 class="fw-bold mb-0 text-warning">${pendingApps.length}</h4><small class="text-muted">Pending Approvals</small></div></div>
                    <div class="col-md-3"><div class="glass-card p-3"><h4 class="fw-bold mb-0 text-info">${db.products.length}</h4><small class="text-muted">Listed Products</small></div></div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-lg-8"><div class="glass-card p-4"><h5 class="fw-bold mb-3">Business Growth Visualizer</h5><canvas id="growthChart" height="110"></canvas></div></div>
                    <div class="col-lg-4"><div class="glass-card p-4"><h5 class="fw-bold mb-3">Sector Distribution</h5><canvas id="categoryDistributionChart" height="200"></canvas></div></div>
                </div>

                <div class="glass-card p-4">
                    <h5 class="fw-bold mb-3">Business Approval Workflow</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light"><tr><th>Business Name</th><th>Owner</th><th>City</th><th>Status</th><th>Action</th></tr></thead>
                            <tbody>
                                ${db.businesses.map(b => `
                                    <tr>
                                        <td class="fw-semibold">${b.name}</td>
                                        <td>${b.owner_name}</td>
                                        <td>${b.city}</td>
                                        <td><span class="badge ${b.status === 'approved' ? 'bg-success' : 'bg-warning text-dark'}">${b.status}</span></td>
                                        <td>
                                            ${b.status === 'pending' ? `
                                                <form action="/admin/business/approve/${b.id}" method="POST" class="d-inline">
                                                    <button type="submit" class="btn btn-sm btn-success rounded-circle me-1" title="Approve"><i class="bi bi-check"></i></button>
                                                </form>
                                                <form action="/admin/business/reject/${b.id}" method="POST" class="d-inline">
                                                    <button type="submit" class="btn btn-sm btn-warning rounded-circle me-1" title="Reject"><i class="bi bi-x"></i></button>
                                                </form>
                                            ` : ''}
                                            <form action="/admin/business/delete/${b.id}" method="POST" class="d-inline">
                                                <button type="submit" class="btn btn-sm btn-outline-danger confirm-delete-btn"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Admin Dashboard', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/admin/business/approve/:id', requireRole('admin'), (req, res) => {
  const b = db.businesses.find(x => x.id === parseInt(req.params.id));
  if (b) b.status = 'approved';
  req.session.flashMessage = 'Business has been approved!';
  res.redirect('/admin/dashboard');
});

app.post('/admin/business/reject/:id', requireRole('admin'), (req, res) => {
  const b = db.businesses.find(x => x.id === parseInt(req.params.id));
  if (b) b.status = 'rejected';
  req.session.flashMessage = 'Business has been rejected.';
  res.redirect('/admin/dashboard');
});

app.post('/admin/business/delete/:id', requireRole('admin'), (req, res) => {
  db.businesses = db.businesses.filter(x => x.id !== parseInt(req.params.id));
  req.session.flashMessage = 'Business deleted.';
  res.redirect('/admin/dashboard');
});

app.get('/admin/businesses', requireRole('admin'), (req, res) => {
  res.redirect('/admin/dashboard');
});

app.get('/admin/users', requireRole('admin'), (req, res) => {
  const content = `
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-2">
                <div class="sidebar p-3 glass-card rounded-4">
                    <div class="nav flex-column">
                        <a href="/admin/dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                        <a href="/admin/users" class="nav-link active"><i class="bi bi-people me-2"></i> Users</a>
                        <a href="/admin/categories" class="nav-link"><i class="bi bi-tags me-2"></i> Categories</a>
                        <a href="/admin/reports" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <h3 class="fw-bold mb-4">User Accounts Management</h3>
                <div class="glass-card p-4">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Status</th><th>Actions</th></tr></thead>
                        <tbody>
                            ${db.users.map(u => `
                                <tr>
                                    <td class="fw-semibold">${u.name}</td>
                                    <td>${u.email}</td>
                                    <td>${u.phone}</td>
                                    <td><span class="badge bg-primary-subtle text-primary">${u.role}</span></td>
                                    <td><span class="badge bg-success">${u.status}</span></td>
                                    <td>
                                        <form action="/admin/users/delete/${u.id}" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-outline-danger confirm-delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Manage Users', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/admin/users/delete/:id', requireRole('admin'), (req, res) => {
  db.users = db.users.filter(u => u.id !== parseInt(req.params.id));
  req.session.flashMessage = 'User account deleted.';
  res.redirect('/admin/users');
});

app.get('/admin/categories', requireRole('admin'), (req, res) => {
  const content = `
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-2">
                <div class="sidebar p-3 glass-card rounded-4">
                    <div class="nav flex-column">
                        <a href="/admin/dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                        <a href="/admin/users" class="nav-link"><i class="bi bi-people me-2"></i> Users</a>
                        <a href="/admin/categories" class="nav-link active"><i class="bi bi-tags me-2"></i> Categories</a>
                        <a href="/admin/reports" class="nav-link"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">Business Categories</h3>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addCatModal"><i class="bi bi-plus-lg me-1"></i> Add Category</button>
                </div>
                <div class="glass-card p-4">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Icon</th><th>Category Name</th><th>Slug</th><th>Description</th></tr></thead>
                        <tbody>
                            ${db.categories.map(c => `
                                <tr>
                                    <td><i class="bi ${c.icon} fs-4 text-primary"></i></td>
                                    <td class="fw-semibold">${c.name}</td>
                                    <td><code>${c.slug}</code></td>
                                    <td class="small text-muted">${c.description}</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Category -->
    <div class="modal fade" id="addCatModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content glass-card p-3 border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Add Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/admin/categories/add" method="POST">
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label small fw-semibold">Name</label><input type="text" name="name" class="form-control rounded-3" required></div>
                        <div class="mb-3"><label class="form-label small fw-semibold">Icon Class</label><input type="text" name="icon" class="form-control rounded-3" placeholder="bi-shop"></div>
                        <div class="mb-3"><label class="form-label small fw-semibold">Description</label><textarea name="description" rows="2" class="form-control rounded-3"></textarea></div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Save Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Categories Manager', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/admin/categories/add', requireRole('admin'), (req, res) => {
  const { name, icon, description } = req.body;
  const newCat = {
    id: db.categories.length + 1,
    name,
    slug: name.toLowerCase().replace(/[^a-z0-9]+/g, '-'),
    icon: icon || 'bi-shop',
    description: description || ''
  };
  db.categories.push(newCat);
  req.session.flashMessage = 'New category added!';
  res.redirect('/admin/categories');
});

app.get('/admin/reports', requireRole('admin'), (req, res) => {
  const content = `
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-2">
                <div class="sidebar p-3 glass-card rounded-4">
                    <div class="nav flex-column">
                        <a href="/admin/dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                        <a href="/admin/reports" class="nav-link active"><i class="bi bi-file-earmark-bar-graph me-2"></i> Reports</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <h3 class="fw-bold mb-4">Platform Data Reports</h3>
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="glass-card p-4">
                            <h5 class="fw-bold mb-3"><i class="bi bi-file-earmark-excel text-success me-2"></i>Export Business Records</h5>
                            <p class="text-muted small">Download CSV file with all registered businesses details.</p>
                            <a href="/admin/reports/export" class="btn btn-success rounded-pill px-4"><i class="bi bi-download me-1"></i> Download Businesses CSV</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Reports Export', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.get('/admin/reports/export', requireRole('admin'), (req, res) => {
  let csv = "ID,Business Name,Owner,Email,Phone,City,Status\n";
  db.businesses.forEach(b => {
    csv += `"${b.id}","${b.name}","${b.owner_name}","${b.email}","${b.phone}","${b.city}","${b.status}"\n`;
  });
  res.setHeader('Content-Type', 'text/csv');
  res.setHeader('Content-Disposition', 'attachment; filename=business_connect_report.csv');
  res.status(200).send(csv);
});

// ====================================================
// BUSINESS OWNER PORTAL (BUSINESS_OWNER ROLE ONLY)
// ====================================================

app.get('/business/dashboard', requireRole('business_owner', 'admin'), (req, res) => {
  const user = req.session.user;
  const myBiz = db.businesses.find(b => b.user_id === user.id) || db.businesses[0];

  const myProducts = db.products.filter(p => p.business_id === myBiz.id);
  const myInquiries = db.inquiries.filter(i => i.business_id === myBiz.id);
  const myReviews = db.reviews.filter(r => r.business_id === myBiz.id);

  const content = `
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-2">
                <div class="sidebar p-3 glass-card rounded-4">
                    <h6 class="text-uppercase text-muted fw-bold small px-3 mb-3">Business Portal</h6>
                    <div class="nav flex-column">
                        <a href="/business/dashboard" class="nav-link active"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                        <a href="/business/profile" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Business Profile</a>
                        <a href="/business/products" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a>
                        <a href="/business/services" class="nav-link"><i class="bi bi-tools me-2"></i> Services</a>
                        <a href="/business/inquiries" class="nav-link"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h3 class="fw-bold mb-0">Welcome, ${myBiz.name}</h3>
                        <p class="text-muted small mb-0">Track engagement, manage offerings, and respond to inquiries</p>
                    </div>
                    <a href="/business/profile" class="btn btn-primary rounded-pill px-4"><i class="bi bi-pencil me-1"></i> Edit Profile</a>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-3"><div class="glass-card p-3"><h4 class="fw-bold mb-0 text-primary">${myBiz.views_count}</h4><small class="text-muted">Total Page Views</small></div></div>
                    <div class="col-md-3"><div class="glass-card p-3"><h4 class="fw-bold mb-0 text-success">${myInquiries.length}</h4><small class="text-muted">Customer Inquiries</small></div></div>
                    <div class="col-md-3"><div class="glass-card p-3"><h4 class="fw-bold mb-0 text-warning">${getAverageRating(myBiz.id)}</h4><small class="text-muted">Average Rating</small></div></div>
                    <div class="col-md-3"><div class="glass-card p-3"><h4 class="fw-bold mb-0 text-info">${myProducts.length}</h4><small class="text-muted">Active Products</small></div></div>
                </div>

                <div class="glass-card p-4">
                    <h5 class="fw-bold mb-3">Customer Inquiries Inbox</h5>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light"><tr><th>Customer</th><th>Subject</th><th>Status</th><th>Action</th></tr></thead>
                            <tbody>
                                ${myInquiries.length > 0 ? myInquiries.map(inq => `
                                    <tr>
                                        <td class="fw-semibold">${inq.name}</td>
                                        <td>${inq.subject}</td>
                                        <td><span class="badge ${inq.status === 'replied' ? 'bg-success' : 'bg-warning text-dark'}">${inq.status}</span></td>
                                        <td><a href="/business/inquiries" class="btn btn-sm btn-outline-primary rounded-pill">View & Reply</a></td>
                                    </tr>
                                `).join('') : `<tr><td colspan="4" class="text-center text-muted">No inquiries received yet.</td></tr>`}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Business Owner Portal', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

// Profile Editor
app.get('/business/profile', requireRole('business_owner', 'admin'), (req, res) => {
  const user = req.session.user;
  const myBiz = db.businesses.find(b => b.user_id === user.id) || db.businesses[0];

  const content = `
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-2">
                <div class="sidebar p-3 glass-card rounded-4">
                    <div class="nav flex-column">
                        <a href="/business/dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                        <a href="/business/profile" class="nav-link active"><i class="bi bi-person-lines-fill me-2"></i> Profile</a>
                        <a href="/business/products" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a>
                        <a href="/business/services" class="nav-link"><i class="bi bi-tools me-2"></i> Services</a>
                        <a href="/business/inquiries" class="nav-link"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <h3 class="fw-bold mb-4">Manage Business Profile</h3>
                <form action="/business/profile/update" method="POST">
                    <div class="glass-card p-4 mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Business Information</h5>
                        <div class="row g-3">
                            <div class="col-md-6"><label class="form-label small fw-semibold">Business Name</label><input type="text" name="name" class="form-control rounded-3" value="${myBiz.name}" required></div>
                            <div class="col-md-6"><label class="form-label small fw-semibold">Owner Name</label><input type="text" name="owner_name" class="form-control rounded-3" value="${myBiz.owner_name}" required></div>
                            <div class="col-md-6"><label class="form-label small fw-semibold">Email</label><input type="email" name="email" class="form-control rounded-3" value="${myBiz.email}" required></div>
                            <div class="col-md-6"><label class="form-label small fw-semibold">Phone</label><input type="text" name="phone" class="form-control rounded-3" value="${myBiz.phone}" required></div>
                            <div class="col-md-6"><label class="form-label small fw-semibold">GST Number</label><input type="text" name="gst_number" class="form-control rounded-3" value="${myBiz.gst_number || ''}"></div>
                            <div class="col-md-6"><label class="form-label small fw-semibold">Website</label><input type="text" name="website" class="form-control rounded-3" value="${myBiz.website || ''}"></div>
                            <div class="col-12"><label class="form-label small fw-semibold">Description</label><textarea name="description" rows="3" class="form-control rounded-3" required>${myBiz.description}</textarea></div>
                        </div>
                    </div>
                    <div class="glass-card p-4 mb-4">
                        <h5 class="fw-bold mb-3 border-bottom pb-2">Location & Timings</h5>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label small fw-semibold">City</label><input type="text" name="city" class="form-control rounded-3" value="${myBiz.city}" required></div>
                            <div class="col-md-4"><label class="form-label small fw-semibold">State</label><input type="text" name="state" class="form-control rounded-3" value="${myBiz.state}" required></div>
                            <div class="col-md-4"><label class="form-label small fw-semibold">PIN Code</label><input type="text" name="pin_code" class="form-control rounded-3" value="${myBiz.pin_code}" required></div>
                            <div class="col-12"><label class="form-label small fw-semibold">Address</label><input type="text" name="address" class="form-control rounded-3" value="${myBiz.address}" required></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary btn-lg rounded-pill px-5">Save Profile Changes</button>
                </form>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Edit Business Profile', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/business/profile/update', requireRole('business_owner', 'admin'), (req, res) => {
  const user = req.session.user;
  const myBiz = db.businesses.find(b => b.user_id === user.id) || db.businesses[0];
  const { name, owner_name, email, phone, gst_number, website, description, city, state, pin_code, address } = req.body;

  myBiz.name = name;
  myBiz.owner_name = owner_name;
  myBiz.email = email;
  myBiz.phone = phone;
  myBiz.gst_number = gst_number;
  myBiz.website = website;
  myBiz.description = description;
  myBiz.city = city;
  myBiz.state = state;
  myBiz.pin_code = pin_code;
  myBiz.address = address;

  req.session.flashMessage = 'Business profile details updated successfully!';
  res.redirect('/business/dashboard');
});

// Business Products CRUD
app.get('/business/products', requireRole('business_owner', 'admin'), (req, res) => {
  const user = req.session.user;
  const myBiz = db.businesses.find(b => b.user_id === user.id) || db.businesses[0];
  const myProducts = db.products.filter(p => p.business_id === myBiz.id);

  const content = `
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-2">
                <div class="sidebar p-3 glass-card rounded-4">
                    <div class="nav flex-column">
                        <a href="/business/dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                        <a href="/business/profile" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Profile</a>
                        <a href="/business/products" class="nav-link active"><i class="bi bi-box-seam me-2"></i> Products</a>
                        <a href="/business/services" class="nav-link"><i class="bi bi-tools me-2"></i> Services</a>
                        <a href="/business/inquiries" class="nav-link"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">Products Catalog</h3>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addProductModal"><i class="bi bi-plus-lg me-1"></i> Add Product</button>
                </div>
                <div class="glass-card p-4">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Product Name</th><th>Price</th><th>Stock</th><th>Availability</th><th>Actions</th></tr></thead>
                        <tbody>
                            ${myProducts.length > 0 ? myProducts.map(p => `
                                <tr>
                                    <td class="fw-semibold">${p.name}</td>
                                    <td class="text-primary fw-bold">$${parseFloat(p.price).toFixed(2)}</td>
                                    <td>${p.stock}</td>
                                    <td><span class="badge bg-success-subtle text-success">${p.availability}</span></td>
                                    <td>
                                        <form action="/business/products/delete/${p.id}" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-outline-danger confirm-delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            `).join('') : `<tr><td colspan="5" class="text-center text-muted">No products added yet. Click Add Product to start.</td></tr>`}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Product -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content glass-card p-3 border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/business/products/add" method="POST">
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label small fw-semibold">Product Name</label><input type="text" name="name" class="form-control rounded-3" required></div>
                        <div class="mb-3"><label class="form-label small fw-semibold">Description</label><textarea name="description" rows="3" class="form-control rounded-3" required></textarea></div>
                        <div class="row g-2 mb-3">
                            <div class="col-6"><label class="form-label small fw-semibold">Price ($)</label><input type="number" step="0.01" name="price" class="form-control rounded-3" required></div>
                            <div class="col-6"><label class="form-label small fw-semibold">Stock</label><input type="number" name="stock" value="10" class="form-control rounded-3" required></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Create Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Products Catalog', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/business/products/add', requireRole('business_owner', 'admin'), (req, res) => {
  const user = req.session.user;
  const myBiz = db.businesses.find(b => b.user_id === user.id) || db.businesses[0];
  const { name, description, price, stock } = req.body;

  const newProd = {
    id: db.products.length + 1,
    business_id: myBiz.id,
    name,
    description,
    price: parseFloat(price),
    discount: 0,
    stock: parseInt(stock) || 10,
    availability: 'in_stock',
    status: 'active'
  };
  db.products.push(newProd);

  req.session.flashMessage = 'Product added successfully!';
  res.redirect('/business/products');
});

app.post('/business/products/delete/:id', requireRole('business_owner', 'admin'), (req, res) => {
  db.products = db.products.filter(p => p.id !== parseInt(req.params.id));
  req.session.flashMessage = 'Product deleted.';
  res.redirect('/business/products');
});

// Business Services CRUD
app.get('/business/services', requireRole('business_owner', 'admin'), (req, res) => {
  const user = req.session.user;
  const myBiz = db.businesses.find(b => b.user_id === user.id) || db.businesses[0];
  const myServices = db.services.filter(s => s.business_id === myBiz.id);

  const content = `
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-2">
                <div class="sidebar p-3 glass-card rounded-4">
                    <div class="nav flex-column">
                        <a href="/business/dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                        <a href="/business/profile" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Profile</a>
                        <a href="/business/products" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a>
                        <a href="/business/services" class="nav-link active"><i class="bi bi-tools me-2"></i> Services</a>
                        <a href="/business/inquiries" class="nav-link"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">Services Catalog</h3>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#addServiceModal"><i class="bi bi-plus-lg me-1"></i> Add Service</button>
                </div>
                <div class="glass-card p-4">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Service Name</th><th>Price</th><th>Duration</th><th>Availability</th><th>Actions</th></tr></thead>
                        <tbody>
                            ${myServices.length > 0 ? myServices.map(s => `
                                <tr>
                                    <td class="fw-semibold">${s.name}</td>
                                    <td class="text-success fw-bold">$${parseFloat(s.price).toFixed(2)}</td>
                                    <td>${s.duration}</td>
                                    <td><span class="badge bg-success-subtle text-success">${s.availability}</span></td>
                                    <td>
                                        <form action="/business/services/delete/${s.id}" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-outline-danger confirm-delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            `).join('') : `<tr><td colspan="5" class="text-center text-muted">No services added yet. Click Add Service to start.</td></tr>`}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Add Service -->
    <div class="modal fade" id="addServiceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content glass-card p-3 border-0">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="/business/services/add" method="POST">
                    <div class="modal-body">
                        <div class="mb-3"><label class="form-label small fw-semibold">Service Name</label><input type="text" name="name" class="form-control rounded-3" required></div>
                        <div class="mb-3"><label class="form-label small fw-semibold">Description</label><textarea name="description" rows="3" class="form-control rounded-3" required></textarea></div>
                        <div class="row g-2 mb-3">
                            <div class="col-6"><label class="form-label small fw-semibold">Price ($)</label><input type="number" step="0.01" name="price" class="form-control rounded-3" required></div>
                            <div class="col-6"><label class="form-label small fw-semibold">Duration</label><input type="text" name="duration" class="form-control rounded-3" placeholder="1 Hour, 2 Days"></div>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary rounded-pill px-4">Create Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Services Catalog', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/business/services/add', requireRole('business_owner', 'admin'), (req, res) => {
  const user = req.session.user;
  const myBiz = db.businesses.find(b => b.user_id === user.id) || db.businesses[0];
  const { name, description, price, duration } = req.body;

  const newServ = {
    id: db.services.length + 1,
    business_id: myBiz.id,
    name,
    description,
    price: parseFloat(price),
    duration: duration || '1 Hour',
    availability: 'available'
  };
  db.services.push(newServ);

  req.session.flashMessage = 'Service added successfully!';
  res.redirect('/business/services');
});

app.post('/business/services/delete/:id', requireRole('business_owner', 'admin'), (req, res) => {
  db.services = db.services.filter(s => s.id !== parseInt(req.params.id));
  req.session.flashMessage = 'Service deleted.';
  res.redirect('/business/services');
});

// Business Inquiries Inbox
app.get('/business/inquiries', requireRole('business_owner', 'admin'), (req, res) => {
  const user = req.session.user;
  const myBiz = db.businesses.find(b => b.user_id === user.id) || db.businesses[0];
  const myInquiries = db.inquiries.filter(i => i.business_id === myBiz.id);

  const content = `
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-2">
                <div class="sidebar p-3 glass-card rounded-4">
                    <div class="nav flex-column">
                        <a href="/business/dashboard" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                        <a href="/business/profile" class="nav-link"><i class="bi bi-person-lines-fill me-2"></i> Profile</a>
                        <a href="/business/products" class="nav-link"><i class="bi bi-box-seam me-2"></i> Products</a>
                        <a href="/business/services" class="nav-link"><i class="bi bi-tools me-2"></i> Services</a>
                        <a href="/business/inquiries" class="nav-link active"><i class="bi bi-envelope me-2"></i> Inquiries</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-10">
                <h3 class="fw-bold mb-4">Customer Inquiries Inbox</h3>
                <div class="glass-card p-4">
                    <table class="table align-middle mb-0">
                        <thead class="table-light"><tr><th>Customer</th><th>Email / Phone</th><th>Subject</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            ${myInquiries.length > 0 ? myInquiries.map(inq => `
                                <tr>
                                    <td class="fw-semibold">${inq.name}</td>
                                    <td class="small">${inq.email}<br>${inq.phone}</td>
                                    <td>${inq.subject}</td>
                                    <td><span class="badge ${inq.status === 'replied' ? 'bg-success' : 'bg-warning text-dark'}">${inq.status}</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-primary rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#replyModal${inq.id}">View & Reply</button>
                                        
                                        <!-- Reply Modal -->
                                        <div class="modal fade" id="replyModal${inq.id}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content glass-card p-3 border-0">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title fw-bold">Inquiry from ${inq.name}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <form action="/business/inquiries/reply/${inq.id}" method="POST">
                                                        <div class="modal-body">
                                                            <p class="small text-muted mb-1"><strong>Subject:</strong> ${inq.subject}</p>
                                                            <div class="p-3 bg-body-tertiary rounded-3 mb-3 small">${inq.message}</div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-semibold">Your Response</label>
                                                                <textarea name="reply_message" rows="3" class="form-control rounded-3" required>${inq.reply_message || ''}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="submit" class="btn btn-success rounded-pill px-4">Send Reply</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `).join('') : `<tr><td colspan="5" class="text-center text-muted">No inquiries received yet.</td></tr>`}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Inquiries Inbox', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.post('/business/inquiries/reply/:id', requireRole('business_owner', 'admin'), (req, res) => {
  const inq = db.inquiries.find(x => x.id === parseInt(req.params.id));
  if (inq) {
    inq.reply_message = req.body.reply_message;
    inq.status = 'replied';
  }
  req.session.flashMessage = 'Reply sent to customer!';
  res.redirect('/business/inquiries');
});

// ====================================================
// CUSTOMER PORTAL (CUSTOMER ROLE ONLY)
// ====================================================

app.get('/customer/dashboard', requireRole('customer', 'admin'), (req, res) => {
  const user = req.session.user;
  const myFavs = db.favorites.filter(f => f.customer_id === user.id);
  const myInquiries = db.inquiries.filter(i => i.customer_id === user.id);

  const content = `
    <div class="container py-4">
        <h3 class="fw-bold mb-4">Customer Dashboard</h3>
        <div class="row g-4 mb-4">
            <div class="col-md-6"><div class="glass-card p-4 text-center"><h4 class="fw-bold mb-0 text-danger">${myFavs.length}</h4><small class="text-muted">Saved Favorite Businesses</small></div></div>
            <div class="col-md-6"><div class="glass-card p-4 text-center"><h4 class="fw-bold mb-0 text-primary">${myInquiries.length}</h4><small class="text-muted">Inquiries Submitted</small></div></div>
        </div>

        <div class="row g-4">
            <div class="col-lg-6">
                <div class="glass-card p-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0">Saved Favorites</h5>
                        <a href="/customer/favorites" class="btn btn-sm btn-outline-primary rounded-pill">View All</a>
                    </div>
                    <div class="list-group list-group-flush">
                        ${myFavs.map(f => {
                          const biz = db.businesses.find(b => b.id === f.business_id) || {};
                          return `<a href="/businesses/${biz.id}" class="list-group-item list-group-item-action bg-transparent px-0 py-2"><strong>${biz.name}</strong> <small class="text-muted">(${biz.city})</small></a>`;
                        }).join('')}
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="glass-card p-4 h-100">
                    <h5 class="fw-bold mb-3">Recent Inquiries & Replies</h5>
                    <div class="list-group list-group-flush">
                        ${myInquiries.map(i => {
                          const biz = db.businesses.find(b => b.id === i.business_id) || {};
                          return `
                            <div class="list-group-item bg-transparent border-0 px-0 py-2">
                                <div class="d-flex justify-content-between"><strong class="small">${biz.name}</strong><span class="badge ${i.status === 'replied' ? 'bg-success' : 'bg-warning text-dark'}">${i.status}</span></div>
                                <small class="text-muted d-block">${i.subject}</small>
                                ${i.reply_message ? `<div class="p-2 bg-success-subtle rounded-3 small mt-1">Reply: ${i.reply_message}</div>` : ''}
                            </div>
                          `;
                        }).join('')}
                    </div>
                </div>
            </div>
        </div>
    </div>
  `;
  res.send(renderPageLayout('Customer Portal', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

app.get('/customer/favorites', requireRole('customer', 'admin'), (req, res) => {
  const user = req.session.user;
  const myFavs = db.favorites.filter(f => f.customer_id === user.id);

  const content = `
    <div class="container py-4">
        <h3 class="fw-bold mb-4">My Saved Favorites</h3>
        <div class="row g-4">
            ${myFavs.map(f => {
              const b = db.businesses.find(x => x.id === f.business_id);
              if (!b) return '';
              return `
                <div class="col-md-6 col-lg-4">
                    <div class="glass-card p-4 h-100 d-flex flex-column">
                        <h5 class="fw-bold mb-1">${b.name}</h5>
                        <p class="text-muted small">${b.description.substring(0, 90)}...</p>
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-auto">
                            <a href="/businesses/${b.id}" class="btn btn-primary rounded-pill btn-sm">View Profile</a>
                        </div>
                    </div>
                </div>
              `;
            }).join('')}
        </div>
    </div>
  `;
  res.send(renderPageLayout('Saved Favorites', req.session.user, res.locals.flashMessage, res.locals.flashError, content));
});

// Toggle Favorite Handler
app.post('/favorite/toggle/:businessId', requireAuth, (req, res) => {
  const bizId = parseInt(req.params.businessId);
  const userId = req.session.user.id;
  const existingIndex = db.favorites.findIndex(f => f.customer_id === userId && f.business_id === bizId);

  if (existingIndex >= 0) {
    db.favorites.splice(existingIndex, 1);
    req.session.flashMessage = 'Removed from favorites.';
  } else {
    db.favorites.push({ id: db.favorites.length + 1, customer_id: userId, business_id: bizId });
    req.session.flashMessage = 'Added to favorites!';
  }
  res.redirect(`/businesses/${bizId}`);
});

// Submit Inquiry Handler
app.post('/inquiries/send', (req, res) => {
  const { business_id, name, email, phone, subject, message } = req.body;
  const newInq = {
    id: db.inquiries.length + 1,
    customer_id: req.session.user ? req.session.user.id : null,
    business_id: parseInt(business_id),
    name,
    email,
    phone,
    subject,
    message,
    status: 'pending',
    reply_message: null
  };
  db.inquiries.push(newInq);
  req.session.flashMessage = 'Inquiry sent successfully! The business owner will get back to you shortly.';
  res.redirect(`/businesses/${business_id}`);
});

// Add Review Handler
app.post('/reviews/add', requireAuth, (req, res) => {
  const { business_id, rating, review_text } = req.body;
  const newRev = {
    id: db.reviews.length + 1,
    customer_id: req.session.user.id,
    customer_name: req.session.user.name,
    business_id: parseInt(business_id),
    rating: parseInt(rating),
    review_text,
    created_at: new Date().toISOString().split('T')[0]
  };
  db.reviews.push(newRev);
  req.session.flashMessage = 'Thank you for submitting your review!';
  res.redirect(`/businesses/${business_id}`);
});

// Search Autosuggest API
app.get('/api/search/suggestions', (req, res) => {
  const term = (req.query.term || '').toLowerCase();
  const matches = db.businesses.filter(b => b.status === 'approved' && b.name.toLowerCase().includes(term));
  res.json({ businesses: matches });
});

app.listen(PORT, () => {
  console.log(`\n===================================================`);
  console.log(` Business Connect Stateful Live Server Active!`);
  console.log(` Access URL: http://localhost:${PORT}`);
  console.log(`===================================================\n`);
});
