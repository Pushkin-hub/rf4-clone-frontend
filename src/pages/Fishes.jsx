import React, { useEffect, useState } from "react";
import axios from "axios";
import { Row, Col, Card, Container } from "react-bootstrap";

export default function Fishes() {
  const [fishes, setFishes] = useState([]);

  useEffect(() => {
    axios.get("/api/fishes.php").then(r => setFishes(r.data));
  }, []);

  return (
    <Container className="my-4">
      <h1>{("fishes")}</h1>
      <Row>
        {fishes.map(fish => (
          <Col key={fish.id} xs={12} md={6} lg={4} xl={3} className="mb-3">
            <Card>
              <Card.Img variant="top" src={fish.image} alt={fish.name || fish.name['ru']} />
              <Card.Body>
                <Card.Title>{fish.name || fish.name['ru']}</Card.Title>
                <Card.Text>{fish.description || fish.description['ru']}</Card.Text>
                <Card.Text><small>{("weight")}: {fish.weight}</small></Card.Text>
              </Card.Body>
            </Card>
          </Col>
        ))}
      </Row>
    </Container>
  );
}
