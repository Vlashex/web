import React, { useState, useEffect, useRef } from "react";

const Gallery: React.FC = () => {
  const images: string[] = [
    "/img1.png",
    "/img2.png",
    "/img3.png",
    "/img4.png",
    "/img5.png",
    "/img6.png",
    "/img7.png",
    "/img8.png",
  ];

  const trackRef = useRef<HTMLDivElement | null>(null);
  const [currentIndex, setCurrentIndex] = useState<number>(0);
  const [perPage, setPerPage] = useState<number>(
    window.innerWidth < 768 ? 1 : 3
  );

  const total = images.length;
  const totalPages = Math.ceil(total / perPage);
  const maxIndex = total - perPage;

  // адаптивность при изменении ширины окна
  useEffect(() => {
    const handleResize = (): void => {
      const newPerPage = window.innerWidth < 768 ? 1 : 3;
      setPerPage(newPerPage);
      setCurrentIndex(0);
    };
    window.addEventListener("resize", handleResize);
    return () => window.removeEventListener("resize", handleResize);
  }, []);

  const handleNext = (): void => {
    if (currentIndex < maxIndex) setCurrentIndex((p) => p + 1);
  };

  const handlePrev = (): void => {
    if (currentIndex > 0) setCurrentIndex((p) => p - 1);
  };

  const handleDotClick = (page: number): void => {
    setCurrentIndex(page * perPage);
  };

  // плавный сдвиг ленты
  useEffect(() => {
    const track = trackRef.current;
    if (!track) return;

    const slideWidth = 240 + 15;
    const offset = currentIndex * slideWidth;
    track.style.transform = `translateX(-${offset}px)`;
  }, [currentIndex, perPage]);

  return (
    <section className="flex flex-col items-center w-full py-10 bg-slate-50">
      <div className={`flex items-center justify-center gap-6`}>
        {/* Кнопка назад */}
        <button
          onClick={handlePrev}
          disabled={currentIndex === 0}
          className="rounded-full p-3 bg-slate-200 hover:bg-slate-300 
                     text-slate-700 font-bold shadow-sm 
                     transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed
                     active:bg-slate-400
                     max-lg:hidden
                     "
          aria-label="Назад"
        >
          ←
        </button>

        {/* Слайдер */}
        <div className="w-[750px] overflow-hidden max-md:w-60">
          <div
            ref={trackRef}
            className="flex transition-transform duration-500 ease-out gap-[15px]"
          >
            {images.map((src, i) => (
              <div key={i} className="flex-none w-60">
                <img
                  src={src}
                  alt={`Изображение ${i + 1}`}
                  className="w-60 h-60 object-cover rounded-xl shadow-md select-none"
                  loading="lazy"
                  width={240}
                  height={240}
                />
              </div>
            ))}
          </div>
        </div>

        {/* Кнопка вперёд */}
        <button
          onClick={handleNext}
          disabled={currentIndex >= maxIndex}
          className="rounded-full p-3 bg-slate-200 hover:bg-slate-300 
                     text-slate-700 font-bold shadow-sm 
                     transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed
                     active:bg-slate-400
                     max-lg:hidden
                     "
          aria-label="Вперёд"
        >
          →
        </button>
      </div>

      {/* Пейджер */}
      <div className="flex gap-3 mt-6">
        {Array.from({ length: totalPages }, (_, i) => (
          <button
            key={i}
            onClick={() => handleDotClick(i)}
            className={`w-3 h-3 rounded-full transition-all duration-200 ${
              Math.floor(currentIndex / perPage) === i
                ? "bg-slate-600 scale-110"
                : "bg-slate-300 hover:bg-slate-400"
            }`}
            aria-label={`Перейти к странице ${i + 1}`}
          />
        ))}
      </div>

      <div className="lg:hidden flex gap-5 mt-5">
        <button
          onClick={handlePrev}
          disabled={currentIndex === 0}
          className="rounded-full p-3 bg-slate-200 hover:bg-slate-300 
                     text-slate-700 font-bold shadow-sm 
                     transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed
                     active:bg-slate-400"
          aria-label="Назад"
        >
          ←
        </button>
        <button
          onClick={handleNext}
          disabled={currentIndex >= maxIndex}
          className="rounded-full p-3 bg-slate-200 hover:bg-slate-300 
                     text-slate-700 font-bold shadow-sm 
                     transition-all duration-200 disabled:opacity-40 disabled:cursor-not-allowed
                     active:bg-slate-400"
          aria-label="Вперёд"
        >
          →
        </button>
      </div>
    </section>
  );
};

export default Gallery;
