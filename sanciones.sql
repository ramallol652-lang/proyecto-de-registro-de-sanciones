DROP DATABASE IF EXISTS sanciones;
CREATE DATABASE sanciones
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;

USE sanciones;

-- ==========================
-- TABLA USUARIOS
-- ==========================
CREATE TABLE usuarios (
    id_usuario INT AUTO_INCREMENT PRIMARY KEY,
    nombre_completo VARCHAR(150) NOT NULL,
    usuario VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('Administrador','Director','Inspector','Docente') NOT NULL,
    estado ENUM('Activo','Inactivo') DEFAULT 'Activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================
-- TABLA GESTIONES
-- ==========================
CREATE TABLE gestiones (
    id_gestion INT AUTO_INCREMENT PRIMARY KEY,
    gestion YEAR NOT NULL UNIQUE,
    estado ENUM('Activa','Inactiva') DEFAULT 'Inactiva'
) ENGINE=InnoDB;

-- ==========================
-- TABLA CURSOS
-- ==========================
CREATE TABLE cursos (
    id_curso INT AUTO_INCREMENT PRIMARY KEY,
    id_gestion INT NOT NULL,
    nivel ENUM('Inicial','Primaria','Secundaria') NOT NULL,
    curso VARCHAR(20) NOT NULL,
    paralelo VARCHAR(5) NOT NULL,
    turno ENUM('Mañana','Tarde','Noche') DEFAULT 'Mañana',
    estado ENUM('Activo','Inactivo') DEFAULT 'Activo',

    CONSTRAINT fk_curso_gestion
    FOREIGN KEY (id_gestion)
    REFERENCES gestiones(id_gestion),

    UNIQUE KEY uk_curso (
        id_gestion,
        nivel,
        curso,
        paralelo,
        turno
    )
) ENGINE=InnoDB;

-- ==========================
-- TABLA ESTUDIANTES
-- ==========================
CREATE TABLE estudiantes (
    id_estudiante INT AUTO_INCREMENT PRIMARY KEY,
    codigo_estudiante VARCHAR(20) NOT NULL UNIQUE,
    ci VARCHAR(20) UNIQUE NULL,
    nombres VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE NULL,
    sexo ENUM('M','F') NULL,

    id_curso INT NOT NULL,

    nombre_tutor VARCHAR(150) NOT NULL,
    telefono_tutor VARCHAR(20),
    direccion VARCHAR(255),

    estado ENUM('Activo','Retirado') DEFAULT 'Activo',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_estudiante_curso
    FOREIGN KEY (id_curso)
    REFERENCES cursos(id_curso)
) ENGINE=InnoDB;

-- ==========================
-- TABLA TIPOS DE SANCIÓN
-- ==========================
CREATE TABLE tipos_sancion (
    id_tipo INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(20) NOT NULL UNIQUE,
    puntos INT NOT NULL,
    requiere_citacion ENUM('SI','NO') DEFAULT 'NO'
) ENGINE=InnoDB;

-- ==========================
-- TABLA SANCIONES
-- ==========================
CREATE TABLE sanciones (
    id_sancion INT AUTO_INCREMENT PRIMARY KEY,

    numero_caso VARCHAR(20) NOT NULL UNIQUE,

    id_estudiante INT NOT NULL,
    id_tipo INT NOT NULL,
    id_usuario INT NOT NULL,
    id_gestion INT NOT NULL,

    fecha_sancion DATE NOT NULL,

    descripcion TEXT NOT NULL,
    accion_tomada TEXT,

    estado ENUM(
        'Abierta',
        'En seguimiento',
        'Cerrada'
    ) DEFAULT 'Abierta',

    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_sancion_estudiante
    FOREIGN KEY (id_estudiante)
    REFERENCES estudiantes(id_estudiante),

    CONSTRAINT fk_sancion_tipo
    FOREIGN KEY (id_tipo)
    REFERENCES tipos_sancion(id_tipo),

    CONSTRAINT fk_sancion_usuario
    FOREIGN KEY (id_usuario)
    REFERENCES usuarios(id_usuario),

    CONSTRAINT fk_sancion_gestion
    FOREIGN KEY (id_gestion)
    REFERENCES gestiones(id_gestion)
) ENGINE=InnoDB;

-- ==========================
-- TABLA COMPROMISOS
-- 1 sanción = 1 compromiso
-- ==========================
CREATE TABLE compromisos (
    id_compromiso INT AUTO_INCREMENT PRIMARY KEY,

    id_sancion INT NOT NULL UNIQUE,

    fecha_compromiso DATE NOT NULL,

    texto_compromiso TEXT NOT NULL,

    firma_estudiante VARCHAR(150),
    firma_tutor VARCHAR(150),
    firma_responsable VARCHAR(150),

    estado ENUM(
        'Pendiente',
        'Cumplido',
        'Incumplido'
    ) DEFAULT 'Pendiente',

    observaciones TEXT,

    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_compromiso_sancion
    FOREIGN KEY (id_sancion)
    REFERENCES sanciones(id_sancion)
    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================
-- TABLA CITACIONES
-- Una sanción puede tener
-- varias citaciones
-- ==========================
CREATE TABLE citaciones (
    id_citacion INT AUTO_INCREMENT PRIMARY KEY,

    id_sancion INT NOT NULL,

    fecha_citacion DATE NOT NULL,
    hora_citacion TIME NOT NULL,

    nombre_apoderado VARCHAR(150),

    asistio ENUM(
        'Pendiente',
        'SI',
        'NO'
    ) DEFAULT 'Pendiente',

    fecha_asistencia DATE NULL,

    observaciones TEXT,

    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_citacion_sancion
    FOREIGN KEY (id_sancion)
    REFERENCES sanciones(id_sancion)
    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================
-- TABLA SEGUIMIENTOS
-- ==========================
CREATE TABLE seguimientos (
    id_seguimiento INT AUTO_INCREMENT PRIMARY KEY,

    id_sancion INT NOT NULL,

    fecha DATE NOT NULL,

    responsable VARCHAR(150) NOT NULL,

    observacion TEXT NOT NULL,

    resultado ENUM(
        'Mejora',
        'Reincidencia',
        'Cumplimiento',
        'Otro'
    ) DEFAULT 'Otro',

    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_seguimiento_sancion
    FOREIGN KEY (id_sancion)
    REFERENCES sanciones(id_sancion)
    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================
-- ÍNDICES
-- ==========================
CREATE INDEX idx_estudiante_nombre
ON estudiantes(apellidos, nombres);

CREATE INDEX idx_sancion_fecha
ON sanciones(fecha_sancion);

CREATE INDEX idx_sancion_estudiante
ON sanciones(id_estudiante);

CREATE INDEX idx_citacion_fecha
ON citaciones(fecha_citacion);

-- ==========================
-- DATOS INICIALES
-- ==========================

INSERT INTO gestiones (gestion, estado)
VALUES
(2026,'Activa');

INSERT INTO tipos_sancion
(nombre,puntos,requiere_citacion)
VALUES
('Leve',1,'NO'),
('Media',3,'SI'),
('Grave',5,'SI');

INSERT INTO usuarios
(
nombre_completo,
usuario,
password,
rol
)
VALUES
(
'Administrador General',
'admin',
'admin123',
'Administrador'
);

-- ==========================
-- CURSO DE EJEMPLO
-- ==========================
INSERT INTO cursos
(
id_gestion,
nivel,
curso,
paralelo,
turno
)
VALUES
(
1,
'Secundaria',
'1ro',
'A',
'Mañana'
);

-- ==========================
-- ESTUDIANTE DE PRUEBA
-- ==========================
INSERT INTO estudiantes
(
codigo_estudiante,
ci,
nombres,
apellidos,
fecha_nacimiento,
sexo,
id_curso,
nombre_tutor,
telefono_tutor,
direccion
)
VALUES
(
'EST-2026-0001',
NULL,
'Juan Carlos',
'Pérez Rojas',
'2012-05-10',
'M',
1,
'María Rojas',
'70000000',
'Santa Cruz'
);

-- ==========================
-- SANCIÓN DE PRUEBA
-- ==========================
INSERT INTO sanciones
(
numero_caso,
id_estudiante,
id_tipo,
id_usuario,
id_gestion,
fecha_sancion,
descripcion,
accion_tomada
)
VALUES
(
'DS-2026-0001',
1,
2,
1,
1,
CURDATE(),
'Falta de respeto al docente.',
'Citación a padres.'
);

INSERT INTO compromisos
(
id_sancion,
fecha_compromiso,
texto_compromiso,
firma_estudiante,
firma_tutor,
firma_responsable
)
VALUES
(
1,
CURDATE(),
'El estudiante se compromete a mejorar su conducta y respetar las normas institucionales.',
'Juan Carlos Pérez',
'María Rojas',
'Inspector General'
);

INSERT INTO citaciones
(
id_sancion,
fecha_citacion,
hora_citacion,
nombre_apoderado,
observaciones
)
VALUES
(
1,
CURDATE(),
'08:00:00',
'María Rojas',
'Primera citación.'
);

INSERT INTO seguimientos
(
id_sancion,
fecha,
responsable,
observacion,
resultado
)
VALUES
(
1,
CURDATE(),
'Inspector General',
'Se realizará seguimiento durante dos semanas.',
'Otro'
);
