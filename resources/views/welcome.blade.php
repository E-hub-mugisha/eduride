<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>EDURIDE | Smart School Transport</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <!-- Favicons -->
    <link href="assets/img/favicon.png" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/aos/aos.css" rel="stylesheet">
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

    <!-- Main CSS File -->
    <link href="assets/css/main.css" rel="stylesheet">

</head>

<body class="bg-gray-50">

    <header id="header" class="header d-flex align-items-center sticky-top">
        <div class="container position-relative d-flex align-items-center justify-content-between">

            <a href="/" class="logo d-flex align-items-center me-auto me-xl-0">
                <!-- Uncomment the line below if you also wish to use an image logo -->
                <!-- <img src="assets/img/logo.webp" alt=""> -->
                <h1 class="sitename">{{ config('app.name')}}</h1><span>.</span>
            </a>

            <nav id="navmenu" class="navmenu">
                <ul>
                    <li><a href="#hero" class="active">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="{{ route('dashboard') }}">Dashboard</a></li>
                </ul>
                <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
            </nav>

            <a class="btn-getstarted" href="{{ route('login') }}">Get Started</a>

        </div>
    </header>

    <main class="main">

        <!-- Hero Section -->
        <section id="hero" class="hero section">

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row align-items-center gy-5">

                    <div class="col-lg-6" data-aos="fade-right" data-aos-delay="200">
                        <div class="hero-content">
                            <div class="hero-tag" data-aos="fade-up" data-aos-delay="250">
                                <span class="tag-dot"></span>
                                <span class="tag-text">{{ config('app.name')}}</span>
                            </div>

                            <h1 class="hero-headline" data-aos="fade-up" data-aos-delay="300">Smart, Safe & Real-Time School Transport</h1>

                            <p class="hero-text" data-aos="fade-up" data-aos-delay="350">EDURIDE empowers schools, parents, and drivers with GPS-enabled live tracking, safety alerts, and seamless communication for student transport.</p>

                            <div class="hero-cta" data-aos="fade-up" data-aos-delay="400">
                                <a href="#features" class="cta-button">
                                    <span>Learn More</span>
                                    <i class="bi bi-arrow-right"></i>
                                </a>
                                <a href="{{ route('login') }}" class="glightbox cta-link" data-gallery="hero-video">

                                    <span>Get Started</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6" data-aos="fade-left" data-aos-delay="300">
                        <div class="stats-grid">
                            <div class="stat-card stat-card-primary" data-aos="zoom-in" data-aos-delay="350">
                                <div class="stat-icon-wrap">
                                    <i class="bi bi-rocket-takeoff"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">150+</span>
                                    <span class="stat-title">Projects Launched</span>
                                </div>
                            </div>

                            <div class="stat-card" data-aos="zoom-in" data-aos-delay="400">
                                <div class="stat-icon-wrap">
                                    <i class="bi bi-heart"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">98%</span>
                                    <span class="stat-title">Client Satisfaction</span>
                                </div>
                            </div>

                            <div class="stat-card" data-aos="zoom-in" data-aos-delay="450">
                                <div class="stat-icon-wrap">
                                    <i class="bi bi-lightbulb"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">12+</span>
                                    <span class="stat-title">Years Experience</span>
                                </div>
                            </div>

                            <div class="stat-card stat-card-accent" data-aos="zoom-in" data-aos-delay="500">
                                <div class="stat-icon-wrap">
                                    <i class="bi bi-briefcase"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">40+</span>
                                    <span class="stat-title">Team Experts</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </section><!-- /Hero Section -->

        <!-- Why Us Section -->
        <section id="why-us" class="why-us section light-background">


            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row g-5">
                    <div class="col-lg-5" data-aos="fade-right" data-aos-delay="200">
                        <div class="sidebar-content">
                            <div class="badge-wrapper">
                                <span class="section-badge"><i class="bi bi-stars"></i> Our Difference</span>
                            </div>
                            <h2>Why Schools & Parents Choose EDURIDE</h2>
                            <p class="description">EDURIDE empowers schools, parents, and drivers with GPS-enabled live tracking, safety alerts, and seamless communication for student transport. Parents track the bus in real time and receive notifications for their child’s safety.</p>

                            <div class="stat-cards">
                                <div class="stat-card" data-aos="zoom-in" data-aos-delay="300">
                                    <div class="stat-value">
                                        <span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $totalTrips }}" data-purecounter-duration="2">{{ $totalTrips }}</span>+
                                    </div>
                                    <div class="stat-text">Total Trips</div>
                                </div>
                                <div class="stat-card" data-aos="zoom-in" data-aos-delay="350">
                                    <div class="stat-value">
                                        <span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $totalVehicles }}" data-purecounter-duration="2">{{ $totalVehicles }}</span>%
                                    </div>
                                    <div class="stat-text">Total Vehicles</div>
                                </div>
                                <div class="stat-card" data-aos="zoom-in" data-aos-delay="400">
                                    <div class="stat-value">
                                        <span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $totalDrivers }}" data-purecounter-duration="2">{{ $totalDrivers }}</span>%
                                    </div>
                                    <div class="stat-text">Total Drivers</div>
                                </div>
                                <div class="stat-card" data-aos="zoom-in" data-aos-delay="450">
                                    <div class="stat-value">
                                        <span class="purecounter" data-purecounter-start="0" data-purecounter-end="{{ $totalStudents }}" data-purecounter-duration="2">{{ $totalStudents }}</span>%
                                    </div>
                                    <div class="stat-text">Total Students</div>
                                </div>
                            </div>

                            <div class="action-buttons">
                                <a href="#" class="btn-main">Get Started Today</a>
                                <a href="#" class="btn-outline">Explore Portfolio</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7" data-aos="fade-left" data-aos-delay="200">
                        <div class="features-grid">
                            <div class="feature-box highlight" data-aos="fade-up" data-aos-delay="250">
                                <div class="feature-ribbon">Top Rated</div>
                                <div class="feature-icon">
                                    <i class="bi bi-rocket-takeoff-fill"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Live GPS Tracking</h4>
                                    <p>Track buses in real time with precise locations and live updates.</p>
                                    <a href="#" class="feature-link">Discover How <i class="bi bi-chevron-right"></i></a>
                                </div>
                            </div><!-- End Feature Box -->

                            <div class="feature-box" data-aos="fade-up" data-aos-delay="300">
                                <div class="feature-icon">
                                    <i class="bi bi-bar-chart-line-fill"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Parent Notifications</h4>
                                    <p>Receive alerts when your child’s bus is approaching pickup or drop-off points.</p>
                                    <a href="#" class="feature-link">Discover How <i class="bi bi-chevron-right"></i></a>
                                </div>
                            </div><!-- End Feature Box -->

                            <div class="feature-box" data-aos="fade-up" data-aos-delay="350">
                                <div class="feature-icon">
                                    <i class="bi bi-award-fill"></i>
                                </div>
                                <div class="feature-content">
                                    <h4>Student Safety</h4>
                                    <p>Monitor routes, ensure accountability, and handle emergencies effectively.</p>
                                    <a href="#" class="feature-link">Discover How <i class="bi bi-chevron-right"></i></a>
                                </div>
                            </div><!-- End Feature Box -->
                        </div>

                        <div class="process-timeline" data-aos="fade-up" data-aos-delay="400">
                            <h5 class="timeline-title"><i class="bi bi-diagram-3-fill"></i> Our Proven Methodology</h5>
                            <div class="timeline-steps">
                                <div class="timeline-step">
                                    <div class="step-marker">1</div>
                                    <div class="step-info">
                                        <strong>Research</strong>
                                        <span>Gathering insights</span>
                                    </div>
                                </div>
                                <div class="timeline-connector"></div>
                                <div class="timeline-step">
                                    <div class="step-marker">2</div>
                                    <div class="step-info">
                                        <strong>Blueprint</strong>
                                        <span>Creating roadmap</span>
                                    </div>
                                </div>
                                <div class="timeline-connector"></div>
                                <div class="timeline-step">
                                    <div class="step-marker">3</div>
                                    <div class="step-info">
                                        <strong>Build</strong>
                                        <span>Developing solution</span>
                                    </div>
                                </div>
                                <div class="timeline-connector"></div>
                                <div class="timeline-step">
                                    <div class="step-marker">4</div>
                                    <div class="step-info">
                                        <strong>Refine</strong>
                                        <span>Iterating results</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="capabilities-section" data-aos="fade-up" data-aos-delay="450">
                            <h5 class="capabilities-heading">How It Works</h5>
                            <div class="capabilities-grid">
                                <div class="capability-card">
                                    <div class="capability-icon">
                                        <i class="bi bi-bullseye"></i>
                                    </div>
                                    <h6>Assign Routes</h6>
                                    <p>Administrators assign drivers and vehicles to routes quickly and efficiently.</p>
                                </div>
                                <div class="capability-card">
                                    <div class="capability-icon">
                                        <i class="bi bi-code-slash"></i>
                                    </div>
                                    <h6>Start Trip</h6>
                                    <p>Drivers start trips and share live GPS location during the route.</p>
                                </div>
                                <div class="capability-card">
                                    <div class="capability-icon">
                                        <i class="bi bi-arrow-repeat"></i>
                                    </div>
                                    <h6>Track & Notify</h6>
                                    <p>Parents track the bus in real time and receive notifications for their child’s safety.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </section><!-- /Why Us Section -->
        

        
        <!-- Contact Section -->
        <section id="contact" class="contact section">

            <!-- Section Title -->
            <div class="container section-title" data-aos="fade-up">
                <h2>Contact</h2>
                <p>Necessitatibus eius consequatur ex aliquid fuga eum quidem sint consectetur velit</p>
            </div><!-- End Section Title -->

            <div class="container" data-aos="fade-up" data-aos-delay="100">

                <div class="row gy-5 align-items-stretch">

                    <div class="col-lg-5" data-aos="fade-right" data-aos-delay="200">
                        <div class="info-panel">
                            <div class="panel-header">
                                <span class="section-badge">
                                    <i class="bi bi-chat-dots-fill"></i>
                                    Get In Touch
                                </span>
                                <h3>Let's Bring Your Vision to Life</h3>
                                <p>Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores.</p>
                            </div>

                            <div class="contact-methods">
                                <div class="method-item">
                                    <div class="method-icon">
                                        <i class="bi bi-envelope-paper-fill"></i>
                                    </div>
                                    <div class="method-details">
                                        <span class="method-label">Email Us</span>
                                        <a href="mailto:support@example.com">support@example.com</a>
                                    </div>
                                </div>

                                <div class="method-item">
                                    <div class="method-icon">
                                        <i class="bi bi-headset"></i>
                                    </div>
                                    <div class="method-details">
                                        <span class="method-label">Call Us</span>
                                        <a href="tel:+15558901234">+1 (555) 890-1234</a>
                                    </div>
                                </div>

                                <div class="method-item">
                                    <div class="method-icon">
                                        <i class="bi bi-pin-map-fill"></i>
                                    </div>
                                    <div class="method-details">
                                        <span class="method-label">Visit Us</span>
                                        <span>1847 Oak Avenue, Portland, OR 97201</span>
                                    </div>
                                </div>
                            </div>

                            <div class="stats-strip">
                                <div class="stat-item">
                                    <span class="stat-number">98%</span>
                                    <span class="stat-text">Satisfaction</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">24/7</span>
                                    <span class="stat-text">Support</span>
                                </div>
                                <div class="stat-item">
                                    <span class="stat-number">3.2k</span>
                                    <span class="stat-text">Projects</span>
                                </div>
                            </div>

                            <div class="social-connect">
                                <span>Follow Us</span>
                                <div class="social-icons">
                                    <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                                    <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                                    <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                                    <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Info Panel -->

                    <div class="col-lg-7" data-aos="fade-left" data-aos-delay="300">
                        <div class="form-card">
                            <div class="form-card-header">
                                <div class="header-icon">
                                    <i class="bi bi-send-fill"></i>
                                </div>
                                <div class="header-text">
                                    <h4>Send Us a Message</h4>
                                    <p>Fill out the form and our team will respond within 24 hours.</p>
                                </div>
                            </div>

                            <form action="forms/contact.php" method="post" class="php-email-form">
                                <div class="row g-4">
                                    <div class="col-md-6">
                                        <div class="input-group-custom">
                                            <label>Your Name</label>
                                            <div class="input-wrapper">
                                                <i class="bi bi-person"></i>
                                                <input type="text" name="name" placeholder="John Doe" required="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="input-group-custom">
                                            <label>Email Address</label>
                                            <div class="input-wrapper">
                                                <i class="bi bi-envelope"></i>
                                                <input type="email" name="email" placeholder="john@example.com" required="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="input-group-custom">
                                            <label>Subject</label>
                                            <div class="input-wrapper">
                                                <i class="bi bi-chat-square-text"></i>
                                                <input type="text" name="subject" placeholder="How can we help?" required="">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="input-group-custom">
                                            <label>Your Message</label>
                                            <div class="input-wrapper textarea-wrapper">
                                                <i class="bi bi-pencil-square"></i>
                                                <textarea name="message" rows="5" placeholder="Tell us about your project..." required=""></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="loading">Loading</div>
                                    <div class="error-message"></div>
                                    <div class="sent-message">Your message has been sent. Thank you!</div>

                                    <button type="submit" class="btn-submit">
                                        <span>Send Message</span>
                                        <i class="bi bi-arrow-right-circle-fill"></i>
                                    </button>

                                    <div class="secure-note">
                                        <i class="bi bi-lock-fill"></i>
                                        <span>Your data is encrypted and secure</span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div><!-- End Form Card -->

                </div>

            </div>

        </section><!-- /Contact Section -->
        <footer id="footer" class="footer light-background">

            <div class="container footer-bottom">
                <div class="row gy-3">
                    <div class="col-md-6 order-2 order-md-1">
                        <div class="copyright">
                            <p>© <span>Copyright</span>{{ date('Y') }} <strong class="sitename">EDURIDE | IRERERO Academy</strong>. All Rights Reserved.</p>
                        </div>
                    </div>
                    <div class="col-md-6 order-1 order-md-2">
                        <div class="legal-links">
                            <a href="#!">Smart • Safe • Reliable School Transport</a>
                        </div>
                    </div>
                </div>
            </div>

        </footer>


        <!-- Scroll Top -->
        <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

        <!-- Preloader -->
        <div id="preloader"></div>

        <!-- Vendor JS Files -->
        <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <script src="assets/vendor/php-email-form/validate.js"></script>
        <script src="assets/vendor/aos/aos.js"></script>
        <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
        <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
        <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
        <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
        <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>

        <!-- Main JS File -->
        <script src="assets/js/main.js"></script>

</body>

</html>