document.addEventListener('DOMContentLoaded', () => {
  const toggle = document.querySelector('[data-menu-toggle]');
  const nav = document.querySelector('[data-main-nav]');
  if (toggle && nav) toggle.addEventListener('click', () => {
    const open = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', String(open));
  });

  document.querySelectorAll('[data-mark-progress]').forEach(button => {
    button.addEventListener('click', async () => {
      const original = button.textContent;
      button.disabled = true;
      try {
        const response = await fetch(`${window.SOMALILINGUA_BASE || '/SomaliLingua'}/api/progress.php`, {
          method: 'POST', headers: {'Content-Type':'application/json'},
          body: JSON.stringify({item_type: button.dataset.itemType, item_id: button.dataset.itemId, csrf_token: button.dataset.csrf})
        });
        const result = await response.json();
        if (!response.ok || !result.ok) throw new Error(result.message || 'Could not save progress.');
        button.textContent = '✓ Saved'; button.classList.add('is-saved');
      } catch (error) { button.disabled = false; button.textContent = original; alert(error.message); }
    });
  });
});
