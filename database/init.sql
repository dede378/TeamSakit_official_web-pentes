CREATE TABLE IF NOT EXISTS articles (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(120) NOT NULL UNIQUE,
  title VARCHAR(200) NOT NULL,
  summary VARCHAR(500) NOT NULL,
  body TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(80) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  display_name VARCHAR(120) NOT NULL,
  role ENUM('member','admin') NOT NULL DEFAULT 'member',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO articles (slug,title,summary,body) VALUES
('mulai-dari-rumah','Mulai dari Rumah','Ide kecil untuk membuat ruang tinggal lebih nyaman.','Perubahan kecil dapat membuat rumah terasa lebih hangat. Mulailah dari hal yang paling sering digunakan.'),
('cerita-komunitas','Cerita Komunitas','Berbagi cerita dan pengalaman bersama.','Komunitas tumbuh ketika setiap orang mendapat ruang untuk berbagi pengalaman dan belajar satu sama lain.'),
('panduan-digital','Panduan Digital','Kebiasaan sederhana untuk aktivitas digital.','Gunakan kata sandi yang unik, perbarui perangkat, dan pikirkan baik-baik sebelum membagikan informasi pribadi.')
ON DUPLICATE KEY UPDATE title=VALUES(title);
