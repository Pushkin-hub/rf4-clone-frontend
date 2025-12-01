import React from 'react';
import { Navbar, Nav, NavDropdown } from 'react-bootstrap';
import { Link } from 'react-router-dom';
import Logo from '../assets/RF4_logo_b.png';


export default function Header() {

  return (
    <Navbar expand="lg" bg="dark" variant="dark" sticky="top">
      <Navbar.Brand to="/">
        <img src={Logo} width="50" alt="logo" className="mr-2" />
        {("Русская  рыбалка 4")}
      </Navbar.Brand>
      <Navbar.Toggle aria-controls="rf4-navbar" />
      <Navbar.Collapse id="rf4-navbar">
        <Nav className="ml-auto">
          <Link to="/" className="nav-link">{("Главная страница")}</Link>
          <Link to="/download" className="nav-link">{("Скачать игру")}</Link>
          <Link to="/news" className="nav-link">{("Новости")}</Link>
          <Link to="/media" className="nav-link">{("Медиа")}</Link>
          <Link to="/rating" className="nav-link">{("Рейтинг")}</Link>
          <Link to="/forum" className="nav-link">{("Форум")}</Link>
          <Link to="/login" className="nav-link">{("Войти")}</Link>
          <Link to="/register" className="nav-link">{("Регистрация")}</Link>
        </Nav>
      </Navbar.Collapse>
    </Navbar>
  );
}
