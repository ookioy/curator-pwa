const CACHE = 'curator-v1';
const SHELL = [
  'style.css',     
  'offline.php',   
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
];

self.addEventListener('install', e => {
  e.waitUntil(
    caches.open(CACHE).then(c => c.addAll(SHELL))
  );
  self.skipWaiting();
});

self.addEventListener('activate', e => {
  e.waitUntil(
    caches.keys().then(keys =>
      Promise.all(keys.filter(k => k !== CACHE).map(k => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', e => {
  const { request } = e;

  // PHP-сторінки: network-first, при помилці — офлайн
  if (request.url.includes('.php') || request.url.endsWith('/')) {
    e.respondWith(
      fetch(request)
        .catch(() => caches.match('offline.php'))
    );
    return;
  }

  // Статичні ресурси: cache-first
  e.respondWith(
    caches.match(request).then(cached => {
      if (cached) return cached;
      return fetch(request).then(resp => {
        if (resp.ok) {
          const clone = resp.clone();
          caches.open(CACHE).then(c => c.put(request, clone));
        }
        return resp;
      });
    })
  );
});