import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import { Navbar, Nav, NavDropdown } from 'react-bootstrap';
import { Link } from 'react-router-dom';

const Footer = () => (
  <footer className="bg-dark text-light py-4 mt-auto">
    <Container fluid>
      <Row className="align-items-center">
        <Col md={9} className="mb-3 mb-md-0">
          <div>
            <b>© {new Date().getFullYear()} Русская рыбалка 4</b>
          </div>
        </Col>
        <Col md={3} className="text-md-end text-center">
          <Navbar expand="lg" bg="dark" variant="dark" sticky="top">
            <Navbar.Toggle aria-controls="rf4-navbar" />
            <Navbar.Collapse id="rf4-navbar">
              <Nav className="ml-auto">
                <Link to="/privacyPolicy" className="nav-link">{("Политика конфиденциальности")}</Link>
                <Link to="/rules" className="nav-link">{("Правила")}</Link>
              </Nav>
            </Navbar.Collapse>
          </Navbar>
        </Col>
      </Row>

    </Container>
  </footer>
);

export default Footer;
