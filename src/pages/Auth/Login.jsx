import React, { useState } from 'react';
import { Container, Form, Button, Row, Col, Alert } from 'react-bootstrap';
import axios from 'axios';

const Login = () => {
  const [form, setForm] = useState({ email: '', password: '' });
  const [err, setErr] = useState('');
  const [isLoading, setLoading] = useState(false);

  const handleChange = e => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async e => {
    e.preventDefault();
    setLoading(true);
    setErr('');

    try {
      const response = await axios.post('http://localhost:5000/api/login'); // Замените URL при необходимости

      console.log('Успешный вход:', response.data);

     if(response.status === 200 && response.data.token){
        localStorage.setItem('token', response.data.token);

        window.location.href = '/profile';
      } else {
        setErr('Ошибка при получении токена.');
      }

    } catch (error) {
      console.error('Ошибка входа:', error);
      if(error.response){
          setErr(error.response.data?.message || 'Неизвестная ошибка');
      } else {
        setErr('Ошибка соединения с сервером.');
      }

    } finally{
      setLoading(false);
    }
  };

  return (
    <Container className="py-4">
      <Row className="justify-content-center">
        <Col md={6} lg={4}>
          <h2 className="mb-4">{('login.title', 'Вход')}</h2>
          {err && <Alert variant="danger">{err}</Alert>}
          <Form onSubmit={handleSubmit}>
            <Form.Group controlId="loginEmail" className="mb-3">
              <Form.Label>{('login.email', 'Email или Логин')}</Form.Label>
              <Form.Control
                type="email"
                name="email"
                value={form.email}
                onChange={handleChange}
                required
                autoFocus
              />
            </Form.Group>
            <Form.Group controlId="loginPassword" className="mb-3">
              <Form.Label>{('login.password', 'Пароль')}</Form.Label>
              <Form.Control
                type="password"
                name="password"
                value={form.password}
                onChange={handleChange}
                required
              />
            </Form.Group>
            <Button type="submit" variant="primary" disabled={isLoading} className="w-100">
              {('login.enterBtn', 'Войти')}
            </Button>
          </Form>
        </Col>
      </Row>
    </Container>
  );
};

export default Login;
