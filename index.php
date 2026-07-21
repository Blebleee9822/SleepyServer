<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sleepyserver.com | Premium Editorial Ad Agency</title>

    <!-- Browser Tab Icon (Favicon) -->
    <link rel="icon" type="image/png" href="uploads/logo.png">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Playfair Display for editorial feel, Inter for clean copy -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .editorial-title {
            font-family: 'Playfair Display', serif;
        }

        /* Custom scrollbar for news list */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(241, 241, 241, 0.5); /* Slightly transparent track */
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Custom animation for left-to-right marquee */
        @keyframes marquee-ltr {
            from { transform: translateX(-100%); }
            to { transform: translateX(100vw); }
        }

        /* Premium UI Animations & Textures */
        @keyframes fade-in-up {
            from { opacity: 0; transform: translateY(12px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fade-in-up 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .noise-overlay {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            pointer-events: none;
            z-index: 50;
            opacity: 0.02;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.7' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
        }

        /* Hide scrollbar for category tabs */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>
<body class="bg-[#FAF9F6] text-slate-900 transition-colors duration-200">
    <div class="noise-overlay"></div>

    <!-- Top Utility Bar and Main Masthead (Scrolls out of view) -->
    <header class="border-b border-stone-100 bg-white transition-colors duration-200 shadow-sm relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-14 border-b border-stone-100 text-xs text-stone-500">
                <div class="flex items-center space-x-4">
                    <span id="live-date">Loading Date...</span>
                    <span class="hidden md:inline text-stone-300">|</span>
                    <span class="hidden md:inline">Mainframe Status: Online</span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="#about-agency" class="hover:underline">System Documentation</a>
                    <span class="text-stone-300">|</span>
                    <button onclick="openAdModal()" class="bg-stone-900 hover:bg-stone-800 text-white px-3 py-1.5 rounded-sm font-medium transition-colors">
                        Serve Technical SEO
                    </button>
                </div>
            </div>

            <!-- Main Masthead -->
            <div class="py-6 text-center flex flex-col items-center justify-center">
                <div class="text-[10px] uppercase tracking-[0.25em] text-stone-500 font-bold mb-3">The Daily Business Directory</div>

                <h1 class="editorial-title text-4xl sm:text-5xl md:text-6xl font-black tracking-tight text-stone-950 select-none">
                    SLEEPYSERVER.COM
                </h1>
                <p class="text-xs sm:text-sm text-stone-600 italic mt-2 max-w-lg mx-auto">
                    "Connecting top-tier service enterprises with audiences worldwide through simple, structured promotion."
                </p>
            </div>
        </div>
    </header>

    <!-- Anchor for detecting scroll position -->
    <div id="video-anchor" class="absolute w-full h-1 invisible pointer-events-none" style="top: 250px;"></div>

    <!-- Featured Hero Video -->
    <section id="hero-video-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative z-10 transition-all duration-500 max-h-[1000px]">
        <div id="video-wrapper" class="w-full h-[30vh] sm:h-[40vh] md:h-[50vh] min-h-[300px] rounded-2xl overflow-hidden shadow-xl border border-stone-200 relative group transition-all duration-500 bg-stone-900">
            <!-- Added muted attribute for guaranteed browser autoplay -->
            <video id="hero-video" autoplay loop muted playsinline class="w-full h-full object-cover">
                <source src="uploads/backgroundvideo.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="absolute inset-0 bg-stone-900/5 mix-blend-multiply pointer-events-none"></div>
        </div>
    </section>

    <!-- Sticky Navigation Category & Search Strip -->
    <nav id="sticky-nav" class="sticky top-0 z-40 bg-white/95 backdrop-blur-md border-b border-stone-200 shadow-sm transition-all duration-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="relative flex items-center w-full overflow-x-auto text-sm font-semibold text-stone-600 no-scrollbar">
                <div class="flex w-full gap-2 sm:gap-4 relative min-w-max" id="category-tabs">
                    <!-- Dynamic categories go here -->
                </div>
            </div>
        </div>
    </nav>

    <!-- Breaking News Ticker -->
    <section class="bg-stone-900 text-stone-100 py-2.5 overflow-hidden text-xs relative border-b border-stone-800 z-10">
        <div class="max-w-7xl mx-auto px-4 flex items-center">
            <div class="relative w-full overflow-hidden whitespace-nowrap">
                <div class="inline-block" style="animation: marquee-ltr 30s linear infinite;">
                    <span class="mx-6 text-stone-300"><strong class="text-white">Quantum Web Studio:</strong> Award-winning premium enterprise landing pages. Book your redesign today!</span>
                    <span class="mx-6 text-stone-300"><strong class="text-white">SEO Elevate Pro:</strong> Dominate search rankings with our elite technical SEO audit packages.</span>
                    <span class="mx-6 text-stone-300"><strong class="text-white">CyberShield Sec:</strong> Advanced malware removal & zero-day protection for high-traffic servers.</span>
                    <span class="mx-6 text-stone-300"><strong class="text-white">CloudMigrate Masters:</strong> Seamless, zero-downtime database and system configuration migrations.</span>
                </div>
            </div>
        </div>
    </section>


    <!-- Main Content Area -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">

        <!-- Welcome Promo Row (Simple & Informational) -->
        <div class="bg-amber-50/90 backdrop-blur-md border border-amber-200/60 rounded-lg p-5 mb-8 flex flex-col md:flex-row items-center justify-between gap-4 shadow-sm">
            <div class="space-y-1">
                <h3 class="text-stone-900 font-bold text-base flex items-center">
                    Get Featured on sleepyserver.com
                </h3>
                <p class="text-xs sm:text-sm text-stone-600">
                    We compile and advertise premium services of reliable businesses. No complex signups, no bloated directories—just dynamic news-style promotion.
                </p>
            </div>
            <div class="flex items-center space-x-3 w-full md:w-auto shrink-0">
                <button onclick="openAdModal()" class="bg-stone-950 hover:bg-stone-800 text-white text-xs font-bold px-4 py-2.5 rounded shadow transition-all w-full md:w-auto">
                    Publish My Ad Live
                </button>
                <button onclick="alertNotification('Detailed documentation and guides are coming soon!')" class="border border-stone-300 hover:border-stone-500 bg-white/80 backdrop-blur text-stone-700 text-xs font-bold px-4 py-2.5 rounded transition-all text-center w-full md:w-auto">
                    Learn How It Works
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            <!-- Left & Center: News Column Feed (8 Columns) -->
            <div id="main-feed-column" class="lg:col-span-8 space-y-8 transition-all duration-300 ease-out transform translate-y-0 opacity-100">

                <!-- Section Header -->
                <div class="border-b-2 border-stone-950 pb-2 flex items-center justify-between bg-white/80 backdrop-blur-sm p-2 rounded-t-md">
                    <h2 class="editorial-title text-2xl font-bold text-stone-950" id="feed-heading">
                        All Sponsored Bulletins
                    </h2>
                    <span class="text-xs text-stone-500 font-semibold" id="ad-count-badge">8 active promotions found</span>
                </div>

                <!-- Featured Lead Story Ad (dynamic spotlight) -->
                <div id="lead-story-container" class="border-b border-stone-200 pb-8">
                    <!-- Dynamic Featured Ad -->
                </div>

                <!-- Regular Ads Grid (2 Columns, responsive) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6" id="ads-grid">
                    <!-- Dynamic Regular Ads populated by JS -->
                </div>

                <!-- Empty State -->
                <div id="empty-state" class="hidden text-center py-12 border border-dashed border-stone-300 rounded-lg bg-white/90 backdrop-blur-sm">
                    <h3 class="font-bold text-stone-800 text-lg">No Classifieds Match Your Search</h3>
                    <p class="text-stone-500 text-sm mt-1 max-w-md mx-auto">Try selecting another business category or altering your search query to discover other promotions.</p>
                    <button onclick="resetFilters()" class="mt-4 px-4 py-2 bg-stone-900 text-white text-xs font-bold rounded hover:bg-stone-800">
                        View All Listings
                    </button>
                </div>

            </div>


            <!-- Right: Interactive Newspaper Sidebar (4 Columns) -->
            <aside class="lg:col-span-4 space-y-8">

                <!-- Sticky Container -->
                <div class="sticky top-28 space-y-8">

                    <!-- Classified Spotlight Widget (Now a Contact Form) -->
                    <div class="bg-stone-950/95 backdrop-blur-md text-white p-6 rounded-lg relative overflow-hidden shadow-lg border border-stone-800">
                        <div class="absolute -right-12 -top-12 w-32 h-32 bg-stone-800 rounded-full opacity-30"></div>
                        <span class="text-[10px] uppercase font-bold tracking-widest text-amber-400 bg-stone-900 border border-stone-800 px-2 py-1 rounded inline-block mb-3">
                            Direct Inquiry
                        </span>
                        <h3 class="editorial-title text-xl font-bold mb-2">Need to contact us?</h3>
                        <p class="text-xs text-stone-300 mb-4 leading-relaxed">
                            Have questions about our editorial guidelines or custom advertising packages? Drop us a message and our team will get back to you promptly.
                        </p>

                        <form onsubmit="handleContactFormSubmit(event)" class="space-y-3 relative z-10">
                            <div>
                                <input type="text" id="contact-name" required placeholder="Your Name" class="w-full px-3 py-2 bg-stone-900/80 border border-stone-800 rounded text-xs text-white focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-all placeholder-stone-500">
                            </div>
                            <div>
                                <input type="email" id="contact-email" required placeholder="Email Address" class="w-full px-3 py-2 bg-stone-900/80 border border-stone-800 rounded text-xs text-white focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-all placeholder-stone-500">
                            </div>
                            <div>
                                <textarea id="contact-message" required rows="3" placeholder="How can we help you today?" class="w-full px-3 py-2 bg-stone-900/80 border border-stone-800 rounded text-xs text-white focus:outline-none focus:ring-1 focus:ring-amber-400 focus:border-amber-400 transition-all placeholder-stone-500 resize-none"></textarea>
                            </div>
                            <button type="submit" class="w-full bg-amber-400 hover:bg-amber-300 text-stone-950 font-bold text-xs py-2.5 rounded transition-all flex items-center justify-center space-x-1 mt-2">
                                <span>Send Message</span>
                            </button>
                        </form>
                    </div>

                    <!-- Trending Advertisers Column -->
                    <div class="bg-white/90 backdrop-blur-md border border-stone-200 p-5 rounded-lg shadow-sm">
                        <h3 class="editorial-title text-lg font-bold text-stone-950 border-b border-stone-100 pb-3 mb-4">
                            Verified Partner Status
                        </h3>
                        <div class="space-y-4" id="trending-partners-list">
                            <!-- Populated dynamically with partner details -->
                        </div>
                    </div>

                    <!-- Statistics / Why Advertise Here -->
                    <div class="bg-white/90 backdrop-blur-md border border-stone-200 p-5 rounded-lg shadow-sm">
                        <h3 class="editorial-title text-base font-bold text-stone-950 border-b border-stone-100 pb-3 mb-3">
                            Bulletins Performance Audit
                        </h3>
                        <div class="grid grid-cols-2 gap-4 text-center">
                            <div class="p-3 bg-stone-50/80 rounded border border-stone-100">
                                <div class="text-xl font-black text-stone-900" id="stat-impressions">142.8K</div>
                                <div class="text-[10px] text-stone-500 font-semibold uppercase">Daily Reads</div>
                            </div>
                            <div class="p-3 bg-stone-50/80 rounded border border-stone-100">
                                <div class="text-xl font-black text-stone-900" id="stat-clicks">12.4%</div>
                                <div class="text-[10px] text-stone-500 font-semibold uppercase">Average CTR</div>
                            </div>
                            <div class="p-3 bg-stone-50/80 rounded border border-stone-100">
                                <div class="text-xl font-black text-stone-900">450+</div>
                                <div class="text-[10px] text-stone-500 font-semibold uppercase">Partner Outlets</div>
                            </div>
                            <div class="p-3 bg-stone-50/80 rounded border border-stone-100">
                                <div class="text-xl font-black text-stone-900">100%</div>
                                <div class="text-[10px] text-stone-500 font-semibold uppercase">Vetted Brands</div>
                            </div>
                        </div>
                    </div>

                    <!-- About Agency Minimal Block -->
                    <div id="about-agency" class="bg-stone-50/90 backdrop-blur-md border border-stone-200 p-5 rounded-lg text-xs text-stone-600 leading-relaxed shadow-sm">
                        <h4 class="font-bold text-stone-900 mb-2 uppercase tracking-wide">About sleepyserver.com</h4>
                        <p class="mb-2">
                            Unlike overwhelming directories, we treat service advertisements as narrative news pieces. We promote reliable plumbing, legal, financial, and modern technology consultants through simple visual narratives that appeal to discerning clients.
                        </p>
                        <p class="font-semibold text-stone-900">Partner Hotline: +1 (555) 019-9482</p>
                    </div>

                </div>
            </aside>

        </div>
    </main>


    <!-- Footer -->
    <footer class="bg-stone-950 text-stone-400 py-12 border-t-4 border-stone-900 mt-20 text-xs sm:text-sm relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div class="space-y-3">
                    <h3 class="editorial-title text-white text-lg font-bold">sleepyserver.com</h3>
                    <p class="text-stone-400 text-xs leading-relaxed">
                        The modern web's premier classified hub. Merging high-quality journalism aesthetics with dynamic corporate and local promotions.
                    </p>
                    <div class="flex space-x-4 text-stone-300 pt-2 text-xs font-semibold uppercase tracking-wider">
                        <a href="#" class="hover:text-amber-400">Twitter</a>
                        <a href="#" class="hover:text-amber-400">LinkedIn</a>
                        <a href="#" class="hover:text-amber-400">Facebook</a>
                        <a href="#" class="hover:text-amber-400">Insta</a>
                    </div>
                </div>
                <div>
                    <h4 class="text-stone-100 font-bold uppercase tracking-wider text-xs mb-3">Trending Service Hubs</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" onclick="selectCategory('Tech'); return false;" class="hover:underline hover:text-white">Software & Tech Dev</a></li>
                        <li><a href="#" onclick="selectCategory('Home Services'); return false;" class="hover:underline hover:text-white">Commercial Repair & Build</a></li>
                        <li><a href="#" onclick="selectCategory('Wellness'); return false;" class="hover:underline hover:text-white">Holistic Personal Care</a></li>
                        <li><a href="#" onclick="selectCategory('Creative'); return false;" class="hover:underline hover:text-white">Design & Studio Services</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-stone-100 font-bold uppercase tracking-wider text-xs mb-3">Advertising Information</h4>
                    <ul class="space-y-2 text-xs">
                        <li><a href="#" onclick="openAdModal(); return false;" class="hover:underline hover:text-white">Pricing Packages</a></li>
                        <li><a href="#" onclick="openAdModal(); return false;" class="hover:underline hover:text-white">Submit Ad Circular</a></li>
                        <li><a href="#" onclick="openAdModal(); return false;" class="hover:underline hover:text-white">Guest Post Terms</a></li>
                        <li><a href="#" class="hover:underline hover:text-white">API Feed Access</a></li>
                    </ul>
                </div>
                <div class="space-y-3">
                    <h4 class="text-stone-100 font-bold uppercase tracking-wider text-xs mb-3">Newsletter Subscriptions</h4>
                    <p class="text-stone-400 text-xs leading-relaxed">
                        Stay informed of top service deals, trusted certified experts, and verified reviews delivered weekly.
                    </p>
                    <div class="flex">
                        <input type="email" placeholder="Email Address" class="bg-stone-900 border border-stone-800 text-white rounded-l px-3 py-1.5 w-full focus:outline-none focus:ring-1 focus:ring-amber-400 text-xs">
                        <button onclick="alertNotification('Thank you for subscribing to our Daily Circular!')" class="bg-amber-400 hover:bg-amber-300 text-stone-950 font-bold px-3 py-1.5 rounded-r text-xs">Join</button>
                    </div>
                </div>
            </div>
            <div class="border-t border-stone-800 pt-8 flex flex-col sm:flex-row justify-between items-center text-xs text-stone-500">
                <p>&copy; 2026 sleepyserver.com. Developed for premier business reach. All mock rights simulated.</p>
                <div class="flex space-x-4 mt-4 sm:mt-0">
                    <a href="#" class="hover:underline">Privacy Policy</a>
                    <a href="#" class="hover:underline">Terms of Promotion</a>
                    <a href="#" class="hover:underline">Contact Editorial</a>
                </div>
            </div>
        </div>
    </footer>


    <!-- SUBMIT AD MODAL -->
    <div id="submit-ad-modal" class="fixed inset-0 bg-stone-950/80 backdrop-blur-sm hidden z-50 items-center justify-center p-4 overflow-y-auto">
        <div class="bg-white rounded-lg shadow-2xl max-w-lg w-full overflow-hidden border border-stone-200 flex flex-col my-8">
            <div class="bg-stone-950 text-white px-6 py-4 flex items-center justify-between">
                <div>
                    <h3 class="editorial-title text-xl font-bold">Request SEO Audit</h3>
                    <p class="text-[10px] text-stone-400 uppercase tracking-widest mt-0.5">Serve Technical SEO Profile</p>
                </div>
                <button onclick="closeAdModal()" class="text-stone-400 hover:text-white font-bold transition-all">Close</button>
            </div>

            <form id="add-listing-form" onsubmit="handleSEOServiceRequest(event)" class="p-6 space-y-4 text-sm overflow-y-auto max-h-[80vh]">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-stone-700 mb-1">Company / Brand Name *</label>
                        <input type="text" id="req-biz-name" required placeholder="e.g., Summit Legal Advisors" class="w-full px-3 py-2 border border-stone-300 rounded focus:ring-1 focus:ring-stone-950 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-stone-700 mb-1">Current Primary URL *</label>
                        <input type="url" id="req-website" required placeholder="https://yoursite.com" class="w-full px-3 py-2 border border-stone-300 rounded focus:ring-1 focus:ring-stone-950 focus:outline-none bg-white">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-stone-700 mb-1">Primary SEO Objectives *</label>
                    <input type="text" id="req-goals" required placeholder="e.g., Increase organic traffic, fix core web vitals..." class="w-full px-3 py-2 border border-stone-300 rounded focus:ring-1 focus:ring-stone-950 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase text-stone-700 mb-1">Current Technical Issues / Notes *</label>
                    <textarea id="req-issues" rows="3" required placeholder="Describe any known crawling errors, indexing issues, or specific areas you want our technical SEO team to investigate." class="w-full px-3 py-2 border border-stone-300 rounded focus:ring-1 focus:ring-stone-950 focus:outline-none"></textarea>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold uppercase text-stone-700 mb-1">Target Audience Location</label>
                        <input type="text" id="req-location" placeholder="e.g., Chicago, IL / Nationwide Remote" class="w-full px-3 py-2 border border-stone-300 rounded focus:ring-1 focus:ring-stone-950 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase text-stone-700 mb-1">Contact Email *</label>
                        <input type="email" id="req-contact-email" required placeholder="you@company.com" class="w-full px-3 py-2 border border-stone-300 rounded focus:ring-1 focus:ring-stone-950 focus:outline-none">
                    </div>
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <input type="checkbox" id="req-urgent" class="h-4 w-4 text-stone-950 focus:ring-stone-950 border-stone-300 rounded">
                    <label for="req-urgent" class="text-xs text-stone-600 font-semibold select-none cursor-pointer">
                        Mark this as an <strong class="text-stone-950">Urgent Audit Request</strong> (24-48hr turnaround)
                    </label>
                </div>

                <div class="pt-4 border-t border-stone-100 flex justify-end space-x-3">
                    <button type="button" onclick="closeAdModal()" class="px-4 py-2 border border-stone-300 hover:border-stone-500 rounded text-stone-700 text-xs font-bold transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-stone-900 hover:bg-stone-800 text-white rounded text-xs font-bold transition-all">
                        Submit Audit Request
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- DETAIL / CONTACT MODAL -->
    <div id="detail-modal" class="fixed inset-0 bg-stone-950/80 backdrop-blur-sm hidden z-50 items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full overflow-hidden border border-stone-200 flex flex-col md:flex-row max-h-[90vh]">

            <!-- Left Side: Styled Image / Badge info -->
            <div class="md:w-1/2 bg-stone-100 relative h-48 md:h-auto">
                <img id="detail-img" src="" alt="Service Image" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-stone-950 via-stone-950/40 to-transparent flex flex-col justify-end p-6">
                    <span id="detail-badge" class="bg-amber-400 text-stone-950 text-[10px] uppercase font-bold tracking-widest px-2.5 py-1 rounded w-fit mb-2"></span>
                    <h3 id="detail-biz-name" class="editorial-title text-2xl font-bold text-white leading-tight"></h3>
                </div>
            </div>

            <!-- Right Side: Extensive Editorial review & simulated action -->
            <div class="md:w-1/2 p-6 flex flex-col justify-between overflow-y-auto">
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-xs text-stone-500 border-b border-stone-100 pb-2">
                        <span id="detail-location"></span>
                        <span>4.9 Verified Review</span>
                    </div>

                    <h4 id="detail-title" class="font-extrabold text-stone-950 text-base leading-snug"></h4>

                    <p id="detail-desc" class="text-xs text-stone-600 leading-relaxed"></p>

                    <div class="bg-stone-50 p-3 rounded border border-stone-200 space-y-1">
                        <div class="text-[10px] uppercase font-bold tracking-widest text-stone-500">Service Estimate Rate:</div>
                        <div id="detail-pricing" class="text-base font-extrabold text-stone-900"></div>
                    </div>

                    <!-- Live Website Button (Dynamic) -->
                    <div id="live-link-container" class="hidden pt-2">
                        <a id="detail-live-link" href="#" target="_blank" class="w-full flex items-center justify-center bg-amber-400 hover:bg-amber-300 text-stone-950 font-bold text-xs py-2.5 rounded transition-all shadow-sm">
                            View Live Website &nearr;
                        </a>
                    </div>

                    <!-- Simulated Inquiry Form inside Details -->
                    <div class="border-t border-stone-100 pt-3 space-y-2">
                        <h5 class="text-xs font-bold text-stone-950 uppercase tracking-wide">Direct Lead Inquiry</h5>
                        <div class="grid grid-cols-2 gap-2">
                            <input type="text" id="lead-name" placeholder="Your Name" class="px-2.5 py-1.5 border border-stone-300 rounded text-xs focus:ring-1 focus:ring-stone-950 focus:outline-none">
                            <input type="email" id="lead-email" placeholder="Your Email" class="px-2.5 py-1.5 border border-stone-300 rounded text-xs focus:ring-1 focus:ring-stone-950 focus:outline-none">
                        </div>
                        <button onclick="submitLead()" class="w-full bg-stone-900 hover:bg-stone-800 text-white font-bold text-xs py-2 rounded transition-all">
                            Request Callback / Book Estimate
                        </button>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-stone-100 flex items-center justify-between">
                    <span id="detail-contact" class="text-xs text-stone-500 font-semibold truncate max-w-[150px]"></span>
                    <button onclick="closeDetailModal()" class="px-4 py-1.5 bg-stone-100 hover:bg-stone-200 text-stone-800 rounded text-xs font-bold transition-all">
                        Close
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- Notification Alert Overlay -->
    <div id="toast-notif" class="fixed bottom-6 right-6 bg-stone-950 text-white px-5 py-3 rounded-lg shadow-xl border border-stone-800 hidden items-center space-x-3 z-50 animate-bounce">
        <div>
            <p id="toast-msg" class="text-xs font-semibold"></p>
        </div>
    </div>


    <!-- JAVASCRIPT LOGIC BLOCK -->
    <script>

        // Category configurations and icon mappings
        const categories = ["Web development", "SEO", "Bug and malware clean", "Migration and systemconfiguration"];

        const categoryGradients = {
            "Tech": "from-sky-50 to-indigo-100",
            "Home Services": "from-emerald-50 to-green-100",
            "Wellness": "from-rose-50 to-pink-100",
            "Finance": "from-slate-50 to-zinc-100",
            "Creative": "from-amber-50 to-yellow-100",
            "Education": "from-violet-50 to-purple-100"
        };

        const defaultImages = {
            "Tech": "https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=600&q=80",
            "Home Services": "https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=600&q=80",
            "Wellness": "https://images.unsplash.com/photo-1544367567-0f2fcb009e0b?auto=format&fit=crop&w=600&q=80",
            "Finance": "https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=600&q=80",
            "Creative": "https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&w=600&q=80",
            "Education": "https://images.unsplash.com/photo-1503676260728-1c00da094a0b?auto=format&fit=crop&w=600&q=80"
        };

        // Master Dataset containing Advertised Business Services
        let adsData = [
            {
                id: 1,
                bizName: "Starlight Enterprise",
                category: "Web development",
                title: "High-Performance Next.js Headless E-Commerce Build",
                description: "Architected a fully custom headless e-commerce solution using Next.js and Vercel edge networks, resulting in a 40% increase in conversion rates and sub-100ms load times.",
                pricing: "$25k+ Enterprise Build",
                location: "New York, NY",
                contact: "admin@sleepyserver.com",
                liveLink: "http://test7371.live-website.com",
                image: "uploads/website1.png",
                date: "Aug 12, 2026",
                isFeatured: true,
                rating: 5.0,
                reviewsCount: 12
            },
            {
                id: 2,
                bizName: "Aura Creative",
                category: "Web development",
                title: "Interactive WebGL Promotional Campaign Landing",
                description: "Developed a heavily animated, award-winning promotional landing page utilizing Three.js for 3D rendering and GSAP for scroll animations, increasing time-on-site by 300%.",
                pricing: "Custom Project",
                location: "Los Angeles, CA",
                contact: "admin@sleepyserver.com",
                liveLink: "https://blebleee9822.github.io/myart/",
                image: "uploads/website2.png",
                date: "Aug 05, 2026",
                isFeatured: false,
                rating: 4.9,
                reviewsCount: 24
            },
            {
                id: 3,
                bizName: "Apex Financial",
                category: "SEO",
                title: "Technical Architecture Overhaul & Core Web Vitals",
                description: "Restructured site architecture, resolving over 5,000 indexing errors and heavy main-thread blocking issues. Achieved perfect Lighthouse performance scores.",
                pricing: "Retainer Contract",
                location: "Chicago, IL",
                contact: "admin@sleepyserver.com",
                image: "https://images.unsplash.com/photo-1551288049-bebda4e38f71?auto=format&fit=crop&w=800&q=80",
                date: "Jul 28, 2026",
                isFeatured: false,
                rating: 4.9,
                reviewsCount: 18
            },
            {
                id: 4,
                bizName: "Global Logistics Hub",
                category: "SEO",
                title: "International Hreflang Architecture & Localization",
                description: "Implemented complex international SEO strategies across 12 languages. Deployed dynamic rendering and optimized crawl budgets to capture top global SERP rankings.",
                pricing: "Retainer Contract",
                location: "London, UK",
                contact: "admin@sleepyserver.com",
                image: "https://images.unsplash.com/photo-1542744173-8e7e53415bb0?auto=format&fit=crop&w=800&q=80",
                date: "Jul 15, 2026",
                isFeatured: false,
                rating: 5.0,
                reviewsCount: 31
            },
            {
                id: 5,
                bizName: "MedCare Health Network",
                category: "Bug and malware clean",
                title: "Zero-Day Exploit Mitigation & Server Hardening",
                description: "Responded to a critical zero-day vulnerability in a legacy patient portal. Cleaned infected core files, patched entry points, and implemented enterprise-grade WAF.",
                pricing: "Emergency Rate",
                location: "Boston, MA",
                contact: "admin@sleepyserver.com",
                image: "https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=800&q=80",
                date: "Jul 02, 2026",
                isFeatured: false,
                rating: 5.0,
                reviewsCount: 15
            },
            {
                id: 6,
                bizName: "Boutique Retailer",
                category: "Bug and malware clean",
                title: "Payment Gateway Ransomware Recovery",
                description: "Identified and eliminated a persistent skimmer script on a custom checkout flow. Restored secure database backups and achieved full PCI-DSS compliance.",
                pricing: "Emergency Rate",
                location: "Miami, FL",
                contact: "admin@sleepyserver.com",
                image: "https://images.unsplash.com/photo-1563986768494-4dee2763ff3f?auto=format&fit=crop&w=800&q=80",
                date: "Jun 20, 2026",
                isFeatured: false,
                rating: 4.9,
                reviewsCount: 11
            },
            {
                id: 7,
                bizName: "EduTech Academy",
                category: "Migration and systemconfiguration",
                title: "Legacy Monolith to Kubernetes Microservices",
                description: "Deconstructed a sluggish 10-year-old monolithic application into agile microservices on AWS EKS, reducing server response times by 75% and scaling dynamically.",
                pricing: "Custom Project",
                location: "San Francisco, CA",
                contact: "admin@sleepyserver.com",
                image: "https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&w=800&q=80",
                date: "Jun 10, 2026",
                isFeatured: false,
                rating: 5.0,
                reviewsCount: 42
            },
            {
                id: 8,
                bizName: "FinServe Partners",
                category: "Migration and systemconfiguration",
                title: "Zero-Downtime Multi-Cloud Database Migration",
                description: "Successfully orchestrated a live, zero-downtime migration of 50TB of highly sensitive financial records from on-premise servers to a multi-cloud Google Cloud/Azure environment.",
                pricing: "Custom Project",
                location: "Denver, CO",
                contact: "admin@sleepyserver.com",
                image: "https://images.unsplash.com/photo-1558494949-ef010cbdcc31?auto=format&fit=crop&w=800&q=80",
                date: "May 28, 2026",
                isFeatured: false,
                rating: 4.8,
                reviewsCount: 22
            }
        ];

        // App Filter State Variables
        let selectedCategory = "Web development";
        let searchQuery = "";


        // Date Display
        function initDate() {
            const dateObj = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            document.getElementById('live-date').innerHTML = `${dateObj.toLocaleDateString('en-US', options)}`;
        }

        // Initialize App UI
        window.onload = function() {
            initDate();
            renderCategories();
            renderAdsListings();
            renderPartnerList();
            setupIntersectionObservers();
            setupVideoAudioFade();

            const video = document.getElementById('hero-video');
            if(video) {
                // Browsers block unmuted autoplay. Start muted so the visual plays instantly.
                video.muted = true;
                video.volume = 1.0;

                // Play the video immediately
                const playPromise = video.play();
                if (playPromise !== undefined) {
                    playPromise.catch(e => {
                        console.log("Autoplay was prevented.", e);
                    });
                }

                // Unmute the audio on the user's first click anywhere on the page
                document.body.addEventListener('click', () => {
                    video.muted = false;
                }, { once: true });
            }

            // Handle window resize for the sliding tab indicator
            window.addEventListener('resize', () => {
                const activeBtn = document.querySelector(`[data-category="${selectedCategory}"]`);
                if (activeBtn) moveIndicator(activeBtn);
            });
        };

        // Render Top Category Navigation with Sliding Indicator
        function renderCategories() {
            const container = document.getElementById("category-tabs");
            container.innerHTML = "";

            // Create the sliding black indicator
            const indicator = document.createElement("div");
            indicator.id = "tab-indicator";
            indicator.className = "absolute top-0 bottom-0 left-0 bg-stone-950 rounded-md transition-all duration-300 ease-[cubic-bezier(0.4,0,0.2,1)] z-0 shadow-md";
            container.appendChild(indicator);

            categories.forEach(cat => {
                const isActive = (selectedCategory === cat);
                const btn = document.createElement("button");
                btn.className = `category-btn relative z-10 flex-1 text-center px-4 py-3 rounded-md transition-colors duration-300 text-[10px] sm:text-xs font-bold uppercase tracking-widest whitespace-nowrap ${
                    isActive ? "text-white" : "text-stone-500 hover:text-stone-950"
                }`;
                btn.textContent = cat;
                btn.dataset.category = cat;

                btn.onclick = (e) => {
                    selectCategory(cat);
                };

                container.appendChild(btn);
            });

            // Initial setup of indicator position
            setTimeout(() => {
                const activeBtn = container.querySelector(`[data-category="${selectedCategory}"]`);
                if (activeBtn) {
                    indicator.style.transition = 'none'; // Snap instantly on load
                    moveIndicator(activeBtn);
                    setTimeout(() => indicator.style.transition = '', 50); // Restore animation
                }
            }, 50);
        }

        // Helper function to animate the black background pill
        function moveIndicator(targetBtn) {
            const indicator = document.getElementById("tab-indicator");
            if (!indicator || !targetBtn) return;

            indicator.style.left = targetBtn.offsetLeft + 'px';
            indicator.style.width = targetBtn.offsetWidth + 'px';

            // Update text colors for contrast
            document.querySelectorAll('.category-btn').forEach(btn => {
                if (btn === targetBtn) {
                    btn.classList.remove('text-stone-500', 'hover:text-stone-950');
                    btn.classList.add('text-white');
                } else {
                    btn.classList.remove('text-white');
                    btn.classList.add('text-stone-500', 'hover:text-stone-950');
                }
            });
        }

        // Set active category filters with smooth content transition
        function selectCategory(category) {
            if (selectedCategory === category) return;

            const feedCol = document.getElementById("main-feed-column");

            if (feedCol) {
                // Smooth slide down and fade out
                feedCol.classList.remove("opacity-100", "translate-y-0");
                feedCol.classList.add("opacity-0", "translate-y-4");

                // Wait for fade to finish, then swap content and fade in
                setTimeout(() => {
                    selectedCategory = category;
                    renderAdsListings(); // Only re-render content, leave tabs alone to keep pill intact

                    // Move indicator specifically on click selection
                    const activeBtn = document.querySelector(`[data-category="${selectedCategory}"]`);
                    if (activeBtn) moveIndicator(activeBtn);

                    // Small delay to ensure the browser registers the new DOM before fading in
                    requestAnimationFrame(() => {
                        feedCol.classList.remove("opacity-0", "translate-y-4");
                        feedCol.classList.add("opacity-100", "translate-y-0");
                    });
                }, 300); // Faster, smoother 300ms transition
            } else {
                selectedCategory = category;
                renderAdsListings();

                const activeBtn = document.querySelector(`[data-category="${selectedCategory}"]`);
                if (activeBtn) moveIndicator(activeBtn);
            }
        }

        // Real-time Search Handler
        function handleSearch() {
            searchQuery = document.getElementById("news-search") ? document.getElementById("news-search").value.toLowerCase() : "";
            renderAdsListings();
        }

        // Reset search/filters
        function resetFilters() {
            searchQuery = "";
            selectCategory("Web development");
        }


        // Filter data source dynamically
        function getFilteredAds() {
            let filtered = adsData.filter(ad => ad.category === selectedCategory);
            return filtered;
        }

        // Render lead spotlight and regular ad grids
        function renderAdsListings() {
            const filteredAds = getFilteredAds();
            const leadContainer = document.getElementById("lead-story-container");
            const adsGrid = document.getElementById("ads-grid");
            const emptyState = document.getElementById("empty-state");
            const countBadge = document.getElementById("ad-count-badge");
            const feedHeading = document.getElementById("feed-heading");

            // Update header text based on category
            feedHeading.textContent = `${selectedCategory} Portfolio`;
            countBadge.textContent = `${filteredAds.length} project${filteredAds.length === 1 ? '' : 's'} displayed`;

            if (filteredAds.length === 0) {
                leadContainer.classList.add("hidden");
                adsGrid.classList.add("hidden");
                emptyState.classList.remove("hidden");
                return;
            }

            emptyState.classList.add("hidden");
            leadContainer.classList.remove("hidden");
            adsGrid.classList.remove("hidden");

            // Reset Containers for clean rendering
            leadContainer.innerHTML = "";
            adsGrid.innerHTML = "";

            // Apply fade-in animation class to the parent wrappers
            leadContainer.className = "border-b border-stone-200 pb-8 animate-fade-in";
            adsGrid.className = "animate-fade-in";

            if (selectedCategory === "Web development") {
                // STRUCTURE 1: Lead Story + 2-Column Grid
                leadContainer.classList.remove("hidden");
                adsGrid.className = "grid grid-cols-1 md:grid-cols-2 gap-8 animate-fade-in";

                let featuredAd = filteredAds.find(ad => ad.isFeatured);
                if (!featuredAd && filteredAds.length > 0) featuredAd = filteredAds[0];
                const regularAds = filteredAds.filter(ad => ad.id !== (featuredAd ? featuredAd.id : null));

                if (featuredAd) {
                    leadContainer.innerHTML = `
                        <div class="bg-white border border-stone-200 rounded-xl overflow-hidden grid grid-cols-1 md:grid-cols-12 gap-0 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300">
                            <div class="md:col-span-6 relative h-64 md:h-auto overflow-hidden group">
                                <span class="absolute top-4 left-4 bg-red-600 text-white text-[9px] font-black uppercase tracking-widest px-2.5 py-1 rounded shadow-sm z-10">
                                    Featured Project
                                </span>
                                <img src="${featuredAd.image}" alt="${featuredAd.bizName}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" onerror="this.onerror=null; this.src='https://placehold.co/800x600/f5f5f4/1c1917?text=sleepyserver.com';">
                            </div>
                            <div class="md:col-span-6 p-6 sm:p-10 flex flex-col justify-between space-y-4 bg-white/95">
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[10px] font-bold text-stone-500 uppercase tracking-widest bg-stone-100 px-2.5 py-1 rounded">Web Dev</span>
                                        <span class="text-[10px] text-stone-400 font-bold uppercase tracking-wider">${featuredAd.date}</span>
                                    </div>
                                    <h3 class="editorial-title text-2xl sm:text-3xl font-extrabold text-stone-950 hover:text-stone-700 transition-colors cursor-pointer leading-tight" onclick="openDetailModal(${featuredAd.id})">
                                        ${featuredAd.title}
                                    </h3>
                                    <div class="text-xs font-bold text-amber-600 uppercase tracking-widest">
                                        Client: <strong class="text-stone-900">${featuredAd.bizName}</strong>
                                    </div>
                                    <p class="text-sm text-stone-600 leading-relaxed">
                                        ${featuredAd.description}
                                    </p>
                                </div>
                                <div class="flex items-center justify-between border-t border-stone-100 pt-5 mt-4">
                                    <div class="text-xs text-stone-500 font-medium">
                                        ${featuredAd.location}
                                    </div>
                                    <button onclick="openDetailModal(${featuredAd.id})" class="bg-stone-950 hover:bg-stone-800 text-white text-xs font-bold px-5 py-2.5 rounded shadow-md transition-all">
                                        View Case Study
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                }

                regularAds.forEach(ad => {
                    adsGrid.innerHTML += `
                        <div class="bg-white border border-stone-200 rounded-xl overflow-hidden flex flex-col justify-between shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] hover:-translate-y-1 transition-all duration-300">
                            <div>
                                <div class="relative h-52 overflow-hidden bg-stone-100 group">
                                    <img src="${ad.image}" alt="${ad.bizName}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500" onerror="this.onerror=null; this.src='https://placehold.co/600x400/f5f5f4/1c1917';">
                                    <span class="absolute top-3 left-3 bg-white/90 backdrop-blur text-stone-900 text-[9px] uppercase font-bold tracking-widest px-2.5 py-1 rounded shadow-sm">
                                        Web Dev
                                    </span>
                                </div>
                                <div class="p-6 space-y-3 bg-white/95">
                                    <div class="text-[10px] uppercase font-bold text-stone-500 tracking-widest flex justify-between items-center">
                                        <span class="text-amber-600">${ad.bizName}</span>
                                        <span class="text-stone-400">${ad.date}</span>
                                    </div>
                                    <h4 class="editorial-title text-xl font-bold text-stone-950 leading-snug cursor-pointer hover:text-stone-700 transition-colors" onclick="openDetailModal(${ad.id})">
                                        ${ad.title}
                                    </h4>
                                    <p class="text-xs text-stone-600 leading-relaxed">
                                        ${ad.description.substring(0, 120)}...
                                    </p>
                                </div>
                            </div>
                            <div class="p-6 pt-0 border-t border-stone-100 mt-4 bg-white/95">
                                <div class="flex items-center justify-between text-xs pt-4">
                                    <span class="text-stone-500 font-medium truncate max-w-[140px]">${ad.location}</span>
                                    <button onclick="openDetailModal(${ad.id})" class="text-stone-950 hover:text-stone-700 font-extrabold flex items-center space-x-1.5 transition-colors group">
                                        <span>Read More</span>
                                        <span class="transform group-hover:translate-x-1 transition-transform">&rarr;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });

            } else if (selectedCategory === "SEO") {
                // STRUCTURE 2: Analytical Horizontal List View
                leadContainer.classList.add("hidden");
                adsGrid.className = "flex flex-col space-y-6 animate-fade-in";

                filteredAds.forEach(ad => {
                    adsGrid.innerHTML += `
                        <div class="flex flex-col sm:flex-row bg-white border border-stone-200 rounded-xl overflow-hidden shadow-sm hover:shadow-[0_8px_30px_rgb(0,0,0,0.06)] transition-all duration-300 group">
                            <div class="sm:w-2/5 relative h-56 sm:h-auto overflow-hidden bg-stone-100">
                                <img src="${ad.image}" alt="${ad.bizName}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" onerror="this.onerror=null; this.src='https://placehold.co/600x400/f5f5f4/1c1917';">
                                <div class="absolute top-4 left-4 bg-stone-950 text-white text-[9px] font-bold uppercase tracking-widest px-2.5 py-1 rounded shadow-md">SEO Audit</div>
                            </div>
                            <div class="sm:w-3/5 p-6 sm:p-8 flex flex-col justify-center space-y-4 bg-white/95">
                                <div class="text-[10px] uppercase font-bold text-amber-600 tracking-widest">Client: ${ad.bizName}</div>
                                <h4 class="editorial-title text-2xl font-bold text-stone-950 leading-snug cursor-pointer hover:text-stone-700 transition-colors" onclick="openDetailModal(${ad.id})">${ad.title}</h4>
                                <p class="text-sm text-stone-600 leading-relaxed">${ad.description}</p>
                                <div class="pt-4 border-t border-stone-100 flex gap-8">
                                    <div class="flex flex-col">
                                        <span class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-emerald-500 to-teal-700">+185%</span>
                                        <span class="text-[9px] uppercase tracking-widest text-stone-500 font-bold mt-1">Organic Traffic</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-2xl font-black bg-clip-text text-transparent bg-gradient-to-r from-emerald-500 to-teal-700">Top 3</span>
                                        <span class="text-[9px] uppercase tracking-widest text-stone-500 font-bold mt-1">SERP Rankings</span>
                                    </div>
                                </div>
                                <div class="mt-4 text-xs font-bold text-stone-900 cursor-pointer hover:text-emerald-700 flex items-center space-x-1 group-hover:translate-x-1 transition-transform w-fit" onclick="openDetailModal(${ad.id})">
                                    <span>Read Full Analysis</span>
                                    <span>&rarr;</span>
                                </div>
                            </div>
                        </div>
                    `;
                });

            } else if (selectedCategory === "Bug and malware clean") {
                // STRUCTURE 3: High-Contrast Security Incident Report View
                leadContainer.classList.add("hidden");
                adsGrid.className = "grid grid-cols-1 gap-6 animate-fade-in";

                filteredAds.forEach(ad => {
                    adsGrid.innerHTML += `
                        <div class="bg-stone-950 border border-stone-800 rounded-xl overflow-hidden flex flex-col relative group shadow-lg hover:shadow-[0_0_20px_rgba(220,38,38,0.15)] transition-all duration-300">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-red-600 to-amber-500"></div>
                            <div class="p-6 md:p-10 flex-1 grid md:grid-cols-3 gap-8 items-center bg-stone-950/90">
                                <div class="md:col-span-2 space-y-4 relative z-10">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                                        <div class="font-mono text-[10px] text-emerald-400 bg-emerald-400/10 border border-emerald-400/20 px-2 py-0.5 rounded tracking-widest uppercase">
                                            Status: Secured & Patched
                                        </div>
                                    </div>
                                    <h4 class="text-2xl font-bold text-white leading-snug cursor-pointer hover:text-stone-300 transition-colors" onclick="openDetailModal(${ad.id})">${ad.title}</h4>
                                    <div class="text-xs font-semibold text-amber-500 uppercase tracking-widest">Client: <span class="text-stone-300">${ad.bizName}</span></div>
                                    <p class="text-sm text-stone-400 leading-relaxed font-mono mt-2">${ad.description}</p>
                                    <button onclick="openDetailModal(${ad.id})" class="mt-6 px-5 py-2.5 bg-stone-900 border border-stone-700 text-stone-300 hover:bg-stone-800 hover:text-white hover:border-stone-500 text-xs font-bold transition-all rounded w-max font-mono flex items-center space-x-2">
                                        <span>View Incident Log</span>
                                        <span class="transform group-hover:translate-x-1 transition-transform">&rarr;</span>
                                    </button>
                                </div>
                                <div class="md:col-span-1 h-48 md:h-full min-h-[200px] relative rounded-lg overflow-hidden border border-stone-800 grayscale opacity-50 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700">
                                    <img src="${ad.image}" alt="${ad.bizName}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://placehold.co/600x400/1c1917/f5f5f4';">
                                    <div class="absolute inset-0 bg-stone-900/60 mix-blend-multiply group-hover:bg-stone-900/20 transition-all duration-700"></div>
                                </div>
                            </div>
                        </div>
                    `;
                });

            } else if (selectedCategory === "Migration and systemconfiguration") {
                // STRUCTURE 4: Enterprise Zig-Zag Case Study Layout
                leadContainer.classList.add("hidden");
                adsGrid.className = "flex flex-col space-y-12 mt-4 animate-fade-in";

                filteredAds.forEach((ad, index) => {
                    const isEven = index % 2 === 0;
                    adsGrid.innerHTML += `
                        <div class="flex flex-col ${isEven ? 'md:flex-row' : 'md:flex-row-reverse'} gap-10 items-center bg-gradient-to-br from-stone-50 to-white/95 p-6 md:p-10 rounded-2xl border border-stone-200 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-500 group">
                            <div class="w-full md:w-1/2 relative rounded-xl overflow-hidden shadow-lg h-72 md:h-[380px]">
                                <img src="${ad.image}" alt="${ad.bizName}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" onerror="this.onerror=null; this.src='https://placehold.co/600x600/f5f5f4/1c1917';">
                                <div class="absolute inset-0 border border-black/10 rounded-xl pointer-events-none"></div>
                            </div>
                            <div class="w-full md:w-1/2 space-y-5 ${isEven ? 'md:pr-10' : 'md:pl-10'}">
                                <div class="inline-flex items-center space-x-2 bg-white px-3.5 py-1.5 rounded-full border border-stone-200 shadow-sm">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-stone-600">Zero Downtime Migration</span>
                                </div>
                                <h4 class="editorial-title text-3xl font-extrabold text-stone-950 leading-tight">${ad.title}</h4>
                                <div class="text-[10px] font-bold text-stone-500 uppercase tracking-widest">Enterprise Client: <span class="text-stone-900">${ad.bizName}</span></div>
                                <p class="text-sm text-stone-600 leading-relaxed">${ad.description}</p>
                                <div class="pt-6 border-t border-stone-100">
                                    <button onclick="openDetailModal(${ad.id})" class="bg-stone-950 hover:bg-stone-800 text-white text-xs font-bold px-6 py-3 rounded shadow-md transition-all flex items-center space-x-2 group-hover:px-7">
                                        <span>Explore Architecture</span>
                                        <span>&rsaquo;</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
        }

        // Render Trending Sidebar Partners
        function renderPartnerList() {
            const container = document.getElementById("trending-partners-list");
            container.innerHTML = "";

            // Take first 3-4 top rated ads for partners preview
            const partners = [...adsData].sort((a,b) => b.rating - a.rating).slice(0, 3);

            partners.forEach(partner => {
                const partnerDiv = document.createElement("div");
                partnerDiv.className = "flex items-start space-x-3 cursor-pointer hover:bg-stone-50 p-1.5 rounded transition-all";
                partnerDiv.onclick = () => openDetailModal(partner.id);
                partnerDiv.innerHTML = `
                    <div class="w-11 h-11 rounded bg-stone-100 overflow-hidden shrink-0 border border-stone-200">
                        <img src="${partner.image}" alt="" class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-extrabold text-stone-950 truncate">${partner.bizName}</h4>
                            <div class="flex items-center text-[10px] text-amber-500">
                                <span class="font-bold">${partner.rating}</span>
                            </div>
                        </div>
                        <p class="text-[11px] text-stone-500 truncate mt-0.5">${partner.title}</p>
                    </div>
                `;
                container.appendChild(partnerDiv);
            });
        }


        // Submit Inquiry Lead Handler (VIA FORMSPREE)
        function submitLead() {
            const name = document.getElementById("lead-name").value;
            const email = document.getElementById("lead-email").value;
            const bizName = document.getElementById("detail-biz-name").textContent;

            if(!name || !email) {
                alertNotification("Please specify both a name and contact email to submit inquiry.");
                return;
            }

            // Using Fetch API to send data to Formspree without leaving the page
            fetch("https://formspree.io/f/xnjevpyo", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    Form_Type: "Direct Lead Inquiry",
                    Target_Business: bizName,
                    Lead_Name: name,
                    Lead_Email: email
                })
            })
            .then(response => {
                if (response.ok) {
                    alertNotification(`Excellent! Your request has been transmitted directly to ${bizName}.`);
                    // reset form elements
                    document.getElementById("lead-name").value = "";
                    document.getElementById("lead-email").value = "";
                    closeDetailModal();
                } else {
                    alertNotification("Oops! There was a problem sending your inquiry.");
                }
            })
            .catch(error => {
                console.error("Form Submit Error:", error);
                alertNotification("Oops! A network error occurred.");
            });
        }

        // Custom Simulated Toast Notification
        function alertNotification(message) {
            const toast = document.getElementById("toast-notif");
            const toastMsg = document.getElementById("toast-msg");

            toastMsg.textContent = message;
            toast.classList.remove("hidden");
            toast.classList.add("flex");

            setTimeout(() => {
                toast.classList.remove("flex");
                toast.classList.add("hidden");
            }, 4000);
        }

        // Submit Ad Modal Controls
        function openAdModal() {
            document.getElementById("submit-ad-modal").classList.remove("hidden");
            document.getElementById("submit-ad-modal").classList.add("flex");
            document.body.style.overflow = "hidden"; // Disable background scrolling
        }

        function closeAdModal() {
            document.getElementById("submit-ad-modal").classList.add("hidden");
            document.getElementById("submit-ad-modal").classList.remove("flex");
            document.body.style.overflow = "auto";
        }

        // Detail View Modal Controls
        function openDetailModal(adId) {
            const ad = adsData.find(item => item.id === adId);
            if (!ad) return;

            document.getElementById("detail-img").src = ad.image;
            document.getElementById("detail-badge").textContent = ad.category;
            document.getElementById("detail-biz-name").textContent = ad.bizName;
            document.getElementById("detail-location").textContent = ad.location;
            document.getElementById("detail-title").textContent = ad.title;
            document.getElementById("detail-desc").textContent = ad.description;
            document.getElementById("detail-pricing").textContent = ad.pricing || "Inquire for quote estimation";
            document.getElementById("detail-contact").textContent = `Contact: ${ad.contact}`;

            // Check if live link exists and show/hide button accordingly
            const liveLinkContainer = document.getElementById("live-link-container");
            const liveLinkBtn = document.getElementById("detail-live-link");
            if (ad.liveLink) {
                liveLinkBtn.href = ad.liveLink.startsWith('http') ? ad.liveLink : 'http://' + ad.liveLink;
                liveLinkContainer.classList.remove("hidden");
            } else {
                liveLinkContainer.classList.add("hidden");
            }

            document.getElementById("detail-modal").classList.remove("hidden");
            document.getElementById("detail-modal").classList.add("flex");
            document.body.style.overflow = "hidden";
        }

        function closeDetailModal() {
            document.getElementById("detail-modal").classList.add("hidden");
            document.getElementById("detail-modal").classList.remove("flex");
            document.body.style.overflow = "auto";
        }

        // Handle SEO Service Request (VIA FORMSPREE)
        function handleSEOServiceRequest(event) {
            event.preventDefault();

            const bizName = document.getElementById("req-biz-name").value;
            const website = document.getElementById("req-website").value;
            const goals = document.getElementById("req-goals").value;
            const issues = document.getElementById("req-issues").value;
            const location = document.getElementById("req-location").value;
            const email = document.getElementById("req-contact-email").value;
            const urgent = document.getElementById("req-urgent").checked ? "Yes" : "No";

            fetch("https://formspree.io/f/xnjevpyo", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    Form_Type: "SEO Audit Request",
                    Company_Name: bizName,
                    Website_URL: website,
                    SEO_Goals: goals,
                    Technical_Issues: issues,
                    Location: location,
                    Contact_Email: email,
                    Is_Urgent: urgent
                })
            })
            .then(response => {
                if(response.ok) {
                    // Clean/Reset Form fields
                    document.getElementById("add-listing-form").reset();
                    closeAdModal();
                    // Notify user of immediate submission
                    alertNotification(`Thank you! Your SEO audit request for "${bizName}" has been received. Our technical team will contact you shortly.`);
                } else {
                    alertNotification("Oops! There was a problem submitting your request.");
                }
            })
            .catch(error => {
                console.error("Form Submit Error:", error);
                alertNotification("Oops! A network error occurred.");
            });
        }

        // Setup some subtle scroll animation cues
        function setupIntersectionObservers() {
            // Optional addition for subtle loading states as ads are rendered
            const observerOptions = {
                root: null,
                threshold: 0.1
            };

            const observer = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('opacity-100', 'translate-y-0');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);
        }

        // Smooth Volume Fade Logic based on Scroll Position
        let audioFadeInterval;

        function fadeVolume(video, targetVolume, duration = 800) {
            clearInterval(audioFadeInterval);

            const startVolume = video.volume;
            const volumeChange = targetVolume - startVolume;
            const stepTime = 50; // ms per step
            const steps = duration / stepTime;
            const volumeStep = volumeChange / steps;

            let currentStep = 0;

            if (volumeChange === 0) return;

            audioFadeInterval = setInterval(() => {
                currentStep++;
                let newVolume = startVolume + (volumeStep * currentStep);

                // Clamp volume between 0 and 1 to prevent errors
                newVolume = Math.max(0, Math.min(1, newVolume));

                video.volume = newVolume;

                if (currentStep >= steps || video.volume === targetVolume) {
                    clearInterval(audioFadeInterval);
                    video.volume = targetVolume;
                }
            }, stepTime);
        }

        function setupVideoAudioFade() {
            const anchor = document.getElementById('video-anchor');
            const video = document.getElementById('hero-video');

            if (!anchor || !video) return;

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    // Check if we scrolled past the anchor (y is negative, meaning it's above viewport)
                    if (!entry.isIntersecting && entry.boundingClientRect.y < 0) {
                       // Fade volume out when scrolled down
                       fadeVolume(video, 0.0);
                    }
                    // If anchor comes back into view
                    else if (entry.isIntersecting) {
                        // Fade volume back in when visible
                        fadeVolume(video, 1.0);
                    }
                });
            }, {
                threshold: 0,
                rootMargin: "0px"
            });

            observer.observe(anchor);
        }

        // Handle the new sidebar contact form submission (VIA FORMSPREE)
        function handleContactFormSubmit(event) {
            event.preventDefault();

            const name = document.getElementById("contact-name").value;
            const email = document.getElementById("contact-email").value;
            const message = document.getElementById("contact-message").value;

            fetch("https://formspree.io/f/xnjevpyo", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    Form_Type: "General Contact / Inquiry",
                    Name: name,
                    Email: email,
                    Message: message
                })
            })
            .then(response => {
                if(response.ok) {
                    alertNotification(`Thanks ${name}, we have received your message and will reply shortly.`);
                    // Reset the form
                    document.getElementById("contact-name").value = "";
                    document.getElementById("contact-email").value = "";
                    document.getElementById("contact-message").value = "";
                } else {
                    alertNotification("Oops! There was a problem sending your message.");
                }
            })
            .catch(error => {
                console.error("Form Submit Error:", error);
                alertNotification("Oops! A network error occurred.");
            });
        }
    </script>
</body>
</html>
