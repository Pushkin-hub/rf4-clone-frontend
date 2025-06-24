import React from 'react';
import Belaya from '../assets/belaya_01.jpg';

const screenshots = [
  {
    src: Belaya,
    alt: 'Скриншот 1',
    caption: 'Лучшие туманные угодья'
  },
  {
    src: '/media/screenshot2.jpg',
    alt: 'Скриншот 2',
    caption: 'Моменты на закате'
  },
  {
    src: '/media/screenshot3.jpg',
    alt: 'Скриншот 3',
    caption: 'Трофейная рыба'
  }
  // Добавляй новые скриншоты!
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
  // youtube/vimeo id или src для новых видео!
];

function Media() {

  return (
    <div className="container py-4">
      <h1 className="mb-4">{('media.title')}</h1>

      <section className="mb-5">
        <h2 className="h4 mb-3">{('media.screenshots')}</h2>
        <div className="row">
          {screenshots.map((img, idx) => (
            <div className="col-12 col-sm-6 col-md-4 mb-3" key={idx}>
              <div className="card h-100 shadow-sm">
                <img
                  src={img.src}//src={Belaya} работает не так, как надо
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
        <h2 className="h4 mb-3">{('media.trailers')}</h2>
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
