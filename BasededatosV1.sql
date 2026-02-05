DROP DATABASE IF EXISTS ecoaventura;
CREATE DATABASE ecoaventura CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE ecoaventura;

-- ========================
-- Tabla: usuario (login + roles + aprobación)
-- ========================
CREATE TABLE usuario (
    id_usuario INT PRIMARY KEY AUTO_INCREMENT,
    correo VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    rol ENUM('admin', 'turista', 'hotelero', 'restaurantero') NOT NULL,
    activo BOOLEAN NOT NULL DEFAULT TRUE,

    -- Bandeja de solicitudes (aplica a hotelero/restaurantero)
    estado ENUM('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'aprobado',
    fecha_solicitud DATETIME NULL,
    fecha_respuesta DATETIME NULL,
    motivo_rechazo VARCHAR(200) NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: turista (perfil)
-- ========================
CREATE TABLE turista (
    id_turista INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    apaterno VARCHAR(45) NOT NULL,
    amaterno VARCHAR(45),
    id_usuario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: hotelero (perfil)
-- ========================
CREATE TABLE hotelero (
    id_hotelero INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    apaterno VARCHAR(45) NOT NULL,
    amaterno VARCHAR(45),
    telefono VARCHAR(15) NOT NULL,
    id_usuario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: restaurantero (perfil)
-- ========================
CREATE TABLE restaurantero (
    id_restaurantero INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    apaterno VARCHAR(45) NOT NULL,
    amaterno VARCHAR(45),
    telefono VARCHAR(15) NOT NULL,
    id_usuario INT NOT NULL UNIQUE,
    FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: sitio (destinos)
-- Guías NO se registran: info fija en info_guia
-- ========================
CREATE TABLE sitio (
    id_sitio INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    descripcion TEXT NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    horario VARCHAR(45) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    categoria ENUM('Balneario', 'Ecoturistico', 'Turistico') NOT NULL,
    comunidad VARCHAR(45) NOT NULL,
    ciudad VARCHAR(45) NOT NULL,
    costo DECIMAL(8,2) NOT NULL,
    foto VARCHAR(100) NOT NULL,
    info_guia TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Servicios (OPCIÓN 2): 1 catálogo global
-- ========================
CREATE TABLE servicio_catalogo (
    id_servicio INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(60) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Puente: sitio -> servicios
CREATE TABLE sitio_servicio (
    id_sitio INT NOT NULL,
    id_servicio INT NOT NULL,
    PRIMARY KEY (id_sitio, id_servicio),
    FOREIGN KEY (id_sitio) REFERENCES sitio(id_sitio) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES servicio_catalogo(id_servicio) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: hotel
-- Nota: NO se borra en cascada al borrar hotelero
-- ========================
CREATE TABLE hotel (
    id_hotel INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    descripcion TEXT NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    foto VARCHAR(100) NOT NULL,
    id_hotelero INT NOT NULL,
    CONSTRAINT fk_hotel_hotelero
        FOREIGN KEY (id_hotelero) REFERENCES hotelero(id_hotelero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Puente: hotel -> servicios
CREATE TABLE hotel_servicio (
    id_hotel INT NOT NULL,
    id_servicio INT NOT NULL,
    PRIMARY KEY (id_hotel, id_servicio),
    FOREIGN KEY (id_hotel) REFERENCES hotel(id_hotel) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES servicio_catalogo(id_servicio) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: habitacion
-- ========================
CREATE TABLE habitacion (
    id_habitacion INT PRIMARY KEY AUTO_INCREMENT,
    tipo VARCHAR(45) NOT NULL,
    capacidad INT NOT NULL,
    precio DECIMAL(8,2) NOT NULL,
    estado ENUM('disponible', 'ocupada') NOT NULL DEFAULT 'disponible',
    id_hotel INT NOT NULL,
    FOREIGN KEY (id_hotel) REFERENCES hotel(id_hotel) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: pago_externo (referencia tipo "papelito")
-- Tú decides en tu backend cómo generar folio/monto/cuenta
-- ========================
CREATE TABLE pago_externo (
    id_pago INT PRIMARY KEY AUTO_INCREMENT,
    folio VARCHAR(30) NOT NULL,
    monto DECIMAL(8,2) NOT NULL,
    cuenta_pago VARCHAR(45) NOT NULL,
    estado ENUM('pendiente', 'pagado') NOT NULL DEFAULT 'pendiente',
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: reserva_hotel
-- ========================
CREATE TABLE reserva_hotel (
    id_reserva INT PRIMARY KEY AUTO_INCREMENT,
    fecha_entrada DATE NOT NULL,
    fecha_salida DATE NOT NULL,
    estado ENUM('pendiente','confirmada','cancelada') NOT NULL DEFAULT 'pendiente',
    id_turista INT NOT NULL,
    id_habitacion INT NOT NULL,
    id_pago INT NULL,
    FOREIGN KEY (id_turista) REFERENCES turista(id_turista),
    FOREIGN KEY (id_habitacion) REFERENCES habitacion(id_habitacion),
    FOREIGN KEY (id_pago) REFERENCES pago_externo(id_pago)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: restaurante
-- (fotos_menu es opcional si usarás tabla imagen; lo dejamos por si quieres portada)
-- ========================
CREATE TABLE restaurante (
    id_restaurante INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(45) NOT NULL,
    descripcion TEXT NOT NULL,
    direccion VARCHAR(100) NOT NULL,
    horario VARCHAR(45) NOT NULL,
    telefono VARCHAR(15) NOT NULL,
    foto VARCHAR(100) NOT NULL,
    id_restaurantero INT NOT NULL,
    CONSTRAINT fk_restaurante_restaurantero
        FOREIGN KEY (id_restaurantero) REFERENCES restaurantero(id_restaurantero)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Puente: restaurante -> servicios (buffet, vegano, etc.)
CREATE TABLE restaurante_servicio (
    id_restaurante INT NOT NULL,
    id_servicio INT NOT NULL,
    PRIMARY KEY (id_restaurante, id_servicio),
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante) ON DELETE CASCADE,
    FOREIGN KEY (id_servicio) REFERENCES servicio_catalogo(id_servicio) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: reserva_restaurante
-- (si después quieres pago aquí, ya está id_pago listo)
-- ========================
CREATE TABLE reserva_restaurante (
    id_reserva_restaurante INT PRIMARY KEY AUTO_INCREMENT,
    fecha DATE NOT NULL,
    hora TIME NOT NULL,
    personas INT NOT NULL,
    estado ENUM('pendiente','confirmada','cancelada') NOT NULL DEFAULT 'pendiente',
    id_turista INT NOT NULL,
    id_restaurante INT NOT NULL,
    id_pago INT NULL,
    FOREIGN KEY (id_turista) REFERENCES turista(id_turista),
    FOREIGN KEY (id_restaurante) REFERENCES restaurante(id_restaurante) ON DELETE CASCADE,
    FOREIGN KEY (id_pago) REFERENCES pago_externo(id_pago)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: favorito (turista guarda destinos)
-- ========================
CREATE TABLE favorito (
    id_favorito INT PRIMARY KEY AUTO_INCREMENT,
    id_turista INT NOT NULL,
    id_sitio INT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE (id_turista, id_sitio),
    FOREIGN KEY (id_turista) REFERENCES turista(id_turista) ON DELETE CASCADE,
    FOREIGN KEY (id_sitio) REFERENCES sitio(id_sitio) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: comentario (sitio/hotel/restaurante/pagina)
-- id_referencia:
--  - apunta al id del elemento según el tipo
--  - NULL cuando tipo='pagina'
-- ========================
CREATE TABLE comentario (
    id_comentario INT PRIMARY KEY AUTO_INCREMENT,
    comentario TEXT NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tipo ENUM('sitio', 'hotel', 'restaurante', 'pagina') NOT NULL,
    id_referencia INT NULL,
    id_turista INT NOT NULL,
    FOREIGN KEY (id_turista) REFERENCES turista(id_turista) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ========================
-- Tabla: imagen (genérica para sitio/hotel/restaurante)
-- La página sabe el tipo por la ruta:
--   sitio.php?id=7       -> tipo='sitio', id_referencia=7
--   hotel.php?id=3       -> tipo='hotel', id_referencia=3
--   restaurante.php?id=2 -> tipo='restaurante', id_referencia=2
-- ========================
CREATE TABLE imagen (
    id_imagen INT PRIMARY KEY AUTO_INCREMENT,
    tipo ENUM('sitio','hotel','restaurante') NOT NULL,
    id_referencia INT NOT NULL,
    ruta VARCHAR(150) NOT NULL,
    fecha DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;