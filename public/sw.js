const CACHE_NAME = 'mood-cache-v1';

const PRECACHE_URLS = ['/'];

// Install: precache essential resources
self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME).then(cache => cache.addAll(PRECACHE_URLS))
  );
  self.skipWaiting();
});

// Activate: clean up old caches
self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys
          .filter(key => key !== CACHE_NAME)
          .map(key => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

// Fetch: route requests by strategy
self.addEventListener('fetch', event => {
  const {request} = event;
  const url = new URL(request.url);

  // Only handle same-origin requests
  if (url.origin !== self.location.origin) return;

  // Network-only: mutations (POST/PUT/DELETE)
  if (request.method !== 'GET') return;

  // Network-only: audio files and track media
  if (
    url.pathname.match(/\.(mp3|wav|ogg|flac|aac|m4a)$/i) ||
    url.pathname.includes('/storage/track_media/') ||
    request.headers.get('range') ||
    (request.headers.get('accept') || '').includes('audio/')
  ) {
    return;
  }

  // Cache-first: Vite-hashed assets (immutable)
  if (url.pathname.includes('/build/assets/')) {
    event.respondWith(cacheFirst(request));
    return;
  }

  // Cache-first: favicons and static images
  if (
    url.pathname.startsWith('/favicon/') ||
    url.pathname.startsWith('/images/')
  ) {
    event.respondWith(cacheFirst(request));
    return;
  }

  // Network-first: API GET requests
  if (url.pathname.startsWith('/api/')) {
    event.respondWith(networkFirst(request));
    return;
  }

  // Network-first: HTML navigation requests
  if (request.mode === 'navigate') {
    event.respondWith(networkFirstWithOfflineFallback(request));
    return;
  }

  // Default: network-first for everything else
  event.respondWith(networkFirst(request));
});

async function cacheFirst(request) {
  const cached = await caches.match(request);
  if (cached) return cached;

  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    return new Response('', {status: 408, statusText: 'Offline'});
  }
}

async function networkFirst(request) {
  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    const cached = await caches.match(request);
    return cached || new Response('', {status: 408, statusText: 'Offline'});
  }
}

async function networkFirstWithOfflineFallback(request) {
  try {
    const response = await fetch(request);
    if (response.ok) {
      const cache = await caches.open(CACHE_NAME);
      cache.put(request, response.clone());
    }
    return response;
  } catch {
    const cached = await caches.match(request);
    if (cached) return cached;

    return new Response(
      `<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Mood Music - Hors ligne</title>
  <style>
    body{font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;background:#1a1a2e;color:#e0e0e0;text-align:center}
    .container{padding:2rem}
    h1{font-size:1.5rem;margin-bottom:0.5rem}
    p{color:#999;margin-bottom:1.5rem}
    button{background:#689f38;color:#fff;border:none;padding:0.75rem 1.5rem;border-radius:8px;font-size:1rem;cursor:pointer}
    button:hover{background:#7cb342}
  </style>
</head>
<body>
  <div class="container">
    <h1>Vous êtes hors ligne</h1>
    <p>Vérifiez votre connexion internet et réessayez.</p>
    <button onclick="location.reload()">Réessayer</button>
  </div>
</body>
</html>`,
      {
        status: 200,
        headers: {'Content-Type': 'text/html; charset=utf-8'},
      }
    );
  }
}
