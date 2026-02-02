/**
 * Mobile Bottom Navigation JavaScript
 *
 * Handles panel open/close, animations, and interactions.
 * Completely independent and self-contained.
 *
 * @package LegalPress
 * @since 2.5.0
 */

(function () {
	"use strict";

	// Wait for DOM to be ready
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", init);
	} else {
		init();
	}

	function init() {
		const bottomNav = document.getElementById("mobile-bottom-nav");
		if (!bottomNav) return;

		// Initialize all nav items
		const navItems = bottomNav.querySelectorAll(
			".mobile-bottom-nav__item[data-nav]",
		);

		navItems.forEach((item) => {
			const navType = item.getAttribute("data-nav");
			const panelId = `mobile-${navType}-panel`;
			const panel = document.getElementById(panelId);

			if (panel && item.tagName === "BUTTON") {
				// Set up button to open panel
				item.addEventListener("click", () => {
					togglePanel(panel, item);
				});

				// Set up close button
				const closeBtn = panel.querySelector(".mobile-panel__close");
				if (closeBtn) {
					closeBtn.addEventListener("click", () => {
						closePanel(panel, item);
					});
				}

				// Close on overlay click
				const overlay = panel.querySelector(".mobile-panel__overlay");
				if (overlay) {
					overlay.addEventListener("click", () => {
						closePanel(panel, item);
					});
				}
			}
		});

		// Close panels on escape key
		document.addEventListener("keydown", (e) => {
			if (e.key === "Escape") {
				closeAllPanels();
			}
		});

		// Handle swipe down to close
		initSwipeToClose();

		// Hide/show bottom nav on scroll
		initScrollBehavior();

		// Initialize bookmarks if enabled
		initBookmarks();
	}

	/**
	 * Toggle panel open/close
	 */
	function togglePanel(panel, trigger) {
		const isOpen = panel.classList.contains("is-open");

		if (isOpen) {
			closePanel(panel, trigger);
		} else {
			openPanel(panel, trigger);
		}
	}

	/**
	 * Open a panel
	 */
	function openPanel(panel, trigger) {
		// Close any other open panels first
		closeAllPanels();

		// Open this panel
		panel.classList.add("is-open");
		panel.setAttribute("aria-hidden", "false");
		trigger.setAttribute("aria-expanded", "true");

		// Prevent body scroll
		document.body.style.overflow = "hidden";

		// Focus management
		const firstFocusable = panel.querySelector("input, button, a");
		if (firstFocusable) {
			setTimeout(() => firstFocusable.focus(), 350);
		}
	}

	/**
	 * Close a panel
	 */
	function closePanel(panel, trigger) {
		panel.classList.remove("is-open");
		panel.setAttribute("aria-hidden", "true");

		if (trigger) {
			trigger.setAttribute("aria-expanded", "false");
		}

		// Re-enable body scroll
		document.body.style.overflow = "";
	}

	/**
	 * Close all open panels
	 */
	function closeAllPanels() {
		const openPanels = document.querySelectorAll(".mobile-panel.is-open");

		openPanels.forEach((panel) => {
			const triggerId = panel.id.replace("-panel", "");
			const trigger = document.querySelector(
				`[data-nav="${triggerId.replace("mobile-", "")}"]`,
			);
			closePanel(panel, trigger);
		});
	}

	/**
	 * Initialize swipe to close functionality
	 */
	function initSwipeToClose() {
		const panels = document.querySelectorAll(".mobile-panel");

		panels.forEach((panel) => {
			const content = panel.querySelector(".mobile-panel__content");
			if (!content) return;

			let startY = 0;
			let currentY = 0;
			let isDragging = false;

			content.addEventListener(
				"touchstart",
				(e) => {
					// Only allow swipe from the header area
					const header = content.querySelector(".mobile-panel__header");
					if (!header.contains(e.target)) return;

					startY = e.touches[0].clientY;
					isDragging = true;
					content.style.transition = "none";
				},
				{ passive: true },
			);

			content.addEventListener(
				"touchmove",
				(e) => {
					if (!isDragging) return;

					currentY = e.touches[0].clientY;
					const deltaY = currentY - startY;

					// Only allow dragging down
					if (deltaY > 0) {
						content.style.transform = `translateY(${deltaY}px)`;
					}
				},
				{ passive: true },
			);

			content.addEventListener("touchend", () => {
				if (!isDragging) return;

				isDragging = false;
				content.style.transition = "";

				const deltaY = currentY - startY;

				// If dragged more than 100px, close the panel
				if (deltaY > 100) {
					const triggerId = panel.id.replace("-panel", "");
					const trigger = document.querySelector(
						`[data-nav="${triggerId.replace("mobile-", "")}"]`,
					);
					closePanel(panel, trigger);
				}

				content.style.transform = "";
			});
		});
	}

	/**
	 * Initialize scroll behavior (hide/show on scroll)
	 */
	function initScrollBehavior() {
		const bottomNav = document.getElementById("mobile-bottom-nav");
		if (!bottomNav) return;

		let lastScrollY = window.scrollY;
		let ticking = false;

		window.addEventListener(
			"scroll",
			() => {
				if (!ticking) {
					window.requestAnimationFrame(() => {
						const currentScrollY = window.scrollY;

						// Hide when scrolling down, show when scrolling up
						if (currentScrollY > lastScrollY && currentScrollY > 100) {
							bottomNav.style.transform = "translateY(100%)";
						} else {
							bottomNav.style.transform = "translateY(0)";
						}

						lastScrollY = currentScrollY;
						ticking = false;
					});

					ticking = true;
				}
			},
			{ passive: true },
		);

		// Add transition for smooth hide/show
		bottomNav.style.transition = "transform 0.3s ease";
	}

	/**
	 * Initialize bookmarks functionality
	 */
	function initBookmarks() {
		const bookmarksList = document.getElementById("mobile-bookmarks-list");
		const bookmarksCount = document.getElementById("bookmarks-count");

		if (!bookmarksList) return;

		// Get bookmarks from localStorage
		const bookmarks = getBookmarks();

		// Update badge count
		updateBookmarksCount(bookmarks.length);

		// Render bookmarks list
		renderBookmarks(bookmarks);
	}

	/**
	 * Get bookmarks from localStorage
	 */
	function getBookmarks() {
		try {
			const stored = localStorage.getItem("legalpress_bookmarks");
			return stored ? JSON.parse(stored) : [];
		} catch (e) {
			return [];
		}
	}

	/**
	 * Update bookmarks count badge
	 */
	function updateBookmarksCount(count) {
		const badge = document.getElementById("bookmarks-count");
		if (!badge) return;

		if (count > 0) {
			badge.textContent = count > 99 ? "99+" : count;
			badge.style.display = "flex";
		} else {
			badge.style.display = "none";
		}
	}

	/**
	 * Render bookmarks list
	 */
	function renderBookmarks(bookmarks) {
		const list = document.getElementById("mobile-bookmarks-list");
		if (!list) return;

		if (bookmarks.length === 0) {
			// Show empty state
			list.innerHTML = `
                <div class="mobile-bookmarks-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <p>No saved articles yet</p>
                    <span>Articles you save will appear here</span>
                </div>
            `;
			return;
		}

		// Render bookmarks
		let html = '<div class="mobile-bookmarks-items">';
		bookmarks.forEach((bookmark, index) => {
			html += `
                <div class="mobile-bookmark-item" data-index="${index}">
                    <a href="${bookmark.url}" class="mobile-bookmark-item__link">
                        <div class="mobile-bookmark-item__content">
                            <h4 class="mobile-bookmark-item__title">${bookmark.title}</h4>
                            <span class="mobile-bookmark-item__date">${bookmark.date || ""}</span>
                        </div>
                    </a>
                    <button type="button" class="mobile-bookmark-item__remove" data-index="${index}" aria-label="Remove bookmark">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>
            `;
		});
		html += "</div>";

		list.innerHTML = html;

		// Add remove handlers
		list.querySelectorAll(".mobile-bookmark-item__remove").forEach((btn) => {
			btn.addEventListener("click", (e) => {
				e.preventDefault();
				const index = parseInt(btn.getAttribute("data-index"));
				removeBookmark(index);
			});
		});
	}

	/**
	 * Remove a bookmark
	 */
	function removeBookmark(index) {
		const bookmarks = getBookmarks();
		bookmarks.splice(index, 1);
		localStorage.setItem("legalpress_bookmarks", JSON.stringify(bookmarks));

		updateBookmarksCount(bookmarks.length);
		renderBookmarks(bookmarks);
	}

	/**
	 * Initialize Live Search
	 */
	function initLiveSearch() {
		const searchInput = document.getElementById("mobile-live-search");
		const resultsContainer = document.getElementById("mobile-search-results");
		const popularContainer = document.getElementById("mobile-search-popular");
		const recentContainer = document.getElementById("mobile-search-recent");

		if (!searchInput || !resultsContainer) return;

		const loader = document.querySelector(".mobile-search-form__loader");
		const resultsList = resultsContainer.querySelector(
			".mobile-search-results__list",
		);
		const resultsCount = resultsContainer.querySelector(
			".mobile-search-results__count",
		);
		const viewAllLink = resultsContainer.querySelector(
			".mobile-search-results__viewall",
		);
		const clearBtn = resultsContainer.querySelector(
			".mobile-search-results__clear",
		);
		const recentList =
			recentContainer ?
				recentContainer.querySelector(".mobile-search-recent__list")
			:	null;
		const recentClearBtn =
			recentContainer ?
				recentContainer.querySelector(".mobile-search-recent__clear")
			:	null;

		let searchTimeout = null;
		let currentRequest = null;

		// Load and display recent searches
		renderRecentSearches();

		// Debounced search input handler
		searchInput.addEventListener("input", function () {
			const query = this.value.trim();

			// Clear existing timeout
			if (searchTimeout) {
				clearTimeout(searchTimeout);
			}

			// Hide results if query is too short (min 3 chars required by server)
			if (query.length < 3) {
				hideResults();
				showPopular();
				return;
			}

			// Debounce the search
			searchTimeout = setTimeout(() => {
				performSearch(query);
			}, 300);
		});

		// Handle focus to show recent searches
		searchInput.addEventListener("focus", function () {
			if (this.value.trim().length < 3) {
				renderRecentSearches();
			}
		});

		// Handle form submit to save recent search
		const searchForm = searchInput.closest("form");
		if (searchForm) {
			searchForm.addEventListener("submit", function () {
				const query = searchInput.value.trim();
				if (query.length >= 3) {
					saveRecentSearch(query);
				}
			});
		}

		// Clear button
		if (clearBtn) {
			clearBtn.addEventListener("click", function () {
				searchInput.value = "";
				hideResults();
				showPopular();
				searchInput.focus();
			});
		}

		// Clear recent searches
		if (recentClearBtn) {
			recentClearBtn.addEventListener("click", function () {
				localStorage.removeItem("legalpress_recent_searches");
				hideRecent();
			});
		}

		/**
		 * Perform AJAX search
		 */
		function performSearch(query) {
			// Cancel previous request
			if (currentRequest) {
				currentRequest.abort();
			}

			// Check if legalpressSearch data exists
			if (typeof legalpressSearch === "undefined") {
				console.warn("Search data not available");
				return;
			}

			// Show loader
			if (loader) loader.style.display = "flex";

			// Create abort controller for this request
			const controller = new AbortController();
			currentRequest = controller;

			// Build request
			const formData = new FormData();
			formData.append("action", "legalpress_search");
			formData.append("nonce", legalpressSearch.nonce);
			formData.append("query", query);

			fetch(legalpressSearch.ajaxUrl, {
				method: "POST",
				body: formData,
				signal: controller.signal,
			})
				.then((response) => response.json())
				.then((data) => {
					if (loader) loader.style.display = "none";

					if (data.success && data.data) {
						renderResults(data.data.results, data.data.total, query);
					} else {
						renderNoResults(query);
					}
				})
				.catch((error) => {
					if (error.name !== "AbortError") {
						console.error("Search error:", error);
						if (loader) loader.style.display = "none";
					}
				});
		}

		/**
		 * Render search results
		 */
		function renderResults(results, total, query) {
			hidePopular();
			hideRecent();

			if (!results || results.length === 0) {
				renderNoResults(query);
				return;
			}

			// Update count
			if (resultsCount) {
				resultsCount.textContent = `${total} result${total !== 1 ? "s" : ""} found`;
			}

			// Update view all link
			if (viewAllLink) {
				viewAllLink.href = `/?s=${encodeURIComponent(query)}`;
				viewAllLink.style.display = total > results.length ? "flex" : "none";
			}

			// Render results list
			let html = "";
			results.forEach((item) => {
				html += `
                    <a href="${item.url}" class="mobile-search-result" onclick="LegalPressMobileSearch.saveRecent('${escapeHtml(item.title.replace(/'/g, "\\'"))}')">
                        <div class="mobile-search-result__thumb">
                            ${
															item.thumbnail ?
																`<img src="${item.thumbnail}" alt="" loading="lazy">`
															:	`<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                    <circle cx="9" cy="9" r="2"></circle>
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"></path>
                                   </svg>`
														}
                        </div>
                        <div class="mobile-search-result__content">
                            <h4 class="mobile-search-result__title">${highlightMatch(item.title, query)}</h4>
                            <div class="mobile-search-result__meta">
                                ${item.category ? `<span class="mobile-search-result__category">${item.category}</span>` : ""}
                                <span class="mobile-search-result__date">${item.date}</span>
                            </div>
                        </div>
                    </a>
                `;
			});

			resultsList.innerHTML = html;
			resultsContainer.style.display = "block";

			// Save as recent search when clicking a result
			resultsContainer
				.querySelectorAll(".mobile-search-result")
				.forEach(function (link) {
					link.addEventListener("click", function () {
						saveRecentSearch(query);
					});
				});
		}

		/**
		 * Render no results message
		 */
		function renderNoResults(query) {
			hidePopular();
			hideRecent();

			if (resultsCount) {
				resultsCount.textContent = "No results found";
			}

			if (viewAllLink) {
				viewAllLink.style.display = "none";
			}

			resultsList.innerHTML = `
                <div class="mobile-search-empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="8" y1="8" x2="14" y2="14"></line>
                        <line x1="14" y1="8" x2="8" y2="14"></line>
                    </svg>
                    <p>No articles found for "<strong>${escapeHtml(query)}</strong>"</p>
                    <span>Try different keywords or check the spelling</span>
                </div>
            `;

			resultsContainer.style.display = "block";
		}

		/**
		 * Render recent searches
		 */
		function renderRecentSearches() {
			const recent = getRecentSearches();

			if (!recentContainer || !recentList || recent.length === 0) {
				hideRecent();
				return;
			}

			let html = "";
			recent.forEach((term) => {
				html += `
                    <button type="button" class="mobile-search-recent__item" data-term="${escapeHtml(term)}">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="1 4 1 10 7 10"></polyline>
                            <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                        </svg>
                        <span>${escapeHtml(term)}</span>
                    </button>
                `;
			});

			recentList.innerHTML = html;
			recentContainer.style.display = "block";

			// Click handler to fill search
			recentList
				.querySelectorAll(".mobile-search-recent__item")
				.forEach((btn) => {
					btn.addEventListener("click", function () {
						const term = this.getAttribute("data-term");
						searchInput.value = term;
						performSearch(term);
					});
				});
		}

		/**
		 * Get recent searches from localStorage
		 */
		function getRecentSearches() {
			try {
				return JSON.parse(
					localStorage.getItem("legalpress_recent_searches") || "[]",
				);
			} catch (e) {
				return [];
			}
		}

		/**
		 * Save a search term to recent
		 */
		function saveRecentSearch(term) {
			if (!term || term.length < 2) return;

			let recent = getRecentSearches();

			// Remove if exists
			recent = recent.filter((t) => t.toLowerCase() !== term.toLowerCase());

			// Add to beginning
			recent.unshift(term);

			// Keep max 5
			recent = recent.slice(0, 5);

			localStorage.setItem(
				"legalpress_recent_searches",
				JSON.stringify(recent),
			);
		}

		/**
		 * Hide results container
		 */
		function hideResults() {
			resultsContainer.style.display = "none";
			resultsList.innerHTML = "";
		}

		/**
		 * Show popular container
		 */
		function showPopular() {
			if (popularContainer) {
				popularContainer.style.display = "block";
			}
		}

		/**
		 * Hide popular container
		 */
		function hidePopular() {
			if (popularContainer) {
				popularContainer.style.display = "none";
			}
		}

		/**
		 * Hide recent container
		 */
		function hideRecent() {
			if (recentContainer) {
				recentContainer.style.display = "none";
			}
		}

		/**
		 * Highlight matching text in title
		 */
		function highlightMatch(text, query) {
			if (!query) return escapeHtml(text);
			const regex = new RegExp(`(${escapeRegex(query)})`, "gi");
			return escapeHtml(text).replace(
				regex,
				'<mark class="search-highlight">$1</mark>',
			);
		}

		/**
		 * Escape HTML entities
		 */
		function escapeHtml(str) {
			const div = document.createElement("div");
			div.textContent = str;
			return div.innerHTML;
		}

		/**
		 * Escape regex special characters
		 */
		function escapeRegex(str) {
			return str.replace(/[.*+?^${}()|[\]\\]/g, "\\$&");
		}

		// Expose save function globally
		window.LegalPressMobileSearch = {
			saveRecent: saveRecentSearch,
		};
	}

	// Initialize live search when DOM is ready
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", initLiveSearch);
	} else {
		initLiveSearch();
	}

	// Expose functions globally for external use
	window.LegalPressMobileNav = {
		openPanel: openPanel,
		closePanel: closePanel,
		closeAllPanels: closeAllPanels,
		getBookmarks: getBookmarks,
		updateBookmarksCount: updateBookmarksCount,
	};
})();
