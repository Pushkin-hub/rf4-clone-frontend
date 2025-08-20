import React from 'react';
import { Container, Row, Col, Card } from 'react-bootstrap';
import RU_tehnicheskie from '../assets/news_img/RU_tehnicheskie.jpg';
import DenRyibaka from '../assets/news_img/DenRyibaka.jpg';
import PatchNout from '../assets/news_img/PatchNout.jpg';
import Perezagruzka from '../assets/news_img/Perezagruzka.jpg';
import Obnovlenie from '../assets/news_img/Obnovlenie.jpg';
import LosinoeNews from '../assets/news_img/LosinoeNews.jpg';
import LosinoeNews2 from '../assets/news_img/LosinoeNews2.png';
import Skidki from '../assets/news_img/Skidki.jpg';

// Новости
const newsList = [
  {
    id: 1,
    title: 'Технические работы',
    date: '28.07.2025',
    summary: '29 июля в 00 МСК игровой сервер будет остановлен для проведения технических работ. Время работы ~2 часа.',
    image: RU_tehnicheskie,
  },
  {
    id: 2,
    title: 'Турнир “День рыбака”',
    date: '05.07.2025',
    summary: 'Дорогие друзья! С 7 по 13 июля состоится праздничный тернир "день рыбака".',
    image: DenRyibaka,
  },
  {
    id: 3,
    title: 'Патчноут 18.06.2025',
    date: '18.06.2025',
    summary: 'Лодка, расположенная на оз. Лосиное, оборудована стаканами для удилищ. Исправлено. Некоторые игроки не могли войти в игру из-за ошибки при проверке никнейма. Исправлено. У некоторых игроков после предыдущего обновления мог уменьшиться трофейный рейтинг. Перерасчёт рейтинга произойдет автоматически при поимке очередного трофея. Исправлено. Опыт за поимку белого краппи начислялся неверно.',
    image: PatchNout,
  },
  {
    id: 4,
    title: 'Перезагрузка сервера и обновление клиента',
    date: '17.06.2025',
    summary: '18 июня 2025 в 00:00 МСК будут выполнены перезагрузка сервера и обновление клиента игры. Время работ ~2ч',
    image: Perezagruzka,
  },
  {
    id: 5,
    title: 'Патчноут 16.06.2025',
    date: '16.06.2025',
    summary: 'Добавлен новый водоем - оз. Лосиное. Данный водоем доступен игрокам всех уровней. На компьютерах, не удовлетворяющих минимальным системным требованиям, переход на оз. Лосиное невозможно',
    image: PatchNout,
  },
  {
    id: 6,
    title: 'Перезагрузка сервера и обновление клиента',
    date: '15.06.2025',
    summary: '16.06.2025 в 9:00 будут выполнены перезагрузка сервера и обновление клиента. Время работ ~6 часов',
    image: Obnovlenie,
  },
  {
    id: 7,
    title: 'Добро пожаловать на Лосиное озеро',
    date: '13.06.2025',
    image: LosinoeNews,
  },
  {
    id: 8,
    title: 'Перезагрузка сервера и обновление клиента',
    date: '28.05.2025',
    summary: '29.05.2025 в 1:00 МСК будет выполнена перезагрузка сервера и обновление клиента. Время работ ~ 2 часа.',
    image: Perezagruzka,
  },
  {
    id: 9,
    title: 'Навстречу новым приключениям',
    date: '25.05.2025',
    image: LosinoeNews2,
  },
  {
    id: 10,
    title: 'Весенние скидки',
    date: '24.05.2025',
    summary: 'С 12:00 МСК 24.05.2025 до 12:00 МСК 02.06.2025 скидки на: золото - 25%, премиум - 25%',
    image: Skidki,
  }
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
