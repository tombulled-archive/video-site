CREATE TABLE IF NOT EXISTS table_categories (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    identifier TEXT,
    description TEXT,
    category_id INTEGER
);

CREATE TABLE IF NOT EXISTS table_files (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    extension TEXT,
    url TEXT,
    size INTEGER,
    duration INTEGER,
    local_name TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS table_map_categories (
    id INTEGER NOT NULL,
    post_id INTEGER NOT NULL,
    category_id INTEGER NOT NULL,
    FOREIGN KEY(post_id) REFERENCES table_posts(id),
    FOREIGN KEY(category_id) REFERENCES table_categories(category_id)
);

CREATE TABLE IF NOT EXISTS table_producers (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    url TEXT,
    company_id INTEGER,
    description TEXT,
    poster_id INTEGER,
    advert_domain TEXT,
    identifier TEXT,
    title TEXT,
    FOREIGN KEY(company_id) REFERENCES table_producers(id),
    FOREIGN KEY(poster_id) REFERENCES table_files(id)
);

CREATE TABLE IF NOT EXISTS table_posts (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    post_id INTEGER,
    producer_id INTEGER,
    categories_id INTEGER,
    poster_id INTEGER,
    video_id INTEGER,
    image_id INTEGER,
    description TEXT,
    title TEXT,
    year INTEGER,
    month INTEGER,
    day INTEGER,
    FOREIGN KEY(producer_id) REFERENCES table_producers(id),
    FOREIGN KEY(image_id) REFERENCES table_files(id),
    FOREIGN KEY(poster_id) REFERENCES table_files(id),
    FOREIGN KEY(video_id) REFERENCES table_files(id)
);

INSERT INTO table_categories (id, name, identifier, description, category_id) VALUES (
    NULL, "Technology", "technology", "The latest in technology", 1
);

INSERT INTO table_files (id, name, extension, url, size, duration, local_name) VALUES (
    NULL, "hqdefault", "jpg", "https://i.ytimg.com/vi/L4AKeW0Y-F0/hqdefault.jpg", 35075, 0, "poster"
);
INSERT INTO table_files (id, name, extension, url, size, duration, local_name) VALUES (
    NULL, "video", "mp4", "https://domain.com/video.mp4", 99951818, 1130, "video"
);
INSERT INTO table_files (id, name, extension, url, size, duration, local_name) VALUES (
    NULL, "image", "jpg", "https://domain.com/image.jpg", 123, 0, "image"
);

INSERT INTO table_map_categories (id, post_id, category_id) VALUES (
    1, 1, 1
);

INSERT INTO table_producers (id, name, url, company_id, description, poster_id, advert_domain, identifier, title) VALUES (
    NULL, "Linus Tech Tips", "https://www.youtube.com/c/LinusTechTips", NULL, "Linus Tech Tips is a passionate team of 'professionally curious' experts in consumer technology and video production which aims to inform and educate people of all ages through our entertaining videos", 1, "www.youtube.com", "linus-tech-tips", "Linus Tech Tips"
);

INSERT INTO table_posts (id, post_id, producer_id, categories_id, poster_id, video_id, image_id, description, title, year, month, day) VALUES (
    NULL, 1, 1, 1, 1, 1, 1, "SSDs are fast, but in the datacenter, fast is never fast enough. So why did Pure Storage switch to slower QLC flash, and how is it FASTER than TLC?", "This is an SSD?!", 2022, 7, 28
);