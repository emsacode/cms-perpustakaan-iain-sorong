@props(['value', 'class' => ''])

<span x-data="{ 
          current: 0, 
          target: {{ $value }}, 
          duration: 1500,
          animate() {
              let start = null;
              const step = (timestamp) => {
                  if (!start) start = timestamp;
                  const progress = Math.min((timestamp - start) / this.duration, 1);
                  this.current = Math.floor(progress * this.target);
                  if (progress < 1) {
                      window.requestAnimationFrame(step);
                  }
              };
              window.requestAnimationFrame(step);
          }
      }"
      x-init="
          const observer = new IntersectionObserver((entries) => {
              if (entries[0].isIntersecting) {
                  animate();
                  observer.disconnect();
              }
          }, { threshold: 0.1 });
          observer.observe($el);
      "
      class="{{ $class }}"
      x-text="current">
    0
</span>
