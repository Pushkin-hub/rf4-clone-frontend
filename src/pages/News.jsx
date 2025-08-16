import React from 'react';
import { Container, Row, Col, Card } from 'react-bootstrap';
import RU_tehnicheskie from '../assets/RU_tehnicheskie.jpg';
import DenRyibaka from '../assets/DenRyibaka.jpg';
// Новости
const newsList = [
  {
    id: 1,
    title: 'Технические работы',
    date: '28-07-2025',
    summary: '29 июля в 00 МСК игровой сервер будет остановлен для проведения технических работ. Время работы ~2 часа.',
    image: RU_tehnicheskie,
  },
  {
    id: 2,
    title: 'Турнир “День рыбака”',
    date: '05-07-2025',
    summary: 'Дорогие друзья! С 7 по 13 июля состоится праздничный тернир "день рыбака".',
    image: DenRyibaka,
  },
  {
    id: 3,
    title: 'Турнир “Весенний улов”',
    date: '2024-05-25',
    summary: 'Стартует новый международный онлайн-турнир.',
    image: '/assets/news2.jpg',
  },
  {
    id: 4,
    title: 'Турнир “Весенний улов”',
    date: '2024-05-25',
    summary: 'Стартует новый международный онлайн-турнир.',
    image: '/assets/news2.jpg',
  },
  {
    id: 5,
    title: 'Турнир “Весенний улов”',
    date: '2024-05-25',
    summary: 'Стартует новый международный онлайн-турнир.',
    image: '/assets/news2.jpg',
  },
  {
    id: 6,
    title: 'Турнир “Весенний улов”',
    date: '2024-05-25',
    summary: 'Стартует новый международный онлайн-турнир.',
    image: '/assets/news2.jpg',
  },
  {
    id: 7,
    title: 'Турнир “Весенний улов”',
    date: '2024-05-25',
    summary: 'Стартует новый международный онлайн-турнир.',
    image: '/assets/news2.jpg',
  },
];

const News = () => {

  return (
    <Container className="py-4">
      <h2 className="mb-4">{('news.title', 'Новости проекта')}</h2>
      <Row>
        {newsList.map(news => (
          <Col lg={6} key={news.id} className="mb-4">
            <Card className="h-100 shadow-sm">
              {news.image && <Card.Img variant="top" src={news.image} alt={news.title} />}
              <Card.Body>
                <Card.Title>{news.title}</Card.Title>
                <Card.Subtitle className="mb-2 text-muted">{news.date}</Card.Subtitle>
                <Card.Text>{news.summary}</Card.Text>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>
    </Container>
  );
};

export default News;
