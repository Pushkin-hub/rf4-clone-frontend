import React, { useState } from 'react';
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
          <Link to="/download">{("Скачать игру")}</Link>
          <Link to="/news">{("Новости")}</Link>
          <Link to="/media">{("Медиа")}</Link>
          <Link to="/rating">{("Рейтинг")}</Link>
          <Link to="/forum">{("Форум")}</Link>
          <Link to="/login">{("Войти")}</Link>
          <Link to="/register">{("Регистрация")}</Link>
        </Nav>
      </Navbar.Collapse>
    </Navbar>
  );
}
