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

const form = document.getElementById('contactForm');
const nameInput = document.getElementById('name');
const phoneInput = document.getElementById('phone');
const emailInput = document.getElementById('email');
const phonePattern = /^\+375 \(\d{2}\) \d{3}-\d{2}-\d{2}$/;
const phonePrefix = '+375 (';

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
    if (!phoneInput) return;
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
    if (!phoneInput) return;
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
    if (!phoneInput) return;
    if (!phoneInput.value) {
        phoneInput.value = phonePrefix;
    } else if (!phoneInput.value.startsWith('+375')) {
        phoneInput.value = formatPhone(phoneInput.value);
    }
    setPhoneCaretEnd();
};

if (nameInput) {
    nameInput.addEventListener('input', () => {
        nameInput.value = nameInput.value.replace(/[^a-zA-Zа-яА-ЯёЁ\s\-]/g, '');
        nameInput.setCustomValidity('');
    });
}

if (phoneInput) {
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
}

if (emailInput) {
    emailInput.addEventListener('input', () => {
        emailInput.value = emailInput.value.replace(/\s+/g, '');
    });
}

if (form) {
    form.addEventListener('submit', (event) => {
        if (phoneInput && phoneInput.value && !phonePattern.test(phoneInput.value)) {
            phoneInput.setCustomValidity('Введите номер в формате +375 (__) ___-__-__');
            phoneInput.reportValidity();
            phoneInput.focus();
            setPhoneCaretEnd();
            event.preventDefault();
        }
        if (nameInput && nameInput.value.trim().length < 2) {
            nameInput.setCustomValidity('Введите корректное имя');
            nameInput.reportValidity();
            event.preventDefault();
        }
    });
}
