const header = document.querySelector('.site-header');
const toggle = document.querySelector('.site-header__toggle');
const drawerLinks = document.querySelectorAll('.site-header__drawer a');

if (toggle && header) {
    toggle.addEventListener('click', () => {
        const isOpen = header.classList.toggle('site-header--open');
        toggle.setAttribute('aria-expanded', String(isOpen));
    });

    drawerLinks.forEach((link) => {
        link.addEventListener('click', () => {
            header.classList.remove('site-header--open');
            toggle.setAttribute('aria-expanded', 'false');
        });
    });
}

const initProductGallery = () => {
    const galleries = document.querySelectorAll('[data-product-gallery]');

    galleries.forEach((gallery) => {
        const slides = Array.from(gallery.querySelectorAll('[data-gallery-slide]'));
        const thumbs = Array.from(gallery.querySelectorAll('[data-gallery-thumb]'));
        const prevButton = gallery.querySelector('[data-gallery-prev]');
        const nextButton = gallery.querySelector('[data-gallery-next]');

        if (slides.length <= 1) {
            return;
        }

        let currentIndex = slides.findIndex((slide) => slide.classList.contains('is-active'));
        if (currentIndex < 0) {
            currentIndex = 0;
        }

        const setSlide = (nextIndex) => {
            const total = slides.length;
            const index = (nextIndex % total + total) % total;
            currentIndex = index;

            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === index;
                slide.classList.toggle('is-active', isActive);
                slide.setAttribute('aria-hidden', String(!isActive));
            });

            thumbs.forEach((thumb, thumbIndex) => {
                const isActive = thumbIndex === index;
                thumb.classList.toggle('is-active', isActive);
                thumb.setAttribute('aria-current', isActive ? 'true' : 'false');
            });
        };

        prevButton?.addEventListener('click', () => {
            setSlide(currentIndex - 1);
        });

        nextButton?.addEventListener('click', () => {
            setSlide(currentIndex + 1);
        });

        thumbs.forEach((thumb) => {
            thumb.addEventListener('click', () => {
                const index = Number.parseInt(thumb.dataset.galleryThumb || '-1', 10);
                if (Number.isNaN(index) || index < 0) {
                    return;
                }
                setSlide(index);
            });
        });

        gallery.addEventListener('keydown', (event) => {
            if (event.key === 'ArrowLeft') {
                event.preventDefault();
                setSlide(currentIndex - 1);
            }
            if (event.key === 'ArrowRight') {
                event.preventDefault();
                setSlide(currentIndex + 1);
            }
        });

        setSlide(currentIndex);
    });
};

initProductGallery();

const phonePattern = /^\+375 \(\d{2}\) \d{3}-\d{2}-\d{2}$/;
const phonePrefix = '+375 (';

const getCurrentProductName = () => {
    const candidates = [
        document.querySelector('.product-hero__title'),
        document.querySelector('.product_title'),
        document.querySelector('h1'),
    ];

    for (const item of candidates) {
        if (!item) continue;
        const text = item.textContent ? item.textContent.trim() : '';
        if (text !== '') return text;
    }

    return '';
};

const setProductName = (productName = '') => {
    const normalizedName = productName.trim() !== '' ? productName.trim() : getCurrentProductName();

    document.querySelectorAll('.js-product-name').forEach((input) => {
        input.value = normalizedName;
    });

    document.querySelectorAll('.js-popup-product-name').forEach((node) => {
        node.textContent = normalizedName || 'Не указан';
    });
};

