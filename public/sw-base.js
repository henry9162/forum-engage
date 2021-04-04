importScripts(
	'https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js'
);

workbox.precaching.precacheAndRoute([]);

workbox.routing.registerRoute(
	new RegExp('/photos/'),
	new workbox.strategies.StaleWhileRevalidate({
		cacheName: 'photos',
		plugins: [
			new workbox.expiration.Plugin({
				maxEntries: 15,
				maxAgeSeconds: 7 * 24 * 60 * 60,
			}),
			new workbox.cacheableResponse.Plugin({
				statuses: [0, 200]
			})
		]
	})
);

const networkFirstHandler = new workbox.strategies.NetworkFirst({
	cacheName: 'dynamic',
	plugins: [
		new workbox.expiration.Plugin({
			maxEntries: 10,
			maxAgeSeconds: 7 * 24 * 60 * 60,
		}),
		new workbox.cacheableResponse.Plugin({
			statuses: [0, 200]
		})
	]
});

const FALLBACK_URL = workbox.precaching.getCacheKeyForURL('/offline.html');
const matcher = ({ event }) => event.request.mode === 'navigate';
const handler = args =>
	networkFirstHandler
		.handle(args)
		.then(response => response || caches.match(FALLBACK_URL))
		.catch(() => caches.match(FALLBACK_URL));

workbox.routing.registerRoute(matcher, handler);