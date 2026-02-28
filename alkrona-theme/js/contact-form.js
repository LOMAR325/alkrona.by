(() => {
    if (typeof contactForm === 'undefined') {
        return;
    }

    const forms = document.querySelectorAll('.js-contact-form');
    if (!forms.length) {
        return;
    }

    const getCurrentProductName = () => {
        const titleElement = document.querySelector('.product-hero__title, .product_title, h1');
        if (!titleElement || !titleElement.textContent) {
            return '';
        }
        return titleElement.textContent.trim();
    };

    forms.forEach((form) => {
        const statusContainer = form.closest('.form-block, .product-popup__content');
        const status = statusContainer ? statusContainer.querySelector('.js-form-status') : null;
        const submitButton = form.querySelector('button[type="submit"]');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            const formData = new FormData(form);
            formData.append('action', 'send_contact_form');
            formData.append('security', contactForm.nonce);

            const currentProductName = (formData.get('product_name') || '').toString().trim() || getCurrentProductName();
            if (currentProductName !== '') {
                formData.set('product_name', currentProductName);
            }

            if (status) {
                status.textContent = 'Отправка...';
            }

            if (submitButton) {
                submitButton.disabled = true;
            }

            try {
                const response = await fetch(contactForm.ajax_url, {
                    method: 'POST',
                    credentials: 'same-origin',
                    body: formData,
                });

                const data = await response.json();
                const message = data && typeof data.data === 'string' ? data.data : 'Ошибка отправки.';

                if (status) {
                    status.textContent = message;
                }

                if (response.ok && data && data.success) {
                    form.reset();
                    const hiddenProductField = form.querySelector('.js-product-name');
                    if (hiddenProductField && currentProductName !== '') {
                        hiddenProductField.value = currentProductName;
                    }
                }
            } catch (error) {
                if (status) {
                    status.textContent = 'Ошибка соединения.';
                }
            } finally {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            }
        });
    });
})();
