import React from 'react';
import { Container, Row, Col, Card, Button } from 'react-bootstrap';
import Logo from '../assets/RF4_logo_b.png';

const downloadLinks = [
  {
    id: 1,
    platform: 'Windows',
    url: 'https://rf4game.ru/download/windows-installer.exe',
    icon: '/assets/windows.svg',
    size: '120 МБ'
  },
  /*
  {
    id: 2,
    platform: 'macOS',
    url: 'https://rf4game.ru/download/mac-installer.dmg',
    icon: '/assets/mac.svg',
    size: '135 МБ'
  },
  {
    id: 3,
    platform: 'Linux',
    url: 'https://rf4game.ru/download/linux-installer.deb',
    icon: '/assets/linux.svg',
    size: '125 МБ'
  }
  */
];

const Download = () => {

  return (
    <Container className="py-4">
      <Row className="justify-content-center mb-4">
        <Col md={8}>
          <h2 className="mb-3">{('download.title', 'Скачать Русская Рыбалка 4')}</h2>
          <p className="lead">{('download.lead', 'Выберите Вашу платформу и загрузите клиент игры. Все установщики безопасны и проходят проверку на вирусы.')}</p>
        </Col>
      </Row>
      <Row className="justify-content-center">
        {downloadLinks.map(link => (
          <Col key={link.id} md={5} className="mb-4">
            <Card className="text-center shadow-sm h-100">
              <Card.Body>
                {link.icon && (
                  <img
                    src={Logo}
                    alt={link.platform}
                    style={{ width: '48px', height: '48px', marginBottom: '1rem' }}
                  />
                )}
                <Card.Title>{(`download.platform.${link.platform.toLowerCase()}`, link.platform)}</Card.Title>
                <Card.Text>
                  {('download.size', 'Размер')}: {link.size}
                </Card.Text>
                <Button
                  variant="primary"
                  href={link.url}
                  size="lg"
                  rel="noopener noreferrer"
                  target="_blank"
                  download
                >
                  {('download.downloadBtn', 'Скачать')}
                </Button>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>
      <Row className="justify-content-center mt-3">
        <Col md={4}>
          <Card bg="light">
            <Card.Body>
              <Card.Title>{('Минимальные системные требования')}</Card.Title>
              <Card.Text>
                {(
                  'Процессор: Intel® i3 3Ghz'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Видео: GTX 760 series'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Память: 8Gb'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Свободное место на жестком диске: 40 Gb'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Разрешение экрана: 1280×768'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'ОС: Windows 7/8/10 64-бита'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Стабильное интернет соединение'
                )}
              </Card.Text>
            </Card.Body>
          </Card>
        </Col>
        <Col md={4}>
          <Card bg="light">
            <Card.Body>
              <Card.Title>{('Рекомендуемые системные требования')}</Card.Title>
              <Card.Text>
                {(
                  'Процессор: Intel® i5 3.5Ghz'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Видео: GTX 1060'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Память: 8Gb'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Свободное место на жестком диске: 40 Gb'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Разрешение экрана: 1920×1080'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'ОС: Windows 10 64-бита'
                )}
              </Card.Text>
              <Card.Text>
                {(
                  'Стабильное интернет соединение'
                )}
              </Card.Text>
            </Card.Body>
          </Card>
        </Col>
      </Row>
    </Container>
  );
};

export default Download;
