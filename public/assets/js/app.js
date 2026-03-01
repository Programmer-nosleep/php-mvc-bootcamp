(() => {
  function getMetaContent(name) {
    const el = document.querySelector(`meta[name="${name}"]`);
    return el?.getAttribute("content") || "";
  }

  function buildUrl(path) {
    const base = getMetaContent("app-url") || "/";
    const clean = String(path || "").replace(/^\/+/, "");
    return new URL(clean, base).toString();
  }

  function csrfToken() {
    return getMetaContent("csrf-token");
  }

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

  async function requestMidtransToken(idName) {
    const token = csrfToken();
    const body = new URLSearchParams();
    body.set("id_name", idName);
    body.set("_token", token);

    const response = await fetch(buildUrl("/midtrans/token"), {
      method: "POST",
      headers: {
        Accept: "application/json",
        "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
        "X-CSRF-TOKEN": token,
      },
      body: body.toString(),
    });

    const data = await response.json().catch(() => ({}));
    if (!response.ok) {
      throw new Error(data?.message || "Failed to initialize payment.");
    }

    return data;
  }

  document.addEventListener("click", async (event) => {
    const button = event.target.closest("[data-midtrans-pay]");
    if (!button) return;

    const idName = button.getAttribute("data-id-name") || "";
    if (!idName) return;

    if (!window.snap?.pay) {
      alert("Midtrans Snap is not loaded yet.");
      return;
    }

    const originalText = button.textContent || "Pay";
    button.setAttribute("disabled", "disabled");
    button.textContent = "Processing...";

    try {
      const { token } = await requestMidtransToken(idName);
      window.snap.pay(token, {
        onSuccess: () => {
          button.textContent = "Paid";
        },
        onPending: () => {
          button.textContent = "Pending";
          button.removeAttribute("disabled");
        },
        onError: () => {
          button.textContent = originalText;
          button.removeAttribute("disabled");
          alert("Payment failed. Please try again.");
        },
        onClose: () => {
          button.textContent = originalText;
          button.removeAttribute("disabled");
        },
      });
    } catch (error) {
      button.textContent = originalText;
      button.removeAttribute("disabled");
      alert(error?.message || "Payment failed. Please try again.");
    }
  });
})();
