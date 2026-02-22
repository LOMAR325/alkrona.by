(function () {
  const grid = document.getElementById('productsGrid');
  const template = document.getElementById('productCardTemplate');
  const categoryTitleEls = document.querySelectorAll('[data-category-title]');
  const categoryDescriptionEl = document.querySelector('[data-category-description]');
  const productsCountEl = document.querySelector('[data-products-count]');
  const filtersWrap = document.getElementById('catalogFilters');

  if (!grid || !template) return;

  const params = new URLSearchParams(window.location.search);
  const requestedSlug = params.get('category');

  const setPageCategory = (category) => {
    categoryTitleEls.forEach((el) => {
      el.textContent = category.title;
    });
    if (categoryDescriptionEl) {
      categoryDescriptionEl.textContent = category.description || '';
    }
    if (productsCountEl) {
      productsCountEl.textContent = `${category.products.length} позиций`;
    }
  };

  const setActiveChip = (activeSlug) => {
    if (!filtersWrap) return;
    filtersWrap.querySelectorAll('.catalog-chip').forEach((chip) => {
      chip.classList.toggle('catalog-chip--active', chip.dataset.slug === activeSlug);
    });
  };

  const updateUrl = (slug) => {
    const search = new URLSearchParams(window.location.search);
    search.set('category', slug);
    const newUrl = `${window.location.pathname}?${search.toString()}`;
    window.history.replaceState({}, '', newUrl);
  };

  const renderProducts = (category) => {
    grid.innerHTML = '';

    if (!category.products.length) {
      grid.innerHTML = '<p class="catalog__empty">Товары в этой категории появятся скоро</p>';
      return;
    }

    category.products.forEach((product) => {
      const clone = template.content.firstElementChild.cloneNode(true);

      const img = clone.querySelector('.product-card__img');
      if (img) {
        img.src = product.image;
        img.alt = product.name || '';
        img.loading = 'lazy';
      }

      const titleEl = clone.querySelector('.product-card__title');
      if (titleEl) titleEl.textContent = product.name || '';

      const priceEl = clone.querySelector('.product-card__price');
      if (priceEl) {
        const pricePrefixEl = priceEl.querySelector('.product-card__price-from');
        const priceValueEl = priceEl.querySelector('.product-card__price-value');
        if (pricePrefixEl) pricePrefixEl.textContent = product.pricePrefix ? `${product.pricePrefix} ` : '';
        if (priceValueEl) {
          const rawPrice = typeof product.price === 'number' ? product.price.toFixed(2) : (product.price || '—');
          priceValueEl.textContent = `${rawPrice} ${product.currency || ''}`.trim();
        }
      }

      const btn = clone.querySelector('.product-card__btn');
      if (btn) {
        const detailHref = product.link || `product.html?id=${encodeURIComponent(product.id)}`;
        btn.href = detailHref;
        btn.setAttribute('aria-label', `Подробнее: ${product.name}`);
      }

      const potText = clone.querySelector('.pot-badge text');
      if (potText) potText.textContent = product.size || '';

      grid.appendChild(clone);
    });
  };

  const buildFilters = (categories, activeSlug) => {
    if (!filtersWrap) return;
    filtersWrap.innerHTML = '';

    categories.forEach((category) => {
      const chip = document.createElement('button');
      chip.type = 'button';
      chip.className = 'catalog-chip';
      chip.dataset.slug = category.slug;
      chip.textContent = category.title;
      chip.addEventListener('click', () => {
        setPageCategory(category);
        renderProducts(category);
        setActiveChip(category.slug);
        updateUrl(category.slug);
      });
      if (category.slug === activeSlug) chip.classList.add('catalog-chip--active');
      filtersWrap.appendChild(chip);
    });
  };

  const init = (data) => {
    if (!data.categories || !data.categories.length) return;
    const fallbackCategory = data.categories[0];
    const activeCategory = data.categories.find((cat) => cat.slug === requestedSlug) || fallbackCategory;

    setPageCategory(activeCategory);
    buildFilters(data.categories, activeCategory.slug);
    renderProducts(activeCategory);
  };

  fetch('assets/data/catalog.json')
    .then((res) => {
      if (!res.ok) throw new Error('network');
      return res.json();
    })
    .then((data) => init(data))
    .catch(() => {
      grid.innerHTML = '<p class="catalog__error">Не удалось загрузить каталог. Попробуйте позже.</p>';
    });
})();
