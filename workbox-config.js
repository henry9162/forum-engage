module.exports = {
    maximumFileSizeToCacheInBytes: 5000000,
	globDirectory: 'public/',
	globPatterns: ['**/*.{js,css,png,svg,js,json,xml,jpg}','offline.html'],
	swSrc: 'public/sw-base.js',
    swDest: 'public/service-worker.js',
	globIgnores: [
		'../workbox-cli-config.js',
		'photos/**'
  ]
};