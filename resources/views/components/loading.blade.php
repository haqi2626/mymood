

<div id="loading">
    <div id="lottie-loading"></div>
    <div id="loading-text">Loading...</div>
  </div>
  
  <style>
    #loading {
      position: fixed;
      top: 0; left: 0;
      width: 100%; height: 100%;
      background: #f9f9f9;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      transition: all 1s ease;
    }
    #lottie-loading { width: 300px; height: 300px; }
    #loading-text {
      margin-top: 20px;
      font-size: 24px;
      font-weight: bold;
      color: #555;
      animation: blink 1s infinite;
    }
    @keyframes blink {
      0%, 100% { opacity: 1; }
      50% { opacity: 0.5; }
    }
    #loading.hide {
      transform: translateX(100%);
      opacity: 0;
      pointer-events: none;
    }
  </style>
  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bodymovin/5.7.6/lottie.min.js"></script>
  <script>
    const animation = lottie.loadAnimation({
      container: document.getElementById('lottie-loading'),
      renderer: 'svg',
      loop: true,
      autoplay: true,
      path: '{{ asset('lottie/WKnWHI9nUS.json') }}'
    });
  
    window.addEventListener('load', () => {
      setTimeout(() => {
        document.getElementById('loading').classList.add('hide');
        document.getElementById('content').classList.add('show');
        document.body.style.overflow = 'auto';
      }, 3000);
    });
  </script>
    
  