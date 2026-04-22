<script>
/* -------------------------------
   PAGE SPEED OPTIMIZATION SCRIPT
   ------------------------------- */

// ✅ 1. Lazy load images & iframes
document.addEventListener("DOMContentLoaded", function() {
  const lazyElements = document.querySelectorAll('img[data-src], iframe[data-src]');
  const lazyLoad = (element) => {
    element.src = element.dataset.src;
    element.removeAttribute('data-src');
  };
  
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        lazyLoad(entry.target);
        obs.unobserve(entry.target);
      }
    });
  }, { rootMargin: "200px" });
  
  lazyElements.forEach(el => observer.observe(el));
});

// ✅ 2. Defer non-critical JavaScript files
(function() {
  const scripts = document.querySelectorAll('script[data-defer]');
  scripts.forEach(script => {
    const s = document.createElement('script');
    s.src = script.getAttribute('data-src');
    s.async = true;
    document.body.appendChild(s);
  });
})();

// ✅ 3. Preload critical CSS
(function() {
  const preloadLinks = document.querySelectorAll('link[rel="preload-css"]');
  preloadLinks.forEach(link => {
    const l = document.createElement('link');
    l.rel = 'stylesheet';
    l.href = link.getAttribute('data-href');
    document.head.appendChild(l);
  });
})();
</script>
