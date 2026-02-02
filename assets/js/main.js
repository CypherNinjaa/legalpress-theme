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
				"(prefers-color-scheme: dark)",
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
				".mobile-submenu-toggle",
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
	// SEARCH OVERLAY WITH LIVE SUGGESTIONS
	// ==========================================================================

	const searchOverlay = {
		init() {
			this.overlay = document.querySelector(".search-overlay");
			this.toggle = document.querySelector(".search-toggle");
			this.closeBtn = document.querySelector(".search-overlay__close");
			this.backdrop = document.querySelector(".search-overlay__backdrop");
			this.searchInput = document.querySelector(".search-overlay__input");
			this.form = document.querySelector(".search-overlay__form");
			this.resultsContainer = null;
			this.debounceTimer = null;
			this.isLoading = false;

			if (!this.overlay) return;

			// Create results container
			this.createResultsContainer();

			// Set max length on input
			if (this.searchInput && typeof legalpressSearch !== "undefined") {
				this.searchInput.maxLength = legalpressSearch.maxLength || 100;
			}

			if (this.toggle) {
				this.toggle.addEventListener("click", () => this.open());
			}

			if (this.closeBtn) {
				this.closeBtn.addEventListener("click", () => this.close());
			}

			if (this.backdrop) {
				this.backdrop.addEventListener("click", () => this.close());
			}

			// Live search on input
			if (this.searchInput) {
				this.searchInput.addEventListener("input", (e) => {
					this.handleInput(e.target.value);
				});
			}

			// Close on escape key
			document.addEventListener("keydown", (e) => {
				if (
					e.key === "Escape" &&
					this.overlay.classList.contains("is-active")
				) {
					this.close();
				}
			});
		},

		createResultsContainer() {
			const content = this.overlay?.querySelector(".search-overlay__content");
			if (!content) return;

			this.resultsContainer = document.createElement("div");
			this.resultsContainer.className = "search-results";
			this.resultsContainer.innerHTML = "";
			content.appendChild(this.resultsContainer);
		},

		handleInput(query) {
			// Clear previous timer
			clearTimeout(this.debounceTimer);

			// Clear results if query too short
			const minChars =
				(typeof legalpressSearch !== "undefined" ?
					legalpressSearch.minChars
				:	3) || 3;

			if (query.length < minChars) {
				this.resultsContainer.innerHTML = "";
				return;
			}

			// Debounce: wait 300ms after user stops typing
			this.debounceTimer = setTimeout(() => {
				this.performSearch(query);
			}, 300);
		},

		async performSearch(query) {
			if (this.isLoading || typeof legalpressSearch === "undefined") return;

			this.isLoading = true;
			this.showLoading();

			const formData = new FormData();
			formData.append("action", "legalpress_search");
			formData.append("nonce", legalpressSearch.nonce);
			formData.append("query", query);

			try {
				const response = await fetch(legalpressSearch.ajaxUrl, {
					method: "POST",
					body: formData,
				});

				const data = await response.json();

				if (data.success && data.data.results) {
					this.displayResults(data.data.results, data.data.total, query);
				} else {
					this.resultsContainer.innerHTML = "";
				}
			} catch (error) {
				console.error("[Search] Error:", error);
				this.resultsContainer.innerHTML =
					'<p class="search-results__error">Search failed. Please try again.</p>';
			}

			this.isLoading = false;
		},

		showLoading() {
			this.resultsContainer.innerHTML = `
				<div class="search-results__loading">
					<svg class="spin" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
						<circle cx="12" cy="12" r="10" opacity="0.3"/>
						<path d="M12 2a10 10 0 0 1 10 10"/>
					</svg>
					<span>Searching...</span>
				</div>
			`;
		},

		displayResults(results, total, query) {
			if (results.length === 0) {
				this.resultsContainer.innerHTML = `
					<div class="search-results__empty">
						<p>No results found for "<strong>${this.escapeHtml(query)}</strong>"</p>
						<p class="search-results__tip">Try different keywords or check your spelling</p>
					</div>
				`;
				return;
			}

			let html = `<div class="search-results__header">
				<span>${total} result${total !== 1 ? "s" : ""} found</span>
			</div>
			<ul class="search-results__list">`;

			results.forEach((result) => {
				html += `
					<li class="search-results__item">
						<a href="${result.url}" class="search-results__link">
							${result.thumbnail ? `<img src="${result.thumbnail}" alt="" class="search-results__thumb" loading="lazy"/>` : ""}
							<div class="search-results__info">
								${result.category ? `<span class="search-results__category">${this.escapeHtml(result.category)}</span>` : ""}
								<h4 class="search-results__title">${this.escapeHtml(result.title)}</h4>
								<p class="search-results__excerpt">${this.escapeHtml(result.excerpt)}</p>
								<span class="search-results__date">${result.date}</span>
							</div>
						</a>
					</li>
				`;
			});

			html += `</ul>`;

			if (total > results.length) {
				html += `<div class="search-results__footer">
					<button type="submit" class="search-results__view-all">View all ${total} results</button>
				</div>`;
			}

			this.resultsContainer.innerHTML = html;
		},

		escapeHtml(text) {
			const div = document.createElement("div");
			div.textContent = text;
			return div.innerHTML;
		},

		open() {
			this.overlay.classList.add("is-active");
			this.overlay.setAttribute("aria-hidden", "false");
			document.body.style.overflow = "hidden";

			if (this.searchInput) {
				setTimeout(() => this.searchInput.focus(), 100);
			}
		},

		close() {
			this.overlay.classList.remove("is-active");
			this.overlay.setAttribute("aria-hidden", "true");
			document.body.style.overflow = "";

			// Clear search on close
			if (this.searchInput) {
				this.searchInput.value = "";
			}
			if (this.resultsContainer) {
				this.resultsContainer.innerHTML = "";
			}
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
				},
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
				}, 100),
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
					},
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
			const buttons = document.querySelectorAll(".share-btn--copy");

			buttons.forEach((btn) => {
				btn.addEventListener("click", (e) => {
					e.preventDefault();
					const url = btn.dataset.url || window.location.href;
					this.copyToClipboard(url, btn);
				});
			});
		},

		copyToClipboard(text, btn) {
			const copyFn = () => {
				// Show feedback
				btn.classList.add("copied");

				const iconCopy = btn.querySelector(".icon-copy");
				const iconCheck = btn.querySelector(".icon-check");
				const copyText = btn.querySelector(".copy-text");

				if (iconCopy) iconCopy.style.display = "none";
				if (iconCheck) iconCheck.style.display = "block";
				if (copyText) copyText.textContent = "Copied!";

				setTimeout(() => {
					btn.classList.remove("copied");
					if (iconCopy) iconCopy.style.display = "block";
					if (iconCheck) iconCheck.style.display = "none";
					if (copyText) copyText.textContent = "Copy";
				}, 2000);
			};

			if (navigator.clipboard) {
				navigator.clipboard.writeText(text).then(copyFn);
			} else {
				// Fallback
				const textarea = document.createElement("textarea");
				textarea.value = text;
				document.body.appendChild(textarea);
				textarea.select();
				document.execCommand("copy");
				document.body.removeChild(textarea);
				copyFn();
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
							((scrollY - articleTop + windowHeight) / articleHeight) * 100,
						),
					);

					progressBar.style.width = `${progress}%`;
				}, 16),
			);
		},
	};

	// ==========================================================================
	// POST CARD CLICK (Mobile Support)
	// ==========================================================================

	const postCardClick = {
		init() {
			// Handle post card clicks for mobile
			document.querySelectorAll(".post-card").forEach((card) => {
				const href = card.getAttribute("data-href");
				if (!href) return;

				// Add click handler to the entire card for mobile
				card.addEventListener("click", (e) => {
					// Don't navigate if clicking on a link, category badge, or other interactive element
					if (e.target.closest("a:not(.post-card-link-overlay)")) {
						return;
					}

					// Navigate to the post
					window.location.href = href;
				});

				// Also handle touch events explicitly for iOS
				card.addEventListener("touchend", (e) => {
					// Only if not scrolling and not tapping a link
					if (e.target.closest("a:not(.post-card-link-overlay)")) {
						return;
					}

					// Small delay to distinguish from scroll
					const touch = e.changedTouches[0];
					if (touch) {
						// Don't preventDefault here to allow normal link behavior
					}
				});
			});
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
		postCardClick.init();
	});

	// Expose utilities for external use
	window.LegalPress = {
		utils,
		skeletonLoader,
	};
})();
