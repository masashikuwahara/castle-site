function initGalleryModal() {
  const modal = document.getElementById("galleryModal");
  if (!modal) return;

  const overlay = document.getElementById("galleryOverlay");
  const closeBtn = document.getElementById("galleryClose");
  const imgEl = document.getElementById("galleryImage");
  const capEl = document.getElementById("galleryCaption");
  const prevBtn = document.getElementById("galleryPrev");
  const nextBtn = document.getElementById("galleryNext");

  const items = Array.from(document.querySelectorAll('[data-gallery="place-gallery"]'));
  if (items.length === 0) return;

  let current = 0;

  function render() {
    const el = items[current];
    const src = el.getAttribute("data-src") || "";
    const caption = el.getAttribute("data-caption") || "";

    imgEl.src = src;
    capEl.textContent = caption;

    // 1枚しかない時は矢印を消す
    const showNav = items.length > 1;
    prevBtn.style.display = showNav ? "" : "none";
    nextBtn.style.display = showNav ? "" : "none";
  }

  function openAt(index) {
    current = index;
    render();
    modal.classList.remove("hidden");
    document.body.classList.add("overflow-hidden");
  }

  function close() {
    modal.classList.add("hidden");
    document.body.classList.remove("overflow-hidden");
    imgEl.src = ""; // 画像読み込み停止
  }

  function prev() {
    current = (current - 1 + items.length) % items.length;
    render();
  }

  function next() {
    current = (current + 1) % items.length;
    render();
  }

  // click thumbnail
  items.forEach((el, idx) => {
    el.addEventListener("click", () => openAt(idx));
  });

  // buttons
  closeBtn.addEventListener("click", close);
  // overlay.addEventListener("click", close);
  prevBtn.addEventListener("click", prev);
  nextBtn.addEventListener("click", next);

  modal.addEventListener("click", (e) => {
  // クリックされた要素から上にたどって、data-close="0" があれば閉じない
    if (e.target.closest('[data-close="0"]')) return;

  // data-close="1" 側（背景・余白）をクリックしたら閉じる
    if (e.target.closest('[data-close="1"]')) close();
  });


  // keyboard
  document.addEventListener("keydown", (e) => {
    if (modal.classList.contains("hidden")) return;

    if (e.key === "Escape") close();
    if (e.key === "ArrowLeft") prev();
    if (e.key === "ArrowRight") next();
  });
}

// Turbo/SPAでないのでDOMContentLoadedでOK
document.addEventListener("DOMContentLoaded", initGalleryModal);
