<section class="contact container" id="contacts">
    <h4 class="contact__title">Если у вас есть вопросы или нужна помощь — мы всегда на связи!</h4>
    <div class="contact__info">
        <div class="contact__info-item">
            <svg class="contact__info-item__icon" width="30" height="30" viewBox="0 0 30 30" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path
                    d="M22.0312 16.1106C20.9766 15.7199 20.0586 15.9543 19.2773 16.8138L17.3438 19.158C14.6094 17.5562 12.4414 15.3878 10.8398 12.6529L13.1836 10.7189C14.043 9.93749 14.2773 9.01934 13.8867 7.96445L11.0742 1.40066C10.8398 0.892752 10.4688 0.521586 9.96094 0.287165C9.49219 0.0136745 8.98438 -0.0644657 8.4375 0.0527447L1.875 1.45927C0.703125 1.77183 0.078125 2.5337 0 3.74487C0.0390625 8.31608 1.09375 12.477 3.16406 16.2278C5.19531 19.9785 7.96875 23.0651 11.4844 25.4874C15 27.8707 18.9844 29.3358 23.4375 29.8828C24.0234 29.9219 24.6094 29.9609 25.1953 30C25.5469 30 25.8984 30 26.25 30C27.4609 29.9219 28.2227 29.3163 28.5352 28.1832L29.9414 21.6195C30.0586 21.0334 30 20.506 29.7656 20.0371C29.4922 19.5292 29.1016 19.158 28.5938 18.9236L22.0312 16.1106ZM25.8984 27.187C21.6016 27.0697 17.7344 25.9758 14.2969 23.9051C10.8594 21.8734 8.125 19.1385 6.09375 15.7003C4.02344 12.2622 2.92969 8.41375 2.8125 4.15511L8.61328 2.86579L11.1328 8.78492L9.02344 10.4845C8.51562 10.9533 8.20312 11.5198 8.08594 12.184C7.96875 12.8091 8.06641 13.4343 8.37891 14.0594C10.2539 17.2241 12.7734 19.7441 15.9375 21.6195C16.5625 21.9711 17.207 22.0688 17.8711 21.9125C18.4961 21.7953 19.043 21.4827 19.5117 20.9748L21.2695 18.865L27.1289 21.385L25.8984 27.187Z"
                    fill="#22543D" />
            </svg>
            <a href="tel:+375293191844">+375(29) 319 18 44</a>
        </div>
        <div class="contact__info-item">
            <svg class="contact__info-item__icon" width="32" height="22" viewBox="0 0 32 22" fill="none"
                xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path
                    d="M4 2.75C3.375 2.78819 3.04167 3.09375 3 3.66667V4.92708L13.8125 13.0625C14.4792 13.5208 15.2083 13.75 16 13.75C16.8333 13.75 17.5833 13.5208 18.25 13.0625L29 4.92708V3.66667C28.9583 3.09375 28.625 2.78819 28 2.75H4ZM3 8.47917V18.3333C3.04167 18.9062 3.375 19.2118 4 19.25H28C28.625 19.2118 28.9583 18.9062 29 18.3333V8.47917L20.125 15.1823C18.875 16.0608 17.5 16.5 16 16.5C14.5 16.5 13.125 16.0608 11.875 15.1823L3 8.47917ZM0 3.66667C0.0416667 2.63542 0.4375 1.77604 1.1875 1.08854C1.9375 0.401042 2.875 0.0381944 4 0H28C29.125 0.0381944 30.0625 0.401042 30.8125 1.08854C31.5625 1.77604 31.9583 2.63542 32 3.66667V18.3333C31.9583 19.3646 31.5625 20.224 30.8125 20.9115C30.0625 21.599 29.125 21.9618 28 22H4C2.875 21.9618 1.9375 21.599 1.1875 20.9115C0.4375 20.224 0.0416667 19.3646 0 18.3333V3.66667Z"
                    fill="#22543D" />
            </svg>
            <a href="mailto:alkrona@inbox.ru">alkrona@inbox.ru</a>
        </div>
    </div>
    <div class="form-map" id="contact-form">
        <div class="form-map__head form-map__head--form">Мы свяжемся с вами!</div>
        <div class="form-map__head form-map__head--map">
            <div class="form-map__title">Наше расположение</div>
            <div class="form-map__address">микрорайон "Свислочь Новая", г.п. Свислочь,<br>Минская область 220823</div>
        </div>
        <div class="form-block">
            <form class="contact__form js-contact-form" id="contactForm">
                <div class="contact__form__block">
                    <div>
                        <label for="name" class="form__label">ФИО</label>
                        <label for="phone" class="form__label form__label--margin">Телефон</label>
                        <label for="email" class="form__label">Почта</label>
                    </div>
                    <div>
                        <input type="text" id="name" name="name" class="form__input" placeholder="Ваше имя" autocomplete="name" pattern="[A-Za-zА-Яа-яЁё\s\-]+" title="Только буквы, пробелы и дефис" required>
                        <input type="tel" id="phone" name="phone" class="form__input form__input--margin" placeholder="+375 (__) ___-__-__" inputmode="numeric" autocomplete="tel" pattern="\+375 \(\d{2}\) \d{3}-\d{2}-\d{2}" title="Формат: +375 (__) ___-__-__" maxlength="19" required>
                        <input type="email" id="email" name="email" class="form__input" placeholder="email@example.com" autocomplete="email">
                        <input type="hidden" name="product_name" class="js-product-name" value="">
                    </div>
                </div>
                <div class="form__checkbox">
                    <input type="checkbox" id="consent" name="consent" required>
                    <label for="consent">Согласие на обработку персональных данных</label>
                </div>
                <button type="submit" class="form__button">Отправить</button>
            </form>
            <div id="formStatus" class="form__status js-form-status" style="margin-top: 20px;"></div>
        </div>
        <div class="contact__map" id="delivery">
            <iframe src="https://yandex.ru/map-widget/v1/?um=constructor%3A8d1dd97cea5bcbd13bef0f22e17e92874f72eb091e16b5cc76e652ec276bca34&amp;source=constructor" allowfullscreen></iframe>
        </div>
    </div>
