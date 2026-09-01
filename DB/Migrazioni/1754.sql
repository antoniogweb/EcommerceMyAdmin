create table promozioni_log (
	id_p_log INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
	data_creazione timestamp default CURRENT_TIMESTAMP,
	id_p INT UNSIGNED NOT NULL default 0,
	ip varchar(50) not null default '',
	user_agent varchar(255) not null default '',
	post text null,
	
	INDEX idx_id_p (id_p),
    INDEX idx_ip (ip),
    INDEX idx_data_creazione (data_creazione),
    INDEX idx_ip_data (ip, data_creazione)
);