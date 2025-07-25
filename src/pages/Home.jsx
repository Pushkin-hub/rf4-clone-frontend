import React from 'react';
import { Container, Row, Col, Button } from 'react-bootstrap';
import Waters from './Waters';
import Logo from '../assets/RF4_logo_b.png';

const Home = () => {

  return (
    <main>
      <Container className="py-5">
        <Row className="align-items-center">
          <Col md={6}>
            <h1>{('home.title', 'Русская рыбалка 4 — симулятор для настоящих рыбаков!')}</h1>
            <p>{('home.description', 'Погрузитесь в атмосферу настоящей рыбалки в лучших водоемах России и мира!')}</p>
            <Button href="/download" variant="success" size="lg" className="me-2">{('home.download', 'Скачать игру')}</Button>
            <Button href="/news" variant="outline-secondary" size="lg">{('home.news', 'Новости проекта')}</Button>
          </Col>
          <Col md={6} className="d-none d-md-block">
            <img src= {Logo} alt="Главная картинка" className="img-fluid rounded shadow" />
          </Col>
          <Col md={10}>
            <br/>
            <h2>Об игре</h2>
            <br/>
            <p>«Русская Рыбалка 4» — долгожданное продолжение известной серии рыболовных симуляторов. Новая игра стала значительно динамичней
              и отличается тщательно проработанной физикой. В основе игрового процесса лежит идея полной свободы игрока. Исследуйте водоёмы в
              поисках рыбных мест. Используйте любые снасти, соответствующие вашему уровню и умениям. К вашим услугам впечатляющий арсенал рыболовного
              оборудования и предметов экипировки. Помимо этого, игровой мир «Русской Рыбалки 4» постоянно расширяется, а значит, в нём всегда есть место
              неизведанному. Игру покупать не нужно, достаточно просто скачать!</p>
          </Col>
          <Waters />
        </Row>
      </Container>
    </main>
  );
};

export default Home;
