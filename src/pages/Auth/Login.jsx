// src/pages/Login.jsx
import React, { useState } from 'react';
import { Container, Form, Button, Row, Col, Alert } from 'react-bootstrap';
import { useTranslation } from 'react-i18next';

const Login = () => {
  const { t } = useTranslation();
  const [form, setForm] = useState({ email: '', password: '' });
  const [err, setErr] = useState('');
  const [isLoading, setLoading] = useState(false);

  const handleChange = e => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async e => {
    e.preventDefault();
    setLoading(true);
    setErr('');
    // Здесь будет вызов бекенда, сейчас просто имитация
    if (form.email === 'test@test.com' && form.password === '1234') {
      // Логика успеха: редирект или изменение состояния
      alert('Успешный вход! (псевдо)');
    } else {
      setErr(t('login.invalid', 'Неверные данные для входа.'));
    }
    setLoading(false);
  };

  return (
    <Container className="py-4">
      <Row className="justify-content-center">
        <Col md={6} lg={4}>
          <h2 className="mb-4">{t('login.title', 'Вход')}</h2>
          {err && <Alert variant="danger">{err}</Alert>}
          <Form onSubmit={handleSubmit}>
            <Form.Group controlId="loginEmail" className="mb-3">
              <Form.Label>{t('login.email', 'Email или Логин')}</Form.Label>
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
              <Form.Label>{t('login.password', 'Пароль')}</Form.Label>
              <Form.Control
                type="password"
                name="password"
                value={form.password}
                onChange={handleChange}
                required
              />
            </Form.Group>
            <Button type="submit" variant="primary" disabled={isLoading} className="w-100">
              {t('login.enterBtn', 'Войти')}
            </Button>
          </Form>
        </Col>
      </Row>
    </Container>
  );
};

export default Login;
