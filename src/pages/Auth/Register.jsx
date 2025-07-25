import React, { useState } from 'react';
import { Container, Form, Button, Row, Col, Alert } from 'react-bootstrap';

const Register = () => {
  const [form, setForm] = useState({ email: '', password: '', confirm: '' });
  const [err, setErr] = useState('');
  const [success, setSuccess] = useState(false);

  const handleChange = e => setForm({ ...form, [e.target.name]: e.target.value });

  const handleSubmit = async e => {
    e.preventDefault();
    setErr('');
    if (form.password !== form.confirm) {
      setErr(('register.pwdMismatch', 'Пароли не совпадают'));
      return;
    }
    // Здесь будет вызов бекенда
    if (!form.email.includes('@')) {
      setErr(('register.badEmail', 'Неверный email'));
      return;
    }
    setSuccess(true);
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
