const express = require('express');
const session = require('express-session');
const multer = require('multer');
const path = require('path');
const fs = require('fs');

const app = express();
const upload = multer({ storage: multer.memoryStorage() });

// Get project root (parent of api/)
const projectRoot = path.join(__dirname, '..');

// Configure session
app.use(session({
  secret: 'kichai-secret-key',
  resave: false,
  saveUninitialized: true,
  cookie: { 
    httpOnly: true,
    secure: process.env.NODE_ENV === 'production',
    sameSite: 'lax',
    maxAge: 1000 * 60 * 60 * 24 // 24 hours
  }
}));

// Middleware to serve PHP files as HTML (strips PHP code, fixes redirects)
const servePhpAsHtml = (req, res, next) => {
  const originalSendFile = res.sendFile;
  res.sendFile = function(filepath, options, callback) {
    if (filepath.endsWith('.php')) {
      fs.readFile(filepath, 'utf8', (err, data) => {
        if (err) return res.status(404).send('Not found');
        // Strip PHP tags and code, keep only HTML
        let html = data.replace(/<\?php[\s\S]*?\?>/g, '');
        
        // Replace localhost redirects with proper URLs
        html = html.replace(/http:\/\/localhost:3000/g, '');
        html = html.replace(/href="([^"]*)\s+/g, 'href="$1" ');
        
        res.set('Content-Type', 'text/html; charset=utf-8');
        res.send(html);
      });
    } else {
      originalSendFile.call(this, filepath, options, callback);
    }
  };
  next();
};

app.use(servePhpAsHtml);

// Middleware
app.use(express.urlencoded({ extended: true, limit: '50mb' }));
app.use(express.json({ limit: '50mb' }));

// Serve static files from all directories
app.use(express.static(projectRoot, { index: false }));

// ======================== Routes ========================

// Root landing page
app.get('/', (req, res) => {
  res.sendFile(path.join(projectRoot, 'index.php'));
});

// ======================== USER SIGNUP ========================
// Step 1: User Sign Up Page 1
app.get('/user-signup-1', (req, res) => {
  res.sendFile(path.join(projectRoot, 'user-SignUp Page-1', 'index.php'));
});

app.post('/user-signup-1', (req, res) => {
  req.session.user = {
    firstName: req.body['First-name'],
    lastName: req.body['Last-name'],
    email: req.body['E-mail'],
    phone: req.body['Phone-number'],
    password: req.body.password,
    retypePassword: req.body['retype-password'],
    city: req.body.City,
    postCode: req.body['Post-Code']
  };
  res.redirect('/user-signup-2');
});

// Step 2: User Sign Up Page 2
app.get('/user-signup-2', (req, res) => {
  res.sendFile(path.join(projectRoot, 'User-SignUp page-2', 'index2.php'));
});

app.post('/user-signup-2', upload.single('Vendor-image'), (req, res) => {
  req.session.user = req.session.user || {};
  req.session.user.image = req.file ? req.file.buffer.toString('base64') : null;
  req.session.user.about = req.body.about;
  res.redirect('/user-otp');
});

// OTP Page
app.get('/user-otp', (req, res) => {
  res.sendFile(path.join(projectRoot, 'OTP user', 'index4.php'));
});

app.post('/user-otp', (req, res) => {
  req.session.user = req.session.user || {};
  req.session.user.otp = req.body.otp;
  // After OTP verification, redirect to dashboard
  res.redirect('/user-dashboard-1');
});

// ======================== VENDOR SIGNUP ========================
// Step 1: Vendor Sign Up Page 1
app.get('/vendor-signup-1', (req, res) => {
  res.sendFile(path.join(projectRoot, 'Vendor-SignUp Page-1', 'index.php'));
});

app.post('/vendor-signup-1', (req, res) => {
  req.session.vendor = {
    firstName: req.body['First-name'],
    lastName: req.body['Last-name'],
    email: req.body['E-mail'],
    phone: req.body['Phone-number'],
    password: req.body.password,
    retypePassword: req.body['retype-password'],
    city: req.body.City,
    postCode: req.body['Post-Code']
  };
  res.redirect('/vendor-signup-2');
});

