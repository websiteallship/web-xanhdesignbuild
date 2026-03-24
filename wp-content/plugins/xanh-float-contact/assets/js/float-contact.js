/**
 * XANH Float Contact — FAB interactions.
 *
 * Toggle open/close, close on outside click/Escape,
 * auto-close after idle timeout.
 *
 * @package XanhFloatContact
 */
(function () {
  'use strict';

  /** Auto-close delay in ms. */
  var IDLE_TIMEOUT = 8000;

  var container = document.getElementById('xfc-float-contact');
  if (!container) return;

  var trigger  = container.querySelector('.xfc-float__trigger');
  var channels = container.querySelector('.xfc-float__channels');
  if (!trigger || !channels) return;

  var idleTimer = null;

  /**
   * Open the FAB menu.
   */
  function open() {
    container.classList.add('is-open');
    trigger.setAttribute('aria-expanded', 'true');
    channels.setAttribute('aria-hidden', 'false');
    resetIdleTimer();
  }

  /**
   * Close the FAB menu.
   */
  function close() {
    container.classList.remove('is-open');
    trigger.setAttribute('aria-expanded', 'false');
    channels.setAttribute('aria-hidden', 'true');
    clearIdleTimer();
  }

  /**
   * Toggle open/close.
   */
  function toggle() {
    if (container.classList.contains('is-open')) {
      close();
    } else {
      open();
    }
  }

  /**
   * Reset the idle auto-close timer.
   */
  function resetIdleTimer() {
    clearIdleTimer();
    idleTimer = setTimeout(function () {
      close();
    }, IDLE_TIMEOUT);
  }

  /**
   * Clear the idle timer.
   */
  function clearIdleTimer() {
    if (idleTimer) {
      clearTimeout(idleTimer);
      idleTimer = null;
    }
  }

  /* ── Event Listeners ── */

  // Toggle on trigger click.
  trigger.addEventListener('click', function (e) {
    e.stopPropagation();
    toggle();
  });

  // Close on outside click.
  document.addEventListener('click', function (e) {
    if (!container.contains(e.target)) {
      close();
    }
  });

  // Close on Escape key.
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && container.classList.contains('is-open')) {
      close();
      trigger.focus();
    }
  });

  // Reset idle timer on any interaction within the widget.
  container.addEventListener('mouseover', function () {
    if (container.classList.contains('is-open')) {
      resetIdleTimer();
    }
  });

  container.addEventListener('focusin', function () {
    if (container.classList.contains('is-open')) {
      resetIdleTimer();
    }
  });
})();
