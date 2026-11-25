CREATE TABLE usuarios (
  id INT PRIMARY KEY,
  nombre VARCHAR(100),
  correo VARCHAR(100),
  clave VARCHAR(255),
  foto_url VARCHAR(200),
  tipo VARCHAR(10)
);

CREATE TABLE tutores (
  id INT PRIMARY KEY,
  usuario_id INT,
  bio VARCHAR(300),
  tarifa_hora INT,
  calificacion_promedio DECIMAL(2,1),
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE estudiantes (
  id INT PRIMARY KEY,
  usuario_id INT,
  xp INT,
  FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE materias (
  id INT PRIMARY KEY,
  nombre VARCHAR(50)
);

CREATE TABLE tutor_materia (
  tutor_id INT,
  materia_id INT,
  PRIMARY KEY (tutor_id, materia_id),
  FOREIGN KEY (tutor_id) REFERENCES tutores(id),
  FOREIGN KEY (materia_id) REFERENCES materias(id)
);

CREATE TABLE sesiones (
  id INT PRIMARY KEY,
  estudiante_id INT,
  tutor_id INT,
  materia_id INT,
  fecha DATE,
  hora VARCHAR(5),
  duracion INT,
  modalidad VARCHAR(10),
  estado VARCHAR(12),
  precio_total INT,
  FOREIGN KEY (estudiante_id) REFERENCES estudiantes(id),
  FOREIGN KEY (tutor_id) REFERENCES tutores(id),
  FOREIGN KEY (materia_id) REFERENCES materias(id)
);

CREATE TABLE resenas (
  id INT PRIMARY KEY,
  sesion_id INT,
  calificacion INT,
  comentario VARCHAR(300),
  FOREIGN KEY (sesion_id) REFERENCES sesiones(id)
);

INSERT INTO usuarios VALUES (1, 'Elena Ramos', 'elena@correo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uploads/elena.jpg', 'tutor');
INSERT INTO usuarios VALUES (2, 'Sofia King', 'sofia@correo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uploads/sofia.jpg', 'estudiante');
INSERT INTO tutores VALUES (1, 1, 'Mas de 8 anos ensenando matematicas', 24000, 4.9);
INSERT INTO estudiantes VALUES (1, 2, 120);
INSERT INTO materias VALUES (1, 'Calculo');
INSERT INTO materias VALUES (2, 'Fisica');
INSERT INTO tutor_materia VALUES (1, 1);
INSERT INTO sesiones VALUES (1, 1, 1, 1, '2026-10-26', '15:30', 60, 'virtual', 'aceptada', 26000);
INSERT INTO resenas VALUES (1, 1, 5, 'Excelente clase');

INSERT INTO usuarios VALUES (3, 'Carlos Mendez', 'carlos@correo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uploads/carlos.jpg', 'tutor');
INSERT INTO usuarios VALUES (4, 'Mateo Ortiz', 'mateo@correo.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'uploads/mateo.jpg', 'tutor');
INSERT INTO tutores VALUES (2, 3, 'PhD en fisica teorica', 30000, 5.0);
INSERT INTO tutores VALUES (3, 4, 'Ex-olimpico de quimica', 22000, 4.9);
INSERT INTO materias VALUES (3, 'Quimica');
INSERT INTO tutor_materia VALUES (2, 2);
INSERT INTO tutor_materia VALUES (3, 3);
INSERT INTO sesiones VALUES (2, 1, 1, 1, '2026-10-27', '16:00', 60, 'virtual', 'pendiente', 26000);
