import React from 'react';
import { Dropdown } from 'react-bootstrap';
import { useTranslation } from 'react-i18next';

const LANGUAGES = [
  { code: 'ru', label: 'РУ' },
  { code: 'de', label: 'DE' },
  { code: 'en', label: 'EN' },
  { code: 'pl', label: 'PL' },
  { code: 'fr', label: 'FR' },
  { code: 'cn', label: 'CN' },
  { code: 'ko', label: 'KO' },
  { code: 'ja', label: 'JA' },
];

const LanguageSwitcher = () => {
  const { i18n } = useTranslation();

  const handleSelect = (code) => {
    i18n.changeLanguage(code);
    localStorage.setItem('rf4_lang', code);
  };

  return (
    <Dropdown align="end" className="d-inline mx-2">
      <Dropdown.Toggle variant="outline-light" size="sm" id="dropdown-lang">
        {LANGUAGES.find(l => l.code === i18n.language)?.label || i18n.language.toUpperCase()}
      </Dropdown.Toggle>
      <Dropdown.Menu>
        {LANGUAGES.map(lang => (
          <Dropdown.Item
            key={lang.code}
            onClick={() => handleSelect(lang.code)}
            active={i18n.language === lang.code}
          >
            {lang.label}
          </Dropdown.Item>
        ))}
      </Dropdown.Menu>
    </Dropdown>
  );
};

export default LanguageSwitcher;
