// Theme Switcher - Dark Mode Toggle
document.addEventListener('DOMContentLoaded', () => {
  const themeToggleBtn = document.getElementById('theme-toggle-btn');
  const htmlEl = document.documentElement;

  const currentTheme = localStorage.getItem('bc_theme') || 'light';
  htmlEl.setAttribute('data-bs-theme', currentTheme);
  updateToggleButton(currentTheme);

  if (themeToggleBtn) {
    themeToggleBtn.addEventListener('click', () => {
      const newTheme = htmlEl.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
      htmlEl.setAttribute('data-bs-theme', newTheme);
      localStorage.setItem('bc_theme', newTheme);
      updateToggleButton(newTheme);
    });
  }

  function updateToggleButton(theme) {
    if (!themeToggleBtn) return;
    if (theme === 'dark') {
      themeToggleBtn.innerHTML = '<i class="bi bi-sun-fill text-warning"></i> Light Mode';
    } else {
      themeToggleBtn.innerHTML = '<i class="bi bi-moon-stars-fill text-primary"></i> Dark Mode';
    }
  }
});