// Step 2: Vendor Sign Up Page 2
app.get('/vendor-signup-2', (req, res) => {
  res.sendFile(path.join(projectRoot, 'Vendor-SignUp page-2', 'index2.php'));
});

app.post('/vendor-signup-2', upload.single('Vendor-image'), (req, res) => {
  req.session.vendor = req.session.vendor || {};
  req.session.vendor.image = req.file ? req.file.buffer.toString('base64') : null;
  req.session.vendor.about = req.body.about;
  res.redirect('/vendor-signup-3');
});

// Step 3: Vendor Sign Up Page 3
app.get('/vendor-signup-3', (req, res) => {
  res.sendFile(path.join(projectRoot, 'Vendor-SignUp Page-3', 'index5.php'));
});

app.post('/vendor-signup-3', (req, res) => {
  req.session.vendor = req.session.vendor || {};
  req.session.vendor.businessName = req.body['Business-Name'];
  req.session.vendor.businessLicense = req.body['Business-License'];
  res.redirect('/vendor-otp');
});

// Vendor OTP
app.get('/vendor-otp', (req, res) => {
  res.sendFile(path.join(projectRoot, 'OTP vendor', 'index44.php'));
});

app.post('/vendor-otp', (req, res) => {
  req.session.vendor = req.session.vendor || {};
  req.session.vendor.otp = req.body.otp;
  res.redirect('/vendor-dashboard-1');
});

// ======================== SPECIALIST SIGNUP ========================
// Step 1: Specialist Sign Up Page 1
app.get('/specialist-signup-1', (req, res) => {
  res.sendFile(path.join(projectRoot, 'Specialist-SignUp Page-1', 'index.php'));
});

app.post('/specialist-signup-1', (req, res) => {
  req.session.specialist = {
    firstName: req.body['First-name'],
    lastName: req.body['Last-name'],
    email: req.body['E-mail'],
    phone: req.body['Phone-number'],
    password: req.body.password,
    retypePassword: req.body['retype-password'],
    city: req.body.City,
    postCode: req.body['Post-Code']
  };
  res.redirect('/specialist-signup-2');
});

// Step 2: Specialist Sign Up Page 2
app.get('/specialist-signup-2', (req, res) => {
  res.sendFile(path.join(projectRoot, 'Specialist-SignUp page-2', 'index2.php'));
});

app.post('/specialist-signup-2', upload.single('Vendor-image'), (req, res) => {
  req.session.specialist = req.session.specialist || {};
  req.session.specialist.image = req.file ? req.file.buffer.toString('base64') : null;
  req.session.specialist.about = req.body.about;
  res.redirect('/specialist-signup-3');
});

// Step 3: Specialist Sign Up Page 3
app.get('/specialist-signup-3', (req, res) => {
  res.sendFile(path.join(projectRoot, 'Specialist-SignUp Page-3', 'index5.php'));
});

app.post('/specialist-signup-3', (req, res) => {
  req.session.specialist = req.session.specialist || {};
  req.session.specialist.skills = req.body.skills;
  res.redirect('/specialist-otp');
});

// Specialist OTP
app.get('/specialist-otp', (req, res) => {
  res.sendFile(path.join(projectRoot, 'OTP specialist', 'index444.php'));
});

app.post('/specialist-otp', (req, res) => {
  req.session.specialist = req.session.specialist || {};
  req.session.specialist.otp = req.body.otp;
  res.redirect('/specialist-dashboard-1');
});

// ======================== LOGIN ========================
app.get('/login', (req, res) => {
  res.sendFile(path.join(projectRoot, 'usertype', 'page2.php'));
});

app.post('/login', (req, res) => {
  const userType = req.body.userType;
  const email = req.body.email;
  const password = req.body.password;
  
  req.session.currentUser = { userType, email };
  
  if (userType === 'user') res.redirect('/user-dashboard-1');
  else if (userType === 'vendor') res.redirect('/vendor-dashboard-1');
  else if (userType === 'specialist') res.redirect('/specialist-dashboard-1');
  else res.redirect('/login');
});

