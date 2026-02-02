/**
 * Service Worker for Push Notifications
 * LegalPress Theme
 *
 * This file should be placed at the root of your WordPress installation
 * or served via a rewrite rule
 */

const CACHE_NAME = "legalpress-push-v1";

// Install event
self.addEventListener("install", (event) => {
	console.log("[Service Worker] Installing...");
	self.skipWaiting();
});

// Activate event
self.addEventListener("activate", (event) => {
	console.log("[Service Worker] Activated");
	event.waitUntil(clients.claim());
});

// Push event - receives push notifications
self.addEventListener("push", (event) => {
	console.log("[Service Worker] Push received");

	let data = {
		title: "New Update",
		body: "Check out the latest content!",
		url: "/",
		icon: "/wp-content/themes/legalpress/assets/images/icon-192.png",
		badge: "/wp-content/themes/legalpress/assets/images/icon-72.png",
	};

	// Try to parse push data
	if (event.data) {
		try {
			data = { ...data, ...event.data.json() };
		} catch (e) {
			console.log("[Service Worker] Could not parse push data:", e);
			data.body = event.data.text();
		}
	}

	const options = {
		body: data.body,
		icon: data.icon,
		badge: data.badge,
		vibrate: [100, 50, 100],
		data: {
			url: data.url,
			dateOfArrival: Date.now(),
		},
		actions: [
			{
				action: "open",
				title: "Read Now",
			},
			{
				action: "close",
				title: "Dismiss",
			},
		],
		requireInteraction: false,
		tag: "legalpress-notification",
		renotify: true,
	};

	event.waitUntil(self.registration.showNotification(data.title, options));
});

// Notification click event
self.addEventListener("notificationclick", (event) => {
	console.log("[Service Worker] Notification clicked");

	event.notification.close();

	if (event.action === "close") {
		return;
	}

	// Get the URL from notification data
	const urlToOpen = event.notification.data?.url || "/";

	event.waitUntil(
		clients
			.matchAll({ type: "window", includeUncontrolled: true })
			.then((clientList) => {
				// Check if there's already a window open
				for (const client of clientList) {
					if (client.url.includes(self.location.origin) && "focus" in client) {
						client.navigate(urlToOpen);
						return client.focus();
					}
				}
				// Open new window if none exists
				if (clients.openWindow) {
					return clients.openWindow(urlToOpen);
				}
			}),
	);
});

// Notification close event
self.addEventListener("notificationclose", (event) => {
	console.log("[Service Worker] Notification closed");
});

// Background sync (for offline support)
self.addEventListener("sync", (event) => {
	console.log("[Service Worker] Sync event:", event.tag);
});

// Handle subscription change
self.addEventListener("pushsubscriptionchange", (event) => {
	console.log("[Service Worker] Push subscription changed");

	event.waitUntil(
		self.registration.pushManager
			.subscribe({
				userVisibleOnly: true,
				applicationServerKey: self.applicationServerKey,
			})
			.then((subscription) => {
				// Re-subscribe and update server
				return fetch(
					"/wp-admin/admin-ajax.php?action=legalpress_save_subscription",
					{
						method: "POST",
						headers: {
							"Content-Type": "application/x-www-form-urlencoded",
						},
						body: new URLSearchParams({
							subscription: JSON.stringify(subscription.toJSON()),
							nonce: self.pushNonce,
						}),
					},
				);
			}),
	);
});
