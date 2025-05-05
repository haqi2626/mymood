document.addEventListener('DOMContentLoaded', function() {
  // Element selectors
  const hamburgerMenu = document.getElementById('hamburger-menu');
  const closeButton = document.getElementById('close-sidebar');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');
  const contentWrapper = document.getElementById('content-wrapper');
  const menuLinks = document.querySelectorAll('.sidebar-menu-item');
  
  // Open sidebar
  hamburgerMenu.addEventListener('click', function() {
      sidebar.classList.add('active');
      overlay.classList.add('active');
      contentWrapper.classList.add('shifted');
  });
  
  // Close sidebar with X button
  closeButton.addEventListener('click', function() {
      closeSidebar();
  });
  
  // Close sidebar with overlay click
  overlay.addEventListener('click', function() {
      closeSidebar();
  });
  
  // Close sidebar with menu item click
  menuLinks.forEach(function(link) {
      link.addEventListener('click', function() {
          closeSidebar();
      });
  });

  // Function to close sidebar
  function closeSidebar() {
      sidebar.classList.remove('active');
      overlay.classList.remove('active');
      contentWrapper.classList.remove('shifted');
  }

  // Close sidebar with escape key
  document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && sidebar.classList.contains('active')) {
          closeSidebar();
      }
  });
});