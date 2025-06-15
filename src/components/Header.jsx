import React from 'react';
import { Navbar, Nav, NavDropdown } from 'react-bootstrap';
import logo from '../assets/logo.svg';
import { useTranslation } from 'react-i18next';


export default function Header() {
  const { t, i18n } = useTranslation();
  return (
    <Navbar expand="lg" bg="dark" variant="dark" sticky="top">
      <Navbar.Brand href="/">
        <img src={logo} width="50" alt="logo" className="mr-2" />
        {t("Русская  рыбалка 4")}
      </Navbar.Brand>
      <Navbar.Toggle aria-controls="rf4-navbar" />
      <Navbar.Collapse id="rf4-navbar">
        <Nav className="ml-auto">
          <Nav.Link href="/download">{t("Скачать игру")}</Nav.Link>
          <Nav.Link href="/news">{t("Новости")}</Nav.Link>
          <Nav.Link href="/media">{t("Медиа")}</Nav.Link>
          <Nav.Link href="/rating">{t("Рейтинг")}</Nav.Link>
          <Nav.Link href="/forum">{t("Форум")}</Nav.Link>
          <Nav.Link href="/login">{t("Войти")}</Nav.Link>
          <Nav.Link href="/register">{t("Регистрация")}</Nav.Link>
        </Nav>
      </Navbar.Collapse>
    </Navbar>
  );
}
