importScripts(
	'https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js'
);

workbox.precaching.precacheAndRoute([
  {
    "url": "css/app.css",
    "revision": "d1e18394397b146fca091eda670c43e3"
  },
  {
    "url": "css/header.css",
    "revision": "98412ba53f60e60e8cff9ddc8ccff3de"
  },
  {
    "url": "css/main.css",
    "revision": "227f12a518bc94c9901f061f51aa19c3"
  },
  {
    "url": "css/vendor/jquery.atwho.css",
    "revision": "907b17820a0723e492d41e03aabfaa82"
  },
  {
    "url": "images/avatars/default.png",
    "revision": "935530be5820b3e94252488182d71f3e"
  },
  {
    "url": "images/header/avatar.jpg",
    "revision": "b2b2333472b7281e90e21586ecf9b343"
  },
  {
    "url": "images/header/engage-logo.png",
    "revision": "4f6c21f93611bc92ce4d442802a973fb"
  },
  {
    "url": "images/header/engage-logo2.png",
    "revision": "4fdaf31595f9509218b4abc6c97b4a11"
  },
  {
    "url": "images/header/engage-symbol.png",
    "revision": "9bf249105fff07711a27926d39719b22"
  },
  {
    "url": "images/header/engage.png",
    "revision": "ef0e216bdc91fe4eccd0670274948c22"
  },
  {
    "url": "images/header/forum.png",
    "revision": "32ca3227faf3aa7326fbefc21d007527"
  },
  {
    "url": "images/header/header-background.png",
    "revision": "521c4ff3f660c5a26f6a31ebe98185fd"
  },
  {
    "url": "images/header/header-image.png",
    "revision": "35e6aaa7a2cf90c3f0b6c33f2e02465b"
  },
  {
    "url": "images/header/newBannerBlack.png",
    "revision": "d21c91b8882e44013ba48f745d709658"
  },
  {
    "url": "images/header/newBannerWhite.png",
    "revision": "c0afbe4da3385b6abfdc987453cb5d2a"
  },
  {
    "url": "images/header/newengagelogo.png",
    "revision": "f49cd11bb287de3af13f95625ee089ad"
  },
  {
    "url": "images/header/nysclogo.png",
    "revision": "18bad03b903b29a81053d5be6b608bb1"
  },
  {
    "url": "images/header/og3-06.png",
    "revision": "8348f4f0dfe7d28f969a5020b92f59c7"
  },
  {
    "url": "images/header/og4-06.png",
    "revision": "19d9eb29ca42b58112af2c3411c10a83"
  },
  {
    "url": "images/header/tailwind_bg.jpg",
    "revision": "df03dbf3d439a459b87b31120d92c283"
  },
  {
    "url": "images/header/tailwind_logo.jpg",
    "revision": "c40475dce4bc3872e531b453610ddada"
  },
  {
    "url": "images/icons/icon-128x128.png",
    "revision": "419e0b1114e8f0ed0d9b4b51dcfed737"
  },
  {
    "url": "images/icons/icon-144x144.png",
    "revision": "343861e53efc0805feb2631ce0eeae4d"
  },
  {
    "url": "images/icons/icon-152x152.png",
    "revision": "50e0835614feed1f6bb1feb718f7b7c4"
  },
  {
    "url": "images/icons/icon-192x192.png",
    "revision": "818bec6f60436e2a9985516c6c966a47"
  },
  {
    "url": "images/icons/icon-384x384.png",
    "revision": "75776611ccd0920913cca8a4826dd96a"
  },
  {
    "url": "images/icons/icon-512x512.png",
    "revision": "6b7fe89fc3bb8f762e567a5352bb90b8"
  },
  {
    "url": "images/icons/icon-72x72.png",
    "revision": "629684d78bc03593e6f35dc3d079beae"
  },
  {
    "url": "images/icons/icon-96x96.png",
    "revision": "323c636180a6518b21a7b49301b44cba"
  },
  {
    "url": "img/error.png",
    "revision": "90901890fbf9b379405f47a23313e63b"
  },
  {
    "url": "img/play-pause.png",
    "revision": "a012413b54276e2eefd145c7aec60f93"
  },
  {
    "url": "img/video-play.png",
    "revision": "288308b2037f409d293916c7a3913f20"
  },
  {
    "url": "js/app.js",
    "revision": "63fe199fd7db8f964b74e45376bd2bc4"
  },
  {
    "url": "sw-base.js",
    "revision": "b3398478eaf60930bd831c9f2c3aea9a"
  },
  {
    "url": "offline.html",
    "revision": "1d028cacbeda70df31e673f70cd6a250"
  }
]);

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