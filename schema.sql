CREATE TABLE Users (
	id INT AUTO_INCREMENT PRIMARY KEY,
	date_reg TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	email VARCHAR(256) NOT NULL UNIQUE,
	name VARCHAR(256) NOT NULL,
	password VARCHAR(256) NOT NULL
);

CREATE TABLE Projects (
	id INT AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(256) NOT NULL,
	authorId int,
	FOREIGN KEY (authorId)
		REFERENCES Users (id)
		ON DELETE CASCADE
);

CREATE TABLE Tasks (
	id INT AUTO_INCREMENT PRIMARY KEY,
	date_create TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	task_completed int DEFAULT 0,
	name VARCHAR(256) NOT NULL,
	file VARCHAR(256) NULL,
  date_expiration DATE default NULL,
	projectId int,
	FOREIGN KEY (projectId)
		REFERENCES Projects (id)
		ON DELETE CASCADE
);

ALTER TABLE `Tasks` ADD INDEX idx_task (name);
ALTER TABLE `Projects` ADD UNIQUE INDEX idx_project (name, authorId);

CREATE FULLTEXT INDEX task_ft_search ON Tasks(name);
