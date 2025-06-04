import React, { useEffect, useState } from "react";
import axios from "axios";
import { useTranslation } from "react-i18next";
import { Row, Col, Card, Container } from "react-bootstrap";

export default function Fishes() {
  const [fishes, setFishes] = useState([]);
  const { t, i18n } = useTranslation();
  useEffect(() => {
    axios.get("/api/fishes.php").then(r => setFishes(r.data));
  }, []);

  return (
    <Container className="my-4">
      <h1>{t("fishes")}</h1>
      <Row>
        {fishes.map(fish => (
          <Col key={fish.id} xs={12} md={6} lg={4} xl={3} className="mb-3">
            <Card>
              <Card.Img variant="top" src={fish.image} alt={fish.name[i18n.language] || fish.name['ru']} />
              <Card.Body>
                <Card.Title>{fish.name[i18n.language] || fish.name['ru']}</Card.Title>
                <Card.Text>{fish.description[i18n.language] || fish.description['ru']}</Card.Text>
                <Card.Text><small>{t("weight")}: {fish.weight}</small></Card.Text>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>
    </Container>
  );
}
