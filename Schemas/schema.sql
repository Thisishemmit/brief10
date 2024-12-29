CREATE TABLE IF NOT EXISTS Users(
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('member', 'admin') DEFAULT 'member',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS Categories(
    id_category INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS Books(
    id_book INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    author VARCHAR(255) NOT NULL,
    category_id INT,
    cover_image VARCHAR(255) NOT NULL,
    summary TEXT NOT NULL,
    id_user INT NOT NULL,
    status ENUM('available', 'borrowed') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES Users(id_user) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES Categories(id_category) ON DELETE SET NULL
);


CREATE TABLE IF NOT EXISTS BorrowedBooks(
    id_borrowed_book INT PRIMARY KEY AUTO_INCREMENT,
    id_book INT NOT NULL,
    id_user INT NOT NULL,
    borrowed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    due_at TIMESTAMP NOT NULL,
    returned_at TIMESTAMP,
    email_sent BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (id_book) REFERENCES Books(id_book) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES Users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS BorrowRequests(
    id_borrow_request INT PRIMARY KEY AUTO_INCREMENT,
    id_book INT NOT NULL,
    id_user INT NOT NULL,
    due_at TIMESTAMP NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_book) REFERENCES Books(id_book) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES Users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS ReturnRequests(
    id_return_request INT PRIMARY KEY AUTO_INCREMENT,
    id_borrowed_book INT NOT NULL,
    id_user INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    requested_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_borrowed_book) REFERENCES BorrowedBooks(id_borrowed_book) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES Users(id_user) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS Reservations(
    id_reservation INT PRIMARY KEY AUTO_INCREMENT,
    id_book INT NOT NULL,
    id_user INT NOT NULL,
    reserved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_book) REFERENCES Books(id_book) ON DELETE CASCADE,
    FOREIGN KEY (id_user) REFERENCES Users(id_user) ON DELETE CASCADE
);

