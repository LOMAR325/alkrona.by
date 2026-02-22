(function () {
  const params = new URLSearchParams(window.location.search);
  const productId = params.get('id');

  const titleEl = document.querySelector('[data-product-title]');
  const latinEl = document.querySelector('[data-product-latin]');
  const descEl = document.querySelector('[data-product-description]');
  const pricePrefixEl = document.querySelector('[data-product-price-prefix]');
  const priceEl = document.querySelector('[data-product-price]');
  const sizeTextEl = document.querySelector('[data-product-size]');
  const imageEl = document.querySelector('[data-product-image]');
  const matrixEl = document.getElementById('priceMatrix');

  const formatPrice = (value, currency) => {
    if (typeof value === 'number') return `${value.toFixed(2)} ${currency || ''}`.trim();
    if (value) return `${value} ${currency || ''}`.trim();
    return '—';
  };

  const renderMatrix = (variants, currency) => {
    if (!matrixEl) return;
    matrixEl.innerHTML = '';
    if (!variants || !variants.length) {
      matrixEl.innerHTML = '<tr><td>Цены уточняйте у менеджера</td></tr>';
      return;
    }
    const headerRow = document.createElement('tr');
    const priceRow = document.createElement('tr');

    const headerFirst = document.createElement('th');
    headerFirst.textContent = 'размер горшка';
    headerRow.appendChild(headerFirst);

    const priceFirst = document.createElement('th');
    priceFirst.textContent = 'цена';
    priceRow.appendChild(priceFirst);

    variants.forEach((variant) => {
      const th = document.createElement('th');
      th.textContent = variant.size || '';
      headerRow.appendChild(th);

      const td = document.createElement('td');
      td.textContent = formatPrice(variant.price, currency);
      priceRow.appendChild(td);
    });

    matrixEl.appendChild(headerRow);
    matrixEl.appendChild(priceRow);
  };

  const applyProduct = (product) => {
    if (titleEl) titleEl.textContent = product.name || 'Без названия';
    if (latinEl) latinEl.textContent = product.latinName || '';
    if (descEl) descEl.textContent = product.description || '';
    if (pricePrefixEl) pricePrefixEl.textContent = product.pricePrefix ? `${product.pricePrefix} ` : '';
    if (priceEl) priceEl.textContent = formatPrice(product.price, product.currency);
    if (sizeTextEl) sizeTextEl.textContent = product.size || '';
    if (imageEl) {
      imageEl.src = product.heroImage || product.image || '';
      imageEl.alt = product.name || '';
    }
    if (product.name) {
      document.title = `${product.name} — Алькрона`;
    }
    renderMatrix(product.variants, product.currency);
  };

  const showError = () => {
    if (titleEl) titleEl.textContent = 'Товар не найден';
    if (latinEl) latinEl.textContent = '';
    if (descEl) descEl.textContent = 'Попробуйте вернуться в каталог.';
    if (matrixEl) matrixEl.innerHTML = '';
  };

  fetch('assets/data/catalog.json')
    .then((res) => res.json())
    .then((data) => {
      const products = (data.categories || []).flatMap((cat) => cat.products || []);
      const fallback = products[0];
      const product = products.find((p) => p.id === productId) || fallback;
      if (product) applyProduct(product);
      else showError();
    })
    .catch(() => showError());
})();
