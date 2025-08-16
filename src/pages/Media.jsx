import React from 'react';
import Belaya from '../assets/belaya_01.jpg';
import Ahtuba2 from '../assets/media_img/ahtuba2.jpg';
import Ahtuba3 from '../assets/media_img/ahtuba3.jpg';
import Fish1 from '../assets/media_img/fish1.jpg';
import Fish2 from '../assets/media_img/fish2.jpg';
import Fish3 from '../assets/media_img/fish3.jpg';
import Fish4 from '../assets/media_img/fish4.jpg';
import Gameplay1 from '../assets/media_img/gameplay1.jpg';
import Gameplay2 from '../assets/media_img/gameplay2.jpg';
import Gameplay3 from '../assets/media_img/gameplay3.jpg';
import Gameplay4 from '../assets/media_img/gameplay4.jpg';
import Gameplay5 from '../assets/media_img/gameplay5.jpg';
import Gameplay6 from '../assets/media_img/gameplay6.jpg';
import Gameplay7 from '../assets/media_img/gameplay7.jpg';
import Home1 from '../assets/media_img/home1.jpg';
import Komarinoe2 from '../assets/media_img/komarinoe2.jpg';
import Krab from '../assets/media_img/krab.jpg';
import Spinning1 from '../assets/media_img/spinning1.jpg';
import Spinning2 from '../assets/media_img/spinning2.jpg';
import Spinning3 from '../assets/media_img/spinning3.jpg';
import Spinning4 from '../assets/media_img/spinning4.jpg';
import Spinning5 from '../assets/media_img/spinning5.jpg';
import Spinning6 from '../assets/media_img/spinning6.jpg';
import Tunguska2 from '../assets/media_img/tunguska2.jpg';
import Tunguska3 from '../assets/media_img/tunguska3.jpg';
import Yantarnoe2 from '../assets/media_img/yantarnoe2.jpg';
import Yantarnoe3 from '../assets/media_img/yantarnoe3.jpg';


const screenshots = [
  {
    src: Belaya,
    alt: 'Скриншот 1',
  },
  {
    src: Ahtuba2,
    alt: 'Скриншот 2',
  },
  {
    src: Ahtuba3,
    alt: 'Скриншот 3',
  },
   {
    src: Fish1,
    alt: 'Скриншот 3',
  },
   {
    src: Fish2,
    alt: 'Скриншот 3',
  },
   {
    src: Fish3,
    alt: 'Скриншот 3',
  },
   {
    src: Fish4,
    alt: 'Скриншот 3',
  },
   {
    src: Gameplay1,
    alt: 'Скриншот 3',
  },
   {
    src: Gameplay2,
    alt: 'Скриншот 3',
  },
   {
    src: Gameplay3,
    alt: 'Скриншот 3',
  },
   {
    src: Gameplay4,
    alt: 'Скриншот 3',
  },
  {
    src: Gameplay5,
    alt: 'Скриншот 3',
  },
  {
    src: Gameplay6,
    alt: 'Скриншот 3',
  },
  {
    src: Gameplay7,
    alt: 'Скриншот 3',
  },
  {
    src: Home1,
    alt: 'Скриншот 3',
  },
  {
    src: Komarinoe2,
    alt: 'Скриншот 3',
  },
  {
    src: Krab,
    alt: 'Скриншот 3',
  },
  {
    src: Spinning1,
    alt: 'Скриншот 3',
  },
  {
    src: Spinning2,
    alt: 'Скриншот 3',
  },
  {
    src: Spinning3,
    alt: 'Скриншот 3',
  },
  {
    src: Spinning4,
    alt: 'Скриншот 3',
  },
  {
    src: Spinning5,
    alt: 'Скриншот 3',
  },
  {
    src: Spinning6,
    alt: 'Скриншот 3',
  },
  {
    src: Tunguska2,
    alt: 'Скриншот 3',
  },
  {
    src: Tunguska3,
    alt: 'Скриншот 3',
  },
  {
    src: Yantarnoe2,
    alt: 'Скриншот 3',
  },
  {
    src: Yantarnoe3,
    alt: 'Скриншот 3',
  }
];

const trailers = [
  {
    src: 'https://www.youtube.com/embed/GAmgVkI9fA0',
    title: 'Трейлер №1'
  },
  {
    src: 'https://www.youtube.com/embed/cQUuFh8R92s',
    title: 'Трейлер №2'
  }
];

function Media() {

  return (
    <div className="container py-4">
      <h1 className="mb-4">{('Медиа')}</h1>

      <section className="mb-5">
        <h2 className="h4 mb-3">{('Картинки и скриншоты')}</h2>
        <div className="row">
          {screenshots.map((img, idx) => (
            <div className="col-12 col-sm-6 col-md-4 mb-3" key={idx}>
              <div className="h-100">
                <img
                  src={img.src}
                  className="card-img-top img-fluid rounded"
                  alt={img.alt}
                  loading="lazy"
                  style={{width: "200px", height: "100px"}}
                />
                <div className="card-body py-2">
                  <p className="card-text small text-muted">{img.caption}</p>
                </div>
              </div>
            </div>
          ))}
        </div>
      </section>

      <section>
        <h2 className="h4 mb-3">{('Видео')}</h2>
        <div className="row">
          {trailers.map((video, idx) => (
            <div className="col-12 col-md-6 mb-4" key={idx}>
              <div className="ratio ratio-16x9 rounded shadow-sm">
                <iframe
                  src={video.src}
                  title={video.title}
                  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                  allowFullScreen
                  loading="lazy"
                />
              </div>
              <div className="mt-2 small text-muted">{video.title}</div>
            </div>
          ))}
        </div>
      </section>
    </div>
  );
}

export default Media;
