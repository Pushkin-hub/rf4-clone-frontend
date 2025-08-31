import React, { useRef } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import Header from "./components/Header";
import Footer from './components/Footer';
import Fishes from "./pages/Fishes";
import Waters from "./pages/Waters";
import Forum from "./pages/Forum";
import Home from './pages/Home';
import Download from "./pages/Download";
import News from "./pages/News";
import Media from "./pages/Media";
import Rating from "./pages/Rating";
import Login from "./pages/Auth/Login";
import Register from "./pages/Auth/Register";
import Rules from "./pages/Rules";
import PrivacyPolicy from "./pages/PrivacyPolicy";
import './styles/custom.scss';
import 'bootstrap/dist/css/bootstrap.min.css';


const App = () => {
  return (
    <Router>
      <div className="d-flex flex-column min-vh-100">
      <Header />
      <Routes>
        <Route exact path="/" Component={Home} />
        <Route path="/fishes" Component={Fishes} />
        <Route path="/waters" Component={Waters} />
        <Route path="/forum" Component={Forum} />
        <Route path="/download" Component={Download} />
        <Route path="/news" Component={News} />
        <Route path="/media" Component={Media} />
        <Route path="/rating" Component={Rating} />
        <Route path="/login" Component={Login} />
        <Route path="/register" Component={Register} />
        <Route path="/rules" Component={Rules} />
        <Route path="/privacyPolicy" Component={PrivacyPolicy} />
        {/* ... */}
        <Route path="/404" Component={ () => (<div>404</div>)}/>
        {/* <Route path="*" element={<Navigate to="/404" replace />} /> */}
      </Routes>
      <Footer />
      </div>
    </Router>
  );
}

export default App;