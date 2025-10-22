import React, { useState } from 'react';
import { Container, Form, Button, Row, Col, Alert } from 'react-bootstrap';
import axios from 'axios';

const Register = () => {
  const [form, setForm] = useState({ email: '', password: '', confirm: '' });
  const [err, setErr] = useState('');
  const [success, setSuccess] = useState(false);

  const handleChange = e => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async e => {
    e.preventDefault();
    setErr('');
    if (form.password !== form.confirm) {
      setErr('Пароли не совпадают');
      return;
    }

    if (!form.email.includes('@')) {
      setErr('Неверный email');
      return;
    }

    try {
      const response = await axios.post('http://localhost:8000/api/register', form); // Замените URL при необходимости

      console.log('Успешная регистрация:', response.data);

      if (response.status === 201) {
        setSuccess(true);
        // Опционально: редирект на страницу входа или другую страницу
        // window.location.href = '/login';
      } else {
        setErr('Ошибка при регистрации.');
      }

    } catch (error) {
      console.error('Ошибка регистрации:', error);
      if (error.response) {
        setErr(error.response.data?.message || 'Неизвестная ошибка');
      } else {
        setErr('Ошибка соединения с сервером.');
      }
    }
  };

  return (
    <Container className="py-4">
      <Row className="justify-content-center">
        <Col md={6} lg={4}>
          <h2 className="mb-4">{('register.title', 'Регистрация')}</h2>
          {err && <Alert variant="danger">{err}</Alert>}
          {success ? (
            <Alert variant="success">
              {('register.success', 'Регистрация прошла успешно!')}
            </Alert>
          ) : (
            <Form onSubmit={handleSubmit}>
              <Form.Group controlId="registerEmail" className="mb-3">
                <Form.Label>{('register.email', 'Email')}</Form.Label>
                <Form.Control
                  type="email"
                  name="email"
                  value={form.email}
                  onChange={handleChange}
                  required
                />
              </Form.Group>
              <Form.Group controlId="registerPassword" className="mb-3">
                <Form.Label>{('register.password', 'Пароль')}</Form.Label>
                <Form.Control
                  type="password"
                  name="password"
                  value={form.password}
                  onChange={handleChange}
                  required
                  minLength={4}
                />
              </Form.Group>
              <Form.Group controlId="registerConfirm" className="mb-4">
                <Form.Label>{('register.confirm', 'Повторите пароль')}</Form.Label>
                <Form.Control
                  type="password"
                  name="confirm"
                  value={form.confirm}
                  onChange={handleChange}
                  required
                  minLength={4}
                />
              </Form.Group>
              <Button type="submit" variant="success" className="w-100">
                {('register.regBtn', 'Зарегистрироваться')}
              </Button>
            </Form>
          )}
        </Col>
      </Row>
    </Container>
  );
};

export default Register;
