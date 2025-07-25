// service-worker.js
const CACHE_NAME = 'ctc-site-cache-v1';
const urlsToCache = [
  '/',
  '/index.php',
  '/about.html',
  '/blog.html',
  '/case-study-standard.html',
  '/case-study-details.html',
  '/assets/css/style2.css',
  '/assets/css/bootstrap.min.css',
  '/assets/js/app.js',
  '/assets/img/logo.svg',
  // Add more URLs to cache as needed
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => cache.addAll(urlsToCache))
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response =>
        response || fetch(event.request)
      )
  );
});

self.addEventListener('activate', event => {
  const cacheWhitelist = [CACHE_NAME];
  event.waitUntil(
    caches.keys().then(keyList =>
      Promise.all(keyList.map(key => {
        if (!cacheWhitelist.includes(key)) {
          return caches.delete(key);
        }
      }))
    )
  );
});