</section>

<?php if (function_exists('is_singular') && is_singular('product')) : ?>
<div class="product-popup" id="productBuyPopup" aria-hidden="true">
    <div class="product-popup__backdrop" data-popup-close></div>
    <div class="product-popup__dialog" role="dialog" aria-modal="true" aria-labelledby="productPopupTitle">
        <button type="button" class="product-popup__close" aria-label="Закрыть" data-popup-close>&times;</button>
        <div class="product-popup__content">
            <h4 class="product-popup__title" id="productPopupTitle">Оставить заявку</h4>
            <p class="product-popup__subtitle">Мы свяжемся с вами для оформления заказа.</p>
            <p class="product-popup__product">Товар: <strong class="js-popup-product-name"><?php echo esc_html((string) get_the_title()); ?></strong></p>
            <div class="product-popup__delivery">
                <p class="product-popup__delivery-title"><strong>Самовывоз из питомника — бесплатно</strong></p>
                <p class="product-popup__delivery-text">Адрес: микрорайон "Свислочь Новая", г.п. Свислочь, Минская область 220823.</p>
                <p class="product-popup__delivery-text">График работы: 9:00-17:00, воскресенье — выходной.</p>
                <p class="product-popup__delivery-text">Для розничных клиентов минимальный заказ — от 500 руб.</p>
                <p class="product-popup__delivery-text">Доставка по г. Минску и в радиусе 10-30 км от Минска при заказе от 1000 руб., согласовывается с менеджером на индивидуальных условиях.</p>
                <p class="product-popup__delivery-text"><strong>Доставка через транспортную компанию:</strong> только по г. Минску.</p>
            </div>

            <form class="contact__form contact__form--popup js-contact-form" id="productPopupForm">
                <div class="contact__form__block">
                    <div>
                        <label for="popup-name" class="form__label">ФИО</label>
                        <label for="popup-phone" class="form__label form__label--margin">Телефон</label>
                        <label for="popup-email" class="form__label">Почта</label>
                    </div>
                    <div>
                        <input type="text" id="popup-name" name="name" class="form__input" placeholder="Ваше имя" autocomplete="name" pattern="[A-Za-zА-Яа-яЁё\s\-]+" title="Только буквы, пробелы и дефис" required>
                        <input type="tel" id="popup-phone" name="phone" class="form__input form__input--margin" placeholder="+375 (__) ___-__-__" inputmode="numeric" autocomplete="tel" pattern="\+375 \(\d{2}\) \d{3}-\d{2}-\d{2}" title="Формат: +375 (__) ___-__-__" maxlength="19" required>
                        <input type="email" id="popup-email" name="email" class="form__input" placeholder="email@example.com" autocomplete="email">
                        <input type="hidden" name="product_name" class="js-product-name" value="">
                    </div>
                </div>
                <div class="form__checkbox">
                    <input type="checkbox" id="popup-consent" name="consent" required>
                    <label for="popup-consent">Согласие на обработку персональных данных</label>
                </div>
                <button type="submit" class="form__button">Отправить</button>
            </form>
            <div id="popupFormStatus" class="form__status js-form-status" style="margin-top: 20px;"></div>
        </div>
    </div>
</div>
<?php endif; ?>
