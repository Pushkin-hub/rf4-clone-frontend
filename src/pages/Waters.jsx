// src/pages/Waters.jsx
import React from 'react';
import { Container, Row, Col, Card, Badge } from 'react-bootstrap';
import { useTranslation } from 'react-i18next';

// Примерные водоемы
const watersList = [
  {
    id: 1,
    name: 'Медвежье озеро',
    location: 'Россия, Карелия',
    fish: ['Щука', 'Лещ', 'Судак'],
    image: '/assets/water_lake.jpg',
    description: 'Большое пресноводное озеро на севере России, известное уловами трофейной щуки.',
  },
  {
    id: 2,
    name: 'Река Волга',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
];

const Waters = () => {
  const { t } = useTranslation();

  return (
    <Container className="py-4">
      <h2 className="mb-4">{t('waters.title', 'Водоемы')}</h2>
      <Row>
        {watersList.map(water => (
          <Col lg={6} key={water.id} className="mb-4">
            <Card className="h-100 shadow-sm">
              <Card.Img variant="top" src={water.image} alt={water.name} />
              <Card.Body>
                <Card.Title>{water.name}</Card.Title>
                <Card.Subtitle className="mb-2 text-muted">{water.location}</Card.Subtitle>
                <Card.Text>{water.description}</Card.Text>
                <div>
                  {water.fish.map(f => (
                    <Badge bg="success" className="me-2" key={f}>{f}</Badge>
                  ))}
                </div>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>
    </Container>
  );
};

export default Waters;
