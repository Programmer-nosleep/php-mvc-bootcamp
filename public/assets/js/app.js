(() => {
  function copyTextFromInput(input) {
    input.focus();
    input.select();
    input.setSelectionRange(0, input.value.length);

    const text = input.value;

    if (navigator.clipboard?.writeText) {
      return navigator.clipboard.writeText(text);
    }

    document.execCommand("copy");
    return Promise.resolve();
  }

  document.addEventListener("click", async (event) => {
    const button = event.target.closest("[data-copy]");
    if (!button) return;

    const selector = button.getAttribute("data-copy");
    if (!selector) return;

    const input = document.querySelector(selector);
    if (!(input instanceof HTMLInputElement)) return;

    const originalText = button.textContent || "Copy";

    try {
      await copyTextFromInput(input);
      button.textContent = "Copied";
      button.setAttribute("data-copied", "1");
      setTimeout(() => {
        button.textContent = originalText;
        button.removeAttribute("data-copied");
      }, 1200);
    } catch {
      // noop
    }
  });
})();

