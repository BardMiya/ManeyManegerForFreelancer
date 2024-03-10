import { createI18n } from 'vue-i18n';
import ja from '../lang/ja.json';
import en from '../lang/en.json';

export const i18n = createI18n({
    locale: 'ja',   // ★言語を指定
    legacy: false,
    fallbackLocale: 'en',
    messages: {
        ja : ja,
        en : en
    }
});