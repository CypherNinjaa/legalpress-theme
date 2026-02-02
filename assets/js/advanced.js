/**
 * LegalPress Advanced JavaScript
 * Beautiful animations, skeleton loading, and modern interactions
 */

(function () {
	"use strict";

	// ==========================================================================
	// CONFIGURATION
	// ==========================================================================

	const CONFIG = {
		animationThreshold: 0.15,
		animationRootMargin: "0px 0px -50px 0px",
		skeletonMinDuration: 800,
		scrollOffset: 100,
		magneticStrength: 0.3,
		parallaxSpeed: 0.5,
	};

	// ==========================================================================
	// UTILITY FUNCTIONS
	// ==========================================================================

	const debounce = (func, wait = 20) => {
		let timeout;
		return function executedFunction(...args) {
			const later = () => {
				clearTimeout(timeout);
				func(...args);
			};
			clearTimeout(timeout);
			timeout = setTimeout(later, wait);
		};
	};

	const throttle = (func, limit = 100) => {
		let inThrottle;
		return function (...args) {
			if (!inThrottle) {
				func.apply(this, args);
				inThrottle = true;
				setTimeout(() => (inThrottle = false), limit);
			}
		};
	};

	const lerp = (start, end, factor) => start + (end - start) * factor;

	// ==========================================================================
	// SCROLL REVEAL ANIMATIONS
	// ==========================================================================

	class ScrollReveal {
		constructor() {
			this.elements = document.querySelectorAll(
				"[data-animate], .reveal, .reveal-left, .reveal-right, .reveal-scale"
			);
			if (this.elements.length === 0) return;
			this.init();
		}

		init() {
			const options = {
				threshold: CONFIG.animationThreshold,
				rootMargin: CONFIG.animationRootMargin,
			};

			this.observer = new IntersectionObserver((entries) => {
				entries.forEach((entry, index) => {
					if (entry.isIntersecting) {
						// Add stagger delay based on element index in view
						const delay = index * 100;
						setTimeout(() => {
							entry.target.classList.add("animated", "revealed");

							// Get animation type from data attribute
							const animationType = entry.target.dataset.animate;
							if (animationType) {
								entry.target.classList.add(`animate-${animationType}`);
							}
						}, delay);

						this.observer.unobserve(entry.target);
					}
				});
			}, options);

			this.elements.forEach((el) => this.observer.observe(el));
		}
	}

	// ==========================================================================
	// SKELETON LOADING
	// ==========================================================================

	class SkeletonLoader {
		constructor() {
			this.skeletons = document.querySelectorAll("[data-skeleton]");
			this.init();
		}

		init() {
			// Show skeletons on page load
			document.addEventListener("DOMContentLoaded", () => {
				this.showSkeletons();

				// Hide skeletons after content loads
				window.addEventListener("load", () => {
					setTimeout(() => this.hideSkeletons(), CONFIG.skeletonMinDuration);
				});
			});
		}

		showSkeletons() {
			this.skeletons.forEach((skeleton) => {
				skeleton.style.display = "block";
				const target = document.querySelector(skeleton.dataset.skeleton);
				if (target) {
					target.style.opacity = "0";
				}
			});
		}

		hideSkeletons() {
			this.skeletons.forEach((skeleton) => {
				skeleton.style.opacity = "0";
				skeleton.style.transition = "opacity 0.3s ease";

				setTimeout(() => {
					skeleton.style.display = "none";
					const target = document.querySelector(skeleton.dataset.skeleton);
					if (target) {
						target.style.opacity = "1";
						target.style.transition = "opacity 0.5s ease";
					}
				}, 300);
			});
		}

		// Create skeleton dynamically
		static createSkeleton(type = "card") {
			const templates = {
				card: `
                    <div class="skeleton-card">
                        <div class="skeleton skeleton-card__image"></div>
                        <div class="skeleton-card__content">
                            <div class="skeleton skeleton-card__category"></div>
                            <div class="skeleton skeleton-card__title"></div>
                            <div class="skeleton skeleton-card__title-2"></div>
                            <div class="skeleton skeleton-card__text"></div>
                            <div class="skeleton skeleton-card__text"></div>
                            <div class="skeleton-card__meta">
                                <div class="skeleton skeleton-card__meta-item"></div>
                                <div class="skeleton skeleton-card__meta-item"></div>
                            </div>
                        </div>
                    </div>
                `,
				hero: `
                    <div class="skeleton-hero">
                        <div class="skeleton-hero__content">
                            <div class="skeleton skeleton-hero__category"></div>
                            <div class="skeleton skeleton-hero__title"></div>
                            <div class="skeleton skeleton-hero__title-2"></div>
                            <div class="skeleton-hero__meta">
                                <div class="skeleton skeleton-hero__meta-item"></div>
                                <div class="skeleton skeleton-hero__meta-item"></div>
                            </div>
                        </div>
                    </div>
                `,
				listItem: `
                    <div class="skeleton-list-item">
                        <div class="skeleton skeleton-list-item__image"></div>
                        <div class="skeleton-list-item__content">
                            <div class="skeleton skeleton-list-item__title"></div>
                            <div class="skeleton skeleton-list-item__text"></div>
                            <div class="skeleton skeleton-list-item__meta"></div>
                        </div>
                    </div>
                `,
			};

			const container = document.createElement("div");
			container.innerHTML = templates[type] || templates.card;
			return container.firstElementChild;
		}
	}

	// ==========================================================================
	// ENHANCED HEADER
	// ==========================================================================

	class EnhancedHeader {
		constructor() {
			this.header = document.querySelector(".site-header");
			if (!this.header) return;

			this.lastScroll = 0;
			this.scrollThreshold = 100;
			this.init();
		}

		init() {
			window.addEventListener(
				"scroll",
				throttle(() => this.handleScroll(), 10)
			);
		}

		handleScroll() {
			const currentScroll = window.pageYOffset;

			// Add/remove scrolled class
			if (currentScroll > this.scrollThreshold) {
				this.header.classList.add("scrolled");
			} else {
				this.header.classList.remove("scrolled");
			}

			// Hide/show on scroll direction
			if (currentScroll > this.lastScroll && currentScroll > 300) {
				this.header.style.transform = "translateY(-100%)";
			} else {
				this.header.style.transform = "translateY(0)";
			}

			this.lastScroll = currentScroll;
		}
	}

	// ==========================================================================
	// MOBILE MENU
	// ==========================================================================

	class MobileMenu {
		constructor() {
			this.toggle = document.querySelector(".mobile-menu-toggle");
			this.menu = document.querySelector(".mobile-menu, .primary-nav");
			this.body = document.body;

			if (!this.toggle || !this.menu) return;
			this.init();
		}

		init() {
			this.toggle.addEventListener("click", () => this.toggleMenu());

			// Close on escape
			document.addEventListener("keydown", (e) => {
				if (e.key === "Escape" && this.isOpen()) {
					this.closeMenu();
				}
			});

			// Close on outside click
			document.addEventListener("click", (e) => {
				if (
					this.isOpen() &&
					!this.menu.contains(e.target) &&
					!this.toggle.contains(e.target)
				) {
					this.closeMenu();
				}
			});
		}

		toggleMenu() {
			if (this.isOpen()) {
				this.closeMenu();
			} else {
				this.openMenu();
			}
		}

		openMenu() {
			this.menu.classList.add("active", "animate-fade-in-down");
			this.toggle.classList.add("active");
			this.toggle.setAttribute("aria-expanded", "true");
			this.body.style.overflow = "hidden";
		}

		closeMenu() {
			this.menu.classList.remove("active", "animate-fade-in-down");
			this.toggle.classList.remove("active");
			this.toggle.setAttribute("aria-expanded", "false");
			this.body.style.overflow = "";
		}

		isOpen() {
			return this.menu.classList.contains("active");
		}
	}

	// ==========================================================================
	// MAGNETIC BUTTONS
	// ==========================================================================

	class MagneticElements {
		constructor() {
			this.elements = document.querySelectorAll(".btn, .hover-magnetic");
			if (this.elements.length === 0) return;
			this.init();
		}

		init() {
			this.elements.forEach((el) => {
				el.addEventListener("mousemove", (e) => this.handleMouseMove(e, el));
				el.addEventListener("mouseleave", (e) => this.handleMouseLeave(e, el));
			});
		}

		handleMouseMove(e, el) {
			const rect = el.getBoundingClientRect();
			const x = e.clientX - rect.left - rect.width / 2;
			const y = e.clientY - rect.top - rect.height / 2;

			el.style.transform = `translate(${x * CONFIG.magneticStrength}px, ${
				y * CONFIG.magneticStrength
			}px)`;
		}

		handleMouseLeave(e, el) {
			el.style.transform = "translate(0, 0)";
		}
	}

	// ==========================================================================
	// SMOOTH SCROLL
	// ==========================================================================

	class SmoothScroll {
		constructor() {
			this.links = document.querySelectorAll('a[href^="#"]');
			if (this.links.length === 0) return;
			this.init();
		}

		init() {
			this.links.forEach((link) => {
				link.addEventListener("click", (e) => this.handleClick(e, link));
			});
		}

		handleClick(e, link) {
			const href = link.getAttribute("href");
			if (href === "#") return;

			const target = document.querySelector(href);
			if (!target) return;

			e.preventDefault();

			const offset = CONFIG.scrollOffset;
			const targetPosition =
				target.getBoundingClientRect().top + window.pageYOffset - offset;

			window.scrollTo({
				top: targetPosition,
				behavior: "smooth",
			});

			// Update URL without jumping
			history.pushState(null, null, href);
		}
	}

	// ==========================================================================
	// READING PROGRESS BAR
	// ==========================================================================

	class ReadingProgress {
		constructor() {
			this.progressBar = document.querySelector(".reading-progress");
			this.article = document.querySelector(
				".single-post__content, .entry-content, article"
			);

			if (!this.article) return;
			this.init();
		}

		init() {
			// Create progress bar if it doesn't exist
			if (!this.progressBar) {
				this.progressBar = document.createElement("div");
				this.progressBar.className = "reading-progress";
				this.progressBar.innerHTML =
					'<div class="reading-progress__bar"></div>';
				document.body.prepend(this.progressBar);

				// Add styles
				const style = document.createElement("style");
				style.textContent = `
                    .reading-progress {
                        position: fixed;
                        top: 0;
                        left: 0;
                        right: 0;
                        height: 3px;
                        background: rgba(0, 0, 0, 0.1);
                        z-index: 9999;
                    }
                    .reading-progress__bar {
                        height: 100%;
                        background: linear-gradient(90deg, #c9a227, #e8b824);
                        width: 0;
                        transition: width 0.1s ease;
                    }
                `;
				document.head.appendChild(style);
			}

			this.bar =
				this.progressBar.querySelector(".reading-progress__bar") ||
				this.progressBar;

			window.addEventListener(
				"scroll",
				throttle(() => this.updateProgress(), 10)
			);
		}

		updateProgress() {
			const articleTop = this.article.offsetTop;
			const articleHeight = this.article.offsetHeight;
			const windowHeight = window.innerHeight;
			const scrollTop = window.pageYOffset;

			const start = articleTop - windowHeight;
			const end = articleTop + articleHeight - windowHeight;
			const progress = Math.max(
				0,
				Math.min(1, (scrollTop - start) / (end - start))
			);

			this.bar.style.width = `${progress * 100}%`;
		}
	}

	// ==========================================================================
	// IMAGE LAZY LOADING WITH FADE
	// ==========================================================================

	class LazyImages {
		constructor() {
			this.images = document.querySelectorAll(
				'img[data-src], img[loading="lazy"]'
			);
			if (this.images.length === 0) return;
			this.init();
		}

		init() {
			if ("IntersectionObserver" in window) {
				const observer = new IntersectionObserver(
					(entries) => {
						entries.forEach((entry) => {
							if (entry.isIntersecting) {
								this.loadImage(entry.target);
								observer.unobserve(entry.target);
							}
						});
					},
					{ rootMargin: "50px 0px" }
				);

				this.images.forEach((img) => {
					img.style.opacity = "0";
					img.style.transition = "opacity 0.5s ease";
					observer.observe(img);
				});
			} else {
				// Fallback for older browsers
				this.images.forEach((img) => this.loadImage(img));
			}
		}

		loadImage(img) {
			const src = img.dataset.src || img.src;
			if (src) {
				img.src = src;
				img.removeAttribute("data-src");
			}

			img.addEventListener("load", () => {
				img.style.opacity = "1";
			});

			// Handle already cached images
			if (img.complete) {
				img.style.opacity = "1";
			}
		}
	}

	// ==========================================================================
	// PARALLAX EFFECTS
	// ==========================================================================

	class Parallax {
		constructor() {
			this.elements = document.querySelectorAll("[data-parallax]");
			if (this.elements.length === 0) return;
			this.init();
		}

		init() {
			window.addEventListener(
				"scroll",
				throttle(() => this.update(), 10)
			);
		}

		update() {
			const scrollY = window.pageYOffset;

			this.elements.forEach((el) => {
				const speed = parseFloat(el.dataset.parallax) || CONFIG.parallaxSpeed;
				const rect = el.getBoundingClientRect();
				const visible = rect.top < window.innerHeight && rect.bottom > 0;

				if (visible) {
					const yPos = (scrollY - el.offsetTop) * speed;
					el.style.transform = `translate3d(0, ${yPos}px, 0)`;
				}
			});
		}
	}

	// ==========================================================================
	// CARD HOVER EFFECTS
	// ==========================================================================

	class CardEffects {
		constructor() {
			this.cards = document.querySelectorAll(".post-card, .card");
			if (this.cards.length === 0) return;
			this.init();
		}

		init() {
			this.cards.forEach((card) => {
				card.addEventListener("mousemove", (e) =>
					this.handleMouseMove(e, card)
				);
				card.addEventListener("mouseleave", (e) =>
					this.handleMouseLeave(e, card)
				);
			});
		}

		handleMouseMove(e, card) {
			const rect = card.getBoundingClientRect();
			const x = e.clientX - rect.left;
			const y = e.clientY - rect.top;

			card.style.setProperty("--mouse-x", `${x}px`);
			card.style.setProperty("--mouse-y", `${y}px`);
		}

		handleMouseLeave(e, card) {
			card.style.removeProperty("--mouse-x");
			card.style.removeProperty("--mouse-y");
		}
	}

	// ==========================================================================
	// COPY CODE BLOCKS
	// ==========================================================================

	class CopyCode {
		constructor() {
			this.codeBlocks = document.querySelectorAll("pre code");
			if (this.codeBlocks.length === 0) return;
			this.init();
		}

		init() {
			this.codeBlocks.forEach((block) => {
				const wrapper = block.closest("pre");
				const button = document.createElement("button");
				button.className = "copy-code-btn";
				button.innerHTML = `
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="9" y="9" width="13" height="13" rx="2" ry="2"></rect>
                        <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path>
                    </svg>
                    <span>Copy</span>
                `;

				wrapper.style.position = "relative";
				wrapper.appendChild(button);

				button.addEventListener("click", () => this.copyCode(block, button));
			});

			// Add styles
			const style = document.createElement("style");
			style.textContent = `
                .copy-code-btn {
                    position: absolute;
                    top: 0.5rem;
                    right: 0.5rem;
                    display: flex;
                    align-items: center;
                    gap: 0.25rem;
                    padding: 0.35rem 0.75rem;
                    background: rgba(255, 255, 255, 0.1);
                    border: 1px solid rgba(255, 255, 255, 0.2);
                    border-radius: 6px;
                    color: rgba(255, 255, 255, 0.7);
                    font-size: 0.75rem;
                    cursor: pointer;
                    transition: all 0.2s ease;
                }
                .copy-code-btn:hover {
                    background: rgba(255, 255, 255, 0.2);
                    color: #fff;
                }
                .copy-code-btn.copied {
                    background: #10b981;
                    color: #fff;
                    border-color: #10b981;
                }
            `;
			document.head.appendChild(style);
		}

		async copyCode(block, button) {
			const code = block.textContent;

			try {
				await navigator.clipboard.writeText(code);
				button.classList.add("copied");
				button.querySelector("span").textContent = "Copied!";

				setTimeout(() => {
					button.classList.remove("copied");
					button.querySelector("span").textContent = "Copy";
				}, 2000);
			} catch (err) {
				console.error("Failed to copy:", err);
			}
		}
	}

	// ==========================================================================
	// TYPEWRITER EFFECT
	// ==========================================================================

	class Typewriter {
		constructor(element, options = {}) {
			this.element = element;
			this.text = options.text || element.textContent;
			this.speed = options.speed || 50;
			this.delay = options.delay || 0;

			this.init();
		}

		init() {
			this.element.textContent = "";
			this.element.style.opacity = "1";

			setTimeout(() => this.type(), this.delay);
		}

		type(index = 0) {
			if (index < this.text.length) {
				this.element.textContent += this.text.charAt(index);
				setTimeout(() => this.type(index + 1), this.speed);
			}
		}
	}

	// Initialize typewriter for elements with data-typewriter attribute
	class TypewriterInit {
		constructor() {
			this.elements = document.querySelectorAll("[data-typewriter]");
			if (this.elements.length === 0) return;

			const observer = new IntersectionObserver((entries) => {
				entries.forEach((entry) => {
					if (entry.isIntersecting) {
						new Typewriter(entry.target, {
							speed: parseInt(entry.target.dataset.typewriterSpeed) || 50,
							delay: parseInt(entry.target.dataset.typewriterDelay) || 0,
						});
						observer.unobserve(entry.target);
					}
				});
			});

			this.elements.forEach((el) => observer.observe(el));
		}
	}

	// ==========================================================================
	// COUNTER ANIMATION
	// ==========================================================================

	class CounterAnimation {
		constructor() {
			this.counters = document.querySelectorAll("[data-counter]");
			if (this.counters.length === 0) return;
			this.init();
		}

		init() {
			const observer = new IntersectionObserver(
				(entries) => {
					entries.forEach((entry) => {
						if (entry.isIntersecting) {
							this.animate(entry.target);
							observer.unobserve(entry.target);
						}
					});
				},
				{ threshold: 0.5 }
			);

			this.counters.forEach((counter) => observer.observe(counter));
		}

		animate(element) {
			const target = parseInt(element.dataset.counter);
			const duration = parseInt(element.dataset.counterDuration) || 2000;
			const start = 0;
			const startTime = performance.now();

			const update = (currentTime) => {
				const elapsed = currentTime - startTime;
				const progress = Math.min(elapsed / duration, 1);

				// Easing function (easeOutQuart)
				const eased = 1 - Math.pow(1 - progress, 4);
				const current = Math.floor(eased * (target - start) + start);

				element.textContent = current.toLocaleString();

				if (progress < 1) {
					requestAnimationFrame(update);
				} else {
					element.textContent = target.toLocaleString();
				}
			};

			requestAnimationFrame(update);
		}
	}

	// ==========================================================================
	// NEWSLETTER FORM
	// ==========================================================================

	class NewsletterForm {
		constructor() {
			this.forms = document.querySelectorAll(".newsletter-form");
			if (this.forms.length === 0) return;
			this.init();
		}

		init() {
			this.forms.forEach((form) => {
				form.addEventListener("submit", (e) => this.handleSubmit(e, form));
			});
		}

		async handleSubmit(e, form) {
			e.preventDefault();

			const button = form.querySelector('button[type="submit"]');
			const input = form.querySelector('input[type="email"]');
			const originalText = button.textContent;

			button.textContent = "Subscribing...";
			button.disabled = true;

			// Simulate API call
			await new Promise((resolve) => setTimeout(resolve, 1500));

			button.textContent = "Subscribed! ✓";
			button.classList.add("success");
			input.value = "";

			setTimeout(() => {
				button.textContent = originalText;
				button.disabled = false;
				button.classList.remove("success");
			}, 3000);
		}
	}

	// ==========================================================================
	// DARK MODE TOGGLE (Optional)
	// ==========================================================================

	class DarkMode {
		constructor() {
			this.toggle = document.querySelector("[data-dark-mode-toggle]");
			this.init();
		}

		init() {
			// Check for saved preference or system preference
			const savedTheme = localStorage.getItem("theme");
			const systemPrefersDark = window.matchMedia(
				"(prefers-color-scheme: dark)"
			).matches;

			if (savedTheme === "dark" || (!savedTheme && systemPrefersDark)) {
				document.documentElement.classList.add("dark-mode");
			}

			if (this.toggle) {
				this.toggle.addEventListener("click", () => this.toggleDarkMode());
			}
		}

		toggleDarkMode() {
			document.documentElement.classList.toggle("dark-mode");
			const isDark = document.documentElement.classList.contains("dark-mode");
			localStorage.setItem("theme", isDark ? "dark" : "light");
		}
	}

	// ==========================================================================
	// INITIALIZE ALL MODULES
	// ==========================================================================

	document.addEventListener("DOMContentLoaded", () => {
		// Core functionality
		new ScrollReveal();
		new SkeletonLoader();
		new EnhancedHeader();
		new MobileMenu();
		new SmoothScroll();
		new LazyImages();

		// Enhanced effects
		new MagneticElements();
		new CardEffects();
		new ReadingProgress();
		new CopyCode();

		// Animations
		new Parallax();
		new TypewriterInit();
		new CounterAnimation();

		// Forms
		new NewsletterForm();

		// Theme
		new DarkMode();

		// Add loaded class for initial animations
		document.body.classList.add("loaded");

		// Stagger animations for card grids
		document.querySelectorAll(".posts-grid, .cards-grid").forEach((grid) => {
			grid.classList.add("stagger-children");
		});

		console.log("🚀 LegalPress Advanced JS Loaded");
	});

	// Export for use in other scripts
	window.LegalPress = {
		SkeletonLoader,
		Typewriter,
		debounce,
		throttle,
	};
})();
