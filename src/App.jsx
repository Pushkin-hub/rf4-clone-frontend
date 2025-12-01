import React from 'react';
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
        <Route exact path="/" element={<Home/>} />
        <Route path="/fishes" element={<Fishes/>} />
        <Route path="/waters" element={<Waters/>} />
        <Route path="/forum" element={<Forum/>} />
        <Route path="/download" element={<Download/>} />
        <Route path="/news" element={<News/>} />
        <Route path="/media" element={<Media/>} />
        <Route path="/rating" element={<Rating/>} />
        <Route path="/login" element={<Login/>} />
        <Route path="/register" element={<Register/>} />
        <Route path="/rules" element={<Rules/>} />
        <Route path="/privacyPolicy" element={<PrivacyPolicy/>} />
        {/* ... */}
        <Route path="/404" element={ () => (<div>404</div>)}/>
        {/* <Route path="*" element={<Navigate to="/404" replace />} /> */}
      </Routes>
      <Footer />
      </div>
    </Router>
  );
}

export default App;