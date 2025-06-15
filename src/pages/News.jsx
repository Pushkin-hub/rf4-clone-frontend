import React from 'react';
import { Container, Row, Col, Card } from 'react-bootstrap';

// Новости
const newsList = [
  {
    id: 1,
    title: 'Вышло новое обновление',
    date: '2024-06-10',
    summary: 'Добавлены новые водоемы и виды рыб.',
    image: '/assets/news1.jpg',
  },
  {
    id: 2,
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
