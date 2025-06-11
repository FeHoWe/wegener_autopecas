document.addEventListener('DOMContentLoaded', function () {
    const btnMenu = document.getElementById('btn-menu');
    const menuLateral = document.getElementById('menu-lateral');
  
    if (btnMenu && menuLateral) {
      btnMenu.addEventListener('click', () => {
        const isVisible = menuLateral.style.display === 'block';
        menuLateral.style.display = isVisible ? 'none' : 'block';
      });
  
      document.addEventListener('click', (event) => {
        if (!btnMenu.contains(event.target) && !menuLateral.contains(event.target)) {
          menuLateral.style.display = 'none';
        }
      });
    }
  });
  