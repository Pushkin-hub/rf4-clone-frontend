import React from 'react';
import { Navbar, Nav, NavDropdown } from 'react-bootstrap';
import logo from '../assets/logo.png';
import { useTranslation } from 'react-i18next';

const LANGS = [
  { code: "ru", label: "RU" },
  { code: "en", label: "EN" },
  // другие языки...
];

export default function Header() {
  const { t, i18n } = useTranslation();
  return (
    <Navbar expand="lg" bg="dark" variant="dark" sticky="top">
      <Navbar.Brand href="/">
        <img src={logo} width="45" alt="logo" className="mr-2" />
        {t("siteName")}
      </Navbar.Brand>
      <Navbar.Toggle aria-controls="rf4-navbar" />
      <Navbar.Collapse id="rf4-navbar">
        <Nav className="ml-auto">
          <Nav.Link href="/download">{t("download")}</Nav.Link>
          <Nav.Link href="/news">{t("news")}</Nav.Link>
          <Nav.Link href="/media">{t("media")}</Nav.Link>
          <Nav.Link href="/rating">{t("rating")}</Nav.Link>
          <Nav.Link href="/forum">{t("forum")}</Nav.Link>
          <Nav.Link href="/login">{t("login")}</Nav.Link>
          <Nav.Link href="/register">{t("register")}</Nav.Link>
          <NavDropdown title = {(typeof i18n.language === 'string' && i18n.language) ? i18n.language.toUpperCase() : 'RU'} id="nav-lang">
            {LANGS.map(({ code, label }) => (
              <NavDropdown.Item key={code}
                onClick={() => i18n.changeLanguage(code)}>
                {label}
              </NavDropdown.Item>
            ))}
          </NavDropdown>
        </Nav>
      </Navbar.Collapse>
    </Navbar>
  );
}