const initFormValidation = (form) => {
    const nameInput = form.querySelector('input[name="name"]');
    const phoneInput = form.querySelector('input[name="phone"]');
    const emailInput = form.querySelector('input[name="email"]');

    if (!nameInput || !phoneInput) {
        return;
    }

    const formatPhone = (value) => {
        let digits = value.replace(/\D/g, '');
        if (digits.startsWith('375')) {
            digits = digits.slice(3);
        }
        digits = digits.slice(0, 9);

        const part1 = digits.slice(0, 2);
        const part2 = digits.slice(2, 5);
        const part3 = digits.slice(5, 7);
        const part4 = digits.slice(7, 9);

        let result = '+375';
        if (part1.length) {
            result += ' (' + part1;
            if (part1.length === 2) result += ')';
        }
        if (part2.length) {
            result += ' ' + part2;
        }
        if (part3.length) {
            result += '-' + part3;
        }
        if (part4.length) {
            result += '-' + part4;
        }
        return result;
    };

    const setPhoneCaretEnd = () => {
        requestAnimationFrame(() => {
            const pos = phoneInput.value.length;
            phoneInput.setSelectionRange(pos, pos);
        });
    };

    const getLocalDigitsBeforeCaret = (value, caretPos) => {
        let count = 0;
        for (let i = 0; i < caretPos; i += 1) {
            if (/\d/.test(value[i])) count += 1;
        }
        return Math.max(0, count - 3);
    };

    const removeLocalDigitAt = (value, index) => {
        let digits = value.replace(/\D/g, '');
        if (digits.startsWith('375')) digits = digits.slice(3);
        if (index < 0 || index >= digits.length) return digits;
        return digits.slice(0, index) + digits.slice(index + 1);
    };

    const setCaretByLocalDigits = (localCount) => {
        if (localCount <= 0) {
            phoneInput.setSelectionRange(phonePrefix.length, phonePrefix.length);
            return;
        }
        const targetDigits = localCount + 3;
        let count = 0;
        for (let i = 0; i < phoneInput.value.length; i += 1) {
            if (/\d/.test(phoneInput.value[i])) {
                count += 1;
                if (count === targetDigits) {
                    phoneInput.setSelectionRange(i + 1, i + 1);
                    return;
                }
            }
        }
        setPhoneCaretEnd();
    };

    const ensurePhonePrefix = () => {
        if (!phoneInput.value) {
            phoneInput.value = phonePrefix;
        } else if (!phoneInput.value.startsWith('+375')) {
            phoneInput.value = formatPhone(phoneInput.value);
        }
        setPhoneCaretEnd();
    };

    nameInput.addEventListener('input', () => {
        nameInput.value = nameInput.value.replace(/[^a-zA-Zа-яА-ЯёЁ\s\-]/g, '');
        nameInput.setCustomValidity('');
    });

    phoneInput.addEventListener('focus', () => {
        ensurePhonePrefix();
    });

    phoneInput.addEventListener('click', () => {
        if (phoneInput.selectionStart < phonePrefix.length) {
            setPhoneCaretEnd();
        }
    });

    phoneInput.addEventListener('keydown', (event) => {
        const start = phoneInput.selectionStart;
        const end = phoneInput.selectionEnd;
        if (start === null || end === null) return;

        if (event.key === 'Backspace' && start <= phonePrefix.length) {
            event.preventDefault();
            setPhoneCaretEnd();
            return;
        }

        if (event.key === 'Backspace' && start === end) {
            const caret = start;
            if (caret > phonePrefix.length && /\D/.test(phoneInput.value[caret - 1])) {
                event.preventDefault();
                const localBefore = getLocalDigitsBeforeCaret(phoneInput.value, caret);
                const newDigits = removeLocalDigitAt(phoneInput.value, localBefore - 1);
                phoneInput.value = formatPhone(newDigits);
                setCaretByLocalDigits(localBefore - 1);
                phoneInput.setCustomValidity('');
            }
        }
    });

    phoneInput.addEventListener('input', () => {
        phoneInput.value = formatPhone(phoneInput.value);
        phoneInput.setCustomValidity('');
        setPhoneCaretEnd();
    });

    phoneInput.addEventListener('blur', () => {
        if (phoneInput.value && !phonePattern.test(phoneInput.value)) {
            phoneInput.setCustomValidity('Введите номер в формате +375 (__) ___-__-__');
        } else {
            phoneInput.setCustomValidity('');
        }
    });

    if (emailInput) {
        emailInput.addEventListener('input', () => {
            emailInput.value = emailInput.value.replace(/\s+/g, '');
        });
    }

    form.addEventListener('submit', (event) => {
        if (phoneInput.value && !phonePattern.test(phoneInput.value)) {
            phoneInput.setCustomValidity('Введите номер в формате +375 (__) ___-__-__');
            phoneInput.reportValidity();
            phoneInput.focus();
            setPhoneCaretEnd();
            event.preventDefault();
        }

        if (nameInput.value.trim().length < 2) {
            nameInput.setCustomValidity('Введите корректное имя');
            nameInput.reportValidity();
            event.preventDefault();
        }
    });
};

document.querySelectorAll('.js-contact-form').forEach((form) => {
    initFormValidation(form);
});

setProductName();

const popup = document.getElementById('productBuyPopup');
const buyButtons = document.querySelectorAll('.btn--buy, [data-open-contact-popup]');
let lastFocusedElement = null;

const openPopup = (productName = '') => {
    if (!popup) return;

    setProductName(productName);
    popup.classList.add('product-popup--open');
    popup.setAttribute('aria-hidden', 'false');
    document.body.classList.add('popup-open');

    const closeButton = popup.querySelector('[data-popup-close]');
    if (closeButton) {
        closeButton.focus();
    }
};

const closePopup = () => {
    if (!popup) return;

    popup.classList.remove('product-popup--open');
    popup.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('popup-open');

    if (lastFocusedElement && typeof lastFocusedElement.focus === 'function') {
        lastFocusedElement.focus();
    }
};

if (popup) {
    popup.querySelectorAll('[data-popup-close]').forEach((element) => {
        element.addEventListener('click', closePopup);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && popup.classList.contains('product-popup--open')) {
            closePopup();
        }
    });

    buyButtons.forEach((button) => {
        button.addEventListener('click', (event) => {
            event.preventDefault();
            lastFocusedElement = button;

            const productName = button.dataset.productName ? button.dataset.productName.trim() : '';
            openPopup(productName);
        });
    });
}
