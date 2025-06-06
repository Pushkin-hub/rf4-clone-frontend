import React from 'react';
import { Container, Row, Col, Button } from 'react-bootstrap';
import { useTranslation } from 'react-i18next';

const Home = () => {
  const { t } = useTranslation();

  return (
    <main>
      <Container className="py-5">
        <Row className="align-items-center">
          <Col md={6}>
            <h1>{t('home.title', 'Русская рыбалка 4 — симулятор для настоящих рыбаков!')}</h1>
            <p>{t('home.description', 'Погрузитесь в атмосферу настоящей рыбалки в лучших водоемах России и мира!')}</p>
            <Button href="/download" variant="success" size="lg" className="me-2">{t('home.download', 'Скачать игру')}</Button>
            <Button href="/news" variant="outline-secondary" size="lg">{t('home.news', 'Новости проекта')}</Button>
          </Col>
          <Col md={6} className="d-none d-md-block">
            <img src="/assets/hero_rf4.jpg" alt="Главная картинка" className="img-fluid rounded shadow" />
          </Col>
        </Row>
      </Container>
    </main>
  );
};

export default Home;
