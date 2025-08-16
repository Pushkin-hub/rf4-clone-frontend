import React from 'react';
import { Container, Table } from 'react-bootstrap';
import { useTranslation } from 'react-i18next';

// Моковые данные рейтинга
const ratingList = [
  { id: 1, username: 'NIKOSTA', country: 'RU', score: 28240 },
  { id: 2, username: 'Senya Den', country: 'RU', score: 31162 },
  { id: 3, username: 'kameshok', country: 'RU', score: 26230},
  { id: 4, username: 'Любитель66', country: 'RU', score: 9311 },
  { id: 5, username: 'gromov.grom777', country: 'RU', score: 24805 },
  { id: 6, username: 'VovkaMel', country: 'RU', score: 22687 },
  { id: 7, username: 'ryabzhen', country: 'JU', score: 23260 },
  { id: 8, username: 'Устах', country: 'RU', score: 22216 },
  { id: 9, username: 'Светлая', country: 'RU', score: 19164 },
  { id: 10, username: 'D.Black', country: 'EN', score: 20018 },
  { id: 11, username: 'den8411ser', country: 'RU', score: 20236 },
  { id: 12, username: 'CH36', country: 'EN', score: 18322 },
  { id: 13, username: 'Bullochnik', country: 'RU', score: 12030 },
  { id: 14, username: 'КЕНТ_', country: 'RU', score: 25567 },
  { id: 15, username: 'васяквасин', country: 'RU', score: 16475 },
  { id: 16, username: 'миксарь', country: 'RU', score: 17910 },
  { id: 17, username: 'Сэн Сэй', country: 'RU', score: 18897 },
  { id: 18, username: 'псих с удой', country: 'RU', score: 20439 },
  { id: 19, username: 'igoreshka', country: 'RU', score: 37743 },
  { id: 20, username: 'Angller', country: 'EN', score: 15394 },
  { id: 21, username: 'mordvin13rus', country: 'RU', score: 19056 },
  { id: 22, username: 'EUGENE 7', country: 'JU', score: 21355 },
  { id: 23, username: 'I max', country: 'EN', score: 17898 },
  { id: 24, username: 'avlich', country: 'EN', score: 25477 },
  { id: 25, username: 'Dellisen', country: 'EN', score: 17712 },
  { id: 26, username: 'Рыбачник 69', country: 'RU', score: 17861 },
  { id: 27, username: 'serega VII', country: 'RU', score: 10956 },
  { id: 28, username: 'Edgaras.', country: 'EN', score: 19159 },
  { id: 29, username: 'TEA59', country: 'EN', score: 19996 },
  { id: 30, username: 'Доватор', country: 'RU', score: 24529 },
  { id: 31, username: 'Deadnote', country: 'EN', score: 22167 },
  { id: 32, username: 'Hitman2711', country: 'RU', score: 24977 },
  { id: 33, username: 'SharkAttack', country: 'RU', score: 18449 },
  { id: 34, username: 's.v.s', country: 'KO', score: 23876 },
  { id: 35, username: 'Синоптик', country: 'RU', score: 12686 },
  { id: 36, username: 'Veselchak', country: 'RU', score: 25586 },
  { id: 37, username: 'ZoV_PredkoV', country: 'RU', score: 17975 },
  { id: 38, username: 'Хапугыч', country: 'RU', score: 16524 },
  { id: 39, username: 'Sheher_', country: 'KO', score: 14453 },
  { id: 40, username: 'Егорыч.', country: 'RU', score: 14534 },
  { id: 41, username: 'RoLLeRNFS', country: 'EN', score: 15150 },
  { id: 42, username: 'andrejp', country: 'RU', score: 21026 },
  { id: 43, username: 'Tarirazimbabue80', country: 'RU', score: 41088 },
  { id: 44, username: 'yura1957', country: 'RU', score: 26193 },
  { id: 45, username: 'Кобринский', country: 'RU', score: 15746 },
  { id: 46, username: 'Владимир Терёхин', country: 'RU', score: 13140 },
  { id: 47, username: 'brat_123', country: 'RU', score: 18076 },
  { id: 48, username: 'Сиш', country: 'RU', score: 12595 },
  { id: 49, username: 'Р У С И Ч', country: 'RU', score: 23356 },
  { id: 50, username: 'YashaZar', country: 'RU', score: 18860 },
];

const Rating = () => {

  return (
    <Container className="py-4">
      <h2 className="mb-4">{('rating.title', 'Рейтинг игроков')}</h2>
      <Table striped bordered hover responsive>
        <thead>
          <tr>
            <th>#</th>
            <th>{('rating.player', 'Игрок')}</th>
            <th>{('rating.country', 'Страна')}</th>
            <th>{('rating.score', 'Очки')}</th>
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
