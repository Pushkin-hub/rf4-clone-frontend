CREATE TABLE `groups` (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    groupname VARCHAR(100) NOT NULL UNIQUE,
    `description` VARCHAR(100)
);

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    group_id INT,
    is_admin BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (group_id) REFERENCES `groups`(id)
);

CREATE TABLE categories (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    category_name VARCHAR(100) NOT NULL
);

CREATE TABLE questions(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    question_text VARCHAR(255) NOT NULL UNIQUE,
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE question_options (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    option_text VARCHAR(255) NOT NULL,
    is_correct BOOLEAN NOT NULL DEFAULT FALSE,
    question_id INT,
    FOREIGN KEY (question_id) REFERENCES questions(id)
);

CREATE TABLE user_answers (
    user_id INT NOT NULL,
    question_id INT NOT NULL,
    option_id INT NOT NULL,
    PRIMARY KEY (user_id, question_id),
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (question_id) REFERENCES questions(id),
    FOREIGN KEY (option_id) REFERENCES question_options(id)
);

CREATE VIEW test_results AS
SELECT
    u.id AS user_id,
    u.username,
    c.id AS category_id,
    c.category_name,
    COUNT(q.id) AS questions_answered,
    SUM(qo.is_correct) AS scores,
    ROUND (
        IF(
            COUNT(q.id) > 0,
            SUM(qo.is_correct) * 100.0 / COUNT(q.id),
            0
        ),
        2
    ) AS success_rate
FROM users u
CROSS JOIN categories c
INNER JOIN questions q ON c.id = q.category_id
INNER JOIN user_answers ua ON u.id = ua.user_id AND q.id = ua.question_id
INNER JOIN question_options qo ON ua.option_id = qo.id
GROUP BY u.id, u.username, c.id, c.category_name;

DELIMITER //

CREATE PROCEDURE get_question_by_number(IN category_id_param INT, IN question_num INT)
READS SQL DATA
DETERMINISTIC
BEGIN
    WITH numbered_questions AS (
        SELECT
            id,
            question_text,
            category_id,
            ROW_NUMBER() OVER (ORDER BY id) AS row_num
        FROM questions
        WHERE category_id = category_id_param
    ) SELECT
        q.id AS question_id,
        q.question_text,
        qo.id AS option_text,
        qo.option_text
    FROM numbered_questions q
    INNER JOIN question_options qo ON q.id = qo.question_id
    WHERE q.row_num = question_num
    ORDER BY qo.id;
END//
DELIMITER ;