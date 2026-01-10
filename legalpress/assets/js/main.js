/**
 * LegalPress Theme - Main JavaScript
 * Modern Legal News Theme
 * @version 2.0.0
 */

(function () {
	"use strict";

	// ==========================================================================
	// UTILITY FUNCTIONS
	// ==========================================================================

	const utils = {
		/**
		 * Debounce function
		 */
		debounce(func, wait = 100) {
			let timeout;
			return function (...args) {
				clearTimeout(timeout);
				timeout = setTimeout(() => func.apply(this, args), wait);
			};
		},

		/**
		 * Throttle function
		 */
		throttle(func, limit = 100) {
			let inThrottle;
			return function (...args) {
				if (!inThrottle) {
					func.apply(this, args);
					inThrottle = true;
					setTimeout(() => (inThrottle = false), limit);
				}
			};
		},

		/**
		 * Check if element is in viewport
		 */
		isInViewport(element, offset = 0) {
			const rect = element.getBoundingClientRect();
			return rect.top <= window.innerHeight - offset && rect.bottom >= offset;
		},

		/**
		 * Check if prefers reduced motion
		 */
		prefersReducedMotion() {
			return window.matchMedia("(prefers-reduced-motion: reduce)").matches;
		},
	};

	// ==========================================================================
	// THEME TOGGLE (Light/Dark Mode)
	// ==========================================================================

	const themeToggle = {
		init() {
			this.toggle = document.querySelector(".theme-toggle");
			if (!this.toggle) return;

			// Check saved preference or system preference
			this.setInitialTheme();

			// Toggle event
			this.toggle.addEventListener("click", () => this.toggleTheme());
		},

		setInitialTheme() {
			const savedTheme = localStorage.getItem("legalpress-theme");
			const prefersDark = window.matchMedia(
				"(prefers-color-scheme: dark)"
			).matches;

			if (savedTheme) {
				document.documentElement.setAttribute("data-theme", savedTheme);
			} else if (prefersDark) {
				document.documentElement.setAttribute("data-theme", "dark");
			}
		},

		toggleTheme() {
			const currentTheme = document.documentElement.getAttribute("data-theme");
			const newTheme = currentTheme === "dark" ? "light" : "dark";

			document.documentElement.setAttribute("data-theme", newTheme);
			localStorage.setItem("legalpress-theme", newTheme);
		},
	};

	// ==========================================================================
	// HEADER BEHAVIOR
	// ==========================================================================

	const header = {
		init() {
			this.header = document.querySelector(".site-header");
			if (!this.header) return;

			this.lastScrollY = 0;
			this.ticking = false;

			window.addEventListener("scroll", () => {
				if (!this.ticking) {
					window.requestAnimationFrame(() => {
						this.onScroll();
						this.ticking = false;
					});
					this.ticking = true;
				}
			});
		},

		onScroll() {
			const scrollY = window.scrollY;

			// Add scrolled class
			if (scrollY > 50) {
				this.header.classList.add("header-scrolled");
			} else {
				this.header.classList.remove("header-scrolled");
			}

			// Hide/show on scroll (optional)
			if (scrollY > this.lastScrollY && scrollY > 200) {
				this.header.classList.add("header-hidden");
			} else {
				this.header.classList.remove("header-hidden");
			}

			this.lastScrollY = scrollY;
		},
	};

	// ==========================================================================
	// MOBILE MENU
	// ==========================================================================

	const mobileMenu = {
		init() {
			this.toggle = document.querySelector(".mobile-menu-toggle");
			this.menu = document.querySelector(".mobile-navigation");

			if (!this.toggle || !this.menu) return;

			this.toggle.addEventListener("click", () => this.toggleMenu());

			// Submenu toggles
			const submenuToggles = this.menu.querySelectorAll(
				".mobile-submenu-toggle"
			);
			submenuToggles.forEach((toggle) => {
				toggle.addEventListener("click", (e) => {
					e.preventDefault();
					this.toggleSubmenu(toggle);
				});
			});

			// Close on escape
			document.addEventListener("keydown", (e) => {
				if (e.key === "Escape" && this.menu.classList.contains("is-open")) {
					this.closeMenu();
				}
			});
		},

		toggleMenu() {
			const isOpen = this.menu.classList.contains("is-open");

			if (isOpen) {
				this.closeMenu();
			} else {
				this.openMenu();
			}
		},

		openMenu() {
			this.menu.classList.add("is-open");
			this.toggle.classList.add("is-active");
			this.toggle.setAttribute("aria-expanded", "true");
			document.body.style.overflow = "hidden";
		},

		closeMenu() {
			this.menu.classList.remove("is-open");
			this.toggle.classList.remove("is-active");
			this.toggle.setAttribute("aria-expanded", "false");
			document.body.style.overflow = "";
		},

		toggleSubmenu(toggle) {
			const submenu = toggle.closest(".menu-item").querySelector(".sub-menu");
			if (!submenu) return;

			const isOpen = submenu.classList.contains("is-open");

			if (isOpen) {
				submenu.classList.remove("is-open");
				toggle.classList.remove("is-open");
			} else {
				submenu.classList.add("is-open");
				toggle.classList.add("is-open");
			}
		},
	};

	// ==========================================================================
	// SEARCH OVERLAY
	// ==========================================================================

	const searchOverlay = {
		init() {
			this.overlay = document.querySelector(".search-overlay");
			this.toggle = document.querySelector(".search-toggle");
			this.closeBtn = document.querySelector(".search-overlay-close");
			this.searchInput = document.querySelector(
				".search-overlay .search-field"
			);

			if (!this.overlay) return;

			if (this.toggle) {
				this.toggle.addEventListener("click", () => this.open());
			}

			if (this.closeBtn) {
				this.closeBtn.addEventListener("click", () => this.close());
			}

			// Close on escape
			document.addEventListener("keydown", (e) => {
				if (e.key === "Escape" && this.overlay.classList.contains("is-open")) {
					this.close();
				}
			});

			// Close on overlay click
			this.overlay.addEventListener("click", (e) => {
				if (e.target === this.overlay) {
					this.close();
				}
			});
		},

		open() {
			this.overlay.classList.add("is-open");
			document.body.style.overflow = "hidden";

			if (this.searchInput) {
				setTimeout(() => this.searchInput.focus(), 100);
			}
		},

		close() {
			this.overlay.classList.remove("is-open");
			document.body.style.overflow = "";
		},
	};

	// ==========================================================================
	// SCROLL REVEAL ANIMATIONS
	// ==========================================================================

	const scrollReveal = {
		init() {
			if (utils.prefersReducedMotion()) return;

			this.elements = document.querySelectorAll("[data-reveal]");
			if (!this.elements.length) return;

			// Create IntersectionObserver
			this.observer = new IntersectionObserver(
				(entries) => {
					entries.forEach((entry) => {
						if (entry.isIntersecting) {
							entry.target.classList.add("is-revealed");
							this.observer.unobserve(entry.target);
						}
					});
				},
				{
					threshold: 0.1,
					rootMargin: "0px 0px -50px 0px",
				}
			);

			// Observe elements
			this.elements.forEach((el) => {
				this.observer.observe(el);
			});
		},
	};

	// ==========================================================================
	// BACK TO TOP BUTTON
	// ==========================================================================

	const backToTop = {
		init() {
			this.button = document.querySelector(".back-to-top");
			if (!this.button) return;

			// Show/hide based on scroll
			window.addEventListener(
				"scroll",
				utils.throttle(() => {
					if (window.scrollY > 500) {
						this.button.classList.add("is-visible");
					} else {
						this.button.classList.remove("is-visible");
					}
				}, 100)
			);

			// Scroll to top on click
			this.button.addEventListener("click", (e) => {
				e.preventDefault();
				window.scrollTo({
					top: 0,
					behavior: "smooth",
				});
			});
		},
	};

	// ==========================================================================
	// SKELETON LOADING
	// ==========================================================================

	const skeletonLoader = {
		init() {
			// Hide skeletons when content is loaded
			const skeletons = document.querySelectorAll(".skeleton-container");
			const content = document.querySelectorAll(".content-container");

			if (skeletons.length && content.length) {
				// Simulate content load
				window.addEventListener("load", () => {
					setTimeout(() => {
						skeletons.forEach((skeleton) => {
							skeleton.style.display = "none";
						});
						content.forEach((c) => {
							c.classList.add("is-loaded");
						});
					}, 300);
				});
			}
		},

		/**
		 * Show skeleton for an element
		 */
		show(container) {
			const skeleton = container.querySelector(".skeleton-container");
			const content = container.querySelector(".content-container");

			if (skeleton) skeleton.style.display = "block";
			if (content) content.classList.remove("is-loaded");
		},

		/**
		 * Hide skeleton for an element
		 */
		hide(container) {
			const skeleton = container.querySelector(".skeleton-container");
			const content = container.querySelector(".content-container");

			if (skeleton) skeleton.style.display = "none";
			if (content) content.classList.add("is-loaded");
		},
	};

	// ==========================================================================
	// IMAGE LAZY LOADING
	// ==========================================================================

	const lazyLoad = {
		init() {
			const images = document.querySelectorAll("img[data-src]");
			if (!images.length) return;

			if ("IntersectionObserver" in window) {
				this.observer = new IntersectionObserver(
					(entries) => {
						entries.forEach((entry) => {
							if (entry.isIntersecting) {
								this.loadImage(entry.target);
								this.observer.unobserve(entry.target);
							}
						});
					},
					{
						rootMargin: "50px 0px",
					}
				);

				images.forEach((img) => this.observer.observe(img));
			} else {
				// Fallback for older browsers
				images.forEach((img) => this.loadImage(img));
			}
		},

		loadImage(img) {
			const src = img.getAttribute("data-src");
			if (!src) return;

			img.src = src;
			img.removeAttribute("data-src");
			img.classList.add("is-loaded");
		},
	};

	// ==========================================================================
	// SMOOTH SCROLL FOR ANCHOR LINKS
	// ==========================================================================

	const smoothScroll = {
		init() {
			document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
				anchor.addEventListener("click", (e) => {
					const href = anchor.getAttribute("href");
					if (href === "#") return;

					const target = document.querySelector(href);
					if (target) {
						e.preventDefault();
						const headerHeight =
							document.querySelector(".site-header")?.offsetHeight || 0;
						const targetPosition =
							target.getBoundingClientRect().top +
							window.scrollY -
							headerHeight -
							20;

						window.scrollTo({
							top: targetPosition,
							behavior: "smooth",
						});
					}
				});
			});
		},
	};

	// ==========================================================================
	// NEWSLETTER FORM
	// ==========================================================================

	const newsletterForm = {
		init() {
			const forms = document.querySelectorAll(".newsletter-form");

			forms.forEach((form) => {
				form.addEventListener("submit", (e) => {
					e.preventDefault();
					this.handleSubmit(form);
				});
			});
		},

		handleSubmit(form) {
			const email = form.querySelector(".newsletter-input");
			const submit = form.querySelector(".newsletter-submit");

			if (!email || !email.value) return;

			// Validate email
			if (!this.validateEmail(email.value)) {
				email.classList.add("error");
				return;
			}

			email.classList.remove("error");
			submit.disabled = true;
			submit.textContent = "Subscribing...";

			// Simulate API call (replace with actual implementation)
			setTimeout(() => {
				submit.textContent = "Subscribed!";
				email.value = "";

				setTimeout(() => {
					submit.disabled = false;
					submit.textContent = "Subscribe";
				}, 2000);
			}, 1000);
		},

		validateEmail(email) {
			return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
		},
	};

	// ==========================================================================
	// COPY LINK BUTTON
	// ==========================================================================

	const copyLink = {
		init() {
			const buttons = document.querySelectorAll(".share-btn-copy");

			buttons.forEach((btn) => {
				btn.addEventListener("click", (e) => {
					e.preventDefault();
					this.copyToClipboard(window.location.href);

					// Show feedback
					const originalTitle = btn.getAttribute("title");
					btn.setAttribute("title", "Copied!");
					setTimeout(() => {
						btn.setAttribute("title", originalTitle || "Copy link");
					}, 2000);
				});
			});
		},

		copyToClipboard(text) {
			if (navigator.clipboard) {
				navigator.clipboard.writeText(text);
			} else {
				// Fallback
				const textarea = document.createElement("textarea");
				textarea.value = text;
				document.body.appendChild(textarea);
				textarea.select();
				document.execCommand("copy");
				document.body.removeChild(textarea);
			}
		},
	};

	// ==========================================================================
	// READING PROGRESS BAR
	// ==========================================================================

	const readingProgress = {
		init() {
			const progressBar = document.querySelector(".reading-progress-bar");
			const article = document.querySelector(".entry-content");

			if (!progressBar || !article) return;

			window.addEventListener(
				"scroll",
				utils.throttle(() => {
					const articleTop = article.offsetTop;
					const articleHeight = article.offsetHeight;
					const windowHeight = window.innerHeight;
					const scrollY = window.scrollY;

					const progress = Math.max(
						0,
						Math.min(
							100,
							((scrollY - articleTop + windowHeight) / articleHeight) * 100
						)
					);

					progressBar.style.width = `${progress}%`;
				}, 16)
			);
		},
	};

	// ==========================================================================
	// INITIALIZE
	// ==========================================================================

	document.addEventListener("DOMContentLoaded", () => {
		themeToggle.init();
		header.init();
		mobileMenu.init();
		searchOverlay.init();
		scrollReveal.init();
		backToTop.init();
		skeletonLoader.init();
		lazyLoad.init();
		smoothScroll.init();
		newsletterForm.init();
		copyLink.init();
		readingProgress.init();
	});

	// Expose utilities for external use
	window.LegalPress = {
		utils,
		skeletonLoader,
	};
})();
