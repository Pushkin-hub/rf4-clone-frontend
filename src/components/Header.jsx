import React, { useState } from 'react';
import { Navbar, Nav, NavDropdown } from 'react-bootstrap';
import logo from '../assets/logo.svg';


export default function Header() {

  return (
    <Navbar expand="lg" bg="dark" variant="dark" sticky="top">
      <Navbar.Brand href="/">
        <img src={logo} width="50" alt="logo" className="mr-2" />
        {("Русская  рыбалка 4")}
      </Navbar.Brand>
      <Navbar.Toggle aria-controls="rf4-navbar" />
      <Navbar.Collapse id="rf4-navbar">
        <Nav className="ml-auto">
          <Nav.Link href="/download">{("Скачать игру")}</Nav.Link>
          <Nav.Link href="/news">{("Новости")}</Nav.Link>
          <Nav.Link href="/media">{("Медиа")}</Nav.Link>
          <Nav.Link href="/rating">{("Рейтинг")}</Nav.Link>
          <Nav.Link href="/forum">{("Форум")}</Nav.Link>
          <Nav.Link href="/login">{("Войти")}</Nav.Link>
          <Nav.Link href="/register">{("Регистрация")}</Nav.Link>
        </Nav>
      </Navbar.Collapse>
    </Navbar>
  );
}