// ======================== DASHBOARDS ========================
app.get('/user-dashboard-1', (req, res) => {
  res.sendFile(path.join(projectRoot, 'UserDashboard-1', 'index.php'));
});

app.get('/vendor-dashboard-1', (req, res) => {
  res.sendFile(path.join(projectRoot, 'vendorDashboard-1', 'index.php'));
});

app.get('/specialist-dashboard-1', (req, res) => {
  res.sendFile(path.join(projectRoot, 'specialist Dashboard-1', 'index.php'));
});

// ======================== GENERIC FORM POST HANDLER ========================
// This handles form submissions from any directory-based page
app.post('/:dir/', upload.single('Vendor-image'), (req, res, next) => {
  const dirPath = path.join(projectRoot, req.params.dir);
  const indexPath = path.join(dirPath, 'index.php');
  
  if (!fs.existsSync(indexPath)) {
    return next();
  }
  
  // Store form data in session
  req.session.formData = req.session.formData || {};
  req.session.formData = { ...req.session.formData, ...req.body };
  if (req.file) {
    req.session.formData.file = req.file.buffer.toString('base64');
    req.session.formData.fileName = req.file.originalname;
  }
  
  // Look for redirect in the PHP file (extract header redirects)
  fs.readFile(indexPath, 'utf8', (err, data) => {
    if (err) return res.status(500).send('Error processing form');
    
    // Extract redirect URL from PHP
    const redirectMatch = data.match(/header\s*\(\s*["']location\s*:\s*([^"']+)["']\s*\)/);
    if (redirectMatch) {
      let redirectUrl = redirectMatch[1];
      // Remove localhost and protocol
      redirectUrl = redirectUrl.replace(/http:\/\/localhost:\d+/g, '');
      // URL decode
      redirectUrl = decodeURIComponent(redirectUrl);
      return res.redirect(redirectUrl);
    }
    
    // If no redirect, just reload the same page
    res.redirect(req.originalUrl);
  });
});

app.post('/:dir', (req, res, next) => {
  // Redirect with trailing slash for consistency
  res.redirect(req.originalUrl + '/');
});

// ======================== CATCH-ALL ROUTING ========================
// This handles all directory-based requests

// For directory requests with trailing slash, serve index.php
app.get('/:dir/', (req, res, next) => {
  const dirPath = path.join(projectRoot, req.params.dir);
  const indexPath = path.join(dirPath, 'index.php');
  
  // Check if index.php exists in the directory
  if (fs.existsSync(indexPath)) {
    return res.sendFile(indexPath);
  }
  
  next();
});

// For requests without trailing slash, redirect to add trailing slash
app.get('/:dir', (req, res, next) => {
  const dirPath = path.join(projectRoot, req.params.dir);
  
  // Skip if it looks like a file (has extension)
  if (path.extname(req.params.dir)) {
    return next();
  }
  
  // Check if directory exists with index.php
  const indexPath = path.join(dirPath, 'index.php');
  if (fs.existsSync(indexPath)) {
    // Redirect to the trailing slash version
    return res.redirect(req.originalUrl + '/');
  }
  
  next();
});

// Handle requests with subpaths (e.g., /path/to/subdir/)
app.get('/:dir/*', (req, res, next) => {
  const pathStr = req.params.dir + '/' + req.params[0];
  const fullPath = path.join(projectRoot, pathStr);
  const indexPath = path.join(fullPath, 'index.php');
  
  // Check if it's a direct file
  if (fs.existsSync(fullPath) && fs.statSync(fullPath).isFile()) {
    return res.sendFile(fullPath);
  }
  
  // Check if it's a directory with index.php
  if (fs.existsSync(indexPath)) {
    return res.sendFile(indexPath);
  }
  
  next();
});

// 404 handler
app.use((req, res) => {
  res.status(404).send('Page not found');
});

// Start server
const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`KiChai server running on port ${PORT}`);
});

module.exports = app;
