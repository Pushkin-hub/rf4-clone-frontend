import React from 'react';
import { Container, Table } from 'react-bootstrap';
import { useTranslation } from 'react-i18next';

// Моковые данные рейтинга
const ratingList = [
  { id: 1, username: 'FishMaster404', country: 'RU', score: 9945 },
  { id: 2, username: 'TrophyHunter', country: 'DE', score: 8700 },
  { id: 3, username: 'ProFisher', country: 'EN', score: 8250 },
];

const Rating = () => {
  const { t } = useTranslation();

  return (
    <Container className="py-4">
      <h2 className="mb-4">{t('rating.title', 'Рейтинг игроков')}</h2>
      <Table striped bordered hover responsive>
        <thead>
          <tr>
            <th>#</th>
            <th>{t('rating.player', 'Игрок')}</th>
            <th>{t('rating.country', 'Страна')}</th>
            <th>{t('rating.score', 'Очки')}</th>
          </tr>
        </thead>
        <tbody>
          {ratingList.map((row, idx) => (
            <tr key={row.id}>
              <td>{idx + 1}</td>
              <td>{row.username}</td>
              <td>{row.country}</td>
              <td>{row.score}</td>
            </tr>
          ))}
        </tbody>
      </Table>
    </Container>
  );
};

export default Rating;
