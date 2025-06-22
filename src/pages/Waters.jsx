import React from 'react';
import { Container, Row, Col, Card, Badge } from 'react-bootstrap';
import Belaya from '../assets/belaya_01.jpg'

// Водоемы
const watersList = [
  {
    id: 1,
    name: 'Озеро Комариное',
    location: 'Россия',
    fish: ['Карась золотой', 'Плотва', 'Окунь', 'Уклейка', 
      'Густера', 'Лещ', 'Линь', 'Ерш', 'Карп чешуйчатый', 
      'Карась серебряный', 'Ротан', 'Щука обыкновенная', 'Ерш-налим'],
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 2,
    name: 'Озеро Лосиное',
    location: 'Россия',
    fish: ['Большеротый окунь', 'Малоротый окунь', 'Булый краппи',
      'Пятнистый окунь', 'Вармоус', 'Белый окунь', 'Речной горбыль',
      'Светлопертый судак', 'Павлиний окунь', 'Солнечник красноухий',
      'Гибридный полосатый лаврак', 'Северная доросома', 'Канальный сомик',
      'Оливковый сомик', 'Полосатый лаврак', 'Щука панцирная', 'Солнечник синежаберный',
      'Черный краппи', 'Пятнистай панцирник'],
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 3,
    name: 'Река Вьюнок',
    location: 'Россия',
    fish: ['Белоглазка', 'Ерш', 'Карась'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 4,
    name: 'Озеро Старый Острог',
    location: 'Россия',
    fish: ['Лещ', 'Буфало', 'Карп', 'Карась'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 5,
    name: 'Река Белая',
    location: 'Россия',
    fish: ['Хариус', 'Форель', 'Ерш'],//Добавиль всю рыбу
    image: '../assets/belaya_01.jpg',
    style: 'width="50"',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 6,
    name: 'Озеро Куори',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'], //Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 7,
    name: 'Медвежье озеро',
    location: 'Россия, Карелия',
    fish: ['Щука', 'Лещ', 'Судак'],//Добавиль всю рыбу
    image: '/assets/water_lake.jpg',
    description: 'Большое пресноводное озеро на севере России, известное уловами трофейной щуки.',
  },
  {
    id: 8,
    name: 'Река Волхов',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 9,
    name: 'Река Северский Донец',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 10,
    name: 'Река Сура',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 11,
    name: 'Озеро Ладожское',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 12,
    name: 'Озеро Янтарное',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 13,
    name: 'Ладожский архипелаг',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 14,
    name: 'Река Ахтуба',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 15,
    name: 'Озеро Медное',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 16,
    name: 'Река Нижняя Тунгуска',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 17,
    name: 'Река Яма',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
  {
    id: 18,
    name: 'Норведское море',
    location: 'Россия',
    fish: ['Сом', 'Осётр', 'Жерех'],//Добавиль всю рыбу
    image: '/assets/water_volga.jpg',
    description: 'Самая большая река Европы, популярное место среди рыболовов.',
  },
];

const Waters = () => {

  return (
    <Container className="py-4">
      <h2 className="mb-4">{('waters.title', 'Водоемы')}</h2>
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
