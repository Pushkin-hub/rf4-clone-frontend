import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';
import { Navbar, Nav, NavDropdown } from 'react-bootstrap';

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
                <Nav.Link href="/privacyPolicy">{("Политика конфиденциальности")}</Nav.Link>
                <Nav.Link href="/rules">{("Правила")}</Nav.Link>
              </Nav>
            </Navbar.Collapse>
          </Navbar>
        </Col>
      </Row>

    </Container>
  </footer>
);

export default Footer;
