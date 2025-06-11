import React from 'react';
import { Container, Row, Col } from 'react-bootstrap';
// import LanguageSwitcher from './LanguageSwitcher';

const Footer = () => (
  <footer className="bg-dark text-light py-4 mt-auto">
    <Container fluid>
      <Row className="align-items-center">
        <Col md={4} className="mb-3 mb-md-0">
          <div>
            <b>© {new Date().getFullYear()} Русская рыбалка 4</b>
          </div>
        </Col>
        <Col md={4} className="mb-3 mb-md-0 text-center">
          {/* Место для соцсетей */}
          <a href="#" className="text-light mx-2" aria-label="Discord"><i className="bi bi-discord"></i></a>
          <a href="#" className="text-light mx-2" aria-label="VK"><i className="bi bi-vk"></i></a>
        </Col>
        <Col md={4} className="text-md-end text-center">
          <a href="/privacy" className="text-light mx-2">Политика конфиденциальности</a>
          <a href="/terms" className="text-light mx-2">Правила</a>
          {/* <LanguageSwitcher /> */}
        </Col>
      </Row>
    </Container>
  </footer>
);

export default Footer;
