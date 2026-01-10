/**
 * LegalPress Theme Main JavaScript
 *
 * Handles mobile menu toggle, sticky header on scroll,
 * and other interactive functionality.
 *
 * @package LegalPress
 * @since 1.0.0
 */

(function () {
	"use strict";

	/**
	 * DOM Ready Function
	 */
	function domReady(fn) {
		if (document.readyState === "loading") {
			document.addEventListener("DOMContentLoaded", fn);
		} else {
			fn();
		}
	}

	/**
	 * Mobile Menu Toggle
	 * Handles opening/closing the mobile navigation
	 */
	function initMobileMenu() {
		const menuToggle = document.querySelector(".menu-toggle");
		const mobileNav = document.querySelector(".mobile-nav");

		if (!menuToggle || !mobileNav) return;

		menuToggle.addEventListener("click", function () {
			const isExpanded = this.getAttribute("aria-expanded") === "true";

			// Toggle button state
			this.classList.toggle("is-active");
			this.setAttribute("aria-expanded", !isExpanded);

			// Toggle menu visibility
			mobileNav.classList.toggle("is-open");

			// Prevent body scroll when menu is open
			document.body.style.overflow = !isExpanded ? "hidden" : "";
		});

		// Close menu when clicking outside
		document.addEventListener("click", function (e) {
			if (!menuToggle.contains(e.target) && !mobileNav.contains(e.target)) {
				menuToggle.classList.remove("is-active");
				menuToggle.setAttribute("aria-expanded", "false");
				mobileNav.classList.remove("is-open");
				document.body.style.overflow = "";
			}
		});

		// Close menu on escape key
		document.addEventListener("keydown", function (e) {
			if (e.key === "Escape" && mobileNav.classList.contains("is-open")) {
				menuToggle.classList.remove("is-active");
				menuToggle.setAttribute("aria-expanded", "false");
				mobileNav.classList.remove("is-open");
				document.body.style.overflow = "";
			}
		});

		// Handle sub-menu toggles on mobile
		const menuItemsWithChildren = mobileNav.querySelectorAll(
			".menu-item-has-children > a"
		);
		menuItemsWithChildren.forEach(function (item) {
			item.addEventListener("click", function (e) {
				const parentLi = this.parentElement;
				const subMenu = parentLi.querySelector(".sub-menu");

				if (subMenu && window.innerWidth < 768) {
					e.preventDefault();
					parentLi.classList.toggle("is-open");
					subMenu.style.display = parentLi.classList.contains("is-open")
						? "block"
						: "none";
				}
			});
		});
	}

	/**
	 * Sticky Header
	 * Adds shadow to header when scrolled
	 */
	function initStickyHeader() {
		const header = document.querySelector(".site-header");
		if (!header) return;

		let lastScroll = 0;
		const scrollThreshold = 50;

		function handleScroll() {
			const currentScroll = window.pageYOffset;

			// Add shadow when scrolled
			if (currentScroll > scrollThreshold) {
				header.classList.add("is-scrolled");
			} else {
				header.classList.remove("is-scrolled");
			}

			lastScroll = currentScroll;
		}

		// Throttle scroll event for better performance
		let ticking = false;
		window.addEventListener(
			"scroll",
			function () {
				if (!ticking) {
					window.requestAnimationFrame(function () {
						handleScroll();
						ticking = false;
					});
					ticking = true;
				}
			},
			{ passive: true }
		);

		// Initial check
		handleScroll();
	}

	/**
	 * Smooth Scroll for Anchor Links
	 */
	function initSmoothScroll() {
		document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
			anchor.addEventListener("click", function (e) {
				const targetId = this.getAttribute("href");

				// Skip if it's just "#"
				if (targetId === "#") return;

				const targetElement = document.querySelector(targetId);

				if (targetElement) {
					e.preventDefault();

					const headerHeight =
						document.querySelector(".site-header").offsetHeight || 0;
					const targetPosition =
						targetElement.getBoundingClientRect().top +
						window.pageYOffset -
						headerHeight;

					window.scrollTo({
						top: targetPosition,
						behavior: "smooth",
					});
				}
			});
		});
	}

	/**
	 * Lazy Load Images
	 * Uses native lazy loading with IntersectionObserver fallback
	 */
	function initLazyLoad() {
		// Check for native lazy loading support
		if ("loading" in HTMLImageElement.prototype) {
			// Browser supports native lazy loading
			const lazyImages = document.querySelectorAll('img[loading="lazy"]');
			lazyImages.forEach(function (img) {
				if (img.dataset.src) {
					img.src = img.dataset.src;
				}
			});
		} else {
			// Fallback for older browsers
			const lazyImages = document.querySelectorAll("img[data-src]");

			if ("IntersectionObserver" in window) {
				const imageObserver = new IntersectionObserver(
					function (entries) {
						entries.forEach(function (entry) {
							if (entry.isIntersecting) {
								const img = entry.target;
								img.src = img.dataset.src;
								img.removeAttribute("data-src");
								imageObserver.unobserve(img);
							}
						});
					},
					{
						rootMargin: "50px 0px",
					}
				);

				lazyImages.forEach(function (img) {
					imageObserver.observe(img);
				});
			} else {
				// Very old browsers - load all images immediately
				lazyImages.forEach(function (img) {
					img.src = img.dataset.src;
				});
			}
		}
	}

	/**
	 * Reading Progress Bar (optional)
	 * Shows progress on single posts
	 */
	function initReadingProgress() {
		const article = document.querySelector(".single-post__content");
		if (!article) return;

		// Create progress bar
		const progressBar = document.createElement("div");
		progressBar.className = "reading-progress";
		progressBar.innerHTML = '<div class="reading-progress__bar"></div>';
		progressBar.style.cssText =
			"position: fixed; top: 0; left: 0; width: 100%; height: 3px; z-index: 9999; background: transparent;";

		const bar = progressBar.querySelector(".reading-progress__bar");
		bar.style.cssText =
			"height: 100%; background: var(--color-accent, #c9a227); width: 0; transition: width 0.1s ease;";

		document.body.appendChild(progressBar);

		function updateProgress() {
			const articleTop = article.offsetTop;
			const articleHeight = article.offsetHeight;
			const windowHeight = window.innerHeight;
			const scrollTop = window.pageYOffset;

			const progress = Math.min(
				Math.max(
					((scrollTop - articleTop + windowHeight * 0.3) / articleHeight) * 100,
					0
				),
				100
			);

			bar.style.width = progress + "%";
		}

		let ticking = false;
		window.addEventListener(
			"scroll",
			function () {
				if (!ticking) {
					window.requestAnimationFrame(function () {
						updateProgress();
						ticking = false;
					});
					ticking = true;
				}
			},
			{ passive: true }
		);

		updateProgress();
	}

	/**
	 * Copy Code Blocks
	 * Adds copy button to code blocks in post content
	 */
	function initCopyCode() {
		const codeBlocks = document.querySelectorAll(".single-post__content pre");

		codeBlocks.forEach(function (block) {
			// Create copy button
			const copyButton = document.createElement("button");
			copyButton.className = "copy-code-btn";
			copyButton.textContent = "Copy";
			copyButton.style.cssText =
				"position: absolute; top: 8px; right: 8px; padding: 4px 8px; font-size: 12px; background: var(--color-bg-dark, #f0f0f0); border: none; border-radius: 4px; cursor: pointer; opacity: 0; transition: opacity 0.2s;";

			// Wrap pre in relative container
			const wrapper = document.createElement("div");
			wrapper.style.position = "relative";
			block.parentNode.insertBefore(wrapper, block);
			wrapper.appendChild(block);
			wrapper.appendChild(copyButton);

			// Show/hide on hover
			wrapper.addEventListener("mouseenter", function () {
				copyButton.style.opacity = "1";
			});
			wrapper.addEventListener("mouseleave", function () {
				copyButton.style.opacity = "0";
			});

			// Copy functionality
			copyButton.addEventListener("click", function () {
				const code = block.textContent;
				navigator.clipboard
					.writeText(code)
					.then(function () {
						copyButton.textContent = "Copied!";
						setTimeout(function () {
							copyButton.textContent = "Copy";
						}, 2000);
					})
					.catch(function () {
						copyButton.textContent = "Failed";
					});
			});
		});
	}

	/**
	 * Initialize All Functions
	 */
	domReady(function () {
		initMobileMenu();
		initStickyHeader();
		initSmoothScroll();
		initLazyLoad();
		initReadingProgress();
		initCopyCode();
	});
})();
