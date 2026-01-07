-- platformy streamingowe
CREATE TABLE IF NOT EXISTS platforms
(
    id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- kategorie
CREATE TABLE IF NOT EXISTS categories
(
    id INT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- filmy / seriale
CREATE TABLE IF NOT EXISTS titles
(
    id INT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    kind VARCHAR(20) NOT NULL,
    year SMALLINT

    CONSTRAINT check_titles_kind
    CHECK (kind IN ('movie', 'series'))
);

-- filmy - kategorie
CREATE TABLE IF NOT EXISTS title_categories
(
    title_id BIGINT NOT NULL,
    category_id BIGINT NOT NULL,

    PRIMARY KEY (title_id, category_id),

    CONSTRAINT fk_title_categories_title
    FOREIGN KEY (title_id)
    REFERENCES titles (id)
    ON DELETE CASCADE,

    CONSTRAINT fk_title_categories_category
    FOREIGN KEY (category_id)
    REFERENCES categories (id)
    ON DELETE CASCADE
);

-- filmy - platformy streamingowe
CREATE TABLE IF NOT EXISTS title_platforms
(
    title_id BIGINT NOT NULL,
    platform_id BIGINT NOT NULL,

    PRIMARY KEY (title_id, platform_id),

    CONSTRAINT fk_title_platforms_title
    FOREIGN KEY (title_id)
    REFERENCES titles (id)
    ON DELETE CASCADE,

    CONSTRAINT fk_title_platforms_platform
    FOREIGN KEY (platform_id)
    REFERENCES platforms (id)
    ON DELETE CASCADE
);