import React, { useRef } from 'react';
import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

import ru from './ru.json';
import en from './en.json';
// Подключать остальные по аналогии

i18n
  .use(initReactI18next)
  .init({
    resources: {
      ru: { translation: ru },
      en: { translation: en },
      // ...
    },
    lng: localStorage.getItem('rf4_lang') || 'ru',
    fallbackLng: 'ru',
    interpolation: { escapeValue: false },
  });

export default i18n;
