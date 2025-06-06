import React, { useRef } from 'react';
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Header from "./components/Header";
import Footer from './components/Footer';
import Fishes from "./pages/Fishes";
import Waters from "./pages/Waters";
import Forum from "./pages/Forum";
import Home from './pages/Home';
import './styles/custom.scss';


const App = () => {
  return (
    <Router>
      <div className="d-flex flex-column min-vh-100">
      <Header />
      <Routes>
        <Route exact path="/" component={Home} />
        <Route path="/fishes" component={Fishes} />
        <Route path="/waters" component={Waters} />
        <Route path="/forum" component={Forum} />
        {/* ... */}
        <Route path="*"><div>404</div></Route>
      </Routes>
      <Footer />
      </div>
    </Router>
  );
}

export default App;